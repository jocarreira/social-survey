(function($) {
    'use strict';

    var defaults = {
        mode: 'lg-slide',
        cssEasing: 'ease',
        easing: 'linear',
        speed: 600,
        height: '100%',
        width: '100%',
        addClass: '',
        galleryId: 1
    };

    function AysSurveyPlugin(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays-survey-';
        this.dbOptionsPrefix = 'survey_';
        this.ajaxAction = 'ays_survey_ajax';
        this.dbOptions = undefined;
        this.QuizQuestionsOptions = undefined;
        this.uniqueId;
        this.surveyId;
        this.startDate;
        this.endDate;
        this.sectionsContainer;
        this.sections;
        this.current_fs;
        this.next_fs;
        this.previous_fs; //fieldsets
        this.left;
        this.opacity;
        this.scale; //fieldset properties which we will animate
        this.animating;
        this.percentAnimate; //flag to prevent quick multi-click glitches
        this.explanationTimeout;
        this.maxCountTimeout;
        this.confirmBeforeUnload = false;

        // new added email pattern
        this.emailValivatePattern = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.\w{2,}$/;

        // phone pattern
        this.phoneValivatePattern = /^[0-9)(+ -]+$/;

        // old email pattern(works slower)
        // this.emailValivatePattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;

        this.logicJumpQuestionTypes = [ 'radio', 'select', 'yesorno', 'checkbox' ];
        this.logicJumpQuestionSteps = {};
        this.logicJumpSectionSteps = {};

        this.init();

        return this;
    }

    AysSurveyPlugin.prototype.init = function() {
        var _this = this;
        _this.uniqueId = _this.$el.data('id');

        if( typeof window.aysSurveyOptions != 'undefined' ){
            _this.dbOptions = JSON.parse( atob( window.aysSurveyOptions[ _this.uniqueId ] ) );
        }

        _this.$el.find('.ays-survey-wait-loading-loader').css("display" , "none");
        if( _this.$el.hasClass( _this.htmlClassPrefix + 'blocked-content' ) ){
            _this.blockedContent( true );
            return false;
        }
        
        _this.setup();
        _this.blockedContent( false );
        _this.setEvents();
        
        // if(_this.dbOptions.survey_enable_survey_start_loader){
            
        // }

        // _this.share();
        _this.keydown();
    };

    AysSurveyPlugin.prototype.setup = function(e) {
        var _this = this;
        _this.sectionsContainer = _this.$el.find('.' + _this.htmlClassPrefix + 'sections');
        _this.sections = _this.sectionsContainer.find('.' + _this.htmlClassPrefix + 'section');

        _this.sections.first().addClass('active-section');
        
        if( _this.$el.find("div.ays-survey-section[data-id]").length === 1 ){
            _this.sections.first().find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'finish-button').removeClass( _this.htmlClassPrefix + 'display-none' );
            _this.sections.first().find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'next-button').addClass( _this.htmlClassPrefix + 'display-none' );
        }
        
        _this.logicJumpQuestionSteps[ _this.sections.first().data('id') ] = {
            section: +_this.sections.first().data('id'),
            prevSection: null,
            active: true
        };
        
        _this.logicJumpSectionSteps[ _this.sections.first().data('id') ] = {
            section: +_this.sections.first().data('id'),
            prevSection: null,
            active: true
        };

		var questionTypeText = $(document).find('textarea.' + _this.htmlClassPrefix + 'question-input-textarea');
		autosize(questionTypeText);

		var questionTypeSelect = $(document).find('.' + _this.htmlClassPrefix + 'question-select');
		
        questionTypeSelect.each(function(){
			$(this).aysDropdown({
				duration: 150,
				transition: 'scale',
                onChange: function(value, text, $selectedItem) {
                    $selectedItem.parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
                    $selectedItem.parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
                }
			});
		});
        
        if( _this.dbOptions.options ){
            if( _this.dbOptions.options.survey_enable_i_autofill ){
                var checkAutoFill = _this.dbOptions.options.survey_enable_i_autofill.length > 0 && _this.dbOptions.options.survey_enable_i_autofill == "on" ? true : false; 
                if(checkAutoFill){
                    var form = _this.$el.find('form');
                    var data = form.serializeFormJSON();
                    data.action    = _this.ajaxAction;
                    data.function  = 'ays_survey_get_user_information';
                    data.end_date  = _this.GetFullDateTime();
                    data.unique_id = _this.uniqueId;
                    _this.aysAutofillData(data , _this.$el);
                }
            }
        }
    }

    AysSurveyPlugin.prototype.setEvents = function(e) {
        var _this = this;
        
        _this.aysNext();
        _this.aysFinish();

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'start-button', function(e){
            _this.start(e);
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'section-button.ays-check-survey-password', function(e){
            var passwordSurvey = $(this).parents('.ays-survey-section,.ays-survey-chat-container').find("input.ays-survey-password").val();            
            var activPsw = _this.dbOptions.options.survey_generated_passwords.survey_active_passwords;
            var form = $(this).parents('form');
            if( _this.checkSurveyPassword( passwordSurvey, true ) !== false ){
                if(_this.dbOptions.options.survey_password_type == 'generated_password'){
                    if( _this.dbOptions.options.survey_enable_password && activPsw.length != 0 ){
                        var userData;
                        userData = form.serializeFormJSON()
                        userData.action = _this.ajaxAction;
                        userData.function = 'ays_survey_used_password_ajax';
                        userData.userGeneratedPassword = passwordSurvey;
                        userData.uniqueId = _this.uniqueId;

                        $.ajax({
                            url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
                            method: 'post',
                            dataType: 'json',
                            data: userData,
                            success: function(response){
                                if(response.status){
                                   
                                }
                            }
                        });
                    }
                }
            }else{
                return false;
            }
            $(this).parents('.ays-survey-section').removeClass('active-section');
            $(this).parents('.ays-survey-section').next().addClass('active-section');
            var chatModeMainParent = $(this).parents('.ays-survey-chat-container')
            chatModeMainParent.find('.ays-survey-chat-content').css("display", "block");
            chatModeMainParent.find('.ays-survey-section-password-content').css("display", "none");
                
            if( _this.$el.find("div.ays-survey-section[data-id]").length === 1 ){
                $(this).parents('.ays-survey-section').next().find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'finish-button').removeClass( _this.htmlClassPrefix + 'display-none' );
                $(this).parents('.ays-survey-section').next().find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'next-button').addClass( _this.htmlClassPrefix + 'display-none' );
            }
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'single-submission-results-export', function(e){
            var $_this    = $(this)
            var type      = $_this.attr('data-type');
            var surveyID  = $_this.attr('survey-id');
            var submissionID  = $_this.attr('data-submission');
            var action    = _this.ajaxAction;
            var afunction = 'ays_survey_single_submission_results_export_public';
           
            $_this.prop('disabled',true);
            $_this.addClass('disabled');

            $.ajax({
                url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: {
                    action: action,
                    survey_id: surveyID,
                    submission_id: submissionID,
                    function: afunction,
                    type: type
                },
                success: function (response) {
                    if (response.status) {
                        var options = {
                            fileName: "survey_single_submission_export",
                            header: true
                        };
                        var tableData = [{
                            "sheetName": "Survey submission",
                            "data": response.data
                        }];
                        Jhxlsx.export(tableData, options);
                    }else{
                        swal.fire({
                            type: 'info',
                            html: "<h2>"+ aysSurveyLangObj.loadResource +"</h2><br><h6>"+ aysSurveyLangObj.dataDeleted +"</h6>"
                        })
                    }
                    $_this.prop('disabled',false);
                    $_this.removeClass('disabled');
                },
                error: function() {
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ aysSurveyLangObj.loadResource +"</h2><br><h6>"+ aysSurveyLangObj.dataDeleted +"</h6>"
                    }).then(function(res){
                        $_this.prop('disabled',false);
                        $_this.removeClass('disabled');
                    });
                }
            });
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'answer-label-other input', function(){
            $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-other-input').focus();
            _this.confirmBeforeUnload = true;
        });
        
        _this.$el.on('input', '.' + _this.htmlClassPrefix + 'answer-other-input', function(){
            
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');

            if( $(this).parents('.' + _this.htmlClassPrefix + 'question').data('type') == 'checkbox' ){
                var checkedCount = $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:checked').length;
                var questionId = $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-id').val();
                questionId = parseInt( questionId );
                var options = _this.dbOptions[ _this.dbOptionsPrefix + 'checkbox_options' ][questionId];
                if(typeof options != "undefined"){
                    if( options.enable_max_selection_count === true ){
                        if( options.max_selection_count != null && ( options.max_selection_count < checkedCount + 1 ) ){
                        }else{
                            $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-label-other input').prop('checked', true);
                        }
                    }else{
                        $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-label-other input').prop('checked', true);
                    }
                }
            }else{
                $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-label-other input').prop('checked', true);
            }
            _this.confirmBeforeUnload = true;
        });
        
        _this.$el.on('input', 'textarea[name^="' + _this.htmlClassPrefix + 'answers"],'+
            'input[type="text"][name^="' + _this.htmlClassPrefix + 'answers"], input[type="number"][name^="' + _this.htmlClassPrefix + 'answers"]', function(){
            
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');

            _this.confirmBeforeUnload = true;
        });
        
        _this.$el.on('change', 'input[type="date"][name^="' + _this.htmlClassPrefix + 'answers"]', function(){
            
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');

            _this.confirmBeforeUnload = true;
        });

        _this.sectionsContainer.on('change', 'input[type="radio"][name^="' + _this.htmlClassPrefix + 'answers"]', function(){
            var check_required = $(this).parents('.' + _this.htmlClassPrefix + 'question').data('required');
            
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
            
            if(!check_required){
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-clear-selection-container').addClass('in');
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-clear-selection-container').removeClass('out');
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-clear-selection-container').removeClass(_this.htmlClassPrefix + 'visibility-none');
            }
            _this.confirmBeforeUnload = true;
        });

        _this.sectionsContainer.on('click', 'input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:not(.' + _this.htmlClassPrefix + 'matrix-scale-checbox-inputs)', function(){
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
            
            clearTimeout( _this.maxCountTimeout );
            var questionBox = $(this).parents('.' + _this.htmlClassPrefix + 'question');
            questionBox.removeClass('ays-has-error');
            var checkedCount = $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:checked').length;
            var allVotesCount = $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]').length;
            var questionId = questionBox.data('id');

            questionId = parseInt( questionId );
            var options = _this.dbOptions[ _this.dbOptionsPrefix + 'checkbox_options' ][questionId];
            var minVotes = options.min_selection_count;
            var maxVotes = options.max_selection_count != null ? options.max_selection_count : 0;

            if( options.enable_max_selection_count === true ){
                var checkVotesCount = (maxVotes != null || minVotes != null) ? true : false;
                if(checkVotesCount){
                    if(maxVotes <= 0 ){
                        maxVotes = allVotesCount;
                    }

                    if( maxVotes < checkedCount ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.maximumVotes + ' ' + maxVotes + '</span>';
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').html(errorMessage);
                        
                        questionBox.addClass('ays-has-error');
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').show();
                        _this.maxCountTimeout = setTimeout(function() {
                            questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').hide();
                        }, 3000);
                        return false;
                    }

                    if(minVotes > maxVotes){
                        minVotes = maxVotes;
                    }

                    if(minVotes > allVotesCount){
                        minVotes = allVotesCount;
                    }

                    if(minVotes <= checkedCount){
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').hide();
                    }else{
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.minimumVotes + ' ' + minVotes + '</span>';
                        questionBox.addClass('ays-has-error');
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').html(errorMessage);
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').show();
                    }
                }
            }
        });

        _this.sectionsContainer.on('click', '.' + _this.htmlClassPrefix + 'answer-clear-selection-container .' + _this.htmlClassPrefix + 'button', function(){
            var clearContainer = $(this).parents('.' + _this.htmlClassPrefix + 'answer-clear-selection-container');
            $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('input[type="radio"][name^="' + _this.htmlClassPrefix + 'answers"]').prop('checked', false);
            clearContainer.removeClass('in');
            clearContainer.addClass('out');

            if( $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-star').length > 0 ){
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-star .' + _this.htmlClassPrefix + 'answer-label').each(function() {
                    $(this).find('i').removeClass('ays-fa-star').addClass('ays-fa-star-o').removeAttr('style');
                    $(this).removeClass('active-answer');
                });
            }

            setTimeout(function(){
                clearContainer.addClass(_this.htmlClassPrefix + 'visibility-none');
            }, 200);
        });

        if(_this.dbOptions.survey_question_text_to_speech){
            window.addEventListener('beforeunload', function(event) {
                event.preventDefault();
                speechSynthesis.cancel();
            });
            
            this.voices = surveyGetVoices();
            var voiceAction = false;
            _this.$el.on('click', '.' + _this.htmlClassPrefix + 'question-title-text-to-speech-icon', function(){
                voiceAction = !voiceAction;
                var questionText = atob($(this).attr('data-question'));
                if ('speechSynthesis' in window) {
                    
                    this.voices = surveyGetVoices();
                    var rate = 1, pitch = 1, volume = 1;
                    if(voiceAction){
                        _this.listenQuestionText(questionText, this.voices[0], rate, pitch, volume, 'play' );
                    }
                    else{
                        _this.listenQuestionText(questionText, this.voices[0], rate, pitch, volume, 'cancel' );                        
                    }
                }else{
                    console.log('Speech Synthesis Not Supported');
                }
            });
        }

        _this.radioCheckboxTypesChanges( this.$el, _this.htmlClassPrefix, _this.dbOptions  );
        _this.makeThemeQuestionContentActive( this.$el, _this.htmlClassPrefix, _this.dbOptions  );

        if( _this.dbOptions[ _this.dbOptionsPrefix + 'enable_leave_page' ] ){
            window.onbeforeunload =  function (e) {
                if( _this.confirmBeforeUnload === true ){
                    return true;
                }else{
                    return null;
                }
            }
        }

        _this.$el.on('input', '.' + _this.htmlClassPrefix + 'question-email-input', function(){
            
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
            
            if($(this).val() != ''){
                if (!(_this.emailValivatePattern.test($(this).val()))) {
                    var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                    errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').addClass('ays-has-error');
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                }else{
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').hide();
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
                }
            }else{
                $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
                $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').hide();
                $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
            }
        });

        _this.$el.on('keypress', '.' + _this.htmlClassPrefix + 'question-input[type="number"]', function(event){
            if (event.keyCode < 58 && event.keyCode > 47 || event.key > -1 && event.key < 10) {
                /* nothing */
            } else if (event.keyCode == 8 || event.keyCode == 0) {
                /* nothing */
            // } else if ( $(this).val().toLowerCase().indexOf( "e" ) == -1 && ( event.keyCode == 101 || event.keyCode == 69 ) ){
                /* nothing */
            } else {
                event.preventDefault();
            }
        });

        var active = false;
        _this.$el.on('mouseout', '.' + _this.htmlClassPrefix + 'answer-star',function(){
            var surveyColor = _this.dbOptions[ _this.dbOptionsPrefix + 'text_color' ];
            var surveyActiveColor = _this.dbOptions[ _this.dbOptionsPrefix + 'color' ];
            var allRateLabels = $(this).find('label');
            
            if($(this).find(".ays-fa-star").length !== 0) {
                active = true;
            }

            if (active) {   
                var index = -1;
                allRateLabels.each(function() {
                    if ($(this).hasClass('active-answer')) {
                        index = allRateLabels.index(this);
                    }   
                });
                for (var i = 0; i < allRateLabels.length; i++) {
                    if( _this.dbOptions[ _this.dbOptionsPrefix+'is_business'] ){
                        if (i > index) {
                            allRateLabels.eq(i).find('i').css('color','#ffffff');
                        } else {
                            allRateLabels.eq(i).find('i').css('color','#fc0');
                        }
                    }else{
                        if (i > index) {
                            allRateLabels.eq(i).find('i').removeClass('ays-fa-star').addClass('ays-fa-star-o');
                        } else {
                            allRateLabels.eq(i).find('i').removeClass('ays-fa-star-o').addClass('ays-fa-star');
                        }
                    }
                }
            }else{
                allRateLabels.each(function() {
                    if( _this.dbOptions[ _this.dbOptionsPrefix+'is_business'] ){
                        $(this).find('i').css('color','#ffffff');
                    }
                    else{
                        $(this).find('i').removeClass('ays-fa-star').addClass('ays-fa-star-o');
                    }
                });
            }
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'answer-star .'+ _this.htmlClassPrefix +'answer-label',function(){
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');

            $(this).parent().find('label').each(function() {
                $(this).removeClass('active-answer');
            });
            $(this).addClass('active-answer');
            active = true;
        });

        _this.$el.on('mouseover', '.'+ _this.htmlClassPrefix + 'answer-star .'+ _this.htmlClassPrefix +'answer-label',function(){
            var surveyColor = _this.dbOptions[ _this.dbOptionsPrefix + 'color' ];
            var allRateLabels = $(this).parent().find('label');
            var index = allRateLabels.index(this);
            allRateLabels.each(function() {
                if( _this.dbOptions[ _this.dbOptionsPrefix+'is_business'] ){
                    $(this).find('i').css('color','#ffffff');
                }
                else{
                    $(this).find('i').removeClass('ays-fa-star').addClass('ays-fa-star-o');
                }

            });
            for (var i = 0; i <= index; i++) {
                if( _this.dbOptions[ _this.dbOptionsPrefix+'is_business'] ){
                    allRateLabels.eq(i).find('i').css('color','#fc0');
                }
                else{                    
                    allRateLabels.eq(i).find('i').removeClass('ays-fa-star-o').addClass('ays-fa-star');
                }
            }
        });
        
        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'restart-button', function(){
            window.location.reload();
        });

        _this.$el.find(".ays-survey-range-type-input[data-type='just-range']").on('change' , function(){
            $(this).parents('.ays-survey-question').attr("data-required", false);
        });

        _this.$el.find(".ays-survey-range-type-input-for-required").on('change' , function(){
            $(this).addClass("isChanged");
        });

        _this.$el.find(".ays-survey-range-type-input-for-required").on('input' , function(e){
            var rangeCalcType = $(this).parents(".ays-survey-answer-slider-list-main").attr('data-calc-type');
            if(rangeCalcType == 'combined'){
                var rangeMaxLength = $(this).parents(".ays-survey-answer-slider-list-main").attr('data-max-length');
                
                var currentRangeName = $(this).attr("name");
                var allRanges = $(this).parents(".ays-survey-answer-slider-list-container").find('input:not([name="'+currentRangeName+'"])');
    
                var allRangesSum = 0;
                allRanges.each(function(){
                    allRangesSum += parseInt($(this).val());
                });
                
                var rangeMaxVal = rangeMaxLength - allRangesSum;
                if( $(this).val() >= rangeMaxVal){
                    $(this).val(rangeMaxVal);
                    $(this).addClass('ays-survey-range-type-input-disaled');
                }
                else{
                    $(this).removeClass('ays-survey-range-type-input-disaled');
                }

            }
        });
 
        _this.$el.find(".ays-survey-range-type-input").each(function(){
            var input = $(this).get(0);
            var span  = $(this).parents('.ays-survey-answer-range-type-main').find("span").get(0);

            setBubble( input, span );
        });

        _this.$el.find(".ays-survey-range-type-input").on('input', function(){
            var input = $(this).get(0);
            var span  = $(this).parents('.ays-survey-answer-range-type-main').find("span").get(0);

            setBubble( input, span );
        });
 
        _this.$el.find(".ays-survey-range-type-input").hover(function(){
            $(this).parents('.ays-survey-answer-range-type-main').find("span").addClass('ays-survey-answer-range-type-main-show');
        }, function(){
            $(this).parents('.ays-survey-answer-range-type-main').find("span").removeClass('ays-survey-answer-range-type-main-show');
        });

        _this.$el.find(".ays-survey-range-type-input").on("touchstart" , function(){
            $(this).parents('.ays-survey-answer-range-type-main').find("span").addClass('ays-survey-answer-range-type-main-show');
        });

        _this.$el.find(".ays-survey-range-type-input").on("touchend" , function(){
            $(this).parents('.ays-survey-answer-range-type-main').find("span").removeClass('ays-survey-answer-range-type-main-show');
        });

        // Social buttons
        _this.$el.find(".ays-survey-share-btn").on("click" , function(e){
            e.preventDefault();
            var wWidth = 650,
                wHeight = 450;
            var windowOptions = 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width='+wWidth+',height='+wHeight+',top='+(screen.height/2-wHeight/2)+',left='+(screen.width/2-wWidth/2);
            window.open(this.href, "_blank", windowOptions);
        });

        // Survey full screen
        _this.$el.find('.ays-survey-close-full-screen, .ays-survey-open-full-screen').on('click', function() {
            _this.toggleFullscreen(_this.el);
        });
        _this.aysSurveyFullScreenDeactivateAll();

        // Text limit character/word
        _this.$el.find('textarea.ays-survey-check-word-limit, input.ays-survey-check-word-limit').on('keyup keypress', function(e) {
            var currentQuestion = $(this).parents(".ays-survey-question");
            var currentquestionId = currentQuestion.find('.' + _this.htmlClassPrefix + 'question-id').val();
            var questionTextLimitOptions = _this.dbOptions[ _this.dbOptionsPrefix + 'text_limit_options' ][currentquestionId];
            _this.aysSurveyCheckTextLimit(currentQuestion,questionTextLimitOptions,e,$(this));
        });

        // Number limit character/word
        _this.$el.find('input.ays-survey-check-number-limit').on('keyup keypress', function(e) {
            var currentQuestion = $(this).parents(".ays-survey-question");
            var currentquestionId = currentQuestion.find('.' + _this.htmlClassPrefix + 'question-id').val();
            var questionTextLimitOptions = _this.dbOptions[ _this.dbOptionsPrefix + 'number_limit_options' ][currentquestionId];
            _this.aysSurveyCheckNumberLimit(currentQuestion,questionTextLimitOptions,e,$(this));
        });

        _this.aysSurveyonTabPress();

        // Number limit character/word
        _this.$el.find('input.ays-survey-is-phone-type').on('keypress', function(e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var checkingVal = _this.phoneValivatePattern.test(key);
            if( !checkingVal ){
                theEvent.returnValue = false;
                if(theEvent.preventDefault) e.preventDefault();

            }
        });



        _this.$el.one("click" , function(){
            _this.startDate = _this.GetFullDateTime();
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'collapse-question-action',function(){
            var $this = $(this);
            _this.collapseQuestion( $this );
        });        

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'action-expand-question' + ',' + '.'+ _this.htmlClassPrefix +'question-wrap-collapsed-action' ,function(){
            var $this = $(this);
            _this.expandQuestion( $this );
        });

        _this.$el.on('change', '.' + _this.htmlClassPrefix + 'answer-upload-type-file',function(){
            var $this = $(this);
            var thisParent = $this.parents('.' + _this.htmlClassPrefix + 'question');
            var typeError  = thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type-error-type');
            var sizeError  = thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type-error-size');
            var questionId = thisParent.data("id");
            var thisQuestionopts = _this.dbOptions[ _this.dbOptionsPrefix + 'questions' ][questionId].options;
            
            var file = $this.prop("files")[0];
            if(typeof file != "undefined"){
                var currentFileName = file.name;
                var currentFileType = currentFileName.split('.').pop();

                var fileSize = file.size;
                var fileSizeMb = ((fileSize/1024)/1024).toFixed(4);
                var fileAllowedSize = thisQuestionopts.file_upload_types_size;

                var allExtentions = new Array("pdf", "doc", "docx", "png", "jpg","jpeg", "gif");
                var allowedExtentions = new Array();
                if(thisQuestionopts.file_upload_toggle == "on"){
                    for(var i = 0; i <= allExtentions.length - 1; i++){
                        var checker = "file_upload_types_"+allExtentions[i];
                        if(thisQuestionopts[checker] != "undefined" && thisQuestionopts[checker] == "on"){
                            if(allExtentions[i] == "jpg"){
                                allowedExtentions.push("jpg" , "jpeg");
                            }else if(allExtentions[i] == "doc"){
                                allowedExtentions.push("doc" , "docx");
                            }else{
                                allowedExtentions.push(allExtentions[i]);
                            }
                        }
                    }
                }else{
                    allowedExtentions = allExtentions;
                }
                
                if(allowedExtentions.indexOf(currentFileType.toLowerCase()) !== -1){
                    typeError.hide();
                    sizeError.hide();
                    if(+fileSizeMb <= +fileAllowedSize){
                        typeError.hide();
                        sizeError.hide();
                        $this.parents('.ays-survey-question').attr("data-required", false);
                        var formData = new FormData();
                        var sFunction = 'ays_survey_upload_file';
                        formData.append('file', file);
                        formData.append('function', sFunction);
                        formData.append('action', _this.ajaxAction);
                        thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type-loader').show();
                        _this.uploadFile(formData , thisParent , currentFileName);
                    }else{
                        typeError.hide();
                        sizeError.css("display" , "flex");
                        
                    }
                }else{
                    typeError.css("display" , "flex");
                    sizeError.hide();
                }
                thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type-file').val("");
                setTimeout(function(){
                    typeError.hide();
                    sizeError.hide();
                    thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type-loader').hide();
                }, 6000);
            }
        });
        
        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'answer-upload-ready-image-box',function(){
            var $this = $(this);
            var isRequiredQuestion = $this.parents('.' + _this.htmlClassPrefix + 'answer-upload-type-main').attr("data-required");
            var $thisParent = $this.parents('.' + _this.htmlClassPrefix + 'answer-upload-type-main');
            var thisParent  = $this.parents('.' + _this.htmlClassPrefix + 'question');
            $thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-ready').hide();
            $thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type').show();
            $thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-ready-url-link').val("");
            $thisParent.find('.' + _this.htmlClassPrefix + 'answer-upload-type-file').val("");
            if(isRequiredQuestion == "true"){
                thisParent.attr("data-required", true);
            }
        });

        var timeInput = _this.$el.find('.' + _this.htmlClassPrefix + 'timepicker');
        if(timeInput.length > 0){
            _this.$el.find('.' + _this.htmlClassPrefix + 'timepicker').timepicker();
        }
        _this.$el.find('.' + _this.htmlClassPrefix + 'answer-matrix-scale-main').scroll(function(e) {
            if($(this).scrollLeft() > 0){
                $(this).find('.ays-survey-answer-matrix-scale-row:not(:first-child) .' + _this.htmlClassPrefix + 'answer-matrix-scale-column-row-header').addClass(_this.htmlClassPrefix + 'answer-matrix-scale-column-row-header-scrolled');
            }
            else{
                $(this).find('.ays-survey-answer-matrix-scale-row:not(:first-child) .' + _this.htmlClassPrefix + 'answer-matrix-scale-column-row-header').removeClass(_this.htmlClassPrefix + 'answer-matrix-scale-column-row-header-scrolled');
            }
        });

        _this.$el.on('click', '.ays-survey-edit-previous-submission-button',function(){            
            var currentSurveyId = typeof _this.dbOptions.id != 'undefined' && _this.dbOptions.id ? _this.dbOptions.id : null;
            $(this).addClass('display_none');
            $(this).parents('.ays-survey-edit-previous-submission-box').find('img.ays-survey-edit-previous-submission-loader').removeClass('display_none');
            if($(this).hasClass('ays-survey-edit-previous-submission-restricted')){
                _this.$el.find('.ays-survey-restricted-content.active-section').removeClass('active-section');
                _this.$el.find('.ays-survey-sections').removeClass('display_none');
            }
            _this.requestForEditSubmission(currentSurveyId , $(this));
        })

    }

    AysSurveyPlugin.prototype.start = function(e) {
        var _this = this;
        var $this = _this.$el.find('.' + _this.htmlClassPrefix + 'start-button');


        _this.current_fs = $(e.target).parents('.' + _this.htmlClassPrefix + 'section');
        _this.next_fs = $(e.target).parents('.' + _this.htmlClassPrefix + 'section').next();
        // _this.aysResetQuiz( _this.$el );

        var sectionsData = _this.dbOptions[ _this.dbOptionsPrefix + 'sections' ];
        var logicJumpSectionsGoTo = {};

        $.each(sectionsData, function(i){
            var logicSection = sectionsData[i];

            if( typeof logicSection.options != 'undefined' ){
                var logicSecOptions = logicSection.options;
                if( typeof logicSecOptions.go_to_section != 'undefined' ){
                    logicJumpSectionsGoTo[ logicSection.id ] = logicSecOptions.go_to_section;
                }
            }
        });
        
        var whatToDoSection = null;
        var thisSectionId = null;
        var whereToJumpSection = parseInt( logicJumpSectionsGoTo[ thisSectionId ] );
        if( whereToJumpSection == thisSectionId ){
            whatToDoSection = 'go_to_same_section';
        }else if( whereToJumpSection == -1 ){
            whatToDoSection = null;
        }else if( whereToJumpSection == -2 ){
            whatToDoSection = 'submit_form';
        }else{
            whatToDoSection = 'go_to_section';
        }
        
        if( $.isEmptyObject( logicJumpSectionsGoTo ) ){
            whereToJumpSection = null;
        }

        if( isNaN( whereToJumpSection ) ){
            whereToJumpSection = null;
        }

        var whatToDoSectionNext = null;
        var nextSectionId = null;
        var whereToJumpSectionNext = parseInt( logicJumpSectionsGoTo[ nextSectionId ] );
        if( whereToJumpSectionNext == nextSectionId ){
            whatToDoSectionNext = 'go_to_same_section';
        }else if( whereToJumpSectionNext == -1 ){
            whatToDoSectionNext = 'go_to_next_section';
        }else if( whereToJumpSectionNext == -2 ){
            whatToDoSectionNext = 'submit_form';
        }else{
            whatToDoSectionNext = 'go_to_section';
        }
        
        if( $.isEmptyObject( logicJumpSectionsGoTo ) ){
            whatToDoSectionNext = 'go_to_next_section';
        }

        if( isNaN( whereToJumpSectionNext ) ){
            whatToDoSectionNext = 'go_to_next_section';
        }

        if( ( whatToDoSectionNext == 'go_to_next_section' ) && _this.$el.find("div.ays-survey-section[data-id]").length - 1 == _this.$el.find("div.ays-survey-section[data-id]").index( _this.next_fs ) ){
            whatToDoSectionNext = null;
        }

        if( whatToDoSectionNext == 'submit_form' || whatToDoSectionNext == null ){
            _this.next_fs.find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'finish-button').removeClass( _this.htmlClassPrefix + 'display-none' );
            _this.next_fs.find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'next-button').addClass( _this.htmlClassPrefix + 'display-none' );
        }

        _this.activeStep( $(e.target), 'next', null);
    };

    AysSurveyPlugin.prototype.startTime = function (e) {
        var _this = this;
        var $this = $(e.target);
        var thisStep = $this.parents('.step');
        _this.$el.find('.ays-live-bar-wrap').css({'display': 'block'});
        _this.$el.find('.ays-live-bar-percent').css({'display': 'inline-block'});
        _this.$el.find('input.ays-start-date').val(_this.GetFullDateTime());
        if (_this.dbOptions.enable_timer == 'on') {
            _this.$el.find('div.ays-survey-timer').hide();
            var timer = parseInt(_this.$el.find('div.ays-survey-timer').attr('data-timer'));
            if (!isNaN(timer) && _this.dbOptions.timer !== undefined) {
                if (_this.dbOptions.timer === timer && timer !== 0) {
                    timer += 2;
                    if (timer !== undefined) {
                        _this.timer(timer, {
                            isTimer: true,
                            blockedContent: false,
                            blockedElement: null,
                        });
                    }
                } else {
                    alert('Wanna cheat??');
                    window.location.reload();
                }
            }
        }
    }

    AysSurveyPlugin.prototype.timer = function(timer, args) {
        if(typeof args == "undefined"){
            args = {};
        }
        var _this = this;
        var addTime = 0;
        if(timer >= 0){
            addTime = (timer * 1000);
        }
        var countDownDate = new Date().getTime() + addTime;
        var timeForShow;
        if(addTime > 0){
            var x = setInterval(function (){
                var now = new Date().getTime();
                var distance = countDownDate - Math.ceil(now/1000)*1000;
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if(hours <= 0){
                    hours = null;
                }else if (hours < 10) {
                    hours = '0' + hours;
                }
                if (minutes < 10) {
                    minutes = '0' + minutes;
                }
                if (seconds < 10) {
                    seconds = '0' + seconds;
                }
                timeForShow = ((hours == null)? "" : (hours + ":")) + minutes + ":" + seconds;
                if(distance <= 1000){
                    timeForShow = ((hours == null) ? "" : "00:") + "00:00";
                }else{
                    timeForShow = timeForShow;
                }
                _this.$el.find('.' + _this.htmlClassPrefix + 'countdown-time').html(timeForShow);
                // _this.$el.find('.' + _this.htmlClassPrefix + 'countdown-time').show(500);
                if(_this.$el.find('.' + _this.htmlClassPrefix + 'countdown-time').length === 0){
                    clearInterval(x);
                }

                if (distance <= 1000) {
                    clearInterval(x);
                    if(args.blockedContent){
                        if(args.redirectNewTab){
                            window.open( args.redirectUrl , "_blank");
                        }
                        else{
                            window.location.assign( args.redirectUrl );
                        }
                    }
                }
            }, 1000);
        }
        else{
            if(args.blockedContent){
                if(args.redirectNewTab){
                    window.open( args.redirectUrl , "_blank");
                }
                else{
                    window.location.assign( args.redirectUrl );
                }
            }
        }
    };

    AysSurveyPlugin.prototype.ratingAvg = function() {
        var _this = this;
        _this.$el.find('.for_quiz_rate_avg.ui.rating').rating('disable');
    };

    AysSurveyPlugin.prototype.aysNext = function() {
        var _this = this;

        var questionsData = _this.dbOptions[ _this.dbOptionsPrefix + 'questions' ];
        var sectionsData = _this.dbOptions[ _this.dbOptionsPrefix + 'sections' ];
        var logicJumpQuestionsAnswersGoTo = {};
        var logicJumpQuestionCheckboxGoTo = {};
        var logicJumpQuestionsGoTo = {};
        var logicJumpSectionsGoTo = {};

        $.each(sectionsData, function(i){
            var logicSection = sectionsData[i];

            if( typeof logicSection.options != 'undefined' ){
                var logicSecOptions = logicSection.options;
                if( typeof logicSecOptions.go_to_section != 'undefined' ){
                    logicJumpSectionsGoTo[ logicSection.id ] = logicSecOptions.go_to_section;
                }
            }
        });
        
        $.each(questionsData, function(i){
            var logicQuestion = questionsData[i];
            var logicAnswers = logicQuestion.answers;
            var qOptions = logicQuestion.options;
            var isLogicJump = typeof qOptions.is_logic_jump != 'undefined' ? qOptions.is_logic_jump : false;

            if( isLogicJump === true && logicAnswers.length > 0 ){
                if( $.inArray( logicQuestion.type, _this.logicJumpQuestionTypes ) !== -1 ){
                    if ( logicQuestion.type === 'checkbox' ){
                        if (typeof qOptions.other_logic_jump != 'undefined') {
                            logicJumpQuestionCheckboxGoTo[logicQuestion.id] = [];
                            $.each(qOptions.other_logic_jump, function (j) {
                                var logicAns = qOptions.other_logic_jump[j];
                                if (typeof logicAns.go_to_section != 'undefined') {
                                    logicJumpQuestionCheckboxGoTo[logicQuestion.id].push({
                                        'go_to_section': parseInt( logicAns.go_to_section ),
                                        'selected_options': logicAns.selected_options,
                                        'otherwise': typeof qOptions.other_logic_jump_otherwise != 'undefined' ? parseInt( qOptions.other_logic_jump_otherwise ) : -1
                                    });
                                }
                            });
                        }
                    }else {
                        $.each(logicAnswers, function (j) {
                            var logicAns = logicAnswers[j];
                            if (typeof logicAns.options != 'undefined') {
                                var logicAnsOptions = logicAns.options;
                                if (typeof logicAnsOptions.go_to_section != 'undefined') {
                                    logicJumpQuestionsAnswersGoTo[logicAns.id] = logicAnsOptions.go_to_section;
                                }
                            }
                        });

                        if ( qOptions.other_answer_logic_jump !== "off" ) {
                            logicJumpQuestionsGoTo[logicQuestion.id] = qOptions.other_answer_logic_jump;
                        }
                    }
                }
            }
        });
        
        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'next-button', function (e){
            _this.confirmBeforeUnload = true;
            e.preventDefault();
            if (_this.animating) return false;
            _this.animating = true;
            if( _this.checkForm( $( e.target ) ) ){
                var section = $(this).parents('.ays-survey-section');
                var nextSection = section.next();
                var thisSectionQuestions = section.find('.ays-survey-question');

                var whereToJump = null;
                var whereToJumpOtherAnswer = null;
                var thisSectionId = section.data('id');
                // var nextSectionId = section.next().data('id');
                var nextSectionId = nextSection.data('id');
                var currentPage = _this.$el.find("div.ays-survey-section.active-section").data("pageNumber") + 1;

                thisSectionQuestions.each(function(){
                    var questionType = $(this).data('type');
                    var questionId = $(this).data('id');
                    if( $.inArray( questionType, _this.logicJumpQuestionTypes ) !== -1 ){
                        var checkedElement = null;
                        switch( questionType ){
                            case 'radio':
                                checkedElement = $(this).find('.ays-survey-answer input[type="radio"]:checked');
                            break;
                            case 'checkbox':
                                checkedElement = $(this).find('.ays-survey-answer input[type="checkbox"]:checked');
                            break;
                            case 'select':
                                checkedElement = $(this).find('.ays-survey-answer .ays-survey-question-select input[type="hidden"]');
                            break;
                            default:
                            break;
                        }
                        if( questionType === 'checkbox' ){
                            var selectedOptions = [];
                            $.each(checkedElement, function (i) {
                                if( $(this).val() == '0' ){
                                    if( $(this).parents('.ays-survey-answer').find('.ays-survey-answer-other-input').val() !== '' ) {
                                        selectedOptions.push('other');
                                    }
                                }else {
                                    selectedOptions.push($(this).val());
                                }
                            });

                            if ( typeof logicJumpQuestionCheckboxGoTo[questionId] != 'undefined' ) {
                                var ljChceckboxQuestion = logicJumpQuestionCheckboxGoTo[questionId];
                                var ljChceckboxConditions = {};
                                for ( var i = 0; i < ljChceckboxQuestion.length; i++ ){
                                    if ( typeof ljChceckboxQuestion[i].selected_options != 'undefined' ) {
                                        if (ljChceckboxQuestion[i].selected_options.equals(selectedOptions)) {
                                            ljChceckboxConditions.go_to_section = ljChceckboxQuestion[i].go_to_section;
                                            ljChceckboxConditions.otherwise = null;
                                            break;
                                        } else {
                                            ljChceckboxConditions.go_to_section = null;
                                            ljChceckboxConditions.otherwise = ljChceckboxQuestion[i].otherwise;
                                        }
                                    }else if( typeof ljChceckboxQuestion[i].otherwise != 'undefined' ){
                                        ljChceckboxConditions.go_to_section = null;
                                        ljChceckboxConditions.otherwise = ljChceckboxQuestion[i].otherwise;
                                    }
                                }

                                if( ljChceckboxConditions.go_to_section !== null ){
                                    whereToJump = ljChceckboxConditions.go_to_section;
                                    if (whereToJump !== -1) {
                                        currentPage = _this.$el.find("div.ays-survey-section[data-id=" + whereToJump + "]").data("pageNumber");
                                    }
                                }else if( ljChceckboxConditions.otherwise !== null ){
                                    whereToJump = ljChceckboxConditions.otherwise;
                                    if (whereToJump !== -1) {
                                        currentPage = _this.$el.find("div.ays-survey-section[data-id=" + whereToJump + "]").data("pageNumber");
                                    }
                                }
                            }
                        }else {
                            var checkedValue = checkedElement.val();
                            var otherAnswerCheckedValue = checkedElement.data('logicjump');
                            if( typeof logicJumpQuestionsAnswersGoTo[ checkedValue ] != 'undefined' ){
                                whereToJump = logicJumpQuestionsAnswersGoTo[ checkedValue ];
                                if( whereToJump != -1 ){
                                    currentPage = _this.$el.find("div.ays-survey-section[data-id=" + whereToJump + "]").data("pageNumber");
                                }
                            }

                            if( typeof logicJumpQuestionsGoTo[ otherAnswerCheckedValue ] != 'undefined' ) {
                                whereToJump = logicJumpQuestionsGoTo[ otherAnswerCheckedValue ];
                                if( whereToJump != -1 ) {
                                    currentPage = _this.$el.find("div.ays-survey-section[data-id=" + whereToJump + "]").data("pageNumber");
                                }
                            }
                        }
                    }
                });

                var whatToDo = null;
                if( whereToJump == thisSectionId ){
                    whatToDo = 'go_to_same_section';
                }else if( whereToJump == -1 ){
                    whatToDo = null;
                }else if( whereToJump == -2 ){
                    whatToDo = 'submit_form';
                }else{
                    whatToDo = 'go_to_section';
                }

                if( whereToJump === null ){
                    whatToDo = null;
                }

                if( isNaN( whereToJump ) ){
                    whatToDo = null;
                }

                var whatToDoSectionNext = null;
                var whereToJumpSectionNext = parseInt( logicJumpSectionsGoTo[ nextSectionId ] );
                if( whereToJumpSectionNext == nextSectionId ){
                    whatToDoSectionNext = 'go_to_same_section';
                }else if( whereToJumpSectionNext == -1 ){
                    whatToDoSectionNext = 'go_to_next_section';
                }else if( whereToJumpSectionNext == -2 ){
                    whatToDoSectionNext = 'submit_form';
                }else{
                    whatToDoSectionNext = 'go_to_section';
                }

                if( $.isEmptyObject( logicJumpSectionsGoTo ) ){
                    whatToDoSectionNext = 'go_to_next_section';
                }

                if( isNaN( whereToJumpSectionNext ) ){
                    whatToDoSectionNext = 'go_to_next_section';
                }

                if( ( whatToDoSectionNext == 'go_to_next_section' ) && _this.$el.find("div.ays-survey-section[data-id]").length - 1 == _this.$el.find("div.ays-survey-section[data-id]").index( nextSection ) ){
                    whatToDoSectionNext = null;
                }

                if( whatToDoSectionNext == 'submit_form' || whatToDoSectionNext == null ){
                    section.next().find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'finish-button').removeClass( _this.htmlClassPrefix + 'display-none' );
                    section.next().find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'next-button').addClass( _this.htmlClassPrefix + 'display-none' );
                }

                if( whatToDo !== null ){
                    if( whatToDo == 'submit_form' ){
                        section.find('input.' + _this.htmlClassPrefix + 'finish-button').trigger('click');
                    }else if( whatToDo == 'go_to_section' ){
                        _this.activeStep($(e.target), 'next', whereToJump);
                        _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);

                        $.each( _this.logicJumpQuestionSteps, function(){
                            this.active = false;
                        });

                        _this.logicJumpQuestionSteps[ whereToJump ] = {
                            section: +whereToJump,
                            prevSection: thisSectionId,
                            active: true
                        };

                        if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                            _this.goToTop( _this.next_fs, 'start');
                        }else{
                            _this.goTo();
                        }
                    }else if( whatToDo == 'go_to_same_section' ){
                        _this.activeStep($(e.target), 'next', whereToJump);
                        _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs, 200);
    
                        if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                            _this.goToTop( _this.next_fs, 'start');
                        }else{
                            _this.goTo();
                        }
                    }
                }else{
                    var whatToDoSection = null;
                    var whereToJumpSection = parseInt( logicJumpSectionsGoTo[ thisSectionId ] );
                    if( whereToJumpSection == thisSectionId ){
                        whatToDoSection = 'go_to_same_section';
                    }else if( whereToJumpSection == -1 ){
                        whatToDoSection = null;
                    }else if( whereToJumpSection == -2 ){
                        whatToDoSection = 'submit_form';
                    }else{
                        whatToDoSection = 'go_to_section';
                    }
                    
                    if( $.isEmptyObject( logicJumpSectionsGoTo ) ){
                        whereToJumpSection = null;
                    }

                    if( isNaN( whereToJumpSection ) ){
                        whereToJumpSection = null;
                    }

                    var whatToDoSectionNext = null;
                    var whereToJumpSectionNext = parseInt( logicJumpSectionsGoTo[ nextSectionId ] );
                    if( whereToJumpSectionNext == nextSectionId ){
                        whatToDoSectionNext = 'go_to_same_section';
                    }else if( whereToJumpSectionNext == -1 ){
                        whatToDoSectionNext = 'go_to_next_section';
                        if( whereToJumpSection != -1 && whereToJumpSection != -2 && whereToJumpSection !== null && whereToJumpSection != thisSectionId ) {
                            nextSection = _this.$el.find("div.ays-survey-section[data-id='" + whereToJumpSection + "']");
                        }
                    }else if( whereToJumpSectionNext == -2 ){
                        whatToDoSectionNext = 'submit_form';
                    }else{
                        whatToDoSectionNext = 'go_to_section';
                        if( whereToJumpSection != -1 && whereToJumpSection != -2 && whereToJumpSection !== null && whereToJumpSection != thisSectionId ) {
                            nextSection = _this.$el.find("div.ays-survey-section[data-id='" + whereToJumpSection + "']");
                        }
                    }
                    
                    if( $.isEmptyObject( logicJumpSectionsGoTo ) ){
                        whatToDoSectionNext = 'go_to_next_section';
                    }

                    if( isNaN( whereToJumpSectionNext ) ){
                        whatToDoSectionNext = 'go_to_next_section';
                    }

                    if( ( whatToDoSectionNext == 'go_to_next_section' ) && _this.$el.find("div.ays-survey-section[data-id]").length - 1 == _this.$el.find("div.ays-survey-section[data-id]").index( nextSection ) ){
                        whatToDoSectionNext = null;
                    }

                    if( whatToDoSectionNext == 'submit_form' || whatToDoSectionNext == null ){
                        nextSection.find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'finish-button').removeClass( _this.htmlClassPrefix + 'display-none' );
                        nextSection.find('.' + _this.htmlClassPrefix + 'section-button.' + _this.htmlClassPrefix + 'next-button').addClass( _this.htmlClassPrefix + 'display-none' );
                    }

                    if( whatToDoSection !== null ){
                        if( whatToDoSection == 'submit_form' ){
                            section.find('input.' + _this.htmlClassPrefix + 'finish-button').trigger('click');
                        }else if( whatToDoSection == 'go_to_section' ){
                            _this.activeStep($(e.target), 'next', whereToJumpSection);
                            _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);
        
                            $.each( _this.logicJumpSectionSteps, function(){
                                this.active = false;
                            });
    
                            _this.logicJumpSectionSteps[ whereToJumpSection ] = {
                                section: whereToJumpSection,
                                prevSection: thisSectionId,
                                active: true
                            };
                            
                            currentPage = _this.$el.find("div.ays-survey-section[data-id=" + whereToJumpSection + "]").data("pageNumber");

                            if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                                _this.goToTop( _this.next_fs, 'start');
                            }else{
                                _this.goTo();
                            }

                        }else if( whatToDoSection == 'go_to_same_section' ){
                            _this.activeStep($(e.target), 'next', whereToJumpSection);
                            _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs, 200);
        
                            if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                                _this.goToTop( _this.next_fs, 'start');
                            }else{
                                _this.goTo();
                            }
                        }
                    }else{
                        // _this.actionLiveProgressBar($(e.target), 'next');
                        _this.activeStep($(e.target), 'next', null);

                        // _this.validateButtonsVisibility();

                        if( thisSectionId ){
                            if( _this.logicJumpQuestionSteps && _this.logicJumpQuestionSteps[ nextSectionId ] && typeof _this.logicJumpQuestionSteps[ nextSectionId ] != "undefined" ){
                                _this.logicJumpQuestionSteps[ nextSectionId ] = {
                                    section: +thisSectionId,
                                    prevSection: null,
                                    active: true
                                };
                            }
                        }

                        _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);

                        if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                            _this.goToTop( _this.next_fs, 'start');
                        }else{
                            _this.goTo();
                        }
                    }
                }

                if( _this.dbOptions.survey_cover_only_first_section && currentPage == 2){
                    _this.$el.find(".ays-survey-cover-photo-title-wrap").css('height' , 'auto');
                    _this.$el.find(".ays-survey-cover-photo-title-wrap").css('background-image' , 'unset');
                }
                
                var pageCount = _this.$el.find("div.ays-survey-section[data-id]").length;
                var fillWidth = ( currentPage * 100 ) / pageCount;
                _this.$el.find("div.ays-survey-live-bar-fill").animate({ 'width': fillWidth+"%" }, 1000);
                _this.$el.find("span.ays-survey-live-bar-changeable-text").text(currentPage);
            }else{
                
            }
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'prev-button', function (e){
            _this.confirmBeforeUnload = true;
            e.preventDefault();

            var section = $(this).parents('.ays-survey-section');
            var thisSectionId = section.data('id');

            var currentPage = _this.$el.find("div.ays-survey-section.active-section").data("pageNumber") - 1;

            if (_this.animating) return false;
            _this.animating = true;
            if( _this.dbOptions.survey_cover_only_first_section && currentPage == 1){
                _this.$el.find(".ays-survey-cover-photo-title-wrap").css('height' , _this.dbOptions.survey_cover_photo_height);
                _this.$el.find(".ays-survey-cover-photo-title-wrap").css('background-image' , "url(" + (_this.dbOptions.survey_cover_photo) + ")");
            }

            //if( _this.checkForm( $( e.target ) ) ){
                // _this.actionLiveProgressBar($(e.target), 'next');
                // _this.activeStep($(e.target), 'prev');

                var logicJumpPrevQuestion = -1;
                $.each( _this.logicJumpQuestionSteps, function(){
                    if( this.section === thisSectionId ){
                        logicJumpPrevQuestion = this.prevSection;
                    }
                });

                if( logicJumpPrevQuestion !== -1 ){
                    currentPage = _this.$el.find("div.ays-survey-section[data-id=" + logicJumpPrevQuestion + "]").data("pageNumber");
                    _this.activeStep($(e.target), 'prev', logicJumpPrevQuestion );
                    _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);

                    $.each( _this.logicJumpQuestionSteps, function(){
                        this.active = false;
                    });

                    if( typeof _this.logicJumpQuestionSteps[ logicJumpPrevQuestion ] != 'undefined' ){
                        _this.logicJumpQuestionSteps[ logicJumpPrevQuestion ].active = true;
                    }

                    if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                        _this.goToTop( _this.next_fs, 'start');
                    }else{
                        _this.goTo();
                    }
                }else{

                    var logicJumpPrevSection = -1;
                    $.each( _this.logicJumpSectionSteps, function(){
                        if( this.section === thisSectionId ){
                            logicJumpPrevSection = this.prevSection;
                        }
                    });
                    
                    if( logicJumpPrevSection !== -1 ){
                        currentPage = _this.$el.find("div.ays-survey-section[data-id=" + logicJumpPrevSection + "]").data("pageNumber");
                        $.each( _this.logicJumpSectionSteps, function(){
                            this.active = false;
                        });

                        if( typeof _this.logicJumpSectionSteps[ logicJumpPrevSection ] != 'undefined' ){
                            _this.logicJumpSectionSteps[ logicJumpPrevSection ].active = true;
                        }
                        
                        _this.activeStep( $(e.target), 'prev', logicJumpPrevSection );
                        _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);
                        
                        if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                            _this.goToTop( _this.next_fs, 'start');
                        }else{
                            _this.goTo();
                        }
                    }else{
                        // _this.validateButtonsVisibility();

                        // _this.next_fs.find('.ays-text-input').trigger( "focus" );

                        // _this.aysAnimateStep(_this.$el.data('questEffect'), _this.current_fs, _this.next_fs);

                        _this.activeStep( $(e.target), 'prev', null );
                        _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);
                        
                        if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                            _this.goToTop( _this.next_fs, 'start');
                        }else{
                            _this.goTo();
                        }
                    }   
                }
                
                var pageCount = _this.$el.find("div.ays-survey-section[data-id]").length;
                var fillWidth = ( currentPage * 100 ) / pageCount;
                _this.$el.find("div.ays-survey-live-bar-fill").animate({ 'width': fillWidth+"%" }, 500);
                _this.$el.find("span.ays-survey-live-bar-changeable-text").text( currentPage );
                // _this.goToTop();
            //}else{
                
            //}
        });

    };

    AysSurveyPlugin.prototype.aysFinish = function() {
        var _this = this;
        var fButtonSelector = 'input.' + _this.htmlClassPrefix + 'finish-button';

        _this.$el.on('click', fButtonSelector, function (e) {
            e.preventDefault();

            var form = _this.$el.find('form');

            var surveyRecaptcha = _this.dbOptions.options.enable_recaptcha && _this.dbOptions.options.enable_recaptcha == "on" ? true : false;

            var formCaptchaValidation = null;
            if( surveyRecaptcha ){
                if( form.attr('data-recaptcha-validate') ){
                    formCaptchaValidation = form.attr('data-recaptcha-validate') == 'true' ? true : false;
                }
            }

            if( surveyRecaptcha && formCaptchaValidation === null && _this.checkForm( $( e.target ) )){
                var cEvent = new CustomEvent('afterSurveySubmission', {
                    detail: {
                        _this: _this,
                        thisButton: $( e.target )
                    }
                });
                form.get(0).dispatchEvent(cEvent);
            }

            if( surveyRecaptcha === false ){
                formCaptchaValidation = true;
            }

            _this.confirmBeforeUnload = false;
            _this.endDate = _this.GetFullDateTime();

            if( _this.dbOptions.survey_edit_previous_submission ){
                var editPreviousSubmissionButton = _this.$el.find('.ays-survey-edit-previous-submission-box');
                if( editPreviousSubmissionButton.length > 0 ){
                    editPreviousSubmissionButton.remove();
                }
            }
            // if(_this.$el.find('.ays_music_sound').length !== 0){
            //     _this.$el.find('.ays_music_sound').fadeOut();
            //     setTimeout(function() {
            //         audioVolumeOut(_this.$el.find('.ays_quiz_music').get(0));
            //     },4000);
            //     setTimeout(function() {
            //         _this.$el.find('.ays_quiz_music').get(0).pause();
            //     },6000);
            // }
            // if(_this.$el.find('audio').length > 0){
            //     _this.$el.find('audio').each(function(e, el){
            //         el.pause();
            //     });
            // }
            // if(_this.$el.find('video').length > 0){
            //     _this.$el.find('video').each(function(e, el){
            //         el.pause();
            //     });
            // }
            // _this.$el.find('.ays-live-bar-wrap').addClass('bounceOut');
            // setTimeout(function () {
            //     _this.$el.find('.ays-live-bar-wrap').css('display','none');
            // },300);

            if( _this.checkForm( $( e.target ) ) && formCaptchaValidation === true ){


                // var next_sibilings_count = $(this).parents('form').find('.ays_question_count_per_page').val();
                // _this.$el.find('input[name^="ays_questions"]').attr('disabled', false);
                // _this.$el.find('div.ays-quiz-timer').slideUp(500);
                // if(_this.$el.find('div.ays-quiz-after-timer').hasClass('empty_after_timer_text')){
                //     _this.$el.find('div.ays-quiz-timer').parent().slideUp(500);
                // }

                // _this.activeStep($(this), 'next');

                // _this.next_fs = $(this).parents('.step').next();
                // _this.current_fs = $(this).parents('.step');
                // _this.next_fs.addClass('active-step');
                // _this.current_fs.removeClass('active-step');

                // var form = _this.$el.find('form');

                var data = form.serializeFormJSON();
                // var questionsIds = data.ays_quiz_questions.split(',');

                // for(var i = 0; i < questionsIds.length; i++){
                //     if(! data['ays_questions[ays-question-'+questionsIds[i]+']']){
                //         data['ays_questions[ays-question-'+questionsIds[i]+']'] = "";
                //     }
                // }
                var surveyCurrentPageLink = form.find('input[name="ays-survey-curent_page_link"]').val();
                data.action = _this.ajaxAction;
                data.function = 'ays_finish_survey';
                data.start_date = _this.startDate;
                data.end_date = _this.endDate;
                data.unique_id = _this.uniqueId;
                data.survey_current_page_link = surveyCurrentPageLink;


                var sections = _this.$el.find('.' + _this.htmlClassPrefix + 'sections > .' + _this.htmlClassPrefix + 'section:not(:last-child)');
                // sections.hide(100);
                setTimeout( function(){
                    sections.remove();
                }, 100 );

                _this.$el.find('.' + _this.htmlClassPrefix + 'sections > .' + _this.htmlClassPrefix + 'section.' + _this.htmlClassPrefix + 'results-content').show();

                var aysQuizLoader = form.find('div[data-role="loader"]');
                aysQuizLoader.addClass(aysQuizLoader.data('class'));
                aysQuizLoader.removeClass('ays-loader');

                setTimeout(function () {
                    _this.sendSurveyData(data, $(e.target));
                }, 2000);

                if(!_this.dbOptions.survey_enable_chat_mode){
                    if( _this.$el.parents('.ays-survey-popup-survey-window').length > 0 ){
                        _this.goToTop( _this.next_fs, 'start');
                    }else{
                        _this.goTo();
                    }
                }
                // if (parseInt(next_sibilings_count) > 0 && ($(this).parents('.step').attr('data-question-id') || $(this).parents('.step').next().attr('data-question-id'))) {
                //     current_fs = $(this).parents('form').find('div[data-question-id]');
                // }

                // aysAnimateStep(ays_quiz_container.data('questEffect'), current_fs, next_fs);
            }
        });
    }

    AysSurveyPlugin.prototype.validateButtonsVisibility = function() {
        var _this = this;
        var nextQuestionType = _this.next_fs.find('input[name^="ays_questions"]').attr('type');
        var buttonsDiv = _this.next_fs.find('.ays_buttons_div');
        var enableArrows = _this.$el.find(".ays_qm_enable_arrows").val();

        if(_this.dbOptions.enable_arrows){
            enableArrows = _this.dbOptions.enable_arrows == 'on' ? true : false;
        }else{
            enableArrows = parseInt(enableArrows) == 1 ? true : false;
        }
        var nextArrowIsDisabled = buttonsDiv.find('i.ays_next_arrow').hasClass('ays_display_none');
        var nextButtonIsDisabled = buttonsDiv.find('input.ays_next').hasClass('ays_display_none');

        if(_this.next_fs.find('textarea[name^="ays_questions"]').attr('type')==='text' && nextArrowIsDisabled && nextButtonIsDisabled){
           buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(_this.next_fs.find('textarea[name^="ays_questions"]').attr('type')==='text' && enableArrows){
           buttonsDiv.find('input.ays_next').addClass('ays_display_none');
           buttonsDiv.find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'checkbox' && nextArrowIsDisabled && nextButtonIsDisabled){
           buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'checkbox' && enableArrows){
           buttonsDiv.find('input.ays_next').addClass('ays_display_none');
           buttonsDiv.find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'number' && nextArrowIsDisabled && nextButtonIsDisabled){
           buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'number' && enableArrows){
           buttonsDiv.find('input.ays_next').addClass('ays_display_none');
           buttonsDiv.find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'text' && nextArrowIsDisabled && nextButtonIsDisabled){
           next_fs.find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'text' && enableArrows){
           next_fs.find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
           next_fs.find('.ays_buttons_div').find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'date' && nextArrowIsDisabled && nextButtonIsDisabled){
            buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'date' && enableArrows){
            buttonsDiv.find('input.ays_next').addClass('ays_display_none');
            buttonsDiv.find('.ays_next_arrow').removeClass('ays_display_none');
        }

        var isRequiredQuestion = (_this.dbOptions.make_questions_required && _this.dbOptions.make_questions_required == "on") ? true : false;
        if(isRequiredQuestion === true){
            if(_this.next_fs.find('.information_form').length === 0){
                if(enableArrows){
                    buttonsDiv.find('i.ays_next_arrow').attr('disabled', 'disabled');
                }else{
                    buttonsDiv.find('input.ays_next').attr('disabled', 'disabled');
                }
            }
        }
    }

    AysSurveyPlugin.prototype.goToTop = function( el, position = "center" ) {
        el.get(0).scrollIntoView({
            block: position,
            behavior: "smooth"
        });
    }

    AysSurveyPlugin.prototype.goTo = function() {
        var surveyAnimationTop;
        
        if(this.dbOptions != 'undefined'){
            surveyAnimationTop = (this.dbOptions.survey_animation_top && this.dbOptions.survey_animation_top != 0) ? parseInt(this.dbOptions.survey_animation_top) : 200;
        }else{
            surveyAnimationTop = 200;
        }

        if(this.dbOptions.survey_enable_animation_top){
            $('html, body').animate({
                scrollTop: this.$el.offset().top - surveyAnimationTop + 'px'
            }, 'fast');
        }

        return this; // for chaining...
    }

    AysSurveyPlugin.prototype.keydown = function(){
        var _this = this, $this = _this.$el;
        $this.find('input').on('focus', function () {
            $(window).on('keydown', function (event) {
                if (event.keyCode === 13) {
                    return false;
                }
            });
        });

        $this.find('input').on('blur', function () {
            $(window).off('keydown');
        });
    }

    AysSurveyPlugin.prototype.aysAnimateStep = function(animation, current_fs, next_fs, duration){
        var _this = this;
        if(typeof duration == "undefined"){
            duration = 500;
        }

        if(typeof next_fs !== "undefined"){
            switch(animation){
                case "lswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });

                    current_fs.addClass('swing-out-right-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                        next_fs.css('display', 'flex');
                        next_fs.addClass('swing-in-left-fwd');
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        next_fs.css({
                            'position':'relative',
                            'pointer-events': 'auto'
                        });
                        current_fs.removeClass('swing-out-right-bck');
                        next_fs.removeClass('swing-in-left-fwd');
                        _this.animating = false;
                    },1000);
                break;
                case "rswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });

                    current_fs.addClass('swing-out-left-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                        next_fs.css('display', 'flex');
                        next_fs.addClass('swing-in-right-fwd');
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        next_fs.css({
                            'position':'relative',
                            'pointer-events': 'auto'
                        });
                        current_fs.removeClass('swing-out-left-bck');
                        next_fs.removeClass('swing-in-right-fwd');
                        _this.animating = false;
                    },1000);
                break;
                case "shake":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.scale = 1 - (1 - now) * 0.2;
                            _this.left = (now * 50) + "%";
                            _this.opacity = 1 - now;
                            current_fs.css({
                                'transform': 'scale(' + _this.scale + ')',
                                'position': 'absolute',
                                'top':0,
                                'opacity': 1,
                                'pointer-events': 'none'
                            });
                            next_fs.css({
                                'left': _this.left,
                                'opacity': _this.opacity,
                                'display':'flex',
                                'position':'relative',
                                'pointer-events': 'none'
                            });
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            current_fs.css({
                                'pointer-events': 'auto',
                                'opacity': 1,
                                'position': 'static'
                            });
                            next_fs.css({
                                'display':'flex',
                                'position':'relative',
                                'transform':'scale(1)',
                                'opacity': 1,
                                'pointer-events': 'auto'
                            });
                            _this.animating = false;
                        },
                        easing: 'easeInOutBack'
                    });
                break;
                case "fade":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.opacity = 1 - now;
                            current_fs.css({
                                'position': 'absolute',
                                'width': '100%',
                                'pointer-events': 'none'
                            });
                            next_fs.css({
                                'opacity': _this.opacity,
                                'position':'relative',
                                'display':'block',
                                'pointer-events': 'none'
                            });
                        },
                        duration: duration,
                        complete: function () {
                            current_fs.hide();
                            current_fs.css({
                                'pointer-events': 'auto',
                                'position': 'static'
                            });
                            next_fs.css({
                                'display':'block',
                                'position':'relative',
                                'opacity': 1,
                                'pointer-events': 'auto'
                            });
                            _this.animating = false;
                        }
                    });
                break;
                default:
                    current_fs.animate({}, {
                        step: function (now, mx) {
                            current_fs.css({
                                'pointer-events': 'none'
                            });
                            next_fs.css({
                                'position':'relative',
                                'pointer-events': 'none'
                            });
                        },
                        duration: 0,
                        complete: function () {
                            current_fs.hide();
                            current_fs.css({
                                'pointer-events': 'auto'
                            });
                            next_fs.css({
                                'display':'block',
                                'position':'relative',
                                'transform':'scale(1)',
                                'pointer-events': 'auto'
                            });
                            _this.animating = false;
                        }
                    });
                break;
            }
        }else{
            switch(animation){
                case "lswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });
                    current_fs.addClass('swing-out-right-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        current_fs.removeClass('swing-out-right-bck');
                        _this.animating = false;
                    },1000);
                break;
                case "rswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });
                    current_fs.addClass('swing-out-left-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        current_fs.removeClass('swing-out-left-bck');
                        _this.animating = false;
                    },1000);
                case "shake":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.scale = 1 - (1 - now) * 0.2;
                            _this.left = (now * 50) + "%";
                            _this.opacity = 1 - now;
                            current_fs.css({
                                'transform': 'scale(' + _this.scale + ')',
                            });
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            _this.animating = false;
                        },
                        easing: 'easeInOutBack'
                    });
                break;
                case "fade":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.opacity = 1 - now;
                        },
                        duration: 500,
                        complete: function () {
                            current_fs.hide();
                            _this.animating = false;
                        },
                        easing: 'easeInOutBack'
                    });
                break;
                default:
                    current_fs.animate({}, {
                        step: function (now, mx) {

                        },
                        duration: 0,
                        complete: function () {
                            current_fs.hide();
                            _this.animating = false;
                        }
                    });
                break;
            }
        }
    }

    AysSurveyPlugin.prototype.activeStep = function(button, action, where) {
        var _this = this;
        _this.current_fs = button.parents('.' + _this.htmlClassPrefix + 'section');
        if(action == 'next'){
            if( where !== null ){
                _this.next_fs = _this.$el.find( '.' + _this.htmlClassPrefix + 'section[data-id="'+ where +'"]' );
            }else{
                _this.next_fs = button.parents('.' + _this.htmlClassPrefix + 'section').next();
            }
        }
        if(action == 'prev'){
            if( where !== null ){
                _this.next_fs = _this.$el.find( '.' + _this.htmlClassPrefix + 'section[data-id="'+ where +'"]' );
            }else{
                _this.next_fs = button.parents('.' + _this.htmlClassPrefix + 'section').prev();
            }
        }
        _this.current_fs.removeClass('active-section');
        _this.next_fs.addClass('active-section');
    }

    AysSurveyPlugin.prototype.startLiveProgressBar = function(button) {
        var _this = this;
        if (button.parents('.step').next().find('.information_form').length === 0 ){
            var questions_count = _this.$el.find('form').find('div[data-question-id]').length;
            var curent_number = _this.$el.find('form').find('div[data-question-id]').index(button.parents('div[data-question-id]')) + 1;
            var final_width = ((curent_number+1) / questions_count * 100) + "%";
            if(button.parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
            }else{
                button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
            }
            button.parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 500);
        }
    }

    AysSurveyPlugin.prototype.actionLiveProgressBar = function(button, action) {
        var _this = this;
        var questions_count = _this.$el.find('form').find('div[data-question-id]').length;
        var curent_number;
        if(action == 'next'){c
            curent_number = _this.$el.find('form').find('div[data-question-id]').index(button.parents('div[data-question-id]')) + 1;
        }
        if(action == 'prev'){
            curent_number = _this.$el.find('form').find('div[data-question-id]').index(button.parents('div[data-question-id]')) - 1;
        }
        if(curent_number != questions_count){
            if((button.hasClass('ays_finish')) == false){
                if (!($(this).hasClass('start_button'))) {
                    var current_width = button.parents('.ays-quiz-container').find('.ays-live-bar-fill').width();
                    var final_width = ((curent_number+1) / questions_count * 100) + "%";
                    if(button.parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                        button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
                    }else{
                        button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                    }
                    button.parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 500);
                }
            }
        }else{
            button.parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
            setTimeout(function () {
                button.parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
            },300)
        }

        if(questions_count === curent_number){
            if(_this.current_fs.hasClass('.information_form').length !== 0){
                _this.current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().slideUp(500);
                setTimeout(function () {
                    _this.current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
                },500);
            }
        }
    }

    AysSurveyPlugin.prototype.checkForm = function( button ){
        var _this = this;
        _this.animating = false;

        var section = button.parents('.' + _this.htmlClassPrefix + 'section');
        var requiredQuestions = section.find('[data-required="true"],[data-is-min="true"]');
        if ( requiredQuestions.length !== 0) {
            var empty_inputs = 0;
            var errorQuestions = section.find('.ays-has-error');
            section.find('.ays-has-error').removeClass('ays-has-error');
            for (var i = 0; i < requiredQuestions.length; i++) {
                var item = requiredQuestions.eq(i);
                var checkMinVotes = item.data('isMin');
                var checkAllTermsAndConds = item.data('allTermsCheck');
                if( item.data('type') == 'text' || item.data('type') == 'email' || item.data('type') == 'name' || item.data('type') == 'short_text' || item.data('type') == 'number' || item.data('type') == 'phone' || item.data('type') == 'date' || item.data('type') == 'time' || item.data('type') == 'date_time' ){
                    var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                    if(item.data('type') == 'date_time'){
                        var dateTimeInps = item.find( '.' + _this.htmlClassPrefix + 'input' );
                        var dateTimeInps = item.find( '.' + _this.htmlClassPrefix + 'input:not(.' + _this.htmlClassPrefix + 'not-required-field)' );
                        var dateTimeChecker = true;
                        dateTimeInps.each(function(e,i){
                            if($(i).val() == ''){
                                dateTimeChecker = false;
                                
                                if($(dateTimeInps[0]).val() == ''){
                                    $(dateTimeInps[0]).focus();
                                }
                                else{
                                    $(dateTimeInps[e]).focus();
                                }
                                errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                                item.addClass('ays-has-error');
                                item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                                item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                                _this.goToTop( item );
                                
                                empty_inputs++;
                            }
                        });
                        if(!dateTimeChecker){
                            break;
                        }
                    }
                    if( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() == '' ){
                        errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        item.find( '.' + _this.htmlClassPrefix + 'input:not(.' + _this.htmlClassPrefix + 'not-required-field)' ).focus();
                        empty_inputs++;
                        break;
                    }else{
                        if( item.data('type') == 'email' ){
                            if ( ! (_this.emailValivatePattern.test( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() ) ) ) {
                                errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';
                                item.addClass('ays-has-error');
                                item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                                item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                                _this.goToTop( item );
                                item.find( '.' + _this.htmlClassPrefix + 'input:not(.' + _this.htmlClassPrefix + 'not-required-field)' ).focus();
                                empty_inputs++;
                            }
                        }else{
                            continue;
                        }
                    }
                }

                var errorFlag = false;
                var checker = false;
                if ( item.data('type') == 'radio' || item.data('type') == 'checkbox' || item.data('type') == 'linear_scale' || item.data('type') == 'star' || item.data('type') == 'matrix_scale' || item.data('type') == 'matrix_scale_checkbox' || item.data('type') == 'star_list' || item.data('type') == 'slider_list' || item.data('type') == 'range' || item.data('type') == 'upload' ) {
                    var questType = item.data('type');
                    if(questType == 'linear_scale' || questType == 'star' ){
                        questType = "radio";
                    }
                    
                    if( questType == 'matrix_scale' || questType == 'star_list' || questType == 'matrix_scale_checkbox'){
                        var checkedFlag = 0;
                        var checkedInputs = "";
                        var checkableinputType = "radio";
                        if(questType == 'matrix_scale' || questType == 'matrix_scale_checkbox'){
                            checkedInputs = item.find( '.ays-survey-answer-matrix-scale-row:not(:first-child)');                            
                        }
                        else if(questType == 'star_list'){
                            checkedInputs = item.find( '.ays-survey-answer-star-list-row');
                        }
                        var checkedInputsLength = checkedInputs.length;
                        if(questType == 'matrix_scale_checkbox'){
                            checkableinputType = "checkbox";
                        }
                        checkedInputs.each(function(){
                            var getCheckedInputs = $(this).find('input[type="'+checkableinputType+'"]:checked');
                            if(getCheckedInputs.length > 0){
                                checkedFlag++;
                            }
                        });
                        
                        if(checkedFlag < checkedInputsLength){
                            errorFlag = true;
                        }
                    }
                    else if( questType == 'slider_list' ){
                        var checkedFlag = 0;
                        var checkedInputs = item.find( '.ays-survey-answer-slider-list-row:not(:first-child)');
                        var checkedInputsLength = checkedInputs.length;
                        var x  = checkedInputs.find('input.isChanged');
                        
                        checkedInputs.each(function(){
                            var getCheckedInputs = $(this).find('input.isChanged');
                            if(getCheckedInputs.length > 0){
                                checkedFlag++;
                            }
                        });
                        
                        if(checkedFlag < checkedInputsLength){
                            errorFlag = true;
                        }
                    }
                    else{
                        if( item.find('input[type="'+ questType +'"]:checked').length == 0 ){
                            errorFlag = true;
                        }
                    }

                    if(typeof checkMinVotes != 'undefined' && checkMinVotes){
                        checker = _this.checkMinVotes( item );
                        if( ! checker ){
                            return false;
                        }
                    }

                    if(typeof checkAllTermsAndConds != 'undefined' && checkAllTermsAndConds){
                        checker = _this.checkTermsAndConds( item );
                        if( ! checker ){
                            return false;
                        }
                    }


                    if( item.find('.' + _this.htmlClassPrefix + 'answer-other-input').length != 0 && 
                        item.find('.' + _this.htmlClassPrefix + 'answer-label-other input[value="0"]:checked').length > 0 ){
                        if( item.find('.' + _this.htmlClassPrefix + 'answer-other-input').val() == '' ){
                            errorFlag = true;
                            item.find('.' + _this.htmlClassPrefix + 'answer-other-input').focus();
                        }
                    }

                    if( errorFlag ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }

                if ( item.data('type') == 'select' ) {
                    if( item.find('.' + _this.htmlClassPrefix + 'question-select').aysDropdown('get value') == '' ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }
            }
            
            for (var i = 0; i < errorQuestions.length; i++) {
                var item = errorQuestions.eq(i);
                if( item.data('type') == 'email' ){
                    if( ! (_this.emailValivatePattern.test( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() ) ) ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';

                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        item.find( '.' + _this.htmlClassPrefix + 'input:not(.' + _this.htmlClassPrefix + 'not-required-field)' ).focus();
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }
            }

            if (empty_inputs !== 0) {
                return false;
            }else{
                return true;
            }
        }else{
            var empty_inputs = 0;
            // section.find('.ays-has-error').removeClass('ays-has-error');
            var errorQuestions = section.find('.ays-has-error');
            for (var i = 0; i < errorQuestions.length; i++) {
                var item = errorQuestions.eq(i);
                if( item.data('type') == 'email' ){
                    if( ! (_this.emailValivatePattern.test( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() ) ) ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';

                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        item.find( '.' + _this.htmlClassPrefix + 'input:not(.' + _this.htmlClassPrefix + 'not-required-field)' ).focus();
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }
            }
            
            var questions = section.find('.' + _this.htmlClassPrefix + 'question[data-is-min="true"]');
            if(questions.length > 0){
                for(var i = 0; i < questions.length; i++){
                    var checker =  _this.checkMinVotes( questions.eq(i) );
                    if( ! checker ){
                        return false;
                    }
                }
            }

            if (empty_inputs !== 0) {
                return false;
            }else{
                return true;
            }
        }
        
        return true;
    }

    AysSurveyPlugin.prototype.blockedContent = function( blocked ) {
        var _this = this;
        if( blocked ){
            var blockedContent = _this.$el.hasClass( _this.htmlClassPrefix + 'blocked-content' );
            if ( blockedContent ) {
                var limitAttemptCount  = _this.dbOptions[ _this.dbOptionsPrefix + 'limit_users' ];
                var redirectUrl = _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_url' ];
                if( limitAttemptCount && redirectUrl != ''){
                    var redirectTimerHTML = '<div class="' + _this.htmlClassPrefix + 'redirect-timer ' + _this.htmlClassPrefix + 'countdown-timer">' + 
                        aysSurveyLangObj.redirectAfter + ' <span class="' + _this.htmlClassPrefix + 'countdown-time">' +  _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_delay_seconds' ]  + '</span>' +
                    '</div>';
                    
                    _this.$el.find('.' + _this.htmlClassPrefix + 'section-header').prepend( $( redirectTimerHTML ) );

                    var timer = _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_delay' ] + 2;
                    _this.timer( timer, {
                        blockedContent: true,
                        redirectUrl: _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_url' ]
                    });
                }
            }
        }
        
        if( _this.$el.find('.ays_survey_login_form').length > 0 ){
            var surveyLoginForm = _this.$el.find('.ays_survey_login_form');
            var usernameInput = surveyLoginForm.find('input[type="text"]');
            var passwordInput = surveyLoginForm.find('input[type="password"]');
            var checkboxInput = surveyLoginForm.find('input[type="checkbox"]');
            var submitInput = surveyLoginForm.find('input[type="submit"]');
            _this.makeSurveyStyleInput( usernameInput );
            _this.makeSurveyStyleInput( passwordInput );
            _this.makeSurveyStyleSubmit( submitInput );
            if( checkboxInput.length > 0 ){
                _this.makeSurveyStyleCheckbox( checkboxInput );
            }
        }

        if(_this.dbOptions[ _this.dbOptionsPrefix + 'enable_survey_start_loader' ]){
            _this.$el.find('.ays-survey-wait-loading-loader').css("display" , "none");
        }

    }

    AysSurveyPlugin.prototype.selects = function(){
        var _this = this;
        _this.$el.find('.ays-field').on('click', function() {
            if ($(this).find(".select2").hasClass('select2-container--open')) {
                $(this).find('b[role="presentation"]').removeClass('ays_fa ays_fa_chevron_down');
                $(this).find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_up');
            } else {
                $(this).find('b[role="presentation"]').removeClass('ays_fa ays_fa_chevron_up');
                $(this).find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
            }
        });

        _this.$el.find('select.ays-select').on("select2:selecting", function(e){
            $(this).parents('.ays-quiz-container').find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
        });

        _this.$el.find('select.ays-select').on("select2:closing", function(e){
            $(this).parents('.ays-quiz-container').find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
        });

        _this.$el.find('select.ays-select').on("select2:select", function(e){
            $(this).parent().find('.ays-select-field-value').attr("value", $(this).val());
            if ($(this).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && $(this).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                $(this).parents('div[data-question-id]').find('.ays_next').trigger('click');
            }
            if($(this).parents(".ays-questions-container").find('form[id^="ays_finish_quiz"]').hasClass('enable_correction')) {
                if ($(this).find('option:selected').data("chisht") == 1) {
                    $(this).parents('.ays-field').addClass('correct correct_div');
                    $(this).parents('.ays-field').find('.select2-selection.select2-selection--single').css("border-bottom-color", "green");
                } else {
                    $(this).parents('.ays-field').addClass('wrong wrong_div');
                    $(this).parents('.ays-field').find('.select2-selection.select2-selection--single').css("border-bottom-color", "red");
                }
                if ($(this).find('option:selected').data("chisht") == 1) {
                    $(e.target).parents().eq(3).find('.right_answer_text').fadeIn();
                }
                else {
                    $(e.target).parents().eq(3).find('.wrong_answer_text').fadeIn();
                }
                $(this).attr("disabled", true);
                $(e.target).next().css("background-color", "#777");
                $(e.target).next().find('.selection').css("background-color", "#777");
                $(e.target).next().find('.select2-selection').css("background-color", "#777");
            }
            var this_select_value = $(this).val();
            $(this).find("option").removeAttr("selected");
            $(this).find("option[value='"+this_select_value+"']").attr("selected", true);
        });
    }

    AysSurveyPlugin.prototype.answersField = function(){
        var _this = this;

        _this.$el.on('change', 'input[name^="ays_questions"]', function (e) {
            var quizContainer = _this.$el;
            if(typeof myOptions != 'undefined'){
                var isRequiredQuestion = (_this.dbOptions.make_questions_required && _this.dbOptions.make_questions_required == "on") ? true : false;
                if(isRequiredQuestion === true){
                    if($(e.target).attr('type') === 'radio' || $(e.target).attr('type') === 'checkbox'){
                        if($(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]:checked').length != 0){
                            if (!$(e.target).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none')){
                                $(e.target).parents('div[data-question-id]').find('input.ays_next').removeAttr('disabled');
                                $(e.target).parents('div[data-question-id]').find('input.ays_early_finish').removeAttr('disabled');
                            }else if(!$(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                $(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').removeAttr('disabled');
                                $(e.target).parents('div[data-question-id]').find('i.ays_early_finish').removeAttr('disabled');
                            }
                        }else{
                            if (!$(e.target).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none')){
                                $(e.target).parents('div[data-question-id]').find('input.ays_next').attr('disabled', true);
                                $(e.target).parents('div[data-question-id]').find('input.ays_early_finish').attr('disabled', true);
                            }else if(!$(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                $(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').attr('disabled', true);
                                $(e.target).parents('div[data-question-id]').find('i.ays_early_finish').attr('disabled', true);
                            }
                        }
                    }
                }
            }

            if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                }
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                    }
                }
                if ($(e.target).parents().eq(4).hasClass('enable_correction')) {
                    if (checked_inputs.attr('type') === "radio") {
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                    } else if (checked_inputs.attr('type') === "checkbox") {
                        $(e.target).attr('disabled', true);
                        $(e.target).off('change');
                    }
                }
                return false;
            }

            if (quizContainer.find('form').hasClass('enable_correction')) {
                var right_answer_sound = quizContainer.find('.ays_quiz_right_ans_sound').get(0);
                var wrong_answer_sound = quizContainer.find('.ays_quiz_wrong_ans_sound').get(0);
                var finishAfterWrongAnswer = (_this.dbOptions.finish_after_wrong_answer && _this.dbOptions.finish_after_wrong_answer == "on") ? true : false;
                if ($(e.target).parents().eq(1).find('input[name="ays_answer_correct[]"]').length !== 0) {
                    var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                    if (checked_inputs.attr('type') === "radio") {

                        checked_inputs.nextAll().addClass('answered');
                        checked_inputs.parent().addClass('checked_answer_div');
                        if (checked_inputs.prev().val() == 1){
                            checked_inputs.nextAll().addClass('correct')
                            checked_inputs.parent().addClass('correct_div');
                        }else{
                            checked_inputs.nextAll().addClass('wrong');
                            checked_inputs.parent().addClass('wrong_div');
                        }

                        if (checked_inputs.prev().val() == 1) {
                            if(_this.dbOptions.answers_rw_texts && (_this.dbOptions.answers_rw_texts == 'on_passing' || _this.dbOptions.answers_rw_texts == 'on_both')){
                                var explanationTime = _this.dbOptions.explanation_time && _this.dbOptions.explanation_time != "" ? parseInt(_this.dbOptions.explanation_time) : 4;
                                if(! $(e.target).parents('.step').hasClass('not_influence_to_score')){
                                    $(e.target).parents().eq(3).find('.right_answer_text').slideDown(250);
                                }
                                _this.explanationTimeout = setTimeout(function(){
                                    if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                        checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                        checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                    }
                                }, explanationTime * 1000);
                            }else{
                                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                    checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                }
                            }
                            if((right_answer_sound)){
                                resetPlaying([right_answer_sound, wrong_answer_sound]);
                                setTimeout(function(){
                                    right_answer_sound.play();
                                }, 10);
                            }
                        } else {
                            $(e.target).parents('.ays-quiz-answers').find('input[name="ays_answer_correct[]"][value="1"]').parent().addClass('correct_div').addClass('checked_answer_div');
                            $(e.target).parents('.ays-quiz-answers').find('input[name="ays_answer_correct[]"][value="1"]').nextAll().addClass('correct answered');

                            if(_this.dbOptions.answers_rw_texts && (_this.dbOptions.answers_rw_texts == 'on_passing' || _this.dbOptions.answers_rw_texts == 'on_both')){
                                var explanationTime = _this.dbOptions.explanation_time && _this.dbOptions.explanation_time != "" ? parseInt(_this.dbOptions.explanation_time) : 4;
                                if(! $(e.target).parents('.step').hasClass('not_influence_to_score')){
                                    $(e.target).parents().eq(3).find('.wrong_answer_text').slideDown(250);
                                }
                                _this.explanationTimeout = setTimeout(function(){
                                    if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                        checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                        if(finishAfterWrongAnswer){
                                            goToLastPage(e);
                                        }else{
                                            checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                        }
                                    }else{
                                        if(finishAfterWrongAnswer){
                                            goToLastPage(e);
                                        }
                                    }
                                }, explanationTime * 1000);
                            }else{
                                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                    if(finishAfterWrongAnswer){
                                        goToLastPage(e);
                                    }else{
                                        checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                    }
                                }else{
                                    if(finishAfterWrongAnswer){
                                        goToLastPage(e);
                                    }
                                }
                            }
                            if((wrong_answer_sound)){
                                resetPlaying([right_answer_sound, wrong_answer_sound]);
                                setTimeout(function(){
                                    wrong_answer_sound.play();
                                }, 10);
                            }
                        }
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                        $(e.target).parents('div[data-question-id]').find('.ays-field').css({
                            'pointer-events': 'none'
                        });

                    }else if(checked_inputs.attr('type') === "checkbox"){
                        checked_inputs = $(e.target);
                        if (checked_inputs.length === 1) {
                            checked_inputs.parent().addClass('checked_answer_div');
                            if(checked_inputs.prev().val() == 1){
                                if((right_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        right_answer_sound.play();
                                    }, 10);
                                }
                                checked_inputs.parent().addClass('correct_div');
                                checked_inputs.nextAll().addClass('correct answered');
                            }else{
                                if((wrong_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        wrong_answer_sound.play();
                                    }, 10);
                                }
                                if(finishAfterWrongAnswer){
                                    goToLastPage(e);
                                }
                                checked_inputs.parent().addClass('wrong_div');
                                checked_inputs.nextAll().addClass('wrong answered');
                            }
                        }else{
                            for (var i = 0; i < checked_inputs.length; i++) {
                                if(checked_inputs.eq(i).prev().val() == 1){
                                    if((right_answer_sound)){
                                        resetPlaying([right_answer_sound, wrong_answer_sound]);
                                        setTimeout(function(){
                                            right_answer_sound.play();
                                        }, 10);
                                    }
                                    checked_inputs.eq(i).nextAll().addClass('correct answered');
                                    checked_inputs.eq(i).parent().addClass('correct_div');
                                    checked_inputs.eq(i).parent().addClass('checked_answer_div');
                                }else{
                                    if((wrong_answer_sound)){
                                        resetPlaying([right_answer_sound, wrong_answer_sound]);
                                        setTimeout(function(){
                                            wrong_answer_sound.play();
                                        }, 10);
                                    }
                                    if(finishAfterWrongAnswer){
                                        goToLastPage(e);
                                    }
                                    checked_inputs.eq(i).parent().addClass('checked_answer_div');
                                    checked_inputs.eq(i).nextAll().addClass('wrong answered');
                                    checked_inputs.eq(i).parent().addClass('wrong_div');
                                }
                            }
                            if(checked_inputs.eq(i).prev().val() == 1){
                                checked_inputs.eq(i).next().addClass('correct answered');
                                if((right_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        right_answer_sound.play();
                                    }, 10);
                                }
                            }else{
                                checked_inputs.eq(i).next().addClass('wrong answered');
                                if((wrong_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        wrong_answer_sound.play();
                                    }, 10);
                                }
                            }

                        }
                        $(e.target).attr('disabled', true);
                        $(e.target).off('change');
                    }
                }
            }else{
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                }
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                    }
                }
            }
        });

        _this.$el.find('button.ays_check_answer').on('click', function (e) {
            var thisAnswerOptions;
            var quizContainer = _this.$el;
            var right_answer_sound = quizContainer.find('.ays_quiz_right_ans_sound').get(0);
            var wrong_answer_sound = quizContainer.find('.ays_quiz_wrong_ans_sound').get(0);
            var questionId = $(this).parents('.step').data('questionId');
            var finishAfterWrongAnswer = (_this.dbOptions.finish_after_wrong_answer && _this.dbOptions.finish_after_wrong_answer == "on") ? true : false;
            thisAnswerOptions = _this.QuizQuestionsOptions[questionId];
            if($(this).parent().find('.ays-text-input').val() !== ""){
                if ($(e.target).parents('form[id^="ays_finish_quiz"]').hasClass('enable_correction')) {
                    if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                        return false;
                    }
                    $(this).css({
                        animation: "bounceOut .5s",
                    });
                    setTimeout(function(){
                        $(e.target).parent().find('.ays-text-input').css('width', '100%');
                        $(e.target).css("display", "none");
                    },480);
                    $(e.target).parent().find('.ays-text-input').css('background-color', '#eee');
                    $(this).parent().find('.ays-text-input').attr('disabled', 'disabled');
                    $(this).attr('disabled', 'disabled');
                    $(this).off('change');
                    $(this).off('click');

                    var input = $(this).parent().find('.ays-text-input');
                    var type = input.attr('type');
                    var userAnsweredText = input.val().trim();

                    var trueAnswered = false;
                    var thisQuestionAnswer = thisAnswerOptions.question_answer.toLowerCase();

                    if(type == 'date'){
                        var correctDate = new Date(thisAnswerOptions.question_answer),
                            correctDateYear = correctDate.getFullYear(),
                            correctDateMonth = correctDate.getMonth(),
                            correctDateDay = correctDate.getDate();
                        var userDate = new Date(userAnsweredText),
                            userDateYear = userDate.getFullYear(),
                            userDateMonth = userDate.getMonth(),
                            userDateDay = userDate.getDate();
                        if(correctDateYear == userDateYear && correctDateMonth == userDateMonth && correctDateDay == userDateDay){
                            trueAnswered = true;
                        }
                    }else if(type != 'number'){
                        thisQuestionAnswer = thisQuestionAnswer.split('%%%');
                        for(var i = 0; i < thisQuestionAnswer.length; i++){
                            if(userAnsweredText.toLowerCase() == thisQuestionAnswer[i].trim()){
                                trueAnswered = true;
                                break;
                            }
                        }
                    }else{
                        if(userAnsweredText.toLowerCase() == thisQuestionAnswer.trim()){
                            trueAnswered = true;
                        }
                    }

                    if(trueAnswered){
                        if((right_answer_sound)){
                            resetPlaying([right_answer_sound, wrong_answer_sound]);
                            setTimeout(function(){
                                right_answer_sound.play();
                            }, 10);
                        }
                        $(this).parent().find('.ays-text-input').css('background-color', 'rgba(39,174,96,0.5)');
                        $(this).parent().find('input[name="ays_answer_correct[]"]').val(1);
                    }else{
                        if((wrong_answer_sound)){
                            resetPlaying([right_answer_sound, wrong_answer_sound]);
                            setTimeout(function(){
                                wrong_answer_sound.play();
                            }, 10);
                        }
                        $(this).parent().find('.ays-text-input').css('background-color', 'rgba(243,134,129,0.8)');
                        $(this).parent().find('input[name="ays_answer_correct[]"]').val(0);
                        var rightAnswerText = '<div class="ays-text-right-answer">';

                        if(type == 'date'){
                            var correctDate = new Date(thisAnswerOptions.question_answer),
                                correctDateYear = correctDate.getFullYear(),
                                correctDateMonth = (correctDate.getMonth() + 1) < 10 ? "0"+(correctDate.getMonth() + 1) : (correctDate.getMonth() + 1),
                                correctDateDay = (correctDate.getDate() < 10) ? "0"+correctDate.getDate() : correctDate.getDate();
                            rightAnswerText += [correctDateMonth, correctDateDay, correctDateYear].join('/');
                        }else if(type != 'number'){
                            rightAnswerText += thisQuestionAnswer[0];
                        }else{
                            rightAnswerText += thisQuestionAnswer;
                        }

                        rightAnswerText += '</div>';
                        $(this).parents('.ays-quiz-answers').append(rightAnswerText);
                        $(this).parents('.ays-quiz-answers').find('.ays-text-right-answer').slideDown(500);
                        if(finishAfterWrongAnswer){
                            goToLastPage(e);
                        }
                    }
                }
            }
        });
    }

    AysSurveyPlugin.prototype.makeSurveyStyleInput = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'question-input-box">'+
            '<div class="' + _this.htmlClassPrefix + 'input-underline"></div>'+
            '<div class="' + _this.htmlClassPrefix + 'input-underline-animation"></div>'+
        '</div>';
        html = $( html );
        
        html.insertAfter( input );
        
        html.prepend( input );
        
        input.addClass( _this.htmlClassPrefix + 'input' );
        input.addClass( _this.htmlClassPrefix + 'question-input' );
    }

    AysSurveyPlugin.prototype.makeSurveyStyleCheckbox = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'answer-label-content">'+
            '<div class="' + _this.htmlClassPrefix + 'answer-icon-content">'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-ink"></div>'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-1">'+
                    '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-2">'+
                        '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-3"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<span class=""></span>'+
        '</div>';
        html = $( html );
        
        var inputCheckboxLabel = input.parents('label');
        var inputCheckbox = input;
        
        inputCheckboxLabel.addClass('ays-survey-answer-label');
        html.find('span').text( inputCheckboxLabel.text() );
        inputCheckboxLabel.text('');
        input.remove();
        inputCheckboxLabel.prepend( inputCheckbox );
        html.insertAfter( inputCheckbox );
    }

    AysSurveyPlugin.prototype.makeSurveyStyleRadio = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'answer-label-content">'+
            '<div class="' + _this.htmlClassPrefix + 'answer-icon-content">'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-ink"></div>'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-1">'+
                    '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-2">'+
                        '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-3"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<span class=""></span>'+
        '</div>';
        html = $( html );
        
        var inputCheckboxLabel = input.parents('label');
        var inputCheckbox = input;
        
        inputCheckboxLabel.addClass('ays-survey-answer-label');
        html.find('span').text( inputCheckboxLabel.text() );
        inputCheckboxLabel.text('');
        input.remove();
        inputCheckboxLabel.prepend( inputCheckbox );
        html.insertAfter( inputCheckbox );
    }

    AysSurveyPlugin.prototype.makeSurveyStyleSubmit = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'section-buttons">'+
            '<div class="' + _this.htmlClassPrefix + 'section-button-container">'+
                '<div class="' + _this.htmlClassPrefix + 'section-button-content">'+
                '</div>'+
            '</div>'+
        '</div>';
        html = $( html );
        
        html.insertAfter( input );
        
        html.find('.' + _this.htmlClassPrefix + 'section-button-content').append( input );

        input.addClass('ays-survey-section-button');
        input.removeClass('button button-primary');
    }

    /**
     * @return {string}
     */
    AysSurveyPlugin.prototype.GetFullDateTime = function() {
        var now = new Date();
        var strDateTime = [[now.getFullYear(), AddZero(now.getMonth() + 1), AddZero(now.getDate())].join("-"), [AddZero(now.getHours()), AddZero(now.getMinutes()), AddZero(now.getSeconds())].join(":")].join(" ");
        return strDateTime;
    }

    /**
     * @return {string}
     */
    AysSurveyPlugin.prototype.AddZero = function (num) {
        return (num >= 0 && num < 10) ? "0" + num : num + "";
    }

    /**
     * @return {boolean}
     */
    AysSurveyPlugin.prototype.validatePhoneNumber = function (input) {
      	var phoneno = /^[+ 0-9-]+$/;
      	if (input.value.match(phoneno)) {
      		  return true;
      	} else {
      		  return false;
      	}
    }

    AysSurveyPlugin.prototype.sendSurveyData = function(data, element){
        var _this = this;
        if(typeof _this.sendSurveyData.counter == 'undefined'){
            _this.sendSurveyData.counter = 0;
        }
        if(window.navigator.onLine){
            _this.sendSurveyData.counter++;
            $.ajax({
                url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: data,
                success: function(response){
                    if(response.status === true){
                        _this.doSurveyResult(response);
                    }else{
                        if(_this.sendSurveyData.counter >= 5){
                            swal.fire({
                                type: 'error',
                                html: aysSurveyLangObj.sorry + ".<br>" + aysSurveyLangObj.unableStoreData + "."
                            });
                            // _this.goQuizFinishPage(element);
                        }else{
                            if(window.navigator.onLine){
                                setTimeout(function(){
                                    _this.sendSurveyData(data, element);
                                },3000);
                            }else{
                                _this.sendSurveyData(data, element);
                            }
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(_this.sendSurveyData.counter >= 5){
                        swal.fire({
                            type: 'error',
                            html: aysSurveyLangObj.sorry + ".<br>" + aysSurveyLangObj.unableStoreData + "."
                        });
                        // _this.goQuizFinishPage(element);
                    }else{
                        setTimeout(function(){
                            _this.sendSurveyData(data, element);
                        },3000);
                    }
                }
            });
        }else{
            swal.fire({
                type: 'warning',
                html: aysSurveyLangObj.connectionLost + ".<br>" + aysSurveyLangObj.checkConnection + "."
            });
            _this.sendSurveyData.counter = 0;
            // _this.goQuizFinishPage(element);
        }
    }

    AysSurveyPlugin.prototype.goQuizFinishPage = function(element){
        var _this = this;
        var currentFS = _this.$el.find('.step.active-step');
        var next_sibilings_count = _this.$el.find('.ays_question_count_per_page').val();
        if (parseInt(next_sibilings_count) > 0 &&
            (element.parents('.step').attr('data-question-id') ||
             element.parents('.step').next().attr('data-question-id'))) {
            currentFS = _this.$el.find('div[data-question-id]');
        }
        currentFS.prev().css('display', 'flex');
        _this.aysAnimateStep(_this.$el.data('questEffect'), currentFS, currentFS.prev());
        // currentFS.animate({opacity: 0}, {
        //     step: function(now, mx) {
        //         options.scale = 1 - (1 - now) * 0.2;
        //         options.left = (now * 50)+"%";
        //         options.opacity = 1 - now;
        //         currentFS.css({
        //             'transform': 'scale('+options.scale+')',
        //             'position': '',
        //             'pointer-events': 'none'
        //         });
        //         currentFS.prev().css({
        //             'left': options.left,
        //             'opacity': options.opacity,
        //             'pointer-events': 'none'
        //         });
        //     },
        //     duration: 800,
        //     complete: function(){
        //         currentFS.hide();
        //         currentFS.css({
        //             'opacity': '1',
        //             'pointer-events': 'auto',
        //         });
        //         currentFS.prev().css({
        //             'transform': 'scale(1)',
        //             'position': 'relative',
        //             'opacity': '1',
        //             'pointer-events': 'auto'
        //         });
        //         options.animating = false;
        //     },
        //     easing: 'easeInOutBack'
        // });
        if(_this.dbOptions.enable_correction == 'on'){
            if(currentFS.prev().find('input:checked').length > 0){
                currentFS.prev().find('.ays-field input').attr('disabled', 'disabled');
                currentFS.prev().find('.ays-field input').on('click', function(){
                    return false;
                });
                currentFS.prev().find('.ays-field input').on('change', function(){
                    return false;
                });
            }
            if(currentFS.prev().find('option:checked').length > 0){
                currentFS.prev().find('.ays-field select').attr('disabled', 'disabled');
                currentFS.prev().find('.ays-field select').on('click', function(){
                    return false;
                });
                currentFS.prev().find('.ays-field select').on('change', function(){
                    return false;
                });
            }
            if(currentFS.prev().find('textarea').length > 0){
                if(currentFS.prev().find('textarea').val() !== ''){
                    currentFS.prev().find('.ays-field textarea').attr('disabled', 'disabled');
                    currentFS.prev().find('.ays-field textarea').on('click', function(){
                        return false;
                    });
                    currentFS.prev().find('.ays-field textarea').on('change', function(){
                        return false;
                    });
                }
            }
        }
    }

    AysSurveyPlugin.prototype.doSurveyResult = function( response ){
        var _this = this;
        if( response.status ){
            // var formResultsContainer = _this.$el.find('.' + _this.htmlClassPrefix + 'results-content');
            var formResults = _this.$el.find('.' + _this.htmlClassPrefix + 'thank-you-page');
            if (_this.$el.hasClass('enable_questions_result')) {

            }

            _this.$el.find('.'+_this.htmlClassPrefix+'chat-footer').css({'display':'flex'});

            var aysQuizLoader = _this.$el.find('div[data-role="loader"]');
            aysQuizLoader.addClass('ays-loader');
            aysQuizLoader.removeClass(aysQuizLoader.data('class'));

            var conditionPageMessage = "";
            var conditionEmailMessage = "";
            var conditionRedirectDelay = "";
            var conditionRedirectUrl = "";
            var conditionRedirectCountDown = "";
            var trueConditionsCount = "";
            
            if(typeof response.conditionData != "undefined"){
                if(response.conditionData.pageMessage){
                    conditionPageMessage = response.conditionData.pageMessage;
                }
                if(response.conditionData.emailMessage){
                    conditionEmailMessage = response.conditionData.emailMessage;
                }
                if(response.conditionData.redirectDelay){
                    conditionRedirectDelay = response.conditionData.redirectDelay;
                }
                if(response.conditionData.redirectUrl){
                    conditionRedirectUrl = response.conditionData.redirectUrl;
                }
                if(response.conditionData.redirectCountDown){
                    conditionRedirectCountDown = response.conditionData.redirectCountDown;
                }
                if(response.conditionData.trueConditionsCount){
                    trueConditionsCount = response.conditionData.trueConditionsCount;
                }
                
            }

            if(trueConditionsCount){
                console.log("You have more than one ("+trueConditionsCount+") true condition. Note that the program will take only the latest condition as true.");
            }

            if(conditionRedirectUrl){
                if(conditionRedirectDelay && +conditionRedirectDelay == 0){
                    if( conditionRedirectDelay == 0 && conditionRedirectUrl != "" ){
                        window.location = conditionRedirectUrl;
                        return;
                    }
                }
            }

            if(conditionPageMessage){
                if(typeof conditionPageMessage == 'string'){
                    formResults.prepend( $( '<div>' + conditionPageMessage + '</div>' ) );
                }
                else{
                    var conditionsBox = $("<div></div>");
                    $.each(conditionPageMessage , function(index , conditionResMessage){
                        conditionsBox.append(conditionResMessage);
                    })
                    formResults.prepend( conditionsBox );
                }
            }
            

            var chatSurveyMessage = null;
            if( response.message ){
                formResults.prepend( $( '<div>' + response.message + '</div>' ) );
                _this.$el.find('.'+_this.htmlClassPrefix+'chat-header').find('.'+_this.htmlClassPrefix+'chat-final-message-loader').removeClass('active-chat-loader');
                var thankYouMessageChatSurvey = '<div class="' + _this.htmlClassPrefix + 'chat-thank-you-message ' + _this.htmlClassPrefix +'chat-question-title">'+response.message + '<br>' + conditionPageMessage+'</div>';
                _this.$el.find('.'+_this.htmlClassPrefix+'chat-header-main').append(thankYouMessageChatSurvey)
                chatSurveyMessage = _this.$el.find('.' + _this.htmlClassPrefix + 'chat-thank-you-message');
                setTimeout(function(){
                    if( _this.$el.find('.ays-survey-chat-thank-you-message').length > 0 ){
                        _this.$el.find('.ays-survey-chat-thank-you-message').get(0).scrollIntoView({
                            block: 'center',
                            behavior: "smooth"
                        });
                    }
                }, 100);
            }

            if( _this.dbOptions[ _this.dbOptionsPrefix + 'show_summary_after_submission' ] ){

                var surveySubmissionSummaryContainers = _this.$el.find( '.' + _this.htmlClassPrefix + 'submission-summary-container' );

                surveySubmissionSummaryContainers.each(function(){
                    var uniqueId = $(this).data('id');

                    // Survey per answer count
                    var thisAysSurveyPublicChartData = JSON.parse( atob( window.aysSurveyPublicChartData[ uniqueId ] ) );
                    
                    if ( typeof thisAysSurveyPublicChartData.surveyColor === 'undefined' ) {
                        thisAysSurveyPublicChartData.surveyColor = '#FF5722';
                    }
            
                    $.each( thisAysSurveyPublicChartData.perAnswerData, function(){
                        switch( this.question_type ){
                            case "radio":
                            case "yesorno":
                                forRadioType( this, thisAysSurveyPublicChartData );
                            break;
                            case "checkbox":
                                forCheckboxType( this, thisAysSurveyPublicChartData );
                            break;
                            case "select":
                                forRadioType( this, thisAysSurveyPublicChartData );
                            break;
                            case "linear_scale":
                                forLinearScaleType( this, thisAysSurveyPublicChartData );
                            break;
                            case "star":
                                forLinearScaleType( this, thisAysSurveyPublicChartData );
                            break;
                            case "matrix_scale":
                            case "matrix_scale_checkbox":
                                forMatrixScaleTypeCustom( this, thisAysSurveyPublicChartData );
                            break;
                            case "star_list":
                                forStarListTypeCustom( this, thisAysSurveyPublicChartData );
                            break;
                            case "slider_list":
                                forSliderListTypeCustom( this, thisAysSurveyPublicChartData );
                            break;
                            case "range":
                                forRangeType( this, thisAysSurveyPublicChartData );
                            break;
                        }
                    });
                });
            }

            if(conditionRedirectUrl){
                if(conditionRedirectDelay && (+conditionRedirectDelay > 0 || +conditionRedirectDelay == 0)){
                    var redirectAfterSubmitTimerHTML = '<div class="' + _this.htmlClassPrefix + 'redirect-timer ' + _this.htmlClassPrefix + 'countdown-timer">' + 
                        aysSurveyLangObj.redirectAfter + ' <span class="' + _this.htmlClassPrefix + 'countdown-time">' + conditionRedirectCountDown + '</span>' +
                    '</div>';
                    formResults.prepend( $( redirectAfterSubmitTimerHTML ) );
                    _this.$el.find('.'+_this.htmlClassPrefix+'chat-footer').prepend( $( redirectAfterSubmitTimerHTML ) );

                    var timer = +conditionRedirectDelay + 2;
                    _this.timer( timer,  {
                        blockedContent: true,
                        redirectUrl: conditionRedirectUrl
                    });
                }
            }else{
                if( _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_after_submit' ] ){
                    var uniqueCode = response.unique_code;
                    var redirectUrl = _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_url' ];

                    redirectUrl = redirectUrl.includes('?') ? redirectUrl.replace('(uniquecode)', `&uniquecode=${uniqueCode}`) : redirectUrl.replace('(uniquecode)', `?uniquecode=${uniqueCode}`);
                    
                    if( parseInt( _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_delay' ] ) > 0 ){
                        if( redirectUrl != '' ){
                            var redirectAfterSubmitTimerHTML = '<div class="' + _this.htmlClassPrefix + 'redirect-timer ' + _this.htmlClassPrefix + 'countdown-timer">' + 
                                aysSurveyLangObj.redirectAfter + ' <span class="' + _this.htmlClassPrefix + 'countdown-time">' + _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_seconds' ] + '</span>' +
                            '</div>';
                            formResults.prepend( $( redirectAfterSubmitTimerHTML ) );
                            _this.$el.find('.'+_this.htmlClassPrefix+'chat-footer').append( $( redirectAfterSubmitTimerHTML ) );
                            
                            if( chatSurveyMessage !== null ){
                                chatSurveyMessage.prepend( $( redirectAfterSubmitTimerHTML ) );
                            }
                            
                            var timer = parseInt( _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_delay' ] ) + 2;
                            _this.timer( timer,  {
                                blockedContent: true,
                                redirectUrl: redirectUrl,
                                redirectNewTab: _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_new_tab' ]
                            });
                        }
                    } else{

                        _this.timer( timer,  {
                            blockedContent: true,
                            redirectUrl: redirectUrl,
                            redirectNewTab: _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_new_tab' ]
                        });
                    }
                }
            }

            formResults.css({'display':'block'});
            _this.$el.find('.'+_this.htmlClassPrefix+'chat-footer').css({'display':'block'});
        }else{

        }
    }

    AysSurveyPlugin.prototype.aysResetQuiz = function ($quizContainer){
        var cont = $quizContainer.find('div[data-question-id]');
        cont.find('input[type="text"], textarea, input[type="number"], input[type="url"], input[type="email"]').each(function(){
            $(this).val('');
        });
        cont.find('select').each(function(){
            $(this).val('');
        });
        cont.find('select.ays-select').each(function(){
            $(this).val(null).trigger('change');
        });
        cont.find('select option').each(function(){
            $(this).removeAttr('selected');
        });
        cont.find('input[type="radio"], input[type="checkbox"]').each(function(){
            $(this).removeAttr('checked');
        });
    }

    AysSurveyPlugin.prototype.aysAutofillData = function (data, currentEl){
        $.ajax({
            url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if(response !== null){
                    if( response.data ){
                        currentEl.find(".ays-survey-question[data-type='name'] .ays-survey-question-input-box input.ays-survey-input").val(response.data.display_name);
                        currentEl.find(".ays-survey-question[data-type='email'] .ays-survey-question-input-box input.ays-survey-question-email-input").val(response.data.user_email);
                    }
                } //:last-of-type
            }
        });
    }

    AysSurveyPlugin.prototype.checkSurveyPassword = function(passwordSurvey, isAlert){
        var _this = this;
        var activPsw = _this.dbOptions.options.survey_generated_passwords.survey_active_passwords;
        var flag = false;
        
        if(_this.dbOptions.options.survey_password_type == 'general'){
            if( _this.dbOptions.options.survey_enable_password && _this.dbOptions.options.survey_password_survey != "" ){
                if( _this.dbOptions.options.survey_password_survey !== passwordSurvey ){
                    if(isAlert){
                        alert( aysSurveyLangObj.passwordIsWrong );
                    }
                    return false;
                }
            }
            return true;

        }else if(_this.dbOptions.options.survey_password_type == 'generated_password'){

            if( _this.dbOptions.options.survey_enable_password && activPsw.length != 0 ){

                for (var activePass in activPsw) {

                    if(activPsw[activePass] == passwordSurvey){
                        flag = true;
                        break;
                    }
                }
                if( flag === false ){
                    if(isAlert){
                        alert( aysSurveyLangObj.passwordIsWrong );
                    }
                    return false;
                }
            }
        }

        // if( _this.dbOptions.options.survey_enable_password && _this.dbOptions.options.survey_password_survey != "" ){
        //     if( _this.dbOptions.options.survey_password_survey !== passwordSurvey ){

        //         if(isAlert){
        //             alert( aysSurveyLangObj.passwordIsWrong );
        //         }

        //         return false;
        //     }
        // }
        return true;
    }

    AysSurveyPlugin.prototype.checkMinVotes = function( item ){
        var _this = this;
        var options;
        var questionId = item.data('id');
        options = _this.dbOptions[ _this.dbOptionsPrefix + 'checkbox_options' ][questionId];
        if( options.enable_max_selection_count ){
            var allVotesCount = item.find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]').length;
            var checkedCount = item.find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:checked').length;
            var MinVotes = options.min_selection_count;
            var MaxVotes = options.max_selection_count;

            if(MinVotes > MaxVotes){
                MinVotes = MaxVotes;
            }

            if(MinVotes > allVotesCount){
                MinVotes = allVotesCount;
            }

            var errorMessage = '';
            if( MinVotes <= checkedCount ){
                item.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').hide();
            }

            if( MinVotes > checkedCount ){
                errorMessage += '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                errorMessage += '<span>' + aysSurveyLangObj.minimumVotes + ' ' + MinVotes + '</span>';
                item.addClass('ays-has-error');
                item.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').html(errorMessage);
                item.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').show();
                _this.goToTop( item );
                return false;
            }else{
                return true;
            }
        }
        return true;
    }

    AysSurveyPlugin.prototype.toggleFullscreen = function (elem) {
        var _this = this;
        elem = elem || document.documentElement;
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            _this.aysSurveyFullScreenActivate( elem );
            _this.aysSurveyFullScreenWindowActivator( elem );
        }else{
            _this.aysSurveyFullScreenDeactivate( elem );
            _this.aysSurveyFullScreenWindowDeactivator();
        }
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenActivate = function (elem) {
        $(elem).css({'background-color':'#fff'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-close-full-screen').css({'display':'inline'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-open-full-screen').css('display','none');
        $(elem).find('.ays-survey-full-screen-mode').css({'padding-right':'15px'});
        $(elem).css({'overflow':'auto'});
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenDeactivate = function (elem) {
        $(elem).css({'background-color':'initial'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-open-full-screen').css({'display':'inline'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-close-full-screen').css('display','none');        
        $(elem).find('.ays-survey-full-screen-mode').css({'padding-right':'0'});
        $(elem).css({'overflow':'initial'});
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenDeactivateAll = function (elem) {
        var _this = this;
        document.addEventListener('fullscreenchange', function(event) {
            if (!document.fullscreenElement) {
                var eventTarget = event.target;
                if( $( eventTarget ).hasClass('ays-survey-container') ){
                    _this.aysSurveyFullScreenDeactivate( eventTarget );
                }
            }
        }, false);
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenWindowActivator = function (elem) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        }else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        }else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenWindowDeactivator = function () {
        if(document.exitFullscreen) {
            document.exitFullscreen();
        }else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }

    AysSurveyPlugin.prototype.aysSurveyCheckTextLimit = function(question, options,e,current) {
        var $this = current;
        var _this = this;
        var box = question.find('.ays-survey-question-text-message');
        var questionTextMessage = question.find('.ays-survey-question-text-message-span');
        // Maximum length of a text field
        var enable_question_text_max_length = (options.enable_word_limitation && options.enable_word_limitation != "") ? options.enable_word_limitation : false;
        // Length
        var question_text_max_length = (options.limit_length && options.limit_length != "") ? parseInt(options.limit_length) : '';
        // Limit by
        var question_limit_text_type = (options.limit_by && options.limit_by != "") ? options.limit_by : '';
        // Show word/character counter
        var question_enable_text_message = (options.limit_counter && options.limit_counter != '') ? options.limit_counter : false;

        var remainder = '';
        if(question_text_max_length != '' && question_text_max_length != 0){
            switch ( question_limit_text_type ) {
                case 'char':
                    var tval = $this.val();
                    var tlength = tval.length;
                    var set = question_text_max_length;
                    var remain = parseInt(set - tlength);
                    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
                        $this.val((tval).substring(0, tlength - 1));
                    }
                    if (e.type=="keyup") {
                        var tval = $this.val().trim();
                        if(tval.length > 0 && tval != null){
                            var wordsLength = $this[0].value.split('').length;
                            if (wordsLength > question_text_max_length) {
                                var trimmed = tval.split('', question_text_max_length).join("");
                                $this.val(trimmed);
                            }
                        }
                    }
                    remainder = remain;
                    break;
                case 'word':
                    if (e.type=="keyup") {
                        var tval = $this.val().trim();
                        if(tval.length > 0 && tval != null){
                            var wordsLength = $this[0].value.match(/\S+/g).length;
                            if (wordsLength > question_text_max_length) {
                                var trimmed = tval.split(/\s+/, question_text_max_length).join(" ");
                                $this.val(trimmed + " ");
                            }
                            remainder = question_text_max_length - wordsLength;
                        }
                    }
                    break;
                default:
                    break;
            }
            if (e.type=="keyup") {
                if ( question_enable_text_message ) {
                    if(question_text_max_length != '' && question_text_max_length != 0){
                        if (remainder <= 0) {
                            remainder = 0;
                            if (! box.hasClass(_this.htmlClassPrefix + ' question-text-error-message') ) {
                                box.addClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }else{
                            if ( box.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                box.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }
                        if (tval.length == 0 || tval == null) {
                            if ( box.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                box.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                            remainder = question_text_max_length;
                        }

                        questionTextMessage.html( remainder );
                    }
                }
            }
        }
    }

    AysSurveyPlugin.prototype.aysSurveyCheckNumberLimit = function(question, options,e,current) {
        var $this = current;
        var _this = this;
        var errorBox = question.find('.ays-survey-number-limit-message-box');
        var limitBox = question.find('.ays-survey-question-text-message');
        var questionTextMessage = question.find('.ays-survey-question-text-message-span');
        // Enable Limitation
        var enableQuestionLimit = (options.enable_number_limitation && options.enable_number_limitation != "") ? options.enable_number_limitation : false;
        // Min Value
        var questionTextMinValue = (options.number_min_selection && options.number_min_selection != "") ? parseInt(options.number_min_selection) : '';
        // Max Value
        var questionTextMaxValue = (options.number_max_selection && options.number_max_selection != "") ? parseInt(options.number_max_selection) : '';
        // Show error message
        var enableTextMessage = (options.enable_number_error_message && options.enable_number_error_message != '') ? options.enable_number_error_message : false;
        // Max Length
        var questionTextMaxLength = (options.number_limit_length && options.number_limit_length != "") ? parseInt(options.number_limit_length) : '';
        // Show char length
        var questionEnableTextMessage = (options.enable_number_limit_counter && options.enable_number_limit_counter != '') ? options.enable_number_limit_counter : false;
        if(questionTextMinValue >= questionTextMaxValue){
            questionTextMaxValue = questionTextMinValue;
        }
        if(enableQuestionLimit){
            var remainder = '';
            if(questionTextMaxLength != '' && questionTextMaxLength != 0){
                var tval = $this.val();
                var tlength = tval.length;
                var set = questionTextMaxLength;
                var remain = parseInt(set - tlength);
                if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
                    $this.val((tval).substring(0, tlength - 1));
                }
                if (e.type=="keyup") {
                    var tval = $this.val().trim();
                    if(tval.length > 0 && tval != null){
                        var wordsLength = $this[0].value.split('').length;
                        if (wordsLength > questionTextMaxLength) {
                            var trimmed = tval.split('', questionTextMaxLength).join("");
                            $this.val(trimmed);
                        }
                    }
                }
                remainder = remain;
            }
        
            var enteredValue = $this.val().trim();
            var inputValue = parseInt( enteredValue );
            if(questionTextMinValue != '' && questionTextMinValue != 0){
                if( ! isNaN(inputValue) ){
                    if (e.type=="keyup") {
                        if ( inputValue < questionTextMinValue ) {
                            $this.val(questionTextMinValue);
                        }
                    }
                }
            }
            if(questionTextMaxValue != '' && questionTextMaxValue != 0){
                if (e.type=="keyup") {
                    if ( inputValue > questionTextMaxValue ) {
                        $this.val(questionTextMaxValue);
                    }
                }
            }
            if (e.type=="keyup") {
                if ( questionEnableTextMessage ) {
                    if(questionTextMaxLength != '' && questionTextMaxLength != 0){
                        if (remainder <= 0) {
                            remainder = 0;
                            if (! limitBox.hasClass(_this.htmlClassPrefix + ' question-text-error-message') ) {
                                limitBox.addClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }else{
                            if ( limitBox.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                limitBox.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }
                        if (tval.length == 0 || tval == null) {
                            if ( limitBox.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                limitBox.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                            remainder = questionTextMaxLength;
                        }

                        questionTextMessage.html( remainder );
                    }
                }
            }
            if(enableTextMessage){
                errorBox.show();
            }
        }
    }


    AysSurveyPlugin.prototype.collapseQuestion = function($this){
        var _this = this;
        var questsCont = $this.parents('.' + _this.htmlClassPrefix + 'question');
        // var questsText = questsCont.find('.' + _this.htmlClassPrefix + 'question-title').attr('value');
        // questsCont.find('.ays-survey-question-wrap-collapsed-action-contnet-text').html( questsText );
        questsCont.find('.ays-survey-question-wrap-collapsed-action').removeClass('ays-survey-display-none');
        questsCont.find('.ays-survey-question-wrap-expanded-action').addClass('ays-survey-display-none');
    }

    AysSurveyPlugin.prototype.expandQuestion = function($this){
        var _this = this;
        var questsCont = $this.parents('.' + _this.htmlClassPrefix + 'question');
        questsCont.find('.ays-survey-question-wrap-collapsed-action').addClass('ays-survey-display-none');
        questsCont.find('.ays-survey-question-wrap-expanded-action').removeClass('ays-survey-display-none');
    }

    AysSurveyPlugin.prototype.uploadFile = function(data, mainBox, fileName){
        var _this = this;
        $.ajax({
            url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: data,            
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status){
                    var messageBox = mainBox.find('.' + _this.htmlClassPrefix + 'answer-upload-ready');
                    var messageBoxLink = messageBox.find('.' + _this.htmlClassPrefix + 'answer-upload-ready-link');
                    var messageBoxHidden = messageBox.find('.' + _this.htmlClassPrefix + 'answer-upload-ready-url-link');
                    messageBoxLink.html(fileName);
                    messageBoxLink.attr("download", fileName);
                    messageBoxLink.attr("href", response.fileUrl);
                    messageBoxHidden.val(response.fileUrl);
                    mainBox.find('.' + _this.htmlClassPrefix + 'answer-upload-type').hide();
                    mainBox.find('.' + _this.htmlClassPrefix + 'answer-upload-type-loader').hide();
                    mainBox.attr("data-required", false);
                    messageBox.show();
                }
            }
        });
    }

    AysSurveyPlugin.prototype.checkTermsAndConds = function( item ){
        var _this = this;
        var alltermsCondsCount = item.find('.' + _this.htmlClassPrefix + 'is-checked-terms-and-conditions').length;
        var checkedTermsConsdCount = item.find('.' + _this.htmlClassPrefix + 'is-checked-terms-and-conditions:checked').length;
        if( alltermsCondsCount != checkedTermsConsdCount ){
            if( _this.dbOptions['enable_terms_and_conditions_required_message' ] ){
                item.find('.'+_this.htmlClassPrefix+'section-terms-and-conditions-required-message-content').css( 'display', 'block' );
            }
            item.find('.' + _this.htmlClassPrefix + 'is-checked-terms-and-conditions:not(:checked)').next().addClass('ays-has-error');
            return false;
        }else{
            return true;
        }
    }

    AysSurveyPlugin.prototype.aysSurveyonTabPress = function() {
        var _this = this;
        _this.$el.on('keydown', (e) => {
            var selectedBox = $(e.target);
            if( !selectedBox.hasClass( 'ays-survey-answer-text-inputs' ) && !selectedBox.hasClass( 'ays-survey-chat-short-text-input' )){
                if (e.keyCode === 32) {  
                    e.preventDefault();
                    if(selectedBox.hasClass('ays-survey-section-button-container')){
                        selectedBox.find('.ays-survey-section-button').trigger("click");
                    }
                    else{
                        selectedBox.trigger("click");
                    }
                }
            }
        });
    }

    AysSurveyPlugin.prototype.radioCheckboxTypesChanges = function( elem, htmlPreffix, options ){
        elem.each(function(index){   
            var theme = $(this).data('theme');
            if(theme == 'elegant'){              
                elem.eq(index).on('click', '.'+htmlPreffix+'answer-matrix-scale-column-content-wrap, .'+htmlPreffix+'answer', function(){
                    if( $(this).parents('.'+ htmlPreffix + theme + '-theme-question').attr('data-type') == 'radio'){

                        $(this).parents('.'+ htmlPreffix + 'question-answers').find('.' + htmlPreffix + 'answer-' + theme + '-active').removeClass(htmlPreffix + 'answer-' + theme + '-active');
                        $(this).find('input.'+htmlPreffix+'elegant-theme-answers').prop( 'checked', true );
                        if($(this).find('input.'+htmlPreffix+'elegant-theme-answers').prop( 'checked')){
                            $(this).addClass(htmlPreffix + 'answer-' + theme + '-active'); 
                        }
                    }
                });
            }

            elem.eq(index).find('input[type="checkbox"][class*="' + htmlPreffix + theme + '-theme-answers"], input[type="radio"][class^="' + htmlPreffix + theme + '-theme-answers"], .'+ htmlPreffix + 'matrix-scale-checbox-inputs.' + htmlPreffix + theme +'-theme-answers').on('change', function(){                 
                var thisClick = $(this).prop('checked');
                var thisParentsElegant = $(this).parents('.'+ htmlPreffix + theme + '-theme-style-for-other-answer');
                if(theme == 'business' || theme == 'elegant'){
                    if( $(this).parents('.'+ htmlPreffix + theme + '-theme-question').attr('data-type') == 'radio' || $(this).parents('.'+ htmlPreffix + theme + '-theme-question').attr('data-type') == 'linear_scale'){
                        $(this).parents('.'+ htmlPreffix + 'question-answers').find('.' + htmlPreffix + 'answer-' + theme + '-active').removeClass(htmlPreffix + 'answer-' + theme + '-active');
                        if(thisClick){
                            $(this).parents('.'+ htmlPreffix + 'answer-' + theme + '-hover').addClass(htmlPreffix + 'answer-' + theme + '-active');
                        }
                    }
                    else if( $(this).parents('.'+ htmlPreffix + theme + '-theme-question').attr('data-type') == 'matrix_scale'){
                        if(theme == 'elegant'){
                            $(this).parents('.'+ htmlPreffix + 'answer-matrix-scale-row-content').find('.'+ htmlPreffix + 'answer-' + theme+ '-active').removeClass(htmlPreffix + 'answer-' + theme + '-active-matrix-scale');
                            if(thisClick){
                                $(this).parents('.'+ htmlPreffix + 'answer-' + theme + '-hover').addClass(htmlPreffix + 'answer-' + theme + '-active-matrix-scale');
                            }
                        }else{
                            $(this).parents('.'+ htmlPreffix + 'answer-matrix-scale-row-content').find('.'+ htmlPreffix + 'answer-' + theme+ '-active').removeClass(htmlPreffix + 'answer-' + theme + '-active');
                            if(thisClick){
                                $(this).parents('.'+ htmlPreffix + 'answer-' + theme + '-hover').addClass(htmlPreffix + 'answer-' + theme + '-active');
                            }
                        }
                    }
                    else{
                        if(thisClick){
                            if(theme == 'business'){
                                $(this).parents('.'+ htmlPreffix + 'answer-'+theme+'-hover').css({
                                    'border': '1px solid' + options.survey_text_color,
                                    'background-color': '#f2f2f2',
                                    'border-radius': '7px',
                                });
                            }
        
                            if(theme == 'elegant'){
                                if( $(this).parents('.'+ htmlPreffix + theme + '-theme-question').attr('data-type') == 'matrix_scale_checkbox'){
                                    $(this).parents('.'+ htmlPreffix + 'answer-'+theme+'-hover').addClass(htmlPreffix + 'answer-' + theme + '-active-matrix-scale');
                                }else{
                                    $(this).parents('.'+ htmlPreffix + 'answer-'+theme+'-hover').addClass(htmlPreffix + 'answer-' + theme + '-active');
                                    
                                    thisParentsElegant.find('div.'+ htmlPreffix+'answer-other-text').css({
                                        'background':'#fff',
                                        'display':'block',
                                    });
                                    thisParentsElegant.find('div.'+ htmlPreffix+'answer-other-text > input.'+htmlPreffix+'answer-other-input').css({
                                        'caret-color': options.ays_survey_color,
                                        'border-bottom': '1px solid '+options.ays_survey_buttons_bg_color+' !important',
                                        'color': options.ays_survey_color,
                                        'font-size': 'inherit',
                                        'font-family': 'inherit',
                                        'font-weight': 'inherit',
                                        'line-height': 'inherit',
                                    });
                                }         
                            }
                        }else{
                            if(theme == 'business'){
                                $(this).parents('.'+ htmlPreffix + 'answer-' + theme + '-hover').css({
                                    'border': '1px solid #00000000',
                                    'background-color': 'unset',
                                    'border-radius': '7px',
                                });
                            }
        
                            if(theme == 'elegant'){
                                $(this).parents('.' + htmlPreffix + 'answer-' + theme + '-active').removeClass(htmlPreffix + 'answer-' + theme + '-active');

                                thisParentsElegant.find('div.'+ htmlPreffix+'answer-other-text').css({
                                    'display':'none',
                                });
                                thisParentsElegant.find('div.'+ htmlPreffix+'answer-other-text > input.'+htmlPreffix+'answer-other-input').css({
                                    'background-color':'unset',
                                });
                                    
                            }
                        }
                    }
                }
            });

            
        });
    }

    AysSurveyPlugin.prototype.makeThemeQuestionContentActive = function( elem, htmlPreffix, options ){
        elem.each(function(index){  
            var theme = $(this).data('theme'); 
            if( theme == 'elegant' ){
                elem.eq(index).on('click', '.' + htmlPreffix + theme +'-theme-question', function(e){
                    elem.eq(index).find('.' + htmlPreffix + theme +'-theme-question').removeClass(htmlPreffix + theme + '-theme-question-active');
                    $(this).addClass(htmlPreffix + theme + '-theme-question-active');
                
                } );
            
                $(document).on('click', function(e){
                    if (!elem.eq(index).find('.' + htmlPreffix + theme + '-theme-question').is(e.target) && !elem.eq(index).find('.' + htmlPreffix + theme + '-theme-question').has(e.target).length) {
                        elem.eq(index).find('.' + htmlPreffix + theme + '-theme-question').removeClass(htmlPreffix + theme +'-theme-question-active');
                    }
                });
            }
        });
    }

    AysSurveyPlugin.prototype.listenQuestionText = function( text, voice, rate, pitch, volume, action ){
        if(action == 'play'){
            let speakData = new SpeechSynthesisUtterance();
            speakData.volume = volume; // From 0 to 1
            speakData.rate = rate; // From 0.1 to 10
            speakData.pitch = pitch; // From 0 to 2
            speakData.text = text;
            speakData.lang = 'en';
            speakData.voice = voice;
            speechSynthesis.speak(speakData);
        }
        else if (action == 'cancel'){
            speechSynthesis.cancel();
        }
    }

    AysSurveyPlugin.prototype.requestForEditSubmission = async function( surveyId , thisButton){
        var _this  = this;
        var action    = _this.ajaxAction;
        var afunction = 'ays_survey_edit_previous_submission_fn';
        _this.$el.addClass('ays-survey-edit-previous-submission-wait-layer');
        var responseForLoop;
        var responseFlag = false;
        var errorMessage;
        if(surveyId){
            await $.ajax({
                url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: {
                    action: action,
                    survey_id: surveyId,
                    function: afunction
                },
                success: function (response) {
                    if(response.status){
                        responseForLoop = response;
                        responseFlag = true;
                    }
                    else{
                        errorMessage = response.message;
                    }
                }
            });
            if(responseForLoop){
                
                await _this.preventQuestionAnswers(responseForLoop.questions);

                var submissionId = responseForLoop.submission_id;
                var userId = responseForLoop.user_id;
                var submQuestIdsMatrixTypes = responseForLoop.subm_quest_ids_matrix_types;

                var sendData = {
                    userId   : userId,
                    surveyId : surveyId,
                    submissionId : submissionId,
                    subm_quest_ids_matrix_types : submQuestIdsMatrixTypes,
                }

                var sendDataEncoded =  btoa(JSON.stringify(sendData));
                var inputForUpdate = '<input type="hidden" name="ays_survey_update_submission_'+surveyId+'" value="'+sendDataEncoded+'">'
                _this.$el.find('.ays-survey-form').append(inputForUpdate);
                
            }
        }
        _this.$el.removeClass('ays-survey-edit-previous-submission-wait-layer');
        _this.$el.find('.ays-survey-edit-previous-submission-box img.ays-survey-edit-previous-submission-loader').addClass('display_none');

        if(!responseFlag){
            thisButton.removeClass('display_none');
            errorMessage = $('<span class="ays-survey-edit-previous-submission-box-failed ays-survey-edit-previous-submission-box-message">'+errorMessage+'</span>');            
            thisButton.parents('.ays-survey-edit-previous-submission-box').prepend(errorMessage);
            setTimeout(function() {
                errorMessage.fadeOut(500, function() {
                    errorMessage.remove();
                });
              }, 3000);
        }
        else{
            _this.$el.find('.ays-survey-edit-previous-submission-box').html('<span class="ays-survey-edit-previous-submission-box-message">' + responseForLoop.message + '</span>');
        }
    }

    AysSurveyPlugin.prototype.preventQuestionAnswers = function (response) {
        if(response.length < 1){
            return;
        }
        var _this = this;
        var inputSimpleTypes = ['radio', 'yesorno', 'checkbox', 'select', 'star', 'linear_scale' , 'range'];
        var inputTextTypes   = ['text', 'short_text', 'number', 'phone', 'date', 'date_time', 'time' , 'email' , 'name', 'upload'];
        var inputMatrixTypes = ['matrix_scale', 'matrix_scale_checkbox', 'star_list', 'slider_list'];
        var rPromise = new Promise(function(resolve, reject) {
            for(var questionId in response){
                var eachQuestion  = _this.$el.find('.ays-survey-question[data-id="'+questionId+'"]');
                var questionType  = eachQuestion.attr('data-type');
                var eachAnswerBox = eachQuestion.find('.ays-survey-question-answers');
                var uAnswer = '';
                
                var userExplanation = typeof response[questionId].user_explanation != 'undefined' && response[questionId].user_explanation ? response[questionId].user_explanation : '';
                
                if(inputSimpleTypes.includes(questionType)){
                    uAnswer = response[questionId].answer;
                    // For checkbox type
                    if(Array.isArray(uAnswer)){
                        $.each(uAnswer, function(i, value){
                            eachAnswerBox.find('input[value="'+value+'"]').prop('checked', true);
                        } );
                    }
                    else{
                        eachAnswerBox.find('input[value="'+uAnswer+'"]').prop('checked', true);
                    }

                    if(questionType == 'range'){
                        var rangeInput = eachAnswerBox.find('input.ays-survey-range-type-input[type="range"]');
                        if(rangeInput.length > 0){
                            rangeInput.val(uAnswer);
                            rangeInput.trigger('change');
                            rangeInput.trigger('input');
                        }
                    }

                    if(questionType == 'star'){
                        var starInput = eachAnswerBox.find('.ays-survey-answer-star-radio input[value="'+uAnswer+'"]').parents('.ays-survey-answer-label ');
                        if(starInput.length > 0){
                            starInput.trigger('click');
                            starInput.trigger("mouseover");
                        }
                    }

                    if(questionType == 'select'){
                        if(uAnswer){
                            var selectBox = eachAnswerBox.find(".ays-survey-question-select .menu .item[data-value='"+uAnswer+"']");
                            selectBox.trigger("click");
                        }
                    }
                    
                }

                else if(inputTextTypes.includes(questionType)){
                    uAnswer = response[questionId].answer;

                    var tAnswer = '';
                    if(questionType == 'date' || questionType == 'date_time'){
                        if(uAnswer){
                            var dateParts = uAnswer.split(' ');
                            uAnswer = dateParts[4] + "-" + dateParts[2] + "-" + dateParts[0];
                            tAnswer = (dateParts[5] && dateParts[7]) ? dateParts[5] + ":" + dateParts[7] : '';
                        }
                    }
                    else if(questionType == 'time'){
                        if(uAnswer){
                            var dateParts = uAnswer.split(' : ');
                            tAnswer = dateParts[0] + ":" + dateParts[1];
                        }
                    }

                    if(tAnswer){
                        eachAnswerBox.find('.ays-survey-question-input-box input.ays-survey-timepicker').val(tAnswer);
                    }
                    if(uAnswer){
                        eachAnswerBox.find('.ays-survey-question-input-box input:not(.ays-survey-timepicker), .ays-survey-question-input-box textarea').val(uAnswer);
                    }

                    if(questionType == 'email'){
                        eachAnswerBox.find('.ays-survey-question-input-box input[type="hidden"][name*="ays-survey-user-email"]').val(questionId);
                    }

                    if(questionType == 'upload'){
                        var uAnswerName = response[questionId].answer_name;
                        if(uAnswer && uAnswerName){
                        
                            var uploadBox = eachAnswerBox.find('.ays-survey-answer-upload-type-main');
                            var uploadInput = uploadBox.find('input.ays-survey-answer-upload-ready-url-link');
                            var uploadLink  = uploadBox.find('a.ays-survey-answer-upload-ready-link');

                            uploadBox.find('.ays-survey-answer-upload-type').hide();
                            uploadBox.find('.ays-survey-answer-upload-ready').show();

                            uploadInput.val(uAnswer);
                            uploadLink.attr('download' , uAnswerName);
                            uploadLink.attr('href' , uAnswer);
                            uploadLink.html(uAnswerName);
                        }
                    }
                }

                else if(inputMatrixTypes.includes(questionType)){
                    switch(questionType){
                        case 'matrix_scale':
                        case 'matrix_scale_checkbox':
                            uAnswer = response[questionId].answer_ids;
                            break;
                        case 'star_list':
                            uAnswer = response[questionId].star_list_answer_ids;
                            break;
                        case 'slider_list':
                            uAnswer = response[questionId].slider_list_answer_ids;
                            break;
                    }
                    $.each(uAnswer, function(i, value){
                        if(Array.isArray(value)){
                            $.each(value, function(innerI, innerValue){
                                eachAnswerBox.find('input[name*="'+i+'"][value="'+innerValue+'"]').prop('checked', true);
                            } );
                        }
                        else{
                            eachAnswerBox.find('input[name*="'+i+'"][value="'+value+'"]').prop('checked', true);
                            eachAnswerBox.find('input[name*="'+i+'"][value="'+value+'"]').parents('.ays-survey-answer-label ').trigger("mouseover");
                            eachAnswerBox.find('input[name*="'+i+'"][value="'+value+'"]').parents('.ays-survey-answer-label ').trigger("click");

                            eachAnswerBox.find('input.ays-survey-range-type-input[name*="'+i+'"][type="range"]').val(value);
                            eachAnswerBox.find('input.ays-survey-range-type-input[name*="'+i+'"][type="range"]').trigger('change');
                            eachAnswerBox.find('input.ays-survey-range-type-input[name*="'+i+'"][type="range"]').trigger('input');
                        }
                    } );
                }

                if(userExplanation){                    
                    var userExplanationBox = eachQuestion.find('.ays-survey-user-explanation');
                    if(userExplanationBox.length > 0){
                        var userExplanationInput = userExplanationBox.find('textarea[name*="ays-survey-user-explanation"]');
                        if(userExplanationInput.length > 0){
                            userExplanationInput.val(userExplanation);
                        }
                    }
                }


            }
          resolve();
        });
        
        return rPromise;
    }


    
    /**
     * @return {string}
     */
    function AddZero(num) {
        return (num >= 0 && num < 10) ? "0" + num : num + "";
    }

    /**
     * @return {string}
     */
    function aysEscapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>\"']/g, function(m) { return map[m]; });
    }

    function validatePhoneNumber(input) {
        var phoneno = /^[+ 0-9-]+$/;
        if (input.value.match(phoneno)) {
            return true;
        } else {
            return false;
        }
    }

    function setBubble(range, bubble) {
        var val = range.value;
        var min = range.min ? range.min : 0;
        var max = range.max ? range.max : 100;
        var newVal = Number( ( ( val - min ) * 100 ) / ( max - min ) );
        bubble.innerHTML = val;

        // Sorta magic numbers based on size of the native UI thumb
        bubble.style.left = 'calc( ' +  newVal + '% + ' + ( 9 - newVal * 0.18 ) + 'px )';
    }

    function surveyGetVoices() {
        var voices = speechSynthesis.getVoices();
        if(!voices.length){
          var utterance = new SpeechSynthesisUtterance("");
          speechSynthesis.speak(utterance);
          voices = speechSynthesis.getVoices();          
        }
        return voices;
    }

    $.fn.serializeFormJSON = function () {
        var o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    // Warn if overriding existing method
    if(Array.prototype.equals)
        console.warn("Overriding existing Array.prototype.equals. Possible causes: New API defines the method, there's a framework conflict or you've got double inclusions in your code.");

    // attach the .equals method to Array's prototype to call it on any array
    Array.prototype.equals = function (array) {
        // if the other array is a falsy value, return
        if (!array)
            return false;

        // compare lengths - can save a lot of time
        if (this.length != array.length)
            return false;

        for (var i = 0, l=this.length; i < l; i++) {
            // Check if we have nested arrays
            if (this[i] instanceof Array && array[i] instanceof Array) {
                // recurse into the nested arrays
                if (!this[i].equals(array[i]))
                    return false;
            }
            else if (this[i] != array[i]) {
                // Warning - two different object instances will never be equal: {x:20} != {x:20}
                return false;
            }
        }
        return true;
    }
    // Hide method from for-in loops
    Object.defineProperty(Array.prototype, "equals", {enumerable: false});


    $.fn.AysSurveyMaker = function(options) {
        return this.each(function() {
            if (!$.data(this, 'AysSurveyMaker')) {
                $.data(this, 'AysSurveyMaker', new AysSurveyPlugin(this, options));
            } else {
                try {
                    $(this).data('AysSurveyMaker').init();
                } catch (err) {
                    console.error('AysSurveyMaker has not initiated properly');
                }
            }
        });
    };

})(jQuery);
