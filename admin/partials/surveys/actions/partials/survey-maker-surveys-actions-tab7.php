<div id="tab7" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab7') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('E-mail settings', $this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_mail_user">
                <?php echo __('Send email to user',$this->plugin_name)?>
                <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Activate the option of sending emails to your users after taking the survey.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_survey_enable_mail_user" name="ays_survey_enable_mail_user" value="on" <?php echo ($survey_enable_mail_user) ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_enable_mail_user) ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
            <div class="col-sm-12">
                    <label class="form-check form-check-inline checkbox_ays">
                        <input type="radio" class="ays-enable-timer1 ays_survey_send_mail_type" name="ays_survey_send_mail_type" value="custom" <?php echo ($survey_send_mail_type == 'custom' || !$is_sendgrid_enabled ) ? 'checked' : '' ?>/>
                        <span><?php echo __( "Custom", $this->plugin_name ); ?></span>
                    </label>
                    <label class="form-check form-check-inline checkbox_ays" style="<?php echo ($is_sendgrid_enabled) ? '' : 'opacity: 0.6;'; ?>">
                        <input type="radio" class="ays-enable-timer1 ays_survey_send_mail_type" name="ays_survey_send_mail_type" value="sendgrid" <?php echo ($is_sendgrid_enabled) ? '' : 'disabled'; ?> <?php echo ($survey_send_mail_type == 'sendgrid' && $is_sendgrid_enabled) ? 'checked' : ''; ?>/>
                        <span><?php echo __( "SendGrid", $this->plugin_name ); ?></span>
                        <img style="width: 25px; height: auto" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/integrations/sendgrid_logo.png" alt="">
                    </label>
                    <a class="ays_help" data-toggle="tooltip" data-html="true" title="
                        <?php 
                            echo htmlspecialchars( sprintf(
                                __("%sThere are two ways of sending a message to the users. %sCustom%sWrite your own message inside the textarea. %sSendGrid%sChoose your template from the selection and the system will send it to the users.%s", $this->plugin_name ),
                                '<p style="text-indent:10px;margin:0;">',
                                '</p><p style="text-indent:10px;margin:0;"><strong>',
                                ': </strong>',
                                '</p><p style="text-indent:10px;margin:0;"><strong>',
                                ': </strong>',
                                '</p>'
                            ) );
                        ?>">
                        <i class="ays_fa ays_fa_info_circle"></i>
                    </a>
                    <?php 
                        $blockquote_html = '';
                        if( ! $is_sendgrid_enabled ){
                            $blockquote_html .= sprintf(
                                __("%s SendGrid is not confirmed. To confirm SendGrid, go to the %s General settings %s page.%s", $this->plugin_name ),
                                '<blockquote class="" style="display: inline; margin-left: 10px;">',
                                ' <a href="?page=survey-maker-settings&ays_survey_tab=tab2" target="_blank">',
                                '</a> ',
                                '</blockquote>'
                            );
                        }
                        echo $blockquote_html;
                    ?>
                </div>
            </div>
            <hr>
            <div class="ays_survey_send_mail_type_custom <?php echo ($survey_send_mail_type == 'custom' || ! $is_sendgrid_enabled ) ? '' : 'display_none_not_important'; ?>">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_mail_message">
                            <?php echo __('Email message',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                                echo htmlspecialchars( sprintf(
                                    __('Write the message to send it out to your survey takers via email. You can use %sMessage Variables%s as well.',$this->plugin_name),
                                    '<strong>',
                                    '</strong>'
                                ) );
                            ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                        <?php if( Survey_Maker_Data::survey_maker_capabilities_for_editing() ): ?>
                        <p class="ays_survey_small_hint_text_for_message_variables">
                            <span><?php echo __( "To see all Message Variables " , $this->plugin_name ); ?></span>
                            <a href="?page=survey-maker-settings&ays_survey_tab=tab3" target="_blank"><?php echo __( "click here" , $this->plugin_name ); ?></a>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = $survey_mail_message;
                        $editor_id = 'ays_survey_mail_message';
                        $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_mail_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>                
                <hr>
                <div class='row'>
                    <div class="col-sm-3">
                        <label for="ays_survey_summary_single_email_to_users">
                            <?php echo __('Send user summary', $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option the user will receive its own survey summary.',$this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                        
                    </div>
                    <div class="col-sm-9">
                        <input type="checkbox" id='ays_survey_summary_single_email_to_users' name="ays_survey_summary_single_email_to_users" value="on" <?php echo $survey_summary_single_email_to_users ? 'checked' : '' ;?>>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_test_email">
                            <?php echo __('Send email for testing',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                                echo htmlspecialchars( sprintf(
                                    __('Provide an email and click on the %s Send %s button to see what the message looks like. Note that you need to write a message on the %sEmail message%s field beforehand. Take into account that the message variables will not work while testing.',$this->plugin_name),
                                    '<strong>',
                                    '</strong>',
                                    '<strong>',
                                    '</strong>'
                                ) );
                            ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div class="ays_send_test">
                            <input type="text" id="ays_survey_test_email" name="ays_survey_test_email" class="ays-text-input" value="<?php echo $ays_super_admin_email; ?>">
                            <input type="hidden" name="ays_survey_id_for_test" value="<?php echo $id; ?>">
                            <a href="javascript:void(0)" class="ays_survey_test_mail_btn button button-primary"><?php echo __( "Send", $this->plugin_name ); ?></a>
                            <span id="ays_survey_test_delivered_message" data-src="<?php echo SURVEY_MAKER_ADMIN_URL . "/images/loaders/loading.gif" ?>" style="display: none;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ays_survey_send_mail_type_sendgrid <?php echo ($survey_send_mail_type == 'sendgrid' && $is_sendgrid_enabled ) ? '' : 'display_none_not_important'; ?>">
                <div class="form-group row" style="margin:0;">
                    <div class="col-sm-12 question_bank_by_category_div">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_sendgrid_field_subject">
                                    <?php echo __('Template', $this->plugin_name); ?>
                                    <a class="ays_help" data-toggle="tooltip" data-placement="top" title="<?php echo __('Choose the template from the dropdown list which will be sent to the user. Create templates from your SendGrid account beforehand.', $this->plugin_name); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="ays-text-input ays-text-input-short" name="ays_survey_sendgrid_template_id" >
                                    <option value="" disabled selected><?php echo __( "Select template", $this->plugin_name ); ?></option>
                                    <?php
                                    if (! empty( $sendgrid_templates ) ) {
                                        $templates_option = '';
                                        $templates =  (isset($sendgrid_templates['templates']) && !empty($sendgrid_templates['templates'])) ? $sendgrid_templates['templates'] : array();

                                        foreach($templates as $key => $temp){
                                            $select_template = '';
                                            if($survey_sendgrid_template_id == $temp['id']){
                                                $select_template = "selected";
                                            }   
                                            $templates_option .= "<option value=".$temp['id']." ".$select_template.">".$temp['name']."</option>";
                                            
                                        }

                                        echo $templates_option;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> <!-- Select template -->
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <blockquote>
                                    <?php 
                                        echo sprintf( 
                                            __( "Note that you must give the %sSender email%s and %sSender name%s fields of the SendGrid the same value as the %sFrom Email%s and %sFrom Name%s fields of the below options correspondingly.", $this->plugin_name ), 
                                            "<strong>",
                                            "</strong>",
                                            "<strong>",
                                            "</strong>",
                                            "<strong>",
                                            "</strong>",
                                            "<strong>",
                                            "</strong>"
                                        ); 
                                    ?><br>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Send Mail To User -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_mail_admin">
                <?php echo __('Send email to admin',$this->plugin_name)?>
                <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Activate this option so that the survey data can be sent to the super admin of your WordPress site.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_survey_enable_mail_admin" name="ays_survey_enable_mail_admin" value="on" <?php echo ($survey_enable_mail_admin) ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_enable_mail_admin) ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_send_mail_to_site_admin">
                        <?php echo __('Admin', $this->plugin_name)?>
                        <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Send the survey results to the super admin.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timerl" id="ays_survey_send_mail_to_site_admin" name="ays_survey_send_mail_to_site_admin" value="on" <?php echo ($survey_send_mail_to_site_admin) ? 'checked' : ''; ?>/>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="ays-text-input ays-enable-timerl" placeholder="<?php echo $ays_super_admin_email; ?>" disabled />
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_additional_emails">
                        <?php echo __('Additional emails',$this->plugin_name)?>
                        <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide additional email addresses that will receive the survey results. List the emails by separating them with commas.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" id="ays_survey_additional_emails" name="ays_survey_additional_emails" value="<?php echo $survey_additional_emails; ?>" placeholder="example1@gmail.com, example2@gmail.com, ..."/>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_mail_message_admin">
                        <?php echo __('Email message',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                            echo htmlspecialchars( sprintf(
                                __('Provide a text message to be sent to the super admin and/or the provided additional emails. %sMessage Variables%s will be of help.',$this->plugin_name),
                                '<strong>',
                                '</strong>'
                            ) );
                        ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <?php if( Survey_Maker_Data::survey_maker_capabilities_for_editing() ): ?>
                    <p class="ays_survey_small_hint_text_for_message_variables">
                        <span><?php echo __( "To see all Message Variables " , $this->plugin_name ); ?></span>
                        <a href="?page=survey-maker-settings&ays_survey_tab=tab3" target="_blank"><?php echo __( "click here" , $this->plugin_name ); ?></a>
                    </p>
                    <?php endif; ?>
                </div>
                <div class="col-sm-9">
                    <?php
                    $content = $survey_mail_message_admin;
                    $editor_id = 'ays_survey_mail_message_admin';
                    $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_mail_message_admin', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_send_submission_report">
                        <?php echo __('Send submission report',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                            echo __('Send detailed submissions of the completed survey to the admin and/or the provided additional emails.', $this->plugin_name );
                        ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="checkbox" class="" id="ays_survey_send_submission_report" name="ays_survey_send_submission_report" value="on" <?php echo ($survey_send_submission_report) ? 'checked' : ''; ?>/>
                </div>
            </div>
        </div>
    </div> <!-- Send mail to admin -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Email configuration',$this->plugin_name)?>
                <a  class="ays_help" data-toggle="tooltip" title="<?php echo __('Set up the attributes of the sending email.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8 ays_divider_left">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_email_configuration_from_email">
                        <?php echo __('From email',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                            echo htmlspecialchars( sprintf(
                                __('Specify the email address from which the results will be sent. If you leave the field blank, the sending email address will take the default value — %ssurvey_maker@{your_site_url}%s.',$this->plugin_name),
                                '<em>',
                                '</em>'
                            ) );
                        ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" id="ays_survey_email_configuration_from_email" name="ays_survey_email_configuration_from_email" value="<?php echo $survey_email_configuration_from_email; ?>"/>
                </div>
            </div> <!-- From email -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_email_configuration_from_name">
                        <?php echo __('From name',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                            echo htmlspecialchars( sprintf(
                                __("Specify the name that will be displayed as the sender of the results. If you don't enter any name, it will be %sSurvey Maker%s.",$this->plugin_name),
                                '<em>',
                                '</em>'
                            ) );
                        ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" id="ays_survey_email_configuration_from_names" name="ays_survey_email_configuration_from_name" value="<?php echo $survey_email_configuration_from_name; ?>"/>
                </div>
            </div><!-- From name -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_email_configuration_from_subject">
                        <?php echo __('Subject',$this->plugin_name)?>
                        <a  class="ays_help" data-toggle="tooltip" title="<?php echo __("Fill in the subject field of the message. If you don't, it will take the survey title.",$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" id="ays_survey_email_configuration_from_subject" name="ays_survey_email_configuration_from_subject" value="<?php echo $survey_email_configuration_from_subject; ?>"/>
                </div>
            </div> <!-- Subject -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_email_configuration_replyto_email">
                        <?php echo __('Reply to email',$this->plugin_name)?>
                        <a  class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify to which email the survey taker can reply. If you leave the field blank, the email address won't be specified.",$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" id="ays_survey_email_configuration_replyto_email" name="ays_survey_email_configuration_replyto_email" value="<?php echo $survey_email_configuration_replyto_email; ?>"/>
                </div>
            </div> <!-- Reply to email -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_email_configuration_replyto_name">
                        <?php echo __('Reply to name',$this->plugin_name)?>
                        <a  class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the name of the email address to which the survey taker can reply. If you leave the field blank, the name won't be specified.",$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" id="ays_survey_email_configuration_replyto_name" name="ays_survey_email_configuration_replyto_name" value="<?php echo $survey_email_configuration_replyto_name; ?>"/>
                </div>
            </div> <!-- Reply to name -->
        </div>
    </div> <!-- Email Configuration -->
    <?php if( $id != null ): ?>
    <hr>
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
                    <label for="">
                        <?php echo __('Send summary', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Send a detailed summary of the survey to the selected people. Click on the Send Now button and the summary will be sent at that given moment combined with data collected before it.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ays_divider_left">
                    <div class='form-grpoup'>
                        <div class='row'>
                            <div class="col-sm-3">
                                <label for="ays_survey_summary_emails_to_admin">
                                    <?php echo __('To admin', $this->plugin_name); ?>  
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Send a detailed summary of the survey to the registered email of the super admin of your WordPress website.',$this->plugin_name); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="checkbox" id='ays_survey_summary_emails_to_admin' name="ays_survey_summary_emails_to_admin" value="on">
                            </div>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class="col-sm-3">
                                <label for="ays_survey_summary_emails_to_users">
                                    <?php echo __('To users', $this->plugin_name); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Send a detailed summary of the survey to the survey participants.',$this->plugin_name); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                                
                            </div>
                            <div class="col-sm-9">
                                <input type="checkbox" id='ays_survey_summary_emails_to_users' name="ays_survey_summary_emails_to_users" value="on">
                            </div>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class="col-sm-3">
                                <label for="ays_survey_summary_emails_to_admins">
                                    <?php echo __('To additional emails', $this->plugin_name); ?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide additional email addresses. These email accounts will receive a detailed summary of the survey. List the emails by separating them with commas.', $this->plugin_name); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_summary_emails_to_admins" name="ays_survey_summary_emails_to_admins" value="<?php echo $survey_send_summary_email_to_additional_users; ?>">
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class='form-group' style="display:flex;align-items: center;">
                        <input type="hidden" name="ays_survey_id_summary_mail" id="ays_survey_id_summary_mail" value="<?php echo $id; ?>">
                        <a href="javascript:void(0)" class="ays_survey_summary_mail_btn button button-primary"><?php echo __( "Send now", $this->plugin_name ); ?></a>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . "/images/loaders/loading.gif"; ?>" alt="" style="display: none;margin-left: 15px;width:20px;height:20px" class="ays_survey_summary_delivered_message_loader" >
                        <span id="ays_survey_summary_delivered_message" style="display: none;margin-left: 15px;"></span>
                    </div>
                </div> 
            </div>
        </div> 
    </div><!-- Send Summary -->
    <?php endif; ?>
</div>
