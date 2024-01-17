<?php
    $action = isset( $_GET["action"] ) ? sanitize_text_field( $_GET["action"] ) : "";

    $id = (isset($_GET["id"])) ? absint( sanitize_text_field( $_GET["id"] ) ) : null;

    $html_name_prefix = "ays_";

    $user_id = get_current_user_id();

    $options = array(
        "width"         	 => "400",
        "height"        	 => "500",
        "popup_position"     => "center-center",
        "popup_margin"       => "0",
        "popup_trigger"      => "on_load",
        "popup_selector"     => "",
        "except_types"       => "",
        "except_posts"       => "",
        "hide_popup"         => "off",
        "full_screen_mode"   => "off",
        "popup_bg_color"     => "#ffffff",
    );

    $object = array(
        "survey_id" => "",
        "title" => "",
        "show_all" => "all",
        "status" => "published",
        'date_created' => current_time( 'mysql' ),
        'date_modified' => current_time( 'mysql' ),
        'author_id' => $user_id,
        "options" => json_encode( $options ),
    );

    $heading = "";
    switch ($action) {
        case "add":
            $heading = __( "Add new popup", $this->plugin_name );
            break;
        case "edit":
            $heading = __( "Edit popup", $this->plugin_name );
            $object = $this->popup_surveys_obj->get_item_by_id( $id );
            break;
    }


    if (isset($_POST["ays_submit"]) || isset($_POST["ays_submit_top"])) {
        $_POST["id"] = $id;
        $this->popup_surveys_obj->add_or_edit_item();
    }

    if(isset($_POST["ays_apply"]) || isset($_POST["ays_apply_top"])){
        $_POST["id"] = $id;
        $_POST["save_type"] = "apply";
        $this->popup_surveys_obj->add_or_edit_item();
    }

    if(isset($_POST["ays_save_new"]) || isset($_POST["ays_save_new_top"])){
        $_POST["id"] = $id;
        $_POST["save_type"] = "save_new";
        $this->popup_surveys_obj->add_or_edit_item();
    }


    // Options
    $options = isset( $object["options"] ) && $object["options"] != "" ? $object["options"] : "";
    $options = json_decode( $options, true );

    $gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode($this->settings_obj->ays_get_setting('options'), true);

    // Author ID
    $author_id = isset( $object['author_id'] ) && $object['author_id'] != '' ? intval( $object['author_id'] ) : $user_id;
    
    $owner = false;
    if( $user_id == $author_id ){
        $owner = true;
    }

    if( $this->current_user_can_edit ){
        $owner = true;
    }

    if( !$owner ){
        $url = esc_url_raw( remove_query_arg( array( 'action', 'survey', 'id' ) ) );
        wp_redirect( $url );
    }

    // Title
    $title = isset( $object["title"] ) && $object["title"] != "" ? stripslashes( htmlentities( $object["title"] ) ) : "";
    
    // Survey_id
    $survey_id = isset( $object["survey_id"] ) && $object["survey_id"] != "" ? stripslashes( htmlentities( $object["survey_id"] ) ) : "";
    
    // Status
    $status = isset( $object["status"] ) && $object["status"] != "" ? stripslashes( $object["status"] ) : "published";

    // Date created
    $date_created = isset( $object['date_created'] ) && Survey_Maker_Admin::validateDate( $object['date_created'] ) ? $object['date_created'] : current_time( 'mysql' );
    
    // Date modified
    $date_modified = current_time( 'mysql' );
    
    // Show All
    $show_all = isset( $object["show_all"] ) && $object["show_all"] != "" ? stripslashes( $object["show_all"] ) : "all";

    // Width
    $popup_survey_width = (isset($options["width"]) && $options["width"] != "") ? absint ( intval( $options["width"] ) ) : 400;
   
    // Height
    $popup_survey_height = (isset($options["height"]) && $options["height"] != "") ? absint ( intval( $options["height"] ) ) : 500;

    // Popup Position
    $popup_position = (isset($options["popup_position"]) && $options["popup_position"] != "center-center") ? $options["popup_position"] : "center-center";

    // Popup Margin
    $popup_margin = (isset($options["popup_margin"]) && $options["popup_margin"] != "") ? $options["popup_margin"] : "0";

    //Popup Trigger
    $trigger_type_arr = array(
        'on_load'  => __('On load', $this->plugin_name),
        'on_click' => __('On Click', $this->plugin_name),
        'on_exit'  => __('On Exit', $this->plugin_name)
    ); 

    $popup_trigger_type = (isset($options["popup_trigger"]) && $options["popup_trigger"] != "") ? $options["popup_trigger"] : "on_load";

    $popup_selector = (isset($options["popup_selector"]) && $options["popup_selector"] != "") ? stripslashes( esc_attr($options["popup_selector"])) : "";    

    // Popup background color
    $survey_popup_bg_color = (isset($options["popup_bg_color"]) && $options["popup_bg_color"] != "") ? $options["popup_bg_color"] : "#ffffff";

    $posts = array();
    $except_posts       = (isset($options["except_posts"]) && $options["except_posts"] != "") ? ($options["except_posts"]) : array();
    $except_post_types  = (isset($options["except_post_types"]) && $options["except_post_types"] != "") ? ($options["except_post_types"]) : array();

    if ( !empty( $except_posts ) ) {
        $post_ids = implode(",", $except_posts);

        $posts = get_posts(array(
            'post_type'     => $except_post_types,
            'post_status'   => 'publish',
            'numberposts'   => -1, 
            'include'       => $post_ids
        ));
    }

    $args = array(
        "public" => true
    );

    $all_post_types = get_post_types( $args, "objects" );

    if( array_key_exists( 'attachment', $all_post_types ) ){
        unset( $all_post_types['attachment'] );
    }

    //Show on home page
    $show_on_home_page = (isset($options["show_on_home_page"]) && $options["show_on_home_page"] == "on") ? "on" : "off";

    // Hide Popup
    $hide_popup = (isset($options["hide_popup"]) && $options["hide_popup"] == "on") ? $options["hide_popup"] : "off";

    // Survey categories IDs
    $surveys = $this->popup_surveys_obj->get_surveys();

    $loader_iamge = '<span class="display_none ays_survey_loader_box"><img src="". SURVEY_MAKER_ADMIN_URL ."/images/loaders/loading.gif"></span>';

    // Popup full screen mode
    $survey_popup_full_screen = (isset($options["full_screen_mode"]) && $options["full_screen_mode"] == "on") ? "checked" : "";
