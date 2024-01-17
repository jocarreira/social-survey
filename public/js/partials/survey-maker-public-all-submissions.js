(function ($) {
    'use strict';    
    $(document).ready(function () {
        $(document).find('#ays_survey_all_submissions_page').DataTable({
        	paging: 5,
            "bDestroy": true,
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, surveyLangDataTableSubmissionsObj.all]],
        	"language": {
				"sEmptyTable":     surveyLangDataTableSubmissionsObj.sEmptyTable,
				"sInfo":           surveyLangDataTableSubmissionsObj.sInfo,
				"sInfoEmpty":      surveyLangDataTableSubmissionsObj.sInfoEmpty,
				"sInfoFiltered":   surveyLangDataTableSubmissionsObj.sInfoFiltered,
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     surveyLangDataTableSubmissionsObj.sLengthMenu,
				"sLoadingRecords": surveyLangDataTableSubmissionsObj.sLoadingRecords,
				"sProcessing":     surveyLangDataTableSubmissionsObj.sProcessing,
				"sSearch":         surveyLangDataTableSubmissionsObj.sSearch,
				"sUrl":            "",
				"sZeroRecords":    surveyLangDataTableSubmissionsObj.sZeroRecords,
				"oPaginate": {
					"sFirst":    surveyLangDataTableSubmissionsObj.sFirst,
					"sLast":     surveyLangDataTableSubmissionsObj.sLast,
					"sNext":     surveyLangDataTableSubmissionsObj.sNext,
					"sPrevious": surveyLangDataTableSubmissionsObj.sPrevious,
				},
				"oAria": {
					"sSortAscending":  surveyLangDataTableSubmissionsObj.sSortAscending,
					"sSortDescending": surveyLangDataTableSubmissionsObj.sSortDescending
				}
		    }
		});
    });
    
})(jQuery);
