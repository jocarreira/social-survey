<?php
global $wpdb;
$survey_id = isset($_GET['survey']) ? intval( sanitize_text_field( $_GET['survey'] ) ) : null;

$survey_posts = Survey_Maker_Data::ays_survey_get_survey_posts($survey_id);

$filters = array();
$filters['post_id'] = isset($_GET['filterbypost']) ? intval( sanitize_text_field( $_GET['filterbypost'] ) ) : null;
$filters['start_date'] = isset($_GET['filterbystartdate']) ? sanitize_text_field( $_GET['filterbystartdate'] ) : null;
$filters['end_date'] = isset($_GET['filterbyenddate']) ? sanitize_text_field( $_GET['filterbyenddate'] ) : null;
$is_filter = (isset($filters['post_id']) || isset($filters['start_date']) || isset($filters['end_date'])) ? true : false;
$filters['is_filter'] = $is_filter;

if($survey_id === null){
    wp_redirect( admin_url('admin.php') . '?page=' . $this->plugin_name . '-submissions' );
}

if(isset($_GET['ays_survey_tab'])){
    $ays_survey_tab = sanitize_text_field( $_GET['ays_survey_tab'] );
}else{
    $ays_survey_tab = 'statistics_of_answer';
}

if(isset($_REQUEST['s'])){
    $ays_survey_tab = 'poststuff';
}

$submission_count_and_ids = Survey_Maker_Data::get_submission_count_and_ids( $survey_id, $filters );
$submission_ids_arr = ( isset( $submission_count_and_ids['submission_ids_arr'] ) && ! empty( $submission_count_and_ids['submission_ids_arr'] ) ) ? Survey_Maker_Data::recursive_sanitize_text_field( $submission_count_and_ids['submission_ids_arr'] ) : array();
$filters['filter_submission_ids'] = $submission_ids_arr;

$ays_survey_tab = isset($_GET['orderby']) || isset($_GET['order']) ? 'poststuff' : $ays_survey_tab;

$sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE id =" . absint( $survey_id );
$survey_name = $wpdb->get_row( $sql, 'ARRAY_A' );

$survey_options = isset( $survey_name['options'] ) && $survey_name['options'] != '' ? json_decode( $survey_name['options'], true ) : array();

$survey_for_charts = isset( $survey_options['survey_color'] ) && $survey_options['survey_color'] != '' ? esc_attr($survey_options['survey_color']) : "rgb(255, 87, 34)";
if($survey_options['survey_color'] == 'rgba(0,0,0,0)'){
    $survey_for_charts = "rgba(0,0,0,1)";
}
// Allow HTML in answers
$survey_options[ 'survey_allow_html_in_answers' ] = isset($survey_options[ 'survey_allow_html_in_answers' ]) ? $survey_options[ 'survey_allow_html_in_answers' ] : 'off';
$allow_html_in_answers = (isset($survey_options[ 'survey_allow_html_in_answers' ]) && $survey_options[ 'survey_allow_html_in_answers' ] == 'on') ? true : false;

// Allow HTML in description
$survey_options[ 'survey_allow_html_in_section_description' ] = isset($survey_options[ 'survey_allow_html_in_section_description' ]) ? $survey_options[ 'survey_allow_html_in_section_description' ] : 'off';
$survey_allow_html_in_section_description = (isset($survey_options[ 'survey_allow_html_in_section_description' ]) && $survey_options[ 'survey_allow_html_in_section_description' ] == 'on') ? true : false;

$user_id = get_current_user_id();
$author_id = intval( $survey_name['author_id'] );

$owner = false;
if( $user_id == $author_id ){
    $owner = true;
}

if( $this->current_user_can_edit ){
    $owner = true;
}

if( !$owner ){
    $url = esc_url_raw( remove_query_arg( array( 'page', 'survey' ) ) ) . "?page=survey-maker-submissions";
    wp_redirect( $url );
}

$get_count_per_day = $this->each_submission_obj->get_submision_line_chart($survey_id, $filters);
$get_users_count = $this->each_submission_obj->survey_users_count($filters);
$get_divices_count_arr = $this->each_submission_obj->get_devices_bar_chart($survey_id, $filters);
$get_countries_arr = $this->each_submission_obj->get_countries_pie_chart($survey_id, $filters);

