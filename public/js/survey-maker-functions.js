// (function( $ ) {

function forRadioType( item, thisAysSurveyPublicChartData ){
    var questionId = item.question_id;
    var dataTypes = [[ aysSurveyPublicChartLangObj.answers ,  aysSurveyPublicChartLangObj.percent ]];
    for (var key in item.answers) {
        dataTypes.push([
            item.answerTitles[key] + '', +item.answers[key]
        ]);
    }
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {

        var data = google.visualization.arrayToDataTable(dataTypes);
        var options = {
            height: 250,
            fontSize: 14,
            chartArea: { 
                width: '80%',
                height: '80%'
            }
        };

        var chart = new google.visualization.PieChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

        chart.draw(data, options);

        //create trigger to resizeEnd event     
        jQuery(window).resize(function() {
            if(this.resizeTO) clearTimeout(this.resizeTO);
            this.resizeTO = setTimeout(function() {
                jQuery(this).trigger('resizeEnd');
            }, 500);
        });

        //redraw graph when window resize is completed  
        jQuery(window).on('resizeEnd', function() {
            chart.draw(data, options);
        });
    }
}

function forCheckboxType( item, thisAysSurveyPublicChartData ){
    var questionId = item.question_id;
    var dataTypes = [[ aysSurveyPublicChartLangObj.answers ,  aysSurveyPublicChartLangObj.count ]];
    var sum_of_answers_count = item.sum_of_answers_count;

    if( ! jQuery.isEmptyObject( item.answers ) ){
        for (var key in item.answers) {
            dataTypes.push([
                item.answerTitles[key] + '', item.answers[key]
            ]);
        }
    }else{
        dataTypes.push([
            '', 0
        ]);
    }

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {

        var data = google.visualization.arrayToDataTable(dataTypes);
        var groupData = google.visualization.data.group(
            data,
            [{column: 0, modifier: function () {return 'total'}, type:'string'}],
            [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}]
        );
        
        var formatPercent = new google.visualization.NumberFormat({
            pattern: '#%'
        });
    
        var formatShort = new google.visualization.NumberFormat({
            pattern: 'short'
        });
    
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1, {
            calc: function (dt, row) {
                if( jQuery.isEmptyObject( item.answers ) ){
                    return amount;
                }
                var amount =  formatShort.formatValue(dt.getValue(row, 1));
                if( sum_of_answers_count == 0 ){
                    var percent = 0;
                } else {
                    var percent = formatPercent.formatValue(dt.getValue(row, 1) / sum_of_answers_count);
                }
                return amount + ' (' + percent + ')';
            },
            type: 'string',
            role: 'annotation'
        }]);
    
        var options = {
            height: 300,
            fontSize: 14,
            chartArea: { 
                width: '50%',
                height: '80%'
            },
            annotations: {
                alwaysOutside: true
            },
            bars: 'horizontal',
            bar: { groupWidth: "50%" },
            colors: [ aysSurveyRgba2hex( thisAysSurveyPublicChartData.surveyColor ) ]
        };

        var chart = new google.visualization.BarChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

        chart.draw(view, options);

        //create trigger to resizeEnd event     
        jQuery(window).resize(function() {
            if(this.resizeTO) clearTimeout(this.resizeTO);
            this.resizeTO = setTimeout(function() {
                jQuery(this).trigger('resizeEnd');
            }, 500);
        });

        //redraw graph when window resize is completed  
        jQuery(window).on('resizeEnd', function() {
            chart.draw(view, options);
        });
    }
}

