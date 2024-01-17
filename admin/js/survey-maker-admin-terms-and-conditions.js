(function($) {
    'use strict';

    function AysSurveyMakerCondTerms(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays_survey_';
        this.dbOptions = undefined;
        this.htmlPrefix   = 'ays_';
        this.addSection   = this.htmlPrefix+'terms_and_condition_add';
        this.init();

        return this;
    }

    AysSurveyMakerCondTerms.prototype.init = function() {
        var _this = this;
         _this.setEvents();
     };

    AysSurveyMakerCondTerms.prototype.setEvents = function(e){
        var _this = this;

        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"add_new_textarea", function(){
            _this.addTermsConds();
        }); 

        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"remove_textarea", function(){
            _this.deleteTermsConds($(this));
        }); 
    }

    AysSurveyMakerCondTerms.prototype.addTermsConds = function (element){
        var deleteImageUrl = SurveyMakerAdmin.icons.deleteTermsAndConds;
        var content = '';

        var addedTermsandConds = this.$el.find("."+this.htmlClassPrefix+"terms_conditions_edit_block");
        var addedTermsandCondsId = this.$el.find("."+this.htmlClassPrefix+"terms_conditions_edit_block:last-child");
        var dataId = addedTermsandConds.length >= 1 ? addedTermsandCondsId.data("conditionId") + 1 : 1;

        var termsCondsMessageAttrName = this.newTermsCondsMessageAttrName( this.addSection ,  dataId , "messages" );

            content += '<div class = "ays_survey_terms_conditions_edit_block" data-condition-id="' + dataId + '">';
                content += '<div class="ays_survey_terms_and_conditions_checkbox">';
                    content += '<div class="ays-survey-icons">'
                        content += '<img src="'+SurveyMakerAdmin.icons.checkboxUnchecked+'">'
                    content += '</div>'
                content += '</div>';
                content += '<div class="ays_survey_terms_and_conditions_textarea_div">';
                content += '<textarea name="' + termsCondsMessageAttrName + '"></textarea>';
                content += '</div>';
                content += '<div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl">';
                    content += '<img class="ays_survey_remove_textarea" src="' + deleteImageUrl + '">';
                content += '</div>';
            content += '</div>';
            
            $(document).find('.ays_survey_terms_and_conditions_content').append(content);
        }

        AysSurveyMakerCondTerms.prototype.deleteTermsConds = function (element){
            var $this = element;
            var $thisMainParent = $this.parent().parent();
            $thisMainParent.remove();
        }

        AysSurveyMakerCondTerms.prototype.newTermsCondsMessageAttrName = function (termCondName, termCondId, messageText){
            var _this = this;
            return termCondName + '['+ termCondId +']['+ messageText +']';
        
        }

        $.fn.AysSurveyTermsAndConditions = function(options) {
            return this.each(function() {
                if (!$.data(this, 'AysSurveyTermsAndConditions')) {
                    $.data(this, 'AysSurveyTermsAndConditions', new AysSurveyMakerCondTerms(this, options));
                } else {
                    try {
                        $(this).data('AysSurveyTermsAndConditions').init();
                    } catch (err) {
                        console.error('AysSurveyTermsAndConditions has not initiated properly');
                    }
                }
            });
        };
    
    $(document).find('.ays_survey_terms_and_conditions_all_inputs_block').AysSurveyTermsAndConditions();
})(jQuery);
