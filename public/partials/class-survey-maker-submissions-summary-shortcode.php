<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/public/partials
 */

class Survey_Maker_Submissions_Summary
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $html_class_prefix = 'ays-survey-';
    private $html_name_prefix = 'ays-survey-';
    private $name_prefix = 'survey_';
    private $unique_id;
    private $unique_id_in_class;
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;


        add_shortcode('ays_survey_submissions_summary', array($this, 'ays_generate_submissions_summary_method'));
    }

    public function ays_survey_get_data_by_survey_id( $attr ){
        global $wpdb;

        
        $user_flag = null;
        if(isset($attr['user-flag'])){
            $user_id   = get_current_user_id();
            $user_flag =  isset($user_id) && $user_id > 0 ? $user_id : 0;
        }

        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );

        $survey_id = (isset($attr['id']) && sanitize_text_field( $attr['id'] ) != '') ? absint( sanitize_text_field( $attr['id'] ) ) : null;
        $detect_data_type = (isset($attr['restype']) ) ? sanitize_text_field( $attr['restype'] ) : null;
        
        if ( is_null( $survey_id ) ) {
            return null;
        }

        $results = array();

        $sql = "SELECT * FROM " . $surveys_table . " WHERE id =" . absint( $survey_id );
        $survey_name = $wpdb->get_row( $sql, 'ARRAY_A' );

        if ( empty( $survey_name ) || is_null( $survey_name ) ) {
            return null;
        }

        // // For charts in summary
        $survey_question_results = Survey_Maker_Data::ays_survey_question_results( $survey_id, null, null, $user_flag, $detect_data_type );
        $question_results = Survey_Maker_Data::apply_translation_for_arrays($survey_question_results['questions']);

        $last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id( $survey_id );

        $ays_survey_individual_questions = Survey_Maker_Data::ays_survey_individual_results_for_one_submission( $last_submission, $survey_name );

        if( empty( $ays_survey_individual_questions['sections'] ) ){
            $question_results = array();
        }

        $submission_count_and_ids = Survey_Maker_Data::get_submission_count_and_ids( $survey_id );
        wp_localize_script( $this->plugin_name . '-public-charts', 'aysSurveyPublicChartLangObj', array(
            'answers'        => __( 'Answers' , $this->plugin_name ),
            'percent'        => __( 'Percent' , $this->plugin_name ),
            'count'          => __( 'Count' , $this->plugin_name ),
            'openSettingsImg' => '<img src="' . SURVEY_MAKER_ADMIN_URL .'/images/icons/settings.svg">',
        ) );
        
        $matrix_modal_html = isset($survey_question_results['matrix_modal_html']) && $survey_question_results['matrix_modal_html'] != '' ? $survey_question_results['matrix_modal_html'] : '';

        $options = array(
            'perAnswerData'   => $question_results,
            'surveyColor'     => $this->options[ $this->name_prefix . 'color' ],
            'sectionDescHtml' => $this->options[ $this->name_prefix . 'allow_html_in_section_description' ],
            'matrixModalHtml' => $matrix_modal_html,
        );
        
            
        $script = '<script type="text/javascript">';
        $script .= "
                if(typeof aysSurveyPublicChartData === 'undefined'){
                    var aysSurveyPublicChartData = [];
                }
                aysSurveyPublicChartData['" . $this->unique_id . "']  = '" . base64_encode( json_encode( $options ) ) . "';";
        $script .= '</script>';

        $results = array(
            'ays_survey_individual_questions' => $ays_survey_individual_questions,
            'submission_count_and_ids' => $submission_count_and_ids,
            'question_results' => $question_results,
            'script' => $script,
            'options' => $options,
        );

        return $results;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( $this->plugin_name . "-font-awesome", SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-font-awesome.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . "-public-submissions", SURVEY_MAKER_PUBLIC_URL . '/css/partials/survey-maker-public-submissions.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(){

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_script( $this->plugin_name . '-public-charts-google', SURVEY_MAKER_ADMIN_URL . '/js/google-chart.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-functions.js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-functions.js', array('jquery'), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-public-charts', SURVEY_MAKER_PUBLIC_URL . '/js/partials/survey-maker-public-submissions-charts.js', array('jquery'), $this->version, true);
    }

    public function ays_submissions_summary_html( $attr ){

        $content = array();

        $results = $this->ays_survey_get_data_by_survey_id( $attr );

        if( $results === null ){
            $content = "<p style='text-align: center;font-style:italic;'>" . __( "There are no questions atteched yet.", $this->plugin_name ) . "</p>";
            return $content;
        }

        $ays_survey_individual_questions = $results['ays_survey_individual_questions'];
        $submission_count_and_ids = $results['submission_count_and_ids'];
        $question_results = $results['question_results'];
        $script = $results['script'];
        
        $hide_questions_ids_arr = isset($attr['hide_questions_ids']) && $attr['hide_questions_ids'] != '' ? explode("," , $attr['hide_questions_ids']) : array();

        // Allow HTML in description
        $survey_allow_html_in_section_description = (isset($results['options'][ 'sectionDescHtml' ]) && $results['options'][ 'sectionDescHtml' ] == 'on') ? true : false;

        $text_types = array(
            'text',
            'short_text',
            'number',
            'phone',
            'name',
            'email',
            'date',
            'time',
            'hidden'
        );

        $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-container" id="' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . '" data-id="' . $this->unique_id . '">';

            $display_none = '';
            if( isset( $attr['restype'] ) && $attr['restype'] == 'public' && $attr['restype'] == true){
                $display_none = 'display_none';
            }

            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-container '.$display_none.'" style="padding: 20px;">';
                $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-container-header">';
                    $content[] = sprintf( __( 'In total %s submission', $this->plugin_name ), intval( $submission_count_and_ids['submission_count'] ) );
                $content[] = '</div>';
            $content[] = '</div>';
            
            if( is_array( $ays_survey_individual_questions['sections'] ) ):
                foreach ($ays_survey_individual_questions['sections'] as $section_key => $section):
                    $content[] = '<div class="' . $this->html_class_prefix . 'submission-section ' . $this->html_class_prefix . 'submission-summary-section">';

                        $content[] = '<div class="ays_survey_name ' . $this->html_class_prefix . 'submission-summary-section-header">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-section-header-title">' . $section['title'] . '</div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-section-header-description">' . ($survey_allow_html_in_section_description) ? strip_tags(htmlspecialchars_decode($section['description'] )) : nl2br( $section['description'] ) . '</div>';
                        $content[] = '</div>';

                        foreach ( $section['questions'] as $q_key => $question ):
                            if(is_array($hide_questions_ids_arr) && !empty($hide_questions_ids_arr)){
                                if(in_array($question['id'] , $hide_questions_ids_arr)){
                                    continue;
                                }
                            }
                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-container">';

                                $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header-content">';
                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header-content-title">' . nl2br( $question_results[ $question['id'] ]['question'] ) . '</div>';
                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header-content-submission">' . $question_results[ $question['id'] ]['sum_of_answers_count'] . ' ' . __( 'submissions', $this->plugin_name ) . '</div>';
                                    $content[] = '</div>';
                                $content[] = '</div>';

                                $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-content">';
                                    if( in_array( $question_results[ $question['id'] ]['question_type'], $text_types ) && $question_results[ $question['id'] ]['question_type'] != 'date' && $question_results[ $question['id'] ]['question_type'] != 'time'):
                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answers-div">';
                                            if( isset( $question_results[ $question['id'] ]['answers'] ) && !empty( $question_results[ $question['id'] ]['answers'] ) ):
                                                if( isset( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) && !empty( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) ):
                                                    $filtered_text_answers = array_values(array_unique($question_results[ $question['id'] ]['answers'][ $question['id'] ]));
                                                    foreach( $filtered_text_answers as $aid => $answer ):
                                                        $text_answer_count = isset($question_results[ $question['id'] ]['sum_of_same_answers'][$answer]) && $question_results[ $question['id'] ]['sum_of_same_answers'][$answer] != "" ? $question_results[ $question['id'] ]['sum_of_same_answers'][$answer] : "";
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answer">
                                                                        <div>'. stripslashes(nl2br( esc_attr($answer) )) .'</div>
                                                                        <div>'. nl2br( $text_answer_count ) .'</div>
                                                                    </div>';
                                                    endforeach;
                                                endif;
                                            endif;
                                        $content[] = '</div>';
                                    elseif( $question_results[ $question['id'] ]['question_type'] == 'date' ):
                                        $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-wrapper">';
                                            $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-wrap">';
                                                if( isset( $question_results[ $question['id'] ]['answers'] ) && !empty( $question_results[ $question['id'] ]['answers'] ) ){
                                                    if( isset( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) && !empty( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) ){
                                                        $dates_array = array();
                                                        foreach( $question_results[ $question['id'] ]['answers'][ $question['id'] ] as $aid => $answer ){
                                                            $year_month = explode( '-', $answer );
                                                            $day = $year_month[2];
                                                            if( isset( $dates_array[ $year_month[0] ] ) ){
                                                                if( isset( $dates_array[ $year_month[0] ][ $year_month[1] ] ) ){
                                                                    if( isset( $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] ) ){
                                                                        $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] += 1;
                                                                    }else{
                                                                        $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                                                    }
                                                                }else{
                                                                    $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                                                }
                                                            }else{
                                                                $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                                            }
                                                        }

                                                        ksort( $dates_array, SORT_NATURAL );
                                                        foreach( $dates_array as $year => $months ){
                                                            ksort( $months, SORT_NATURAL );
                                                            foreach( $months as $month => $days ){
                                                                ksort( $days, SORT_NATURAL );
                                                            }
                                                        }

                                                        foreach( $dates_array as $year => $months ){
                                                            foreach( $months as $month => $days ){
                                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-row">';
                                                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-year-month">' . date_i18n( 'F Y', strtotime( $year ."-". $month ) ) . '</div>';
                                                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days">';
                                                                        $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row">';
                                                                            foreach( $days as $day => $count ){
                                                                                if( $count == 1 ){
                                                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row-day">';
                                                                                    $content[] = '<span>' . esc_html( $day ). '</span>';
                                                                                $content[] = '</div>';
                                                                                }else{
                                                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row-day ' . $this->html_class_prefix . 'question-date-summary-days-row-day-with-count">';
                                                                                    $content[] = '<span>' . esc_html( $day ) . '</span>';
                                                                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row-day-count">' . esc_html( $count ) . '</div>';
                                                                                $content[] = '</div>';
                                                                                }
                                                                            }
                                                                        $content[] = '</div>';
                                                                    $content[] = '</div>';
                                                                $content[] = '</div>';
                                                            }
                                                        }
                                                    }
                                                }
                                            $content[] = '</div>';
                                        $content[] = '</div>';
                                        
                                    elseif( $question_results[ $question['id'] ]['question_type'] == 'time' ):
                                        $content[] = '<div class="ays-survey-question-time-summary-wrapper">';
                                            $content[] = '<div class="ays-survey-question-time-summary-wrap">';
                                                if( isset( $question_results[ $question['id'] ]['answers'] ) && !empty( $question_results[ $question['id'] ]['answers'] ) ){
                                                    if( isset( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) && !empty( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) ){
                                                        $hours_array = array();
                                                        foreach( $question_results[ $question['id'] ]['answers'][ $question['id'] ] as $aid => $answer ){
                                                            $answer_hour_minutes = explode( ':', $answer );
                                                            $answer_hour = isset($answer_hour_minutes[0]) && $answer_hour_minutes[0] != "" ? esc_attr($answer_hour_minutes[0]) : "00";
                                                            $answer_minute = isset($answer_hour_minutes[1]) && $answer_hour_minutes[1] != "" ? esc_attr($answer_hour_minutes[1]) : "00";                                                            
                                                            if( isset( $hours_array[ $answer_hour ] ) ){
                                                                if( isset( $hours_array[ $answer_hour ][ $answer_minute ] ) ){
                                                                    $hours_array[ $answer_hour ][ $answer_minute ] += 1;
                                                                }else{
                                                                    $hours_array[ $answer_hour ][ $answer_minute ] = 1;
                                                                }
                                                            }else{
                                                                $hours_array[ $answer_hour ][ $answer_minute ] = 1;
                                                            }
                                                        }
                                                        ksort($hours_array);
                                                        foreach( $hours_array as $k_hours => $v_hours ){
                                                                
                                                            $content[] = '<div class="ays-survey-question-time-summary-row">';
                                                                $content[] = '<div class="ays-survey-question-time-summary-hour"><span class="ays-survey-question-time-summary-hour-all">'.$k_hours.' :</span></div>';
                                                                    $content[] = '<div class="ays-survey-question-time-summary-hours">';
                                                                        $content[] = '<div class="ays-survey-question-time-summary-hours-row">';
                                                                                foreach( $v_hours as $k_hour => $count ){
                                                                                    if( $count == 1 ){
                                                                                        $content[] = '<div class="ays-survey-question-time-summary-hours-row-hour">
                                                                                                        <span>'. esc_html( $k_hour ) .'</span>
                                                                                                    </div>';
                                                                                    }else{
                                                                                        $content[] = '<div class="ays-survey-question-time-summary-hours-row-hour ays-survey-question-time-summary-hours-row-hour-with-count">
                                                                                                        <span>'. esc_html( $k_hour ) .'</span>
                                                                                                        <div class="ays-survey-question-time-summary-hours-row-hour-count">'. esc_html( $count ) .'</div>';
                                                                                        $content[] = '</div>';
                                                                                    }
                                                                                }
                                                                    $content[] = '</div>';
                                                                $content[] = '</div>';
                                                            $content[] = '</div>';
                                                        }
                                                    }
                                                }
                                            $content[] = '</div>';
                                        $content[] = '</div>';
                                    elseif( $question_results[ $question['id'] ]['question_type'] == 'date_time' ):
                            
                                        $content[] = '<div class="ays-survey-question-date-time-summary-wrapper">';
                                            $content[] = '<div class="ays-survey-question-date-time-summary-wrap">';
                                                
                                                if( isset( $question_results[ $question['id'] ]['answers'] ) && !empty( $question_results[ $question['id'] ]['answers'] ) ){
                                                    if( isset( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) && !empty( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) ){
                                                        $dates_array = array();
                                                        $time_array = array();
                                                        foreach( $question_results[ $question['id'] ]['answers'][ $question['id'] ] as $aid => $answer ){
                                                            $each_answer = explode(" " , trim($answer));
                                                            $date_answer = isset($each_answer[0]) && $each_answer[0] != '' ? $each_answer[0] : '';
                                                            $time_answer = isset($each_answer[1]) && $each_answer[1] != '' ? $each_answer[1] : '';
                                                            if($time_answer != '-' || $date_answer != '-'){
                                                                $year_month_day = $date_answer != '-' ? explode( '-', $date_answer ) : '-';
                                                            
                                                                $dates_collected = '';
                                                                if($year_month_day != '-'){
                                                                    $dates_collected = date_i18n( 'F Y d', strtotime( $year_month_day[0] ."-". $year_month_day[1] . "-". $year_month_day[2] ) );
                                                                }
                                                                
                                                                $dates_array[] = $dates_collected;
                                                                $time_array[$dates_collected][] = $time_answer;
                                                                $dates_new_array = array_count_values($dates_array);
                                                            }
                                                        }
                                                        if(!empty($dates_array)){
                                                            foreach($dates_new_array as $new_date => $new_date_value){
                                                                
                                                                $content[] = '<div class="ays-survey-question-date-time-summary-row">';
                                                                    
                                                                    $content[] = '<div class="ays-survey-question-date-time-summary-year-month-day">';
                                                                        $content[] = '<div class="ays-survey-question-date-time-summary-year-month-day-row">';
                                                                            
                                                                                if( $new_date_value == 1 ){
                                                                                    $style_for_one_submission = (!$new_date) ? 'style="background-color: white;"' : '';
                                                                                    $content[] = '<div class="ays-survey-question-time-summary-hours-row-hour" '.$style_for_one_submission.' >';
                                                                                        $content[] = '<span>'.esc_html( $new_date ).'</span>';
                                                                                    $content[] = '</div>';
                                                                                
                                                                                }elseif($new_date != ''){
                                                                                
                                                                                    $content[] = '<div class="ays-survey-question-time-summary-hours-row-hour ays-survey-question-time-summary-hours-row-hour-with-count">';
                                                                                        $content[] = '<span><?php echo esc_html( $new_date ); ?></span>';
                                                                                        $content[] = '<div class="ays-survey-question-time-summary-hours-row-hour-count">'.esc_html( $new_date_value ).'</div>';
                                                                                    $content[] = '</div>';
                                                                                
                                                                                }
                                                                            
                                                                        $content[] = '</div>';
        
                                                                    $content[] = '</div>';
                                                                    $content[] = '<div class="ays-survey-question-date-summary-days">';
                                                                    
                                                                    
                                                                        foreach($time_array[$new_date] as $f => $r){
                                                                            if($r != '' && $r != '-'){
                                                                            
                                                                                $content[] = '<span class="ays-survey-question-date-time-summary-hour-all">'.$r.'</span>';
                                                                            
                                                                            }
                                                                        }
                                                                    
                                                                $content[] = '</div>';
                                                            $content[] = '</div>';
                                                            
                                                            }
                                                        }
                                                        
                                                    }
                                                }
                                                
                                            $content[] = '</div>';
                                        $content[] = '</div>';
                                    elseif( $question_results[ $question['id'] ]['question_type'] == 'upload' ):
                                        $upload_answers = array();
                                        $upload_answers_name = array();
                                        if(isset($question_results[ $question['id'] ]['answers'])){
                                            $upload_answers = isset($question_results[ $question['id'] ]['answers'][ $question['id'] ]) ? $question_results[ $question['id'] ]['answers'][ $question['id'] ] : array();
                                        }
                                        if(isset($question_results[ $question['id'] ]['answers_name'])){
                                            $upload_answers_name = isset($question_results[ $question['id'] ]['answers_name'][ $question['id'] ]) ? $question_results[ $question['id'] ]['answers_name'][ $question['id'] ] : array();
                                        }
                                        
                                        $content[] = '<div class="ays_questions_upload_type_answers_summary">';
                                        foreach($upload_answers as $u_key => $u_value){
                                            $upload_answer_ready_name = isset($upload_answers_name[$u_key]) ? $upload_answers_name[$u_key] : "";
                                            if(!$upload_answer_ready_name) continue;
                                            $content[] = '<div class="ays-survey-answer-upload-ready-summary">';
                                                $content[] = '<a href="'.$u_value.'" class="ays-survey-answer-upload-ready-link-summary" download="'.$upload_answer_ready_name.'">'.$upload_answer_ready_name.'</a>';                                            
                                            $content[] = '</div>';                                            
                                        }
                                        $content[] = '</div>';
                                    else:
                                        $content[] = '<div id="survey_answer_chart_' . $question_results[ $question['id'] ]['question_id'] . '" style="width: 100%;" class="chart_div"></div>';
                                        if( !empty( $question_results[ $question['id'] ]['otherAnswers'] ) ):
                                            $content[] = '<div class="' . $this->html_class_prefix . 'other-answer-row">' . __( '"Other" answers', $this->plugin_name ) . '</div>';
                                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answers-div">';
                                                if( isset( $question_results[ $question['id'] ]['otherAnswers'] ) && !empty( $question_results[ $question['id'] ]['otherAnswers'] ) ):
                                                    $filtered_other_answers = array_values(array_unique($question_results[ $question['id'] ]['otherAnswers']));
                                                    foreach( $question_results[ $question['id'] ]['otherAnswers'] as $aid => $answer ):
                                                        $other_answer_count = isset($question_results[ $question['id'] ]['same_other_count'][$answer]) && $question_results[ $question['id'] ]['same_other_count'][$answer] != "" ? $question_results[ $question['id'] ]['same_other_count'][$answer] : "";
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answer">
                                                                        <div>' . stripslashes(esc_attr($answer)) . '</div>
                                                                        <div>' . stripslashes($other_answer_count) . '</div>
                                                                      </div>';
                                                    endforeach;
                                                endif;
                                            $content[] = '</div>';
                                        endif;
                                    endif;
                                $content[] = '</div>';

                            $content[] = '</div>';
                        endforeach;

                    $content[] = '</div>';

                endforeach;
            endif;

        $content[] = $this->get_styles();
        $content[] = $script;
        $content[] = '</div>';
        $content = implode( '', Survey_Maker_Data::ays_survey_translate_content($content) );

        return $content;
    }

    public function get_styles(){
        
        $content = array();
        $content[] = '<style type="text/css">';

        $mobile_max_width = $this->options[ $this->name_prefix . 'mobile_max_width' ];
        
        if( absint( $mobile_max_width ) > 0 ){
            $mobile_max_width .= '%';
        }else{
            $mobile_max_width = '90%';
        }

        $content[] = '
            #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-summary-section-header {
                border-top-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-summary-question-container {
                border-left-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }

            #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-summary-question-container .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .' . $this->html_class_prefix . 'answer-matrix-scale-column {
                color: #000;
            }

            @media screen and (max-width: 640px){
                #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' {
                    max-width: '. $mobile_max_width .';
                }
            }
            
            ';
        
        $content[] = '</style>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_generate_submissions_summary_method( $attr ) {

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint(intval($attr['id'])) : null;

        if (is_null($id)) {
            $content = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), '', $content);
        }

        $this->enqueue_styles();
        $this->enqueue_scripts();

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;

        $survey = Survey_Maker_Data::get_survey_by_id( $id );

        $this->options = Survey_Maker_Data::get_survey_validated_data_from_array( $survey, $attr );

        $content = $this->ays_submissions_summary_html( $attr );

        return str_replace(array("\r\n", "\n", "\r"), "\n", $content);
    }
}