function forLinearScaleType( item, thisAysSurveyPublicChartData ){

    var questionId = item.question_id;
    var dataTypes = [[ aysSurveyPublicChartLangObj.answers ,  aysSurveyPublicChartLangObj.count , { role: 'style' }]];

    var xAxesFrom = "";
    var xAxesTo = "";
    var xAxesLengthMin = "1";
    var xAxesLengthMax = "";
    if(typeof item.labels != "undefined"){
        xAxesFrom =  typeof item.labels.from != "undefined" && item.labels.from != "" ? item.labels.from  : "";
        xAxesTo   =  typeof item.labels.to != "undefined" && item.labels.to != "" ? item.labels.to : "";
        xAxesLengthMax  =  typeof item.labels.length != "undefined" && item.labels.length != "" ? item.labels.length : "";
    }

    var xAxesLength = 5;
    if ( xAxesLengthMax !== "" ) {
        xAxesLength = parseInt(xAxesLengthMax);
    }

    var dataValues = {};

    for (var i=1; i <= xAxesLength; i++) {
        dataValues[i] = 0;
    }

    for (var key in item.answers[questionId]) {
        dataValues[ item.answers[questionId][key] ]++;
    }

    for (var key in dataValues) {
        dataTypes.push([
            key + '', parseInt( dataValues[key] ), thisAysSurveyPublicChartData.surveyColor
        ]);
    }
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable( dataTypes );
        var view = new google.visualization.DataView(data);
        
        var groupData = google.visualization.data.group(
            data,
            [{column: 0, modifier: function () {return 'total'}, type:'string'}],
            [{column: 1, aggregation: google.visualization.data.sum, type: 'number'}]
        );

        var formatPercent = new google.visualization.NumberFormat({
            pattern: '#%'
        });
    
        var formatShort = new google.visualization.NumberFormat({
            pattern: 'short'
        });
        
        view.setColumns([0, 1, {
            calc: function (dt, row) {
                if( jQuery.isEmptyObject( item.answers[questionId] ) ){
                    return amount;
                }
                var amount =  formatShort.formatValue(dt.getValue(row, 1));
                var percent = formatPercent.formatValue(dt.getValue(row, 1) / groupData.getValue(0, 1));

                if( percent == NaN || ( typeof percent == 'string' && percent == 'NaN' ) ){
                    percent = '0%';
                }

                return amount + ' (' + percent + ')';
            },
            sourceColumn: 1,
            type: "string",
            role: "annotation" 
        } ]);
        var chartTitle = xAxesFrom != "" && xAxesTo != "" ? xAxesLengthMin + " = " + xAxesFrom + " , " + xAxesLengthMax + " = " + xAxesTo : "";
        var options = {
            height: 300,
            fontSize: 14,
            title: chartTitle,
            titleTextStyle: {
                fontSize: 13,
                bold: false,
                italic: true
            },
            chartArea: { 
                width: '80%',
                height: '80%'
            },
            legend: {
                position: "none"
            },
            annotations: {
                alwaysOutside: true
            },
            bar: {
                groupWidth: "80%"
            },
            colors: [ aysSurveyRgba2hex( thisAysSurveyPublicChartData.surveyColor ) ]
        };

        var chart = new google.visualization.ColumnChart( document.getElementById( 'survey_answer_chart_' + questionId ) );
        chart.draw(view, options);

        
        //create trigger to resizeEnd event     
        jQuery(window).resize(function() {
            if(this.resizeTO) clearTimeout(this.resizeTO);
            this.resizeTO = setTimeout(function() {
                jQuery(this).trigger('resizeEnd');
            }, 500);
        });

        //redraw graph when window resize is completed  
        jQuery(window).on('resizeEnd', function() {
            chart.draw(view, options);
        });
    }
}

