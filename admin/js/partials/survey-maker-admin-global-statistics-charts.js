(function($){
    'use strict';
    $(document).ready(function(){

    	var surveySelect = $(document).find('#global_survey_stat_select');
	 	var surveySelect2 = surveySelect.select2({
			dropdownParent: surveySelect.parent(),
 		});

    	$(document).find('.ays_survey_global_stat_container').find('.ays_survey_stat_select_div').find('img.loader').removeClass('display_none');
    	
     	var ays_chart_survey = setInterval( function() {
	    	if (document.readyState === 'complete') {

	    		var survey_id = $(document).find('#global_survey_stat_select').val();
	    		
		        var action    = 'ays_survey_admin_ajax';
        		var afunction = 'get_current_survey_statistic';
		       
		        var data = {};

		        data.action = action;
		        data.function = afunction;
		        data.survey_id = survey_id;

		        $.ajax({
		            url: ajaxurl,
		            method: 'post',
		            dataType: 'json',
		            data: data,
		            success: function(response) {
		                if ( response.status && response.status === true ) {
	                		$(document).find('.ays-survey-submission-summary-question-content .ays-survey-submissions-statistics-message').remove();
		                    var perData = response.datesCounts;                
		                    for (var i = 0; i < perData.length; i++) {
		                        perData[i] = new Array(
		                            new Date(
		                                perData[i].submission_date
		                            ),
		                            parseInt(perData[i].count)
		                        );
		                    }

		                    globalStatisticsCharts(perData);
							$(document).find('.ays_survey_stat_select_div').find('img.loader').addClass('display_none');
		                }else{
		                	if( response.message && response.message != '' ){
		                		var message = '<div class="ays-survey-submissions-statistics-message">' + response.message + '</div>';
		                		$(document).find('.ays-survey-submission-summary-question-content').append( message );
		                	}
							$(document).find('.ays_survey_stat_select_div').find('img.loader').addClass('display_none');
						}
		            },
					error: function(){
						$(document).find('.ays_survey_stat_select_div').find('img.loader').addClass('display_none');
					}
		        });

	        }

	        clearInterval(ays_chart_survey);
        }, 500);


	    $(document).find('#global_survey_stat_select').on('change',function(e){
	        var survey_id = $(document).find('#global_survey_stat_select').val();
          	var action    = 'ays_survey_admin_ajax';
    		var afunction = 'get_current_survey_statistic';
		       
	        var data = {};

	        data.action = action;
	        data.function = afunction;
	        data.survey_id = survey_id;

	        var $this = $(this);
	        $this.parents('.ays_survey_global_stat_container').find('.ays_survey_stat_select_div').find('img.loader').removeClass('display_none');
	        $.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: data,
	            dataType: "json",
	            success: function (response) {
            		$(document).find('.ays-survey-submission-summary-question-content .ays-survey-submissions-statistics-message').remove();

            	  	if (response.status) {
		                $("#chart_glob_surveys_stat_div").empty();
		                var perData = response.datesCounts;                
	                    for (var i = 0; i < perData.length; i++) {
	                        perData[i] = new Array(
	                            new Date(
	                                perData[i].submission_date
	                            ),
	                            parseInt(perData[i].count)
	                        );
	                    }
		               	globalStatisticsCharts(perData);
	                
	                	$this.parents('.ays_survey_global_stat_container').find('.ays_survey_stat_select_div').find('img.loader').addClass('display_none');
	           		}else{
						$(document).find("#chart_glob_surveys_stat_div").empty();
	                	if( response.message && response.message != '' ){
	                		var message = '<div class="ays-survey-submissions-statistics-message">' + response.message + '</div>';
	                		$(document).find('.ays-survey-submission-summary-question-content').append( message );
	                	}
	                	$this.parents('.ays_survey_global_stat_container').find('.ays_survey_stat_select_div').find('img.loader').addClass('display_none');
					}
	            },
				error: function(){
					$(document).find("#chart_glob_surveys_stat_div").empty();
					$this.parents('.ays_survey_global_stat_container').find('.ays_survey_stat_select_div').find('img.loader').addClass('display_none');
				}
	        });
	    });


	    function globalStatisticsCharts(perData){
	    	google.charts.load('current', {
	        	packages: ['corechart']
	        }).then(function () {
	            var data = new google.visualization.DataTable();
	            data.addColumn('date', SurveyMakerAdmin.date);
	            data.addColumn('number', SurveyMakerAdmin.count);

	            data.addRows(perData);

	            var populationRange = data.getColumnRange(1);
				var chartParent = $(document).find('#chart_glob_surveys_stat_div').parents('.ays-survey-submission-summary-question-content');
	            var logOptions = {
	                // title: '',
	                // legend: 'none',
	                width: chartParent.width(),
	                height: chartParent.height(),
					
					chartArea: { 
						width: '80%',
						height: '80%'
					},
	                fontSize: 14,
	                hAxis: {
	                    title: SurveyMakerAdmin.date,
	                    format: 'MMM d',
	                    gridlines: {count: 15}
	                },
	                vAxis: {
	                    title: SurveyMakerAdmin.count
	                }
	            };

	            var logChart = new google.visualization.LineChart(document.getElementById('chart_glob_surveys_stat_div'));
	            logChart.draw(data, logOptions);

				function resizeChart () {
					
					var chartParent = $(document).find('#chart_glob_surveys_stat_div').parents('.ays-survey-submission-summary-question-content');
					var logOptions = {
						// title: '',
						// legend: 'none',
						width: chartParent.width(),
						height: chartParent.height(),
						
						chartArea: { 
							width: '70%',
							height: '80%'
						},
						fontSize: 14,
						hAxis: {
							title: SurveyMakerAdmin.date,
							format: 'MMM d',
							gridlines: {count: 15}
						},
						vAxis: {
							title: SurveyMakerAdmin.count,
						}
					};
					logChart.draw(data, logOptions);
				}

				if (document.addEventListener) {
					window.addEventListener('resize', resizeChart);
				}else if (document.attachEvent) {
					window.attachEvent('onresize', resizeChart);
				}else {
					window.resize = resizeChart;
				}

				$(window).trigger('resize');
	        });
	    }
	});
})(jQuery);

