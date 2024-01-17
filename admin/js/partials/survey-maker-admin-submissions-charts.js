(function($){
    'use strict';
    $(document).ready(function(){

        var isInitializedAnalyticsCharts = false;
        var isInitializedSummaryCharts = false;


        // Survey per answer count 
        var perAnswerData = SurveyChartData.perAnswerCount;
        var contValueSummary = $('div#statistics_of_answer').css('display');
        if( contValueSummary == 'block' ){
            $.each(perAnswerData, function(){
                switch( this.question_type ){
                    case "radio":
                    case "yesorno":
                        forRadioType( this );
                    break;
                    case "checkbox":
                        forCheckboxType( this );
                    break;
                    case "select":
                        forRadioType( this );
                    break;
                    case "linear_scale":
                        forLinearScaleType( this );
                    break;
                    case "star":
                        forLinearScaleType( this );
                    break;
                    case "matrix_scale":
                    case "matrix_scale_checkbox":
                        // forMatrixScaleType( this );
                        forMatrixScaleTypeCustom( this );
                    break;
                    case "star_list":
                        forStarListTypeCustom( this );
                    break;
                    case "slider_list":
                        forSliderListTypeCustom( this );
                    break;
                    case "range":
                        forRangeType( this );
                    break;
                    case "upload":
                        forUploadType( this );
                    break;
                }
            });
        }


        function forRadioType( item ){
            var questionId = item.question_id;
            var dataTypes = [[SurveyMakerAdmin.answers, SurveyMakerAdmin.percent]];
            for (var key in item.answers) {
                dataTypes.push([
                    item.answerTitles[key] + '', item.answers[key]
                ]);
            }
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            
            function drawChart() {

                var data = google.visualization.arrayToDataTable(dataTypes);
                var options = {
                    height: 250,
                    fontSize: 16,
                    chartArea: { 
                        width: '80%',
                        height: '80%'
                    }
                };

                var chart = new google.visualization.PieChart( document.getElementById( 'survey_answer_chart_' + questionId ) );
                
                chartToImgDownload(chart,questionId);

                chart.draw(data, options);
                resizeChart(chart, data, options);
            }
        }
        
        function forCheckboxType( item ){
            var questionId = item.question_id;
            var dataTypes = [[SurveyMakerAdmin.answers, SurveyMakerAdmin.count]];
            var sum_of_answers_count = item.sum_of_answers_count;

            if( ! $.isEmptyObject( item.answers ) ){
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
                switch (SurveyChartData.submissionsOrderType){
                    case 'by_asc':
                        data.sort({column: 1, desc: false});
                        break;
                    case 'by_desc':
                        data.sort({column: 1, desc: true});
                        break;
                }
                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1, {
                    calc: function (dt, row) {
                        if( $.isEmptyObject( item.answers ) ){
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
                    // width: 700,
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
                    colors: [ detectColor(SurveyChartData.chartColor) ]
                };

                var chart = new google.visualization.BarChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

                chartToImgDownload(chart,questionId);
                chart.draw(view, options);
                resizeChart(chart, view, options);
            }
        }

        function forLinearScaleType( item ){

            var questionId = item.question_id;
            var dataTypes = [[SurveyMakerAdmin.answers, SurveyMakerAdmin.count, { role: 'style' }]];

            var xAxesFrom = "";
            var xAxesTo = "";
            var xAxesLengthMin = "1";
            var xAxesLengthMax = "";
            if(typeof item.labels != "undefined"){
                xAxesFrom       =  typeof item.labels.from != "undefined" && item.labels.from != "" ? item.labels.from  : "";
                xAxesTo         =  typeof item.labels.to != "undefined" && item.labels.to != "" ? item.labels.to : "";
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
                    key + '', parseInt( dataValues[key] ), "#FF5722"
                ]);
            }
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable( dataTypes );
                switch (SurveyChartData.submissionsOrderType){
                    case 'by_asc':
                        data.sort({column: 1, desc: false});
                        break;
                    case 'by_desc':
                        data.sort({column: 1, desc: true});
                        break;
                }

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
                        if( $.isEmptyObject( item.answers[questionId] ) ){
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
                    // width: 700,
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
                    colors: [ detectColor(SurveyChartData.chartColor) ]
                };

                var chart = new google.visualization.ColumnChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

                chartToImgDownload(chart,questionId);
                chart.draw(view, options);
                resizeChart(chart, view, options);
            }
        }

        function forMatrixScaleType( item ){
            var questionId = item.question_id;
            var results    = item.matrix_data;
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            
            function drawChart() {

                var data = google.visualization.arrayToDataTable(results);
          
                var options = {
                    isStacked: false,
                    width: 700,
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
            }
        }

        function forMatrixScaleTypeCustom( item ){
            var questionId = item.question_id;
            var questionType = (item.question_type) ? item.question_type : '';
            var martixDataColumns = new Array();
            var martixDataAnswers = item.allAnswers;
            if(item.matrix_data){
                martixDataColumns = item.matrix_data[0].length > 0 ? item.matrix_data[0] : martixDataColumns;
            }
            
            var classForAnswerText;
            switch(questionType){
                case "matrix_scale":
                    classForAnswerText = 'ays-survey-answer-matrix-scale-column-text-circle';
                    break;
                case "matrix_scale_checkbox":
                    classForAnswerText = 'ays-survey-answer-matrix-scale-column-text-square';
                    break;
            }

            if( SurveyChartData.matrixShowType == "by_percentage" ){
                classForAnswerText = 'ays-survey-answer-matrix-scale-column-text-percnet';
            }
            
            var martixDataColumnsForRows = item.matrix_data.slice();
            martixDataColumnsForRows.shift();
            var content = '<div class="ays_questions_answers">';
                    content += '<div class="ays-survey-answer-matrix-scale-main ays-survey-answer-matrix-scale-main-chart">';
                        content += '<div class="ays-survey-answer-matrix-scale-container">';
                            content += '<div class="ays-survey-answer-matrix-scale-row ays-survey-answer-matrix-scale-row-chart-columns">';
                            for(var columnKey in martixDataColumns){
                                content += '<div class="ays-survey-answer-matrix-scale-column" title="'+martixDataColumns[columnKey]+'">'+surveyRestrictionRtring(martixDataColumns[columnKey], 3)+'</div>';
                            }
                            content += '</div>';
                            content += '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
                            for(var rowKey in martixDataColumnsForRows){
                                content += '<div class="ays-survey-answer-matrix-scale-row ays-survey-answer-matrix-scale-row-chart">';
                                    content += '<div class="ays-survey-answer-matrix-scale-row-content">';
                                        content += '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header ays-survey-answer-matrix-scale-column-row-header-chart" title="'+martixDataColumnsForRows[rowKey][0]+'">'+surveyRestrictionRtring(martixDataColumnsForRows[rowKey][0], 5)+'</div>';
                                        martixDataColumnsForRows[rowKey] = martixDataColumnsForRows[rowKey].slice();
                                        martixDataColumnsForRows[rowKey].shift();
                                        var eachAnswerAllCount = martixDataAnswers[rowKey];
                                        for(var rowInnerKey in martixDataColumnsForRows[rowKey]){
                                            var percent = 0;
                                            if(martixDataColumnsForRows[rowKey][rowInnerKey] > 0 && eachAnswerAllCount > 0){
                                                percent = ((martixDataColumnsForRows[rowKey][rowInnerKey] * 100) / eachAnswerAllCount);
                                                if(isFloat(percent)){
                                                    percent = percent.toFixed(1);
                                                }
                                            }
                                            var matrixColor;
                                            var matrixTextColor = "#000";
                                            if(percent == 0){
                                                matrixColor = "#fff";
                                                
                                            }
                                            else{
                                                matrixColor = detectColor(SurveyChartData.chartColor , percent);
                                            }

                                            if(matrixColor != "#fff"){
                                                matrixTextColor = "#fff";
                                            }
                                            var answersCount = martixDataColumnsForRows[rowKey][rowInnerKey] > 0 ? martixDataColumnsForRows[rowKey][rowInnerKey] : '';
                                            
                                            content +=  '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-chart" style="background-color:'+matrixColor+'; " title="'+(SurveyChartData.matrixShowType == "by_votes" ? percent+'%' : martixDataColumnsForRows[rowKey][rowInnerKey] + " (Votes)")+'">'+(SurveyChartData.matrixShowType == "by_votes" ? "<span class='"+classForAnswerText+"'>" + answersCount + "</span>" :  "<span class='"+classForAnswerText+"'>" +  percent+'%')+ "</span>" +'</div>';
                                        }
                                    content += '</div>';
                                content += '</div>';
                                content += '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
                            }
                        content += '</div>';
                    content += '</div>';
               content += '</div>';

               $(document).find( '#survey_answer_chart_' + questionId ).html(content);
        }

        function forStarListTypeCustom( item ){
            var questionId = item.question_id;
            var StarListAllAnswers = item.answerTitles;
            var StarListChangeableAnswers = item.star_list_data[questionId];
            var makeDataSortable = [];
            for(var rowKey in StarListChangeableAnswers){
                makeDataSortable.push(StarListChangeableAnswers[rowKey]);
            }
            
            switch (SurveyChartData.submissionsOrderType){
                case 'by_asc':
                    makeDataSortable.sort(function(a, b) {
                        return  a.answered_sum - b.answered_sum;
                    });
                    break;
                case 'by_desc':
                    makeDataSortable.sort(function(a, b) {
                        return  b.answered_sum - a.answered_sum ;
                    });
                    break;
            }
            if(typeof StarListChangeableAnswers != "undefined"){
                var starsLength = parseInt(item.question_options);
                var content = '<div class="ays_questions_answers">';
                        content += '<div class="ays-survey-answer-star-list-main ays-survey-answer-star-list-main-chart">';
                            content += '<div class="ays-survey-answer-star-list-container">';
                                content += '<div class="ays-survey-answer-star-list-row ays-survey-answer-star-list-row-chart-columns">';
                                
                                    content += '<div class="ays-survey-answer-star-list-column" title=""></div>';
                                    content += '<div class="ays-survey-answer-star-list-column" title="">'+ SurveyMakerAdmin.rating +'</div>';
                                    content += '<div class="ays-survey-answer-star-list-column" title="" >'+ SurveyMakerAdmin.stars_count +'</div>';
                                
                                content += '</div>';
                                content += '<div class="ays-survey-answer-star-list-row-spacer"></div>';
                                var i = 0;
                                for(var rowKey in StarListAllAnswers){
                                    var averageSum = 0;
                                        var currentAnswer = typeof makeDataSortable[i] != "undefined" ? makeDataSortable[i] : [];
                                        if(currentAnswer == ""){
                                            currentAnswer['answered_sum'] = 0;
                                            currentAnswer['answered_count'] = 0;
                                        }
                                        if(currentAnswer.answered_sum > 0 && currentAnswer.answered_count > 0){
                                            averageSum = (currentAnswer.answered_sum/currentAnswer.answered_count).toFixed(1);
                                        }
                                            content += '<div class="ays-survey-answer-star-list-row ays-survey-answer-star-list-row-chart">';
                                                content += '<div class="ays-survey-answer-star-list-row-content">';
                                                    content += '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header ays-survey-answer-star-list-column-row-header-chart" title="'+StarListAllAnswers[makeDataSortable[i].answer_id]+'">'+surveyRestrictionRtring(StarListAllAnswers[makeDataSortable[i].answer_id], 5)+'</div>';                                                
                                                        content +=  '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;font-weight: 600; font-size: 17px;" >'+averageSum+'</div>';
                                                        content +=  '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;position:relative;width:15%;text-align:initial;padding: 0 18px;">';
                                                            var starsAverageSum = (averageSum*100)/starsLength;
                                                            content += getStars(starsLength,starsAverageSum);
                                                        content += '</div>';
                                                content += '</div>';
                                            content += '</div>';
                                            content += '<div class="ays-survey-answer-star-list-row-spacer"></div>';
                                            i++;
                                    
                                }
                            content += '</div>';
                        content += '</div>';
                content += '</div>';

                $(document).find( '#survey_answer_chart_' + questionId ).html(content);
            }
        }

        function forSliderListTypeCustom( item ){
            var questionId = item.question_id;
            var sliderListAllAnswers = item.answerTitles;
            var sliderListChangeableAnswers = item.slider_list_data[questionId];
            var makeDataSortable = [];
            for(var rowKey in sliderListChangeableAnswers){
                makeDataSortable.push(sliderListChangeableAnswers[rowKey]);
            }
            
            switch (SurveyChartData.submissionsOrderType){
                case 'by_asc':
                    makeDataSortable.sort(function(a, b) {
                        return  a.answered_sum - b.answered_sum;
                    });
                    break;
                case 'by_desc':
                    makeDataSortable.sort(function(a, b) {
                        return  b.answered_sum - a.answered_sum ;
                    });
                    break;
            }

            if(typeof sliderListChangeableAnswers != "undefined"){
                var content = '<div class="ays_questions_answers">';
                        content += '<div class="ays-survey-answer-slider-list-main ays-survey-answer-slider-list-main-chart">';
                            content += '<div class="ays-survey-answer-slider-list-container">';
                                content += '<div class="ays-survey-answer-slider-list-row ays-survey-answer-slider-list-row-chart-columns">';
                                
                                    content += '<div class="ays-survey-answer-slider-list-column" title=""></div>';
                                    content += '<div class="ays-survey-answer-slider-list-column" title="">'+ SurveyMakerAdmin.average +'</div>';
                                
                                content += '</div>';
                                content += '<div class="ays-survey-answer-slider-list-row-spacer"></div>';
                                var i = 0;
                                for(var rowKey in sliderListAllAnswers){
                                    var averageSum = 0;
                                        var currentAnswer = typeof makeDataSortable[i] != "undefined" ? makeDataSortable[i] : [];
                                        if(currentAnswer == ""){
                                            currentAnswer['answered_sum'] = 0;
                                            currentAnswer['answered_count'] = 0;
                                        }
                                        if(currentAnswer.answered_sum > 0 && currentAnswer.answered_count > 0){
                                            averageSum = (currentAnswer.answered_sum/currentAnswer.answered_count).toFixed(1);
                                        }
                                            content += '<div class="ays-survey-answer-slider-list-row ays-survey-answer-slider-list-row-chart">';
                                                content += '<div class="ays-survey-answer-slider-list-row-content">';
                                                    content += '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header ays-survey-answer-star-list-column-row-header-chart" title="'+sliderListAllAnswers[makeDataSortable[i].answer_id]+'">'+surveyRestrictionRtring(sliderListAllAnswers[makeDataSortable[i].answer_id], 5)+'</div>';                                                
                                                        content +=  '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;font-weight: 600; font-size: 17px;" >'+averageSum+'</div>';
                                                content += '</div>';
                                            content += '</div>';
                                            content += '<div class="ays-survey-answer-slider-list-row-spacer"></div>';
                                    i++;
                                }
                            content += '</div>';
                        content += '</div>';
                content += '</div>';

                $(document).find( '#survey_answer_chart_' + questionId ).html(content);
            }
        }

        function getStars(lenght,averagePercent){


            var content = "";
                content += '<div class="ays-survey-star-list-chart-star-box">';
                    content += '<div class="ays-survey-star-list-chart-star-box-wrap">';
                        content += '<span class="ays-survey-star-list-chart-stars-active" style="width:'+averagePercent+'%">';
                        for (var i = 1; i <= lenght; i++) {
                            content += '<i class="ays_fa ays_fa_star" aria-hidden="true"></i>';
                        }
                        content += '</span>';
                
                        content += '<span class="ays-survey-star-list-chart-stars-inactive">';
                        for (var i = 1; i <= lenght; i++) {
                            content += '<i class="ays_fa ays_fa_star_o"></i>';
                        }
                        content += '</span>';
                    content += '</div>';
                content += '</div>';
                return content;
        }

        function forRangeType( item ){
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
                    width: 700,
                    height: 300,
                    fontSize: 14,
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
                    colors: [ detectColor(SurveyChartData.chartColor) ]
                };
                var chart = new google.visualization.LineChart(document.getElementById( 'survey_answer_chart_' + questionId ));

                chartToImgDownload(chart,questionId);
                chart.draw(data, options);
                resizeChart(chart, data, options);
            }
        }

        function forUploadType( item ){
            if(typeof item != "undefined"){
                var questionId = item.question_id;
                var uploadAnswers = item.answers[questionId] ? item.answers[questionId] : {};
                var uploadAnswersName = item.answers_name[questionId];
                var content = '<div class="ays_questions_upload_type_answers_summary">';
                        for(var answer in uploadAnswers){
                            if(uploadAnswers[answer] && uploadAnswersName[answer]){
                                content += '<div class="ays-survey-answer-upload-ready-summary">';
                                    content += '<a href="'+uploadAnswers[answer]+'" class="ays-survey-answer-upload-ready-link-summary" download="'+uploadAnswersName[answer]+'">'+uploadAnswersName[answer]+'</a>';                                            
                                content += '</div>';
                            }
                        }
                content += '</div>';

                $(document).find( '#survey_answer_chart_' + questionId ).html(content);
            }
        }

        function surveyRestrictionRtring(word, length){
            
            if( typeof word != 'string' || word === null ){
                word = '';
            }
            
            var output = "";
            var res = word.split(" ");
            if(res.length <= length){
                output = res.join(" ");
            }
            else {
                res = res.slice(0,length);
                output = res.join(" ") + '...';
            }
            return output;
        }
        
        function isFloat(n){
            return Number(n) === n && n % 1 !== 0;
        }

        // AV Google charts
        $(document).find('.nav-tab').on('click', function(){
            var _this = this;
            setTimeout(function(){
                initAnalyticsCharts( _this );
            }, 500);
        });
        
        function initAnalyticsCharts( _this ) {
            var contValue = $('div#statistics').css('display');
            var contValueSummary = $('div#statistics_of_answer').css('display');
            
            if( !isInitializedAnalyticsCharts ){
                if (_this.getAttribute('href') == '#statistics' && contValue == 'block') {

                    //Reports count per day
                    var perData = SurveyChartData.countPerDayData;                
                    for (var l = 0; l < perData.length; l++) {
                        perData[l] = new Array(
                            new Date(
                                perData[l][0]
                            ),
                            perData[l][1]
                        );
                    }

                    google.charts.load('current', {
                      packages: ['corechart']
                    }).then(function () {
                        var data = new google.visualization.DataTable();
                        data.addColumn('date', SurveyMakerAdmin.date);
                        data.addColumn('number', SurveyMakerAdmin.count);

                        data.addRows(perData);

                        var populationRange = data.getColumnRange(1);

                        var logOptions = {
                            // title: '',
                            // legend: 'none',
                            width: 700,
                            height: 300,
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

                        var logChart = new google.visualization.LineChart(document.getElementById('survey_chart1_div'));
                        logChart.draw(data, logOptions);
                    });

                    // Survey passed users
                    var userCount = SurveyChartData.usersCount;
                    var dataTypes = [[SurveyMakerAdmin.users, SurveyMakerAdmin.percent]];

                    if(parseInt(userCount.guests) !== 0){
                        dataTypes.push([
                            SurveyMakerAdmin.guests, parseInt(userCount.guests)
                        ]);
                    }

                    if(parseInt(userCount.loggedIn) !== 0){
                        for (var m = 0; m < userCount.userRoles.length; m++) {
                            dataTypes.push([
                                userCount.userRoles[m].type, parseInt(userCount.userRoles[m].percent)
                            ]);
                        }
                    }

                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                        var data = google.visualization.arrayToDataTable(dataTypes);

                        var options = {
                          // title: 'My Daily Activities'
                            width: 700,
                            height: 300,
                            fontSize: 16,
                            chartArea: { 
                                width: '80%',
                                height: '80%',
                            }
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('survey_chart2_div'));

                        chart.draw(data, options);
                        resizeChart(chart, data, options);
                    }

                    isInitializedAnalyticsCharts = true;

                    // Device chart
                    var devicesCount = SurveyChartData.deviceCountArr;

                    var aysDevicesBarChartData = new Array(['', '']);
                    for (var count in devicesCount) {
                        aysDevicesBarChartData.push([
                          count,
                          parseInt(devicesCount[count])
                        ]);
                    }

                    google.charts.load('current', {packages: ['corechart', 'bar']});
                    google.charts.setOnLoadCallback(drawBasic);

                    function drawBasic() {

                        var data = google.visualization.arrayToDataTable(aysDevicesBarChartData);
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
                                if( groupData.getValue(0, 1) == 0 ){
                                    return amount;
                                }
                                var amount =  formatShort.formatValue(dt.getValue(row, 1));
                                var percent = formatPercent.formatValue(dt.getValue(row, 1) / groupData.getValue(0, 1));
                                return amount + ' (' + percent + ')';
                            },
                            type: 'string',
                            role: 'annotation'
                        }]);
                    
                        var options = {
                            width: 700,
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
                        };

                        var chart = new google.visualization.BarChart( document.getElementById( 'survey_chart3_div') );

                        chart.draw(view,options);
                    }

                    var countriesCount = SurveyChartData.countriesCount;
                    
                    var aysCountriesBarChartData = new Array(['', '']);
                    for (var count in countriesCount) {
                        aysCountriesBarChartData.push([
                          count,
                          parseInt(countriesCount[count])
                        ]);
                    }

                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart2);

                    function drawChart2() {

                        var data = google.visualization.arrayToDataTable(aysCountriesBarChartData);

                        var options = {
                          // title: 'My Daily Activities'
                            width: 700,
                            height: 300,
                            fontSize: 16,
                            chartArea: { 
                                width: '80%',
                                height: '80%',
                            }
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('survey_chart4_div'));

                        chart.draw(data, options);
                        resizeChart(chart, data, options);
                    }
                }
            }
            
            if( !isInitializedSummaryCharts ){
                if (_this.getAttribute('href') == '#statistics_of_answer' && contValueSummary == 'block'){
                    // Survey per answer count 
                    var perAnswerData = SurveyChartData.perAnswerCount;

                    $.each(perAnswerData, function(){
                        switch( this.question_type ){
                            case "radio":
                            case "yesorno":
                                forRadioType( this );
                            break;
                            case "checkbox":
                                forCheckboxType( this );
                            break;
                            case "select":
                                forRadioType( this );
                            break;
                            case "linear_scale":
                                forLinearScaleType( this );
                            break;
                            case "star":
                                forLinearScaleType( this );
                            break;
                            case "matrix_scale":
                            case "matrix_scale_checkbox":
                                // forMatrixScaleType( this );
                                forMatrixScaleTypeCustom( this );
                            break;
                            case "star_list":
                                forStarListTypeCustom( this );
                            break;
                            case "slider_list":
                                forSliderListTypeCustom( this );
                            break;
                            case "range":
                                forRangeType( this );
                            break;
                            case "upload":
                                forUploadType( this );
                            break;
                        }
                    });
                    
                    isInitializedSummaryCharts = true;
                }
            }
        }

        function chartToImgDownload(chart,questionId) {

            google.visualization.events.addListener(chart, 'ready', function() {
                var container = document.getElementById('survey_answer_chart_' + questionId );

                var exportBtn = container.parentElement.parentElement.querySelector('.ays-survey-submission-summary-question-container-buttons');
                var questionTitle = container.parentElement.parentElement.querySelector('.ays-survey-submission-summary-question-title-item').textContent;
                var download = '<a href="'+chart.getImageURI()+'" title="'+SurveyChartData.exportToPng+'" download="'+questionTitle +'_'+ questionId + '.png" >'+SurveyChartData.downloadFile+'</a>';

                exportBtn.innerHTML = download;
            });     

        }

        function detectColor(color , percent) {
            if(typeof percent == "undefined"){
                percent = "";
            }
            var returnColor;
            var indexOfColor = color.indexOf("(");
            if(indexOfColor !== -1){
                var rgb = color.substring(indexOfColor + 1, color.length-1).replace(/ /g, '').split(',');
                if(percent != ""){
                    returnColor = 'rgba('+rgb[0]+','+rgb[1]+','+rgb[2]+' , '+percent+'%)';
                }
                else{
                    returnColor = 'rgb('+rgb[0]+','+rgb[1]+','+rgb[2]+')';
                }
            }
            else{
                if(percent != ""){
                    if(percent == 100){
                        returnColor = color;
                    }
                    else{
                        returnColor = color + (isNaN(parseInt(percent , 8)) ? "" : parseInt(percent , 8));

                    }
                }
                else{
                    returnColor = color;
                }
            }
            return returnColor;
    
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

    });
})(jQuery);