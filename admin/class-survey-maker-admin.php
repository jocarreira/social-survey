<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/admin
 * @author     Survey Maker team <info@ays-pro.com>
 */

use PhpOffice\PhpSpreadsheet\IOFactory;

class Survey_Maker_Admin {

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

    /**
     * The surveys list table object.
	 * Customers - Jeferson Carreira
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $customers_obj    The surveys list table object.
     */
    private $customers_obj;

    /**
     * The surveys list table object.
	 * Customers - Jeferson Carreira
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $samples_obj    The surveys list table object.
     */
    private $samples_obj;

	/**
	 * The surveys list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $surveys_obj    The surveys list table object.
	 */
    private $surveys_obj;

	/**
	 * The surveys categories list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $surveys_categories_obj    The surveys categories list table object.
	 */
    private $surveys_categories_obj;

	/**
	 * The survey questions list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $questions_obj    The survey questions list table object.
	 */
    private $questions_obj;

	/**
	 * The survey questions categories list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $question_categories_obj    The survey questions categories list table object.
	 */
    private $question_categories_obj;

	/**
	 * The survey submissions list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $results_obj    The survey submissions list table object.
	 */
    private $submissions_obj;

	/**
	 * The survey questions categories list table object for each survey.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $each_result_obj    The survey submissions list table object for each survey.
	 */
    private $each_submission_obj;

	/**
	 * The settings object of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $settings_obj    The settings object of this plugin.
	 */
    private $settings_obj;

	/**
	 * The capability of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $capability    The capability for users access to this plugin.
	 */
    private $capability;

	/**
	 * The access of this plugin for editing.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_user_can_edit    The permission for users access to this plugin.
	 */
    private $current_user_can_edit;