$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode($this->settings_obj->ays_get_setting('options'), true);
// General settings for submissions matrix scale 
$survey_matrix_show_result_type = isset($gen_options['survey_matrix_show_result_type']) && $gen_options['survey_matrix_show_result_type'] != "" ? $gen_options['survey_matrix_show_result_type'] : "by_votes";
// General settings for submissions order type
$survey_show_submissions_order_type = isset($gen_options['survey_show_submissions_order_type']) && $gen_options['survey_show_submissions_order_type'] != "" ? $gen_options['survey_show_submissions_order_type'] : "by_defalut";
// For charts in summary
// $survey_question_results = Survey_Maker_Data::ays_survey_question_results( $survey_id, null, null, null, null, false, $filters );
// $question_results = $survey_question_results['questions'];
// $total_count = $survey_question_results['total_count'];

$last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id( $survey_id, 0, $filters );

$ays_survey_individual_questions = Survey_Maker_Data::ays_survey_individual_results_for_one_submission( $last_submission, $survey_name, false, $filters );

// Show question title as HTML
$survey_options[ 'survey_show_questions_as_html' ] = isset($survey_options[ 'survey_show_questions_as_html' ]) ? $survey_options[ 'survey_show_questions_as_html' ] : 'on';
$survey_show_questions_as_html = $survey_options[ 'survey_show_questions_as_html' ] == 'on' ? true : false;

// Get user info
$individual_user_name   = "";
$individual_user_email  = "";
$individual_user_ip     = "";
$individual_user_date   = "";
$individual_user_sub_id = "";
$individual_user_password = "";
if( isset($ays_survey_individual_questions['user_info']) && is_array( $ays_survey_individual_questions['user_info']) ){
    $individual_user_name   = isset($ays_survey_individual_questions['user_info']['user_name']) && isset($ays_survey_individual_questions['user_info']['user_name']) ? stripslashes(  $ays_survey_individual_questions['user_info']['user_name'] ) : "";
    $individual_user_email  = isset($ays_survey_individual_questions['user_info']['user_email']) && isset($ays_survey_individual_questions['user_info']['user_email'])  ? stripslashes( esc_attr( $ays_survey_individual_questions['user_info']['user_email'] ) ) : "";
    $individual_user_ip     = isset($ays_survey_individual_questions['user_info']['user_ip']) && isset($ays_survey_individual_questions['user_info']['user_ip'])  ? stripslashes( esc_attr( $ays_survey_individual_questions['user_info']['user_ip'] ) ) : "";
    $individual_user_date   = isset($ays_survey_individual_questions['user_info']['submission_date']) && isset($ays_survey_individual_questions['user_info']['submission_date'])  ? stripslashes( esc_attr( $ays_survey_individual_questions['user_info']['submission_date'] ) ) : "";
    $individual_user_sub_id = isset($ays_survey_individual_questions['user_info']['id']) && isset($ays_survey_individual_questions['user_info']['id'])  ? stripslashes( esc_attr( $ays_survey_individual_questions['user_info']['id'] ) ) : "";
    $individual_user_password = isset($ays_survey_individual_questions['user_info']['password']) && isset($ays_survey_individual_questions['user_info']['password'])  ? stripslashes( esc_attr( $ays_survey_individual_questions['user_info']['password'] ) ) : "";
}

// if( empty( $ays_survey_individual_questions['sections'] ) ){
//     $question_results = array();
// }

$text_types = array(
    'text',
    'short_text',
    'number',
    'phone',
    'name',
    'hidden',
    'email',
    'date',
    'time',
    'date_time',
);

// $submission_count_and_ids = Survey_Maker_Data::get_submission_count_and_ids( $survey_id );

// $submission_ids_arr = ( isset( $submission_count_and_ids['submission_ids_arr'] ) && ! empty( $submission_count_and_ids['submission_ids_arr'] ) ) ? Survey_Maker_Data::recursive_sanitize_text_field( $submission_count_and_ids['submission_ids_arr'] ) : array();

$submission_first_id = '';
if (! empty( $submission_ids_arr ) ) {
    $submission_first_id = $submission_ids_arr[ count( $submission_ids_arr ) - 1 ];
}

$submissions_count = 0;
if(intval($submission_count_and_ids['submission_count']) > 0){
    $submissions_count = $submission_count_and_ids['submission_count'];
}

$export_disabled = 'disabled';
if( $submissions_count > 0 ){
    $export_disabled = '';
}

