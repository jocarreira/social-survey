<?php
    $actions = $this->settings_obj;
    $loader_iamge = "<span class='display_none ays_survey_loader_box'><img src=". SURVEY_MAKER_ADMIN_URL ."/images/loaders/loading.gif></span>";

    if( isset( $_REQUEST['ays_submit'] ) ){
        $actions->store_data();
    }
    if(isset($_GET['ays_survey_tab'])){
        $ays_survey_tab = sanitize_text_field( $_GET['ays_survey_tab'] );
    }else{
        $ays_survey_tab = 'tab1';
    }

    if(isset($_GET['action']) && $_GET['action'] == 'update_duration'){
        $actions->update_duration_data();
    }

    $db_data = $actions->get_db_data();
    $options = ($actions->ays_get_setting('options') === false) ? array() : json_decode($actions->ays_get_setting('options'), true);
    $front_request_options = ($actions->ays_get_setting('front_request_options') === false) ? array() : json_decode($actions->ays_get_setting('front_request_options'), true);

    global $wp_roles;
    $ays_users_roles = $wp_roles->role_names;
    $user_roles = $actions->ays_get_setting('user_roles');
    if( $user_roles === null || $user_roles === false ){
        $user_roles = array();
    }else{
        $user_roles = json_decode( $user_roles );
    }

    // User roles to change survey
    $user_roles_to_change_survey = (isset($options['user_roles_to_change_survey']) && !empty( $options['user_roles_to_change_survey'] ) ) ? $options['user_roles_to_change_survey'] : array('administrator');

    $survey_types = array(
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
        "html" => __("HTML", $this->plugin_name),
        "upload" => __("File upload", $this->plugin_name),
        "email" => __("Email", $this->plugin_name),
        "name" => __("Name", $this->plugin_name),
    );

    $options['survey_default_type'] = !isset($options['survey_default_type']) ? 'radio' : $options['survey_default_type'];
    $survey_default_type = (isset($options['survey_default_type']) && $options['survey_default_type'] != '') ? $options['survey_default_type'] : 'radio';
    $survey_answer_default_count = (isset($options['survey_answer_default_count']) && $options['survey_answer_default_count'] != '')? $options['survey_answer_default_count'] : 1;
    
    // Do not store IP addresses
    $options['survey_disable_user_ip'] = (isset($options['survey_disable_user_ip']) && $options['survey_disable_user_ip'] == 'on') ? $options['survey_disable_user_ip'] : 'off' ;

    // Block Users by IP addresses
    $options['survey_block_by_user_ips'] = (isset($options['survey_block_by_user_ips']) && $options['survey_block_by_user_ips'] == 'on') ? $options['survey_block_by_user_ips'] : 'off' ;
    $survey_block_by_user_ips = (isset($options['survey_block_by_user_ips']) && $options['survey_block_by_user_ips'] == 'on') ? true : false;
    // Users IP addresses that will be blocked
    $survey_users_ips_that_will_blocked = (isset($options['survey_users_ips_that_will_blocked'])  && $options['survey_users_ips_that_will_blocked'] != '') ? $options['survey_users_ips_that_will_blocked'] : '';
 
    // Do not store user names
    $options['survey_disable_user_name'] = (isset($options['survey_disable_user_name']) && $options['survey_disable_user_name'] != '') ? $options['survey_disable_user_name'] : 'off' ;
    $survey_disable_user_name = (isset($options['survey_disable_user_name']) && $options['survey_disable_user_name'] == 'on') ? true : false;

    // Do not store user emails
    $options['survey_disable_user_email'] = (isset($options['survey_disable_user_email']) && $options['survey_disable_user_email'] != '') ? $options['survey_disable_user_email'] : 'off' ;
    $survey_disable_user_email = (isset($options['survey_disable_user_email']) && $options['survey_disable_user_email'] == 'on') ? true : false;
    
    $survey_disable_user_ip = (isset($options['survey_disable_user_ip']) && $options['survey_disable_user_ip'] == 'on') ? true : false;
    
    $survey_submmission_title_length = (isset($options['survey_submissions_title_length']) && $options['survey_submissions_title_length'] != '') ? intval($options['survey_submissions_title_length']) : 5;
    $survey_title_length = (isset($options['survey_title_length']) && $options['survey_title_length'] != '') ? intval($options['survey_title_length']) : 5;

    $survey_categories_title_length = (isset($options['survey_categories_title_length']) && $options['survey_categories_title_length'] != '') ? intval($options['survey_categories_title_length']) : 5;

    // Animation Top 
    $survey_animation_top = (isset($options['survey_animation_top']) && $options['survey_animation_top'] != '') ? absint(intval($options['survey_animation_top'])) : 200;

    $options['survey_enable_animation_top'] = isset($options['survey_enable_animation_top']) ? $options['survey_enable_animation_top'] : 'on';
    $survey_enable_animation_top = (isset($options['survey_enable_animation_top']) && $options['survey_enable_animation_top'] == "on") ? true : false;

    // Disable Survey Maker menu item notification
    $options['survey_disable_survey_menu_notification'] = isset($options['survey_disable_survey_menu_notification']) ? esc_attr( $options['survey_disable_survey_menu_notification'] ) : 'off';
    $survey_disable_survey_menu_notification = (isset($options['survey_disable_survey_menu_notification']) && esc_attr( $options['survey_disable_survey_menu_notification'] ) == "on") ? true : false;

    // Disable Submissions menu item notification
    $options['survey_disable_submission_menu_notification'] = isset($options['survey_disable_submission_menu_notification']) ? esc_attr( $options['survey_disable_submission_menu_notification'] ) : 'off';
    $survey_disable_submission_menu_notification = (isset($options['survey_disable_submission_menu_notification']) && esc_attr( $options['survey_disable_submission_menu_notification'] ) == "on") ? true : false;

    $options['ays_show_result_report'] = !isset( $options['ays_show_result_report'] ) ? 'on' : $options['ays_show_result_report'];

    // $default_user_page_columns = array(
    //     'quiz_name' => 'quiz_name',
    //     'start_date' => 'start_date',
    //     'end_date' => 'end_date',
    //     'duration' => 'duration',
    //     'score' => 'score',
    //     'details' => 'details',
    // );

    // $options['user_page_columns'] = ! isset( $options['user_page_columns'] ) ? $default_user_page_columns : $options['user_page_columns'];
    // $user_page_columns = (isset( $options['user_page_columns'] ) && !empty($options['user_page_columns']) ) ? $options['user_page_columns'] : array();
    // $user_page_columns_order = (isset( $options['user_page_columns_order'] ) && !empty($options['user_page_columns_order']) ) ? $options['user_page_columns_order'] : $default_user_page_columns;

    // $default_user_page_column_names = array(
    //     "quiz_name" => __( 'Quiz name', $this->plugin_name ),
    //     "start_date" => __( 'Start date', $this->plugin_name ),
    //     "end_date" => __( 'End date', $this->plugin_name ),
    //     "duration" => __( 'Duration', $this->plugin_name ),
    //     "score" => __( 'Score', $this->plugin_name ),
    //     "details" => __( 'Details', $this->plugin_name )
    // );

    // Aro Buttons Text

    $buttons_texts_res      = ($actions->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $actions->ays_get_setting('buttons_texts');
    $buttons_texts          = json_decode($buttons_texts_res, true);

    $survey_next_button     = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next';
    $survey_previous_button = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev';
    $survey_clear_button    = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $buttons_texts['clear_button'] ) ) : 'Clear selection';
    $survey_finish_button   = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $buttons_texts['finish_button'] ) ) : 'Finish';
    $survey_restart_button  = (isset($buttons_texts['restart_button']) && $buttons_texts['restart_button'] != '') ? stripslashes( esc_attr( $buttons_texts['restart_button'] ) ) : 'Restart survey';
    $survey_exit_button     = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? stripslashes( esc_attr( $buttons_texts['exit_button'] ) ) : 'Exit';
    $survey_login_button    = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? stripslashes( esc_attr( $buttons_texts['login_button'] ) ) : 'Log In';
    $survey_check_button    = (isset($buttons_texts['check_button']) && $buttons_texts['check_button'] != '') ? stripslashes( esc_attr( $buttons_texts['check_button'] ) ) : 'Check';
    $survey_start_button    = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start';

    //Aro end

    // Submissions settings matrix scale 
    $survey_matrix_show_result_type = (isset($options['survey_matrix_show_result_type'])  && $options['survey_matrix_show_result_type'] != '') ? esc_attr($options['survey_matrix_show_result_type']) : 'by_votes';

    // Submissions settings submissions order type
    $survey_show_submissions_order_type = (isset($options['survey_show_submissions_order_type'])  && $options['survey_show_submissions_order_type'] != '') ? esc_attr($options['survey_show_submissions_order_type']) : 'by_default';

    // Textarea height (public)
    $survey_textarea_height = (isset($options['survey_textarea_height']) && $options['survey_textarea_height'] != '' && $options['survey_textarea_height'] != 0) ? absint( sanitize_text_field($options['survey_textarea_height']) ) : 100;

    // WP Editor height
    $survey_wp_editor_height = (isset($options['survey_wp_editor_height']) && $options['survey_wp_editor_height'] != '' && $options['survey_wp_editor_height'] != 0) ? absint( esc_attr($options['survey_wp_editor_height']) ) : 100;

    // Make question required
    $options['survey_make_questions_required'] = (isset($options['survey_make_questions_required']) && $options['survey_make_questions_required'] != '') ? $options['survey_make_questions_required'] : 'off' ;
    $survey_make_questions_required = (isset($options['survey_make_questions_required']) && $options['survey_make_questions_required'] == 'on') ? true : false;

    // Lazy loading for images
    $options['survey_lazy_loading_for_images'] = (isset($options['survey_lazy_loading_for_images']) && $options['survey_lazy_loading_for_images'] != '') ? $options['survey_lazy_loading_for_images'] : 'off' ;
    $survey_lazy_loading_for_images = (isset($options['survey_lazy_loading_for_images']) && $options['survey_lazy_loading_for_images'] == 'on') ? true : false;

    // all submission shortcodes
    //default all submission column
    $default_all_submissions_columns = array(
        'user_name'       => 'user_name',
        'survey_name'     => 'survey_name',
        'submission_date' => 'submission_date',
    );
    $options['all_submissions_columns'] = ! isset( $options['all_submissions_columns'] ) ? array() : $options['all_submissions_columns'];
    $all_submissions_columns = (isset( $options['all_submissions_columns'] ) && !empty($options['all_submissions_columns']) ) ? $options['all_submissions_columns'] : $default_all_submissions_columns;
    $all_submissions_columns_order = (isset( $options['all_submissions_columns_order'] ) && !empty($options['all_submissions_columns_order']) ) ? $options['all_submissions_columns_order'] : $default_all_submissions_columns;

    $default_all_submissions_column_names = array(
        "user_name"         => __( 'User name', $this->plugin_name),
        "survey_name"       => __( 'Survey name', $this->plugin_name ),
        "submission_date"   => __( 'Submission Date',$this->plugin_name ),
    );

    // Show publicly
    $options['all_submissions_show_publicly'] = isset($options['all_submissions_show_publicly']) ? $options['all_submissions_show_publicly'] : 'off';
    $all_submissions_show_publicly = (isset($options['all_submissions_show_publicly']) && $options['all_submissions_show_publicly'] == "on") ? true : false;
    
    // Front request auto approve
    $front_request_options['survey_front_request_auto_approve'] = isset($front_request_options['survey_front_request_auto_approve']) ? sanitize_text_field($front_request_options['survey_front_request_auto_approve']) : 'off';
    $survey_front_request_auto_approve = (isset($front_request_options['survey_front_request_auto_approve']) && $front_request_options['survey_front_request_auto_approve'] == "on") ? true : false;

    //show in User history
    $ays_survey_default_user_history_columns = array(
        'survey_name' => 'survey_name',
        'submission_date' => 'submission_date',
    );

    $options['user_history_columns'] = ! isset( $options['user_history_columns'] ) ? array() : $options['user_history_columns'];
    $ays_survey_user_history_columns = (isset( $options['user_history_columns'] ) && !empty($options['user_history_columns']) ) ? $options['user_history_columns'] : $ays_survey_default_user_history_columns;
    $ays_survey_user_history_columns_order = (isset( $options['user_history_columns_order'] ) && !empty($options['user_history_columns_order']) ) ? $options['user_history_columns_order'] : $ays_survey_default_user_history_columns;

    $ays_survey_default_user_history_column_names = array(
        "survey_name" => __( 'Survey name', $this->plugin_name ),
        "submission_date" => __( 'Submission Date', $this->plugin_name ),
    );
    
    // Enable Promote Plugin
    $options['survey_enable_promote_plugin'] = isset($options['survey_enable_promote_plugin']) ? $options['survey_enable_promote_plugin'] : 'on';
    $survey_enable_promote_plugin = (isset($options['survey_enable_promote_plugin']) && $options['survey_enable_promote_plugin'] == "on") ? true : false;

    // Add message varibales here
    $message_variables = array(        
        'general_message_variables' => array(
            'current_date'    => __('The date of the submission survey.' , "survey-maker"),
            'current_time'    => __('The time of the submission survey.' , "survey-maker"),
            'unique_code'     => __('Use to identify the uniqueness of each attempt.' , "survey-maker"),
            'post_id'         => __('The ID of the current post.' , "survey-maker"),
            'home_page_url'   => __('The URL of the home page.' , "survey-maker"),
            'post_author_email' => __('The Email of the author of the post.' , "survey-maker"),
            'post_author_nickname' => __('The Nickname of the author of the post.' , "survey-maker"),
        ),
        'user_message_variables' => array(
            'user_name'  => __('The name the user entered into the survey form. It will work only if the name field exists in the form.' , "survey-maker"),
            'user_email' => __('The E-mail the user entered into the survey form. It will work only if the email field exists in the form.' , "survey-maker"),
            'user_wordpress_email' => __('The E-mail that was filled in their WordPress site during registration.' , "survey-maker"),
            'user_id' => __('The ID of the current user.' , "survey-maker"),
            'users_count'     => __('The number of the passed users count of the given survey.' , "survey-maker"),
            'users_first_name' => __('The user\'s first name that was filled in their WordPress site during registration.' , "survey-maker"),
            'users_last_name'  => __('The user\'s last name that was filled in their WordPress site during registration.' , "survey-maker"),
            'users_nick_name'  => __('The user\'s nick name that was filled in their WordPress site during registration.' , "survey-maker"),
            'user_wordpress_roles'  => __('The user\'s role(s) when logged-in. In case the user is not logged-in, the field will be empty.' , "survey-maker"),
            'users_display_name'    => __('The user\'s display name that was filled in their WordPress site during registration.' , "survey-maker"),
            'users_ip_address'      => __('The user\'s ip address.' , "survey-maker"),
            'admin_email'      => __('Shows the admin\'s email that was filled in their WordPress profile.' , "survey-maker"),
        ),
        'survey_message_variables' => array(
            'survey_title' => __('The title of the survey.' , "survey-maker"),
            'survey_id'    => __('The ID of the survey.' , "survey-maker"),
            'current_survey_author' => __('It will show the author of the current survey.' , "survey-maker"),
            'current_survey_author_email' => __('It will show the author email of the current survey.' , "survey-maker"),
            'current_survey_page_link'    => __('Prints the webpage link where the current survey is posted.' , "survey-maker"),
            'questions_count' => __('The number of the questions of the given survey.' , "survey-maker"),
            'sections_count'  => __('The number of the sections of the given survey.' , "survey-maker"),
            'submission_count' => __('Shows the submission count of a particular survey.' , "survey-maker"),
            'creation_date'         => __('The creation date of the survey.' , "survey-maker"),
        )
    );

    ?>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
                </a>
            </div>
        </div>
        <form method="post" id="ays-survey-settings-form">
            <input type="hidden" name="ays_survey_tab" value="<?php echo $ays_survey_tab; ?>">
            <h1 class="wp-heading-inline">
            <?php
                echo __('General Settings',$this->plugin_name);
            ?>
            </h1>
            <?php                
                if( isset( $_REQUEST['status'] ) ){
                    $actions->survey_settings_notices( sanitize_text_field( $_REQUEST['status'] ) );
                }
            ?>
            <hr/>
            <div class="ays-settings-wrapper">
                <div class="ays-settings-wrapper-tabs">
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_survey_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Geral", $this->plugin_name);?>
                        </a>
                        <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_survey_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Integrações", $this->plugin_name);?>
                        </a>
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_survey_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Variáveis de mensagem", $this->plugin_name);?>
                        </a>
                        <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_survey_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Botões Textos", $this->plugin_name);?>
                        </a>
                        <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_survey_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Shortcodes", $this->plugin_name);?>
                        </a>
                    </div>
                </div>
                <div class="ays-survey-tabs-wrapper">
                    <div id="tab1" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab1') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('General Settings',$this->plugin_name)?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_globe"></i></strong>
                                <h5><?php echo __('Who will have permission to Survey',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_user_roles">
                                        <?php echo __( "Select user role for giving access to Survey menu", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Give access to the Survey Maker plugin to only the selected user role(s) on your WP dashboard. Each selected user will see only his/her created surveys and the submissions of those surveys.",$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="ays_user_roles[]" id="ays_user_roles" multiple>
                                        <?php
                                            foreach($ays_users_roles as $role => $role_name){
                                                $selected = in_array($role, $user_roles) ? 'selected' : '';
                                                echo "<option ".$selected." value='".$role."'>".$role_name."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_user_roles_to_change_survey">
                                        <?php echo __( "Select user role for giving access to change all survey data", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give permissions to manage all surveys and submissions to these user roles. Please add the given user roles to the above field as well.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 ays-survey-user-roles">
                                    <select name="ays_user_roles_to_change_survey[]" id="ays_user_roles_to_change_survey" multiple>
                                        <?php
                                            foreach($ays_users_roles as $role => $role_name){
                                                $selected = in_array($role, $user_roles_to_change_survey) ? 'selected' : '';
                                                echo "<option ".$selected." value='".$role."'>".$role_name."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <blockquote>
                                <?php echo __( "Control the access of the plugin from the dashboard and manage the capabilities of those user roles.", $this->plugin_name ); ?>
                                <br>
                                <?php echo __( "If you want to give a full control to the given user role, please add the role in both fields.", $this->plugin_name ); ?>
                            </blockquote>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_question"></i></strong>
                                <h5><?php echo __('Default parameters for Survey',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_default_type">
                                        <?php echo __( "Surveys default question type", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose default question type which will be selected in the Add new survey page.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_survey_default_type" name="ays_survey_default_type">
                                        <option></option>
                                        <?php
                                            foreach($survey_types as $survey_type => $survey_label):
                                            $selected = $survey_default_type == $survey_type ? "selected" : "";
                                        ?>
                                        <option value="<?php echo $survey_type; ?>" <?php echo $selected; ?> ><?php echo $survey_label; ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                        <option disabled>Net promoter score (Agency)</option>
                                        <option disabled>Ranking (Agency)</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_answer_default_count">
                                        <?php echo __( "Answer default count", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can write the default answer count which will be showing in the Add new question page (this will work only with radio, checkbox, and dropdown types).',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_answer_default_count" id="ays_survey_answer_default_count" class="ays-text-input" value="<?php echo $survey_answer_default_count; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_textarea_height">
                                        <?php echo __( "Textarea height (public)", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the height of the textarea by entering a numeric value. It applies to Paragraph question type textarea.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_textarea_height" id="ays_survey_textarea_height" class="ays-text-input" value="<?php echo $survey_textarea_height; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_wp_editor_height">
                                        <?php echo __( "WP Editor height", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give the default value to the height of the WP Editor. It will apply to all WP Editors within the plugin on the dashboard.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_wp_editor_height" id="ays_survey_wp_editor_height" class="ays-text-input" value="<?php echo $survey_wp_editor_height; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_make_questions_required">
                                        <?php echo __( "Make questions required", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, the questions of newly created surveys will be required by default. Note: The changes will not be applied to the already created surveys.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_make_questions_required" name="ays_survey_make_questions_required" value="on" <?php echo $survey_make_questions_required ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_lazy_loading_for_images">
                                        <?php echo __( "Lazy loading for images", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to delay the loading of images of questions and answers to improve the performance of your plugin.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_lazy_loading_for_images" name="ays_survey_lazy_loading_for_images" value="on" <?php echo $survey_lazy_loading_for_images ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-server"></i></strong>
                                <h5><?php echo __('Users IP addresses',$this->plugin_name)?></h5>
                            </legend>
                            <blockquote class="ays_warning">
                                <p style="margin:0;"><?php echo __( "If this option is enabled then the 'Limitation by IP' option will not work!", $this->plugin_name ); ?></p>
                            </blockquote>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_user_ip">
                                        <?php echo __( "Do not store IP addresses", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, IP address of the users will not be stored in database. Note: If this option is enabled, then the `Limits user by IP` option will not work.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_disable_user_ip" name="ays_survey_disable_user_ip" value="on" <?php echo $survey_disable_user_ip ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-server"></i></strong>
                                <h5><?php echo __('Block Users by IP addresses',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_block_by_user_ips">
                                        <?php echo __( "Block Users by IP addresses", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option you will be able to block particular User IPs.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays_toggle_checkbox" id="ays_survey_block_by_user_ips" name="ays_survey_block_by_user_ips" value="on" <?php echo $survey_block_by_user_ips ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group ays_toggle_target" style='<?php echo $survey_block_by_user_ips ? "display:block" : "display:none";?>'>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_users_ips_that_will_blocked">
                                            <?php echo __( "Block User IP's", $this->plugin_name ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("After adding User IP's, you can restrict the access to the survey. The users with particular IP addresses will not be able to pass the survey. You will be able to add as many User IP's as you may need.",$this->plugin_name); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="ays-poll-email-to-admins">                                        
                                            <input type="text" name="ays_survey_users_ips_that_will_blocked" class="" id="ays_survey_users_ips_that_will_blocked" value="<?php echo $survey_users_ips_that_will_blocked; ?>" placeholder="User IPs" style="width:100%" multiple>                                          
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-user-secret"></i></strong>
                                <h5><?php echo __('Anonymity Survey',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_user_name">
                                        <?php echo __( "Do not store User Names", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, User Names will not be stored in database.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_disable_user_name" name="ays_survey_disable_user_name" value="on" <?php echo $survey_disable_user_name ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_user_email">
                                        <?php echo __( "Do not store User Emails", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, User Emails will not be stored in database.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_disable_user_email" name="ays_survey_disable_user_email" value="on" <?php echo $survey_disable_user_email ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset> <!-- Anonymity Survey -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-align-left"></i></strong>
                                <h5><?php echo __('Excerpt words count in list tables',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_title_length">
                                        <?php echo __( "Survey list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the surveys to be shown in the Surveys List Table by putting your preferred count of words in the following field. (For example: if you put 10,  you will see the first 10 words of each survey in the Surveys page of your dashboard.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_title_length" id="ays_survey_title_length" class="ays-text-input" value="<?php echo $survey_title_length; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_submissions_title_length">
                                        <?php echo __( "Submissions list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the submissions to be shown in the Submissions List Table by putting your preferred count of words in the following field. (For example: if you put 10,you will see the first 10 words of each submissions in the Submissions page of your dashboard.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_submissions_title_length" id="ays_survey_submissions_title_length" class="ays-text-input" value="<?php echo $survey_submmission_title_length; ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_categories_title_length">
                                        <?php echo __( "Survey categories list table", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the survey categories to be shown in the Survey categories List Table by putting your preferred count of words in the following field. (For example: if you put 10,you will see the first 10 words of each Category in the Survey categories page of your dashboard.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_categories_title_length" id="ays_survey_categories_title_length" class="ays-text-input" value="<?php echo esc_attr($survey_categories_title_length); ?>">
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-code"></i></strong>
                                <h5><?php echo __('Animation Top',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_enable_animation_top">
                                        <?php echo __( "Enable animation", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable animation of the scroll offset of the survey container. It works when the survey container is visible on the screen partly and the user starts the survey and moves from one question to another.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_survey_enable_animation_top" id="ays_survey_enable_animation_top" value="on" <?php echo $survey_enable_animation_top ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_animation_top">
                                        <?php echo __( "Scroll offset(px)", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the scroll offset of the survey container after the animation starts. It works when the survey container is visible on the screen partly and the user starts the survey and moves from one question to another.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_animation_top" id="ays_survey_animation_top" class="ays-text-input" value="<?php echo $survey_animation_top; ?>">
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-bell"></i></strong>
                                <h5><?php echo __('Menu notifications', 'survey-maker'); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_survey_menu_notification">
                                        <?php echo __( "Disable Survey Maker menu item notification", 'survey-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Survey Maker menu.', 'survey-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_survey_disable_survey_menu_notification" id="ays_survey_disable_survey_menu_notification" value="on" <?php echo $survey_disable_survey_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_submission_menu_notification">
                                        <?php echo __( "Disable Submissions menu item notification", 'survey-maker' ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Submissions menu.', 'survey-maker') ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_survey_disable_submission_menu_notification" id="ays_survey_disable_submission_menu_notification" value="on" <?php echo $survey_disable_submission_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- Menu notifications -->
                        <!-- summary email start -->
                        <hr/>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-envelope-o"></i></strong>
                                <h5><?php echo __("Send automatic email reporting to admin(s)",$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0px;">
                                <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
                                <div class="ays-pro-features-v2-small-buttons-box">
                                    <div class="ays-pro-features-v2-video-button"></div>
                                        <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="<?php echo (SURVEY_MAKER_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                            <div class="ays-pro-features-v2-upgrade-text">
                                                <?php echo __("Upgrade to Developer/Agency" , "survey-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_email_to_admins">
                                                <?php echo __( "Send automatic email reporting per session", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set up automatic email reporting to inform yourself or anyone else, about the submissions of surveys. It will indicate the times of submissions all surveys will have at that given moment,  will send a table which will include the names of all surveys and the number of submissions each one will have and will show the statistics of the growth/decline of the submissions in percentage',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="ays-poll-email-to-admins">                                        
                                                <input type="checkbox" id="ays_survey_email_to_admins" value="on">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group" style="display:block;">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="ays_survey_admin_email_sessions">
                                                    <?php echo __( "Session period", $this->plugin_name ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the time for one session and the provided email(s) will receive an automatic email notification once during the period.',$this->plugin_name); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="ays-poll-email-to-admins">                                        
                                                    <select id="ays_survey_admin_email_session">
                                                            <option>Hourly</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="ays_survey_admins_emails">
                                                    <?php echo __( "Email addresses", $this->plugin_name ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Provide emails to which you need to send the survey's reports. Insert emails comma separated.",$this->plugin_name); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="ays-poll-email-to-admins">                                        
                                                    <input type="email" class="" id="ays_survey_admins_emails" placeholder="Admins email" style="width:100%" multiple>                                          
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- summary email end -->
                        <hr/>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                            <strong style="font-size:30px;"><i class="fa fa-th-list"></i></strong>
                                <h5><?php echo __("Submissions settings",$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>
                                        <?php echo __( "Matrix scale results", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The results can be displayed by Votes and by Percentage. Choosing by Votes, the system will display the results based on votes. To see the percentage you need to hover over the vote numbers. You will be able to do the same in the case of choosing by Percentage as well.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label class="checkbox_ays form-check form-check-inline" for="ays_survey_matrix_scale_show_result_type_votes">
                                            <input type="radio" id="ays_survey_matrix_scale_show_result_type_votes" name='ays_survey_matrix_scale_show_result_type' value='by_votes' <?php echo $survey_matrix_show_result_type == 'by_votes' ? 'checked' : '';?>>
                                            <?php echo __('By Votes', $this->plugin_name) ?>
                                        </label>
                                        <label class="checkbox_ays form-check form-check-inline" for="ays_survey_matrix_scale_show_result_type_percentage">
                                            <input type="radio" id="ays_survey_matrix_scale_show_result_type_percentage" name="ays_survey_matrix_scale_show_result_type" value="by_percentage" <?php echo $survey_matrix_show_result_type == 'by_percentage' ? 'checked' : '';?>>
                                            <?php echo __('By Percentage', $this->plugin_name) ?>
                                        </label>
                                    </div>
                                </div>
                            </div> 
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>
                                        <?php echo __( "Show results by", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify how the charts will be displayed. Note that ascending and descending orderings can be used with Checkbox, Linear/Star scales, Star list, Slider List question types only.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label class="checkbox_ays form-check form-check-inline" for="ays_survey_show_submissions_order_type_defalut">
                                            <input type="radio" id="ays_survey_show_submissions_order_type_defalut" name="ays_survey_show_submissions_order_type" value="by_default" <?php echo $survey_show_submissions_order_type == 'by_default' ? 'checked' : '';?>>
                                            <?php echo __('Default', $this->plugin_name) ?>
                                        </label>
                                        <label class="checkbox_ays form-check form-check-inline" for="ays_survey_show_submissions_order_type_ascending">
                                            <input type="radio" id="ays_survey_show_submissions_order_type_ascending" name='ays_survey_show_submissions_order_type' value='by_asc' <?php echo $survey_show_submissions_order_type == 'by_asc' ? 'checked' : '';?>>
                                            <?php echo __('Ascending', $this->plugin_name) ?>
                                        </label>
                                        <label class="checkbox_ays form-check form-check-inline" for="ays_survey_show_submissions_order_type_descending">
                                            <input type="radio" id="ays_survey_show_submissions_order_type_descending" name='ays_survey_show_submissions_order_type' value='by_desc' <?php echo $survey_show_submissions_order_type == 'by_desc' ? 'checked' : '';?>>
                                            <?php echo __('Descending', $this->plugin_name) ?>
                                        </label>
                                    </div>
                                </div>
                            </div> 
                        </fieldset><!-- submissions settings -->
                        <hr>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __("Survey multilanguage","survey-maker")?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0px;">
                                <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
                                    <div class="ays-pro-features-v2-small-buttons-box">
                                        <div class="ays-pro-features-v2-video-button"></div>
                                        <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                            <div class="ays-pro-features-v2-upgrade-text">
                                                <?php echo __("Upgrade to Agency" , "survey-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_survey_multilanugage_shortcode">
                                                        <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Write your desired text in any WordPress language. It will be translated in the front-end. The languages must be included in the ISO 639-1 Code column.', "survey-maker"); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[:en]Hello[:es]Hola[:]'>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <ul class="ays-survey-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( "In this shortcode you can add your desired text and its translation. The translated version of the text will be displayed in the front-end. The languages must be written in the %sLanguage Code%s", "survey-maker" ),
                                                        '<a href="https://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank">',
                                                        '</a>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset><!-- Survey multilanguage -->
                        <hr>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-bullhorn" aria-hidden="true"></i></strong>
                                <h5><?php echo __("Promote the plugin",$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_enable_promote_plugin">
                                        <?php echo __( "Powered By", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, the text logo will be displayed at the bottom of the Survey.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" id="ays_survey_enable_promote_plugin" name='ays_survey_enable_promote_plugin' value='on' <?php echo $survey_enable_promote_plugin ? 'checked' : '';?>>
                                </div>
                            </div> 
                        </fieldset><!-- Promote plugin -->
                    </div>
                    <div id="tab2" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab2') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Integrations',$this->plugin_name)?></p>
                        <hr/>
                        <?php
                            do_action( 'ays_sm_settings_page_integrations' );
                        ?>                        
                    </div>
                    <div id="tab3" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab3') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Message variables',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p><?php echo esc_attr__( 'You can copy these variables and paste them in the following options from the survey settings', $this->plugin_name ); ?>:</p>
                                <ul class='ays_tooltop_ul'>
                                    <li><?php echo esc_attr__( 'Thank you message', $this->plugin_name ); ?></li>
                                    <li><?php echo esc_attr__( 'Send email to user', $this->plugin_name ); ?></li>
                                    <li><?php echo esc_attr__( 'Send email to admin', $this->plugin_name ); ?></li>
                                    <li><?php echo esc_attr__( 'Email configuration', $this->plugin_name ); ?>
                                        <ul class='ays_tooltop_ul_inside'>
                                            <li><?php echo esc_attr__( 'From Name', $this->plugin_name ); ?></li>
                                            <li><?php echo esc_attr__( 'Subject', $this->plugin_name ); ?></li>
                                            <li><?php echo esc_attr__( 'Reply To Name', $this->plugin_name ); ?></li>
                                        </ul>
                                    </li>
                                </ul>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p><?php echo __( "You can copy these variables and paste them in the following options from the survey settings", $this->plugin_name ); ?>:</p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Thank you message", $this->plugin_name ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Send email to user", $this->plugin_name ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Send email to admin", $this->plugin_name ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Email configuration", $this->plugin_name ); ?></p>
                            <p style="text-indent:30px;margin:0;">* <?php echo __( "From Name", $this->plugin_name ); ?></p>
                            <p style="text-indent:30px;margin:0;">* <?php echo __( "Subject", $this->plugin_name ); ?></p>
                            <p style="text-indent:30px;margin:0;">* <?php echo __( "Reply To Name", $this->plugin_name ); ?></p>
                        </blockquote>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?php echo $actions->message_variables_section($message_variables); ?>
                            </div>
                        </div>
                    </div>
                    <div id="tab4" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab4') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Buttons texts',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'If you make a change here, these words will not be translatable via translation tools!', $this->plugin_name ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote class="ays_warning">
                            <p style="margin:0;"><?php echo __( "If you make a change here, these words will not be translatable via translation tools!", $this->plugin_name ); ?></p>
                        </blockquote>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_next_button">
                                    <?php echo __( "Next button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_next_button" name="ays_survey_next_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_next_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_previous_button">
                                    <?php echo __( "Previous button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_previous_button" name="ays_survey_previous_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_previous_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_clear_button">
                                    <?php echo __( "Clear selection button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_clear_button" name="ays_survey_clear_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_clear_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_finish_button">
                                    <?php echo __( "Finish button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_finish_button" name="ays_survey_finish_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_finish_button; ?>'>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_restart_button">
                                    <?php echo __( "Restart survey button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_restart_button" name="ays_survey_restart_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_restart_button; ?>'>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_exit_button">
                                    <?php echo __( "Exit button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_exit_button" name="ays_survey_exit_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_exit_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_login_button">
                                    <?php echo __( "Log In button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_login_button" name="ays_survey_login_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_login_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_check_button">
                                    <?php echo __( "Check button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_check_button" name="ays_survey_check_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_check_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_start_button">
                                    <?php echo __( "Start button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_start_button" name="ays_survey_start_button" class="ays-text-input ays-text-input-short"  value='<?php echo $survey_start_button; ?>'>
                            </div>
                        </div>                
                    </div>
                    <div id="tab5" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab5') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Shortcodes',$this->plugin_name); ?>
                        </p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('All submissions shortcode settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_all_submission">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and insert it into any post or page to show all the submissions of the surveys.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_all_submission" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_all_submissions]'>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_all_submission_show_publicly">
                                        <?php echo __( "Show to guests too", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the table to guests as well. By default, it is displayed only for logged-in users. So if the option is disabled, then only the logged-in users will be able to see the table. 
                                        Note: Despite the fact of showing the table to the guests, the table will contain only the info of the logged-in users.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_all_submission_show_publicly" name="ays_survey_all_submission_show_publicly" value="on" <?php echo $all_submissions_show_publicly ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label>
                                        <?php echo __( "Table columns", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select and reorder the given columns which should be displayed on the front-end.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                    <div class="ays-show-user-page-table-wrap">
                                        <ul class="ays-all-submission-table ays-show-user-page-table">
                                            <?php
                                                foreach ($all_submissions_columns_order as $key => $val) {
                                                    $checked = '';
                                                    if(is_array($all_submissions_columns)){
                                                        if(in_array($default_all_submissions_columns[$val],$all_submissions_columns)){
                                                            $checked = 'checked';
                                                        }else{
                                                            $checked = '';
                                                        }
                                                    }else{
                                                        if($default_all_submissions_columns[$val] == $all_submissions_columns){
                                                            $checked = 'checked';
                                                        }else{
                                                            $checked = '';
                                                        }
                                                    }
                                                    ?>
                                                    <li class="ays-user-page-option-row ui-state-default">
                                                        <input type="hidden" value="<?php echo $val; ?>" name="ays_survey_all_submission_columns_order[<?php echo $val; ?>]"/>
                                                        <input type="checkbox" id="ays_show_result_<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" name="ays_survey_all_submission_columns[<?php echo $val; ?>]" <?php echo $checked; ?>/>
                                                        <label for="ays_show_result_<?php echo $val; ?>">
                                                            <?php echo $default_all_submissions_column_names[$val]; ?>
                                                        </label>
                                                    </li>
                                                <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- All Submission settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User history settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_user_history">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and insert it into any post or page to show the current user’s submissions history. Each user will see individually presented content based on their taken surveys.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_user_history" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_history]'>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label>
                                        <?php echo __( "User history submissions table columns", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select and reorder the given columns which should be displayed on the front-end.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                    <div class="ays-show-user-page-table-wrap">
                                        <ul class="ays-show-user-page-table">
                                            <?php
                                                foreach ($ays_survey_user_history_columns_order as $key => $val) {
                                                    $checked = '';
                                                    if(is_array($ays_survey_user_history_columns)){
                                                        if(in_array($ays_survey_default_user_history_columns[$val], $ays_survey_user_history_columns)){
                                                            $checked = 'checked';
                                                        }else{
                                                            $checked = '';
                                                        }
                                                    }else{
                                                        if($ays_survey_default_user_history_columns[$val] == $ays_survey_user_history_columns){
                                                            $checked = 'checked';
                                                        }else{
                                                            $checked = '';
                                                        }
                                                    }
                                                    ?>
                                                    <li class="ays-user-page-option-row ui-state-default">
                                                        <input type="hidden" value="<?php echo $val; ?>" name="ays_survey_user_history_columns_order[<?php echo $val; ?>]"/>
                                                        <input type="checkbox" id="ays_show_<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" name="ays_survey_user_history_columns[<?php echo $val; ?>]" <?php echo $checked; ?>/>
                                                        <label for="ays_show_<?php echo $val; ?>">
                                                            <?php echo $ays_survey_default_user_history_column_names[$val]; ?>
                                                        </label>
                                                    </li>
                                                    <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- User history settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display survey summary',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_submissions_summary">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title='<?php echo __("Copy the given shortcode and insert it into any post or page to show the charts of the submissions of the given survey. Please change the \"Your_Survey_ID\" with your survey ID number.",$this->plugin_name); ?>'>
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_submissions_summary" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_submissions_summary id="Your_Survey_ID" hide_questions_ids="QUESTIONS_IDS"]'>
                                    <p class="ays-survey-shortcode-example">Example: [ays_survey_submissions_summary id="1" hide_questions_ids="1,2,3"]</p>
                                </div>
                            </div>
                        </fieldset> <!-- Display survey summary -->                        
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Extra shortcodes',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_first_name">
                                                <?php echo __( "Show User First Name", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's First Name. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_first_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_first_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_last_name">
                                                <?php echo __( "Show User Last Name", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's last name. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_last_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_last_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_nickname">
                                                <?php echo __( "Show User Nickname", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's nickname. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_nickname" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_nick_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_display_name">
                                                <?php echo __( "Show User Display name", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's display name. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_display_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_display_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_email">
                                                <?php echo __( "Show User Email", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's email. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_email" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_email]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_wordpress_roles">
                                                <?php echo __( "Show User Wordpress Roles", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows user's role(s) when logged-in. In case the user is not logged-in, the field will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_wordpress_roles" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_wordpress_roles]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_passed_users_count">
                                                <?php echo __( "Passed users count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it in posts. Insert the Survey ID to receive the number of participants of the survey.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_passed_users_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_passed_users_count id="Your_survey_ID"]'>
                                            <p class="ays-survey-shortcode-example">Example: [ays_survey_passed_users_count id="1"]</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_show_creation_date">
                                                <?php echo __( "Show survey creation date", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Survey ID in the shortcode. It will show the creation date of the particular survey. If there is no survey available/found with that particular Survey ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_show_creation_date" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_creation_date id="Your_Survey_ID"]'>
                                            <p class="ays-survey-shortcode-example">Example: [ays_survey_creation_date id="1"]</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_show_sections_count">
                                                <?php echo __( "Show survey sections count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Survey ID in the shortcode. It will show the number of the sections of the given survey. If there is no survey available/found with that particular Survey ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_show_sections_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_sections_count id="Your_Survey_ID"]'>
                                            <p class="ays-survey-shortcode-example">Example: [ays_survey_sections_count id="1"]</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_show_questions_count">
                                                <?php echo __( "Show survey questions count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Survey ID in the shortcode. It will show the number of the questions of the given survey. If there is no survey available/found with that particular Survey ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_show_questions_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_questions_count id="Your_Survey_ID"]'>
                                            <p class="ays-survey-shortcode-example">Example: [ays_survey_questions_count id="1"]</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_categories_count">
                                                <?php echo __( "Show survey categories count", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of survey categories.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_categories_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_categories_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset> <!-- Extra shortcodes -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Most popular survey',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_most_popular">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Designed to show the most popular survey that is passed most commonly by users.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_most_popular" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_most_popular count="1"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Most popular survey -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Survey Links by Category',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_links_by_category">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After adding the shortcode all surveys from the particular category will be collected in the front-end. After clicking on the Open button you will be redirected to the Survey page.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_links_by_category" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_links_by_category id="YOUR_CATEGORY_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Survey Links by Category -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display question summary',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_question_summary">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display each question summary individually on the front-end. To insert question ID, please click on the three dots located at the bottom right corner of the question(Surveys > the given survey > General tab).',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_question_summary" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_question_summary id="Your_question_ID"]'>
                                    <p class="ays-survey-shortcode-example">Example: [ays_survey_question_summary id="1"]</p>
                                </div>
                            </div>
                        </fieldset> <!-- Display question summary -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Recent surveys',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_recent_surveys">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" data-html="true" 
                                            title="<?php
                                                echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post or widget.',$this->plugin_name) .
                                                "<ul class='ays_tooltip_list'>".
                                                    "<li>". __('Random - If you set the ordering method as random and gave a value to count option, then it will randomly display that given amount of surveys from your created surveys.',$this->plugin_name) ."</li>".
                                                    "<li>". __('Recent - If you set the ordering method as recent and gave a value to count option, then it will display that given amount of surveys from your recently created surveys.',$this->plugin_name) ."</li>".
                                                "</ul>";
                                            ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_recent_surveys" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_display_surveys orderby="random/recent" count="1"]'>
                                </div>
                            </div>
                        </fieldset> <!-- Recent surveys -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Survey categories',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_categories">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post/page. Put IDs of your preferred categories divided by commas, choose the method of displaying (all/random) and specify the count of surveys.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_categories" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_cat ids="Your_Survey_Category_IDs" display="random" count="5" layout="list"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <ul class="ays-survey-general-settings-blockquote-ul">
                                    <li style="padding-bottom: 5px;">
                                        <?php
                                            echo sprintf(
                                                __( '%s IDs %s', $this->plugin_name ) . ' - ' . __( 'Enter IDs of the categories. Example: ids="2,13".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Display %s', $this->plugin_name ) . ' - ' . __( 'Choose the method of displaying. Example: display="random" count="5".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-survey-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s All %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as All, it will show all surveys from the given categories. In this case, it is not required to fill the %sCount%s attribute. You can either remove it or the system will ignore the value given to it.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>',
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Random %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Random, please give a value to %sCount%s option too, and it will randomly display that given amount of surveys from the given categories.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>',
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Layout %s', $this->plugin_name ) . ' - ' . __( 'Choose the design of the layout. Example:layout=grid.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                                <ul class='ays-survey-general-settings-ul'>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s List %s', $this->plugin_name ) . ' - ' . __( 'Choose the design of the layout as list․', $this->plugin_name ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s Grid %s', $this->plugin_name ) . ' - ' . __( 'Choose the design of the layout as grid․', $this->plugin_name ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li style="padding-bottom: 5px;">
                                        <?php
                                            echo sprintf(
                                                __( '%s Example %s', $this->plugin_name ) . ' - ' . __( '[ays_survey_cat ids="1,2,3" display="random" count="3" layout="list"]', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                    </li>
                                </ul>
                            </blockquote>
                        </fieldset> <!-- Survey categories -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User Activity Per Day Settings',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_user_activity_per_day">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('See how many surveys a user passed per day.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_user_activity_per_day" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_activity_per_day]'>
                                </div>
                            </div>
                        </fieldset> <!-- User Activity Per Day Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Survey Activity Per Day Settings',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_survey_activity_per_day">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('See how many times the survey is passed per day.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_survey_activity_per_day" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_activity_per_day id="Your_Survey_ID"]'>
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <?php
                                    echo sprintf(
                                        __( '%s ID %s', $this->plugin_name ) . ' - ' . __( 'Enter ID of the Survey. Example: id="2".', $this->plugin_name ),
                                        '<b>',
                                        '</b>'
                                    );
                                ?>
                            </blockquote>
                        </fieldset> <!-- Survey Activity Per Day Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __( 'Request Form' , $this->plugin_name )?></h5>
                            </legend>                            
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_survey_front_request">
                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it into your desired post. It will allow users to send a request for building a survey with simple settings (Survey title, questions, answers). Find the list of the requests in the Frontend Requests page, which is located on the Survey Maker left navbar. For accepting the request, the admin needs to click on the Approve button next to the given survey.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="ays_survey_survey_front_request" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_request_form]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_front_request_auto_approve">
                                        <?php echo __( "Enable auto-approve", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the option is enabled, the user requests from the Request Form shortcode will automatically be approved and added to the Surveys page.', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" id="ays_survey_front_request_auto_approve" name="ays_survey_front_request_auto_approve" value='on' <?php echo $survey_front_request_auto_approve ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <blockquote>
                                <p style="margin:0;"><?php echo __( "Ability to allow users to create a survey from the front-end.", $this->plugin_name ); ?></p>
                            </blockquote>
                        </fieldset>
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;" class="ays-survey-save-changes-settings-page-mobile">
            <?php
                wp_nonce_field('settings_action', 'settings_action');
                $other_attributes = array(
                    'id' => 'ays-button-apply',
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );

                submit_button(__('Save changes', $this->plugin_name), 'primary ays-survey-loader-banner ays-survey-gen-settings-save', 'ays_submit', true, $other_attributes);
                echo $loader_iamge;
            ?>
            </div>
        </form>
    </div>
</div>
