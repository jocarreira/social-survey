<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
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
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Survey_Maker_History_Page
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


    protected $settings;

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

        $this->settings = new Survey_Maker_Settings_Actions($this->plugin_name);

        add_shortcode('ays_survey_user_history', array($this, 'ays_generate_user_history_method'));

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles(){

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

        wp_enqueue_style( $this->plugin_name . '-dataTable-min', SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-dataTables.min.css', array(), $this->version, 'all');
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

        wp_enqueue_script( $this->plugin_name . '-datatable-min', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-datatable.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-user-history-public', SURVEY_MAKER_PUBLIC_URL . '/js/partials/survey-maker-public-user-history.js', array('jquery'), $this->version, true);

        wp_localize_script( $this->plugin_name . '-user-history-public', 'surveyLangDataTableObj', array(
            "sEmptyTable"           => __( "No data available in table", $this->plugin_name ),
            "sInfo"                 => __( "Showing _START_ to _END_ of _TOTAL_ entries", $this->plugin_name ),
            "sInfoEmpty"            => __( "Showing 0 to 0 of 0 entries", $this->plugin_name ),
            "sInfoFiltered"         => __( "(filtered from _MAX_ total entries)", $this->plugin_name ),
            // "sInfoPostFix":          => __( "", $this->plugin_name ),
            // "sInfoThousands":        => __( ",", $this->plugin_name ),
            "sLengthMenu"           => __( "Show _MENU_ entries", $this->plugin_name ),
            "sLoadingRecords"       => __( "Loading...", $this->plugin_name ),
            "sProcessing"           => __( "Processing...", $this->plugin_name ),
            "sSearch"               => __( "Search:", $this->plugin_name ),
            // "sUrl":                  => __( "", $this->plugin_name ),
            "sZeroRecords"          => __( "No matching records found", $this->plugin_name ),
            "sFirst"                => __( "First", $this->plugin_name ),
            "sLast"                 => __( "Last", $this->plugin_name ),
            "sNext"                 => __( "Next", $this->plugin_name ),
            "sPrevious"             => __( "Previous", $this->plugin_name ),
            "sSortAscending"        => __( ": activate to sort column ascending", $this->plugin_name ),
            "sSortDescending"       => __( ": activate to sort column descending", $this->plugin_name ),
            "all"                   => __( "All", $this->plugin_name ),
        ) );
    }

    /*
    ==========================================
        User History shortcode
    ==========================================
    */

    public function get_user_reports_info(){
        global $wpdb;

        $current_user = wp_get_current_user();
        $id = $current_user->ID;
        if($id == 0){
            return null;
        }

        $surveys_table     = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );
        $submissions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" );

        $sql = "SELECT survey.title, s.submission_date,s.id
                FROM $submissions_table AS s
                LEFT JOIN $surveys_table AS survey
                ON s.survey_id = survey.id
                WHERE s.user_id=$id
                ORDER BY s.id DESC";

        $results = $wpdb->get_results($sql, "ARRAY_A");

        return $results;

    }

    public function ays_user_history_html(){

        $survey_settings = $this->settings;
        $survey_settings_options = ($survey_settings->ays_get_setting('options') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('options');
        $survey_set_option = json_decode($survey_settings_options, true);
        
        $survey_set_option['ays_show_result_report'] = !isset($survey_set_option['ays_show_result_report']) ? 'on' : $survey_set_option['ays_show_result_report'];
        $show_result_report = isset($survey_set_option['ays_show_result_report']) && $survey_set_option['ays_show_result_report'] == 'on' ? true : false;

        $results = $this->get_user_reports_info();

        $default_user_history_columns = array(
            'survey_name'=> 'survey_name',
            'submission_date' => 'submission_date',
        );
        
        $user_history_columns = (isset( $survey_set_option['user_history_columns'] ) && !empty($survey_set_option['user_history_columns']) ) ? $survey_set_option['user_history_columns'] : $default_user_history_columns;
        $user_history_columns_order = (isset( $survey_set_option['user_history_columns_order'] ) && !empty($survey_set_option['user_history_columns_order']) ) ? $survey_set_option['user_history_columns_order'] : $default_user_history_columns;

        $ays_default_header_value = array(
            "survey_name"   => "<th style='width:20%;'>" . __( "Survey Name", $this->plugin_name ) . "</th>",
            "submission_date"    => "<th style='width:17%;'>" . __( "Submission Date", $this->plugin_name ) . "</th>",
        );

        if($results === null){
            $user_history_html = "<p style='text-align: center;font-style:italic;'>" . __( "You must log in to see your results.", $this->plugin_name ) . "</p>";
            return $user_history_html;
        }
        
        $user_history_html = "<div class='ays-survey-user-history-container'>
        <table class='ays_survey_user_history ays-survey-data-table-responsive'>
        <thead>
        <tr>";
        
        foreach ($user_history_columns_order as $key => $value) {
            if (isset($user_history_columns[$value])) {
                $user_history_html .= $ays_default_header_value[$value];
            }
        }
        
        $user_history_html .= "</tr></thead>";


        foreach($results as $key => $result){
            $id         = isset($result['id']) ? $result['id'] : null;
            $user_id    = isset($result['user_id']) ? intval($result['user_id']) : 0;
            $title      = isset($result['title']) ? $result['title'] : "";
            $submission_date = date_create($result['submission_date']);
            $start_date = date_format($submission_date, 'H:i:s M d, Y');

            $start_date_for_ordering = strtotime($result['submission_date']);

            $ays_default_html_order = array(
                "survey_name" => "<td>$title</td>",
                "submission_date" => "<td data-order='". $start_date_for_ordering ."'>$start_date</td>",
            );

            $user_history_html .= "<tr>";
            foreach ($user_history_columns_order as $key => $value) {
                if (isset($user_history_columns[$value])) {
                    $user_history_html .= $ays_default_html_order[$value];
                }
            }
            $user_history_html .= "</tr>";
        }

        $user_history_html .= "</table>
            </div>";
        
        return $user_history_html;
    }

    public function ays_generate_user_history_method(){
        $this->enqueue_styles();
        $this->enqueue_scripts();

        $user_history_html = $this->ays_user_history_html();

        return str_replace(array("\r\n", "\n", "\r"), '', Survey_Maker_Data::ays_survey_translate_content($user_history_html));
    }

}
