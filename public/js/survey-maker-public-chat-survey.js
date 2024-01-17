(function( $ ) {
	'use strict';

    $(document).ready(function () {
 
    	$(document).find('.ays-survey-chat-container').find('.ays-survey-chat-question-box').first().find('.ays-survey-chat-question-item').addClass('active-chat-question');
    	$(document).find('.ays-survey-chat-container').find('.ays-survey-chat-answer-box').first().addClass('active-chat-answer');

		$(document).on('keydown', '.ays-survey-chat-answer-text-type', function(event){

    		if (event.keyCode === 13) {
				var sendButton = $(this).find(".ays-survey-chat-answer-input-content");
				sendButton.trigger("click");
				event.preventDefault();
			}
    	});

		$(document).on('click', '.ays-survey-chat-answer-label-content', function(){
			var _this = $(this);

			var clickedAnswer = $(this).find('span').text();

			var questionId = $(this).parents('.ays-survey-chat-answer-box').attr('data-questionid');

			var questionBox = _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header').find('.ays-survey-chat-question-box[data-id='+questionId+']');

			var questionToGo = _this.attr('data-go-to-question') ? _this.attr('data-go-to-question') : '';

			var nextQuestion = questionBox.next();
			var nextAnswerBox = _this.parents('.ays-survey-chat-answer-box').next();
			
			if (questionToGo !== '' && questionToGo != 'submit_form') {
				nextQuestion = _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header').find('.ays-survey-chat-question-box[data-id='+questionToGo+']');
				nextAnswerBox = _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-footer').find('.ays-survey-chat-answer-box[data-questionid='+questionToGo+']');				
			}
			
			if (questionBox.index() > nextQuestion.index()) {
				nextQuestion = questionBox.next();
				nextAnswerBox = _this.parents('.ays-survey-chat-answer-box').next();
			}

			if(questionToGo != 'submit_form'){
				setTimeout(function(){ 
					questionBox.next().find('.ays-survey-chat-question-animation-dots').addClass('active-chat-question');
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').scrollTop( _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').height() );
				}, 100);
			}
				
			questionBox.find('.ays-survey-chat-question-reply').css({'display':'flex'});
			questionBox.find('.ays-survey-chat-question-reply-title').html('<span class="ays-survey-chat-question-title-content">'+clickedAnswer+'</span>');

			$(this).parents('.ays-survey-chat-answer-box').removeClass('active-chat-answer');

			if(questionToGo != 'submit_form'){
				setTimeout(function(){ 
					questionBox.next().find('.ays-survey-chat-question-animation-dots').removeClass('active-chat-question');
					nextQuestion.find('.ays-survey-chat-question-item').addClass('active-chat-question');
					nextAnswerBox.addClass('active-chat-answer');
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').scrollTop( _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').height() );
					nextAnswerBox.find('.ays-survey-chat-short-text-input').focus();
				}, 2000);
			}


			if($(this).parents('.ays-survey-chat-answer-box').is(':last-child')	|| questionToGo == 'submit_form'){
				setTimeout(function(){ 
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-button-container').find('.ays-survey-finish-button').trigger('click');
					var dots = '<div class="ays-survey-chat-question-animation-dots ays-survey-chat-final-message-loader">'
					+ '<div class="ays-survey-chat-question-pre-icon"><img src="'+aysSurveyChat.questionPrIconURL+'" ></div>'
					+ aysSurveyChat.chatLoader 
					+ '</div>';
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').append(dots);
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header').find('.ays-survey-chat-final-message-loader').addClass('active-chat-loader');
				}, 1);
			}
			

		});


		$(document).on('click', '.ays-survey-chat-answer-input-content', function(){
			var _this = $(this);

			var clickedAnswer = $(this).parents('.ays-survey-chat-answer-text-type').find('input.ays-survey-chat-short-text-input').val();

			var questionId = $(this).parents('.ays-survey-chat-answer-box').attr('data-questionid');

			var questionBox = _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header').find('.ays-survey-chat-question-box[data-id='+questionId+']');

			setTimeout(function(){ 
				questionBox.next().find('.ays-survey-chat-question-animation-dots').addClass('active-chat-question');
				_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').scrollTop( _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').height() );
			}, 100);
		
			questionBox.find('.ays-survey-chat-question-reply').css({'display':'flex'});
			questionBox.find('.ays-survey-chat-question-reply-title').html('<span class="ays-survey-chat-question-title-content">'+clickedAnswer+'</span>');

			$(this).parents('.ays-survey-chat-answer-box').removeClass('active-chat-answer');
			

			setTimeout(function(){ 
				questionBox.next().find('.ays-survey-chat-question-animation-dots').removeClass('active-chat-question');
				questionBox.next().find('.ays-survey-chat-question-item').addClass('active-chat-question');
				_this.parents('.ays-survey-chat-answer-box').next().addClass('active-chat-answer');
				_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').scrollTop( _this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').height() );
				_this.parents('.ays-survey-chat-answer-box').next().find('.ays-survey-chat-short-text-input').focus();
			}, 2000);


			if($(this).parents('.ays-survey-chat-answer-box').is(':last-child')	){
				setTimeout(function(){ 
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-button-container').find('.ays-survey-finish-button').trigger('click');
					var dots = '<div class="ays-survey-chat-question-animation-dots ays-survey-chat-final-message-loader"> '
					+ '<div class="ays-survey-chat-question-pre-icon"><img src="'+aysSurveyChat.questionPrIconURL+'" ></div>'
					+ aysSurveyChat.chatLoader 
					+ '</div>';
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header-main').append(dots);
					_this.parents('.ays-survey-chat-content').find('.ays-survey-chat-header').find('.ays-survey-chat-final-message-loader').addClass('active-chat-loader');
					
			}, 1);
				
			}
			

		});

	});
	

})( jQuery );
