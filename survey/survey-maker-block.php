<?php
    /**
     * Enqueue front end and editor JavaScript
     */

    function ays_survey_gutenberg_scripts() {
        global $current_screen;
        global $wp_version;
        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.3';
        $versionCompare = aysSurveyMakerVersionCompare($version1, $operator, $version2);
        if( ! $current_screen ){
            return null;
        }

        if( ! $current_screen->is_block_editor ){
            return null;
        }

        wp_enqueue_script( SURVEY_MAKER_NAME . "-plugin", SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-public-plugin.js', array('jquery'), SURVEY_MAKER_VERSION, true);
        wp_enqueue_script( SURVEY_MAKER_NAME, SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-public.js', array('jquery'), SURVEY_MAKER_VERSION, true);

        // Enqueue the bundled block JS file
        if($versionCompare){
            wp_enqueue_script(
                'survey-maker-block-js',
                SURVEY_MAKER_BASE_URL ."/survey/survey-maker-block-new.js",
                array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
                SURVEY_MAKER_VERSION, true //( AYS_QUIZ_BASE_URL . 'quiz-maker-block.js' )
            );
        }
        else{
            wp_enqueue_script(
                'survey-maker-block-js',
                SURVEY_MAKER_BASE_URL ."/survey/survey-maker-block.js",
                array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
                SURVEY_MAKER_VERSION, true //( AYS_QUIZ_BASE_URL . 'quiz-maker-block.js' )
            );
        }
        
        wp_enqueue_style( SURVEY_MAKER_NAME, SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-public.css', array(), SURVEY_MAKER_VERSION, 'all');
        
        wp_enqueue_style( SURVEY_MAKER_NAME . "-public-chat-survey", SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-public-chat-survey.css', array(), SURVEY_MAKER_VERSION, 'all' );
        wp_enqueue_style( SURVEY_MAKER_NAME . "-dropdown", SURVEY_MAKER_PUBLIC_URL . '/css/dropdown.min.css', array(), SURVEY_MAKER_VERSION, 'all' );
        // Enqueue the bundled block CSS file
        if(!$versionCompare){            
            wp_enqueue_style(
                'survey-maker-block-css',
                SURVEY_MAKER_BASE_URL ."/survey/survey-maker-block-new.css",
                array(),
                SURVEY_MAKER_VERSION, 'all'
            );
        }
        else{            
            wp_enqueue_style(
                'survey-maker-block-css',
                SURVEY_MAKER_BASE_URL ."/survey/survey-maker-block.css",
                array(),
                SURVEY_MAKER_VERSION, 'all'
            );
        }
    }

    function ays_survey_gutenberg_block_register() {
        
        global $wpdb;
        $block_name = 'survey';
        $block_namespace = 'survey-maker/' . $block_name;
        
        $current_user = get_current_user_id();
        $sql = "SELECT * FROM ". $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE status != 'trashed' ";
        if( ! current_user_can( 'manage_options' ) ){
            $sql .= " AND author_id = ". absint( $current_user ) ." ";
        }
        $sql .= "ORDER BY id DESC";
        $results = $wpdb->get_results($sql, "ARRAY_A");
        
        register_block_type(
            $block_namespace, 
            array(
                'render_callback'   => 'survey_maker_render_callback',                
                'editor_script'     => 'survey-maker-block-js',  // The block script slug
                'style'             => 'survey-maker-block-css',
                'attributes'	    => array(
                    'idner' => $results,
                    'metaFieldValue' => array(
                        'type'  => 'integer', 
                    ),
                    'shortcode' => array(
                        'type'  => 'string',				
                    ),
                    'className' => array(
                        'type'  => 'string',
                    ),
                    'openPopupId' => array(
                        'type'  => 'string',
                    ),
                ),
            )
        );
    }    
    
    function survey_maker_render_callback( $attributes ) {
        global $current_screen;
        $is_front = true;

        if( ! empty( $current_screen ) ){
            if( isset( $current_screen->is_block_editor ) && $current_screen->is_block_editor === true ){
                $is_front = false;
            }
        }elseif ( wp_is_json_request() ) {
            $is_front = false;
        }

        $ays_html = "<div></div>";

        if( ! empty( $attributes["shortcode"] ) ) {
            $ays_html = do_shortcode( $attributes["shortcode"] );
        }else{
            if( $is_front === true ){
                $ays_html = '';
            }
        }

        return $ays_html;
    }

if(function_exists("register_block_type")){
        // Hook scripts function into block editor hook
    add_action( 'enqueue_block_editor_assets', 'ays_survey_gutenberg_scripts' );
    add_action( 'init', 'ays_survey_gutenberg_block_register' );
}

    function aysSurveyMakerVersionCompare($version1, $operator, $version2) {
    
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
    
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
    
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
    
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }
