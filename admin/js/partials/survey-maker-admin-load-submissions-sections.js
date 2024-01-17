(function($) {
    'use strict';

    var isInitializedAnalyticsCharts = false;
    var isInitializedSummaryCharts = false;

    function AysSurveyMakerLoadSubmissionsSummarySections(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays-survey-';
        this.dbOptions = undefined;
        this.requestsSuccesses = 0;
        this.poolStart = 0;

        this.question      = 'question_id';
        this.answer        = 'answer';

        this.ajaxAction    = 'ays_survey_admin_ajax';

        this.init();

        return this;
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.init = function() {
        var _this = this;
        _this.setEvents();
    };

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.setEvents = function(e){
        var _this = this;

        $(document).ready(function () {

            var surveyId = _this.$el.data('surveyId');

            $.ajax({
                url: SurveyMakerAdmin.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: {
                    action: _this.ajaxAction,
                    function: 'mark_as_read_all_submissions',
                    survey_id: surveyId
                },
                async: true,
                success: function (response) {

                }
            });

            // AV Google charts
            $(document).find('.nav-tab').on('click', function(){
                var $this = this;
                $(window).trigger('resize');

                setTimeout(function(){
                    _this.initAnalyticsCharts( $this );
                }, 500);
            });

            var sections = _this.$el.find('.ays-survey-submission-section');
            var questions = _this.$el.find('.ays-survey-submission-summary-question-container:not(.ays-survey-submission-summary-header-container)');

            if(sections.length > 0 && questions.length > 0){
                var progressBar = $(`<div class="ays-survey-questions-progress-bar" style="width: 42px;">
                                        <svg class="ays-survey-questions-progress-svg">
                                            <circle class="ays-survey-questions-progress-bg" cx="50%" cy="50%" r="40%" />
                                            <circle class="ays-survey-questions-progress-fill" style="transition: 0.5s;" cx="50%" cy="50%" r="40%" />
                                            </svg>
                                            <span class="ays-survey-questions-progress-text" style="font-size: 9px;">0%</span>
                                        </div>`);

                $(document).find('.ays-survey-submission-summary-question-container-buttons-with-loader').prepend( progressBar );
            }
            setTimeout(function () {
                $(document).find( '.' + _this.htmlClassPrefix +'questions-loading-progress-bar-fill' ).css('padding-right', '10px');
            }, 1);

            _this.loadingfullPercent = questions.length;
            _this.loadingfillPercent = 0;

            _this.loadingStartTime = Date.now();
            _this.loadSectionsRecursively( sections, 0, false );
        });
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.loadSectionsRecursively = function( sections, index, last ) {
        var _this = this;

        if( last ){
            // document.querySelector(".ays-survey-questions-progress-fill").style.strokeDashoffset = (250 - 100 - 60);
            _this.loadingfillPercent = 0;
            return;
        }

        var questions = _this.$el.find('.ays-survey-submission-summary-question-container:not(.ays-survey-submission-summary-header-container)');

        var time = Date.now();
        var section = sections.eq( index ) ;

        _this.loadQuestionsPool( section, questions );
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.loadQuestionsPool = async function( section, questions ) {
        var _this = this;

        var questionsPool = questions.slice( _this.poolStart, _this.poolStart + 10 );
        for (var i = 0; i < questionsPool.length; i++) {
            await _this.loadQuestion( section, questionsPool.eq(i), questions, {} );
        }
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.loadQuestion =  async function( section, questionsPool, questions, args ) {
        var _this = this;

        var surveyId = section.attr('data-survey-id');
        var sectionId = section.attr('data-section-id');

        var questionIds = [];
        var questionArr = {};

        for (var i=0; i < questionsPool.length; i++){
            questionIds.push( $(questionsPool[i]).attr('data-question-id') );
            questionArr[ $(questionsPool[i]).attr('data-question-id') ] = $(questionsPool[i]);
        }

        // Get the current URL
        var url = new URL(window.location.href);
        // Get individual GET parameters
        var filterByPostParam = url.searchParams.get("filterbypost");
        var filterByStartdate = url.searchParams.get("filterbystartdate");
        var filterByEndParam = url.searchParams.get("filterbyenddate");

        var data = {
            action: _this.ajaxAction,
            function: 'get_survey_submissions_question_summary_html',
            id: surveyId,
            question_ids: questionIds,
            section_id: sectionId,
            postFilters: {
                filterByPostParam: filterByPostParam,
                filterByStartdate: filterByStartdate,
                filterByEndParam: filterByEndParam
            }
        };

        await $.ajax({
            url: SurveyMakerAdmin.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: data,
            async: true,
            success: function(response){
                _this.loadingfillPercent += questionsPool.length;
                if( _this.loadingfillPercent === _this.loadingfullPercent ){
                    setTimeout(function () {
                        $(document).find( '.' + _this.htmlClassPrefix +'questions-progress-bar' ).remove();
                    }, 500);
                }
                var percent = (_this.loadingfillPercent / _this.loadingfullPercent) * 100;
                percent = parseInt(percent);
                _this.requestsSuccesses++;

                if( _this.requestsSuccesses === 10 ){
                    _this.poolStart += 10;
                    _this.loadQuestionsPool( section, questions );
                    _this.requestsSuccesses = 0;
                }

                document.querySelector(".ays-survey-questions-progress-fill").style.strokeDashoffset = (250 - percent - 13)
                $(document).find('.ays-survey-questions-progress-text').text(percent + '%');
                if( response.status === true ) {
                    for (var questionId in response.questionHtml) {
                        var questionEl = questionArr[ questionId ];
                        questionEl.find('.ays-survey-submission-summary-question-content').html( response.questionHtml[ questionId ].html );
                        questionEl.find('.ays-survey-submission-summary-question-submissions-count').html( response.questionHtml[ questionId ].data.sum_of_answers_count );                        

                        // Survey per answer count
                        var perAnswerData = response.questionHtml[ questionId ].data;

                        switch( perAnswerData.question_type ){
                            case "radio":
                            case "yesorno":
                                _this.forRadioType( perAnswerData );
                                break;
                            case "checkbox":
                                _this.forCheckboxType( perAnswerData );
                                break;
                            case "select":
                                _this.forRadioType( perAnswerData );
                                break;
                            case "linear_scale":
                                _this.forLinearScaleType( perAnswerData );
                                break;
                            case "star":
                                _this.forLinearScaleType( perAnswerData );
                                break;
                            case "matrix_scale":
                            case "matrix_scale_checkbox":
                                // forMatrixScaleType( this );
                                _this.forMatrixScaleTypeCustom( perAnswerData );
                                break;
                            case "star_list":
                                _this.forStarListTypeCustom( perAnswerData );
                                break;
                            case "slider_list":
                                _this.forSliderListTypeCustom( perAnswerData );
                                break;
                            case "range":
                                _this.forRangeType( perAnswerData );
                                break;
                            case "upload":
                                _this.forUploadType( perAnswerData );
                                break;
                        }
                        questionEl.find( '.ays-survey-submission-summary-loader' ).remove();
                    }
                }
            }
        });
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forRadioType = function( item ){
        var _this = this;

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
                fontSize: 14,
                chartArea: {
                    width: '80%',
                    height: '80%'
                }
            };

            var chart = new google.visualization.PieChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

            _this.chartToImgDownload(chart,questionId);

            chart.draw(data, options);
            _this.resizeChart(chart, data, options);
        }
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forCheckboxType = function( item ){
        var _this = this;
        var questionId = item.question_id;
        var dataTypes = [[SurveyMakerAdmin.answers, SurveyMakerAdmin.count]];

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
                    var percent = formatPercent.formatValue(dt.getValue(row, 1) / groupData.getValue(0, 1));
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
                colors: [ _this.detectColor(SurveyChartData.chartColor) ]
            };

            var chart = new google.visualization.BarChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

            _this.chartToImgDownload(chart,questionId);
            chart.draw(view, options);
            _this.resizeChart(chart, view, options);
        }
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forLinearScaleType = function( item ){
        var _this = this;

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
                colors: [ _this.detectColor(SurveyChartData.chartColor) ]
            };

            var chart = new google.visualization.ColumnChart( document.getElementById( 'survey_answer_chart_' + questionId ) );

            _this.chartToImgDownload(chart,questionId);
            chart.draw(view, options);
            _this.resizeChart(chart, view, options);
        }
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forMatrixScaleType = function( item ){
        var _this = this;
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forMatrixScaleTypeCustom = function( item ){
        var _this = this;
        var questionId = item.question_id;
        var martixDataColumns = new Array();
        var martixDataAnswers = item.allAnswers;
        if(item.matrix_data){
            martixDataColumns = item.matrix_data[0].length > 0 ? item.matrix_data[0] : martixDataColumns;
        }
        var martixDataColumnsForRows = item.matrix_data.slice();
        martixDataColumnsForRows.shift();
        var content = '<div class="ays_questions_answers">';
        content += '<div class="ays-survey-answer-matrix-scale-main ays-survey-answer-matrix-scale-main-chart">';
        content += '<div class="ays-survey-answer-matrix-scale-container">';
        content += '<div class="ays-survey-answer-matrix-scale-row ays-survey-answer-matrix-scale-row-chart-columns">';
        for(var columnKey in martixDataColumns){
            content += '<div class="ays-survey-answer-matrix-scale-column" title="'+martixDataColumns[columnKey]+'">'+_this.surveyRestrictionRtring(martixDataColumns[columnKey], 3)+'</div>';
        }
        content += '</div>';
        content += '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
        for(var rowKey in martixDataColumnsForRows){
            content += '<div class="ays-survey-answer-matrix-scale-row ays-survey-answer-matrix-scale-row-chart">';
            content += '<div class="ays-survey-answer-matrix-scale-row-content">';
            content += '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header ays-survey-answer-matrix-scale-column-row-header-chart" title="'+martixDataColumnsForRows[rowKey][0]+'">'+_this.surveyRestrictionRtring(martixDataColumnsForRows[rowKey][0], 5)+'</div>';
            martixDataColumnsForRows[rowKey] = martixDataColumnsForRows[rowKey].slice();
            martixDataColumnsForRows[rowKey].shift();
            var eachAnswerAllCount = martixDataAnswers[rowKey];
            for(var rowInnerKey in martixDataColumnsForRows[rowKey]){
                var percent = 0;
                if(martixDataColumnsForRows[rowKey][rowInnerKey] > 0 && eachAnswerAllCount > 0){
                    percent = ((martixDataColumnsForRows[rowKey][rowInnerKey] * 100) / eachAnswerAllCount);
                    if(_this.isFloat(percent)){
                        percent = percent.toFixed(1);
                    }
                }
                var matrixColor;
                var matrixTextColor = "#000";
                if(percent == 0){
                    matrixColor = "#fff";

                }
                else{
                    matrixColor = _this.detectColor(SurveyChartData.chartColor , percent);
                }

                if(matrixColor != "#fff"){
                    matrixTextColor = "#fff";
                }

                content +=  '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-chart" style="background-color:'+matrixColor+'; color: '+matrixTextColor+';" title="'+(SurveyChartData.matrixShowType == "by_votes" ? percent+'%' : martixDataColumnsForRows[rowKey][rowInnerKey] + " (Votes)")+'">'+(SurveyChartData.matrixShowType == "by_votes" ? martixDataColumnsForRows[rowKey][rowInnerKey] : percent+'%')+'</div>';
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forStarListTypeCustom = function( item ){
        var _this = this;
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
                content += '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header ays-survey-answer-star-list-column-row-header-chart" title="'+StarListAllAnswers[makeDataSortable[i].answer_id]+'">'+_this.surveyRestrictionRtring(StarListAllAnswers[makeDataSortable[i].answer_id], 5)+'</div>';
                content +=  '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;font-weight: 600; font-size: 17px;" >'+averageSum+'</div>';
                content +=  '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-chart" style="box-shadow:inset 0px 0px 7px 0 #e9e4e4;position:relative;width:15%;text-align:initial;padding: 0 18px;">';
                var starsAverageSum = (averageSum*100)/starsLength;
                content += _this.getStars(starsLength,starsAverageSum);
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forSliderListTypeCustom = function( item ){
        var _this = this;
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
                content += '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header ays-survey-answer-star-list-column-row-header-chart" title="'+sliderListAllAnswers[makeDataSortable[i].answer_id]+'">'+_this.surveyRestrictionRtring(sliderListAllAnswers[makeDataSortable[i].answer_id], 5)+'</div>';
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.getStars = function(lenght,averagePercent){
        var _this = this;

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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forRangeType = function( item ){
        var _this = this;
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
                colors: [ _this.detectColor(SurveyChartData.chartColor) ]
            };
            var chart = new google.visualization.LineChart(document.getElementById( 'survey_answer_chart_' + questionId ));

            _this.chartToImgDownload(chart,questionId);
            chart.draw(data, options);
            _this.resizeChart(chart, data, options);
        }
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.forUploadType = function( item ){
        var _this = this;
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.surveyRestrictionRtring = function(word, length){
        var _this = this;

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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.isFloat = function(n){
        return Number(n) === n && n % 1 !== 0;
    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.initAnalyticsCharts = function( _this ) {
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.chartToImgDownload = function(chart,questionId) {
        var _this = this;

        google.visualization.events.addListener(chart, 'ready', function() {
            var container = document.getElementById('survey_answer_chart_' + questionId );

            var exportBtn = container.parentElement.parentElement.querySelector('.ays-survey-submission-summary-question-container-buttons');
            var questionTitle = container.parentElement.parentElement.querySelector('.ays-survey-submission-summary-question-title-item').textContent;
            var download = '<a href="'+chart.getImageURI()+'" title="'+SurveyChartData.exportToPng+'" download="'+questionTitle +'_'+ questionId + '.png" >'+SurveyChartData.downloadFile+'</a>';

            exportBtn.innerHTML = download;
        });

    }

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.detectColor = function(color , percent) {
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

    AysSurveyMakerLoadSubmissionsSummarySections.prototype.resizeChart = function(chart, data, options){
        var _this = this;

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

    $.fn.AysSurveyLoadSubmissionsSummarySections = function(options) {
        return this.each(function() {
            if (!$.data(this, 'AysSurveyLoadSubmissionsSummarySections')) {
                $.data(this, 'AysSurveyLoadSubmissionsSummarySections', new AysSurveyMakerLoadSubmissionsSummarySections(this, options));
            } else {
                try {
                    $(this).data('AysSurveyLoadSubmissionsSummarySections').init();
                } catch (err) {
                    console.error('AysSurveyLoadSubmissionsSummarySections has not initiated properly');
                }
            }
        });
    };

    $(document).find('#statistics_of_answer').AysSurveyLoadSubmissionsSummarySections();
})(jQuery);