<div id="tab3" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab3') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Survey Settings',$this->plugin_name)?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays-category">
                <?php echo __('Survey categories', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                    echo htmlspecialchars( sprintf(
                        __('Choose the category/categories your survey belongs to. To create a category, go to the %sSurvey Categories%s page.',$this->plugin_name),
                        '<strong>',
                        '</strong>'
                    ) );
                ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select id="ays-category" name="<?php echo $html_name_prefix; ?>category_ids[]" multiple>
                <option></option>
                <?php
                foreach ($categories as $key => $category) {
                    $selected = in_array( $category['id'], $category_ids ) ? "selected" : "";
                    if( empty( $category_ids ) ){
                        if ( intval( $category['id'] ) == 1 ) {
                            $selected = 'selected';
                        }
                    }
                    echo '<option value="' . $category["id"] . '" ' . $selected . '>' . stripslashes( $category['title'] ) . '</option>';
                }
                ?>
            </select>
        </div>
    </div> <!-- Survey Category -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays-status">
                <?php echo __('Survey status', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                    echo htmlspecialchars( sprintf(
                        __("Decide whether your survey is active or not. If you choose %sDraft%s, the survey won't be shown anywhere on your website (you don't need to remove shortcodes).", $this->plugin_name),
                        '<strong>',
                        '</strong>'
                    ) );
                ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select id="ays-status" name="<?php echo $html_name_prefix; ?>status">
                <option></option>
                <option <?php selected( $status, 'published' ); ?> value="published"><?php echo __( "Published", $this->plugin_name ); ?></option>
                <option <?php selected( $status, 'draft' ); ?> value="draft"><?php echo __( "Draft", $this->plugin_name ); ?></option>
            </select>
        </div>
    </div> <!-- Survey Status -->
    <hr/>
        <?php if($post_id === null): ?>
        <div class="form-group row ays_toggle_parent">
            <div class="col-sm-4">
                <label for="ays_add_post_for_survey">
                    <?php echo __('Create post for survey',$this->plugin_name)?>
                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('A new WordPress post will be created automatically and will include the shortcode of this survey. This function will be executed only once. You can find this post on Posts page, which will have the same title as the survey. The image of the survey will be the featured image of the post.',$this->plugin_name)?>">
                        <i class="ays_fa ays_fa_info_circle"></i>
                    </a>
                </label>
            </div>
            <div class="col-sm-1">
                <input type="checkbox" id="ays_add_post_for_survey" name="ays_add_post_for_survey" value="on" class="ays-enable-timer1 ays_toggle_checkbox"/>
            </div>
            <div class="col-sm-7 ays_toggle_target ays_divider_left display_none_not_important">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_add_postcat_for_survey">
                            <?php echo __('Choose post categories',$this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose one or several categories. These categories are WordPress default post categories. There is no connection with survey categories.',$this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <select name="ays_add_postcat_for_survey[]"
                                    id="ays_add_postcat_for_survey"
                                    class="ays_postcat_for_survey"
                                    multiple>
                                <?php
                                    foreach ($cat_list as $cat) {
                                        echo "<option value='" . $cat->cat_ID . "' >" . esc_attr($cat->name) . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Create WP Post -->
        <hr/>
        <?php else: ?>
        <div class="form-group row">
            <div class="col-sm-4">
                <label for="ays_add_post_for_survey">
                    <?php echo __('WP post', $this->plugin_name); ?>
                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Via these two links you can see the connected post in front end and make changes in the dashboard.',$this->plugin_name); ?>">
                        <i class="ays_fa ays_fa_info_circle"></i>
                    </a>
                </label>
            </div>
            <div class="col-sm-8">
                <div class="row" style="margin-left: 0;">
                    <div style="margin-right: 10px;">
                        <a class="button" href="<?php echo $ays_survey_view_post_url; ?>" target="_blank"><?php echo __( "View Post", $this->plugin_name ); ?> <i class="ays_fa ays_fa_external_link"></i></a>
                    </div>
                    <div>
                        <a class="button" href="<?php echo $ays_survey_edit_post_url; ?>" target="_blank"><?php echo __( "Edit Post", $this->plugin_name ); ?> <i class="ays_fa ays_fa_external_link"></i></a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="ays_post_id_for_survey" value="<?php echo $post_id; ?>">
        </div> <!-- WP Post -->
        <hr>
    <?php endif; ?>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_title">
                <?php echo __('Show survey title',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the name of the survey on the front-end.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
                <input type="checkbox" id="ays_survey_show_title" name="ays_survey_show_title" value="on" <?php echo $survey_show_title ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Show survey title -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_section_header">
                <?php echo __('Show section header info',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to show the title and description of the section on the front-end.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" id="ays_survey_show_section_header" name="ays_survey_show_section_header" value="on" <?php echo $survey_show_section_header ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Show section header info -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_leave_page">
                <?php echo __('Enable confirmation box for leaving the page',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show a popup box whenever the survey taker wants to refresh or leave the page while taking the survey.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_leave_page" name="ays_survey_enable_leave_page" value="on" <?php echo ($survey_enable_leave_page) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable confirmation box for leaving the page -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_full_screen_mode">
                <?php echo __('Enable full-screen mode',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow the survey to enter full-screen mode by pressing the icon located in the top-right corner of the survey container.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_full_screen_mode" name="ays_survey_enable_full_screen_mode" value="on" <?php echo $survey_full_screen ? 'checked' : '';?>>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_full_screen ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for='ays_survey_full_screen_button_color'>
                        <?php echo __('Full screen button color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the full screen button.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ">
                    <input type="text" class="ays-text-input" id='ays_survey_full_screen_button_color' name='ays_survey_full_screen_button_color' data-alpha="true" value="<?php echo $survey_full_screen_button_color; ?>"/>
                </div>
            </div>
        </div>
    </div> <!-- Open Full Screen Mode -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_progres_bar">
                <?php echo __('Enable live progress bar',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the current state of the user passing the survey. It will be shown at the bottom of the survey container.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_progres_bar" name="ays_survey_enable_progres_bar" value="on" <?php echo $survey_enable_progress_bar ? "checked" : ''; ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_enable_progress_bar == "checked" ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_hide_section_pagination_text">
                        <?php echo __('Hide the pagination text',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to hide the pagination text.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox"
                   class="ays-enable-timer1"
                   id="ays_survey_hide_section_pagination_text"
                   name="ays_survey_hide_section_pagination_text"
                   value="on"
                   <?php echo $survey_hide_section_pagination_text; ?>
                   />
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_pagination_positioning">
                        <?php echo __('Pagination items positioning',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox to change the position of the pagination items.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <select class="ays-text-input ays-text-input-short" name="ays_survey_pagination_positioning">
                        <option <?php echo $survey_pagination_positioning == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", $this->plugin_name); ?></option>
                        <option <?php echo $survey_pagination_positioning == "reverse" ? "selected" : ""; ?> value="reverse"><?php echo __( "Reverse", $this->plugin_name); ?></option>
                        <option <?php echo $survey_pagination_positioning == "column" ? "selected" : ""; ?> value="column"><?php echo __( "Column", $this->plugin_name); ?></option>
                        <option <?php echo $survey_pagination_positioning == "column_reverse" ? "selected" : ""; ?> value="column_reverse"><?php echo __( "Column Reverse", $this->plugin_name); ?></option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_hide_section_bar">
                        <?php echo __('Hide the bar',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to hide the bar.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox"
                   class="ays-enable-timer1"
                   id="ays_survey_hide_section_bar"
                   name="ays_survey_hide_section_bar"
                   value="on"
                   <?php echo $survey_hide_section_bar; ?>
                   />
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_progress_bar_text">
                        <?php echo __('Progress bar text',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text of the progress bar.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="ays-text-input ays-text-input-short" id="ays_survey_progress_bar_text" name="ays_survey_progress_bar_text" value="<?php echo $survey_progress_bar_text; ?>">
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for='ays_survey_pagination_text_color'>
                        <?php echo __('Progress bar text color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the pagination text.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ">
                    <input type="text" class="ays-text-input" id='ays_survey_pagination_text_color' name='ays_survey_pagination_text_color' data-alpha="true" value="<?php echo $survey_pagination_text_color; ?>"/>
                </div>
            </div> <!-- Progress bar text color' -->
        </div>
    </div> <!-- Live progres bar -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_clear_answer">
                <?php echo __('Enable clear answer button',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow the survey taker to clear the chosen answer.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_clear_answer" name="ays_survey_enable_clear_answer" value="on" <?php echo ($survey_enable_clear_answer) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Enable clear answer button -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_previous_button">
                <?php echo __('Enable previous button', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Add a previous button that will let the users go back to the previous sections.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_previous_button" name="ays_survey_enable_previous_button" value="on" <?php echo ($survey_enable_previous_button) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Enable previous button -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_survey_start_loader">
                <?php echo __('Enable survey loader', "survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to display a loader until the survey container is loaded.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_survey_start_loader" name="ays_survey_enable_survey_start_loader" value="on" <?php echo ($survey_enable_survey_start_loader) ? 'checked' : '' ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target" <?php echo ($survey_enable_survey_start_loader) ? '' : "style='display: none;'" ?>>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="default" <?php echo ($survey_before_start_loader == 'default') ? 'checked' : ''; ?>>
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </label>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="circle" <?php echo ($survey_before_start_loader == 'circle') ? 'checked' : ''; ?>>
                <div class="lds-circle"></div>
            </label>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="dual_ring" <?php echo ($survey_before_start_loader == 'dual_ring') ? 'checked' : ''; ?>>
                <div class="lds-dual-ring"></div>
            </label>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="facebook" <?php echo ($survey_before_start_loader == 'facebook') ? 'checked' : ''; ?>>
                <div class="lds-facebook"><div></div><div></div><div></div></div>
            </label>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="hourglass" <?php echo ($survey_before_start_loader == 'hourglass') ? 'checked' : ''; ?>>
                <div class="lds-hourglass"></div>
            </label>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="ripple" <?php echo ($survey_before_start_loader == 'ripple') ? 'checked' : ''; ?>>
                <div class="lds-ripple"><div></div><div></div></div>
            </label>
            <label class="ays_survey_before_start_loader">
                <input name="ays_survey_before_start_loader" class="ays_toggle_loader_radio" type="radio" value="snake" <?php echo ($survey_before_start_loader == 'snake') ? 'checked' : ''; ?>>                
                <div class="ays-survey-loader-snake"><div></div><div></div><div></div><div></div><div></div><div></div></div>
            </label>
        </div>
    </div> <!-- Enable survey start loader -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_allow_html_in_section_description">
                <?php echo __('Allow HTML in section description',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow implementing HTML coding in section description boxes.', $this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_allow_html_in_section_description" name="ays_survey_allow_html_in_section_description" value="on" <?php echo ($survey_allow_html_in_section_description) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Allow HTML in section description -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Change current survey creation date',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Change the survey creation date to your preferred date.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <div class="input-group mb-3">
                <input type="text" class="ays-text-input ays-text-input-short ays-survey-date-create" id="ays_survey_change_creation_date" name="ays_survey_change_creation_date" value="<?php echo $date_created; ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                <div class="input-group-append">
                    <label for="ays_survey_change_creation_date" class="input-group-text">
                        <span><i class="ays_fa ays_fa_calendar"></i></span>
                    </label>
                </div>
            </div>
        </div>
    </div> <!-- Change current survey creation date -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_copy_protection">
                <?php echo __('Enable copy protection',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Disable copy functionality in the survey. The user will not be able to copy the text or right-click on it.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_copy_protection"
                   name="ays_survey_enable_copy_protection"
                   value="on" <?php echo $enable_copy_protection ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable copy protection -->
    <hr>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_allow_collecting_logged_in_users_data">
                <?php echo __('Allow collecting information of logged in users',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Allow collecting information from logged-in users. Email and name of users will be stored in the database. Email options will be work for these users.",$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" id="ays_survey_allow_collecting_logged_in_users_data" name="ays_survey_allow_collecting_logged_in_users_data" value="on" <?php echo ($survey_allow_collecting_logged_in_users_data) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Allow collecting information of logged in users -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_enable_rtl_direction">
                <?php echo __('Use RTL Direction',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable Right to Left direction for the text. This option is intended for the Arabic language.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timerl" id="ays_enable_rtl_direction"
                   name="ays_survey_enable_rtl_direction"
                   value="on" <?php echo (isset($options['survey_enable_rtl_direction']) && $options['survey_enable_rtl_direction'] == 'on') ? 'checked' : ''; ?>/>
        </div> 
    </div> <!--  Use RTL direction -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_schedule">
                <?php echo __('Schedule the Survey', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the period of time when your survey will be active. When the time is due, a message will inform the survey takers about it.', $this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
            <?php if( Survey_Maker_Data::survey_maker_capabilities_for_editing() ): ?>
            <p class="ays_survey_small_hint_text_for_message_variables">
                <span><?php echo __( "To change your GMT " , $this->plugin_name ); ?></span>
                <a href="<?php echo $wp_general_settings_url; ?>" target="_blank"><?php echo __( "click here" , $this->plugin_name ); ?></a>
            </p>
            <?php endif; ?>
        </div>
        <div class="col-sm-1">
            <input id="ays_survey_enable_schedule" type="checkbox" class="active_date_check ays_toggle_checkbox" name="ays_survey_enable_schedule" <?php echo $survey_enable_schedule ? 'checked' : '' ?>>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left active_date <?php echo $survey_enable_schedule ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="form-check-label" for="ays_survey_schedule_active">
                        <?php echo __('Start date:', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set a date since which your survey will be active.', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <div class="input-group mb-3">
                        <input type="text" class="ays-text-input ays-text-input-short" id="ays_survey_schedule_active" name="ays_survey_schedule_active" value="<?php echo $survey_schedule_active; ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                        <div class="input-group-append">
                            <label for="ays_survey_schedule_active" class="input-group-text">
                                <span><i class="ays_fa ays_fa_calendar"></i></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="form-check-label" for="ays_survey_schedule_deactive">
                        <?php echo __('End date:', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set a date since which your survey will be inactive.', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <div class="input-group mb-3">
                        <input type="text" class="ays-text-input ays-text-input-short" id="ays_survey_schedule_deactive" name="ays_survey_schedule_deactive" value="<?php echo $survey_schedule_deactive; ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                        <div class="input-group-append">
                            <label for="ays_survey_schedule_deactive" class="input-group-text">
                                <span><i class="ays_fa ays_fa_calendar"></i></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div> <!--Show timer start -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="form-check-label" for="ays_survey_schedule_pre_start_message">
                        <?php echo __("Pre start message:", $this->plugin_name) ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write a message that will inform the survey takers about the activation of the survey.', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <div class="editor">
                        <?php
                        $content   = $survey_schedule_pre_start_message;
                        $editor_id = 'ays_survey_schedule_pre_start_message';
                        $settings  = array(
                            'editor_height'  => $survey_wp_editor_height,
                            'textarea_name'  => 'ays_survey_schedule_pre_start_message',
                            'editor_class'   => 'ays-textarea',
                            'media_elements' => false
                        );
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="form-check-label" for="ays_survey_schedule_expiration_message">
                        <?php echo __("Expiration message:", $this->plugin_name) ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set down a message that will inform the survey takers that they cannot take the survey anymore.', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <div class="editor">
                        <?php
                        $content   = $survey_schedule_expiration_message;
                        $editor_id = 'ays_survey_schedule_expiration_message';
                        $settings  = array(
                            'editor_height'  => $survey_wp_editor_height,
                            'textarea_name'  => 'ays_survey_schedule_expiration_message',
                            'editor_class'   => 'ays-textarea',
                            'media_elements' => false
                        );
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_dont_show_survey_container">
                        <?php echo __('Don\'t show survey',$this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Do not show the survey container on the front-end at all when it is expired or has not started yet.",$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" id="ays_survey_dont_show_survey_container" name="ays_survey_dont_show_survey_container" value="on" <?php echo ($survey_dont_show_survey_container) ? 'checked' : ''; ?>/>
                </div>
            </div> <!-- Dont Show Survey -->
        </div>
    </div> <!-- Schedule the Survey -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_edit_previous_submission">
                <?php echo __('Edit previous submission',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By activating this option, the previous answers will be displayed and the user will be able to edit them. Note: This option will be available only for the logged-in users.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timerl" id="ays_survey_edit_previous_submission"
                   name="ays_survey_edit_previous_submission"
                   value="on" <?php echo ($survey_edit_previous_submission) ? 'checked' : ''; ?>/>
        </div> 
    </div> <!--  Edit previous submission -->
    <hr/>
    <p class="ays-subtitle"><?php echo __('Question Settings',$this->plugin_name)?></p>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_randomize_questions">
                <?php echo __('Enable randomize questions',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the questions in a random sequence every time the survey takers participate in a survey.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timerl" id="ays_survey_enable_randomize_questions" name="ays_survey_enable_randomize_questions" value="on" <?php echo ($survey_enable_randomize_questions) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable randomize questions -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Questions numbering',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Assign numbering to each question in ascending sequential order. Choose your preferred type from the list.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <div class="form-group row ays_toggle_parent">
                <div class="col-sm-3">
                    <select class="ays-text-input ays-text-input-short ays_toggle_select" style="width: 100%; max-width: 200px;" name="ays_survey_show_question_numbering">
                        <option <?php echo $survey_auto_numbering_questions == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", $this->plugin_name); ?></option>
                        <option <?php echo $survey_auto_numbering_questions == "1."   ? "selected" : ""; ?>   value="1."><?php echo __( "1.", $this->plugin_name); ?></option>
                        <option <?php echo $survey_auto_numbering_questions == "1)"   ? "selected" : ""; ?>   value="1)"><?php echo __( "1)", $this->plugin_name); ?></option>
                        <option <?php echo $survey_auto_numbering_questions == "A."   ? "selected" : ""; ?>   value="A."><?php echo __( "A.", $this->plugin_name); ?></option>
                        <option <?php echo $survey_auto_numbering_questions == "A)"   ? "selected" : ""; ?>   value="A)"><?php echo __( "A)", $this->plugin_name); ?></option>
                        <option <?php echo $survey_auto_numbering_questions == "a."   ? "selected" : ""; ?>   value="a."><?php echo __( "a.", $this->plugin_name); ?></option>
                        <option <?php echo $survey_auto_numbering_questions == "a)"   ? "selected" : ""; ?>   value="a)"><?php echo __( "a)", $this->plugin_name); ?></option>
                    </select>
                </div>
                <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_auto_numbering_questions != 'none') ? '' : 'display_none_not_important'; ?>">     
                    <div class="form-group row">    
                        <div class="col-sm-3">           
                            <label for="ays_survey_enable_question_numbering_by_sections">
                                <?php echo __('By sections',$this->plugin_name)?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By activating this option, the questions in each section will have their sequence. If this option is disabled, the numbering will start from 1 only in the first section (e.g Section 1 - Q1, Q2, Q3 Section 2 - Q4, Q5, Q6).',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>     
                        </div>     
                        <div class="col-sm-6">            
                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_question_numbering_by_sections"
                            name="ays_survey_enable_question_numbering_by_sections"
                            value="on" <?php echo $survey_enable_question_numbering_by_sections ? 'checked' : ''; ?>/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Show question numbering -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_questions_count">
                <?php echo __('Show questions count',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to show every sections questions count',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox"
                class="ays-enable-timer1"
                id="ays_survey_show_questions_count"
                name="ays_survey_show_questions_count"
                value="on"
                <?php echo $survey_show_sections_questions_count;?>/>
        </div>
    </div> <!-- Show sections questions count -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_required_questions_message">
                <?php echo __('Required questions message',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the required message text displayed in case of the required questions.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" name="ays_survey_required_questions_message" id="ays_survey_required_questions_message" value="<?php echo __($survey_required_questions_message , $this->plugin_name); ?>" placeholder="<?php echo __( 'Required question message' , $this->plugin_name ); ?>">
        </div>
    </div> <!--Required questions message -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_expand_collapse_question">
                <?php echo __('Expand/collapse questions',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Expand/collapse questions on the front page.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_expand_collapse_question"
                   name="ays_survey_enable_expand_collapse_question"
                   value="on" <?php echo ($enable_expand_collapse_question) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Expand/collapse questions -->    
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
        <div class="ays-pro-features-v2-small-buttons-box">
            <div class="ays-pro-features-v2-video-button"></div>
                <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                    <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="<?php echo (SURVEY_MAKER_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                    <div class="ays-pro-features-v2-upgrade-text">
                        <?php echo __("Upgrade to Agency" , "survey-maker"); ?>
                    </div>
                </a>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_question_text_to_speech">
                        <?php echo __('Enable text to speech',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option to allow listening to the questions being read aloud. Note this option can be used only for questions.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1"/>
                </div>
            </div> 
        </div>
    </div> <!-- Questions text to speech -->
    <hr>
    <p class="ays-subtitle"><?php echo __('Answer Settings',$this->plugin_name)?></p>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_randomize_answers">
                <?php echo __('Enable randomize answers',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the answers in a random sequence every time the survey takers participate in a survey.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timerl" id="ays_survey_enable_randomize_answers" name="ays_survey_enable_randomize_answers" value="on" <?php echo ($survey_enable_randomize_answers) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable randomize answers -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_answers_numbering">
                <?php echo __('Answers numbering',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Assign numbering to each answer in ascending sequential order. Choose your preferred type from the list.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select class="ays-text-input ays-text-input-short" name="ays_survey_show_answers_numbering" id="ays_survey_show_answers_numbering">
                <option <?php echo $survey_auto_numbering == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", $this->plugin_name); ?></option>
                <option <?php echo $survey_auto_numbering == "1." ? "selected" : ""; ?> value="1."><?php echo __( "1.", $this->plugin_name); ?></option>
                <option <?php echo $survey_auto_numbering == "1)" ? "selected" : ""; ?> value="1)"><?php echo __( "1)", $this->plugin_name); ?></option>
                <option <?php echo $survey_auto_numbering == "A." ? "selected" : ""; ?> value="A."><?php echo __( "A.", $this->plugin_name); ?></option>
                <option <?php echo $survey_auto_numbering == "A)" ? "selected" : ""; ?> value="A)"><?php echo __( "A)", $this->plugin_name); ?></option>
                <option <?php echo $survey_auto_numbering == "a." ? "selected" : ""; ?> value="a."><?php echo __( "a.", $this->plugin_name); ?></option>
                <option <?php echo $survey_auto_numbering == "a)" ? "selected" : ""; ?> value="a)"><?php echo __( "a)", $this->plugin_name); ?></option>
            </select>

        </div>
    </div> <!-- Show answers numbering -->
    <hr>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_allow_html_in_answers">
                <?php echo __('Allow HTML in answers',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow implementing HTML coding in answer boxes. This works only for Radio and Checkbox (Multiple) questions.', $this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_allow_html_in_answers" name="ays_survey_allow_html_in_answers" value="on" <?php echo ($survey_allow_html_in_answers) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Allow HTML in answers -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_i_autofill">
                <?php echo __('Autofill logged-in user data',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __("After enabling this option, the logged-in user's name and email address will be auto-filled in the Name and Email fields.",$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" id="ays_survey_enable_i_autofill" name="ays_survey_enable_i_autofill" value="on" <?php echo ($survey_i_autofill); ?>/>
        </div>
    </div> <!-- Autofill logged-in user data -->
    <hr>
    <p class="ays-subtitle"><?php echo __('Advanced Settings',$this->plugin_name)?></p>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_chat_mode">
                <?php echo __('Enable chat mode',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Make your survey look like an online chat conversation. It will print each pre-set question instantly after the user answers the previous one.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays_toggle_checkbox" id="ays_survey_enable_chat_mode" name="ays_survey_enable_chat_mode" value="on" <?php echo ($enable_chat_mode) ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $enable_chat_mode ? '' : 'display_none_not_important'; ?>">
            <blockquote class="chat_mode_blockquote_message"><?php echo __("The option works only with these questions types: <strong>Radio, Short text, and Yes or No.</strong>", $this->plugin_name); ?></blockquote>
        </div>
    </div> <!-- Enable chat mode -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_change_create_author">
                <?php echo __('Change the author of the current survey',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can change the author who created the current survey to your preferred one. You need to write the User ID here. Please note, that in case you write an ID, by which there are no users found, the changes will not be applied and the previous author will remain the same.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_change_create_author'name='ays_survey_change_create_author' value="<?php echo $survey_change_create_author; ?>"/>
        </div>
    </div> <!-- Change the author of the current quiz -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_terms_and_conditions">
                <?php echo __('Terms and Conditions',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __(' Write your terms and conditions here. It will be displayed in the front end in top of the finish button. Please note that you will be able to click on the finish button only when all the checkboxes are ticked.',$this->plugin_name)?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays_toggle_checkbox" id="ays_survey_terms_and_conditions" name="ays_survey_terms_and_conditions" value="on" <?php echo ($enable_terms_and_conditions) ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-7 ays_divider_left ays_toggle_target <?php echo $enable_terms_and_conditions ? '' : 'display_none_not_important'; ?> ays_survey_terms_and_conditions_all_inputs_block">
            <div>
                <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl">
                    <img class="ays_survey_add_new_textarea" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                </div>
                <div class="ays_survey_terms_and_conditions_content">
                <?php if(!empty($terms_and_conditions)):
                    foreach($terms_and_conditions as $term_cond_id => $term_cond_value):
                        ?>
                        <?php if(isset($term_cond_value)):?>
                        <?php
                        $term_cond_message_values = isset($condition_messages_page['message']) && $condition_messages_page['message'] != "" ? stripslashes( wpautop($condition_messages_page['message'])) : "";
                        ?>
                        <div class = "ays_survey_terms_conditions_edit_block" data-condition-id = "<?php echo $term_cond_id; ?>">
                            <div class="ays_survey_terms_and_conditions_checkbox">  
                                <div class="ays-survey-icons">
                                    <img src= "<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                </div>
                            </div>
                            <div class="ays_survey_terms_and_conditions_textarea_div">
                                <textarea name="ays_terms_and_condition_add[<?php echo $term_cond_id; ?>][messages]" value=""><?php echo stripslashes($term_cond_value['messages']); ?></textarea>
                            </div>
                            <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl" data-trigger="hover">
                                <img class="ays_survey_remove_textarea" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if(empty($terms_and_conditions)):?>
                    <div class = "ays_survey_terms_conditions_edit_block" data-condition-id = "1">
                        <div class="ays_survey_terms_and_conditions_textarea_div">
                            <textarea name="ays_terms_and_condition_add[1][messages]" value=""></textarea>
                        </div>
                        <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl" data-trigger="hover">
                            <img class="ays_survey_remove_textarea" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
                        </div>
                    </div>
                <?php endif; ?>
                </div>
                <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl">
                    <img class="ays_survey_add_new_textarea" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                </div>         
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4">
                    <label class="form-check-label" for="ays_survey_terms_and_conditions_required_message">
                        <?php echo __("Required Message Text", $this->plugin_name) ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If this option is enabled, when skipping the confirmation of terms the users will see the following text "By clicking on the checkbox, you agree to our Terms and Conditions".', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="" id="ays_survey_terms_and_conditions_required_message" name="ays_survey_terms_and_conditions_required_message" value="on" <?php echo ($enable_terms_and_conditions_required_message) ? 'checked' : ''; ?>>
                </div>
            </div>
        </div>    
    </div> <!-- Terms and Conditions -->
    <hr>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_main_url">
                <?php echo __('Survey main URL',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo  __('Write the URL link where your survey is located (in Front-end).To open your survey right from the surveys page, please fill in this field and navigate to the general tab to see the \'View\' button',"survey-maker");?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="url" id="ays_survey_main_url" name="ays_survey_main_url" class="ays-text-input" value="<?php echo $survey_main_url; ?>">
        </div>
    </div> <!-- Survey Main URL -->
</div>
