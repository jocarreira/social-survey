<div id="tab5" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab5') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Limitation of Users',$this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_limit_users">
                <?php echo __('Maximum number of attempts per user',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, you can manage the attempts count per user for taking the survey.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_limit_users" name="ays_survey_limit_users"
                   value="on" <?php echo ($survey_limit_users) ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_limit_users) ? "" : "display_none_not_important"; ?>">
            <div class="ays-limitation-options">
                <!-- Limitation by -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_limit_users_by_ip">
                            <?php echo __('Detect users by',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                                echo htmlspecialchars( sprintf(__('Choose the method of user detection:',$this->plugin_name) . '
                                <ul class="ays_help_ul">
                                    <li>' .__('%sBy IP%s - Detect the users by their IP addresses and limit them. This will work both for guests and registered users. Note: in general, IP is not a static variable, it is constantly changing when the users change their location/ WIFI/ Internet provider.',$this->plugin_name) . '</li>
                                    <li>' .__('%sBy User ID%s - Detect the users by their WP User IDs and limit them. This will work only for registered users. It\'s recommended to use this method to get more reliable results.',$this->plugin_name) . '</li>
                                    <li>' .__('%sBy Cookie%s - Detect the users by their browser cookies and limit them.  It will work both for guests and registered users.',$this->plugin_name) . '</li>
                                    <li>' . __('By Cookie and IP - Detect the users both by their browser cookies and IP addresses and limit them. It will work both for guests and registered users.',$this->plugin_name) .'</li>
                                </ul>',
                                '<em>',
                                '</em>',
                                '<em>',
                                '</em>',
                                '<em>',
                                '</em>'
                            ) );

                            ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_ip" class="form-check-input" name="ays_survey_limit_users_by" value="ip" <?php echo ($survey_limit_users_by == 'ip') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_ip"><?php echo __('IP',$this->plugin_name)?></label>
                        </div>
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_user_id" class="form-check-input" name="ays_survey_limit_users_by" value="user_id" <?php echo ($survey_limit_users_by == 'user_id') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_user_id"><?php echo __('User ID',$this->plugin_name)?></label>
                        </div>
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_cookie" class="form-check-input" name="ays_survey_limit_users_by" value="cookie" <?php echo ($survey_limit_users_by == 'cookie') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_cookie"><?php echo __('Cookie',$this->plugin_name)?></label>
                        </div>
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_ip_cookie" class="form-check-input" name="ays_survey_limit_users_by" value="ip_cookie" <?php echo ($survey_limit_users_by == 'ip_cookie') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_ip_cookie"><?php echo __('IP and Cookie',$this->plugin_name)?></label>
                        </div>
                    </div>
                </div>
                <hr/>
                <!-- Limitation count -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_max_pass_count">
                            <?php echo __('Attempts count',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the count of the attempts per user for taking the survey.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="number" class="ays-text-input" id="ays_survey_max_pass_count" name="ays_survey_max_pass_count" value="<?php echo $survey_max_pass_count; ?>"/>
                    </div>
                </div>
                <hr/>
                <!-- Limitation message -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_limitation_message">
                            <?php echo __('Message',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write the message for those survey takers who have already passed the survey under the given conditions.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9 ays-survey-box-for-mv">
                        <div class="ays-survey-message-vars-box">
                            <div class="ays-survey-message-vars-icon">
                                <div>
                                    <i class="ays_fa ays_fa_link"></i>
                                </div>
                                <div>
                                    <span><?php echo __("Message Variables" , "survey-maker"); ?></span>
                                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                                        echo htmlspecialchars( sprintf(
                                            __('Insert your preferred message variable into the editor by clicking.',"survey-maker"),
                                            '<strong>',
                                            '</strong>'
                                        ) );
                                    ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ays-survey-message-vars-data" data-tmce="ays_survey_limitation_message">
                                <?php $var_counter = 0; foreach($survey_limitation_message_vars as $var => $var_name): $var_counter++; ?>
                                    <label class="ays-survey-message-vars-each-data-label">
                                        <input type="radio" class="ays-survey-message-vars-each-data-checker" hidden id="ays_survey_message_var_count_<?php echo esc_attr($var_counter)?>" name="ays_survey_message_var_count">
                                        <div class="ays-survey-message-vars-each-data">
                                            <input type="hidden" class="ays-survey-message-vars-each-var" value="<?php echo esc_attr($var); ?>">
                                            <span><?php echo esc_attr($var_name); ?></span>
                                        </div>
                                    </label>              
                                <?php endforeach ?>
                            </div>
                        </div>

                        <?php
                        $content = $survey_limitation_message;
                        $editor_id = 'ays_survey_limitation_message';
                        $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_limitation_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr/>
                <!-- Limitation redirect url -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_redirect_url">
                            <?php echo __('Redirect URL',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Redirect your visitors to a different URL.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="ays_survey_redirect_url" id="ays_survey_redirect_url" class="ays-text-input" value="<?php echo $survey_redirect_url; ?>"/>
                    </div>
                </div>
                <hr/>
                <!-- Limitation redirect delay -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_redirection_delay">
                            <?php echo __('Redirect delay',$this->plugin_name)?>(s)
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the delay on the redirect in seconds. If you set it 0, the redirection will be disabled.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="number" name="ays_survey_redirection_delay" id="ays_survey_redirection_delay" class="ays-text-input" value="<?php echo $survey_redirect_delay; ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Maximum number of attempts per user -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_logged_users">
                <?php echo __('Access only to logged-in users',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, only logged-in users will be able to participate in the survey.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_logged_users" name="ays_survey_enable_logged_users" <?php echo $survey_only_for_user_role || $only_for_selected_user ? 'disabled' : ''; ?> value="on" <?php echo $survey_enable_logged_users || $survey_only_for_user_role || $only_for_selected_user  ? 'checked' : ''; ?> />
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_enable_logged_users || $survey_only_for_user_role || $only_for_selected_user) ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_logged_in_message">
                        <?php echo __('Message',$this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write a message for unauthorized users.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <?php
                    $content = $survey_logged_in_message;
                    $editor_id = 'ays_survey_logged_in_message';
                    $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_logged_in_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_show_login_form">
                        <?php echo __('Show Login form',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the login form to not logged-in users.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_show_login_form" name="ays_survey_show_login_form" value="on" <?php echo $survey_show_login_form ? 'checked' : ''; ?>/>
                </div>
            </div>
        </div>
    </div> <!-- Only for logged in users -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_restriction_pass">
                <?php echo __('Access only to selected user role(s)',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Make the survey available only for the user roles mentioned in the list. By enabling this option, the Only for logged-in users option will be enabled automatically.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_restriction_pass"
                   name="ays_survey_enable_restriction_pass"
                   value="on" <?php echo  $survey_only_for_user_role ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo $survey_only_for_user_role ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_users_roles">
                        <?php echo __('User role(s)',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the role(s) of the user. The option accepts multiple values.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <select name="ays_survey_users_roles[]" id="ays_survey_users_roles" multiple>
                        <?php
                        foreach ($ays_survey_users_roles as $key => $ays_survey_users_role_name) {
                            $selected_role = "";
                            if( isset( $survey_user_roles ) ){
                                if( is_array( $survey_user_roles ) ){
                                    if( in_array( $key, $survey_user_roles ) ){
                                        $selected_role = 'selected';
                                    }else{
                                        $selected_role = '';
                                    }
                                }else{
                                    if( $survey_user_roles == $key ){
                                        $selected_role = 'selected';
                                    }else{
                                        $selected_role = '';
                                    }
                                }
                            }
                            echo "<option value='" . $key . "' " . $selected_role . ">" . $ays_survey_users_role_name . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_restriction_pass_message">
                        <?php echo __('Message',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Message for the users who aren’t included in the above-mentioned list.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <?php
                    $content = $survey_user_roles_message;
                    $editor_id = 'ays_survey_restriction_pass_message';
                    $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_restriction_pass_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div>
        </div>
    </div> <!-- Only for selected user role -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_restriction_pass_users">
                <?php echo __('Access only to selected user(s)',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Make the survey available only for the users mentioned in the list. By enabling this option, the Only for logged-in users option will be enabled automatically.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_restriction_pass_users"
                   name="ays_survey_enable_restriction_pass_users"
                   value="on" <?php echo  $only_for_selected_user ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo $only_for_selected_user ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_users_search">
                        <?php echo __('User(s)',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the user(s). The option accepts multiple values.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <select id="ays_survey_users_search" name="ays_survey_users_search[]" multiple>
                        <?php
                        foreach ($ays_survey_users_search as $key => $ays_survey_user_search) {
                            $user_search = $ays_survey_user_search;
                            $selected_users = "";
                            if(isset($survey_user)){
                                if(is_array($survey_user)){
                                    if(in_array($user_search['ID'], $survey_user)){
                                        echo "<option value='" . $user_search['ID'] . "' selected>" . $user_search['display_name'] . "</option>";
                                    }else{
                                        echo "";
                                    }
                                }else{
                                    if($survey_user == $user_search['ID']){
                                        echo "<option value='" . $user_search['ID'] . "' selected>" . $user_search['display_name'] . "</option>";
                                    }else{
                                        echo "";
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_restriction_pass_users_message">
                        <?php echo __('Message',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show messages to those not included in the above-mentioned list.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <?php
                    $content = $survey_user_message;
                    $editor_id = 'ays_survey_restriction_pass_users_message';
                    $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_restriction_pass_users_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div>
        </div>
    </div> <!-- Access Only selected users -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_tackers_count">
                <?php echo __('Max count of takers', $this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose how many users can participate in the survey.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_tackers_count"
                   name="ays_survey_enable_tackers_count" value="on" <?php echo $enable_takers_count ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo  $enable_takers_count ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_tackers_count">
                        <?php echo __('Count',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Indicate the number of users who can participate in the survey.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <input type="number" name="ays_survey_tackers_count" id="ays_survey_tackers_count" class="ays-enable-timerl ays-text-input"
                           value="<?php echo $survey_takers_count; ?>">
                </div>
            </div>
        </div>
    </div> <!-- Limitation count of takers -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_password">
                <?php echo __('Password for passing survey', $this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set a custom password for the survey, or use auto-generated password(s).',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_password"
                   name="ays_survey_enable_password" value="on" <?php echo $enable_password ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo  $enable_password ? '' : 'display_none_not_important'; ?>">
            <div class="form-group">
                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_general_psw">
                    <input type="radio" id="ays_survey_general_psw" name='ays_survey_psw_type' value='general' <?php echo $ays_survey_passwords_type == 'general' ? 'checked' : '';?>>
                    <?php echo __('General', $this->plugin_name) ?>
                </label>
                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_generated_psw_radio">
                    <input type="radio" id="ays_survey_generated_psw_radio" name="ays_survey_psw_type" value="generated_password" <?php echo $ays_survey_passwords_type == 'generated_password' ? 'checked' : '';?>>
                    <?php echo __('Generated Passwords', $this->plugin_name) ?>
                </label>
            </div>
            <hr>
            <div class="form-group row <?php echo  $ays_survey_passwords_type == 'general' ? '' : 'display_none_not_important'; ?>" id="ays_survey_psw_content">
                <div class="col-sm-2">
                    <label for="ays_survey_password_survey">
                        <?php echo __('Password',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set a password for users to pass the survey.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <input type="text" name="ays_survey_password_survey" id="ays_survey_password_survey" class="ays-enable-timer ays-text-input" value="<?php echo $password_survey; ?>">
                </div>
            </div>
            <div class="form-group row <?php echo  $ays_survey_passwords_type == 'generated_password' ? '' : 'display_none_not_important'; ?>" id="ays_survey_generate_psw_content">
                <div class="col-sm-12">
                    <div class="form-group row">
                       <div class="col-sm-3">
                            <label for="ays_survey_password_count">
                                <?php echo __('Passwords Count',$this->plugin_name)?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Type down your preferred number of passwords and click "Create" so that the system generates them for you. Then, to copy the passwords, click on the Copy icon next to them. You can see the active and used passwords with columns.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9" style="display:flex;">
                            <input type="text" name="ays_survey_password_count" id="ays_survey_password_count" class="ays-text-input" value="" style="margin-right: 5px;">
                            <input type="button" id="ays_survey_generate_password_submit" name="ays_survey_generate_password_submit" value="<?php echo __( "Create", $this->plugin_name ); ?>" class="ays-survey-genreted-password-count button">
                        </div>
                    </div>
                    <div id="ays_survey_generated_password" class="form-group row">
                        <div class="col-sm-4">
                            <p>
                               <?php echo __('Created',$this->plugin_name)?>
                                <a class="ays-survey-gen-psw-copy-all" title="Click for copy" data-original-title="Click for copy" data-toggle = 'tooltip' id="ays_survey_gen_psw_copy_all">
                                    <i class="fa fa-clipboard" aria-hidden="true"></i>
                                </a>
                            </p>
                            <ul class="ays-survey-created" id="ays_survey_generated_psw">
                                <?php
                                    if(!empty($ays_survey_created_passwords)){
                                        $ays_survey_created_passwords_content = '';
                                        foreach ($ays_survey_created_passwords as $key => $created_password) {
                                            $ays_survey_created_passwords_content .= '<li>';
                                                $ays_survey_created_passwords_content .= '<span class="ays-survey-created-psw">'.$created_password.'</span>';
                                                $ays_survey_created_passwords_content .= '<a class="ays-survey-gen-psw-copy" title="Click for copy" data-original-title="Click for copy" data-toggle = "tooltip"><i class="fa fa-clipboard" aria-hidden="true"></i></a>';
                                                $ays_survey_created_passwords_content .= '<input type="hidden" name="ays_survey_generated_psw[]" value="'.$created_password.'" class="ays-survey-generated-psw">';
                                            $ays_survey_created_passwords_content .= '</li>';
                                        }
                                        echo $ays_survey_created_passwords_content;
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <p><?php echo __('Active',$this->plugin_name)?></p>
                            <ul class="ays-survey-active">
                                <?php
                                    $ays_survey_active_passwords_content = '';
                                    if(!empty($ays_survey_active_passwords)){
                                        foreach ($ays_survey_active_passwords as $key => $active_password){
                                            $ays_survey_active_passwords_content .= '<li>';
                                                $ays_survey_active_passwords_content .= '<span class="ays-survey-created-psw">'.$active_password.'</span>';
                                                $ays_survey_active_passwords_content .= '<input type="hidden" name="ays_survey_active_gen_psw[]" value="'.$active_password.'" class="ays-survey-active-gen-psw">';
                                            $ays_survey_active_passwords_content .= '</li>';
                                        }
                                    }
                                    else {
                                        $ays_survey_active_passwords_content = '<li class="ays_survey_active_password_empty_notice" style="font-size: 12px; font-weight: 400; font-style: italic; color: #8f8f8f; border-bottom: none;">'.__('If this field is left blank, the password option will not work.', $this->plugin_name).'</li>';
                                    }
                                    echo $ays_survey_active_passwords_content;
                                ?>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <p><?php echo __('Used',$this->plugin_name)?></p>
                            <ul class="ays_used">
                                <?php
                                    if(!empty($ays_survey_used_passwords)){
                                        $ays_survey_used_passwords_content = '';
                                        foreach ($ays_survey_used_passwords as $key => $used_password) {
                                            $ays_survey_used_passwords_content .= '<li>';
                                                $ays_survey_used_passwords_content .= '<span class="ays-survey-created-psw">'.$used_password.'</span>';
                                                $ays_survey_used_passwords_content .= '<input type="hidden" name="ays_survey_used_psw[]" value="'.$used_password.'" class="ays-survey-used-psw">';
                                                $ays_survey_used_passwords_content .= '<a href="'. admin_url('admin.php').'?page='.$this->plugin_name . '-each-submission&ays_survey_tab=poststuff&survey='.$id.'&sub_password='.urlencode($used_password).'" target="_blank" class="ays-survey-used-psw-redirect" style="font-size: 13px">';
                                                    $ays_survey_used_passwords_content .= '(Open Result)';
                                                $ays_survey_used_passwords_content .= '</a>';
                                            $ays_survey_used_passwords_content .= '</li>';
                                        }
                                        echo $ays_survey_used_passwords_content;
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_password_message">
                        <?php echo __('Message',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write the message for users who must fill in the password for taking this survey.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <?php
                    $content = $survey_password_message;
                    $editor_id = 'ays_survey_password_message';
                    $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_password_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div>
        </div>
    </div> <!-- Survey Password -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_limit_by_country">
                <?php echo __('Block by Country', $this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, the given survey will not be available in the selected countries.' , $this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_limit_by_country" name="ays_survey_enable_limit_by_country" value="on" <?php echo  ($enable_limit_by_country) ? 'checked': '';?> />
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($enable_limit_by_country) ? '' : 'display_none_not_important'; ?>">
            <select name="ays_survey_limit_country[]" class="ays-text-input ays-text-input-short" id="ays_survey_limit_country" multiple>
                <?php 
                    foreach ( $countries as $country_key => $country ) { 
                        $selected = '';
                        if(in_array($country_key, $limit_country)){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                ?>
                    <option value="<?php echo $country_key;  ?>" <?php echo $selected; ?>><?php echo $country[1]; ?></option>
                <?php } ?>
                
            </select>
        </div>
    </div> <!-- Limit by country -->
</div>
