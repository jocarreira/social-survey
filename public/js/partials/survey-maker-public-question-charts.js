(function($){
    'use strict';
    $(document).ready(function(){

        var surveySubmissionSummaryContainers = $(document).find('.ays-survey-question-summary-container');
        var modalMatrixHTML = '';
        surveySubmissionSummaryContainers.each(function(){
            var uniqueId = $(this).data('id');
            // Survey per answer count
            var thisAysSurveyPublicChartData = JSON.parse( atob( window.aysSurveyPublicQuestionChartData[ uniqueId ] ) );
            
            modalMatrixHTML = thisAysSurveyPublicChartData.matrixModalHtml ? thisAysSurveyPublicChartData.matrixModalHtml : '';

            if ( typeof thisAysSurveyPublicChartData.surveyColor === 'undefined' || thisAysSurveyPublicChartData.surveyColor === null ) {
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
                    // case "matrix_scale":
                    //     forMatrixScaleType( this, thisAysSurveyPublicChartData );
                    // break;
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
    });
})(jQuery);