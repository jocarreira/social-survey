(function( $ ) {
	'use strict';
	$(document).find('.ays-survey-wait-loading-loader').css('display' , 'block');

    $(document).ready(function () {
		
		$(document).find('.ays-survey-container').AysSurveyMaker();
		
	});

})( jQuery );
