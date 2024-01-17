(function( $ ) {
    'use strict';
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
    
    // $.fn.aysModal = function(action){
    //     var $this = $(this);
    //     switch(action){
    //         case 'hide':
    //             $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
    //             setTimeout(function(){
    //                 $(document.body).removeClass('modal-open');
    //                 $(document).find('.ays-modal-backdrop').remove();
    //                 $this.hide();
    //             }, 250);
    //         break;
    //         case 'show': 
    //         default:
    //             $this.show();
    //             $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
    //             $(document).find('.modal-backdrop').remove();
    //             $(document.body).append('<div class="ays-modal-backdrop"></div>');
    //             $(document.body).addClass('modal-open');
    //         break;
    //     }
    // };

    $(document).find('.unread-result-badge.unread-result').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);

        var survey_id = $this.attr('data-id');
        var action = 'survey_maker_mark_requests_as_read';
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: {
                survey_id: survey_id,
                action: action,
            },
            success: function(res) {
                if (res.status) {
                    $this.removeClass('unread-result');
                    $this.parents('tr').find('td').css('font-weight', 'normal');

                    $('li.toplevel_page_survey-maker .apm-badge.badge-danger').each(function () {
                        var $unreadCounter = $(this);

                        if ($unreadCounter.text() != '') {
                            var counter = +$unreadCounter.text();
                            counter--;
                            $unreadCounter.text(counter);
                        }
                    });
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });


    $(document).find('.ays_survey_test_mail_btn').on('click', function(e) {
        var form = $(document).find('#ays-survey-form');
        var del_message = $(document).find('#ays_test_delivered_message');
        var loader = '<img src="'+ del_message.data('src') +'">';
        del_message.html(loader);
        del_message.show();
        var data = form.serializeFormJSON();
        var action = 'ays_survey_admin_ajax';
        data.action = action;
        data.function = 'ays_survey_send_testing_mail';
        
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.status) {
                    if(response.mail){
                        del_message.css("color", "green");
                    }else{
                        del_message.css("color", "red");
                    }
                    del_message.html(response.message);
                }else{
                    del_message.html(response.message);
                    del_message.css("color", "red");
                }
                setTimeout(function(){
                    del_message.fadeOut(500);
                }, 1500);
            }
        });
    });

    $(document).find('.ays_export_surveys').on('click', function(){
        var form = $(this).parents('form');
        var data = form.serializeFormJSON();
        data.action = 'ays_survey_admin_ajax';
        data.function = 'ays_surveys_export_json';

        $.ajax({
            url: ajaxurl,
            dataType: 'json',
            data: data,
            method: 'post',
            success: function(response){
                if (response.status) {
                    var text = JSON.stringify(response.data);
                    var data = new Blob([text], {type: "application/json"});
                    var fileUrl = window.URL.createObjectURL(data);
                    $('#downloadFile').attr({
                        'href': fileUrl,
                        'download': response.title+".json",
                    })[0].click();
                    window.URL.revokeObjectURL(fileUrl);
                }
            }
        });
    });

    // ===============================================================
    // ======================      Aro      ==========================
    // =========================  Start   ============================

    // Export Answers filters
    $(document).find('.ays-survey-export-answers-filters').on('click', function(e) {
        e.preventDefault();
        var $this = $('#ays-survey-export-answers-filters');

        $this.find('div.ays-survey-preloader').css('display', 'flex');
        $this.aysModal('show');

        var action = 'ays_survey_admin_ajax';
        var afunction = 'ays_survey_show_filters';
        var survey_id = $this.find('#survey_id-answers-filter').val();
        
        $.post({
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                survey_id: survey_id,
                flag : true
            },
            success: function(res) {
                $this.find('div.ays-survey-preloader').css('display', '');
                var newUserSelect = "";
                for (var u in res.users) {
                    newUserSelect += '<option value="'+ res.users[u].user_id +'">'+ res.users[u].display_name +'</option>';
                }

                $this.find('#survey_user_id-answers-filter').html(newUserSelect);
                var userSel  = $this.find('#survey_user_id-answers-filter');
                var userSel2 = userSel.select2({
                    dropdownParent: userSel.parent(),
                    closeOnSelect: true,
                    allowClear: false
                });

                $(document).on('click', '.select2-selection__choice__remove', function(){
                    userSel2.select2("close");
                });

                $this.find(".export_results_count span").text(res.count);
                $this.find('.ays-modal-body').show();
            },
            error: function() {
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.somethingWentWrong +"</h6>"
                }).then(function(res){
                    $this.find('div.ays-survey-preloader').css('display', 'none');
                    $this.aysModal('hide');
                });
            }
        });
    });

    $(document).find('#start-date-answers-filter, #start-date-filter').on('change', function(e) {
        var _this = $(this);

        if ( _this.attr('id') == "start-date-filter" ) {
            $(document).find('#ays_export_filter').submit();
        } else {
            $(document).find('#ays_export_answers_filter').submit();
        }

        e.preventDefault();
    });

    $(document).find('#end-date-answers-filter , #end-date-filter').on('change', function(e) {
        var _this = $(this);

        if ( _this.attr('id') == "end-date-filter" ) {
            $(document).find('#ays_export_filter').submit();
        } else {
            $(document).find('#ays_export_answers_filter').submit();
        }

        e.preventDefault();
    });

    $(document).on('change.select2', '#survey_user_id-answers-filter, #user_id-filter, #survey_id-filter', function(e) {
        var _this = $(this);

        if ( _this.attr('id') == "user_id-filter" || _this.attr('id') == "survey_id-filter" ) {
            $(document).find('#ays_export_filter').submit();
        } else {
            $(document).find('#ays_export_answers_filter').submit();
        }

        e.preventDefault();
    });

    $(document).find('#ays_export_answers_filter').on('submit', function(e) {
        e.preventDefault();
        var $this     = $(document).find('#ays-survey-export-answers-filters');
        var action    = 'ays_survey_admin_ajax';
        var afunction = 'ays_survey_results_export_filter';
        var user_id   = $this.find('#survey_user_id-answers-filter').val();
        var survey_id = $this.find('#survey_id-answers-filter').val();
        var date_from = $this.find('#start-date-answers-filter').val();
        var date_to   = $this.find('#end-date-answers-filter').val();
        $this.find('div.ays-survey-preloader').css('display', 'flex');
        var data = {};

        data.action = action;
        data.function = afunction;
        data.user_id = user_id && user_id.length > 0 ? user_id : null;
        data.survey_id = survey_id ? survey_id : null;
        data.date_from = date_from ? date_from : null;
        data.date_to = date_to ? date_to : null;
        data.flag = true;

        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                $this.find('div.ays-survey-preloader').css('display', 'none');
                $this.find(".export_results_count span").text(response.qanak);
            }
        });
    });

    $(document).on('click', '.ays_surveyid_clear', function(){
        var surveySel2 = $(document).find('#survey_id-answers-filter, #survey_id-filter');
        surveySel2.val(null).trigger('change');
        return false;
    });
    
    $(document).on('click', '.ays_userid_clear', function(){
        var userSel2 = $(document).find('#survey_user_id-answers-filter, #user_id-filter');
        userSel2.val(null).trigger('change');
        return false;
    });

    // Export Answers XLSX
    $(document).find('.ays-survey-export-anwers-action').on('click', function(e) {
        e.preventDefault();
        var _this = $(this);
        var $this = $(document).find('#ays-survey-export-answers-filters');

        _this.prop('disabled',true);
        _this.addClass('disabled');

        var action     = 'ays_survey_admin_ajax';
        var afunction  = 'ays_survey_answers_statistics_export';
        var type       = _this.data('type');
        var survey_id  = _this.attr('survey-id');
        var user_id    = $(document).find('#survey_user_id-answers-filter').val();
        var date_from  = $(document).find('#start-date-answers-filter').val();
        var date_to    = $(document).find('#end-date-answers-filter').val();
        $this.find('div.ays-survey-preloader').css('display', 'flex');
        $.post({
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                type: type,
                user_id: user_id,
                survey_id: survey_id,
                date_from: date_from,
                date_to: date_to
            },
            success: function (response) {
                if (response.status) {
                    var options = {
                        fileName: "survey_answers_export",
                        header: true
                    };
                    var tableData = [{
                        "sheetName": "Survey questions",
                        "data": response.data
                    }];
                    Jhxlsx.export(tableData, options);
                    $this.find('div.ays-survey-preloader').css('display', 'none');
                }else{
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h4>"+ SurveyMakerAdmin.dataDeleted +"</h4>"
                    }).then(function(response) {
                        $this.find('div.ays-survey-preloader').css('display', 'none');
                    });
                }
                _this.prop('disabled',false);
                _this.removeClass('disabled');
            }
        });
    });

    // Export Answers CSV
    $(document).find('.ays-survey-export-anwers-action-csv').on('click', function(e) {
        e.preventDefault();
        var _this = $(this);
        var $this = $(document).find('#ays-survey-export-answers-filters');

        _this.prop('disabled',true);
        _this.addClass('disabled');

        var action     = 'ays_survey_admin_ajax';
        var afunction  = 'ays_survey_answers_statistics_export_csv';
        var type       = _this.data('type');
        var survey_id  = _this.attr('survey-id');
        var user_id    = $(document).find('#survey_user_id-answers-filter').val();
        var date_from  = $(document).find('#start-date-answers-filter').val();
        var date_to    = $(document).find('#end-date-answers-filter').val();
        $this.find('div.ays-survey-preloader').css('display', 'flex');
        $.post({
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                type: type,
                user_id: user_id,
                survey_id: survey_id,
                date_from: date_from,
                date_to: date_to
            },
            success: function (response) {
                if (response.status) {
                    var csvOptions = {
                        fieldSeparator: ",",                  
                        fileName: 'survey_submissions_export.csv'
                      };

                      response.headersData.forEach(row => {
                        row.forEach((col, index) => {
                          if (col.includes(",") || col.includes("\n")) {
                            col =  col.replace(/\n/g, '');
                            row[index] = `"${col}"`;
                          }
                        });
                      });

                      response.data.forEach(row => {
                        row.forEach((col, index) => {
                          if (col.includes(",") || col.includes("\n")) {
                            col =  col.replace(/\n/g, '');
                            row[index] = `"${col}"`;
                          }
                        });
                      });
                      
                    var exportCSV = new CSVExport(response.data, response.headersData, csvOptions);
                    $this.find('div.ays-survey-preloader').css('display', 'none');
                }else{
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h4>"+ SurveyMakerAdmin.dataDeleted +"</h4>"
                    }).then(function(response) {
                        $this.find('div.ays-survey-preloader').css('display', 'none');
                    });
                }
                _this.prop('disabled',false);
                _this.removeClass('disabled');
            }
        });
    });

    // Export single submission results
    $(document).on('click','.ays-survey-single-submission-results-export', function(e) {
        var $this = $(this);

        $this.prop('disabled',true);
        $this.addClass('disabled');

        var type          = $this.attr('data-type');
        var submission_id = $this.attr('data-result');
        var action        = 'ays_survey_admin_ajax';
        var afunction     = 'ays_survey_single_submission_results_export';
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                submission_id: submission_id,
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
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                    })
                }
                $this.prop('disabled',false);
                $this.removeClass('disabled');
            },
            error: function() {
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                }).then(function(res){
                    $this.prop('disabled',false);
                    $this.removeClass('disabled');
                });
            }
        });
        e.preventDefault();
    });

    // Export single submission results
    $(document).on('click','.ays-survey-single-submission-pdf-export', function(e) {
        var $this = $(this);

        $this.prop('disabled',true);
        $this.addClass('disabled');

        var submission_id = $this.attr('data-result');
        var action        = 'ays_survey_admin_ajax';
        var afunction     = 'ays_survey_single_submission_pdf_export';
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                submission_id: submission_id
            },
            success: function (response) {
                if (response.status) {
                    $this.parent().find('#downloadFile').attr({
                        'href': response.fileUrl,
                        'download': response.fileName,
                    })[0].click();
                    window.URL.revokeObjectURL(response.fileUrl);
                }else{
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                    })
                }
                $this.prop('disabled',false);
                $this.removeClass('disabled');
            },
            error: function() {
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                }).then(function(res){
                    $this.prop('disabled',false);
                    $this.removeClass('disabled');
                });
            }
        });
        e.preventDefault();
    });

    // Submission export filters | Start
    $(document).find('#ays_export_filter').on('submit', function(e) {
        e.preventDefault();
        var $this     = $('#export-filters');
        var action    = 'ays_survey_admin_ajax';
        var afunction = 'ays_survey_results_export_filter';

        var user_id   = $this.find('#user_id-filter').val();
        var survey_id = $this.find('#survey_id-filter').val();
        var date_from = $this.find('#start-date-filter').val();
        var date_to   = $this.find('#end-date-filter').val();

        var data = {};

        data.action = action;
        data.function = afunction;
        data.user_id = user_id && user_id.length > 0 ? user_id : null;
        data.survey_id = survey_id ? survey_id : null;
        data.date_from = date_from ? date_from : null;
        data.date_to = date_to ? date_to : null;
        data.flag = true;

        $this.find('div.ays-survey-preloader').css('display', 'flex');

        var action    = 'ays_survey_admin_ajax';
        var afunction = 'ays_survey_submissions_export_filter';
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                user_id: user_id,
                survey_id: survey_id,
                date_from: date_from,
                date_to: date_to
            },
            success: function(response) {
                $this.find('div.ays-survey-preloader').css('display', 'none');
                $this.find(".export_results_count span").text(response.count);
            }
        });
    });

    var userSel2, surveySel2;
    $(document).find('.ays-export-filters').on('click', function(e) {
        e.preventDefault();

        var $this = $('#export-filters');
            $this.find('div.ays-survey-preloader').css('display', 'flex');
            $this.aysModal('show');

        var action    = 'ays_survey_admin_ajax';
        var afunction = 'ays_survey_maker_show_filters';
        $.post({
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: action,
                function: afunction
            },
            success: function(res) {
                if (res.status) {
                    $this.find('div.ays-survey-preloader').css('display', '');
                    
                    var newUserSelect = "";
                    var newSurveySelect = "";
                    for (var u in res.users) {
                        newUserSelect += '<option value="'+ res.users[u].user_id +'">'+ res.users[u].display_name +'</option>';
                    }
                    for (var s in res.surveys) {
                        newSurveySelect += '<option value="'+ res.surveys[s].survey_id +'">'+ res.surveys[s].title +'</option>';
                    }
                    
                    $this.find('#user_id-filter').html(newUserSelect);
                    var userSel  = $this.find('#user_id-filter');
                        userSel2 = userSel.select2({
                        dropdownParent: userSel.parent(),
                        closeOnSelect: true,
                        allowClear: false
                    });

                    $this.find('#survey_id-filter').html(newSurveySelect);
                    var surveySel  = $this.find('#survey_id-filter');
                        surveySel2 = surveySel.select2({
                        dropdownParent: surveySel.parent(),
                        closeOnSelect: true,
                        allowClear: false
                    });
                    
                    $(document).on('click', '.select2-selection__choice__remove', function(){
                        userSel2.select2("close");
                        surveySel2.select2("close");
                    });
                    
                    $this.find(".export_results_count span").text(res.count);
                    $this.find('.ays-modal-body').show();
                } else {
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                    }).then(function(res){
                        $(document).find('#export-filters div.ays-survey-preloader').css('display', 'none');   
                        $this.aysModal('hide');
                    });
                }
            },
            error: function() {
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                }).then(function(res){
                    $(document).find('#export-filters div.ays-survey-preloader').css('display', 'none');   
                    $this.aysModal('hide');
                });
            }
        });
    });

    $(document).find('.export-action').on('click', function(e) {
        e.preventDefault();
        var $this = $('#export-filters');

        $this.find('div.ays-survey-preloader').css('display', 'flex');

        var user_id   = $('#user_id-filter').val();
        var survey_id = $('#survey_id-filter').val();
        var type      = $(this).data('type');
        var date_from = $('#start-date-filter').val() || $('#start-date-filter').attr('min');
        var date_to   = $('#end-date-filter').val() || $('#end-date-filter').attr('max');

        var action    = 'ays_survey_admin_ajax';
        var afunction = 'ays_submissions_export_file';
        $.post({
            url: ajaxurl,
            dataType: 'json',
            data: { 
                action: action,
                function: afunction,
                type: type, 
                user_id: user_id, 
                survey_id: survey_id, 
                date_from: date_from, 
                date_to: date_to 
            },
            success: function(response) {
                if (response.status) {
                    switch (response.type) {
                        case 'xlsx':
                            var options = {
                                fileName: "survey_submissions_export",
                                header: true
                            };
                            var tableData = [{
                                "sheetName": "Survey submissions",
                                "data": response.data
                            }];
                            Jhxlsx.export(tableData, options);
                            break;
                        case 'csv':
                            var csvOptions = {
                                separator: ',',
                                fileName: 'survey_submissions_export.csv'
                            };
                            var x = new CSVExport(response.data, response.fileFields, csvOptions);
                            break;
                        case 'json':
                            var text    = JSON.stringify(response.data);
                            var data    = new Blob([text], {type: "application/" + response.type});
                            var fileUrl = window.URL.createObjectURL(data);

                            $('#downloadFile').attr({
                                'href': fileUrl,
                                'download': "survey_submissions_export." + response.type,
                            })[0].click();

                            window.URL.revokeObjectURL(fileUrl);
                            break;
                        default:
                            break;
                    }
                }
                $this.find('div.ays-survey-preloader').css('display', 'none');
            }
        });
    });
    // Submission export filters | END

    // ===============================================================
    // ======================      Aro      ==========================
    // ==========================  End   =============================

    $(document).on('click', '.ays-survey-apply-question-changes', function(e){
        var sectionCont = $(document).find('.ays-survey-sections-conteiner');
        var editorPopup = $(document).find('#ays-edit-question-content');
        var questionId = $(this).attr('data-question-id');
        var questionName = $(this).attr('data-question-name');
        var sectionId = $(this).attr('data-section-id');
        var sectionName = $(this).attr('data-section-name');
        var question = sectionCont.find('.ays-survey-section-box[data-id="'+sectionId+'"][data-name="'+sectionName+'"] .ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionName+'"]');
        var editor = window.tinyMCE.get('ays_survey_question_editor');
        var questionContent = '';

        question.find('.ays-survey-open-question-editor-flag').val('on');

        editorPopup.find('.ays-survey-preloader').css('display', 'flex');

        if ( editorPopup.find("#wp-ays_survey_question_editor-wrap").hasClass("tmce-active")){
            questionContent = editor.getContent();
        }else{
            questionContent = editorPopup.find('#ays_survey_question_editor').val();
        }
        
        var action    = 'ays_survey_admin_ajax';
        var afunction = 'ays_live_preivew_content';
        var data = {};
        data.action = action;
        data.function = afunction;
        data.content = questionContent;
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.status) {
                    editorPopup.find('.ays-survey-preloader').css('display', 'none');
                    question.find('textarea.ays-survey-question-input-textarea').val( questionContent );
                    question.find('.ays-survey-question-preview-box').html( response.content );

                    question.find('.ays-survey-question-input-box').addClass('display_none');
                    question.find('.ays-survey-question-preview-box').removeClass('display_none');

                    editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-id', '' );
                    editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-name', '' );
                    editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', '' );
                    editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', '' );
                    // window.tinyMCE.get('ays_survey_question_editor').setContent( '' );
                    var SurveyTinyMCE = window.tinyMCE.get('ays_survey_question_editor');
                    if(SurveyTinyMCE != null){
                        SurveyTinyMCE.setContent( '' );
                    }
                    else{
                        $(document).find('#ays_survey_question_editor').val(" ");
                    }

                    editorPopup.aysModal('hide');
                }
            }
        });
    });

    // ===============================================================
    // ======================      Xcho      =========================
    // ===============================================================
    
    // Slack integration
    $(document).on('click', '#slackOAuthGetToken', function () {
        var clientId = $("#ays_slack_client").val(),
            clientSecret = $("#ays_slack_secret").val(),
            clientCode = $(this).attr('data-code'),
            successText = $(this).attr('data-success');
        if (clientId == '' || clientSecret == "" || clientCode == "") {
            return false;
        }
        $('#ays_submit').prop('disabled', true);
        $.post({
            url: "https://slack.com/api/oauth.access",
            data: {
                client_id: clientId,
                client_secret: clientSecret,
                code: clientCode
            },
            success: function (res) {
                $('#slackOAuthGetToken')
                    .text(successText)
                    .toggleClass('btn-secondary btn-success pointer-events-none');
                $('#ays_slack_token').val(res.access_token);
                $('#ays_submit').prop('disabled', false);
            }
        });
    });


    $(document).find("#testZapier").on('click', function () {
        var AysSurvey = {};
        var $this = $(this);
        $this.prop('disabled', true);
        var parent = $(document).find('.ays-survey-sections-conteiner');

        var surveyQuestions = parent.find('.ays-survey-question-answer-conteiner');

        var surveyTitle = $(document).find('#ays-survey-title').val();
        AysSurvey.survey_title = surveyTitle;
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        var endDate = [year, month, day].join('-');

        AysSurvey.date = endDate;
        
        surveyQuestions.each( function(e) {
            var _this = $(this);
            var dataName = _this.attr('data-name');

            if ( dataName == 'questions' ) {

                var questionID = _this.attr('data-id');
                var question   = _this.find('.ays-survey-question-conteiner .ays-survey-question-box textarea.ays-survey-question-input-textarea').val();

                if ( question != '' ) {
                    AysSurvey[ 'question_id_' + questionID ] = question;
                }
            }
        });

        $.post({
            url: $this.attr('data-url'),
            dataType: 'json',
            data: {
                "AysSurvey": JSON.stringify(AysSurvey)
            },
            success: function (response) {
                $this.prop('disabled', false);
                if (response.status) {
                    $this.removeClass('btn-outline-secondary').addClass('btn-success').text( SurveyMakerAdmin.successfullySent )
                } else {
                    $this.removeClass('btn-outline-secondary').addClass('btn-danger').text( SurveyMakerAdmin.failed )
                }
            },
            error: function () {
                $this.prop('disabled', false).removeClass('btn-outline-secondary').addClass('btn-danger').text( SurveyMakerAdmin.failed )
            }
        });
    });
    
    // Send Summary Email
    $(document).find('.ays_survey_summary_mail_btn').on('click', function(e) {
        var sendToUsers  = $(document).find('#ays_survey_summary_emails_to_users').prop('checked');
        var sendToAdmin  = $(document).find('#ays_survey_summary_emails_to_admin').prop('checked');
        var sendToAdmins = $(document).find('#ays_survey_summary_emails_to_admins').val();
        var survey_id    = $(document).find('#ays_survey_id_summary_mail').val();
        var loader = $(document).find('img.ays_survey_summary_delivered_message_loader');
        var action = 'ays_survey_admin_ajax';
        var sFunction = 'send_summary_email';
        var del_message = $(document).find('#ays_survey_summary_delivered_message');
        var data = {
            action:action,
            function:sFunction,
            sendToUsers:sendToUsers,
            sendToAdmin:sendToAdmin,
            sendToAdmins:sendToAdmins,
            surveyId:survey_id,
        }
        loader.css({'display':'block'});
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                loader.fadeOut(500);
                if (response.status) {
                    if(response.mail){
                        del_message.css("color", "green");
                    }else{
                        del_message.css("color", "red");
                    }
                    del_message.html(response.message).css({'display':'block'});
                }else{
                    del_message.html(response.message);
                    del_message.css({'display':'block','color':'red'});
                }
                setTimeout(function(){
                    del_message.fadeOut(500);
                }, 1500);
            }
        });
    });

    $(document).on('click', "#ays_survey_googleOAuth2", function (e) {
        e.preventDefault();

        var gRedirectUri = $(document).find("#ays_google_redirect").val();
        var gClientId = $(document).find("#ays_google_client").val();
        var gClientSecret = $(document).find("#ays_google_secret").val();

        if(gClientId && gClientSecret){
            $(this).parents('form').append('<input type="hidden" name="ays_googleOAuth2" value="ays_googleOAuth2">');
            $(this).parents('form').get(0).submit();
        }else{
            return false;
        }
    });

    // Export Submissions to XLSX
    $(document).find('.ays-survey-export-submissions-to-xlsx').on('click', function(e) {
        e.preventDefault();
        var _this = $(this);

        _this.prop('disabled',true);
        _this.addClass('disabled');

        var action     = 'ays_survey_admin_ajax';
        var afunction  = 'ays_survey_export_submissions_to_xlsx';
        var type       = _this.data('type');
        var survey_id  = _this.attr('survey-id');
        var preloader = _this.parent().next('div.ays-survey-preloader').css('display', 'flex');
        $.post({
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                type: type,
                survey_id: survey_id,
            },
            success: function (response) {
                if (response.status) {
                    var options = {
                        fileName: "survey_answers_export",
                        header: true
                    };
                    var tableData = [{
                        "sheetName": "Survey summary",
                        "data": response.data
                    }];
                    Jhxlsx.export(tableData, options);
                    preloader.css('display', 'none');
                }else{
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h4>"+ SurveyMakerAdmin.dataDeleted +"</h4>"
                    }).then(function(response) {
                        preloader.css('display', 'none');
                    });
                }
                _this.prop('disabled',false);
                _this.removeClass('disabled');
            }
        });
    });

    // Export single submission results
    $(document).on('click','.ays-survey-single-submission-results-csv-export', function(e) {
        var $this = $(this);

        $this.prop('disabled',true);
        $this.addClass('disabled');

        var type          = $this.attr('data-type');
        var submission_id = $this.attr('data-result');
        var action        = 'ays_survey_admin_ajax';
        var afunction     = 'ays_survey_single_submission_results_csv_export';
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: {
                action: action,
                function: afunction,
                submission_id: submission_id,
                type: type
            },
            success: function (response) {
                if (response.status) {
                    var csvOptions = {
                        separator: ',',
                        fileName: 'survey_submissions_export.csv'
                    };
                    var x = new CSVExport(response.data, response.fileFields, csvOptions);
                }else{
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                    })
                }
                $this.prop('disabled',false);
                $this.removeClass('disabled');
            },
            error: function() {
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ SurveyMakerAdmin.loadResource +"</h2><br><h6>"+ SurveyMakerAdmin.dataDeleted +"</h6>"
                }).then(function(res){
                    $this.prop('disabled',false);
                    $this.removeClass('disabled');
                });
            }
        });
        e.preventDefault();
    });   
    
})( jQuery );