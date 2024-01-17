<div id="tab6" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab6') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Start page settings',$this->plugin_name)?></p>
    <p><?php echo __("Configure your survey's start page by adding the title, description and styling it the way you want. The start page will be shown to the survey takers before displaying the survey.",$this->plugin_name)?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="ays_survey_enable_start_page">
                <?php echo __('Enable start page',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to add a start page to your survey. After enabling this option, a new tab will appear next to the Settings tab, where you can configure Start Page settings.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-9">
            <input type="checkbox" id="ays_survey_enable_start_page" name="ays_survey_enable_start_page" value="on" <?php echo $survey_enable_start_page ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable start page -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="ays_survey_start_page_title">
                <?php echo __('Start page title',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give a title to the start page',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-9">
            <input type="text" class="ays-text-input" id="ays_survey_start_page_title" name="ays_survey_start_page_title" value="<?php echo $survey_start_page_title; ?>"/>
        </div>
    </div> <!-- Start page title -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-3">
            <label for="ays_survey_start_page_description">
                <?php echo __('Start page description',$this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide some information about the survey. This will show up on the start page.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-9">
            <?php
            $content = $survey_start_page_description;
            $editor_id = 'ays_survey_start_page_description';
            $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_start_page_description', 'editor_class' => 'ays-textarea', 'media_elements' => true);
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
    </div> <!-- Start page description -->
    <hr/>
    <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Start page styles',$this->plugin_name); ?></p>
    <hr/>
    <div class="form-group row"> <!-- Start page Styles -->
        <div class="col-lg-7 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_start_page_background_color'>
                        <?php echo __('Background color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the background color of the start page.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_start_page_background_color' data-alpha="true" name='ays_survey_start_page_background_color' value="<?php echo $survey_start_page_background_color; ?>"/>
                </div>
            </div> <!-- Start page Background Color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for='ays_survey_start_page_text_color'>
                        <?php echo __('Text color', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text color of the start page.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" id='ays_survey_start_page_text_color' data-alpha="true"name='ays_survey_start_page_text_color' value="<?php echo $survey_start_page_text_color; ?>"/>
                </div>
            </div> <!-- Start page Text Color -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="ays_survey_start_page_custom_class">
                        <?php echo __('Custom class for start page container',$this->plugin_name)?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Use your custom HTML class for adding your custom styles to the survey start page container.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-7 ays_divider_left">
                    <input type="text" class="ays-text-input" name="ays_survey_start_page_custom_class" id="ays_survey_start_page_custom_class" placeholder="myClass myAnotherClass..." value="<?php echo $survey_start_page_custom_class; ?>">
                </div>
            </div> <!-- Custom class for start page container -->
        </div>
        <hr/>
        <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">
            <div id="ays_buttons_styles_tab" class="display_none" style="position:sticky;top:50px; margin:auto;">
                <div class="ays_buttons_div" style="justify-content: center; overflow:hidden;">
                    <input type="button" name="next" class="action-button ays-quiz-live-button" style="padding:0;" value="<?php echo __( "Start", $this->plugin_name ); ?>">
                </div>
            </div>
        </div> <!-- Start page Styles Live -->
    </div> <!-- Start page Styles End -->
</div>
