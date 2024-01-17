<?php
class Survey_Maker_Settings_Actions {
    private $plugin_name;

    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
    }

    public function store_data(){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        if( isset($_REQUEST["settings_action"]) && wp_verify_nonce( $_REQUEST["settings_action"], 'settings_action' ) ){
            $success = 0;
            $name_prefix = 'ays_';

            $roles = (isset($_REQUEST['ays_user_roles']) && !empty($_REQUEST['ays_user_roles'])) ? array_map( 'sanitize_text_field', $_REQUEST['ays_user_roles'] ) : array('administrator');
            
            // User roles to change survey
            $user_roles_to_change_survey = (isset($_REQUEST['ays_user_roles_to_change_survey']) && !empty( $_REQUEST['ays_user_roles_to_change_survey'] ) ) ? array_map( 'sanitize_text_field', $_REQUEST['ays_user_roles_to_change_survey'] ) : array('administrator');

            $next_button            = (isset($_REQUEST[$name_prefix .'survey_next_button']) && $_REQUEST[$name_prefix .'survey_next_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_next_button'] )) : 'Next';
            $previous_button        = (isset($_REQUEST[$name_prefix .'survey_previous_button']) && $_REQUEST[$name_prefix .'survey_previous_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_previous_button'] )) : 'Prev';
            $clear_button           = (isset($_REQUEST[$name_prefix .'survey_clear_button']) && $_REQUEST[$name_prefix .'survey_clear_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_clear_button'] )) : 'Clear selection';
            $finish_button          = (isset($_REQUEST[$name_prefix .'survey_finish_button']) && $_REQUEST[$name_prefix .'survey_finish_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_finish_button'] )) : 'Finish';
            $restart_button         = (isset($_REQUEST[$name_prefix .'survey_restart_button']) && $_REQUEST[$name_prefix .'survey_restart_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_restart_button'] )) : 'Restart survey';
            $exit_button            = (isset($_REQUEST[$name_prefix .'survey_exit_button']) && $_REQUEST[$name_prefix .'survey_exit_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_exit_button'] )) : 'Exit';
            $login_button           = (isset($_REQUEST[$name_prefix .'survey_login_button']) && $_REQUEST[$name_prefix .'survey_login_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_login_button'] )) : 'Log in';
            $check_button           = (isset($_REQUEST[$name_prefix .'survey_check_button']) && $_REQUEST[$name_prefix .'survey_check_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_check_button'] )) : 'Check';
            $start_button           = (isset($_REQUEST[$name_prefix .'survey_start_button']) && $_REQUEST[$name_prefix .'survey_start_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_start_button'] )) : 'Start';

            $buttons_texts = array(
                'next_button'           => $next_button,
                'previous_button'       => $previous_button,
                'clear_button'          => $clear_button,
                'finish_button'         => $finish_button,
                'restart_button'        => $restart_button,
                'exit_button'           => $exit_button,
                'login_button'          => $login_button,
                'check_button'          => $check_button,
                'start_button'          => $start_button,
            );

            $survey_default_type = (isset($_REQUEST[$name_prefix .'survey_default_type']) && $_REQUEST[$name_prefix .'survey_default_type'] != '') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix .'survey_default_type'] ) ) : '';
            $survey_answer_default_count = (isset($_REQUEST[$name_prefix . 'survey_answer_default_count']) && $_REQUEST[$name_prefix . 'survey_answer_default_count'] != '') ? absint( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_answer_default_count'] ) ) : 1;
            
            // Do not store IP addresses
            $survey_disable_user_ip = (isset($_REQUEST[$name_prefix . 'survey_disable_user_ip']) && $_REQUEST[$name_prefix . 'survey_disable_user_ip'] == 'on') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_disable_user_ip'] ) ) : '';

            // Block Users by IP addresses
            $survey_block_by_user_ips = (isset($_REQUEST[$name_prefix . 'survey_block_by_user_ips']) && $_REQUEST[$name_prefix . 'survey_block_by_user_ips'] == 'on') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_block_by_user_ips'] ) ) : '';
            // Users IP addresses that will be blocked
            $survey_users_ips_that_will_blocked = isset($_REQUEST[$name_prefix .'survey_users_ips_that_will_blocked']) && $_REQUEST[$name_prefix .'survey_users_ips_that_will_blocked'] != '' ? sanitize_text_field( $_REQUEST[$name_prefix .'survey_users_ips_that_will_blocked'] ) : '';
 
            // Do not store User Names
            $survey_disable_user_name = (isset($_REQUEST[$name_prefix . 'survey_disable_user_name']) && $_REQUEST[$name_prefix . 'survey_disable_user_name'] == 'on') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_disable_user_name'] ) ) : 'off';

            // Do not store User Emails
            $survey_disable_user_email = (isset($_REQUEST[$name_prefix . 'survey_disable_user_email']) && $_REQUEST[$name_prefix . 'survey_disable_user_email'] == 'on') ? stripslashes( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_disable_user_email'] ) ) : 'off';
            
            
            $survey_submissions_title_length = (isset($_REQUEST[$name_prefix . 'survey_submissions_title_length']) && $_REQUEST[$name_prefix . 'survey_submissions_title_length'] != '') ? absint( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_submissions_title_length'] ) ) : 5;
            $survey_title_length = (isset($_REQUEST[$name_prefix . 'survey_title_length']) && $_REQUEST[$name_prefix . 'survey_title_length'] != '') ? absint( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_title_length'] ) ) : 5;

            $survey_categories_title_length = (isset($_REQUEST[$name_prefix . 'survey_categories_title_length']) && $_REQUEST[$name_prefix . 'survey_categories_title_length'] != '') ? absint( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_categories_title_length'] ) ) : 5;
            
            //Animation top
            $survey_animation_top = (isset($_REQUEST[$name_prefix . 'survey_animation_top']) && $_REQUEST[$name_prefix . 'survey_animation_top'] != '') ? absint( sanitize_text_field( $_REQUEST[$name_prefix . 'survey_animation_top'] ) ) : 200;

            $survey_enable_animation_top = (isset( $_REQUEST['ays_survey_enable_animation_top'] ) && $_REQUEST['ays_survey_enable_animation_top'] ) == 'on' ? 'on' : 'off';

            // Disable Survey Maker menu item notification
            $survey_disable_survey_menu_notification = ( isset( $_REQUEST[ $name_prefix . 'survey_disable_survey_menu_notification'] ) && $_REQUEST[$name_prefix . 'survey_disable_survey_menu_notification'] ) == 'on' ? 'on' : 'off';

            // Disable Submissions menu item notification
            $survey_disable_submission_menu_notification = ( isset( $_REQUEST[ $name_prefix . 'survey_disable_submission_menu_notification'] ) && $_REQUEST[$name_prefix . 'survey_disable_submission_menu_notification'] ) == 'on' ? 'on' : 'off';

            // User history columns
            // $show_result_report = (isset( $data[$name_prefix . 'show_result_report'] ) && $data[$name_prefix . 'show_result_report'] == 'on') ? 'on' : 'off';
            $user_history_columns = (isset( $_REQUEST[$name_prefix . 'survey_user_history_columns'] ) && !empty($_REQUEST[$name_prefix . 'survey_user_history_columns'])) ? $_REQUEST[$name_prefix . 'survey_user_history_columns'] : array();
            $user_history_columns_order = (isset( $_REQUEST[$name_prefix . 'survey_user_history_columns_order'] ) && !empty($_REQUEST[$name_prefix . 'survey_user_history_columns_order'])) ? $_REQUEST[$name_prefix . 'survey_user_history_columns_order'] : array();

            // All Submission
            $all_submissions_columns = (isset($_REQUEST[$name_prefix . 'survey_all_submission_columns']) && !empty($_REQUEST[$name_prefix . 'survey_all_submission_columns'])) ? $_REQUEST[$name_prefix . 'survey_all_submission_columns'] : array();
            $all_submissions_columns_order = (isset($_REQUEST[$name_prefix . 'survey_all_submission_columns_order']) && !empty($_REQUEST[$name_prefix . 'survey_all_submission_columns_order'])) ? $_REQUEST[$name_prefix . 'survey_all_submission_columns_order'] : array();
            
            // Show publicly
            $all_submissions_show_publicly = (isset( $_REQUEST[$name_prefix . 'survey_all_submission_show_publicly'] ) && $_REQUEST[$name_prefix . 'survey_all_submission_show_publicly'] == 'on') ? 'on' : 'off';

            // Enable promote plugin
            $survey_enable_promote_plugin = (isset( $_REQUEST[$name_prefix . 'survey_enable_promote_plugin'] ) && $_REQUEST[$name_prefix . 'survey_enable_promote_plugin'] == 'on') ? 'on' : 'off';

            // Submissions settings
            $survey_matrix_show_result_type = isset($_REQUEST[$name_prefix .'survey_matrix_scale_show_result_type'])  && $_REQUEST[$name_prefix .'survey_matrix_scale_show_result_type'] != '' ? sanitize_text_field( $_REQUEST[$name_prefix .'survey_matrix_scale_show_result_type'] ) : 'by_votes';

            // Submissions settings
            $survey_show_submissions_order_type = isset($_REQUEST[$name_prefix .'survey_show_submissions_order_type'])  && $_REQUEST[$name_prefix .'survey_show_submissions_order_type'] != '' ? sanitize_text_field( $_REQUEST[$name_prefix .'survey_show_submissions_order_type'] ) : 'by_defalut';

            // Textarea height (public)
            $survey_textarea_height = (isset($_REQUEST[$name_prefix . 'survey_textarea_height']) && $_REQUEST[$name_prefix . 'survey_textarea_height'] != '' && $_REQUEST[$name_prefix . 'survey_textarea_height'] != 0 ) ? absint( sanitize_text_field($_REQUEST[$name_prefix . 'survey_textarea_height']) ) : 100;

            // WP Editor height
            $survey_wp_editor_height = (isset($_REQUEST[$name_prefix . 'survey_wp_editor_height']) && $_REQUEST[$name_prefix . 'survey_wp_editor_height'] != '' && $_REQUEST[$name_prefix . 'survey_wp_editor_height'] != 0) ? absint( sanitize_text_field($_REQUEST[$name_prefix . 'survey_wp_editor_height']) ) : 100 ;

            // Make question required
            $survey_make_questions_required = (isset($_REQUEST[$name_prefix . 'survey_make_questions_required']) && $_REQUEST[$name_prefix . 'survey_make_questions_required'] == 'on') ? sanitize_text_field( $_REQUEST[$name_prefix . 'survey_make_questions_required'] )  : 'off';

            // Lazy loading for images
            $survey_lazy_loading_for_images = (isset($_REQUEST[$name_prefix . 'survey_lazy_loading_for_images']) && $_REQUEST[$name_prefix . 'survey_lazy_loading_for_images'] == 'on') ? sanitize_text_field( $_REQUEST[$name_prefix . 'survey_lazy_loading_for_images'] )  : 'off';

            $options = array(
                "survey_default_type"               => $survey_default_type,
                "survey_answer_default_count"       => $survey_answer_default_count,
                "survey_disable_user_ip"            => $survey_disable_user_ip,
                "survey_block_by_user_ips"          => $survey_block_by_user_ips,
                "survey_users_ips_that_will_blocked" => $survey_users_ips_that_will_blocked,
                "survey_disable_user_name"          => $survey_disable_user_name,
                "survey_disable_user_email"         => $survey_disable_user_email,
                "survey_submissions_title_length"   => $survey_submissions_title_length,
                "survey_title_length"               => $survey_title_length,
                "survey_categories_title_length"    => $survey_categories_title_length,
                "survey_animation_top"              => $survey_animation_top,
                "survey_enable_animation_top"       => $survey_enable_animation_top,
                "survey_disable_survey_menu_notification" => $survey_disable_survey_menu_notification,
                "survey_disable_submission_menu_notification" => $survey_disable_submission_menu_notification,
                "survey_textarea_height"            => $survey_textarea_height,
                "survey_wp_editor_height"           => $survey_wp_editor_height,
                "survey_make_questions_required"    => $survey_make_questions_required,
                "survey_lazy_loading_for_images"    => $survey_lazy_loading_for_images,

                // User history  shortcode
                // "ays_show_result_report"        => $show_result_report,
                "user_history_columns"             => $user_history_columns,
                "user_history_columns_order"       => $user_history_columns_order,

                // All submissions
                "all_submissions_columns"           => $all_submissions_columns,
                "all_submissions_columns_order"     => $all_submissions_columns_order,
                "all_submissions_show_publicly"     => $all_submissions_show_publicly,
                
                // Submissions settings
                "survey_matrix_show_result_type"     => $survey_matrix_show_result_type,
                "survey_show_submissions_order_type" => $survey_show_submissions_order_type,
                
                // User roles options
                "user_roles_to_change_survey"       => $user_roles_to_change_survey,
                
                //Promote plugin
                "survey_enable_promote_plugin"      => $survey_enable_promote_plugin,
            );

            // Front request auto approve
            $survey_front_request_auto_approve = isset($_REQUEST[$name_prefix . 'survey_front_request_auto_approve']) && $_REQUEST[$name_prefix . 'survey_front_request_auto_approve'] == 'on' ? sanitize_text_field($_REQUEST[$name_prefix . 'survey_front_request_auto_approve']) : 'off';

            $front_request_options = array(
                "survey_front_request_auto_approve" => $survey_front_request_auto_approve,
            );

           // $month_count = 10;
            $del_stat = "";
            $month_count = isset($_REQUEST['ays_delete_results_by']) ? absint( sanitize_text_field( $_REQUEST['ays_delete_results_by'] ) ) : null;
            if($month_count !== null && $month_count > 0){
                $year = intval( date( 'Y', current_time('timestamp') ) );
                $dt = intval( date( 'n', current_time('timestamp') ) );
                $month = $dt - $month_count;
                if($month < 0){
                    $month = 12 - $month;
                    if($month > 12){
                        $mn = $month % 12;
                        $mnac = ($month - $mn) / 12;
                        $month = 12 - ($mn);
                        $year -= $mnac;
                    }
                }elseif($month == 0){        
                    $month = 12;
                    $year--;
                }                
                $sql = "DELETE FROM " . $wpdb->prefix . "aysquiz_reports 
                        WHERE YEAR(end_date) = '".$year."' 
                          AND MONTH(end_date) <= '".$month."'";
                $res = $wpdb->query($sql);
                if($res >= 0){
                    $del_stat = "&del_stat=ok&mcount=" . $month_count;
                }
            }

            $fields = array();

            $fields['user_roles'] = $roles;
            $fields['buttons_texts'] = $buttons_texts;
            $fields['options'] = $options;
            $fields['front_request_options'] = $front_request_options;

            $fields = apply_filters( 'ays_sm_settings_page_integrations_saves', $fields, $_REQUEST );
            foreach ($fields as $key => $value) {
                $result = $this->ays_update_setting( $key, json_encode( $value ) );
                if($result){
                    $success++;
                }
            }

            $message = "saved";
            if($success > 0){
                $tab = "";
                if( isset( $_REQUEST['ays_survey_tab'] ) ){
                    $tab = "&ays_survey_tab=". sanitize_text_field( $_REQUEST['ays_survey_tab'] );
                }

                $url = admin_url('admin.php') . "?page=survey-maker-settings" . $tab . '&status=' . $message . $del_stat;
                wp_redirect( $url );
            }
        }
        
    }

    public function get_data(){
        $data = get_option( "ays_quiz_integrations" );
        if($data == null || $data == ''){
            return array();
        }else{
            return json_decode( get_option( "ays_quiz_integrations" ), true );
        }
    }

    public function get_db_data(){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $sql = "SELECT * FROM ".$settings_table;
        $results = $wpdb->get_results($sql, ARRAY_A);
        if(count($results) > 0){
            return $results;
        }else{
            return array();
        }
    }    
    
    public function check_settings_meta($metas){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        foreach($metas as $meta_key){
            $sql = "SELECT COUNT(*) FROM ".$settings_table." WHERE meta_key = '". esc_sql( $meta_key ) ."'";
            $result = $wpdb->get_var($sql);
            if(intval($result) == 0){
                $this->ays_add_setting($meta_key, "", "", "");
            }
        }
        return false;
    }
    
    public function check_setting_user_roles(){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $sql = "SELECT COUNT(*) FROM ".$settings_table." WHERE meta_key = 'user_roles'";
        $result = $wpdb->get_var($sql);
        if(intval($result) == 0){
            $roles = json_encode(array('administrator'));
            $this->ays_add_setting("user_roles", $roles, "", "");
        }
        return false;
    }
    
    public function ays_get_setting($meta_key){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = '".$meta_key."'";
        $result = $wpdb->get_var($sql);
        if($result != ""){
            return $result;
        }
        return false;
    }
    
    public function ays_add_setting($meta_key, $meta_value, $note = "", $options = ""){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $result = $wpdb->insert(
            $settings_table,
            array(
                'meta_key'    => $meta_key,
                'meta_value'  => $meta_value,
                'note'        => $note,
                'options'     => $options
            ),
            array( '%s', '%s', '%s', '%s' )
        );
        if($result >= 0){
            return true;
        }
        return false;
    }
    
    public function ays_update_setting($meta_key, $meta_value, $note = null, $options = null){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $value = array(
            'meta_value'  => $meta_value,
        );
        $value_s = array( '%s' );
        if($note != null){
            $value['note'] = $note;
            $value_s[] = '%s';
        }
        if($options != null){
            $value['options'] = $options;
            $value_s[] = '%s';
        }
        $result = $wpdb->update(
            $settings_table,
            $value,
            array( 'meta_key' => $meta_key, ),
            $value_s,
            array( '%s' )
        );
        if($result >= 0){
            return true;
        }
        return false;
    }
    
    public function ays_delete_setting($meta_key){
        global $wpdb;
        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $wpdb->delete(
            $settings_table,
            array( 'meta_key' => $meta_key ),
            array( '%s' )
        );
    }

    public function get_empty_duration_rows_count(){
        global $wpdb;
        $sql = "SELECT COUNT(*) AS c
                FROM {$wpdb->prefix}aysquiz_reports
                WHERE (duration = '' OR duration IS NULL)";
        $result = $wpdb->get_var($sql);
        return intval($result);
    }

    public function update_duration_data(){
        global $wpdb;
        $sql = "UPDATE `{$wpdb->prefix}socialsurv_reports`
                SET `duration`= TIMESTAMPDIFF(SECOND, start_date, end_date)";
        $result = $wpdb->query($sql);
        if($result){
            $tab = "&ays_survey_tab=tab3";
            $message = "duration_updated";
            $url = admin_url('admin.php') . "?page=survey-maker-settings" . $tab . '&status=' . $message;
            wp_redirect( $url );
            exit;
        }
    }

    public function survey_settings_notices($status){

        if ( empty( $status ) )
            return;

        if ( 'saved' == $status )
            $updated_message = esc_html( __( 'Changes saved.', $this->plugin_name ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Quiz attribute .', $this->plugin_name ) );
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Quiz attribute deleted.', $this->plugin_name ) );
        elseif ( 'duration_updated' == $status )
            $updated_message = esc_html( __( 'Duration old data is successfully updated.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }

    public function message_variables_section($message_variables, $with_flags = true){
        if( isset($message_variables) && !empty($message_variables) ){
            $content = array();
            foreach($message_variables as $message_variable_box_key => $message_variable_box_value){
                $content[] = '<fieldset class="ays-survey-message-vars-fields-box">';
                    $content[] = '<legend>
                                    <h5 class="ays-survey-message-vars-fields-heading">'.__( ucwords(str_replace('_', ' ', $message_variable_box_key)) ,"survey-maker").'</h5>
                                  </legend>';
                foreach($message_variable_box_value as $message_variable => $description){
                    $message_variable_input = $with_flags ? '%%' . $message_variable . '%%' : $message_variable;
                    $content[] = '<p class="vmessage">';
                        $content[] = '<strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="'.esc_attr($message_variable_input).'"/>
                                    </strong>';
                        $content[] = '<span> - </span>';
                        $content[] = '<span style="font-size:18px;">
                                        '. $description .'
                                    </span>';
                    $content[] = '</p>';
                }
                $content[] = '</fieldset>';
            }
            return implode('' , $content);
        }
    }
    
}
