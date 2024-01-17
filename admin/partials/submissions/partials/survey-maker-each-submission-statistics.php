<?php
extract($args);
?>
<div id="statistics" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'statistics') ? 'ays-survey-tab-content-active' : ''; ?>">
    <div class="wrap">
        <div class="ays-survey-submission-summary-question-container">
            <div class="ays-survey-submission-summary-question-header">
                <div class="ays-survey-submission-summary-question-header-content">
                    <h1 style="text-align:center;"><?php echo __("Submissions count per day", SURVEY_MAKER_NAME); ?></h1>
                </div>
            </div>
            <div class="ays-survey-submission-summary-question-content">
                <div id="survey_chart1_div" class="chart_div"></div>
            </div>
        </div>
        <div class="ays-survey-submission-summary-question-container">
            <div class="ays-survey-submission-summary-question-header">
                <div class="ays-survey-submission-summary-question-header-content">
                    <h1 style="text-align:center;"><?php echo __("Survey passed users by user role", SURVEY_MAKER_NAME); ?></h1>
                </div>
            </div>
            <div class="ays-survey-submission-summary-question-content">
                <div id="survey_chart2_div" class="chart_div"></div>
            </div>
        </div>
        <div class="ays-survey-submission-summary-question-container">
            <div class="ays-survey-submission-summary-question-header">
                <div class="ays-survey-submission-summary-question-header-content">
                    <h1 style="text-align:center;"><?php echo __("Detected device", SURVEY_MAKER_NAME); ?></h1>
                </div>
            </div>
            <div class="ays-survey-submission-summary-question-content">
                <div id="survey_chart3_div" class="chart_div"></div>
            </div>
        </div>
        <div class="ays-survey-submission-summary-question-container">
            <div class="ays-survey-submission-summary-question-header">
                <div class="ays-survey-submission-summary-question-header-content">
                    <h1 style="text-align:center;"><?php echo __("Detected Countries", SURVEY_MAKER_NAME); ?></h1>
                </div>
            </div>
            <div class="ays-survey-submission-summary-question-content">
                <div id="survey_chart4_div" class="chart_div"></div>
            </div>
        </div>
    </div>
</div>