    private $orders_obj;
    private $requests_obj;
    private $popup_surveys_obj;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
        // $per_page_array = array(
        //     'quizes_per_page',
        //     'questions_per_page',
        //     'quiz_categories_per_page',
        //     'question_categories_per_page',
        //     'attributes_per_page',
        //     'quiz_results_per_page',
        //     'quiz_each_results_per_page',
        //     'quiz_orders_per_page',
        // );
        // foreach($per_page_array as $option_name){
        //     add_filter('set_screen_option_'.$option_name, array(__CLASS__, 'set_screen'), 10, 3);
        // }

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {

        wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        
        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_style( $this->plugin_name . '-sweetalert-css', SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-sweetalert2.min.css', array(), $this->version, 'all');
        }

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;
            
        // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
        wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui' );

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style( $this->plugin_name . '-banner.css', plugin_dir_url(__FILE__) . 'css/banner.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-animate.css', plugin_dir_url(__FILE__) . 'css/animate.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-animations.css', plugin_dir_url(__FILE__) . 'css/animations.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-font-awesome-icons', plugin_dir_url(__FILE__) . 'css/ays-font-awesome.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-select2', SURVEY_MAKER_PUBLIC_URL .  '/css/survey-maker-select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-transition', SURVEY_MAKER_PUBLIC_URL .  '/css/transition.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-dropdown', SURVEY_MAKER_PUBLIC_URL .  '/css/dropdown.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-popup', plugin_dir_url(__FILE__) . 'css/popup.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-data-bootstrap', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');

        wp_enqueue_style( $this->plugin_name . "-general", plugin_dir_url( __FILE__ ) . 'css/survey-maker-general.css', array(), time(), 'all' );
        wp_enqueue_style( $this->plugin_name . "-affiliate", plugin_dir_url( __FILE__ ) . 'css/survey-maker-affiliate.css', array(), time(), 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/survey-maker-admin.css', array(), time(), 'all' );
        wp_enqueue_style( $this->plugin_name . "-pro-features", plugin_dir_url( __FILE__ ) . 'css/survey-maker-pro-features.css', array(), time(), 'all' );
        wp_enqueue_style( $this->plugin_name . "-loaders", plugin_dir_url(__FILE__) . 'css/loaders.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . "-condition", plugin_dir_url(__FILE__) . 'css/survey-maker-admin-condition.css', array(), $this->version, 'all');

        if ( false !== strpos($hook_suffix, $this->plugin_name . "-each-submission" ) ){
            wp_enqueue_style( $this->plugin_name . "-print-submissions", plugin_dir_url(__FILE__) . 'css/print-submissions.css', array(), $this->version, 'print');
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {
        global $wp_version;

        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.5';
        $versionCompare = Survey_Maker_Data::aysSurveyMakerVersionCompare($version1, $operator, $version2);

        if ($versionCompare) {
            wp_enqueue_script( $this->plugin_name.'-wp-load-scripts', plugin_dir_url(__FILE__) . 'js/survey-maker-wp-load-scripts.js', array(), $this->version, true);
        }

        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script( $this->plugin_name . '-sweetalert-js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->plugin_name . '-admin', 'SurveyMakerAdmin', array( 
            	'ajaxUrl' => admin_url( 'admin-ajax.php' )
            ) );
        }
        
        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        // wp_enqueue_script( 'common' );
        // wp_enqueue_editor();
        wp_enqueue_media();
        wp_enqueue_script( $this->plugin_name . '-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, true );
        $color_picker_strings = array(
            'clear'            => __( 'Clear', $this->plugin_name ),
            'clearAriaLabel'   => __( 'Clear color', $this->plugin_name ),
            'defaultString'    => __( 'Default', $this->plugin_name ),
            'defaultAriaLabel' => __( 'Select default color', $this->plugin_name ),
            'pick'             => __( 'Select Color', $this->plugin_name ),
            'defaultLabel'     => __( 'Color value', $this->plugin_name ),
        );
        wp_localize_script( $this->plugin_name . '-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );


		/* 
        ========================================== 
           * Bootstrap
           * select2
           * jQuery DataTables
        ========================================== 
        */
        wp_enqueue_script( $this->plugin_name . "-popper", plugin_dir_url(__FILE__) . 'js/popper.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . "-bootstrap", plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-select2js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-sweetalert-js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-datatable-min', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-datatable.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-transition-min', SURVEY_MAKER_PUBLIC_URL . '/js/transition.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-dropdown-min', SURVEY_MAKER_PUBLIC_URL . '/js/dropdown.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . "-db4.min.js", plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . "-datetimepicker", plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-autosize', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-autosize.js', array( 'jquery' ), $this->version, false );

        /* 
        =======================================================
           Survey admin dashboard script (Global statistics)
        =======================================================
        */
        if ( strpos($hook_suffix, 'global-statistics') !== false ) {
            wp_enqueue_script( $this->plugin_name . '-charts-google', plugin_dir_url(__FILE__) . 'js/google-chart.js', array('jquery'), $this->version, true);
            wp_enqueue_script( $this->plugin_name . '-global-statistics', plugin_dir_url(__FILE__) . 'js/partials/survey-maker-admin-global-statistics-charts.js', array('jquery'), $this->version, true);
        }

        // if( strpos( $hook_suffix, 'each-submission' ) !== false || strpos($hook_suffix, 'submission') !== false ){
        if( strpos( $hook_suffix, 'each-submission' ) !== false || strpos($hook_suffix, 'submission') !== false || 
            isset( $_GET['page'] ) && ( $_GET['page'] == $this->plugin_name ) && isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ){
            /* 
            ========================================== 
            File exporters
            * SCV
            * xlsx
            ========================================== 
            */
            wp_enqueue_script( $this->plugin_name . "-CSVExport.js", plugin_dir_url( __FILE__ ) . 'js/CSVExport.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . "-xlsx.core.min.js", plugin_dir_url( __FILE__ ) . 'js/xlsx.core.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . "-fileSaver.js", plugin_dir_url( __FILE__ ) . 'js/FileSaver.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . "-jhxlsx.js", plugin_dir_url( __FILE__ ) . 'js/jhxlsx.js', array( 'jquery' ), $this->version, true );
        }

        /* 
        ================================================
           Survey admin dashboard scripts (Google charts)
        ================================================
        */
        if ( strpos($hook_suffix, 'each-submission') !== false ) {
            wp_enqueue_script( $this->plugin_name . '-charts-google', plugin_dir_url(__FILE__) . 'js/google-chart.js', array('jquery'), $this->version, false);
            wp_enqueue_script( $this->plugin_name . "-load-submissions-sections", plugin_dir_url( __FILE__ ) . 'js/partials/survey-maker-admin-load-submissions-sections.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '-charts', plugin_dir_url(__FILE__) . 'js/partials/survey-maker-admin-submissions-charts.js', array('jquery'), $this->version, true);
        }

        /* 
        ================================================
           Survey admin dashboard scripts (Conditions)
        ================================================
        */
        if(isset($_GET['action']) && ($_GET['action'] == "add" || $_GET['action'] == "edit")){
            wp_enqueue_editor();
            wp_enqueue_script( $this->plugin_name."-condition", plugin_dir_url( __FILE__ ) . 'js/survey-maker-admin-condition.js', array( 'jquery' ), $this->version, true );
        }

        if( isset( $_GET['action'] ) && $_GET['action'] == "edit" ){
            wp_enqueue_script( $this->plugin_name."-load-sections", plugin_dir_url( __FILE__ ) . 'js/partials/survey-maker-admin-load-sections.js', array( 'jquery' ), $this->version, true );
        }

        /* 
        ================================================
           Quiz admin dashboard scripts (and for AJAX)
        ================================================
        */
        wp_enqueue_script( $this->plugin_name . "-survey-styles", plugin_dir_url(__FILE__) . 'js/partials/survey-maker-admin-survey-styles.js', array('jquery', 'wp-color-picker'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . "-functions", plugin_dir_url(__FILE__) . 'js/functions.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-questions-library', plugin_dir_url(__FILE__) . 'js/partials/survey-maker-questions-library.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-ajax', plugin_dir_url(__FILE__) . 'js/survey-maker-admin-ajax.js', array('jquery'), $this->version, true);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/survey-maker-admin.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name."-terms-and-conditions", plugin_dir_url( __FILE__ ) . 'js/survey-maker-admin-terms-and-conditions.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( $this->plugin_name, 'SurveyMakerAdmin', array( 
        	'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'inputAnswerText'                   => __( 'Your answer', $this->plugin_name ),
            'emailField'                        => __( 'Your email', $this->plugin_name ),
            'nameField'                         => __( 'Your name', $this->plugin_name ),
            'phoneAnswerText'                   => __( 'Phone', $this->plugin_name ),
            'selectUserRoles'                   => __( 'Select user roles', $this->plugin_name ),
            'addQuestion'                       => __( 'Add question', $this->plugin_name ),
            'addSection'                        => __( 'Add section', $this->plugin_name ),
            'duplicate'                         => __( 'Duplicate', $this->plugin_name ),
            'delete'                            => __( 'Delete', $this->plugin_name ),
            'addImage'                          => __( 'Add Image', $this->plugin_name ),
            'editImage'                         => __( 'Edit Image', $this->plugin_name ),
            'removeImage'                       => __( 'Remove Image', $this->plugin_name ),
            'collapseSectionQuestions'          => __( 'Collapse section questions', $this->plugin_name ),
            'expandSectionQuestions'            => __( 'Expand section questions', $this->plugin_name ),
            'selectQuestionDefaultType'         => __( 'Select question default type', $this->plugin_name ),
            'chooseAnswer'                      => __( 'Choose answer', $this->plugin_name ),
            'yes'                               => __( 'Yes', $this->plugin_name ),
            'cancel'                            => __( 'Cancel', $this->plugin_name ),
            'questionDeleteConfirmation'        => __( 'Are you sure you want to delete this question?', $this->plugin_name ),
            'sectionDeleteConfirmation'         => __( 'Are you sure you want to delete this section?', $this->plugin_name ),
            'loadResource'                      => __( "Can't load resource.", $this->plugin_name ),
            'somethingWentWrong'                => __( "Maybe something went wrong.", $this->plugin_name ),
            'dataDeleted'                       => __( "Maybe the data has been deleted.", $this->plugin_name ),
            'minimumCountOfQuestions'           => __( 'Sorry minimum count of questions should be 1', $this->plugin_name ),
            'enableMaxSelectionCount'           => __( 'Enable max selection count', $this->plugin_name ),
            'disableMaxSelectionCount'          => __( 'Disable max selection count', $this->plugin_name ),
            'enableWordLimitation'              => __( 'Enable word limitation', $this->plugin_name ),
            'disableWordLimitation'             => __( 'Disable word limitation', $this->plugin_name ),
            'enableNumberLimitation'            => __( 'Enable limitation', $this->plugin_name ),
            'disableNumberLimitation'           => __( 'Disable limitation', $this->plugin_name ),
            'successfullySent'                  => __( 'Successfully sent', $this->plugin_name ),
            'failed'                            => __( 'Failed', $this->plugin_name ),
            'selectPage'                        => __( 'Select page', $this->plugin_name ),
            'selectPostType'                    => __( 'Select post type', $this->plugin_name ),
            'addIntoSection'                    => __( 'Add into Section', $this->plugin_name ),
            'goToSection'                       => __( 'Go to section', $this->plugin_name ),
            'untitledForm'                      => __( 'Untitled form', $this->plugin_name ),
            'continueToNextSection'             => __( 'Continue to next section', $this->plugin_name ),
            'submitForm'                        => __( 'Submit form', $this->plugin_name ),
            'copied'                            => __( 'Copied!', $this->plugin_name),
            'clickForCopy'                      => __( 'Click for copy', $this->plugin_name),
            'moveToSection'                     => __( 'Move to section', $this->plugin_name),
            'confirmMessageTemplate'            => __( 'If you choose one of these templates, your questions will be deleted.', $this->plugin_name),
            'insertIntoSection'                 => __( 'Insert into section', $this->plugin_name),
            'selectSurvey'                      => __( 'Select survey', $this->plugin_name),
            'enableSelectionCount'              => __( 'Enable selection count', $this->plugin_name ),
            'disableSelectionCount'             => __( 'Disable selection count', $this->plugin_name ),
            'questionImportEmptyError'          => __( 'The file that you are trying to import is empty. Please, choose another file.', $this->plugin_name),
            'confirmMessageTemplate'            => __( 'If you choose one of these templates, your questions will be deleted.', $this->plugin_name),
            'importQuestions'                   => __( 'Questions have been imported successfully', $this->plugin_name),
            'other'                             => __( 'Other', $this->plugin_name),
            'row'                               => __( 'Row', $this->plugin_name),
            'column'                            => __( 'Column', $this->plugin_name),
            'select_user'                       => __( 'Select users', $this->plugin_name),
            'count'                             => __( 'Count', $this->plugin_name),
            'date'                              => __( 'Date', $this->plugin_name),
            'average'                           => __( 'Average', $this->plugin_name),
            'rating'                            => __( 'Rating', $this->plugin_name),
            'stars_count'                       => __( 'Stars count', $this->plugin_name),
            'answers'                           => __( 'Answers', $this->plugin_name),
            'percent'                           => __( 'Percent', $this->plugin_name),
            'users'                             => __( 'Users', $this->plugin_name),
            'guests'                            => __( 'Guests', $this->plugin_name),
            'addQuestionImageCaption'           => __( 'Add a caption', $this->plugin_name),
            'closeQuestionImageCaption'         => __( 'Close caption', $this->plugin_name),
            'deleteElementFromListTable'        => __( 'Are you sure you want to delete?', "survey-maker"),
            'nonce' => wp_create_nonce( 'ajax-nonce' ),
            'icons' => array(
                'radioButtonUnchecked'  => SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg',
                'checkboxUnchecked'     => SURVEY_MAKER_ADMIN_URL . '/images/icons/checkbox-unchecked.svg',
                'deleteTermsAndConds'   => SURVEY_MAKER_ADMIN_URL. '/images/icons/trash.svg',
            )
        ) );

        //Upload Charts Canvas CDN
        wp_enqueue_script( $this->plugin_name ."-canvashtml",  plugin_dir_url( __FILE__ ) . "js/html2canvas.min.js", array(), $this->version, false );
        wp_enqueue_script( $this->plugin_name ."-canvasimage", plugin_dir_url( __FILE__ ) . "js/survey-maker-canvas2image.js", array(), $this->version, false );
        

    }

    public function ays_survey_add_survey_template() {
        global $wpdb;

        $template_file_name = (isset($_REQUEST['template_file_name']) && $_REQUEST['template_file_name'] != "") ? sanitize_text_field($_REQUEST['template_file_name'] ) : null;
        
        switch($template_file_name){
            case 'customer-feedback-form':
                $json = file_get_contents(SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/templates/customer-feedback-form.json");
            break;
            case 'employee-satisfaction-survey':
                $json = file_get_contents(SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/templates/employee-satisfaction-survey.json");
            break;
            case 'event-evaluation-survey':
                $json = file_get_contents(SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/templates/event-evaluation-survey.json");
            break;
            case 'product-research-survey':
                $json = file_get_contents(SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/templates/product-research-survey.json");
            break;
        }
        
        $response = array();
        $json = json_decode($json, true);
        $json_key = isset($json['ays_survey_key']) ? $json['ays_survey_key'] : false;

        if($json_key) {
            $response = array(
                'status' => true,
                'data'   => $json
            );
            
            echo json_encode( $response );
            wp_die();
        }else{
             $response = array(
                'status' => false
            );
            
            echo json_encode( $response );
            wp_die();  
        }
    } 

    /**
     * De-register JavaScript files for the admin area.
     *
     * @since    1.0.0
     */
    public function disable_scripts($hook_suffix) {
        if (false !== strpos($hook_suffix, $this->plugin_name)) {
            if (is_plugin_active('ai-engine/ai-engine.php')) {
                wp_deregister_script('mwai');
                wp_deregister_script('mwai-vendor');
                wp_dequeue_script('mwai');
                wp_dequeue_script('mwai-vendor');
            }
        }
    }

    
    public function codemirror_enqueue_scripts($hook) {
        if(strpos($hook, $this->plugin_name) !== false){
            if(function_exists('wp_enqueue_code_editor')){
                $cm_settings['codeEditor'] = wp_enqueue_code_editor(array(
                    'type' => 'text/css',
                    'codemirror' => array(
                        'inputStyle' => 'contenteditable',
                        'theme' => 'cobalt',
                    )
                ));

                wp_enqueue_script('wp-theme-plugin-editor');
                wp_localize_script('wp-theme-plugin-editor', 'cm_settings', $cm_settings);

                wp_enqueue_style('wp-codemirror');
            }
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu(){

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        // global $wpdb;
        // $sql = "SELECT COUNT(*) FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions WHERE `read` = 0 OR `read` = 2 ";
        // $unread_results_count = intval( $wpdb->get_var( $sql ) );
        // $menu_item = ($unread_results_count == 0) ? 'Social Survey' : 'Social Survey' . '<span class="ays-survey-menu-badge ays-survey-results-bage">' . $unread_results_count . '</span>';
        
        $setting_actions = new Survey_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);

        // Disable Survey Maker menu item notification
        $options['survey_disable_survey_menu_notification'] = isset($options['survey_disable_survey_menu_notification']) ? esc_attr( $options['survey_disable_survey_menu_notification'] ) : 'off';
        $survey_disable_survey_menu_notification = (isset($options['survey_disable_survey_menu_notification']) && esc_attr( $options['survey_disable_survey_menu_notification'] ) == "on") ? true : false;

        if( $survey_disable_survey_menu_notification ){
            $menu_item = 'Social Survey';
        } else {
            global $wpdb;
            $sql = "SELECT COUNT(*) FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions WHERE `read` = 0 OR `read` = 2 ";
            $unread_results_count = intval( $wpdb->get_var( $sql ) );
            $menu_item = ($unread_results_count == 0) ? 'Social Survey' : 'Social Survey' . '<span class="ays-survey-menu-badge ays-survey-results-bage">' . $unread_results_count . '</span>';
        }

        $this->capability = $this->survey_maker_capabilities();
        $this->current_user_can_edit = Survey_Maker_Data::survey_maker_capabilities_for_editing();
                
        add_menu_page(
            'Social Survey', 
            $menu_item,
            $this->capability,
            $this->plugin_name,
            array($this, 'display_plugin_surveys_page'), 
            SURVEY_MAKER_ADMIN_URL . '/images/icons/survey-make-menu-logo.svg',
            '6.21'
        );
    }

    public function add_plugin_surveys_submenu(){
        $hook_survey_maker = add_submenu_page(
            $this->plugin_name,
            __('Pesquisas', $this->plugin_name),
            __('Pesquisas', $this->plugin_name),
            $this->capability,
            $this->plugin_name,
            array($this, 'display_plugin_surveys_page')
        );

        add_action("load-$hook_survey_maker", array($this, 'screen_option_surveys'));
    }

    /**
     * Customers (Clientes) - Novo ítem submenu
     * Jeferson Carreira
     */
    public function add_plugin_customers_submenu(){
        $hook_customer_maker = add_submenu_page(
            $this->plugin_name,
            __('Clientes', $this->plugin_name),
            __('Clientes', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-customers',
            array($this, 'display_plugin_customers_page')
        );

        add_action("load-$hook_customer_maker", array($this, 'screen_option_customers'));
    }

    /**
     * Customers (Clientes) - Novo ítem submenu
     * Jeferson Carreira
     */
    public function add_plugin_interviewers_submenu(){
        $hook_customer_maker = add_submenu_page(
            $this->plugin_name,
            __('Entrevistadores', $this->plugin_name),
            __('Entrevistadores', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-interviewers',
            array($this, 'display_plugin_interviewers_page')
        );

        add_action("load-$hook_customer_maker", array($this, 'screen_option_interviewers'));
    }

    public function add_plugin_export_import_submenu(){
        $hook_exp_imp = add_submenu_page(
            $this->plugin_name,
            __('Exportação / Importação', $this->plugin_name),
            __('Exportação / Importação', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-export-import',
            array($this, 'display_plugin_export_import_page')
        );

        // add_action("load-$hook_exp_imp", array($this, 'screen_option_questions'));
    }

    public function add_plugin_survey_categories_submenu(){
        $hook_survey_categories = add_submenu_page(
            $this->plugin_name,
            __('Categorias de Pesquisa', $this->plugin_name),
            __('Categorias de Pesquisa', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-survey-categories',
            array($this, 'display_plugin_survey_categories_page')
        );

        add_action("load-$hook_survey_categories", array($this, 'screen_option_survey_categories'));
    }

    public function add_plugin_popup_surveys_submenu(){
        $hook_popup_surveys = add_submenu_page(
            $this->plugin_name,
            __('Popup de Pesquisa', $this->plugin_name),
            __('Popup de Pesquisa', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-popup-surveys',
            array($this, 'display_plugin_popup_surveys_page')
        );

        add_action("load-$hook_popup_surveys", array($this, 'screen_option_popup_surveys'));
    }

    public function add_plugin_submissions_submenu(){
        global $wpdb;
        
        // $sql = "SELECT COUNT(*) FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions WHERE `read` = 0 OR `read` = 2 ";
        // $unread_results_count = intval( $wpdb->get_var( $sql ) );

        // $results_text = __('Submissions', $this->plugin_name);
        // $menu_item = ( $unread_results_count == 0 ) ? $results_text : $results_text . '<span class="ays-survey-menu-badge ays-survey-results-bage">' . $unread_results_count . '</span>';

        $setting_actions = new Survey_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);

        // Disable Submissions menu item notification
        $options['survey_disable_submission_menu_notification'] = isset($options['survey_disable_submission_menu_notification']) ? esc_attr( $options['survey_disable_submission_menu_notification'] ) : 'off';
        $survey_disable_submission_menu_notification = (isset($options['survey_disable_submission_menu_notification']) && esc_attr( $options['survey_disable_submission_menu_notification'] ) == "on") ? true : false;

        if( $survey_disable_submission_menu_notification ){
            $results_text = __('Submissões', "survey-maker");
            $menu_item    = __('Submissões', "survey-maker");
        } else {
            $sql = "SELECT COUNT(*) FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions WHERE `read` = 0 OR `read` = 2 ";
            $unread_results_count = intval( $wpdb->get_var( $sql ) );

            $results_text = __('Submissões', "survey-maker");
            $menu_item = ( $unread_results_count == 0 ) ? $results_text : $results_text . '<span class="ays-survey-menu-badge ays-survey-results-bage">' . $unread_results_count . '</span>';
        }

        $hook_submissions = add_submenu_page(
            $this->plugin_name,
            $results_text,
            $menu_item,
            $this->capability,
            $this->plugin_name . '-submissions',
            array($this, 'display_plugin_submissions_page')
        );

        add_action("load-$hook_submissions", array($this, 'screen_option_submissions'));
        
        $hook_each_submission = add_submenu_page(
            'each_submission_slug',
            __('Each', $this->plugin_name),
            null,
            $this->capability,
            $this->plugin_name . '-each-submission',
            array($this, 'display_plugin_each_submission_page')
        );

        add_action("load-$hook_each_submission", array($this, 'screen_option_each_survey_submission'));

        $hook_global_statistics = add_submenu_page(
            'global_statistics_slug',
            __('Submissions', $this->plugin_name),
            null,
            $this->capability,
            $this->plugin_name . '-global-statistics',
            array($this, 'display_plugin_global_statistics_page')
        );

        add_action("load-$hook_global_statistics", array($this, 'screen_option_global_statistics'));

        add_filter('parent_file', array($this,'survey_maker_select_submenu'));
    }

    public function add_plugin_dashboard_submenu(){
        $hook_quizes = add_submenu_page(
            $this->plugin_name,
            __('How to use', $this->plugin_name),
            __('How to use', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-dashboard',
            array($this, 'display_plugin_setup_page')
        );
    }

    public function add_plugin_general_settings_submenu(){
        $hook_settings = add_submenu_page( $this->plugin_name,
            __('General Settings', $this->plugin_name),
            __('General Settings', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_plugin_settings_page') 
        );
        add_action("load-$hook_settings", array($this, 'screen_option_settings'));
    }

    public function add_plugin_featured_plugins_submenu(){
        add_submenu_page( $this->plugin_name,
            __('Our products', $this->plugin_name),
            __('Our products', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-our-products',
            array($this, 'display_plugin_featured_plugins_page') 
        );
    }

    public function add_plugin_affiliate_submenu(){
        add_submenu_page( $this->plugin_name,
            __('Affiliates', $this->plugin_name),
            __('Affiliates', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-affiliates',
            array($this, 'display_plugin_affiliate_page') 
        );
    }

    public function add_plugin_orders_submenu(){
        $hook_orders = add_submenu_page(
            $this->plugin_name,
            __('Orders', $this->plugin_name),
            __('Orders', $this->plugin_name),
            $this->capability,
            $this->plugin_name . '-survey-orders',
            array($this, 'display_plugin_orders_page')
        );

        add_action("load-$hook_orders", array($this, 'screen_option_orders'));
    }
    
    public function add_plugin_survey_front_requests_submenu(){
        $unread_requests_count = $this->get_unread_frontend_requests_count();
		$show = $unread_requests_count > 0 ? " <span class=\"ays-survey-menu-badge\">$unread_requests_count</span>" : '';
        
        $hook_requests = add_submenu_page(
            $this->plugin_name,
            __('Frontend Requests', $this->plugin_name),
            __('Frontend Requests', $this->plugin_name) . $show,
            $this->capability,
            $this->plugin_name . '-requests',
            array($this, 'display_plugin_requests_page')
        );

        add_action("load-$hook_requests", array($this, 'screen_option_requests'));

        $hook_each_requests = add_submenu_page(
			'',
			__('Requests per survey', $this->plugin_name),
			__('Requests per survey', $this->plugin_name),
			$this->capability,
			$this->plugin_name . '-requests-each',
			array($this, 'display_plugin_each_requests_page')
		);

		add_filter('parent_file', array($this,'survey_maker_select_submenu'));

    }

    public function survey_maker_select_submenu($file) {
        global $plugin_page;
        if ($this->plugin_name."-each-submission" == $plugin_page) {
            $plugin_page = $this->plugin_name."-submissions";
        }else if($this->plugin_name."-global-statistics" == $plugin_page){
            $plugin_page = $this->plugin_name."-submissions";
        }else if ($this->plugin_name."-requests-each" == $plugin_page) {
            $plugin_page = $this->plugin_name."-requests";
        }

        return $file;
    }
    
    protected function survey_maker_capabilities(){
        global $wpdb;

        $sql = "SELECT meta_value FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "settings WHERE `meta_key` = 'user_roles'";
        $result = $wpdb->get_var($sql);
        
        $capability = 'ays_survey_maker_manage_options';
        if($result !== null){
            $ays_user_roles = json_decode($result, true);
            if(is_user_logged_in()){
                $current_user = wp_get_current_user();
                $current_user_roles = $current_user->roles;
                $ishmar = 0;
                foreach($current_user_roles as $r){
                    if(in_array($r, $ays_user_roles)){
                        $ishmar++;
                    }
                }
                if($ishmar > 0){
                    $capability = "read";
                }
            }
        }
        return $capability;
    }


    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links){
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
            '<a href="https://ays-demo.com/wordpress-survey-plugin-pro-demo/" target="_blank">' . __('Demo', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    public function add_survey_row_meta( $links, $file ) {
        if ( SURVEY_MAKER_BASENAME == $file ) {
            $row_meta = array(
                'ays-survey-support'       => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/survey-maker/' ) . '" target="_blank">' . esc_html__( 'Free Support', $this->plugin_name ) . '</a>',
                'ays-survey-documentation' => '<a href="' . esc_url( 'https://ays-pro.com/wordpress-survey-maker-user-manual' ) . '" target="_blank">' . esc_html__( 'Documentation', $this->plugin_name ) . '</a>',
                'ays-survey-rate-us' => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/survey-maker/reviews/?rate=5#new-post' ) . '" target="_blank">' . esc_html__( 'Rate us', $this->plugin_name ) . '</a>',
                'ays-survey-rate-us' => '<a href="' . esc_url( 'https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA' ) . '" target="_blank">' . esc_html__( 'Video tutorial', $this->plugin_name ) . '</a>',
                );

            return array_merge( $links, $row_meta );
        }
        return $links;
    }


    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page(){
        include_once('partials/survey-maker-admin-display.php');
    }

    public function display_plugin_surveys_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';
        include_once 'partials/surveys/actions/survey-maker-survey-countries.php';
        switch ($action) {
            case 'add':
                include_once('partials/surveys/actions/survey-maker-surveys-actions.php');
                break;
            case 'edit':
                include_once('partials/surveys/actions/survey-maker-surveys-actions.php');
                break;
            default:
                include_once('partials/surveys/survey-maker-surveys-display.php');
        }
    }

    /**
     * Customers (Clientes)
     * Jeferson Carreira
     */
    public function display_plugin_customers_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';
        include_once 'partials/surveys/actions/survey-maker-survey-countries.php';
        switch ($action) {
            case 'add':
                include_once('partials/surveys/actions/survey-maker-survey-customers-actions.php');
                break;
            case 'edit':
                include_once('partials/surveys/actions/survey-maker-survey-customers-actions.php');
                break;
            default:
                include_once('partials/surveys/survey-maker-customers-display.php');
        }
    }    

    public function display_plugin_survey_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/surveys/actions/survey-maker-survey-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/surveys/actions/survey-maker-survey-categories-actions.php');
                break;
            default:
                include_once('partials/surveys/survey-maker-survey-categories-display.php');
        }
    }

    public function display_plugin_popup_surveys_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/popups/actions/survey-maker-popup-surveys-actions.php');
                break;
            case 'edit':
                include_once('partials/popups/actions/survey-maker-popup-surveys-actions.php');
                break;
            default:
            include_once('partials/popups/survey-maker-popups-display.php');
        }
    }

    public function display_plugin_submissions_page(){

        include_once('partials/submissions/survey-maker-submissions-display.php');
    }
    
    public function display_plugin_each_submission_page(){
        include_once 'partials/submissions/survey-maker-each-submission-display.php';
    }

    public function display_plugin_global_statistics_page(){
        include_once 'partials/submissions/survey-maker-global-statistics-display.php';
    }
    
    public function display_plugin_settings_page(){
        include_once('partials/settings/survey-maker-settings.php');
    }

    public function display_plugin_export_import_page(){
        include_once('partials/export-import/survey-maker-export-import-display.php');
    }

    public function display_plugin_featured_plugins_page(){
        include_once('partials/features/survey-maker-plugin-featured-display.php');
    }

    public function display_plugin_affiliate_page(){
        include_once('partials/affiliate/survey-maker-affiliate-display.php');
    }

    public function display_plugin_orders_page(){
        include_once('partials/orders/survey-maker-orders-display.php');
    }
    
    public function display_plugin_requests_page(){
        include_once('partials/requests/survey-maker-requests-display.php');
    }

    public function display_plugin_each_requests_page(){
		include_once 'partials/requests/survey-maker-requests-each-display.php';
	}

    public static function set_screen($status, $option, $value){
        return $value;
    }

    public function screen_option_surveys(){
        $option = 'per_page';
        $args = array(
            'label' => __('Pesquisas', $this->plugin_name),
            'default' => 20,
            'option' => 'surveys_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }
        
        $this->surveys_obj = new Surveys_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    /**
     * CUSTOMERS
     * Jeferson Carreira
     */
    public function screen_option_customers(){
        $option = 'per_page';
        $args = array(
            'label' => __('Clientes', $this->plugin_name),
            'default' => 20,
            'option' => 'customers_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }
        $this->customers_obj = new Customers_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }


    public function screen_option_survey_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Survey Categories', $this->plugin_name),
            'default' => 20,
            'option' => 'survey_categories_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }
        $this->surveys_categories_obj = new Survey_Categories_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_popup_surveys(){
        $option = 'per_page';
        $args = array(
            'label' => __('Popup Survey', $this->plugin_name),
            'default' => 20,
            'option' => 'popup_survey_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option( $option, $args );
        }

        $this->popup_surveys_obj = new Popup_Survey_List_Table( $this->plugin_name );
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Questions', $this->plugin_name),
            'default' => 20,
            'option' => 'survey_questions_per_page'
        );

        add_screen_option($option, $args);
        $this->questions_obj = new Survey_Questions_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Question Categories', $this->plugin_name),
            'default' => 20,
            'option' => 'survey_question_categories_per_page'
        );

        add_screen_option($option, $args);
        $this->question_categories_obj = new Survey_Question_Categories_List_Table($this->plugin_name);
    }

    public function screen_option_submissions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Submissions', $this->plugin_name),
            'default' => 20,
            'option' => 'survey_submissions_results_per_page'
        );

        add_screen_option($option, $args);
        $this->submissions_obj = new Submissions_List_Table( $this->plugin_name );
    }

    public function screen_option_each_survey_submission() {
        $option = 'per_page';
        $args = array(
            'label' => __('Results', $this->plugin_name),
            'default' => 50,
            'option' => 'survey_each_submission_results_per_page',
        );

        add_screen_option($option, $args);
        $this->each_submission_obj = new Survey_Each_Submission_List_Table($this->plugin_name);        
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }
    
    public function screen_option_global_statistics(){
        $this->submissions_obj = new Submissions_List_Table( $this->plugin_name );
    }
    
    public function screen_option_settings(){
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_orders(){
        $option = 'per_page';
        $args = array(
            'label' => __('Orders', $this->plugin_name),
            'default' => 20,
            'option' => 'survey_orders_per_page'
        );

        add_screen_option($option, $args);
        $this->orders_obj = new Survey_Orders_List_Table($this->plugin_name);
    }

    public function screen_option_requests(){
        $option = 'per_page';
        $args = array(
            'label' => __('Requests', $this->plugin_name),
            'default' => 20,
            'option' => 'survey_frontend_requests_per_page'
        );

        add_screen_option($option, $args);
        // $this->orders_obj = new Survey_Requests_List_Table($this->plugin_name);
        $this->requests_obj = new Survey_Requests_List_Table($this->plugin_name);
    }

	public function ays_survey_admin_ajax(){
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

			// $results = array();
			// switch ($function) {
			// 	case 'deactivate_plugin_option':
            //         // Deactivate plugin AJAx action
			// 		$results = $this->deactivate_plugin_option();
			// 		break;
			// 	case 'ays_surveys_export_json':
			// 		$results = $this->ays_surveys_export_json();
			// 		break;
			// 	case 'ays_survey_submission_report':
			// 		$results = $this->ays_survey_submission_report();
			// 		break;
			// 	case 'ays_survey_send_testing_mail':
            //         // Send test Mail
			// 		$results = $this->ays_survey_send_testing_mail();
			// 		break;
			// 	case 'ays_survey_users_search':
            //         // User Search
			// 		$results = $this->ays_survey_users_search();
			// 		break;
			// 	case 'ays_survey_show_filters':
			// 		$results = $this->ays_survey_show_filters();
			// 		break;
			// 	case 'ays_survey_results_export_filter':
			// 		$results = $this->ays_survey_results_export_filter();
			// 		break;
			// 	case 'ays_survey_answers_statistics_export':
			// 		$results = $this->ays_survey_answers_statistics_export();
			// 		break;
			// 	case 'ays_survey_single_submission_results_export':
			// 		$results = $this->ays_survey_single_submission_results_export();
			// 		break;
            //     case 'send_summary_email':
            //         //send summary email 
            //         $results = $this->send_summary_email();
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

    public function deactivate_plugin_option(){
        $request_value = $_REQUEST['upgrade_plugin'];
        $upgrade_option = get_option( 'ays_survey_maker_upgrade_plugin', '' );
        if($upgrade_option === ''){
            add_option( 'ays_survey_maker_upgrade_plugin', $request_value );
        }else{
            update_option( 'ays_survey_maker_upgrade_plugin', $request_value );
        }
        return json_encode( array( 'option' => get_option( 'ays_survey_maker_upgrade_plugin', '' ) ) );
    }

    public function survey_maker_admin_footer($a){
        if(isset($_REQUEST['page'])){
            if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), $this->plugin_name)){
                ?>
                <p style="font-size:13px;text-align:center;font-style:italic;">
                    <span style="margin-left:0px;margin-right:12px;" class="fa-regular fa-building"></span>
                    <span><?php echo __( "Visite o nosso site https://survey.jocartec.com", $this->plugin_name); ?></span>
                </p>
            <?php
            }
        }
    }

    public static function ays_restriction_string($type, $x, $length){
        $output = "";
        switch($type){
            case "char":                
                if(strlen($x)<=$length){
                    $output = $x;
                } else {
                    $output = substr($x,0,$length) . '...';
                }
                break;
            case "word":
                $res = explode(" ", $x);
                if(count($res)<=$length){
                    $output = implode(" ",$res);
                } else {
                    $res = array_slice($res,0,$length);
                    $output = implode(" ",$res) . '...';
                }
            break;
        }
        return $output;
    }    
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function get_max_id( $table ) {
        global $wpdb;
        $db_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . $table;

        $sql = "SELECT MAX(id) FROM {$db_table}";

        $result = intval( $wpdb->get_var( $sql ) );

        return $result;
    }

    public function get_all_surveys(){
        global $wpdb;
        $surveys_table = $wpdb->prefix . "socialsurv_surveys";
        $sql = "SELECT * FROM {$surveys_table} WHERE status != 'trashed' ";
        
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $sql .= " AND author_id = ".$current_user." ";
        }
        
        $sql .= " ORDER BY id DESC ";

        $surveys = $wpdb->get_results($sql);
        return $surveys;
    }

    //--------------------- Xcho Survey export ------------------------------

    public function ays_surveys_export_json() {
        global $wpdb;

        $surveys_ids = isset($_REQUEST['surveys_ids']) ? array_map( 'sanitize_text_field', $_REQUEST['surveys_ids'] ) : array();
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
        $survey_category_table = $wpdb->prefix. SURVEY_MAKER_DB_PREFIX . "survey_categories";
        $questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX. 'questions';
        $questions_category_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'question_categories';
        $answers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'answers';
        $sections_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sections';
        $where = array();
        if( !empty( $surveys_ids ) ){
            $where[] = " id IN (". implode(',', $surveys_ids) .") ";
        }

        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $where[] = " author_id = ".$current_user." ";
        }

        if ( ! empty( $where ) ){
            $where = ' WHERE ' . implode( ' AND ', $where );
        }else{
            $where = '';
        }

        $sql_survey_categories = "SELECT * FROM ".$survey_category_table;
        $survey_categories = $wpdb->get_results($sql_survey_categories, 'ARRAY_A');
        $survey_all_categories = array();
        foreach ($survey_categories as $survey_categories_key) {
            $survey_all_categories[$survey_categories_key['id']] = $survey_categories_key['title'];
        }
        $sql_surveys = "SELECT * FROM ".$surveys_table.$where;
        $surveys = $wpdb->get_results($sql_surveys, 'ARRAY_A');
        $data = array();
        $data['ays_survey_key'] = 1;
        $data['surveys'] = array();
        foreach ($surveys as $survey_key => &$survey) {
            $questions_id = trim($survey['question_ids'], ',');
            $survey_cat_ids = explode(',' , $surveys[$survey_key]['category_ids']);
            foreach ($survey_cat_ids as $survey_cat_key) {
                $surveys[$survey_key]['survey_categories'][$survey_cat_key] = ( isset( $survey_all_categories[$survey_cat_key] ) && $survey_all_categories[$survey_cat_key] != '' ) ? $survey_all_categories[$survey_cat_key] : '';
            }
            unset($survey['id']);
            unset($survey['category_ids']);
            if(empty($questions_id)){
                $survey["questions"] = array();
            }else{
                $sql_sections = "SELECT id,title,description,ordering FROM ".$sections_table." WHERE id IN (". esc_sql( $survey['section_ids'] ) .")";
                $sections = $wpdb->get_results($sql_sections, 'ARRAY_A');
                $sql_question_cat = "SELECT * FROM ".$questions_category_table;
                $questions_categories = $wpdb->get_results($sql_question_cat, 'ARRAY_A');
                $categories = array();
                foreach ($questions_categories as $question_key) {
                    $categories[$question_key['id']] = $question_key['title'];
                }
                $sql_questions = "SELECT * FROM ".$questions_table." WHERE id IN (". esc_sql( $questions_id ) .")" ;
                $all_questions = $wpdb->get_results($sql_questions, 'ARRAY_A');
                $cat_ids = '';
                foreach ($all_questions as $key => &$question) {
                    $all_questions[$key]['answers'] = $this->get_question_answers($question['id']);
                    // $cat_ids = explode(',' , $all_questions[$key]['category_ids']);
                    // foreach ($cat_ids as $cat_key) {
                    //     $all_questions[$key]['question_categories'][$cat_key] = $categories[$cat_key];
                    // }
                }
            }
            $survey['sections'] = $sections;
            $survey['questions'] = $all_questions;
        }        
        $data['surveys'] = $surveys;

        $response = array(
            'status' => true,
            'data'   => $data,
            'title'  => 'surveys-export',
        );
        return $response;
    }

    //------------------ Xcho Survey Import ---------------------

    public function ays_survey_import( $import_file ) {
        global $wpdb;
        $name_arr = explode('.', $import_file['name']);
        $type     = end($name_arr);

        $json = file_get_contents($import_file['tmp_name']);
        $json = json_decode($json, true);
        $json_key = isset($json['ays_survey_key']) ? $json['ays_survey_key'] : false;

        if($json_key) {
            $surveys_table             = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
            $questions_table           = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
            $answers_table             = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
            $survey_category_table     = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories";
            $questions_category_table  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories";
            $sections_table            = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";

            $user_id = get_current_user_id();
            $user    = get_userdata($user_id);
            $author  = array(
                'id'   => $user->ID,
                'name' => $user->data->display_name
            );
            
            $survey_all_unique_questions_id = array();

            // Question categories
            $categories_r = $wpdb->get_results("SELECT id, title FROM ".$questions_category_table, 'ARRAY_A');
            $categories = array();
            foreach($categories_r as $cat){
                $categories[$cat['id']] = strtolower($cat['title']);
            }

            // Survey categories
            $scategories_r = $wpdb->get_results("SELECT id, title FROM ".$survey_category_table, 'ARRAY_A');
            $scategories = array();
                       
            foreach($scategories_r as $cat){
                $scategories[$cat['id']] = strtolower($cat['title']);
            }
            $surveys = $json['surveys'];

            $imported_surveys = 0;
            $failed_surveys = 0;
            $imported_qusts = 0;
            $failed_qusts = 0;
            $imported_answers = 0;
            $failed_answers = 0;

            // Import surveys
            foreach ($surveys as $survey_val => $survey) {
                $scategory_ids           = array();
                $survey_question_id      = array();
                $for_question_import     = array();
                $section_all_ids         = array();
                $survey_category_all_ids = array();
                $survey_section_ids      = array();
                $survey_categrories      = $survey['survey_categories'];
                $questions               = $survey['questions'];
                $sections                = $survey['sections'];

                // Import survey categories
                foreach ($survey_categrories as $category_key => $category_val) {
                    $survey_category = 1;
                    $survey_category_file = 'Uncategorized';
                    if(isset($category_val)){
                        $survey_category_file = strval($category_val);
                    }

                    if($this->string_starts_with_number($survey_category_file)){
                        $survey_category = 1;
                    }elseif(in_array(strtolower($survey_category_file), $scategories)){
                        $category_id = array_search(strtolower($survey_category_file), $scategories);
                        if($category_id !== false){
                            $survey_category = intval($category_id);
                        }else{
                            $survey_category = 1;
                        }
                    }else{
                        $wpdb->insert(
                            $survey_category_table,
                            array( 
                                'title'  =>  $survey_category_file,
                                'date_created'  =>  current_time( 'mysql' ),
                                'date_modified'  =>  current_time( 'mysql' )
                            ),
                            array( '%s' ),
                            array( '%s' ),
                            array( '%s' )
                        );
                        $survey_category = $wpdb->insert_id;
                        $scategories[strval($survey_category)] = strtolower($survey_category_file);
                    }
                    $survey_category_all_ids[] = $survey_category;
                    
                    $category_id = $survey_category;
                }

                // Import sections
                foreach ($sections as $section_key => $section_value) {
                    $section_ordering = $this->get_max_id('sections');
                    $section_ordering++;
                    $wpdb->insert(
                        $sections_table,
                        array( 'title'     =>  $section_value['title'],
                               'description'  =>  isset($section_value['description']) ? sanitize_text_field($section_value['description']) : '',
                               'ordering'  =>  $section_ordering
                        ),
                        array( '%s' ,
                               '%s', 
                               '%d' 
                        )
                    );
                    $survey_new_id = $wpdb->insert_id;
                    $section_all_ids[$section_value['id']] = $survey_new_id;
                    $survey_section_ids[] = $section_all_ids[$section_value['id']];
                }

                // Import questions
                foreach ($questions as $question_key => $question_value) {
                    $question_category_ids = array();
                    $question_section_ids  = array();
                    $question_old_id       = $question_value['id'];
                    if(! array_key_exists($question_old_id."", $survey_all_unique_questions_id)){

                        $question_value['answers'] = isset($question_value['answers']) ? $question_value['answers'] : array();
                        
                        // Import Question categories
                        // foreach ($question_categories as $question_categories_key => $question_categories_value) {
                        //     $question_category = 1;
                        //     $question_category_file = 'Uncategorized';
                        //     if(isset($question_categories_value)){
                        //         $question_category_file = strval($question_categories_value);                                
                        //     }
                        //     if($this->string_starts_with_number($question_category_file)){
                        //         $question_category = 1;
                        //     }elseif(in_array(strtolower($question_category_file), $categories)){
                        //         $question_category_id = array_search(strtolower($question_category_file), $categories);
                        //         if($question_category_id !== false){
                        //             $question_category = intval($question_category_id);
                        //         }else{
                        //             $question_category = 1;
                        //         }
                        //     }else{
                        //         $wpdb->insert(
                        //             $questions_category_table,
                        //             array( 'title'  =>  $question_category_file),
                        //             array( '%s' )
                        //         );
                        //         $question_category = $wpdb->insert_id;
                        //         $categories[strval($question_category)] = strtolower($question_category_file);
                        //     }                            
                        //     $question_category_id = $question_category;
                        //     $question_category_ids[] = $question_category_id;                            
                        // }

                        if(isset($section_all_ids[$question_value['section_id']])){
                            $question_section_id = $section_all_ids[$question_value['section_id']];                           
                        }
                        $question_categories = array( '1' );
                        $question_content = htmlspecialchars_decode(isset($question_value['question']) && $question_value['question'] != '' ? $question_value['question'] : '', ENT_HTML5);
                        $question_description = isset($question_value['question_description']) && $question_value['question_description'] != '' ? $question_value['question_description'] : '';
                        $question_image   = (isset($question_value['image']) && $question_value['image'] != '') ? $question_value['image'] : '';
                        $type             = (isset($question_value['type']) && $question_value['type'] != '') ? $question_value['type'] : 'radio';
                        $published        = (isset($question_value['status']) && $question_value['status'] != '') ? $question_value['status'] : 'published';
                        $trash_status     = (isset($question_value['trash_status']) && $question_value['trash_status'] != '') ? $question_value['trash_status'] : '';
                        $user_variant     = (isset($question_value['user_variant']) && $question_value['user_variant'] != '') ? $question_value['user_variant'] : '';
                        $user_explanation = '';
                        $author_id        = get_current_user_id();
                        $create_date      = current_time( 'mysql' );
                        $modified_date    = current_time( 'mysql' );
                        $question_options = (isset($question_value['options']) && $question_value['options'] != '') ? $question_value['options'] : '';
                        $answers_get      = $question_value['answers'];
                        
                        // Collect answers
                        $answers = array();
                        foreach($answers_get as $answer_key => $answer){
                            $answer_content = (isset($answer['answer']) && $answer['answer'] != '') ? htmlspecialchars_decode($answer['answer'], ENT_HTML5) : '';
                            $image = (isset($answer['image']) && $answer['image'] != '') ? $answer['image'] : '';
                            $ordering = $answer_key + 1;
                            $placeholder = (isset($answer['placeholder']) && $answer['placeholder'] != '') ? htmlspecialchars_decode($answer['placeholder'], ENT_HTML5) : '';
                            
                            $answer_options = array();
                            if( isset( $answer['options'] ) && $answer['options'] != '' && !is_array($answer['options']) ){
                                $answer_options = json_decode( $answer['options'], true );
                            }

                            $answer_options['go_to_section'] = isset( $answer_options['go_to_section'] ) && $answer_options['go_to_section'] != '' ? $answer_options['go_to_section'] : '';

                            if( $answer_options['go_to_section'] != '' && $answer_options['go_to_section'] != '-1' && $answer_options['go_to_section'] != '-2' ){
                                if( isset( $section_all_ids[ $answer_options['go_to_section'] ] ) ){
                                    $answer_options['go_to_section'] = $section_all_ids[ $answer_options['go_to_section'] ];                           
                                }
                            }

                            $answer_options = json_encode( $answer_options );

                            $answers[] = array(
                                'answer'        => $answer_content,
                                'image'         => $image,
                                'ordering'      => $ordering,
                                'placeholder'   => $placeholder,
                                'options'       => $answer_options,
                            );
                        }

                        // Collect questions
                        $for_question_import[] = array(
                            'id'                        => $question_old_id,
                            'author_id'                 => $author_id,
                            'section_id'                => $question_section_id,
                            'category_ids'              => implode(',', $question_category_ids),
                            'question'                  => $question_content,
                            'question_description'      => $question_description,
                            'type'                      => $type,
                            'status'                    => $published,
                            'trash_status'              => $trash_status,
                            'date_created'              => $create_date,
                            'date_modified'             => $modified_date,                            
                            'user_variant'              => $user_variant,
                            'user_explanation'          => $user_explanation,                            
                            'image'                     => $question_image,
                            'options'                   => $question_options,
                            'answers'                   => $answers,
                        );
                    }else{
                        $survey_question_id[] = $survey_all_unique_questions_id[$question_value["id"]];
                    }
                }

                $imported = 0;
                $failed = 0;

                // Import collected questions and answers
                foreach($for_question_import as $key => $question) {
                    $qusetion_ordering = $this->get_max_id('questions');
                    $qusetion_ordering++;
                    $import_array = array(
                        'author_id'                 => $question['author_id'],
                        'section_id'                => $question['section_id'],
                        'category_ids'              => $question['category_ids'],
                        'question'                  => $question['question'],
                        'question_description'      => $question['question_description'],
                        'type'                      => $question['type'],
                        'status'                    => $question['status'],
                        'trash_status'              => $question['trash_status'],
                        'date_created'              => $question['date_created'],
                        'date_modified'             => $question['date_modified'],
                        'user_variant'              => $question['user_variant'],
                        'user_explanation'          => $question['user_explanation'],
                        'image'                     => $question['image'],
                        'options'                   => $question['options'],
                        'ordering'                  => $qusetion_ordering
                    );
                    $import_array_vals = array(
                        '%d', //author_id
                        '%d', //section_id
                        '%s', //category_ids
                        '%s', //question
                        '%s', //question_description
                        '%s', //type
                        '%d', //published
                        '%s', //trash_status
                        '%s', //date_created
                        '%s', //date_modified
                        '%s', //user_variant
                        '%s', //user_explanation
                        '%s', //image                        
                        '%s', //options
                        '%d', //orderind
                    );

                    $quest_res = $wpdb->insert(
                        $questions_table,                       
                        $import_array,
                        $import_array_vals
                    );
                   
                    $question_id = $wpdb->insert_id;
                    $survey_question_id[] = $question_id;
                    $survey_all_unique_questions_id[$question["id"]] = $question_id;

                    $ordering = 1;
                    $answer_res_success = 0;
                    $answer_res_fail = 0;
                        foreach ( $question['answers'] as $answer ) {
                            $result = $wpdb->insert(
                                $answers_table,
                                array(
                                    'question_id'   => $question_id,
                                    'answer'        => $answer['answer'],
                                    'image'         => $answer['image'],
                                    'ordering'      => $answer['ordering'],                                
                                    'placeholder'   => $answer['placeholder'],
                                    'options'       => $answer['options'],
                                ),
                                array(
                                    '%d', // question_id
                                    '%s', // answer
                                    '%s', // image
                                    '%d', // ordering                                
                                    '%s', // placeholder
                                    '%s'  // placeholder
                                )
                            );

                            if($result === false){
                                $answer_res_fail++;
                            }
                            if($result >= 0){
                                $answer_res_success++;
                            }
                        }

                    $imported_answers += $answer_res_success;
                    $failed_answers += $answer_res_fail;

                    if($quest_res === false){
                        $failed++;
                    }
                    if($quest_res >= 0){
                        $imported++;
                    }else{
                        $failed++;
                    }
                }

                // Import surveys
                $imported_qusts += $imported;
                $failed_qusts += $failed;
                $survey_ordering = $this->get_max_id('surveys');
                $survey_ordering++;

                $survey_res = $wpdb->insert(
                    $surveys_table,
                    array(
                        'author_id'         => get_current_user_id(),
                        'title'             => htmlspecialchars_decode($survey['title'], ENT_HTML5),
                        'description'       => htmlspecialchars_decode($survey['description'], ENT_HTML5),
                        'category_ids'      => implode(',' , $survey_category_all_ids),
                        'question_ids'      => implode(',' , $survey_question_id),
                        'section_ids'       => implode(',' ,  $survey_section_ids),
                        'sections_count'    => $survey['sections_count'],
                        'questions_count'   => $survey['questions_count'],
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'image'             => $survey['image'],
                        'status'            => 'published',
                        'trash_status'      => '',
                        'ordering'          => $survey_ordering,
                        'post_id'           => 0,
                        'options'           => $survey['options']
                    ),
                    array(
                        '%d', // author id
                        '%s', // title
                        '%s', // description
                        '%s', // category_ids
                        '%s', // question_ids
                        '%s', // section_ids
                        '%d', // sections_count
                        '%d', // questions_count
                        '%s', // date_created
                        '%s', // date_modified
                        '%s', // image
                        '%s', // status
                        '%s', // trash_status
                        '%s', // ordering
                        '%d',  // post_id
                        '%s'  // options
                    )
                );
                if($survey_res === false){
                    $failed_surveys++;
                }
                if($survey_res >= 0){
                    $imported_surveys++;
                }else{
                    $failed_surveys++;
                }
            }
                
            $stats = array(
                'surveys_successed'   => $imported_surveys,
                'surveys_failed'      => $failed_surveys,
                'questions_successed' => $imported_qusts,
                'questions_failed'    => $failed_qusts,
                'answers_successed'   => $imported_answers,
                'answers_failed'      => $failed_answers
            );
            return $stats;            
        }
        return null;
    } 

    public static function string_starts_with_number($string){
        $match = preg_match('/^\d/', $string);
        if($match === 1){
            return true;
        }else{
            return false;
        }
    }

    public function get_question_answers( $question_id ) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}". SURVEY_MAKER_DB_PREFIX . "answers WHERE question_id=" . absint( $question_id );

        $results = $wpdb->get_results( $sql, 'ARRAY_A' );
        foreach ($results as $key => &$result) {
            unset($result['id']);
            unset($result['question_id']);
        }

        return $results;
    }

    // Send test Mail
    public function ays_survey_send_testing_mail(){
        if(isset($_REQUEST['ays_survey_test_email']) && filter_var($_REQUEST['ays_survey_test_email'], FILTER_VALIDATE_EMAIL)){
            $nsite_url_base = get_site_url();
            $nsite_url_replaced = str_replace( array( 'http://', 'https://' ), '', $nsite_url_base );
            $nsite_url = trim( $nsite_url_replaced, '/' );
            $nno_reply = "noreply@".$nsite_url;

            if(isset($_REQUEST['ays_email_configuration_from_name']) && $_REQUEST['ays_email_configuration_from_name'] != "") {
                $uname = stripslashes($_REQUEST['ays_email_configuration_from_name']);
            } else {
                $uname = 'Social Survey';
            }

            if(isset($_REQUEST['ays_survey_email_configuration_from_name']) && $_REQUEST['ays_survey_email_configuration_from_name'] != "") {
                $nfrom = "From: " . $uname . " <".stripslashes($_REQUEST['ays_survey_email_configuration_from_name']).">";
            }else{
                $nfrom = "From: " . $uname . " <survey_maker@".$nsite_url.">";
            }

            if(isset($_REQUEST['ays_survey_email_configuration_from_subject']) && $_REQUEST['ays_survey_email_configuration_from_subject'] != "") {
                $subject = stripslashes($_REQUEST['ays_survey_email_configuration_from_subject']);
            } else {
                $subject = stripslashes($_REQUEST['ays_title']);
            }

            $headers = $nfrom."\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $attachment = array();

            $message_content = (isset($_REQUEST['ays_survey_mail_message']) && !empty($_REQUEST['ays_survey_mail_message'])) ? stripslashes($_REQUEST['ays_survey_mail_message']) : __( "Message text", $this->plugin_name );
            
            $message = $message_content;
            $to = $_REQUEST['ays_survey_test_email'];

            $ays_send_test_mail = (wp_mail($to, $subject, $message, $headers, $attachment)) ? true : false;
            $response_text = __( "Test email delivered", $this->plugin_name );
            if($ays_send_test_mail === false){
                $response_text = __( "Test email not delivered", $this->plugin_name );
            }

            return array(
                'status' => true,
                'mail' => $ays_send_test_mail,
                'message' => $response_text,
            );
        }else{
            $response_text = __( "Test email not delivered", $this->plugin_name );
            return array(
                'status' => false,
                'message' => $response_text,
            );
        }
    }

    public function ays_survey_submission_report(){
        global $wpdb;
        if (isset($_REQUEST['function']) && $_REQUEST['function'] == 'ays_survey_submission_report' && wp_verify_nonce( $_REQUEST['nonce'], 'ajax-nonce') ) {

            $survey_id = (isset($_REQUEST['surveyId']) && $_REQUEST['surveyId'] != "") ? intval($_REQUEST['surveyId']) : null;
            if($survey_id === null){
                return false;
            }
            
            $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE id =" . absint( $survey_id );
            $survey = $wpdb->get_row( $sql, 'ARRAY_A' );

            $submission_id = (isset($_REQUEST['submissionId']) && $_REQUEST['submissionId'] != '') ? absint( sanitize_text_field( $_REQUEST['submissionId'] ) ) : null;
            if($submission_id == null){
                return false;
            }
            $submission = array(
                'id' => $submission_id
            );

            $results = Survey_Maker_Data::ays_survey_individual_results_for_one_submission( $submission, $survey );
            $individual_user_name   = isset($results['user_info']['user_name']) && isset($results['user_info']['user_name']) ? esc_attr($results['user_info']['user_name']) : "";
            $individual_user_email  = isset($results['user_info']['user_email']) && isset($results['user_info']['user_email'])  ? wp_kses_post($results['user_info']['user_email']) : "";
            $individual_user_ip     = isset($results['user_info']['user_ip']) && isset($results['user_info']['user_ip'])  ? esc_attr($results['user_info']['user_ip']) : "";
            $individual_user_date   = isset($results['user_info']['submission_date']) && isset($results['user_info']['submission_date'])  ? esc_attr($results['user_info']['submission_date']) : "";
            $individual_user_sub_id = isset($results['user_info']['id']) && isset($results['user_info']['id'])  ? esc_attr($results['user_info']['id']) : "";
            $individual_user_password = isset($results['user_info']['password']) && isset($results['user_info']['password'])  ? esc_attr($results['user_info']['password']) : "";
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

            return $response = array(
                'status' => true,
                'questions' => $results['questions'],
                'user_info' => $results['user_info'],
                'user_info_for_copy' => Survey_Maker_Data::ays_survey_copy_text_formater($survey_data_clipboard)
            );
        }
        return $response = array(
            'status' => false
        );
    }

    // Survey Maker Elementor widget init
    public function survey_maker_el_widgets_registered() {
        // We check if the Elementor plugin has been installed / activated.
        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
            // get our own widgets up and running:
            // copied from widgets-manager.php
            if ( class_exists( 'Elementor\Plugin' ) ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();
                    if ( isset( $elementor->widgets_manager ) ) {
                        if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
                            wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
                            wp_enqueue_style( SURVEY_MAKER_NAME . "-dropdown", SURVEY_MAKER_PUBLIC_URL . '/css/dropdown.min.css', array(), SURVEY_MAKER_VERSION, 'all' );
                            $widget_file   = 'plugins/elementor/survey-maker-elementor.php';
                            $template_file = locate_template( $widget_file );
                            if ( !$template_file || !is_readable( $template_file ) ) {
                                $template_file = SURVEY_MAKER_DIR . 'pb_templates/survey-maker-elementor.php';
                            }
                            if ( $template_file && is_readable( $template_file ) ) {
                                require_once $template_file;
                                Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Survey_Maker_Elementor() );
                            }
                        }
                    }
                }
            }
        }
    }

    public function ays_survey_users_search(){
        
        $checked = isset($_REQUEST['val']) && $_REQUEST['val'] !='' ? $_REQUEST['val'] : null;
        $search = isset($_REQUEST['q']) && $_REQUEST['q'] !='' ? $_REQUEST['q'] : null;
        $args = 'search=';
        if($search !== null){
            $args .= $search;
            $args .= '*';
        }

        $users = get_users($args);

        $content_text = array(
            'results' => array()
        );

        foreach ($users as $key => $value) {
            if ($checked !== null) {
                if (in_array($value->ID, $checked)) {
                    continue;
                }else{
                    $content_text['results'][] = array(
                        'id' => $value->ID,
                        'text' => $value->data->display_name,
                    );
                }
            }else{
                $content_text['results'][] = array(
                    'id' => $value->ID,
                    'text' => $value->data->display_name,
                );
            }
        }

        return  $content_text;
    }

    // EXPORT FILTERS | Aro
    public function ays_survey_show_filters(){
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $survey_table      = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        $current_user = get_current_user_id();
        $db_prefix    = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;

        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_show_filters') {
            
            $user_sql = "SELECT
                    $submissions_table.user_id,
                    {$db_prefix}users.display_name
                FROM $submissions_table
                JOIN {$db_prefix}users
                    ON $submissions_table.user_id = {$db_prefix}users.ID
                GROUP BY
                    $submissions_table.user_id";
            
            if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
                $user_sql = "SELECT
                        r.user_id,
                        u.display_name 
                    FROM
                        $submissions_table AS r
                    JOIN {$db_prefix}users AS u
                        ON r.user_id = u.ID 
                    LEFT JOIN {$survey_table} AS q
                        ON r.survey_id = q.id
                    WHERE q.author_id = {$current_user}
                    GROUP BY
                        r.user_id";
            }

            $users = $wpdb->get_results( $user_sql, "ARRAY_A" );

            $is_there_guest = 0 == $wpdb->get_var("SELECT MIN(user_id) FROM {$submissions_table}");

            if ($is_there_guest) {
                $users[] = array('user_id' => 0, 'display_name' => 'Guests');
            }
            $surveys_sql = "SELECT
                    $submissions_table.survey_id,
                    $survey_table.title 
                FROM
                    $submissions_table
                JOIN $survey_table ON $submissions_table.survey_id = $survey_table.id
                GROUP BY
                    $submissions_table.survey_id";

            if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
                $surveys_sql = "SELECT
                        r.survey_id,
                        q.title 
                    FROM $submissions_table AS r
                    JOIN $survey_table AS q
                        ON r.survey_id = q.id
                    WHERE q.author_id = {$current_user}
                    GROUP BY
                        r.survey_id";
            }

            $surveys = $wpdb->get_results( $surveys_sql, "ARRAY_A" );

            $min_date_sql = "SELECT DATE(MIN(start_date)) FROM {$submissions_table}";
            $max_date_sql = "SELECT DATE(MAX(start_date)) FROM {$submissions_table}";
            if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
                $min_date_sql = "SELECT DATE(MIN(r.start_date))
                                FROM {$submissions_table} AS r
                                LEFT JOIN {$survey_table} AS q
                                    ON r.survey_id = q.id
                                WHERE q.author_id = ".$current_user;
                $max_date_sql = "SELECT DATE(MAX(r.start_date))
                                FROM {$submissions_table} AS r
                                LEFT JOIN {$survey_table} AS q
                                    ON r.survey_id = q.id
                                WHERE q.author_id = ".$current_user;
            }
            $date_min = $wpdb->get_var($min_date_sql);
            $date_max = $wpdb->get_var($max_date_sql);
            $sql = "SELECT COUNT(*) FROM {$submissions_table} ORDER BY id DESC";
            if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
                $sql = "SELECT COUNT(*) 
                        FROM {$submissions_table} AS r
                        LEFT JOIN {$survey_table} AS q
                            ON r.survey_id = q.id
                        WHERE q.author_id = {$current_user}
                        ORDER BY r.id DESC";
            }

            if (isset($_REQUEST['flag']) && sanitize_text_field($_REQUEST['flag'])) {
                $survey_id_sql = "SELECT survey_id FROM {$submissions_table}";
                if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
                    $survey_id_sql = "SELECT rp.survey_id 
                                    FROM {$submissions_table} AS rp
                                    LEFT JOIN {$survey_table} AS qz
                                        ON rp.survey_id = qz.id
                                    WHERE qz.author_id = ".$current_user;
                }
                $survey_id = (isset($_REQUEST['survey_id']) && sanitize_text_field($_REQUEST['survey_id']) != null) ? intval(sanitize_text_field($_REQUEST['survey_id'])) : $survey_id_sql;
                $sql = "SELECT COUNT(*) FROM {$submissions_table} WHERE survey_id IN ($survey_id) ORDER BY id DESC";
                if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
                    $sql = "SELECT COUNT(*) 
                            FROM {$submissions_table} AS r
                            LEFT JOIN {$survey_table} AS q
                                ON r.survey_id = q.id
                            WHERE q.author_id = {$current_user} AND
                                survey_id IN ($survey_id)
                            ORDER BY r.id DESC";
                }
            }
            $qanak = $wpdb->get_var($sql);
            
            return array(
                "surveys"   => $surveys,
                "users"     => $users,
                "date_min"  => $date_min,
                "date_max"  => $date_max,
                "count"     => $qanak,
            );
        }
    }

    public function ays_survey_results_export_filter(){
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $survey_table      = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        $user_id_sql   = "SELECT user_id FROM {$submissions_table}";
        $survey_id_sql = "SELECT survey_id FROM {$submissions_table}";
        $current_user  = get_current_user_id();
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $user_id_sql = "SELECT sb.user_id 
                            FROM {$submissions_table} AS sb
                            LEFT JOIN {$survey_table} AS sr
                                ON sb.survey_id = sr.id
                            WHERE sr.author_id = ".$current_user;
            $survey_id_sql = "SELECT sbm.survey_id 
                            FROM {$submissions_table} AS sbm
                            LEFT JOIN {$survey_table} AS srv
                                ON sbm.survey_id = srv.id
                            WHERE srv.author_id = ".$current_user;
        }
        
        $user_id = (isset($_REQUEST['user_id']) && !empty( $_REQUEST['user_id'] ) ) ? implode( ',', array_map( 'sanitize_text_field', $_REQUEST['user_id'] ) ) : $user_id_sql;
        $survey_id = (isset($_REQUEST['survey_id']) && sanitize_text_field( $_REQUEST['survey_id'] ) != null) ? sanitize_text_field( $_REQUEST['survey_id'] ) : $survey_id_sql;

        if (isset($_REQUEST['flag']) && sanitize_text_field($_REQUEST['flag'])) {
            $survey_id = (isset($_REQUEST['survey_id']) && sanitize_text_field($_REQUEST['survey_id']) != null) ? intval(sanitize_text_field($_REQUEST['survey_id'])) : $survey_id_sql;
        }
        $date_from = (isset($_REQUEST['date_from']) && sanitize_text_field($_REQUEST['date_from']) != '') ? sanitize_text_field($_REQUEST['date_from']) : '2000-01-01';
        $date_to = (isset($_REQUEST['date_to']) && sanitize_text_field($_REQUEST['date_to']) != '') ? sanitize_text_field($_REQUEST['date_to']) : current_time('Y-m-d');
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $sql = "SELECT COUNT(*) AS qanak
                    FROM {$submissions_table} AS r
                    LEFT JOIN {$survey_table} AS s
                        ON r.survey_id = s.id
                    WHERE
                        s.author_id = {$current_user} AND
                        r.user_id IN ($user_id) AND
                        r.survey_id = $survey_id AND
                        r.start_date BETWEEN '$date_from' AND '$date_to 23:59:59'
                    ORDER BY r.id DESC";
        }else{
            $sql = "SELECT COUNT(*) AS qanak
                    FROM {$submissions_table}
                    WHERE
                        user_id IN ($user_id) AND
                        survey_id = $survey_id AND
                        start_date BETWEEN '$date_from' AND '$date_to 23:59:59'
                    ORDER BY id DESC";
        }
        $results = $wpdb->get_row($sql);
        return $results;
    }

    public function ays_survey_answers_statistics_export() {
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $questions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $answers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";


        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_answers_statistics_export') {

            $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? intval($_REQUEST['survey_id']) : null;
            if($survey_id === null){
                return array(
                    'status' => false,
                );
            }
            $survey_id = ($survey_id !== null) ? $survey_id : "SELECT survey_id FROM {$submissions_table}";
            $user_id   = (isset($_REQUEST['user_id']) && !empty( $_REQUEST['user_id'] ) ) ? implode( ',', array_map( 'sanitize_text_field', $_REQUEST['user_id'] ) ) : "SELECT user_id FROM {$submissions_table} WHERE survey_id IN ($survey_id)";
            $date_from = (isset($_REQUEST['date_from']) && sanitize_text_field($_REQUEST['date_from']) != '') ? sanitize_text_field($_REQUEST['date_from']) : '2000-01-01';
            $date_to   = (isset($_REQUEST['date_to']) && sanitize_text_field($_REQUEST['date_to']) != '') ? sanitize_text_field($_REQUEST['date_to']) : current_time('Y-m-d');

            $sql = "SELECT *
                    FROM {$submissions_table}
                    WHERE
                        user_id IN ($user_id) AND
                        survey_id IN ($survey_id) AND
                        start_date BETWEEN '$date_from' AND '$date_to 23:59:59'
                    ORDER BY id DESC";
            $results = $wpdb->get_results($sql);

            $questions_ids_arr = array_column($results, 'questions_ids');
            $submission_question_ids = array();
            foreach($questions_ids_arr as $key => $question_ids){
                $current_question_ids = explode("," , $question_ids);

                foreach($current_question_ids as $key => $question_id){
                    $submission_question_ids[$question_id] = $question_id;
                }
            }
            $fitlered_question_ids = (implode(',', $submission_question_ids));

            $all_questions_result = $wpdb->get_results("SELECT id, COALESCE(JSON_UNQUOTE(JSON_EXTRACT(options, '$.matrix_columns')), '') AS matrix_columns, type, question FROM {$questions_table} WHERE id IN ($fitlered_question_ids)", ARRAY_A);
            $all_questions = array();
            foreach ($all_questions_result as $question) {
                $question_id = intval($question['id']);
                $all_questions[$question_id] = $question;
            }

            $all_submissions_questions = $wpdb->get_results("SELECT answer_id, submission_id, question_id, type, user_answer, user_variant, user_explanation FROM {$submissions_questions_table} WHERE survey_id = ($survey_id)", ARRAY_A);
            $submission_question_groups = array();
            foreach ($all_submissions_questions as $submission_question) {
                $submission_id = $submission_question['submission_id'];
                $question_id = $submission_question['question_id'];

                if (!isset($submission_question_groups[$submission_id])) {
                    $submission_question_groups[$submission_id] = array();
                }

                if (!isset($submission_question_groups[$submission_id][$question_id])) {
                    $submission_question_groups[$submission_id][$question_id] = array();
                }

                $submission_question_groups[$submission_id][$question_id][] = $submission_question;
            }

            $all_answers = $wpdb->get_results("SELECT id, answer, question_id FROM {$answers_table} WHERE question_id IN ($fitlered_question_ids)", ARRAY_A);
            $all_answers_arr = array();
            foreach ($all_answers as $answer) {
                $question_id = $answer['question_id'];
                if (!isset($all_answers_arr[$question_id])) {
                    $all_answers_arr[$question_id] = array();
                }
                $all_answers_arr[$question_id][$answer['id']] = array('id' => $answer['id'], 'answer' => $answer['answer']);
            }

            $quest       = array();
            $users       = array();
            $users_data  = array();
            $quest_data  = array();
            $all_questions_ids_arr = array();
            $explanation = array();
            if (! is_null($results) && !empty($results) ) {                
                foreach ($results as $key => $result) {
                    $user_id   = intval($result->user_id);
                    $user      = get_user_by('id', $user_id);
                    $user      = ($user_id === 0) ? 'Guest' : $user->data->display_name;
                    $email     = (isset($result->user_email) && ($result->user_email !== '' || $result->user_email !== null)) ? stripslashes($result->user_email) : '';
                    $user_nam  = (isset($result->user_name) && ($result->user_name !== '' || $result->user_name !== null)) ? stripslashes($result->user_name) : '';

                    $submission_id = (isset($result->id) && ($result->id !== '' || $result->id !== null)) ? absint( intval($result->id) ) : null;
                    $user_name     = html_entity_decode(strip_tags(stripslashes($user_nam)));

                    if ($user == 'Guest') {
                        if ($user_name == '' && $email == '') {
                            $user = __( 'Guest', $this->plugin_name );
                        }else {
                            $user_name_arr = array(
                                __( 'Guest', $this->plugin_name ),
                            );

                            if ($user_name != '') {
                                $user_name_arr[] = $user_name;
                            }

                            if ($email != '') {
                                $user_name_arr[] = $email;
                            }

                            $user = implode(' - ', $user_name_arr);
                        }
                    }
                    $users[] = $user;

                    $questions_ids_str  = (isset($result->questions_ids) && ($result->questions_ids !== '' || $result->questions_ids !== null)) ? stripslashes($result->questions_ids) : '';
                    $questions_ids_arr = array();
                    if ($questions_ids_str != '') {
                        $questions_ids_arr  = explode(',', $questions_ids_str);

                        foreach ($questions_ids_arr as $k => $q_id) {
                            if (! in_array($q_id, $all_questions_ids_arr) ) {
                                $all_questions_ids_arr[ $q_id ] = $q_id;

                                $question = isset($all_questions[$q_id]) && $all_questions[$q_id] != '' ? esc_attr( trim( stripslashes($all_questions[$q_id]["question"]) ) ) : '';
                                if ($question == '') {
                                    continue;
                                }

                                $quest_data[strval($q_id)] = array(
                                    'question' => $question,
                                );
                            }
                        }
                    }

                    $attr = array();
                    $user_tvyal = array();
                    $user_exp = array();
                    foreach ($all_questions_ids_arr as $questions_key => $questions_id) {
                        if ($questions_id != '') {
                            $question_id = absint(intval( $questions_id ));

                            $question_type = isset($all_questions[$questions_id]['type']) && $all_questions[$questions_id]['type'] != "" ? $all_questions[$questions_id]['type'] : "";
                            $question_matrix_columns = isset($all_questions[$questions_id]['matrix_columns']) && $all_questions[$questions_id]['matrix_columns'] != "" ? $all_questions[$questions_id]['matrix_columns'] : "";

                            $submission_question = isset($submission_question_groups[$submission_id]) && isset($submission_question_groups[$submission_id][$question_id]) ? $submission_question_groups[$submission_id][$question_id] : array();

                            $attr = array(
                                'submission_id' => $submission_id,
                                'question_id'   => $question_id,
                                'survey_id'     => $survey_id,
                                'question_type' => $question_type,                                
                                'matrix_columns' => $question_matrix_columns,
                                'all_answers' => $all_answers_arr,
                                'submission_question' => $submission_question,
                                // 'question_options' => $question_options,
                                'all_results'   => true,
                            );
                            $user_answered = Survey_Maker_Data::ays_survey_get_user_answered_for_submissions_export($attr);
                            $user_answer   = html_entity_decode(strip_tags(stripslashes($user_answered)));
                            $user_answer_exp   = (isset($submission_question['user_explanation']) && $submission_question['user_explanation'] != '') ? stripslashes($submission_question['user_explanation']) : '';

                            $user_tvyal[strval($question_id)] = array(
                                'user_answer' => $user_answer,
                                'user_explanation' => $user_answer_exp,
                            );
                        }
                    }
                    $user_tvyal['submissions']['submission_date'] = $result->end_date;
                    $users_data[] = $user_tvyal;
                }

                $headers = array(
                    array( 'text' => __( "Questions", $this->plugin_name ) ),
                );

                foreach ($quest_data as $qid => $question) {
                    $headers[] = array( 'text' => $question['question'] );
                }

                $headers[] = array( 'text' => __( "Submissoin Date", $this->plugin_name ) );                

                $questions = array();
                $user_answered_quest = array();
                $u_explanations = array();
                
                foreach ($users_data as $k => $v) {
                    $user_answered_quest = array(
                        array( 'text' => $users[$k] ),
                    );
                    $u_explanations = array(
                        array( 'text' => 'user_explanation' ),
                    );

                    foreach ($quest_data as $qid => $question) {
                        if( isset( $v[$qid] ) && !empty( $v[$qid] ) ){
                            $answers = array( 'text' => $v[$qid]['user_answer'] );
                            $user_answered_quest[] = $answers;
                            $exp = array( 'text' => $v[$qid]['user_explanation'] );
                            $u_explanations[] = $exp;
                        }
                    }
                    $user_answered_quest[] = array( 'text' => $v['submissions']['submission_date']);
                    $questions[] = $user_answered_quest;
                    $questions[] = $u_explanations;
                }
                // die();
                // foreach ($users_data as $k => $v) {
                //     $headers[] = array( 'text' => $users[$k] );
                // }

                // $questions = array();
                // $user_answered_quest = array();
                // foreach ($quest_data as $qid => $question) {

                //     $user_answered_quest = array(
                //         array( 'text' => $question['question'] ),
                //     );

                //     foreach ($users_data as $user => $usr_ans) {
                //         $answers = array( 'text' => $usr_ans[$qid]['user_answer'] );
                //         $user_answered_quest[] = $answers;
                //     }
                //     $questions[] = $user_answered_quest;

                // }

                $quest[] = $headers;
                $quest[] = array(
                    array( 'text' => '' ),
                );
                for ($i=0; $i < count($questions) ; $i++) {
                    $quest[] = $questions[$i];
                }
            }

            $export_data = array(
                'status' => true,
                'type'   => 'xlsx',
                'data'   => $quest
            );

            return $export_data;
        }
    }

    // Export answers to CSV 
    public function ays_survey_answers_statistics_export_csv() {
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $questions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $answers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        
        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_answers_statistics_export_csv') {

            $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? intval($_REQUEST['survey_id']) : null;
            if($survey_id === null){
                return array(
                    'status' => false,
                );
            }
            $survey_id = ($survey_id !== null) ? $survey_id : "SELECT survey_id FROM {$submissions_table}";
            $user_id   = (isset($_REQUEST['user_id']) && !empty( $_REQUEST['user_id'] ) ) ? implode( ',', array_map( 'sanitize_text_field', $_REQUEST['user_id'] ) ) : "SELECT user_id FROM {$submissions_table} WHERE survey_id IN ($survey_id)";
            $date_from = (isset($_REQUEST['date_from']) && sanitize_text_field($_REQUEST['date_from']) != '') ? sanitize_text_field($_REQUEST['date_from']) : '2000-01-01';
            $date_to   = (isset($_REQUEST['date_to']) && sanitize_text_field($_REQUEST['date_to']) != '') ? sanitize_text_field($_REQUEST['date_to']) : current_time('Y-m-d');

            $sql = "SELECT *
                    FROM {$submissions_table}
                    WHERE
                        user_id IN ($user_id) AND
                        survey_id IN ($survey_id) AND
                        start_date BETWEEN '$date_from' AND '$date_to 23:59:59'
                    ORDER BY id DESC";
            $results = $wpdb->get_results($sql);
           
            $questions_ids_arr = array_column($results, 'questions_ids');
            $submission_question_ids = array();
            foreach($questions_ids_arr as $key => $question_ids){
                $current_question_ids = explode("," , $question_ids);

                foreach($current_question_ids as $key => $question_id){
                    $submission_question_ids[$question_id] = $question_id;
                }
            }
            $fitlered_question_ids = (implode(',', $submission_question_ids));

            $all_questions_result = $wpdb->get_results("SELECT id, COALESCE(JSON_UNQUOTE(JSON_EXTRACT(options, '$.matrix_columns')), '') AS matrix_columns, type, question FROM {$questions_table} WHERE id IN ($fitlered_question_ids)", ARRAY_A);
            $all_questions = array();
            foreach ($all_questions_result as $question) {
                $question_id = intval($question['id']);
                $all_questions[$question_id] = $question;
            }

            $all_submissions_questions = $wpdb->get_results("SELECT answer_id, submission_id, question_id, type, user_answer, user_variant, user_explanation FROM {$submissions_questions_table} WHERE survey_id = ($survey_id)", ARRAY_A);
            $submission_question_groups = array();
            foreach ($all_submissions_questions as $submission_question) {
                $submission_id = $submission_question['submission_id'];
                $question_id = $submission_question['question_id'];

                if (!isset($submission_question_groups[$submission_id])) {
                    $submission_question_groups[$submission_id] = array();
                }

                if (!isset($submission_question_groups[$submission_id][$question_id])) {
                    $submission_question_groups[$submission_id][$question_id] = array();
                }

                $submission_question_groups[$submission_id][$question_id][] = $submission_question;
            }

            $all_answers = $wpdb->get_results("SELECT id, answer, question_id FROM {$answers_table} WHERE question_id IN ($fitlered_question_ids)", ARRAY_A);
            $all_answers_arr = array();
            foreach ($all_answers as $answer) {
                $question_id = $answer['question_id'];
                if (!isset($all_answers_arr[$question_id])) {
                    $all_answers_arr[$question_id] = array();
                }
                $all_answers_arr[$question_id][$answer['id']] = array('id' => $answer['id'], 'answer' => $answer['answer']);
            }
            
            $quest       = array();
            $users       = array();
            $users_data  = array();
            $quest_data  = array();
            $all_questions_ids_arr = array();
            $explanation = array();
            if (! is_null($results) && !empty($results) ) {                
                foreach ($results as $key => $result) {
                    $user_id   = intval($result->user_id);
                    $user      = get_user_by('id', $user_id);
                    $user      = ($user_id === 0) ? 'Guest' : $user->data->display_name;
                    $email     = (isset($result->user_email) && ($result->user_email !== '' || $result->user_email !== null)) ? stripslashes($result->user_email) : '';
                    $user_nam  = (isset($result->user_name) && ($result->user_name !== '' || $result->user_name !== null)) ? stripslashes($result->user_name) : '';

                    $submission_id = (isset($result->id) && ($result->id !== '' || $result->id !== null)) ? absint( intval($result->id) ) : null;
                    $user_name     = html_entity_decode(strip_tags(stripslashes($user_nam)));

                    if ($user == 'Guest') {
                        if ($user_name == '' && $email == '') {
                            $user = __( 'Guest', $this->plugin_name );
                        }else {
                            $user_name_arr = array(
                                __( 'Guest', $this->plugin_name ),
                            );

                            if ($user_name != '') {
                                $user_name_arr[] = $user_name;
                            }

                            if ($email != '') {
                                $user_name_arr[] = $email;
                            }

                            $user = implode(' - ', $user_name_arr);
                        }
                    }
                    $users[] = $user;

                    $questions_ids_str  = (isset($result->questions_ids) && ($result->questions_ids !== '' || $result->questions_ids !== null)) ? stripslashes($result->questions_ids) : '';
                    $questions_ids_arr = array();
                    if ($questions_ids_str != '') {
                        $questions_ids_arr  = explode(',', $questions_ids_str);

                        foreach ($questions_ids_arr as $k => $q_id) {
                            if (! in_array($q_id, $all_questions_ids_arr) ) {
                                $all_questions_ids_arr[ $q_id ] = $q_id;
                                
                                $question = isset($all_questions[$q_id]) && $all_questions[$q_id] != '' ? strip_tags( trim( stripslashes($all_questions[$q_id]["question"]) ) ) : '';

                                if ($question == '') {
                                    continue;
                                }

                                $question = str_replace(',' , ' ', $question);

                                $quest_data[strval($q_id)] = array(
                                    'question' => $question,
                                );
                            }
                        }
                    }

                    $attr = array();
                    $user_tvyal = array();
                    $user_exp = array();
                    foreach ($all_questions_ids_arr as $questions_key => $questions_id) {
                        if ($questions_id != '') {
                            $question_id = absint(intval( $questions_id ));

                            $question_type = isset($all_questions[$questions_id]['type']) && $all_questions[$questions_id]['type'] != "" ? $all_questions[$questions_id]['type'] : "";
                            $question_matrix_columns = isset($all_questions[$questions_id]['matrix_columns']) && $all_questions[$questions_id]['matrix_columns'] != "" ? $all_questions[$questions_id]['matrix_columns'] : "";

                            $submission_question = isset($submission_question_groups[$submission_id]) && isset($submission_question_groups[$submission_id][$question_id]) ? $submission_question_groups[$submission_id][$question_id] : array();

                            $attr = array(
                                'submission_id' => $submission_id,
                                'question_id'   => $question_id,
                                'survey_id'     => $survey_id,
                                'question_type' => $question_type,                                
                                'matrix_columns' => $question_matrix_columns,
                                'all_answers' => $all_answers_arr,
                                'submission_question' => $submission_question,
                                'all_results'   => true,
                            );
                            $user_answered = Survey_Maker_Data::ays_survey_get_user_answered_for_submissions_export($attr);
                            $user_answer   = html_entity_decode(strip_tags(stripslashes($user_answered)));

                            $user_answer_exp   = (isset($submission_question['user_explanation']) && $submission_question['user_explanation'] != '') ? stripslashes($submission_question['user_explanation']) : '';

                            $user_tvyal[strval($question_id)] = array(
                                'user_answer' => $user_answer,
                                'user_explanation' => $user_answer_exp,
                            );
                        }
                    }
                    $users_data[] = $user_tvyal;
                }

                $headers = array(
                    __( "Questions", $this->plugin_name ),
                );

                foreach ($quest_data as $qid => $question) {
                    $headers[] = $question['question'];
                }
                
                $headers_ready = array();
                $headers_ready[] = $headers;

                $questions = array();
                $user_answered_quest = array();
                $u_explanations = array();
                
                foreach ($users_data as $k => $v) {
                    $user_answered_quest = array(
                        $users[$k],
                    );
                    $u_explanations = array(
                         'user_explanation',
                    );

                    foreach ($quest_data as $qid => $question) {
                        if( isset( $v[$qid] ) && !empty( $v[$qid] ) ){
                            $answers = $v[$qid]['user_answer'];
                            $user_answered_quest[] = $answers;
                            $exp = $v[$qid]['user_explanation'];
                            $u_explanations[] = $exp;
                        }
                    }
                    
                    $questions[] = $user_answered_quest;
                    $questions[] = $u_explanations;
                }
                for ($i=0; $i < count($questions) ; $i++) {
                    $quest[] = $questions[$i];
                }
            }
            $export_data = array(
                'status' => true,
                'type'   => 'csv',
                'data'   => $quest,
                'headersData'   => $headers_ready,
            );

            return $export_data;
        }
    }

    public function ays_survey_single_submission_results_export() {
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $questions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        
        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_single_submission_results_export') {

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
            $options   = json_decode($results['options']);

            // $start_date      = (isset($results['start_date']) && sanitize_text_field($results['start_date']) != '') ? stripslashes( sanitize_text_field( $results['start_date'] ) ) : '';
            // $end_date        = (isset($results['end_date']) && sanitize_text_field($results['end_date']) != '') ? stripslashes( sanitize_text_field( $results['end_date'] ) ) : '';
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
                        $question         = ( isset($question_content["question"]) && $question_content["question"] != '') ? htmlspecialchars_decode( trim( stripslashes($question_content["question"]) ) ) : '';
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
    
    public function get_survey_data_for_sendig_summary_email($survey_id){ 
        global $wpdb;
       
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        $get_question_submissions = Survey_Maker_Data::ays_survey_question_results( $survey_id , null, null, null, null, true);
        $survey_questions = (isset($get_question_submissions['questions']) && !empty($get_question_submissions['questions'])) ? $get_question_submissions['questions'] : array();
        $question_ids = array_keys($survey_questions);

        //Passed users count
        $survey_users_count_sql = "SELECT COUNT(id) AS submission_count FROM {$submissions_table} WHERE survey_id={$survey_id}";
        $survey_passed_users_count = $wpdb->get_var($survey_users_count_sql);
        
        $passed_users_count = isset($survey_passed_users_count) && $survey_passed_users_count != '' ? intval($survey_passed_users_count) : 0;
        
        $content = '';

        $content .= '<table style="border-collapse:collapse;width: 100%;margin:auto;">';
            $content .=	'<tr>';
                $content .=	'<th style="text-align:center;font-family: Arial, Helvetica, sans-serif;">
                                <h2>Please find your survey report attached.</h2>';
                $content .=	'</th>';
            $content .=	'</tr>';
            $content .=	'<tr>';
                $content .=	'<th style="text-align:center;font-family: Arial, Helvetica, sans-serif;">
                                <p>'.$passed_users_count.' participants have taken this survey in total.</p>';
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

            $answer_total_count = '';
            if(!empty($survey_question['otherAnswers']) && ($question_type != 'checkbox' && $question_type != 'radio' && $question_type != 'select' && $question_type != 'yesorno')){
                $answer_total_count = $total_answer_count + intval(count($survey_question['otherAnswers']));
            }else{
                $answer_total_count = $total_answer_count;
            }

            if($question_type != 'name' && $question_type != 'email'){

                if($question_type == 'checkbox' || $question_type == 'radio' || $question_type == 'select' || $question_type == 'yesorno'){
                    if($question_type == 'checkbox'){
                        $total_answer_count_for_checkbox = array_sum($answers_array);
                    }
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
                            if($question_type == 'checkbox'){
                                if($answer_total_count != 0){
                                    $answer_percentage = (intval($per_answers_count) * 100) / $total_answer_count_for_checkbox;
                                }else{
                                    $answer_percentage = 0;
                                }
                            }
                            else{                            
                                if($answer_total_count != 0){
                                    $answer_percentage = (intval($per_answers_count) * 100) / $answer_total_count;
                                }else{
                                    $answer_percentage = 0;
                                }
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

                if ($question_type == 'text' || $question_type == 'short_text' || $question_type == 'number' || $question_type == 'phone' || $question_type == 'date' || $question_type == 'time' || $question_type == 'date_time' || $question_type == 'hidden') {
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
                    $slider_list_range_min_value  = isset($survey_question['slider_list_range_min_value']) && $survey_question['slider_list_range_min_value'] != "" ? $survey_question['slider_list_range_min_value'] : "0";
                    $slider_list_range_max_value  = isset($survey_question['slider_list_range_length']) && $survey_question['slider_list_range_length'] != "" ? $survey_question['slider_list_range_length'] : "100";
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

                if($question_type == 'range'){
                    $range_min_value  = isset($survey_question['range_min_value']) && $survey_question['range_min_value'] != "" ? $survey_question['range_min_value'] : "0";
                    $range_max_value  = isset($survey_question['range_length']) && $survey_question['range_length'] != "" ? $survey_question['range_length'] : "100";
                    $content .= '<table style="border-collapse:collapse;width:80%;text-align:center;font-family: Arial, Helvetica, sans-serif;margin:auto;margin-bottom: 30px">';
                    $content .= '<thead>';
                        $content .= '<tr>';
                            $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">';
                                $content .= $survey_question_name;
                            $content .= '</th>';
                            $content .= '<th style="background-color: #fff;border: 1px solid #dadce0;border-top: 10px solid #ff5722;padding: 8px;text-align: left;color: #1d2327;margin-bottom: 12px;box-sizing: border-box;width:30%;">Total count '.'('.$answer_total_count.')'.' - Min '.$range_min_value.' / Max '.$range_max_value.'</th>';
                            
                        $content .= '</tr>';
                    $content .= '</thead>';
                    $content .= '<tbody>';
                    foreach ($answers_title_array as $key => $answer_title) {
                        foreach($answer_title as $file_key => $file){
                            $content .= '<tr>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= '<span>' .__("Answer" , "survey-maker"). '</span>';
                                $content .= '</td>';
                                $content .= '<td style="border: 1px solid #ddd;padding: 8px;text-align: left;width:30%;">';
                                        $content .= $file;
                                $content .= '</td>';
                            $content .= '</tr>';
                        }
                    }
                        $content .= '</tbody>';
                    $content .= '</table>';
                }
            }
        }

        return $content;
    }

    public function get_summary_email_of_users($survey_id){
        global $wpdb;

        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        
        $survey_users_email_sql = "SELECT user_email AS emails FROM {$submissions_table} WHERE user_email != '' AND survey_id={$survey_id}";
        $survey_users_email = $wpdb->get_results($survey_users_email_sql,"ARRAY_A");

        return $survey_users_email;
    }

    public function send_summary_email(){

        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'send_summary_email') {

            $survey_id = isset($_REQUEST['surveyId']) ? intval($_REQUEST['surveyId']) : null;
            $survey_send_summary_email_to_site_admin = (isset($_REQUEST['sendToAdmin']) && $_REQUEST['sendToAdmin'] == 'true') ? true : false;
            $survey_send_summary_email_to_users = (isset($_REQUEST['sendToUsers']) && $_REQUEST['sendToUsers'] == 'true') ? true : false;
            $survey_send_summary_email_to_additional_users = (isset($_REQUEST['sendToAdmins']) && $_REQUEST['sendToAdmins'] != '') ? $_REQUEST['sendToAdmins'] : '';

            if ($survey_id == null){
                return array(
                    'status' => false,
                );
            }else{
                $message_content = $this->get_survey_data_for_sendig_summary_email($survey_id);
               
                $users_emails = array();
                $admin_email  = '';
                $additional_users  = '';
                
                if($survey_send_summary_email_to_site_admin){
                    $admin_email = get_option('admin_email');
                    $users_emails[] = $admin_email;
                }

                if ($survey_send_summary_email_to_users) {
                    $message_data = $this->get_summary_email_of_users($survey_id);
                    foreach ($message_data as $key => $users_email) {
                        $email = (isset($users_email['emails']) && sanitize_email($users_email['emails']) != '') ? sanitize_email($users_email['emails']) : '';
                        if( $email != '' && !in_array( $email, $users_emails ) ){
                            $users_emails[] = $email;
                        }
                    }
                }

                if ($survey_send_summary_email_to_additional_users != '') {
                    $added_user = explode(',', $survey_send_summary_email_to_additional_users);
                }


                $additional_users = (isset($added_user) && !empty($added_user)) ? $added_user : array();

                if(!empty($added_user)){
                    foreach ($additional_users as $key => $additional_user) {
                        $users_emails[] = sanitize_email( $additional_user );
                    }
                }

                if( !empty( $users_emails ) && $message_content != ''){
                    $status = 0;
                    foreach( $users_emails as $k => $email ){
                        $nsite_url_base = get_site_url();
                        $nsite_url_replaced = str_replace( array( 'http://', 'https://' ), '', $nsite_url_base );
                        $nsite_url = trim( $nsite_url_replaced, '/' );
                        $nno_reply = "noreply@".$nsite_url;
                        $nfrom = "From:Survey Maker<survey_maker@".$nsite_url.">";
                        $headers = $nfrom."\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                        $subject = __( 'Survey Summary', $this->plugin_name );
                        $message = $message_content;
                        $to = "<" . $email . ">";
                        
                        $ays_send_summary_mail = wp_mail($to, $subject, $message, $headers) ? true : false;
                        $response_text = __( "Summary email delivered", $this->plugin_name );
                        if($ays_send_summary_mail === false){
                            $response_text = __( "Summary email not delivered", $this->plugin_name );
                        }else{
                            $status++;
                        }
                    }

                    return array(
                        'status' => true,
                        'mail' => $ays_send_summary_mail,
                        'message' => $response_text,
                    );
                }else{
                    return array(
                        'status'  => false,
                        'message' => __( 'Please select at least one of the recipients.', $this->plugin_name ),
                    );
                }	
            }
        }
        return array(
            'status' => true,
            'mail' => $ays_send_summary_mail,
            'message' => $response_text,
        );
    }

    public function get_selected_posts() {
        $checked = isset( $_REQUEST['val'] ) && $_REQUEST['val'] !='' ? array_map( 'sanitize_text_field', $_REQUEST['val'] ) : null;
        $search = isset( $_REQUEST['q'] ) && $_REQUEST['q'] !='' ? sanitize_text_field( $_REQUEST['q'] ) : null;

        if($checked !== null){
	        $args = array(
				'post_type'   => $checked,
				'post_status' => 'publish',
				'numberposts' => -1
	        );
			if ($search !== null) {
				$args['s'] = $search;
			}

			$results = get_posts( $args );
		} else {
			$results = array();
		}

        $content_text = array(
            'results' => array()
        );

        foreach ($results as $value) {
            if ($checked !== null) {
                if (in_array($value->ID, $checked)) {
                    continue;
                }else{
                    $content_text['results'][] = array(
                        'id' => $value->ID,
                        'text' => $value->post_title,
                    );
                }
            }else{
             	$content_text['results'][] = array(
                    'id' => $value->ID,
                    'text' => $value->post_title,
                );
            }
        }
        return $content_text;
	}

    public function ays_survey_get_post_type(){
		global $wpdb;
        $checked = isset($_REQUEST['val']) && $_REQUEST['val'] !='' ?  $_REQUEST['val']  : null;
        $search = isset($_REQUEST['q']) && $_REQUEST['q'] !='' ? sanitize_text_field( $_REQUEST['q'] ) . '%' : null;
        $pref_posts = $wpdb->prefix . "posts";

        if($search !== null){
        	$search_query = "SELECT DISTINCT( post_type ) FROM ".$pref_posts." WHERE post_type LIKE '". $search ."' ";
			$results = $wpdb->get_results($search_query);
        }

        $content_text = array(
            'results' => array()
        );

        foreach ($results as $value) {
            if ($checked !== null) {
                if (in_array($value->post_type, $checked)) {
                    continue;
                }else{
        			$p_type = get_post_types( [ 'name'=>$value->post_type ], 'objects' );
                    $content_text['results'][] = array(
                        'id' => $value->post_type,
                        'text' => $p_type[$value->post_type]->label,
                    );
                }
            }else{
                $p_type = get_post_types( [ 'name'=>$value->post_type ], 'objects' );
                $content_text['results'][] = array(
                    'id' => $value->post_type,
                    'text' => $p_type[$value->post_type]->label,
                );
            }
        }
        return $content_text;
    }

    public function ays_survey_single_submission_pdf_export() {
        global $wpdb;

        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $questions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $sections_table    = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        
        $pdf_response = null;
        $pdf_content  = null;
        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_single_submission_pdf_export') {

            $submission_id = (isset($_REQUEST['submission_id']) && sanitize_text_field($_REQUEST['submission_id']) != '') ? absint( sanitize_text_field( $_REQUEST['submission_id'] ) ) : null;

            if ( is_null($submission_id) ) {
                $export_data = array(
                    'status' => false,
                );

                return $export_data;
            }

            $results = $wpdb->get_row("SELECT * FROM {$submissions_table} WHERE id={$submission_id}", "ARRAY_A");

            if ( is_null($results) || empty($results) ) {
                $export_data = array(
                    'status' => false,
                );

                return $export_data;
            }

            $user_id   = (isset($results['user_id']) && sanitize_text_field($results['user_id']) != '') ? absint( sanitize_text_field( $results['user_id'] ) ) : 0;
            $survey_id = (isset($results['survey_id']) && sanitize_text_field($results['survey_id']) != '') ? absint( sanitize_text_field( $results['survey_id'] ) ) : null;

            $user_ip   = (isset($results['user_ip']) && sanitize_text_field($results['user_ip']) != '') ? stripslashes( sanitize_text_field( $results['user_ip'] ) ) : '';
            $options   = json_decode($results['options']);

            // $start_date      = (isset($results['start_date']) && sanitize_text_field($results['start_date']) != '') ? stripslashes( sanitize_text_field( $results['start_date'] ) ) : '';
            // $end_date        = (isset($results['end_date']) && sanitize_text_field($results['end_date']) != '') ? stripslashes( sanitize_text_field( $results['end_date'] ) ) : '';
            $duration        = (isset($results['duration']) && sanitize_text_field($results['duration']) != '') ? absint( sanitize_text_field( $results['duration'] ) )."s" : "";

            $submission_date = (isset($results['submission_date']) && sanitize_text_field($results['submission_date']) != '') ? stripslashes( sanitize_text_field( $results['submission_date'] ) ) : '';
            $questions_count = (isset($results['questions_count']) && sanitize_text_field($results['questions_count']) != '') ? stripslashes( sanitize_text_field( $results['questions_count'] ) ) : '';
            $unique_code     = (isset($results['unique_code']) && sanitize_text_field($results['unique_code']) != '') ? stripslashes( sanitize_text_field( $results['unique_code'] ) ) : '';

            $questions_ids_str  = (isset($results['questions_ids']) && ( sanitize_text_field( $results['questions_ids']) !== '' || sanitize_text_field( $results['questions_ids'] ) !== null) ) ? stripslashes( sanitize_text_field( $results['questions_ids'] ) ) : '';

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

            $each_result = $wpdb->get_results("SELECT question_id,user_explanation FROM {$submissions_questions_table} WHERE submission_id={$submission_id}", "ARRAY_A");
            $each_explanation = array();
            foreach($each_result as $q_key => $q_value){
                $each_explanation[ $q_value['question_id'] ] = $q_value['user_explanation'];
            }

            // ==============================================================
            // ======================  Generate Data  =======================
            // ========================    START   ==========================

            $quests      = array();
            $export_data = array();

            $data_headers      = array();
            $data_questions    = array();
            $questions_ids_arr = array();

            $data_sections     = array();
            $data_section_ids  = array();

            $data_headers['user_data'] = array(
                'api_user_information_header'   => __( "User Information", $this->plugin_name ),

                'api_user_ip_header'     => __( "User IP", $this->plugin_name ),
                'api_user_id_header'     => __( "User ID", $this->plugin_name ),
                'api_user_header'        => __( "User", $this->plugin_name ),
                'api_user_mail_header'   => __( "Email", $this->plugin_name ),
                'api_user_name_header'   => __( "Name", $this->plugin_name ),

                'api_survey_information_header' => __( "Survey Information", $this->plugin_name ),

                'api_user_ip'     =>  $from,
                'api_user_id'     =>  $user_id."",
                'api_user'        =>  $user,
                'api_user_mail'   =>  $user_email,
                'api_user_name'   =>  $user_name,

                'api_submission_date_header'    =>  __( "Submission date", $this->plugin_name ),
                'api_questions_count_header'    =>  __( "Questions count", $this->plugin_name ),
                'api_duration_header'           =>  __( "Duration", $this->plugin_name ),
                'api_unique_code_header'        =>  __( "Unique code", $this->plugin_name ),

                'api_submission_date'    =>  $submission_date,
                'api_questions_count'    =>  $questions_count,
                'api_duration'           =>  $duration,
                'api_unique_code'        =>  $unique_code,
            );

            $data_questions['headers'] = array(
                'api_glob_question_header'  => __( "Reports", $this->plugin_name ),
                'api_question_header'       => __( "Question", $this->plugin_name ),
                'api_user_answer_header'    => __( "User answered", $this->plugin_name ),
                'api_user_answer_explanation'    => __( "User explanation", $this->plugin_name ),
                'api_user_question_description'  => __( "Question description", $this->plugin_name ),
            );

            // Questions Data
            if ($questions_ids_str != '') {
                $questions_ids_arr = explode(',', $questions_ids_str);

                $attr = array();
                foreach ($questions_ids_arr as $key => $questions_id) {
                    if ($questions_id != '') {

                        $question_id      = absint(intval( $questions_id ));
                        $question_content = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                        $question_type = isset($question_content['type']) && $question_content['type'] != "" ? $question_content['type'] : "";
                        $question_options = isset($question_content['options']) && $question_content['options'] != "" ? json_decode($question_content ['options'] , true) : "";

                        $question         = (isset( $question_content["question"] ) && $question_content["question"] != '') ? esc_attr( trim( stripslashes($question_content["question"]) ) ) : '';
                        $section_id       = (isset( $question_content["section_id"] ) && $question_content["section_id"] != '') ? sanitize_text_field( $question_content["section_id"] ) : '';

                        if ($question == '') {
                            $question = ' - ';
                        }

                        if ($section_id != '' && ! in_array( $section_id , $data_section_ids)) {
                            $data_section_ids[] = $section_id;
                        }

                        $user_explanation_text = isset( $each_explanation[ $questions_id ] ) ? $each_explanation[ $questions_id ] : '';

                        if ($user_explanation_text == '') {
                            $user_explanation_text = ' - ';
                        }

                        $question_description = (isset( $question_content["question_description"] ) && $question_content["question_description"] != '') ? esc_attr( trim( stripslashes($question_content["question_description"]) ) ) : '';

                        if ($question_description == '') {
                            $question_description = ' - ';
                        }

                        $attr = array(
                            'submission_id' => $submission_id,
                            'question_id'   => $question_id,
                            'survey_id'     => $survey_id,
                            'question_type' => $question_type,
                            'question_options' => $question_options,
                            'all_results'   => true,
                        );

                        $user_answered = Survey_Maker_Data::ays_survey_get_user_answered($attr);
                        $user_answer   = html_entity_decode( strip_tags( stripslashes( $user_answered ) ) );

                        if ($user_answer == '') {
                            $user_answer = ' - ';
                        }

                        $quests[] = array(
                            'question'    => $question,
                            'user_answer' => $user_answer,
                            'section_id'  => $section_id,
                            'question_explanation' => $user_explanation_text,
                            'question_description' => $question_description,
                        );
                    }
                }

                $data_questions['data_question'] = $quests;
            }

            // Sections Data
            foreach ($data_section_ids as $key => $section_id) {
                if ( ! empty( $section_id ) ) {
                    $section_id       = absint(intval( $section_id ));
                    $section_content  = $wpdb->get_row("SELECT * FROM {$sections_table} WHERE id={$section_id}", "ARRAY_A");

                    $section_title    = (isset( $section_content["title"] ) && $section_content["title"] != '') ? esc_attr( trim( stripslashes($section_content["title"]) ) ) : '';

                    $data_sections[ $section_id."" ] = $section_title;
                }
            }

            $data_questions['data_sections'] = $data_sections;

            // ==============================================================
            // ======================  Generate Data  =======================
            // ========================    END    ===========================

            if ( class_exists( 'SURVEY_MAKER_PDF_API' ) ) {
                $pdf = new SURVEY_MAKER_PDF_API();
                $export_data = array(
                    'status'          => true,
                    'type'            => 'pdfapi',
                    'api_surevy_id'   => $survey_id,
                    'data_headers'    => $data_headers,
                    'data_questions'  => $data_questions,
                );

                $pdf_response = $pdf->generate_submission_PDF($export_data);
                $pdf_content  = isset($pdf_response['status']) ? $pdf_response['status'] : false;
            }

            if($pdf_content === true){
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode($pdf_response);
            }else{
                $export_data = array(
                    'status' => false,
                );
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode($export_data);
            }
            wp_die();
        } else {
            $export_data = array(
                'status' => false,
            );
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode($export_data);
            wp_die();
        }
    }

    // ==================================================================
    // =================== Submission export filters  ===================
    // ========================    START   ==============================

    public function ays_survey_submissions_export_filter(){
        global $wpdb;
        
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $surveys_table     = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $current_user = get_current_user_id();
        if( ! current_user_can( 'manage_options' ) ){
            $user_id_sql = "SELECT DISTINCT s.user_id 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id
                            WHERE s.status = 'published' AND survey.author_id = ".$current_user;

            $survey_id_sql = "SELECT DISTINCT s.survey_id 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id
                            WHERE survey.author_id = ".$current_user;
        } else {
            $user_id_sql = "SELECT DISTINCT s.user_id 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id
                            WHERE s.status = 'published' ";

            $survey_id_sql = "SELECT DISTINCT s.survey_id 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id ";

        }
        $user_id_result   = $wpdb->get_col($user_id_sql);
        $survey_id_result = $wpdb->get_col($survey_id_sql);

        $user_id   = (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != null) ? implode(',', $_REQUEST['user_id']) : implode(',', $user_id_result);
        $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? implode(',', $_REQUEST['survey_id']) : implode(',', $survey_id_result);
        
        if (isset($_REQUEST['flag']) && $_REQUEST['flag']) {
            // $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? intval(sanitize_text_field($_REQUEST['survey_id'])) : implode(',', $survey_id_result);
        }
       
        $date_from = isset($_REQUEST['date_from']) && $_REQUEST['date_from'] != '' ? sanitize_text_field($_REQUEST['date_from']) : '2000-01-01';
        $date_to   = isset($_REQUEST['date_to']) && $_REQUEST['date_to'] != '' ? sanitize_text_field($_REQUEST['date_to']) : current_time('Y-m-d');
        
        if( ! current_user_can( 'manage_options' ) ){
            $sql = "SELECT COUNT(*) AS count
                    FROM {$submissions_table} AS s
                    LEFT JOIN {$surveys_table} AS survey
                        ON s.survey_id = survey.id
                    WHERE survey.author_id = {$current_user} AND
                        s.user_id IN ($user_id) AND
                        s.survey_id IN ($survey_id) AND
                        s.start_date BETWEEN '$date_from' AND '$date_to 23:59:59' AND
                        s.status = 'published'
                    ORDER BY s.id DESC";
        }else{
            $sql = "SELECT COUNT(*) AS count
                    FROM {$submissions_table}
                    WHERE
                        user_id IN ($user_id) AND
                        survey_id IN ($survey_id) AND
                        start_date BETWEEN '$date_from' AND '$date_to 23:59:59' AND
                        `status` = 'published'
                    ORDER BY id DESC";
        }
        
        $results = $wpdb->get_row($sql);

        if ( ! isset( $results->count ) ) {
            $results->count = 0;
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode($results);
        wp_die();
    }

    public function ays_survey_maker_show_filters(){
        global $wpdb;

        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $surveys_table     = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $current_user = get_current_user_id();
        $db_prefix    = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;

        if (isset($_REQUEST['function']) && $_REQUEST['function'] == 'ays_survey_maker_show_filters') {
            
            $user_sql = "SELECT
                    $submissions_table.user_id,
                    {$db_prefix}users.display_name
                FROM $submissions_table
                JOIN {$db_prefix}users
                    ON $submissions_table.user_id = {$db_prefix}users.ID
                GROUP BY
                    $submissions_table.user_id";
            
            if( ! current_user_can( 'manage_options' ) ){
                $user_sql = "SELECT
                        s.user_id,
                        u.display_name 
                    FROM
                        $submissions_table AS s
                    JOIN {$db_prefix}users AS u
                        ON s.user_id = u.ID 
                    LEFT JOIN {$surveys_table} AS survey
                        ON s.survey_id = survey.id
                    WHERE survey.author_id = {$current_user}
                        AND s.status = 'published'
                    GROUP BY
                        s.user_id";
            }

            $users = $wpdb->get_results( $user_sql, "ARRAY_A" );
            
            $is_there_guest = 0 == $wpdb->get_var("SELECT MIN(user_id) FROM {$submissions_table}");
            
            if ($is_there_guest) {
                $users[] = array('user_id' => 0, 'display_name' => 'Guests');
            }

            $surveys_sql = "SELECT
                    $submissions_table.survey_id,
                    $surveys_table.title 
                FROM
                    $submissions_table
                JOIN $surveys_table ON $submissions_table.survey_id = $surveys_table.id
                GROUP BY
                    $submissions_table.survey_id";

            if( ! current_user_can( 'manage_options' ) ){
                $surveys_sql = "SELECT
                    s.survey_id,
                    survey.title 
                FROM $submissions_table AS s
                JOIN $surveys_table AS survey
                    ON s.survey_id = survey.id
                WHERE survey.author_id = {$current_user}
                GROUP BY
                    s.survey_id";
            }
            $surveys = $wpdb->get_results( $surveys_sql, "ARRAY_A" );

            $min_date_sql = "SELECT DATE(MIN(submission_date)) FROM {$submissions_table}";
            $max_date_sql = "SELECT DATE(MAX(submission_date)) FROM {$submissions_table}";

            if( ! current_user_can( 'manage_options' ) ){
                $min_date_sql = "SELECT DATE(MIN(s.submission_date))
                                FROM {$submissions_table} AS s
                                LEFT JOIN {$surveys_table} AS survey
                                    ON s.survey_id = survey.id
                                WHERE survey.author_id = ".$current_user;
                $max_date_sql = "SELECT DATE(MAX(s.submission_date))
                                FROM {$submissions_table} AS s
                                LEFT JOIN {$surveys_table} AS survey
                                    ON s.survey_id = survey.id
                                WHERE survey.author_id = ".$current_user;
            }

            $date_min = $wpdb->get_var($min_date_sql);
            $date_max = $wpdb->get_var($max_date_sql);
            
            $sql = "SELECT COUNT(*) FROM {$submissions_table} WHERE `status` = 'published' ORDER BY id DESC";
            if( ! current_user_can( 'manage_options' ) ){
                $sql = "SELECT COUNT(*) 
                        FROM {$submissions_table} AS s
                        LEFT JOIN {$surveys_table} AS survey
                            ON s.survey_id = survey.id
                        WHERE survey.author_id = {$current_user}
                        ORDER BY s.id DESC";
            }

            if (isset($_REQUEST['flag']) && $_REQUEST['flag']) {
                $survey_id_sql = "SELECT survey_id FROM {$submissions_table}";
                if( ! current_user_can( 'manage_options' ) ){
                    $survey_id_sql = "SELECT sb.survey_id 
                                    FROM {$submissions_table} AS sb
                                    LEFT JOIN {$surveys_table} AS ss
                                        ON sb.survey_id = ss.id
                                    WHERE ss.author_id = ".$current_user;
                }
                $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? intval($_REQUEST['survey_id']) : $survey_id_sql;
                $sql = "SELECT COUNT(*) FROM {$submissions_table} WHERE survey_id IN ($survey_id) AND `status` = 'published' ORDER BY id DESC";
                if( ! current_user_can( 'manage_options' ) ){
                    $sql = "SELECT COUNT(*) 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id
                            WHERE survey.author_id = {$current_user} AND
                                survey_id IN ($survey_id)
                            ORDER BY s.id DESC";
                }
            }
            $count = $wpdb->get_var($sql);

            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                "status"   => true,
                "surveys"  => $surveys, 
                "users"    => $users,
                "min_date" => $date_min,
                "max_date" => $date_max,
                "count"    => $count
            ));
            wp_die();
        } else {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                "status"   => false,
                "surveys"  => array(), 
                "users"    => array(),
                "min_date" => '',
                "max_date" => '',
                "count"    => ''
            ));
            wp_die();
        }
    }

    public function ays_submissions_export_file(){
        global $wpdb;

        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $surveys_table     = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        
        $user_id_sql   = "SELECT DISTINCT user_id FROM {$submissions_table}";
        $survey_id_sql = "SELECT DISTINCT survey_id FROM {$submissions_table}";

        $current_user = get_current_user_id();

        if( ! current_user_can( 'manage_options' ) ){
            $user_id_sql = "SELECT DISTINCT s.user_id 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id
                            WHERE s.status = 'published' AND survey.author_id = ".$current_user;
            $survey_id_sql = "SELECT DISTINCT s.survey_id 
                            FROM {$submissions_table} AS s
                            LEFT JOIN {$surveys_table} AS survey
                                ON s.survey_id = survey.id
                            WHERE s.status = 'published' AND survey.author_id = ".$current_user;
        }
        $user_id_result   = $wpdb->get_col($user_id_sql);
        $survey_id_result = $wpdb->get_col($survey_id_sql);

        $user_id     = (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != null) ? implode(',', $_REQUEST['user_id']) : implode(',', $user_id_result);
        $survey_id   = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? implode(',', $_REQUEST['survey_id']) : implode(',', $survey_id_result);
        $date_from   = isset($_REQUEST['date_from']) && $_REQUEST['date_from'] != '' ? sanitize_text_field($_REQUEST['date_from']) : '2000-01-01';
        $date_to     = isset($_REQUEST['date_to']) && $_REQUEST['date_to'] != '' ? sanitize_text_field($_REQUEST['date_to']) : current_time('Y-m-d');
        $type        = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

        if( ! current_user_can( 'manage_options' ) ){
            $sql = "SELECT s.*
                    FROM {$submissions_table} AS s
                    LEFT JOIN {$surveys_table} AS survey
                        ON s.survey_id = survey.id
                    WHERE
                    survey.author_id = {$current_user} AND
                        s.user_id IN ($user_id) AND
                        s.survey_id IN ($survey_id) AND
                        s.start_date BETWEEN '$date_from' AND '$date_to 23:59:59' AND
                        s.status = 'published'
                    ORDER BY s.id DESC";
        }else{
            $sql = "SELECT *
                    FROM {$submissions_table}
                    WHERE
                        user_id IN ($user_id) AND
                        survey_id IN ($survey_id) AND
                        start_date BETWEEN '$date_from' AND '$date_to 23:59:59' AND
                        `status` = 'published'
                    ORDER BY id DESC";
        }

        $results = $wpdb->get_results($sql);

        switch($type){
            case 'csv':
                $export_data = $this->ays_submissions_export_csv($results);
            break;
            case 'xlsx':
                $export_data = $this->ays_submissions_export_xlsx($results);
            break;
            case 'json':
                $export_data = $this->ays_submissions_export_json($results);
            break;
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode($export_data);
        wp_die();
    }

    public function ays_submissions_export_csv($results){
        global $wpdb;

        $export_file_fields  = array('user','user_ip','user_name','user_email','survey_name','submission_date','unique_code');
        $export_file_fields0 = array('','','','','','');
        $results_array_csv   = array();

        if(empty($results)){
            $export_data = array(
                'status'        => true,
                'data'          => $export_file_fields0,
                'fileFields'    => $export_file_fields,
                'type'          => 'csv'
            );
        }else{
            foreach ($results as $key => $result){
                $result      = (array)$result;
                $survey_id   = intval($result['survey_id']);
                $survey      = Survey_Maker_Data::get_survey_by_id($survey_id); 
                $survey_name = stripslashes($survey->title);

                unset($result['survey_id'],$result['id']);
                $user = get_user_by('id', $result['user_id']);

                if( $user !== false ){
                    $user_name = $user->data->display_name;
                }else{
                    $user_name = __( 'Guest', $this->plugin_name );
                }
                $results_array_csv[] = array(
                    $user_name,
                    $result['user_ip'],
                    $result['user_name'],
                    $result['user_email'],
                    $survey_name,
                    $result['submission_date'],
                    $result['unique_code'],
                );
            }
            $export_data = array(
                'status'        => true,
                'data'          => $results_array_csv,
                'fileFields'    => $export_file_fields,
                'type'          => 'csv'
            );
        }
        return $export_data;
    }
    
    public function ays_submissions_export_xlsx($results){
        global $wpdb;
        
        $results_array = array();
        
        $results_headers = array(
            array( 'text' => "user" ),
            array( 'text' => "user_ip" ),
            array( 'text' => "user_name" ),
            array( 'text' => "user_email" ),
            array( 'text' => "survey_name" ),
            array( 'text' => "submission_date" ),
            array( 'text' => "unique_code" ),
        );  
        $results_array[] = $results_headers;
        
        foreach ($results as $key => $result){
            
            $result      = (array)$result;
            $survey_id   = intval($result['survey_id']);
            $survey      = Survey_Maker_Data::get_survey_by_id($survey_id);
            $survey_name = stripslashes($survey->title);

            unset($result['survey_id'],$result['id']);
            $user = get_user_by('id', $result['user_id']);

            if( $user !== false ){
                $user_name = $user->data->display_name;
            }else{
                $user_name = __( 'Guest', $this->plugin_name );
            }
            $res_array = array(
                array( 'text' => $user_name ),
                array( 'text' => $result['user_ip'] ),
                array( 'text' => $result['user_name'] ),
                array( 'text' => $result['user_email'] ),
                array( 'text' => $survey_name ),
                array( 'text' => $result['submission_date'] ),
                array( 'text' => $result['unique_code'] ),
            );
            
            $results_array[] = $res_array;
        }
        
        $response = array(
            'status' => true,
            'data'   => $results_array,
            "type"   => 'xlsx'
        );
        return $response;
    }
    
    public function ays_submissions_export_json($results){
        global $wpdb;
        
        $results_array = array();
        foreach ($results as $key => $result){
            $result       = (array)$result;
            $survey_id    = intval($result['survey_id']);
            $survey       = Survey_Maker_Data::get_survey_by_id($survey_id);
            $survey_name  = stripslashes($survey->title);

            unset($result['survey_id'],$result['id']);
            $user = get_user_by('id', $result['user_id']);

            if( $user !== false ){
                $user_name = $user->data->display_name;
            }else{
                $user_name = __( 'Guest', $this->plugin_name );
            }
            $res_array = array( 
                'user'            => $user_name,
                'user_ip'         => $result['user_ip'],
                'user_name'       => $result['user_name'],
                'user_email'      => $result['user_email'],
                'survey_name'     => $survey_name,
                'submission_date' => $result['submission_date'],
                'unique_code'     => $result['unique_code'],
            );
            
            $results_array[] = $res_array;
        }
        
        $response = array(
            'status' => true,
            'data'   => $results_array,
            "type"   => 'json'
        );
        return $response;
    }

    // ==================================================================
    // =================== Submission export filters  ===================
    // ========================     End    ==============================

    // ==================================================================
    // =====================  Questions library  ========================
    // ========================    START   ==============================

    public function ays_survey_get_questions_library(){
        global $wpdb;
        $survey_id = isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != '' ? intval($_REQUEST['survey_id']) : 0;
        $start = isset($_REQUEST['start']) && $_REQUEST['start'] != '' ? intval($_REQUEST['start']) : 0;
        $length = isset($_REQUEST['length']) && $_REQUEST['length'] != '' ? intval($_REQUEST['length']) : 5;
        $search = isset($_REQUEST['search']) && !empty($_REQUEST['search']) ? $_REQUEST['search'] : array();
        $cats = isset($_REQUEST['cats']) && !empty($_REQUEST['cats']) ? $_REQUEST['cats'] : array();
        $search_value = isset($search['value']) && $search['value'] != '' ? esc_sql( $search['value'] ) : '';

        $order = isset($_REQUEST['order']) && !empty($_REQUEST['order']) ? $_REQUEST['order'] : array();
        $order_col = isset($order[0]['column']) && $order[0]['column'] != '' ? intval($order[0]['column']) : 0;
        $order_dir = isset($order[0]['dir']) && $order[0]['dir'] != '' ? esc_sql($order[0]['dir']) : ' DESC ';
        $order_columns = array(
            0 => ' q.id ',
            1 => ' q.question ',
            2 => ' q.type ',
            3 => ' q.date_created ',
            4 => ' q.date_modified ',
            5 => ' q.id ',
        );
        $order_column = $order_columns[$order_col];
        $order_dir = strtoupper($order_dir);

        $where = array();
        if($search_value != ''){
            $where[] = " q.id LIKE '%".$search_value."%' ";
            $where[] = " q.question LIKE '%".$search_value."%' ";
            $where[] = " q.type LIKE '%".$search_value."%' ";
            // $where[] = " q.date_created LIKE '%".$search_value."%' ";
            // $where[] = " q.date_modified LIKE '%".$search_value."%' ";
            // $where[] = " c.title LIKE '%".$search_value."%' ";
        }

        $where_sql = "";

        if( ! empty( $cats ) ){
            $where_sql = " AND s.id IN (" . implode(",", $cats) . ") ";
        }

        if( ! empty( $where ) ){
            $where_sql = " AND ( " . implode(" OR ", $where) . ") ";
        }

        $limit = " LIMIT " . $start . ", " . $length;

        if(intval($length) < 0){
            $limit = '';
        }

        $sql = "SELECT q.*
                FROM {$wpdb->prefix}". SURVEY_MAKER_DB_PREFIX . "questions AS q
                JOIN {$wpdb->prefix}". SURVEY_MAKER_DB_PREFIX . "surveys AS s
                    ON FIND_IN_SET( q.id, s.question_ids )
                WHERE s.id != " . $survey_id . " AND q.status = 'published' ".$where_sql."
                ORDER BY ".$order_column." ".$order_dir."
                ". $limit ."";
        $question_id_array = array();
        if($survey_id != 0){
            $survey = Survey_Maker_Data::get_survey_by_id( $survey_id );
            $question_id_array = isset( $survey->question_ids ) && $survey->question_ids != '' ? explode(',', $survey->question_ids ) : array();
            // $question_question_id_array = $survey['question_ids'];
        }
        // die();
        $questions = $wpdb->get_results( $sql, 'ARRAY_A' );

        $results = array();
        $json = array();

        $sql = "SELECT COUNT(*)
                FROM {$wpdb->prefix}". SURVEY_MAKER_DB_PREFIX . "questions AS q
                JOIN {$wpdb->prefix}". SURVEY_MAKER_DB_PREFIX . "surveys AS s
                    ON FIND_IN_SET( q.id, s.question_ids )
                WHERE s.id != " . $survey_id . " AND q.status = 'published' 
                ORDER BY q.id DESC";
        $total_count = $wpdb->get_var($sql);

        $sql = "SELECT COUNT(*)
                FROM {$wpdb->prefix}socialsurv_questions AS q
                JOIN {$wpdb->prefix}socialsurv_surveys AS s
                    ON FIND_IN_SET( q.id, s.question_ids )
                WHERE s.id != " . $survey_id . " AND q.status = 'published' ".$where_sql."
                ORDER BY q.id DESC";
        $filtered_count = $wpdb->get_var($sql);

        $json["recordsTotal"] = intval($total_count);
        $json["recordsFiltered"] = intval($filtered_count);
        $json['loader'] = SURVEY_MAKER_ADMIN_URL . "/images/loaders/tail-spin.svg";
        $json['loaderText'] = __( "Processing...", $this->plugin_name );

        $used_questions = array(); //$this->get_published_questions_used();
        foreach ($questions as $index => $question) {

            if ( in_array( $question["id"], $question_id_array ) ){
                // $first_column .= '<i class="ays-select-single ays_fa ays_fa_check_square_o"></i>';
                continue;
            }
            // $question_options = json_decode($question['options'], true);
            $create_date = isset($question['date_created']) && $question['date_created'] != '' ? $question['date_created'] : "0000-00-00 00:00:00";
            $modified_date = isset($question['date_modified']) && $question['date_modified'] != '' ? $question['date_modified'] : "0000-00-00 00:00:00";

            $first_column = '<span>';
                $first_column .= '<i class="ays-select-single ays_fa ays_fa_square_o"></i>';
            $first_column .= '</span>';

            if( self::validateDate( $create_date ) ){
                $date = date( 'Y/m/d', strtotime( $question['date_created'] ) );
                $date2 = date( 'H:i:s', strtotime( $question['date_created'] ) );
                $title_date = date( 'l jS \of F Y h:i:s A', strtotime( $question['date_created'] ) );
                $create_date_html = "<p style=';font-size:14px;margin:0;text-decoration: dotted underline;' title='" . $title_date . "'>" . $date2 . "<br>" . $date . "</p>";
            }

            if( self::validateDate( $modified_date ) ){
                $date = date( 'Y/m/d', strtotime( $question['date_modified'] ) );
                $date2 = date( 'H:i:s', strtotime( $question['date_modified'] ) );
                $title_date = date( 'l jS \of F Y h:i:s A', strtotime( $question['date_modified'] ) );
                $modified_date_html = "<p style=';font-size:14px;margin:0;text-decoration: dotted underline;' title='" . $title_date . "'>" . $date2 . "<br>" . $date . "</p>";
            }
            // if($author['name'] !== "Unknown"){
            //     $text .= "<p style='margin:0;text-align:left;'><b>Author:</b> ".$author['name']."</p>";
            // }

            $question_types = array(
                "radio" => __("Radio", $this->plugin_name),
                "checkbox" => __("Checkbox (Multi)", $this->plugin_name),
                "select" => __("Dropdown", $this->plugin_name),
                "linear_scale" => __("Linear Scale", $this->plugin_name),
                "star" => __("Star Rating", $this->plugin_name),
                "text" => __("Paragraph", $this->plugin_name),
                "short_text" => __("Short Text", $this->plugin_name),
                "number" => __("Number", $this->plugin_name),
                "phone" => __("Phone", $this->plugin_name),
                "date" => __("Date", $this->plugin_name),
                "time" => __("Time", $this->plugin_name),
                "date_time" => __("Date and Time", $this->plugin_name),
                "matrix_scale" => __("Matrix Scale", $this->plugin_name),
                "matrix_scale_checkbox" => __("Matrix Scale Checkbox", $this->plugin_name),
                "star_list" => __("Star List", $this->plugin_name),
                "slider_list" => __("Slider List", $this->plugin_name),
                "yesorno" => __("Yes or No", $this->plugin_name),
                "range" => __("Slider", $this->plugin_name),
                "upload" => __("File upload", $this->plugin_name),
                "html" => __("HTML", $this->plugin_name),
                "hidden" => __("Hidden", $this->plugin_name),
                "email" => __("Email", $this->plugin_name),
                "name" => __("Name", $this->plugin_name),
            );
            
            $qtype = $question_types[ $question['type'] ];

            $selected_question = in_array( $question["id"], $question_id_array ) ? "selected" : "";
            $table_question = strip_tags( stripslashes( $question['question'] ) );
            $table_question = $this->ays_restriction_string( "word", $table_question, 8 );

            $results[] = array(
                'first_column' => $first_column,
                'id' => $question['id'],
                'type' => $qtype,
                'question' => $table_question,
                'create_date' => $create_date_html,
                'modified_date' => $modified_date_html,
                'selected' => $selected_question,
            );
        }
        $json["data"] = $results;
        return $json;
    }
    
    /**
     * Adding questions from modal
     */
    public function ays_add_questions_from_library(){

        $question_ids = $_REQUEST["ays_questions_ids"];
        $rows = array();
        $ids = array();
        if (!empty($question_ids)) {
            $questions = Survey_Maker_Data::get_question_by_ids( $question_ids );
            foreach ( $questions as $key => &$question ) {
                $question = (array) $question;
                $question['options'] = isset( $question['options'] ) && $question['options'] != '' ? json_decode( $question['options'], true ) : array();

                if( !empty( $question['answers'] ) ){
                    foreach( $question['answers'] as $ans_key => &$answer ){
                        $answer = (array) $answer;
                        $answer['options'] = isset( $answer['options'] ) && $answer['options'] != '' ? json_decode( $answer['options'], true ) : array();
                    }
                }

                $rows[ $question['id'] ] = $question;
            }

            return array(
                'status' => true,
                'questions' => $rows,
                'ids' => $ids
            );
        } else {
            return array(
                'status' => true,
                'questions' => array(),
                'ids' => array()
            );
        }
    }

    // ==================================================================
    // =====================  Questions library  ========================
    // ========================     End    ==============================

    // Questions content live preview
    public function ays_live_preivew_content(){

        $content = isset($_REQUEST['content']) && $_REQUEST['content'] != '' ? wp_kses_post( $_REQUEST['content'] ) : null;
        if($content === null){
            return array(
                'status' => false,
            );
        }
        
        // $content = Survey_Maker_Data::ays_autoembed( $content );
        $content = stripslashes( wpautop( $content ) );
        return array(
            'status' => true,
            'content' => $content,
        );
        wp_die();
    }

    public function get_current_survey_statistic(){
        $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != "") ? absint( $_REQUEST['survey_id'] ) : 0;
        $data = array();

        $data = Submissions_List_Table::get_results_dates( $survey_id );
        $result = array();
        if( ! empty( $data ) ){
            $result = array(
                'status' => true,
                'datesCounts' => $data,
            );
        }else{
            $result = array(
                'status' => false,
                'message' => __( 'There is no data to show.', $this->plugin_name )
            );
        }

        return $result;
    }

    public function ays_survey_import_questions(){
        $status = false;
        $data   = array();
        $all_answers_for_js = array();
        require_once(SURVEY_MAKER_DIR . 'includes/PHPExcel/vendor/autoload.php');
        $spreadsheet = IOFactory::load($_FILES['questions_data']['tmp_name']);
        $sheet_section_names = $spreadsheet->getSheetNames();
        $row_data = array();
        $row_section_titles = array();
        foreach($sheet_section_names as $section_names_key => $section_name){
            $highest_row = 0;
            $highest_column = 0;
            $current_sheet = "";
            $current_sheet = $spreadsheet->getSheet($section_names_key);
            $highest_row = $current_sheet->getHighestRow(); 
            $highest_column = $current_sheet->getHighestColumn();
            //  Loop through each row of the worksheet in turn
            
            $row_section_titles[$section_names_key] = $section_name;
            for ($row = 1; $row <= $highest_row; $row++){ 
                //  Read a row of data into an array
                $ready_array = $current_sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, "", false, true );
                //  Insert row data array into your database of choice here
                $ready_array = array_values( $ready_array );
                $row_data[$section_names_key][] = $ready_array[0];
            }
        }
        
        $ready_data = array();
        $all_answers = array();
        foreach($row_data as $row_key => $row_value){
            $headings = array_shift($row_value);
            foreach($row_value as $row_value_key => $row_value_value){
                $answers = array();
                $data_collect = array();
                foreach($row_value_value as $s_key => $s_value){
                    $heading_key = strtolower( $headings[$s_key] );
                    if($s_value != ""){
                        $data_collect[ $heading_key ] = $s_value;
                    }
                    if( $heading_key != "question" && $heading_key != "type" ){
                        if($s_value != ""){
                            $answers[] = $s_value;
                        }
                    }
                    
                }
                $ready_data[$row_key][] = $data_collect;
                $all_answers[$row_key][] = $answers;
            }
        }
        if(!empty($ready_data)){
            $status = true;
            $data = $ready_data;
        }

        $survey_titles = array();
        if(!empty($row_section_titles)){
            $survey_titles = $row_section_titles;
        }

        if(!empty($all_answers)){
            $all_answers_for_js = $all_answers;
        }
        $response = array(
            'status'          => $status,
            'data'            => $data,
            'survey_titles'   => $survey_titles,
            'all_answers'     => $all_answers_for_js
        );
        return $response;
    }


    // Survey Summary export to xlsx start An
    public function ays_survey_export_submissions_to_xlsx() {
    
        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_export_submissions_to_xlsx') {
            $survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != null) ? intval($_REQUEST['survey_id']) : null;

            if($survey_id === null){
                return array(
                    'status' => false,
                );
            }

            $results = Survey_Maker_Data::get_survey_submission_results( $survey_id );

            $question_data = array();
            foreach($results as $result_key => $result){
                //Question
                $question = ( isset( $result['question'] ) && $result['question'] != '' ) ? stripslashes( $result['question'] ) : '';

                //Question type
                $question_type = ( isset( $result['question_type'] ) && $result['question_type'] != '' ) ? stripslashes( $result['question_type'] ) : '';

                //Sum of aswers
                $sum_of_answers = ( isset( $result['sum_of_answers'] ) && $result['sum_of_answers'] != '' ) ? intval( $result['sum_of_answers'] ) : 0;
                
                //Sum of each answer
                $sum_of_each_answer = ( isset( $result['sum_of_each_answer'] ) && !empty($result['sum_of_each_answer']) ) ? $result['sum_of_each_answer'] : array();
                
                //Percent of each answer
                $percents = ( isset( $result['percent'] ) && !empty($result['percent']) ) ? $result['percent'] : array();

                //Percent of each answer
                $columns = ( isset( $result['columns'] ) && !empty($result['columns']) ) ? $result['columns'] : array();

                $answers = array();
                $answers[] =  array('text' => $question);
                
                if( $question_type != 'matrix_scale' && $question_type != 'matrix_scale_checkbox' && $question_type != 'star_list' && $question_type != 'slider_list' ){
                    $sum_of_each_answers = array();
                    $sum_of_each_answers[] = array('text' => __('Each answer count ('.$sum_of_answers.')', $this->plugin_name));

                    $percent_arr = array();
                    $percent_arr[] = array('text' => __('Percent of each answer', $this->plugin_name));

                    foreach ($percents as $percent_key => $percent) {
                        $answers[] = array('text' => strval($percent_key));
                        $each_answer_sum = isset($sum_of_each_answer[$percent_key]) ? $sum_of_each_answer[$percent_key] : "";
                        $sum_of_each_answers[] = array('text' => $each_answer_sum);
                        $percent_arr[] = array('text' => round( $percent ).'%');
                    }

                    $question_data[] = $answers;
                    $question_data[] = $percent_arr;
                    $question_data[] = $sum_of_each_answers;
                    $question_data[] = array(array('text' => ''));
                }else{

                    foreach($columns as $col => $column){
                        $answers[] = array('text' => $column);
                    }
                    $question_data[] = $answers;

                    foreach ($percents as $percent_key => $percent) {
                        $rows = array();
                        $rows[] = array('text' => $percent_key);
                        foreach($percent as $p_key => $p_val){
                            $rows[] = array('text' => round( $p_val ).'%');
                        }
                        $question_data[] = $rows;
                    }   

                    $question_data[] = array( array( 'text', '' ) );
                }
            }

            return array(
                'status' => true,
                'type'   => 'xlsx',
                'data'   => $question_data
            );
        }
    }
    //Survey Summary export to xlsx end

    public function ays_survey_single_submission_results_csv_export() {
        
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $questions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        
        if (isset($_REQUEST['function']) && sanitize_text_field($_REQUEST['function']) == 'ays_survey_single_submission_results_csv_export') {

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
            $options   = json_decode($results['options']);

            // $start_date      = (isset($results['start_date']) && sanitize_text_field($results['start_date']) != '') ? stripslashes( sanitize_text_field( $results['start_date'] ) ) : '';
            // $end_date        = (isset($results['end_date']) && sanitize_text_field($results['end_date']) != '') ? stripslashes( sanitize_text_field( $results['end_date'] ) ) : '';
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

            $export_data = array();

            $user_information = array(
                __( 'User Information', $this->plugin_name )
            );
            $export_file_fields[] = $user_information;

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
                    $user_information_headers[$key] ,
                    $user_information_results[$key],
                    '',
                );
                $results_array_csv[] = $user_results;
            }


            $survey_information = array(
                __( 'Survey Information', $this->plugin_name )
            );

            $results_array_csv[] = $survey_information;
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
                        $survey_information_headers[$key],
                        $survey_information_results[$key],
                        '',
                    );
                    $results_array_csv[] = $user_results;
                }

            }

            $questions_headers = array(
                __( "Questions", $this->plugin_name ),
                __( "User answers", $this->plugin_name ),
                __( "User explanation", $this->plugin_name ),
            );
            $results_array_csv[] = $questions_headers;

            $questions_ids_str  = ( isset($results['questions_ids']) && ( sanitize_text_field( $results['questions_ids'] ) !== '' || sanitize_text_field( $results['questions_ids'] ) !== null) ) ? stripslashes( sanitize_text_field( $results['questions_ids'] ) ) : '';

            $questions_ids_arr = array();
            if ($questions_ids_str != '') {
                $questions_ids_arr = explode(',', $questions_ids_str);

                $attr = array();
                foreach ($questions_ids_arr as $key => $questions_id) {
                    if ($questions_id != '') {
                        $question_id      = absint(intval( $questions_id ));
                        $question_content = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                        $question         = ( isset($question_content["question"]) && $question_content["question"] != '' ) ? htmlspecialchars_decode( trim( stripslashes($question_content["question"]) ) ) : '';
                        $question_type    = isset($question_content['type']) && $question_content['type'] != "" ? $question_content ['type'] : "";
                        $question_options = isset($question_content['options']) && $question_content['options'] != "" ? json_decode($question_content ['options'] , true) : "";

                        if ($question == '') {
                            $question = ' - ';
                        }

                        $attr = array(
                            'submission_id'    => $submission_id,
                            'question_id'      => $question_id,
                            'survey_id'        => $survey_id,
                            'question_type'    => $question_type,
                            'question_options' => $question_options,
                            'all_results'      => false,
                            'is_csv'           => true,
                        );
                        $user_answered = Survey_Maker_Data::ays_survey_get_user_answered($attr);
                        
                        if($question_type != "matrix_scale" && $question_type != "star_list" && $question_type != "slider_list" && $question_type != "matrix_scale_checkbox"){
                            $user_answer = html_entity_decode( strip_tags( stripslashes( $user_answered ) ) );
                            $user_explanation_text = isset( $each_explanation[ $questions_id ] ) ? $each_explanation[ $questions_id ] : "";
                            $results_array_csv[] = array(
                                $question,
                                $user_answer,
                                $user_explanation_text,
                            );
                        }else{
                            $user_answer = $user_answered;
                            $user_explanation_text = isset($each_explanation[$questions_id]) ? $each_explanation[$questions_id] : "";
                            foreach($user_answer as $mat_key => $mat_value){
                                $results_array_csv[] = array(
                                    $question,
                                    $mat_key . ": " . $mat_value ,
                                    $user_explanation_text,
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
                'data'          => $results_array_csv,
                'fileFields'    => $export_file_fields,
                'type'          => 'csv'
            );

            return $export_data;
        }
    }

    /* FRONTEND REQUEST START */
    public function ays_survey_approve_front_requests () {
		if(isset($_REQUEST['action']) && $_REQUEST['action'] != ''){
			error_reporting(0);
			global $wpdb;

			$approved_id = (isset($_REQUEST['approved_id']) && $_REQUEST['approved_id'] != '') ? intval($_REQUEST['approved_id']) : null;

			if ($approved_id == null) {
				return;
			}

			$requests_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'requests';
            $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
            $questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'questions';
            $sections_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sections';
            $answers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'answers';

            $sql = "SELECT * FROM {$requests_table} WHERE id=".$approved_id;
            $results = $wpdb->get_results( $sql, 'ARRAY_A' )[0];

            if (!empty($results)) {
                $category_ids = isset($results['category_id']) && intval($results['category_id']) > 0 ? $results['category_id'] : '1';
                $author_id = isset($results['user_id']) && $results['user_id'] != '' ? intval($results['user_id']) : 0;
                $user_ip = isset($results['user_ip']) && $results['user_ip'] != '' ? $results['user_ip'] : '';
                $title = isset($results['survey_title']) && $results['survey_title'] != '' ? sanitize_text_field($results['survey_title']) : 'Survey';
                $date_created = isset($results['request_date']) && $results['request_date'] != '' ? $results['request_date'] : current_time( 'mysql' );
                
                $data = isset($results['request_data']) && $results['request_data'] != '' ? json_decode($results['request_data'], true) : array();

                /* Sections start */
                    $section_ids = array();
                    $section_options = array(
                        'collapsed' => "expanded",
                        'go_to_section' => '-1'
                    );

                    $sections_default = array(
                        'title'         => "",
                        'description'   => "",
                        'ordering'      => 1,
                        'options'       => json_encode($section_options),
                    );

                    $result = $wpdb->insert(
                        $sections_table,
                        $sections_default,
                        array(
                            '%s', // title
                            '%s', // description
                            '%d', // ordering
                            '%s', // options
                        )
                    );
                    $section_insert_id = $wpdb->insert_id;
                    $section_ids[] = $section_insert_id;
                /* Sections end */

                /* Questions start */
                    $question_options = array(
                        'required' => 'off',
                        'collapsed' => 'expanded',
                        'linear_scale_1' => '',
                        'linear_scale_2' => '',
                        'scale_length' => '5',
                        'star_1' => '',
                        'star_2' => '',
                        'star_scale_length' => '5',
                        'matrix_columns' => '',
                        'star_list_stars_length' => '',
                        'slider_list_range_length' => '',
                        'slider_list_range_step_length' => '',
                        'slider_list_range_min_value' => '',
                        'slider_list_range_default_value' => '',
                        'slider_list_range_calculation_type' => 'seperatly',
                        'enable_max_selection_count' => 'off',
                        'max_selection_count' => '',                                
                        'min_selection_count' => '',

                        // Text Limitations
                        'enable_word_limitation' => 'off',
                        'limit_by' => '',
                        'limit_length' => '',
                        'limit_counter' => 'off',

                        // Number Limitations
                        'enable_number_limitation'    => 'off',
                        'number_min_selection'        => '',
                        'number_max_selection'        => '',
                        'number_error_message'        => '',
                        'enable_number_error_message' => 'off',  
                        'number_limit_length'         => '',
                        'enable_number_limit_counter' => 'off',

                        'survey_input_type_placeholder' => '',

                        // Question caption
                        'image_caption'        => '',
                        'image_caption_enable' => 'off',

                        'is_logic_jump' => 'off',
                        'other_answer_logic_jump' => 'off',
                        'user_explanation' => 'off',
                        'with_editor' => 'off',
                        'range_length'        => '',
                        'range_step_length'   => '',
                        'range_min_value'     => '',
                        'range_default_value' => '',
                        'enable_admin_note'   => 'off',
                        'admin_note'          => '',
                        'enable_url_parameter'=> 'off',
                        'url_parameter'       => '',
                        'enable_hide_results' => 'off',
                        'file_upload_toggle'     => 'off',
                        'file_upload_types_pdf'  => 'off',
                        'file_upload_types_doc'  => 'off',
                        'file_upload_types_png'  => 'off',
                        'file_upload_types_jpg'  => 'off',
                        'file_upload_types_gif'  => 'off',
                        'file_upload_types_size' => '5',
                        'other_logic_jump' => array(),
                        'other_logic_jump_otherwise' => -1,
                        'html_types_content'     => '',
                    );
                    
                    $question_ids = array();
                    foreach ($data as $question_id => $question) {
                        $question_title = isset($question['question']) && $question['question'] != '' ? sanitize_Text_field($question['question']) : 'Question';
                        $question_type = isset($question['type']) && $question['type'] != '' ? sanitize_Text_field($question['type']) : 'radio';

                        $question_result = $wpdb->insert(
                            $questions_table,
                            array(
                                'author_id'         => $author_id,
                                'section_id'        => $section_insert_id,
                                'category_ids'      => $category_ids,
                                'question'          => $question_title,
                                'question_description'  => '',
                                'type'              => $question_type,
                                'status'            => 'published',
                                'trash_status'      => '',
                                'date_created'      => $date_created,
                                'date_modified'     => $date_created,
                                'user_variant'      => 'off',
                                'user_explanation'  => '',
                                'image'             => '',
                                'ordering'          => $question_id,
                                'options'           => json_encode($question_options, JSON_UNESCAPED_SLASHES),
                            ),
                            array(
                                '%d', // author_id
                                '%d', // section_id
                                '%s', // category_ids
                                '%s', // question
                                '%s', // question_description
                                '%s', // type
                                '%s', // status
                                '%s', // trash_status
                                '%s', // date_created
                                '%s', // date_modified
                                '%s', // user_variant
                                '%s', // user_explanation
                                '%s', // image
                                '%d', // ordering
                                '%s', // options
                            )
                        );
                        $question_insert_id = $wpdb->insert_id;
                        $question_ids[] = $question_insert_id;

                        /* Answers start */
                            $answers = isset($question['answers']) && $question['answers'] != '' ? $question['answers'] : array();
                            foreach ($answers as $answer_id => $answer) {
                                $answer_title = isset($answer['answer']) && $answer['answer'] != '' ? sanitize_Text_field($answer['answer']) : 'Option '.$answer_id;

                                $answer_result = $wpdb->insert(
                                    $answers_table,
                                    array(
                                        'question_id'       => $question_insert_id,
                                        'answer'            => $answer_title,
                                        'image'             => '',
                                        'ordering'          => $answer_id,
                                        'placeholder'       => '',
                                        'options'           => json_encode( array("go_to_section" => -1) )
                                    ),
                                    array(
                                        '%d', // question_id
                                        '%s', // answer
                                        '%s', // image
                                        '%d', // ordering
                                        '%s', // placeholder
                                        '%s', // options
                                    )
                                );
                            }
                        /* Answers end */
                    }

                /* Questions end */

                // Survey options 
                $default_options = array(
                    // Styles Tab
                    'survey_theme' => 'classic_light',
                    'survey_color' => 'rgb(255, 87, 34)', // #673ab7
                    'survey_background_color' => '#fff',
                    'survey_text_color' => '#333',
                    'survey_buttons_text_color' => '#333',
                    'survey_width' => '',
                    'survey_width_by_percentage_px' => 'pixels',
                    'survey_mobile_max_width' => '',
                    'survey_custom_class' => '',
                    'survey_custom_css' => '',
                    'survey_logo' => '',
            
                    'survey_question_font_size' => 16,
                    'survey_question_image_width' => '',
                    'survey_question_image_height' => '',
                    'survey_question_image_sizing' => 'cover',
                    'survey_question_padding' => 24,
                    'survey_question_caption_text_color' => '#333',
                    'survey_question_caption_text_alignment' => 'center',
                    'survey_question_caption_font_size' => 16,
                    'survey_question_caption_font_size_on_mobile' => 16,
                    'survey_question_caption_text_transform' => 'none',
            
                    'survey_answer_font_size' => 15,
                    'survey_answer_font_size_on_mobile' => 15,
                    'survey_answers_view' => 'grid',
                    'survey_answers_view_alignment' => 'space_around',
                    'survey_answers_object_fit' => 'cover',
                    'survey_answers_padding' => 8,
                    'survey_answers_gap' => 0,
            
                    'survey_buttons_size' => 'medium',
                    'survey_buttons_font_size' => 14,
                    'survey_buttons_left_right_padding' => 24,
                    'survey_buttons_top_bottom_padding' => 0,
                    'survey_buttons_border_radius' => 4,
                    'survey_buttons_alignment' => 'left',
                    'survey_buttons_top_distance' => 10,

                    // Settings Tab
                    'survey_show_title' => 'off',
                    'survey_show_section_header' => 'on',
                    'survey_enable_randomize_answers' => 'off',
                    'survey_enable_randomize_questions' => 'off',
                    'survey_enable_clear_answer' => 'on',
                    'survey_enable_previous_button' => 'on',
                    'survey_allow_html_in_answers' => 'off',
                    'survey_enable_leave_page' => 'on',
                    'survey_enable_info_autofill' => 'off',
                    'survey_enable_schedule' => 'off',
                    'survey_schedule_active' => current_time( 'mysql' ),
                    'survey_schedule_deactive' => current_time( 'mysql' ),
                    'survey_schedule_show_timer' => 'off',
                    'survey_show_timer_type' => 'countdown',
                    'survey_schedule_pre_start_message' =>  __("The survey will be available soon!", SURVEY_MAKER_NAME),
                    'survey_schedule_expiration_message' => __("This survey has expired!", SURVEY_MAKER_NAME),
                    'survey_dont_show_survey_container' => 'off',
                    'survey_edit_previous_submission' => 'off',
                    'survey_auto_numbering' => 'none',

                    // Result Settings Tab
                    'survey_redirect_after_submit' => 'off',
                    'survey_submit_redirect_url' => '',
                    'survey_submit_redirect_delay' => '',
                    'survey_submit_redirect_new_tab' => 'off',
                    'survey_enable_exit_button' => 'off',
                    'survey_exit_redirect_url' => '',
                    'survey_enable_restart_button' => 'on',
                    'survey_show_questions_as_html' => 'on',
                    'survey_final_result_text' => '',
                    'survey_loader' => 'ripple',

                    // Limitation Tab
                    'survey_limit_users' => 'off',
                    'survey_limit_users_by' => 'ip',
                    'survey_max_pass_count' => 1,
                    'survey_limitation_message' => '',
                    'survey_redirect_url' => '',
                    'survey_redirect_delay' => 0,
                    'survey_enable_logged_users' => 'off',
                    'survey_logged_in_message' => '',
                    'survey_show_login_form' => 'off',
                    'survey_enable_takers_count' => 'off',
                    'survey_takers_count' => 1,

                    // E-Mail Tab
                    'survey_enable_mail_user' => 'off',
                    'survey_mail_message' => '',
                    'survey_enable_mail_admin' => 'off',
                    'survey_send_mail_to_site_admin' => 'on',
                    'survey_additional_emails' => '',
                    'survey_mail_message_admin' => '',
                    'survey_email_configuration_from_email' => '',
                    'survey_email_configuration_from_name' => '',
                    'survey_email_configuration_from_subject' => '',
                    'survey_email_configuration_replyto_email' => '',
                    'survey_email_configuration_replyto_name' => '',            
                );

                $sections_count = count( $section_ids );
                $questions_count = count( $question_ids );
                $section_ids_str = empty( $section_ids ) ? '' : implode( ',', $section_ids );
                $question_ids_str = empty( $question_ids ) ? '' : implode( ',', $question_ids );

                $max_id = Survey_Maker_Admin::get_max_id('surveys');
                $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;

                $result = $wpdb->insert(
                    $surveys_table,
                    array(
                        'author_id'         => $author_id,
                        'title'             => $title,
                        'description'       => '',
                        'category_ids'      => $category_ids,
                        'question_ids'      => $question_ids_str,
                        'sections_count'    => $sections_count,
                        'questions_count'   => $questions_count,
                        'image'             => '',
                        'status'            => 'published',
                        'trash_status'      => '',
                        'date_created'      => $date_created,
                        'date_modified'     => $date_created,
                        'ordering'          => $ordering,
                        'section_ids'       => $section_ids_str,
                        'conditions'        => '',
                        'options'           => json_encode( $default_options, JSON_UNESCAPED_SLASHES ),
                    ),
                    array(
                        '%d', // author_id
                        '%s', // title
                        '%s', // description
                        '%s', // category_ids
                        '%s', // question_ids
                        '%d', // sections_count
                        '%d', // questions_count
                        '%s', // image
                        '%s', // status
                        '%s', // trash_status
                        '%s', // date_created
                        '%s', // date_modified
                        '%d', // ordering
                        '%s', // section_ids
                        '%s', // conditions
                        '%s', // options
                    )
                );

                $inserted_id = $wpdb->insert_id;

                $request_res = $wpdb->update(
                    $requests_table,
                    array(
                        'survey_id'     => $inserted_id,
                        'status'        => 'Published',
                        'approved'      => 'approved',
                    ),
                    array( 'id' => $approved_id ),
                    array( '%d', '%s', '%s'),
                    array( '%d' )
                );
                $result = array(
                    'status' => true,
                    'survey_id' => $inserted_id
                );

                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode($result);
                wp_die();
            }
        }
	}

    public function ays_survey_get_request_data_by_id ($id) {
		global $wpdb;

		$request_table = esc_sql($wpdb->prefix.'socialsurv_requests');

		$sql = "SELECT * FROM {$request_table} WHERE id=".$id;

		$result = $wpdb->get_row( $sql, 'ARRAY_A' );

		return $result;
	}

	public function ays_survey_get_survey_category_by_id ($id) {
		global $wpdb;

		$categories_table = esc_sql($wpdb->prefix.'socialsurv_survey_categories');

		$sql = "SELECT `title` FROM {$categories_table} WHERE id=".$id;

		$result = $wpdb->get_var( $sql );

		return $result;
	}
    /* FRONTEND REQUEST END */

    /**
     * Check if Block Editor is active.
     * Must only be used after plugins_loaded action is fired.
     *
     * @return bool
    */
    public static function is_active_gutenberg() {
        // Gutenberg plugin is installed and activated.
        $gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );
        // Block editor since 5.0.
        $block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

        if ( ! $gutenberg && ! $block_editor ) {
            return false;
        }

        if ( self::is_classic_editor_plugin_active() ) {
            $editor_option       = get_option( 'classic-editor-replace' );
            $block_editor_active = array( 'no-replace', 'block' );

            return in_array( $editor_option, $block_editor_active, true );
        }

        return true;
    }

    /**
     * Check if Classic Editor plugin is active.
     *
     * @return bool
    */
    public static function is_classic_editor_plugin_active() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
            return true;
        }

        return false;
    }

    // ==================================================================
	// =====================  Sections  loading  ========================
	// ========================    START   ==============================

    public function get_survey_question_html(){

		$_REQUEST['action'] = 'edit';

		$this->surveys_obj = new Surveys_List_Table($this->plugin_name);
		$this->settings_obj = new Survey_Maker_Settings_Actions( $this->plugin_name );

		$id = (isset($_REQUEST['id']) && $_REQUEST['id'] != "") ? absint( $_REQUEST['id'] ) : 0;
		$section_id = (isset($_REQUEST['section_id']) && $_REQUEST['section_id'] != "") ? absint( $_REQUEST['section_id'] ) : 0;
		$question_id = (isset($_REQUEST['question_id']) && $_REQUEST['question_id'] != "") ? absint( $_REQUEST['question_id'] ) : 0;
		$question_ids = (isset($_REQUEST['question_ids']) && $_REQUEST['question_ids'] != "") ?  $_REQUEST['question_ids'] : array();

        $section = Survey_Maker_Data::get_section_by_id( $section_id, 'array' );
        $question = Survey_Maker_Data::get_question_by_id( $question_id, 'array' );

		$result = array();
		if( ! empty( $question_ids ) ){
            ob_start();

			// Conditions
			$condition_questions        = array();
			$condition_sections         = array();
			$condition_html_questions   = array();
			$condition_html_answers     = array();

            
            $object = $this->surveys_obj->get_item_by_id( $id );
            // Survey Options
            $options = isset( $object['options'] ) && $object['options'] != '' ? $object['options'] : '';
            $options = json_decode( $options, true );
            
			foreach ( $question_ids as $question_id ) {
				$question = Survey_Maker_Data::get_question_by_id( $question_id, 'array' );

				require( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/questions/question-html-options.php" );


				$condition_questions[ $section_id ][$question['id']] = $question;
				$condition_questions[ $section_id ][$question['id']]['answers'] = $q_answers;

				if( in_array( $question_type_for_conditions, $other_question_types ) ){

					$star_length   = (isset($opts['star_scale_length']) && $opts['star_scale_length'] != '') ? $opts['star_scale_length'] : '5';
					$linear_length = (isset($opts['scale_length']) && $opts['scale_length'] != '') ? $opts['scale_length'] : '5';

					$condition_questions[ $section_id ][$question['id']]['star_length']   = $star_length;
					$condition_questions[ $section_id ][$question['id']]['linear_length'] = $linear_length;
				}

                $condition_html_questions[$question['id']] = $question;
                $condition_html_questions[$question['id']]['answers'] = $q_answers;

				Survey_Maker_Data::get_template_part( 'partials/surveys/actions/partials/questions/question-html', '', get_defined_vars() );
			}

            $question_html = ob_get_clean();

			$result = array(
				'status' => true,
				'questionHtml' => $question_html,
				'conditions' => $condition_questions,
                'questions' => $condition_html_questions
			);
		}else{
			$result = array(
				'status' => false,
				'message' => __( 'There is no data to show.', $this->plugin_name )
			);
		}

		return $result;
	}


	public function get_survey_submissions_question_summary_html(){

		$_REQUEST['action'] = 'edit';

		$this->surveys_obj = new Surveys_List_Table($this->plugin_name);
		$this->settings_obj = new Survey_Maker_Settings_Actions( $this->plugin_name );

        $filters = array();
        $filters['post_id']    = isset($_REQUEST['postFilters']['filterByPostParam']) && $_REQUEST['postFilters']['filterByPostParam'] != '' ? intval( sanitize_text_field( $_REQUEST['postFilters']['filterByPostParam'] ) ) : null;
        $filters['start_date'] = isset($_REQUEST['postFilters']['filterByStartdate']) && $_REQUEST['postFilters']['filterByStartdate'] != '' ? sanitize_text_field( $_REQUEST['postFilters']['filterByStartdate'] ) : null;
        $filters['end_date']   = isset($_REQUEST['postFilters']['filterByEndParam']) && $_REQUEST['postFilters']['filterByEndParam'] != '' ? sanitize_text_field( $_REQUEST['postFilters']['filterByEndParam'] ) : null;
        $is_filter             = (isset($filters['post_id']) || isset($filters['start_date']) || isset($filters['end_date'])) ? true : false;
        $filters['is_filter']  = $is_filter;
        
		$id = (isset($_REQUEST['id']) && $_REQUEST['id'] != "") ? absint( $_REQUEST['id'] ) : 0;
		$section_id = (isset($_REQUEST['section_id']) && $_REQUEST['section_id'] != "") ? absint( $_REQUEST['section_id'] ) : 0;
		$question_id = (isset($_REQUEST['question_id']) && $_REQUEST['question_id'] != "") ? absint( $_REQUEST['question_id'] ) : 0;
		$question_ids = (isset($_REQUEST['question_ids']) && $_REQUEST['question_ids'] != "") ? $_REQUEST['question_ids'] : array();

        foreach ($question_ids as $k => $question_id){
            if( ! $question_id ) {
	            unset( $question_ids[ $k ] );
            }
        }
        $question_ids = array_values( $question_ids );

		$result = array();
		if( ! empty( $question_ids ) ){
			$survey_question_results = Survey_Maker_Data::ays_survey_question_results_by_id( $id, $question_ids, $filters, true );
            $question_results = $survey_question_results['questions'];
            $total_count = $survey_question_results['total_count'];

            $questionsResults = array();
			foreach ( $question_results as $question ) {
				ob_start();
				Survey_Maker_Data::get_template_part( 'partials/submissions/partials/questions/question-html', '', get_defined_vars() );
				$question_html = ob_get_clean();

				$questionsResults[ $question['question_id'] ]['html'] = $question_html;
				$questionsResults[ $question['question_id'] ]['data'] = $question;
			}

			$result = array(
				'status' => true,
				'questionHtml' => $questionsResults,
			);
		}else{
			$result = array(
				'status' => false,
				'message' => __( 'There is no data to show.', $this->plugin_name )
			);
		}

		return $result;
	}

	public function mark_as_read_all_submissions(){
		global $wpdb;

		$survey_id = (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != "") ? absint( $_REQUEST['survey_id'] ) : 0;

        if( $survey_id ) {
	        $wpdb->update(
		        $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
		        array( 'read' => 1 ),
		        array(
			        'read'      => 0,
			        'survey_id' => $survey_id,
		        ),
		        array( '%d' ),
		        array( '%d', '%d' )
	        );
        }

        return array(
            'status' => true
        );
	}

	// ==================================================================
	// =====================  Sections  loading  ========================
	// ========================     End    ==============================

    

	public function get_unread_frontend_requests_count(){
		global $wpdb;
		$sql = "SELECT COUNT(unread) FROM {$wpdb->prefix}socialsurv_requests WHERE unread=1";
		$unread_requests_count = $wpdb->get_var($sql);
        return $unread_requests_count;
	}

    public function survey_maker_mark_requests_as_read () {
        
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'survey_maker_mark_requests_as_read') {
            global $wpdb;
            $requests_table = $wpdb->prefix . "socialsurv_requests";
            
            $id = isset($_REQUEST['survey_id']) ? absint($_REQUEST['survey_id']) : 0;
			$res = $wpdb->update($requests_table,
				array('unread' => 0),
				array('id' => $id),
				array('%d'),
				array('%d')
			);

            if ($res > 0) {
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode([
                    "status" => true,
                ]);
                wp_die();
            }
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode([
            "status" => false,
        ]);
        wp_die();
    }

    public function survey_maker_update_request_read_status ($id) {
        global $wpdb;
        $requests_table = $wpdb->prefix . "socialsurv_requests";
        
        $res = $wpdb->update($requests_table,
            array('unread' => 0),
            array('id' => $id),
            array('%d'),
            array('%d')
        );
    }

}