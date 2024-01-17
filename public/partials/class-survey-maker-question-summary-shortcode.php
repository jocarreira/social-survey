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

class Survey_Maker_Question_Summary
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


        add_shortcode('ays_survey_question_summary', array($this, 'ays_generate_question_summary_method'));
    }

    public function ays_survey_get_survey_data_by_question_id( $question_id ){
        global $wpdb;

        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );


        if ( is_null( $question_id ) ) {
            return null;
        }

        $results = array();

        $sql = "SELECT * FROM " . $surveys_table . " WHERE FIND_IN_SET('". $question_id ."', `question_ids` ) > 0";
        $survey_data = $wpdb->get_row( $sql, 'ARRAY_A' );


        if ( empty( $survey_data ) || is_null( $survey_data ) ) {
            return null;
        }


        $survey_id = ( isset($survey_data['id']) && sanitize_text_field( $survey_data['id'] ) != '' ) ? absint( sanitize_text_field( $survey_data['id'] ) ) : null;

        $survey = Survey_Maker_Data::get_survey_by_id( $survey_id );
        $attr = array(
            "id" => $survey_id,
        );

        $this->options = Survey_Maker_Data::get_survey_validated_data_from_array( $survey, $attr );

        if ( empty( $survey_id ) || is_null( $survey_id ) ) {
            return null;
        }

        // For charts in summary
        $survey_question_results = Survey_Maker_Data::ays_survey_question_results( $survey_id , null, $question_id );
        $question_results = Survey_Maker_Data::apply_translation_for_arrays($survey_question_results['questions']);

        wp_localize_script( $this->plugin_name . '-public-charts', 'aysSurveyPublicChartLangObj', array(
            'answers'        => __( 'Answers' , $this->plugin_name ),
            'percent'        => __( 'Percent' , $this->plugin_name ),
            'count'          => __( 'Count' , $this->plugin_name ),
            'openSettingsImg' => '<img src="' . SURVEY_MAKER_ADMIN_URL .'/images/icons/settings.svg">',
        ) );

        $options = array(
            'perAnswerData' => $question_results,
            'surveyColor'    => $this->options[ $this->name_prefix . 'color' ],
            'matrixModalHtml' => isset($survey_question_results['matrix_modal_html']) ? $survey_question_results['matrix_modal_html'] : '',
        );
        
            
        $script = '<script type="text/javascript">';
        $script .= "
                if(typeof aysSurveyPublicQuestionChartData === 'undefined'){
                    var aysSurveyPublicQuestionChartData = [];
                }
                aysSurveyPublicQuestionChartData['" . $this->unique_id . "']  = '" . base64_encode( json_encode( $options ) ) . "';";
        $script .= '</script>';

        $results = array(
            'survey_id' => $survey_id,
            'question_results' => $question_results,
            'script' => $script,
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
        wp_enqueue_style( $this->plugin_name . "-public-questions", SURVEY_MAKER_PUBLIC_URL . '/css/partials/survey-maker-public-question.css', array(), $this->version, 'all' );

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
        wp_enqueue_script( $this->plugin_name . '-public-question-charts', SURVEY_MAKER_PUBLIC_URL . '/js/partials/survey-maker-public-question-charts.js', array('jquery'), $this->version, true);

        wp_localize_script( $this->plugin_name . '-public-question-charts', 'aysSurveyPublicChartLangObj', array(
            'answers'        => __( 'Answers' , $this->plugin_name ),
            'percent'        => __( 'Percent' , $this->plugin_name ),
            'count'          => __( 'Count' , $this->plugin_name ),
            'openSettingsImg' => '<img src="' . SURVEY_MAKER_ADMIN_URL .'/images/icons/settings.svg">',
        ) );
    }

    public function ays_question_summary_html( $id ){


        $content = array();

        $results = $this->ays_survey_get_survey_data_by_question_id( $id );
        
        if( $results === null ){
            $content = "<p style='text-align: center;font-style:italic;'>" . __( "There are no questions atteched yet.", $this->plugin_name ) . "</p>";
            return $content;
        }

        $survey_id = $results['survey_id'];
        $question_results = $results['question_results'];
        $script = $results['script'];

        $text_types = array(
            'text',
            'short_text',
            'number',
            'phone',
            'name',
            'email',
            'date',
            'time',
            'date_time',
            'hidden',
        );

        $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-container" id="' . $this->html_class_prefix . 'question-summary-container-' . $this->unique_id_in_class . '" data-id="' . $this->unique_id . '">';
            
            if( is_array( $question_results ) ):

                foreach ( $question_results as $q_key => $question ):

                    $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-question-container">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-question-header">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-question-header-content">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-question-header-content-title">' . nl2br( $question['question'] ) . '</div>';
                                $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-question-header-content-question">' . $question['sum_of_answers_count'] . ' ' . __( 'submission', $this->plugin_name ) . '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="' . $this->html_class_prefix . 'question-summary-question-content">';
                            if( in_array( $question['question_type'], $text_types ) && $question['question_type'] != 'date' && $question['question_type'] != 'time' && $question['question_type'] != 'date_time'):
                                $content[] = '<div class="' . $this->html_class_prefix . 'question-text-answers-div">';
                                    if( isset( $question['answers'] ) && !empty( $question['answers'] ) ):

                                        if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ):

                                            foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ):

                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-text-answer">'. stripslashes( nl2br( $answer ) ) .'</div>';

                                            endforeach;
                                        endif;
                                    endif;
                                $content[] = '</div>';
                            elseif( $question['question_type'] == 'date' ):
                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-wrapper">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-wrap">';
                                        if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                                            if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ){
                                                $dates_array = array();
                                                foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ){
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
                            elseif( $question['question_type'] == 'time' ):
                                $content[] = '<div class="ays-survey-question-time-summary-wrapper">';
                                    $content[] = '<div class="ays-survey-question-time-summary-wrap">';
                                        if( isset( $question['answers'] ) && !empty( isset( $question['answers'] ) ) ){
                                            if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ){
                                                $hours_array = array();
                                                foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ){
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
                            elseif( $question['question_type'] == 'date_time' ):
                                
                                $content[] = '<div class="ays-survey-question-date-time-summary-wrapper">';
                                    $content[] = '<div class="ays-survey-question-date-time-summary-wrap">';
                                        
                                        if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                                            if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ){
                                                $dates_array = array();
                                                $time_array = array();
                                                foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ){
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
                            elseif( $question['question_type'] == 'upload' ):
                                $upload_answers = array();
                                $upload_answers_name = array();
                                if(isset($question['answers'])){
                                    $upload_answers = isset($question['answers'][ $question['question_id'] ]) ? $question['answers'][ $question['question_id'] ] : array();
                                }
                                if(isset($question['answers_name'])){
                                    $upload_answers_name = isset($question['answers_name'][ $question['question_id'] ]) ? $question['answers_name'][ $question['question_id'] ] : array();
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
                                $content[] = '<div id="survey_answer_chart_' . $question['question_id'] . '" style="width: 100%;" class="chart_div"></div>';
                                if( !empty( $question['otherAnswers'] ) ):
                                    $content[] = '<div class="' . $this->html_class_prefix . 'other-answer-row">' . __( '"Other" answers', $this->plugin_name ) . '</div>';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-text-answers-div">';
                                        if( isset( $question['otherAnswers'] ) && !empty( $question['otherAnswers'] ) ):
                                            foreach( $question['otherAnswers'] as $aid => $answer ):
                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-text-answer">' . stripslashes( $answer ) . '</div>';
                                            endforeach;
                                        endif;
                                    $content[] = '</div>';
                                endif;
                            endif;
                        $content[] = '</div>';

                    $content[] = '</div>';
                endforeach;
            endif;

        $content[] = $this->get_styles();
        $content[] = $script;
        $content[] = '</div>';

        $content = implode( '', $content );

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
            #' . $this->html_class_prefix . 'question-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-summary-section-header {
                border-top-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'question-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-summary-question-container {
                border-left-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }

            #' . $this->html_class_prefix . 'question-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-summary-question-container .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .' . $this->html_class_prefix . 'answer-matrix-scale-column {
                color: #000;
            }

            @media screen and (max-width: 640px){
                #' . $this->html_class_prefix . 'question-summary-container-' . $this->unique_id_in_class . ' {
                    max-width: '. $mobile_max_width .';
                }
            }
            
            ';
        
        $content[] = '</style>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_generate_question_summary_method( $attr ) {

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

        $content = $this->ays_question_summary_html( $id );

        return str_replace(array("\r\n", "\n", "\r"), "\n", Survey_Maker_Data::ays_survey_translate_content($content));
    }
}
