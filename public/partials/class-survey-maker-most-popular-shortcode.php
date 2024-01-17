<?php

class Ays_Survey_Maker_Most_Popular_Shortcodes_Public{

    protected $plugin_name;    
    private $version;
    private $html_class_prefix = 'ays-survey-most-popular-shortcodes-';
    private $html_name_prefix = 'ays-survey-';
    private $name_prefix = 'ays_survey_';
    private $unique_id;
    private $unique_id_in_class;

    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('ays_survey_most_popular', array($this, 'ays_generate_most_popular_method'));
    }

    /*
    ==========================================
        Most Popular Survey | Start
    ==========================================
    */

    public function ays_generate_most_popular_method( $attr ){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = "-" . $unique_id;

        $survey_most_popular_html = $this->ays_survey_most_popular_html( $attr );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_most_popular_html);
    }

    public function ays_survey_most_popular_html( $attr ){

        $survey_count = (isset( $attr['count'] ) && $attr['count'] != "") ? absint( sanitize_text_field( $attr['count'] ) ) : ""; 

        $survey_ids = $this->ays_survey_get_most_popular_survey_id( $survey_count );

        $content_html = array();

        if( is_null( $survey_ids ) || empty( $survey_ids ) ){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        if ( ! empty( $survey_ids ) ) {
            $content_html[] = "<div class='ays-survey-most-popular-container'>";

            foreach ($survey_ids as $key => $survey_id) {

                $shortcode = "[ays_survey id='".$survey_id."']";

                $content_html[] = do_shortcode( $shortcode );
            }

            $content_html[] = "</div>";
        }

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    public function ays_survey_get_most_popular_survey_id( $survey_count ) {
        global $wpdb;

        $submissions_table = esc_sql( $wpdb->prefix . "socialsurv_submissions" );
        $surveys_table     = esc_sql( $wpdb->prefix . "socialsurv_surveys" );
        
        
        $most_popular_survey_count = (isset( $survey_count ) && $survey_count != "") ? absint( sanitize_text_field( $survey_count ) ) : "";
        $limit = "";
        
        if($most_popular_survey_count != "" && $most_popular_survey_count != 0){
            $limit = "LIMIT ".$most_popular_survey_count;            
        }
        
        $sql = "SELECT COUNT(*) AS `res_count`, `survey_id`
                FROM `{$submissions_table}`
                GROUP BY `survey_id`
                ORDER BY `res_count`
                DESC ".$limit;

        $results = $wpdb->get_results($sql, 'ARRAY_A');
        $survey_ids = array();
        if ( ! is_null( $results ) && ! empty( $results ) ) {
            foreach ($results as $key => $value) {
                $id = ( isset( $value['survey_id'] ) && $value['survey_id'] != "" && absint( $value['survey_id'] ) > 0 ) ? absint( sanitize_text_field( $value['survey_id'] ) ) : null;
                $survey_ids[] = $id;
            }
        }
        return $survey_ids;
    }

    /*
    ==========================================
        Most Popular Survey | End
    ==========================================
    */

}
