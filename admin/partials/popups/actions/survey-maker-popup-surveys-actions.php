<?php
    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/popups/actions/survey-maker-popup-surveys-actions-options.php" );
?>
<div class="wrap">
    <div class="container-fluid">
        <form class="ays-survey-popup-surveys-form" id="ays-survey-popup-surveys-form" method="post">
            <div class="ays-survey-heading-box">
                <div class="ays-survey-wordpress-user-manual-box">
                    <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                        <i class="ays_fa ays_fa_file_text" ></i> 
                        <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
                    </a>

                </div>
            </div>
            <h1><?php echo $heading; ?></h1>
            <hr/>
            <div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="<?php echo $html_name_prefix; ?>status">
                            <?php echo __('Enable popup',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Turn on the popup for the website based on your configured options.', $this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="checkbox" id="<?php echo $html_name_prefix; ?>status" name="<?php echo $html_name_prefix; ?>status" value="on" <?php echo $status == 'published' ? 'checked' : ''; ?>/>
                    </div>
                </div> <!-- Publish/Unpublish popup -->
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays-title">
                            <?php echo __("Title", $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Give a title to your popup.",$this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="ays-text-input" id="ays-title" name="<?php echo $html_name_prefix; ?>title" value="<?php echo $title; ?>" />
                    </div>
                </div> <!-- Title -->
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays-category">
                            <?php echo __("Select Survey", $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                                echo htmlspecialchars( sprintf(
                                    __("Choose the survey which you want to display within the popup from your already created list.",$this->plugin_name),
                                    "<strong>",
                                    "</strong>"
                                ) );
                            ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <select id="ays-select-popup-survey-id" name="<?php echo $html_name_prefix; ?>survey_id">
                            <?php
                          $selected = "";
                          foreach ( $surveys as $key => $survey ):
                              if( $survey_id == $survey["id"] ){
                                  $selected = "selected";
                              }else{
                                  $selected = "";
                              }
                              ?>
                              <option value="<?php echo $survey["id"]; ?>" <?php echo $selected; ?>><?php echo $survey["title"]; ?></option>
                          <?php
                          endforeach;
                            ?>
                        </select>
                    </div>
                    
                </div> <!-- Survey Id -->
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_popup_survey_width">
                            <?php echo __("Popup width", $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the width of your popup in pixels.",$this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div>
                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_popup_survey_width" name="ays_popup_survey_width" value="<?php echo $popup_survey_width; ?>"/>
                        </div>
                    </div>
                </div> <!-- Survey width -->
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_popup_survey_height">
                            <?php echo __("Popup height", $this->plugin_name); ?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the height of your popup in pixels.",$this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div>
                            <input type="number" class="ays-text-input ays-text-input-short" id="ays_popup_survey_height" name="ays_popup_survey_height" value="<?php echo $popup_survey_height; ?>"/>
                        </div>
                    </div>
                </div> <!-- Survey height -->
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label>
                            <span><?php echo __("Show on ", $this->plugin_name); ?></span>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Select on which pages of your website you need the popup to be loaded. For the Except and Selected options, you can choose specific posts and post types.", $this->plugin_name) ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <label class="ays_survey_loader">
                            <input type="radio" class="ays-survey-popup-show-where" name="<?php echo $html_name_prefix; ?>survey_show_all" value="all" <?php echo $show_all == "all" ? "checked" : ""; ?> />
                            <span><?php echo __("All pages", $this->plugin_name); ?></span>
                        </label>
                        <label class="ays_survey_loader">
                            <input type="radio" class="ays-survey-popup-show-where" name="<?php echo $html_name_prefix; ?>survey_show_all" value="except" <?php echo $show_all == "except" ? "checked" : ""; ?>/>
                            <span><?php echo __("Except", $this->plugin_name); ?></span>
                        </label>
                        <label class="ays_survey_loader">
                            <input type="radio" class="ays-survey-popup-show-where" name="<?php echo $html_name_prefix; ?>survey_show_all" value="selected" <?php echo $show_all == "selected" ? "checked" : ""; ?>/>
                            <span><?php echo __("Selected", $this->plugin_name); ?></span>
                        </label>
                    </div>
                </div>
                <div class="ays-field ays_survey_view_place_tr <?php echo $show_all == "all" ? "display_none" : ""; ?>">
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_post_types"><?php echo __("Post type", $this->plugin_name); ?></label>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Select the post type from the box. You have to enter 3 or more characters here.", $this->plugin_name) ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                        <div class="col-sm-9">
                            <select name="ays_survey_except_post_types[]" id="ays_survey_post_types" class="form-control" multiple="multiple">
                                <?php
                                    foreach ($all_post_types as $post_type) {
                                        if($except_post_types) {
                                            $checked = (in_array($post_type->name, $except_post_types)) ? "selected" : "";
                                        }else{
                                            $checked = "";
                                        }
                                        echo "<option value='" . $post_type->name . "' {$checked}>{$post_type->label}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_posts"><?php echo __("Posts", $this->plugin_name); ?></label>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Select the posts from the box. You have to enter 3 or more characters here.", $this->plugin_name) ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                        <div class="col-sm-9">
                            <select name="ays_survey_except_posts[]" id="ays_survey_posts" class="form-control" multiple="multiple">
                                <?php
                                    foreach ( $posts as $post ) {
                                        $checked = (is_array($except_posts) && in_array($post->ID, $except_posts)) ? "selected" : "";
                                        echo "<option value=" . $post->ID . " {$checked}>{$post->post_title}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_show_on_home_page">
                                <span><?php echo __("Show on Home page", $this->plugin_name); ?></span>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __("If the checkbox is ticked, then the popup will be loaded on the Home page, too, in addition to the values given above.", $this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <p class="onoffswitch">
                                <input type="checkbox" name="ays_survey_show_on_home_page" class="onoffswitch-checkbox" id="ays_survey_show_on_home_page" <?php echo ($show_on_home_page == "on") ? "checked" : "" ?> >
                            </p>
                        </div>
                     </div>
                </div>
                <hr>
                <div class="popup_survey_position_block">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="<?php echo $this->plugin_name; ?>-popup-position">
                                <span><?php echo __("Popup position", $this->plugin_name); ?></span>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the position of the popup on the screen.", $this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <table id="ays-survey-popup-position-table">
                                <tr>
                                    <td data-value="left-top" data-id="1" style="<?php echo $popup_position == "left-top" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                    <td data-value="top-center" data-id="2" style="<?php echo $popup_position == "top-center" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                    <td data-value="right-top" data-id="3" style="<?php echo $popup_position == "right-top" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                </tr>
                                <tr>
                                    <td data-value="left-center" data-id="4" style="<?php echo $popup_position == "left-center" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                    <td data-value="center-center" data-id="5" style="<?php echo $popup_position == "center-center" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                    <td data-value="right-center" data-id="6" style="<?php echo $popup_position == "right-center" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                </tr>
                                <tr>
                                    <td data-value="left-bottom" data-id="7" style="<?php echo $popup_position == "left-bottom" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                    <td data-value="center-bottom" data-id="8" style="<?php echo $popup_position == "center-bottom" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                    <td data-value="right-bottom" data-id="9" style="<?php echo $popup_position == "right-bottom" ? "background-color: #a2d6e7" : ""; ?>"></td>
                                </tr>
                            </table>
                            <input type="hidden" name="<?php echo $html_name_prefix; ?>survey_popup_position" id="ays-survey-popup-position-val" value="<?php echo $popup_position; ?>" >
                        </div>
                    </div>
                    <hr class="ays_pb_hr_hide <?php echo $popup_position == "center-center" ? "display_none" : ""; ?>"/>
                    <div id="popupMargin" class="form-group row <?php echo $popup_position == "center-center" ? "display_none" : ""; ?>">
                        <div class="col-sm-3">
                            <label for="<?php echo $this->plugin_name; ?>-pb_margin">
                                <span><?php echo __("Popup margin", $this->plugin_name); ?>(px)</span>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the popup margin in pixels. It accepts only numerical values.", $this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="number" id="<?php echo $this->plugin_name; ?>-popup_margin" name="<?php echo $html_name_prefix; ?>survey_popup_margin"  class="ays-text-input-short"  value="<?php echo $popup_margin; ?>" />
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Popup Trigger start  -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="<?php echo $html_name_prefix; ?>survey_popup_trigger">
                            <span> <?php echo __('Popup trigger', $this->plugin_name); ?></span>
                                <a class="ays_help" data-toggle="tooltip" data-html="true"
                                title="<?php
                                    echo __('Choose when to show the popup on the website.',$this->plugin_name) .
                                    "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                        "<li>". __('On page load - popup will be shown as soon as the page is loaded.',$this->plugin_name) ."</li>".
                                        "<li>". __('On click - popup will be shown when the user clicks on the assigned CSS element(s). Select CSS element with the help of CSS selector(s) option.',$this->plugin_name) ."</li>".
                                        "<li>". __('On Exit - the popup will show up as soon as the user wants to leave the page.',$this->plugin_name) ."</li>".
                                    "</ul>";
                                ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <select id="<?php echo $html_name_prefix; ?>survey_popup_trigger" class="ays-text-input ays_survey_aysDropdown" name="<?php echo $html_name_prefix; ?>survey_popup_trigger">
                            <?php
                                foreach ($trigger_type_arr as $trigger_type_key => $trigger_type_val):
                                    if($popup_trigger_type == $trigger_type_key):
                                        $selected = 'selected';
                                    else:
                                        $selected = '';
                                    endif;
                            ?>
                                <option value="<?php echo $trigger_type_key; ?>" <?php echo $selected; ?>><?php echo $trigger_type_val; ?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <hr class="<?php echo $popup_trigger_type == 'on_load' ||  $popup_trigger_type == 'on_exit' ? 'display_none_not_important' : '' ?>"/>
                <div class="form-group row ays-survey-popup-selector <?php echo $popup_trigger_type == 'on_load' ||  $popup_trigger_type == 'on_exit' ? 'display_none_not_important' : '' ?> ">
                    <div class="col-sm-3">
                        <label for="<?php echo $html_name_prefix; ?>survey_popup_selector">
                    <span>
                        <?php echo __('CSS selector(s) for trigger click', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Add your preferred CSS selector(s) if you have given “On click” or “Both” value to the “Popup trigger” option. For example #mybutton or .mybutton.", $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </span>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="<?php echo $html_name_prefix; ?>survey_popup_selector" name="<?php echo $html_name_prefix; ?>survey_popup_selector"  class="ays-text-input" value="<?php echo $popup_selector; ?>" placeholder="#myButtonId, .myButtonClass, .myButton" />
                    </div>
                </div>
                <!-- Popup Trigger end  -->
                <hr/>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_popup_bg_color">
                            <span><?php echo __("Popup background color", $this->plugin_name); ?></span>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the background color of your popup.", $this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="ays-text-input" id='ays_survey_popup_bg_color' name='ays_survey_popup_bg_color' data-alpha="true" value="<?php echo $survey_popup_bg_color; ?>"/>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_hide_popup">
                            <span><?php echo __("Hide popup after one submission", $this->plugin_name); ?></span>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __("By enabling the option, the popup will not be shown as soon as a visitor submits the survey.", $this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="checkbox" name="ays_survey_hide_popup" class="" id="ays_survey_hide_popup" <?php echo ($hide_popup == "on") ? "checked" : ""  ?>>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_enable_popup_full_screen_mode">
                            <?php echo __('Enable full-screen mode',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow the popup to enter full-screen mode by pressing the icon located in the top-right corner of the popup container.',$this->plugin_name)?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="checkbox"
                               class="ays-enable-timer1"
                               id="ays_survey_enable_popup_full_screen_mode"
                               name="ays_survey_enable_popup_full_screen_mode"
                               value="on"
                               <?php echo $survey_popup_full_screen;?>/>
                    </div>
                </div> <!-- Open Full Screen Mode -->
            </div>
            <hr/>
            
            <input type="hidden" name="<?php echo $html_name_prefix; ?>author_id" value="<?php echo $author_id; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>date_created" value="<?php echo $date_created; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>date_modified" value="<?php echo $date_modified; ?>">
            <?php
                wp_nonce_field("popup_survey_action", "popup_survey_action");
                //$other_attributes = array("id" => "ays-button-save");
                $other_attributes = array(
                    'id' => 'ays-button-save',
                    'title' => 'Salvar',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );
                submit_button(__("Salvar e fechar", $this->plugin_name), "primary ays-button ays-survey-loader-banner", "ays_submit", false, $other_attributes);
                $other_attributes = array(
                    'id' => 'ays-button-apply',
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );
                submit_button(__("Salvar", $this->plugin_name), "ays-button ays-survey-loader-banner", "ays_apply", false, $other_attributes);

                echo $loader_iamge;
            ?>
        </form>
    </div>
</div>