function forMatrixScaleType( item, thisAysSurveyPublicChartData ){
    var questionId = item.question_id;
    var results    = item.matrix_data;
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {

        var data = google.visualization.arrayToDataTable(results);
  
        var options = {
            isStacked: false,
            height: 300,
            fontSize: 14,
            chartArea: { 
                width: '90%',
                height: '80%'
            },
            legend: {
                position: 'top'
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById( 'survey_answer_chart_' + questionId ));
        chart.draw(data, options);
        
        //create trigger to resizeEnd event     
        jQuery(window).resize(function() {
            if(this.resizeTO) clearTimeout(this.resizeTO);
            this.resizeTO = setTimeout(function() {
                jQuery(this).trigger('resizeEnd');
            }, 500);
        });

        //redraw graph when window resize is completed  
        jQuery(window).on('resizeEnd', function() {
            chart.draw(data, options);
        });
    }
}

function forMatrixScaleTypeCustom( item, thisAysSurveyPublicChartData ){
    var questionId = item.question_id;
    var martixDataColumns = new Array();
    var martixDataAnswers = item.allAnswers;
    if(item.matrix_data){
        martixDataColumns = item.matrix_data[0].length > 0 ? item.matrix_data[0] : martixDataColumns;
    }
    var martixDataColumnsForRows = item.matrix_data;
    martixDataColumnsForRows.shift();
    var content = '<div class="ays_questions_answers">';
            content += '<div class="ays-survey-answer-matrix-scale-main ays-survey-answer-matrix-scale-main-chart">';
                content += '<div class="ays-survey-answer-matrix-scale-container">';
                    content += '<div class="ays-survey-answer-matrix-scale-row ays-survey-answer-matrix-scale-row-chart-columns">';
                    for(var columnKey in martixDataColumns){
                        content += '<div class="ays-survey-answer-matrix-scale-column" title="'+martixDataColumns[columnKey]+'">' + aysSurveyRestrictionString(martixDataColumns[columnKey], 3) + '</div>';
                    }
                    content += '</div>';
                    content += '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
                    for(var rowKey in martixDataColumnsForRows){
                        content += '<div class="ays-survey-answer-matrix-scale-row ays-survey-answer-matrix-scale-row-chart">';
                            content += '<div class="ays-survey-answer-matrix-scale-row-content">';
                                content += '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header ays-survey-answer-matrix-scale-column-row-header-chart" title="'+martixDataColumnsForRows[rowKey][0]+'">'+ aysSurveyRestrictionString( martixDataColumnsForRows[rowKey][0], 5 ) +'</div>';
                                martixDataColumnsForRows[rowKey].shift();
                                var eachAnswerAllCount = martixDataAnswers[rowKey];
                                for(var rowInnerKey in martixDataColumnsForRows[rowKey]){
                                    var percent = 0;
                                    if(martixDataColumnsForRows[rowKey][rowInnerKey] > 0 && eachAnswerAllCount > 0){
                                        percent = ((martixDataColumnsForRows[rowKey][rowInnerKey] * 100) / eachAnswerAllCount);
                                        if( aysSurveyIsFloat( percent ) ){
                                            percent = percent.toFixed(1);
                                        }
                                    }
                                    content +=  '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-chart" style="background-color:rgb(255, 87, 34, '+percent+'%);" title="'+percent+'%">'+martixDataColumnsForRows[rowKey][rowInnerKey]+'</div>';
                                }
                            content += '</div>';
                        content += '</div>';
                        content += '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
                    }
                content += '</div>';
            content += '</div>';
       content += '</div>';

       jQuery(document).find( '#survey_answer_chart_' + questionId ).html(content);
}

function forStarListTypeCustom( item ){
    var questionId = item.question_id;
    var StarListAllAnswers = item.answerTitles;
    var StarListChangeableAnswers = typeof item.star_list_data != "undefined" ? item.star_list_data[questionId] : "undefined";

    if(typeof StarListChangeableAnswers != "undefined"){
        var starsLength = parseInt(item.question_options);
        var content = '<div class="ays_questions_answers">';
                content += '<div class="ays-survey-answer-star-list-main ays-survey-answer-star-list-main-chart">';
                    content += '<div class="ays-survey-answer-star-list-container">';
                        content += '<div class="ays-survey-answer-star-list-row ays-survey-answer-star-list-row-chart-columns">';
                        
                            content += '<div class="ays-survey-answer-star-list-column" title=""></div>';
                            content += '<div class="ays-survey-answer-star-list-column" title="" style="color: #000">Rating</div>';
                            content += '<div class="ays-survey-answer-star-list-column" title="" style="color: #000">Stars count</div>';
                        
                        content += '</div>';
                        content += '<div class="ays-survey-answer-star-list-row-spacer"></div>';
                        for(var rowKey in StarListAllAnswers){
                            var averageSum = 0;
                                var currentAnswer = typeof StarListChangeableAnswers[rowKey] != "undefined" ? StarListChangeableAnswers[rowKey] : [];
                                if(currentAnswer == ""){
                                    currentAnswer['answered_sum'] = 0;
                                    currentAnswer['answered_count'] = 0;
                                }
                                if(currentAnswer.answered_sum > 0 && currentAnswer.answered_count > 0){
                                    averageSum = (currentAnswer.answered_sum/currentAnswer.answered_count).toFixed(1);
                                }
                                    content += '<div class="ays-survey-answer-star-list-row ays-survey-answer-star-list-row-chart">';
                                        content += '<div class="ays-survey-answer-star-list-row-content">';
                                            content += '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header ays-survey-answer-star-list-column-row-header-chart" style="color: #000" title="'+StarListAllAnswers[rowKey]+'">'+aysSurveyRestrictionString(StarListAllAnswers[rowKey], 5)+'</div>';                                                
                                                content +=  '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;font-weight: 600; font-size: 17px; color: #000;" >'+averageSum+'</div>';
                                                content +=  '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;position:relative;width:15%;text-align:initial;padding: 0 18px; color: #000;">';
                                                    var starsAverageSum = (averageSum*100)/starsLength;
                                                    content += getStars(starsLength,starsAverageSum);
                                                content += '</div>';
                                        content += '</div>';
                                    content += '</div>';
                                    content += '<div class="ays-survey-answer-star-list-row-spacer"></div>';
                            
                        }
                    content += '</div>';
                content += '</div>';
        content += '</div>';

        jQuery(document).find( '#survey_answer_chart_' + questionId ).html(content);
    }
}

function forSliderListTypeCustom( item ){
    var questionId = item.question_id;
    var sliderListAllAnswers = item.answerTitles;
    var sliderListChangeableAnswers = item.slider_list_data[questionId];
    
    if(typeof sliderListChangeableAnswers != "undefined"){
        var content = '<div class="ays_questions_answers">';
                content += '<div class="ays-survey-answer-slider-list-main ays-survey-answer-slider-list-main-chart">';
                    content += '<div class="ays-survey-answer-slider-list-container">';
                        content += '<div class="ays-survey-answer-slider-list-row ays-survey-answer-slider-list-row-chart-columns">';
                        
                            content += '<div class="ays-survey-answer-slider-list-column" title=""></div>';
                            content += '<div class="ays-survey-answer-slider-list-column" title="" style="color: #000">Average</div>';
                        
                        content += '</div>';
                        content += '<div class="ays-survey-answer-slider-list-row-spacer"></div>';
                        for(var rowKey in sliderListAllAnswers){
                            var averageSum = 0;
                                var currentAnswer = typeof sliderListChangeableAnswers[rowKey] != "undefined" ? sliderListChangeableAnswers[rowKey] : [];
                                if(currentAnswer == ""){
                                    currentAnswer['answered_sum'] = 0;
                                    currentAnswer['answered_count'] = 0;
                                }
                                if(currentAnswer.answered_sum > 0 && currentAnswer.answered_count > 0){
                                    averageSum = (currentAnswer.answered_sum/currentAnswer.answered_count).toFixed(1);
                                }
                                    content += '<div class="ays-survey-answer-slider-list-row ays-survey-answer-slider-list-row-chart">';
                                        content += '<div class="ays-survey-answer-slider-list-row-content">';
                                            content += '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header ays-survey-answer-star-list-column-row-header-chart" style="color: #000" title="'+sliderListAllAnswers[rowKey]+'">'+aysSurveyRestrictionString(sliderListAllAnswers[rowKey], 5)+'</div>';                                                
                                                content +=  '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;font-weight: 600; font-size: 17px; color: #000;" >'+averageSum+'</div>';
                                        content += '</div>';
                                    content += '</div>';
                                    content += '<div class="ays-survey-answer-slider-list-row-spacer"></div>';
                            
                        }
                    content += '</div>';
                content += '</div>';
        content += '</div>';

        jQuery(document).find( '#survey_answer_chart_' + questionId ).html(content);
    }
}

function getStars(lenght,averagePercent){


    var content = "";
        content += '<div class="ays-survey-star-list-chart-star-box">';
            content += '<div class="ays-survey-star-list-chart-star-box-wrap">';
                content += '<span class="ays-survey-star-list-chart-stars-active" style="width:'+averagePercent+'%">';
                for (var i = 1; i <= lenght; i++) {
                    content += '<i class="ays-fa ays-fa-star" aria-hidden="true"></i>';
                }
                content += '</span>';
        
                content += '<span class="ays-survey-star-list-chart-stars-inactive">';
                for (var i = 1; i <= lenght; i++) {
                    content += '<i class="ays-fa ays-fa-star-o"></i>';
                }
                content += '</span>';
            content += '</div>';
        content += '</div>';
        return content;
}

function forRangeType( item, thisAysSurveyPublicChartData ){
    var questionId = item.question_id;
    var results = item.answers[questionId] ? item.answers[questionId] : new Array();
    var readyRes = new Array();
    for(var i = 0; i < results.length; i++){
        readyRes.push([(i + 1) , parseInt(results[i])]);
    }
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('number' , 'X' );
        data.addColumn('number' , 'Answer' );
        data.addRows(readyRes);
        var options = {
            height: 300,
            fontSize: 14,
            chartArea: { 
                width: '90%',
                height: '80%'
            },
            legend: {
                position: "none"
            },
            annotations: {
                alwaysOutside: true
            },
            bar: {
                groupWidth: "80%"
            },
            colors: [ '#FF5722' ]
        };
        var chart = new google.visualization.LineChart(document.getElementById( 'survey_answer_chart_' + questionId ));
        chart.draw(data, options);
        
        //create trigger to resizeEnd event     
        jQuery(window).resize(function() {
            if(this.resizeTO) clearTimeout(this.resizeTO);
            this.resizeTO = setTimeout(function() {
                jQuery(this).trigger('resizeEnd');
            }, 500);
        });

        //redraw graph when window resize is completed  
        jQuery(window).on('resizeEnd', function() {
            chart.draw(data, options);
        });
    }
}