wp_localize_script( $this->plugin_name, 'SurveyChartData', array(
    'exportToPng'   => __( 'Export to PNG', $this->plugin_name ),
    'downloadFile'    => '<img src="' . SURVEY_MAKER_ADMIN_URL .'/images/icons/download-file.svg">',
    'openSettingsImg' => '<img src="' . SURVEY_MAKER_ADMIN_URL .'/images/icons/settings.svg">',
    'countPerDayData' => $get_count_per_day,
    'usersCount' => $get_users_count,
    'deviceCountArr' => $get_divices_count_arr,
    // 'perAnswerCount' => $question_results,
    // 'submission_count' => $submission_count
    // 'perAnswerCount' => $question_results,
    'countriesCount' => $get_countries_arr,
    'chartColor' => $survey_for_charts,
    'matrixShowType' => $survey_matrix_show_result_type,
    'submissionsOrderType' => $survey_show_submissions_order_type,
) );

$survey_data_clipboard = array(
    "user_name"   => $individual_user_name,
    "user_email"  => $individual_user_email,
    "user_ip"     => $individual_user_ip,
    "user_date"   => $individual_user_date,
    "user_sub_id" => $individual_user_sub_id,
);

if($individual_user_password){
    $survey_data_clipboard['user_password'] = $individual_user_password;
}

$survey_data_formated_for_clipboard = Survey_Maker_Data::ays_survey_copy_text_formater($survey_data_clipboard);


?>

