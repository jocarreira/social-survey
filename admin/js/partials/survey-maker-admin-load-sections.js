(function($) {
    'use strict';

    function AysSurveyMakerLoadSections(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays-survey-';
        this.dbOptions = undefined;

        this.question      = 'question_id';
        this.answer        = 'answer';

        this.ajaxAction    = 'ays_survey_admin_ajax';

        this.answerDragHandle = {
            handle: '.ays-survey-answer-dlg-dragHandle',
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'clone',
            tolerance: "pointer",
            helper: "clone",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var answers = sortableContainer.find('.ays-survey-answer-row');
                answers.each(function(i){
                    $(this).find('.ays-survey-answer-ordering').val(i+1);
                });
            }
        }

        this.init();

        return this;
    }

    AysSurveyMakerLoadSections.prototype.init = function() {
        var _this = this;

        _this.setEvents();
    };

    AysSurveyMakerLoadSections.prototype.setEvents = function(e){
        var _this = this;

        $(document).ready(function () {
            $(document).find('.ays-button').attr('disabled', 'disabled');
            $(document).find('.ays-button').prop('disabled', true);

            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');

            var progressBar = $(`<div class="ays-survey-questions-progress-bar">
            <svg class="ays-survey-questions-progress-svg">
              <circle class="ays-survey-questions-progress-bg" cx="50%" cy="50%" r="40%" />
              <circle class="ays-survey-questions-progress-fill" cx="50%" cy="50%" r="40%" />
            </svg>
            <span class="ays-survey-questions-progress-text">0%</span>
          </div>`);

            $(document).find('.aysFormeditorViewFatRoot').append( progressBar );
            setTimeout(function () {
                $(document).find( '.' + _this.htmlClassPrefix +'questions-loading-progress-bar-fill' ).css('padding-right', '10px');
            }, 1);

            _this.loadingfullPercent = 0;

            sections.each(function(){
                var surveyId = _this.$el.data('id');
                var section = $(this);
                var sectionId = section.data('id');
                var questionIds = section.attr('data-questions-ids');

                if( questionIds.indexOf(',') ) {
                    questionIds = questionIds.split(',');
                }
                
                _this.loadingfullPercent += questionIds.length;
            });

            _this.loadingfillPercent = 0;
            _this.loadingStartTime = Date.now();

            _this.loadSectionsRecursively( sections, 0, false );
        });
    }

    AysSurveyMakerLoadSections.prototype.loadSectionsRecursively = async function( sections, index, last ) {
        var _this = this;

        if( last ){
            _this.loadingfillPercent = 0;

            $(document).find('.ays-button').removeAttr('disabled');
            $(document).find('.ays-button').prop('disabled', false);
            $(document).find('.ays_survey_loader_box').addClass('display_none');

            return;
        }
        var surveyId = _this.$el.data('id');
        var section = sections.eq( index ) ;
        var sectionId = section.data('id');
        var questionIds = section.attr('data-questions-ids');
        
        if( questionIds.indexOf(',') ) {
            questionIds = questionIds.split(',');
        }

        var questionsQueueLength = Math.ceil( questionIds.length / 10 );
        for( var i=0; i < questionsQueueLength; i++ ){
            var start = i * 10;
            var questionsPool = questionIds.slice( start, start + 10 );

            await _this.loadQuestion( section, questionsPool, {
                surveyId: surveyId,
                sectionId: sectionId,
            } );
        }

        if( sections.length - 1 === index ){
            last = true;
            document.querySelector(".ays-survey-questions-progress-fill").style.strokeDashoffset = (250 - 100 - 25)
        }

        return _this.loadSectionsRecursively( sections, ++index, last );
    }

    AysSurveyMakerLoadSections.prototype.loadQuestion = async function( section, questions, args ) {
        var _this = this;
        var data = {
            action: _this.ajaxAction,
            function: 'get_survey_question_html',
            id: args.surveyId,
            question_ids: questions,
            section_id: args.sectionId
        };
        await $.ajax({
            url: SurveyMakerAdmin.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: data,
            async: true,
            success: function(response){
                _this.loadingfillPercent += questions.length;
                if( _this.loadingfillPercent === _this.loadingfullPercent ){
                    setTimeout(function () {
                    _this.initConditions();
                        $(document).find( '.ays-survey-questions-progress-bar' ).remove();
                    }, 1000);
                }
                var percent = ((_this.loadingfillPercent / _this.loadingfullPercent) * 100);
                percent = parseInt(percent);
                
                document.querySelector(".ays-survey-questions-progress-fill").style.strokeDashoffset = (250 - percent - 10)
                $(document).find('.ays-survey-questions-progress-text').text(percent + '%');

                if( response.status === true ) {
                    if( ! SurveyMakerCondtionData.sections[ args.sectionId ] ) {
                        SurveyMakerCondtionData.sections[ args.sectionId ] = {};
                    }

                    SurveyMakerCondtionData.sections[ args.sectionId ] = Object.assign( SurveyMakerCondtionData.sections[ args.sectionId ], response.conditions[ args.sectionId ] );
                    SurveyMakerCondtionData.questions = Object.assign( SurveyMakerCondtionData.questions, response.questions );

                    var question = _this.initQuestion( $(response.questionHtml) );
                    section.find('.ays-survey-section-questions-loader').hide();
                    section.find('.ays-survey-section-questions').removeClass('ays-survey-section-questions-before-load');
                    section.find('.ays-survey-section-body').removeClass('ays-survey-section-body-before-load');
                    section.find('.ays-survey-section-questions').append( question );
                    var wpEditorOprions = {
                        tinymce: {
                          plugins : 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
                          toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
                          toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                          height : "100px"
                        },
                        quicktags: {buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'},
                        mediaButtons: true,
                    }

                    var htmlQTypeBox = section.find('.ays-survey-question-types_html');
                    if(htmlQTypeBox.length > 0){
                        htmlQTypeBox.each(function(e, elem){
                            wp.editor.initialize($(elem).find('textarea.ays-survey-html-question-type-for-js').attr('id'),  wpEditorOprions);
                        });
                    }
                }

            }
        });
    }

    AysSurveyMakerLoadSections.prototype.initQuestion = function( question ) {
        var _this = this;

        question.find('[data-toggle="popover"]').each(function(){
            $(this).popover();
        });

        // Answers ordering jQuery UI
        question.find('.ays-survey-answers-conteiner').sortable(_this.answerDragHandle);


        var aysSurveyTextArea = question.find('textarea.ays-survey-question-input-textarea');
        setTimeout( function(){
            autosize(aysSurveyTextArea);
        }, 100 );

        var aysSurveyQuestionDescriptionTextArea = question.find('textarea.ays-survey-question-description-input-textarea');
        setTimeout( function(){
            autosize(aysSurveyQuestionDescriptionTextArea);
        }, 100 );

        question.find('.ays-survey-question-more-actions').removeClass('display_none');
        question.find('select.ays-survey-question-type').aysDropdown();
        question.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');

        return question;
    }

    AysSurveyMakerLoadSections.prototype.initConditions = function() {
        var _this = this;
        var container = $(document).find('.ays-survey-condition-containers-info');
        container.find('.ays-survey-condition-containers-added').each(function () {
            _this.initCondition( $(this) );
        });
    }

    AysSurveyMakerLoadSections.prototype.initCondition = function( condition ) {
        var _this = this;

        var questions = SurveyMakerCondtionData.questions;
        condition.find('.ays-survey-condition-select-question-box').each(function (){
            var options = '<option value="0">Select</option>';
            var answerOptions = '<option value="0">Select</option>';
            var selected = "";
            var qid = $(this).data('questionId');

            for( var question_id in questions ){
                if( questions[ question_id ].type &&
                    questions[ question_id ].type !== "matrix_scale" &&
                    questions[ question_id ].type !== "upload" &&
                    questions[ question_id ].type !== "star_list" ){
                    selected = qid && qid === parseInt( question_id ) ? "selected" : "";
                    options += "<option value='" + question_id + "' data-type='" + questions[ question_id ].type + "' data-question-id='" + question_id + "' " + selected + ">" + questions[ question_id ].question + "</option>";
                }
            }
            
            var aid = $(this).find('.ays-survey-condition-select-question-answers').data('answer');
            if( questions[ qid ] ){
                if( questions[ qid ].type &&
                    questions[ qid ].type !== "linear_scale" &&
                    questions[ qid ].type !== "star" ) {
                        
                    
                    if (questions[qid].answers) {
                        var answers = questions[qid].answers;

                        for (var answer_key in answers) {
                            var cond_answer_id = answers[answer_key].id && answers[answer_key].id !== "" ? parseInt(answers[answer_key].id) : "";
                            var answer_selected = aid === cond_answer_id ? "selected" : "";
                            var cond_answer_title = answers[answer_key]['answer'] && answers[answer_key]['answer'] !== "" ? answers[answer_key]['answer'] : "";
                            answerOptions += "<option value=" + cond_answer_id + " " + answer_selected + ">" + cond_answer_title + "</option>";
                        }
                    }
                }else{
                    var loop_length = 0;
                    var loop_start = 1;
                    if( questions[ qid ].type === "linear_scale" ) {
                        loop_length = questions[qid].options && questions[qid].options.star_scale_length ? parseInt(questions[qid].options.star_scale_length) : 5;
                    }else if( questions[ qid ].type === "star" ) {
                        loop_length = questions[qid].options && questions[qid].options.scale_length ? parseInt(questions[qid].options.scale_length) : 5;
                    }

                    for ( var i = loop_start; i <= loop_length; i++ ){
                        var answer_selected = aid === i ? "selected" : "";
                        answerOptions += "<option value=" + i + " " + answer_selected + ">" + i + "</option>";
                    }
                }
            }

            $(this).find('select.ays-survey-condition-select-question').html( options );
            $(this).find('select.ays-survey-condition-select-question-with-answers').html( answerOptions );
        });
    }

    $.fn.AysSurveyLoadSections = function(options) {
        return this.each(function() {
            if (!$.data(this, 'AysSurveyCondition')) {
                $.data(this, 'AysSurveyCondition', new AysSurveyMakerLoadSections(this, options));
            } else {
                try {
                    $(this).data('AysSurveyCondition').init();
                } catch (err) {
                    console.error('AysSurveyCondition has not initiated properly');
                }
            }
        });
    };

    $(document).find('#ays-survey-form').AysSurveyLoadSections();
})(jQuery);