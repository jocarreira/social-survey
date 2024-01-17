<?php
    class Survey_Maker_Custom_Post_Type {

        private $plugin_name;
        private $version;
        private $survey_flush_version;
        public $name_prefix;

        public function __construct($plugin_name, $version){
            $this->plugin_name = $plugin_name;
            $this->name_prefix = 'ays-';
            $this->version = $version;
            $this->survey_flush_version = '1.0.0';
            add_action( 'init', array( $this, 'survey_register_custom_post_type' ) );
        }

        public function survey_register_custom_post_type(){
            $args = array(
                'public'  => true,
                'rewrite' => true,
                'show_in_menu' => false,
                'exclude_from_search' => false, 
                'show_ui' => false,
                'show_in_nav_menus' => false,
                'show_in_rest' => false
            );

            register_post_type( $this->name_prefix . $this->plugin_name, $args );
            $this->custom_survey_rewrite_rule();
            $this->survey_flush_permalinks();
        }

        public static function survey_add_custom_post($args, $update = true){
            
            $survey_id    = isset($args['survey_id']) && $args['survey_id'] != '' && $args['survey_id'] != 0 ? esc_attr($args['survey_id']) : '';
            $survey_title = isset($args['survey_title']) && $args['survey_title'] != '' ? esc_attr($args['survey_title']) : '';
            $author_id    = isset($args['author_id']) && $args['author_id'] != '' ? esc_attr($args['author_id']) : get_current_user_id();

            $post_content = '[ays_survey id="'.$survey_id.'"]';

            $new_post = array(
                'post_title' => $survey_title,
                'post_author' => $author_id,
                'post_type'   => 'ays-survey-maker', // Custom post type name is -> ays-survey-maker
                'post_content' => $post_content,
                'post_status' => 'draft',
                'post_date' => current_time( 'mysql' ),
            );
            $post_id = wp_insert_post($new_post);
            if($update){
                if(isset($post_id) && $post_id > 0){
                    self::update_surveys_table_custom_post_id($post_id, $survey_id);
                }
            }
            return $post_id;
        }

        public static function update_surveys_table_custom_post_id($custom_post_id, $survey_id){
            global $wpdb;
            $table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );
            $result = $wpdb->update(
                $table,
                array('custom_post_id' => $custom_post_id),
                array( 'id' => $survey_id ),
                array('%d'),
                array('%d')
            );
        }

        public function survey_flush_permalinks(){
            if ( get_site_option( 'survey_flush_version' ) != $this->survey_flush_version ) {
                flush_rewrite_rules();
            }
            update_option( 'survey_flush_version', $this->survey_flush_version );            
        }
        
        public function custom_survey_rewrite_rule() {
            add_rewrite_rule(
                'ays-survey-maker/([^/]+)/?',
                'index.php?post_type=ays-survey-maker&name=$matches[1]',
                'top'
            );
        }
    }