function aysSurveyRestrictionString(word, length){
    var output = "";
    var res = word.split(" ");
    if(res.length <= length){
        output = res.join(" ");
    } else {
        res = res.slice(0,length);
        output = res.join(" ") + '...';
    }
    return output;
}

function aysSurveyIsFloat(n){
    return Number(n) === n && n % 1 !== 0;
}

function aysSurveyRgba2hex(orig) {
    if( orig.indexOf('#') !== -1 ){
        return orig;
    }

    var a, isPercent,
      rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
      alpha = (rgb && rgb[4] || "").trim(),
      hex = rgb ?
      (rgb[1] | 1 << 8).toString(16).slice(1) +
      (rgb[2] | 1 << 8).toString(16).slice(1) +
      (rgb[3] | 1 << 8).toString(16).slice(1) : orig;
  
    if (alpha !== "") {
      a = alpha;
    } else {
      a = 0o1;
    }

    // multiply before convert to HEX
    a = ''; //((a * 255) | 1 << 8).toString(16).slice(1)

    if( hex == '000000' || hex == '000' ){
        hex = '3366cc';
    }

    hex = '#' + hex + a;
  
    return hex;
}

function resizeChart(chart, data, options){
    
    //create trigger to resizeEnd event     
    jQuery(window).resize(function() {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
            jQuery(this).trigger('resizeEnd');
        }, 100);
    });

    //redraw graph when window resize is completed  
    jQuery(window).on('resizeEnd', function() {
        chart.draw(data, options);
    });
    
}

jQuery.fn.aysModal = function(action){
    var $this = jQuery(this);
    switch(action){
        case 'hide':
            jQuery(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
            setTimeout(function(){
                jQuery(document.body).removeClass('modal-open');
                jQuery(document).find('.ays-modal-backdrop').remove();
                $this.hide();
            }, 250);
            break;
        case 'show':
        default:
            $this.show();
            jQuery(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
            jQuery(document).find('.modal-backdrop').remove();
            jQuery(document.body).append('<div class="ays-modal-backdrop"></div>');
            jQuery(document.body).addClass('modal-open');
            break;
    }
}
// })( jQuery );
