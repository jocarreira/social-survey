<?php

class Ays_Survey_Maker_Extra_Shortcodes_Public {
    protected $plugin_name;
    private $version;

    private $html_class_prefix = 'ays-survey-extra-shortcodes-';
    private $html_name_prefix = 'ays-survey-';
    private $name_prefix = 'ays_survey_';
    private $unique_id;
    private $unique_id_in_class;

    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $shortcodes = array( 
            "user_first_name",
            "user_last_name" ,
            "user_nick_name" ,
            "user_display_name" ,
            "user_email" ,
            "user_wordpress_roles" ,
            "passed_users_count" ,
            "creation_date" ,
            "sections_count",
            "questions_count",
            "categories_count" ,
        );


        foreach($shortcodes as $shortcode){
            add_shortcode('ays_survey_'.$shortcode, array($this, 'ays_generate_'.$shortcode.'_method'));
        }
    }

    
        
    /*
    ==========================================
        Show users firstname | Start
    ==========================================
    */
    public function ays_generate_user_first_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_first_name_html = "";
        if(is_user_logged_in()){
            $user_first_name_html = $this->ays_generate_users_html('first');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_first_name_html);
    }
    /*
    ==========================================
        Show users firstname | End
    ==========================================
    */

    /*
    ==========================================
        Show users lastname | Start
    ==========================================
    */
    public function ays_generate_user_last_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_last_name_html = "";
        if(is_user_logged_in()){
            $user_last_name_html = $this->ays_generate_users_html('last');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_last_name_html);
    }
        /*
    ==========================================
        Show users lastname | end
    ==========================================
    */

    /*
    ==========================================
        Show users nick name | Start
    ==========================================
    */
    public function ays_generate_user_nick_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_last_name_html = "";
        if(is_user_logged_in()){
            $user_last_name_html = $this->ays_generate_users_html('nick');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_last_name_html);
    }
        /*
    ==========================================
        Show users nick name | End
    ==========================================
    */
    /*
    ==========================================
        Show users displayname | Start
    ==========================================
    */
    public function ays_generate_user_display_name_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_last_name_html = "";
        if(is_user_logged_in()){
            $user_last_name_html = $this->ays_generate_users_html('display');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_last_name_html);
    }
        /*
    ==========================================
        Show users displayname | End
    ==========================================
    */
    /*
    ==========================================
        Show users email | Start
    ==========================================
    */
    public function ays_generate_user_email_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_last_email_html = "";
        if(is_user_logged_in()){
            $user_last_email_html = $this->ays_generate_users_html('email');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_last_email_html);
    }
    /*
    ==========================================
        Show users email | End
    ==========================================
    */
    /*
    ==========================================
        Show users wordpress roles | Start
    ==========================================
    */
    public function ays_generate_user_wordpress_roles_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $user_last_email_html = "";
        if(is_user_logged_in()){
            $user_last_email_html = $this->ays_generate_users_html('wordpress_roles');
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $user_last_email_html);
    }
    /*
    ==========================================
        Show users wordpress roles | End
    ==========================================
    */



    /*
    ==========================================
        Passed users count | Start
    ==========================================
    */
    public function ays_generate_passed_users_count_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $passed_users_count_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $passed_users_count_html = $this->ays_survey_passed_users_count_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
    }
    
    public function ays_survey_passed_users_count_html( $id ){

        $results = Survey_Maker_Data::get_survey_results_count_by_id($id);

        $content_html = array();

        if($results === null){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $passed_users_count = (isset( $results['res_count'] ) && $results['res_count'] != '') ? sanitize_text_field( $results['res_count'] ) : 0;

        $content_html[] = "<span class='". $this->html_name_prefix ."passed-users-count-box' id='". $this->html_name_prefix ."passed-users-count-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $passed_users_count;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }
    /*
    ==========================================
        Passed users count | End
    ==========================================
    */
    /*
    ==========================================
        Survey show creation date | Start
    ==========================================
    */
    public function ays_generate_creation_date_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $surve_creation_date_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $surve_creation_date_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $surve_creation_date_html = $this->ays_survey_creation_date_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $surve_creation_date_html);
    }
    
    public function ays_survey_creation_date_html( $id ){

        $results = $this->get_curent_survey_creation_date($id);

        $content_html = array();

        if($results === null){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."creation-date-box' id='". $this->html_name_prefix ."creation-date-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }
    /*
    ==========================================
        Survey show creation date | End
    ==========================================
    */
       /*
    ==========================================
        Survey show sections count | Start
    ==========================================
    */
    public function ays_generate_sections_count_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $survey_sections_count_html  = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_sections_count_html );
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $survey_sections_count_html  = $this->ays_survey_sections_count_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_sections_count_html );
    }
    
    public function ays_survey_sections_count_html( $id ){

        $results = Survey_Maker_Data::get_survey_sections_count($id);

        $content_html = array();

        if($results === null || $results === 0){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."sections-count-box' id='". $this->html_name_prefix ."sections-count-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }
    /*
    ==========================================
        Survey show sections count | End
    ==========================================
    */
    /*
    ==========================================
        Survey show questions count | Start
    ==========================================
    */
    public function ays_generate_questions_count_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $survey_questions_count_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_questions_count_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $survey_questions_count_html = $this->ays_survey_questions_sections_count_html( $id , 'questions' );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_questions_count_html);
    }
    /*
    ==========================================
        Survey show questions count | End
    ==========================================
    */

    public function ays_generate_users_html($arg){

        $results = $this->get_user_profile_data();

        $content_html = array();
        
        if( is_null( $results ) || $results == 0 ){
            $content_html = "";
            return $content_html;
        }

        $user_info = (isset( $results['user_'.$arg.'_name'] ) && $results['user_'.$arg.'_name']  != "") ? sanitize_text_field( $results['user_'.$arg.'_name'] ) : '';

        $content_html[] = "<span class='ays-survey-user-".$arg."-name' id='ays-survey-user-".$arg."-name-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $user_info;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

	public function get_user_profile_data(){

        $user_first_name = '';
        $user_last_name  = '';
        $user_nickname   = '';

        $user_id = get_current_user_id();
        if($user_id != 0){
            $usermeta = get_user_meta( $user_id );
            if($usermeta !== null){
                $user_first_name = (isset($usermeta['first_name'][0]) && sanitize_text_field( $usermeta['first_name'][0] != '') ) ? sanitize_text_field( $usermeta['first_name'][0] ) : '';
                $user_last_name  = (isset($usermeta['last_name'][0]) && sanitize_text_field( $usermeta['last_name'][0] != '') ) ? sanitize_text_field( $usermeta['last_name'][0] ) : '';
                $user_nickname   = (isset($usermeta['nickname'][0]) && sanitize_text_field( $usermeta['nickname'][0] != '') ) ? sanitize_text_field( $usermeta['nickname'][0] ) : '';
            }
        }

        $current_user_data = get_userdata( $user_id );
        if ( ! is_null( $current_user_data ) && $current_user_data ) {
            $user_display_name = ( isset( $current_user_data->data->display_name ) && $current_user_data->data->display_name != '' ) ? sanitize_text_field( $current_user_data->data->display_name ) : "";
            $user_email = ( isset( $current_user_data->data->user_email ) && $current_user_data->data->user_email != '' ) ? sanitize_text_field( $current_user_data->data->user_email ) : "";
            $user_wordpress_roles = ( isset( $current_user_data->roles ) && ! empty( $current_user_data->roles ) ) ? $current_user_data->roles : "";
            if ( !empty( $user_wordpress_roles ) && $user_wordpress_roles != "" ) {
                if ( is_array( $user_wordpress_roles ) ) {
                    $user_wordpress_roles = implode(",", $user_wordpress_roles);
                }
            }

        }


        $message_data = array(
            'user_first_name'   => $user_first_name,
            'user_last_name'    => $user_last_name,
            'user_nick_name'    => $user_nickname,
            'user_display_name' => $user_display_name,
            'user_email_name'   => $user_email,
            'user_wordpress_roles_name'   => $user_wordpress_roles,
        );
		
        return $message_data;
    }

    public function get_curent_survey_creation_date( $id ){
        global $wpdb;

        $surveys_table = esc_sql( $wpdb->prefix . "socialsurv_surveys" );

        if (is_null($id) || $id == 0 ) {
            return null;
        }

        $id = absint( $id );

        $sql = "SELECT DATE(`date_created`) FROM `{$surveys_table}` WHERE `id` = {$id}";

        $results = $wpdb->get_var($sql);
        if ( is_null( $results ) || $results == "" ) {
            $results = null;
        }

        return sanitize_text_field( $results );
    }

    public function ays_survey_questions_sections_count_html( $id , $detect ){
        $detector = "get_survey_".$detect."_count";
        $results = Survey_Maker_Data::$detector($id);

        $content_html = array();

        if($results === null || $results === 0){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix.$detect."-count-box' id='". $this->html_name_prefix.$detect."-count-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Show users lastname | Start
    ==========================================
    */
    public function ays_generate_categories_count_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $categories_count_html = "";
        $categories_count_html = $this->ays_survey_categories_count_html();

        return str_replace(array("\r\n", "\n", "\r"), "\n", $categories_count_html);
    }

    public function ays_survey_categories_count_html(){

        $results = $this->ays_survey_get_categories_count();
        $content_html = array();

        if( is_null($results) || $results == 0){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $results_count = (isset( $results ) && $results != '') ? sanitize_text_field( $results ) : 0;

        if ( $results_count == 0 ) {
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."categories-count' id='". $this->html_name_prefix ."categories-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results_count;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    public static function ays_survey_get_categories_count(){
        global $wpdb;
        $surveys_category_table = esc_sql( $wpdb->prefix . "socialsurv_survey_categories" );

        $sql = "SELECT count(*) as res_count FROM {$surveys_category_table}";

        $res = $wpdb->get_var($sql);

        return $res;
    }
        /*
    ==========================================
        Show users lastname | End
    ==========================================
    */




}
