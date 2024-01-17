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

class Survey_Maker_Recent_Surveys
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

        add_shortcode('ays_display_surveys', array($this, 'ays_generate_display_surveys_method'));
    }

    public function recent_survey_ids($survey_attr){

        global $wpdb;

        $survey_table = $wpdb->prefix.'socialsurv_surveys';

        $ays_recent_survey_order_by = (isset($survey_attr['orderby']) && sanitize_text_field($survey_attr['orderby']) != '') ?sanitize_text_field($survey_attr['orderby']) : "random";
        $ays_recent_survey_count = (isset($survey_attr['count']) && intval($survey_attr['count']) != '') ? intval($survey_attr['count']) : "5";

        $last_surveys_sql = "SELECT id from {$survey_table} WHERE status != 'trashed'";

        switch ($ays_recent_survey_order_by) {
            case 'recent':
                $last_surveys_sql .= "ORDER BY id DESC LIMIT ".$ays_recent_survey_count;
                break;
            case 'random':
                $last_surveys_sql .= "ORDER BY RAND() LIMIT ".$ays_recent_survey_count;
                break;
            default:
                $last_surveys_sql .= "ORDER BY RAND() LIMIT ".$ays_recent_survey_count;
                break;
        }

        $last_survey_ids = $wpdb->get_results($last_surveys_sql,'ARRAY_A');

        return $last_survey_ids;
    }

    public function ays_generate_display_surveys_method($attr) {

        $recent_survey_ids = $this->recent_survey_ids($attr);

        $content = '<div class="ays_recent_surveys">';
        $surveyzes = array();
        foreach ($recent_survey_ids as $key => $last_survey_id) {
            $survey_id = (isset($last_survey_id['id']) && intval($last_survey_id['id']) != '') ? intval($last_survey_id['id']) : '';
            $shortcode = '[ays_survey id="'.$survey_id.'"]';
            $surveys[] = do_shortcode( $shortcode );
        }
        $content .= implode( '', $surveys );

        $content .= '</div>';

        // echo $content;
        return str_replace(array("\r\n", "\n", "\r"), "\n", $content);
    }
}
