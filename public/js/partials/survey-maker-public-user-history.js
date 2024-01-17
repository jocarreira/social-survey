(function ($) {
    'use strict';    
    
    $(document).ready(function () {
        $(document).find('.ays_survey_user_history').DataTable({
        	paging: 5,
            "bDestroy": true,
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, surveyLangDataTableObj.all]],
            "language": {
                "sEmptyTable":     surveyLangDataTableObj.sEmptyTable,
                "sInfo":           surveyLangDataTableObj.sInfo,
                "sInfoEmpty":      surveyLangDataTableObj.sInfoEmpty,
                "sInfoFiltered":   surveyLangDataTableObj.sInfoFiltered,
                "sInfoPostFix":    "",
                "sInfoThousands":  ",",
                "sLengthMenu":     surveyLangDataTableObj.sLengthMenu,
                "sLoadingRecords": surveyLangDataTableObj.sLoadingRecords,
                "sProcessing":     surveyLangDataTableObj.sProcessing,
                "sSearch":         surveyLangDataTableObj.sSearch,
                "sUrl":            "",
                "sZeroRecords":    surveyLangDataTableObj.sZeroRecords,
                "oPaginate": {
                    "sFirst":    surveyLangDataTableObj.sFirst,
                    "sLast":     surveyLangDataTableObj.sLast,
                    "sNext":     surveyLangDataTableObj.sNext,
                    "sPrevious": surveyLangDataTableObj.sPrevious,
                },
                "oAria": {
                    "sSortAscending":  surveyLangDataTableObj.sSortAscending,
                    "sSortDescending": surveyLangDataTableObj.sSortDescending
                }
            }
        });
    });
    
})(jQuery);