<div class="wrap ays_each_results_table">
    <div class="ays-survey-heading-box">
        <div class="ays-survey-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                <i class="ays_fa ays_fa_file_text" ></i> 
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
            </a>
        </div>
    </div>
    <h1 class="wp-heading-inline" style="padding-left:15px;">
        <?php
        echo sprintf( '<a href="?page=%s" class="go_back"><span><i class="fa fa-long-arrow-left" aria-hidden="true"></i> %s </span></a>', $this->plugin_name."-submissions", __("Back to Submissions", $this->plugin_name) );
        ?>
    </h1>
    <div style="display: flex; justify-content: space-between;">
        <h1 class="wp-heading-inline" style="padding-left:15px;margin-bottom:0;">
            <?php
                $url = admin_url('admin.php');
                $url = esc_url_raw(add_query_arg(array(
                    "page"   => $this->plugin_name,
                    "action" => "edit",
                    "id"     => $survey_id
                ), $url));
                echo "<span>". __("Reports for", $this->plugin_name) . "</span>" ." <a href='".$url."' target='_blank' class='ays-survey-to-current-survey'>\"" . esc_html__( $survey_name['title'], $this->plugin_name ) . "\""."</a>";
            ?>
        </h1>
        <div class="ays-survey-question-action-butons" style="padding: 10px; display: inline-block;">
            <button type="button" class="button button-primary ays-survey-export-answers-filters" data-type="xlsx"quiz-id="<?php echo $survey_id ?>"><?php echo __('Export submissions', $this->plugin_name); ?></button>
            <button type="button" class="button button-primary ays-survey-export-submissions-to-xlsx" data-type="xlsx"survey-id="<?php echo $survey_id ?>"><?php echo __('Export to XLSX', $this->plugin_name); ?></button>
            <a class="ays_help" data-toggle="tooltip" data-hover="ays-survey-export-to-xlsx-tooltip" data-html="true"title="<?php echo __('Click the Export to XLSX button and export your submissions. Note, that the file does not include paragraph, short text, number, phone, email, name, upload, star list, slider list, types.',$this->plugin_name)?>">
                <i class="ays_fa ays_fa_info_circle"></i>
            </a>
        </div>
    </div>
    <div class="ays-survey-submissions-filters-section">
        <div class="ays-survey-submissions-filter-button">
            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 16 16" width="16" height="16"><g transform="translate(0, 0)"><defs><path id="path-169778375629214" d="M20.31816722868322 5.092834549770544 C20.31816722868322 5.092834549770544 5.817641447783829 5.092834549770544 5.817641447783829 5.092834549770544 C5.512596577862579 5.071023695167673 5.251129546501509 5.256415959292082 5.14218495010106 5.539957069129414 C5.011451434420527 5.801687324363874 5.076818192260794 6.1397555707083855 5.316496304341776 6.3360532621342305 C5.316496304341776 6.3360532621342305 10.894459640044625 11.930537467770824 10.894459640044625 11.930537467770824 C10.894459640044625 11.930537467770824 10.894459640044625 17.448683682297368 10.894459640044625 17.448683682297368 C10.894459640044625 17.644981373723212 10.970720857524936 17.830373637847625 11.112348832845516 17.961238765464852 C11.112348832845516 17.961238765464852 14.02116955673743 20.872987854948224 14.02116955673743 20.872987854948224 C14.151903072417964 21.01475840986689 14.337108886298722 21.09109640097694 14.533209159819528 21.09109640097694 C14.631259296579927 21.09109640097694 14.729309433340331 21.069285546374072 14.81646511046069 21.036569264469758 C15.099721061101844 20.927514991455404 15.27403241534256 20.654879308919504 15.252243496062473 20.3495273444793 C15.252243496062473 20.3495273444793 15.252243496062473 11.930537467770826 15.252243496062473 11.930537467770826 C15.252243496062473 11.930537467770826 20.841101291405362 6.336053262134233 20.841101291405362 6.336053262134233 C21.0698849438463 6.139755570708387 21.146146161326612 5.812592751665312 21.00451818600603 5.539957069129416 C20.895573589605586 5.256415959292082 20.623212098604473 5.071023695167673 20.31816722868322 5.092834549770544 Z" vector-effect="non-scaling-stroke"/></defs><g transform="translate(-5.07598791074798, -5.0910964009769435)"><path d="M20.31816722868322 5.092834549770544 C20.31816722868322 5.092834549770544 5.817641447783829 5.092834549770544 5.817641447783829 5.092834549770544 C5.512596577862579 5.071023695167673 5.251129546501509 5.256415959292082 5.14218495010106 5.539957069129414 C5.011451434420527 5.801687324363874 5.076818192260794 6.1397555707083855 5.316496304341776 6.3360532621342305 C5.316496304341776 6.3360532621342305 10.894459640044625 11.930537467770824 10.894459640044625 11.930537467770824 C10.894459640044625 11.930537467770824 10.894459640044625 17.448683682297368 10.894459640044625 17.448683682297368 C10.894459640044625 17.644981373723212 10.970720857524936 17.830373637847625 11.112348832845516 17.961238765464852 C11.112348832845516 17.961238765464852 14.02116955673743 20.872987854948224 14.02116955673743 20.872987854948224 C14.151903072417964 21.01475840986689 14.337108886298722 21.09109640097694 14.533209159819528 21.09109640097694 C14.631259296579927 21.09109640097694 14.729309433340331 21.069285546374072 14.81646511046069 21.036569264469758 C15.099721061101844 20.927514991455404 15.27403241534256 20.654879308919504 15.252243496062473 20.3495273444793 C15.252243496062473 20.3495273444793 15.252243496062473 11.930537467770826 15.252243496062473 11.930537467770826 C15.252243496062473 11.930537467770826 20.841101291405362 6.336053262134233 20.841101291405362 6.336053262134233 C21.0698849438463 6.139755570708387 21.146146161326612 5.812592751665312 21.00451818600603 5.539957069129416 C20.895573589605586 5.256415959292082 20.623212098604473 5.071023695167673 20.31816722868322 5.092834549770544 Z" style="stroke-width: 0; stroke-linecap: butt; stroke-linejoin: miter; fill: rgb(19, 101, 143);" vector-effect="non-scaling-stroke"/></g></g></svg>
            <?php echo __( "Filters", $this->plugin_name ); ?>
        </div>
        <div class="ays-survey-submissions-filter-container" <?php echo !($is_filter) ? 'style="display:none;"' : '' ?>>
            <div class="ays-survey-submissions-filter-section">
                <select name="filterbytype" class="form-select" id="ays-survey-submissions-filter-post">
                    <option value=""><?php echo __( "Select post", $this->plugin_name ); ?></option>
                    <?php
                        foreach ($survey_posts as $id => $title){
                            ?>
                                <option value="<?php echo $id; ?>" <?php echo $filters['post_id'] == $id ? 'selected' : '' ?>><?php echo $title; ?></option> 
                            <?php
                        }
                    ?>
                </select>
                <input type="date" name="filterbystartdate" class="ays-survey-submissions-filter-date" id="ays-survey-submissions-filter-start-date" placeholder="From" value="<?php echo is_null($filters['start_date']) ? '' : $filters['start_date']; ?>">
                <input type="date" name="filterbyenddate" class="ays-survey-submissions-filter-date" id="ays-survey-submissions-filter-end-date" placeholder="To" value="<?php echo is_null($filters['end_date']) ? '' : $filters['end_date']; ?>">
                <button type="submit" class="btn btn-outline-primary btn-sm" name="ays_survey_submissions_filter" id="ays-survey-submissions-filter"><?php echo esc_html(__( 'Filter', "chart-builder" )); ?></button>
                <button type="submit" class="btn btn-outline-primary btn-sm" name="ays_survey_submissions_filter_clear" id="ays-survey-submissions-filter-clear"><?php echo esc_html(__( 'Clear filters', "chart-builder" )); ?></button>
            </div>
        </div>
    </div>
    <div class="nav-tab-wrapper">
        <a href="#statistics_of_answer" class="nav-tab <?php echo ($ays_survey_tab == 'statistics_of_answer') ? 'nav-tab-active' : ''; ?>"><?php echo __("Summary", $this->plugin_name); ?></a>
        <a href="#questions" class="nav-tab <?php echo ($ays_survey_tab == 'questions') ? 'nav-tab-active' : ''; ?>"><?php echo __("Individual", $this->plugin_name); ?></a>
        <a href="#poststuff" class="nav-tab <?php echo ($ays_survey_tab == 'poststuff') ? 'nav-tab-active' : ''; ?>" ><?php echo __("Submissions", $this->plugin_name); ?></a>
        <a href="#statistics" class="nav-tab <?php echo ($ays_survey_tab == 'statistics') ? 'nav-tab-active' : ''; ?>"><?php echo __("Analytics", $this->plugin_name); ?></a>
    </div>
    <?php
        $tabs = array(
            'statistics_of_answer',
            'questions',
            'poststuff',
            'statistics',
        );
        $each_submission_obj = $this->each_submission_obj;

        foreach ( $tabs as $tab ) {
            Survey_Maker_Data::get_template_part( '/partials/submissions/partials/survey-maker-each-submission', $tab, get_defined_vars() );
        }
    ?>

    <div id="ays-results-modal" class="ays-modal">
        <div class="ays-modal-content">
            <div class="ays-survey-preloader">
                <img class="loader" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
            </div>
            <div class="ays-modal-header">
                <span class="ays-close" id="ays-close-results">&times;</span>
                <h2><?php echo __("Detailed report", $this->plugin_name); ?></h2>
            </div>
            <div class="ays-modal-body" id="ays-results-body">
            </div>
        </div>
    </div>

    <div class="ays-modal" id="ays-survey-export-answers-filters">
        <div class="ays-modal-content">
            <div class="ays-survey-preloader">
                <img class="loader" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
            </div>
          <!-- Modal Header -->
            <div class="ays-modal-header">
                <span class="ays-close">&times;</span>
                <h2><?=__('Export Filter', $this->plugin_name)?></h2>
            </div>

          <!-- Modal body -->
            <div class="ays-modal-body">
                <form method="post" id="ays_export_answers_filter">
                    <div class="filter-col">
                        <label for="survey_user_id-answers-filter"><?=__("Users", $this->plugin_name)?></label>
                        <button type="button" class="ays_userid_clear button button-small wp-picker-default"><?=__("Clear", $this->plugin_name)?></button>
                        <select name="survey_user_id-select[]" id="survey_user_id-answers-filter" multiple="multiple"></select>
                        <input type="hidden" name="survey_id-answers-filter" id="survey_id-answers-filter" value="<?php echo $survey_id; ?>">
                    </div>
                    <div class="filter-block">
                        <div class="filter-block filter-col">
                            <label for="start-date-answers-filter"><?=__("Start Date from", $this->plugin_name)?></label>
                            <input type="date" name="start-date-filter" id="start-date-answers-filter">
                        </div>
                        <div class="filter-block filter-col">
                            <label for="end-date-answers-filter"><?=__("Start Date to", $this->plugin_name)?></label>
                            <input type="date" name="end-date-filter" id="end-date-answers-filter">
                        </div>
                    </div>
                </form>
            </div>

          <!-- Modal footer -->
            <div class="ays-modal-footer">
                <div class="export_results_count">
                    <p><?php echo __( "Matched", $this->plugin_name ); ?> <span></span> <?php echo " " . __( "results", $this->plugin_name ); ?></p>
                </div>
                <div>
                    <span><?php echo __('Export to', $this->plugin_name) . " "; ?></span>
                    <button type="button" class="button button-primary ays-survey-export-anwers-action-csv" data-type="csv" survey-id="<?php echo $survey_id; ?>"><?=__('CSV', $this->plugin_name)?></button>
                    <a download="" id="downloadFile" hidden href=""></a>
                    <button type="button" class="button button-primary ays-survey-export-anwers-action" data-type="xlsx" survey-id="<?php echo $survey_id; ?>"><?=__('XLSX', $this->plugin_name)?></button>
                    <a download="" id="downloadFile" hidden href=""></a>
                </div>
            </div>

        </div>
    </div>

</div>

