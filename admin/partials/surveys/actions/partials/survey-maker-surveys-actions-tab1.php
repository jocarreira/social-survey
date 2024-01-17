<div id="tab1" class="m2 ays-survey-tab-content <?php echo ($ays_tab == 'tab1') ? 'ays-survey-tab-content-active' : ''; ?>">
    <div class="form-group row">
        <div class="col-sm-12 col-lg-11">
            <div class="form-group row">
                <div class="col-sm-6">
                    <p class="ays-subtitle"><?php echo __('Questionário',$this->plugin_name)?></p>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center justify-content-end w-100 h-100 mt-2 flex-wrap" style="gap: 10px;">
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=developer" target="_blank" style="padding: 0;" class="ays-survey-ai-survey-builder-icon" >
                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/ai-survey-builder.png" alt="ChatGPT Survey Builder" title="<?php echo __("This feature is available only in Agency version" , "survey-maker") ?>">
                        </a>
                        <?php if(isset($survey_main_url) && $survey_main_url != ''): ?>
                            <a data-toggle="tooltip" title="<?php echo esc_attr__("After clicking on the View button you will be redirected to the particular survey link.","survey-maker");?>" href="<?php echo $survey_main_url != '' ? esc_url($survey_main_url) : 'javascript:void(0)'; ?>" target="<?php echo $survey_main_url != '' ? '_blank' : ''; ?>" type="button" class="button button-primary" style="margin-right: 12px;">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <span style="margin-left: 5px;"><?php echo __( 'View', "survey-maker" ); ?></span>
                            </a>
                        <?php endif; ?>
                        <button type="button" disabled="disabled" class="ays-survey-open-questions-library button button-primary"><?php echo __( 'Questions library', $this->plugin_name ); ?></button>
                        <button type="button" disabled="disabled" class="button button-primary ays-survey-open-templates-modal"><?php echo __("Survey templates" ,  $this->plugin_name)?></button>
                        <button type="button" disabled="disabled" class="button button-primary ays-survey-open-import-modal"><?php echo __("Import questions" ,  $this->plugin_name)?></button>
                    </div>
                </div>
            </div>
            <?php if($id !== null): ?>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label> <?php echo __( "Shortcode text for editor", $this->plugin_name ); ?> </label>
                    </div>
                    <div class="col-sm-9">
                        <p style="font-size:14px; font-style:italic;">
                            <?php echo __("To insert the Survey into a page, post or text widget, copy shortcode", $this->plugin_name); ?>
                            <strong class="ays-survey-shortcode-box" onClick="selectElementContents(this)" style="font-size:16px; font-style:normal;" class="ays_help" data-toggle="tooltip" title="<?php echo __('Click for copy',$this->plugin_name);?>" ><?php echo "[ays_survey id='".$id."']"; ?></strong>
                            <?php echo " " . __( "and paste it at the desired place in the editor.", $this->plugin_name); ?>
                        </p>
                    </div>
                    <hr/>
                </div>
            <?php endif;?>
            <div class="form-group row">
                <div class="col-sm-12">
                    <p class="m-0 text-right">
                        <a class="ays-survey-collapse-all" href="javascript:void(0);"><?php echo __( "Collapse All", $this->plugin_name ); ?></a>
                        <span>|</span>
                        <a class="ays-survey-expand-all" href="javascript:void(0);"><?php echo __( "Expand All", $this->plugin_name ); ?></a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-12 col-lg-11">
            <div class="ays-survey-sections-conteiner">
            <?php
            if(empty($sections_ids)){
                ?>
                <div class="ays-survey-section-box ays-survey-new-section" data-name="<?php echo $html_name_prefix; ?>section_add" data-id="1">
                    <input type="hidden" class="ays-survey-section-collapsed-input" name="<?php echo $html_name_prefix; ?>section_add[1][options][collapsed]" value="expanded">
                    <div class="ays-survey-section-wrap-collapsed display_none">
                        <div class="ays-survey-section-head-wrap">
                            <div class="ays-survey-section-head-top <?php echo $multiple_sections ? '' : 'display_none'; ?>">
                                <div class="ays-survey-section-counter">
                                    <span>
                                        <span><?php echo __( 'Section', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-section-number"><?php echo 1; ?></span>
                                        <span><?php echo __( 'of', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-sections-count"><?php echo 1; ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="ays-survey-section-head">
                                <div class="ays-survey-section-dlg-dragHandle">
                                    <div class="ays-survey-icons">
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                    </div>
                                </div>
                                <div class="ays-survey-section-wrap-collapsed-contnet">
                                    <div class="ays-survey-action-questions-count appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Questions count',$this->plugin_name)?>"><span>1</span></div>
                                    <div class="ays-survey-section-wrap-collapsed-contnet-text"></div>
                                    <div>
                                        <div class="ays-survey-action-expand-section appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Expand section',$this->plugin_name)?>">
                                            <div class="ays-section-img-icon-content">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/expand-section.svg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-answer-icon-box ays-survey-section-actions-more dropdown">
                                    <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button type="button" class="dropdown-item ays-survey-delete-section display_none"><?php echo __( 'Delete section', $this->plugin_name ); ?></button>
                                        <button type="button" class="dropdown-item ays-survey-duplicate-section"><?php echo __( 'Duplicate section', $this->plugin_name ); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ays-survey-section-wrap-expanded">
                        <div class="ays-survey-section-head-wrap">
                            <div class="ays-survey-section-head-top display_none">
                                <div class="ays-survey-section-counter">
                                    <span>
                                        <span><?php echo __( 'Section', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-section-number">1</span>
                                        <span><?php echo __( 'of', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-sections-count">1</span>
                                    </span>
                                </div>
                            </div>
                            <div class="ays-survey-section-head">
                                <!--  Section Title Start  -->
                                <div class="ays-survey-section-title-conteiner">
                                    <input type="text" class="ays-survey-section-title ays-survey-input" tabindex="0" name="<?php echo $html_name_prefix; ?>section_add[1][title]" placeholder="<?php echo __( 'Section title' , $this->plugin_name ); ?>" value=""/>
                                    <div class="ays-survey-input-underline"></div>
                                    <div class="ays-survey-input-underline-animation"></div>
                                </div>
                                <!--  Section Title End  -->

                                <!--  Section Description Start  -->
                                <div class="ays-survey-section-description-conteiner">
                                    <textarea class="ays-survey-section-description ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][description]" placeholder="<?php echo __( 'Section Description' , $this->plugin_name ); ?>"/></textarea>
                                    <div class="ays-survey-input-underline"></div>
                                    <div class="ays-survey-input-underline-animation"></div>
                                </div>
                                <!--  Section Description End  -->

                                <div class="ays-survey-section-actions">
                                    <div class="ays-survey-action-questions-count appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Questions count',$this->plugin_name)?>"><span>1</span></div>
                                    <div class="ays-survey-action-collapse-section appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Collapse section',$this->plugin_name)?>">
                                        <div class="ays-question-img-icon-content">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/collapse-section.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box ays-survey-section-actions-more dropdown">
                                        <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button type="button" class="dropdown-item ays-survey-collapse-section-questions"><?php echo __( 'Collapse section questions', $this->plugin_name ); ?></button>
                                            <input type="checkbox" hidden class="make-questions-required-checkbox">
                                            <button type="button" class="dropdown-item ays-survey-section-questions-required" data-flag="off"><?php echo __( 'Make questions required', $this->plugin_name ); ?> <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg" class="ays-survey-required-section-img"></button>
                                            <button type="button" class="dropdown-item ays-survey-duplicate-section"><?php echo __( 'Duplicate section', $this->plugin_name ); ?></button>
                                            <button type="button" class="dropdown-item ays-survey-delete-section display_none"><?php echo __( 'Delete section', $this->plugin_name ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="ays-survey-section-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][ordering]" value="1">
                        </div>
                        <div class="ays-survey-section-body">
                            <div class="ays-survey-section-questions">
                                <div class="ays-survey-question-answer-conteiner ays-survey-new-question" data-name="questions_add" data-id="1">
                                    <input type="hidden" class="ays-survey-question-collapsed-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][collapsed]" value="expanded">
                                    <input type="hidden" class="ays-survey-question-is-logic-jump" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][is_logic_jump]" value="off">
                                    <input type="hidden" class="ays-survey-question-user-explanation" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][user_explanation]" value="off">
                                    <input type="hidden" class="ays-survey-question-admin-note-saver" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_admin_note]" value="off">
                                    <input type="hidden" class="ays-survey-question-url-parameter-saver" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_url_parameter]" value="off">
                                    <input type="hidden" class="ays-survey-question-hide-results-saver" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_hide_results]" value="off">
                                    <div class="ays-survey-question-wrap-collapsed display_none">
                                        <div class="ays-survey-question-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-horizontal.svg">
                                            </div>
                                        </div>
                                        <div class="ays-survey-question-wrap-collapsed-contnet ays-survey-question-wrap-collapsed-contnet-box">
                                            <div class="ays-survey-question-wrap-collapsed-contnet-text"></div>
                                            <div>
                                                <div class="ays-survey-action-expand-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Expand question',$this->plugin_name)?>">
                                                    <div class="ays-question-img-icon-content">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/expand-section.svg">
                                                    </div>
                                                </div>
                                                <div class="ays-survey-answer-icon-box ays-survey-question-more-actions droptop ">
                                                    <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                                        <div class="ays-question-img-icon-content">
                                                            <div class="ays-question-img-icon-content-div">
                                                                <div class="ays-survey-icons">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <button type="button" class="dropdown-item ays-survey-action-delete-question" ><?php echo __( 'Delete question', $this->plugin_name ); ?></button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action ays-survey-question-id-copy-box" data-toggle="tooltip" class="ays_help" data-action="copy-question-id" onClick="selectElementContents(this)" title="<?php echo __('Click for copy',$this->plugin_name);?>">
                                                            <?php echo __( 'Question ID', $this->plugin_name ); ?>
                                                            <strong class="ays-survey-question-id-copy" style="font-size:16px; font-style:normal;"  ></strong>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ays-survey-question-wrap-expanded">
                                        <div class="ays-survey-question-conteiner">
                                            <div class="ays-survey-question-dlg-dragHandle">
                                                <div class="ays-survey-icons ays-survey-icons-hidden">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-horizontal.svg">
                                                    <input type="hidden" class="ays-survey-question-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][ordering]" value="1">
                                                </div>
                                            </div>
                                            <div class="ays-survey-question-row-wrap">
                                                <div class="ays-survey-question-row">
                                                    <div class="ays-survey-question-box">
                                                        <div class="ays-survey-question-input-box">
                                                            <textarea class="ays-survey-remove-default-border ays-survey-question-input-textarea ays-survey-question-input ays-survey-input" 
                                                                name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][title]" 
                                                                placeholder="<?php echo __( 'Question', $this->plugin_name ); ?>" style="height: 24px;"></textarea>
                                                            <input type="hidden" name="<?php echo $html_name_prefix; ?>question_ids[]" value="">
                                                            <div class="ays-survey-input-underline"></div>
                                                            <div class="ays-survey-input-underline-animation"></div>
                                                        </div>
                                                        <div class="ays-survey-description-box ays-survey-question-input-box <?php echo $survey_default_type == 'html' ? 'display_none_not_important' : ''; ?>">
                                                            <textarea type="text" class="ays-survey-remove-default-border ays-survey-question-description-input-textarea ays-survey-description-input" 
                                                                name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][description]"
                                                                placeholder="<?php echo __( 'Question description', $this->plugin_name ); ?>" style="height: 24px;"></textarea>
                                                            <div class="ays-survey-input-underline"></div>
                                                            <div class="ays-survey-input-underline-animation"></div>
                                                        </div>
                                                        <div class="ays-survey-question-preview-box display_none"></div>
                                                    </div>
                                                    <div class="ays-survey-question-img-icon-box">
                                                        <div class="ays-survey-open-question-editor appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Open editor',$this->plugin_name)?>">
                                                            <div class="ays-question-img-icon-content">
                                                                <div class="ays-question-img-icon-content-div">
                                                                    <div class="ays-survey-icons">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/edit-content.svg">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" class="ays-survey-open-question-editor-flag" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][with_editor]" value="off">
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-question-img-icon-box">
                                                        <div class="ays-survey-add-question-image appsMaterialWizButtonPapericonbuttonEl" data-type="questionImgButton" data-type="questionImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                                            <div class="ays-question-img-icon-content">
                                                                <div class="ays-question-img-icon-content-div">
                                                                    <div class="ays-survey-icons">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-question-type-box">
                                                        <select name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][type]" tabindex="-1" class="ays-survey-question-type survey_default_type" aria-hidden="true" data-type="<?php echo $survey_default_type;?>">
                                                            <?php 
                                                                $selected = '';
                                                                foreach ( $question_types as $type_slug => $type ):
                                                                    if( $survey_default_type == $type_slug ){
                                                                        $selected = 'selected';
                                                                    }else{
                                                                        $selected = '';
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $type_slug; ?>" <?php echo $selected; ?>><?php echo $type; ?></option>
                                                                    <?php
                                                                endforeach;
                                                            ?>
                                                            <option disabled>Net promoter score (Agency)</option>
                                                            <option disabled>Ranking (Agency)</option>
                                                        </select>
                                                        <input type="hidden" class="ays-survey-check-type-before-change" value="<?php echo $survey_default_type; ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="ays-survey-question-img-icon-box">
                                                        <div class="ays-survey-action-collapse-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Collapse',$this->plugin_name)?>">
                                                            <div class="ays-question-img-icon-content">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/collapse-section.svg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ays-survey-question-image-container" style="display: none;" >
                                            <div class="ays-survey-question-image-body">
                                                <div class="ays-survey-question-image-wrapper aysFormeditorViewMediaImageWrapper">
                                                    <div class="ays-survey-question-image-pos aysFormeditorViewMediaImagePos">
                                                        <div class="d-flex">
                                                            <div class="dropdown mr-1">
                                                                <div class="ays-survey-question-edit-menu-button dropdown-menu-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <div class="ays-survey-icons">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item ays-survey-question-img-action" data-action="edit-image" href="javascript:void(0);"><?php echo __( 'Edit', $this->plugin_name ); ?></a>
                                                                    <a class="dropdown-item ays-survey-question-img-action" data-action="delete-image" href="javascript:void(0);"><?php echo __( 'Delete', $this->plugin_name ); ?></a>
                                                                    <a class="dropdown-item ays-survey-question-img-action" data-action="add-caption" href="javascript:void(0);"><?php echo __( 'Add a caption', $this->plugin_name ); ?></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <img class="ays-survey-question-img" src="" tabindex="0" aria-label="Captionless image" />
                                                        <input type="hidden" class="ays-survey-question-img-src" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][image]" value="">
                                                        <input type="hidden" class="ays-survey-question-img-caption-enable" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][image_caption_enable]" value="off">
                                                    </div>
                                                    <div class="ays-survey-question-image-caption-text-row display_none" >
                                                        <div class="ays-survey-question-image-caption-box-wrap">
                                                            <!-- <div class="ays-survey-answer-box-wrap"> -->
                                                                <!-- <div class="ays-survey-answer-box"> -->
                                                                    <!-- <div class="ays-survey-answer-box-input-wrap"> -->
                                                                        <input type="text" class="ays-survey-input ays-survey-question-image-caption" autocomplete="off" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][image_caption]">
                                                                        <div class="ays-survey-input-underline ays-survey-question-image-caption-input-underline"></div>
                                                                        <div class="ays-survey-input-underline-animation"></div>
                                                                    <!-- </div> -->
                                                                <!-- </div> -->
                                                            <!-- </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ays-survey-answers-conteiner">
                                        <?php 
                                            if($survey_default_type == 'radio' || $survey_default_type == 'select' || $survey_default_type == 'checkbox'){
                                                for( $i = 1; $i <= $survey_answer_default_count; $i++ ){
                                                ?>
                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="<?php echo $i;?>" data-name="answers_add">
                                                    <div class="ays-survey-answer-wrap">
                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                            </div>
                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][<?php echo $i; ?>][ordering]" value="<?php echo $i;?>">
                                                        </div>
                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                            <?php
                                                                if($survey_default_type == 'radio' || $survey_default_type == 'select'){
                                                                ?>
                                                                <div class="ays-survey-icons">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                </div>
                                                                <?php
                                                                }else if($survey_default_type == 'checkbox'){
                                                                    ?>
                                                                    <div class="ays-survey-icons">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                                                    </div>
                                                                <?php
                                                                }else{
                                                                    ?>
                                                                    <div class="ays-survey-icons">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                    </div>
                                                                <?php
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="ays-survey-answer-box-wrap">
                                                            <div class="ays-survey-answer-box">
                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                    <input type="text" class="ays-survey-input" autocomplete="off" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][<?php echo $i;?>][title]" placeholder="Option <?php echo $i;?>" value="Option <?php echo $i;?>" data-count="<?php echo $survey_answer_default_count; ?>">
                                                                    <div class="ays-survey-input-underline"></div>
                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-answer-icon-box">
                                                                <div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                                                    <div class="ays-question-img-icon-content">
                                                                        <div class="ays-question-img-icon-content-div">
                                                                            <div class="ays-survey-icons">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-answer-icon-box">
                                                                <span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" style="<?php echo $survey_answer_default_count > 1 ? '' : 'visibility: hidden;'; ?>" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                </span>
                                                            </div>
                                                            <div class="ays-survey-answer-logic-jump-wrap display_none">
                                                                <div class="ays-survey-answer-logic-jump-cont">
                                                                    <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][<?php echo $i;?>][options][go_to_section]">
                                                                        <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                                        <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                                        <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-image-container" style="display: none;">
                                                        <div class="ays-survey-answer-image-body">
                                                            <div class="ays-survey-answer-image-wrapper">
                                                                <div class="ays-survey-answer-image-wrapper-delete-wrap">
                                                                    <div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
                                                                        <span class="exportIcon">
                                                                            <div class="ays-survey-answer-image-wrapper-delete-icon-cont">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <img class="ays-survey-answer-img" src="" tabindex="0" aria-label="Captionless image" />
                                                                <input type="hidden" class="ays-survey-answer-img-src" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][<?php echo $i;?>][image]" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                }
                                            }elseif($survey_default_type == 'yesorno'){
                                                ?>
                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1">
                                                    <div class="ays-survey-answer-wrap">
                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                            </div>
                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][ordering]" value="1">
                                                        </div>
                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                            <div class="ays-survey-icons">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                            </div>
                                                        </div>
                                                        <div class="ays-survey-answer-box-wrap">
                                                            <div class="ays-survey-answer-box">
                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                <input type="text" class="ays-survey-input" autocomplete="off" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][title]" placeholder="<?php echo __("Yes", $this->plugin_name); ?>" value="<?php echo __("Yes", $this->plugin_name); ?>">
                                                                    <div class="ays-survey-input-underline"></div>
                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                </div>
                                                                <div class="ays-survey-answer-icon-box">
                                                                    <div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                                                        <div class="ays-question-img-icon-content">
                                                                            <div class="ays-question-img-icon-content-div">
                                                                                <div class="ays-survey-icons">
                                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="ays-survey-answer-icon-box">
                                                                    <span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                    </span>
                                                                </div>
                                                                <div class="ays-survey-answer-logic-jump-wrap display_none">
                                                                    <div class="ays-survey-answer-logic-jump-cont" >
                                                                        <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][options][go_to_section]">
                                                                            <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                                            <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                                            <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-image-container" style="display: none;">
                                                        <div class="ays-survey-answer-image-body">
                                                            <div class="ays-survey-answer-image-wrapper">
                                                                <div class="ays-survey-answer-image-wrapper-delete-wrap">
                                                                    <div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
                                                                        <span class="exportIcon">
                                                                            <div class="ays-survey-answer-image-wrapper-delete-icon-cont">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <img class="ays-survey-answer-img" src="" tabindex="0" aria-label="Captionless image" />
                                                                <input type="hidden" class="ays-survey-answer-img-src" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][image]" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="2">
                                                    <div class="ays-survey-answer-wrap">
                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                            </div>
                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][2][ordering]" value="2">
                                                        </div>
                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                            <div class="ays-survey-icons">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                            </div>
                                                        </div>
                                                        <div class="ays-survey-answer-box-wrap">
                                                            <div class="ays-survey-answer-box">
                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                    <input type="text" class="ays-survey-input" autocomplete="off" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][2][title]" placeholder="<?php echo __("No", $this->plugin_name) ?>" value="<?php echo __("No", $this->plugin_name) ?>">
                                                                    <div class="ays-survey-input-underline"></div>
                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                </div>
                                                                <div class="ays-survey-answer-icon-box">
                                                                    <div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                                                        <div class="ays-question-img-icon-content">
                                                                            <div class="ays-question-img-icon-content-div">
                                                                                <div class="ays-survey-icons">
                                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="ays-survey-answer-icon-box">
                                                                    <span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                    </span>
                                                                </div>
                                                                <div class="ays-survey-answer-logic-jump-wrap display_none">
                                                                    <div class="ays-survey-answer-logic-jump-cont" >
                                                                        <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][2][options][go_to_section]">
                                                                            <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                                            <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                                            <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-image-container" style="display: none;">
                                                        <div class="ays-survey-answer-image-body">
                                                            <div class="ays-survey-answer-image-wrapper">
                                                                <div class="ays-survey-answer-image-wrapper-delete-wrap">
                                                                    <div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
                                                                        <span class="exportIcon">
                                                                            <div class="ays-survey-answer-image-wrapper-delete-icon-cont">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <img class="ays-survey-answer-img" src="" tabindex="0" aria-label="Captionless image" />
                                                                <input type="hidden" class="ays-survey-answer-img-src" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][2][image]" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif( isset($survey_default_type) && in_array( $survey_default_type, $text_question_types ) ){
                                                ?>
                                                <div class="ays-survey-question-types <?php if($select_question_type == "hidden") {echo "display_none";}?>">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box ays-survey-question-type-all-text-types-box isDisabled <?php echo $survey_default_type; ?>">
                                                                <div class="ays-survey-question-types-box-body">
                                                                    <div class="ays-survey-question-types-input-box">
                                                                        <input type="text" class="ays-survey-remove-default-border ays-survey-question-types-input ays-survey-question-types-input-with-placeholder" autocomplete="off" tabindex="0" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][placeholder]" value="<?php echo esc_attr($question_types_placeholders[ $survey_default_type ]); ?>" placeholder="<?php echo $question_types_placeholders[ $survey_default_type ]; ?>" style="font-size: 14px;">                                                                    </div>
                                                                    <div class="ays-survey-question-types-input-underline"></div>
                                                                    <div class="ays-survey-question-types-input-focus-underline"></div>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-question-text-types-note-text"><span>* <?php echo __('You can insert your custom placeholder for input. Note your custom text will not be translated', $this->plugin_name); ?></span></div>
                                                            <?php if($survey_default_type == "phone"): ?>
                                                            <div class="ays-survey-question-types-box-phone-type-note">
                                                                <?php
                                                                    echo "<span>" . __( "Note: Phone question type can contain only numbers and the following signs + ( ) -", $this->plugin_name ) . "</span>";
                                                                ?>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif($survey_default_type == 'linear_scale'){
                                                ?>
                                                <div class="ays-survey-question-types_linear_scale">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box<?php echo $survey_default_type; ?>">
                                                                <div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght">
                                                                    <div class="ays-survey-question-types_linear_scale_span">
                                                                        <span style="font-size: 25px;" class="ays-survey_linear_scale_span">1 to</span>
                                                                    </div>
                                                                    <div class="ays-survey-question-types-for-select-lenght">
                                                                        <select class="ays-survey-choose-for-select-lenght" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][scale_length]">
                                                                            <?php
                                                                                $scale_options = "" ;
                                                                                for($l_i = 3; $l_i <= 10; $l_i++){
                                                                                    $scale_option_selected = (5 == $l_i) ? "selected" : "";
                                                                                    $scale_options .= "<option value=".$l_i." ".$scale_option_selected.">".$l_i."</option>";
                                                                                }
                                                                                echo $scale_options;
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                                                    <span class="ays_survey_linear_scale_span">1</span>
                                                                    <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-linear-scale-1 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][linear_scale_1]">
                                                                    <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                                                    <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-not-adding-enter-box">
                                                                    <span class="ays_survey_linear_scale_span ays_survey_linear_scale_span_changeable">5</span>
                                                                    <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-linear-scale-2 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][linear_scale_2]" value="">
                                                                    <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                                                    <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif($survey_default_type == 'date'){
                                                ?>
                                                <div class="ays-survey-question-types_date">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box isDisabled">
                                                                <div class="ays-survey-question-types-box-body">
                                                                    <div class="ays-survey-answer-box ays_survey_date">
                                                                        <input type="text" autocomplete="off" tabindex="0" value="<?php echo __("Month, day, year", $this->plugin_name); ?>" disabled="" dir="auto">
                                                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif($survey_default_type == 'time'){
                                                ?>
                                                <div class="ays-survey-question-types_time">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box isDisabled">
                                                                <div class="ays-survey-question-types-box-body">
                                                                    <div class="ays-survey-answer-box ays_survey_time">
                                                                        <input type="text" autocomplete="off" tabindex="0" value="<?php echo __("Time", $this->plugin_name); ?>" disabled="" dir="auto">
                                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif($survey_default_type == 'date_time'){
                                                ?>
                                                <div class="ays-survey-question-types_date_time">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box isDisabled">
                                                                <div class="ays-survey-question-types-box-body">
                                                                    <div class="ays-survey-answer-box ays_survey_time">
                                                                        <input type="text" autocomplete="off" tabindex="0" value="Month, day, year, hour, minute" disabled="" dir="auto">
                                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif($survey_default_type == 'star'){
                                                ?>
                                                <div class="ays-survey-question-types_star">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box<?php echo $survey_default_type; ?>">
                                                            <div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght">
                                                                <div class="ays-survey-question-types_star_span">
                                                                    <span style="font-size: 25px;" class="ays-survey_star_span">1 to</span>
                                                                </div>
                                                                <div class="ays-survey-question-types-for-select-lenght">
                                                                    <select class="ays-survey-choose-for-start-select-lenght" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][star_scale_length]">
                                                                        <?php
                                                                            $star_scale_options = "" ;
                                                                            for($s_i = 3; $s_i <= 10; $s_i++){
                                                                                $star_scale_option_selected = (5 == $s_i) ? "selected" : "";
                                                                                $star_scale_options .= "<option value=".$s_i." ".$star_scale_option_selected.">".$s_i."</option>";
                                                                            }
                                                                            echo $star_scale_options;
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-answer-box ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                                                <span class="ays_survey_star_span">1</span>
                                                                <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-star-1 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][star_1]">
                                                                <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                                                <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                                                            </div>
                                                            <div class="ays-survey-answer-box ays-survey-not-adding-enter-box">
                                                                <span class="ays_survey_star_span">5</span>
                                                                <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-star-2 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][star_2]" value="">
                                                                <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                                                <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php 
                                            }elseif($survey_default_type == 'matrix_scale'){
                                                ?>
                                                <div class="ays-survey-question-matrix_scale ays-survey-question-all-matrix-types">
                                                    <div class="ays-survey-question-matrix_scale_row">
                                                        <div class="ays-survey-answers-conteiner-matrix-row">
                                                            <div class="ays-survey-question-matrix_scale_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                                                            <!-- Add rows start -->
                                                            <div class="ays-survey-answers-conteiner-row">
                                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1">
                                                                    <div class="ays-survey-answer-wrap">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][ordering]" value="1">
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box-wrap">
                                                                            <div class="ays-survey-answer-box">
                                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                                    <input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][title]" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                                                    <div class="ays-survey-input-underline"></div>
                                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ays-survey-answer-icon-box">
                                                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Add rows end -->
                                                        </div>

                                                        <!-- Add "Add button for rows" start -->
                                                        <div class="ays-survey-answers-conteiner-matrix-button">
                                                            <div class="ays-survey-matrix-scale-row-add-button">
                                                                <div class="ays-survey-answer-row">
                                                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons invisible">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box d-flex">
                                                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                                                <div class="ays-question-img-icon-content">
                                                                                    <div class="ays-question-img-icon-content-div">
                                                                                        <div class="ays-survey-icons">
                                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add "Add button for rows" end -->
                                                    </div>
                                                    <div class="ays-survey-question-matrix_scale_column">
                                                        <div class="ays-survey-question-matrix_scale_column_title"><?php echo __("Columns" , $this->plugin_name)?></div>
                                                        <!-- Add column start -->
                                                        <div class="ays-survey-answers-conteiner-column" data-flag="true">
                                                            <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add">
                                                                <div class="ays-survey-answer-wrap">
                                                                    <div class="ays-survey-answer-dlg-dragHandle">
                                                                        <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                        </div>
                                                                        <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                                                    </div>
                                                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                    </div>
                                                                    <div class="ays-survey-answer-box-wrap">
                                                                        <div class="ays-survey-answer-box">
                                                                            <div class="ays-survey-answer-box-input-wrap">
                                                                                <input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][columns][uid_1]" value="<?php echo __("Column", $this->plugin_name); ?> 1">
                                                                                <div class="ays-survey-input-underline"></div>
                                                                                <div class="ays-survey-input-underline-animation"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box">
                                                                            <span class="ays-survey-answer-icon ays-survey-answer-delete-column appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add column end -->
                                                        <!-- Add "Add button for columns" start -->
                                                        <div class="ays-survey-matrix-scale-column-add-button">
                                                            <div class="ays-survey-other-answer-and-actions-column">
                                                                <div class="ays-survey-answer-row">                                                                
                                                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons invisible">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box d-flex">
                                                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add column',$this->plugin_name)?>" data-dir="col">
                                                                                <div class="ays-question-img-icon-content">
                                                                                    <div class="ays-question-img-icon-content-div">
                                                                                        <div class="ays-survey-icons">
                                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add "Add button for columns" end -->
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            elseif($survey_default_type == 'matrix_scale_checkbox'){
                                                ?>
                                                <div class="ays-survey-question-matrix_scale_checkbox ays-survey-question-all-matrix-types">
                                                    <div class="ays-survey-question-matrix_scale_checkbox_row">
                                                        <div class="ays-survey-answers-conteiner-matrix-row">
                                                            <div class="ays-survey-question-matrix_scale_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                                                            <!-- Add rows start -->
                                                            <div class="ays-survey-answers-conteiner-row">
                                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1">
                                                                    <div class="ays-survey-answer-wrap">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][ordering]" value="1">
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box-wrap">
                                                                            <div class="ays-survey-answer-box">
                                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                                    <input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][title]" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                                                    <div class="ays-survey-input-underline"></div>
                                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ays-survey-answer-icon-box">
                                                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Add rows end -->
                                                        </div>

                                                        <!-- Add "Add button for rows" start -->
                                                        <div class="ays-survey-answers-conteiner-matrix-button">
                                                            <div class="ays-survey-matrix-scale-row-add-button">
                                                                <div class="ays-survey-answer-row">
                                                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons invisible">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box d-flex">
                                                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                                                <div class="ays-question-img-icon-content">
                                                                                    <div class="ays-question-img-icon-content-div">
                                                                                        <div class="ays-survey-icons">
                                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add "Add button for rows" end -->
                                                    </div>
                                                    <div class="ays-survey-question-matrix_scale_checkbox_column">
                                                        <div class="ays-survey-question-matrix_scale_column_title"><?php echo __("Columns" , $this->plugin_name)?></div>
                                                        <!-- Add column start -->
                                                        <div class="ays-survey-answers-conteiner-column" data-flag="true">
                                                            <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add">
                                                                <div class="ays-survey-answer-wrap">
                                                                    <div class="ays-survey-answer-dlg-dragHandle">
                                                                        <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                        </div>
                                                                        <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                                                    </div>
                                                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                                                    </div>
                                                                    <div class="ays-survey-answer-box-wrap">
                                                                        <div class="ays-survey-answer-box">
                                                                            <div class="ays-survey-answer-box-input-wrap">
                                                                                <input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][columns][uid_1]" value="<?php echo __("Column", $this->plugin_name); ?> 1">
                                                                                <div class="ays-survey-input-underline"></div>
                                                                                <div class="ays-survey-input-underline-animation"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box">
                                                                            <span class="ays-survey-answer-icon ays-survey-answer-delete-column appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add column end -->
                                                        <!-- Add "Add button for columns" start -->
                                                        <div class="ays-survey-matrix-scale-column-add-button">
                                                            <div class="ays-survey-other-answer-and-actions-column">
                                                                <div class="ays-survey-answer-row">                                                                
                                                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons invisible">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box d-flex">
                                                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add column',$this->plugin_name)?>" data-dir="col">
                                                                                <div class="ays-question-img-icon-content">
                                                                                    <div class="ays-question-img-icon-content-div">
                                                                                        <div class="ays-survey-icons">
                                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add "Add button for columns" end -->
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            elseif($survey_default_type == 'star_list'){
                                                ?>
                                                <div class="ays-survey-question-star_list ays-survey-question-all-matrix-types">
                                                    <div class="ays-survey-question-star_list_row">
                                                        <div class="ays-survey-answers-conteiner-star-list-row">
                                                            <div class="ays-survey-question-star_list_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                                                            <!-- Add rows start -->
                                                            <div class="ays-survey-answers-conteiner-row">
                                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1">
                                                                    <div class="ays-survey-answer-wrap">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][ordering]" value="1">
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box-wrap">
                                                                            <div class="ays-survey-answer-box">
                                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                                    <input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][title]" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                                                    <div class="ays-survey-input-underline"></div>
                                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ays-survey-answer-icon-box">
                                                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Add rows end -->
                                                        </div>

                                                        <!-- Add "Add button for rows" start -->
                                                        <div class="ays-survey-answers-conteiner-star-list-button">
                                                            <div class="ays-survey-star-list-row-add-button">
                                                                <div class="ays-survey-answer-row">
                                                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons invisible">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box d-flex">
                                                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                                                <div class="ays-question-img-icon-content">
                                                                                    <div class="ays-question-img-icon-content-div">
                                                                                        <div class="ays-survey-icons">
                                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add "Add button for rows" end -->
                                                    </div>
                                                    <div class="ays-survey-question-star_list_options">
                                                        <div class="ays-survey-question-star_list_column_title"><?php echo __("Stars length" , $this->plugin_name)?></div>
                                                        <div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght ays-survey-question-star-list-length-box">
                                                            <div class="ays-survey-question-types_star_list_span">
                                                                <span style="font-size: 25px;" class="ays-survey_star_list_span">1 to</span>
                                                            </div>
                                                            <div class="ays-survey-question-types-for-select-lenght">
                                                                <select class="ays-survey-choose-for-select-lenght-star-list" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][star_list_stars_length]">
                                                                    <?php
                                                                        $scale_options = "" ;
                                                                        for($l_i = 3; $l_i <= 10; $l_i++){
                                                                            $scale_option_selected = (5 == $l_i) ? "selected" : "";
                                                                            $scale_options .= "<option value=".$l_i." ".$scale_option_selected.">".$l_i."</option>";
                                                                        }
                                                                        echo $scale_options;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            elseif($survey_default_type == 'slider_list'){
                                                ?>
                                                <div class="ays-survey-question-slider_list ays-survey-question-all-matrix-types">
                                                    <div class="ays-survey-question-slider_list_row">
                                                        <div class="ays-survey-answers-conteiner-slider-list-row">
                                                            <div class="ays-survey-question-slider_list_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                                                            <!-- Add rows start -->
                                                            <div class="ays-survey-answers-conteiner-row">
                                                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1">
                                                                    <div class="ays-survey-answer-wrap">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                            <input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][ordering]" value="1">
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box-wrap">
                                                                            <div class="ays-survey-answer-box">
                                                                                <div class="ays-survey-answer-box-input-wrap">
                                                                                    <input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][answers_add][1][title]" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                                                    <div class="ays-survey-input-underline"></div>
                                                                                    <div class="ays-survey-input-underline-animation"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ays-survey-answer-icon-box">
                                                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Add rows end -->
                                                        </div>

                                                        <!-- Add "Add button for rows" start -->
                                                        <div class="ays-survey-answers-conteiner-star-list-button">
                                                            <div class="ays-survey-star-list-row-add-button">
                                                                <div class="ays-survey-answer-row">
                                                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                                                        <div class="ays-survey-answer-dlg-dragHandle">
                                                                            <div class="ays-survey-icons invisible">
                                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                                            </div>
                                                                        </div>
                                                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                                        </div>
                                                                        <div class="ays-survey-answer-box d-flex">
                                                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                                                <div class="ays-question-img-icon-content">
                                                                                    <div class="ays-question-img-icon-content-div">
                                                                                        <div class="ays-survey-icons">
                                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Add "Add button for rows" end -->
                                                    </div>
                                                    
                                                    <div class="ays-survey-question-slider_list_options">
                                                        <div class="ays-survey-question-star_list_column_title"><?php echo __("Stars length" , $this->plugin_name)?></div>
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box ays-survey-range-box">
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider length" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-range-length" autocomplete="off" tabindex="0" placeholder="<?php echo __( "100 (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][slider_list_range_length]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider step length" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-step-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-range-step-length" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][slider_list_range_step_length]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider minimum value" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-min-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-min-value" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][slider_list_range_min_value]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider default value" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-default-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-default-value" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][slider_list_range_default_value]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider calulation type" , $this->plugin_name); ?></span>
                                                                        </div>                                                                        
                                                                    </label>
                                                                    <div class="my-3 ml-2 ays-survey-question-range-length-label">
                                                                        <label for="ays-survey-slider-list-calculation-seperatly-type-1" style="margin-bottom: 0.3rem; ">
                                                                            <span><?php echo __( 'Seperatly', $this->plugin_name ); ?></span>
                                                                        </label>
                                                                        <input type="radio" id="ays-survey-slider-list-calculation-seperatly-type-1" class="display_none ays-survey-slider-list-calculation-type ays-switch-checkbox" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][slider_list_range_calculation_type]" value="seperatly" checked>
                                                                        <input type="radio" id="ays-survey-slider-list-calculation-combined-type-1" class="display_none ays-survey-slider-list-calculation-type ays-switch-checkbox" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][slider_list_range_calculation_type]" value="combined" >
                                                                        <div class="switch-checkbox-wrap mx-2 ays-survey-slider-list-center-toggle" aria-label="Required" tabindex="0" role="checkbox" data-toggle-type="seperatly">
                                                                            <div class="switch-checkbox-track"></div>
                                                                            <div class="switch-checkbox-ink"></div>
                                                                            <div class="switch-checkbox-circles">
                                                                                <div class="switch-checkbox-thumb"></div>
                                                                            </div>
                                                                        </div>
                                                                        <label for="ays-survey-slider-list-calculation-combined-type-1" style="margin-bottom: 0.3rem; font-weight: 600;">
                                                                            <span><?php echo __( 'Combined', $this->plugin_name ); ?></span>
                                                                        </label>                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            elseif($survey_default_type == 'range'){
                                                ?>
                                                <div class="ays-survey-question-types_range">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box ays-survey-range-box">
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider length" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" placeholder="<?php echo __( "100 (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][range_length]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider step length" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-step-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][range_step_length]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider minimum value" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-min-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][range_min_value]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                                                    <label class="ays-survey-question-range-length-label">
                                                                        <div class="ays-survey-types-range-options-span">
                                                                            <span class="ays_survey_range_span"><?php echo __("Slider default value" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-types-range-options-input">
                                                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-default-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][range_default_value]">
                                                                            <div class="ays-survey-input-underline-animation"></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }elseif( $survey_default_type == 'upload' ){
                                                ?>
                                                <div class="ays-survey-question-types_upload">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box ays-survey-range-box">
                                                                <div class="ays-survey-question-type-upload-main-box ays_toggle_parent">
                                                                    <div class="ays-survey-question-type-upload-allow-type-box">
                                                                        <div class="ays-survey-question-type-upload-allow-type-box-title">
                                                                            <span><?php echo __("Allow only specific file types" , $this->plugin_name); ?></span>
                                                                        </div>
                                                                        <div class="ays-survey-question-type-upload-allow-type-box-checkbox">
                                                                            <label>
                                                                                <input type="checkbox" class="display_none ays-survey-upload-tpypes-on-off ays-switch-checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][toggle_types]">
                                                                                <div class="switch-checkbox-wrap" aria-label="Required" tabindex="0" role="checkbox">
                                                                                    <div class="switch-checkbox-track"></div>
                                                                                    <div class="switch-checkbox-ink"></div>
                                                                                    <div class="switch-checkbox-circles">
                                                                                        <div class="switch-checkbox-thumb"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ays-survey-question-type-upload-allowed-types ays_toggle_target" style="display: none;">
                                                                        <div>
                                                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                                                <input class="ays-survey-current-upload-type-pdf ays-survey-current-upload-type-file-types" type="checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][upload_pdf]">
                                                                                <div class="ays-survey-answer-label-content">
                                                                                    <div class="ays-survey-answer-icon-content">
                                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                                            </div>      
                                                                                        </div>
                                                                                    </div>
                                                                                <span class="">PDF</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                                                <input class="ays-survey-current-upload-type-doc ays-survey-current-upload-type-file-types" type="checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][upload_doc]">
                                                                                <div class="ays-survey-answer-label-content">
                                                                                    <div class="ays-survey-answer-icon-content">
                                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                                            </div>      
                                                                                        </div>
                                                                                    </div>
                                                                                <span class="">DOC,DOCX</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                                                <input class="ays-survey-current-upload-type-png ays-survey-current-upload-type-file-types" type="checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][upload_png]">
                                                                                <div class="ays-survey-answer-label-content">
                                                                                    <div class="ays-survey-answer-icon-content">
                                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                                            </div>      
                                                                                        </div>
                                                                                    </div>
                                                                                <span class="">PNG</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                                                <input class="ays-survey-current-upload-type-jpg ays-survey-current-upload-type-file-types" type="checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][upload_jpg]">
                                                                                <div class="ays-survey-answer-label-content">
                                                                                    <div class="ays-survey-answer-icon-content">
                                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                                            </div>      
                                                                                        </div>
                                                                                    </div>
                                                                                <span class="">JPG, JPEG</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                                                <input class="ays-survey-current-upload-type-gif ays-survey-current-upload-type-file-types" type="checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][upload_gif]">
                                                                                <div class="ays-survey-answer-label-content">
                                                                                    <div class="ays-survey-answer-icon-content">
                                                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                                                        <div class="ays-survey-answer-icon-content-1">
                                                                                            <div class="ays-survey-answer-icon-content-2">
                                                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                                                            </div>      
                                                                                        </div>
                                                                                    </div>
                                                                                <span class="">GIF</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="ays-survey-question-type-upload-max-size-main-box">
                                                                    <div class="ays-survey-question-type-upload-max-size-text-box">
                                                                        <span class="ays-survey-question-type-upload-max-size-text">
                                                                            <?php echo __("Maximum file size" , $this->plugin_name); ?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="ays-survey-question-type-upload-max-size-select-box">
                                                                        <select class="ays-survey-question-type-upload-max-size-select" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][upload_size]">
                                                                            <option value="1">1 MB</option>
                                                                            <option value="5" selected>5 MB</option>
                                                                            <option value="10">10 MB</option>
                                                                            <option value="100">100 MB</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-question-types-box-upload-size">
                                                                <?php
                                                                    echo "<span>" . __( "Maximum upload file size of your website: ", $this->plugin_name ) . " " . wp_max_upload_size() / 1024 / 1024 . " MB.</span>";
                                                                ?>
                                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The chosen value must be equal or higher than the value set on your Server. For example, if the Server value is 64MB, in case of choosing 100MB, the users will not be able to upload the file. Please, note that in the note text, the value set in the Server will be displayed.',$this->plugin_name); ?>">
                                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php    
                                            }elseif($survey_default_type == 'html'){
                                                ?>
                                                <div class="ays-survey-question-types_html">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box">
                                                                <div class="ays-survey-question-types-box-body">
                                                                    <?php
                                                                        $content = '';
                                                                        $editor_id = $html_name_prefix.'html_type_editor_1';
                                                                        $settings = array(
                                                                        'editor_height' => $survey_wp_editor_height, 
                                                                        'textarea_name' => $html_name_prefix.'section_add[1][questions_add][1][options][html_type_editor]', 'editor_class' => $html_name_prefix.'textarea-html-type',
                                                                        'media_elements' => false);
                                                                        wp_editor($content, $editor_id, $settings);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php  
                                            }                                            
                                            else{
                                                ?>
                                                <div class="ays-survey-question-types">
                                                    <div class="ays-survey-answer-row" data-id="1">
                                                        <div class="ays-survey-question-types-conteiner">
                                                            <div class="ays-survey-question-types-box isDisabled <?php echo $survey_default_type; ?>">
                                                                <div class="ays-survey-question-types-box-body">
                                                                    <div class="ays-survey-question-types-input-box">
                                                                        <input type="text" class="ays-survey-remove-default-border ays-survey-question-types-input" autocomplete="off" tabindex="0" disabled="" placeholder="<?php echo $survey_default_type; ?>" style="font-size: 14px;">
                                                                    </div>
                                                                    <div class="ays-survey-question-types-input-underline"></div>
                                                                    <div class="ays-survey-question-types-input-focus-underline"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                        </div>
                                        <div class="ays-survey-other-answer-and-actions-row">
                                            <?php if( !in_array( $survey_default_type, $text_question_types ) && !in_array( $survey_default_type, $other_question_types ) ): ?>
                                            <div class="ays-survey-answer-row ays-survey-other-answer-row" style="display: none;">
                                                <div class="ays-survey-answer-wrap">
                                                    <div class="ays-survey-answer-dlg-dragHandle">
                                                        <div class="ays-survey-icons invisible">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-box-wrap">
                                                        <div class="ays-survey-answer-box">
                                                            <div class="ays-survey-answer-box-input-wrap">
                                                                <input type="text" autocomplete="off" disabled class="ays-survey-input ays-survey-input-other-answer" placeholder="<?php echo __( 'Other...', $this->plugin_name ); ?>" value="<?php echo __( 'Other...', $this->plugin_name ); ?>">
                                                                <div class="ays-survey-input-underline"></div>
                                                                <div class="ays-survey-input-underline-animation"></div>
                                                            </div>
                                                        </div>
                                                        <div class="ays-survey-answer-icon-box">
                                                            <span class="ays-survey-answer-icon ays-survey-other-answer-delete appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                            </span>
                                                        </div>
                                                        <?php  if($survey_default_type == "radio" || $survey_default_type == "yesorno"): ?>
                                                        <div class="ays-survey-answer-logic-jump-wrap display_none">
                                                            <div class="ays-survey-answer-logic-jump-cont">
                                                                <select tabindex="-1" class="ays-survey-answer-logic-jump-select ays-survey-answer-logic-jump-select-other" aria-hidden="true" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][go_to_section]">
                                                                    <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                                    <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                                    <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-row">
                                                <div class="ays-survey-answer-wrap">
                                                    <div class="ays-survey-answer-dlg-dragHandle">
                                                        <div class="ays-survey-icons invisible">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                        <div class="ays-survey-icons">
                                                            <?php if ( $survey_default_type != "checkbox"): ?>
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                                            <?php else: ?>
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="ays-survey-answer-box-wrap">
                                                        <div class="ays-survey-answer-box">
                                                            <div class="ays-survey-action-add-answer appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add option',$this->plugin_name)?>">
                                                                <div class="ays-question-img-icon-content">
                                                                    <div class="ays-question-img-icon-content-div">
                                                                        <div class="ays-survey-icons">
                                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                                        </div>
                                                                        <div class="ays-survey-action-add-answer-text">
                                                                            <?php  echo __('Add option' , "survey-maker"); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-other-answer-add-wrap" <?php echo $survey_default_type == 'select' ? 'style="display:none;"' : ''; ?>>
                                                                <span class=""><?php echo __( 'or', $this->plugin_name ) ?></span>
                                                                <div class="ays-survey-other-answer-container ays-survey-other-answer-add">
                                                                    <div class="ays-survey-other-answer-container-overlay"></div>
                                                                    <span class="ays-survey-other-answer-content">
                                                                        <span class="appsMaterialWizButtonPaperbuttonLabel quantumWizButtonPaperbuttonLabel"><?php echo __( 'add "Other"', $this->plugin_name ) ?></span>
                                                                        <input type="checkbox" class="display_none ays-survey-other-answer-checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][user_variant]">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="ays-survey-answer-other-logic-jump-wrapper display_none">
                                            <div class="ays-survey-row-divider"><div></div></div>
                                            <div class="ays-survey-answer-other-logic-jump-add-condition d-flex align-items-center justify-content-between flex-wrap">
                                                <div class="ays-survey-action-add-condition appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add Condition',$this->plugin_name)?>">
                                                    <div class="ays-question-img-icon-content ays-question-img-icon-content-conditions">
                                                        <div class="ays-question-img-icon-content-div">
                                                            <div class="ays-survey-icons">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span style="padding: 10px;"><?php echo __("Add Condition" , $this->plugin_name); ?></span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="button" class="button ays-survey-condition-refresh-data"><?php echo __( "Refresh question data", $this->plugin_name ); ?></button>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-other-logic-jump-wrap">
                                                <div class="ays-survey-answer-checkbox-logic-jump-conditions">
                                                    <div class="ays-surevy-checkbox-logic-jump-empty-condition">
                                                        <span><?php echo __( 'Press "Add condition" button to add a new condition', $this->plugin_name ); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-other-logic-jump-wrap ays-survey-answer-other-logic-jump-else-wrap display_none">
                                                <div class="ays-survey-answer-checkbox-condition-selects">
                                                    <div class="ays-survey-checkbox-condition-selects-if"><span><?php echo __("Otherwise", $this->plugin_name); ?></span></div>
                                                    <div class="ays-survey-answer-checkbox-condition-selects-row">
                                                        <div class="ays-survey-answer-logic-jump-cont">
                                                            <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true"
                                                                    name="<?php echo $html_name_prefix; ?>sections[1][questions][1][options][other_logic_jump_otherwise]">
                                                                <option selected value="-1"><?php echo __( "Continue to next section" ); ?></option>
							                                    <?php
							                                    foreach ($sections as $sk => $sval):
								                                    ?>
                                                                    <option value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", $this->plugin_name ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
							                                    <?php
							                                    endforeach;
							                                    ?>
                                                                <option value="-2"><?php echo __( "Submit form" ); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ays-survey-row-divider"><div></div></div>
                                        <div class="ays-survey-question-more-options-wrap">
                                            <!-- Min -->
                                            <div class="ays-survey-question-more-option-wrap ays-survey-question-min-selection-count display_none">
                                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-min-selection-count-label">
                                                        <span><?php echo __( "Minimum selection count", $this->plugin_name ); ?></span>
                                                        <input type="number" class="ays-survey-input ays-survey-min-votes-field" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Minimum selection count", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][min_selection_count]"
                                                            value="" min="0">
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Max -->
                                            <div class="ays-survey-question-more-option-wrap ays-survey-question-max-selection-count display_none">
                                                <input type="checkbox" class="display_none ays-survey-question-max-selection-count-checkbox" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_max_selection_count]" value="on">
                                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-max-selection-count-label">
                                                        <span><?php echo __( "Maximum selection count", $this->plugin_name ); ?></span>
                                                        <input type="number" class="ays-survey-input ays-survey-max-votes-field" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Maximum selection count", $this->plugin_name ); ?>" style="font-size: 14px;" 
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][max_selection_count]"
                                                            value="" min="0">
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Text limitations -->
                                            <div class="ays-survey-question-word-limitations display_none">
                                                <input type="checkbox" class="display_none ays-survey-question-word-limitations-checkbox" value="on">

                                                <div class="ays-survey-question-more-option-wrap-limitations ays-survey-question-word-limit-by ">
                                                    <div class="ays-survey-question-word-limit-by-text">
                                                        <span><?php echo __("Limit by", $this->plugin_name); ?></span>
                                                    </div>
                                                    <div class="ays-survey-question-word-limit-by-select">
                                                        <select name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][limit_by]" class="ays-text-input ays-text-input-short ">
                                                            <option value="char"> <?php echo __("Characters")?> </option>
                                                            <option value="word"> <?php echo __("Word")?> </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="ays-survey-row-divider"><div></div></div>
                                                <div class="ays-survey-question-more-option-wrap-limitations ">
                                                    <div class="ays-survey-answer-box">
                                                        <label class="ays-survey-question-limitations-label">
                                                            <span><?php echo __( "Length", $this->plugin_name ); ?></span>
                                                            <input type="number" 
                                                                   name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][limit_length]"        
                                                                   class="ays-survey-input ays-survey-limit-length-input" autocomplete="off" tabindex="0" 
                                                                   placeholder="<?php echo __( "Length", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                                   value="" min="0">
                                                            <div class="ays-survey-input-underline"></div> 
                                                            <div class="ays-survey-input-underline-animation"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="ays-survey-row-divider"><div></div></div>
                                                <div class="ays-survey-question-more-option-wrap-limitations ays-survey-question-word-show-word ">
                                                    <label class="ays-survey-question-limitations-counter-label">
                                                        <span><?php echo __( "Show word/character counter", $this->plugin_name ); ?></span>
                                                        <input type="checkbox" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][limit_counter]" autocomplete="off" value="on" class="ays-survey-text-limitations-counter-input">
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Number limitations start -->
                                            <div class="ays-survey-question-number-limitations display_none">
                                                <input type="checkbox" class="display_none ays-survey-question-number-limitations-checkbox" value="on" name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_number_limitation]">
                                                <!-- Min Number -->
                                                <div class="ays-survey-question-number-min-box ays-survey-question-number-votes-count-box <?php echo ($survey_default_type == 'phone') ? "display_none" : "" ?>" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-number-min-selection-label">
                                                        <span><?php echo __( "Minimum value", $this->plugin_name ); ?></span>
                                                        <input type="number" class="ays-survey-input ays-survey-number-min-votes ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Minimum value", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                            value=""
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][number_min_selection]">
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                                <!-- Max Number -->
                                                <div class="ays-survey-question-number-max-box ays-survey-question-number-votes-count-box <?php echo ($survey_default_type == 'phone') ? "display_none" : "" ?>" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-number-max-selection-label">
                                                        <span><?php echo __( "Maximum value", $this->plugin_name ); ?></span>
                                                        <input type="number" class="ays-survey-input ays-survey-number-max-votes ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Maximum value", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                            value=""
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][number_max_selection]">
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                                <!-- Error message -->
                                                <div class="ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">                                                        
                                                    <label class="ays-survey-question-number-min-selection-label">
                                                        <span><?php echo __( "Error message", $this->plugin_name ); ?></span>
                                                        <input type="text"
                                                            class="ays-survey-input ays-survey-number-error-message ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Error Message", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                            value=""
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][number_error_message]">
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                                <!-- Show error message -->
                                                <div class="ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">                                                        
                                                    <label class="ays-survey-question-number-min-selection-label ays-survey-question-number-message-label">
                                                        <span><?php echo __( "Show error message", $this->plugin_name ); ?></span>
                                                        <input type="checkbox"
                                                            autocomplete="off" 
                                                            value="on" 
                                                            class="ays-survey-number-enable-error-message"
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_number_error_message]">
                                                    </label>
                                                </div>
                                                <!-- Char length -->
                                                <div class="ays-survey-question-number-votes-count-box ">
                                                    <div class="ays-survey-answer-box">
                                                        <label class="ays-survey-question-number-min-selection-label">
                                                            <span><?php echo __( "Length", $this->plugin_name ); ?></span>
                                                            <input type="number" 
                                                                class="ays-survey-input ays-survey-number-limit-length ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                                                placeholder="<?php echo __( "Length", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                                value="" 
                                                                name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][number_limit_length]">
                                                            <div class="ays-survey-input-underline"></div> 
                                                            <div class="ays-survey-input-underline-animation"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- Show Char length -->
                                                <div class="ays-survey-question-number-votes-count-box ">
                                                    <label class="ays-survey-question-number-min-selection-label ays-survey-question-number-message-label">
                                                        <span><?php echo __( "Show character counter", $this->plugin_name ); ?></span>
                                                        <input type="checkbox"
                                                                autocomplete="off" 
                                                                value="on" 
                                                                class="ays-survey-number-number-limit-length"
                                                                name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][enable_number_limit_counter]"
                                                                >
                                                    </label>
                                                </div>
                                                <hr>

                                            </div>
                                            <!-- Number limitations end -->

                                            <!-- User explanation -->
                                            <div class="ays-survey-question-user-explanation-wrap display_none">
                                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-user-explanation-label">
                                                        <input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" disabled
                                                            placeholder="<?php echo __( "User Explanation", $this->plugin_name ); ?>" style="font-size: 14px;"  
                                                            value="<?php echo __( "User Explanation", $this->plugin_name ); ?>" >
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Admin note -->
                                            <div class="ays-survey-question-admin-note display_none">
                                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-admin-note-label">
                                                        <span><?php echo __( "Admin note", $this->plugin_name ); ?></span>
                                                        <input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Note", $this->plugin_name ); ?>" style="font-size: 14px;" 
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][admin_note]"
                                                            value="" >
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- URL Parameter -->
                                            <div class="ays-survey-question-url-parameter display_none">
                                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                                    <label class="ays-survey-question-url-parameter-label">
                                                        <span><?php echo __( "Parameter Name", $this->plugin_name ); ?></span>
                                                        <input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" 
                                                            placeholder="<?php echo __( "Parameter", $this->plugin_name ); ?>" style="font-size: 14px;" 
                                                            name="<?php echo $html_name_prefix; ?>section_add[1][questions_add][1][options][url_parameter]"
                                                            value="" >
                                                        <div class="ays-survey-input-underline"></div> 
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ays-survey-actions-row">
                                            <div class="ays-survey-actions-left">
                                                <div class="ays-survey-actions-answers-bulk-add">
                                                    <div class="ays-survey-answer-icon-box">
                                                        <div class="ays-survey-action-bulk-add-answer">
                                                            <div class="ays-survey-action-bulk-add-answer-content">
                                                                <div class="ays-survey-action-bulk-add-answer-div">
                                                                    <div class="ays-survey-icons ays-survey-action-bulk-add-answer-icon">
                                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/download.svg" class="ays-survey-action-bulk-add-answer-icon-svg">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ays-survey-action-bulk-add-answer-text">
                                                                <span><?php echo __('Bulk add',$this->plugin_name)?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-actions">
                                                <div class="ays-survey-answer-icon-box">
                                                    <div class="ays-survey-action-duplicate-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Duplicate',$this->plugin_name)?>">
                                                        <div class="ays-question-img-icon-content">
                                                            <div class="ays-question-img-icon-content-div">
                                                                <div class="ays-survey-icons">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/duplicate.svg">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ays-survey-answer-icon-box">
                                                    <div class="ays-survey-action-delete-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                        <div class="ays-question-img-icon-content">
                                                            <div class="ays-question-img-icon-content-div">
                                                                <div class="ays-survey-icons">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ays-survey-vertical-divider"><div></div></div>
                                                <div class="ays-survey-answer-elem-box <?php echo $survey_default_type == 'html' ? 'display_none_not_important' : ''; ?>">
                                                    <label>
                                                        <span>
                                                            <span><?php echo __( 'Required', $this->plugin_name ); ?></span>
                                                        </span>
                                                        <input type="checkbox" <?php echo ($survey_make_questions_required) ? 'checked' : '' ?> class="display_none ays-survey-input-required-question ays-switch-checkbox" name="<?php echo esc_attr($html_name_prefix); ?>section_add[1][questions_add][1][options][required]" value="on">
                                                        <div class="switch-checkbox-wrap" aria-label="Required" tabindex="0" role="checkbox">
                                                            <div class="switch-checkbox-track"></div>
                                                            <div class="switch-checkbox-ink"></div>
                                                            <div class="switch-checkbox-circles">
                                                                <div class="switch-checkbox-thumb"></div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="ays-survey-answer-icon-box ays-survey-question-more-actions droptop">
                                                    <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                                        <div class="ays-question-img-icon-content">
                                                            <div class="ays-question-img-icon-content-div">
                                                                <div class="ays-survey-icons">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="move-to-section">
                                                            <?php echo __( 'Move to section', $this->plugin_name ); ?>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action <?php echo $survey_default_type == 'checkbox' ? '' : 'display_none'; ?>" data-action="max-selection-count-enable">
                                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                                            <?php echo __( 'Enable selection count', $this->plugin_name ); ?>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action <?php echo in_array( $survey_default_type, $logic_jump_question_types ) ? '' : 'display_none'; ?>" data-action="go-to-section-based-on-answers-enable">
                                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                                            <?php echo __( 'Logic jump', $this->plugin_name ); ?>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-user-explanation">
                                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                                            <?php echo __( 'User explanation', $this->plugin_name ); ?>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-admin-note">
                                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                                            <?php echo __( 'Admin note', $this->plugin_name ); ?>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-url-parameter">
                                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                                            <?php echo __( 'URL Parameter', $this->plugin_name ); ?>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-hide-results">
                                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                                            <?php echo __( 'Hide results', $this->plugin_name ); ?>
                                                        </button>
                                                        <?php if($survey_default_type == 'matrix_scale' || $survey_default_type == 'linear_scale'): ?>
                                                        <button type="button" class="dropdown-item ays-survey-question-action">
                                                            <a href="https://ays-pro.com/wordpress/survey-maker" style="color: gray;font-style: italic;" target="_blank" ><?php echo __( 'Likert scale', "survey-maker" ); ?> (Agency) </a>
                                                        </button>
                                                        <?php endif;?>
                                                        <button type="button" class="dropdown-item ays-survey-question-action ays-survey-question-id-copy-box" class="ays_help" data-toggle="tooltip" title="<?php echo __('Click for copy',$this->plugin_name);?>" onClick="selectElementContents(this)" data-action="copy-question-id">
                                                            <?php echo __( 'Question ID', $this->plugin_name ); ?>
                                                            <strong class="ays-survey-question-id-copy" style="font-size:16px; font-style:normal;"  > <?php echo $id; ?></strong>
                                                        </button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action <?php echo $survey_default_type == 'text' || $survey_default_type == 'short_text' ? '' : 'display_none'; ?>" data-action="word-limitation-enable"><?php echo __( 'Enable word limitation', $this->plugin_name ); ?></button>
                                                        <button type="button" class="dropdown-item ays-survey-question-action <?php echo $survey_default_type == 'number' || $survey_default_type == 'phone' ? '' : 'display_none'; ?>" data-action="number-word-limitation-enable"><?php echo __( 'Enable limitation', $this->plugin_name ); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-section-footer-wrap">
                            <div class="ays-survey-add-question-from-section-bottom">
                                <div class="ays-survey-add-question-to-this-section ays-survey-add-question-button-container" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Question',$this->plugin_name); ?>">
                                    <div class="ays-survey-add-question-button appsMaterialWizButtonPapericonbuttonEl">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <span><?php echo __('Add Question',$this->plugin_name)?></span>
                                    </div>
                                </div>
                                <div class="ays-survey-add-new-section-from-bottom ays-survey-add-question-button-container" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Section',$this->plugin_name); ?>">
                                    <div class="ays-survey-add-question-button appsMaterialWizButtonPapericonbuttonEl">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-section.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <span><?php echo __('Add Section',$this->plugin_name)?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-section-logic-jump-container">
                                <div class="ays-survey-section-logic-jump-wrap">
                                    <span data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<?php echo __('Choose which section will come next or submit the form after this one.',$this->plugin_name); ?>">
                                        <?php echo __('After this section',$this->plugin_name); ?>
                                    </span>
                                    <select tabindex="-1" class="ays-survey-section-logic-jump-select" aria-hidden="true" name="<?php echo $html_name_prefix; ?>section_add[1][options][go_to_section]">
                                        <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                        <option value="new_section_1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                        <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
            } else {
                foreach ($sections as $key => $section):
                    ?>
                    <!-- Sections start -->
                    <div class="ays-survey-section-box ays-survey-old-section" data-name="<?php echo $html_name_prefix; ?>sections" data-id="<?php echo $section['id']; ?>" data-questions-ids="<?= $section['questions_ids'] ?>">
                        <input type="hidden" class="ays-survey-section-collapsed-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][options][collapsed]" value="<?php echo $section['options']['collapsed']; ?>">
                        <div class="ays-survey-section-wrap-collapsed <?php echo $section['options']['collapsed'] == 'expanded' ? 'display_none' : ''; ?>">
                            <div class="ays-survey-section-head-wrap">
                                <div class="ays-survey-section-head-top <?php echo $multiple_sections ? '' : 'display_none'; ?>">
                                    <div class="ays-survey-section-counter">
                                        <span>
                                            <span><?php echo __( 'Section', $this->plugin_name ); ?></span>
                                            <span class="ays-survey-section-number"><?php echo $key+1; ?></span>
                                            <span><?php echo __( 'of', $this->plugin_name ); ?></span>
                                            <span class="ays-survey-sections-count"><?php echo count($sections); ?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="ays-survey-section-head <?php echo count($sections) > 1 ? 'ays-survey-section-head-topleft-border-none' : ''; ?>">
                                    <div class="ays-survey-section-dlg-dragHandle">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-section-wrap-collapsed-contnet">
                                        <div class="ays-survey-action-questions-count appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Questions count',$this->plugin_name)?>"><span><?php echo $section['questions_count'] ?></span></div>
                                        <div class="ays-survey-section-wrap-collapsed-contnet-text"><?php echo $section['title']; ?></div>
                                        <div>
                                            <div class="ays-survey-action-expand-section appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Expand section',$this->plugin_name)?>">
                                                <div class="ays-section-img-icon-content">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/expand-section.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box ays-survey-section-actions-more dropdown">
                                        <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button type="button" class="dropdown-item ays-survey-delete-section <?php echo $multiple_sections ? '' : 'display_none'; ?>"><?php echo __( 'Delete section', $this->plugin_name ); ?></button>
                                            <button type="button" class="dropdown-item ays-survey-duplicate-section"><?php echo __( 'Duplicate section', $this->plugin_name ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-section-wrap-expanded <?php echo $section['options']['collapsed'] == 'collapsed' ? 'display_none' : ''; ?>">
                            <input type="hidden" name="<?php echo $html_name_prefix; ?>sections_ids[]" value="<?php echo $section['id']; ?>">
                            <div class="ays-survey-section-head-wrap">
                                <div class="ays-survey-section-head-top <?php echo $multiple_sections ? '' : 'display_none'; ?>">
                                    <div class="ays-survey-section-counter">
                                        <span>
                                            <span><?php echo __( 'Section', $this->plugin_name ); ?></span>
                                            <span class="ays-survey-section-number"><?php echo $key+1; ?></span>
                                            <span><?php echo __( 'of', $this->plugin_name ); ?></span>
                                            <span class="ays-survey-sections-count"><?php echo count($sections); ?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="ays-survey-section-head <?php echo count($sections) > 1 ? 'ays-survey-section-head-topleft-border-none' : ''; ?>">
                                    <!--  Section Title Start  -->
                                    <div class="ays-survey-section-title-conteiner">
                                        <input type="text" class="ays-survey-section-title ays-survey-input" tabindex="0" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][title]" placeholder="<?php echo __( 'Section title' , $this->plugin_name ); ?>" value="<?php echo $section['title']; ?>"/>
                                        <div class="ays-survey-input-underline"></div>
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </div>
                                    <!--  Section Title End  -->

                                    <!--  Section Description Start  -->
                                    <div class="ays-survey-section-description-conteiner">
                                        <textarea class="ays-survey-section-description ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][description]" placeholder="<?php echo __( 'Section Description' , $this->plugin_name ); ?>"><?php echo $section['description']; ?></textarea>
                                        <div class="ays-survey-input-underline"></div>
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </div>

                                    <div class="ays-survey-section-actions">
                                        <div class="ays-survey-action-questions-count appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Questions count',$this->plugin_name)?>"><span><?php echo $section['questions_count'] ?></span></div>
                                        <div class="ays-survey-action-collapse-section appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Collapse section',$this->plugin_name)?>">
                                            <div class="ays-question-img-icon-content">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/collapse-section.svg">
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-section-actions-more dropdown">
                                            <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button type="button" class="dropdown-item ays-survey-collapse-section-questions"><?php echo __( 'Collapse section questions', $this->plugin_name ); ?></button>
                                                <input type="checkbox" hidden class="make-questions-required-checkbox" >
                                                <button type="button" class="dropdown-item ays-survey-section-questions-required" data-flag="off"><?php echo __( 'Make questions required', $this->plugin_name ); ?> <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg" class="ays-survey-required-section-img"></button>
                                                <button type="button" class="dropdown-item ays-survey-duplicate-section"><?php echo __( 'Duplicate section', $this->plugin_name ); ?></button>
                                                <button type="button" class="dropdown-item ays-survey-delete-section <?php echo $multiple_sections ? '' : 'display_none'; ?>"><?php echo __( 'Delete section', $this->plugin_name ); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="ays-survey-section-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][ordering]" value="<?php echo $section['ordering']; ?>">
                            </div>
                            <div class="ays-survey-section-body ays-survey-section-body-before-load">
                                <div class="ays-survey-section-questions ays-survey-section-questions-before-load">
                                    <!-- Questons start (old place)-->
                                    <!-- Questons end -->
                                </div>
                                <div class="ays-survey-section-questions-loader">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL ?>/images/loaders/tail-spin.svg" class="ays-survey-question-loader" >
                                </div>

                            </div>
                            <div class="ays-survey-section-footer-wrap">
                                <div class="ays-survey-add-question-from-section-bottom">
                                    <div class="ays-survey-add-question-to-this-section ays-survey-add-question-button-container" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Question',$this->plugin_name); ?>">
                                        <div class="ays-survey-add-question-button appsMaterialWizButtonPapericonbuttonEl">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                    </div>
                                                </div>
                                            </div>
                                            <span><?php echo __('Add Question',$this->plugin_name)?></span>
                                        </div>
                                    </div>
                                    <div class="ays-survey-add-new-section-from-bottom ays-survey-add-question-button-container" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Section',$this->plugin_name); ?>">
                                        <div class="ays-survey-add-question-button appsMaterialWizButtonPapericonbuttonEl">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-section.svg">
                                                    </div>
                                                </div>
                                            </div>
                                            <span><?php echo __('Add Section',$this->plugin_name)?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-section-logic-jump-container">
                                    <div class="ays-survey-section-logic-jump-wrap">
                                    <span data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<?php echo __('Choose which section will come next or submit the form after this one.',$this->plugin_name); ?>">
                                            <?php echo __('After this section',$this->plugin_name); ?>
                                        </span>
                                        <select tabindex="-1" class="ays-survey-section-logic-jump-select" aria-hidden="true" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][options][go_to_section]">
                                            <option <?php echo $section['options']['go_to_section'] == -1 ? 'selected' : ''; ?> value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                            <?php
                                            foreach ($sections as $sk => $sval):
                                                $selected = '';
                                                if( intval( $sval['id'] ) == intval( $section['options']['go_to_section'] ) ){
                                                    $selected = ' selected ';
                                                }

                                                $logic_section_title = $sval['title'];
                                                if( $logic_section_title == ''){
                                                    $logic_section_title = 'Untitled form';
                                                }

                                                ?>
                                                <option <?php echo $selected; ?> value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", $this->plugin_name ) . " " . ( $sk + 1 ) . " (" . $logic_section_title . ")"; ?></option>
                                                <?php
                                            endforeach;
                                            ?>
                                            <option <?php echo $section['options']['go_to_section'] == -2 ? 'selected' : ''; ?> value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sections end -->
                <?php
                endforeach;
            }
            ?>
            </div>
        </div>
        <div class="col-sm-1">
            <input type="hidden" class="ays-survey-scroll-section" value="1">
            <!-- Bar Menu  Start-->
            <div class="aysFormeditorViewFatRoot aysFormeditorViewFatDesktop">
                <div class="aysFormeditorViewFatPositioner">
                    <div class="aysFormeditorViewFatCard">
                        <div class="dropleft">
                            <div data-action="add-question" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Question',$this->plugin_name)?>">
                                <div class="appsMaterialWizButtonPapericonbuttonEl">
                                    <div class="ays-question-img-icon-content">
                                        <div class="ays-question-img-icon-content-div">
                                            <div class="ays-survey-icons">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-menu"></div>
                        </div>
                        <!-- 
                        <div data-action="import-question" class="ays-survey-general-action">
                            <div class="appsMaterialWizButtonPapericonbuttonEl">
                                <div class="ays-question-img-icon-content">
                                    <div class="ays-question-img-icon-content-div">
                                        <div class="ays-survey-icons">
                                            <div class="aysMaterialIconIconImage ays-qp-icon-import-question-m2" aria-hidden="true">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-action="add-section-header" data-action-properties="enabled" class="ays-survey-general-action">
                            <div class="appsMaterialWizButtonPapericonbuttonEl">
                                <div class="ays-question-img-icon-content">
                                    <div class="ays-question-img-icon-content-div">
                                        <div class="ays-survey-icons">
                                            <div class="aysMaterialIconIconImage ays-qp-icon-add-header" aria-hidden="true">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-action="add-image" class="ays-survey-general-action">
                            <div class="appsMaterialWizButtonPapericonbuttonEl">
                                <div class="ays-question-img-icon-content">
                                    <div class="ays-question-img-icon-content-div">
                                        <div class="ays-survey-icons">
                                            <div class="aysMaterialIconIconImage ays-qp-icon-image-m2" aria-hidden="true">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-action="add-video" class="ays-survey-general-action">
                            <div class="appsMaterialWizButtonPapericonbuttonEl">
                                <div class="ays-question-img-icon-content">
                                    <div class="ays-question-img-icon-content-div">
                                        <div class="ays-survey-icons">
                                            <div class="aysMaterialIconIconImage ays-qp-icon-video-m2" aria-hidden="true">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <div data-action="add-section" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="<?php echo __('Add Section',$this->plugin_name)?>">
                            <div class="appsMaterialWizButtonPapericonbuttonEl">
                                <div class="ays-question-img-icon-content">
                                    <div class="ays-question-img-icon-content-div">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-section.svg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-action="open-modal" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="<?php echo __('Import questions',$this->plugin_name)?>">
                            <div class="appsMaterialWizButtonPapericonbuttonEl ays-survey-icon-svg">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/import.svg">
                            </div>
                        </div>
                        <div data-action="make-questions-required" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-flag="off" data-content="<?php echo __('Make questions required',$this->plugin_name)?>">
                            <input type="checkbox" hidden class="make-questions-required-checkbox">
                            <div class="appsMaterialWizButtonPapericonbuttonEl ays-survey-icon-svg">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/asterisk.svg">
                            </div>
                        </div>
                        <div data-action="save-changes" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="<?php echo __('Save changes',$this->plugin_name)?>">
                            <div class="appsMaterialWizButtonPapericonbuttonEl ays-survey-icon-svg">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/save-outline.svg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bar Menu  End-->

            <!-- Question to clone -->
            <div class="ays-question-to-clone display_none">
                <div class="ays-survey-question-answer-conteiner ays-survey-new-question" data-name="questions_add" data-id="1">
                    <input type="hidden" class="ays-survey-question-collapsed-input" value="expanded">
                    <input type="hidden" class="ays-survey-question-is-logic-jump" value="off">
                    <input type="hidden" class="ays-survey-question-user-explanation" value="off">
                    <input type="hidden" class="ays-survey-question-admin-note-saver" value="off">
                    <input type="hidden" class="ays-survey-question-url-parameter-saver" value="off">
                    <input type="hidden" class="ays-survey-question-hide-results-saver" value="off">
                    <div class="ays-survey-question-wrap-collapsed display_none">
                        <div class="ays-survey-question-dlg-dragHandle">
                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-horizontal.svg">
                            </div>
                        </div>
                        <div class="ays-survey-question-wrap-collapsed-contnet ays-survey-question-wrap-collapsed-contnet-box">
                            <div class="ays-survey-question-wrap-collapsed-contnet-text"></div>
                            <div>
                                <div class="ays-survey-action-expand-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Expand question',$this->plugin_name)?>">
                                    <div class="ays-question-img-icon-content">
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/expand-section.svg">
                                    </div>
                                </div>
                                <div class="ays-survey-answer-icon-box ays-survey-question-more-actions droptop">
                                    <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button type="button" class="dropdown-item ays-survey-action-delete-question"><?php echo __( 'Delete question', $this->plugin_name ); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ays-survey-question-wrap-expanded">
                        <div class="ays-survey-question-conteiner">
                            <div class="ays-survey-question-dlg-dragHandle">
                                <div class="ays-survey-icons ays-survey-icons-hidden">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-horizontal.svg">
                                    <input type="hidden" class="ays-survey-question-ordering" value="1">
                                </div>
                            </div>
                            <div class="ays-survey-question-row-wrap">
                                <div class="ays-survey-question-row">
                                    <div class="ays-survey-question-box">
                                        <div class="ays-survey-question-input-box">
                                            <textarea type="text" class="ays-survey-remove-default-border ays-survey-question-input-textarea ays-survey-question-input ays-survey-input" 
                                                placeholder="<?php echo __( 'Question', $this->plugin_name ); ?>"style="height: 24px;"></textarea>
                                            <input type="hidden" value="">
                                            <div class="ays-survey-input-underline"></div>
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                        <div class="ays-survey-description-box ays-survey-question-input-box">
                                            <textarea type="text" class="ays-survey-remove-default-border ays-survey-question-description-input-textarea ays-survey-input ays-survey-description-input" 
                                                placeholder="<?php echo __( 'Question description', $this->plugin_name ); ?>"style="height: 24px;"></textarea>
                                            <div class="ays-survey-input-underline"></div>
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                        <div class="ays-survey-question-preview-box display_none"></div>
                                    </div>
                                    <div class="ays-survey-question-img-icon-box">
                                        <div class="ays-survey-open-question-editor appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Open editor',$this->plugin_name)?>">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/edit-content.svg">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" class="ays-survey-open-question-editor-flag" value="off">
                                        </div>
                                    </div>
                                    <div class="ays-survey-question-img-icon-box">
                                        <div class="ays-survey-add-question-image appsMaterialWizButtonPapericonbuttonEl" data-type="questionImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ays-survey-question-type-box">
                                        <select tabindex="-1" class="ays-survey-question-type" aria-hidden="true">
                                            <?php 
                                                foreach ($question_types as $type_slug => $type):
                                                    ?>
                                                    <option value="<?php echo $type_slug; ?>"><?php echo $type; ?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                        <input type="hidden" class="ays-survey-check-type-before-change" value="<?php echo 'radio'; ?>">
                                    </div>
                                </div>
                                <div>
                                    <div class="ays-survey-question-img-icon-box">
                                        <div class="ays-survey-action-collapse-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Collapse',$this->plugin_name)?>">
                                            <div class="ays-question-img-icon-content">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/collapse-section.svg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-question-image-container" style="display: none;" >
                            <div class="ays-survey-question-image-body">
                                <div class="ays-survey-question-image-wrapper aysFormeditorViewMediaImageWrapper">
                                    <div class="ays-survey-question-image-pos aysFormeditorViewMediaImagePos">
                                        <div class="d-flex">
                                            <div class="dropdown mr-1">
                                                <div class="ays-survey-question-edit-menu-button dropdown-menu-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                    </div>
                                                </div>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item ays-survey-question-img-action" data-action="edit-image" href="javascript:void(0);"><?php echo __( 'Edit', $this->plugin_name ); ?></a>
                                                    <a class="dropdown-item ays-survey-question-img-action" data-action="delete-image" href="javascript:void(0);"><?php echo __( 'Delete', $this->plugin_name ); ?></a>
                                                    <a class="dropdown-item ays-survey-question-img-action" data-action="add-caption" href="javascript:void(0);"><?php echo __( 'Add a caption', $this->plugin_name ); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <img class="ays-survey-question-img" src="" tabindex="0" aria-label="Captionless image" />
                                        <input type="hidden" class="ays-survey-question-img-src" value="">
                                        <input type="hidden" class="ays-survey-question-img-caption-enable">
                                    </div>
                                    <div class="ays-survey-question-image-caption-text-row display_none">
                                        <div class="ays-survey-question-image-caption-box-wrap">
                                            <!-- <div class="ays-survey-answer-box-wrap"> -->
                                                <!-- <div class=""> -->
                                                    <!-- <div class="ays-survey-answer-box-input-wrap"> -->
                                                        <input type="text" class="ays-survey-input ays-survey-question-image-caption" autocomplete="off">
                                                        <div class="ays-survey-input-underline ays-survey-question-image-caption-input-underline"></div>
                                                        <div class="ays-survey-input-underline-animation"></div>
                                                    <!-- </div> -->
                                                <!-- </div> -->
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-answers-conteiner">
                        <?php 
                            for( $i = 1; $i <= $survey_answer_default_count; $i++ ){
                            ?>
                            <div class="ays-survey-answer-row ays-survey-new-answer" data-id="<?php echo $i; ?>" data-name="answers_add">
                                <div class="ays-survey-answer-wrap">
                                    <div class="ays-survey-answer-dlg-dragHandle">
                                        <div class="ays-survey-icons ays-survey-icons-hidden">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                        </div>
                                        <input type="hidden" class="ays-survey-answer-ordering" value="<?php echo $i; ?>">
                                    </div>
                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-box-wrap">
                                        <div class="ays-survey-answer-box">
                                            <div class="ays-survey-answer-box-input-wrap">
                                                <input type="text" autocomplete="off" class="ays-survey-input" placeholder="Option <?php echo $i; ?>" value="Option <?php echo $i; ?>">
                                                <div class="ays-survey-input-underline"></div>
                                                <div class="ays-survey-input-underline-animation"></div>
                                            </div>
                                        </div>

                                        <div class="ays-survey-answer-icon-box">
                                            <div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box">
                                            <span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" style="<?php echo $survey_answer_default_count > 1 ? '' : 'visibility: hidden;'; ?>" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                            </span>
                                        </div>
                                        <div class="ays-survey-answer-logic-jump-wrap display_none">
                                            <div class="ays-survey-answer-logic-jump-cont">
                                                <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true">
                                                    <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                    <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                    <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-answer-image-container" style="display: none;" >
                                    <div class="ays-survey-answer-image-body">
                                        <div class="ays-survey-answer-image-wrapper">
                                            <div class="ays-survey-answer-image-wrapper-delete-wrap">
                                                <div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
                                                    <span class="exportIcon">
                                                        <div class="ays-survey-answer-image-wrapper-delete-icon-cont">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <img class="ays-survey-answer-img" src="" tabindex="0" aria-label="Captionless image" />
                                            <input type="hidden" class="ays-survey-answer-img-src" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                        ?>
                        </div>
                        <div class="ays-survey-other-answer-and-actions-row">
                            <div class="ays-survey-answer-row ays-survey-other-answer-row" style="display: none;">
                                <div class="ays-survey-answer-wrap">
                                    <div class="ays-survey-answer-dlg-dragHandle">
                                        <div class="ays-survey-icons invisible">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-box-wrap">
                                        <div class="ays-survey-answer-box">
                                            <div class="ays-survey-answer-box-input-wrap">
                                                <input type="text" autocomplete="off" disabled class="ays-survey-input ays-survey-input-other-answer" placeholder="<?php echo __( 'Other...', $this->plugin_name ); ?>" value="<?php echo __( 'Other...', $this->plugin_name ); ?>">
                                                <div class="ays-survey-input-underline"></div>
                                                <div class="ays-survey-input-underline-animation"></div>
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box">
                                            <span class="ays-survey-answer-icon ays-survey-other-answer-delete appsMaterialWizButtonPapericonbuttonEl">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                            </span>
                                        </div>
                                        <div class="ays-survey-answer-logic-jump-wrap display_none">
                                            <div class="ays-survey-answer-logic-jump-cont">
                                                <select tabindex="-1" class="ays-survey-answer-logic-jump-select ays-survey-answer-logic-jump-select-other" aria-hidden="true">
                                                    <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                    <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                    <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-answer-row">
                                <div class="ays-survey-answer-wrap">
                                    <div class="ays-survey-answer-dlg-dragHandle">
                                        <div class="ays-survey-icons invisible">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                        <div class="ays-survey-icons">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-box-wrap">
                                        <div class="ays-survey-answer-box">
                                            <div class="ays-survey-action-add-answer appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add option',$this->plugin_name)?>">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                        </div>
                                                        <div class="ays-survey-action-add-answer-text">
                                                            <?php  echo __('Add option' , "survey-maker"); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-other-answer-add-wrap">
                                                <span class=""><?php echo __( 'or', $this->plugin_name ) ?></span>
                                                <div class="ays-survey-other-answer-container ays-survey-other-answer-add">
                                                    <div class="ays-survey-other-answer-container-overlay"></div>
                                                    <span class="ays-survey-other-answer-content">
                                                        <span class="appsMaterialWizButtonPaperbuttonLabel quantumWizButtonPaperbuttonLabel"><?php echo __( 'add "Other"', $this->plugin_name ) ?></span>
                                                        <input type="checkbox" class="display_none ays-survey-other-answer-checkbox" value="on">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ays-survey-answer-other-logic-jump-wrapper display_none">
                            <div class="ays-survey-row-divider"><div></div></div>
                            <div class="ays-survey-answer-other-logic-jump-add-condition d-flex align-items-center justify-content-between flex-wrap">
                                <div class="ays-survey-action-add-condition appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add condition',$this->plugin_name)?>">
                                    <div class="ays-question-img-icon-content ays-question-img-icon-content-conditions">
                                        <div class="ays-question-img-icon-content-div">
                                            <div class="ays-survey-icons">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span style="padding: 10px;"><?php echo __("Add Condition" , $this->plugin_name); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-answer-other-logic-jump-wrap">
                                <div class="ays-survey-answer-checkbox-logic-jump-conditions">
                                    <div class="ays-surevy-checkbox-logic-jump-empty-condition">
                                        <span><?php echo __( 'Press "Add condition" button to add a new condition', $this->plugin_name ); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-answer-other-logic-jump-wrap ays-survey-answer-other-logic-jump-else-wrap display_none">
                                <div class="ays-survey-answer-checkbox-condition-selects">
                                    <div class="ays-survey-checkbox-condition-selects-if"><span><?php echo __("Otherwise", $this->plugin_name); ?></span></div>
                                    <div class="ays-survey-answer-checkbox-condition-selects-row">
                                        <div class="ays-survey-answer-logic-jump-cont">
                                            <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true">
                                                <option selected value="-1"><?php echo __( "Continue to next section" ); ?></option>
						                        <?php
						                        foreach ($sections as $sk => $sval):
							                        ?>
                                                    <option value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", $this->plugin_name ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
						                        <?php
						                        endforeach;
						                        ?>
                                                <option value="-2"><?php echo __( "Submit form" ); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ays-survey-row-divider"><div></div></div>
                        <div class="ays-survey-question-more-options-wrap">
                            <!-- Min -->
                            <div class="ays-survey-question-more-option-wrap ays-survey-question-min-selection-count display_none">
                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-min-selection-count-label">
                                        <span><?php echo __( "Minimum selection count", $this->plugin_name ); ?></span>
                                        <input type="number" class="ays-survey-input ays-survey-min-votes-field" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Minimum selection count", $this->plugin_name ); ?>" style="font-size: 14px;"
                                            value="" min="0">
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                            </div>
                            <!-- Max -->
                            <div class="ays-survey-question-more-option-wrap ays-survey-question-max-selection-count display_none">
                                <input type="checkbox" class="display_none ays-survey-question-max-selection-count-checkbox" value="on">
                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-max-selection-count-label">
                                        <span><?php echo __( "Maximum selection count", $this->plugin_name ); ?></span>
                                        <input type="number" class="ays-survey-input ays-survey-max-votes-field" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Maximum selection count", $this->plugin_name ); ?>" style="font-size: 14px;" 
                                            value="" min="0">
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                            </div>
                             <!-- Text limitations -->
                            <div class="ays-survey-question-word-limitations display_none">
                                <input type="checkbox" class="display_none ays-survey-question-word-limitations-checkbox" value="on">

                                <div class="ays-survey-question-more-option-wrap-limitations ays-survey-question-word-limit-by ">
                                    <div class="ays-survey-question-word-limit-by-text">
                                        <span><?php echo __("Limit by", $this->plugin_name); ?></span>
                                    </div>
                                    <div class="ays-survey-question-word-limit-by-select">
                                        <select class="ays-text-input ays-text-input-short ">
                                            <option value="char"> <?php echo __("Characters")?> </option>
                                            <option value="word"> <?php echo __("Word")?> </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="ays-survey-row-divider"><div></div></div>
                                <div class="ays-survey-question-more-option-wrap-limitations ">
                                    <div class="ays-survey-answer-box">
                                        <label class="ays-survey-question-limitations-label">
                                            <span><?php echo __( "Length", $this->plugin_name ); ?></span>
                                            <input type="number" class="ays-survey-input ays-survey-limit-length-input" autocomplete="off" tabindex="0" 
                                                placeholder="<?php echo __( "Length", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                value="" min="0">
                                            <div class="ays-survey-input-underline"></div> 
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="ays-survey-row-divider"><div></div></div>
                                <div class="ays-survey-question-more-option-wrap-limitations ays-survey-question-word-show-word ">
                                    <label class="ays-survey-question-limitations-counter-label">
                                        <span><?php echo __( "Show word/character counter", $this->plugin_name ); ?></span>
                                        <input type="checkbox" autocomplete="off" value="on" class="ays-survey-text-limitations-counter-input">
                                    </label>
                                </div>
                            </div>
                             <!-- Number limitations start -->
                             <div class="ays-survey-question-number-limitations display_none">
                                <input type="checkbox" class="display_none ays-survey-question-number-limitations-checkbox" value="on">
                                <!-- Min Number -->
                                <div class="ays-survey-question-number-min-box ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-number-min-selection-label">
                                        <span><?php echo __( "Minimum value", $this->plugin_name ); ?></span>
                                        <input type="number" class="ays-survey-input ays-survey-number-min-votes ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Minimum value", $this->plugin_name ); ?>" style="font-size: 14px;"
                                            value="">
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                                <!-- Max Number -->
                                <div class="ays-survey-question-number-max-box ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-number-max-selection-label">
                                        <span><?php echo __( "Maximum value", $this->plugin_name ); ?></span>
                                        <input type="number" class="ays-survey-input ays-survey-number-max-votes ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Maximum value", $this->plugin_name ); ?>" style="font-size: 14px;"
                                            value="" >
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                                <!-- Error message -->
                                <div class="ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">                                                        
                                    <label class="ays-survey-question-number-min-selection-label">
                                        <span><?php echo __( "Error message", $this->plugin_name ); ?></span>
                                        <input type="text"
                                            class="ays-survey-input ays-survey-number-error-message ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Error Message", $this->plugin_name ); ?>" style="font-size: 14px;"
                                            >
                                            <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                                <!-- Show error message -->
                                <div class="ays-survey-question-number-votes-count-box ays-survey-question-number-message-label" style="margin: 20px 0px;">                                                        
                                    <label class="ays-survey-question-number-min-selection-label">
                                        <span><?php echo __( "Show error message", $this->plugin_name ); ?></span>
                                        <input type="checkbox"
                                            autocomplete="off" 
                                            value="on" 
                                            class="ays-survey-number-enable-error-message">
                                    </label>
                                </div>
                                <!-- Char length -->
                                <div class="ays-survey-question-number-votes-count-box ">
                                    <div class="ays-survey-answer-box">
                                        <label class="ays-survey-question-number-min-selection-label">
                                            <span><?php echo __( "Length", $this->plugin_name ); ?></span>
                                            <input type="number" 
                                                class="ays-survey-input ays-survey-number-limit-length ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
                                                placeholder="<?php echo __( "Length", $this->plugin_name ); ?>" style="font-size: 14px;"
                                                value="" >
                                            <div class="ays-survey-input-underline"></div> 
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Show Char length -->
                                <div class="ays-survey-question-number-votes-count-box ">
                                    <label class="ays-survey-question-number-min-selection-label ays-survey-question-number-message-label">
                                        <span><?php echo __( "Show character counter", $this->plugin_name ); ?></span>
                                        <input type="checkbox"
                                                autocomplete="off" 
                                                value="on" 
                                                class="ays-survey-number-number-limit-length">
                                    </label>
                                </div>
                                <hr>

                            </div>
                            <!-- Number limitations end -->

                            <!-- User explanation -->
                            <div class="ays-survey-question-user-explanation-wrap display_none">
                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-user-explanation-label">
                                        <input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" disabled
                                            placeholder="<?php echo __( "User Explanation", $this->plugin_name ); ?>" style="font-size: 14px;"  
                                            value="<?php echo __( "User Explanation", $this->plugin_name ); ?>" >
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                            </div>
                            <!-- Admin note -->
                            <div class="ays-survey-question-admin-note display_none">
                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-admin-note-label">
                                        <span><?php echo __( "Admin note", $this->plugin_name ); ?></span>
                                        <input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Note", $this->plugin_name ); ?>" style="font-size: 14px;"                                            
                                            value="" min="0">
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                            </div>
                            <!-- URL Parameter -->
                            <div class="ays-survey-question-url-parameter display_none">
                                <div class="ays-survey-answer-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-url-parameter-label">
                                        <span><?php echo __( "Parameter Name", $this->plugin_name ); ?></span>
                                        <input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" 
                                            placeholder="<?php echo __( "Parameter", $this->plugin_name ); ?>" style="font-size: 14px;" 
                                            value="" >
                                        <div class="ays-survey-input-underline"></div> 
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-actions-row">
                            <div class="ays-survey-actions-left">
                                <div class="ays-survey-actions-answers-bulk-add">
                                    <div class="ays-survey-answer-icon-box">
                                        <div class="ays-survey-action-bulk-add-answer">
                                            <div class="ays-survey-action-bulk-add-answer-content">
                                                <div class="ays-survey-action-bulk-add-answer-content-div">
                                                    <div class="ays-survey-icons ays-survey-action-bulk-add-answer-icon">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/download.svg" class="ays-survey-action-bulk-add-answer-icon-svg">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-action-bulk-add-answer-text">
                                                <span><?php echo __('Bulk add',$this->plugin_name)?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-actions">
                                <div class="ays-survey-answer-icon-box">
                                    <div class="ays-survey-action-duplicate-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Duplicate',$this->plugin_name)?>">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/duplicate.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-answer-icon-box">
                                    <div class="ays-survey-action-delete-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-vertical-divider"><div></div></div>
                                <div class="ays-survey-answer-elem-box">
                                    <label>
                                        <span>
                                            <span><?php echo __( 'Required', $this->plugin_name ); ?></span>
                                        </span>
                                        <input type="checkbox" <?php echo ($survey_make_questions_required) ? 'checked' : '' ?>  class="display_none ays-survey-input-required-question ays-switch-checkbox" value="on">
                                        <div class="switch-checkbox-wrap" aria-label="Required" tabindex="0" role="checkbox">
                                            <div class="switch-checkbox-track"></div>
                                            <div class="switch-checkbox-ink"></div>
                                            <div class="switch-checkbox-circles">
                                                <div class="switch-checkbox-thumb"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-icon-box ays-survey-question-more-actions droptop">
                                    <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="move-to-section">
                                            <?php echo __( 'Move to section', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="max-selection-count-enable">
                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                            <?php echo __( 'Enable selection count', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action <?php echo in_array( $survey_default_type, $logic_jump_question_types ) ? '' : 'display_none'; ?>" data-action="go-to-section-based-on-answers-enable">
                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                            <?php echo __( 'Logic jump', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-user-explanation">
                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                            <?php echo __( 'User explanation', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-admin-note">
                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                            <?php echo __( 'Admin note', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-url-parameter">
                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                            <?php echo __( 'URL Parameter', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action" data-action="enable-hide-results">
                                            <img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
                                            <?php echo __( 'Hide results', $this->plugin_name ); ?>
                                        </button>
                                        <button type="button" class="dropdown-item ays-survey-question-action display_none" data-action="word-limitation-enable"><?php echo __( 'Enable word limitation', $this->plugin_name ); ?></button>
                                        <button type="button" class="dropdown-item ays-survey-question-action display_none" data-action="number-word-limitation-enable"><?php echo __( 'Enable limitation', $this->plugin_name ); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ays-survey-section-box ays-survey-new-section" data-name="<?php echo $html_name_prefix; ?>section_add" data-id="1">
                    <input type="hidden" class="ays-survey-section-collapsed-input" value="expanded">
                    <div class="ays-survey-section-wrap-collapsed display_none">
                        <div class="ays-survey-section-head-wrap">
                            <div class="ays-survey-section-head-top <?php echo $multiple_sections ? '' : 'display_none'; ?>">
                                <div class="ays-survey-section-counter">
                                    <span>
                                        <span><?php echo __( 'Section', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-section-number"><?php echo 1; ?></span>
                                        <span><?php echo __( 'of', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-sections-count"><?php echo 1; ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="ays-survey-section-head">
                                <div class="ays-survey-section-dlg-dragHandle">
                                    <div class="ays-survey-icons">
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                    </div>
                                </div>
                                <div class="ays-survey-section-wrap-collapsed-contnet">
                                    <div class="ays-survey-action-questions-count appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Questions count',$this->plugin_name)?>"><span>1</span></div>
                                    <div class="ays-survey-section-wrap-collapsed-contnet-text"></div>
                                    <div>
                                        <div class="ays-survey-action-expand-section appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Expand section',$this->plugin_name)?>">
                                            <div class="ays-section-img-icon-content">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/expand-section.svg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-answer-icon-box ays-survey-section-actions-more dropdown">
                                    <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button type="button" class="dropdown-item ays-survey-delete-section display_none"><?php echo __( 'Delete section', $this->plugin_name ); ?></button>
                                        <button type="button" class="dropdown-item ays-survey-duplicate-section"><?php echo __( 'Duplicate section', $this->plugin_name ); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ays-survey-section-wrap-expanded">
                        <div class="ays-survey-section-head-wrap">
                            <div class="ays-survey-section-head-top display_none">
                                <div class="ays-survey-section-counter">
                                    <span>
                                        <span><?php echo __( 'Section', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-section-number">1</span>
                                        <span><?php echo __( 'of', $this->plugin_name ); ?></span>
                                        <span class="ays-survey-sections-count">1</span>
                                    </span>
                                </div>
                            </div>
                            <div class="ays-survey-section-head">
                                <!--  Section Title Start  -->
                                <div class="ays-survey-section-title-conteiner">
                                    <input type="text" class="ays-survey-section-title ays-survey-input" tabindex="0" placeholder="<?php echo __( 'Section title' , $this->plugin_name ); ?>" value=""/>
                                    <div class="ays-survey-input-underline"></div>
                                    <div class="ays-survey-input-underline-animation"></div>
                                </div>
                                <!--  Section Title End  -->

                                <!--  Section Description Start  -->
                                <div class="ays-survey-section-description-conteiner">
                                    <textarea class="ays-survey-section-description ays-survey-input" placeholder="<?php echo __( 'Section Description' , $this->plugin_name ); ?>"/></textarea>
                                    <div class="ays-survey-input-underline"></div>
                                    <div class="ays-survey-input-underline-animation"></div>
                                </div>
                                <!--  Section Description End  -->

                                <div class="ays-survey-section-actions">
                                    <div class="ays-survey-action-questions-count appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Questions count',$this->plugin_name)?>"><span>1</span></div>
                                    <div class="ays-survey-action-collapse-section appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Collapse section',$this->plugin_name)?>">
                                        <div class="ays-question-img-icon-content">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/collapse-section.svg">
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box ays-survey-section-actions-more dropdown">
                                        <div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button type="button" class="dropdown-item ays-survey-collapse-section-questions ays-survey-collapse-sec-quests"><?php echo __( 'Collapse section questions', $this->plugin_name ); ?></button>
                                            <input type="checkbox" hidden class="make-questions-required-checkbox" >
                                            <button type="button" class="dropdown-item ays-survey-section-questions-required" data-flag="off"><?php echo __( 'Make questions required ', $this->plugin_name ); ?> <img class="ays-survey-required-section-img" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg"></button>
                                            <button type="button" class="dropdown-item ays-survey-delete-section display_none"><?php echo __( 'Delete section', $this->plugin_name ); ?></button>
                                            <button type="button" class="dropdown-item ays-survey-duplicate-section"><?php echo __( 'Duplicate section', $this->plugin_name ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="ays-survey-section-ordering" value="1">
                        </div>
                        <div class="ays-survey-section-body">
                            <div class="ays-survey-section-questions">
                            </div>
                        </div>
                        <div class="ays-survey-section-footer-wrap">
                            <div class="ays-survey-add-question-from-section-bottom">
                                <div class="ays-survey-add-question-to-this-section ays-survey-add-question-button-container" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Question',$this->plugin_name); ?>">
                                    <div class="ays-survey-add-question-button appsMaterialWizButtonPapericonbuttonEl">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <span><?php echo __('Add Question',$this->plugin_name)?></span>
                                    </div>
                                </div>
                                <div class="ays-survey-add-new-section-from-bottom ays-survey-add-question-button-container" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Section',$this->plugin_name); ?>">
                                    <div class="ays-survey-add-question-button appsMaterialWizButtonPapericonbuttonEl">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-section.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <span><?php echo __('Add Section',$this->plugin_name)?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-section-logic-jump-container">
                                <div class="ays-survey-section-logic-jump-wrap">
                                    <span data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<?php echo __('Choose which section will come next or submit the form after this one.',$this->plugin_name); ?>">
                                        <?php echo __('After this section',$this->plugin_name); ?>
                                    </span>
                                    <select tabindex="-1" class="ays-survey-section-logic-jump-select" aria-hidden="true">
                                        <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                        <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                        <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question Type Checkbox logic jump condition clone Start -->
                <div class="ays-survey-checkbox-logic-jump-condition-to-clone">
                    <div class="ays-survey-answer-checkbox-logic-jump-condition" data-question-id="" data-condition-id="1">
                        <div class="ays-survey-answer-checkbox-condition-selects">
                            <div class="ays-survey-checkbox-condition-selects-if"><span><?php echo __("If", $this->plugin_name); ?></span></div>
                            <div class="ays-survey-answer-checkbox-condition-selects-row">
                                <div class="ays-survey-checkbox-condition-selects">
                                    <select class="ays-survey-checkbox-condition-select" multiple>
                                        <?php
                                        $checkbox_lj_select_options = "";
                                        $checkbox_lj_select_options .= '<option value="">'. __("Select" , $this->plugin_name).'</option>';
                                        echo $checkbox_lj_select_options;
                                        ?>
                                    </select>
                                </div>
                                <div class="ays-survey-answer-logic-jump-cont">
                                    <div class="ays-survey-checkbox-condition-selects-then"><span><?php echo __("are selected then", $this->plugin_name); ?></span></div>
                                    <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true">
                                        <option selected value="-1"><?php echo __( "Continue to next section" ); ?></option>
                                        <?php
                                        foreach ($sections as $sk => $sval):
                                            ?>
                                            <option value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", $this->plugin_name ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                        <option value="-2"><?php echo __( "Submit form" ); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="ays-survey-condition-delete-currnet">
                                <div class="ays-survey-delete-question-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl ays-survey-delete-button" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __("Delete", $this->plugin_name ); ?>">
                                    <div class="ays-question-img-icon-content">
                                        <div class="ays-question-img-icon-content-div">
                                            <div class="ays-survey-icons ays-survey-icons">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL?>/images/icons/trash.svg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ays-surevy-checkbox-logic-jump-empty-condition">
                        <span><?php echo __( 'Press "Add condition" button to add a new condition', $this->plugin_name ); ?></span>
                    </div>
                </div>
                <!-- Question Type Checkbox logic jump condition clone End -->

                
                <!-- Question Type Text/Short Text clone Start -->
                <div class="ays-survey-question-types">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-question-types-box-body">
                                    <div class="ays-survey-question-types-input-box">
                                        <input type="text" class="ays-survey-remove-default-border ays-survey-question-types-input ays-survey-question-types-input-with-placeholder" autocomplete="off" tabindex="0" placeholder="" style="font-size: 14px;">
                                    </div>
                                    
                                    <div class="ays-survey-question-types-input-underline"></div>
                                    <div class="ays-survey-question-types-input-focus-underline"></div>
                                </div>
                            </div>
                            <div class="ays-survey-question-text-types-note-text"><span>* <?php echo __('You can insert your custom placeholder for input. Note your custom text will not be translated', $this->plugin_name); ?></span></div>
                            <div class="ays-survey-question-types-box-phone-type-note display_none">
                                <?php
                                    echo "<span>" . __( "Note: Phone question type can contain only numbers and the following signs + ( ) -", $this->plugin_name ) . "</span>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question Type Text/Short Text clone End -->

                <!-- Question type linear scale start -->
                <div class="ays-survey-question-types_linear_scale">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght">
                                    <div class="ays-survey-question-types_linear_scale_span">
                                        <span style="font-size: 25px;" class="ays-survey_linear_scale_span">1 to</span>
                                    </div>
                                    <div class="ays-survey-question-types-for-select-lenght">
                                        <select class="ays-survey-choose-for-select-lenght">
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5" selected>5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                    <span class="ays_survey_linear_scale_span">1</span>
                                    <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-linear-scale-1 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="">
                                    <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                    <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-not-adding-enter-box">
                                    <span class="ays_survey_linear_scale_span ays_survey_linear_scale_span_changeable">5</span>
                                    <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-linear-scale-2 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="">
                                    <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                    <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type linear scale end -->

                <!-- Question type star start  -->
                <div class="ays-survey-question-types_star">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box">
                            <div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght">
                                <div class="ays-survey-question-types_star_span">
                                    <span style="font-size: 25px;" class="ays-survey_star_span">1 to</span>
                                </div>
                                <div class="ays-survey-question-types-for-select-lenght">
                                    <select class="ays-survey-choose-for-start-select-lenght">
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5" selected>5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ays-survey-answer-box ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                <span class="ays_survey_star_span">1</span>
                                <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-star-1 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="">
                                <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                            </div>
                            <div class="ays-survey-answer-box ays-survey-not-adding-enter-box">
                                <span class="ays_survey_star_span ays_survey_linear_scale_span_changeable">5</span>
                                <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-star-2 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="">
                                <div class="ays-survey-question-types-input-underline-linear-scale"></div> 
                                <div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type star end  -->
               
                <!-- Question type date starting  -->
                <div class="ays-survey-question-types_date">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-question-types-box-body">
                                    <div class="ays-survey-answer-box ays_survey_date">
                                        <input type="text" autocomplete="off" tabindex="0" value="<?php echo __("Month, day, year", $this->plugin_name); ?>" disabled="" dir="auto">
                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type date end -->
               
                <!-- Question type time starting  -->
                <div class="ays-survey-question-types_time">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-question-types-box-body">
                                    <div class="ays-survey-answer-box ays_survey_time">
                                        <input type="text" autocomplete="off" tabindex="0" value="<?php echo __("Time", $this->plugin_name); ?>" disabled="" dir="auto">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type time end -->
               
                <!-- Question type date and time starting  -->
                <div class="ays-survey-question-types_date_time">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-question-types-box-body">
                                    <div class="ays-survey-answer-box ays_survey_time">
                                        <input type="text" autocomplete="off" tabindex="0" value="Month, day, year, hour, minute" disabled="" dir="auto">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type date and time end -->

                <!-- Question Type Yes or No clone Start -->
                <div class="ays-survey-question-type-yes-or-no">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-answer-wrap">
                            <div class="ays-survey-answer-dlg-dragHandle">
                                <div class="ays-survey-icons ays-survey-icons-hidden">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                </div>
                                <input type="hidden" class="ays-survey-answer-ordering" value="1">
                            </div>
                            <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                <div class="ays-survey-icons">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                </div>
                            </div>
                            <div class="ays-survey-answer-box-wrap">
                                <div class="ays-survey-answer-box">
                                    <div class="ays-survey-answer-box-input-wrap">
                                        <input type="text" class="ays-survey-input" autocomplete="off" placeholder="<?php echo __("Yes", $this->plugin_name); ?>" value="<?php echo __("Yes", $this->plugin_name); ?>">
                                        <div class="ays-survey-input-underline"></div>
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box">
                                        <div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box">
                                        <span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                        </span>
                                    </div>
                                    <div class="ays-survey-answer-logic-jump-wrap display_none">
                                        <div class="ays-survey-answer-logic-jump-cont">
                                            <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true">
                                                <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-answer-image-container" style="display: none;">
                            <div class="ays-survey-answer-image-body">
                                <div class="ays-survey-answer-image-wrapper">
                                    <div class="ays-survey-answer-image-wrapper-delete-wrap">
                                        <div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
                                            <span class="exportIcon">
                                                <div class="ays-survey-answer-image-wrapper-delete-icon-cont">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <img class="ays-survey-answer-img" src="" tabindex="0" aria-label="Captionless image" />
                                    <input type="hidden" class="ays-survey-answer-img-src" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ays-survey-answer-row" data-id="2">
                        <div class="ays-survey-answer-wrap">
                            <div class="ays-survey-answer-dlg-dragHandle">
                                <div class="ays-survey-icons ays-survey-icons-hidden">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                </div>
                                <input type="hidden" class="ays-survey-answer-ordering" value="2">
                            </div>
                            <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                <div class="ays-survey-icons">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                </div>
                            </div>
                            <div class="ays-survey-answer-box-wrap">
                                <div class="ays-survey-answer-box">
                                    <div class="ays-survey-answer-box-input-wrap">
                                        <input type="text" class="ays-survey-input" autocomplete="off" placeholder="<?php echo __("No", $this->plugin_name) ?>" value="<?php echo __("No", $this->plugin_name) ?>">
                                        <div class="ays-survey-input-underline"></div>
                                        <div class="ays-survey-input-underline-animation"></div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box">
                                        <div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',$this->plugin_name)?>">
                                            <div class="ays-question-img-icon-content">
                                                <div class="ays-question-img-icon-content-div">
                                                    <div class="ays-survey-icons">
                                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ays-survey-answer-icon-box">
                                        <span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                        </span>
                                    </div>
                                    <div class="ays-survey-answer-logic-jump-wrap display_none">
                                        <div class="ays-survey-answer-logic-jump-cont">
                                            <select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true">
                                                <option value="-1"><?php echo __( "Continue to next section", $this->plugin_name ); ?></option>
                                                <option value="1"><?php echo __( "Go to section", $this->plugin_name ) . " 1 (Untitled form)"; ?></option>
                                                <option value="-2"><?php echo __( "Submit form", $this->plugin_name ); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-answer-image-container" style="display: none;">
                            <div class="ays-survey-answer-image-body">
                                <div class="ays-survey-answer-image-wrapper">
                                    <div class="ays-survey-answer-image-wrapper-delete-wrap">
                                        <div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
                                            <span class="exportIcon">
                                                <div class="ays-survey-answer-image-wrapper-delete-icon-cont">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <img class="ays-survey-answer-img" src="" tabindex="0" aria-label="Captionless image" />
                                    <input type="hidden" class="ays-survey-answer-img-src" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question Type Yes or No clone End -->

                <!-- Question type matrix scale start -->
                <div class="ays-survey-question-matrix_scale ays-survey-question-all-matrix-types">
                    <div class="ays-survey-question-matrix_scale_row">

                        <div class="ays-survey-answers-conteiner-matrix-row">
                            <div class="ays-survey-question-matrix_scale_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                            <!-- Add rows start -->                        
                            <div class="ays-survey-answers-conteiner-row">
                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add" data-answer="answer_row">
                                    <div class="ays-survey-answer-wrap">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                            <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box-wrap">
                                            <div class="ays-survey-answer-box">
                                                <div class="ays-survey-answer-box-input-wrap">
                                                    <input type="text" class="ays-survey-input" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                    <div class="ays-survey-input-underline"></div>
                                                    <div class="ays-survey-input-underline-animation"></div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box">
                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add rows end -->
                        </div>

                        <!-- Add "Add button for rows" start -->
                        <div class="ays-survey-answers-conteiner-matrix-button">
                            <div class="ays-survey-matrix-scale-row-add-button">
                                <div class="ays-survey-answer-row">
                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons invisible">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box d-flex">
                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add "Add button for rows" end -->
                    </div>
                    <div class="ays-survey-question-matrix_scale_column">
                        <div class="ays-survey-question-matrix_scale_column_title"><?php echo __("Columns" , $this->plugin_name)?></div>
                        <!-- Add column start -->                        
                            <div class="ays-survey-answers-conteiner-column" data-flag="false">
                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add">
                                    <div class="ays-survey-answer-wrap">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                            <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>                                                                        
                                        <div class="ays-survey-answer-box-wrap">
                                            <div class="ays-survey-answer-box">
                                                <div class="ays-survey-answer-box-input-wrap">
                                                    <input type="text" class="ays-survey-input" value="<?php echo __("Column", $this->plugin_name); ?> 1" placeholder="<?php echo __("Column", $this->plugin_name); ?> 1">
                                                    <div class="ays-survey-input-underline"></div>
                                                    <div class="ays-survey-input-underline-animation"></div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box">
                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-column appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add column end -->
                            <!-- Add "Add button for columns" start -->
                            <div class="ays-survey-matrix-scale-column-add-button">
                                <div class="ays-survey-other-answer-and-actions-column">
                                    <div class="ays-survey-answer-row">                                                                
                                        <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                            <div class="ays-survey-answer-dlg-dragHandle">
                                                <div class="ays-survey-icons invisible">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                            </div>
                                            <div class="ays-survey-answer-box d-flex">
                                                <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add column',$this->plugin_name)?>" data-dir="col">
                                                    <div class="ays-question-img-icon-content">
                                                        <div class="ays-question-img-icon-content-div">
                                                            <div class="ays-survey-icons">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add "Add button for columns" end -->
                        </div>
                    </div>
                <!-- Question type matrix scale end -->

                <!-- Question type matrix scale checkbox start -->
                <div class="ays-survey-question-matrix_scale_checkbox ays-survey-question-all-matrix-types">
                    <div class="ays-survey-question-matrix_scale_checkbox_row">

                        <div class="ays-survey-answers-conteiner-matrix-row">
                            <div class="ays-survey-question-matrix_scale_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                            <!-- Add rows start -->                        
                            <div class="ays-survey-answers-conteiner-row">
                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add" data-answer="answer_row">
                                    <div class="ays-survey-answer-wrap">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                            <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box-wrap">
                                            <div class="ays-survey-answer-box">
                                                <div class="ays-survey-answer-box-input-wrap">
                                                    <input type="text" class="ays-survey-input" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                    <div class="ays-survey-input-underline"></div>
                                                    <div class="ays-survey-input-underline-animation"></div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box">
                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add rows end -->
                        </div>

                        <!-- Add "Add button for rows" start -->
                        <div class="ays-survey-answers-conteiner-matrix-button">
                            <div class="ays-survey-matrix-scale-row-add-button">
                                <div class="ays-survey-answer-row">
                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons invisible">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box d-flex">
                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add "Add button for rows" end -->
                    </div>
                    <div class="ays-survey-question-matrix_scale_checkbox_column">
                        <div class="ays-survey-question-matrix_scale_column_title"><?php echo __("Columns" , $this->plugin_name)?></div>
                        <!-- Add column start -->                        
                            <div class="ays-survey-answers-conteiner-column" data-flag="false">
                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add">
                                    <div class="ays-survey-answer-wrap">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                            <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                        </div>                                                                        
                                        <div class="ays-survey-answer-box-wrap">
                                            <div class="ays-survey-answer-box">
                                                <div class="ays-survey-answer-box-input-wrap">
                                                    <input type="text" class="ays-survey-input" value="<?php echo __("Column", $this->plugin_name); ?> 1" placeholder="<?php echo __("Column", $this->plugin_name); ?> 1">
                                                    <div class="ays-survey-input-underline"></div>
                                                    <div class="ays-survey-input-underline-animation"></div>
                                                </div>
                                            </div>

                                            <div class="ays-survey-answer-icon-box">
                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-column appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add column end -->
                            <!-- Add "Add button for columns" start -->
                            <div class="ays-survey-matrix-scale-column-add-button">
                                <div class="ays-survey-other-answer-and-actions-column">
                                    <div class="ays-survey-answer-row">                                                                
                                        <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                            <div class="ays-survey-answer-dlg-dragHandle">
                                                <div class="ays-survey-icons invisible">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
                                            </div>
                                            <div class="ays-survey-answer-box d-flex">
                                                <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add column',$this->plugin_name)?>" data-dir="col">
                                                    <div class="ays-question-img-icon-content">
                                                        <div class="ays-question-img-icon-content-div">
                                                            <div class="ays-survey-icons">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add "Add button for columns" end -->
                        </div>
                    </div>
                <!-- Question type matrix scale checkbox  end -->
                
                <!-- Question type star list start -->
                <div class="ays-survey-question-star_list ays-survey-question-all-matrix-types">
                    <div class="ays-survey-question-star_list_row">
                        <div class="ays-survey-answers-conteiner-star-list-row">
                            <div class="ays-survey-question-star_list_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                            <!-- Add rows start -->                        
                            <div class="ays-survey-answers-conteiner-row">
                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add" data-answer="answer_row">
                                    <div class="ays-survey-answer-wrap">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                            <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box-wrap">
                                            <div class="ays-survey-answer-box">
                                                <div class="ays-survey-answer-box-input-wrap">
                                                    <input type="text" class="ays-survey-input" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                    <div class="ays-survey-input-underline"></div>
                                                    <div class="ays-survey-input-underline-animation"></div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box">
                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add rows end -->
                        </div>

                        <!-- Add "Add button for rows" start -->
                        <div class="ays-survey-answers-conteiner-star-list-button">
                            <div class="ays-survey-star-list-row-add-button">
                                <div class="ays-survey-answer-row">
                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons invisible">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box d-flex">
                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add "Add button for rows" end -->
                    </div>
                    <div class="ays-survey-question-star_list_options">
                        <div class="ays-survey-question-star_list_column_title"><?php echo __("Stars length" , $this->plugin_name)?></div>
                        <div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght ays-survey-question-star-list-length-box">
                            <div class="ays-survey-question-types_star_list_span">
                                <span style="font-size: 25px;" class="ays-survey_linear_scale_span">1 to</span>
                            </div>
                            <div class="ays-survey-question-types-for-select-lenght">
                                <select class="ays-survey-choose-for-select-lenght-star-list">
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5" selected>5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type star list scale end -->

                <!-- Question type slider list start -->
                <div class="ays-survey-question-slider_list ays-survey-question-all-matrix-types">
                    <div class="ays-survey-question-slider_list_row">
                        <div class="ays-survey-answers-conteiner-slider-list-row">
                            <div class="ays-survey-question-slider_list_row_title"><?php echo __("Rows" , $this->plugin_name); ?></div>
                            <!-- Add rows start -->                        
                            <div class="ays-survey-answers-conteiner-row">
                                <div class="ays-survey-answer-row ays-survey-new-answer" data-id="1" data-name="answers_add" data-answer="answer_row">
                                    <div class="ays-survey-answer-wrap">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons ays-survey-icons-hidden">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                            <input type="hidden" class="ays-survey-answer-ordering" value="1">
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box-wrap">
                                            <div class="ays-survey-answer-box">
                                                <div class="ays-survey-answer-box-input-wrap">
                                                    <input type="text" class="ays-survey-input" placeholder="<?php echo __( "Row", $this->plugin_name ); ?> 1" value="<?php echo __( "Row", $this->plugin_name ); ?> 1">
                                                    <div class="ays-survey-input-underline"></div>
                                                    <div class="ays-survey-input-underline-animation"></div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-answer-icon-box">
                                                <span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" style="visibility: hidden;" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add rows end -->
                        </div>

                        <!-- Add "Add button for rows" start -->
                        <div class="ays-survey-answers-conteiner-slider-list-button">
                            <div class="ays-survey-slider-list-row-add-button">
                                <div class="ays-survey-answer-row">
                                    <div class="ays-survey-answer-wrap" style="justify-content: initial;">
                                        <div class="ays-survey-answer-dlg-dragHandle">
                                            <div class="ays-survey-icons invisible">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
                                            </div>
                                        </div>
                                        <div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
                                        </div>
                                        <div class="ays-survey-answer-box d-flex">
                                            <div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add row',$this->plugin_name)?>" data-dir="row">
                                                <div class="ays-question-img-icon-content">
                                                    <div class="ays-question-img-icon-content-div">
                                                        <div class="ays-survey-icons">
                                                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add "Add button for rows" end -->
                    </div>
                    <div class="ays-survey-question-slider_list_options">
                        <div class="ays-survey-question-slider_list_column_title"><?php echo __("Slider options" , $this->plugin_name)?></div>
                        <div class="ays-survey-question-types-conteiner ays-survey-question-slider-list-length-box">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider length" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-range-length" autocomplete="off" tabindex="0" placeholder="<?php echo __( "100 (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="" >
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider step length" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-step-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-range-step-length" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>">
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider minimum value" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-min-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-min-value" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" >
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider default value" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-default-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-default-value" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" pattern="[0-9]+">
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider calulation type" , $this->plugin_name); ?></span>
                                        </div>                                                                        
                                    </label>
                                    <div class="my-3 ml-2 ays-survey-question-range-length-label">
                                        <label for="ays-survey-slider-list-calculation-seperatly-type-1" style="margin-bottom: 0.3rem; font-weight: 600;">
                                            <span><?php echo __( 'Seperatly', $this->plugin_name ); ?></span>
                                        </label>
                                        <input type="radio" id="ays-survey-slider-list-calculation-seperatly-type-1" class="display_none ays-survey-slider-list-calculation-type ays-switch-checkbox" value="seperatly" checked>
                                        <input type="radio" id="ays-survey-slider-list-calculation-combined-type-1" class="display_none ays-survey-slider-list-calculation-type ays-switch-checkbox" value="combined" >
                                        <div class="switch-checkbox-wrap mx-2 ays-survey-slider-list-center-toggle" aria-label="Required" tabindex="0" role="checkbox" data-toggle-type="seperatly">
                                            <div class="switch-checkbox-track"></div>
                                            <div class="switch-checkbox-ink"></div>
                                            <div class="switch-checkbox-circles">
                                                <div class="switch-checkbox-thumb"></div>
                                            </div>
                                        </div>
                                        <label for="ays-survey-slider-list-calculation-combined-type-1" style="margin-bottom: 0.3rem;">
                                            <span><?php echo __( 'Combined', $this->plugin_name ); ?></span>
                                        </label>                                                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type slider list end -->
                
                <!-- Question type Range start -->
                <div class="ays-survey-question-types_range">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider length" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" placeholder="<?php echo __( "100 (Optional)", $this->plugin_name ); ?>" style="font-size: 14px;" value="" >
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider step length" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-step-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>">
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider minimum value" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-min-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" >
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
                                    <label class="ays-survey-question-range-length-label">
                                        <div class="ays-survey-types-range-options-span">
                                            <span class="ays_survey_range_span"><?php echo __("Slider default value" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-types-range-options-input">
                                            <input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-default-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" value="" placeholder="<?php echo __( "0 (Optional)", $this->plugin_name ); ?>" pattern="[0-9]+">
                                            <div class="ays-survey-input-underline-animation"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type Range end -->

                <!-- Question type Upload start -->
                <div class="ays-survey-question-types_upload">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <div class="ays-survey-question-type-upload-main-box ays_toggle_parent">
                                    <div class="ays-survey-question-type-upload-allow-type-box">
                                        <div class="ays-survey-question-type-upload-allow-type-box-title">
                                            <span><?php echo __("Allow only specific file types" , $this->plugin_name); ?></span>
                                        </div>
                                        <div class="ays-survey-question-type-upload-allow-type-box-checkbox">
                                            <label>
                                                <input type="checkbox" class="display_none ays-survey-upload-tpypes-on-off ays-switch-checkbox" value="on">
                                                <div class="switch-checkbox-wrap" aria-label="Required" tabindex="0" role="checkbox">
                                                    <div class="switch-checkbox-track"></div>
                                                    <div class="switch-checkbox-ink"></div>
                                                    <div class="switch-checkbox-circles">
                                                        <div class="switch-checkbox-thumb"></div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ays-survey-question-type-upload-allowed-types ays_toggle_target" style="display: none;">
                                        <div>
                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                <input class="ays-survey-current-upload-type-pdf ays-survey-current-upload-type-file-types" type="checkbox" value="on">
                                                <div class="ays-survey-answer-label-content">
                                                    <div class="ays-survey-answer-icon-content">
                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                        <div class="ays-survey-answer-icon-content-1">
                                                            <div class="ays-survey-answer-icon-content-2">
                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                            </div>      
                                                        </div>
                                                    </div>
                                                <span class="">PDF</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                <input class="ays-survey-current-upload-type-doc ays-survey-current-upload-type-file-types" type="checkbox" value="on">
                                                <div class="ays-survey-answer-label-content">
                                                    <div class="ays-survey-answer-icon-content">
                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                        <div class="ays-survey-answer-icon-content-1">
                                                            <div class="ays-survey-answer-icon-content-2">
                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                            </div>      
                                                        </div>
                                                    </div>
                                                <span class="">DOC,DOCX</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                <input class="ays-survey-current-upload-type-png ays-survey-current-upload-type-file-types" type="checkbox" value="on">
                                                <div class="ays-survey-answer-label-content">
                                                    <div class="ays-survey-answer-icon-content">
                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                        <div class="ays-survey-answer-icon-content-1">
                                                            <div class="ays-survey-answer-icon-content-2">
                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                            </div>      
                                                        </div>
                                                    </div>
                                                <span class="">PNG</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                <input class="ays-survey-current-upload-type-jpg ays-survey-current-upload-type-file-types" type="checkbox" value="on">
                                                <div class="ays-survey-answer-label-content">
                                                    <div class="ays-survey-answer-icon-content">
                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                        <div class="ays-survey-answer-icon-content-1">
                                                            <div class="ays-survey-answer-icon-content-2">
                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                            </div>      
                                                        </div>
                                                    </div>
                                                <span class="">JPG, JPEG</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="ays-survey-answer-label ays-survey-answer-label-grid ">
                                                <input class="ays-survey-current-upload-type-gif ays-survey-current-upload-type-file-types" type="checkbox" value="on">
                                                <div class="ays-survey-answer-label-content">
                                                    <div class="ays-survey-answer-icon-content">
                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                        <div class="ays-survey-answer-icon-content-1">
                                                            <div class="ays-survey-answer-icon-content-2">
                                                                <div class="ays-survey-answer-icon-content-3"></div>
                                                            </div>      
                                                        </div>
                                                    </div>
                                                <span class="">GIF</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-question-type-upload-max-size-main-box">
                                    <div class="ays-survey-question-type-upload-max-size-text-box">
                                        <span class="ays-survey-question-type-upload-max-size-text">
                                            <?php echo __("Maximum file size" , $this->plugin_name); ?>
                                        </span>
                                    </div>
                                    <div class="ays-survey-question-type-upload-max-size-select-box">
                                        <select class="ays-survey-question-type-upload-max-size-select">
                                            <option value="1">1 MB</option>
                                            <option value="5" selected>5 MB</option>
                                            <option value="10">10 MB</option>
                                            <option value="100">100 MB</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="ays-survey-question-types-box-upload-size">
                                <?php
                                    echo __( "Maximum upload file size of your website: ", $this->plugin_name ) . wp_max_upload_size() / 1024 / 1024 . " MB";
                                ?>
                                <a data-toggle="tooltip" class="ays_help" title="<?php echo __('The chosen value must be equal or higher than the value set on your Server. For example, if the Server value is 64MB, in case of choosing 100MB, the users will not be able to upload the file. Please, note that in the note text, the value set in the Server will be displayed.',$this->plugin_name); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type Upload end -->
                
                <!-- Question type HTML start  -->
                <div class="ays-survey-question-types_html">
                    <div class="ays-survey-answer-row" data-id="1">
                        <div class="ays-survey-question-types-conteiner">
                            <div class="ays-survey-question-types-box isDisabled">
                                <textarea class="ays-survey-page-message-editor-html-question-type wp-editor-area ays-survey-html-question-type-messages-textareas"></textarea>
                                <?php
                                    // $content = '';
                                    // $editor_id = $html_name_prefix.'html_type_editor';
                                    // $settings = array(
                                    //     'editor_height'  => $survey_wp_editor_height, 
                                    //     'editor_class'   => $html_name_prefix.'textarea-html-type',
                                    //     'media_elements' => false
                                    // );
                                    // wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Question type HTML end -->
            </div>
        </div>
    </div>
    <div class="aysFormeditorViewFatRoot aysFormeditorViewFatMobile">
        <div class="aysFormeditorViewFatPositioner">
            <div class="aysFormeditorViewFatCard">
                <div class="droptop">
                    <div data-action="add-question" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Question',$this->plugin_name)?>">
                        <div class="appsMaterialWizButtonPapericonbuttonEl">
                            <div class="ays-question-img-icon-content">
                                <div class="ays-question-img-icon-content-div">
                                    <div class="ays-survey-icons">
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-menu"></div>
                </div>
                <!-- 
                <div data-action="import-question" class="ays-survey-general-action">
                    <div class="appsMaterialWizButtonPapericonbuttonEl">
                        <div class="ays-question-img-icon-content">
                            <div class="ays-question-img-icon-content-div">
                                <div class="ays-survey-icons">
                                    <div class="aysMaterialIconIconImage ays-qp-icon-import-question-m2" aria-hidden="true">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-action="add-section-header" data-action-properties="enabled" class="ays-survey-general-action">
                    <div class="appsMaterialWizButtonPapericonbuttonEl">
                        <div class="ays-question-img-icon-content">
                            <div class="ays-question-img-icon-content-div">
                                <div class="ays-survey-icons">
                                    <div class="aysMaterialIconIconImage ays-qp-icon-add-header" aria-hidden="true">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-action="add-image" class="ays-survey-general-action">
                    <div class="appsMaterialWizButtonPapericonbuttonEl">
                        <div class="ays-question-img-icon-content">
                            <div class="ays-question-img-icon-content-div">
                                <div class="ays-survey-icons">
                                    <div class="aysMaterialIconIconImage ays-qp-icon-image-m2" aria-hidden="true">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-action="add-video" class="ays-survey-general-action">
                    <div class="appsMaterialWizButtonPapericonbuttonEl">
                        <div class="ays-question-img-icon-content">
                            <div class="ays-question-img-icon-content-div">
                                <div class="ays-survey-icons">
                                    <div class="aysMaterialIconIconImage ays-qp-icon-video-m2" aria-hidden="true">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                <div data-action="add-section" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add Section',$this->plugin_name)?>">
                    <div class="appsMaterialWizButtonPapericonbuttonEl">
                        <div class="ays-question-img-icon-content">
                            <div class="ays-question-img-icon-content-div">
                                <div class="ays-survey-icons">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-section.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-action="open-modal" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="<?php echo __('Import questions',$this->plugin_name)?>">
                    <div class="appsMaterialWizButtonPapericonbuttonEl ays-survey-icon-svg">
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/import.svg">
                    </div>
                </div>
                <div data-action="make-questions-required" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-flag="off" data-content="<?php echo __('Make questions required',$this->plugin_name)?>">
                    <input type="checkbox" hidden class="make-questions-required-checkbox">
                    <div class="appsMaterialWizButtonPapericonbuttonEl ays-survey-icon-svg">
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/asterisk.svg">
                    </div>
                </div>
                <div data-action="save-changes" class="ays-survey-general-action" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Save changes',$this->plugin_name)?>">
                    <div class="appsMaterialWizButtonPapericonbuttonEl ays-survey-icon-svg">
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/save-outline.svg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>