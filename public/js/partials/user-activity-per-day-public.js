(function($){
    'use strict';
    $(document).ready(function(){

        // ===============================================================
        // ========================= Prefixes ============================
        // ===============================================================

        var $name_prefix       = 'ays-survey-';
        var $html_class_prefix = '.ays-survey-';
        var $html_id_prefix    = '#ays-survey-maker-';

        // ===============================================================
        // ========================= Prefixes ============================
        // ===============================================================
        if (typeof google != undefined) {
            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(initAnalyticsCharts);
        }
        
        $(document).find( $html_class_prefix + 'user-activity-per-day-container' ).hover( function(e){
            var _this  = $(this);

            var circle = _this.find('svg circle');
            if (circle.length == 1) {
                circle.attr('r',5);
                circle.attr('fill-opacity',1);
            }

        }, function(e) {
            var _this  = $(this);

            var circle = _this.find('svg circle');
            if (circle.length == 1) {
                circle.attr('r',5);
                circle.attr('fill-opacity',1);
            }
        });

        function initAnalyticsCharts() {

            var surveyUserActivityPerDayConainter = $(document).find( $html_class_prefix + 'user-activity-per-day-container');
            surveyUserActivityPerDayConainter.each( function( e, i ) {
                var _this = $(this);
                
                var uniqueId = _this.attr( 'data-id' );
                
                var thisAysSurveyPublicChartData = JSON.parse( atob( window.aysSurveyPublicActivityPerDayData[ uniqueId ] ) );
                
                var divId    = $html_id_prefix + 'user-activity-per-day-chart-' + uniqueId;
                var divClass = $html_class_prefix + 'user-activity-per-day-box';
                
                var chartDivId     = _this.find( divId );
                var chartDivIdAttr = _this.find( divClass ).attr('id');
                
                //Reports count per day
                var perData = thisAysSurveyPublicChartData;

                var data = new google.visualization.DataTable();
                data.addColumn('date', AysSurveyPagePerDayLangObj.date);
                data.addColumn('number', AysSurveyPagePerDayLangObj.count);

                for (var l = 0; l < perData.length; l++) {
                    perData[l] = new Array(
                        new Date(
                            perData[l][0]
                        ),
                        perData[l][1]
                    );
                }

                data.addRows(perData);

                var populationRange = data.getColumnRange(1);

                var logOptions = {
                    height: 400,
                    fontSize: 14,
                    interpolateNulls: true,
                    hAxis: {
                        title: AysSurveyPagePerDayLangObj.date,
                        format: 'MMM d Y',
                        gridlines: {count: 15}
                    },
                    vAxis: {
                        title: AysSurveyPagePerDayLangObj.count
                    },
                    viewWindow: {
                        min: thisAysSurveyPublicChartData[0],
                        max: thisAysSurveyPublicChartData[ thisAysSurveyPublicChartData.length - 1 ]
                    },
                    legend: {position: 'none'}
                };

                var logChart = new google.visualization.LineChart(document.getElementById( chartDivIdAttr ));
                logChart.draw(data, logOptions);

                if (thisAysSurveyPublicChartData.length == 1) {
                    setTimeout(function(){
                        var chartCircle = chartDivId.find( 'svg circl e' );
                        chartCircle.attr('r',5);
                        chartCircle.attr('fill-opacity',1);
                    },500);
                }
            });
        }
    });
})(jQuery);