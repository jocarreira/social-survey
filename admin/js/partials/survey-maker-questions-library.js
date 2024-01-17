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
    
    $(document).ready(function(){

        $(document).on('click', '.ays-add-question', function () {
            $(document).find('#ays-questions-modal').aysModal('show');
            qatable.draw();
        });
        
        $(document).on('click', '.ays-survey-open-questions-library', function(e){
            $(document).find('#ays-questions-modal').aysModal('show');
            // $(document).find('#ays-questions-modal .ays-survey-preloader').css('display', 'flex');
            qatable.draw();
        });

        var questCategoryFilter = $(document).find('#add_quest_category_filter').select2({
            placeholder: SurveyMakerAdmin.selectSurvey,
            multiple: true,
            matcher: searchForPage,
            dropdownParent: $(document).find('#quest_cat_container')
        });
        
        $(document).find('.ays_filter_cat_clear').on('click', function(){
            questCategoryFilter.val(null).trigger('change');
            qatable.draw();
        });
        window.aysQuestSelected = [];
        window.aysQuestNewSelected = [];
        
        var qatable = $(document).find('#ays-question-table-add').DataTable({
            paging: 5,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": SurveyMakerAdmin.ajaxUrl,
                "method": "POST",
                "data": function ( d ) {
                    d.action = 'ays_survey_admin_ajax';
                    d.function = 'ays_survey_get_questions_library';
                    var surveyId = $(document).find('#ays-question-table-add').data('surveyId');
                    d.survey_id = surveyId;
                    var catFilter = $(document).find('.cat_filter').val();
                    if(catFilter != null){
                        d.cats = catFilter;
                    }
                }
            },
            "order": [ [ 5, 'desc' ] ],
            columns: [{ 
                data: "first_column"
            },{ 
                data: "question",
                className: "ays-modal-td-question"
            },{ 
                data: "type",
                className: "ays-modal-td-questions-library-type"
            },{ 
                data: "create_date"
            },{ 
                data: "modified_date"
            },{ 
                data: "id"
            }],
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            // "infoCallback": function( settings, start, end, max, total, pre ){
            //     var qaTableSelectAll =  $(document).find('#ays-question-table-add tbody tr.ays_quest_row');
            //     var qaTableSelected =  0;
            //     qaTableSelectAll.each(function(){                    
            //         if(!$(this).hasClass('selected')){
            //             qaTableSelected++;
            //         }
            //     });
            //     if(qaTableSelected > 0){
            //         if($(document).find('#select_all').hasClass('deselect')){
            //             $(document).find('#select_all').removeClass('deselect');
            //             $(document).find('#select_all').text('Select All');
            //         }
            //     }else{
            //         $(document).find('#select_all').addClass('deselect');
            //         $(document).find('#select_all').text('Deselect All');
            //     }
            //     var api = this.api();
            //     var pageInfo = api.page.info();
            
            //     return 'Page '+ (pageInfo.page+1) +' of '+ pageInfo.pages;
            // },
            "drawCallback": function( settings ) {
                setTimeout(function(){
                    var qaTableRows =  $(document).find('#ays-question-table-add tbody tr.ays_quest_row');
                    qaTableRows.each(function(){                    
                        if($.inArray(parseInt($(this).data('id')), window.aysQuestSelected) == -1){
                            $(this).removeClass('selected');
                            $(this).find('.ays-select-single')
                                .removeClass('ays_fa_check_square_o')
                                .addClass('ays_fa_square_o');
                        }
                    });
                }, 1);
            },
            "rowCallback": function( row, data ) {
                $(row).attr('data-id', data['id']);
                $(row).addClass('ays_quest_row');
                if(data['selected'] == 'selected'){
                    $(row).addClass(data['selected']);
                }
            },
            "initComplete": function( settings, json ) {
                
                if( json.data.length > 0 ){
                    $(document).find('.ays-survey-open-questions-library').prop('disabled', false);
                }

                var selectedRows = $(document).find('#ays-question-table-add tbody tr.selected');
                for(var i=0; i < selectedRows.length; i++){
                    window.aysQuestSelected.push(selectedRows.eq(i).data('id'));
                }
                var proccessing = "<div class='dataTables_processing_loader'><span class='dtable_loader'><img src='"+json.loader+"'></span><span>"+json.loaderText+"</span></div>";
                $(document).find('.dataTables_processing').html(proccessing);
            }
        });
        
        $(document).find('.cat_filter').on('select2:select', function() {
            qatable.draw();
        });
        $(document).find('.cat_filter').on('select2:unselect', function() {
            qatable.draw();
            questCategoryFilter.select2("close");
        });
        $(document).find('.cat_filter').on('select2:unselecting', function() {
            questCategoryFilter.select2("close");
        });

        $(document).on('click', '.ays_bulk_del_questions', function(e){
            var accordion_el = accordion.find('tr .ays_del_tr:checked'),
                accordion_el_length = accordion_el.length;
            var id_container = $(document).find('input#ays_already_added_questions'),
                existing_ids = id_container.val().split(',');
            var questions_count = $(document).find('.questions_count_number');
            accordion_el.each(function(e, el){
                $(this).parents("tr").css({
                    'animation-name': 'slideOutLeft',
                    'animation-duration': '.3s'
                });
                var a = $(this);
                var index = 1;
                var questionId = parseInt(a.parents('tr').data('id'));
                var indexOfAddTable = $.inArray(questionId, window.aysQuestSelected);
                if(indexOfAddTable !== -1){
                    window.aysQuestSelected.splice( indexOfAddTable, 1 );
                }

                if ($.inArray(questionId.toString(), existing_ids) !== -1) {
                    var position = $.inArray(questionId.toString(), existing_ids);
                    existing_ids.splice(position, 1);
                    id_container.val(existing_ids.join(','));
                }
                setTimeout(function(){
                    a.parents('tr').remove();
                    questions_count.text(accordion.find('tr.ays-question-row').length);
                    if(accordion.find('tr.ays-question-row').length == 0){
                       var quizEmptytd = '<tr class="ays-question-row ui-state-default">'+
                        '    <td colspan="5" class="empty_quiz_td">'+
                        '        <div>'+
                        '            <i class="ays_fa ays_fa_info" aria-hidden="true" style="margin-right:10px"></i>'+
                        '            <span style="font-size: 13px; font-style: italic;">'+
                        '               There are no questions yet.'+
                        '            </span>'+
                        '            <a class="create_question_link" href="admin.php?page=quiz-maker-questions&action=add" target="_blank">Create question</a>'+
                        '        </div>'+
                        '        <div class="ays_add_question_from_table">'+
                        '            <a href="javascript:void(0)" class="ays-add-question">'+
                        '                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>'+
                        '                Add questions'+
                        '            </a>'+
                        '        </div>'+
                        '    </td>'+
                        '</tr>';
                        accordion.append(quizEmptytd);
                    }                        

                    accordion.find('tr.ays-question-row').each(function () {
                        if ($(this).hasClass('even')) {
                            $(this).removeClass('even');
                        }
                        var className = ((index % 2) === 0) ? 'even' : '';
                        index++;
                        $(this).addClass(className);
                    });
                }, 300);
            });
            setTimeout(function(){
                qatable.draw();                
            }, 500);
            $(document).find('.ays_bulk_del_questions').attr('disabled','disabled');
        });

        $(document).on('click', '.ays-delete-question', function () {
            var index = 1,
                id_container = $(document).find('input#ays_already_added_questions'),
                existing_ids = id_container.val().split(',');
            var q = $(this);
            q.parents("tr").css({
                'animation-name': 'slideOutLeft',
                'animation-duration': '.3s'
            });
            var indexOfAddTable = $.inArray($(this).data('id'), window.aysQuestSelected);
            if(indexOfAddTable !== -1){
                window.aysQuestSelected.splice( indexOfAddTable, 1 );
                qatable.draw();
            }

            if ($.inArray($(this).data('id').toString(), existing_ids) !== -1) {
                var position = $.inArray($(this).data('id').toString(), existing_ids);
                existing_ids.splice(position, 1);
                id_container.val(existing_ids.join(','));
            }

            $(document).find('input[type="checkbox"]#ays_select_' + $(this).data('id')).prop('checked', false);
            
            setTimeout(function(){            
                q.parent('td').parent('tr.ays-question-row').remove();
                var accordion = $(document).find('table.ays-questions-table tbody');
                var questions_count = accordion.find('tr.ays-question-row').length;
                $(document).find('.questions_count_number').text(questions_count);
            
                if($(document).find('tr.ays-question-row').length == 0){
                   var quizEmptytd = '<tr class="ays-question-row ui-state-default">'+
                    '    <td colspan="5" class="empty_quiz_td">'+
                    '        <div>'+
                    '            <i class="ays_fa ays_fa_info" aria-hidden="true" style="margin-right:10px"></i>'+
                    '            <span style="font-size: 13px; font-style: italic;">'+
                    '               There are no questions yet.'+
                    '            </span>'+
                    '            <a class="create_question_link" href="admin.php?page=quiz-maker-questions&action=add" target="_blank">Create question</a>'+
                    '        </div>'+
                    '        <div class="ays_add_question_from_table">'+
                    '            <a href="javascript:void(0)" class="ays-add-question">'+
                    '                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>'+
                    '                Add questions'+
                    '            </a>'+
                    '        </div>'+
                    '    </td>'+
                    '</tr>';
                    $(document).find('#ays-questions-table tbody').append(quizEmptytd);
                }
                $(document).find('tr.ays-question-row').each(function () {
                    if ($(this).hasClass('even')) {
                        $(this).removeClass('even');
                    }
                    var className = ((index % 2) === 0) ? 'even' : '';
                    index++;
                    $(this).addClass(className);
                });
            }, 300);
        });

        
        $(document).find('#ays-question-table-add_info').append('<button id="select_all" class="button" type="button" style="margin-left:10px;">Select All</button>');
        $(document).on('click', '#select_all', function(e){
            window.aysQuestSelected = [];
			if(typeof window.aysQuestNewSelected == 'undefined'){
            	window.aysQuestNewSelected = [];
			}
            let qaTableSelectAll =  $(document).find('#ays-question-table-add tbody tr.ays_quest_row');
            if($(this).hasClass('deselect')){
                qaTableSelectAll.each(function(){
                    $(this).removeClass('selected');
                    $(this).find('.ays-select-single').removeClass('ays_fa_check_square_o').addClass('ays_fa_square_o');
                });
                $(this).removeClass('deselect');
                $(this).text('Select All');
            }else{
                qaTableSelectAll.each(function(){
                    if(! $(this).hasClass('selected')){
                        $(this).addClass('selected');
                        window.aysQuestSelected.unshift($(this).data('id'));
                        window.aysQuestNewSelected.unshift($(this).data('id'));
                        $(this).find('.ays-select-single').removeClass('ays_fa_square_o').addClass('ays_fa_check_square_o');
                    }
                });
                $(this).addClass('deselect');
                $(this).text('Deselect All');
            }
        });
        
        $(document).on('click', '#ays-question-table-add tbody tr.ays_quest_row', function(){
            let id = $(this).data('id');
            let index = $.inArray(id, window.aysQuestSelected);
            let index2 = $.inArray(id, window.aysQuestNewSelected);

            if ( index === -1 ) {
                window.aysQuestSelected.push( id );
            } else {
                window.aysQuestSelected.splice( index, 1 );
            }
            if ( index2 === -1 && index === -1 ) {
                window.aysQuestNewSelected.push( id );
            } else {
                window.aysQuestNewSelected.splice( index2, 1 );
            }
            
            if($(this).hasClass('selected')){
                $(this).find('.ays-select-single').removeClass('ays_fa_check_square_o').addClass('ays_fa_square_o');
            }else{
                $(this).find('.ays-select-single').removeClass('ays_fa_square_o').addClass('ays_fa_check_square_o'); 
            }
            $(this).toggleClass('selected');
        });

        $(document).on('click', '#ays-survey-insert-into-section .ays-close', function(e){
            e.preventDefault();
        });

        $(document).find('form#ays_add_question_rows').on( 'submit', function(e) {
            e.preventDefault();
            var sectionsPopup = $(document).find('#ays-survey-insert-into-section');
            var popup = $(document).find('#ays-questions-modal');

            if( window.aysQuestNewSelected.length > 0 ){
                aysSurveySectionsInitToInsertQuestions();
                sectionsPopup.aysModal('show');
            }else{
                alert('You must select new questions to add to the survey.');
                $(document).find('#ays-question-table-add div.ays-quiz-preloader').css('display', 'none');
            }
        });


        // $(document).on('click', '.ays-survey-move-question-into-section', function(e){
        //     var popup = $(document).find('#ays-survey-move-to-section');
        //     var sectionId = popup.attr('data-section-id');
        //     var sectionDataName = popup.attr('data-section-name');
        //     var questionId = popup.attr('data-question-id');
        //     var questionDataName = popup.attr('data-question-name');
        //     var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+ $(this).data( 'id' ) +'"]:not(.ays-survey-new-section)');
        //     if( $(this).hasClass('ays-survey-move-new-question-into-section') ){
        //         section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box.ays-survey-new-section[data-id="'+ $(this).data( 'id' ) +'"]');
        //     }
        //     var oldSection = $(document).find('.ays-survey-section-box[data-id="'+sectionId+'"][data-name="'+sectionDataName+'"]');
        //     var question = $(document).find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
        //     var questions = oldSection.find('.ays-survey-question-answer-conteiner');
            
        //     if( questions.length <= 1 ){
        //         swal.fire({
        //             type: 'warning',
        //             text: SurveyMakerAdmin.minimumCountOfQuestions
        //         });
        //     }else{
        //         setTimeout(function(){
        //             question.appendTo( section.find('.ays-survey-section-questions') );
        //             draggedQuestionUpdate( question, section );
        //             popup.aysModal('hide');
        //         }, 1);
        //     }
        // });
        
        function aysSurveySectionsInitToInsertQuestions(){
            var popup = $(document).find('#ays-survey-insert-into-section');
            // var currentSectionId = popup.attr('data-section-id');
            // var currentSectionName = popup.attr('data-section-name');
            // var currentQuestionId = popup.attr('data-question-id');
            // var currentQuestionName = popup.attr('data-question-name');

            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var moveSectionsCont = popup.find('.ays-survey-insert-into-section-sections-wrap');
            moveSectionsCont.html('');

            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                }

                var disabled = '';
                // if( currentSectionId == _this.data('id') ){
                //     disabled = ' disabled ';
                // }

                var newClassToAddQuestion = '';
                if( _this.hasClass('ays-survey-new-section') ){
                    newClassToAddQuestion = 'ays-survey-insert-question-into-new-section';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );

                var buttonItem = '<button class="dropdown-item ays-survey-insert-question-into-section '+ newClassToAddQuestion +'" ' + disabled + ' data-id="'+ $(this).data('id') +'" data-name="'+ $(this).data('name') +'" type="button">';
                buttonItem += aysCreateSectionName( _this, i+1, SurveyMakerAdmin.insertIntoSection ); //SurveyMakerAdmin.addIntoSection + ' ' + (i+1);
                buttonItem += '</button>';
                moveSectionsCont.append(buttonItem);
            });
        }

        function aysCreateSectionName( section, index, text ){
            var sectionName = section.find('.ays-survey-section-title').val();
            var name = text + ' ' + index;
            if( sectionName == '' ){
                name +=  ' (Untitled Form) ';
            }else{
                name +=  ' ('+ sectionName +') ';
            }
            return name;
        }
        
    });
    
})( jQuery );

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