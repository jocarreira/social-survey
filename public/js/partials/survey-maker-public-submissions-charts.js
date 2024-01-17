(function($){
    'use strict';
    $(document).ready(function(){

        var surveySubmissionSummaryContainers = $(document).find('.ays-survey-submission-summary-container');
        var modalMatrixHTML = '';
        surveySubmissionSummaryContainers.each(function(){
            var uniqueId = $(this).data('id');
            // Survey per answer count
            var thisAysSurveyPublicChartData = JSON.parse( atob( window.aysSurveyPublicChartData[ uniqueId ] ) );
            
            modalMatrixHTML = thisAysSurveyPublicChartData.matrixModalHtml ? thisAysSurveyPublicChartData.matrixModalHtml : '';
           
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

        $(document).find('.ays-survey-submission-summary-question-content').scroll(function(e) {
            if($(this).scrollLeft() > 0){
                $(this).find('.ays-survey-answer-matrix-scale-row:not(:first-child) .ays-survey-answer-matrix-scale-column-row-header').addClass('ays-survey-answer-matrix-scale-column-row-header-scrolled');
            }
            else{
                $(this).find('.ays-survey-answer-matrix-scale-row:not(:first-child) .ays-survey-answer-matrix-scale-column-row-header').removeClass('ays-survey-answer-matrix-scale-column-row-header-scrolled');
            }
        });
        
    });
})(jQuery);