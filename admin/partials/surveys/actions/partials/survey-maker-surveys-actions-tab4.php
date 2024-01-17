<div id="tab4" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab4') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Survey results settings',$this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_redirect_after_submit">
                <?php echo __('Redirect after submission',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Redirect to the custom URL after the survey taker submits the survey.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_redirect_after_submit" name="ays_survey_redirect_after_submit" value="on" <?php echo $survey_redirect_after_submit ? 'checked' : '' ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_redirect_after_submit ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_submit_redirect_url">
                        <?php echo __('Redirect URL',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the URL for redirection after the survey taker submits the survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="ays-text-input" id="ays_survey_submit_redirect_url" name="ays_survey_submit_redirect_url" value="<?php echo $survey_submit_redirect_url; ?>"/>
                    <span style="display:block;" class="ays_survey_small_hint_text">
                        <?php echo 
                            sprintf( __("Add '%s' phrase at the end of the url.%s Use %s line in your code, by replacing the 'if_false' with a value that will be returned in case something goes wrong.", $this->plugin_name),
                            "<strong class='ays-survey-shortcode-box' onClick='selectElementContents(this)' class='ays_help' data-toggle='tooltip' title='" . __('Copy', $this->plugin_name) . "'>(uniquecode)</strong>",
                            "<br>",
                            "<strong class='ays-survey-shortcode-box' onClick='selectElementContents(this)' class='ays_help' data-toggle='tooltip' title='" . __('Copy', $this->plugin_name) . "'>apply_filters('ays_survey_get_submission_results', 'if_false')</strong>"
                            ); 
                        ?>
                    </span>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_submit_redirect_delay">
                        <?php echo __('Redirect delay (sec)', $this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Redirect the survey takers to the custom URL in a short time after submitting the survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="number" class="ays-text-input" id="ays_survey_submit_redirect_delay" name="ays_survey_submit_redirect_delay" value="<?php echo $survey_submit_redirect_delay; ?>"/>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_submit_redirect_new_tab">
                        <?php echo __('Redirect to the new tab', $this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to redirect to another tab. If you are taking the survey for the first time you will not be able to move to a new tab without clicking on the Allow button.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_submit_redirect_new_tab" name="ays_survey_submit_redirect_new_tab" value="on" <?php echo $survey_submit_redirect_new_tab ? 'checked' : '' ?>/>
                </div>
            </div>
        </div>
    </div> <!-- Redirect after submit -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_exit_button">
                <?php echo __('Enable EXIT button',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The EXIT button will be displayed on the results page and must redirect the survey taker to the custom URL.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_exit_button" name="ays_survey_enable_exit_button" value="on" <?php echo $survey_enable_exit_button ? 'checked' : '' ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_enable_exit_button ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_exit_redirect_url">
                        <?php echo __('Redirect URL',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the URL for redirection. As soon as the survey taker hits the EXIT button, they will be directed to the specified URL.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="ays-text-input" id="ays_survey_exit_redirect_url" name="ays_survey_exit_redirect_url" value="<?php echo $survey_exit_redirect_url; ?>"/>
                </div>
            </div>
        </div>
    </div> <!-- Enable EXIT button -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_restart_button">
                <?php echo __('Enable restart button',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the restart button at the end of the survey for restarting the survey and pass it again.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_restart_button" name="ays_survey_enable_restart_button" value="on" <?php echo $survey_enable_restart_button ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Enable restart button -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_show_summary_after_submission">
                <?php echo __('Show results after submission',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the data presented in the Summary tab(Results page) after a respondent submits the survey.  It includes the total number of submissions and observation of the votes of every question displayed in charts.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_show_summary_after_submission" name="ays_survey_show_summary_after_submission" value="on" <?php echo $survey_show_summary_after_submission ? 'checked' : '' ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_show_summary_after_submission ? '' : 'display_none_not_important'; ?>">
            <div class="form-group">
                <label for="ays_survey_show_submission_summary" class="checkbox_ays form-check form-check-inline">
                    <input type="radio" id="ays_survey_show_submission_summary" class="" name="ays_survey_show_submission_results" value="summary" <?php echo $survey_show_submission_results == 'summary' ? 'checked' : '';?>>
                    <?php echo __('Summary',$this->plugin_name); ?>
                </label>
                <label for="ays_survey_show_submission_individual" class="checkbox_ays form-check form-check-inline">
                    <input type="radio" id="ays_survey_show_submission_individual" class="" name="ays_survey_show_submission_results" value="individual" <?php echo $survey_show_submission_results == 'individual' ? 'checked' : '';?>>
                    <?php echo __('Individual',$this->plugin_name); ?>
                </label>
            </div>
            <hr class="<?php echo $survey_show_submission_results == 'summary' ? '' : 'display_none_not_important'; ?>" >
            <div class="form-group row <?php echo $survey_show_submission_results == 'summary' ? '' : 'display_none_not_important'; ?> ays-survey-show-current-user-results">
                <div class="col-sm-5">
                    <label for="ays_survey_show_current_user_results">
                        <?php echo __('Show only current user results',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to show the users only their latest results.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7">
                    <input type="checkbox" class="" id="ays_survey_show_current_user_results" name="ays_survey_show_current_user_results" value="on" <?php echo $survey_show_current_user_results ? 'checked' : ''; ?>/>
                </div>
            </div>
        </div>
    </div> <!-- Show summary after submission -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Select survey loader',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the preferred loader icon which will appear while the system calculates the results. The loader icon will take the survey text color.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8 ays_toggle_loader_parent">
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="default" <?php echo ($survey_loader == 'default') ? 'checked' : ''; ?>>
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="circle" <?php echo ($survey_loader == 'circle') ? 'checked' : ''; ?>>
                <div class="lds-circle"></div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="dual_ring" <?php echo ($survey_loader == 'dual_ring') ? 'checked' : ''; ?>>
                <div class="lds-dual-ring"></div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="facebook" <?php echo ($survey_loader == 'facebook') ? 'checked' : ''; ?>>
                <div class="lds-facebook"><div></div><div></div><div></div></div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="hourglass" <?php echo ($survey_loader == 'hourglass') ? 'checked' : ''; ?>>
                <div class="lds-hourglass"></div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="ripple" <?php echo ($survey_loader == 'ripple') ? 'checked' : ''; ?>>
                <div class="lds-ripple"><div></div><div></div></div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" type="radio" value="snake" <?php echo ($survey_loader == 'snake') ? 'checked' : ''; ?>>                
                <div class="ays-survey-loader-snake"><div></div><div></div><div></div><div></div><div></div><div></div></div>
            </label>
            <hr/>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" data-flag="true" data-type="text" type="radio" value="text" <?php echo ($survey_loader == 'text') ? 'checked' : ''; ?>>
                <div class="ays_survey_loader_text">
                    <?php echo __( "Text" , $this->plugin_name ); ?>
                </div>
                <div class="ays_toggle_loader_target <?php echo ($survey_loader == 'text') ? '' : 'display_none_not_important' ?>" data-type="text">
                    <input type="text" class="ays-text-input" id="ays_survey_loader_text_value" name="ays_survey_loader_text_value" value="<?php echo $survey_loader_text; ?>">
                </div>
            </label>
            <label class="ays_survey_loader">
                <input name="ays_survey_loader" class="ays_toggle_loader_radio" data-flag="true" data-type="gif" type="radio" value="custom_gif" <?php echo ($survey_loader == 'custom_gif') ? 'checked' : ''; ?>>
                <div class="ays_survey_loader_custom_gif">
                    <?php echo __( "Gif" , $this->plugin_name ); ?>
                </div>
                <div class="ays_toggle_loader_target ays-survey-image-wrap <?php echo ($survey_loader == 'custom_gif') ? '' : 'display_none_not_important' ?>" data-type="gif">
                    <a href="javascript:void(0)" style="<?php echo ($survey_loader_gif == '') ? 'display:inline-block' : 'display:none'; ?>" class="button add_survey_loader_custom_gif"><?php echo __('Add Gif', $this->plugin_name); ?></a>
                    <input type="hidden" class="ays-survey-image-path" id="ays_survey_loader_custom_gif" name="ays_survey_loader_custom_gif" value="<?php echo $survey_loader_gif; ?>"/>
                    <div class="ays-survey-image-container ays-survey-loader-custom-gif-container" style="<?php echo ($survey_loader_gif == '') ? 'display:none' : 'display:block'; ?>">
                        <div class="ays-edit-survey-loader-custom-gif">
                            <i class="ays_fa ays_fa_pencil_square_o"></i>
                        </div>
                        <div class="ays-survey-image-wrapper-delete-wrap">
                            <div role="button" class="ays-survey-image-wrapper-delete-cont removeImage">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close-grey.svg">
                            </div>
                        </div>
                        <div class="ays-survey-loader-gif-info">
                            <img  src="<?php echo $survey_loader_gif; ?>" class="ays_survey_img_loader_custom_gif"/>
                            <span class="ays-survey-loader-gif-error-message"></span>
                        </div>
                    </div>
                </div>
                <div class="ays_toggle_loader_target ays_gif_loader_width_container <?php echo ($survey_loader == 'custom_gif') ? 'ays_survey_display_flex' : 'display_none_not_important'; ?>" data-type="gif" style="margin: 10px;">
                    <div>
                        <label for='ays_survey_loader_custom_gif_width'>
                            <?php echo __('Width (px)', $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Custom Gif width in pixels. It accepts only numeric values.',$this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div style="margin-left: 5px;">
                        <input type="number" class="ays-text-input" id='ays_survey_loader_custom_gif_width' name='ays_survey_loader_custom_gif_width' value="<?php echo ( $survey_loader_gif_width ); ?>"/>
                    </div>
                </div>
            </label>
        </div>
    </div> <!-- Select survey loader -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_social_buttons">
                <?php echo __('Show the Social buttons',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display social buttons for sharing survey page URL. LinkedIn, Facebook, Twitter, VK.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_social_buttons" name="ays_survey_social_buttons" value="on" <?php echo ( $survey_social_buttons ); ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_social_buttons != '' ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_linkedin_share_button">
                        <i class="ays_fa ays_fa_linkedin_square"></i>
                        <?php echo __('Linkedin button',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display LinkedIn social button so that the users can share the page on which your survey is posted.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_linkedin_share_button" name="ays_survey_enable_linkedin_share_button" value="on" <?php echo ( $survey_social_button_ln ); ?>/>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_facebook_share_button">
                        <i class="ays_fa ays_fa_facebook_square"></i>
                        <?php echo __('Facebook button',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display Facebook social button so that the users can share the page on which your survey is posted.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_facebook_share_button" name="ays_survey_enable_facebook_share_button" value="on" <?php echo ( $survey_social_button_fb ); ?>/>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_twitter_share_button">
                        <i class="ays_fa ays_fa_twitter_square"></i>
                        <?php echo __('Twitter button',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display Twitter social button so that the users can share the page on which your survey is posted.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_twitter_share_button" name="ays_survey_enable_twitter_share_button" value="on" <?php echo ( $survey_social_button_tr ); ?>/>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_vkontakte_share_button">
                        <i class="ays_fa ays_fa_vk"></i>
                        <?php echo __('VKontakte button',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display VKontakte social button so that the users can share the page on which your survey is posted.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_vkontakte_share_button" name="ays_survey_enable_vkontakte_share_button" value="on" <?php echo ( $survey_social_button_vk ); ?>/>
                </div>
            </div>
        </div>
    </div> <!-- Show the Social buttons -->
    <hr/>
    <div class="form-group row ays-survey-result-message-vars">
        <div class="col-sm-4">
            <label for="ays_survey_final_result_text">
                <?php echo __('Thank you message',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                    echo htmlspecialchars( sprintf(
                        __('Write down a thank you message to be displayed after survey submission. %sAdd Media%s to the message if you wish.',$this->plugin_name),
                        '<strong>',
                        '</strong>'
                    ) );
                ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
            <p class="ays_survey_small_hint_text_for_message_variables">
                <span><?php echo __( "To see all Message Variables " , $this->plugin_name ); ?></span>
                <a href="?page=survey-maker-settings&ays_survey_tab=tab3" target="_blank"><?php echo __( "click here" , $this->plugin_name ); ?></a>
            </p>
        </div>
        <div class="col-sm-8 ays-survey-box-for-mv">
            <div class="ays-survey-message-vars-box">
                <div class="ays-survey-message-vars-icon">
                    <div>
                        <i class="ays_fa ays_fa_link"></i>
                    </div>
                    <div>
                        <span><?php echo __("Message Variables" , $this->plugin_name); ?></span>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                            echo htmlspecialchars( sprintf(
                                __('Insert your preferred message variable into the editor by pressing double-click.',$this->plugin_name),
                                '<strong>',
                                '</strong>'
                            ) );
                        ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </div>
                </div>
                <div class="ays-survey-message-vars-data" data-tmce="ays_survey_final_result_text">
                    <?php $var_counter = 0; foreach($survey_message_vars as $var => $var_name): $var_counter++; ?>
                        <label class="ays-survey-message-vars-each-data-label">
                            <input type="radio" class="ays-survey-message-vars-each-data-checker" hidden id="ays_survey_message_var_count_<?php echo $var_counter?>" name="ays_survey_message_var_count">
                            <div class="ays-survey-message-vars-each-data">
                                <input type="hidden" class="ays-survey-message-vars-each-var" value="<?php echo $var; ?>">
                                <span><?php echo $var_name; ?></span>
                            </div>
                        </label>              
                    <?php endforeach ?>
                </div>
            </div>
            <?php
            $content = $ays_survey_final_result_text;
            $editor_id = 'ays_survey_final_result_text';
            $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_final_result_text', 'editor_class' => 'ays-textarea ays-survey-text-area-for-mv', 'media_elements' => false);
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
    </div> <!-- Thank you message -->
    <hr/>
    <p class="ays-subtitle"><?php echo __('Dashboard results settings',"survey-maker")?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_questions_as_html">
                <?php echo __('Show question title as HTML',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to show survey question titles in HTML format on the submissions page. Otherwise, it will be shown as plain text.',"survey-maker")?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_show_questions_as_html" name="ays_survey_show_questions_as_html" value="on" <?php echo $survey_show_questions_as_html ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Show question title as HTML -->
</div>