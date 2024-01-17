<div class="wrap ays_results_table">
    <h1 class="wp-heading-inline">
        <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
        ?>
    </h1>

    <div class="nav-tab-wrapper">
        <a href="<?php echo "?page=".$this->plugin_name."-submissions"; ?>" class="no-js nav-tab"><?php echo __('Surveys',$this->plugin_name)?></a>
        <a href="<?php echo "?page=".$this->plugin_name."-global-statistics"; ?>" class="no-js nav-tab nav-tab-active"><?php echo __('Global Statistics',$this->plugin_name)?></a>
    </div>

    <div class="ays_survey_global_stat_container">
        <div class="ays-field ays_survey_stat_select_div" style="display:flex; width:200px; margin-top: 20px;overflow: hidden;">
            <?php
                $surveys = $this->submissions_obj->get_reports_titles();
            ?>
            <select name="global_survey_stat" id="global_survey_stat_select">
                <option value="0"><?php echo __( "All Surveys", $this->plugin_name ); ?></option>
                <?php foreach ($surveys as $survey) {
                    $submissions_count = isset($survey['submissions_count']) && $survey['submissions_count'] > 0 ? esc_attr($survey['submissions_count']) : 0;
                    echo "<option value = '" . $survey['id'] . "' >" . stripslashes($survey['title']) . ' (' . ($submissions_count) . ')' ."</option >";
                } ?>
            </select>
            <img class="loader display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/gear.svg" style="width:30px;"> 
        </div>
        <hr>
         <div class="ays-survey-submission-summary-question-container" style="max-width: 100%;">
            <div class="ays-survey-submission-summary-question-header">
                <div class="ays-survey-submission-summary-question-header-content">
                    <h1 style="text-align:center;"><?php echo __("Survey Statistics", $this->plugin_name); ?></h1>
                </div>
            </div>
            <div class="ays-survey-submission-summary-question-content" style="min-height: 350px;">
                <div id="chart_glob_surveys_stat_div" style="width: 100%; height: 100%;" class=""></div>
            </div>
        </div>
    </div>
</div>