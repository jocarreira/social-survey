(function( $ ) {
	'use strict';

	$(document).ready(function(){
		//Change Type
		$(document).on('change','.ays-survey-front-request-quest-type',function(){
			var content = '';
			var questType = $(this).val();
			var dataCount = $(this).parents('.ays-survey-front-request-survey-question-content').attr('data-id');
			var count = parseInt(dataCount);
			$(this).attr('data-type', questType);
			switch (questType) {
				case 'radio':
				case 'select':
				case 'checkbox':
					$(this).parents('.ays-survey-front-request-survey-question-content').find('.ays-survey-front-request-answer').remove();
					$(this).parents('.ays-survey-front-request-survey-question-content').find('.ays-survey-front-request-answer_text').remove();
					var qtype = questType;
					if( questType == 'select' ){
						qtype = 'radio';
					}
					content +='<div class="ays-survey-front-request-answer">';
						content += '<span class="ays_survey_front_request_answers_container-title">' + 'Answers' + '</span>';
						content += '<div class="ays-survey-front-request-row">';
							content += '<div class="ays-survey-front-request-answer-content ays-survey-front-request-col-8">';
								content += '<div class="ays-survey-front-request-radio-answer">';
									content += '<div class="ays-survey-front-request-answer-row">';
										content += '<img src="'+aysSurveyFrontRequestPublic.icons[qtype]+'">';
										content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+count+'][answers][1][answer]">';
										content += '<a href="javascript:void(0)" class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
											content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
										content += '</a>';
									content += '</div>';
									content += '<div class="ays-survey-front-request-answer-row">';
										content += '<img src="'+aysSurveyFrontRequestPublic.icons[qtype]+'">';
										content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+count+'][answers][2][answer]">';
										content += '<a href="javascript:void(0)" class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
											content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
										content += '</a>';
									content += '</div>';
									content += '<div class="ays-survey-front-request-answer-row">';
										content += '<img src="'+aysSurveyFrontRequestPublic.icons[qtype]+'">';
										content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+count+'][answers][3][answer]">';
										content += '<a href="javascript:void(0)" class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
											content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
										content += '</a>';
									content += '</div>';
								content += '</div>';
							content += '</div>';
						content += '</div>';

						content += '<hr>';
						
						content += '<div class="ays-survey-front-request-questions-actions-container">';
							content += '<div class="ays-survey-front-request-survey-add-answer-container">';
								content += '<a href="javascript:void(0)" class="ays-survey-front-request-add-answer">';
									content += '<i class="ays_fa_front_req_survey ays_fa_plus_square"></i>';
									content += '<span>' + 'Add answer' + '</span>';	
								content += '</a>';	
							content += '</div>';

							content += '<div class="ays-survey-front-request-answer-duplicate-delete-content">';
								content += '<a href="javascript:void(0)" class="ays-survey-front-request-clone-question" title="' + "Duplicate" + '">';
									content += '<i class="ays_fa_front_req_survey ays_fa_clone"></i>';
								content += '</a>';
								content += '<a href="javascript:void(0)" class="ays-survey-front-request-delete-question" title="' + "Delete" + '">';
									content += '<i class="ays_fa_front_req_survey ays_fa_trash_o"></i>';
								content += '</a>';
							content += '</div>';
						content += '</div>';
					content +='</div>';
					$(this).parents('.ays-survey-front-request-survey-question-content').append(content);
					break;
				case 'text':
				case 'number':
				case 'short_text':
				case 'email':
				case 'name':
					$(this).parents('.ays-survey-front-request-survey-question-content').find('.ays-survey-front-request-answer').remove();
					$(this).parents('.ays-survey-front-request-survey-question-content').find('.ays-survey-front-request-answer_text').remove();

					$(this).parents('.ays-survey-front-request-survey-question-content').find('#ays_survey_front_request_quest_title_'+count).attr('name','ays_survey_front_request_question['+count+'][question]');

					content +='<div class="ays-survey-front-request-answer">';
						content += '<hr>';						
						content += '<div class="ays-survey-front-request-questions-actions-container" style="flex-direction:row-reverse;">';
							content += '<div class="ays-survey-front-request-answer-duplicate-delete-content">';
								content += '<a href="javascript:void(0)" class="ays-survey-front-request-clone-question" title="' + "Duplicate" + '">';
									content += '<i class="ays_fa_front_req_survey ays_fa_clone"></i>';
								content += '</a>';
								content += '<a href="javascript:void(0)" class="ays-survey-front-request-delete-question" title="' + "Delete" + '">';
									content += '<i class="ays_fa_front_req_survey ays_fa_trash_o"></i>';
								content += '</a>';
							content += '</div>';
						content += '</div>';
					content +='</div>';
					$(this).parents('.ays-survey-front-request-survey-question-content').append(content);
					break;
				default:
					break;
			}		
		});
		
		//Add Question
		$(document).on('click', '.ays-survey-front-request-add-question', function(){
			var questContainer = $(this).parents('.ays-survey-front-request-body').find('.ays-survey-front-request-survey-question-container');
			var dataCount = questContainer.attr('data-id');

			var count = parseInt(dataCount)+1;

			var content = "";
				content += '<div class="ays-survey-front-request-survey-question-content" data-id="'+count+'">';
					content += '<div class="ays-survey-front-request-row">';
						// Question title
						content += '<div class="ays-survey-front-request-col-8">';
							content += '<div class="ays-survey-front-request-quest-title">';
								content += '<input type="text" id="ays_survey_front_request_question_'+count+'" class="ays-survey-front-request-question" name="ays_survey_front_request_question['+count+'][question]" placeholder="Question title">';
							content += '</div>';
						content +='</div>';

						// Question type
						content += '<div class="ays-survey-front-request-col-4">';
							content += '<div class="ays-survey-front-request-question-type">';
								content += '<select id="ays_survey_front_request_quest_type_'+count+'" class="ays-survey-front-request-quest-type" name="ays_survey_front_request_question['+count+'][type]">';
									content += '<option value="radio">Radio</option>';
									content += '<option value="checkbox">Checkbox</option>';
									content += '<option value="select">Dropdown</option>';
									content += '<option value="text">Paragraph</option>';
									content += '<option value="number">Number</option>';
									content += '<option value="short_text">Short Text</option>';
									content += '<option value="name">Name</option>';
									content += '<option value="email">Email</option>';
								content += '</select >';
							content += '</div>';
						content +='</div>';
						
					content +='</div>';

					content +='<div class="ays-survey-front-request-answer">';
						content += '<span class="ays_survey_front_request_answers_container-title">' + 'Answers' + '</span>';
						content += '<div class="ays-survey-front-request-row">';
							content += '<div class="ays-survey-front-request-answer-content ays-survey-front-request-col-8">';
								content += '<div class="ays-survey-front-request-radio-answer" data-answer="3">';
									content += '<div class="ays-survey-front-request-answer-row">';
										content += '<img src="'+aysSurveyFrontRequestPublic.icons['radio']+'">';
										content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+count+'][answers][1][answer]">';
										content += '<a href="javascript:void(0)"  class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
											content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
										content += '</a>';
									content += '</div>';
									content += '<div class="ays-survey-front-request-answer-row">';
										content += '<img src="'+aysSurveyFrontRequestPublic.icons['radio']+'">';
										content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+count+'][answers][2][answer]">';
										content += '<a href="javascript:void(0)"  class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
											content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
										content += '</a>';
									content += '</div>';
									content += '<div class="ays-survey-front-request-answer-row">';
										content += '<img src="'+aysSurveyFrontRequestPublic.icons['radio']+'">';
										content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+count+'][answers][3][answer]">';
										content += '<a href="javascript:void(0)"  class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
											content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
										content += '</a>';
									content += '</div>';
								content += '</div>';
							content += '</div>';
						content += '</div>';

						content += '<hr>';
						
						content += '<div class="ays-survey-front-request-questions-actions-container">';
							content += '<div class="ays-survey-front-request-survey-add-answer-container">';
								content += '<a href="javascript:void(0)" class="ays-survey-front-request-add-answer">';
									content += '<i class="ays_fa_front_req_survey ays_fa_plus_square"></i>';
									content += '<span>' + 'Add answer' + '</span>';	
								content += '</a>';	
							content += '</div>';

							content += '<div class="ays-survey-front-request-answer-duplicate-delete-content">';
								content += '<a href="javascript:void(0)"  class="ays-survey-front-request-clone-question" title="' + "Duplicate" + '">';
									content += '<i class="ays_fa_front_req_survey ays_fa_clone"></i>';
								content += '</a>';
								content += '<a href="javascript:void(0)"  class="ays-survey-front-request-delete-question" title="' + "Delete" + '">';
									content += '<i class="ays_fa_front_req_survey ays_fa_trash_o"></i>';
								content += '</a>';
							content += '</div>';

						content += '</div>';
					content +='</div>';
				content += '</div>';

			questContainer.attr('data-id', count);
			questContainer.append(content);
		});
		
		//Clone question
		$(document).on('click','.ays-survey-front-request-clone-question', function(){
			var thisQuestion = $(this).parents('.ays-survey-front-request-survey-question-content');
			var questContentClone = $(this).parents('.ays-survey-front-request-survey-question-content').clone(true, false);
			var clonedElementType = thisQuestion.find('.ays-survey-front-request-quest-type').val();

			var dataCount = thisQuestion.parents('.ays-survey-front-request-survey-question-container').attr('data-id');
			dataCount = parseInt( dataCount ) + 1;
			questContentClone.attr('data-id', dataCount);
			thisQuestion.parents('.ays-survey-front-request-survey-question-container').attr('data-id', dataCount);

			var questType = questContentClone.find('select.ays-survey-front-request-quest-type');
			var questTitle = questContentClone.find('input.ays-survey-front-request-question');
			var questTypeText = questContentClone.find('.ays-survey-front-request-answer_text').find('input.ays-survey-front-request-answer-input');

			questType.attr('name','ays_survey_front_request_question['+ dataCount +'][type]');
			questType.attr('id','ays_survey_front_request_quest_type_'+ dataCount +'');

			
			questTitle.attr('name','ays_survey_front_request_question['+ dataCount +'][question]');
			questTitle.attr('id','ays_survey_front_request_question_'+ dataCount +'');
			
			questTypeText.attr('name','ays_survey_front_request_question['+ dataCount +'][text_answer]');

			if(clonedElementType != 'text'){
				var answerDiv = questContentClone.find('.ays-survey-front-request-answer').find('.ays-survey-front-request-radio-answer div');
				
				for (var i = 0; i <= answerDiv.length; i++) {
					answerDiv.eq(i).find('.ays-survey-front-request-answer-input').attr('name', 'ays_survey_front_request_question['+ dataCount +'][answers]['+(i+1)+'][answer]');
				}
			}
			questContentClone.insertAfter(thisQuestion);

			questType.val(clonedElementType);
		});

		//Delete question
		$(document).on('click','.ays-survey-front-request-delete-question',function(){
			if($(this).parents('.ays-survey-front-request-survey-question-container').find('.ays-survey-front-request-survey-question-content').length == 1){
                swal.fire({
                    type: 'warning',
                    text:'Sorry minimum count of questions should be 1'
                });
                return false;
            }

			$(this).parents('.ays-survey-front-request-survey-question-content').remove();
		});

		// Add Answer
		$(document).on('click','.ays-survey-front-request-add-answer',function(){
			var content = '';
			var answerCount = $(this).parents('.ays-survey-front-request-answer').find('.ays-survey-front-request-radio-answer div').length;
			var questCount = $(this).parents('.ays-survey-front-request-survey-question-content').attr('data-id');
			var count = parseInt(answerCount)+1;
			var questType = $(this).parents('.ays-survey-front-request-survey-question-content').find('.ays-survey-front-request-quest-type').val();

			switch (questType) {
				case 'radio':
				case 'checkbox':
				case 'select':
					var qtype = questType;
					if( questType == 'select' ){
						qtype = 'radio';
					}

					content += '<div class="ays-survey-front-request-answer-row">';
						content += '<img src="'+aysSurveyFrontRequestPublic.icons[qtype]+'">';
						content += '<input type="text" class="ays-survey-front-request-answer-input" placeholder="Answer text" name="ays_survey_front_request_question['+questCount+'][answers]['+count+'][answer]">';
						content += '<a href="javascript:void(0)"  class="ays-survey-front-request-delete-answer" title="' + "Delete" + '">';
							content += '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
						content += '</a>';
					content += '</div>';

					$(this).parents('.ays-survey-front-request-answer').find('.ays-survey-front-request-radio-answer').append(content);

					break;
				default:
					break;
			}
		});

		// Delete Answer 
		$(document).on('click','.ays-survey-front-request-delete-answer',function(){
			if($(this).parents('.ays-survey-front-request-radio-answer').find('div').length == 2){
                swal.fire({
                    type: 'warning',
                    text:'Sorry minimum count of answers should be 2'
                });
                return false;
            }
			
			var answerCount = $(this).parents('.ays-survey-front-request-answer-content').find('.ays-survey-front-request-radio-answer div');
			var questContainer = $(this).parents('.ays-survey-front-request-survey-question-content');
			var questCount = questContainer.attr('data-id');
			for (var i = 0; i <= answerCount.length; i++) {
				answerCount.eq(i).find('.ays-survey-front-request-answer-input').attr('name','ays_survey_front_request_question['+questCount+'][answers]['+ ( i + 1 ) +'][answer]');
			}

			$(this).parent().remove();
		});

		// Submit form
		$(document).on('click','.ays-survey-front-request-survey-submit',function(e){
			$(this).parents('.ays-survey-front-request-body').find('.ays-survey-front-request-preloader').css('display', 'flex');
			if($(this).parents('.ays-survey-front-request-body').find('#ays_survey_front_request_survey_title').val() == ''){            
				swal.fire({
					type: 'error',
					text: "Survey title can't be empty"
				});
				$(this).parents('.ays-survey-front-request-body').find('.ays-survey-front-request-preloader').css('display', 'none');
				return false;
			}
			var franswers = $(this).parents('.ays-survey-front-request-body').find('.ays-survey-front-request-radio-answer').find('.ays-survey-front-request-answer-input');
			var emptyAnswers = 0;
			for(var j = 0; j < franswers.length; j++){
				var parent =  franswers.eq(j).parents('.ays-survey-front-request-survey-question-content');
				var questionType = parent.find('.ays-survey-front-request-quest-type').val();
	
				if ( questionType == 'text' ) {
					var answerVal = parent.find('.ays-survey-front-request-answer_text').find('.ays-survey-front-request-answer-input').val();
					if(answerVal == ''){
						emptyAnswers++;
						break;
					}
				} else {
					if(franswers.eq(j).val() == ''){
						emptyAnswers++;
						break;
					}
				}
			}
	
			if(emptyAnswers > 0){
				swal.fire({
					type: 'error',
					text: "You must fill all answers"
				});
				$(this).parents('.ays-survey-front-request-body').find('.ays-survey-front-request-preloader').css('display', 'none');
				return false;
			}
	
			var form = $(this).parents('form#ays_survey_front_request_form');
			var data = form.serializeFormJSON();
	
			data.action = 'ays_survey_ajax';
			data.function = 'ays_survey_insert_data_to_db';

			var loader = $(this).parents('.ays-survey-front-request-body').find('.ays-survey-front-request-preloader');

			$.ajax({
				url: aysSurveyFrontRequestPublic.ajax_url,
				method: 'POST',
				dataType: 'json',
				data: data,
				success:function (response){
					loader.css('display', 'none');
					if ( response.status == true ) {
						var status = 'success';
						if( response.data == false ){
							status = 'error';
						}
	
						swal.fire({
							type: status,
							text: response.message
						}).then(function() {
							window.location.reload();
						});
					}else{
						swal.fire({
							type: 'error',
							text: response.message
						}).then(function() {
							window.location.reload();
						});
					}
				}
			});
		});
		
	});

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

})( jQuery );
