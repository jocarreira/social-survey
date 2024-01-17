<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/public
 * @author     Survey Maker team <info@ays-pro.com>
 */
class Survey_Maker_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $html_class_prefix = 'ays-survey-';
	private $html_name_prefix = 'ays-survey-';
	private $name_prefix = 'survey_';
	private $unique_id;
	private $unique_id_in_class;
    private $options;
    protected $buttons_texts;
    private $settings;
    private $lazy_loading;
    private $message_variable_data;

    private $chat_loader = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="" width="30px" height="30px" viewBox="25 25 50 50" preserveAspectRatio="xMidYMid">
        <circle cx="35" cy="57.5" r="5" fill="#222222">
            <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="57.5;42.5;57.5;57.5" keyTimes="0;0.3;0.6;1" dur="0.8s" begin="-0.4799999999999999s"></animate>
        </circle>
        <circle cx="50" cy="57.5" r="5" fill="#222222">
            <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="57.5;42.5;57.5;57.5" keyTimes="0;0.3;0.6;1" dur="0.8s" begin="-0.32s"></animate>
        </circle>
        <circle cx="65" cy="57.5" r="5" fill="#222222">
            <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="57.5;42.5;57.5;57.5" keyTimes="0;0.3;0.6;1" dur="0.8s" begin="-0.16s"></animate>
        </circle>
    </svg>';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->settings = new Survey_Maker_Settings_Actions($this->plugin_name);

        add_shortcode('ays_survey', array($this, 'ays_generate_survey_method'));
        add_shortcode('ays_survey_popup', array($this, 'ays_generate_survey_popup_method'));
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
        // wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        // wp_enqueue_style( 'jquery-ui' );
        wp_enqueue_style( $this->plugin_name . "-font-awesome", plugin_dir_url( __FILE__ ) . 'css/survey-maker-font-awesome.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . "-transition", plugin_dir_url( __FILE__ ) . 'css/transition.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . "-dropdown", plugin_dir_url( __FILE__ ) . 'css/dropdown.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url(__FILE__) . 'css/survey-maker-select2.min.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name . "-loaders", plugin_dir_url( __FILE__ ) . 'css/loaders.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . '-timepicker', plugin_dir_url( __FILE__ ) . '/css/survey-maker-timepicker.css', array(), $this->version, 'all');
        if( isset($this->options[ $this->name_prefix . 'show_summary_after_submission' ]) && $this->options[ $this->name_prefix . 'show_summary_after_submission' ] ){
            wp_enqueue_style( $this->plugin_name . "-public-submissions", plugin_dir_url( __FILE__ ) . 'css/partials/survey-maker-public-submissions.css', array(), $this->version, 'all' );
        }
    }
    
    public function enqueue_styles_early(){
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/survey-maker-public.css', array(), $this->version, 'all' );
        $is_elementor_exists = Survey_Maker_Data::ays_survey_is_elementor();
        if($is_elementor_exists){
            wp_enqueue_style( $this->plugin_name . "-public-chat-survey", plugin_dir_url( __FILE__ ) . 'css/survey-maker-public-chat-survey.css', array(), $this->version, 'all' );
        }
    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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
        $is_elementor_exists = Survey_Maker_Data::ays_survey_is_elementor();
        if( !$is_elementor_exists ){
            wp_enqueue_script( $this->plugin_name . '-autosize', plugin_dir_url( __FILE__ ) . 'js/survey-maker-autosize.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-transition', plugin_dir_url( __FILE__ ) . 'js/transition.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-dropdown', plugin_dir_url( __FILE__ ) . 'js/dropdown.min.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-select2js', plugin_dir_url(__FILE__) . 'js/survey-maker-select2.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script( $this->plugin_name . '-plugin', plugin_dir_url( __FILE__ ) . 'js/survey-maker-public-plugin.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-sweetalert-js', plugin_dir_url(__FILE__) . 'js/survey-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            // wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( $this->plugin_name . "-timepicker", plugin_dir_url(__FILE__) . '/js/survey-maker-timepicker.js', array( 'jquery' ), $this->version, false );

            if( isset($this->options[ $this->name_prefix . 'show_summary_after_submission' ]) && $this->options[ $this->name_prefix . 'show_summary_after_submission' ] ){
                wp_enqueue_script( $this->plugin_name . '-public-charts-google', SURVEY_MAKER_ADMIN_URL . '/js/google-chart.js', array('jquery'), $this->version, true);
                wp_enqueue_script( $this->plugin_name . '-public-charts', plugin_dir_url( __FILE__ ) . 'js/partials/survey-maker-public-submissions-charts.js', array('jquery'), $this->version, true);

                wp_localize_script( $this->plugin_name . '-public-charts', 'aysSurveyPublicChartLangObj', array(
                    'answers'        => __( 'Answers' , $this->plugin_name ),
                    'percent'        => __( 'Percent' , $this->plugin_name ),
                    'count'          => __( 'Count' , $this->plugin_name ),
                    'openSettingsImg' => '<img src="' . SURVEY_MAKER_ADMIN_URL .'/images/icons/settings.svg">',
                ) );
            }
            
            wp_enqueue_script( $this->plugin_name . '-functions.js', plugin_dir_url(__FILE__) . 'js/survey-maker-functions.js', array('jquery'), $this->version, true );

            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/survey-maker-public.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-ajax', plugin_dir_url( __FILE__ ) . 'js/survey-maker-public-ajax.js', array( 'jquery' ), $this->version, false );
            wp_localize_script( $this->plugin_name . '-plugin', 'aysSurveyMakerAjaxPublic', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'warningIcon' => SURVEY_MAKER_PUBLIC_URL . "/images/warning.svg",
            ) );
            wp_localize_script( $this->plugin_name, 'aysSurveyLangObj', array(
                'notAnsweredText'       => __( 'You have not answered this question', $this->plugin_name ),
                'areYouSure'            => __( 'Do you want to finish the quiz? Are you sure?', $this->plugin_name ),
                'sorry'                 => __( 'Sorry', $this->plugin_name ),
                'unableStoreData'       => __( 'We are unable to store your data', $this->plugin_name ),
                'connectionLost'        => __( 'Connection is lost', $this->plugin_name ),
                'checkConnection'       => __( 'Please check your connection and try again', $this->plugin_name ),
                'selectPlaceholder'     => __( 'Select an answer', $this->plugin_name ),
                'shareDialog'           => __( 'Share Dialog', $this->plugin_name ),
                'passwordIsWrong'       => __( 'Password is wrong!', $this->plugin_name ),
                'choose'                => __( 'Choose', $this->plugin_name ),
                'redirectAfter'         => __( 'Redirecting after', $this->plugin_name ),
                'emailValidationError'  => __( 'Must be a valid email address', $this->plugin_name ),
                'requiredError'         => __( 'This is a required question', $this->plugin_name ),
                'minimumVotes'          => __( 'Min answers count should be', $this->plugin_name ),
                'maximumVotes'          => __( 'Max answers count should be', $this->plugin_name ),
            ) );

            wp_enqueue_script( $this->plugin_name . "-xlsx.core.min.js", SURVEY_MAKER_ADMIN_URL . '/js/xlsx.core.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . "-fileSaver.js", SURVEY_MAKER_ADMIN_URL . '/js/FileSaver.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . "-jhxlsx.js", SURVEY_MAKER_ADMIN_URL . '/js/jhxlsx.js', array( 'jquery' ), $this->version, true );
        }
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts_popups() {
		wp_enqueue_script( $this->plugin_name . '-popups', plugin_dir_url( __FILE__ ) . 'js/survey-maker-public-popups.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name . '-popups', 'aysSurveyMakerPopupsAjaxPublic', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
        ) );
	}

	public function ays_survey_ajax(){
		global $wpdb;

		$response = array(
			"status" => false
		);
		$function = isset($_REQUEST['function']) ? sanitize_text_field( $_REQUEST['function'] ) : null;
        
		if($function !== null){
			$response = array();
			if( is_callable( array( $this, $function ) ) ){
				$response = $this->$function();
                
	            ob_end_clean();
	            $ob_get_clean = ob_get_clean();
				echo json_encode( $response );
				wp_die();
			}
                // $data = $_REQUEST;
                // $results = array();
                // unset($data['action']);
                // unset($data['function']);
                // switch ($function) {
                //     case 'ays_finish_survey':
                //         $results = $this->ays_finish_survey( $data );
                //         break;
                //     case 'ays_survey_get_user_information':
                //         $results = $this->ays_survey_get_user_information( $data );
                //         break;    
                // }

                // ob_end_clean();
                // $ob_get_clean = ob_get_clean();
                // echo json_encode( $results );
                // wp_die();
        }
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
		echo json_encode( $response );
		wp_die();
	}

	public function ays_finish_survey(){
        $unique_id = isset($_REQUEST['unique_id']) ? sanitize_text_field( $_REQUEST['unique_id'] ) : null;
        if($unique_id === null){
            return array("status" => false, "message" => "No no no" );
        } else {
            global $wpdb;
            $name_prefix = 'ays-survey-';
            $valid_name_prefix = 'survey_';
            $survey_id = isset( $_REQUEST[ $name_prefix . 'id-' . $unique_id ] ) ? absint( sanitize_text_field( $_REQUEST[ $name_prefix . 'id-' . $unique_id ] ) ) : null;
            $post_id = isset( $_REQUEST[ $name_prefix . 'post-id' ] ) ? absint( sanitize_text_field( $_REQUEST[ $name_prefix . 'post-id' ] ) ) : null;

            if($survey_id === null){
	            return array("status" => false, "message" => "No no no" );
            }else{
                do_action("ays_sm_front_end_integrations_after_voteing", $survey_id);
                $survey = Survey_Maker_Data::get_survey_by_id( $survey_id );
                $user_id = get_current_user_id();
                $attr = array(
                    'id' => $survey_id
                );
                $options = Survey_Maker_Data::get_survey_validated_data_from_array( $survey, $attr );

                $wp_user = null;
                if( is_user_logged_in() ){
                    $wp_user = get_userdata( get_current_user_id() );
                }
                
                $chat_mode = isset( $options['options'][ $valid_name_prefix . 'enable_chat_mode' ] ) && ($options['options'][ $valid_name_prefix . 'enable_chat_mode' ] == 'on') ? true : false;

                $thank_you_message = trim( $options[ $valid_name_prefix . 'final_result_text' ] );
                if( $thank_you_message == '' ){
                    $thank_you_message = __( "Thank you for completing this survey.", $this->plugin_name );
                }

                $answered_questions = isset( $_REQUEST[ $name_prefix . 'answers-' . $unique_id ] ) && !empty( $_REQUEST[ $name_prefix . 'answers-' . $unique_id ] ) ? Survey_Maker_Data::recursive_sanitize_text_field( $_REQUEST[ $name_prefix . 'answers-' . $unique_id ], array('answer') ) : array();
                $questions_data = isset( $_REQUEST[ $name_prefix . 'questions-' . $unique_id ] ) && !empty( $_REQUEST[ $name_prefix . 'questions-' . $unique_id ] ) ? Survey_Maker_Data::recursive_sanitize_text_field( $_REQUEST[ $name_prefix . 'questions-' . $unique_id ] ) : array();

                $survey_additional_wp_data = isset($_REQUEST[ $valid_name_prefix . 'additional_wp_data' ]) && $_REQUEST[ $valid_name_prefix . 'additional_wp_data' ] != '' ? json_decode(base64_decode($_REQUEST[ $valid_name_prefix . 'additional_wp_data' ]) , true) : array();

                $survey_current_page_link = isset( $_REQUEST['ays-survey-current_page_link'] ) && $_REQUEST['ays-survey-current_page_link'] != '' ? sanitize_url( $_REQUEST['ays-survey-current_page_link'] ) : "";
                $survey_current_page_link_html = "<a href='". esc_sql( $survey_current_page_link ) ."' target='_blank' class='ays-survey-current-page-link-a-tag'>". __( "Survey link", $this->plugin_name ) ."</a>";

                $user_explanation = isset( $_REQUEST[ $name_prefix . 'user-explanation-' . $unique_id ] ) && !empty( $_REQUEST[ $name_prefix . 'user-explanation-' . $unique_id ] ) ? Survey_Maker_Data::recursive_sanitize_text_field( $_REQUEST[ $name_prefix . 'user-explanation-' . $unique_id ] ) : array();

                $user_email = '';
                if( isset( $_REQUEST[ $name_prefix . 'user-email-' . $unique_id ] ) && !empty( $_REQUEST[ $name_prefix . 'user-email-' . $unique_id ] ) ){
                    if( is_array( $_REQUEST[ $name_prefix . 'user-email-' . $unique_id ] ) ){
                        $user_emails_arr = Survey_Maker_Data::recursive_sanitize_text_field( $_REQUEST[ $name_prefix . 'user-email-' . $unique_id ] );
                        $user_email = $answered_questions[ $user_emails_arr[ count( $user_emails_arr ) - 1 ] ];
                    }else{
                        $user_email = $answered_questions[ sanitize_text_field( $_REQUEST[ $name_prefix . 'user-email-' . $unique_id ] ) ];
                    }
                }

                if( is_array( $user_email ) ){
                    if( isset( $user_email['answer'] ) && !empty( $user_email['answer'] ) ){
                        $user_email = $user_email['answer'];
                    }else{
                        $user_email = '';
                    }
                }
                
                if( $options[ $valid_name_prefix . 'allow_collecting_logged_in_users_data' ] && $user_email == '' && $wp_user !== null ){
                    $user_email = $wp_user->data->user_email;
                }
                
                $user_name = '';
                if( isset( $_REQUEST[ $name_prefix . 'user-name-' . $unique_id ] ) && !empty( $_REQUEST[ $name_prefix . 'user-name-' . $unique_id ] ) ){
                    if( is_array( $_REQUEST[ $name_prefix . 'user-name-' . $unique_id ] ) ){
                        $user_names_arr = Survey_Maker_Data::recursive_sanitize_text_field( $_REQUEST[ $name_prefix . 'user-name-' . $unique_id ] );
                        $user_name = $answered_questions[ $user_names_arr[ count( $user_names_arr ) - 1 ] ];
                    }else{
                        $user_name = $answered_questions[ sanitize_text_field( $_REQUEST[ $name_prefix . 'user-name-' . $unique_id ] ) ];
                    }
                }

                if( is_array( $user_name ) ){
                    if( isset( $user_name['answer'] ) && !empty( $user_name['answer'] ) ){
                        $user_name = $user_name['answer'];
                    }else{
                        $user_name = '';
                    }
                }

                if( $options[ $valid_name_prefix . 'allow_collecting_logged_in_users_data' ] && $user_name == '' && $wp_user !== null ){
                    $user_name = $wp_user->data->display_name;
                }

                $user_name = stripslashes( $user_name );

                $start_date = isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '' ? sanitize_text_field( $_REQUEST['start_date'] ) : current_time( 'mysql' );
                $end_date = isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '' ? sanitize_text_field( $_REQUEST['end_date'] ) : current_time( 'mysql' );

                $result_unique_code = strtoupper( uniqid() );

                $setting_options = Survey_Maker_Data::get_setting_data( 'options' );
              
                // Do not store IP addresses 
                $settings_options[ $valid_name_prefix . 'disable_user_ip' ] = (isset($setting_options[ $valid_name_prefix . 'disable_user_ip' ]) &&  $setting_options[ $valid_name_prefix . 'disable_user_ip' ] == 'on') ? stripslashes( $setting_options[ $valid_name_prefix . 'disable_user_ip' ] ): 'off';
                $survey_disable_user_ip = (isset($setting_options[ $valid_name_prefix . 'disable_user_ip' ]) && $setting_options[ $valid_name_prefix . 'disable_user_ip' ] == 'on') ? true : false;

                // Do not store User Names
                $settings_options[ $valid_name_prefix . 'disable_user_name' ] = (isset($setting_options[ $valid_name_prefix . 'disable_user_name' ]) &&  $setting_options[ $valid_name_prefix . 'disable_user_name' ] == 'on') ? stripslashes( $setting_options[ $valid_name_prefix . 'disable_user_name' ] ): 'off';
                $survey_disable_user_name = (isset($setting_options[ $valid_name_prefix . 'disable_user_name' ]) && $setting_options[ $valid_name_prefix . 'disable_user_name' ] == 'on') ? true : false;

                // Do not store User Emails
                $settings_options[ $valid_name_prefix . 'disable_user_email' ] = (isset($setting_options[ $valid_name_prefix . 'disable_user_email' ]) &&  $setting_options[ $valid_name_prefix . 'disable_user_email' ] == 'on') ? stripslashes( $setting_options[ $valid_name_prefix . 'disable_user_email' ] ): 'off';
                $survey_disable_user_email = (isset($setting_options[ $valid_name_prefix . 'disable_user_email' ]) && $setting_options[ $valid_name_prefix . 'disable_user_email' ] == 'on') ? true : false;
                
                $survey_question_count = Survey_Maker_Data::get_survey_questions_count($survey_id);
                $survey_sections_count = Survey_Maker_Data::get_survey_sections_count($survey_id);
                $survey_passed_users_count = Survey_Maker_Data::ays_survey_get_passed_users_count($survey_id);

                $user_ip = '';
                if($survey_disable_user_ip){
                    $user_ip = '';
                }else{
                    $user_ip = Survey_Maker_Data::get_user_ip();
                }

                if ( $survey_disable_user_name ) {
                    $user_name  = '';
                    $user_id    = '';
                }

                if ( $survey_disable_user_email ) {
                    $user_email = '';
                    $user_id    = '';
                }

                $user_used_password = isset($_REQUEST[$this->html_name_prefix.'password']) && $_REQUEST[$this->html_name_prefix.'password'] != "" ? sanitize_text_field($_REQUEST[$this->html_name_prefix.'password']) : '';

                $survey_user_information = Survey_Maker_Data::get_user_profile_data();
                // Get user first name
                $user_first_name = (isset( $survey_user_information['user_first_name'] ) && $survey_user_information['user_first_name']  != "") ? $survey_user_information['user_first_name'] : '';
                // Get user last name
                $user_last_name  = (isset( $survey_user_information['user_last_name'] ) && $survey_user_information['user_last_name']  != "") ? $survey_user_information['user_last_name'] : '';
                // Get user nick name
                $user_nick_name  = (isset( $survey_user_information['user_nickname'] ) && $survey_user_information['user_nickname']  != "") ? $survey_user_information['user_nickname'] : '';
                // Get display name
                $user_display_name  = (isset( $survey_user_information['user_display_name'] ) && $survey_user_information['user_display_name']  != "") ? $survey_user_information['user_display_name'] : '';
                // User Wordpress role
                $user_wordpress_roles = (isset( $survey_user_information['user_wordpress_roles'] ) && $survey_user_information['user_wordpress_roles']  != "") ? $survey_user_information['user_wordpress_roles'] : '';
                // User ip address
                $user_ip_address = (isset( $survey_user_information['user_ip_address'] ) && $survey_user_information['user_ip_address']  != "") ? $survey_user_information['user_ip_address'] : '';
                // User wordpress email
                $user_wordpress_email = (isset( $survey_user_information['user_wordpress_email'] ) && $survey_user_information['user_wordpress_email']  != "") ? esc_attr($survey_user_information['user_wordpress_email']) : '';

                // Current date
                $survey_current_date = date_i18n( 'M d, Y', strtotime( sanitize_text_field( $_REQUEST['end_date'] ) ) );

                // WP home page url
                $home_main_url = home_url();
                $wp_home_page_url = '<a href="'.$home_main_url.'" target="_blank">'.$home_main_url.'</a>';


                // Current time
                $survey_current_time = explode( ' ', current_time( 'mysql' ) );
                $survey_current_time_only = ($survey_current_time[1]) ? $survey_current_time[1] : '';

                // Get submission count
                $survey_submission_count_and_ids = Survey_Maker_Data::get_submission_count_and_ids_for_summary($survey_id);
                $survey_submission_count = isset($survey_submission_count_and_ids['submission_count']) && $survey_submission_count_and_ids['submission_count'] != '' ? intval($survey_submission_count_and_ids['submission_count']) : 0;
                $survey_submission_count += isset($_REQUEST['ays_survey_update_submission_'.$survey_id.'']) ? 0 : 1;

                // Get survey author
                $current_survey_user_data = get_userdata( $survey->author_id );
                $current_survey_author_email = '';
                if ( isset( $current_survey_user_data ) && $current_survey_user_data ) {
                    // Get survey author name
                    $current_survey_author = ( isset( $current_survey_user_data->data->display_name ) && $current_survey_user_data->data->display_name != '' ) ? sanitize_text_field( $current_survey_user_data->data->display_name ) : "";

                    // Get survey author email
                    $current_survey_author_email = ( isset( $current_survey_user_data->data->user_email ) && $current_survey_user_data->data->user_email != '' ) ? sanitize_text_field( $current_survey_user_data->data->user_email ) : "";

                }

                $super_admin_email  = "";
                $wp_all_admins = get_users('role=Administrator');
                if(!empty($wp_all_admins)){
                    $super_admin_email = isset($wp_all_admins[0]) ? $wp_all_admins[0]->data->user_email : '';
                }

                $survey_current_post_id = '';
                $survey_current_post_author_email = '';
                $survey_current_post_author_nickname = '';
                if(!empty($survey_additional_wp_data)){
                    if(isset($survey_additional_wp_data['survey_post_type']) && $survey_additional_wp_data['survey_post_type'] == 'post'){
                        $survey_current_post_id = isset($survey_additional_wp_data['survey_post_id']) && $survey_additional_wp_data['survey_post_id'] != '' ? $survey_additional_wp_data['survey_post_id'] : '';
                    }
                    $survey_current_post_author_email = isset($survey_additional_wp_data['survey_post_author_email']) && $survey_additional_wp_data['survey_post_author_email'] != '' ? esc_attr($survey_additional_wp_data['survey_post_author_email']) : '';
                    $survey_current_post_author_nickname = isset($survey_additional_wp_data['survey_current_post_author_nickname']) && $survey_additional_wp_data['survey_current_post_author_nickname'] != '' ? esc_attr($survey_additional_wp_data['survey_current_post_author_nickname']) : '';
                }

                if ($chat_mode) {
                    foreach ($questions_data as $question_id => $question_data) {
                        if ($question_data['questionType'] == 'email') {
                            $user_email = isset($answered_questions[$question_id]['answer']) ? stripslashes(sanitize_text_field($answered_questions[$question_id]['answer'])) : '';
                        } else if ($question_data['questionType'] == 'name') {
                            $user_name = isset($answered_questions[$question_id]['answer']) ? stripslashes(sanitize_text_field($answered_questions[$question_id]['answer'])) : '';
                        }
                    }
                }

                $detectedDevice = Survey_Maker_Data::ays_survey_detected_device_chart();

                $message_data = array(
                    'survey_title'           => stripslashes($survey->title),
                    'survey_id'              => stripslashes($survey->id),
                    'post_id'                => $survey_current_post_id,
                    'user_name'              => $user_name,
                    'user_email'             => $user_email,
                    'user_wordpress_email'   => $user_wordpress_email,
                    'user_id'                => $user_id,
                    'questions_count'        => $survey_question_count,
                    'current_date'           => $survey_current_date,
                    'current_time'           => $survey_current_time_only,
                    'unique_code'            => $result_unique_code,
                    'sections_count'         => $survey_sections_count,
                    'users_count'            => $survey_passed_users_count,
                    'users_first_name'       => $user_first_name,
                    'users_last_name'        => $user_last_name,
                    'users_nick_name'        => $user_nick_name,
                    'user_wordpress_roles'   => $user_wordpress_roles,
                    'users_display_name'     => $user_display_name,
                    'users_ip_address'       => $user_ip_address,
                    'creation_date'          => sanitize_text_field( $survey->date_created ),
                    'current_survey_author'  => $current_survey_author,
                    'current_survey_author_email' => $current_survey_author_email,
                    'current_survey_page_link' => $survey_current_page_link_html,
                    'admin_email'              => $super_admin_email,
                    'home_page_url'            => $wp_home_page_url,
                    'post_author_email'        => $survey_current_post_author_email,
                    'post_author_nickname'     => $survey_current_post_author_nickname,
                    'submission_count'         => $survey_submission_count,
                );

                $send_data = array(
                    'questions_data' => $questions_data,
                    'answered_questions' => $answered_questions,
                    'survey' => $survey,
                    'questions_ids' => $survey->question_ids,
                    'user_id' => $user_id,
                    'user_ip' => $user_ip,
                    'user_name' => $user_name,
                    'user_email' => $user_email,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'unique_code' => $result_unique_code,
                    'user_explanation' => $user_explanation,
                    'detectedDevice' => $detectedDevice,
                    'chat_mode' => $chat_mode,
                    'user_password' => $user_used_password,
                    'post_id' => $post_id,
                );

                $check_limitations = false;
                
                if(isset($options['survey_limit_users']) && $options['survey_limit_users']){
                    $limit_users_by = isset($options['survey_limit_users_by']) && $options['survey_limit_users_by'] != "" ? $options['survey_limit_users_by'] : "";
                    $limit_users_pass_count = isset($options['survey_max_pass_count']) && $options['survey_max_pass_count'] != "" && $options['survey_max_pass_count'] != 0 ? $options['survey_max_pass_count'] : 0;
                    $limit_users_attr = array(
                        'id'    => $survey_id,
                        'name'  => 'ays_survey_cookie_',
                        'title' => $survey->title,
                        'increase_count' => true,
                    );
                    $check_limitations = $this->ays_survey_check_limitations($limit_users_by, $limit_users_attr);
                }


                // = = = = = = = = = = = = = = = = = = = = = = = = = = =
                // = = = = = = = = Conditions start  = = = = = = = = = = 
                // = = = = = = = = = = = = = = = = = = = = = = = = = = =
                                
                    // Show all conditions results
                    $survey_condition_show_all_results = isset($options['survey_condition_show_all_results']) && $options['survey_condition_show_all_results'] ? true : false;
                    
                    $conditions = isset($survey->conditions) && $survey->conditions != "" ? json_decode($survey->conditions, true) : array();
                    $collected_conditions_all = array();
                    foreach($conditions as $condition_id => $condition_value){
                        $collected_questions  = array();
                        $collected_conditions = array();
                        $condition_questions  = isset($condition_value['condition_question_add']) && !empty($condition_value['condition_question_add']) ? $condition_value['condition_question_add'] : array();
                        foreach($condition_questions as $cond_question_id => $cond_question_value){
                            $cond_question_id      = isset($cond_question_value['question_id']) && $cond_question_value['question_id'] != "" ? $cond_question_value['question_id'] : "";  
                            $cond_question_type    = isset($cond_question_value['type']) && $cond_question_value['type'] != "" ? $cond_question_value['type'] : "";  
                            $cond_question_answer  = isset($cond_question_value['answer']) && $cond_question_value['answer'] != "" ? $cond_question_value['answer'] : "";  
                            $cond_plus_condition   = isset($cond_question_value['plus_condition']) && $cond_question_value['plus_condition'] != "" ? $cond_question_value['plus_condition'] : "";  
                            
                            if($cond_plus_condition == 'and'){
                                $cond_plus_condition_ready = "&&";
                            }elseif($cond_plus_condition == 'or'){
                                $cond_plus_condition_ready = "||";
                            }else{
                                $cond_plus_condition_ready = "";
                            }
                            if(array_key_exists($cond_question_id , $answered_questions)){
                                $if_condition = false;
                                if(isset($answered_questions[$cond_question_id])){
                                    if(is_array($answered_questions[$cond_question_id])){
                                        $cond_question_equality = isset($cond_question_value['equality']) && $cond_question_value['equality'] != "" ? $cond_question_value['equality'] : "==";
                                        if(isset($answered_questions[$cond_question_id]['answer'])){
                                            if(is_array($answered_questions[$cond_question_id]['answer'])){
                                                if(in_array($cond_question_answer,$answered_questions[$cond_question_id]['answer'])){
                                                    $if_condition = true;
                                                }
                                            }else{
                                                if($cond_question_type == 'number' || $cond_question_type == 'phone'){
                                                    if( $answered_questions[$cond_question_id]['answer'] != '' ){
                                                        $if_condition = $this->ays_survey_check_equality(intval($cond_question_answer), intval($answered_questions[$cond_question_id]['answer']), $cond_question_equality);
                                                    }
                                                }else{
                                                    $if_condition = $this->ays_survey_check_equality($cond_question_answer, $answered_questions[$cond_question_id]['answer'], $cond_question_equality);
                                                }
                                            }
                                        }
                                    }else{
                                        $if_condition = $this->ays_survey_check_equality($cond_question_answer, $answered_questions[$cond_question_id]);
                                    }
                                }
                                if($if_condition){
                                    $collected_questions[] = 1;
                                }else{
                                    $collected_questions[] = 0;
                                }
                                $collected_questions[] = $cond_plus_condition_ready;
                            }
                            else{
                                $collected_questions[] = 0;
                                $collected_questions[] = $cond_plus_condition_ready;
                            }
                        }
                        $if_condition_str   = implode("" , $collected_questions);
                        $if_condition_ready = 0;
                        try{
                            $if_condition_ready = eval("return $if_condition_str;");
                        }catch(Throwable $e){
                            var_dump( "Captured Throwable: " . $e->getMessage());
                        }

                        if($if_condition_ready == 1){
                            $if_condition_ready = true;
                        }elseif($if_condition_ready == 0){
                            $if_condition_ready = false;
                        }
                        $collected_conditions_all[$condition_id] = $if_condition_ready;
                    }

                    $true_conditions_count = false;
                    if(array_sum($collected_conditions_all) > 1){
                        $true_conditions_count = array_sum($collected_conditions_all);
                    }
                    $cond_page_message    = $survey_condition_show_all_results ? array() : '';
                    $cond_email_message   = "";
                    $cond_email_file      = "";
                    $cond_redirect_delay  = "";
                    $cond_redirect_url    = "";
                    $cond_timer_countdown = "";
                    $cond_email_file_id   = "";
                    $conditions_data      = array();
                    foreach($collected_conditions_all as $cond_all_key => $cond_all_value){
                        if($cond_all_value){
                            if(array_key_exists($cond_all_key , $conditions)){
                                if(isset($conditions[$cond_all_key]['messages'])){
                                    if(isset($conditions[$cond_all_key]['messages']['page'])){
                                        if(is_array($cond_page_message)){
                                            $cond_page_message[] = isset($conditions[$cond_all_key]['messages']['page']['message']) && $conditions[$cond_all_key]['messages']['page']['message'] != "" ? $conditions[$cond_all_key]['messages']['page']['message'] : "";
                                        }
                                        else{
                                            $cond_page_message = isset($conditions[$cond_all_key]['messages']['page']['message']) && $conditions[$cond_all_key]['messages']['page']['message'] != "" ? $conditions[$cond_all_key]['messages']['page']['message'] : "";
                                        }
                                    }
                                    if(isset($conditions[$cond_all_key]['messages']['email'])){
                                        $cond_email_message = isset($conditions[$cond_all_key]['messages']['email']['message']) && $conditions[$cond_all_key]['messages']['email']['message'] != "" ? $conditions[$cond_all_key]['messages']['email']['message'] : "";
                                        $cond_email_file    = isset($conditions[$cond_all_key]['messages']['email']['file']) && $conditions[$cond_all_key]['messages']['email']['file'] != "" ? $conditions[$cond_all_key]['messages']['email']['file'] : "";
                                        $cond_email_file_id = isset($conditions[$cond_all_key]['messages']['email']['file_id']) && $conditions[$cond_all_key]['messages']['email']['file_id'] != "" ? $conditions[$cond_all_key]['messages']['email']['file_id'] : "";
                                    }
                                    if(isset($conditions[$cond_all_key]['messages']['redirect'])){
                                        $cond_redirect_delay  = isset($conditions[$cond_all_key]['messages']['redirect']['delay']) && $conditions[$cond_all_key]['messages']['redirect']['delay'] != "" ? $conditions[$cond_all_key]['messages']['redirect']['delay'] : "";
                                        $cond_redirect_url    = isset($conditions[$cond_all_key]['messages']['redirect']['url']) && $conditions[$cond_all_key]['messages']['redirect']['url'] != "" ? $conditions[$cond_all_key]['messages']['redirect']['url'] : "";
                                        $cond_timer_countdown = Survey_Maker_Data::secondsToWords( intval( $cond_redirect_delay ) );
                                    }
                                }
                            }
                        }
                    }
                // = = = = = = = = = = = = = = = = = = = = = = = = = = =
                // = = = = = = = =  Conditions end   = = = = = = = = = = 
                // = = = = = = = = = = = = = = = = = = = = = = = = = = =

                if( isset($_REQUEST['ays_survey_update_submission_'.$survey_id.'']) && $_REQUEST['ays_survey_update_submission_'.$survey_id.''] != '' ){
                    $response_data = json_decode( base64_decode($_REQUEST['ays_survey_update_submission_'.$survey_id.'']), true );

                    $updated_submission_id = isset($response_data['submissionId']) && $response_data['submissionId'] != '' ? $response_data['submissionId'] : '';

                    $result = $this->update_submission_in_db( $send_data , $response_data);
                    $submition_q_last_id = intval($updated_submission_id);
                }
                else{
                    $result = $this->add_results_to_db( $send_data );
                    $submition_q_last_id = $wpdb->insert_id;
                }

                $survey->result_last_id = $submition_q_last_id;
                $survey->questions_all_data = $send_data;

                $nsite_url_base = parse_url( get_site_url(), PHP_URL_HOST );
                $nsite_url = trim( $nsite_url_base, '/' );
                
                //$nsite_url = "levon.com";
                $nno_reply = "noreply@".$nsite_url;
                $ays_send_mail = null;
                $ays_send_mail_to_admin = null;

                $message = $options[ $valid_name_prefix . 'mail_message' ];

                if( $options[ $valid_name_prefix . 'email_configuration_from_name' ] ) {
                    $uname = stripslashes( $options[ $valid_name_prefix . 'email_configuration_from_name' ] );
                } else {
                    $uname = 'Social Survey';
                }
                
                if( !empty( $options[ $valid_name_prefix . 'email_configuration_from_email' ] ) && filter_var( $options[ $valid_name_prefix . 'email_configuration_from_email' ], FILTER_VALIDATE_EMAIL ) ) {
                    $nfrom = "From: " . $uname . " <".stripslashes( $options[ $valid_name_prefix . 'email_configuration_from_email' ] ).">";
                }else{
                    $nfrom = "From: " . $uname . " <survey_maker@".$nsite_url.">";
                }

                if( $options[ $valid_name_prefix . 'email_configuration_from_subject' ] ) {
                    $subject = stripslashes( $options[ $valid_name_prefix . 'email_configuration_from_subject' ] );
                } else {
                    $subject = stripslashes( $survey->title );
                }
                
                if( $options[ $valid_name_prefix . 'email_configuration_replyto_name' ] ) {
                    $replyto_name = stripslashes( $options[ $valid_name_prefix . 'email_configuration_replyto_name' ] );
                } else {
                    $replyto_name = '';
                }

                $nreply = "";
                if( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ] ) {
                    if( !empty( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ] ) && filter_var( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ], FILTER_VALIDATE_EMAIL ) ){
                        $nreply = "Reply-To: " . $replyto_name . " <" . stripslashes( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ] ) . ">";
                    }
                }

                // Conditions Email
                $survey_send_cond_email = false;
                if($cond_email_file || $cond_email_message || $cond_email_file_id){
                    $cond_user_email = $user_email == "" ? $wp_user->data->user_email : $user_email;
                    $cond_user_name =  $user_name  == "" ? $wp_user->data->display_name : $user_name;
                    $cond_subject   =  $subject;
                    $cond_to = $cond_user_name . " <$cond_user_email>";

                    $cond_headers = $nfrom."\r\n";
                    if($nreply != ""){
                        $cond_headers .= $nreply."\r\n";
                    }
                    $cond_headers .= "MIME-Version: 1.0\r\n";
                    $cond_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                    $cond_email_message = Survey_Maker_Data::replace_message_variables($cond_email_message, $message_data);
                    $cond_email_message = Survey_Maker_Data::ays_autoembed( $cond_email_message );

                    $attachment_path = false;
                    $attachment_array = array();
                    if($cond_email_file_id){
                        $attachment_path = get_attached_file(intval($cond_email_file_id));
                    }
                    if($attachment_path){
                        $attachment_array = array($attachment_path);
                    }

                    if(!empty($attachment_array) && $cond_email_message == ""){
                        $cond_email_message = ".";
                    }
                    
                    $survey_send_cond_email = (wp_mail($cond_to, $cond_subject, $cond_email_message, $cond_headers, $attachment_array)) ? true : false;
                }

                $hide_results_questions_ids = array();
                foreach ($questions_data as $question_extra_id => $question_extra_data) {
                    if (isset($question_extra_data['questionHideResults']) && $question_extra_data['questionHideResults'] == '1') {
                        $hide_results_questions_ids[] = $question_extra_id;
                    }
                }
                
                $hide_results_questions_ids_comas = implode(',' , $hide_results_questions_ids);
                
                $question_hide_results_attr = '';
                if( $hide_results_questions_ids_comas != '' ){
                    $question_hide_results_attr = 'hide_questions_ids="'.$hide_results_questions_ids_comas.'"';
                }
                else{
                    $hide_results_questions_ids_comas = null;
                }
                
                if ( $options[ $valid_name_prefix . 'enable_mail_user' ] ) {
                    if ( !empty( $user_email ) && filter_var( $user_email, FILTER_VALIDATE_EMAIL ) ) {
                        // Send email to user | Custom | SendGrid
                        switch ( $options[ $valid_name_prefix . 'send_mail_type' ] ) {
                            case 'custom':
                                $message = $options[ $valid_name_prefix . 'mail_message' ];
                                $message = Survey_Maker_Data::replace_message_variables($message, $message_data);

                                $message = Survey_Maker_Data::ays_autoembed( $message );

                                if ( $options[ $valid_name_prefix . 'summary_single_email_to_users' ] ) {
                                    if($user_id > 0 && $user_id != "") {
                                        $message .= $this->ays_create_single_user_submission_report( $survey, $options, $send_data, $hide_results_questions_ids_comas );
                                    }
                                    else {
                                        $message .= $this->ays_create_submission_report( $survey, $options, $send_data );
                                    }
                                }

                                
                                $to = $user_name . " <$user_email>";

                                $headers = $nfrom."\r\n";

                                if($nreply != ""){
                                    $headers .= $nreply."\r\n";
                                }

                                $headers .= "MIME-Version: 1.0\r\n";
                                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                $attachment = array();
                                
                                $ays_send_mail = (wp_mail($to, $subject, $message, $headers, $attachment)) ? true : false;
                                break;
                            case 'sendgrid':

                                // SendGrid email from
                                if( !empty( $options[ $valid_name_prefix . 'email_configuration_from_email' ] ) && filter_var( $options[ $valid_name_prefix . 'email_configuration_from_email' ], FILTER_VALIDATE_EMAIL ) ) {
                                    $options[ $valid_name_prefix . 'sendgrid_email_from' ] = stripslashes( $options[ $valid_name_prefix . 'email_configuration_from_email' ] );
                                }else{
                                    $options[ $valid_name_prefix . 'sendgrid_email_from' ] = "surveyMaker@gmail.com";
                                }

                                // SendGrid Reply to email
                                if( !empty( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ] ) && filter_var( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ], FILTER_VALIDATE_EMAIL ) ) {
                                    $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_email' ] = stripslashes( $options[ $valid_name_prefix . 'email_configuration_replyto_email' ] );
                                }else{
                                    $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_email' ] = "surveyMakerReplyTo@gmail.com";
                                }

                                // SendGrid email name
                                $options[ $valid_name_prefix . 'sendgrid_email_name' ] = $uname;

                                // SendGrid Reply to name
                                $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_name' ] = $replyto_name;

                                // SendGrid email subject
                                $options[ $valid_name_prefix . 'sendgrid_email_subject' ] = $subject;

                                break;
                            default:
                                break;
                        }
                    }
                }
                
                if ( $options[ $valid_name_prefix . 'enable_mail_admin' ] ) {
                    $admin_email = get_option( 'admin_email' );

                    if ( filter_var( $admin_email, FILTER_VALIDATE_EMAIL ) ) {

                        $message_content = '';
                        $message_content = $options[ $valid_name_prefix . 'mail_message_admin' ];
                        $message_content = Survey_Maker_Data::replace_message_variables($message_content, $message_data);
                        $message_content = Survey_Maker_Data::ays_autoembed( $message_content );

                        if ( $options[ $valid_name_prefix . 'send_submission_report' ] ) {
                            $message_content .= $this->ays_create_submission_report( $survey, $options, $send_data );
                        }
                        
                        if ( $options[ $valid_name_prefix . 'send_mail_to_site_admin' ] ) {
                            $email = "<$admin_email>";
                        }else{
                            $email = "";
                        }
                        
                        $add_emails_array = array();
                        if( $options[ $valid_name_prefix . 'send_mail_to_site_admin' ] ){
                            $add_emails_array[] = $email;
                        }

                        $add_emails = "";
                        if( $options[ $valid_name_prefix . 'additional_emails' ] != "" ) {

                            $additional_emails = explode(", ", $options[ $valid_name_prefix . 'additional_emails' ]);

                            foreach($additional_emails as $key => $additional_email){
                                $add_emails_array[] = "<$additional_email>";
                            }
                        }
                        $add_emails = implode( ', ', $add_emails_array );
                        
                        $to = $add_emails;
                        $subject = sprintf( __( "Someone has passed your %s survey", $this->plugin_name ), '"' . stripslashes( $survey->title ) . '"' );
                        $headers = $nfrom."\r\n";

                        if($nreply != ""){
                            $headers .= $nreply."\r\n";
                        }
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                        $attachment = array();

                        $ays_send_mail_to_admin = (wp_mail($to, $subject, $message_content, $headers, $attachment)) ? true : false;
                    }
                }

                $options = array_merge( (array) $survey, $options );
                $integrations_args = apply_filters( 'ays_sm_front_end_integrations_options', array(), $options );
                do_action( 'ays_sm_front_end_integrations', $integrations_args, $options, $send_data );

                $thank_you_message = Survey_Maker_Data::replace_message_variables($thank_you_message, $message_data);

                $thank_you_message = Survey_Maker_Data::ays_autoembed( $thank_you_message );

                if(is_array($cond_page_message)){
                    if(!empty($cond_page_message)){
                        foreach($cond_page_message as $cond_page_message_key => &$cond_page_message_value){
                            $cond_page_message_value = Survey_Maker_Data::replace_message_variables( $cond_page_message_value, $message_data );
                            $cond_page_message_value = Survey_Maker_Data::ays_autoembed( $cond_page_message_value );
                        }
                    }
                }
                else{
                    if($cond_page_message != ""){
                        $cond_page_message = Survey_Maker_Data::replace_message_variables( $cond_page_message, $message_data );
                        $cond_page_message = Survey_Maker_Data::ays_autoembed( $cond_page_message );
                    }
                }

                $conditions_data = array(
                    "pageMessage"         => $cond_page_message,
                    "emailMessage"        => $cond_email_message,
                    "redirectDelay"       => $cond_redirect_delay,
                    "redirectUrl"         => $cond_redirect_url,
                    "redirectCountDown"   => $cond_timer_countdown,
                    "trueConditionsCount" => $true_conditions_count,

                );

                if ( $options[ $valid_name_prefix . 'show_summary_after_submission' ] ) {
                    if( $options[ $valid_name_prefix . 'show_submission_results' ] == 'summary' ){
                        $survey_show_current_user_results = "";
                        $res_type = '';
                        if( $options[ $valid_name_prefix . 'show_current_user_results' ] ){
                            $survey_show_current_user_results = "user-flag='true'";
                            $res_type = "restype=public";
                        }
    
                        $thank_you_message .= '<div class="' . $this->html_class_prefix . 'thank-you-summary-submission-main-container">';
                            $thank_you_message .= do_shortcode( '[ays_survey_submissions_summary id="'. $survey_id .'" '.$survey_show_current_user_results.' '.$res_type.' '.$question_hide_results_attr.']' );
                        $thank_you_message .= '</div>';
                    }else if( $options[ $valid_name_prefix . 'show_submission_results' ] == 'individual' ){
                        $thank_you_message .= '<div class="' . $this->html_class_prefix . 'thank-you-summary-submission-main-container">';
                            $survey_theme = $options['options'][ $valid_name_prefix . 'theme'];
                            $thank_you_message .= $this->create_individual_submission_html( $survey_id , $survey_theme, $hide_results_questions_ids_comas);
                        $thank_you_message .= '</div>';
                    }
                }
                $thank_you_message = Survey_Maker_Data::ays_survey_translate_content($thank_you_message);
            	return array(
                    'status' => $result,
                    "message" => $thank_you_message,
                    "unique_code" => $result_unique_code,
                    "conditionData" => $conditions_data,
                    "mailToUser" => $ays_send_mail,
                    "mailToAdmin" => $ays_send_mail_to_admin,
                    "mailCondition" => $survey_send_cond_email,
                    "limited" => $check_limitations,
                );
            }
        }
        return array("status" => false, "message" => "No no no" );
    }

    protected function add_results_to_db( $data ){
        global $wpdb;

        $questions_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";
        $answers_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";
        $submissions_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions";
        $submissions_questions_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions_questions";

        $survey = $data['survey'];
        $questions_ids = $data['questions_ids'];
        $user_id = $data['user_id'];
        $user_ip = $data['user_ip'];
        $user_name = $data['user_name'];
        $user_email = $data['user_email'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $answered_questions = $data['answered_questions'];
        $questions_data = $data['questions_data'];
        $duration = strtotime( $end_date ) - strtotime( $start_date );
        $unique_code = $data['unique_code'];
        $user_explanation = $data['user_explanation'];
        $detectedDevice = $data['detectedDevice'];
        $chat_mode = $data['chat_mode'];
        $user_password = $data['user_password'];
        $post_id = $data['post_id'];

        $question_ids_array = $questions_ids != '' ? explode(',', $questions_ids) : array();
        $questions_count = count( $question_ids_array );

        
        $country = '';
        $city = '';
        if ($user_ip != '' && $user_ip != '::1') {
            $json = json_decode(file_get_contents("http://ip-api.com/json/{$user_ip}"));
            if(isset($json->status) && $json->status == 'success'){
                $country = $json->country;
                $city    = $json->city;
            }
        }

        $options = array(
            'device' => $detectedDevice,
        );

        $matrix_types = array(
            "matrix_scale",
            "matrix_scale_checkbox",
            "star_list",
            "slider_list"
        );

        $results_submissions = $wpdb->insert(
            $submissions_table,
            array(
                'survey_id' => absint( intval( $survey->id ) ),
                'questions_ids' => $questions_ids,
                'user_id' => $user_id,
                'user_ip' => $user_ip,
                'user_name' => $user_name,
                'user_email' => $user_email,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'submission_date' => $end_date,
                'country' => $country,
                'city' => $city,
                'duration' => $duration,
                'questions_count' => $questions_count,
                'unique_code' => $unique_code,
                'options' => json_encode($options),
                'password' => $user_password,
                'post_id' => $post_id
            ),
            array(
                '%d', // survey_id
                '%s', // questions_ids
                '%d', // user_id
                '%s', // user_ip
                '%s', // user_name
                '%s', // user_email
                '%s', // start_date
                '%s', // end_date
                '%s', // submission_date
                '%s', // country
                '%s', // city
                '%s', // duration
                '%s', // questions_count
                '%s', // unique_code
                '%s', // options
                '%s', // password
                '%d', // post_id
            )
        );

        $submission_id = $wpdb->insert_id;
        $results_submissions_questions = 0;
        
        foreach ($question_ids_array as $key => $qid) {

            if(isset($qid) && $qid != ''){
                $matrix_box = array();
                $questions_options = array(

                );

                $user_answer = '';
                $user_variant = '';
                // $section_id = $questions_data[$qid]['section'];
                $current_question_user_exp = '';

                if ($chat_mode) {
                    $chat_mode_question_ids = array();
                    foreach ($questions_data as $k => $v) {
                        $chat_mode_question_ids[] = $v['questionId'];
                    }
                    
                    if( !in_array( $qid, $chat_mode_question_ids ) ){
                        continue;
                    }
                    $section_id = $questions_data[$qid]['section'];
                }else{
                    $section_id = $questions_data[$qid]['section'];
                }

                $question_answer = '';
                if( isset( $answered_questions[$qid] ) ){
                    if( isset( $answered_questions[$qid]['other'] ) ){
                        $user_variant = $answered_questions[$qid]['other'];
                        unset( $answered_questions[$qid]['other'] );
                    }

                    if( is_array( $answered_questions[$qid] ) ){
                        if( isset( $answered_questions[$qid]['answer'] ) && !empty( $answered_questions[$qid]['answer'] ) ){
                            $question_answer = $answered_questions[$qid]['answer'];
                        }else{
                            $question_answer = '';
                        }
                    }else{
                        $question_answer = $answered_questions[$qid];
                    }
                }
                $answer_id = $question_answer;

                if(isset( $user_explanation[$qid] )){
                    $current_question_user_exp = isset( $user_explanation[$qid]['user_explanation'] ) && $user_explanation[$qid]['user_explanation'] ? stripslashes( $user_explanation[$qid]['user_explanation'] ) : "";                
                }

                $question_type = (isset($questions_data[$qid]['questionType']) && $questions_data[$qid]['questionType'] != '') ? stripslashes( sanitize_text_field( $questions_data[$qid]['questionType'] ) ) : 'radio';

                switch ( $question_type ) {
                    case "radio":
                        $user_answer = '';
                        if($question_answer != ""){
                            $user_variant = '';
                        }
                        break;
                    case "checkbox":
                        if( is_array( $question_answer ) ){
                            if( !in_array( '0', $question_answer ) ){
                                $user_variant = '';
                            }else{
                                if( $user_variant == '' && array_key_exists( '0', $question_answer ) ){
                                    unset( $question_answer['0'] );
                                }
                            }
                            $user_answer = implode(',', $question_answer);
                        }else{
                            $user_answer = $question_answer;
                            if( '0' != $question_answer ){
                                $user_variant = '';
                            }else{
                                if( $user_variant == '' ){
                                    $user_answer = '';
                                }
                            }
                        }
                        $answer_id = 0;
                        break;    
                    case "select":
                        $user_answer = '';
                        break;
                    case "text":
                    case "short_text":
                    case "number":
                    case "phone":
                    case "name":
                    case "email":
                    case "linear_scale":
                    case "star":
                    case "matrix_scale":
                    case "star_list":
                    case "slider_list":
                    case "matrix_scale_checkbox":
                        $matrix_box = isset($answer_id) && is_array($answer_id) ? $answer_id : array();
                    case "date":
                    case "time":
                    case "hidden":
                        $user_answer = $question_answer;
                        $answer_id = 0;
                        break;
                    case "date_time":
                        if(is_array($question_answer) && ($question_answer['date'] != '' || $question_answer['time'] != '')){
                            $question_answer['date'] = $question_answer['date'] != '' ? $question_answer['date'] : '-';
                            $question_answer['time'] = $question_answer['time'] != '' ? $question_answer['time'] : '-';
                            $user_answer = implode(" " , $question_answer);
                        }
                        else{
                            $user_answer = $question_answer;
                        }
                        $answer_id = 0;
                        break;
                    case "range":
                    case "upload":
                        $user_answer = $question_answer != "" ? $question_answer : '';
                        $answer_id = 0;
                        break;
                    default:
                        $user_answer = '';
                        break;
                }
                
                if( in_array( $question_type, $matrix_types ) ){
                    foreach( $matrix_box as $x => $y ){
                        if($question_type == "matrix_scale_checkbox"){
                            $y = implode("," , $y);
                        }
                        $results_submissions_quests = $wpdb->insert(
                            $submissions_questions_table,
                            array(
                                'submission_id'    => intval( $submission_id ),
                                'question_id'      => intval( $qid ),
                                'section_id'       => intval( $section_id ),
                                'survey_id'        => intval( $survey->id ),
                                'user_id'          => $user_id,
                                'answer_id'        => intval( $x ),
                                'user_answer'      => $y,
                                'user_variant'     => $user_variant,
                                'user_explanation' => $current_question_user_exp,
                                'type'    => $question_type,
                                'options' => json_encode( $questions_options ),
                            ),
                            array(
                                '%d', // submission_id
                                '%d', // question_id
                                '%d', // section_id
                                '%d', // survey_id
                                '%d', // user_id
                                '%d', // answer_id
                                '%s', // user_answer
                                '%s', // user_variant
                                '%s', // user_explanation
                                '%s', // type
                                '%s', // options
                            )
                        );
                    }
                }else{
                    $results_submissions_quests = $wpdb->insert(
                        $submissions_questions_table,
                        array(
                            'submission_id' => intval( $submission_id ),
                            'question_id' => intval( $qid ),
                            'section_id' => intval( $section_id ),
                            'survey_id' => intval( $survey->id ),
                            'user_id' => $user_id,
                            'answer_id' => intval( $answer_id ),
                            'user_answer' => $user_answer,
                            'user_variant' => $user_variant,
                            'user_explanation' => $current_question_user_exp,
                            'type' => $question_type,
                            'options' => json_encode( $questions_options ),
                        ),
                        array(
                            '%d', // submission_id
                            '%d', // question_id
                            '%d', // section_id
                            '%d', // survey_id
                            '%d', // user_id
                            '%d', // answer_id
                            '%s', // user_answer
                            '%s', // user_variant
                            '%s', // user_explanation
                            '%s', // type
                            '%s', // options
                        )
                    );
                }

                // $results_submissions_quests = $wpdb->insert(
                //     $submissions_questions_table,
                //     array(
                //         'submission_id' => intval( $submission_id ),
                //         'question_id' => intval( $qid ),
                //         'section_id' => intval( $section_id ),
                //         'survey_id' => intval( $survey->id ),
                //         'user_id' => $user_id,
                //         'answer_id' => intval( $answer_id ),
                //         'user_answer' => $user_answer,
                //         'user_variant' => $user_variant,
                //         'user_explanation' => '',
                //         'type' => $question_type,
                //         'options' => json_encode( $questions_options )
                //     ),
                //     array(
                //         '%d', // submission_id
                //         '%d', // question_id
                //         '%d', // section_id
                //         '%d', // survey_id
                //         '%d', // user_id
                //         '%d', // answer_id
                //         '%s', // user_answer
                //         '%s', // user_variant
                //         '%s', // user_explanation
                //         '%s', // type
                //         '%s', // options
                //     )
                // );
            }
        }


        if ($results_submissions >= 0) {
            return true;
        }

        return false;
    }

    protected function update_submission_in_db( $data, $update_data ){
        global $wpdb;

        $submissions_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions";
        $submissions_questions_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions_questions";
        
        $submission_id = isset($update_data['submissionId']) && $update_data['submissionId'] != '' ? intval($update_data['submissionId']) : '';
        $survey_id     = isset($update_data['surveyId']) && $update_data['surveyId'] != '' ? intval($update_data['surveyId']) : '';
        $user_id       = isset($update_data['userId']) && $update_data['userId'] != '' ? intval($update_data['userId']) : '';

        $user_name  = isset($data['user_name']) && $data['user_name'] != '' ? $data['user_name'] : '';
        $user_email = isset($data['user_email']) && $data['user_email'] != '' ? $data['user_email'] : '';
        
        $insert_submissions_questions = false;
        $update_submissions_questions = false;
        $update_submissions_questions_sipmle = false;

        $survey = $data['survey'];
        $questions_ids = $data['questions_ids'];
        $answered_questions = $data['answered_questions'];
        $questions_data = $data['questions_data'];
        $user_explanation = $data['user_explanation'];
        $chat_mode = $data['chat_mode'];
        $question_ids_array = $questions_ids != '' ? explode(',', $questions_ids) : array();
        
        $questions_count = count( $question_ids_array );

        $matrix_types = array(
            "matrix_scale",
            "matrix_scale_checkbox",
            "star_list",
            "slider_list"
        );

        $sumbission_changed_count = Survey_Maker_Data::get_sum_of_changed_submissions($submission_id);
        $sumbission_changed_count++;

        $update_submissions_table = $wpdb->update(
            $submissions_table,
            array(
                'changed'    => $sumbission_changed_count,
                'user_name'    => $user_name,
                'user_email'    => $user_email,
            ),
            array(
                'id' => $submission_id,
                'user_id' => $user_id,
                'survey_id' => $survey_id,
            ),
            array('%d' , '%s' , '%s'),
            array('%d','%d','%d')
        );
       
        $results_submissions_questions = 0;
        
        foreach ($question_ids_array as $key => $qid) {

            if(isset($qid) && $qid != ''){
                $matrix_box = array();
                $questions_options = array(

                );

                $user_answer = '';
                $user_variant = '';
                $current_question_user_exp = '';

                if ($chat_mode) {
                    $chat_mode_question_ids = array();
                    foreach ($questions_data as $k => $v) {
                        $chat_mode_question_ids[] = $v['questionId'];
                    }
                    
                    if( !in_array( $qid, $chat_mode_question_ids ) ){
                        continue;
                    }
                    $section_id = $questions_data[$qid]['section'];
                }else{
                    $section_id = $questions_data[$qid]['section'];
                }

                $question_answer = '';
                if( isset( $answered_questions[$qid] ) ){
                    if( isset( $answered_questions[$qid]['other'] ) ){
                        $user_variant = $answered_questions[$qid]['other'];
                        unset( $answered_questions[$qid]['other'] );
                    }

                    if( is_array( $answered_questions[$qid] ) ){
                        if( isset( $answered_questions[$qid]['answer'] ) && !empty( $answered_questions[$qid]['answer'] ) ){
                            $question_answer = $answered_questions[$qid]['answer'];
                        }else{
                            $question_answer = '';
                        }
                    }else{
                        $question_answer = $answered_questions[$qid];
                    }
                }
                $answer_id = $question_answer;

                if(isset( $user_explanation[$qid] )){
                    $current_question_user_exp = isset( $user_explanation[$qid]['user_explanation'] ) && $user_explanation[$qid]['user_explanation'] ? stripslashes( $user_explanation[$qid]['user_explanation'] ) : "";                
                }

                $question_type = (isset($questions_data[$qid]['questionType']) && $questions_data[$qid]['questionType'] != '') ? stripslashes( sanitize_text_field( $questions_data[$qid]['questionType'] ) ) : 'radio';

                switch ( $question_type ) {
                    case "radio":
                        $user_answer = '';
                        if($question_answer != ""){
                            $user_variant = '';
                        }
                        break;
                    case "checkbox":
                        if( is_array( $question_answer ) ){
                            if( !in_array( '0', $question_answer ) ){
                                $user_variant = '';
                            }else{
                                if( $user_variant == '' && array_key_exists( '0', $question_answer ) ){
                                    unset( $question_answer['0'] );
                                }
                            }
                            $user_answer = implode(',', $question_answer);
                        }else{
                            $user_answer = $question_answer;
                            if( '0' != $question_answer ){
                                $user_variant = '';
                            }else{
                                if( $user_variant == '' ){
                                    $user_answer = '';
                                }
                            }
                        }
                        $answer_id = 0;
                        break;    
                    case "select":
                        $user_answer = '';
                        break;
                    case "text":
                    case "short_text":
                    case "number":
                    case "phone":
                    case "name":
                    case "email":
                    case "linear_scale":
                    case "star":
                    case "matrix_scale":
                    case "star_list":
                    case "slider_list":
                    case "matrix_scale_checkbox":
                        $matrix_box = isset($answer_id) && is_array($answer_id) ? $answer_id : array();
                    case "date":
                    case "time":
                    case "hidden":
                        $user_answer = $question_answer;
                        $answer_id = 0;
                        break;
                    case "date_time":
                        if(is_array($question_answer) && ($question_answer['date'] != '' || $question_answer['time'] != '')){
                            $question_answer['date'] = $question_answer['date'] != '' ? $question_answer['date'] : '-';
                            $question_answer['time'] = $question_answer['time'] != '' ? $question_answer['time'] : '-';
                            $user_answer = implode(" " , $question_answer);
                        }
                        else{
                            $user_answer = $question_answer;
                        }
                        $answer_id = 0;
                        break;
                    case "range":
                    case "upload":
                        $user_answer = $question_answer != "" ? $question_answer : '';
                        $answer_id = 0;
                        break;
                    default:
                        $user_answer = '';
                        break;
                }
                
                if( in_array( $question_type, $matrix_types ) ){
                    $subm_quest_ids_matrix_types = isset($update_data['subm_quest_ids_matrix_types']) && $update_data['subm_quest_ids_matrix_types'] != '' ? $update_data['subm_quest_ids_matrix_types'] : '';

                    $counter = 0;
                    foreach( $matrix_box as $x => $y ){
                        if($question_type == "matrix_scale_checkbox"){
                            $y = implode("," , $y);
                        }
                        $subm_quest_id = isset($subm_quest_ids_matrix_types[$qid]['submission_questions_ids'][$counter]) ? intval($subm_quest_ids_matrix_types[$qid]['submission_questions_ids'][$counter]) : '';
                        if($subm_quest_id){
                            $update_submissions_questions = $wpdb->update(
                                $submissions_questions_table,
                                array(
                                    'answer_id' => intval( $x ),
                                    'user_answer' => $y,
                                    'user_variant' => $user_variant,
                                    'user_explanation' => $current_question_user_exp,
                                ),
                                array(
                                    'id' => $subm_quest_id,
                                    'submission_id' => $submission_id,
                                    'user_id' => $user_id,
                                    'survey_id' => $survey_id,
                                    'question_id' => $qid,
                                ),
                                array(
                                    '%d', // answer_id
                                    '%s', // user_answer
                                    '%s', // user_variant
                                    '%s', // user_explanation
                                ),
                                array('%d','%d','%d','%d','%d')
                            );
                            $counter++;
                            
                        }
                        else{
                            $insert_submissions_questions = $wpdb->insert(
                            $submissions_questions_table,
                                array(
                                    'submission_id'    => intval( $submission_id ),
                                    'question_id'      => intval( $qid ),
                                    'section_id'       => intval( $section_id ),
                                    'survey_id'        => intval( $survey->id ),
                                    'user_id'          => $user_id,
                                    'answer_id'        => intval( $x ),
                                    'user_answer'      => $y,
                                    'user_variant'     => $user_variant,
                                    'user_explanation' => $current_question_user_exp,
                                    'type'    => $question_type,
                                    'options' => json_encode( $questions_options )
                                ),
                                array(
                                    '%d', // submission_id
                                    '%d', // question_id
                                    '%d', // section_id
                                    '%d', // survey_id
                                    '%d', // user_id
                                    '%d', // answer_id
                                    '%s', // user_answer
                                    '%s', // user_variant
                                    '%s', // user_explanation
                                    '%s', // type
                                    '%s', // options
                                )
                            );
                            
                        }                        
                    }
                }else{
                    
                    $update_submissions_questions_sipmle = $wpdb->update(
                        $submissions_questions_table,
                        array(
                            'answer_id' => intval( $answer_id ),
                            'user_answer' => $user_answer,
                            'user_variant' => $user_variant,
                            'user_explanation' => $current_question_user_exp,
                        ),
                        array(
                            'submission_id' => $submission_id,
                            'user_id' => $user_id,
                            'survey_id' => $survey_id,
                            'question_id' => $qid,
                        ),
                        array(
                            '%d', // answer_id
                            '%s', // user_answer
                            '%s', // user_variant
                            '%s', // user_explanation
                            '%s', // type
                        ),
                        array('%d','%d','%d','%d')
                    );
                }
            }
        }
        
        if($update_submissions_table || $update_submissions_questions || $insert_submissions_questions || $update_submissions_questions_sipmle ){
            return true;
        }
        return false;
    }

    public function ays_generate_survey_method( $attr ){
        $id = (isset($attr['id'])) ? absint(intval($attr['id'])) : null;
        
        if (is_null($id)) {
            $content = "<p class='wrong_shortcode_text' style='color:red;'>" . __( 'Wrong shortcode initialized', $this->plugin_name ) . "</p>";
            return $content;
        }
        
        $content = $this->show_survey($id, $attr);
        
        $this->enqueue_styles();
        $this->enqueue_scripts();

        return str_replace( array( "\r\n", "\n", "\r" ), '', $content );
    }

    public function show_survey( $id, $attr ){

    	$survey = Survey_Maker_Data::get_survey_by_id( $id );

        if ( is_null( $survey ) ) {
            return "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
        }

    	$status = isset( $survey->status ) && $survey->status != '' ? $survey->status : '';

        if ( $status != 'published' ) {
            return "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
        }

        $this->buttons_texts = Survey_Maker_Data::ays_set_survey_texts( $this->plugin_name, $this->options );

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;

        /*******************************************************************************************************/

        $settings_options = $this->settings->ays_get_setting('options');
        if($settings_options){
            $settings_options = json_decode( $settings_options, true );
        }else{
            $settings_options = array();
        }
        
        $this->message_variable_data = Survey_Maker_Data::ays_set_survey_message_variables_data( $id, $survey, $settings_options );

        $this->options = Survey_Maker_Data::get_survey_validated_data_from_array( $survey, $attr );

        $is_elementor_exists = Survey_Maker_Data::ays_survey_is_elementor();
        if( $this->options[ $this->name_prefix . 'enable_copy_protection' ] ){
            if( ! $is_elementor_exists ){
                wp_enqueue_script( $this->plugin_name . '-survey-copy-protection', plugin_dir_url(__FILE__) . 'js/survey-copy-protection.min.js', array('jquery'), $this->version, true );
            }
        }

        $user_id = get_current_user_id();

        /*******************************************************************************************************/

        /*
        ==========================================
        General settings
        ==========================================
        */

        // Textarea height (public)
        $this->options[ $this->name_prefix . 'textarea_height' ] = (isset($settings_options['survey_textarea_height']) && $settings_options['survey_textarea_height'] != '' && $settings_options['survey_textarea_height'] != 0) ? absint( sanitize_text_field($settings_options['survey_textarea_height']) ) : 100;

        // Lazy loading for images
        $this->options[ $this->name_prefix . 'lazy_loading_for_images' ] = (isset($settings_options['survey_lazy_loading_for_images']) && $settings_options['survey_lazy_loading_for_images'] == 'on') ? true : false;
        $this->lazy_loading = '';
        if($this->options[ $this->name_prefix . 'lazy_loading_for_images' ]){
            $this->lazy_loading = Survey_Maker_Data::survey_lazy_loading_for_images($this->options[ $this->name_prefix . 'lazy_loading_for_images' ]);
        }

        /*******************************************************************************************************/
        
        
        $options = isset( $survey->options ) && $survey->options != '' ? json_decode( $survey->options, true ) : array();
    	
    	$sections_ids = isset( $survey->section_ids ) && $survey->section_ids != '' ? $survey->section_ids : '';
        $question_ids = isset( $survey->question_ids ) && $survey->question_ids != '' ? $survey->question_ids : '';
    	
    	if( $sections_ids != '' ){
    		$section_ids_array = explode( ',', $sections_ids );
    	}else{
    		$section_ids_array = array();
        }
        
        /*******************************************************************************************************/
        /* Limit users                                                                                         */
        /*******************************************************************************************************/
        $limit = false;
        $limit_message = false;
        if( $this->options[ $this->name_prefix . 'limit_users' ] ){
            
            $max_pass_count = intval( $this->options[ $this->name_prefix . 'max_pass_count' ] );
            $limit_users_attr = array(
                'id' => $id,
                'name' => 'ays_survey_cookie_',
                'title' => $survey->title,
                'max_pass_count' => $max_pass_count,
            );
            switch( $this->options[ $this->name_prefix . 'limit_users_by' ] ){
                case 'ip':
                    $limit_by = Survey_Maker_Data::get_limit_user_by_ip( $id );
                    $remove_cookie = Survey_Maker_Data::ays_survey_remove_cookie( $limit_users_attr );
                    if( $max_pass_count == 0 ){
                        $limit_by = 0;
                    }
                break;
                case 'user_id':
                    $limit_by = Survey_Maker_Data::get_limit_user_by_id( $id, $user_id );
                    $remove_cookie = Survey_Maker_Data::ays_survey_remove_cookie( $limit_users_attr );
                    if( ! is_user_logged_in() ){
                        $limit_by = 0;
                    }
                break;
                case 'cookie':
                    $check_cookie = Survey_Maker_Data::ays_survey_check_cookie( $limit_users_attr );
                    if ( !$check_cookie ) {
                        $limit_by = 0;
                    }else{
                        $limit_by = Survey_Maker_Data::get_limit_cookie_count( $limit_users_attr );
                    }
                break;
                case 'ip_cookie':
                    $check_cookie = Survey_Maker_Data::ays_survey_check_cookie( $limit_users_attr );
                    $check_user_by_ip = Survey_Maker_Data::get_user_by_ip( $id );
                    if($check_cookie || $check_user_by_ip > 0){
                        $limit_by = $check_user_by_ip;
                    }elseif(! $check_cookie || $check_user_by_ip <= 0){
                        $limit_by = 0;
                    }
                break;

            }
            if( $max_pass_count == 0 ){
                $max_pass_count = 1;
            }

            if( $limit_by >= $max_pass_count ){
                $limit = true;
                $limit_message = $this->options[ $this->name_prefix . 'limitation_message' ];
                $limit_message = Survey_Maker_Data::replace_message_variables($this->options[ $this->name_prefix . 'limitation_message' ], $this->message_variable_data);
                
                if( $limit_message == '' ){
                    $limit_message = __( "You've already responded", $this->plugin_name );
                }
            }

        }

        //Limit user by country        
        $enable_limit_user_by_country = $this->options[ $this->name_prefix . 'enable_limit_by_country' ];
        $blocked_user_country = $this->options[ $this->name_prefix . 'limit_country' ];
        $limit_country_message = '';
        $check_limit_by_country = false;
        if($enable_limit_user_by_country){
            $user_ip = Survey_Maker_Data::get_user_ip();
            $json    = json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
            $country = isset($json->country) && $json->country != '' ? $json->country : "";
            if(in_array($country, $blocked_user_country)){
                $limit_country_message = '<p style="text-align:center">'.__( "This survey is not available in your country", $this->plugin_name ).'</p>';
                $check_limit_by_country = true;
            }
        }

        // Block Users by IP addresses 
        $settings_options[ 'survey_block_by_user_ips' ] = (isset($settings_options[ 'survey_block_by_user_ips' ]) &&  $settings_options[ 'survey_block_by_user_ips' ] == 'on') ? stripslashes( $settings_options[ 'survey_block_by_user_ips' ] ): 'off';
        $survey_block_by_user_ips = (isset($settings_options[ 'survey_block_by_user_ips' ]) && $settings_options[ 'survey_block_by_user_ips' ] == 'on') ? true : false;
        // Users IP addresses that will be blocked
        $survey_users_ips_that_will_blocked = ( isset($settings_options[ 'survey_users_ips_that_will_blocked' ]) && $settings_options[ 'survey_users_ips_that_will_blocked' ] != '' ) ? stripslashes( $settings_options[ 'survey_users_ips_that_will_blocked' ] ) : '';

        $check_block_by_ip_addresses = false;

        if($survey_block_by_user_ips && $survey_users_ips_that_will_blocked != '') {
            $survey_users_ips_that_will_blocked_arr = explode(",",$survey_users_ips_that_will_blocked);
            $user_ip = Survey_Maker_Data::get_user_ip();
            if(in_array($user_ip, $survey_users_ips_that_will_blocked_arr)){
                $block_ips_message = '<p style="text-align:center">'.__( "This survey is blocked for your IP address", $this->plugin_name ).'</p>';
                $check_block_by_ip_addresses = true;
            }
        }
        
        $logged_in_limit = false;
        $logged_in_limit_message = false;
        if( $this->options[ $this->name_prefix . 'enable_logged_users' ] ){
            global $wp_roles;
            if( ! is_user_logged_in() ){
                $logged_in_limit = true;
                $logged_in_limit_message = $this->options[ $this->name_prefix . 'logged_in_message' ];

                if( $logged_in_limit_message == '' ){
                    $logged_in_limit_message = "<h4 style='margin-top:0;text-align:center;'>" . __( "Sign in to continue", $this->plugin_name ) . "</h4>";
                    $logged_in_limit_message .= "<p style='margin-top:0;text-align:center;'>" . __( "To fill out this form, you must be signed in. Your identity will remain anonymous.", $this->plugin_name ) . "</p>";
                }
            }else{
                $search_user_flag = false;
                if ( $this->options[ $this->name_prefix . 'enable_for_user' ] ) {
                    $logged_in_limit = true;
                    $current_users = wp_get_current_user();
                    $current_user  = $current_users->data->ID;
                    $search_users_message = $this->options[ $this->name_prefix . 'user_message' ];
                    $search_users = $this->options[ $this->name_prefix . 'user' ];

                    if( $search_users_message == '' ){
                        $search_users_message = __('Access to the survey is restricted.', $this->plugin_name);
                    }

                    $logged_in_limit_message .= '<div>' . $search_users_message . '</div>';

                    if (is_array($search_users)) {
                        if(empty($search_users)){
                            $logged_in_limit = false;
                        }
                        else{
                            if(in_array($current_user, $search_users)){
                                $logged_in_limit = false;
                                $search_user_flag = true;
                            }
                        }
                    }else{
                        if($current_user == $search_users){
                            $logged_in_limit = false;
                            $search_user_flag = true;
                        }
                    }
                }

                if ( $this->options[ $this->name_prefix . 'enable_for_user_role' ] ) {
                    $logged_in_limit = true;
                    $user = wp_get_current_user();
                    $user_roles = $user->roles;
                    $message = $this->options[ $this->name_prefix . 'user_roles_message' ];

                    if( $message == '' ){
                        $message = __('Access to the survey is restricted.', $this->plugin_name);
                    }

                    $user_role = $this->options[ $this->name_prefix . 'user_roles' ];
                    
                    $logged_in_limit_message = '<div>' .  $message . '</div>';
                    if ( is_array( $user_role )) {
                        if(empty($user_role)){
                            $logged_in_limit = false;
                        }
                        else{                        
                            foreach($user_role as $key => $role){
                                if( in_array( $role, $user_roles ) || $search_user_flag ){
                                    $logged_in_limit = false;
                                    break;
                                }
                            }
                        }
                    }else{
                        if( in_array( $user_role, $user_roles ) || $search_user_flag ){
                            $logged_in_limit = false;
                        }
                    }
                }
            }
            
            // Show login form for not logged in users
            $survey_login_form = "";
            if($this->options[ $this->name_prefix . 'show_login_form' ]){
                $ays_survey_login_button_text = $this->buttons_texts[ 'loginButton' ];
                $args = array(
                    'echo' => false,
                    'id_username' => 'user_login',
                    'id_password' => 'user_pass',
                    'id_remember' => 'rememberme',
                    'id_submit'   => 'wp-submit',
                    'label_log_in' => $ays_survey_login_button_text,
                );
                $survey_login_form = "<div class='ays_survey_login_form'>" . wp_login_form( $args ) . "</div>";
            }
            
            if($logged_in_limit){
                if(!is_user_logged_in()){
                    $logged_in_limit_message .= $survey_login_form;
                }
            }
        }

        // Limitation tackers of quiz
        $tackers_message = "<div><p>" . __( "This survey has expired!", $this->plugin_name ) . "</p></div>";
        $takers_count = Survey_Maker_Data::get_survey_takers_count($id);
        
        if($this->options[ $this->name_prefix . 'enable_takers_count' ]){
            if($this->options[ $this->name_prefix . 'takers_count' ] <= $takers_count ){
                $limit = true;
                $limit_message = $tackers_message;
            }
        }

        $survey_loader = $this->options[ $this->name_prefix . 'loader' ];
        $survey_loader_text = '';
        if(isset($this->options['options'])){
            $survey_loader_text = isset($this->options['options'][ $this->name_prefix . 'loader_text' ]) && $this->options['options'][ $this->name_prefix . 'loader_text' ] != "" ? stripslashes(esc_attr($this->options['options'][ $this->name_prefix . 'loader_text' ])) : '';
        }

        // Loader Gif
        $survey_loader_gif = (isset($this->options[ $this->name_prefix . 'loader_gif' ]) && $this->options[ $this->name_prefix . 'loader_gif' ] != '') ? $this->options[ $this->name_prefix . 'loader_gif' ]  : '';
        $survey_loader_gif_width = (isset($this->options[ $this->name_prefix . 'loader_gif_width' ]) && $this->options[ $this->name_prefix . 'loader_gif_width' ] != '') ? stripslashes( $this->options[ $this->name_prefix . 'loader_gif_width' ] )  : '';

        switch( $survey_loader ){
            case 'default':
                $survey_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='ays-loader'><div></div><div></div><div></div><div></div></div>";
                break;
            case 'circle':
                $survey_loader_html = "<div data-class='lds-circle' data-role='loader' class='ays-loader'></div>";
                break;
            case 'dual_ring':
                $survey_loader_html = "<div data-class='lds-dual-ring' data-role='loader' class='ays-loader'></div>";
                break;
            case 'facebook':
                $survey_loader_html = "<div data-class='lds-facebook' data-role='loader' class='ays-loader'><div></div><div></div><div></div></div>";
                break;
            case 'hourglass':
                $survey_loader_html = "<div data-class='lds-hourglass' data-role='loader' class='ays-loader'></div>";
                break;
            case 'ripple':
                $survey_loader_html = "<div data-class='lds-ripple' data-role='loader' class='ays-loader'><div></div><div></div></div>";
                break;
            // case 'text':
            //     if ($quiz_loader_text_value != '') {
            //         $survey_loader_html = "
            //         <div class='ays-loader' data-class='text' data-role='loader'>
            //             <p class='ays-loader-content'>". $quiz_loader_text_value ."</p>
            //         </div>";
            //     }else{
            //         $survey_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='ays-loader'><div></div><div></div><div></div><div></div></div>";
            //     }ays-survey-loader-with-text
            //     break;
            case 'snake':
                $survey_loader_html = '<div class="ays-loader" data-class="ays-survey-loader-snake" data-role="loader"><div></div><div></div><div></div><div></div><div></div><div></div></div>';
            break;
            case 'text':
                $survey_loader_html = '<div class="ays-loader" data-class="ays-survey-loader-text" data-role="loader">'.$survey_loader_text.'</div>';
            break;
            case 'custom_gif':
                $survey_loader_html = '<div class="ays-loader ays-survey-loader-with-custom-gif" data-class="ays-survey-loader-cistom-gif" data-role="loader"><img src="'.$survey_loader_gif.'" '.$this->lazy_loading.' style="width: '.$survey_loader_gif_width.'px;object-fit:cover;"></div>';
            break;
            default:
                $survey_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='ays-loader'><div></div><div></div><div></div><div></div></div>";
            break;
        }

        $this->options[ $this->name_prefix . 'loader_html' ] = $survey_loader_html;

        /*
         * Schedule quiz
         * Check is quiz expired
         */
        
        $is_expired = false;
        $active_date_check = false;
        $startDate_atr = '';
        $endDate_atr = '';
        $current_time = strtotime( current_time( "Y:m:d H:i:s" ) );
		$startDate = strtotime( $this->options[ $this->name_prefix . 'schedule_active' ] );
        $endDate   = strtotime( $this->options[ $this->name_prefix . 'schedule_deactive' ] );
        
		$expired_survey_message = __('The survey has expired.', $this->plugin_name);

        if ( $this->options[ $this->name_prefix . 'enable_schedule' ] ) {
            $active_date_check = true;

            if ( $this->options[ $this->name_prefix . 'schedule_active' ] ) {
                $startDate_atr = $startDate - $current_time;
            }elseif ( $this->options[ $this->name_prefix . 'schedule_deactive' ] ) {
                $endDate_atr = $endDate - $current_time;
            }

            // $show_timer = '';
            // if ($activeDateCheck && $activeActiveDateCheck && $active_date_check) {
            //     if (isset($options['show_schedule_timer']) && $options['show_schedule_timer'] == 'on') {
            //         $show_timer .= "<div class='ays_quiz_show_timer'>";
            //         if ($show_timer_type == 'countdown') {
            //             $show_timer .= '<p id="show_timer_countdown" data-timer_countdown="'.$startDate_atr.'"></p>';
            //         }else if ($show_timer_type == 'enddate') {
            //             $show_timer .= '<p id="show_timer_countdown">' . __('This Quiz will start on', $this->plugin_name);
            //             $show_timer .= ' ' . date_i18n('H:i:s F jS, Y', intval($startDate));
            //             $show_timer .= '</p>';
            //         }
            //         $show_timer .= "</div>";
            //     }
            // }

            if ($startDate > $current_time) {
                if($this->options[ $this->name_prefix . 'dont_show_survey_container' ]){
                    return '';
                }
				$is_expired = true;
                $expired_survey_message = $this->options[ $this->name_prefix . 'schedule_pre_start_message' ];
			}elseif ($endDate < $current_time) {
                if($this->options[ $this->name_prefix . 'dont_show_survey_container' ]){
                    return '';
                }
                $is_expired = true;
                $expired_survey_message = $this->options[ $this->name_prefix . 'schedule_expiration_message' ];
            }
		}

        // Edit previous submission
        $check_subm_edit_and_reg_user = ( $this->options[ $this->name_prefix . 'edit_previous_submission' ] && is_user_logged_in() ) ? true : false;
        if($check_subm_edit_and_reg_user){
            $last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id( $id , $user_id );
            $check_subm_edit_and_reg_user = (!empty($last_submission)) ? true : false;
        }

        if( !$limit || $check_subm_edit_and_reg_user){
            $sections = Survey_Maker_Data::get_sections_by_survey_id( $sections_ids );
            $sections_count = count( $sections );

            $question_types_placeholders = array(
                "radio" => '',
                "checkbox" => '',
                "select" => '',
                "yesorno" => '',
                "text" => __("Your answer", $this->plugin_name),
                "short_text" => __("Your answer", $this->plugin_name),
                "number" => __("Your answer", $this->plugin_name),
                "email" => __("Your email", $this->plugin_name),
                "name" => __("Your name", $this->plugin_name),
            );


            $multiple_sections = $sections_count > 1 ? true : false;

            foreach ($sections as $section_key => $section) {
                $sections[$section_key]['title'] = (isset($section['title']) && $section['title'] != '') ? stripslashes( esc_html( $section['title'] ) ) : '';
                // $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? nl2br( esc_html( $section['description'] ) ) : '';
                if ( $this->options[ $this->name_prefix . 'allow_html_in_section_description' ] ) {
                    $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? nl2br( $section['description'] ) : '';
                } else {
                    $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? nl2br( esc_html( $section['description'] ) ) : '';
                }
                $section_options = isset( $section['options'] ) && $section['options'] != '' ? json_decode( $section['options'], true ) : array();

                $section_questions = Survey_Maker_Data::get_questions_by_section_id( intval( $section['id'] ), $question_ids );

                foreach ($section_questions as $question_key => $question) {
                    $section_questions[$question_key]['question'] = (isset($question['question']) && $question['question'] != '') ? nl2br( $question['question'] ) : '';
                    $section_questions[$question_key]['image'] = (isset($question['image']) && $question['image'] != '') ? $question['image'] : '';
                    $section_questions[$question_key]['type'] = (isset($question['type']) && $question['type'] != '') ? $question['type'] : 'radio';
                    $section_questions[$question_key]['user_variant'] = (isset($question['user_variant']) && $question['user_variant'] == 'on') ? true : false;

                    $opts = json_decode( $question['options'], true );
                    $opts['required'] = (isset($opts['required']) && $opts['required'] == 'on') ? true : false;
                    $opts['enable_max_selection_count'] = (isset($opts['enable_max_selection_count']) && $opts['enable_max_selection_count'] == 'on') ? true : false;
                    $opts['max_selection_count'] = (isset($opts['max_selection_count']) && $opts['max_selection_count'] != '') ? intval( $opts['max_selection_count'] ) : null;
                    $opts['min_selection_count'] = (isset($opts['min_selection_count']) && $opts['min_selection_count'] != '') ? intval( $opts['min_selection_count'] ) : null;
                    // Text Limitations
                    $opts['enable_word_limitation'] = (isset($opts['enable_word_limitation']) && $opts['enable_word_limitation'] == 'on') ? true : false;
                    $opts['limit_by']      = (isset($opts['limit_by']) && $opts['limit_by'] != '') ? sanitize_text_field($opts['limit_by'])  : '';
                    $opts['limit_length']  = (isset($opts['limit_length']) && $opts['limit_length'] != '') ? intval( $opts['limit_length'] ) : '';
                    $opts['limit_counter'] = (isset($opts['limit_counter']) && $opts['limit_counter'] == 'on') ? true : false;
                    
                    // Number Limitations
                    $opts['enable_number_limitation'] = (isset($opts['enable_number_limitation']) && $opts['enable_number_limitation'] == 'on') ? true : false;
                    $opts['number_min_selection']     = (isset($opts['number_min_selection']) && $opts['number_min_selection'] != '') ? sanitize_text_field($opts['number_min_selection'])  : '';
                    $opts['number_max_selection']     = (isset($opts['number_max_selection']) && $opts['number_max_selection'] != '') ? sanitize_text_field($opts['number_max_selection'])  : '';
                    $opts['number_error_message']  = (isset($opts['number_error_message']) && $opts['number_error_message'] != '') ? sanitize_text_field($opts['number_error_message']) : '';
                    $opts['enable_number_error_message']  = (isset($opts['enable_number_error_message']) && $opts['enable_number_error_message'] == 'on') ? true : false;
                    $opts['number_limit_length']  = (isset($opts['number_limit_length']) && $opts['number_limit_length'] != '') ? intval( $opts['number_limit_length'] ) : '';
                    $opts['enable_number_limit_counter'] = (isset($opts['enable_number_limit_counter']) && $opts['enable_number_limit_counter'] == 'on') ? true : false;
                    
                    // Input types placeholders
                    $opts['placeholder'] = (isset($opts['survey_input_type_placeholder'])) ? stripslashes(esc_attr($opts['survey_input_type_placeholder'])) : (isset($question_types_placeholders[$section_questions[$question_key]['type']]) ? $question_types_placeholders[$section_questions[$question_key]['type']] : '');

                    // Question caption
                    $opts['image_caption'] = (isset($opts['image_caption'])) ? stripslashes(esc_attr($opts['image_caption'])) : '';
                    $opts['image_caption_enable'] = (isset($opts['image_caption_enable']) && $opts['image_caption_enable'] == 'on') ? true : false;

                    $opts['is_logic_jump'] = (isset($opts['is_logic_jump']) && $opts['is_logic_jump'] == 'on') ? true : false;

                    $opts['user_explanation'] = (isset($opts['user_explanation']) && $opts['user_explanation'] == 'on') ? true : false;

                    $opts['enable_admin_note'] = (isset($opts['enable_admin_note']) && $opts['enable_admin_note'] == 'on') ? true : false;
                    $opts['enable_admin_note'] = (isset($opts['enable_admin_note']) && $opts['enable_admin_note'] == 'on') ? true : false;

                    $opts['admin_note'] = isset( $opts['admin_note'] ) && $opts['admin_note'] != '' ? stripslashes( esc_attr( $opts['admin_note'] ) ) : '';

                    $opts['enable_url_parameter'] = (isset($opts['enable_url_parameter']) && $opts['enable_url_parameter'] == 'on') ? true : false;
                    $opts['url_parameter'] = isset( $opts['url_parameter'] ) && $opts['url_parameter'] != '' ? stripslashes( esc_attr( $opts['url_parameter'] ) ) : '';

                    $opts['enable_hide_results'] = (isset($opts['enable_hide_results']) && $opts['enable_hide_results'] == 'on') ? true : false;

                    if( $section_questions[$question_key]['type'] == 'checkbox' ){
                        $this->options[ 'survey_checkbox_options' ][$question['id']]['enable_max_selection_count'] = $opts['enable_max_selection_count'];
                        $this->options[ 'survey_checkbox_options' ][$question['id']]['max_selection_count'] = $opts['max_selection_count'];
                        $this->options[ 'survey_checkbox_options' ][$question['id']]['min_selection_count'] = $opts['min_selection_count'];
                    }

                    if( $section_questions[$question_key]['type'] == 'text' || $section_questions[$question_key]['type'] == 'short_text'){
                        $this->options[ 'survey_text_limit_options' ][$question['id']]['enable_word_limitation'] = $opts['enable_word_limitation'];
                        $this->options[ 'survey_text_limit_options' ][$question['id']]['limit_by'] = $opts['limit_by'];
                        $this->options[ 'survey_text_limit_options' ][$question['id']]['limit_length'] = $opts['limit_length'];        
                        $this->options[ 'survey_text_limit_options' ][$question['id']]['limit_counter'] = $opts['limit_counter'];        
                    }

                    if( $section_questions[$question_key]['type'] == 'number' || $section_questions[$question_key]['type'] == 'phone'){
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['enable_number_limitation'] = $opts['enable_number_limitation'];
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['number_min_selection'] = $opts['number_min_selection'];
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['number_max_selection'] = $opts['number_max_selection'];        
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['number_error_message'] = $opts['number_error_message'];        
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['enable_number_error_message'] = $opts['enable_number_error_message'];
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['number_limit_length'] = $opts['number_limit_length'];        
                        $this->options[ 'survey_number_limit_options' ][$question['id']]['enable_number_limit_counter'] = $opts['enable_number_limit_counter'];    
                    }

                    if( $section_questions[$question_key]['type'] == 'matrix_scale' || $section_questions[$question_key]['type'] == 'matrix_scale_checkbox'){
                        if(isset($opts['matrix_columns']) && $opts['matrix_columns']){
                            foreach ($opts['matrix_columns'] as $column_key => &$column_val) {
                                if( $this->options[ $this->name_prefix . 'allow_html_in_answers' ] === false ){
                                    $column_val = htmlentities( ($column_val) );
                                }
                                $column_val = stripslashes( $column_val );
                            }
                        }
                    }

                    $q_answers = Survey_Maker_Data::get_answers_by_question_id( intval( $question['id'] ) );

                    foreach ($q_answers as $answer_key => $answer) {
                        $answer_content = (isset($answer['answer']) && $answer['answer'] != '') ? $answer['answer'] : '';
                        if( $this->options[ $this->name_prefix . 'allow_html_in_answers' ] === false ){
                            $answer_content = htmlentities( $answer_content );
                        }

                        $q_answers[$answer_key]['answer'] = stripslashes( $answer_content );

                        $q_answers[$answer_key]['image'] = (isset($answer['image']) && $answer['image'] != '') ? $answer['image'] : '';
                        $q_answers[$answer_key]['placeholder'] = (isset($answer['placeholder']) && $answer['placeholder'] != '') ? $answer['placeholder'] : '';
                        $answerOpts = array();
                        if( $answer['options'] != '' ){
                            $answerOpts = json_decode( $answer['options'], true );
                        }
                        $answerOpts['go_to_section'] = (isset($answerOpts['go_to_section']) && $answerOpts['go_to_section'] != '') ? intval( $answerOpts['go_to_section'] ) : -1;
                        $q_answers[$answer_key]['options'] = $answerOpts;
                    }

                    $section_questions[$question_key]['answers'] = $q_answers;

                    $section_questions[$question_key]['options'] = $opts;
                    $this->options[ $this->name_prefix . 'questions' ][$question['id']]['id'] = $question['id'];
                    $this->options[ $this->name_prefix . 'questions' ][$question['id']]['type'] = $question['type'];
                    $this->options[ $this->name_prefix . 'questions' ][$question['id']]['answers'] = $q_answers;
                    $this->options[ $this->name_prefix . 'questions' ][$question['id']]['options'] = $opts;
                }

                $sections[$section_key]['questions'] = $section_questions;
                $this->options[ $this->name_prefix . 'sections' ][$section['id']]['id'] = $section['id'];
                $this->options[ $this->name_prefix . 'sections' ][$section['id']]['title'] = $section['title'];
                $this->options[ $this->name_prefix . 'sections' ][$section['id']]['options'] = $section_options;
            }
        }

        // Survey RTL direction
        if(isset($options['survey_enable_rtl_direction']) && $options['survey_enable_rtl_direction'] == "on") {
            $rtl_direction = 'rtl';
        } else {
            $rtl_direction = 'ltr';
        }
        

        // echo '<pre>';
        // print_r($sections);
        // echo '</pre>';
        // die();

        if( $logged_in_limit ){
            $limit = true;
            $limit_message = $logged_in_limit_message;
        }
        
        $blocked_content_class = '';
        if( ( $limit || $is_expired ) && !$logged_in_limit && !$this->options[ $this->name_prefix . 'edit_previous_submission' ]){
            $blocked_content_class = " " . $this->html_class_prefix . "blocked-content ";
        }

        $content = array();
        
        $content[] = '<div class="' . $this->html_class_prefix . 'container ' . $blocked_content_class . $this->options[ $this->name_prefix . 'custom_class' ] . '" id="' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . '" data-id="' . $unique_id . '" data-theme="'.$this->options[ $this->name_prefix . 'theme' ].'" dir="' . $rtl_direction . '">';
                
        if($this->options[ $this->name_prefix . 'enable_survey_start_loader' ] && !Survey_Maker_Data::ays_survey_is_elementor()){
            $content[]  = Survey_Maker_Data::survey_get_loader($this->options[ $this->name_prefix . 'before_start_loader' ]);
        }

        $survey_full_screen_button_pos = $this->options[ $this->name_prefix . 'show_title' ] ? "" : $this->html_class_prefix . "full-screen-and-no-title";
        $survey_no_cover_photo_class = "";
        if( $this->options[ $this->name_prefix . 'full_screen_mode' ] || $check_subm_edit_and_reg_user){
            $survey_cover_photo_class = "";
            if( $this->options[ $this->name_prefix . 'cover_photo' ] != "" ){
                $survey_cover_photo_class = $this->html_class_prefix . "cover-photo-title-wrap";
                $survey_no_cover_photo_class = $this->html_class_prefix . "no-cover-photo";
            }
            $content[] = '<div class="' . $this->html_class_prefix . 'full-screen-and-title ' . $survey_full_screen_button_pos . ' ' . $survey_cover_photo_class . '">';
        }

        if( $this->options[ $this->name_prefix . 'show_title' ] && $this->options[ $this->name_prefix . 'cover_photo' ] != "" ){
            $content[] = '<div class="' . $this->html_class_prefix . 'title-wrap">';
                if( $this->options[ $this->name_prefix . 'full_screen_mode' ] || $check_subm_edit_and_reg_user ){
                    $content[] = '<div class="' . $this->html_class_prefix . 'cover-photo-title-wrap ' . $survey_no_cover_photo_class . '">';
                }else{
                    $content[] = '<div class="' . $this->html_class_prefix . 'cover-photo-title-wrap">';
                }
                    $content[] = '<span class="' . $this->html_class_prefix . 'title" style="line-height: 1.5;">' . $survey->title . '</span>';
                $content[] = '</div>';
            $content[] = '</div>';
        }else if( $this->options[ $this->name_prefix . 'cover_photo' ] != "" ){
            $content[] = '<div class="' . $this->html_class_prefix . 'title-wrap">';
                if( $this->options[ $this->name_prefix . 'full_screen_mode' ] || ($this->options[ $this->name_prefix . 'edit_previous_submission' ] && is_user_logged_in())){
                    $content[] = '<div class="' . $this->html_class_prefix . 'cover-photo-title-wrap ' . $survey_no_cover_photo_class . '"></div>';
                }else{
                    $content[] = '<div class="' . $this->html_class_prefix . 'cover-photo-title-wrap"></div>';
                }
            $content[] = '</div>';
        }else if($this->options[ $this->name_prefix . 'show_title' ]){
            $content[] = '<div class="' . $this->html_class_prefix . 'title-wrap">';
                $content[] = '<span class="' . $this->html_class_prefix . 'title">' . $survey->title . '</span>';
            $content[] = '</div>';
        }


        if( $check_subm_edit_and_reg_user ){
            $is_limited_for_edit = $limit ? 'ays-survey-edit-previous-submission-restricted' : '';
            $content[] = '<div class="ays-survey-edit-previous-submission-box">';
                $content[] = '<img src="'. SURVEY_MAKER_ADMIN_URL.'/images/loaders/tail-spin.svg" style="width:25px;" class="ays-survey-edit-previous-submission-loader display_none">';
                $content[] = '<button class="ays-survey-edit-previous-submission-button '.$is_limited_for_edit.'" title="'.__('Get previous submission' , $this->plugin_name).'">
                                <img src="'.SURVEY_MAKER_PUBLIC_URL . '/images/edit_pencil.svg">
                            </button>';
            $content[] = '</div>';
        }

        if( $this->options[ $this->name_prefix . 'full_screen_mode' ] && !$limit ){
            $content[] = '<div class="ays-survey-full-screen-mode">
                            <a class="ays-survey-full-screen-container">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" fill="#fff" viewBox="0 0 24 24" width="24" tabindex="0" class="ays-survey-close-full-screen">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" fill="#fff" viewBox="0 0 24 24" width="24" tabindex="0" class="ays-survey-open-full-screen">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                                </svg>
                            </a>
                        </div>';
        }

        if( $this->options[ $this->name_prefix . 'full_screen_mode' ] || $check_subm_edit_and_reg_user ){
            $content[] = '</div>';
        }
        
        if( $check_limit_by_country ){
            
            $content[] = $this->create_restricted_content( $limit_country_message );
            $content[] = $this->get_styles();
            $content[] = $this->get_custom_css();
    
            $content[] = $this->get_encoded_options( $limit );
    
            $content[] = '</div>';
            
            $content = implode( '', $content );
            return $content;
        }

        if( $check_block_by_ip_addresses ){
            
            $content[] = $this->create_restricted_content( $block_ips_message );
            $content[] = $this->get_styles();
            $content[] = $this->get_custom_css();
    
            $content[] = $this->get_encoded_options( $limit );
    
            $content[] = '</div>';
            
            $content = implode( '', $content );
            return $content;
        }

        if( $this->options[ $this->name_prefix . 'enable_logged_users' ] ){
            if( ! is_user_logged_in() && $this->options[ $this->name_prefix . 'show_login_form'] ){
                $content[] = $this->create_restricted_content( $limit_message );
                $content[] = $this->get_styles();
                $content[] = $this->get_custom_css();
        
                $content[] = $this->get_encoded_options( $limit );
        
                $content[] = '</div>';
                
                $content = implode( '', $content );
                return $content;
            }
        }
        
        $enable_chat_mode = $this->options[ $this->name_prefix . 'enable_chat_mode' ];

    	$content[] = '<form class="' . $this->html_class_prefix . 'form" method="post" autocomplete="off">';
    	$content[] = '<input type="hidden" name="'. $this->html_name_prefix .'id-' . $unique_id . '" value="'. $id .'">';
    	$content[] = '<input type="hidden" name="'. $this->html_name_prefix .'post-id" value="'. get_the_ID() .'">';
        // Get survey current page
        $ays_survey_protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";         
        $current_survey_page_link = esc_url( $ays_survey_protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    	$content[] = '<input type="hidden" name="'. $this->html_name_prefix .'current_page_link" value="'. $current_survey_page_link .'">';

        if($enable_chat_mode){
            if( !$limit && !$is_expired ){
                include_once('partials/class-survey-maker-chat-survey.php');
                $chat_mode = new Survey_Maker_Chat_Survey(SURVEY_MAKER_NAME,SURVEY_MAKER_NAME_VERSION);
                $content[] = $chat_mode->create_chat($sections,$this->unique_id,$this->options);
            }else{
                if( $is_expired && !$limit ){
                    $limit_message = $expired_survey_message;
                }
                $content[] = $this->create_restricted_content( $limit_message );
            }
        }
        elseif ((isset($paypal_content['paypal']['show_paypal']) && $paypal_content['paypal']['show_paypal']) ||
                (isset($stripe_payment_content['stripe']['show_stripe']) && $stripe_payment_content['stripe']['show_stripe'])){
            if( !$limit && !$is_expired ){
                $content[] = "<div class='ays-survey-section-header'>";
                    $content[] = $paypal_content['paypal']['survey_paypal'];
                    $content[] = $stripe_payment_content['stripe']['survey_stripe'];
                $content[] = "</div>";
            }else{
                if( $is_expired && !$limit ){
                    $limit_message = $expired_survey_message;
                }
                $content[] = $this->create_restricted_content( $limit_message );
            }
        }
        else{
            if( !$limit && !$is_expired ){
                $content[] = $this->create_sections( $sections );
            }else{
                if( $is_expired && !$limit ){
                    $limit_message = $expired_survey_message;
                }
                else if($is_expired && $limit){
                    $limit_message = $expired_survey_message;
                }
                if(!$is_expired){
                    if($check_subm_edit_and_reg_user){
                        $content[] = $this->create_sections( $sections , true );
                    }
                }
                $content[] = $this->create_restricted_content( $limit_message );


            }
        }

        $content[] = '</form>';

        $content[] = $this->get_styles();
        $content[] = $this->get_custom_css();

        $content[] = $this->get_encoded_options( $limit ,  $check_subm_edit_and_reg_user);

        $content[] = '</div>';
        
    	$content = implode( '', Survey_Maker_Data::ays_survey_translate_content($content) );
    	return $content;
    }

    public function create_sections( $sections, $hidden = false ){

    	$content = array();
        $hide_sections = $hidden ? "display_none" : "";
    	$content[] = '<div class="' . $this->html_class_prefix . 'sections '.$hide_sections.'">';

        if(isset($this->options[ $this->name_prefix .'enable_password']) && $this->options[ $this->name_prefix .'enable_password'] ){
            $ays_survey_show_password_flag = true;

            $password_type = $this->options['options'][ $this->name_prefix .'password_type'];
            $general_psw_input = $this->options[$this->name_prefix .'password_survey'];
            $active_psw = $this->options['options'][ $this->name_prefix .'generated_passwords'][ $this->name_prefix .'active_passwords'];

            if( $password_type == 'generated_password' && empty($active_psw) ){
                $ays_survey_show_password_flag = false;
            }elseif( $password_type == 'general' && $general_psw_input == '' ){
                $ays_survey_show_password_flag = false;
            }else{
                $ays_survey_show_password_flag = true;
            }

            if($ays_survey_show_password_flag){
                $if_generated = isset($this->options[ $this->name_prefix .'password_type']) && $this->options[ $this->name_prefix .'password_type'] == 'generated_password' ? 'name="' . $this->html_class_prefix . 'password"' : '';
                $content[] = '<div class="' . $this->html_class_prefix . 'section">';
    
                    $content[] = '<div class="' . $this->html_class_prefix . 'section-content">';
                        
                        $content[] = '<div style="margin-bottom:10px;">';
                            $content[] = '<div>';
                                $content[] = $this->options[ $this->name_prefix .'password_message'];
                            $content[] = '</div>';
                        $content[] = '</div>';
                        $content[] = '<div style="margin-bottom:10px;">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';
                                $content[] = '<input class="' . $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'password ' . $this->html_class_prefix . 'question-input ' . 
                                                $this->html_class_prefix . 'input" type="password" autocomplete="off" tabindex="0" placeholder="'. __( "Please enter password", $this->plugin_name) .'" '.$if_generated.'/>';
                                $content[] = '<div class="' . $this->html_class_prefix . 'input-underline" style="margin:0;"></div>';
                                $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation" style="margin:0;"></div>';
                            $content[] = '</div>';
                        $content[] = '</div>';
    
                        $content[] = '<div class="' . $this->html_class_prefix . 'check-password-block">';
                            
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-buttons">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'section-button-container" tabindex="0">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'section-button-content">';
    
                                        $content[] = '<input type="button" class="' . $this->html_class_prefix . 'section-button ays-check-survey-password" value="'. $this->buttons_texts[ 'checkButton' ] .'" />';
    
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                            
                        $content[] = '</div>';
                    $content[] = '</div>';
    
                $content[] = '</div>';
            }
        }

        if( $this->options[ $this->name_prefix . 'enable_start_page'] === true ){
            
            $content[] = '<div class="' . $this->html_class_prefix . 'section ' . $this->html_class_prefix . 'section-start-page">';

                $content[] = '<div class="' . $this->html_class_prefix . 'section-content ' . $this->options[ $this->name_prefix .'start_page_custom_class'] . '">';
                    
                    $content[] = '<div class="' . $this->html_class_prefix . 'section-header">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'section-title-row">';
                            $content[] = '<span class="' . $this->html_class_prefix . 'section-title">' . stripslashes( $this->options[ $this->name_prefix .'start_page_title'] ) . '</span>';
                        $content[] = '</div>';

                        $content[] = '<div class="' . $this->html_class_prefix . 'section-desc">' . stripslashes( $this->options[ $this->name_prefix .'start_page_description'] ) . '</div>';

                        $content[] = '<div class="' . $this->html_class_prefix . 'section-buttons">';
    
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-button-container" tabindex="0">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'section-button-content">';
                                    $content[] = '<input type="button" class="' . $this->html_class_prefix . 'section-button ' . $this->html_class_prefix . 'start-button" value="'. $this->buttons_texts[ 'startButton' ] .'" />';
                                $content[] = '</div>';
                            $content[] = '</div>';
    
                        $content[] = '</div>';

                    $content[] = '</div>';

                $content[] = '</div>';

            $content[] = '</div>';
        }

        $sections_count = count( $sections );
        $survey_current_post_id = get_the_ID();
        $survey_current_post_author_email = get_the_author_meta('email');
        $survey_current_post_author_nickname = get_the_author_meta('user_nicename');
        $survey_additional_wp_data = array(
            'survey_post_type' => get_post_type(),
            'survey_post_id' => $survey_current_post_id,
            'survey_post_author_email' => $survey_current_post_author_email,
            'survey_current_post_author_nickname' => $survey_current_post_author_nickname,
        );

        $additional_data = base64_encode(json_encode($survey_additional_wp_data));

        $show_question_numbering = $this->options[ $this->name_prefix . 'auto_numbering_questions' ];
        $this->options[ $this->name_prefix . 'question_numbering_array' ] = Survey_Maker_Data::ays_survey_numbering_all( $show_question_numbering );
        
    	foreach ( $sections as $key => $section ) {
            $first = $key == 0 ? true : false;
            $last = $key + 1 == $sections_count ? true : false;
            $section_numbering = $key + 1;
    		$content[] = $this->create_section( $section, $last, $first, $sections_count, $section_numbering );
        }

        $minimal_theme_header  = $this->options[ $this->name_prefix . 'is_minimal' ] ? 'ays-survey-minimal-theme-header' : "";
        $modern_theme_header   = $this->options[ $this->name_prefix . 'is_modern' ] ? 'ays-survey-modern-theme-header' : "";
        $business_theme_header = $this->options[ $this->name_prefix . 'is_business' ] ? 'ays-survey-business-theme-header' : "";
        $elegant_theme_header  = $this->options[ $this->name_prefix . 'is_elegant' ] ? 'ays-survey-elegant-theme-header' : "";

        $integrations_args = apply_filters( 'ays_sm_front_end_integrations_options', array(), $this->options );
	    $recaptcha_content = apply_filters( "ays_sm_front_end_recaptcha", array(), $integrations_args, $this->options );
	    $content[] = implode( $recaptcha_content );

        $content[] = '<div class="' . $this->html_class_prefix . 'section ' . $this->html_class_prefix . 'results-content">';
            $content[] = '<div class="' . $this->html_class_prefix . 'section-header ' . $minimal_theme_header . ' ' . $modern_theme_header . ' '.$business_theme_header.' '.$elegant_theme_header.'">';
            
                $content[] = '<div class="' . $this->html_class_prefix . 'results">';
                    $content[] = '<input type="hidden" value="'.esc_attr($additional_data).'" name="' . $this->name_prefix . 'additional_wp_data'  . '">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'loader">' . $this->options[ $this->name_prefix . 'loader_html' ] . '</div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'thank-you-page">';

                    if( $this->options[ $this->name_prefix . 'enable_restart_button' ] ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'section-buttons">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-button-container" tabindex="0">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'section-button-content">';
                                    $content[] = '<button type="button" class="' . $this->html_class_prefix . 'section-button ' . $this->html_class_prefix . 'restart-button">'. __( "Restart", $this->plugin_name ) .'</button>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';
                    }

                    if( $this->options[ $this->name_prefix . 'enable_exit_button' ] ){
                        if( $this->options[ $this->name_prefix . 'exit_redirect_url' ] != '' && filter_var( $this->options[ $this->name_prefix . 'exit_redirect_url' ], FILTER_VALIDATE_URL) !== false ){

                            $content[] = '<div class="' . $this->html_class_prefix . 'section-buttons">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'section-button-container" tabindex="0">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'section-button-content">';
                                        $content[] = '<a href="' . $this->options[ $this->name_prefix . 'exit_redirect_url' ] . '" class="' . $this->html_class_prefix . 'section-button">'. $this->buttons_texts[ 'exitButton' ] .'</a>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        }
                    }

                    if( $this->options[ $this->name_prefix . 'social_buttons' ] ){
                        $actual_link = "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on"){
                            $actual_link = "https" . $actual_link;
                        }else{
                            $actual_link = "http" . $actual_link;
                        }
                        $content[] = "<div class='ays-survey-social-shares'>";

                        if ( $this->options[ $this->name_prefix . 'social_button_ln' ] ) {
                            $content[] = "<a class='ays-survey-share-btn ays-survey-share-btn-linkedin ays-survey-share-btn-all'
                                            href='https://www.linkedin.com/shareArticle?mini=true&url=" . $actual_link . "'
                                            title='Share on LinkedIn'>
                                            <span class='ays-survey-share-btn-icon'></span>
                                            <span class='ays-share-btn-text'>LinkedIn</span>
                                         </a>";
                        }
                        if ( $this->options[ $this->name_prefix . 'social_button_fb' ] ) {
                            $content[] = "<a class='ays-survey-share-btn ays-survey-share-btn-facebook ays-survey-share-btn-all'
                                            href='https://www.facebook.com/sharer/sharer.php?u=" . $actual_link . "'
                                            title='Share on Facebook'>
                                            <span class='ays-survey-share-btn-icon'></span>
                                            <span class='ays-share-btn-text'>Facebook</span>
                                          </a>";
                        }
                        if ( $this->options[ $this->name_prefix . 'social_button_tr' ] ) {
                            $content[] = "<a class='ays-survey-share-btn ays-survey-share-btn-twitter ays-survey-share-btn-all'
                                            href='https://twitter.com/share?url=" . $actual_link . "'
                                            title='Share on Twitter'>
                                            <span class='ays-survey-share-btn-icon'></span>
                                            <span class='ays-share-btn-text'>Twitter</span>
                                          </a>";
                        }
                        if ( $this->options[ $this->name_prefix . 'social_button_vk' ] ) {
                            $content[] = "<a class='ays-survey-share-btn ays-survey-share-btn-vkontakte ays-survey-share-btn-all'
                                            href='https://vk.com/share.php?url=" . $actual_link . "'
                                            title='Share on VKontakte'>
                                            <span class='ays-survey-share-btn-icon'></span>
                                            <span class='ays-share-btn-text'>VKontakte</span>
                                          </a>";
                        }
                        $content[] = "</div>";
                    }

                    $content[] = '</div>';
                $content[] = '</div>';

            $content[] = '</div>';
        $content[] = '</div>';

    	$content[] = '</div>';

    	$content = implode( '', $content );

    	return $content;
    }

    public function create_section( $section, $last, $first, $section_count, $section_numbering ){
    
		$content = array();

        $setting_options = Survey_Maker_Data::get_setting_data( 'options' );

        $setting_options[$this->name_prefix . 'enable_promote_plugin'] = isset($setting_options[$this->name_prefix . 'enable_promote_plugin']) ? $setting_options[$this->name_prefix . 'enable_promote_plugin'] : 'on';
        $survey_enable_promote_plugin = (isset($setting_options[$this->name_prefix . 'enable_promote_plugin']) && $setting_options[$this->name_prefix . 'enable_promote_plugin'] == "on") ? true : false;

        $survey_enable_question_numbering_by_sections = $this->options[ $this->name_prefix . 'enable_question_numbering_by_sections' ];
        if(!$survey_enable_question_numbering_by_sections){
            static $question_numbering_counter = 0;
        }

        $content[] = '<div class="' . $this->html_class_prefix . 'section" data-id="' . $section['id'] . '" data-page-number="'.$section_numbering.'">';

        $minimal_theme_header = $this->options[ $this->name_prefix . 'is_minimal' ] ? 'ays-survey-minimal-theme-header' : "";
        $modern_theme_header = $this->options[ $this->name_prefix . 'is_modern' ] ? 'ays-survey-modern-theme-header' : "";
        $business_theme_header = $this->options[ $this->name_prefix . 'is_business' ] ? 'ays-survey-business-theme-header' : "";
        $business_checkmark_label_container = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
        $business_dashed_border = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'answer-business-hover' : '';

        $elegant_theme_header = $this->options[ $this->name_prefix . 'is_elegant' ] ? 'ays-survey-elegant-theme-header' : "";
        $elegant_theme_answer_hover = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'answer-elegant-hover' : '';

            if( $this->options[ $this->name_prefix . 'show_section_header' ] ){
                $show_section_header = true;
                if( $section['title'] == '' && $section['description'] == '' ){
                    $show_section_header = false;
                }
                if( $this->options[ $this->name_prefix . 'show_sections_questions_count' ] ){
                    $show_section_header = true;
                }

                if( $show_section_header ){
                    $content[] = '<div class="' . $this->html_class_prefix . 'section-header ' . $minimal_theme_header . ' ' . $modern_theme_header . ' '.$business_theme_header.' '.$elegant_theme_header.'">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'section-title-row">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-title-row-main">';
                                $content[] = '<span class="' . $this->html_class_prefix . 'section-title">' . stripslashes( $section['title'] ) . '</span>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="' . $this->html_class_prefix . 'section-desc">' . stripslashes( $section['description'] ) . '</div>';
                        if( $this->options[ $this->name_prefix . 'show_sections_questions_count' ] ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-questions-count" title="Questions Count">' . count( $section['questions'] ) . '</div>';
                        }

                    $content[] = '</div>';
                }
            }
            
	    	$content[] = '<div class="' . $this->html_class_prefix . 'section-content">';

                $content[] = '<div class="' . $this->html_class_prefix . 'section-questions">';
                
                if( $this->options[ $this->name_prefix . 'enable_randomize_questions' ] ){
                    shuffle( $section['questions'] );
                }

                $loop_count = 1;
		    	foreach ( $section['questions'] as $key => $question ) {
                    $numbering = "";

                    $question_inner_counter = $key;

                    if(isset($question_numbering_counter)){
                        $question_inner_counter = $question_numbering_counter;                        
                        $question_numbering_counter++;
                    }

                    if(isset($this->options[ $this->name_prefix . 'question_numbering_array' ]) && !empty($this->options[ $this->name_prefix . 'question_numbering_array' ])){
                        $numbering = $this->options[ $this->name_prefix . 'question_numbering_array' ][$question_inner_counter]." ";
                    }
		    		$content[] = $this->create_question( $question , $numbering, $loop_count );
                    $loop_count++;
		    	}
		    	$content[] = '</div>';

	    	$content[] = '</div>';

            $footer_class_with_bar = "";
            $footer_class_with_terms = "";
            $enable_terms_and_conditions = $this->options[ $this->name_prefix . 'enable_terms_and_conditions' ];
            $terms_and_conditions_data = $this->options[ $this->name_prefix . 'terms_and_conditions_data' ];
            if( $this->options[ $this->name_prefix . 'enable_progress_bar' ] ){
                $footer_class_with_bar = "ays-survey-footer-with-live-bar";
            }

            $enable_terms_and_conditions_required_message = $this->options['enable_terms_and_conditions_required_message'];
            if($enable_terms_and_conditions){

                $footer_class_with_terms = "ays-survey-footer-with-terms-and-conds";
            }

	    	$content[] = '<div class="' . $this->html_class_prefix . 'section-footer '.$footer_class_with_bar.' '.$footer_class_with_terms.'">';

                if( $last ){
                    if( $enable_terms_and_conditions ) {
                        $content[] = '<div data-required="true" data-all-terms-check="true" data-type="checkbox" class="' . $this->html_class_prefix . 'section-terms-and-conditions-container">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-terms-and-conditions-content">';
                                $padding_for_bussines = $this->options[ $this->name_prefix . 'is_business' ] ? 'style="padding:0 10px 3px;"' : '';
                                foreach ($terms_and_conditions_data as $tc_text => $tc_text_value) {
                                    if( $tc_text_value['messages'] != '' ){
                                        $content[] = '<div class="' . $this->html_class_prefix . 'terms-and-conditions-content-box '.$business_dashed_border.' '.$elegant_theme_answer_hover.'" '.$padding_for_bussines.'>';
                                            $content[] = '<label class="' . $this->html_class_prefix . 'answer-label ' . $this->html_class_prefix . 'answer-label-terms-conditions '.$business_checkmark_label_container.'">';
                                                    if($this->options[ $this->name_prefix . 'is_business' ]){
                                                            $content[] = '<input type="checkbox" class="' . $this->html_class_prefix . 'is-checked-terms-and-conditions ' . $this->html_class_prefix . 'business-theme-answers">';
                                                            $content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-checkbox" style="top:18px"></span>';
                                                    }else{
                                                        $content[] = '<input type="checkbox" class="' . $this->html_class_prefix . 'is-checked-terms-and-conditions">';
                                                    }
                                                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                                                $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                                            $content[] = '</div>';
                                                        $content[] = '</div>';
                                                    $content[] = '</div>';
                                                    $content[] = '<div class="' . $this->html_class_prefix . 'section-terms-and-conditions">';
                                                        $content[] = '<span class="">' . stripslashes(($tc_text_value['messages'])) . '</span>';
                                                    $content[] = '</div>';
                                                $content[] = '</div>';
                                            $content[] = '</label>';     
                                        
                                        $content[] = '</div>';
                                    }   
                                }
                            $content[] = '</div>';
                            if($enable_terms_and_conditions_required_message){
                                $content[] = '<div class="' . $this->html_class_prefix . 'section-terms-and-conditions-required-message-content" style="display:none;">';
                                    $content[] = '<p class="' . $this->html_class_prefix . 'section-terms-and-conditions-required-message">';
                                        $content[] = '<span class="">' . __('By clicking on the checkbox, you agree to our Terms and Conditions', $this->plugin_name) . '</span>';
                                    $content[] = '</p>';
                                $content[] = '</div>';
                            }
                        $content[] = '</div>';
                    }
                }
		    	$content[] = '<div class="' . $this->html_class_prefix . 'section-buttons">';
    

                    if( ! $first ){
                        if( $this->options[ $this->name_prefix . 'enable_previous_button' ] ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'section-button-container" tabindex="0">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'section-button-content">';
                                    $content[] = '<input type="button" class="' . $this->html_class_prefix . 'section-button ' . $this->html_class_prefix . 'prev-button" value="'. $this->buttons_texts[ 'previousButton' ] .'" />';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        }
                    }

                    $content[] = '<div class="' . $this->html_class_prefix . 'section-button-container">';
                        $content[] = '<div class="' . $this->html_class_prefix . 'section-button-content" tabindex="0">';
                        $finish_display_none_class = $this->html_class_prefix . 'display-none';
                        $next_display_none_class = '';
                        if( $last ){
                            $finish_display_none_class = '';
                            $next_display_none_class = $this->html_class_prefix . 'display-none';
                        }

                            // $display_none_class = '';
                            // // if( !$last ){
                            //     $display_none_class = $this->html_class_prefix . 'display-none';
                            // // }
                            $content[] = '<input type="button" class="' . $this->html_class_prefix . 'section-button ' . $this->html_class_prefix . 'next-button ' . $next_display_none_class . '" value="'. $this->buttons_texts[ 'nextButton' ] .'" />';
                            $content[] = '<input type="button" class="' . $this->html_class_prefix . 'section-button ' . $this->html_class_prefix . 'finish-button ' . $finish_display_none_class . '" value="'. $this->buttons_texts[ 'finishButton' ] .'" />';
                        $content[] = '</div>';
                    $content[] = '</div>';
		    	$content[] = '</div>';

                if($this->options[ $this->name_prefix . 'enable_progress_bar' ] ){
                    $page_fill_percent = (1*100)/$section_count;
                    $content[] = "<div class='" . $this->html_class_prefix . "live-bar-main'>";
                    if(!($this->options[ $this->name_prefix . 'hide_section_bar' ])){
                        $content[] = "<div class='" . $this->html_class_prefix . "live-bar-wrap'>
                                        <div class='" . $this->html_class_prefix . "live-bar-fill' style='width: ".$page_fill_percent."%;'></div>
                                    </div>";
                    }
                    if(!($this->options[ $this->name_prefix . 'hide_section_pagination_text' ])){
                    $section_live_progress_bar_text = sprintf( __("%s %s of %s" , $this->plugin_name), $this->options[ $this->name_prefix . 'progress_bar_text' ], "<span class='" . $this->html_class_prefix . "live-bar-changeable-text'>1</span>", $section_count );
                    $content[] = "<div class='" . $this->html_class_prefix . "live-bar-status'>
                                    <span class='" . $this->html_class_prefix . "live-bar-status-text'>
                                        " . $section_live_progress_bar_text . "
                                    </span>
                                </div>";
                        }
                    $content[] = "</div>";
                }

                if($survey_enable_promote_plugin){
                    $content[] = '<div class="'.$this->html_class_prefix.'promote-survey-content">';
                        $content[] = '<p class="'.$this->html_class_prefix.'promote-survey-text">';
                            $content[] = __('By', $this->plugin_name) . ' <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank">Wordpress Survey plugin</a>';
                        $content[] = '</p>';
                    $content[] = '</div>';
                }

	    	$content[] = '</div>';
    	
    	$content[] = '</div>';

    	$content = implode( '', $content );

    	return $content;
    }

    public function create_question( $question, $numbering, $loop_count ){

        $question_type = $question['type'];
        $answers = $question['answers'];
        $answers_html = array();
        $is_required = $question['options']['required'];
        $user_explanation = $question['options']['user_explanation'];

        $image_caption = isset($question['options']['image_caption']) ? $question['options']['image_caption'] : '';
        $image_caption_enable = $question['options']['image_caption_enable'];

        $is_minimum = "false";
        if($question_type == 'checkbox'){
            if(isset($question['options']['enable_max_selection_count']) && $question['options']['enable_max_selection_count'])
            $is_minimum  = isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != "" ? "true" : "false";
        }

        $hidden_type = $question_type == 'hidden' ? "display_none" : "";

        $enable_admin_note = $question['options']['enable_admin_note'];
        $admin_note = $question['options']['admin_note'];

        $enable_url_parameter = $question['options']['enable_url_parameter'];
        $url_parameter = $question['options']['url_parameter'];

        $enable_hide_results = $question['options']['enable_hide_results'];
        // Logo Image URL
        $survey_logo_url_validate = "javascript:void(0)";
        $survey_logo_url = isset($this->options['options'][ $this->name_prefix . 'logo_url' ]) && $this->options['options'][ $this->name_prefix . 'logo_url' ] != "" ? $this->options['options'][ $this->name_prefix . 'logo_url' ] : "";
        $survey_logo_url_check = isset($this->options['options'][ $this->name_prefix . 'enable_logo_url' ]) && $this->options['options'][ $this->name_prefix . 'enable_logo_url' ] == "on" ? true : false;
        $survey_logo_url_check_new_tab = isset($this->options['options'][ $this->name_prefix . 'logo_url_new_tab' ]) && $this->options['options'][ $this->name_prefix . 'logo_url_new_tab' ] == "on" ? true : false;
        $survey_logo_target = "";
        $logo_cursor = "cursor:context-menu;";
        if($survey_logo_url_check){
            $logo_cursor = "cursor:pointer;";
            $survey_logo_url_validate = filter_var($survey_logo_url, FILTER_VALIDATE_URL) ? $survey_logo_url : $survey_logo_url_validate;
            if(filter_var($survey_logo_url, FILTER_VALIDATE_URL) && $survey_logo_url_check_new_tab){
                $survey_logo_target = "target='_blank'";
            }
        }
        //

        if( $this->options[ $this->name_prefix . 'enable_randomize_answers' ] ){
            shuffle( $question['answers'] );
            shuffle( $answers );
        }

        $other_answer = $question['user_variant'];
        if( $other_answer ){
            $answers[] = array(
                'id' => '0',
                'question_id' => $question['id'],
                'answer' => '',
                'image' => '',
                'ordering' => count( $answers ) + 1,
                'placeholder' => '',
                'is_other' => true,
            );
        }

        $answers['is_first_question'] = $loop_count;
        $question['answers']['is_first_question'] = $loop_count;
        $is_lazy_loading = ($loop_count > 1) ? $this->lazy_loading : "";

        $show_answers_numbering = $this->options[ $this->name_prefix . 'auto_numbering' ];
        $this->options[ $this->name_prefix . 'numbering_array' ] = Survey_Maker_Data::ays_survey_numbering_all( $show_answers_numbering );

        $minimal_theme_question = $this->options[ $this->name_prefix . 'is_minimal' ] ? 'ays-survey-minimal-theme-question' : "";
        $modern_theme_question = $this->options[ $this->name_prefix . 'is_modern' ] ? 'ays-survey-modern-theme-question' : "";
        $business_theme_question = $this->options[ $this->name_prefix . 'is_business' ] ? 'ays-survey-business-theme-question' : "";
        $elegant_theme_question = $this->options[ $this->name_prefix . 'is_elegant' ] ? 'ays-survey-elegant-theme-question' : "";

        $has_answer_image = false;
        foreach ($answers as $key => $answer) {
            if(isset( $answer['image'] ) && $answer['image'] != ""){
                $has_answer_image = true;
            }
        }

        $question_types = array(
            "radio",
            "checkbox",
            "select",
            "text",
            "short_text",
            "number",
            "phone",
            "email",
            "name",
            "linear_scale",
            "star",
            "date",
            "time",
            "date_time",
            "matrix_scale",
            "matrix_scale_checkbox",
            "star_list",
            "slider_list",
            "range",
            "upload",
            "html",
            "hidden",
        );

        $question_types_getting_answers_array = array(
            "radio",
            "checkbox",
        );

        $is_other = false;
        foreach ($answers as $key => $answer) {
            if(isset( $answer['is_other'] ) && $answer['is_other'] == true){
                $is_other = true;
            }
        }

        $yesorno_elegant_grid_view = $this->options[ $this->name_prefix . 'is_elegant' ] && !$is_other && $question_type == 'yesorno' ? $this->html_class_prefix . 'yesorno-elegant-grid-view' : '';

        $dropdown_elegant = $this->options[ $this->name_prefix . 'is_elegant' ] && $question_type == 'select' ? $this->html_class_prefix . 'dropdown-elegant' : '';

        if( !in_array( $question_type, $question_types ) ){
            $question_type = "radio";
        }
        
        // switch ( $question_type ) {
        //     case "radio":
        //         $answers_html[] = $this->ays_survey_question_type_RADIO_html( $answers );
        //         break;
        //     case "checkbox":
        //         $answers_html[] = $this->ays_survey_question_type_CHECKBOX_html( $answers );
        //         break;
        //     case "select":
        //         $answers_html[] = $this->ays_survey_question_type_SELECT_html( $question );
        //         $has_answer_image = false;
        //         break;
        //     case "text":
        //         $answers_html[] = $this->ays_survey_question_type_TEXT_html( $question );
        //         $has_answer_image = false;
        //         break;
        //     case "short_text":
        //         $answers_html[] = $this->ays_survey_question_type_SHORT_TEXT_html( $question );
        //         $has_answer_image = false;
        //         break;
        //     case "number":
        //         $answers_html[] = $this->ays_survey_question_type_NUMBER_html( $question );
        //         $has_answer_image = false;
        //         break;
        //     default:
        //         $answers_html[] = $this->ays_survey_question_type_RADIO_html( $answers );
        //         break;
        // }

        $question_type_function = 'ays_survey_question_type_' . strtoupper( $question_type ) . '_html';
        if($question["options"]["enable_url_parameter"]) {
            $transmitting_array = in_array( $question_type, $question_types_getting_answers_array ) ? array( "answers" => $answers, "questions" => $question) : $question;
        } else {
            $transmitting_array = in_array( $question_type, $question_types_getting_answers_array ) ? $answers : $question;
        }

        if($question_type == "matrix_scale" || $question_type == "matrix_scale_checkbox"){
            $transmitting_array = array( 
                "answers" => $answers, 
                "questions" => $question
            );
        }

        $enable_expand_collapse_question = $this->options[ $this->name_prefix . 'enable_expand_collapse_question' ];

        if( $this->options[ $this->name_prefix . 'enable_expand_collapse_question' ] ){
            $expand_collapse_question_content = '<div class="">
                                                    <div class="' . $this->html_class_prefix . 'collapse-question-action">
                                                        <div class="' . $this->html_class_prefix . 'question-collapse-img-icon-content">
                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="26px" height="26px" viewBox="0 0 26 26" preserveAspectRatio="none">
                                                                <g xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M7.41 18.59L8.83 20 12 16.83 15.17 20l1.41-1.41L12 14l-4.59 4.59zm9.18-13.18L15.17 4 12 7.17 8.83 4 7.41 5.41 12 10l4.59-4.59z"/>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>';
        } else {
            $expand_collapse_question_content = '';
        }

        $answers_html[] = $this->$question_type_function( $transmitting_array );

        $answers_html = implode( '', $answers_html );

        $answer_grid = '';
        if( ($has_answer_image || $this->options[ $this->name_prefix . 'answers_view' ] == 'grid') &&  $question_type != 'upload'){
            $answer_grid = $this->html_class_prefix . 'question-answers-grid';
        }
        $data_required = $is_required ? "true" : "false";

        $is_required_field = $is_required ? '<sup class="' . $this->html_class_prefix . 'question-required-icon">*</sup>' : "";

        $question_title = $question['question'];
        $question_description = $question['question_description'];

        if($numbering){
            preg_match('/<([a-z]*)\b[^>]*>(.*?)<\/\1>/', $question_title, $matches );
            if(empty($matches)){
                $question_title = $numbering . $question_title;                
                if($question_description != ''){
                    $question_description = $numbering . $question_description;
                }
            }else{
                $question_title_numbering_1 = $numbering . $matches[2];
                $question_title_numbering_2 = str_replace( $matches[2], $question_title_numbering_1, $matches[0] );
                $question_title = str_replace( $matches[0], $question_title_numbering_2, $question_title );
                $question_description_numbering_1 = $numbering . $matches[2];
                $question_description_numbering_2 = str_replace( $matches[2], $question_description_numbering_1, $matches[0] );
                $question_description = str_replace( $matches[0], $question_description_numbering_2, $question_description );

            }
        }

        if ( $is_required_field != "" ) {
            preg_match('/<([a-z]+[1-9]*)\b[^>]*>(.*?)<\/\1>/', $question_title, $matches );
            if(empty($matches)){
                $question_title = $question_title . $is_required_field;
            }else{
                $question_title_numbering_1 = $matches[2] . $is_required_field;
                $question_title_numbering_2 = str_replace( $matches[2], $question_title_numbering_1, $matches[0] );
                $question_title = str_replace( $matches[0], $question_title_numbering_2, $question_title );
            }
        }

        // $question_title .= $is_required_field;

        $hide_question = ($question['type'] == 'html' && $question['options']['html_types_content'] == '') ? 'display_none_not_important' : '';

        $survey_question_text_to_speech = (isset($this->options[ $this->name_prefix . 'question_text_to_speech' ]) && $this->options[ $this->name_prefix . 'question_text_to_speech' ]) ? true : false;
        $survey_question_text_to_speech_title_class = ($survey_question_text_to_speech) ? $this->html_class_prefix . 'question-title-text-to-speech' : '';

		$content = array();
    	$content[] = '<div class="' . $this->html_class_prefix . 'question ' . $minimal_theme_question . ' '.$modern_theme_question . ' '.$business_theme_question.' ' . $hidden_type . ' '.$elegant_theme_question.' '.$hide_question.' '.$dropdown_elegant .'" data-required="' . $data_required . '" data-type="' . $question_type . '" data-id="' . $question['id'] . '" data-is-min="'.$is_minimum.'">';
            if($survey_question_text_to_speech){
                $content[] = '<div class="' . $this->html_class_prefix . 'question-title-text-to-speech-icon" data-question="'.base64_encode(strip_tags($question_title)).'"><img src="'. SURVEY_MAKER_ADMIN_URL.'/images/icons/audio-volume-high-svgrepo-com.svg"></div>';
            }
            $content[] = '<div class="' . $this->html_class_prefix . 'question-wrap-collapsed-action ' . $this->html_class_prefix . 'display-none">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-wrap-collapsed-action-contnet">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-wrap-collapsed-action-contnet-text ' . $this->html_class_prefix . 'question-title">' . Survey_Maker_Data::ays_autoembed( $question_title ) . '</div>';
                        $content[] = '<div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'action-expand-question">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'question-img-icon-content">';
                                        $content[] = '<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="26px" height="26px" viewBox="0 0 26 26" preserveAspectRatio="none">
                                                    <g xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M12 5.83L15.17 9l1.41-1.41L12 3 7.41 7.59 8.83 9 12 5.83zm0 12.34L8.83 15l-1.41 1.41L12 21l4.59-4.59L15.17 15 12 18.17z"/>
                                                    </g>
                                                </svg>';
                                $content[] = '</div>';
                            $content[] = '</div>';               
                        $content[] = '</div>';              
               $content[] = '</div>';                
            $content[] = '</div>';
            
            $content[] = '<div class="' . $this->html_class_prefix . 'question-wrap-expanded-action">';
               
                $content[] = '<div class="' . $this->html_class_prefix . 'question-header">';

                $content[] = '<div class="' . $this->html_class_prefix . 'question-header-content">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-title">' . Survey_Maker_Data::ays_autoembed( $question_title );
                    $content[] = '</div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-description">' . Survey_Maker_Data::ays_autoembed( $question_description );
                $content[] = '</div>';

                    $content[] = $expand_collapse_question_content;
                $content[] = '</div>';

                if( isset( $question['image'] ) && $question['image'] != "" ){
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-image-container">';
                        $surve_question_image_alt_text = Survey_Maker_Data::ays_survey_get_image_id_by_url($question['image']);
                        $content[] = '<img class="' . $this->html_class_prefix . 'question-image" src="' . $question['image'] . '" alt="' . $surve_question_image_alt_text . '" '.$is_lazy_loading.' />';
                        if($image_caption_enable){
                            $content[] = '<div class="' . $this->html_class_prefix . 'question-image-caption">';
                                $content[] = '<span>'.$image_caption.'</span>';
                            $content[] = '</div>';
                        }
                    $content[] = '</div>';
                }
                $content[] = '</div>';

	    	// $content[] = '<div class="' . $this->html_class_prefix . 'question-header">';

            //     $content[] = '<div class="' . $this->html_class_prefix . 'question-title">' . Survey_Maker_Data::ays_autoembed( $question['question'] );
                
            //     if( $is_required ){
            //         $content[] = '<sup class="' . $this->html_class_prefix . 'question-required-icon">*</sup>';
            //     }

            //     $content[] = '</div>';

            //     if( isset( $question['image'] ) && $question['image'] != "" ){
            //         $content[] = '<div class="' . $this->html_class_prefix . 'question-image-container">';
            //             $content[] = '<img class="' . $this->html_class_prefix . 'question-image" src="' . $question['image'] . '" alt="' . stripslashes( esc_attr( $question['question'] ) ) . '" />';
            //         $content[] = '</div>';
            //     }

	    	// $content[] = '</div>';

                $content[] = '<div class="' . $this->html_class_prefix . 'question-content">';

                    $content[] = '<div class="' . $this->html_class_prefix . 'question-answers ' . $answer_grid . ' '.$yesorno_elegant_grid_view.'">';
                    
                        $content[] = $answers_html;

                        $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][section]" value="' . $question['section_id'] . '">';
                        $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][questionType]" value="' . $question_type . '">';
                        $content[] = '<input type="hidden" class="' . $this->html_class_prefix . 'question-id" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][questionId]" value="' . $question['id'] . '">';
                        $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][questionHideResults]" value="' . $enable_hide_results . '">';
                    
                    if( $this->options[ $this->name_prefix . 'enable_clear_answer' ] ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-clear-selection-container ' . $this->html_class_prefix . 'visibility-none transition fade">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'simple-button-container">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'button-content">';
                                    $content[] = '<span class="' . $this->html_class_prefix . 'answer-clear-selection-text ' . $this->html_class_prefix . 'button">' . $this->buttons_texts[ 'clearButton' ] . '</span>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';
                    }

                    $content[] = '</div>';
                    
                $content[] = '</div>';

                $content[] = '<div class="' . $this->html_class_prefix . 'question-footer">';

                    $textarea_class = "";
                    if( ! $this->options[ $this->name_prefix . 'is_minimal' ] ){
                        $textarea_class = $this->html_class_prefix . "remove-default-border " . $this->html_class_prefix . "question-input-textarea " . $this->html_class_prefix . "question-input " . $this->html_class_prefix . "input";
                    }

                    if( $user_explanation ){
                        $content[] = "<div class='" . $this->html_class_prefix . "user-explanation'>";
                            $content[] = "<textarea class='" . $textarea_class . " " . $this->html_name_prefix . "not-required-field " . $this->html_name_prefix . "answer-text-inputs' placeholder='" . __( 'You can enter your answer explanation', $this->plugin_name ) . "' name='" . $this->html_name_prefix . "user-explanation-" . $this->unique_id . "[" . $question['id'] . "][user_explanation]'></textarea>";
                            if( ! $this->options[ $this->name_prefix . 'is_minimal' ] ){
                                $content[] = '<div class="' . $this->html_class_prefix . 'input-underline" style="margin:0;"></div>';
                                $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation" style="margin:0;"></div>';
                            }
                        $content[] = "</div>";
                    }

                    if( $enable_admin_note && $admin_note != "" ){
                        $content[] = "<div class='" . $this->html_class_prefix . "admin-note-main'>
                            <div class='" . $this->html_class_prefix . "admin-note-inner'><span class='" . $this->html_class_prefix . "admin-note-text'>" . $admin_note . "</span></div>
                        </div>";
                    }

                    if($is_minimum == 'true'){
                        $content[] = '<div class="' . $this->html_class_prefix . 'votes-count-validation-error" role="alert"></div>';
                    }
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-validation-error" role="alert"></div>';
                    if(isset($this->options[ $this->name_prefix . 'logo' ]) && $this->options[ $this->name_prefix . 'logo' ] != ""){
                        $content[] = '<div class="' . $this->html_class_prefix . 'image-logo-url">
                                <a href="'.$survey_logo_url_validate.'" '.$survey_logo_target.' style="display: inline-block;'.$logo_cursor.'">
                                    <img src="'.$this->options[ $this->name_prefix . 'logo' ].'" class="' . $this->html_class_prefix . 'image-logo-url-img" title="'.$this->options[ $this->name_prefix . 'logo_title' ].'">
                                </a>
                            </div>';
                    }

                $content[] = '</div>';

            $content[] = '</div>';

    	$content[] = '</div>';

    	$content = implode( '', $content );

    	return $content;
    }

    public function ays_survey_question_type_RADIO_html( $answers ){
		
        $content = array();
        if(array_key_exists("questions",$answers)) {
            $question = ($answers["questions"]);
            $answers = $answers["answers"];
        }

        //checked Input
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $selected_input = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $business_checkmark_label_container = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
        $business_dashed_border = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'answer-business-hover' : '';
        $pointer_cursor_for_bussines = $this->options[ $this->name_prefix . 'is_business'] ? "style='cursor: pointer;'" : '';

        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer-for-radio-checkbox' : '';
        $elegant_theme_answers = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answers' : '';
        $elegant_theme_answer_hover = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'answer-elegant-hover' : '';

        $has_answer_image = false;
        foreach ($answers as $key => $answer) {
            if(isset( $answer['image'] ) && $answer['image'] != ""){
                $has_answer_image = true;
            }
        }

        $answer_grid = '';
        $answer_label_grid = '';
        if( $has_answer_image || $this->options[ $this->name_prefix . 'answers_view' ] == 'grid' ){
            $answer_grid = $this->html_class_prefix . 'answer-grid';
            $answer_label_grid = $this->html_class_prefix . 'answer-label-grid';
        }

        $is_first_question = $answers['is_first_question'];        
        $is_lazy_loading = ($is_first_question > 1) ? $this->lazy_loading : "";
        unset($answers['is_first_question']);

        foreach ($answers as $key => $answer) {
            
            $is_other = false;
            if( isset( $answer['is_other'] ) && $answer['is_other'] == true ){
                $is_other = true;
            }
        
            $answer_label_other = '';
            $other_answer_box_width = '';
            if( $is_other ){
                $answer_label_other = $this->html_class_prefix . 'answer-label-other';
                $other_answer_box_width = $this->html_class_prefix . 'other-answer-container';
            }

            $is_checked = ($key + 1) == $selected_input ? "checked" : "";
            $elegant_theme_style_for_other_answer = ( $this->options[ $this->name_prefix . 'is_elegant'] && $is_other ) ? $this->html_class_prefix . 'elegant-theme-style-for-other-answer' : '';
            // if($this->options[ $this->name_prefix . 'is_business']){
            $content[] = '<'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer ' . $answer_grid . ' ' . $business_dashed_border . ' '.$elegant_theme_answer.' '.$elegant_theme_answer_hover.' '.$elegant_theme_style_for_other_answer.' '.$other_answer_box_width.'" '.$pointer_cursor_for_bussines.'>';
            // }
            // else{
            //     $content[] = '<div class="' . $this->html_class_prefix . 'answer ' . $answer_grid . ' ' . $business_dashed_border . ' '.$elegant_theme_answer.' '.$elegant_theme_answer_hover.' '.$elegant_theme_style_for_other_answer.' '.$other_answer_box_width.'">';                
            // }

            // if($this->options[ $this->name_prefix . 'is_business']){
                $content[] = '<'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer-label ' . $answer_label_grid . ' ' . $answer_label_other . ' '.$business_checkmark_label_container.'" tabindex="0">';
            // }
            // else{
            //     $content[] = '<label class="' . $this->html_class_prefix . 'answer-label ' . $answer_label_grid . ' ' . $answer_label_other . ' '.$business_checkmark_label_container.'" tabindex="0">';
            // }
            
                
                    if( $this->options[$this->name_prefix . 'is_minimal'] ){
                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-image-container">';
                                $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                            $content[] = '</div>';
                        }
                        if (!$is_other) {
                            $content[] = '<input class="" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]" value="' . $answer['id'] . '"' . $is_checked . '>';
                        } else {
                            $content[] = '<input class="" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]" data-logicjump="' . $answer['question_id'] . '" value="' . $answer['id'] . '"' . $is_checked . '>';
                        }
                    }
                    else if( $this->options[ $this->name_prefix . 'is_business' ] ){
                        $checkmark_top = '';
                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-image-container">';
                                $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                            $content[] = '</div>';
                            // $checkmark_top = 'top: '. intval( $this->options[ $this->name_prefix . 'answers_image_size' ] + 22 ) .'px';
                        }

                        if (!$is_other) {
                            $content[] = '<input class="' . $this->html_class_prefix . 'business-theme-answers" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]" value="' . $answer['id'] . '" ' . $is_checked . '>';
                        } else {
                            $content[] = '<input class="' . $this->html_class_prefix . 'business-theme-answers" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]"  data-logicjump="' . $answer['question_id'] . '" value="' . $answer['id'] . '" ' . $is_checked . ' >';
                        }
                        $checkmark_other_class = '';

                        if( $is_other ){
                            $checkmark_other_class = $this->html_name_prefix . 'maker-checkmark-other';
                        }
                        $content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark '.$checkmark_other_class.'" style="'.$checkmark_top.'"></span>';
                    }
                    else{
                        if (!$is_other) {
                            $content[] = '<input class="'.$elegant_theme_answers.'" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]" value="' . $answer['id'] . '" ' . $is_checked . '>';
                        } else {
                            $content[] = '<input class="'.$elegant_theme_answers.'" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]" data-logicjump="' . $answer['question_id'] . '" value="' . $answer['id'] . '" ' . $is_checked . '>';
                        }

                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-image-container">';
                                $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                            $content[] = '</div>';
                        }
                    }

                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        if( $is_other ){
                            $content[] = '<span class="">' . __( 'Other', $this->plugin_name ) . ':</span>';
                        }else{
                            if( ! empty( $this->options[ $this->name_prefix . 'numbering_array' ] ) ){
                                $numebering_answer = $this->options[ $this->name_prefix . 'numbering_array' ][$key] . ' ';
                            }else{
                                $numebering_answer = '';
                            }
                            $content[] = '<span class="">' . $numebering_answer . $answer['answer'] . '</span>';
                        }

                    $content[] = '</div>';
                    // if($this->options[ $this->name_prefix . 'is_business']){
                        $content[] = '</'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).'>';
                    // }
                    // else{
                        // $content[] = '</label>';
                    // }

                if( $is_other ){

                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-other-text">';
                        $content[] = '<input id="' . $this->html_class_prefix . 'answer-other-input-' . $answer['question_id'] . '" class="' . $this->html_class_prefix . 'answer-other-input ' .
                                        $this->html_class_prefix . 'remove-default-border ' . 
                                        $this->html_class_prefix . 'question-input ' . 
                                        $this->html_class_prefix . 'input
                                        ' . $this->html_class_prefix . 'answer-text-inputs
                                        ' . $this->html_class_prefix . 'answer-text-inputs-default" 
                                        name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][other]" 
                                        type="text" autocomplete="off" tabindex="0" />';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline" style="margin:0;"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation" style="margin:0;"></div>';
                    $content[] = '</div>';
                    
                }

            $content[] = '</'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).'>';
            // if($this->options[ $this->name_prefix . 'is_business']){
            //     $content[] = '</label>';
            // }
            // else{
            //     $content[] = '</div>';
            // }
        }
        
    	$content = implode( '', $content );

    	return $content;
    }

    public function ays_survey_question_type_CHECKBOX_html( $answers ){
        $content = array();

        if(array_key_exists("questions",$answers)) {
            $question = ($answers["questions"]);
            $answers = $answers["answers"];
        }

        //checked Input
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $selected_input = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? explode(",", $_GET[$url_parameter]) : '';

        $business_checkmark_label_container = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
        $business_dashed_border = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'answer-business-hover' : '';
        $pointer_cursor_for_bussines = $this->options[ $this->name_prefix . 'is_business'] ? "style='cursor: pointer;'" : '';
        
        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer-for-radio-checkbox' : '';
        $elegant_theme_answers = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answers' : '';
        $elegant_theme_answer_hover = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'answer-elegant-hover' : '';

        $has_answer_image = false;
        foreach ($answers as $key => $answer) {
            if(isset( $answer['image'] ) && $answer['image'] != ""){
                $has_answer_image = true;
            }
        }

        $answer_grid = '';
        $answer_label_grid = '';
        if( $has_answer_image || $this->options[ $this->name_prefix . 'answers_view' ] == 'grid' ){
            $answer_grid = $this->html_class_prefix . 'answer-grid';
            $answer_label_grid = $this->html_class_prefix . 'answer-label-grid';
        }

        $is_first_question = $answers['is_first_question'];
        $is_lazy_loading = ($is_first_question > 1) ? $this->lazy_loading : "";
        unset($answers['is_first_question']);


        foreach ($answers as $key => $answer) {
            
            $is_other = false;
            if( isset( $answer['is_other'] ) && $answer['is_other'] == true ){
                $is_other = true;
            }
        
            $answer_label_other = '';
            $other_answer_box_width = '';
            if( $is_other ){
                $answer_label_other = $this->html_class_prefix . 'answer-label-other';
                $other_answer_box_width = $this->html_class_prefix . 'other-answer-container';
            }

            if($selected_input) {
                $is_checked = in_array(($key + 1), $selected_input) ? "checked" : "";
            } else {
                $is_checked = "";
            }
            
            $elegant_theme_style_for_other_answer = ( $this->options[ $this->name_prefix . 'is_elegant'] && $is_other ) ? $this->html_class_prefix . 'elegant-theme-style-for-other-answer' : '';

            $content[] = '<'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer ' . $answer_grid . ' '.$business_dashed_border.' '.$elegant_theme_answer.' '.$elegant_theme_answer_hover.' '.$elegant_theme_style_for_other_answer.'  '.$other_answer_box_width.'" '.$pointer_cursor_for_bussines.'">';

            
                $content[] = '<'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer-label ' . $answer_label_grid . ' ' . $answer_label_other . ' '.$business_checkmark_label_container.'" tabindex="0">';

                    if( $this->options[$this->name_prefix . 'is_minimal'] ){
                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-image-container">';
                                $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                            $content[] = '</div>';
                        }

                        $content[] = '<input class="" type="checkbox" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer][]" value="' . $answer['id'] . '"' . $is_checked . '>';
                    }
                    else if( $this->options[ $this->name_prefix . 'is_business' ] ){
                        $checkmark_top = '';
                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-image-container">';
                                $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                            $content[] = '</div>';
                            // $checkmark_top = 'top: '. intval($this->options[ $this->name_prefix . 'answers_image_size' ] + 22) .'px';
                        }

                        $content[] = '<input class="' . $this->html_class_prefix . 'business-theme-answers" type="checkbox" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer][]" value="' . $answer['id'] . '">';
                        $checkmark_other_class = '';
                        if( $is_other ){
                            $checkmark_other_class = $this->html_name_prefix . 'maker-checkmark-other';
                        }
                        $content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-checkbox '.$checkmark_other_class.'" style="'.$checkmark_top.'"></span>';
                    }
                    else{
                        $content[] = '<input class="'.$elegant_theme_answer.' '.$elegant_theme_answers.'" type="checkbox" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer][]" value="' . $answer['id'] . '" ' . $is_checked . '>';

                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-image-container">';
                                $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                            $content[] = '</div>';
                        }
                    }

                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';
                
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';
                        
                        if( $is_other ){
                            $content[] = '<span class="">' . __( 'Other', $this->plugin_name ) . ':</span>';
                        }else{
                            if( ! empty( $this->options[ $this->name_prefix . 'numbering_array' ] ) ){
                                $numebering_answer = $this->options[ $this->name_prefix . 'numbering_array' ][$key] . ' ';
                            }else{
                                $numebering_answer = '';
                            }
                            $content[] = '<span class="">' . $numebering_answer . $answer['answer'] . '</span>';
                        }

                    $content[] = '</div>';
                $content[] = '</'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).'>';

                if( $is_other ){

                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-other-text">';
                        $content[] = '<input id="' . $this->html_class_prefix . 'answer-other-input-' . $answer['question_id'] . '" class="' . $this->html_class_prefix . 'answer-other-input ' .
                                        $this->html_class_prefix . 'remove-default-border ' . 
                                        $this->html_class_prefix . 'question-input ' . 
                                        $this->html_class_prefix . 'input
                                        ' . $this->html_class_prefix . 'answer-text-inputs
                                        ' . $this->html_class_prefix . 'answer-text-inputs-default"
                                        name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][other]" 
                                        type="text" autocomplete="off" tabindex="0" />';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline" style="margin:0;"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation" style="margin:0;"></div>';
                    $content[] = '</div>';

                }

            $content[] = '</'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).'>';
        }

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_SELECT_html( $question ){
        $content = array();

        $is_first_question = $question['answers']['is_first_question'];
        $is_lazy_loading = ($is_first_question > 1) ? $this->lazy_loading : "";
        unset($question['answers']['is_first_question']);

        //selected Input
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : '';
        $selected_input = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';
        $is_default = $selected_input ? "" : "default ";

        $business_answer = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-answer' : '';
        $select_class = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'question-select-container-business' : $this->html_class_prefix . 'question-select-conteiner-minimal';
        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer' : '';
        if( $this->options[$this->name_prefix . 'is_minimal'] || $this->options[ $this->name_prefix . 'is_business']){
            $content[] = '<div class="' . $this->html_class_prefix . 'answer">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-type-select-box '.$elegant_theme_answer.'">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-select-conteiner">';
                        $content[] = '<select class="' . $select_class  . '"
                        name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . ']">';
                        foreach ( $question['answers'] as $key => $answer ) {
                            $is_selected = ($key + 1) == $selected_input ? "selected" : "";

                                $content[] = "<option " . $is_selected . " value='" . $answer['id'] . "' >";
                                    if( ! empty( $this->options[ $this->name_prefix . 'numbering_array' ] ) ){
                                        $numebering_answer = $this->options[ $this->name_prefix . 'numbering_array' ][$key] . ' ';
                                    }else{
                                        $numebering_answer = '';
                                    }
                                    $content[] = $numebering_answer . stripslashes( $answer['answer'] );
                                $content[] = '</option>';
                            }
                        $content[] = '</select>';
                    $content[] = '</div>';
                $content[] = '</div>';    
            $content[] = '</div>';
        }else{
            $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

                $content[] = '<div class="' . $this->html_class_prefix . 'question-type-select-box">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-select-conteiner">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'question-select ui selection icon dropdown">';
                            
                        $content[] = '<i class="dropdown icon"></i>';
                        $content[] = '<div class="' . $is_default . 'text">'.__($selected_input ? $question['answers'][$selected_input - 1]['answer'] : "Choose", $this->plugin_name).'</div>';

                            $content[] = '<div class="menu">';

                                foreach ( $question['answers'] as $key => $answer ) {
                                    $is_active = ($key + 1) == $selected_input ? "active selected" : "";
                                    if($is_active) {
                                        $answer_id =  $answer['id'];
                                    } else {
                                        $answer_id = '';
                                    }

                                    $content[] = '<div class="item ' . $is_active . '" data-value="'. $answer['id'] .'">';

                                        if( isset( $answer['image'] ) && $answer['image'] != "" ){
                                            $content[] = '<img class="' . $this->html_class_prefix . 'answer-image" '. $is_lazy_loading .' src="' . $answer['image'] . '" alt="' . stripslashes( $answer['answer'] ) . '" />';
                                        }

                                        if( ! empty( $this->options[ $this->name_prefix . 'numbering_array' ] ) ){
                                            $numebering_answer = $this->options[ $this->name_prefix . 'numbering_array' ][$key] . ' ';
                                        }else{
                                            $numebering_answer = '';
                                        }

                                        $content[] = $numebering_answer . stripslashes( $answer['answer'] );

                                    $content[] = '</div>';
                                }

                                    $content[] = '</div>';
                                $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . ']" value="' . $answer_id . '">';
                            $content[] = '</div>';

                    $content[] = '</div>';
                $content[] = '</div>';

            $content[] = '</div>';
        }

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_TEXT_html( $question ){
        $content = array();

        // Input types placeholders
        $survey_input_type_placeholder = isset($question['options']['placeholder']) && $question['options']['placeholder'] != "" ? $question['options']['placeholder'] : '';

        //Input types value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $enable_word_limit = isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on" ? true : false;  
        $show_limit  = isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == "on" ? true : false;  
        $limit_length  = isset($question['options']['limit_length']) && $question['options']['limit_length'] != "" ? $question['options']['limit_length'] : "";  
        $limit_by = "Character";
        $limit_checker = false;
        $survey_question_limit_length_class = '';
        if($enable_word_limit ){
            if($show_limit){
                $limit_checker = true;
            }
            $survey_question_limit_length_class = $this->html_class_prefix . 'check-word-limit ';
        }
        if($question['options']['limit_by'] && $question['options']['limit_by'] == "word"){
            $limit_by = "Word";
        }
        if(intval($limit_length) > 0){
            $limit_by .= "s ";
        }
        $limit_by .= __("left", $this->plugin_name);
        if(intval($limit_length) <= 0){
            $limit_by = '';
        }
        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-input-textarea ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input";
        }

        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer' : '';

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box ' . $this->html_class_prefix . 'question-type-text-box '.$elegant_theme_answer.'">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<textarea class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input '.$survey_question_limit_length_class.' ' . $this->html_class_prefix . 'answer-text-inputs" type="text" style="min-height: 24px;"
                                    placeholder="'. __( $survey_input_type_placeholder, $this->plugin_name ) .'"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]">';
                    $content[] =  __( $survey_input_type_value, $this->plugin_name );
                    $content[] = '</textarea>';
                    
                    if( ! $minimal_theme ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }

                $content[] = '</div>';
                if($limit_checker){
                    $content[] .= '<div class="'.$this->html_class_prefix.'question-text-conteiner">';
                        $content[] .= '<div class="'.$this->html_class_prefix.'question-text-message">';
                            $content[] .= '<span class="'.$this->html_class_prefix.'question-text-message-span">'. $limit_length . '</span> ' . $limit_by;
                        $content[] .= '</div>';
                    $content[] .= '</div>';
                }

            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_SHORT_TEXT_html( $question ){
        $content = array();

        // Input types placeholders
        $survey_input_type_placeholder = isset($question['options']['placeholder']) && $question['options']['placeholder'] != "" ? $question['options']['placeholder'] : '';

        //Input types value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : '';
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $enable_word_limit = isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] ? true : false;  
        $show_limit  = isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == "on" ? true : false;  
        $limit_length  = isset($question['options']['limit_length']) && $question['options']['limit_length'] != "" ? $question['options']['limit_length'] : "";  
        $limit_by = "Character";
        $limit_checker = false;
        $survey_question_limit_length_class = '';
        if($enable_word_limit ){
            if($show_limit){
                $limit_checker = true;
            }
            $survey_question_limit_length_class = $this->html_class_prefix . 'check-word-limit ';
        }
        if($question['options']['limit_by'] && $question['options']['limit_by'] == "word"){
            $limit_by = "Word";
        }
        if(intval($limit_length) > 0){
            $limit_by .= "s ";
        }
        $limit_by .= __("left", $this->plugin_name);
        if(intval($limit_length) <= 0){
            $limit_by = '';
        }

        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' .$this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input";
        }

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input '.$survey_question_limit_length_class.' ' . $this->html_class_prefix . 'answer-text-inputs ' . $this->html_class_prefix . 'answer-text-inputs-default" type="text" style="min-height: 24px;"
                                    placeholder="'. __( $survey_input_type_placeholder, $this->plugin_name ) .'"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"
                                    value="' . __( $survey_input_type_value, $this->plugin_name ) . '">';
                    
                    if( ! $minimal_theme && ! $this->options[ $this->name_prefix . 'is_business' ]){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }
                $content[] = '</div>';

                if($limit_checker){
                    $content[] .= '<div class="'.$this->html_class_prefix.'question-text-conteiner">';
                        $content[] .= '<div class="'.$this->html_class_prefix.'question-text-message">';
                            $content[] .= '<span class="'.$this->html_class_prefix.'question-text-message-span">'. $limit_length . '</span> ' . $limit_by;
                        $content[] .= '</div>';
                    $content[] .= '</div>';
                }

            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_NUMBER_html( $question ){
        $content = array();
        // Input types placeholders
        $survey_input_type_placeholder = isset($question['options']['placeholder']) && $question['options']['placeholder'] != "" ? $question['options']['placeholder'] : '';

        //Input types value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';
        
        $enable_number_limit = isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on" ? true : false;
        $enable_number_limit_message = isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on" ? true : false;
        $number_limit_message = isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != "" ? stripslashes(esc_attr($question['options']['number_error_message'])) : "";
        $show_limit  = isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on" ? true : false;  
        $limit_length  = isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "" ? $question['options']['number_limit_length'] : "";  
        $limit_by = "Character";
        $limit_checker = false;

        $number_limit_class = "";
        if($enable_number_limit){
            if($show_limit){
                $limit_checker = true;
            }

            $number_limit_class = $this->html_class_prefix . 'check-number-limit ';            
        }

        if(intval($limit_length) > 0){
            $limit_by .= "s ";
        }

        $limit_by .= __("left", $this->plugin_name);
        if(intval($limit_length) <= 0){
            $limit_by = '';
        }



        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input";
        }

        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer' : '';

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box '.$elegant_theme_answer.'">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input '.$number_limit_class.' ' . $this->html_class_prefix . 'answer-text-inputs-default" type="number" step="any" style="min-height: 24px;"
                                    placeholder="'. __( $survey_input_type_placeholder, $this->plugin_name ) .'"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"
                                    value="' . __( $survey_input_type_value, $this->plugin_name ) . '">';

                    if( ! $minimal_theme && ! $this->options[ $this->name_prefix . 'is_business' ] ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }

                    if($enable_number_limit_message){
                        $content[] = '<div class="' . $this->html_class_prefix . 'number-limit-message-box ' . $this->html_class_prefix . 'question-text-error-message" style="display: none;">';
                            $content[] = '<span class="' . $this->html_class_prefix . 'number-limit-message-text">';
                                $content[] = $number_limit_message;
                            $content[] = '</span>';
                        $content[] = '</div>';
                    }

                    if($limit_checker){
                        $content[] .= '<div class="'.$this->html_class_prefix.'question-text-conteiner">';
                            $content[] .= '<div class="'.$this->html_class_prefix.'question-text-message">';
                                $content[] .= '<span class="'.$this->html_class_prefix.'question-text-message-span">'. $limit_length . '</span> ' . $limit_by;
                            $content[] .= '</div>';
                        $content[] .= '</div>';
                    }

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_PHONE_html( $question ){
        $content = array();
        // Input types placeholders
        $survey_input_type_placeholder = isset($question['options']['placeholder']) && $question['options']['placeholder'] != "" ? $question['options']['placeholder'] : '';

        //Input types value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $enable_number_limit = isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on" ? true : false;
        $enable_number_limit_message = isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on" ? true : false;
        $number_limit_message = isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != "" ? stripslashes(esc_attr($question['options']['number_error_message'])) : "";
        $show_limit  = isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on" ? true : false;  
        $limit_length  = isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "" ? $question['options']['number_limit_length'] : "";  
        $limit_by = "Character";
        $limit_checker = false;

        $number_limit_class = $this->html_class_prefix . 'is-phone-type ';
        if($enable_number_limit){
            if($show_limit){
                $limit_checker = true;
            }

            $number_limit_class .= $this->html_class_prefix . 'check-number-limit ';
        }

        if(intval($limit_length) > 0){
            $limit_by .= "s ";
        }

        $limit_by .= __("left", $this->plugin_name);
        if(intval($limit_length) <= 0){
            $limit_by = '';
        }



        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input";
        }

        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer' : '';

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box '.$elegant_theme_answer.'">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input '.$number_limit_class.' ' . $this->html_class_prefix . 'answer-text-inputs ' . $this->html_class_prefix . 'answer-text-inputs-default" type="text" tabindex="0" step="any" style="min-height: 24px;"
                                    placeholder="'. __( $survey_input_type_placeholder, $this->plugin_name ) .'"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"
                                    value="' . __( $survey_input_type_value, $this->plugin_name ) . '">';

                    if( ! $minimal_theme && ! $this->options[ $this->name_prefix . 'is_business' ] ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }

                    if($enable_number_limit_message){
                        $content[] = '<div class="' . $this->html_class_prefix . 'number-limit-message-box ' . $this->html_class_prefix . 'question-text-error-message" style="display: none;">';
                            $content[] = '<span class="' . $this->html_class_prefix . 'number-limit-message-text">';
                                $content[] = $number_limit_message;
                            $content[] = '</span>';
                        $content[] = '</div>';
                    }

                    if($limit_checker){
                        $content[] .= '<div class="'.$this->html_class_prefix.'question-text-conteiner">';
                            $content[] .= '<div class="'.$this->html_class_prefix.'question-text-message">';
                                $content[] .= '<span class="'.$this->html_class_prefix.'question-text-message-span">'. $limit_length . '</span> ' . $limit_by;
                            $content[] .= '</div>';
                        $content[] .= '</div>';
                    }

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_EMAIL_html( $question ){
        $content = array();

        //Input value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';
        
        // Input types placeholders
        $survey_input_type_placeholder = isset($question['options']['placeholder']) && $question['options']['placeholder'] != "" ? $question['options']['placeholder'] : '';

        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-email-input ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input " . $this->html_class_prefix . "question-email-input";
        }
        
        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer' : '';

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box '.$elegant_theme_answer.'">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input ' . $this->html_class_prefix . 'answer-text-inputs-default" type="text" style="min-height: 24px;"
                                    placeholder="'. __( $survey_input_type_placeholder, $this->plugin_name ) .'"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"
                                    value="' . __( $survey_input_type_value, $this->plugin_name ) . '">';
                    if( true ){
                        $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'user-email-' . $this->unique_id . '" value="' . $question['id'] . '" >';
                    }

                    if( ! $minimal_theme ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_NAME_html( $question ){
        $content = array();

        //Input value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        // Input types placeholders
        $survey_input_type_placeholder = isset($question['options']['placeholder']) && $question['options']['placeholder'] != "" ? $question['options']['placeholder'] : '';

        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input";
        }

        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer' : '';

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input ' . $this->html_class_prefix . 'answer-text-inputs ' . $this->html_class_prefix . 'answer-text-inputs-default" type="text" style="min-height: 24px;"
                                    placeholder="'. __( $survey_input_type_placeholder, $this->plugin_name ) .'"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"
                                    value="' . __( $survey_input_type_value, $this->plugin_name ) . '">';

                    if( true ){
                        $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'user-name-' . $this->unique_id . '" value="' . $question['id'] . '" >';
                    }

                    if( ! $minimal_theme && ! $this->options[ $this->name_prefix . 'is_business' ] ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_STAR_html( $question ){
        $content = array();
    
        //checked Input
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $selected_input = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $star_label_1 = (isset($question['options']['star_1']) && $question['options']['star_1'] != '') ? $question['options']['star_1'] : '';
        $star_label_2 = (isset($question['options']['star_2']) && $question['options']['star_2'] != '') ? $question['options']['star_2'] : '';
        $star_scale_length = (isset($question['options']['star_scale_length']) && $question['options']['star_scale_length'] != '') ? absint( $question['options']['star_scale_length'] ) : 5;

        $business_star_answer = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-star-answer' : '';

        $elegant_star_list_font_size =  $this->options[ $this->name_prefix . 'is_elegant'] ? '30px ' : '25px';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-star '.$business_star_answer.'">';
            
                $content[] = '<label class="' . $this->html_class_prefix . 'answer-star-label">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio-label" dir="auto"></div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio">';
                    $content[] = $star_label_1;
                    $content[] = '</div>';
                $content[] = '</label>';

                for ($i=1; $i <= $star_scale_length; $i++) { 
                    $is_selected = $i == $selected_input ? "checked" : "";
                    $active_answer = $i == $selected_input ? "active-answer" : "";
                    $selected_stars = ($i <= $selected_input) && ($selected_input <= $star_scale_length) ? "ays-fa-star" : "ays-fa-star-o";

                    $content[] = '<label class="' . $this->html_class_prefix . 'answer-label ' . $active_answer . '">';
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio-label" dir="auto"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio" tabindex="0">';
                        
                            $content[] = '<input type="radio" value="'.$i.'" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"' . $is_selected . '>';
                            if($this->options[ $this->name_prefix . 'is_business']){
                                $content[] = '<i class="ays-fa ' . $selected_stars . ' ' . $this->html_class_prefix . 'star-icon" style=" font-size: '.$elegant_star_list_font_size.';color:#ffffff;"></i>';
                            }else{                                
                                $content[] = '<i class="ays-fa ' . $selected_stars . ' ' . $this->html_class_prefix . 'star-icon" style=" font-size: '.$elegant_star_list_font_size.';"></i>';
                            }
                            
                        $content[] = '</div>';
                    $content[] = '</label>';
                }
                
                $content[] = '<label class="' . $this->html_class_prefix . 'answer-star-label">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio-label" dir="auto"></div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio">';
                    $content[] = $star_label_2;
                    $content[] = '</div>';
                $content[] = '</label>';

            $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_LINEAR_SCALE_html( $question ){
        $content = array();

        //checked Input
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $selected_input = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';
    
        $linear_scale_label_1 = (isset($question['options']['linear_scale_1']) && $question['options']['linear_scale_1'] != '') ? stripslashes(esc_attr($question['options']['linear_scale_1'])) : '';
        $linear_scale_label_2 = (isset($question['options']['linear_scale_2']) && $question['options']['linear_scale_2'] != '') ? stripslashes(esc_attr($question['options']['linear_scale_2'])) : '';
        $linear_scale_length = (isset($question['options']['scale_length']) && $question['options']['scale_length'] != '') ? absint($question['options']['scale_length']) : 5;

        $business_checkmark_label_container = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
        $business_dashed_border = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'answer-business-hover' : '';

        $elegant_theme_answer_hover = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'answer-elegant-hover' : '';
        $elegant_theme_answers = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answers' : '';

        $scale_length_for_width = array(
            '3'  => 71,
            '4'  => 68,
            '5'  => 61,
            '6'  => 57,
            '7'  => 52,
            '8'  => 47,
            '9'  => 43,
            '10' => 40,
        );

        $scale_length_for_left = array(
            '3'  => 58,
            '4'  => 45,
            '5'  => 43,
            '6'  => 30,
            '7'  => 29,
            '8'  => 32,
            '9'  => 27,
            '10' => 28,
        );

        $elegant_theme_linear_length = 'style="width: calc('.$linear_scale_length.'*'.$scale_length_for_width[$linear_scale_length].'px); left: '.$scale_length_for_left[$linear_scale_length].'px"';

            $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale">';
                $content[] = '<label class="' . $this->html_class_prefix . 'answer-linear-scale-label">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale-radio-label" dir="auto"></div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale-radio">';
                    $content[] = $linear_scale_label_1;
                    $content[] = '</div>';
                $content[] = '</label>';

                if($this->options[ $this->name_prefix . 'is_elegant']){
                    $content[] = '<div class="' . $this->html_class_prefix . 'elegant-answer-linear-scale-line-content">';
                        $content[] = '<div class="' . $this->html_class_prefix . 'elegant-answer-linear-scale-line" '.$elegant_theme_linear_length.'>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                }

                
                for ($i=1; $i <= $linear_scale_length; $i++) { 

                    $is_checked = ($i) == $selected_input ? "checked" : "";

                    $content[] = '<label class="' . $this->html_class_prefix . 'answer-label '.$business_checkmark_label_container.'">';
                        
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale-radio-label" dir="auto">' . $i . '</div>';

                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale-radio '.$business_dashed_border.' '.$elegant_theme_answer_hover.'"  tabindex="0">';

                            if( $this->options[ $this->name_prefix . 'is_business'] ){
                                $content[] = '<input type="radio" class="' . $this->html_class_prefix . 'business-theme-answers" value="'.$i.'" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]" ' . $is_checked . '>';
                                $content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-linear-scale"></span>';
                            }else{                                
                                $content[] = '<input type="radio" value="'.$i.'" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]" ' . $is_checked . ' class="'.$elegant_theme_answers.'">';
                            }

                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';
        
                                $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                            $content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                        $content[] = '</div>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
        
                            $content[] = '</div>';
                        $content[] = '</div>';
                    $content[] = '</label>';
                }

                $content[] = '<label class="' . $this->html_class_prefix . 'answer-linear-scale-label">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale-radio-label" dir="auto"></div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-linear-scale-radio">';
                    $content[] = $linear_scale_label_2;
                    $content[] = '</div>';
                $content[] = '</label>';
            $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_DATE_html( $question ){
        $content = array();

        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-date-input ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input " . $this->html_class_prefix."minimal-theme-input-date";
        }

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box ' . $this->html_class_prefix . 'question-date-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box ' . $this->html_class_prefix . 'question-date-input-box">';

                    $content[] = '<input type="date" class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input" type="text" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]"  tabindex="0">';

                    if( ! $minimal_theme && ! $this->options[ $this->name_prefix . 'is_business' ] ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    }

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_TIME_html( $question ){
        $content = array();

        // $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-date-input ' . $this->html_class_prefix . 'question-input';
        // if( $minimal_theme ){
        //     $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input " . $this->html_class_prefix."minimal-theme-input-date";
        // }

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box ' . $this->html_class_prefix . 'question-time-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box ' . $this->html_class_prefix . 'question-time-input-box">';

                    $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input ays-survey-timepicker" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]" placeholder="00:00" tabindex="0">';

                    // if( ! $minimal_theme ){
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                        $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                    // }

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_DATE_TIME_html( $question ){
        $content = array();

        // $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-date-input ' . $this->html_class_prefix . 'question-input';
        // if( $minimal_theme ){
        //     $minimal_class = $this->html_class_prefix . "minimal-theme-textarea-input " . $this->html_class_prefix."minimal-theme-input-date";
        // }

        $minimal_theme = $this->options[ $this->name_prefix . 'is_minimal' ] ? true : false;
        $minimal_date_class = $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'question-date-input ' . $this->html_class_prefix . 'question-input';
        if( $minimal_theme ){
            $minimal_date_class = $this->html_class_prefix . "minimal-theme-textarea-input " . $this->html_class_prefix."minimal-theme-input-date";
        }

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box ' . $this->html_class_prefix . 'question-date-time-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'date-time-inner-box">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box ' . $this->html_class_prefix . 'question-date-input-box">';

                        $content[] = '<input type="date" class="' . $minimal_date_class . ' ' . $this->html_class_prefix . 'input" type="text"
                                        name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer][date]">';

                        if( ! $minimal_theme ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                        }

                    $content[] = '</div>';
                    $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box ' . $this->html_class_prefix . 'question-time-input-box">';

                        $content[] = '<input class="' . $minimal_class . ' ' . $this->html_class_prefix . 'input ays-survey-timepicker" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer][time]" placeholder="00:00" tabindex="0">';

                        // if( ! $minimal_theme ){
                            $content[] = '<div class="' . $this->html_class_prefix . 'input-underline"></div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation"></div>';
                        // }

                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }
    
    public function ays_survey_question_type_MATRIX_SCALE_html( $args ){
        $content = array();
        $answers = isset($args['answers']) ? $args['answers'] : array(); 
        $questions = isset($args['questions']) ? $args['questions'] : array();
        $questions_options = isset($questions['options']) && !empty($questions['options']) ? $questions['options'] : array();
        $question_columns = array();
        $business_checkmark_label_container = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
        $business_dashed_border = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'answer-business-hover' : '';
        $pointer_cursor_for_bussines = $this->options[ $this->name_prefix . 'is_business'] ? "style='cursor: pointer;'" : '';

        if( isset( $questions_options['matrix_columns'] ) ){
            if( is_array( $questions_options['matrix_columns'] ) ){
                $question_columns = $questions_options['matrix_columns'];
            }else{
                $question_columns = json_decode($questions_options['matrix_columns'], true);
            }
        }

        if(!is_array($question_columns)){
            $question_columns = array();
        }
        unset($answers['is_first_question']);
        $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-main">';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-container">';
                $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column ' . $this->html_class_prefix . 'answer-matrix-scale-column-row-header"></div>';
                    foreach($question_columns as $q_key => $q_value){
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column">' . $q_value . '</div>';
                    }

                $content[] = "</div>";

                $row_spacer = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row-spacer"></div>';
                $content[] = $row_spacer;

                $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer-for-radio-checkbox' : '';
                $elegant_theme_answers = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answers' : '';
                $elegant_theme_answer_hover = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'answer-elegant-hover' : '';

                $rows = array();
                foreach($answers as $a_key => $a_value){
                    $rows_content = array();
                    if($this->options[ $this->name_prefix . 'is_elegant']){
                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row">';
                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row-content">';
                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column ' . $this->html_class_prefix . 'answer-matrix-scale-column-row-header">' . $a_value['answer'] . '</div>';
                                foreach($question_columns as $q_key => $q_val){
                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column">';
                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column-content-wrap '.$business_dashed_border.' '.$elegant_theme_answer_hover.' '.$elegant_theme_answer.'">';
                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column-content">';

                                                $rows_content[] = '<label class="' . $this->html_class_prefix . 'answer-label ' . $this->html_class_prefix . 'answer-label-matrix-row '.$business_checkmark_label_container.'" tabindex="0">';
                                                        if( $this->options[ $this->name_prefix . 'is_business'] ){

                                                            $rows_content[] = '<input class="' . $this->html_name_prefix . 'business-theme-answers" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].']" value="' . $q_key . '">';
                                                            $rows_content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-matrix-scale"></span>';
                                                        }else{
                                                            $rows_content[] = '<input class="'.$elegant_theme_answers.'" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].']" value="' . $q_key . '">';
                                                        }
                                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';
                                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                                                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                                                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                                                    $rows_content[] = '</div>';
                                                                $rows_content[] = '</div>';
                                                            $rows_content[] = '</div>';
                                                        $rows_content[] = '</div>';
                                                $rows_content[] = '</label>';

                                            $rows_content[] = '</div>';
                                        $rows_content[] = '</div>';
                                    $rows_content[] = '</div>';
                                }
                            $rows_content[] = '</div>';
                        $rows_content[] = '</div>';
                    }else{
                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row">';
                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row-content">';
                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column ' . $this->html_class_prefix . 'answer-matrix-scale-column-row-header">' . $a_value['answer'] . '</div>';
                                foreach($question_columns as $q_key => $q_val){
                                    $rows_content[] = '<'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer-matrix-scale-column" '.$pointer_cursor_for_bussines.'>';
                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column-content-wrap '.$business_dashed_border.' '.$elegant_theme_answer_hover.' '.$elegant_theme_answer.'">';
                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column-content">';
    
                                                $rows_content[] = '<'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer-label ' . $this->html_class_prefix . 'answer-label-matrix-row '.$business_checkmark_label_container.'" tabindex="0">';
                                                        if( $this->options[ $this->name_prefix . 'is_business'] ){
    
                                                            $rows_content[] = '<input class="' . $this->html_name_prefix . 'business-theme-answers" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].']" value="' . $q_key . '">';
                                                            $rows_content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-matrix-scale"></span>';
                                                        }else{
                                                            $rows_content[] = '<input class="'.$elegant_theme_answers.'" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].']" value="' . $q_key . '">';
                                                        }
                                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';
                                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                                                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                                                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                                                    $rows_content[] = '</div>';
                                                                $rows_content[] = '</div>';
                                                            $rows_content[] = '</div>';
                                                        $rows_content[] = '</div>';
                                                $rows_content[] = '</'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).'>';
    
                                            $rows_content[] = '</div>';
                                        $rows_content[] = '</div>';
                                    $rows_content[] = '</'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).'>';
                                }
                            $rows_content[] = '</div>';
                        $rows_content[] = '</div>';
                    }

                    $rows[] = implode( '', $rows_content );
                }

                $content[] = implode(  $row_spacer, $rows );
        
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_MATRIX_SCALE_CHECKBOX_html( $args ){
        $content = array();
        $answers = isset($args['answers']) ? $args['answers'] : array(); 
        $questions = isset($args['questions']) ? $args['questions'] : array();
        $questions_options = isset($questions['options']) && !empty($questions['options']) ? $questions['options'] : array();
        $question_columns = array();

        $business_checkmark_label_container = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
        $business_dashed_border = $this->options[ $this->name_prefix . 'is_business'] ? $this->html_class_prefix . 'answer-business-hover' : '';
        $pointer_cursor_for_bussines = $this->options[ $this->name_prefix . 'is_business'] ? "style='cursor: pointer;'" : '';
        
        $elegant_theme_answer = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answer-for-radio-checkbox' : '';
        $elegant_theme_answers = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'elegant-theme-answers' : '';
        $elegant_theme_answer_hover = $this->options[ $this->name_prefix . 'is_elegant'] ? $this->html_class_prefix . 'answer-elegant-hover' : '';

        if( isset( $questions_options['matrix_columns'] ) ){
            if( is_array( $questions_options['matrix_columns'] ) ){
                $question_columns = $questions_options['matrix_columns'];
            }else{
                $question_columns = json_decode($questions_options['matrix_columns'], true);
            }
        }

        if(!is_array($question_columns)){
            $question_columns = array();
        }
        
        $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-main">';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-container">';
                $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row">';

                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column ' . $this->html_class_prefix . 'answer-matrix-scale-column-row-header"></div>';
                    foreach($question_columns as $q_key => $q_value){
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column">' . $q_value . '</div>';
                    }

                $content[] = "</div>";

                $row_spacer = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row-spacer"></div>';
                $content[] = $row_spacer;
                
                $rows = array();
                unset($answers['is_first_question']);
                foreach($answers as $a_key => $a_value){
                    $rows_content = array();
                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row">';
                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-row-content">';
                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column ' . $this->html_class_prefix . 'answer-matrix-scale-column-row-header">' . $a_value['answer'] . '</div>';
                            foreach($question_columns as $q_key => $q_val){
                                $rows_content[] = '<'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer-matrix-scale-column" '.$pointer_cursor_for_bussines.'>';
                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column-content-wrap '.$business_dashed_border.' '.$elegant_theme_answer_hover.'">';
                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-matrix-scale-column-content">';

                                            $rows_content[] = '<'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).' class="' . $this->html_class_prefix . 'answer-label ' . $this->html_class_prefix . 'answer-label-matrix-row '.$business_checkmark_label_container.'" tabindex="0">';
                                                if( $this->options[ $this->name_prefix . 'is_business'] ){
                                                    $rows_content[] = '<input class="' . $this->html_class_prefix . 'matrix-scale-checbox-inputs ' . $this->html_name_prefix . 'business-theme-answers" type="checkbox" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].'][]" value="' . $q_key . '">';
                                                    $rows_content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-linear-scale-checkbox"></span>';
                                                }else{
                                                    $rows_content[] = '<input class="' . $this->html_class_prefix . 'matrix-scale-checbox-inputs '.$elegant_theme_answer.' '.$elegant_theme_answers.'" type="checkbox" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].'][]" value="' . $q_key . '">';
                                                }
                                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-label-content">';
                                                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content">';
                                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-ink"></div>';
                                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-1">';
                                                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-2">';
                                                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-icon-content-3"></div>';
                                                                $rows_content[] = '</div>';
                                                            $rows_content[] = '</div>';
                                                        $rows_content[] = '</div>';
                                                    $rows_content[] = '</div>';
                                            $rows_content[] = '</'.$this->ays_survey_replace_open_tags_for_answers(!$this->options[ $this->name_prefix . 'is_business']).'>';

                                        $rows_content[] = '</div>';
                                    $rows_content[] = '</div>';
                                $rows_content[] = '</'.$this->ays_survey_replace_open_tags_for_answers($this->options[ $this->name_prefix . 'is_business']).'>';
                            }
                        $rows_content[] = '</div>';
                    $rows_content[] = '</div>';
                    $rows[] = implode( '', $rows_content );
                }

                $content[] = implode(  $row_spacer, $rows );
        
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_STAR_LIST_html( $args ){
        $content   = array();
        $answers   = isset($args['answers']) ? $args['answers'] : array(); 
        $questions_options = isset($args['options']) ? $args['options'] : array();
        $question_columns = array();

        $star_list_stars_length = (isset( $questions_options['star_list_stars_length'] )) ? $questions_options['star_list_stars_length'] : '5';
        $elegant_star_list_font_size =  $this->options[ $this->name_prefix . 'is_elegant'] ? '30px ' : '25px';
        $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-list-main">';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-star-list-container">';

                $row_spacer = '<div class="' . $this->html_class_prefix . 'answer-star-list-row-spacer"></div>';
                
                $rows = array();
                unset($answers['is_first_question']);
                foreach($answers as $a_key => $a_value){
                    $rows_content = array();
                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star-list-row">';
                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star-list-row-content">';
                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star-list-column ' . $this->html_class_prefix . 'answer-star-list-column-row-header ' . $this->html_class_prefix . 'answer-star-list-rows">' . $a_value['answer'] . '</div>';
                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star-list-column ' . $this->html_class_prefix . 'answer-star-list-column-row-header">';
                                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star">';
                                    for ($i = 1; $i <= $star_list_stars_length; $i++) { 
                                        $rows_content[] = '<label class="' . $this->html_class_prefix . 'answer-label ' . $this->html_class_prefix . 'answer-label-star-list">';
                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio-label" dir="auto"></div>';
                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-star-radio">';
                                            
                                                $rows_content[] = '<input type="radio" value="'.$i.'" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $args['id'] . '][answer]['.$a_value['id'].']" tabindex="0">';
                                                if($this->options[ $this->name_prefix . 'is_business']){
                                                    $rows_content[] = '<i class="ays-fa ays-fa-star ' . $this->html_class_prefix . 'star-icon" style=" font-size: '.$elegant_star_list_font_size.';color:#ffffff"></i>';
                                                }
                                                else{
                                                    $rows_content[] = '<i class="ays-fa ays-fa-star-o ' . $this->html_class_prefix . 'star-icon" style=" font-size: '.$elegant_star_list_font_size.';"></i>';
                                                }
                                            $rows_content[] = '</div>';
                                        $rows_content[] = '</label>';
                                    }                            
                                    $rows_content[] = '</div>';
                                $rows_content[] = '</div>';
                        $rows_content[] = '</div>';
                    $rows_content[] = '</div>';
                    $rows[] = implode( '', $rows_content );
                }

                $content[] = implode(  $row_spacer, $rows );
        
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_SLIDER_LIST_html( $question ){
        $content   = array();
        $answers = isset($question['answers']) ? $question['answers'] : array(); 
        $range_length      = isset($question['options']['slider_list_range_length']) && $question['options']['slider_list_range_length'] != "" ? esc_attr(intval($question['options']['slider_list_range_length'])) : 100;
        if($range_length == 0){
            $range_length = 100;
        }
        $range_step_length = isset($question['options']['slider_list_range_step_length']) && $question['options']['slider_list_range_step_length'] != "" ? esc_attr(intval($question['options']['slider_list_range_step_length'])) : 1;
        if($range_step_length == 0){
            $range_step_length = 1;
        }
        
        $range_type_min_value     = (isset( $question['options']['slider_list_range_min_value'] ) && $question['options']['slider_list_range_min_value'] != '') ? esc_attr(intval($question['options']['slider_list_range_min_value'])) : 0;
        $range_type_default_value = (isset( $question['options']['slider_list_range_default_value'] ) && $question['options']['slider_list_range_default_value'] != '') ? esc_attr(intval($question['options']['slider_list_range_default_value'])) : 0;
        $range_calculation_type = (isset( $question['options']['slider_list_range_calculation_type'] ) && $question['options']['slider_list_range_calculation_type'] != '') ? esc_attr($question['options']['slider_list_range_calculation_type']) : 'seperatly';
        if($range_length == $range_type_min_value){
            $range_length = 100;
            $range_type_min_value = 0;
            $range_step_length = 1;
            $range_type_default_value = 0;
        }
        $left = ($range_type_default_value - $range_type_min_value) * 390 / ($range_length - $range_type_min_value) - $range_type_default_value/$range_length;
        if($range_type_default_value < ($range_length + $range_type_min_value) / 2){
            $left += 8;
        }
        else{
            $left -= 5;
        }

        if($range_type_default_value < $range_type_min_value){
            $range_type_default_value = $range_type_min_value;
        }

        $content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-main" data-calc-type="'.$range_calculation_type.'" data-max-length="'.$range_length.'">';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-container">';

                $content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-row">';

                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-column ' . $this->html_class_prefix . 'answer-slider-list-column-row-header"></div>';

                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-column" style="text-align:right; padding:0 10px;width:300px;">';
                        $content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-min-max-val">';
                        $content[] = __("Min ".$range_type_min_value , $this->plugin_name);
                        $content[] = " / ";
                        $content[] = __("Max ".$range_length , $this->plugin_name);
                        $content[] = "</div>";
                    $content[] = '</div>';            

                $content[] = "</div>";

                $row_spacer = '<div class="' . $this->html_class_prefix . 'answer-slider-list-row-spacer"></div>';
                $content[] = $row_spacer;
                
                $rows = array();
                unset($answers['is_first_question']);
                foreach($answers as $a_key => $a_value){
                    
                    if($range_type_default_value < $range_type_min_value){
                        $range_type_default_value = $range_type_min_value;
                    }
                    $rows_content = array();
                    $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-row"">';
                        $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-row-content">';
                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-column ' . $this->html_class_prefix . 'answer-slider-list-column-row-header ' . $this->html_class_prefix . 'answer-slider-list-rows">' . $a_value['answer'] . '</div>';
                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-slider-list-column ' . $this->html_class_prefix . 'answer-slider-list-column-row-header ' . $this->html_class_prefix . 'answer-slider-list-column-row-header-only-slider" >';                    
                                            $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-main">';
                                                $rows_content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-range"" tabindex="0">';
                                                    $rows_content[] = '<span class="' . $this->html_class_prefix . 'answer-range-type-info-text" style="left: '.($left).'px;">'.$range_type_default_value.'</span>';
                                                    $rows_content[] = '<input type="range" tabindex="0" class="' . $this->html_class_prefix . 'range-type-input ' . $this->html_class_prefix . 'range-type-input-for-required" min="'.$range_type_min_value.'" max="'.$range_length.'" step="'.$range_step_length.'" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $a_value['question_id'] . '][answer]['.$a_value['id'].']" value="'.$range_type_default_value.'">';
                                                $rows_content[] = '</div>';
                                            $rows_content[] = '</div>';
                            $rows_content[] = '</div>';
                        $rows_content[] = '</div>';
                    $rows_content[] = '</div>';
                    $rows[] = implode( '', $rows_content );
                    
                    
                }

                $content[] = implode(  $row_spacer, $rows );
        
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_RANGE_html( $question ){
        $content = array();

        //checked Input
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $selected_default_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $range_length      = isset($question['options']['range_length']) && $question['options']['range_length'] != "" ? esc_attr(intval($question['options']['range_length'])) : 100;
        if($range_length == 0){
            $range_length = 100;
        }
        $range_step_length = isset($question['options']['range_step_length']) && $question['options']['range_step_length'] != "" ? esc_attr(intval($question['options']['range_step_length'])) : 1;
        if($range_step_length == 0){
            $range_step_length = 1;
        }
        
        $range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? esc_attr(intval($question['options']['range_min_value'])) : 0;
        $range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? esc_attr(intval($question['options']['range_default_value'])) : 0;
        $content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-main">';
        if($range_length == $range_type_min_value){
            $range_length = 100;
            $range_type_min_value = 0;
            $range_step_length = 1;
            $range_type_default_value = 0;
        }
        $left = ($range_type_default_value - $range_type_min_value) * 390 / ($range_length - $range_type_min_value) - $range_type_default_value/$range_length;
        if($range_type_default_value < ($range_length + $range_type_min_value) / 2){
            $left += 8;
        }
        else{
            $left -= 5;
        }

        if($range_type_default_value < $range_type_min_value){
            $range_type_default_value = $range_type_min_value;
        }

        if($selected_default_value) {
            $range_type_default_value = $selected_default_value;
        }
            
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-min-max-val">';
                $content[] = __("Min ".$range_type_min_value , $this->plugin_name);
                $content[] = "</div>";
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-range" tabindex="0">';
                $content[] = '<span class="' . $this->html_class_prefix . 'answer-range-type-info-text" style="left: '.($left).'px;">'.$range_type_default_value.'</span>';
                $content[] = '<input type="range" class="' . $this->html_class_prefix . 'range-type-input" min="'.$range_type_min_value.'" max="'.$range_length.'" step="'.$range_step_length.'" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]" value="'.$range_type_default_value.'" data-type="just-range" tabindex="0">';
            $content[] = '</div>';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-range-type-min-max-val">';
                $content[] = __("Max ".$range_length , $this->plugin_name);
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_question_type_UPLOAD_html( $question ){
        $content = array();
        $file_max_size = "5";
        $is_required = "false";
        if(isset($question['options'])){
            $file_max_size = isset($question['options']['file_upload_types_size']) && $question['options']['file_upload_types_size'] != "" ? $question['options']['file_upload_types_size'] : "5";
            $is_required = isset($question['options']['required']) && $question['options']['required'] == 1 ? "true" : "false";
        }
        
        $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-type-main" data-required="'.$is_required.'">';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-ready" style="display: none;">';
                $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-ready-link-box">';
                    $content[] = '<a class="' . $this->html_class_prefix . 'answer-upload-ready-link"></a>';
                    $content[] = '<input class="' . $this->html_class_prefix . 'answer-upload-ready-url-link" type="hidden" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]">';
                $content[] = '</div>';
                $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-ready-image-box">';
                    $content[] = '<img src="'. SURVEY_MAKER_ADMIN_URL.'/images/icons/close.svg" style="width:20px;">';
                $content[] = '</div>';
            $content[] = '</div>';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-type">';
                $content[] = '<label class="' . $this->html_class_prefix . 'answer-upload-type-label">';
                    $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-type-button" tabindex="0">'; 
                        $content[] = '<input type="file" class="' . $this->html_class_prefix . 'answer-upload-type-file" hidden>'; 
                        $content[] = '<img src="'. SURVEY_MAKER_ADMIN_URL.'/images/icons/import.svg" style="width:20px;">';
                        $content[] = '<span class="' . $this->html_class_prefix . 'answer-upload-type-text">'.__("Add file", $this->plugin_name).'</span>';
                    $content[] = '</div>';
                $content[] = '</label>';
            $content[] = '</div>';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-type-error-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-validation-error-upload ays-survey-answer-upload-type-error-type">';
                    $content[] = '<img src="'.SURVEY_MAKER_PUBLIC_URL.'/images/warning.svg" style="margin-right: 10px;">';
                    $content[] = '<span>'.__("This type of file isn't allowed" , $this->plugin_name).'</span>';
                $content[] = '</div>';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-validation-error-upload ays-survey-answer-upload-type-error-size">';
                    $content[] = '<img src="'.SURVEY_MAKER_PUBLIC_URL.'/images/warning.svg" style="margin-right: 10px;">';
                    $content[] = '<span>'.sprintf(__("The file size must be up to %s MB" , $this->plugin_name) , $file_max_size).'</span>';
                $content[] = '</div>';
            $content[] = '</div>';
        $content[] = '</div>';
        $content[] = '<div class="' . $this->html_class_prefix . 'answer-upload-type-loader" style="display: none;">';
            $content[] = '<img src="'. SURVEY_MAKER_ADMIN_URL.'/images/loaders/tail-spin.svg" style="width:20px;">';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;

    }

    public function ays_survey_question_type_HIDDEN_html( $question ){
        $content = array();

        //Input types value
        $enable_url_parameter =  isset($question['options']['enable_url_parameter']) && $question['options']['enable_url_parameter'] == true ? true : false;
        $url_parameter = $enable_url_parameter && isset($question['options']['url_parameter']) && $question['options']['url_parameter'] != "" ? $question['options']['url_parameter'] : ''; 
        $survey_input_type_value = isset($_GET[$url_parameter]) && $_GET[$url_parameter] ? $_GET[$url_parameter] : '';

        $content[] = '<div class="' . $this->html_class_prefix . 'answer">';

            $content[] = '<div class="' . $this->html_class_prefix . 'question-box ' . $this->html_class_prefix . 'question-type-text-box">';
                $content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';

                    $content[] = '<textarea class="' . $this->html_class_prefix . 'input ' . $this->html_class_prefix . 'answer-text-inputs" type="text" style="min-height: 24px;"
                                    name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]">';
                    $content[] =  __( $survey_input_type_value, $this->plugin_name );
                    $content[] = '</textarea>';

                $content[] = '</div>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;

    }

    public function ays_survey_question_type_HTML_html($question){
        if(isset($question['options'])){
            $html_types_content = (isset( $question['options']['html_types_content'] ) && $question['options']['html_types_content'] != '') ? Survey_Maker_Data::ays_autoembed($question['options']['html_types_content']) : "";
        }       

        $content[] = '<div class="' . $this->html_class_prefix . 'answer-html-type-main">';
            $content[] = '<div class="' . $this->html_class_prefix . 'answer-html-type-content">';
                $content[] = $html_types_content;
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function create_restricted_content( $limit_message ){
		
		$content = array();
    	$content[] = '<div class="' . $this->html_class_prefix . 'section ' . $this->html_class_prefix . 'restricted-content active-section">';

            $content[] = '<div class="' . $this->html_class_prefix . 'section-header">';
            
                $content[] = '<div class="' . $this->html_class_prefix . 'restricted-message">';
                    $content[] = $limit_message;
                $content[] = '</div>';

	    	$content[] = '</div>';
    	
    	$content[] = '</div>';

    	$content = implode( '', $content );

    	return $content;
    }

    public function get_styles(){
		
		$content = array();
        $content[] = '<style type="text/css">';


        $question_image_width = '100%';
        if( $this->options[ $this->name_prefix . 'question_image_width' ] != '' ){
            $question_image_width = $this->options[ $this->name_prefix . 'question_image_width' ] . 'px';
        }

        $question_image_height = 'auto';
        if( $this->options[ $this->name_prefix . 'question_image_height' ] != '' ){
            $question_image_height = $this->options[ $this->name_prefix . 'question_image_height' ] . 'px';
        }

        $survey_title_box_shadow_class = '';
        if( isset($this->options[ $this->name_prefix . 'title_box_shadow_enable' ]) && $this->options[ $this->name_prefix . 'title_box_shadow_enable' ] ){
            $survey_title_text_shadow_params = $this->options[ $this->name_prefix . 'title_text_shadow_x_offset' ].'px '.$this->options[ $this->name_prefix . 'title_text_shadow_y_offset' ].'px '.$this->options[ $this->name_prefix . 'title_text_shadow_z_offset' ].'px';
            $survey_title_box_shadow_class = 'text-shadow : '.$survey_title_text_shadow_params.' '.$this->options[ $this->name_prefix . 'title_box_shadow_color' ].';';
        }

        $survey_pagination_positioning = isset($this->options[ $this->name_prefix . 'pagination_positioning' ]) ? $this->options[ $this->name_prefix . 'pagination_positioning' ] : 'none';
        $pagination_positioning = "row";
        $pagination_number_height = "";
        switch ($survey_pagination_positioning) {
            case 'none':
                $pagination_positioning = "row";
            case 'reverse':
                $pagination_positioning = "row-reverse";
                break;
            case 'column':
                $pagination_positioning = "column";
                $pagination_number_height = "line-height: 1;";
                break;
            case 'column_reverse':
                $pagination_positioning = "column-reverse";
                $pagination_number_height = "line-height: 1;";
            break;
            default:
                $pagination_positioning = "row";
                $pagination_number_height = "";
                break;
        }

        $filtered_survey_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'color' ] );
        $filtered_survey_button_bg_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'buttons_bg_color' ] );
        $filtered_text_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'text_color' ] );
        $filtered_background_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'background_color' ] );
        $filtered_button_text_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'buttons_text_color' ] );

        $filtered_slider_question_bubble_bg_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'slider_question_bubble_bg_color' ] );
        $filtered_slider_question_bubble_text_color = Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'slider_question_bubble_text_color' ] );        

        $width = $this->options[ $this->name_prefix . 'width' ];
        $width_by = $this->options[ $this->name_prefix . 'width_by_percentage_px' ];

        $mobile_width = $this->options[ $this->name_prefix . 'mobile_width' ];
        $mobile_width_by = $this->options[ $this->name_prefix . 'mobile_width_by_percent_px' ];
        $mobile_max_width = $this->options[ $this->name_prefix . 'mobile_max_width' ];
        
        if( absint( $width ) == 0 ){
            $width = '100';
            $width_by = 'percentage';
        }

        if( absint( $mobile_width ) == 0 ){
            $mobile_width = '100';
            $mobile_width_by = 'percentage';
        }

        if( absint( $mobile_max_width ) > 0 ){
            $mobile_max_width .= '%';
        }else{
            $mobile_max_width = '95%';
        }

        switch( $width_by ){
            case 'percentage':
                $width .= '%';
            break;
            case 'pixels':
                $width .= 'px';
            break;
            default:
                $width .= '%';
            break;
        }

        switch( $mobile_width_by ){
            case 'percentage':
                $mobile_width .= '%';
            break;
            case 'pixels':
                $mobile_width .= 'px';
            break;
            default:
                $mobile_width .= '%';
            break;
        }

        switch($this->options[ $this->name_prefix . 'logo_image_position' ]){
            case "right":
                $survey_logo_image_position = "right: 5px;";
                break;
            case "left":
                $survey_logo_image_position = "left: 5px;";
                break;
            case "center":
                $survey_logo_image_position = "left: 0;right: 0;margin: auto;";
                break;
            default:
                $survey_logo_image_position = "right: 5px;";
                break;
        }

        switch($this->options[ $this->name_prefix . 'grid_view_count' ]){
            case "2":
                $survey_grid_view_by_count = 'width: calc(('.$width.')/2 - 2%);';
            break;
            case "3":
                $survey_grid_view_by_count = 'width: calc(('.$width.')/3 - 2%);';
            break;
            case "4":
                $survey_grid_view_by_count = 'width: calc(('.$width.')/4 - 2%)';
                break;
            case "adaptive":
            default:
                $survey_grid_view_by_count = '';
            break;
        }

        $answers_list_width = '';
        $answers_list_direction = "";
        if($this->options[ $this->name_prefix . 'answers_view_alignment' ] == 'center' && $this->options[ $this->name_prefix . 'answers_view' ] == 'list'){
            $answers_list_width = "width:50%;";
            $answers_list_direction = "flex-direction: column;";
        }

        $other_answer_box_width = "";
        if($this->options[ $this->name_prefix . 'answers_view_alignment' ] == 'flex-start' && $this->options[ $this->name_prefix . 'answers_view' ] == 'list'){
            $other_answer_box_width = "width: 100%;";
        }

        $answers_grid_min_width = '';
        if($this->options[ $this->name_prefix . 'answers_view_alignment' ] == 'flex-start' && $this->options[ $this->name_prefix . 'answers_view' ] == 'grid'){
            $answers_grid_min_width = "min-width: 50%;";
        }

        // Question padding
        $question_padding = $this->options[ $this->name_prefix . 'question_padding' ];
        
        $content[] = '
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' {
                width: ' . $width . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-header {
                border-top-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question {
                border-left-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
                border-right-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question {
                ' . ( $this->options[ $this->name_prefix . 'logo' ] != '' ? 'padding: '.$question_padding.'px '.$question_padding.'px 0 '.$question_padding.'px;' : 'padding: '.$question_padding.'px;' ) . '
                ' . ( $this->options[ $this->name_prefix . 'logo' ] != '' ? 'padding-bottom: 50px;' : '' ) . '
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'question-input {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px;
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-header,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-terms-and-conditions-container,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question {
                background-color: ' . $this->options[ $this->name_prefix . 'background_color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section.' . $this->html_class_prefix . 'section-start-page .' . $this->html_class_prefix . 'section-header {
                background-color: ' . $this->options[ $this->name_prefix . 'start_page_background_color' ] . ';
                color: ' . $this->options[ $this->name_prefix . 'start_page_text_color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section.' . $this->html_class_prefix . 'section-start-page .' . $this->html_class_prefix . 'section-header *,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section.' . $this->html_class_prefix . 'section-start-page .' . $this->html_class_prefix . 'section-header .' . $this->html_class_prefix . 'section-title-row {
                color: ' . $this->options[ $this->name_prefix . 'start_page_text_color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'question-input ~ .' . $this->html_class_prefix . 'input-underline,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'question-input ~ .' . $this->html_class_prefix . 'input-underline-animation,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'simple-button-container,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label-content > span,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.item,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'thank-you-page,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'loader-with-text,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'thank-you-page a.ays-survey-current-page-link-a-tag,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'restricted-message,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-desc,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions-count,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-title,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-html-type-content,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-terms-and-conditions-container,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-min-max-val,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-clear-selection-container .' . $this->html_class_prefix . 'button-content span.' . $this->html_class_prefix . 'answer-clear-selection-text.' . $this->html_class_prefix . 'button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-title-row {
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections' . ' .' . $this->html_class_prefix . 'section-questions' . ' .' . $this->html_class_prefix . 'question' . ' .' . $this->html_class_prefix . 'question-answers' . ' .' . $this->html_class_prefix . 'answer-star' . ' .' . $this->html_class_prefix . 'star-icon {
                color: ' . $this->options[ $this->name_prefix . 'stars_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections' . ' .' . $this->html_class_prefix . 'section-questions' . ' .' . $this->html_class_prefix . 'question' . ' .' . $this->html_class_prefix . 'answer-html-type-main{
                max-width: 100%;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-title {
                text-align: ' . $this->options[ $this->name_prefix . 'question_title_alignment' ] . ';
            }

            ';
            $content[] = '
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="checkbox"] ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-3,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="radio"] ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-3,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="checkbox"]:checked ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-2,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="radio"]:checked ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-2,
            #'.$this->html_class_prefix.'container-'.$this->unique_id_in_class.' .'.$this->html_class_prefix.'answer-label .'.$this->html_class_prefix.'answer-image-container{
                border-color:' . ( $this->options[ $this->name_prefix . 'is_modern' ] ? $this->options[ $this->name_prefix . 'buttons_bg_color' ] : $this->options[ $this->name_prefix . 'color' ] ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="checkbox"] ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-2,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="radio"] ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-2 {
                border-color: ' . ( $this->options[ $this->name_prefix . 'is_modern' ] ? $this->options[ $this->name_prefix . 'buttons_bg_color' ] : $this->options[ $this->name_prefix . 'text_color' ] ) . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'input-underline-animation,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' textarea.' . $this->html_class_prefix . 'question-input:focus ~ .' . $this->html_class_prefix . 'input-underline-animation,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'input:focus ~ .' . $this->html_class_prefix . 'input-underline-animation {
                background-color: ' . ( $this->options[ $this->name_prefix . 'is_modern' ] ? $this->options[ $this->name_prefix . 'text_color' ] : $this->options[ $this->name_prefix . 'color' ] ) . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button {
                color: ' . $this->options[ $this->name_prefix . 'buttons_text_color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label:hover .' . $this->html_class_prefix . 'answer-icon-ink,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export {
                background-color: ' . Survey_Maker_Data::hex2rgba( $filtered_survey_color, 0.04 ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export {
                color: ' . ( $this->options[ $this->name_prefix . 'is_minimal' ] ? $this->options[ $this->name_prefix . 'text_color' ] : $this->options[ $this->name_prefix . 'color' ] ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-required-icon,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-title {
                font-size: ' . $this->options[ $this->name_prefix . 'question_font_size' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-description {
                font-size: 13px;
                color: #8f8f8f;
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-image {
                width: ' . $question_image_width . ';
                height: ' . $question_image_height . ';
                object-fit: ' . $this->options[ $this->name_prefix . 'question_image_sizing' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-image-caption {
                text-align: ' . $this->options[ $this->name_prefix . 'question_caption_text_alignment' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label-content > span,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-column,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-slider-list-column,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star-list-column {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px;
                line-height: 1;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer {
                padding: ' . $this->options[ $this->name_prefix . 'answers_padding' ] . 'px ' . $this->options[ $this->name_prefix . 'answers_padding' ] . 'px ' . $this->options[ $this->name_prefix . 'answers_padding' ] . 'px 0;
                margin: ' . $this->options[ $this->name_prefix . 'answers_gap' ] . 'px ' . $this->options[ $this->name_prefix . 'answers_gap' ] . 'px ' . $this->options[ $this->name_prefix . 'answers_gap' ] . 'px 0;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-image {
                object-fit: ' . $this->options[ $this->name_prefix . 'answers_object_fit' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button {
                font-size: ' . $this->options[ $this->name_prefix . 'buttons_font_size' ] . 'px;
                padding-left: ' . $this->options[ $this->name_prefix . 'buttons_left_right_padding' ] . 'px;
                padding-right: ' . $this->options[ $this->name_prefix . 'buttons_left_right_padding' ] . 'px;
                padding-top: ' . $this->options[ $this->name_prefix . 'buttons_top_bottom_padding' ] . 'px;
                padding-bottom: ' . $this->options[ $this->name_prefix . 'buttons_top_bottom_padding' ] . 'px;
                background-color: ' . $this->options[ $this->name_prefix . 'buttons_bg_color' ] . ';
                height: initial;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container {
                border-radius: ' . $this->options[ $this->name_prefix . 'buttons_border_radius' ] . 'px;
                background-color: ' . $this->options[ $this->name_prefix . 'background_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.text,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.item {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px !important;
                font-weight: normal;                
                padding: 5px;
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.item {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px !important;
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star .' . $this->html_class_prefix . 'answer-star-radio,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star .' . $this->html_class_prefix . 'answer-star-radio-label,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-linear-scale .' . $this->html_class_prefix . 'answer-linear-scale-radio,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-linear-scale .' . $this->html_class_prefix . 'answer-linear-scale-radio-label {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px !important;
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }';
            $content[] = '
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .ays-survey-loader-snake[data-role="loader"] div,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'loader div.' . $this->html_class_prefix . 'loader-text {
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-ripple[data-role="loader"] div{
                border-color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-dual-ring[data-role="loader"]::after,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-hourglass[data-role="loader"]::after{
                border-color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ' transparent ' . $this->options[ $this->name_prefix . 'text_color' ] . ' transparent;
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-default[data-role="loader"] div,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-ellipsis[data-role="loader"] div,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-facebook[data-role="loader"] div,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .lds-circle[data-role="loader"] {
                background-color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' textarea.' . $this->html_class_prefix . 'question-input {
                min-height: ' . $this->options[ $this->name_prefix . 'textarea_height' ] . 'px !important;
            }

            /*
             * Matrix scale question type
            */

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .' . $this->html_class_prefix . 'answer-matrix-scale-column,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-slider-list-main .' . $this->html_class_prefix . 'answer-slider-list-container .' . $this->html_class_prefix . 'answer-slider-list-row .' . $this->html_class_prefix . 'answer-slider-list-column,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star-list-main .' . $this->html_class_prefix . 'answer-star-list-container .' . $this->html_class_prefix . 'answer-star-list-row .' . $this->html_class_prefix . 'answer-star-list-column {
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }';
            $content[] = '

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-slider-list-main .' . $this->html_class_prefix . 'answer-slider-list-container .' . $this->html_class_prefix . 'answer-slider-list-row,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star-list-main .' . $this->html_class_prefix . 'answer-star-list-container .' . $this->html_class_prefix . 'answer-star-list-row {
                background-color: ' . Survey_Maker_Data::hex2rgba( $filtered_text_color, 0.04 ) . ';
            }

            /*
             * Range question type
             */
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'range-type-input::-webkit-slider-thumb {
                background-color: ' . $this->options[ $this->name_prefix . 'background_color' ] . ';
                border-color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'range-type-input.' . $this->html_class_prefix . 'range-type-input-disaled::-webkit-slider-thumb {
                background-color: #767676;
                border-color: #adadad;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'range-type-input::-moz-range-thumb {
                background-color: ' . $this->options[ $this->name_prefix . 'background_color' ] . ';
                border-color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'answer-range-type-info-text {
                background-color: ' . $this->options[ $this->name_prefix . 'slider_question_bubble_bg_color' ] . ';
                color: ' . $this->options[ $this->name_prefix . 'slider_question_bubble_text_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'answer-range-type-info-text:after {
                border-top-color: ' . $this->options[ $this->name_prefix . 'slider_question_bubble_bg_color' ] . ';
                background-color: ' . $this->options[ $this->name_prefix . 'slider_question_bubble_bg_color' ] . ';
            }

            /* Full screen option */
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'full-screen-mode .' . $this->html_class_prefix . 'close-full-screen,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'full-screen-mode .' . $this->html_class_prefix . 'open-full-screen {
                fill: '.$this->options[ $this->name_prefix . 'full_screen_button_color' ].';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-wrap {
                background-color: ' . (  $this->options[ $this->name_prefix . 'is_minimal' ] ? $this->options[ $this->name_prefix . 'text_color' ] : $this->options[ $this->name_prefix . 'color' ] ). ';
                border-color: ' . (  $this->options[ $this->name_prefix . 'is_minimal' ] ? $this->options[ $this->name_prefix . 'text_color' ] : $this->options[ $this->name_prefix . 'color' ] ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-fill {
                background-color: ' . (  $this->options[ $this->name_prefix . 'is_minimal' ] ? Survey_Maker_Data::ays_color_inverse( Survey_Maker_Data::rgb2hex( $this->options[ $this->name_prefix . 'text_color' ] ) ) : $this->options[ $this->name_prefix . 'background_color' ] ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-status .' . $this->html_class_prefix . 'live-bar-status-text {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px;
                color: ' . $this->options[ $this->name_prefix . 'pagination_text_color' ] . ';
                padding: 0 10px;
                letter-spacing: .2px;
                line-height: 30px;
                font-weight: normal;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'simple-button-container .' . $this->html_class_prefix . 'button-content .' . $this->html_class_prefix . 'button{
                color: ' . $this->options[ $this->name_prefix . 'buttons_text_color' ] . ';
            }

            /* Expand / collapse question */
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-collapse-img-icon-content svg,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-img-icon-content svg {
                fill: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'title {
                text-align: ' . $this->options[ $this->name_prefix . 'title_alignment' ] . ';
                font-size: ' . $this->options[ $this->name_prefix . 'title_font_size' ] . 'px;
                '. $survey_title_box_shadow_class .'
            }';
            $content[] = '

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'section-title-row-main {
                text-align: ' . $this->options[ $this->name_prefix . 'section_title_alignment' ] . ';
                font-size: ' . $this->options[ $this->name_prefix . 'section_title_font_size' ] . 'px;
                width: 100%;
                line-height: 1.5;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'answer-label .' . $this->html_class_prefix . 'answer-image-container {
                height: ' . $this->options[ $this->name_prefix . 'answers_image_size' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'cover-photo-title-wrap{
                height: ' . $this->options[ $this->name_prefix . 'cover_photo_height' ] . 'px;
                background-position: ' . implode(" ", explode("_" , $this->options[ $this->name_prefix . 'cover_photo_position' ])) .';
                background-size: ' . $this->options[ $this->name_prefix . 'cover_photo_object_fit' ] .';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'section-desc{
                text-align: ' . $this->options[ $this->name_prefix . 'section_description_alignment' ] . ';
                font-size: ' . $this->options[ $this->name_prefix . 'section_description_font_size' ] . 'px;
                line-height: 1;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'image-logo-url{
                '.$survey_logo_image_position.'
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'paypal-details-div,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'stripe-details-div{
                text-align: center;
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
                margin-bottom: 12px;
                box-sizing: border-box;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'paypal-details-div p{
                margin: 0;
            }';
            $content[] = '


            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'question-text-message,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'number-limit-message-box{
               color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
               text-align: left;
               font-size: 12px;
               padding-top: 10px;
            }

 
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'question-text-error-message {
                color: #ff0000;
            }
 
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'admin-note-inner span{
                color: ' . $this->options[ $this->name_prefix . 'admin_note_color' ] . ';
                text-transform:' . $this->options[ $this->name_prefix . 'admin_note_text_transform' ] . ';
                font-size: ' . $this->options[ $this->name_prefix . 'admin_note_font_size' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-main{
                flex-direction: '.$pagination_positioning.';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons{
                margin-top: ' . $this->options[ $this->name_prefix . 'buttons_top_distance' ] . 'px;
                text-align: ' . $this->options[ $this->name_prefix . 'buttons_alignment' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'results .' . $this->html_class_prefix . 'section-buttons{
                margin-top: 0;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question-content .' . $this->html_class_prefix . 'question-answers:not(.' . $this->html_class_prefix . 'question-answers-grid){
                align-items: ' . $this->options[ $this->name_prefix . 'answers_view_alignment' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question-content .' . $this->html_class_prefix . 'question-answers-grid{
                justify-content: ' . $this->options[ $this->name_prefix . 'answers_view_alignment' ] . ';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'answer{
                '.$answers_list_width.'
                '.$answers_list_direction.'
                '.$answers_grid_min_width.'
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question .' . $this->html_class_prefix . 'answer.' . $this->html_class_prefix . 'other-answer-container{
                '.$other_answer_box_width.'
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question[data-type="short_text"] .' . $this->html_class_prefix . 'answer,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question[data-type="text"] .' . $this->html_class_prefix . 'answer,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question[data-type="email"] .' . $this->html_class_prefix . 'answer,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question[data-type="name"] .' . $this->html_class_prefix . 'answer,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question[data-type="number"] .' . $this->html_class_prefix . 'answer,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question[data-type="phone"] .' . $this->html_class_prefix . 'answer{
                width: 100%;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer.' .$this->html_class_prefix.'answer-grid{
                '.$survey_grid_view_by_count.'
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question-image-caption span{
                color: '.$this->options[ $this->name_prefix . 'question_caption_text_color' ].';
                font-size: '.$this->options[ $this->name_prefix . 'question_caption_font_size' ].'px;
                text-transform: '.$this->options[ $this->name_prefix . 'question_caption_text_transform' ].';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . '.' . $this->html_class_prefix . 'container .' . $this->html_class_prefix . 'restricted-content.' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'section-header p,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . '.' . $this->html_class_prefix . 'container .' . $this->html_class_prefix . 'restricted-content .' . $this->html_class_prefix . 'section-header *{
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
            }
            
            ';

            if($this->options[ $this->name_prefix . 'enable_survey_start_loader' ]){
                $content[] = '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . '.' . $this->html_class_prefix . 'container {
                    min-height: 350px;
                }';
            }

            $content[] = $this->get_css_mobile_part($mobile_max_width, $mobile_width, $pagination_number_height);
            $content[] = '
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .ays-survey-chat-content {
                background-color: ' . $this->options[ $this->name_prefix . 'background_color' ] . ';
            }            

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .ays-survey-chat-content .ays-survey-chat-answer-label-content span {
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ';
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size' ] . 'px;
                line-height: 1;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'chat-header .' . $this->html_class_prefix . 'chat-question-title, 
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'chat-header .' . $this->html_class_prefix . 'chat-question-reply-title {
                font-size: ' . $this->options[ $this->name_prefix . 'question_font_size' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-upload-type-main .' . $this->html_class_prefix . 'answer-upload-ready-link {
                color: ' . $this->options[ $this->name_prefix . 'text_color' ] . ' !important;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-upload-type-main .' . $this->html_class_prefix . 'answer-upload-ready {
                border-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' label.' . $this->html_class_prefix . 'star-list-answer-submission-content-label > div.' . $this->html_class_prefix . 'star-list-answer-submission-content-div > i.ays-fa.ays_fa_star_o::before,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' label.' . $this->html_class_prefix . 'individual-submission-conatiner-star-label-stars div:nth-child(2) > i.ays-fa.ays_fa_star_o::before{
                content: "\f006";
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' label.' . $this->html_class_prefix . 'star-list-answer-submission-content-label > div.' . $this->html_class_prefix . 'star-list-answer-submission-content-div > i.ays-fa.ays_fa_star::before,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' label.' . $this->html_class_prefix . 'individual-submission-conatiner-star-label-stars div:nth-child(2) > i.ays-fa.ays_fa_star::before{
                content: "\f005";
            }';

            if( $this->options[ $this->name_prefix . 'is_minimal' ] ){
                $content[] = 
                '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'minimal-theme-header {
                    box-shadow: unset;
                    border: 0;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'minimal-theme-question {
                    border: 0;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content {
                    display: none;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input {
                    display: block ;
                    cursor: pointer;
                    outline: none;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label {
                    justify-content: initial;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label .' . $this->html_class_prefix . 'answer-star-radio input {
                    display: none ;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .' . $this->html_class_prefix . 'answer-matrix-scale-column-content,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-slider-list-main .' . $this->html_class_prefix . 'answer-slider-list-container .' . $this->html_class_prefix . 'answer-slider-list-row .' . $this->html_class_prefix . 'answer-slider-list-column-content,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star-list-main .' . $this->html_class_prefix . 'answer-star-list-container .' . $this->html_class_prefix . 'answer-star-list-row .' . $this->html_class_prefix . 'answer-matrix-scale-column-content {
                    width: initial;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input[type=range].' . $this->html_class_prefix . 'range-type-input {
                    -webkit-appearance: auto;
                    appearance: auto;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input[type=range].' . $this->html_class_prefix . 'range-type-input {
                    -moz-appearance: auto;
                    appearance: auto;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .'.$this->html_class_prefix.'minimal-theme-textarea-input {
                    border: 1px solid !important;
                    transition: 0;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select-conteiner-minimal {
                    width: 200px;
                    padding: 5px !important;
                    outline: none;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .'.$this->html_class_prefix.'question-date-input-box {
                    width: 200px;
                    height: 40px;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .'.$this->html_class_prefix.'question-date-input-box input {
                    width: 100%;
                    height: 100%;
                    box-sizing: border-box;
                    font-size: 15px;
                    padding: 10px;
                }';
            }

            if( $this->options[ $this->name_prefix . 'is_modern' ] ){
                $content[] = 
                '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'modern-theme-question{
                    border: none;
                    margin: 15px 0 0;
                    padding: '. ((isset($this->options[ $this->name_prefix . 'logo' ]) && $this->options[ $this->name_prefix . 'logo' ] != "") ? '0 0 50px 0': '1px 12px').';
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'modern-theme-question:not(:last-child){
                    border-radius: unset;
                }
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-footer{
                    margin-top:11px 12pxpx;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > div.' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'modern-theme-question:last-child{
                    border-bottom-left-radius: 5px;
                    border-bottom-right-radius: 5px;
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                }
                
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'modern-theme-header{
                    border-bottom-left-radius: 0;
                    border-bottom-right-radius: 0;
                    border-top-left-radius: 5px;
                    border-top-right-radius: 5px;
                    border: none;
                    box-shadow: none;
                    margin: 0;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'modern-theme-question .' . $this->html_class_prefix . 'question-header{
                    margin: 7px 0;
                }
 
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'modern-theme-question .' . $this->html_class_prefix .'question-content > .' . $this->html_class_prefix . 'question-answers > .' . $this->html_class_prefix . 'answer {
                    padding: 5px 0;
                    margin: 0px;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'modern-theme-header > .' . $this->html_class_prefix . 'results .' . $this->html_class_prefix . 'thank-you-page .' . $this->html_class_prefix . 'submission-summary-question-container, 
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'modern-theme-header > .' . $this->html_class_prefix . 'results .' . $this->html_class_prefix . 'thank-you-page .' . $this->html_class_prefix . 'submission-summary-section-header{
                    border: none;
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-title {
                    font-weight: bold;
                    margin: 0 !important;
                    text-align: ' . $this->options[ $this->name_prefix . 'question_title_alignment' ] . ';
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers > .' . $this->html_class_prefix . 'each-question-answer.' . $this->html_class_prefix . 'answer .ays_text_answer{
                    color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
                }
                
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container:hover .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button,
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export{
                    background-color: ' . Survey_Maker_Data::hex2rgba( $filtered_survey_button_bg_color, 0.8 ) . ';
                    color: '.$this->options[ $this->name_prefix . 'buttons_text_color' ].';
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-wrap {
                    background-color: ' . $this->options[ $this->name_prefix . 'buttons_bg_color' ]. ';
                    border-color: ' . $this->options[ $this->name_prefix . 'buttons_bg_color' ] . ';
                }
    
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-fill {
                    background-color: ' . $this->options[ $this->name_prefix . 'buttons_text_color' ] . ';
                }

                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'modern-theme-question .' . $this->html_class_prefix .'question-input{
                    background-color: unset;
                }

                ';
            }

            if( isset($this->options[ $this->name_prefix . 'cover_photo' ]) && $this->options[ $this->name_prefix . 'cover_photo' ] != "" ){
                $content[] = 
                '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .'.$this->html_class_prefix.'cover-photo-title-wrap {
                    background-image: url('.$this->options[ $this->name_prefix . 'cover_photo' ].');
                    background-repeat: no-repeat;
                }
                              
                #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .'.$this->html_class_prefix.'cover-photo-title-wrap .'.$this->html_class_prefix.'title {
                    display: flex;
                    align-items: flex-end;
                    height: 100%;
                }
                ';

                $title_alignment_with_cover_photo_css = 'flex-start';
                switch( $this->options[ $this->name_prefix . 'title_alignment' ] ){
                    case "left":
                        $title_alignment_with_cover_photo_css = 'flex-start';
                    break;
                    case "right":
                        $title_alignment_with_cover_photo_css = 'flex-end';
                    break;
                    case "center":
                        $title_alignment_with_cover_photo_css = 'center';
                    break;
                    default:
                        $title_alignment_with_cover_photo_css = 'flex-start';
                    break;
                }
                
                $content[] = 
                    '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .'.$this->html_class_prefix.'cover-photo-title-wrap .'.$this->html_class_prefix.'title {
                        justify-content: ' . $title_alignment_with_cover_photo_css . ';
                    }';
            }

            if( $this->options[ $this->name_prefix . 'is_business' ] ){
                $content[] = $this->get_business_theme_styles( $filtered_survey_color, $filtered_survey_button_bg_color, $filtered_text_color, $filtered_background_color, $filtered_button_text_color, $this->options[ $this->name_prefix . 'answers_padding' ], $this->options[ $this->name_prefix . 'answers_gap' ], $filtered_slider_question_bubble_bg_color, $filtered_slider_question_bubble_text_color );
            }

            if( $this->options[ $this->name_prefix . 'is_elegant' ] ){
                $content[] = $this->get_elegant_theme_styles( $filtered_survey_color, $filtered_survey_button_bg_color, $filtered_text_color, $filtered_background_color, $filtered_button_text_color, $filtered_slider_question_bubble_bg_color, $filtered_slider_question_bubble_text_color  );
            }

    	$content[] = '</style>';

    	$content = implode( '', $content );

    	return $content;
    }

    public function get_custom_css(){
		
        $content = array();

        if( $this->options[ $this->name_prefix . 'custom_css' ] != '' ){

            $content[] = '<style type="text/css">';
            
	        	$content[] = $this->options[ $this->name_prefix . 'custom_css' ];
            
            $content[] = '</style>';
            
        }

        $content = implode( '', $content );

    	return $content;
    }

    public function get_encoded_options( $limit , $edit_prev_submission = false ){
        
        $content = array();
        
        if( isset( $this->options[ $this->name_prefix . 'submit_redirect_delay' ] ) ){
            $this->options[ $this->name_prefix . 'submit_redirect_seconds'] = Survey_Maker_Data::secondsToWords( intval( $this->options[ $this->name_prefix . 'submit_redirect_delay' ] ) );
        }

        // Animation Top (px)
        $setting_options = Survey_Maker_Data::get_setting_data( 'options' );
        $survey_animation_top = ( isset($setting_options[ 'survey_animation_top' ]) && $setting_options[ 'survey_animation_top' ] != '' ) ? intval( $setting_options[ 'survey_animation_top' ] ) : '';

        $setting_options['survey_enable_animation_top'] = isset($setting_options['survey_enable_animation_top']) ? $setting_options['survey_enable_animation_top'] : 'on';
        $survey_enable_animation_top = ( isset($setting_options[ 'survey_enable_animation_top' ]) && $setting_options[ 'survey_enable_animation_top' ] == 'on' ) ? true : false;

        
        $this->options['is_user_logged_in'] = is_user_logged_in();

        $this->options[ $this->name_prefix . 'animation_top'] = $survey_animation_top;
        $this->options[ $this->name_prefix . 'enable_animation_top'] = $survey_enable_animation_top;

        $options = array();
        if( ! $limit || $edit_prev_submission){
            foreach( $this->options as $k => $q ){
                if( strpos( $k, 'email' ) !== false ){
                    unset( $this->options[ $k ] );
                }
            }
            $options = $this->options;
        }else{
            if($this->options[ $this->name_prefix . 'redirect_delay' ] && $this->options[ $this->name_prefix . 'redirect_delay' ] != ''){
                if($this->options[ $this->name_prefix . 'redirect_url' ] && $this->options[ $this->name_prefix . 'redirect_url' ] != ''){
                    if($this->options[ $this->name_prefix . 'limit_users' ]){
                        $options = array(
                            $this->name_prefix . 'submit_redirect_seconds' => Survey_Maker_Data::secondsToWords( intval( $this->options[ $this->name_prefix . 'submit_redirect_delay' ] ) ),
                            $this->name_prefix . 'submit_redirect_delay' => intval( $this->options[ $this->name_prefix . 'submit_redirect_delay' ] ),
                            $this->name_prefix . 'submit_redirect_url' => $this->options[ $this->name_prefix . 'submit_redirect_url' ],
                            $this->name_prefix . 'limit_users' => $this->options[ $this->name_prefix . 'limit_users' ],
                            $this->name_prefix . 'redirect_url' => $this->options[ $this->name_prefix . 'redirect_url' ],
                            $this->name_prefix . 'redirect_delay' => intval( $this->options[ $this->name_prefix . 'redirect_delay' ] ),
                            $this->name_prefix . 'redirect_delay_seconds' => Survey_Maker_Data::secondsToWords( intval( $this->options[ $this->name_prefix . 'redirect_delay' ] ) ),
                        );
                    }
                }
            }
            $options[ $this->name_prefix . 'enable_survey_start_loader' ] = $this->options[ $this->name_prefix . 'enable_survey_start_loader' ];
        }
            
        $content[] = '<script type="text/javascript">';
    
        $content[] = "
                if(typeof aysSurveyOptions === 'undefined'){
                    var aysSurveyOptions = [];
                }
                aysSurveyOptions['" . $this->unique_id . "']  = '" . base64_encode( json_encode( $options ) ) . "';";
        
        $content[] = '</script>';

        $content = implode( "", $content );

    	return $content;
    }

    public function ays_survey_get_user_information() {
        if(is_user_logged_in()) {
            $output = wp_get_current_user();
        } else {
            $output = array();
        }
        return $output;
    }

    public function ays_survey_popup_set_cookie(){
        if( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != '' ){
            $id = sanitize_text_field( $_REQUEST['id'] );
        }else{
            $id = null;
        }

        if( $id === null ){
            return array(
                'status' => false
            );
        }

        $cookie_name = 'ays_survey_popup_cookie_name_'.$id;
        $cookie_value = 'ays_survey_popup_cookie_value_'.$id;
        $cookie_expiration = time() + (12 * 30 * 24 * 60 * 60);
        setcookie($cookie_name, $cookie_value, $cookie_expiration, '/');
        return array(
            'status' => true
        );
    }

    public function ays_generate_survey_popup_method( $attr ){

        $id = (isset($attr['id'])) ? absint(intval($attr['id'])) : null;
        
        if (is_null($id)) {
            return '';
        }
        
        $this->enqueue_scripts_popups();

        $content = $this->ays_popup_shortcode_content($id, $attr);
        return $content ? str_replace( array( "\r\n", "\n", "\r" ), "\n", $content ) : '';  
    }

    public function ays_shortcodes_show_all(){
        global $wpdb;
        $post_id = get_the_ID();
        $popup_surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "popup_surveys";
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        $sql = "SELECT p_s.*, s.options as s_options, s.status as survey_status 
                FROM {$popup_surveys_table} as p_s 
                LEFT JOIN {$surveys_table} AS s 
                    ON p_s.survey_id = s.id 
                WHERE p_s.status = 'published'";
        $result = $wpdb->get_results( $sql, "ARRAY_A" );

        foreach($result as $key => $value){
            echo do_shortcode('[ays_survey_popup id="'. $value['id'] .'"]');
        }
    }

    public function ays_popup_shortcode_content( $id, $attr ){
        global $wpdb;
        $post_id = get_the_ID();
        $popup_surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "popup_surveys";
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        $sql = "SELECT p_s.*, s.options as s_options, s.status as survey_status 
                FROM {$popup_surveys_table} as p_s 
                LEFT JOIN {$surveys_table} AS s 
                    ON p_s.survey_id = s.id 
                WHERE p_s.id = {$id}";
        $popup = $wpdb->get_row( $sql, "ARRAY_A" );
        
        if( empty( $popup ) ){
            return '';
        }

        $survey_status = (isset($popup['survey_status'] ) && $popup['survey_status']  != '') ? $popup['survey_status']  : 'published';

        if($survey_status == 'published'){

            $show_all = $popup['show_all'];
            switch($show_all){
                case 'all':
                    $show_popup = true;
                break;
                case 'selected':
                    $show_popup = false;
                break;
                case 'except':
                    $show_popup = true;
                break;
                default:
                    $show_popup = true;
                    $show_all = 'all';
                break;
            }

            $show = array('selected');
            
            $options = array();
            $survey_options = array();
            if ($popup['options'] != '' || $popup['options'] != null) {
                $options = json_decode( $popup['options'], true );
            }

            if ($popup['s_options'] != '' || $popup['s_options'] != null) {
                $survey_options = json_decode( $popup['s_options'], true );
            }

            // Width
            $popup_survey_width = (isset($options['width']) && $options['width'] != '') ? absint ( intval( $options['width'] ) ) : 400;
            
            // Height
            $popup_survey_height = (isset($options['height']) && $options['height'] != '') ? absint ( intval( $options['height'] ) ) : 500;

            // Popup Position
            $popup_position = (isset($options['popup_position']) && $options['popup_position'] != 'center-center') ? $options['popup_position'] : 'center-center';
            
            // Popup Margin
            $popup_margin = (isset($options['popup_margin']) && $options['popup_margin'] != '') ? $options['popup_margin'] : '0';
            
            $hide_popup = (isset($options['hide_popup']) && $options['hide_popup'] == 'on') ?  $options['hide_popup']  : 'off';
            
            $survey_bg = (isset($survey_options['survey_background_color']) && $survey_options['survey_background_color'] != '') ? $survey_options['survey_background_color'] : '#ffffff';
            $survey_theme = (isset($survey_options['survey_theme']) && $survey_options['survey_theme'] != '') ? $survey_options['survey_theme'] : 'classic_light';
            $is_minimal = $survey_theme == 'minimal' ? true : false;
            $is_modern = $survey_theme == 'modern' ? true : false;

            if( $is_minimal || $is_modern){
                $survey_bg = '#ffffff';
            }

            $survey_text_color = (isset($survey_options['survey_text_color']) && $survey_options['survey_text_color'] != '') ? $survey_options['survey_text_color'] : '#ffffff';
            
            // Popup full screen mode
            $survey_popup_full_screen = (isset($options["full_screen_mode"]) && $options["full_screen_mode"] == "on") ? true : false;
            
            // Popup background color
            $popup_bg_color = (isset($options['popup_bg_color']) && $options['popup_bg_color'] != '') ? $options['popup_bg_color'] : '#ffffff';

            // Popup trigger type
            $popup_trigger_type = (isset($options["popup_trigger"]) && $options["popup_trigger"] != "") ? $options["popup_trigger"] : "on_load";

            // Popup selector
            $popup_selector = (isset($options["popup_selector"]) && $options["popup_selector"] != "") ? stripslashes( esc_attr($options["popup_selector"])) : "";


            if($show_all != 'all'){
                if($post_id != false){
                    $post = get_post( $post_id );
                    $this_post_title = strval( $post->ID );
                    $except_posts = array();
                    $except_post_types = array();
                    $postType = $post->post_type;

                    if (isset($options['except_posts']) && !empty($options['except_posts'])) {
                        $except_posts = $options['except_posts'];
                    }

                    if (isset($options['except_post_types']) && !empty($options['except_post_types'])) {
                        $except_post_types = $options['except_post_types'];
                    }
                    
                    $except_all_post_types = ( isset( $options['all_posts'] ) && ! empty( $options['all_posts'] ) ) ? $options['all_posts'] : array();
                    
                    if ( is_front_page() ) {
                        if( isset($options['show_on_home_page']) && $options['show_on_home_page'] == 'on' ){
                            $show_popup = true;
                        }else{
                            $show_popup = false;
                        }
                    }
                    
                    if( in_array( $post_id . "", $except_posts ) ){
                        if( in_array( $show_all, $show ) ){
                            $show_popup = true;
                        }else{
                            $show_popup = false;
                        }
                    }elseif( !in_array( $this_post_title, $except_posts ) && in_array( $postType, $except_all_post_types ) ) {
                        if( in_array( $show_all, $show ) ){
                            $show_popup = true;
                        }else{
                            $show_popup = false;
                        }
                    }
                }
            }

            switch($popup_trigger_type){
                case 'on_click':
                case 'on_exit':
                    $display_popup_on_load = 'display_none_not_important';
                    break;
                case 'on_load': 
                default:
                    $display_popup_on_load = '';
                    break;
            }

            if( ! isset( $_COOKIE[ 'ays_survey_popup_cookie_name_' . $popup['id'] ] ) ){
                if ($show_popup) {
                    $shortcode2 = '[ays_survey id="'. $popup['survey_id'] .'"]';
                    // $popup_survey_view = "<div class='ays-survey-popup-survey-window ays-survey-popup-modal-".$popup['id']."' data-id='".$popup['id']."'>
                    $popup_survey_view = "<div class='ays-survey-popup-survey-window ays-survey-popup-modal-".$popup['id']." ".$display_popup_on_load."' data-id='".$popup['id']."'>
                        <div class='ays-survey-popup-btn-close'>
                            <img class='ays-survey-popup-btn-close-icon' src='". SURVEY_MAKER_PUBLIC_URL ."/images/cross.svg'>
                        </div>";
                        $popup_survey_view .= "<div class='ays-survey-popup-content'>";
                        if($survey_popup_full_screen){
                            $popup_survey_view .= '<div class="ays-survey-popup-full-screen-mode">
                                                        <a class="ays-survey-popup-full-screen-container">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" fill="#fff" viewBox="0 0 24 24" width="24" tabindex="0" class="ays-survey-popup-close-full-screen">
                                                                <path d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" fill="#fff" viewBox="0 0 24 24" width="24" class="ays-survey-popup-open-full-screen">
                                                                <path d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                                                            </svg>
                                                        </a>
                                                    </div>';
                        }
                        $popup_survey_view .= "<div class='ays-survey-popup-main'>".do_shortcode($shortcode2)."</div>
                    </div>";

                    $margin_right = '';
                    $additional_css = '';
                    switch ( $popup_position ){
                        case "center-center":
                            $ays_survey_popup_conteiner_pos_top = '12px';
                            $ays_survey_popup_conteiner_pos_left = '0';
                            $ays_survey_popup_conteiner_pos_right = '0';
                            $ays_survey_popup_conteiner_pos_bottom = '0';
                            $popup_margin = 'auto';
                            $additional_css = 'max-height: calc( 100vh - 12px )';
                            break;
                        case "left-top":

                            $ays_survey_popup_conteiner_pos_top = '0';
                            $ays_survey_popup_conteiner_pos_left = '0';
                            $ays_survey_popup_conteiner_pos_right = 'unset';
                            $ays_survey_popup_conteiner_pos_bottom = 'unset';
                            $popup_margin .= 'px';

                            if( absint( $popup_margin ) < 12 ){
                                $margin_right = 'margin-top: 12px;';
                            }
                            break;
                        case "top-center":

                            $ays_survey_popup_conteiner_pos_top = '0';
                            $ays_survey_popup_conteiner_pos_left = '50%';
                            $ays_survey_popup_conteiner_pos_right = 'unset';
                            $ays_survey_popup_conteiner_pos_bottom = 'unset';
                            $popup_margin .= 'px auto';
                            $additional_css = 'transform: translateX(-50%);';

                            if( absint( $popup_margin ) < 12 ){
                                $margin_right = 'margin-top: 12px;';
                            }
                            break;    
                        case "right-top":

                            $ays_survey_popup_conteiner_pos_top = '0';
                            $ays_survey_popup_conteiner_pos_left = 'unset';
                            $ays_survey_popup_conteiner_pos_right = '0';
                            $ays_survey_popup_conteiner_pos_bottom = 'unset';
                            $popup_margin .= 'px';
                            if( absint( $popup_margin ) < 12 ){
                                $margin_right = 'margin-right: 12px;margin-top: 12px;';
                            }

                            break;
                        case "left-center":

                            $ays_survey_popup_conteiner_pos_top = '0';
                            $ays_survey_popup_conteiner_pos_left = '0';
                            $ays_survey_popup_conteiner_pos_right = 'unset';
                            $ays_survey_popup_conteiner_pos_bottom = '0';
                            $popup_margin = 'auto ' . $popup_margin . 'px';
                            
                            break; 
                        case "right-center":
                            
                            $ays_survey_popup_conteiner_pos_top = '0';
                            $ays_survey_popup_conteiner_pos_left = 'unset';
                            $ays_survey_popup_conteiner_pos_right = '0';
                            $ays_survey_popup_conteiner_pos_bottom = '0';
                            $popup_margin = 'auto ' . $popup_margin . 'px';

                            if( absint( $popup_margin ) < 12 ){
                                $margin_right = 'margin-right: 12px;';
                            }
                            break;       
                        case "right-bottom":

                            $ays_survey_popup_conteiner_pos_top = 'unset';
                            $ays_survey_popup_conteiner_pos_left = 'unset';
                            $ays_survey_popup_conteiner_pos_right = '0';
                            $ays_survey_popup_conteiner_pos_bottom = '0';
                            $popup_margin .= 'px';

                            if( absint( $popup_margin ) < 12 ){
                                $margin_right = 'margin-right: 12px;';
                            }
                            break;
                        case "center-bottom":

                            $ays_survey_popup_conteiner_pos_top = 'unset';
                            $ays_survey_popup_conteiner_pos_left = '50%';
                            $ays_survey_popup_conteiner_pos_right = 'unset';
                            $ays_survey_popup_conteiner_pos_bottom = '0';
                            $popup_margin .= 'px auto';
                            $additional_css = 'transform: translateX(-50%);';
                            
                            break;    
                        case "left-bottom":

                            $ays_survey_popup_conteiner_pos_top = 'unset';
                            $ays_survey_popup_conteiner_pos_left = '0';
                            $ays_survey_popup_conteiner_pos_right = 'unset';
                            $ays_survey_popup_conteiner_pos_bottom = '0';
                            $popup_margin .= 'px';
                            
                            break;
                    }

                    $popup_survey_view .= '
                        <style>
                            .ays-survey-popup-modal-' . $popup['id'] . ' {
                                width: ' . $popup_survey_width . 'px;
                                height: ' . $popup_survey_height . 'px;
                                background-color: ' . $popup_bg_color . ';
                                top: ' . $ays_survey_popup_conteiner_pos_top . ';
                                left: ' . $ays_survey_popup_conteiner_pos_left . ';
                                right: ' . $ays_survey_popup_conteiner_pos_right . ';
                                bottom: ' . $ays_survey_popup_conteiner_pos_bottom . ';
                                margin: ' . $popup_margin . ';
                                ' . $margin_right . '
                                ' . $additional_css . '
                            }
                            .ays-survey-popup-modal-' . $popup['id'] . ' .ays-survey-popup-open-full-screen ,
                            .ays-survey-popup-modal-' . $popup['id'] . ' .ays-survey-popup-close-full-screen {
                                fill: ' . $this->options[ $this->name_prefix . 'full_screen_button_color' ] . ';
                            }
                        </style>
                    ';

                    $popup_survey_view .= '<script type="text/javascript">';
                
                    $popup_survey_view .= "
                        if(typeof aysSurveyPopupsOptions === 'undefined'){
                            var aysSurveyPopupsOptions = [];
                        }
                        aysSurveyPopupsOptions['" . $popup['id'] . "']  = '" . base64_encode( json_encode( array(
                            'hidePopup'      => $hide_popup,
                            'popup_trigger'  => $popup_trigger_type,
                            'popup_selector' => $popup_selector,

                        ) ) ) . "';";
                    $popup_survey_view .= '</script>';
                    $popup_survey_view .= '</div>';

                    return $popup_survey_view;
                }
            }
        }
    }

    public function ays_create_submission_report( $survey, $options, $send_data ){
    	global $wpdb;
        $answers_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

    	$sections_ids = isset( $survey->section_ids ) && $survey->section_ids != '' ? $survey->section_ids : '';
        $question_ids = isset( $survey->question_ids ) && $survey->question_ids != '' ? $survey->question_ids : '';
    	
    	if( $sections_ids != '' ){
    		$section_ids_array = explode( ',', $sections_ids );
    	}else{
    		$section_ids_array = array();
        }

        $sections = Survey_Maker_Data::get_sections_by_survey_id( $sections_ids );
        $sections_count = count( $sections );

        $multiple_sections = $sections_count > 1 ? true : false;

        foreach ($sections as $section_key => $section) {
            $sections[$section_key]['title'] = (isset($section['title']) && $section['title'] != '') ? stripslashes( esc_html( $section['title'] ) ) : '';
            // $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? nl2br( esc_html( $section['description'] ) ) : '';
            if ( isset($this->options[ $this->name_prefix . 'allow_html_in_section_description' ]) && $this->options[ $this->name_prefix . 'allow_html_in_section_description' ]) {
                $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? nl2br( $section['description'] ) : '';
            } else {
                $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? nl2br( esc_html( $section['description'] ) ) : '';
            }


            $section_questions = Survey_Maker_Data::get_questions_by_section_id( intval( $section['id'] ), $question_ids );

            foreach ($section_questions as $question_key => $question) {
                $section_questions[$question_key]['question'] = (isset($question['question']) && $question['question'] != '') ? nl2br( $question['question'] ) : '';
                $section_questions[$question_key]['image'] = (isset($question['image']) && $question['image'] != '') ? $question['image'] : '';
                $section_questions[$question_key]['type'] = (isset($question['type']) && $question['type'] != '') ? $question['type'] : 'radio';
                $section_questions[$question_key]['user_variant'] = (isset($question['user_variant']) && $question['user_variant'] == 'on') ? true : false;

                $opts = json_decode( $question['options'], true );
                $opts['required'] = (isset($opts['required']) && $opts['required'] == 'on') ? true : false;
                $opts['enable_max_selection_count'] = (isset($opts['enable_max_selection_count']) && $opts['enable_max_selection_count'] == 'on') ? true : false;
                $opts['max_selection_count'] = (isset($opts['max_selection_count']) && $opts['max_selection_count'] != '') ? intval( $opts['max_selection_count'] ) : null;
                $opts['is_logic_jump'] = (isset($opts['is_logic_jump']) && $opts['is_logic_jump'] == 'on') ? true : false;

                if( $section_questions[$question_key]['type'] == 'checkbox' ){
                    $options[ 'survey_checkbox_options' ][$question['id']]['enable_max_selection_count'] = $opts['enable_max_selection_count'];
                    $options[ 'survey_checkbox_options' ][$question['id']]['max_selection_count'] = $opts['max_selection_count'];
                }

                if( $section_questions[$question_key]['type'] == 'matrix_scale' || $section_questions[$question_key]['type'] == 'matrix_scale_checkbox'){
                    if(isset($opts['matrix_columns']) && $opts['matrix_columns']){
                        foreach ($opts['matrix_columns'] as $column_key => &$column_val) {
                            if( isset($this->options[ $this->name_prefix . 'allow_html_in_answers' ]) && $this->options[ $this->name_prefix . 'allow_html_in_answers' ] === false ){
                                $column_val = htmlentities( ($column_val) );
                            }
                            $column_val = stripslashes( $column_val );
                        }
                    }
                }

                $q_answers = Survey_Maker_Data::get_answers_by_question_id( intval( $question['id'] ) );

                foreach ($q_answers as $answer_key => $answer) {
                    $answer_content = (isset($answer['answer']) && $answer['answer'] != '') ? $answer['answer'] : '';

                    if( $options[ $this->name_prefix . 'allow_html_in_answers' ] === false ){
                        $answer_content = htmlentities( $answer_content );
                    }

                    $q_answers[$answer_key]['answer'] = stripslashes( $answer_content );

                    $q_answers[$answer_key]['image'] = (isset($answer['image']) && $answer['image'] != '') ? $answer['image'] : '';
                    $q_answers[$answer_key]['placeholder'] = (isset($answer['placeholder']) && $answer['placeholder'] != '') ? $answer['placeholder'] : '';
                    $answerOpts = array();
                    if( $answer['options'] != '' ){
                        $answerOpts = json_decode( $answer['options'], true );
                    }
                    $answerOpts['go_to_section'] = (isset($answerOpts['go_to_section']) && $answerOpts['go_to_section'] != '') ? intval( $answerOpts['go_to_section'] ) : -1;
                    $q_answers[$answer_key]['options'] = $answerOpts;
                }

                $section_questions[$question_key]['answers'] = $q_answers;

                $section_questions[$question_key]['options'] = $opts;
                $options[ $this->name_prefix . 'questions' ][$question['id']]['id'] = $question['id'];
                $options[ $this->name_prefix . 'questions' ][$question['id']]['type'] = $question['type'];
                $options[ $this->name_prefix . 'questions' ][$question['id']]['answers'] = $q_answers;
            }

            $sections[$section_key]['questions'] = $section_questions;
        }

        $content = '';
        
        $content .= '<table style="border-collapse:collapse;width: 100%;">';
            $content .=	'<tr>';
                $content .=	'<th style="text-align:center;font-family: Arial, Helvetica, sans-serif;">
                                <h2>'. stripslashes( $survey->title ) .'</h2>';
                $content .=	'</th>';
            $content .=	'</tr>';
        $content .= '</table>';

        $answered_questions = $send_data['answered_questions'];
      
        foreach ($sections as $key => $section) {
            $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                $content .= '<thead>';
                    $content .= '<tr>';
                        $content .= '<th colspan="2" style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                            $content .= $section['title'];
                        $content .= '</th>';
                    $content .= '</tr>';
                    $content .= '<tr>';
                        $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 5px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                            $content .= __( 'Question', $this->plugin_name );
                        $content .= '</th>';
                        $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 5px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">'. __( 'Answer', $this->plugin_name ) .'</th>';
                    $content .= '</tr>';
                $content .= '</thead>';
                $content .= '<tbody>';

                foreach ($section['questions'] as $key => $survey_question) {
                    $answers_array = (isset($survey_question['answers']) && !empty($survey_question['answers'])) ? $survey_question['answers'] : array();
                    $question_type = (isset($survey_question['type']) && $survey_question['type'] != '') ? sanitize_text_field( $survey_question['type'] ) : '';
                    $matrix_box = array();

                    $user_answer = '';
                    $user_variant = '';
                    $qid = $survey_question['id'];

                    $question_answer = '';
                    if( isset( $answered_questions[$qid] ) ){
                        if( isset( $answered_questions[$qid]['other'] ) ){
                            $user_variant = $answered_questions[$qid]['other'];
                            unset( $answered_questions[$qid]['other'] );
                        }

                        if( is_array( $answered_questions[$qid] ) ){
                            if( isset( $answered_questions[$qid]['answer'] ) && !empty( $answered_questions[$qid]['answer'] ) ){
                                $question_answer = $answered_questions[$qid]['answer'];
                            }else{
                                $question_answer = '';
                            }
                        }else{
                            $question_answer = $answered_questions[$qid];
                        }
                    }
                    $answer_id = $question_answer;

                    switch ( $question_type ) {
                        case "radio":
                        case "yesorno":
                            $user_answer = '';
                            break;
                        case "checkbox":
                            if( is_array( $question_answer ) ){
                                if( !in_array( '0', $question_answer ) ){
                                    $user_variant = '';
                                }else{
                                    if( $user_variant == '' && array_key_exists( '0', $question_answer ) ){
                                        unset( $question_answer['0'] );
                                    }
                                }
                                $user_answer = $question_answer;
                            }else{
                                $user_answer = $question_answer;
                                if( '0' != $question_answer ){
                                    $user_variant = '';
                                }else{
                                    if( $user_variant == '' ){
                                        $user_answer = '';
                                    }
                                }
                            }
                            $answer_id = 0;
                            break;    
                        case "select":
                            $user_answer = '';
                            break;
                        case "text":
                        case "short_text":
                        case "number":
                        case "phone":
                        case "name":
                        case "email":
                        case "linear_scale":
                        case "star":
                        case "range":
                        case "matrix_scale":
                        case "matrix_scale_checkbox":
                        case "star_list":
                        case "slider_list":
                            $matrix_box = isset($answer_id) && is_array($answer_id) ? $answer_id : array();
                        case "date":
                        case "time":
                            $user_answer = $question_answer;
                            $answer_id = 0;
                            break;
                        case "date_time":
                            if (is_array($question_answer)) {
                                $question_answer['date'] = $question_answer['date'] != '' ? $question_answer['date'] : '-';
                                $question_answer['time'] = $question_answer['time'] != '' ? $question_answer['time'] : '-';
                                $user_answer = implode(" " , $question_answer);
                            } else {
                                $user_answer = $question_answer;
                            }
                            $answer_id = 0;
                            break;    
                        case "upload":
                            $user_answer = $question_answer;
                            $answer_id = 0;
                            break;
                        default:
                            $user_answer = '';
                            break;
                    }

                    $content .= '<tr>';
                        $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                        $content .= $survey_question['question'];
                        $content .= '</td>';
                        $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                            if($question_type == 'checkbox' || $question_type == 'radio' || $question_type == 'select' || $question_type == 'yesorno'){
                                $user_answer_content = '';
                                if( $question_type == 'checkbox' ){
                                    $user_answer_content = array();
                                }
                                
                                foreach( $answers_array as $ans_key => $answer ){
                                    if( $question_type == 'checkbox' ){
                                        if( is_array( $user_answer ) ){
                                            if( in_array( $answer['id'], $user_answer ) ){
                                                $user_answer_content[] = $answer['answer'];
                                            }
                                        }else{
                                            if( intval( $answer['id'] ) == intval( $user_answer ) ){
                                                $user_answer_content[] = $answer['answer'];
                                                break;
                                            }
                                        }
                                    }else{
                                        if( intval( $answer['id'] ) == intval( $answer_id ) ){
                                            $user_answer_content = $answer['answer'];
                                            break;
                                        }
                                    }
                                }

                                if( $question_type == 'radio' ){
                                    if( 0 == intval( $answer_id ) && $user_variant != '' ){
                                        $user_answer_content = $user_variant;
                                    }
                                }

                                if( $question_type == 'checkbox' ){
                                    if( 0 == intval( $answer_id ) && $user_variant != '' ){
                                        $user_answer_content[] = $user_variant;
                                    }
                                    $content .= implode( ', ', $user_answer_content );
                                }else{
                                    $content .= $user_answer_content;
                                }
                            }elseif( $question_type == 'matrix_scale' || $question_type == 'star_list' || $question_type == 'slider_list' || $question_type == 'matrix_scale_checkbox'){
                                $question_options = isset($survey_question['options']) && !empty($survey_question['options']) ? $survey_question['options'] : array(); 
                                $question_options_columns = array(); 
                                
                                if( isset( $question_options['matrix_columns'] ) ){
                                    if( is_array( $question_options['matrix_columns'] ) ){
                                        $question_options_columns = $question_options['matrix_columns'];
                                    }else{
                                        $question_options_columns = json_decode($question_options['matrix_columns'], true);
                                    }
                                }
                                $matrix_answer_sql = "SELECT id, answer FROM ".$answers_table." WHERE question_id=".$qid;
                                $matrix_answers = $wpdb->get_results( $matrix_answer_sql, "ARRAY_A" );
                                $matrix_scale_answer_ids = array();
                                foreach($matrix_answers as $a_key => $a_value){
                                    $matrix_scale_answer_ids[$a_value['id']] = $a_value['answer'];
                                }
                                $matrix_results_all = array();
                                if($question_type == 'matrix_scale'){
                                    foreach($matrix_box as $m_key => $m_valaue){
                                        $matrix_results_all[] = '<strong>' . $matrix_scale_answer_ids[ $m_key ] . '</strong>: ' . $question_options_columns[ $m_valaue ];
                                    }
                                }
                                elseif ($question_type == 'star_list' || $question_type == 'slider_list'){
                                    foreach($matrix_box as $m_key => $m_valaue){
                                        $matrix_results_all[] = '<strong>' . $matrix_scale_answer_ids[ $m_key ] . '</strong>: ' . $m_valaue;
                                    }
                                }
                                elseif($question_type == 'matrix_scale_checkbox'){
                                    $devider = ",";
                                        $matrix_results_all = array();
                                        foreach($matrix_box as $m_key => $m_valaue){
                                            $matrix_results_all[] = $matrix_scale_answer_ids[ $m_key ]. ': ';
                                            $loop_iteration = 1;
                                            $columns_count = count($m_valaue);
                                            foreach($m_valaue as $each_answer_key => $each_answer_value){
                                                $matrix_results_all[] = $question_options_columns[ $each_answer_value ];
                                                if($loop_iteration != ($columns_count) && $columns_count != 1){
                                                    $matrix_results_all[] = $devider;
                                                }
                                                $loop_iteration++;
                                            }
                                            $matrix_results_all[] = "<br>";
                                        }
                                    
                                }
                                if($question_type != 'matrix_scale_checkbox'){
                                    $content .= implode(",<br>", $matrix_results_all);
                                    
                                }
                                else{
                                    $content .= implode(" ", $matrix_results_all);
                                }
                            }elseif( $question_type == 'upload' ){
                                if( $user_answer != '' ){
                                    $uploaded_filename = explode( "/", stripslashes( $user_answer ) );
                                    $filename = $uploaded_filename[ count( $uploaded_filename ) - 1 ];
                                    $content .= '<a href="'. stripslashes( $user_answer ) .'" download="'. $filename .'">' . $filename . '</a>';
                                }
                            }else{
                                $content .= stripslashes( nl2br( $user_answer ) );
                            }
                        $content .= '</td>';
                    $content .= '</tr>';
                }

                $content .= '</tbody>';
            $content .= '</table>';
        }

        return $content;
    }

    public function ays_survey_used_password_ajax(){
        
        $response = array();

        $unique_id = isset($_REQUEST['uniqueId']) ? sanitize_text_field( $_REQUEST['uniqueId'] ) : null;

        if($unique_id === null){
            return array(
                "status" => false,
                "message" => "No no no"
            );
        } else {
            global $wpdb;
            $survey_table = $wpdb->prefix . 'socialsurv_surveys';
            $name_prefix = 'ays-survey-';
            
            $survey_id = isset( $_REQUEST[ $name_prefix . 'id-' . $unique_id ] ) ? absint( sanitize_text_field( $_REQUEST[ $name_prefix . 'id-' . $unique_id ] ) ) : null;

            if($survey_id === null){
                return array(
                    "status" => false,
                    "message" => "No no no"
                );
            }else{
                $sql = "SELECT options FROM $survey_table WHERE `id` =".$survey_id;

                $survey_options = $wpdb->get_var($sql);
                
                $options = json_decode( $survey_options, true );
                
                //generated passwords
                $ays_survey_generated_passwords  = (isset($options['survey_generated_passwords']) && $options['survey_generated_passwords'] != '') ? $options['survey_generated_passwords'] : array();
                
                if(!empty($ays_survey_generated_passwords)){

                    //active passwords
                    $ays_survey_active_passwords = (isset( $ays_survey_generated_passwords['survey_active_passwords']) && !empty( $ays_survey_generated_passwords['survey_active_passwords'])) ?  $ays_survey_generated_passwords['survey_active_passwords'] : array();

                    //used passwords array()
                    $ays_survey_used_passwords = (isset( $ays_survey_generated_passwords['survey_used_passwords']) && !empty( $ays_survey_generated_passwords['survey_used_passwords'])) ?  $ays_survey_generated_passwords['survey_used_passwords'] : array();

                    //user used passwords
                    $user_generated_password = (isset($_REQUEST['userGeneratedPassword']) && $_REQUEST['userGeneratedPassword'] != '') ? $_REQUEST['userGeneratedPassword'] : '';

                    //check not active passwords
                    if (($key = array_search($user_generated_password, $ays_survey_active_passwords)) !== false) {
                        unset($ays_survey_active_passwords[$key]);
                    }

                    $ays_survey_used_passwords[] = $user_generated_password;
                    $ays_survey_generated_passwords['survey_active_passwords'] = $ays_survey_active_passwords;
                    $ays_survey_generated_passwords['survey_used_passwords'] = $ays_survey_used_passwords;
                    $generate_password_encode = $ays_survey_generated_passwords;
                    $options['survey_generated_passwords'] = $generate_password_encode;

                    $survey_result = $wpdb->update(
                        $survey_table,
                        array(
                            'options' => json_encode( $options ),
                        ),
                        array( 'id' => $survey_id ),
                        array( '%s'),
                        array( '%d' )
                    );

                }
            }
        }

		return $response;
    }

    public function ays_survey_upload_file(){
        $result = false;
        $user = wp_get_current_user();
        $user_data = isset($user->data) ? $user->data : "";
        $user_name = "guest";
        if($user_data && $user->ID > 0){
            $user_name = isset($user_data->display_name) ? $user_data->display_name : "";
        }
        
        if( isset( $_FILES ) ){
            if( isset( $_FILES["file"] ) ){
                $file_name = isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] != "" ? $user_name."-".$_FILES["file"]["name"] : "";
                
                $file_tmp_name = isset($_FILES["file"]["tmp_name"]) && $_FILES["file"]["tmp_name"] != "" ? $_FILES["file"]["tmp_name"] : "";
                $upload = wp_upload_bits($file_name, null, file_get_contents($file_tmp_name));
                $uploaded_file_path = isset($upload['file']) && $upload['file'] != "" ? $upload['file'] : "";
                $uploaded_file_url  = isset($upload['url']) && $upload['url'] != "" ? $upload['url'] : "";
                $uploaded_file_type = isset($upload['type']) && $upload['type'] != "" ? $upload['type'] : "";
                $result = true;
            }
        }
        

        return array(
            'status' => $result,
            "filePath" => $uploaded_file_path,
            "fileUrl"  => $uploaded_file_url,
            "fileType" => $uploaded_file_type
        );
    }

    public function ays_survey_check_equality( $condition_answer, $answered_answer, $sign = "==" ){
        switch($sign){
            case "==":
                if($answered_answer == $condition_answer){
                    return true;
                }
            break;
            case "!=":
                if($answered_answer != $condition_answer){
                    return true;
                }
            break;
            case "<":
                if($answered_answer < $condition_answer){
                    return true;
                }
            break;
            case "<=":
                if($answered_answer <= $condition_answer){
                    return true;
                }
            break;
            case ">":
                if($answered_answer > $condition_answer){
                    return true;
                }
            break;
            case ">=":
                if($answered_answer >= $condition_answer){
                    return true;
                }
            break;
            case "contains":
                if( strpos($answered_answer, $condition_answer) !== false ){
                    return true;
                }
            break;
            case "not_contain":
                if( strpos( $answered_answer, $condition_answer ) === false ){
                    return true;
                }
            break;
            default:
                if($condition_answer == $answered_answer){
                    return true;
                }
            break;
        }
        return false;
    }

    protected function ays_survey_check_limitations($limit_by, $limit_users_data){
        $is_limited = false;
        switch($limit_by){
            case 'cookie':
                $check_cookie = Survey_Maker_Data::ays_survey_check_cookie( $limit_users_data );
                if( !$check_cookie ){
                    $set_cookie = Survey_Maker_Data::ays_survey_set_cookie( $limit_users_data );
                }
                $is_limited = true;
                break;
            case 'ip_cookie':
                $check_user_by_ip = Survey_Maker_Data::get_user_by_ip( $limit_users_data['id'] );

                $started_user_count = Survey_Maker_Data::get_limit_cookie_count( $limit_users_data );
                $check_cookie = Survey_Maker_Data::ays_survey_check_cookie( $limit_users_data );

                if ( ! $check_cookie || $check_user_by_ip <= 0 ) {
                    if ( ! $check_cookie ) {
                        $set_cookie = Survey_Maker_Data::ays_survey_set_cookie( $limit_users_data );
                    }
                } 
                $is_limited = true;
                break;
        }
        return $is_limited;
    }

    public function get_css_mobile_part($mobile_max_width, $mobile_width, $pagination_number_height) {
        $content = '';
        $content .= '
        @media screen and (max-width: 640px){
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' {
                max-width: '. $mobile_max_width .';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' {
                width: ' . $mobile_width . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-box{
                width: 100%;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-box.' . $this->html_class_prefix . 'question-date-time-box{
                width: 65%;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-box .' . $this->html_class_prefix . 'date-time-inner-box .' . $this->html_class_prefix . 'question-time-input-box{
                width: 170px;
                margin-top: 10px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-box .' . $this->html_class_prefix . 'date-time-inner-box .' . $this->html_class_prefix . 'timepicker{
                width: 170px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-main{
                flex-wrap: wrap;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'footer-with-live-bar {
                flex-direction: column-reverse;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'footer-with-live-bar.' . $this->html_class_prefix . 'footer-with-terms-and-conds {
                flex-direction: column;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-main {
                margin-bottom: 10px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-required-icon,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-title {
                font-size: ' . $this->options[ $this->name_prefix . 'question_font_size_mobile' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button{
                font-size: ' . $this->options[ $this->name_prefix . 'buttons_mobile_font_size' ] . 'px;
                line-height: 2.5;
                white-space: normal;
                word-break: break-word;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'title{
                font-size: ' . $this->options[ $this->name_prefix . 'title_font_size_for_mobile' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'section-title-row-main{
                font-size: ' . $this->options[ $this->name_prefix . 'section_title_font_size_mobile' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'section-desc{
                font-size: ' . $this->options[ $this->name_prefix . 'section_description_font_size_mobile' ] . 'px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'question-input {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size_on_mobile' ] . 'px;
                height: auto;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label-content > span {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size_on_mobile' ] . 'px;
                line-height: 1;
                word-break: break-word;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.text,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.item {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size_on_mobile' ] . 'px !important;
                line-height: 1 !important;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select.dropdown div.item {
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size_on_mobile' ] . 'px !important;
                line-height: 1 !important;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-status .' . $this->html_class_prefix . 'live-bar-status-text{
                font-size: ' . $this->options[ $this->name_prefix . 'answer_font_size_on_mobile' ] . 'px;
                line-height: 1.5;
                '.$pagination_number_height.'
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'cover-photo-title-wrap{
                background-position: ' . implode(" ", explode("_" , $this->options[ $this->name_prefix . 'cover_photo_position' ])) .'; 
                background-size: ' . $this->options[ $this->name_prefix . 'cover_photo_object_fit' ] .';
                height: ' . $this->options[ $this->name_prefix . 'cover_photo_mobile_height' ] . 'px;
            }
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'sections .' . $this->html_class_prefix . 'question-image-caption span{
                font-size: '.$this->options[ $this->name_prefix . 'question_caption_font_size_on_mobile' ].'px;
            }

        }

        @media screen and (max-width: 580px) {
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . '.' . $this->html_class_prefix . 'container .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'answer-label .' . $this->html_class_prefix . 'answer-image-container{
                height: 195px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-main{
                justify-content: center;
            }
        }
         
        @media screen and (min-width: 580px) and (max-width: 1024px) {
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . '.' . $this->html_class_prefix . 'container .' . $this->html_class_prefix . 'section .' . $this->html_class_prefix . 'answer-label .' . $this->html_class_prefix . 'answer-image-container{
               height: 150px;
            }
        }';

        return $content;
    }

    public function get_business_theme_styles( $filtered_survey_color, $filtered_survey_button_bg_color, $filtered_text_color, $filtered_background_color, $filtered_button_text_color, $answer_padding, $answer_gap, $filtered_slider_question_bubble_bg_color, $filtered_slider_question_bubble_text_color ){
        wp_enqueue_style( $this->plugin_name.'-business-theme', plugin_dir_url( __FILE__ ) . 'css/partials/survey-maker-business-theme.css', array(), $this->version, 'all' );
        $content = array();
        $content[] =  
            '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'business-theme-question .' . $this->html_class_prefix .'question-content > .' . $this->html_class_prefix . 'question-answers .' . $this->html_class_prefix . 'answer-business-hover:hover{
                border: 1px dashed '.$filtered_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'business-theme-question .' . $this->html_class_prefix .'question-content > .' . $this->html_class_prefix . 'question-answers .' . $this->html_class_prefix . 'answer-business-hover.' . $this->html_class_prefix . 'answer-business-active{
                border: 1px solid '.$filtered_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer {
                padding: ' . $answer_padding . 'px ' . $answer_padding . 'px ' . $answer_padding . 'px ' . $answer_padding . 'px;
                margin: ' . $answer_gap . 'px 0;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'question-input,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' textarea.' . $this->html_class_prefix . 'question-input-textarea,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-other-text input.' . $this->html_class_prefix . 'answer-other-input{
                border: 1px solid '.$filtered_text_color.' !important;
            }            

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' input.' . $this->html_class_prefix . 'question-input:focus,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' textarea.' . $this->html_class_prefix . 'question-input:focus,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-other-text input.' . $this->html_class_prefix . 'answer-other-input:focus{            
                border: 2px solid ' . $filtered_survey_color . ' !important;
                outline-color: ' . $filtered_survey_color . ' !important;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'question-select-container-business:focus{
                border-color:' . $filtered_survey_color . ' !important;
                outline-color: ' . $filtered_survey_color . ' !important;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-upload-type-main .' . $this->html_class_prefix . 'answer-upload-type-button .' . $this->html_class_prefix . 'answer-upload-type-text {
                color: '.$filtered_button_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-upload-type-main .' . $this->html_class_prefix . 'answer-upload-type-button {
                background: ' . $filtered_survey_button_bg_color . ';
                border-radius: 3px;
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button{
                background-color: ' . $filtered_survey_button_bg_color . ';
                color: '.$filtered_button_text_color.';
            }
                        
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'answer-range-type-info-text {
                background-color: '.$filtered_slider_question_bubble_bg_color.';
                color: '.$filtered_slider_question_bubble_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'answer-range-type-info-text:after {
                border-top-color: '.$filtered_survey_color.';
                background-color: '.$filtered_survey_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-wrap {
                background-color: #ffffff;
                border-color: '.$filtered_survey_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'live-bar-fill {
                background-color: '.$filtered_survey_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix .'business-checkmark-label-container input:checked ~ .' . $this->html_class_prefix .'maker-checkmark{
                background-color: '.$filtered_text_color.';
            }
        '; 

        $content = implode( '', $content );
        
        return $content;
    }

    public function get_elegant_theme_styles( $filtered_survey_color, $filtered_survey_button_bg_color, $filtered_text_color, $filtered_background_color, $filtered_button_text_color,$filtered_slider_question_bubble_bg_color, $filtered_slider_question_bubble_text_color ){
        wp_enqueue_style( $this->plugin_name.'-elegant-theme', plugin_dir_url( __FILE__ ) . 'css/partials/survey-maker-elegant-theme.css', array(), $this->version, 'all' );
        $content = array();

        $content[] =            
            '#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'elegant-theme-answer.'.$this->html_class_prefix.'question-box > .' . $this->html_class_prefix . 'question-input-box > .' . $this->html_class_prefix . 'question-input, 
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'elegant-theme-answer.'.$this->html_class_prefix.'question-box > .' . $this->html_class_prefix . 'question-input-box > textarea.' . $this->html_class_prefix . 'question-input-textarea, 
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question .' . $this->html_class_prefix .'question-content .'.$this->html_class_prefix.'elegant-theme-style-for-other-answer .'.$this->html_class_prefix.'answer-other-text input,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active .'.$this->html_class_prefix.'answer-other-text input,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active textarea.' . $this->html_class_prefix . 'question-input-textarea,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active .' . $this->html_class_prefix . 'question-input,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-elegant-active.'.$this->html_class_prefix.'ind-submission-other .'.$this->html_class_prefix.'answer-other-text input{
                color: '.$filtered_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'elegant-theme-answer.'.$this->html_class_prefix.'question-box > .' . $this->html_class_prefix . 'question-input-box > .' . $this->html_class_prefix . 'question-input:focus,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'elegant-theme-answer.'.$this->html_class_prefix.'question-box > .' . $this->html_class_prefix . 'question-input-box > textarea.' . $this->html_class_prefix . 'question-input-textarea:focus,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question .' . $this->html_class_prefix .'question-content .'.$this->html_class_prefix.'elegant-theme-style-for-other-answer .'.$this->html_class_prefix.'answer-other-text input:focus,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active textarea.' . $this->html_class_prefix . 'question-input-textarea,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active  .' . $this->html_class_prefix . 'question-input,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-elegant-active.'.$this->html_class_prefix.'ind-submission-other .'.$this->html_class_prefix.'answer-other-text input{
                caret-color: '.$filtered_survey_color.';
                border-bottom: 1px solid '.$filtered_survey_button_bg_color.' !important;
                color: '.$filtered_survey_color.';
            }
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'elegant-theme-answer.'.$this->html_class_prefix.'question-box > .' . $this->html_class_prefix . 'question-input-box > .' . $this->html_class_prefix . 'question-input:focus::placeholder,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'elegant-theme-answer.'.$this->html_class_prefix.'question-box > .' . $this->html_class_prefix . 'question-input-box > textarea.' . $this->html_class_prefix . 'question-input-textarea:focus::placeholder,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active textarea.' . $this->html_class_prefix . 'question-input-textarea::placeholder,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question.' . $this->html_class_prefix . 'elegant-theme-question-active .' . $this->html_class_prefix . 'question-input::placeholder{
                color: '.$filtered_survey_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-range-type-main .' . $this->html_class_prefix . 'answer-range-type-info-text {
                background-color: '.$filtered_slider_question_bubble_bg_color.';
                color: '.$filtered_slider_question_bubble_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question .' . $this->html_class_prefix .'question-content > .' . $this->html_class_prefix . 'question-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-elegant-active:not(#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question .' . $this->html_class_prefix .'question-content > .' . $this->html_class_prefix . 'question-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-linear-scale-radio.' . $this->html_class_prefix . 'answer-elegant-active),
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-elegant-active:not(#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers  .' . $this->html_class_prefix . 'question-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-linear-scale-radio.' . $this->html_class_prefix . 'answer-elegant-active){
                background-color: '.$filtered_survey_button_bg_color.';
            }           

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-questions > .' . $this->html_class_prefix . 'question.' . $this->html_class_prefix . 'elegant-theme-question .' . $this->html_class_prefix .'question-content > .' . $this->html_class_prefix . 'question-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-linear-scale-radio.' . $this->html_class_prefix . 'answer-elegant-active .'. $this->html_class_prefix .'elegant-theme-answers,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-elegant-active input:not(#' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-questions-answers .' . $this->html_class_prefix . 'answer-elegant-hover.' . $this->html_class_prefix . 'answer-elegant-active.'.$this->html_class_prefix.'ind-submission-other .'.$this->html_class_prefix.'answer-other-text input){                
                accent-color: '.$filtered_survey_color.';
            }                            

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="checkbox"] ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-2,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-label input[type="radio"] ~ .' . $this->html_class_prefix . 'answer-label-content .' . $this->html_class_prefix . 'answer-icon-content .' . $this->html_class_prefix . 'answer-icon-content-2{
                border: 2px solid '.$filtered_survey_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .'.$this->html_class_prefix.'answer-matrix-scale-column-content-wrap{
                border: 1px solid ' . Survey_Maker_Data::hex2rgba( $filtered_survey_color, 0.2 ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .'.$this->html_class_prefix.'answer-matrix-scale-column-content-wrap.'.$this->html_class_prefix.'answer-elegant-active-matrix-scale{
                background-color: ' . Survey_Maker_Data::hex2rgba( $filtered_survey_color, 0.2 ) . ';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-matrix-scale-main .' . $this->html_class_prefix . 'answer-matrix-scale-container .' . $this->html_class_prefix . 'answer-matrix-scale-row .'.$this->html_class_prefix.'answer-matrix-scale-column-content-wrap:hover{
                border-color: ' . Survey_Maker_Data::hex2rgba( $filtered_survey_color, 0.39 ) . ';
                background-color: ' . Survey_Maker_Data::hex2rgba( $filtered_survey_color, 0.2 ) . ';
            }            
            
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content input.' . $this->html_class_prefix . 'section-button:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export:hover,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'results-content .' . $this->html_class_prefix . 'thank-you-summary-submission-main-container button.' . $this->html_class_prefix . 'single-submission-results-export,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content button.' . $this->html_class_prefix . 'section-button,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'section-buttons .' . $this->html_class_prefix . 'section-button-container .' . $this->html_class_prefix . 'section-button-content a.' . $this->html_class_prefix . 'section-button{
                background-color: ' . $filtered_survey_button_bg_color . ';
                color: '.$filtered_button_text_color.';
            }

            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'answer-star-radio i.' . $this->html_class_prefix . 'star-icon,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' label.' . $this->html_class_prefix . 'star-list-answer-submission-content-label > div.' . $this->html_class_prefix . 'star-list-answer-submission-content-div > i,
            #' . $this->html_class_prefix . 'container-' . $this->unique_id_in_class . ' label.' . $this->html_class_prefix . 'individual-submission-conatiner-star-label-stars div:nth-child(2) > i{
                color: '.$filtered_survey_color.';
            }           
        ';

        $content = implode( '', $content );

        return $content;
    }


    public function create_individual_submission_html( $survey_id , $survey_theme = null, $hide_question_ids = null ){
        global $wpdb;
        $user_id   = get_current_user_id();

        $hide_questions_ids_arr = isset($hide_question_ids) && $hide_question_ids != '' ? explode("," , $hide_question_ids) : array();

        if($survey_theme != null){
            $is_minimal  = $survey_theme == 'minimal' ? true : false;
            $is_modern   = $survey_theme == 'modern' ? true : false;
            $is_business = $survey_theme == 'business' ? true : false;
            $is_elegant = $survey_theme == 'elegant' ? true : false;
        }

        $surveys_table        = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );
        $ays_survey_questions = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions" );
        $content = array();
        $id = (isset( $survey_id ) && $survey_id != '' ) ? intval( $survey_id ) : null;
        
        if($id != null){
            $sql = "SELECT * FROM " . $surveys_table . " WHERE id =" . absint( $id );
            $survey_name = $wpdb->get_row( $sql, 'ARRAY_A' );
            
            if ( empty( $survey_name ) || is_null( $survey_name ) ){
                return null;
            }

            // Allow HTML in answers
            $survey_options[ 'survey_allow_html_in_answers' ] = isset($survey_options[ 'survey_allow_html_in_answers' ]) ? $survey_options[ 'survey_allow_html_in_answers' ] : 'off';
            $allow_html_in_answers = (isset($survey_options[ 'survey_allow_html_in_answers' ]) && $survey_options[ 'survey_allow_html_in_answers' ] == 'on') ? true : false;

            $last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id( $survey_id );
            
            $ays_survey_individual_submissions = Survey_Maker_Data::ays_survey_individual_results_for_one_submission( $last_submission, $survey_name );

            $exclude_sections_logic_jump = Survey_Maker_Data::exclude_sections_logic_jump( $id );

            //Collect submission data in one array
            //Questions
            $ind_questions = ( isset( $ays_survey_individual_submissions['questions'] ) && !empty( $ays_survey_individual_submissions['questions'] ) ) ? $ays_survey_individual_submissions['questions'] : array();

            //Sections
            $sections = ( isset( $ays_survey_individual_submissions['sections'] ) && !empty( $ays_survey_individual_submissions['sections'] ) ) ? $ays_survey_individual_submissions['sections'] : array();
            $section_id = ( isset( $section['id'] ) && $section['id'] != '' ) ? absint( intval( $section['id'] ) ) : null;

            //Submission ID
            $submission_id = ( isset( $ays_survey_individual_submissions['submission_id'] ) && $ays_survey_individual_submissions['submission_id'] != '' ) ? absint( intval( $ays_survey_individual_submissions['submission_id'] ) ) : null;
            
            $business_checkmark_label_container = $is_business ? $this->html_class_prefix . 'business-checkmark-label-container' : '';
            $business_dashed_border = $is_business ? $this->html_class_prefix . 'answer-business-hover' : '';
            $business_star_answer = $is_business ? $this->html_class_prefix . 'business-star-answer' : '';
            $select_class = $is_business ? $this->html_class_prefix . 'question-select-container-business' : '';
            $business_answer = $is_business ? $this->html_class_prefix . 'business-answer' : '';

            $elegant_theme_answer = $is_elegant ? $this->html_class_prefix . 'elegant-theme-answer-for-radio-checkbox' : '';
            $elegant_theme_answers = $is_elegant ? $this->html_class_prefix . 'elegant-theme-answers' : '';
            $elegant_theme_answer_hover = $is_elegant ? $this->html_class_prefix . 'answer-elegant-hover' : '';
            $elegant_theme_style_for_other_answer = $is_elegant ? $this->html_class_prefix . 'elegant-theme-style-for-other-answer' : '';

            $content[] = '<div class="ays-survey-question-result-container">';
                $content[] = '<div class="ays-survey-question-answer">';
                    $content[] = '<div class="ays-survey-submission-sections">';
                        $content[] = '<div class="ays-survey-submission-sections-downloaw-xlsx">';
                            $content[] = '<button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-results-export" data-type="xlsx" survey-id="'.$id.'" data-submission="'.$submission_id.'">';
                                $content[] = __('Export to XLSX', $this->plugin_name);
                            $content[] = '</button>';
                        $content[] = '</div>';
                        $checked = '';
                        $disabled = '';
                        $selected = '';
                        $color = '';
                        $user_matrix_answer = array();
                        $user_star_list_answer = array();
                        $user_slider_list_answer = array();
                        if( is_array( $sections ) ){
                            foreach ($sections as $section_key => $section){
                                //Section ID
                                $section_id = ( isset( $section['id'] ) && $section['id'] != '' ) ? absint( intval( $section['id'] ) ) : null;

                                //Section Title
                                $section_title = ( isset( $section['title'] ) && $section['title'] != '' ) ? stripcslashes( esc_html( $section['title'] ) ) : '';

                                //Section Description
                                $section_description = ( isset( $section['description'] ) && $section['description'] != '' ) ? stripcslashes( esc_html( $section['description'] ) ) : '';

                                //Section questions
                                $section_questions = ( isset( $section['questions'] ) && !empty( $section['questions'] ) ) ? $section['questions'] : array();
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
                                );
                                $exclude_section = '';
                                if( in_array( $section_id, $exclude_sections_logic_jump ) ){
                                    $exclude_section = 'display:none';
                                }
                                $content[] = '<div class="ays-survey-submission-section" data-id="'.$section_id.'" style="'.$exclude_section.'">';
                                    $content[] = '<div class="ays-survey-submission-title">';
                                            $content[] = '<p class="ays-survey-submission-sections-title">';
                                                $content[] = $section_title;
                                            $content[] = '</p>';
                                            $content[] = '<p class="ays-survey-submission-sections-description">';
                                                $content[] = $section_description;
                                            $content[] = '</p>';
                                    $content[] = '</div>';
                                    foreach ( $section_questions as $q_key => $question ) {

                                        if(is_array($hide_questions_ids_arr) && !empty($hide_questions_ids_arr)){
                                            if(in_array($question['id'] , $hide_questions_ids_arr)){
                                                continue;
                                            }
                                        }

                                        $survey_questions = ( isset( $question['question'] ) && $question['question'] != '' ) ? stripslashes( $question['question'] ) : '';

                                        $content[] = '<div class="ays-survey-submission-questions-answers">';
                                            $content[] = '<div style="font-size: 23px;">';
                                                $content[] = $survey_questions;
                                            $content[] = '</div>';
            
                                            $question_type_content = '';
                                            $user_answer = ( isset( $ind_questions[ $question['id'] ] )) ? $ind_questions[ $question['id'] ] : '';
            
                                            $question_result = ( isset( $ind_questions[ $question['id']] ) && !empty( $ind_questions[ $question['id']] ) ) ? $ind_questions[ $question['id']] : array();

                                            $enable_user_explanation = false;
                                            if( isset( $question['options'] ) ){
                                                $enable_user_explanation = isset( $question['options']['user_explanation'] ) && $question['options']['user_explanation'] == "on" ? true : false;
                                            }
                                            
                                            if($question['type'] == 'matrix_scale' || $question['type'] == 'matrix_scale_checkbox'){
                                                $user_matrix_answer = isset( $question_result['answer_ids'] ) ? $question_result['answer_ids'] : array();
                                                $matrix_column_ids = array();
                                                if( isset( $question['options'] ) ){
                                                    if( is_array( $question['options']['matrix_columns'] ) ){
                                                        $matrix_column_ids = $question['options']['matrix_columns'];
                                                    }else{
                                                        $matrix_column_ids = json_decode($question['options']['matrix_columns'] , true);
                                                    }
                                                }
                                            }
                                        
                                            if($question['type'] == 'star_list'){
                                                $user_star_list_answer = isset( $question_result['star_list_answer_ids'] ) ? $question_result['star_list_answer_ids'] : array();
                                            }
            
                                            if($question['type'] == 'slider_list'){
                                                $user_slider_list_answer = isset( $question_result['slider_list_answer_ids'] ) ? $question_result['slider_list_answer_ids'] : array();
                                            }
                                        
                                            $other_answer = '';
                                            if( isset( $user_answer['otherAnswer'] ) ){
                                                $other_answer = $user_answer['otherAnswer'];
                                            }

                                            if( isset( $user_answer['answer'] ) ){
                                                $user_answer = $user_answer['answer'];
                                            }

                                            $question_type_content = '';
                                            if( $question['type'] == 'select' ){
                                                $question_type_content .= '<div class=" ' . $this->html_class_prefix . 'question-select-conteiner ays-survey-each-question-answer">
                                                    <select class="ays-survey-submission-select ays-survey-question-select" disabled data-type="select">
                                                        <option value="">' . __( "Choose", $this->plugin_name ) . '</option>';
                                            }
            
                                            if( in_array( $question['type'], $text_types ) ){
                                                if( !is_array($user_answer) ){
                                                    $user_answer = $user_answer;
                                                }
                                                else{
                                                    $user_answer = '';
                                                }
                                                $question_type_content .= '<div class="ays-survey-each-question-answer ays-survey-answer">
                                                    <p class="ays_text_answer">' . $user_answer . '</p>
                                                </div>';
                                            }
            
                                            if( $question['type'] == 'linear_scale' ){
            
                                                $linear_scale_label_1 = isset( $question['options']['linear_scale_1'] ) && $question['options']['linear_scale_1'] != '' ? $question['options']['linear_scale_1'] : '';
                                                $linear_scale_label_2 = isset( $question['options']['linear_scale_2'] ) && $question['options']['linear_scale_2'] != '' ? $question['options']['linear_scale_2'] : '';
                                                $linear_scale_length = isset( $question['options']['scale_length'] ) && $question['options']['scale_length'] != '' ? absint( $question['options']['scale_length'] ) : 5;
            
                                                $scale_length_for_width = array(
                                                    '3'  => 67,
                                                    '4'  => 64,
                                                    '5'  => 57,
                                                    '6'  => 54,
                                                    '7'  => 49,
                                                    '8'  => 44,
                                                    '9'  => 41,
                                                    '10' => 38,
                                                );
                                        
                                                $scale_length_for_left = array(
                                                    '3'  => 58,
                                                    '4'  => 45,
                                                    '5'  => 42,
                                                    '6'  => 30,
                                                    '7'  => 29,
                                                    '8'  => 31,
                                                    '9'  => 27,
                                                    '10' => 25,
                                                );
                                                $elegant_theme_linear_length = 'style="width: calc('.$linear_scale_length.'*'.$scale_length_for_width[$linear_scale_length].'px); left: '.$scale_length_for_left[$linear_scale_length].'px"';

                                                $question_type_content .= '<div class="ays-survey-each-question-answer">';
            
                                                $question_type_content .= '<div class="ays-survey-answer-linear-scale">
                                                        <label class="ays-survey-answer-linear-scale-label">
                                                            <div class="ays-survey-answer-linear-scale-radio-label" dir="auto"></div>
                                                            <div class="ays-survey-answer-linear-scale-radio">' . stripslashes( $linear_scale_label_1 ) . '</div>
                                                        </label>';

                                                        if($is_elegant){
                                                            $question_type_content .= '<div class="' . $this->html_class_prefix . 'elegant-answer-linear-scale-line-content">';
                                                                $question_type_content .= '<div class="' . $this->html_class_prefix . 'elegant-answer-linear-scale-line" '.$elegant_theme_linear_length.'>';
                                                                $question_type_content .= '</div>';
                                                            $question_type_content .= '</div>';
                                                        }
                                                        
                                                        for ($i=1; $i <= $linear_scale_length; $i++) {
                                                            $checked = '';
                                                            if( intval( $user_answer ) == $i ){
                                                                $checked = 'checked';
                                                            }
            
                                                            $question_type_content .= '<label class="ays-survey-answer-label '.$business_checkmark_label_container.'">
                                                                <div class="ays-survey-answer-linear-scale-radio-label" dir="auto">' . $i . '</div>
                                                                <div class="ays-survey-answer-linear-scale-radio '.$elegant_theme_answer_hover.'">';
                                                                    if($is_business){
                                                                        $question_type_content .='<input type="radio" name="ays-survey-question-linear-scale-' . $question['id'] . '" disabled ' . $checked . ' value="'.$i.'" data-id="' . $i . '" class="' . $this->html_class_prefix . 'business-theme-answers">
                                                                        <span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_class_prefix . 'maker-checkmark-linear-scale"></span>';
                                                                    }else{
                                                                        $question_type_content .='<input type="radio" name="ays-survey-question-linear-scale-' . $question['id'] . '" disabled ' . $checked . ' value="'.$i.'" data-id="' . $i . '" class="'.$elegant_theme_answers.'">';
                                                                    }
                                                                    $question_type_content .= '<div class="ays-survey-answer-label-content">
                                                                        <div class="ays-survey-answer-icon-content">
                                                                            <div class="ays-survey-answer-icon-ink"></div>
                                                                            <div class="ays-survey-answer-icon-content-1">
                                                                                <div class="ays-survey-answer-icon-content-2">
                                                                                    <div class="ays-survey-answer-icon-content-3"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>';
                                                                    $question_type_content .= '</div>';
                                                                $question_type_content .= '</div>';
                                                            $question_type_content .= '</label>';
                                                        }
            
                                                $question_type_content .= '<label class="ays-survey-answer-linear-scale-label">
                                                            <div class="ays-survey-answer-linear-scale-radio-label" dir="auto"></div>
                                                            <div class="ays-survey-answer-linear-scale-radio">' . stripslashes( $linear_scale_label_2 ) . '</div>
                                                        </label>
                                                    </div>
                                                </div>';
                                            }
            
                                            if( $question['type'] == 'star' ){
            
                                                $star_label_1 = isset( $question['options']['star_1'] ) && $question['options']['star_1'] != '' ? $question['options']['star_1'] : '';
                                                $star_label_2 = isset( $question['options']['star_2'] ) && $question['options']['star_2'] != '' ? $question['options']['star_2'] : '';
                                                $star_scale_length = isset( $question['options']['star_scale_length'] ) && $question['options']['star_scale_length'] != '' ? absint( $question['options']['star_scale_length'] ) : 5;
            
                                                $question_type_content .= '<div class="ays-survey-each-question-answer">';
            
                                                $question_type_content .= '<div class="ays-survey-individual-submission-conatiner-star '.$business_star_answer.'">
                                                        <label class="ays-survey-individual-submission-conatiner-star-label-1">
                                                            <div class="" dir="auto"></div>
                                                            <div class="">' . stripslashes( $star_label_1 ) . '</div>
                                                        </label>';
                                                        
                                                        for ($i=1; $i <= $star_scale_length; $i++) {
                                                            $checked = '';
                                                            $icon_class = 'ays_fa_star_o';
                                                            if( intval( $user_answer ) >= $i ){
                                                                $checked = 'checked';
                                                                $icon_class = 'ays_fa_star';
                                                            }
                                                            $style = '';
                                                            if($is_business){
                                                                if( intval( $user_answer ) >= $i ){
                                                                    $style .= 'style="color: #fc0"';
                                                                }else{
                                                                    $style .= 'style="color: #fff"';
                                                                }
                                                                $icon_class = 'ays_fa_star';
                                                            }

            
                                                            $question_type_content .= '<label class="ays-survey-individual-submission-conatiner-star-label-stars">
                                                                <div class="" dir="auto">' . $i . '</div>
                                                                <div class="">
                                                                    <i class="ays-fa ' . $icon_class . '" '.$style.'></i>
                                                                </div>
                                                            </label>';
                                                        }
            
                                                $question_type_content .= '<label class="ays-survey-individual-submission-conatiner-star-label-2">
                                                            <div class="" dir="auto"></div>
                                                            <div class="">' . stripslashes( $star_label_2 ) . '</div>
                                                        </label>
                                                    </div>
                                                </div>';
                                            }

                                            if( $question['type'] == 'matrix_scale' || $question['type'] == 'matrix_scale_checkbox'){
                                                $question_type_content .= '<div class="ays-survey-answer-matrix-scale-main">
                                                    <div class="ays-survey-answer-matrix-scale-container">';
                                            }

                                            if( $question['type'] == 'star_list' ){
                                                $star_list_stars_length = isset( $question['options']['star_list_stars_length'] ) && $question['options']['star_list_stars_length'] != '' ? absint( $question['options']['star_list_stars_length'] ) : 5;
                                                $question_type_content .= '<div class="ays-survey-answer-star-list-main">
                                                    <div class="ays-survey-answer-star-list-container">';
                                            }

                                            if( $question['type'] == 'slider_list' ){
                                                $question_type_content .= '<div class="ays-survey-answer-slider-list-main">
                                                    <div class="ays-survey-answer-slider-list-container">';
                                            }
            
                                            if( $question['type'] == 'range' ){
            
                                                $range_type_length = isset( $question['options']['range_length'] ) && $question['options']['range_length'] != '' ? esc_attr(intval($question['options']['range_length'])) : 100;
                                                $range_type_step_length = isset( $question['options']['range_step_length'] ) && $question['options']['range_step_length'] != '' ? esc_attr(intval($question['options']['range_step_length'])) : 1;
                                                $range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? esc_attr(intval($question['options']['range_min_value'])) : 0;
                                                $range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? esc_attr(intval($question['options']['range_default_value'])) : 0;
                                                if($range_type_length == 0){
                                                    $range_type_length = 100;
                                                }
                                                if($range_type_step_length == 0){
                                                    $range_type_step_length = 1;
                                                }
                                                if($user_answer == ""){
                                                    $user_answer = 0;
                                                }
                                                $user_range_answer = absint( $user_answer );
                                                $left = 0;
                                                
                                                $left = ( $user_range_answer - $range_type_min_value ) * 100 / ( $range_type_length - $range_type_min_value );
                                                // if(intval($user_range_answer) < ($range_type_length - $range_type_min_value) / 2){
                                                //     $left += 5;
                                                // }else{
                                                //     $left -= 5;
                                                // }
                                                // if($left < 0){
                                                //     $left = 0;
                                                // }
            
                                                $leftOffset = 'calc( ' .  $left . '% + ' . ( 9 - $left * 0.18 ) . 'px )';
                                                $question_type_content .= '<div class="ays-survey-each-question-answer ays-survey-answer ">';
                                                    $question_type_content .= '<div class="ays-survey-answer-range-type-main">';
                                                    $question_type_content .= '<div class="ays-survey-answer-range-type-min-max-val">' . __( 'Min', $this->plugin_name ) . ' ' . $range_type_min_value . '</div>';
            
                                                        $question_type_content .= '<div class="ays-survey-answer-range-type-range">';
                                                            $question_type_content .= '<span class="ays-survey-answer-range-type-info-text  ays-survey-answer-range-type-main-show" style="left: '. $leftOffset .';">'.$user_range_answer.'</span>';
                                                            $question_type_content .= '<input type="range" class="ays-survey-range-type-input" min="' . $range_type_min_value . '" max="'.$range_type_length.'" value="'.$user_range_answer.'" disabled>';
                                                        $question_type_content .= '</div>';
                                                        
                                                        $question_type_content .= '<div class="ays-survey-answer-range-type-min-max-val">' . __( 'Max', $this->plugin_name ) . ' ' . $range_type_length . '</div>';
                                                    $question_type_content .= '</div>';
                                                $question_type_content .= '</div>';
                                            }
                                            
                                            if( $question['type'] == 'upload' ){
                                                $file_name = "";
                                                $enable_upload_container = false;
                                                if($user_answer != '0'){
                                                    $file_name = wp_basename($user_answer);
                                                    $enable_upload_container = true;
                                                }
                                                $upload_container = $enable_upload_container ? "" : "display_none_not_important";
                                                $dont_show_box = $file_name ? "" : "display_none";
                                                $question_type_content .= '<div class="ays-survey-answer-upload-ready '.$upload_container.'" >';
                                                    // if($file_name){
                                                        $question_type_content .= '<a href="'.$user_answer.'" class="ays-survey-answer-upload-ready-link '.$dont_show_box.'" download="'.$file_name.'">'.$file_name.'</a>';
                                                    // }
                                                $question_type_content .= '</div>';
                                            }
            
                                            $loop_iteration = 0;
                                            $width = 0;
                                            
                                            foreach ($question['answers'] as $key => $answer) {
                                                $checked = '';
                                                $selected = '';
                                                $disabled = 'disabled';
                                                $answer_content = $allow_html_in_answers ? $answer['answer'] : htmlentities( $answer['answer'] );
                                                $elegant_theme_answer_active = '';
                                                switch( $question['type'] ){
                                                    case 'radio':
                                                    case 'yesorno':
                                                        if( intval( $user_answer ) == intval( $answer['id'] ) ){
                                                            $checked = 'checked';
                                                            $elegant_theme_answer_active = $is_elegant ? $this->html_class_prefix . 'answer-elegant-active' : '';
                                                        }
                                                        $question_type_content .= '<div class="ays-survey-each-question-answer ays-survey-answer '.$elegant_theme_answer.' '.$elegant_theme_answer_hover.' '.$elegant_theme_answer_active.'">
                                                            <label class="ays-survey-answer-label '.$business_checkmark_label_container.'">';
                                                                if($is_business){
                                                                    $question_type_content .= '<input type="radio" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '" class="' . $this->html_class_prefix . 'business-theme-answers">
                                                                    <span class="' . $this->html_name_prefix . 'maker-checkmark"></span>';
                                                                }else{
                                                                    $question_type_content .= '<input type="radio" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '">';
                                                                }
                                                                $question_type_content .= '<div class="ays-survey-answer-label-content">
                                                                    <div class="ays-survey-answer-icon-content">
                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <span style="font-size: 17px;">' . stripslashes( $answer_content ) . '</span> 
                                                                </div>
                                                            </label>
                                                        </div>';
                                                        break;
                                                    case 'checkbox':
                                                        if( is_array( $user_answer ) && !empty( $user_answer ) && in_array( $answer['id'], $user_answer ) ){
                                                            $checked = 'checked';
                                                        }elseif( intval( $user_answer ) == intval( $answer['id'] ) ){
                                                            $checked = 'checked';
                                                        }
                                                        $elegant_theme_answer_active = $is_elegant ? $this->html_class_prefix . 'answer-elegant-active' : '';
                                                        $question_type_content .= '<div class="ays-survey-each-question-answer ays-survey-answer '.$elegant_theme_answer.' '.$elegant_theme_answer_hover.' '.$elegant_theme_answer_active.'">
                                                            <label class="ays-survey-answer-label '.$business_checkmark_label_container.'">';
                                                                if($is_business){
                                                                    $question_type_content .= '<input type="checkbox" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '"class="' . $this->html_class_prefix . 'business-theme-answers">
                                                                    <span class="' . $this->html_name_prefix . 'maker-checkmark ' . $this->html_name_prefix . 'maker-checkmark-checkbox"></span>';
                                                                }else{
                                                                    $question_type_content .= '<input type="checkbox" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '">';
                                                                }
                                                                $question_type_content .= '<div class="ays-survey-answer-label-content">
                                                                    <div class="ays-survey-answer-icon-content">
                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <span style="font-size: 17px;">' . stripslashes( $answer_content ) . '</span> 
                                                                </div>
                                                            </label>
                                                        </div>';
                                                        break;
                                                    case 'select':
                                                        if( intval( $user_answer ) == intval( $answer['id'] ) ){
                                                            $selected = 'selected';
                                                        }
                                                        $question_type_content .= '<option value=' . $answer['id'] . ' ' . $selected . '>' . stripslashes( $answer_content ) . '</option>';
                                                        break;
                                                    case 'matrix_scale':
                                                    case 'matrix_scale_checkbox':
                                                        $m_content = array();
                                                        $row_spacer = '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
                                                            if($loop_iteration == 0){
                                                                $m_content[] = '<div class="ays-survey-answer-matrix-scale-row">';
                                                                    $m_content[] = '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header"></div>';
                                                                        foreach($matrix_column_ids as $q_key => $q_value){
                                                                            $m_content[] = '<div class="ays-survey-answer-matrix-scale-column">' . $q_value . '</div>';
                                                                        }
                                                                    $m_content[] = "</div>";
                                                                $m_content[] = $row_spacer;
                                                            }
                                                        
                                                            $rows_content = array();
                                                            $rows_content[] = '<div class="ays-survey-answer-matrix-scale-row">';
                                                                $rows_content[] = '<div class="ays-survey-answer-matrix-scale-row-content">';
                                                                $rows_content[] = '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header">' . stripslashes( $answer_content ) . '</div>';
                                                                foreach($matrix_column_ids as $a_key => $a_value){
                                                                    $checked = '';
                                                                    $question_matrix_type = "radio";
                                                                    if($question['type'] == 'matrix_scale_checkbox'){
                                                                        $question_matrix_type = "checkbox";
                                                                        if(isset($user_matrix_answer[$answer['id']]) && in_array($a_key , $user_matrix_answer[$answer['id']])){
                                                                            $checked = 'checked';
                                                                        }
                                                                    }
                                                                    else{
                                                                        if(isset($user_matrix_answer[$answer['id']]) && $user_matrix_answer[$answer['id']] == $a_key){
                                                                            $checked = 'checked';
                                                                        }
                                                                    }
                                                                    $elegant_theme_answer_active = $is_elegant ? $this->html_class_prefix . 'answer-elegant-active' : '';
                                                                    $rows_content[] = '<div class="ays-survey-answer-matrix-scale-column ays-survey-each-question-answer ays-survey-answer '.$elegant_theme_answer_active.'">';
                                                                        $rows_content[] = '<div class="ays-survey-answer-matrix-scale-column-content-wrap">';
                                                                            $rows_content[] = '<div class="ays-survey-answer-matrix-scale-column-content">';
            
                                                                                $rows_content[] = '<label class="ays-survey-answer-label ays-survey-answer-label-matrix-row '.$business_checkmark_label_container.'">';
                                                                                    if( $is_business ){
                                                                                        $rows_content[] = '<input class="' . $this->html_class_prefix . 'business-theme-answers" type="'.$question_matrix_type.'" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '" data-col-id="'.$a_key.'">';
                                                                                        $checkmark_checkbox = $question_matrix_type == "checkbox" ?  $this->html_name_prefix  ."maker-checkmark-checkbox" : "";
                                                                                        $rows_content[] = '<span class="' . $this->html_name_prefix . 'maker-checkmark '.$checkmark_checkbox.'"></span>';
                                                                                    }else{
                                                                                        $rows_content[] = '<input class="" type="'.$question_matrix_type.'" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '" data-col-id="'.$a_key.'">';
                                                                                    }
                                                                                        $rows_content[] = '<div class="ays-survey-answer-label-content">';
                                                                                            $rows_content[] = '<div class="ays-survey-answer-icon-content">';
                                                                                                $rows_content[] = '<div class="ays-survey-answer-icon-ink"></div>';
                                                                                                $rows_content[] = '<div class="ays-survey-answer-icon-content-1">';
                                                                                                    $rows_content[] = '<div class="ays-survey-answer-icon-content-2">';
                                                                                                        $rows_content[] = '<div class="ays-survey-answer-icon-content-3"></div>';
                                                                                                    $rows_content[] = '</div>';
                                                                                                $rows_content[] = '</div>';
                                                                                            $rows_content[] = '</div>';
                                                                                        $rows_content[] = '</div>';
                                                                                $rows_content[] = '</label>';
            
                                                                            $rows_content[] = '</div>';
                                                                        $rows_content[] = '</div>';
                                                                    $rows_content[] = '</div>';
                                                                }
                                                                $rows_content[] = '</div>';
                                                            $rows_content[] = '</div>';
                                                            $rows_content[] = $row_spacer;
            
                                                            $m_content[] = implode( '', $rows_content );
                                                        
                                                        $question_type_content .= implode( '', $m_content );
                                                    break;
                                                    case 'star_list':
                                                        $s_content = array();
                                                        $star_list_content = array();
            
                                                        $row_spacer = '<div class="ays-survey-answer-star-list-row-spacer"></div>';
                                                            if($loop_iteration == 0){
                                                                $star_list_content[] = '<div class="ays-survey-answer-star-list-row">';
                                                                    $star_list_content[] = '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header"></div>';
                                                                    $star_list_content[] = '<div class="ays-survey-answer-star ays-survey-answer-stars-star-list">';
                                                                            for($i=1; $i <= $star_list_stars_length; $i++){
                                                                                $checked = '';
                                                                                $icon_class = 'ays_fa_star_o';
                                                                                if( isset( $user_star_list_answer[$answer['id']] ) && intval( $user_star_list_answer[$answer['id']] ) >= $i ){
                                                                                    $checked = 'checked';
                                                                                    $icon_class = 'ays_fa_star';
                                                                                }
                                                                                $style = '';
                                                                                if($is_business){
                                                                                    if( isset( $user_star_list_answer[$answer['id']] ) && intval( $user_star_list_answer[$answer['id']] ) >= $i ){
                                                                                        $style .= 'style="color: #fc0"';
                                                                                    }else{
                                                                                        $style .= 'style="color: #fff"';
                                                                                    }
                                                                                    $icon_class = 'ays_fa_star';
                                                                                }
                                                                                $star_list_content[] = '<label class="ays-survey-answer-label '.$business_checkmark_label_container.'" style="margin:0">
                                                                                <div class="ays-survey-answer-star-radio-label" dir="auto">' . $i . '</div>
                                                                                    
                                                                                </label>';
                                                                            }
                                                                        $star_list_content[] = "</div>";
                                                                    $star_list_content[] = "</div>";
                                                                $star_list_content[] = $row_spacer;
                                                            }
                                                            $star_list_content[] = '<div class="ays-survey-answer-star-list-row">';
                                                                $star_list_content[] = '<div class="ays-survey-answer-star-list-row-content">';
                                                                
                                                                    $star_list_content[] = '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header" data-answer-id="'.$answer['id'].'">' . stripslashes( $answer_content ) . '</div>';
                                                                    $star_list_content[] = '<div class="ays-survey-star-list-answer-submission-content">';
                                                                                            for ($i=1; $i <= $star_list_stars_length; $i++) {
                                                                                                $checked = '';
                                                                                                $icon_class = 'ays_fa_star_o';
                                                                                                if( isset( $user_star_list_answer[$answer['id']] ) && intval( $user_star_list_answer[$answer['id']] ) >= $i ){
                                                                                                    $checked = 'checked';
                                                                                                    $icon_class = 'ays_fa_star';
                                                                                                }
                                                                                                $style = '';
                                                                                                if($is_business){
                                                                                                    if( isset( $user_star_list_answer[$answer['id']] ) && intval( $user_star_list_answer[$answer['id']] ) >= $i ){
                                                                                                        $style .= 'style="color: #fc0"';
                                                                                                    }else{
                                                                                                        $style .= 'style="color: #fff"';
                                                                                                    }
                                                                                                    $icon_class = 'ays_fa_star';
                                                                                                }
                                                                                                $star_list_content[] = '<label class="ays-survey-star-list-answer-submission-content-label" style="margin:0">
                                                                                                    <div class="ays-survey-star-list-answer-submission-content-div">
                                                                                                        <i class="ays-fa ' . $icon_class . '" '.$style.'></i>
                                                                                                    </div>
                                                                                                </label>';
                                                                                            }
                                                                    $star_list_content[] = '</div>';
                                                                $star_list_content[] = '</div>';
                                                            $star_list_content[] = '</div>';
                                                            $star_list_content[] = $row_spacer;
            
                                                            $s_content[] = implode( '', $star_list_content );
                                                        
                                                        $question_type_content .= implode( '', $s_content );
                                                    break;
                                                    case 'slider_list':
            
                                                        $range_type_length        = (isset( $question['options']['slider_list_range_length'] ) && $question['options']['slider_list_range_length'] != '') ? esc_attr(intval($question['options']['slider_list_range_length'])) : 100;
                                                        $range_type_step_length   = (isset( $question['options']['slider_list_range_step_length'] ) && $question['options']['slider_list_range_step_length'] != '') ? esc_attr(intval($question['options']['slider_list_range_step_length'])) : 1;
                                                        $range_type_min_value     = (isset( $question['options']['slider_list_range_min_value'] ) && $question['options']['slider_list_range_min_value'] != '') ? esc_attr(intval($question['options']['slider_list_range_min_value'])) : 0;
                                                        $range_type_default_value = (isset( $question['options']['slider_list_range_default_value'] ) && $question['options']['slider_list_range_default_value'] != '') ? esc_attr(intval($question['options']['slider_list_range_default_value'])) : 0;
                                                        if($range_type_length == 0){
                                                            $range_type_length = 100;
                                                        }
                                                        if($range_type_step_length == 0){
                                                            $range_type_step_length = 1;
                                                        }
                                                        if($user_answer == ""){
                                                            $user_answer = 0;
                                                        }
                                                        $user_range_answer = isset($user_slider_list_answer[$answer['id']]) ? $user_slider_list_answer[$answer['id']] : 0;
                                                        
                                                        $left = 0;
                                                        
                                                        $left = ( $user_range_answer - $range_type_min_value ) * 100 / ( $range_type_length - $range_type_min_value );
            
            
                                                        $leftOffset = 'calc( ' .  $left . '% + ' . ( 9 - $left * 0.18 ) . 'px )';
                                                        
            
                                                        $sl_content = array();
                                                        $slider_list_content = array();
            
                                                        $row_spacer = '<div class="ays-survey-answer-slider-list-row-spacer"></div>';
                                                            if($loop_iteration == 0){
                                                                    $slider_list_content[] = '<div class="ays-survey-answer-slider-list-row">';
                                                                    $slider_list_content[] = '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header"></div>';
                                                                            $slider_list_content[] = '<div class="ays-survey-answer-slider-list-column">';
                                                                                $slider_list_content[] = '<div class="ays-survey-answer-range-type-min-max-val ays-survey-individual-submission-answer-range-type-min-max-val" >';
                                                                                $slider_list_content[] = __("Min ".$range_type_min_value , $this->plugin_name);
                                                                                $slider_list_content[] = " / ";
                                                                                $slider_list_content[] = __("Max ".$range_type_length , $this->plugin_name);
                                                                                $slider_list_content[] = "</div>";
                                                                            $slider_list_content[] = "</div>";
                                                                    $slider_list_content[] = "</div>";
                                                                $slider_list_content[] = $row_spacer;
                                                            }
                                                            $slider_list_content[] = '<div class="ays-survey-answer-slider-list-row">';
                                                                $slider_list_content[] = '<div class="ays-survey-answer-slider-list-row-content">';
                                                                
                                                                    $slider_list_content[] = '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header ays-survey-answer-slider-list-column-row-header-only-slider" data-answer-id="'.$answer['id'].'" >' . stripslashes( $answer_content ) . '</div>';
                                                                        $slider_list_content[] = '<div class="ays-survey-answer-range-type-main ays-survey-ind-submission-answer-range-type-main">';
                                                                            $slider_list_content[] = '<div class="ays-survey-answer-range-type-range">';
                                                                                $slider_list_content[] = '<span class="ays-survey-answer-range-type-info-text ays-survey-answer-range-type-main-show" style="left: '. $leftOffset .';">'.$user_range_answer.'</span>';
                                                                                $slider_list_content[] = '<input type="range" class="ays-survey-range-type-input" min="' . $range_type_min_value . '" max="'.$range_type_length.'" value="'.$user_range_answer.'" disabled>';
                                                                            $slider_list_content[] = '</div>';
                                                                        $slider_list_content[] = '</div>';
                                                                $slider_list_content[] = '</div>';
                                                            $slider_list_content[] = '</div>';
                                                            $slider_list_content[] = $row_spacer;
            
                                                            $sl_content[] = implode( '', $slider_list_content );
                                                        
                                                        $question_type_content .= implode( '', $sl_content );
                                                    break;
                                                }
                                                $loop_iteration++;
                                            }
                                            
                                            if( ( $question['type'] == 'radio' || $question['type'] == 'checkbox' || $question['type'] == 'yesorno' ) && $question['user_variant'] == 'on' ){
                                                $checked = '';
                                                if( $question['type'] == 'radio' && intval( $user_answer ) == 0 && $other_answer != "" ){
                                                    $checked = 'checked';
                                                }
            
                                                if( $question['type'] == 'checkbox' && !empty( $user_answer ) && in_array( '0', $user_answer ) ){
                                                    $checked = 'checked';
                                                }
            
                                                if( $question['type'] == 'yesorno' && intval( $user_answer ) == 0 && $other_answer != "" ){
                                                    $checked = 'checked';
                                                }
            
                                                $input_type = $question['type'];
                                                if( $question['type'] == 'yesorno' ){
                                                    $input_type = 'radio';
                                                }
            
                                                $elegant_theme_answer_active = $is_elegant ? $this->html_class_prefix . 'answer-elegant-active' : '';

                                                $question_type_content .= '<div class="ays-survey-each-question-answer '.$elegant_theme_answer.' ays-survey-answer-label-other ays-survey-answer ays-survey-ind-submission-other '.$elegant_theme_answer_hover.' '.$elegant_theme_answer_active.' '. $elegant_theme_style_for_other_answer.'">
                                                    <label class="ays-survey-answer-label  ays-survey-answer-label-other ays-survey-answer '.$business_checkmark_label_container.'">';
                                                    $checkmark_other = '';
                                                    if($is_business){
                                                        $question_type_content .= '<input type="'. $input_type .'" ' . $checked . ' ' . $disabled . ' data-id="0" class="' . $this->html_class_prefix . 'business-theme-answers">' ;
                                                        $checkmark_checkbox = $input_type == "checkbox" ?  $this->html_name_prefix  ."maker-checkmark-checkbox" : "";
                                                        $checkmark_other = $this->html_name_prefix  ."maker-checkmark-other";
                                                        $question_type_content .= '<span class="' . $this->html_name_prefix . 'maker-checkmark '.$checkmark_checkbox.' '.$checkmark_other.'"></span>';
                                                    }else{
                                                        $question_type_content .= '<input type="'. $input_type .'" ' . $checked . ' ' . $disabled . ' data-id="0">';
                                                        $checkmark_other = '';
                                                    }
                                                    $underline_business = '';
                                                    if($is_business){
                                                        $underline_business = 'display:none;';
                                                    }
                                                    $question_type_content .= '<div class="ays-survey-answer-label-content">
                                                            <div class="ays-survey-answer-icon-content">
                                                                <div class="ays-survey-answer-icon-ink"></div>
                                                                <div class="ays-survey-answer-icon-content-1">
                                                                    <div class="ays-survey-answer-icon-content-2">
                                                                        <div class="ays-survey-answer-icon-content-3"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span style="font-size: 17px;">' . __( 'Other', $this->plugin_name ) . ':</span>
                                                        </div>
                                                    </label>
                                                    <div class="ays-survey-answer-other-text">
                                                        <input class="ays-survey-answer-other-input ays-survey-question-input ays-survey-input '.$checkmark_other.'" disabled type="text" value="' . stripslashes( esc_attr( $other_answer ) ) . '" autocomplete="off" tabindex="0">
                                                    
                                                        <div class="ays-survey-input-underline" style="margin:0;'.$underline_business.'"></div>
                                                        <div class="ays-survey-input-underline-animation" style="'.$underline_business.'"></div>
                                                    </div>
                                                </div>';
                                            }
            
                                            if( $question['type'] == 'select' && $key == count( $question['answers'] ) - 1 ){
                                                $question_type_content .= '</select></div>';
                                            }
                                            
                                            if( $question['type'] == 'matrix_scale' || $question['type'] == 'slider_list' || $question['type'] == 'star_list' || $question['type'] == 'matrix_scale_checkbox'){
                                                $question_type_content .= '</div>
                                                                    </div>';
                                            }                                                
                                            $content[] = $question_type_content;
                                        $content[] = '</div>';
                                    }
                                $content[] = '</div>';
                            };
                        };
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';
        }
        $content = implode( '', $content );

        return $content;
    }

    public function ays_survey_single_submission_results_export_public() {
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $questions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        
        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_single_submission_results_export_public') {
            
            $submission_id = (isset($_REQUEST['submission_id']) && sanitize_text_field($_REQUEST['submission_id']) != '') ? absint( sanitize_text_field( $_REQUEST['submission_id'] ) ) : null;

            if ( is_null($submission_id) ) {
                $export_data = array(
                    'status' => false,
                );

                return $export_data;
            }

            $results = $wpdb->get_row("SELECT * FROM {$submissions_table} WHERE id={$submission_id}", "ARRAY_A");

            $each_result = $wpdb->get_results("SELECT question_id,user_explanation FROM {$submissions_questions_table} WHERE submission_id={$submission_id}", "ARRAY_A");
            $each_explanation = array();
            foreach($each_result as $q_key => $q_value){
                $each_explanation[ $q_value['question_id'] ] = $q_value['user_explanation'];
            }

            if ( is_null($results) || empty($results) ) {
                $export_data = array(
                    'status' => false,
                );

                return $export_data;
            }

            $user_id   = (isset($results['user_id']) && sanitize_text_field($results['user_id']) != '') ? absint( sanitize_text_field( $results['user_id'] ) ) : 0;
            $survey_id = (isset($results['survey_id']) && sanitize_text_field($results['survey_id']) != '') ? absint( sanitize_text_field( $results['survey_id'] ) ) : null;

            $user_ip   = (isset($results['user_ip']) && sanitize_text_field($results['user_ip']) != '') ? stripslashes( sanitize_text_field( $results['user_ip'] ) ) : '';

            $duration        = (isset($results['duration']) && sanitize_text_field($results['duration']) != '') ? absint( sanitize_text_field( $results['duration'] ) )."s" : '';

            $submission_date = (isset($results['submission_date']) && sanitize_text_field($results['submission_date']) != '') ? stripslashes( sanitize_text_field( $results['submission_date'] ) ) : '';
            $questions_count = (isset($results['questions_count']) && sanitize_text_field($results['questions_count']) != '') ? stripslashes( sanitize_text_field( $results['questions_count'] ) ) : '';
            $unique_code     = (isset($results['unique_code']) && sanitize_text_field($results['unique_code']) != '') ? stripslashes( sanitize_text_field( $results['unique_code'] ) ) : '';

            if ( $user_id !== 0 ) {
                $user = Survey_Maker_Data::ays_survey_get_user_display_name( $user_id );
                if (! $user || $user == '') {
                    $user = __( "Guest", $this->plugin_name );
                }
            }else{
                $user = __( "Guest", $this->plugin_name );
            }

            $user_email = (isset($results['user_email']) &&  sanitize_text_field($results['user_email']) != '') ? stripslashes( sanitize_text_field( $results['user_email'] ) ) : '';
            $user_name  = (isset($results['user_name']) &&  sanitize_text_field($results['user_name']) != '') ? stripslashes( sanitize_text_field( $results['user_name'] ) ) : '';

            if ($user_ip != '' && $user_ip != '::1') {
                $json    = json_decode(file_get_contents("http://ipinfo.io/{$user_ip}/json"));
                $country = $json->country;
                $region  = $json->region;
                $city    = $json->city;
                $from    = $city . ', ' . $region . ', ' . $country . ', ' . $user_ip;
            }else{
                $from = '';
            }

            $quests      = array();
            $export_data = array();

            $user_information = array(
                array('text' => __( 'User Information', $this->plugin_name ) )
            );
            $quests[] = $user_information;

            $user_information_headers = array(
                __( "User IP", $this->plugin_name ),
                __( "User ID", $this->plugin_name ),
                __( "User", $this->plugin_name ),
                __( "Email", $this->plugin_name ),
                __( "Name", $this->plugin_name ),
            );

            $user_information_results = array(
                $from,
                $user_id."",
                $user,
                $user_email,
                $user_name,
            );

            foreach ($user_information_headers as $key => $value) {
                if ($user_information_results[$key] == '') {
                    $user_information_results[$key] = ' - ';
                }

                $user_results = array(
                    array( 'text' => $user_information_headers[$key] ),
                    array( 'text' => $user_information_results[$key] )
                );
                $quests[] = $user_results;
            }

            $quests[] = array(
                array( 'text' => '' ),
            );

            $survey_information = array(
                array('text' => __( 'Survey Information', $this->plugin_name ) )
            );

            $quests[] = $survey_information;
            $survey_information_headers = array(
                __( "Submission date", $this->plugin_name ),
                __( "Questions count", $this->plugin_name ),
                __( "Duration", $this->plugin_name ),
                __( "Unique code", $this->plugin_name ),
            );
            $survey_information_results = array(
                $submission_date,
                $questions_count,
                $duration,
                $unique_code,
            );
            foreach ($survey_information_headers as $key => $value) {
                if ($survey_information_results[$key] != '') {
                    $user_results = array(
                        array( 'text' => $survey_information_headers[$key] ),
                        array( 'text' => $survey_information_results[$key] )
                    );
                    $quests[] = $user_results;
                }

            }

            $quests[] = array(
                array( 'text' => '' ),
            );

            $questions_headers = array(
                array( 'text' => __( "Questions", $this->plugin_name ) ),
                array( 'text' => __( "User answers", $this->plugin_name ) ),
                array( 'text' => __( "User explanation", $this->plugin_name ) ),
            );
            $quests[] = $questions_headers;
            $questions_ids_str  = ( isset($results['questions_ids']) && ( sanitize_text_field( $results['questions_ids'] ) !== '' || sanitize_text_field( $results['questions_ids'] ) !== null) ) ? stripslashes( sanitize_text_field( $results['questions_ids'] ) ) : '';

            $quest_data        = array();
            $questions_ids_arr = array();
            if ($questions_ids_str != '') {
                $questions_ids_arr = explode(',', $questions_ids_str);

                $attr = array();
                foreach ($questions_ids_arr as $key => $questions_id) {
                    if ($questions_id != '') {
                        $question_id      = absint(intval( $questions_id ));
                        $question_content = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                        $question         = htmlspecialchars_decode( trim( stripslashes($question_content["question"]) ) );
                        $question_type    = isset($question_content['type']) && $question_content['type'] != "" ? $question_content ['type'] : "";
                        $question_options = isset($question_content['options']) && $question_content['options'] != "" ? json_decode($question_content ['options'] , true) : "";

                        if ($question == '') {
                            $question = ' - ';
                        }

                        $attr = array(
                            'submission_id' => $submission_id,
                            'question_id'   => $question_id,
                            'survey_id'     => $survey_id,
                            'question_type'    => $question_type,
                            'question_options' => $question_options,
                            'all_results'   => false,
                        );
                        $user_answered = Survey_Maker_Data::ays_survey_get_user_answered($attr);
                        if($question_type != "matrix_scale" && $question_type != "star_list" && $question_type != "slider_list" && $question_type != "matrix_scale_checkbox"){
                            $user_answer = html_entity_decode( strip_tags( stripslashes( $user_answered ) ) );
                            $user_explanation_text = isset( $each_explanation[ $questions_id ] ) ? $each_explanation[ $questions_id ] : "";
                            $quests[] = array(
                                array( 'text' => $question ),
                                array( 'text' => $user_answer ),
                                array( 'text' => $user_explanation_text ),
                            );
                        }else{
                            $user_answer = $user_answered;
                            $user_explanation_text = isset($each_explanation[$questions_id]) ? $each_explanation[$questions_id] : "";
                            foreach($user_answer as $mat_key => $mat_value){
                                $quests[] = array(
                                    array( 'text' => $question ),
                                    array( 'text' => $mat_key . ": " . $mat_value ),
                                    array( 'text' => $user_explanation_text ),
                                );
                                $question = "";
                                $user_explanation_text = "";
                            }
                        }
                    }
                }
            }

            $export_data = array(
                'status' => true,
                'type'   => 'xlsx',
                'data'   => $quests
            );

            return $export_data;
        }
    }

    public function ays_create_single_user_submission_report( $survey, $options, $send_data, $hide_results_questions_ids = array() ){
        global $wpdb;
       
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $user_id   = get_current_user_id();

        $get_question_submissions = Survey_Maker_Data::ays_survey_question_results( $survey->id, null, null, $user_id, null, false, array() );
        $survey_questions = (isset($get_question_submissions['questions']) && !empty($get_question_submissions['questions'])) ? $get_question_submissions['questions'] : array();
        $question_ids = array_keys($survey_questions);

        //Passed users count
        $survey_users_count_sql = "SELECT COUNT(id) AS submission_count FROM {$submissions_table} WHERE survey_id={$survey->id} AND user_id={$user_id}";
        $survey_passed_users_count = $wpdb->get_var($survey_users_count_sql);
        
        $passed_users_count = isset($survey_passed_users_count) && $survey_passed_users_count != '' ? intval($survey_passed_users_count) : 0;

        $hide_questions_ids_arr = isset($hide_results_questions_ids) && $hide_results_questions_ids != '' ? explode("," , $hide_results_questions_ids) : array();

        $content = '';

        $content .= '<table style="border-collapse:collapse;width: 100%;margin:auto;">';
            $content .= '<tr>';
                $content .= '<th style="text-align:center;font-family: Arial, Helvetica, sans-serif;">
                                <h2>Please find your survey report attached.</h2>';
                $content .= '</th>';
            $content .= '</tr>';
            $content .= '<tr>';
                $content .= '<th style="text-align:center;font-family: Arial, Helvetica, sans-serif;">
                                <p> You have passed this survey '.$passed_users_count.' time.</p>';
                $content .='</th>';
            $content .='</tr>';
        $content .= '</table>';
        foreach ($survey_questions as $key => $survey_question) {
            $survey_question_name = (isset($survey_question['question']) && $survey_question['question'] != '') ? $survey_question['question'] : '';
            $survey_question_id = (isset($survey_question['question_id']) && $survey_question['question_id'] != '') ? $survey_question['question_id'] : '';
            $answers_array = (isset($survey_question['answers']) && !empty($survey_question['answers'])) ? $survey_question['answers'] : array();
            $answers_title_array = (isset($survey_question['answerTitles']) && !empty($survey_question['answerTitles'])) ? $survey_question['answerTitles'] : array();
            $total_answer_count = (isset($survey_question['sum_of_answers_count']) && $survey_question['sum_of_answers_count'] != '') ? intval($survey_question['sum_of_answers_count']) : 0;
            $question_type = (isset($survey_question['question_type']) && $survey_question['question_type'] != '') ? sanitize_text_field($survey_question['question_type']) : '';
            $other_answer = (isset($survey_question['otherAnswers']) && !empty($survey_question['otherAnswers'])) ? $survey_question['otherAnswers'] : array();
            if(isset($hide_questions_ids_arr) && is_array($hide_questions_ids_arr)){
                if(in_array($survey_question_id, $hide_questions_ids_arr)){
                    continue;
                }
            }
            $answer_total_count = '';
            if(!empty($survey_question['otherAnswers'])){
                $answer_total_count = $total_answer_count + intval(count($survey_question['otherAnswers']));
            }else{
                $answer_total_count = $total_answer_count;
            }

            if($question_type != 'name' && $question_type != 'email'){

                if($question_type == 'checkbox' || $question_type == 'radio' || $question_type == 'select' || $question_type == 'yesorno'){
                    $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                        $content .= '<thead>';
                            $content .= '<tr>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                    $content .= $survey_question_name;
                                $content .= '</th>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Total count '.'('.$answer_total_count.')'.'</th>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;"> Percentage </th>';
                            $content .= '</tr>';
                        $content .= '</thead>';
                        $content .= '<tbody>';
                        foreach ($answers_title_array as $key => $answer_title) {
                            $per_answers_count = (isset($answers_array[$key]) && $answers_array[$key] != '') ? intval($answers_array[$key]) : '0';
                            if($answer_total_count != 0){
                                $answer_percentage = (intval($per_answers_count) * 100) / $answer_total_count;
                            }else{
                                $answer_percentage = 0;
                            }
                            $content .= '<tr>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                    $content .= $answer_title;
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                    $content .= $per_answers_count;
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                    $content .= round($answer_percentage).'%';
                                $content .= '</td>';
                            $content .= '</tr>';
                        }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }

                if ($question_type == 'text' || $question_type == 'short_text' || $question_type == 'number' || $question_type == 'phone' || $question_type == 'date' || $question_type == 'time' || $question_type == 'date_time') {
                    foreach ($answers_title_array as $key => $val) {
                        foreach ($val as $key => $answer) {
                            if($question_type == 'date_time' && $answer == "- -") {
                                $answer_total_count = 0;
                            }
                        }
                    }
                    $content .= '<table style="width: 80%;margin:auto;margin-bottom: 30px">';
                        $content .= '<thead>';
                            $content .= '<tr>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;
                                color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                    $content .= $survey_question_name.'(Total Count: '.$answer_total_count.')';
                                $content .= '</th>';
                            $content .= '</tr>';
                        $content .= '</thead>';
                        $content .= '<tbody>';
                        foreach ($answers_title_array as $key => $answer_title) {
                            foreach ($answer_title as $key => $answer_name) {
                                $content .= '<tr>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= $answer_name;
                                    $content .= '</td>';
                                $content .= '</tr>';
                            }
                        }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }

                if( $question_type == 'matrix_scale' || $question_type == 'matrix_scale_checkbox'){
                    $matrix_data = isset( $survey_question['matrix_data'] ) && !empty( $survey_question['matrix_data'] ) ? $survey_question['matrix_data'] : array();
                    $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                        $content .= '<thead>';
                            $content .= '<tr>';
                                $content .= '<th colspan="'. ( !empty( $matrix_data ) ? count( $matrix_data[0] )*2 : 1 ) .'" style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                    $content .= $survey_question_name;
                                $content .= '</th>';
                            $content .= '</tr>';
                        $content .= '</thead>';
                        $content .= '<tbody>';

                        $matrix_print_data = array();
                        foreach ($matrix_data as $key => $row) {
                            if( $key == 0 ){
                                continue;
                            }

                            foreach( $row as $k => $column ){
                                if( $k == 0 ){
                                    continue;
                                }

                                $matrix_print_data['c'.$k] = 0;
                            }
                        }

                        foreach ($matrix_data as $key => $row) {
                            if( $key == 0 ){
                                continue;
                            }

                            foreach( $row as $k => $column ){
                                if( $k == 0 ){
                                    continue;
                                }

                                $matrix_print_data['c'.$k] += $column;
                            }
                        }

                        foreach ($matrix_data as $key => $row) {
                            if( $key == 0 ){
                                $content .= '<tr>';
                                foreach( $row as $k => $column ){
                                    $content .= '<td colspan="2" '. ( $k == 0 ? 'rowspan="2"' : '' ) .' style="border: 1px solid #ddd;padding: 8px;text-align: left;">';
                                        $content .= $column;
                                    $content .= '</td>';
                                }
                                $content .= '</tr>';
                                $content .= '<tr>';
                                
                                foreach( $row as $k => $column ){
                                    if( $column != '' ){
                                        $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;">';
                                            $content .= __( 'Total count', $this->plugin_name ) . ' ('. $matrix_print_data['c'.$k]  .')';
                                        $content .= '</td>';
                                        $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;">';
                                            $content .= __( 'Percentage', $this->plugin_name );
                                        $content .= '</td>';
                                    }
                                }

                                $content .= '</tr>';
                            }else{
                                $content .= '<tr>';
                                foreach( $row as $k => $column ){
                                    if( $k == 0 ){
                                        $content .= '<td colspan="2" style="border: 1px solid #ddd;padding: 8px;text-align: left;">';
                                            $content .= $column;
                                        $content .= '</td>';
                                    }else{
                                        if($matrix_print_data['c'.$k] != 0){
                                            $answer_percentage = (intval($column) * 100) / $matrix_print_data['c'.$k];
                                        }else{
                                            $answer_percentage = 0;
                                        }
                                        $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;">';
                                            $content .= $column;
                                        $content .= '</td>';
                                        $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;">';
                                        $content .= round($answer_percentage).'%';
                                        $content .= '</td>';
                                    }
                                }
                                $content .= '</tr>';
                            }
                        }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }

                if( $question_type == 'star_list' ){
                    $star_list_changeable_answers = isset( $survey_question['star_list_data'][$survey_question_id] ) ? $survey_question['star_list_data'][$survey_question_id] : array();
                    $stars_count = isset($survey_question['question_options']) && $survey_question['question_options'] != "" ? $survey_question['question_options'] : "5";
                    $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                        $content .= '<thead>';
                            $content .= '<tr>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                    $content .= $survey_question_name;
                                $content .= '</th>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Total count '.'( '.$answer_total_count.' )'.'</th>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;"> Stars Count ( '.$stars_count.' ) </th>';
                            $content .= '</tr>';
                            $content .= '<tr>';
                                $content .= '<td style="background-color: #fff;border: 1px solid #dadce0;padding: 8px;text-align: center;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Answers</th>';
                                $content .= '<td style="background-color: #fff;border: 1px solid #dadce0;padding: 8px;text-align: center;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;" >Average</th>';
                                $content .= '<td style="background-color: #fff;border: 1px solid #dadce0;padding: 8px;text-align: center;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;" >Each answer\'s count</th>';
                            $content .= '</tr>';
                        $content .= '</thead>';
                        $content .= '<tbody>';
                        foreach ($answers_title_array as $key => $answer_title) {
                            $current_answer = ( isset($star_list_changeable_answers[$key]) && $star_list_changeable_answers[$key] != "" ) ? $star_list_changeable_answers[$key] : "";
                            $average_sum = 0;
                            $stars_average_percent = 0;
                            $each_answer_count = 0;
                            if(is_array($current_answer)){
                                $each_answer_count = isset( $current_answer['answered_count'] ) && $current_answer['answered_count'] != "" ? $current_answer['answered_count'] : 0;
                                $average_sum = (intval($current_answer['answered_sum'])/intval($current_answer['answered_count']));
                                $average_sum = number_format((float)$average_sum, 1, '.', '');
                            }
                            $content .= '<tr>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;" >';
                                    $content .= $answer_title;
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: center;width:30%;" >';
                                    $content .= $average_sum;
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: center;width:30%;" >';
                                    $content .= $each_answer_count;
                                $content .= '</td>';
                            $content .= '</tr>';
                        }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }

                if( $question_type == 'slider_list' ){
                    $star_list_changeable_answers = isset($survey_question['slider_list_data'][$survey_question_id]) ? $survey_question['slider_list_data'][$survey_question_id] : array();
                    $slider_list_range_min_value = isset($survey_question['slider_list_range_min_value']) && $survey_question['slider_list_range_min_value'] != "" ? $survey_question['slider_list_range_min_value'] : "0";
                    $slider_list_range_max_value = isset($survey_question['slider_list_range_length']) && $survey_question['slider_list_range_length'] != "" ? $survey_question['slider_list_range_length'] : "100";
                    $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                        $content .= '<thead>';
                            $content .= '<tr>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                    $content .= $survey_question_name;
                                $content .= '</th>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Total count '.'( '.$answer_total_count.' )'.'</th>';
                                $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Min '.$slider_list_range_min_value.' / Max '.$slider_list_range_max_value.' </th>';
                            $content .= '</tr>';
                            $content .= '<tr>';
                                $content .= '<td style="background-color: #fff;border: 1px solid #dadce0;padding: 8px;text-align: center;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Answers</th>';
                                $content .= '<td style="background-color: #fff;border: 1px solid #dadce0;padding: 8px;text-align: center;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;" >Average</th>';
                                $content .= '<td style="background-color: #fff;border: 1px solid #dadce0;padding: 8px;text-align: center;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;" >Each answer\'s count</th>';
                            $content .= '</tr>';
                        $content .= '</thead>';
                        $content .= '<tbody>';
                        foreach ($answers_title_array as $key => $answer_title) {
                            $current_answer = ( isset($star_list_changeable_answers[$key]) && $star_list_changeable_answers[$key] != "" ) ? $star_list_changeable_answers[$key] : "";
                            $average_sum = 0;
                            $each_answer_count = 0;
                            if(is_array($current_answer)){
                                $each_answer_count = isset( $current_answer['answered_count'] ) && $current_answer['answered_count'] != "" ? $current_answer['answered_count'] : 0;
                                $average_sum = (intval($current_answer['answered_sum'])/intval($current_answer['answered_count']));
                                $average_sum = number_format((float)$average_sum, 1, '.', '');
                            }
                            $content .= '<tr>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;" >';
                                    $content .= $answer_title;
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: center;width:30%;" >';
                                    $content .= $average_sum;
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: center;width:30%;" >';
                                    $content .= $each_answer_count;
                                $content .= '</td>';
                            $content .= '</tr>';
                        }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }

                if ($question_type == 'linear_scale' || $question_type == 'star') {
                    $loop_start = 1;
                    $loop_count = 5;
                    if(!empty($answers_array[$key])){
                        $get_count_of_array_values = array_count_values($answers_array[$key]);
                        $answers_count = '';
                        $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px;">';
                            $content .= '<thead>';
                                $content .= '<tr>';
                                    $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box; width:30%;">';
                                        $content .= $survey_question_name;
                                    $content .= '</th>';
                                    $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;"> Total count '.'('.$answer_total_count.')'.'</th>';
                                    $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom:12px;box-sizing:border-box;width:30%;"> Percentage </th>';
                                $content .= '</tr>';
                            $content .= '</thead>';
                            $content .= '<tbody>';
                            for ($answer_title = $loop_start; $answer_title <= $loop_count; $answer_title++) {
                                if (is_array($answers_array[$key])) {
                                    if (in_array($answer_title,$answers_array[$key])) {
                                        $answers_count = $get_count_of_array_values[$answer_title];
                                    }else{
                                        $answers_count = 0;
                                    }
                                }
                                if($answer_total_count != 0){
                                    $answer_percentage = (intval($answers_count) * 100) / $answer_total_count;
                                }else{
                                    $answer_percentage = 0;
                                }
                                $content .= '<tr>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= $answer_title;
                                    $content .= '</td>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= $answers_count;
                                    $content .= '</td>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= round($answer_percentage).'%';
                                    $content .= '</td>';
                                $content .= '</tr>';
                            }
                            $content .= '</tbody>';
                        $content .= '</table>';
                    }else{
                        $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px;">';
                            $content .= '<thead>';
                                $content .= '<tr>';
                                    $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box; width:30%;">';
                                        $content .= $survey_question_name;
                                    $content .= '</th>';
                                    $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;"> Total count '.'('.$answer_total_count.')'.'</th>';
                                    $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom:12px;box-sizing:border-box;width:30%;"> Percentage </th>';
                                $content .= '</tr>';
                            $content .= '</thead>';
                            $content .= '<tbody>';
                            for ($answer_title = 1; $answer_title <= $loop_count; $answer_title++) {
                                $content .= '<tr>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= $answer_title;
                                    $content .= '</td>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= '0';
                                    $content .= '</td>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= '0%';
                                    $content .= '</td>';
                                $content .= '</tr>';
                            }
                            $content .= '</tbody>';
                        $content .= '</table>';
                    }
                }

                if ($question_type == 'upload') {
                    $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                    $content .= '<thead>';
                        $content .= '<tr>';
                            $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                $content .= $survey_question_name;
                            $content .= '</th>';
                            $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Total count '.'('.$answer_total_count.')'.'</th>';
                            
                        $content .= '</tr>';
                    $content .= '</thead>';
                    $content .= '<tbody>';
                    
                    foreach ($answers_title_array as $key => $answer_title) {
                        $per_file = (isset($answers_array[$key]) && !empty($answers_array[$key])) ? $answers_array[$key] : '';
                        $uploaded_filename = "";
                        $filename = "";
                        if($per_file != ""){
                            foreach($per_file as $file_key => $file){
                                $uploaded_filename = explode( "/", stripslashes( $file ) );
                                $filename = $uploaded_filename[ count( $uploaded_filename ) - 1 ];
                                $content .= '<tr>';
                                    $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;" colspan="2">';
                                         $content .= '<a href="'. stripslashes( $file ) .'" download="'. $filename .'">' . $filename . '</a>';
                                    $content .= '</td>';
                                $content .= '</tr>';
                            }
                        }
                    }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }
            }
        }

        return $content;
    }

    public function ays_survey_replace_open_tags_for_answers($is_business){
        if($is_business){
            return "label";
        }
        else{
            return "div";
        }
    }

    // Edit previous submission
    public function ays_survey_edit_previous_submission_fn(){
        global $wpdb;
        $user_id   = get_current_user_id();
        $survey_id = isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != '' ? esc_attr($_REQUEST['survey_id']) : '';
        $status = false;
        $survey_name = (array) Survey_Maker_Data::get_survey_by_id( $survey_id );

        if ( empty( $survey_name ) || is_null( $survey_name ) ){
            $message = 'There are no surveys yet';
        }

        $last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id( $survey_id , $user_id );
        $last_submission_id = isset($last_submission['id']) && $last_submission['id'] != '' ? $last_submission['id'] : 0;

        if(!empty($last_submission) || $last_submission_id < 1){            
            $message = 'There is no submission yet';            
        }

        $ays_survey_individual_submissions = Survey_Maker_Data::ays_survey_individual_results_for_one_submission( $last_submission, $survey_name, true );
        
        $survey_questions = isset($ays_survey_individual_submissions['questions']) ? $ays_survey_individual_submissions['questions'] : array();
        $subm_quest_ids_matrix_types = isset($ays_survey_individual_submissions['subm_quest_ids_matrix_types']) ? $ays_survey_individual_submissions['subm_quest_ids_matrix_types'] : array();
        
        if( !empty($survey_questions) ){
            $status = true;
            $message = "success";
            foreach($survey_questions as $question_id => &$question_data){
                $question_data['question_type'] = Survey_Maker_Data::get_question_type_question_id($question_id);
            }
        }
        $_REQUEST = array();
        return $response = array(
            'status' => $status,
            'message' => __($message , $this->plugin_name),
            'questions' => $survey_questions,
            'subm_quest_ids_matrix_types' => $subm_quest_ids_matrix_types,
            'submission_id' => $last_submission_id,
            'user_id' => $user_id
        );
    }

    /* FRONTEND REQUEST START */

    // Frontend request save data	
	public function ays_survey_insert_data_to_db(){
		if(isset($_REQUEST['function']) && $_REQUEST['function'] == 'ays_survey_insert_data_to_db'){
			global $wpdb;
			$requests_table = $wpdb->prefix . "socialsurv_requests";

			$user_id = get_current_user_id();
            $user_ip = Survey_Maker_Data::get_user_ip();
			$category_id = (isset($_REQUEST['ays_survey_front_request_survey_category']) && $_REQUEST['ays_survey_front_request_survey_category'] != '') ? absint($_REQUEST['ays_survey_front_request_survey_category']) : 1;
			$survey_title = (isset($_REQUEST['ays_survey_front_request_survey_title']) && $_REQUEST['ays_survey_front_request_survey_title'] != '') ? sanitize_text_field($_REQUEST['ays_survey_front_request_survey_title']) : 'Survey';
			$survey_data = (isset($_REQUEST['ays_survey_front_request_question']) && !empty($_REQUEST['ays_survey_front_request_question']) ) ? $_REQUEST['ays_survey_front_request_question'] : array();
			$request_data = $survey_data;
			$options = array();

        	$request_result = $wpdb->insert(
                $requests_table,						
                array(
                    'survey_id'     => '',
                    'category_id'   => $category_id,
                    'user_id'       => $user_id,
                    'user_ip'      	=> $user_ip,
                    'survey_title'  => $survey_title,
                    'request_data' 	=> json_encode($request_data),
                    'request_date'  => current_time( 'mysql' ),
                    'status'        => 'Unpublished',
                    'approved'   	=> 'not-approved',
                    'options'       => json_encode($options),
                ),
                array(
                    '%d', // survey_id
                    '%d', // category_id
                    '%d', // user_id
                    '%s', // user_ip
                    '%s', // survey_title
                    '%s', // request_data
                    '%s', // request_date
                    '%s', // status
                    '%s', // approved
                    '%s', // options
                )
            );
			
			$last_request_id = $wpdb->insert_id;

            $settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
			$front_request_options = ($settings_obj->ays_get_setting('front_request_options') === false) ? json_encode(array()) : $settings_obj->ays_get_setting('front_request_options');
			if (! empty($front_request_options)) {
				$front_request_options = json_decode($front_request_options, true);
			}
			if (! empty($front_request_options)) {
				$options = $front_request_options;
			}
            
			$options['survey_front_request_auto_approve'] = (isset($options['survey_front_request_auto_approve']) && $options['survey_front_request_auto_approve'] != '') ? $options['survey_front_request_auto_approve'] : 'off';
			$auto_approve = (isset($options['survey_front_request_auto_approve']) && $options['survey_front_request_auto_approve'] == 'on') ? true : false;

			if ($auto_approve) {
                Survey_Requests_List_Table::mark_as_approved_requests($last_request_id);
            }
			
			if ( $request_result ) {
				$response_text = __( 'Your request has been sent', $this->plugin_name );
			} else {
				$response_text = __( 'Your request has not been sent, please try again.', $this->plugin_name );
			}

			$result = array(
				'status' => true,
				'data'   => $request_result ? true : false,
				'message' => $response_text,
			);

			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode($result);
			wp_die();
		}else{
			$result = array(
				'status' => false,
				'data'   => false,
				'message' => __( "Your request has not been send, please try again.", $this->plugin_name ),
			);

			ob_end_clean();
			$ob_get_clean = ob_get_clean();
			echo json_encode($result);
			wp_die();
		}
	}

    /* FRONTEND REQUEST END */

    // Custom hook for getting finished survey results
    public function ays_survey_get_submission_results_by_unique_code () {
        global $wpdb;
		$unique_code = isset($_GET['uniquecode']) && $_GET['uniquecode'] != "" ? sanitize_text_field($_GET['uniquecode']) : "";

        $sql = "SELECT * FROM `wp_socialsurv_submissions` WHERE unique_code = '" . $unique_code . "'";
        $results = $wpdb->get_row($sql, 'ARRAY_A');
        if ($results) {
            return json_encode($results);
        }
        
        return null;
	}

}