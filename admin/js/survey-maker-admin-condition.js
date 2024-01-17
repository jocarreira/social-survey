(function($) {
    'use strict';

    function AysSurveyMakerCondition(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays-survey-';
        this.dbOptions = undefined;
        this.questionData = SurveyMakerCondtionData;

        this.htmlPrefix   = 'ays_';

        this.addSection    = this.htmlPrefix+'condition_add';
        this.addQuestion   = 'condition_question_add';
        this.question      = 'question_id';
        this.answer        = 'answer';
        this.equality      = 'equality';
        this.plusCondition = 'plus_condition';

        this.init();

        return this;
    }

    AysSurveyMakerCondition.prototype.init = function() {
        var _this = this;

        _this.popupId = _this.$el.data('id');
        
        if( typeof window.aysSurveyPopupsOptions != 'undefined' ){
            _this.dbOptions = JSON.parse( atob( window.aysSurveyPopupsOptions[ _this.popupId ] ) );
        }
        
        _this.setEvents();
    };

    AysSurveyMakerCondition.prototype.setEvents = function(e){
        var _this = this;
        // Conditions tab
        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"action-add-condition,."+_this.htmlClassPrefix+"condition-containers-editable", function(){
            _this.addCondition();
        }); 

        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"action-add-sub-condition", function(){
            _this.addSubCondition($(this));            
        }); 

        _this.$el.on("change"  , "."+_this.htmlClassPrefix+"condition-select-question", function(){
            _this.selectQuestion($(this));            
        }); 

        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"delete-question-condition", function(){
            _this.deleteQuestion($(this));
        }); 

        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"action-delete-all-condition", function(){
            _this.deleteAllConditions($(this));
        }); 

        _this.$el.on("click"  , "."+_this.htmlClassPrefix+"condition-containers-conditions-tabs", function(){
            _this.conditionTabChangeing($(this));
        });

        _this.$el.on('click', "."+_this.htmlClassPrefix+"add-email-message-files", function (e) {
            openMediaUploaderForConditions(e, $(this));
        });

        _this.$el.on('click', "."+_this.htmlClassPrefix+"email-message-files-wrapper", function () {
            _this.deleteCurrentEmailFile($(this));
        });
    }

    AysSurveyMakerCondition.prototype.addCondition = function (element){
        var selectQuestions = "";
        var conditionBoxCloning = this.$el.find("."+this.htmlClassPrefix+"condition-containers-to-clone");
        this.$el.find(".ays-survey-condition-containers-editable").hide();

        var addedBox = this.$el.find("."+this.htmlClassPrefix+"condition-containers-added");
        var addedBoxId = this.$el.find("."+this.htmlClassPrefix+"condition-containers-added:last-child");
        var dataId = addedBox.length >= 1 ? addedBoxId.data("conditionId") + 1 : 1;
        // Sub condition Delete button
        var deleteImageUrl = this.questionData.removeCondition;
        var deleteSubButton = '<div class="ays-survey-delete-question-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl-small ays-survey-delete-button-small display_none" data-placement="auto">';
                deleteSubButton += '<div class="ays-question-img-icon-content">';
                    deleteSubButton += '<div class="ays-question-img-icon-content-div">';
                        deleteSubButton += '<div class="ays-survey-icons ays-survey-icons-small">';
                            deleteSubButton += '<img src="'+deleteImageUrl+'">';
                        deleteSubButton += '</div>';
                    deleteSubButton += '</div>';
                deleteSubButton += '</div>';
            deleteSubButton += '</div>';
        //
        // Add Element
        var clonedElement = conditionBoxCloning.clone( true , false );
        var sections = this.questionData.sections;
        // Questions and answers start
        selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-select-question-box' data-question-id='1' data-question-name='"+this.question+"'>";
            selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-selects'>";
                // Questions box
                selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-select-question-box-questions-if'><span>"+this.questionData.translations.if+"</span></div>";
                selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-select-question-box-questions'>";
                    selectQuestions += "<select class='"+this.htmlClassPrefix+"condition-select-question' name='"+this.addSection+"["+dataId+"]["+this.addQuestion+"][1]["+this.question+"]'>";
                            selectQuestions += "<option value='0'>"+ SurveyMakerCondtionData.translations._select_ +"</option>";

                    for(var section in sections){
                        var questions = sections[section];
                        for(var question in questions){
                            var currentQuestion = questions[question];
                            var currentQuestionType = currentQuestion.type ? currentQuestion.type : "";
                            if(currentQuestionType && currentQuestionType != 'matrix_scale' && currentQuestionType != 'upload' && currentQuestionType != 'star_list' && currentQuestionType != 'slider_list' && currentQuestionType != 'matrix_scale_checkbox'){
                                selectQuestions += "<option value="+currentQuestion.id+" data-type='"+currentQuestion.type+"' data-question-id='"+currentQuestion.id+"'>"+currentQuestion.question+"</option>";
                            }
                        }
                    }
                    selectQuestions += "</select>";
                    selectQuestions += "<input type='hidden' class='"+this.htmlClassPrefix+"condition-select-question-type-hidden'>";
                selectQuestions += "</div>";
                // Answers box
                selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-select-question-answers'></div>";
                // Delete buttons box
                selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-delete-currnet'>";
                    selectQuestions += deleteSubButton;
                selectQuestions += '</div>';
            selectQuestions += "</div>";
            // Condtions(files) box
            selectQuestions += "<div class='"+this.htmlClassPrefix+"condition-select-question-condition'></div>";
        selectQuestions += "</div>";
        // Questions and answers end

        clonedElement.attr("class" , this.htmlClassPrefix+"condition-containers-added "+this.htmlClassPrefix+"condition-containers-new-added");
        clonedElement.attr("data-condition-id" , dataId);
        clonedElement.attr("data-condition-name" , this.addSection);
        clonedElement.find("."+this.htmlClassPrefix+"condition-containers-list-main").append(selectQuestions);
        // Page message
        var pageMessageAttrName = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "page" , "message", null ,null);
        clonedElement.find("."+this.htmlClassPrefix+"page-message-editor").attr("name" ,pageMessageAttrName);
        clonedElement.find("."+this.htmlClassPrefix+"page-message-editor").attr("id" , this.htmlClassPrefix+"page-message-editor-current-"+dataId);

        // email message
            // Content
            var emailMessageAttrName = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "email" , "message", null ,null);
            clonedElement.find("."+this.htmlClassPrefix+"email-message-editor").attr("name" ,emailMessageAttrName);
            clonedElement.find("."+this.htmlClassPrefix+"email-message-editor").attr("id" ,this.htmlClassPrefix+"email-message-editor-current-"+dataId);
            // Attachment
            var emailAttachAttrFile = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "email" , "file", null ,null);
            var emailAttrFileName   = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "email" , "file_name", null ,null);
            var emailAttrFileId     = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "email" , "file_id", null ,null);
            clonedElement.find("."+this.htmlClassPrefix+"email-message-editor-file").attr("name" ,emailAttachAttrFile);
            clonedElement.find("."+this.htmlClassPrefix+"email-message-editor-file-name").attr("name" ,emailAttrFileName);
            clonedElement.find("."+this.htmlClassPrefix+"email-message-editor-file-id").attr("name" ,emailAttrFileId);

        // redirect message
            // delay
            var redirectDelayAttrName = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "redirect" , "delay", null ,null);
            clonedElement.find("."+this.htmlClassPrefix+"redirect-message-delay").attr("name" ,redirectDelayAttrName);
            clonedElement.find("."+this.htmlClassPrefix+"redirect-message-delay").attr("id" ,this.htmlClassPrefix+"redirect-delay-current-"+dataId);
            clonedElement.find("."+this.htmlClassPrefix+"redirect-message-delay-label").attr("for" ,this.htmlClassPrefix+"redirect-delay-current-"+dataId);
            // url
            var redirectUrlAttrName   = this.newMessageAttrName(  this.addSection ,  dataId , "messages" , "redirect" , "url", null ,null);
            clonedElement.find("."+this.htmlClassPrefix+"redirect-message-url").attr("name" ,redirectUrlAttrName);
            clonedElement.find("."+this.htmlClassPrefix+"redirect-message-url").attr("id" ,this.htmlClassPrefix+"redirect-url-current-"+dataId);
            clonedElement.find("."+this.htmlClassPrefix+"redirect-message-url-label").attr("for" ,this.htmlClassPrefix+"redirect-url-current-"+dataId);

        
        this.$el.find("."+this.htmlClassPrefix+"condition-containers-info").append(clonedElement);
        var latestEl = this.$el.find("."+this.htmlClassPrefix+"condition-containers-added:last-child");
        if(latestEl.length > 0){
            this.goToTop( latestEl );
        }
        var wpEditorOprions = {
            tinymce: {
              wpautop: true,
              plugins : 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
              toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
              toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
              height : "134px"
            },
            quicktags: {buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'},
            mediaButtons: true,
        }
        wp.editor.initialize(this.htmlClassPrefix+"page-message-editor-current-"+dataId,  wpEditorOprions);
        wp.editor.initialize(this.htmlClassPrefix+"email-message-editor-current-"+dataId,  wpEditorOprions);
    }

    AysSurveyMakerCondition.prototype.addSubCondition = function (element){
        var $this = element;
        var $thisParent = $this.parents("."+this.htmlClassPrefix+"condition-containers-added");
        var elementToCloneInto = $thisParent.find("."+this.htmlClassPrefix+"condition-containers-list-main");
        var questionBoxes = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-box");

        var thisQuestionBox = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-box:last-child");
        var elementToCloneIntoLength = questionBoxes.length;
        if(elementToCloneIntoLength > 0){
            elementToCloneInto.find(".appsMaterialWizButtonPapericonbuttonEl").removeClass("display_none");
        }
        // Create new name
        var conditionBoxId = $thisParent.attr("data-condition-id");
        var conditionBoxName = $thisParent.attr("data-condition-name");
        var questionDataId = elementToCloneIntoLength >= 1 ? parseInt(thisQuestionBox.attr("data-question-id")) + 1 : 1;
        var questionAttrName = this.newQuestionAttrName( conditionBoxName ,  conditionBoxId , questionDataId , this.question , null , null);
        var connectorAttrName = this.newQuestionAttrName( conditionBoxName ,  conditionBoxId ,(questionDataId - 1) , this.plusCondition , null , null);

        var toClone = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-box:first-child");
        var toAddCondition = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-box:last-child");
        var  conditionBoxSelect = "<select name='"+connectorAttrName+"'>";
                conditionBoxSelect += "<option value='and'>"+ SurveyMakerCondtionData.translations.and +"</option>";
                conditionBoxSelect += "<option value='or'>"+ SurveyMakerCondtionData.translations.or +"</option>";
            conditionBoxSelect += "</select>";
        toAddCondition.find("."+this.htmlClassPrefix+"condition-select-question-condition").html(conditionBoxSelect);
        toClone = toClone.clone( true , false );
        
        toClone.find("."+this.htmlClassPrefix+"condition-select-question-condition").html("");
        toClone.find("."+this.htmlClassPrefix+"condition-select-question").attr("name" , questionAttrName);
        toClone.find("."+this.htmlClassPrefix+"condition-select-question").find('option:selected').attr("selected" , false);
        toClone.attr("data-question-id" , questionDataId);
        toClone.addClass(this.htmlClassPrefix+"condition-select-question-box-new");
        elementToCloneInto.append(toClone);
        var latestEl = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-box:last-child");
        if(latestEl.length > 0){
            latestEl.find("."+this.htmlClassPrefix+"condition-select-question-answers").html("");
            this.goToTop( latestEl );
        }
    }

    AysSurveyMakerCondition.prototype.selectQuestion = function (element){
        var $this = element;
        var questionData = this.questionData.sections;
        var selectedType = $this.find('option:selected').data("type");
        var selectedQuestionId = $this.find('option:selected').data("questionId");
        var $thisMainParent = $this.parents("."+this.htmlClassPrefix+"condition-containers-added");
        var $thisParent = $this.parents("."+this.htmlClassPrefix+"condition-select-question-box");
        var questionBoxForAnswers = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-answers");
        var answerContent = "";

        // Create new name answer
        var conditionBoxId = $thisMainParent.attr("data-condition-id");
        var conditionBoxName = $thisMainParent.attr("data-condition-name");
        var questionDataId = $thisParent.attr("data-question-id");
        var answerAttrId = this.newQuestionAttrName( conditionBoxName ,  conditionBoxId , questionDataId , this.answer , null , null);
        var questionAttrType = this.newQuestionAttrName( conditionBoxName ,  conditionBoxId , questionDataId , "type" , null , null);
        // Create new name equality
        var equalityAttrName = this.newQuestionAttrName( conditionBoxName ,  conditionBoxId , questionDataId , this.equality , null , null);
        
        var hiddenInput = $thisParent.find("."+this.htmlClassPrefix+"condition-select-question-type-hidden");
        hiddenInput.val(selectedType);
        hiddenInput.attr("name" , questionAttrType);

        switch (selectedType){
            case "radio":
            case "checkbox":
            case "select":
            case "yesorno":
                answerContent += "<select class='"+this.htmlClassPrefix+"condition-select-question-with-answers' name='"+answerAttrId+"'>";
                answerContent += "<option value='0'>"+ SurveyMakerCondtionData.translations._select_ +"</option>";
                for(var question in questionData){
                    var questions = questionData[question];
                    var answers = typeof questions[selectedQuestionId] != "undefined" ? questions[selectedQuestionId].answers : [];
                    if(answers.length > 0){
                        for(var answerKey in answers){
                            answerContent += "<option value="+answers[answerKey].id+" >"+answers[answerKey].answer+"</option>";
                        }
                    }
                }
                answerContent += "</select>";
                questionBoxForAnswers.html(answerContent);
                break;
            case "text":
            case "short_text":
            case "date":
            case "time":
            case "date_time":
            case "name":
            case "email":
                answerContent += "<div class='"+this.htmlClassPrefix+"condition-for-other-types'>";
                    answerContent += "<div class='"+this.htmlClassPrefix+"condition-for-other-types-select'>";
                        answerContent += "<select class='"+this.htmlClassPrefix+"condition-for-text-types-select' name='"+equalityAttrName+"'>";
                            answerContent += "<option value='=='>"+SurveyMakerCondtionData.translations.identical+"</option>";
                            answerContent += "<option value='contains'>"+SurveyMakerCondtionData.translations.contains+"</option>";
                            answerContent += "<option value='not_contain'>"+SurveyMakerCondtionData.translations.notContain+"</option>";
                        answerContent += "</select>";
                    answerContent += "</div>";
                    answerContent += "<div class='"+this.htmlClassPrefix+"condition-for-other-types-text'>";
                        answerContent += "<input type='text' name='"+answerAttrId+"'>";
                    answerContent += "</div>";
                answerContent += "</div>";
                questionBoxForAnswers.html(answerContent);
                break;
            case "number":

            case "phone":
            case "range":
                answerContent += "<div class='"+this.htmlClassPrefix+"condition-for-other-types'>";
                    answerContent += "<div class='"+this.htmlClassPrefix+"condition-for-other-types-select'>";
                        answerContent += "<select class='"+this.htmlClassPrefix+"condition-for-number-types-select' name='"+equalityAttrName+"'>";
                            answerContent += "<option value='=='>"+SurveyMakerCondtionData.translations.equalTo+" (==)</option>";
                            answerContent += "<option value='!='>"+SurveyMakerCondtionData.translations.notEqualTo+" (!=)</option>";
                            answerContent += "<option value='>'>"+SurveyMakerCondtionData.translations.greaterThan+" (>)</option>";
                            answerContent += "<option value='>='>"+SurveyMakerCondtionData.translations.greaterThanOrEqual+" (>=)</option>";
                            answerContent += "<option value='<'>"+SurveyMakerCondtionData.translations.lessThan+" (<)</option>";
                            answerContent += "<option value='<='>"+SurveyMakerCondtionData.translations.lessThanOrEqual+" (<=)</option>";
                        answerContent += "</select>";
                    answerContent += "</div>";
                    answerContent += "<div class='"+this.htmlClassPrefix+"condition-for-other-types-text'>";
                        answerContent += "<input type='number' name='"+answerAttrId+"'>";
                    answerContent += "</div>";
                answerContent += "</div>";
                questionBoxForAnswers.html(answerContent);
                break;
            case "linear_scale":
                answerContent += "<select class='"+this.htmlClassPrefix+"condition-select-question-with-answers' name='"+answerAttrId+"'>";
                answerContent += "<option value='0'>"+ SurveyMakerCondtionData.translations._select_ +"</option>";
                for(var question in questionData){
                    var questions = questionData[question];
                    if( selectedQuestionId in questions ){
                        var scaleLength = typeof questions[selectedQuestionId] != "undefined" ? questions[selectedQuestionId].linear_length : 5;
                        for(var answerKey = 1; answerKey <= scaleLength; answerKey++){
                            answerContent += "<option value="+answerKey+" >"+answerKey+"</option>";
                        }
                        break;
                    }
                }
                answerContent += "</select>";
                questionBoxForAnswers.html(answerContent);
                break;
            case "star":
                answerContent += "<select class='"+this.htmlClassPrefix+"condition-select-question-with-answers' name='"+answerAttrId+"'>";
                answerContent += "<option value='0'>"+ SurveyMakerCondtionData.translations._select_ +"</option>";
                for(var question in questionData){
                    var questions = questionData[question];
                    if( selectedQuestionId in questions ){
                        var scaleLength = typeof questions[selectedQuestionId] != "undefined" ? questions[selectedQuestionId].star_length : 5;
                        for(var answerKey = 1; answerKey <= scaleLength; answerKey++){
                            answerContent += "<option value="+answerKey+">"+answerKey+"</option>";
                        }
                        break;
                    }
                }
                answerContent += "</select>";
                questionBoxForAnswers.html(answerContent);
                break;
            default: questionBoxForAnswers.html(answerContent);
            break;
        }
    }

    AysSurveyMakerCondition.prototype.deleteQuestion = function (element){
        var $this = element;
        var $thisMainParent = $this.parents("."+this.htmlClassPrefix+"condition-containers-list-main");
        var $thisParent = $this.parents("."+this.htmlClassPrefix+"condition-select-question-box");
        $thisParent.remove();
        var latestElement = $thisMainParent.find("."+this.htmlClassPrefix+"condition-select-question-box:last-child");
        latestElement.find("."+this.htmlClassPrefix+"condition-select-question-condition").html("");
        var elementToCloneIntoLength = $thisMainParent.find("."+this.htmlClassPrefix+"condition-select-question-box").length;
        if(elementToCloneIntoLength == 1){
            $thisMainParent.find(".appsMaterialWizButtonPapericonbuttonEl").addClass("display_none");
        }
    }

    AysSurveyMakerCondition.prototype.deleteAllConditions = function (element){
        var $this = element;
        var $thisMainParent = $this.parents("."+this.htmlClassPrefix+"condition-containers-info");
        var addedBoxes = $thisMainParent.find("."+this.htmlClassPrefix+"condition-containers-added");
        var deletableBox = $this.parents("."+this.htmlClassPrefix+"condition-containers-added");
        wp.editor.remove(this.htmlClassPrefix+"page-message-editor-current-"+deletableBox.data("conditionId"));
        wp.editor.remove(this.htmlClassPrefix+"email-message-editor-current-"+deletableBox.data("conditionId"));
        deletableBox.remove();
        if(addedBoxes.length == 1){
            $thisMainParent.find("."+this.htmlClassPrefix+"condition-containers-editable").show();
        }
    }

    AysSurveyMakerCondition.prototype.conditionTabChangeing  = function (element){
        var $this = element;
        
        var $thisParent = $this.parents("."+this.htmlClassPrefix+"condition-containers-conditions");
        var activeTab = $this.data('tabId');
        $thisParent.find("div"+"."+this.htmlClassPrefix+"condition-containers-conditions-tabs").each(function () {
            if ($(this).hasClass('nav-cond-tab-active')) {
                $(this).removeClass('nav-cond-tab-active');
            }
        });
        $this.addClass('nav-cond-tab-active');
        $thisParent.find("."+this.htmlClassPrefix+"condition-containers-conditions-contents").each(function () {
            $(this).css('display', 'none');
        });
        $thisParent.find(".cond-"+activeTab).css('display', 'block');       
    }

    AysSurveyMakerCondition.prototype.deleteCurrentEmailFile = function (element){
        var $this = element;
        var $thisParent = $this.parents("."+this.htmlClassPrefix+"email-message-files");
        $thisParent.find("."+this.htmlClassPrefix+"add-email-message-files").html(SurveyMakerCondtionData.translations.addFile);
        $thisParent.find("."+this.htmlClassPrefix+"email-message-files-content-text").html("");
        $thisParent.find("."+this.htmlClassPrefix+"email-message-editor-file-name").val("");
        $thisParent.find("."+this.htmlClassPrefix+"email-message-editor-file").val("");
        $thisParent.find("."+this.htmlClassPrefix+"email-message-editor-file-id").val("");
        $thisParent.find("."+this.htmlClassPrefix+"email-message-files-body").addClass("display_none_not_important");
        $this.addClass("display_none_not_important");
    }

    AysSurveyMakerCondition.prototype.goToTop = function( el ) {
        el.get(0).scrollIntoView({
            block: "center",
            behavior: "smooth"
        });
    }

    AysSurveyMakerCondition.prototype.newQuestionAttrName = function (conditionName, conditionId, questionId, field, field2 = null, field3 = null){
        var _this = this;
        if(field2 !== null){
            if( field3 !== null ){
                return conditionName + '['+ conditionId +']['+_this.addQuestion+']['+ questionId +']['+ field +']['+ field2 +']['+ field3 +']';
            }
            return conditionName + '['+ conditionId +']['+_this.addQuestion+']['+ questionId +']['+ field +']['+ field2 +']';
        }
        return conditionName + '['+ conditionId +']['+_this.addQuestion+']['+ questionId +']['+ field +']';
    }

    AysSurveyMakerCondition.prototype.newMessageAttrName = function (conditionName, conditionId, messageName, messageText, field1 = null, field2 = null, field3 = null){
        var _this = this;
        if(field1 !== null){
            if(field2 !== null){
                if( field3 !== null ){
                    return conditionName + '['+ conditionId +']['+messageName+']['+ messageText +']['+ field1 +']['+ field2 +']['+ field3 +']';
                }
                return conditionName + '['+ conditionId +']['+messageName+']['+ messageText +']['+ field1 +']['+ field2 +']';
            }
            return conditionName + '['+ conditionId +']['+messageName+']['+ messageText +']['+ field1 +']';
        }
        return conditionName + '['+ conditionId +']['+messageName+']['+ messageText +']';
    }

    $.fn.AysSurveyCondition = function(options) {
        return this.each(function() {
            if (!$.data(this, 'AysSurveyCondition')) {
                $.data(this, 'AysSurveyCondition', new AysSurveyMakerCondition(this, options));
            } else {
                try {
                    $(this).data('AysSurveyCondition').init();
                } catch (err) {
                    console.error('AysSurveyCondition has not initiated properly');
                }
            }
        });
    };
    
    $(document).find('#ays-survey-condition-container-main').AysSurveyCondition();
})(jQuery);