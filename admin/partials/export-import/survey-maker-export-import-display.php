<?php
$surveys = $this->get_all_surveys();

$fileTypeError = '';
$stats = array();
if (isset($_POST['ays_survey_import'])) {
    if( isset( $_FILES['ays_survey_import_file'] ) && $_FILES['ays_survey_import_file']['error'] == 0 ){
        $name_arr = explode('.', $_FILES['ays_survey_import_file']['name']);
        $type     = end($name_arr);         
        if( $type == 'json' ){
            $stats =  $this->ays_survey_import($_FILES['ays_survey_import_file']);
        }else{
            $fileTypeError = __("You can only add a JSON file",$this->plugin_name);
        }
    }else{
        $fileTypeError = __("Please upload a JSON file",$this->plugin_name);
    }
}

$example_export_path = SURVEY_MAKER_ADMIN_URL . '/partials/export-import/survey-export-example.json';

?>
<div class="wrap ays_results_table">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
                </a>

            </div>
        </div>
        <h1 class="wp-heading-inline">
            <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
            ?>
        </h1>
        <!-- <div class="question-action-butons">
            <a href="javascript:void(0)" class="ays-export-filters ays_export_results page-title-action" style="float: right;"><#?php echo __('Export', $this->plugin_name); ?></a>
        </div> -->
        <p style="font-size:14px;"><?php echo __( 'The export/Import section helps to transfer your already created surveys to another website in seconds.', $this->plugin_name ); ?>
            <br><?php echo sprintf( 
                __( 'It is designed for transferring your surveys from the %slocal or staging website to the live one, the old website to the new one, and similar cases%s.', $this->plugin_name ),
                '<em>',
                '</em>'
            ); ?></p>
        <div style="display: flex;justify-content: center; align-items: center;"><iframe width="560" height="315" src="https://www.youtube.com/embed/xLSv8h87fX4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe></div>
        <div class="nav-tab-wrapper">
            <a href="#tab1" class="nav-tab nav-tab-active"><?php echo __('Export',$this->plugin_name); ?></a>
            <a href="#tab2" class="nav-tab"><?php echo __('Import',$this->plugin_name); ?></a>
            <a href="<?php echo $example_export_path; ?>" class="export-survey-example" download="survey-export-example.json"><?php echo __('Download example for import',$this->plugin_name); ?></a>
        </div>

        <div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active">
            <form method="post" id="ays-export-form">
                <p class="ays-subtitle"><?php echo __('Export surveys',$this->plugin_name)?></p>
                <hr/>
                <?php if($fileTypeError != ''): ?>
        <div class="notice notice-error is-dismissible ays-file-type-error">
            <p class="ays-subtitle" style="margin: .5em 0 !important;"> <?php echo $fileTypeError; ?> </p>
        </div>
        <?php endif; ?>

        <?php
            if(!empty($stats)):
                $impoted_surveys = $stats['surveys_successed'];
                $failed_surveys = $stats['surveys_failed'];
                $impoted_questions = $stats['questions_successed'];
                $failed_questions = $stats['questions_failed'];
                $status_color = ' notice-success ';
                $updated_message = '';
                if($impoted_surveys == 0){
                    $updated_message .= esc_html( __( 'Surveys import failed.', $this->plugin_name ) );
                    $status_color = ' notice-error ';
                }else{
                    if($impoted_surveys == 1){
                        $updated_message .= $impoted_surveys . ' ' . esc_html( __( 'survey is imported successfully.', $this->plugin_name ) );
                    }else{
                        $updated_message .= $impoted_surveys . ' ' . esc_html( __( 'surveys are imported successfully.', $this->plugin_name ) );
                    }
                    if($impoted_questions == 1){
                        $updated_message .= '<br>';
                        $updated_message .= $impoted_questions . ' ' . esc_html( __( 'question is imported successfully.', $this->plugin_name ) );
                    }elseif($impoted_questions > 1){
                        $updated_message .= '<br>';
                        $updated_message .= $impoted_questions . ' ' . esc_html( __( 'questions are imported successfully.', $this->plugin_name ) );
                    }
                    if($failed_surveys == 1){
                        $updated_message .= '<br>';
                        $updated_message .= $failed_surveys . ' ' . esc_html( __( 'survey is failed to import.', $this->plugin_name ) );
                    }elseif($failed_surveys > 1){
                        $updated_message .= '<br>';
                        $updated_message .= $failed_surveys . ' ' . esc_html( __( 'surveys are failed to import.', $this->plugin_name ) );
                    }
                    if($failed_questions == 1){
                        $updated_message .= '<br>';
                        $updated_message .= $failed_questions . ' ' . esc_html( __( 'question is failed to import.', $this->plugin_name ) );
                    }elseif($failed_questions > 1){
                        $updated_message .= '<br>';
                        $updated_message .= $failed_questions . ' ' . esc_html( __( 'questions are failed to import.', $this->plugin_name ) );
                    }
                    if($failed_surveys == 0 && $failed_questions == 0){
                        $updated_message .= '<br>';
                        $updated_message .= esc_html( __( 'No failures found.', $this->plugin_name ) );
                    }
                }
        ?>
        <div class="notice <?php echo $status_color ?> is-dismissible">
            <p class="ays-subtitle" style="margin: .5em 0 !important;"> <?php echo $updated_message; ?> </p>
        </div>
        <?php endif; ?>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_select_surveys">
                            <span><?php echo __("Select Surveys", $this->plugin_name); ?></span>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                                echo htmlspecialchars( sprintf(
                                    __('Select survey(s) which you would like to export and click on the %sExport to JSON%s button at the end.', $this->plugin_name),
                                    '<strong>',
                                    '</strong>'
                                ) );
                            ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                        </label>
                    </div>
                    <div class="col-sm-8">                        
                        <select name="surveys_ids[]" id="ays_select_surveys" multiple>
                            <?php foreach ($surveys as $key => $survey): ?>
                            <option value="<?php echo $survey->id; ?>"><?php echo htmlentities($survey->title); ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="button" class="button ays_export_surveys" id="export-reports" ><?php echo __( "Export to JSON", $this->plugin_name ); ?>
                        </button>
                        <a download="" id="downloadFile" hidden href=""></a>
                    </div>
                </div>
            </form>
        </div>

        <div id="tab2" class="ays-survey-tab-content">
            <div class="upload-import-file-wrap show-upload-view">
                <div class="upload-import-file">
                    <p class="install-help"><?php echo __( "After completing the exporting process, move to the website where you are planning to import those surveys.", $this->plugin_name ); ?></p>
                    <p class="install-help"><?php echo ( sprintf(
                        __('Click on the %s Choose file %s button and pick the JSON file which you exported recently. Click on the %s Import Now %s button at the end.', $this->plugin_name),
                        '<strong>',
                        '</strong>',
                        '<strong>',
                        '</strong>'
                    ) ); ?></p>
                    <form method="post" enctype="multipart/form-data" class="ays-dn">
                        <input type="file" accept=".json" name="ays_survey_import_file" id="import_file"/>
                        <label class="screen-reader-text" for="import_file"><?php echo __( "Import file", $this->plugin_name ); ?></label>
                        <input type="submit" name="ays_survey_import" class="button" value="<?php echo __( "Import now", $this->plugin_name ); ?>" disabled="">
                    </form>
                </div>
            </div>
        </div>

        <div class="ays-modal" id="export-filters">
            <div class="ays-modal-content">
                <div class="ays-preloader">
                    <img class="loader" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/3-1.svg">
                </div>
              <!-- Modal Header -->
                <div class="ays-modal-header">
                    <span class="ays-close">&times;</span>
                    <h2><?=__('Export Filter', $this->plugin_name)?></h2>
                </div>

              <!-- Modal body -->
                <div class="ays-modal-body">
                    <form method="post" id="ays_export_filter">
                        <div class="filter-col">
                            <label for="user_id-filter"><?=__("Users", $this->plugin_name)?></label>
                            <button type="button" class="ays_userid_clear button button-small wp-picker-default"><?=__("Clear", $this->plugin_name)?></button>
                            <select name="user_id-select[]" id="user_id-filter" multiple="multiple"></select>
                        </div>
                        <hr>
                        <div class="filter-col">
                            <label for="ays_surveyid_clear-filter"><?=__("Surveys", $this->plugin_name)?></label>
                            <button type="button" class="ays_surveyid_clear button button-small wp-picker-default"><?=__("Clear", $this->plugin_name)?></button>
                            <select name="ays_surveyid_clear-select[]" id="ays_surveyid_clear-filter" multiple="multiple"></select>
                        </div>
                        <div class="filter-block">
                            <div class="filter-block filter-col">
                                <label for="start-date-filter"><?=__("Start Date from", $this->plugin_name)?></label>
                                <input type="date" name="start-date-filter" id="start-date-filter">
                            </div>
                            <div class="filter-block filter-col">
                                <label for="end-date-filter"><?=__("Start Date to", $this->plugin_name)?></label>
                                <input type="date" name="end-date-filter" id="end-date-filter">
                            </div>
                        </div>
                    </form>
                </div>

              <!-- Modal footer -->
                <div class="ays-modal-footer">
                    <div class="export_results_count">
                        <p>Matched <span></span> results</p>
                    </div>
                    <span><?php echo __('Export to', $this->plugin_name); ?></span>
                    <button type="button" class="button button-primary export-action" data-type="csv"><?=__('CSV', $this->plugin_name)?></button>
                    <button type="button" class="button button-primary export-action" data-type="xlsx"><?=__('XLSX', $this->plugin_name)?></button>
                    <button type="button" class="button button-primary export-action" data-type="json"><?=__('JSON', $this->plugin_name)?></button>
                    <a download="" id="downloadFile" hidden href=""></a>
                </div>

            </div>
        </div>
    </div>
</div>

