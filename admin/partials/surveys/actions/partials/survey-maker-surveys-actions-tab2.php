<div id="tab2" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab2') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Survey Styles',$this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-2">
            <label for="ays_survey_theme">
                <?php echo __('Theme', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php 
                        echo htmlspecialchars( sprintf(
                            __('Choose the theme for your survey among these nice themes.',$this->plugin_name) . '<ul class="ays_help_ul"><li>' .
                            __('%sClassic Light:%s Light background, dark text.',$this->plugin_name) . '</li><li>' .
                            __('%sClassic Dark:%s Dark background, light text.',$this->plugin_name) . '</li><li>' .
                            __('%sMinimal:%s Transparent background, dark text.',$this->plugin_name) . '</li></ul>',
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
        <div class="col-sm-10">
            <div class="ays-survey-themes-main-div-wrap">
                <div class="ays-survey-themes-main-div">
                    <input type="radio" id="ays-survey-classic_light" name="ays_survey_theme" value="classic_light" <?php echo ($survey_theme == 'classic_light') ? 'checked' : '' ?>>
                    <label for="ays-survey-classic_light" class="ays-survey-theme-item">
                        <span><?php echo __('Classic Light', $this->plugin_name); ?></span>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/themes/classic-light.png' ?>" alt="Classic Light">
                    </label>
                </div>
                <div class="ays-survey-themes-main-div">
                    <input type="radio" id="ays-survey-classic_dark" name="ays_survey_theme" value="classic_dark" <?php echo ($survey_theme == 'classic_dark') ? 'checked' : '' ?>>
                    <label for="ays-survey-classic_dark" class="ays-survey-theme-item">
                        <span><?php echo __('Classic Dark', $this->plugin_name); ?></span>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/themes/classic-dark.png' ?>" alt="Classic Dark">
                    </label>
                </div>
                <div class="ays-survey-themes-main-div">
                    <input type="radio" id="ays-survey-minimal" name="ays_survey_theme" value="minimal" <?php echo ($survey_theme == 'minimal') ? 'checked' : '' ?>>
                    <label for="ays-survey-minimal" class="ays-survey-theme-item">
                        <span><?php echo __('Minimal', $this->plugin_name); ?></span>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/themes/minimal.jpg' ?>" alt="Minimal">
                    </label>
                </div>
                <div class="ays-survey-themes-main-div">
                    <input type="radio" id="ays-survey-modern" name="ays_survey_theme" value="modern" <?php echo ($survey_theme == 'modern') ? 'checked' : '' ?>>
                    <label for="ays-survey-modern" class="ays-survey-theme-item">
                        <span><?php echo __('Modern', $this->plugin_name); ?></span>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/themes/modern.png' ?>" alt="Modern">
                    </label>
                </div>
                <div class="ays-survey-themes-main-div">
                    <input type="radio" id="ays-survey-business" name="ays_survey_theme" value="business" <?php echo ($survey_theme == 'business') ? 'checked' : '' ?>>
                    <label for="ays-survey-business" class="ays-survey-theme-item">
                        <span><?php echo __('Business', $this->plugin_name); ?></span>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/themes/business.png' ?>" alt="Business">
                    </label>
                </div>
                <div class="ays-survey-themes-main-div">
                    <input type="radio" id="ays-survey-elegant" name="ays_survey_theme" value="elegant" <?php echo ($survey_theme == 'elegant') ? 'checked' : '' ?>>
                    <label for="ays-survey-elegant" class="ays-survey-theme-item">
                        <span><?php echo __('Elegant', $this->plugin_name); ?></span>
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/themes/elegant.png' ?>" alt="Elegant">
                    </label>
                </div>
            </div>
        </div>
    </div> <!-- Survey Theme -->
    <hr/>
    <div class="row">
        <div class="col-lg-7 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_color'>
                        <?php echo __('Survey color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the main color of the survey. The borders of your survey will get the color that you set.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_color' name='ays_survey_color' data-alpha="true" value="<?php echo $survey_color; ?>"/>
                </div>
            </div> <!-- Survey Color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_background_color'>
                        <?php echo __('Background color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the background color of your survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_background_color' data-alpha="true" name='ays_survey_background_color' value="<?php echo $survey_background_color; ?>"/>
                </div>
            </div> <!-- Survey Background Color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_text_color'>
                        <?php echo __('Text color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Identify the text color of your survey, including the questions, the answers, and the thank you message.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_text_color' data-alpha="true"name='ays_survey_text_color' value="<?php echo $survey_text_color; ?>"/>
                </div>
            </div> <!-- Text Color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_width'>
                        <?php echo __('Survey width', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the width of your survey in pixels or percentage.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-6 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_width' name='ays_survey_width' value="<?php echo $survey_width; ?>"/>
                        <span style="display:block;" class="ays_survey_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name); ?></span>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <select id="ays_survey_width_by_percentage_px" name="ays_survey_width_by_percentage_px" class="ays-text-input ays-text-input-short ays_survey_aysDropdown" style="display:inline-block; width: 60px;">
                            <option value="pixels" <?php echo $survey_width_by_percentage_px == "pixels" ? "selected" : ""; ?>>px</option>
                            <option value="percentage" <?php echo $survey_width_by_percentage_px == "percentage" ? "selected" : ""; ?>>%</option>
                        </select>
                    </div>
                </div>
            </div> <!-- Survey width -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_mobile_width'>
                        <?php echo __('Survey mobile width', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the width of your survey in pixels or percentage for mobile devices.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-6 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_mobile_width' name='ays_survey_mobile_width' value="<?php echo $survey_mobile_width; ?>"/>
                        <span style="display:block;" class="ays_survey_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name); ?></span>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <select id="ays_survey_mobile_width_by_percentage_px" name="ays_survey_mobile_width_by_percentage_px" class="ays-text-input ays-text-input-short ays_survey_aysDropdown" style="display:inline-block; width: 60px;">
                            <option value="pixels" <?php echo $survey_mobile_width_by_percentage_px == "pixels" ? "selected" : ""; ?>>px</option>
                            <option value="percentage" <?php echo $survey_mobile_width_by_percentage_px == "percentage" ? "selected" : ""; ?>>%</option>
                        </select>
                    </div>
                </div>
            </div> <!-- Survey mobile width -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>mobile_max_width'>
                        <?php echo __('Survey max-width for mobile', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Survey container max-width for mobile in percentage. This option will work for the screens with less than 640 pixels width.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='<?php echo $html_name_prefix; ?>mobile_max_width'
                           name='<?php echo $html_name_prefix; ?>mobile_max_width' style="display:inline-block;"
                           value="<?php echo $survey_mobile_max_width; ?>"/> 
                           <span style="display:block;" class="ays_survey_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name);?></span>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="%" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Survey max-width for mobile -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label>
                        <?php echo __('Survey logo', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Add logo image for survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_toggle_parent">
                    <div class="ays-survey-image-container">
                        <button class="button ays-survey-add-logo-image" type="button"><?php echo $survey_logo_text; ?></button>
                        <div class="ays-survey-image-body" style="<?php echo $survey_logo != '' ? '' : 'display: none;'; ?>">
                            <div class="ays-survey-image-wrapper">
                                <div class="ays-survey-image-wrapper-delete-wrap">
                                    <div role="button" class="ays-survey-image-wrapper-delete-cont ays-survey-logo-remove removeImage">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close-grey.svg">
                                        </div>
                                    </div>
                                </div>
                                <img class="ays-survey-img" src="<?php echo $survey_logo; ?>" tabindex="0" />
                                <input type="hidden" class="ays-survey-img-src" name="<?php echo $html_name_prefix; ?>survey_logo" value="<?php echo $survey_logo; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row ays-survey-logo-url-box <?php echo $survey_logo_check;?>">
                        <div class="col-sm-4">
                            <label for="ays_survey_logo_enable_image_url">
                                <?= __('Logo URL', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip"
                                data-placement="top"
                                title="<?= __("Add a URL link to the survey's logo image.", $this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>                                   
                        </div>
                        <div class="col-sm-8 ays_divider_left">
                            <input type="checkbox"
                                    name="ays_survey_logo_enable_image_url"
                                    id="ays_survey_logo_enable_image_url"
                                    value="on" class="ays_toggle ays_toggle_slide ays_toggle_checkbox" <?php echo $survey_logo_image_url_checked; ?>>
                            <label for="ays_survey_logo_enable_image_url" class="ays_switch_toggle"></label>
                        </div>
                    </div>
                    <hr class="ays_toggle_target <?php echo $survey_logo_image_url_display; ?> ays-survey-logo-open-close">
                    <div class="row ays_toggle_target ays-survey-logo-open-close <?php echo $survey_logo_image_url_display; ?>" style="padding:0 15px;" >
                        <input type="text"
                                name="ays_survey_logo_image_url"
                                id="ays_survey_logo_image_url"
                                value="<?php echo $survey_logo_image_url?>" style="width: 100%;" class="ays-text-input" placeholder="URL">
                    </div>
                    <hr class="ays_toggle_target <?php echo $survey_logo_image_url_display; ?> ays-survey-logo-open-close">
                    <div class="row ays_toggle_target ays-survey-logo-url-box ays-survey-logo-open-close" style="<?php echo $survey_logo_image_url_display ? "display: none;" : "display:flex;"?>">
                        <div class="col-sm-6">
                            <label for="ays_survey_logo_enable_image_url_new_tab">
                                <?= __('Open in a new tab', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip"
                                data-placement="top"
                                title="<?= __("Activate this option, if you want to open the URL in a new tab.", $this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label> 
                        </div>
                        <div class="col-sm-6 ays_divider_left">
                            <input type="checkbox"
                                    name="ays_survey_logo_enable_image_url_new_tab"
                                    id="ays_survey_logo_enable_image_url_new_tab"
                                    value="on" class="ays_toggle ays_toggle_slide " <?php echo $survey_logo_image_url_check_new_tab; ?>>
                            <label for="ays_survey_logo_enable_image_url_new_tab" class="ays_switch_toggle"></label>
                        </div>
                    </div>
                    <hr class="<?php echo $survey_logo_check; ?> ays-survey-logo-open">
                    <div class="row ays-survey-logo-url-box <?php echo $survey_logo_check; ?>">
                        <div class="col-sm-4">
                            <label for="ays_survey_survey_logo_pos">
                                <?= __('Logo position', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip"
                                data-placement="top"
                                title="<?= __("Specify the position of the Logo image.", $this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label> 
                        </div>
                        <div class="col-sm-8 ays_divider_left">
                            <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-text-input-full" id='<?php echo $html_name_prefix; ?>survey_logo_pos' name='<?php echo $html_name_prefix; ?>survey_logo_pos'>
                                <option value="left"   <?php echo ($survey_logo_image_position == 'left')   ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                                <option value="center" <?php echo ($survey_logo_image_position == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                                <option value="right"  <?php echo ($survey_logo_image_position == 'right')  ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                            </select>
                        </div>
                    </div>
                    <hr class="<?php echo esc_attr($survey_logo_check); ?> ays-survey-logo-open">
                    <div class="row ays-survey-logo-url-box <?php echo esc_attr($survey_logo_check); ?>">
                        <div class="col-sm-4">
                            <label for="ays_survey_logo_title">
                                <?= __('Logo title', "survey-maker"); ?>
                                <a class="ays_help" data-toggle="tooltip"
                                data-placement="top"
                                title="<?php echo __("Specify the title of the Logo image.", "survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label> 
                        </div>
                        <div class="col-sm-8 ays_divider_left">
                            <input type="text" class="ays-text-input" name="ays_survey_logo_title" id="ays_survey_logo_title" value="<?php echo esc_attr($survey_logo_title); ?>">
                        </div>
                    </div>
                </div>
            </div> <!-- Survey logo -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_title_alignment'>
                        <?php echo __('Survey title alignment', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the alignment of the survey title.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown" id='<?php echo $html_name_prefix; ?>survey_title_alignment' name='<?php echo $html_name_prefix; ?>survey_title_alignment'>
                        <option value="left"   <?php echo ($survey_title_alignment == 'left')   ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                        <option value="center" <?php echo ($survey_title_alignment == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                        <option value="right"  <?php echo ($survey_title_alignment == 'right')  ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Survey title alignment -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_title_font_size'>
                        <?php echo __('Survey title font size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the survey title.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <div class="row">
                        <div class="col-sm-5">
                            <label for='<?php echo $html_name_prefix; ?>survey_title_font_size'>
                                <?php echo __('On PC', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the survey title for PC devices.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_survey_display_flex_width">
                            <div>
                                <input type="number" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_title_font_size' name='<?php echo $html_name_prefix; ?>survey_title_font_size' value="<?php echo $survey_title_font_size; ?>"/>
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <label for='<?php echo $html_name_prefix; ?>survey_title_font_size_for_mobile'>
                                <?php echo __('On Mobile', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the survey title for moblie devices.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_survey_display_flex_width">
                            <div>
                                <input type="number" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_title_font_size_for_mobile' name='<?php echo $html_name_prefix; ?>survey_title_font_size_for_mobile' value="<?php echo $survey_title_font_size_for_mobile; ?>"/>
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div> <!-- Survey title font size -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_title_box_shadow_enable'>
                        <?php echo __('Survey title box shadow', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text shadow of the survey title.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_toggle_parent">
                    <div>
                        <input type="checkbox"
                                name="<?php echo $html_name_prefix; ?>survey_title_box_shadow_enable"
                                id="<?php echo $html_name_prefix; ?>survey_title_box_shadow_enable"
                                value="on" class="ays_toggle ays_toggle_slide ays_toggle_checkbox" <?php echo ($survey_title_box_shadow_enable) ? "checked" : "" ?>>
                        <label for="<?php echo $html_name_prefix; ?>survey_title_box_shadow_enable" class="ays_switch_toggle"></label>                           
                    </div>
                    <hr class="ays_toggle_target <?php echo ($survey_title_box_shadow_enable) ? '' : 'display_none_not_important'; ?>">
                    <div class="row ays_toggle_target <?php echo ($survey_title_box_shadow_enable) ? '' : 'display_none_not_important'; ?>" style="padding:0 15px;" >
                        <input type="text" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_title_box_shadow_color' data-alpha="true" name='<?php echo $html_name_prefix; ?>survey_title_box_shadow_color' value="<?php echo $survey_title_box_shadow_color; ?>"/>
                    </div>
                    <hr class="ays_toggle_target <?php echo ($survey_title_box_shadow_enable) ? '' : 'display_none_not_important'; ?>">
                    <div class="form-group row ays_toggle_target <?php echo ($survey_title_box_shadow_enable) ? '' : 'display_none_not_important'; ?>">
                        <div class="col-sm-12">
                            <div class="col-sm-3" style="display: inline-block;">
                                <span class="ays_survey_small_hint_text"><?php echo __('X', $this->plugin_name); ?></span>
                                <input type="number" class="ays-text-input ays-survey-text-input-90-width" id='<?php echo $html_name_prefix; ?>title_text_shadow_x_offset' name='<?php echo $html_name_prefix; ?>title_text_shadow_x_offset' value="<?php echo $survey_title_text_shadow_x_offset; ?>" />
                            </div>
                            <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                <span class="ays_survey_small_hint_text"><?php echo __('Y', $this->plugin_name); ?></span>
                                <input type="number" class="ays-text-input ays-survey-text-input-90-width" id='<?php echo $html_name_prefix; ?>title_text_shadow_y_offset' name='<?php echo $html_name_prefix; ?>title_text_shadow_y_offset' value="<?php echo $survey_title_text_shadow_y_offset; ?>" />
                            </div>
                            <div class="col-sm-3 ays_divider_left" style="display: inline-block;">
                                <span class="ays_survey_small_hint_text"><?php echo __('Z', $this->plugin_name); ?></span>
                                <input type="number" class="ays-text-input ays-survey-text-input-90-width" id='<?php echo $html_name_prefix; ?>title_text_shadow_z_offset' name='<?php echo $html_name_prefix; ?>title_text_shadow_z_offset' value="<?php echo $survey_title_text_shadow_z_offset; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Survey title box shadow enable -->
            <hr/>            
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_section_title_font_size'>
                        <?php echo __('Survey section title font size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the section title.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <div class="row">
                        <div class="col-sm-5">
                            <label for='<?php echo $html_name_prefix; ?>survey_section_title_font_size'>
                                <?php echo __('On PC', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the survey section title for PC devices.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_survey_display_flex_width">
                            <div>
                                <input type="number" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_section_title_font_size' name='<?php echo $html_name_prefix; ?>survey_section_title_font_size' value="<?php echo $survey_section_title_font_size; ?>"/>
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <label for='<?php echo $html_name_prefix; ?>survey_section_title_font_size_mobile'>
                                <?php echo __('On Mobile', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the survey section title for moblie devices.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_survey_display_flex_width">
                            <div>
                                <input type="number" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_section_title_font_size_mobile' name='<?php echo $html_name_prefix; ?>survey_section_title_font_size_mobile' value="<?php echo $survey_section_title_font_size_mobile; ?>"/>
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- Survey section title font size -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_section_title_alignment'>
                        <?php echo __('Survey section title alignment', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the alignment of the section title.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown" id='<?php echo $html_name_prefix; ?>survey_section_title_alignment' name='<?php echo $html_name_prefix; ?>survey_section_title_alignment'>
                        <option value="left"   <?php echo ($survey_section_title_alignment == 'left')   ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                        <option value="center" <?php echo ($survey_section_title_alignment == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                        <option value="right"  <?php echo ($survey_section_title_alignment == 'right')  ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Survey section title alignment -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_section_description_alignment'>
                        <?php echo __('Survey section description alignment', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the alignment of the section description.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown" id='<?php echo $html_name_prefix; ?>survey_section_description_alignment' name='<?php echo $html_name_prefix; ?>survey_section_description_alignment'>
                        <option value="left"   <?php echo ($survey_section_description_alignment == 'left')   ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                        <option value="center" <?php echo ($survey_section_description_alignment == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                        <option value="right"  <?php echo ($survey_section_description_alignment == 'right')  ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Survey section description alignment -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='<?php echo $html_name_prefix; ?>survey_section_description_font_size'>
                        <?php echo __('Survey section description font size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the section description.',$this->plugin_name)?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <div class="row">
                        <div class="col-sm-5">
                            <label for='<?php echo $html_name_prefix; ?>survey_section_description_font_size'>
                                <?php echo __('On PC', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the section description for PC devices.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_survey_display_flex_width">
                            <div>
                                <input type="number" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_section_description_font_size' name='<?php echo $html_name_prefix; ?>survey_section_description_font_size' value="<?php echo $survey_section_description_font_size; ?>"/>
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-5">
                            <label for='<?php echo $html_name_prefix; ?>survey_section_description_font_size_mobile'>
                                <?php echo __('On Mobile', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the section description for moblie devices.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_survey_display_flex_width">
                            <div>
                                <input type="number" class="ays-text-input" id='<?php echo $html_name_prefix; ?>survey_section_description_font_size_mobile' name='<?php echo $html_name_prefix; ?>survey_section_description_font_size_mobile' value="<?php echo $survey_section_description_font_size_mobile; ?>"/>
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Survey section description font size -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label>
                        <?php echo __('Survey cover photo', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Add cover photo for survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_cover_image_main">
                    <div class="ays-survey-image-container">
                        <button class="button ays-survey-add-image" type="button"><?php echo $survey_cover_photo_text; ?></button>
                        <div class="ays-survey-image-body" style="<?php echo $survey_cover_photo != '' ? '' : 'display: none;'; ?>">
                            <div class="ays-survey-image-wrapper">
                                <div class="ays-survey-image-wrapper-delete-wrap">
                                    <div role="button" class="ays-survey-image-wrapper-delete-cont ays-survey-image-remove removeImage">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close-grey.svg">
                                        </div>
                                    </div>
                                </div>
                                <img class="ays-survey-img" src="<?php echo $survey_cover_photo; ?>" tabindex="0" />
                                <input type="hidden" class="ays-survey-img-src" name="<?php echo $html_name_prefix; ?>survey_cover_photo" value="<?php echo $survey_cover_photo; ?>">
                            </div>
                        </div>
                    </div>
                    <hr class="row ays-survey-cover-image-options-hr <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                    <div class="row ays-survey-cover-image-options   <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                        <div class="col-sm-4">
                            <label for='<?php echo $html_name_prefix; ?>survey_cover_image_height'>
                                <?php echo __('Height', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the height of the survey cover image.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-8 ays_divider_left ays_survey_display_flex_width">
                            <div>
                                <input type="number" id="<?php echo $html_name_prefix; ?>survey_cover_image_height" name="<?php echo $html_name_prefix; ?>survey_cover_image_height" class="ays-text-input " value="<?php echo $survey_cover_photo_height; ?>">
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div> <!-- Cover photo height -->
                    <hr class="row ays-survey-cover-image-options-hr <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                    <div class="row ays-survey-cover-image-options <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                        <div class="col-sm-4"> 
                            <label for='<?php echo esc_attr($html_name_prefix); ?>survey_cover_photo_mobile_height'>
                                <?php echo __('Height on mobile', "survey-maker"); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the height of the survey cover image for mobile devices.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-8 ays_divider_left ays_survey_display_flex_width">
                            <div>
                                <input type="number" id="<?php echo esc_attr($html_name_prefix); ?>survey_cover_photo_mobile_height" name="<?php echo esc_attr($html_name_prefix); ?>survey_cover_photo_mobile_height" class="ays-text-input " value="<?php echo esc_attr($survey_cover_photo_mobile_height); ?>">
                            </div>
                            <div class="ays_dropdown_max_width">
                                <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                            </div>
                        </div>
                    </div>
                    <hr class="row ays-survey-cover-image-options-hr <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                    <div class="row ays-survey-cover-image-options <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                        <div class="col-sm-4">
                            <label for='<?php echo $html_name_prefix; ?>survey_cover_image_height'>
                                <?php echo __('Image Position', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the position of the survey cover image.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-8 ays_divider_left">
                            <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-text-input-full" id='<?php echo $html_name_prefix; ?>survey_cover_image_pos' name='<?php echo $html_name_prefix; ?>survey_cover_image_pos'>
                                <option value="left_top"      <?php echo ($survey_cover_photo_position == 'left_top')      ? 'selected' : ''; ?>><?php echo __('Left Top',$this->plugin_name); ?></option>
                                <option value="left_center"   <?php echo ($survey_cover_photo_position == 'left_center')   ? 'selected' : ''; ?>><?php echo __('Left Center',$this->plugin_name); ?></option>
                                <option value="left_bottom"   <?php echo ($survey_cover_photo_position == 'left_bottom')   ? 'selected' : ''; ?>><?php echo __('Left Bottom',$this->plugin_name); ?></option>
                                <option value="center_top"    <?php echo ($survey_cover_photo_position == 'center_top')    ? 'selected' : ''; ?>><?php echo __('Center Top',$this->plugin_name); ?></option>
                                <option value="center_center" <?php echo ($survey_cover_photo_position == 'center_center') ? 'selected' : ''; ?>><?php echo __('Center Center',$this->plugin_name); ?></option>
                                <option value="center_bottom" <?php echo ($survey_cover_photo_position == 'center_bottom') ? 'selected' : ''; ?>><?php echo __('Center Bottom',$this->plugin_name); ?></option>
                                <option value="right_top"     <?php echo ($survey_cover_photo_position == 'right_top')     ? 'selected' : ''; ?>><?php echo __('Right Top',$this->plugin_name); ?></option>
                                <option value="right_center"  <?php echo ($survey_cover_photo_position == 'right_center')  ? 'selected' : ''; ?>><?php echo __('Right Center',$this->plugin_name); ?></option>
                                <option value="right_bottom"  <?php echo ($survey_cover_photo_position == 'right_bottom')  ? 'selected' : ''; ?>><?php echo __('Right Bottom',$this->plugin_name); ?></option>
                            </select>
                        </div>
                    </div> <!-- Cover photo position -->
                    <hr class="row ays-survey-cover-image-options-hr <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                    <div class="row ays-survey-cover-image-options <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                        <div class="col-sm-4">
                            <label for='<?php echo $html_name_prefix; ?>survey_cover_image_height'>
                                <?php echo __('Image object-fit', $this->plugin_name); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify how a survey cover image should be resized to fit its container.',$this->plugin_name)?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-8 ays_divider_left">
                            <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-text-input-full" id='<?php echo $html_name_prefix; ?>survey_cover_photo_object_fit' name='<?php echo $html_name_prefix; ?>survey_cover_photo_object_fit'>
                                <option value="cover"   <?php echo ($survey_cover_photo_object_fit == 'cover')   ? 'selected' : ''; ?>><?php echo __('Cover',$this->plugin_name); ?></option>
                                <option value="contain" <?php echo ($survey_cover_photo_object_fit == 'contain') ? 'selected' : ''; ?>><?php echo __('Contain',$this->plugin_name); ?></option>
                                <option value="unset"   <?php echo ($survey_cover_photo_object_fit == 'unset')   ? 'selected' : ''; ?>><?php echo __('None',$this->plugin_name); ?></option>                     
                            </select>
                        </div>
                    </div> <!-- Cover photo object-fit -->
                    <hr class="row ays-survey-cover-image-options-hr <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                    <div class="row ays-survey-cover-image-options <?php echo $survey_cover_photo != '' ? '' : 'display_none_not_important'; ?>">
                        <div class="col-sm-4">
                            <label for='<?php echo esc_attr($html_name_prefix); ?>survey_cover_only_first_section'>
                                <?php echo __('Show only in the first section', "survey-maker"); ?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to show the cover photo only for the first section.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-8 ays_divider_left ays_toggle_parent">
                            <div>
                                <input type="checkbox"
                                        name="<?php echo esc_attr($html_name_prefix); ?>survey_cover_only_first_section"
                                        id="<?php echo esc_attr($html_name_prefix); ?>survey_cover_only_first_section"
                                        value="on" class="ays_toggle ays_toggle_slide ays_toggle_checkbox" <?php echo ($survey_cover_only_first_section) ? "checked" : "" ?>>
                                <label for="<?php echo esc_attr($html_name_prefix); ?>survey_cover_only_first_section" class="ays_switch_toggle"></label>                           
                            </div>
                        </div>
                    </div> <!-- Show only in the first section -->
                </div>
            </div>
            <hr/>
		    <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Question styles',$this->plugin_name); ?></p>
		    <hr/>
		    <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_font_size'>
                        <?php echo __('Question font size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size of the survey questions in pixels.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_question_font_size'name='ays_survey_question_font_size' value="<?php echo $survey_question_font_size; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Question font size -->
            <hr/>
		    <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_font_size_mobile'>
                        <?php echo __('Question font size on mobile', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size of the survey questions in pixels on mobile devices.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_question_font_size_mobile' name='ays_survey_question_font_size_mobile' value="<?php echo $survey_question_font_size_mobile; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Question font size on mobile -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_question_title_alignment">
                        <?php echo __('Question alignment',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the alignment of the question title.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown" id="ays_survey_question_title_alignment" name="ays_survey_question_title_alignment">
                        <option value="left"   <?php echo ($survey_question_title_alignment == 'left')   ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                        <option value="center" <?php echo ($survey_question_title_alignment == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                        <option value="right"  <?php echo ($survey_question_title_alignment == 'right')  ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Question title alignment -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label>
                        <?php echo __('Questions image styles',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Configure the sizing of your question image.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="ays_survey_question_image_width">
                                <?php echo __('Image width',$this->plugin_name)?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the question image width in pixels.',$this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                            <div class="ays_survey_display_flex_width">
                                <div>
                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_survey_question_image_width" name="ays_survey_question_image_width" value="<?php echo $survey_question_image_width; ?>"/>
                                </div>
                                <div class="ays_dropdown_max_width">
                                    <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                                </div>
                            </div>
                            <span class="ays_survey_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name); ?></span>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="ays_survey_question_image_height">
                                <?php echo __('Image height',$this->plugin_name)?>
                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the question image height in pixels.',$this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                            <div class="ays_survey_display_flex_width">
                                <div>
                                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_survey_question_image_height" name="ays_survey_question_image_height" value="<?php echo $survey_question_image_height; ?>"/>
                                </div>
                                <div class="ays_dropdown_max_width">
                                    <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="ays_survey_question_image_sizing">
                                <?php echo __('Image sizing', $this->plugin_name ); ?>
                                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                                    echo htmlspecialchars( sprintf(
                                        __('Configure the scales of the question images.',$this->plugin_name) . '<ul class="ays_help_ul"><li>' .
                                        __('%sCover:%s Resize the image so that it fills the whole container. The image may be trimmed to fit.',$this->plugin_name) . '</li><li>' .
                                        __('%sContain:%s Resize the image to be displayed fully.',$this->plugin_name) . '</li><li>' .
                                        __('%sNone:%s The image is not resized at all.',$this->plugin_name) . '</li><li>' .
                                        __('%sUnset:%s The variable does not exist.',$this->plugin_name) . '</li></ul>',
                                        '<em>',
                                        '</em>',
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
                            <select name="ays_survey_question_image_sizing" id="ays_survey_question_image_sizing" class="ays-text-input ays-text-input-short ays_survey_aysDropdown" style="display:block;">
                                <option value="cover" <?php echo $survey_question_image_sizing == 'cover' ? 'selected' : ''; ?>><?php echo __( "Cover", $this->plugin_name ); ?></option>
                                <option value="contain" <?php echo $survey_question_image_sizing == 'contain' ? 'selected' : ''; ?>><?php echo __( "Contain", $this->plugin_name ); ?></option>
                                <option value="none" <?php echo $survey_question_image_sizing == 'none' ? 'selected' : ''; ?>><?php echo __( "None", $this->plugin_name ); ?></option>
                                <option value="unset" <?php echo $survey_question_image_sizing == 'unset' ? 'selected' : ''; ?>><?php echo __( "Unset", $this->plugin_name ); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div> <!-- Questions Image Styles -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_padding'>
                        <?php echo __('Question padding', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the padding of the survey questions box in pixels.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_question_padding' name='ays_survey_question_padding' value="<?php echo $survey_question_padding; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Question padding -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_caption_text_color'>
                        <?php echo __('Question caption text color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the color of the question caption texts.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_question_caption_text_color' data-alpha="true" name='ays_survey_question_caption_text_color' value="<?php echo $survey_question_caption_text_color; ?>"/>
                </div>
            </div> <!-- Question caption text color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_caption_text_alignment'>
                        <?php echo __('Question caption text alignment', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the alignment of the question caption texts.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown" id="ays_survey_question_caption_text_alignment" name="ays_survey_question_caption_text_alignment">
                        <option value="left"   <?php echo ($survey_question_caption_text_alignment == 'left')   ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                        <option value="center" <?php echo ($survey_question_caption_text_alignment == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                        <option value="right"  <?php echo ($survey_question_caption_text_alignment == 'right')  ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Question caption text alignment -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_caption_font_size'>
                        <?php echo __('Question caption font size', "survey-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size of the question caption texts.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_question_caption_font_size' name='ays_survey_question_caption_font_size' value="<?php echo esc_attr($survey_question_caption_font_size); ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Question caption font size -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_question_caption_font_size_on_mobile'>
                        <?php echo __('Question caption font size on mobile', "survey-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the font size of the question caption texts.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_question_caption_font_size_on_mobile' name='ays_survey_question_caption_font_size_on_mobile' value="<?php echo esc_attr($survey_question_caption_font_size_on_mobile); ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Question caption font size on mobile -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_question_caption_text_transform">
                        <?php echo __('Question caption text transform',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text transformation of question captions, such as converting to uppercase or lowercase.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-aysDropdown-answer-view" id="ays_survey_question_caption_text_transform" name="ays_survey_question_caption_text_transform">
                        <option value="none" <?php echo ($survey_question_caption_text_transform == 'none') ? 'selected' : ''; ?>><?php echo __('Default',"survey-maker"); ?></option>
                        <option value="capitalize" <?php echo ($survey_question_caption_text_transform == 'capitalize') ? 'selected' : ''; ?>><?php echo __('Capitalize',"survey-maker"); ?></option>
                        <option value="uppercase" <?php echo ($survey_question_caption_text_transform == 'uppercase') ? 'selected' : ''; ?>><?php echo __('Uppercase',"survey-maker"); ?></option>
                        <option value="lowercase" <?php echo ($survey_question_caption_text_transform == 'lowercase') ? 'selected' : ''; ?>><?php echo __('Lowercase',"survey-maker"); ?></option>
                    </select>
                </div>
            </div> <!-- Question caption text transform -->
            <hr/>            
		    <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Answer styles',$this->plugin_name); ?></p>
		    <hr/>
		    <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_answer_font_size'>
                        <?php echo __('Answer font size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the answers in pixels.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_answer_font_size'name='ays_survey_answer_font_size' value="<?php echo $survey_answer_font_size; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Answer font size -->
            <hr/>
		    <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_answer_font_size_on_mobile'>
                        <?php echo __('Answer font size on mobile', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the font size of the answers in pixels on mobile devices.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_answer_font_size_on_mobile' name='ays_survey_answer_font_size_on_mobile' value="<?php echo $survey_answer_font_size_on_mobile; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Answer font size on mobile -->
            <hr/>            
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_answers_view">
                        <?php echo __('Answer view',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Arrange the answers by the following layouts. Note that if at least one of the answers has an image, the answers will be shown in a grid layout. In this case, it won't matter if you have chosen the list layout.",$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-show-grid ays-survey-aysDropdown-answer-view" id="ays_survey_answers_view" name="ays_survey_answers_view">
                        <option value="list" <?php echo ($survey_answers_view == 'list') ? 'selected' : ''; ?>><?php echo __('List',$this->plugin_name); ?></option>
                        <option value="grid" <?php echo ($survey_answers_view == 'grid') ? 'selected' : ''; ?>><?php echo __('Grid',$this->plugin_name); ?></option>
                    </select>
                    <select name="ays_survey_grid_view_count" id="ays_survey_grid_view_count" class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-grid-view-type <?php echo ($survey_answers_view == 'grid') ? '' : 'display_none'; ?>" style="">
                        <option value="adaptive" <?php echo ($survey_grid_view_count == 'adaptive') ? 'selected' : ''; ?>> 
                            <?php echo __('Adaptive',$this->plugin_name); ?>
                        </option>
                        <option value="2" <?php echo ($survey_grid_view_count == '2') ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo ($survey_grid_view_count == '3') ? 'selected' : ''; ?>>3</option>    
                        <option value="4" <?php echo ($survey_grid_view_count == '4') ? 'selected' : ''; ?>>4</option>
                    </select>
                </div>
            </div> <!-- Answer view -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_answers_view_alignment">
                        <?php echo __('Answer view alignment',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                            echo htmlspecialchars( sprintf(__('Specify the alignment of the answers',$this->plugin_name) . '
                                <ul class="ays_help_ul">
                                    <li>' .__('%s Space around %s - Displays the answers with space before, between, and after the lines (Only for grid view).',$this->plugin_name) . '</li>
                                    <li>' .__('%s Space between %s - Displays the answers with space between the lines (Only for grid view).',$this->plugin_name) . '</li>
                                    <li>' .__('%s Left %s - Displays the answers at the beginning of the container.',$this->plugin_name) . '</li>
                                    <li>' .__('%s Right %s - Displays the answers at the end of the container.',$this->plugin_name) . '</li>
                                    <li>' .__('%s Center %s - Displays the answers at the center of the container.',$this->plugin_name) .'</li>
                                </ul>',
                                '<em>',
                                '</em>',
                                '<em>',
                                '</em>',
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
                <div class="col-sm-7 ays_divider_left ays-gagags">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown ays-survey-aysDropdown-answer-view-alignment" id="ays_survey_answers_view_alignment" name="ays_survey_answers_view_alignment" >
                        <?php if($survey_answers_view != 'list'): ?>
                        <option value="space-around" <?php echo ($survey_answers_view_alignment == 'space-around') ? 'selected' : ''; ?>><?php echo __('Space around',$this->plugin_name); ?></option>
                        <option value="space-between" <?php echo ($survey_answers_view_alignment == 'space-between') ? 'selected' : ''; ?>><?php echo __('Space between',$this->plugin_name); ?></option>                       
                        <?php endif;?>
                        <option value="flex-start" <?php echo ($survey_answers_view_alignment == 'flex-start') ? 'selected' : ''; ?>><?php echo __('Left',$this->plugin_name); ?></option>
                        <option value="flex-end" <?php echo ($survey_answers_view_alignment == 'flex-end') ? 'selected' : ''; ?>><?php echo __('Right',$this->plugin_name); ?></option>
                        <option value="center" <?php echo ($survey_answers_view_alignment == 'center') ? 'selected' : ''; ?>><?php echo __('Center',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Answer view alignment-->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_answers_object_fit">
                        <?php echo __('Answer image sizing',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                            echo htmlspecialchars( sprintf(
                                __('Define the size of the images added to the answers. Choose from the following properties:',$this->plugin_name) . '<ul class="ays_help_ul"><li>' .
                                __('%sCover:%s Resize the image to cover the whole container. The image may be trimmed to fit.',$this->plugin_name) . '</li><li>' .
                                __('%sFill:%s Resize the image to cover the whole container. The image may be stretched to fit.',$this->plugin_name) . '</li><li>' .
                                __('%sContain:%s Resize the image to be displayed fully.',$this->plugin_name) . '</li><li>' .
                                __('%sUnset:%s Rescale the image to be smaller than contain or none.',$this->plugin_name) . '</li><li>' .
                                __('%sNone:%s The image is not resized.',$this->plugin_name) . '</li></ul>',
                                '<em>',
                                '</em>',
                                '<em>',
                                '</em>',
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
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short ays_survey_aysDropdown" id="ays_survey_answers_object_fit" name="ays_survey_answers_object_fit">
                        <option value="cover" <?php echo ($survey_answers_object_fit == 'cover') ? 'selected' : ''; ?>><?php echo __('Cover',$this->plugin_name); ?></option>
                        <option value="fill" <?php echo ($survey_answers_object_fit == 'fill') ? 'selected' : ''; ?>><?php echo __('Fill',$this->plugin_name); ?></option>
                        <option value="contain" <?php echo ($survey_answers_object_fit == 'contain') ? 'selected' : ''; ?>><?php echo __('Contain',$this->plugin_name); ?></option>
                        <option value="scale-down" <?php echo ($survey_answers_object_fit == 'scale-down') ? 'selected' : ''; ?>><?php echo __('Scale-down',$this->plugin_name); ?></option>
                        <option value="none" <?php echo ($survey_answers_object_fit == 'none') ? 'selected' : ''; ?>><?php echo __('None',$this->plugin_name); ?></option>
                    </select>
                </div>
            </div> <!-- Answer Object fit -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_answers_padding">
                        <?php echo __('Answer padding',$this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the distance between the text and the border of the answer box in pixels․',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_answers_padding' name='ays_survey_answers_padding' value="<?php echo $survey_answers_padding; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Answer padding -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_answers_gap">
                        <?php echo __('Answer gap',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the gap size between answers in pixels.', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_answers_gap' name='ays_survey_answers_gap' value="<?php echo $survey_answers_gap; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Answers gap -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_answers_image_size">
                        <?php echo __('Answer image size',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the size of answers image in pixels.', $this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_answers_image_size' name='ays_survey_answers_image_size' value="<?php echo $survey_answers_image_size; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Answers image size -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_stars_color'>
                        <?php echo __('Change Stars color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Change the Stars Icon color in all Star Rating questions.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_stars_color' data-alpha="true" name='ays_survey_stars_color' value="<?php echo $survey_stars_color; ?>"/>
                </div>
            </div> <!-- Stars answers Color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label>
                        <?php echo esc_attr__('Slider questions bubble colors',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Configure the slider questions bubble colors.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_slider_question_bubble_bg_color">
                                <?php echo __('Background color',"survey-maker")?>
                            </label>                            
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="ays-text-input" id='ays_survey_slider_question_bubble_bg_color' data-alpha="true" name='ays_survey_slider_question_bubble_bg_color' value="<?php echo $survey_slider_question_bubble_bg_color; ?>"/>
                        </div>
                    </div>                    
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_slider_question_bubble_text_color">
                                <?php echo __('Text color',"survey-maker")?>
                            </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="ays-text-input" id='ays_survey_slider_question_bubble_text_color' data-alpha="true" name='ays_survey_slider_question_bubble_text_color' value="<?php echo $survey_slider_question_bubble_text_color; ?>"/>
                        </div>
                    </div>                    
                </div>
            </div><!-- Slider question bubble colors -->
        </div>
        <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">

        </div> <!-- Live preview container -->
    </div>
    <hr/>
    <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Buttons styles',$this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row"> <!-- Buttons Styles -->
        <div class="col-lg-7 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_button_bg_color'>
                        <?php echo __('Button background color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the survey buttons.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_button_bg_color' name='ays_survey_button_bg_color' data-alpha="true" value="<?php echo $survey_buttons_bg_color; ?>"/>
                </div>
            </div> <!-- Button Background Color -->            
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_buttons_text_color'>
                        <?php echo __('Button text color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the color of the button texts.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_buttons_text_color' data-alpha="true" name='ays_survey_buttons_text_color' value="<?php echo $survey_buttons_text_color; ?>"/>
                </div>
            </div> <!-- Buttons text Color -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_buttons_size">
                        <?php echo __('Button size',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the size of the buttons in your survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short" id="ays_survey_buttons_size" name="ays_survey_buttons_size">
                        <option value="small" <?php echo ($survey_buttons_size == 'small') ? 'selected' : ''; ?>>
                            <?php echo __('Small',$this->plugin_name); ?>
                        </option>
                        <option value="medium" <?php echo ( ($survey_buttons_size == 'medium') || !isset($options['survey_buttons_size']) ) ? 'selected' : ''; ?>>
                            <?php echo __('Medium',$this->plugin_name); ?>
                        </option>
                        <option value="large" <?php echo ($survey_buttons_size == 'large') ? 'selected' : ''; ?>>
                            <?php echo __('Large',$this->plugin_name); ?>
                        </option>
                    </select>
                </div>
            </div> <!-- Button size -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_buttons_font_size'>
                        <?php echo __('Button font-size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text font size inside the buttons in pixels. Note that the button size will change simultaneously with the button text size.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_buttons_font_size' name='ays_survey_buttons_font_size' value="<?php echo $survey_buttons_font_size; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Buttons font size -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_buttons_mobile_font_size'>
                        <?php echo __('Buttons mobile font size', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text font size inside the survey in pixels for mobile devices.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_buttons_mobile_font_size' name='ays_survey_buttons_mobile_font_size' value="<?php echo $survey_buttons_mobile_font_size; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Buttons mobile font size -->
            <hr>
            <div class="form-group row">
	            <div class="col-sm-5">
	                <label for="ays_survey_buttons_left_right_padding">
	                    <?php echo __('Button padding',$this->plugin_name)?> (px)
	                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the distance between the text and the border of the buttons in pixels.',$this->plugin_name); ?>">
	                        <i class="ays_fa ays_fa_info_circle"></i>
	                    </a>
	                </label>
	            </div>
	            <div class="col-sm-7 ays_divider_left" style="padding-left: 0;">
	                <div class="col-sm-5" style="display: inline-block;">
	                    <span class="ays_survey_small_hint_text"><?php echo __('Left / Right',$this->plugin_name); ?></span>
	                    <input type="number" class="ays-text-input" id='ays_survey_buttons_left_right_padding' name='ays_survey_buttons_left_right_padding' value="<?php echo $survey_buttons_left_right_padding; ?>" style="width: 100px;" />
	                </div>
	                <div class="col-sm-5 ays_divider_left" style="display: inline-block;">
	                    <span class="ays_survey_small_hint_text"><?php echo __('Top / Bottom',$this->plugin_name); ?></span>
	                    <input type="number" class="ays-text-input" id='ays_survey_buttons_top_bottom_padding' name='ays_survey_buttons_top_bottom_padding' value="<?php echo $survey_buttons_top_bottom_padding; ?>" style="width: 100px;" />
	                </div>
	            </div>
	        </div> <!-- Buttons padding -->
            <hr>
	        <div class="form-group row">
	            <div class="col-sm-5">
	                <label for="ays_survey_buttons_border_radius">
	                    <?php echo __('Button border-radius',$this->plugin_name)?>
	                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Make the corners of the buttons rounder by setting a pixel value.',$this->plugin_name); ?>">
	                        <i class="ays_fa ays_fa_info_circle"></i>
	                    </a>
	                </label>
	            </div>
	            <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
	                    <input type="number" class="ays-text-input ays-text-input-short" id="ays_survey_buttons_border_radius" name="ays_survey_buttons_border_radius" value="<?php echo $survey_buttons_border_radius; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
	        </div> <!-- Buttons border radius -->
	        <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_buttons_alignment">
                        <?php echo __('Button alignment',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the alignment of the buttons in your survey.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short" id="ays_survey_buttons_alignment" name="ays_survey_buttons_alignment">
                        <option value="left" <?php echo ($survey_buttons_alignment == 'left' ) ? 'selected' : ''; ?>>
                            <?php echo __('Left',$this->plugin_name); ?>
                        </option>
                        <option value="center" <?php echo ( $survey_buttons_alignment == 'center' ) ? 'selected' : ''; ?>>
                            <?php echo __('Center',$this->plugin_name); ?>
                        </option>
                        <option value="right" <?php echo ($survey_buttons_alignment == 'right') ? 'selected' : ''; ?>>
                            <?php echo __('Right',$this->plugin_name); ?>
                        </option>
                    </select>
                </div>
            </div> <!-- Buttons alignment -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_buttons_top_distance'>
                        <?php echo __('Buttons top distance', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Specify the button's distance from the top in pixels.",$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_display_flex_width">
                    <div>
                        <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_buttons_top_distance' name='ays_survey_buttons_top_distance' value="<?php echo $survey_buttons_top_distance; ?>"/>
                    </div>
                    <div class="ays_dropdown_max_width">
                        <input type="text" value="px" class='ays-form-hint-for-size' disabled>
                    </div>
                </div>
            </div> <!-- Buttons top distance -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_custom_class">
                        <?php echo __('Custom class for survey container',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Use your custom HTML class for adding your custom styles to the survey container.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" name="ays_survey_custom_class" id="ays_survey_custom_class" placeholder="myClass myAnotherClass..." value="<?php echo $survey_custom_class; ?>">
                </div>
            </div> <!-- Custom class for quiz container -->
        	<hr/>
        </div>
        <hr/>
        <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">
            <div id="ays_buttons_styles_tab" class="display_none" style="position:sticky;top:50px; margin:auto;">
                <div class="ays_buttons_div" style="justify-content: center; overflow:hidden;">
                    <input type="button" name="next" class="action-button ays-quiz-live-button" style="padding:0;" value="<?php echo __( "Start", $this->plugin_name ); ?>">
                </div>
            </div>
        </div> <!-- Buttons Styles Live -->
    </div> <!-- Buttons Styles End -->
    <hr/>
    <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Admin note styles',$this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row"> <!-- Admin note Styles -->
        <div class="col-lg-7 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_admin_note_color'>
                        <?php echo __('Note color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the admin note.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_admin_note_color' name='ays_survey_admin_note_color' data-alpha="true" value="<?php echo $survey_admin_note_color; ?>"/>
                </div>
            </div> <!-- Admin note color -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_admin_note_text_transform">
                        <?php echo __('Text transform',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify how the text appears in all-uppercase or all-lowercase, or with each word capitalized.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short" id="ays_survey_admin_note_text_transform" name="ays_survey_admin_note_text_transform">
                        <option value="none" <?php echo ($survey_admin_note_text_transform == 'none') ? 'selected' : ''; ?>>
                            <?php echo __('None',$this->plugin_name); ?>
                        </option>
                        <option value="capitalize" <?php echo ($survey_admin_note_text_transform == 'capitalize') ? 'selected' : ''; ?>>
                            <?php echo __('Capitalize',$this->plugin_name); ?>
                        </option>
                        <option value="uppercase" <?php echo ( ($survey_admin_note_text_transform == 'uppercase') || !isset($options['survey_buttons_size']) ) ? 'selected' : ''; ?>>
                            <?php echo __('Uppercase',$this->plugin_name); ?>
                        </option>
                        <option value="lowercase" <?php echo ($survey_admin_note_text_transform == 'lowercase') ? 'selected' : ''; ?>>
                            <?php echo __('Lowercase',$this->plugin_name); ?>
                        </option>
                    </select>
                </div>
            </div> <!-- Admin note text transform -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_admin_note_font_size'>
                        <?php echo __('Font size', $this->plugin_name); ?> (px)
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text font size of the text in pixels.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="number" class="ays-text-input ays-text-input-short" id='ays_survey_admin_note_font_size' name='ays_survey_admin_note_font_size' value="<?php echo $survey_admin_note_font_size; ?>"/>
                </div>
            </div> <!-- Admin note font size -->
        </div>
        <hr/>
        <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;"></div>
    </div> <!-- Admin note Styles End -->
    <hr>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="ays_survey_custom_css">
                <?php echo __('Custom CSS',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enter your own CSS code.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-9">
        	<textarea class="ays-textarea" id="ays_survey_custom_css" name="ays_survey_custom_css" cols="30" rows="10"><?php echo $survey_custom_css; ?></textarea>
        </div>
    </div> <!-- Custom CSS -->
    <hr>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="ays_survey_custom_class">
                <?php echo __('Reset styles',$this->plugin_name)?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Reset styles to default values',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-9 ays_divider_left">
            <button class="ays-button button-secondary ays-survey-reset-styles-button"><?php echo __('Reset',$this->plugin_name); ?></button>
        </div>
    </div> <!-- Reset styles -->
</div>
