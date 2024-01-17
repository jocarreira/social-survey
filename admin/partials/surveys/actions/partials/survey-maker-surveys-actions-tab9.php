<div id="tab9" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab9') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Condição adicional',$this->plugin_name)?></p>
    <hr/>
    <div id="ays-survey-condition-container-main">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="ays-survey-action-add-condition appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',$this->plugin_name)?>">
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
                <button type="button" class="button ays-survey-condition-refresh-data"><?php echo __( "Refresh questions data", $this->plugin_name ); ?></button>    
            </div>
        </div>
        <div class="ays-survey-condition-containers-info">
            <div class="ays-survey-condition-containers-editable <?php echo empty($conditions) ? "" : "display_none_not_important"; ?>">
                <div class="ays-survey-condition-containers-list-main-header">
                    <span><?php echo __('Press "Add condition" button to add a new condition', $this->plugin_name)?></span>
                </div>           
            </div>
            <?php if(!empty($conditions)):
                foreach($conditions as $condition_id => $condition_value):
                    ?>
                    <?php if(isset($condition_value)):?>
                    <?php
                    $condition_questions = isset($condition_value['condition_question_add']) && !empty($condition_value['condition_question_add']) ? $condition_value['condition_question_add'] : array();
                    $condition_messages  = isset($condition_value['messages']) && !empty($condition_value['messages']) ? $condition_value['messages'] : array();
                    // Page message
                    $condition_messages_page = isset($condition_messages['page']) && !empty($condition_messages['page']) ? $condition_messages['page'] : array();
                    $condition_messages_page_message = isset($condition_messages_page['message']) && $condition_messages_page['message'] != "" ? stripslashes( wpautop($condition_messages_page['message'])) : "";
                    // email message
                    $condition_messages_email = isset($condition_messages['email']) && !empty($condition_messages['email']) ? $condition_messages['email'] : array();
                    $condition_messages_email_message = isset($condition_messages_email['message']) && $condition_messages_email['message'] != "" ? stripslashes( wpautop($condition_messages_email['message'])) : "";
                    $condition_messages_email_file = isset($condition_messages_email['file']) && $condition_messages_email['file'] != "" ? esc_attr($condition_messages_email['file']) : "";
                    $condition_messages_email_file_name = isset($condition_messages_email['file_name']) && $condition_messages_email['file_name'] != "" ? esc_attr($condition_messages_email['file_name']) : "";
                    $condition_messages_email_file_id   = isset($condition_messages_email['file_id']) && $condition_messages_email['file_id'] != "" ? esc_attr($condition_messages_email['file_id']) : "";
                    $cond_delete_button     = true;
                    $cond_email_button_text = __("Edit file" , $this->plugin_name);
                    if($condition_messages_email_file == "" && $condition_messages_email_file_name == ""){
                        $cond_email_button_text = __("Add file" , $this->plugin_name);
                        $cond_delete_button = false;
                    }
                    // redirect message
                    $condition_redirect_delay = "";
                    $condition_redirect_url   = "";
                    if(isset($condition_messages['redirect'])){
                        $condition_redirect_delay = isset($condition_messages['redirect']['delay']) && $condition_messages['redirect']['delay'] != "" ? esc_attr(absint($condition_messages['redirect']['delay'])) : "";
                        $condition_redirect_url   = isset($condition_messages['redirect']['url']) && $condition_messages['redirect']['url'] != "" ? esc_url($condition_messages['redirect']['url']) : "";
                    }
                    ?>
                    <div class="ays-survey-condition-containers-added" data-condition-id="<?php echo $condition_id?>" data-condition-name="ays_condition_add">
                        <div class="ays-survey-condition-delete-conteiner-box">
                            <div class="ays-survey-answer-icon-box">
                                <div class="ays-survey-action-delete-all-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl-small" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                                    <div class="ays-question-img-icon-content">
                                        <div class="ays-question-img-icon-content-div">
                                            <div class="ays-survey-icons">
                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ays-survey-condition-containers-ready">
                            <div class="ays-survey-condition-containers-list-main-box">
                                <div class="ays-survey-condition-containers-list-main">
                                    <?php
                                        $loop_iteration = 1;
                                        $cond_questions_count = count($condition_questions);
                                        foreach($condition_questions as $c_question_id => $c_question_value):
                                            $cond_question_type      = (isset($c_question_value['type']) && $c_question_value['type'] != "") ? esc_attr($c_question_value['type']) : "";
                                            $cond_question_id        = (isset($c_question_value['question_id']) && $c_question_value['question_id'] != "") ? $c_question_value['question_id'] : 0;
                                            // $cond_question_answer_id = (isset($c_question_value['answer_id']) && $c_question_value['answer_id'] != "") ? $c_question_value['answer_id'] : 0;
                                            $cond_question_answer    = (isset($c_question_value['answer']) && $c_question_value['answer'] != "") ? $c_question_value['answer'] : "";
                                            $cond_question_plus_cond = (isset($c_question_value['plus_condition']) && $c_question_value['plus_condition'] != "") ? $c_question_value['plus_condition'] : "";
                                            $cond_question_text_cond = (isset($c_question_value['equality']) && $c_question_value['equality'] != "") ? $c_question_value['equality'] : "";

                                            $check_loop_iteration = ($cond_questions_count > 1) ? "" : "display_none";
                                    ?>
                                        <div class="ays-survey-condition-select-question-box" data-question-id="<?php echo $cond_question_id;?>" data-question-name="question_id">
                                            <div class="ays-survey-condition-selects">
                                                <div class="ays-survey-condition-select-question-box-questions-if"><span><?php echo __("If", $this->plugin_name); ?></span></div>
                                                <div class="ays-survey-condition-select-question-box-questions">
                                                    <select class="ays-survey-condition-select-question" name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][question_id]">
                                                    <?php
                                                        $options = "";
                                                        $options .= '<option value="0">'. __("Select" , $this->plugin_name).'</option>';

                                                        $selected = "";
                                                        foreach($condition_html_questions as $question_id => $question_value){
                                                            if(isset($question_value['type']) && $question_value['type'] != "matrix_scale" && $question_value['type'] != "upload" && $question_value['type'] != "star_list" ){
                                                                $selected = (isset($c_question_value['question_id']) && $c_question_value['question_id'] == $question_id) ? "selected" : "";
                                                                $options .= "<option value='".$question_id."' data-type='".$question_value['type']."' data-question-id='".$question_id."' ".$selected.">".$question_value['question']."</option>";                                                            
                                                            }
                                                        }
                                                        echo $options;
                                                        ?>
                                                    </select>
                                                    <input type='hidden' class='ays-survey-condition-select-question-type-hidden' value="<?php echo $cond_question_type; ?>" name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][type]">
                                                </div>
                                                <div class="ays-survey-condition-select-question-answers" data-answer="<?php echo $cond_question_answer ?>">
                                                    <?php if(in_array($cond_question_type , $select_types)):?>
                                                        <select class='ays-survey-condition-select-question-with-answers' name='ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][answer]'>
                                                           <option value='0'><?php echo __("- Select -", $this->plugin_name); ?></option>
                                                            <?php if(isset($condition_html_questions[$cond_question_id])){
                                                                $cond_answers =  isset($condition_html_questions[$cond_question_id]['answers']) && !empty($condition_html_questions[$cond_question_id]['answers']) ? $condition_html_questions[$cond_question_id]['answers'] : array();                                                                
                                                                $answer_options  = "";
                                                                $answer_selected = "";
                                                                if(!empty($cond_answers)){
                                                                    foreach($cond_answers as $cond_answer_key => $cond_answer_value){
                                                                        $cond_answer_id = isset($cond_answer_value['id']) && $cond_answer_value['id'] != "" ? esc_attr($cond_answer_value['id']) : "";
                                                                        $answer_selected = $cond_question_answer == $cond_answer_id ? "selected" : "";
                                                                        $cond_answer_title = isset($cond_answer_value['answer']) && $cond_answer_value['answer'] != "" ? esc_attr($cond_answer_value['answer']) : "";
                                                                        $cond_answer_question_id = isset($cond_answer_value['question_id']) && $cond_answer_value['question_id'] != "" ? esc_attr($cond_answer_value['question_id']) : "";
                                                                        $answer_options .= "<option value=".$cond_answer_id." ".$answer_selected.">".$cond_answer_title."</option>";                                                                        
                                                                        
                                                                    }
                                                                }
                                                                echo $answer_options;
                                                            }
                                                            ?>
                                                        </select>
                                                    <?php elseif(in_array($cond_question_type , $text_types)):?>
                                                        <div class="ays-survey-condition-for-other-types">
                                                            <div class="ays-survey-condition-for-other-types-select">
                                                                <select class="ays-survey-condition-for-text-types-select" name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][equality]">
                                                                    <option value="==" <?php echo ($cond_question_text_cond == "==") ? "selected" : ""; ?>>Identical</option>
                                                                    <option value="contains" <?php echo ($cond_question_text_cond == "contains") ? "selected" : ""; ?>>Contains</option>
                                                                    <option value="not_contain" <?php echo ($cond_question_text_cond == "not_contain") ? "selected" : ""; ?>>Doesn't contain</option>
                                                                </select>
                                                            </div>
                                                            <div class="ays-survey-condition-for-other-types-text">
                                                                <input type="text" name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][answer]" value="<?php echo $cond_question_answer; ?>">
                                                            </div>
                                                        </div>
                                                    <?php elseif(in_array($cond_question_type , $number_types)):?>
                                                        <div class="ays-survey-condition-for-other-types">
                                                            <div class="ays-survey-condition-for-other-types-select">
                                                                <select class="ays-survey-condition-for-number-types-select" name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][equality]">
                                                                    <option value='==' <?php echo ($cond_question_text_cond == "==") ? "selected" : ""; ?>><?php echo __("Equal to" , $this->plugin_name); ?> (==)</option>
                                                                    <option value='!=' <?php echo ($cond_question_text_cond == "!=") ? "selected" : ""; ?>><?php echo __("Not equal to" , $this->plugin_name); ?> (!=)</option>
                                                                    <option value='>' <?php echo ($cond_question_text_cond == ">") ? "selected" : ""; ?>><?php echo __("Greater than" , $this->plugin_name); ?> (>)</option>
                                                                    <option value='>=' <?php echo ($cond_question_text_cond == ">=") ? "selected" : ""; ?>><?php echo __("Greater than or equal to" , $this->plugin_name); ?> (>=)</option>
                                                                    <option value='<' <?php echo ($cond_question_text_cond == "<") ? "selected" : ""; ?>><?php echo __("Less than" , $this->plugin_name); ?> (<)</option>
                                                                    <option value='<=' <?php echo ($cond_question_text_cond == "<=") ? "selected" : ""; ?>><?php echo __("Less than or equal to" , $this->plugin_name); ?> (>=)</option>
                                                                </select>
                                                            </div>
                                                            <div class="ays-survey-condition-for-other-types-text">
                                                                <input type='number' name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][answer]" value="<?php echo $cond_question_answer; ?>">
                                                            </div>
                                                        </div>
                                                    <?php elseif(in_array($cond_question_type, $other_types)):
                                                        $linear_length = isset($question_type_options[$cond_question_id]['length']) && !empty($question_type_options[$cond_question_id]['length']) ? intval($question_type_options[$cond_question_id]['length']) : 5;
                                                    ?>
                                                        <select class='ays-survey-condition-select-question-with-answers' name='ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][answer]'>
                                                           <option value='0'><?php echo __("- Select -", $this->plugin_name); ?></option>
                                                            <?php if(isset($condition_html_questions[$cond_question_id])){
                                                                $scale_length    =  isset($condition_html_questions[$cond_question_id]['answers']) && !empty($condition_html_questions[$cond_question_id]['answers']) ? $condition_html_questions[$cond_question_id]['answers'] : array();                                                                
                                                                $answer_options  = "";
                                                                $answer_selected = "";
                                                                    for($i = 1; $i <= $linear_length; $i++){
                                                                        $answer_selected = $cond_question_answer == $i ? "selected" : "";
                                                                        $cond_answer_question_id = isset($cond_answer_value['question_id']) && $cond_answer_value['question_id'] != "" ? esc_attr($cond_answer_value['question_id']) : "";
                                                                        $answer_options .= "<option ".$answer_selected." ".$answer_selected.">".$i."</option>";
                                                                    }
                                                                echo $answer_options;
                                                            }
                                                            ?>
                                                        </select>
                                                    <?php endif;?>
                                                </div>                                          
                                                <div class="ays-survey-condition-delete-currnet">
                                                    <div class="ays-survey-delete-question-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl-small ays-survey-delete-button-small <?php echo $check_loop_iteration;?>" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="Delete">
                                                        <div class="ays-question-img-icon-content">
                                                            <div class="ays-question-img-icon-content-div">
                                                               <div class="ays-survey-icons ays-survey-icons-small">
                                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL?>/images/icons/trash-small.svg">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ays-survey-condition-select-question-condition">
                                                <?php if($loop_iteration != $cond_questions_count):?>
                                                <select name="ays_condition_add[<?php echo $condition_id;?>][condition_question_add][<?php echo $cond_question_id;?>][plus_condition]">
                                                    <option value='and' <?php echo $cond_question_plus_cond == 'and' ? "selected" : "";?>><?php echo __("And", $this->plugin_name); ?></option>
                                                    <option value='or' <?php echo $cond_question_plus_cond == 'or' ? "selected" : "";?>><?php echo __("Or", $this->plugin_name); ?></option>

                                                </select>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    <?php $loop_iteration++; endforeach; ?>
                                </div>
                                <div class="ays-survey-condition-containers-add-list-button-box">
                                    <div class="ays-survey-action-add-sub-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl-small" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',$this->plugin_name)?>">
                                        <div class="ays-question-img-icon-content">
                                            <div class="ays-question-img-icon-content-div">
                                                <div class="ays-survey-icons ays-survey-icons-small">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline-small.svg">
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="ays-survey-condition-containers-conditions">
                                <div class="ays-survey-condition-containers-conditions-titles">
                                    <div class="ays-survey-condition-containers-conditions-titles-nav-bar">
                                        <div class="ays-survey-condition-containers-conditions-tabs nav-cond-tab-active" data-tab-id="tab1"><?php echo __("Page" , $this->plugin_name); ?></div>
                                        <div class="ays-survey-condition-containers-conditions-tabs " data-tab-id="tab2"><?php echo __("Email" , $this->plugin_name); ?></div>
                                        <div class="ays-survey-condition-containers-conditions-tabs " data-tab-id="tab3"><?php echo __("Redirect" , $this->plugin_name); ?></div>
                                        
                                    </div>
                                </div>
                                <div class="ays-survey-condition-containers-conditions-content">
                                    <div class="ays-survey-condition-containers-conditions-contents cond-tab1">
                                        <div class="ays-survey-condition-containers-conditions-contents-messages-title">
                                            <span class="ays-survey-messages-contnet-titles"><?php echo __("Result message" , $this->plugin_name); ?></span>
                                        </div>
                                        <hr>
                                        <div>
                                            <?php
                                                $content = $condition_messages_page_message;
                                                $editor_page_id = 'ays-survey-conditions-page-editor'.$condition_id;
                                                $textarea_page_name = "ays_condition_add[".$condition_id."][messages][page][message]";
                                                $settings = array('editor_height' => '8', 'textarea_name' => $textarea_page_name, 'editor_class' => 'ays-survey-conditions-messages-textareas', 'media_elements' => false);
                                                wp_editor($content, $editor_page_id, $settings);
                                            ?>
                                        </div>
                                    </div>          
                                    <div class="ays-survey-condition-containers-conditions-contents cond-tab2" style="display:none;">
                                        <div>
                                            <div class="ays-survey-condition-containers-conditions-contents-messages-title">
                                                <span class="ays-survey-messages-contnet-titles"><?php echo __("E-mail content" , $this->plugin_name); ?></span>
                                            </div>
                                            <hr>
                                            <div>
                                                <?php
                                                    $content = $condition_messages_email_message;
                                                    $editor_email_id = 'ays-survey-conditions-email-editor'.$condition_id;
                                                    $textarea_email_name = "ays_condition_add[".$condition_id."][messages][email][message]";
                                                    $settings = array('editor_height' => '8', 'textarea_name' => $textarea_email_name, 'editor_class' => 'ays-survey-conditions-messages-textareas', 'media_elements' => false);
                                                    wp_editor($content, $editor_email_id, $settings);
                                                ?>
                                            </div>  
                                        </div>
                                        <hr>
                                        <div class="ays-survey-email-message-files-main-contnet">
                                            <div class="ays-survey-condition-containers-conditions-contents-messages-title">
                                                <span class="ays-survey-messages-contnet-titles"><?php echo __("E-mail Attachment file" , $this->plugin_name); ?></span>
                                            </div>
                                            <hr>
                                            <div class="ays-survey-email-message-files">
                                                <div>
                                                    <button class="button ays-survey-add-email-message-files" type="button"><?php echo $cond_email_button_text; ?></button>
                                                    <input type="hidden" class="ays-survey-email-message-editor-file" name="ays_condition_add[<?php echo $condition_id;?>][messages][email][file]" value="<?php echo $condition_messages_email_file; ?>" >
                                                    <input type="hidden" class="ays-survey-email-message-editor-file-id" name="ays_condition_add[<?php echo $condition_id;?>][messages][email][file_id]" value="<?php echo $condition_messages_email_file_id; ?>" >
                                                </div>
                                                <div class="ays-survey-email-message-files-body <?php echo !$cond_delete_button ? "display_none_not_important" : "" ?>">
                                                    <div class="ays-survey-email-message-files-content">
                                                        <a href="<?php echo $condition_messages_email_file;?>" download="<?php echo $condition_messages_email_file;?>" class="ays-survey-email-message-files-content-text"><?php echo $condition_messages_email_file_name; ?></a>
                                                        <input type="hidden" class="ays-survey-email-message-editor-file-name" name="ays_condition_add[<?php echo $condition_id;?>][messages][email][file_name]" value="<?php echo $condition_messages_email_file_name; ?>" >
                                                    </div>
                                                </div>
                                                <div class="ays-survey-email-message-files-wrapper <?php echo !$cond_delete_button ? "display_none_not_important" : "" ?>">
                                                    <div class="ays-survey-email-message-files-wrapper-delete-wrap">
                                                        <div role="button" class="ays-survey-email-message-files-wrapper-delete-cont ays-survey-email-message-files-remove removeFile">
                                                            <div class="ays-survey-icons">
                                                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close-grey.svg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <img class="ays-survey-img" src="<#?php echo $survey_logo; ?>" tabindex="0" /> -->
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>          
                                    <div class="ays-survey-condition-containers-conditions-contents cond-tab3" style="display:none;">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="ays-survey-conditions-redirect-messages-labels">
                                                    <label class="ays-survey-redirect-message-delay-label" for="ays-survey-redirect-delay-current-<?php echo $condition_id;?>">
                                                        <?php echo __("Delay" , $this->plugin_name); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="ays-survey-conditions-redirect-messages">
                                                    <input type="number" class="ays-survey-redirect-message-delay ays-survey-conditions-messages-input" name="ays_condition_add[<?php echo $condition_id;?>][messages][redirect][delay]" value="<?php echo $condition_redirect_delay; ?>" id="ays-survey-redirect-delay-current-<?php echo $condition_id;?>">
                                                    <p class="ays_survey_small_hint_text_for_message_variables">
                                                        <span><?php echo __( "Delay time in seconds. To redirect immediately, set it to 0. For disabling redirect leave it blank." , $this->plugin_name ); ?></span>
                                                    </p>
                                                </div>   
                                            </div>
                                        </div>
                                        <hr>  
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <div class="ays-survey-conditions-redirect-messages-labels">
                                                    <label class="ays-survey-redirect-message-url-label" for="ays-survey-redirect-url-current-<?php echo $condition_id;?>">
                                                        <?php echo __("URL" , $this->plugin_name); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="ays-survey-conditions-redirect-messages">
                                                    <input type="text" class="ays-survey-redirect-message-url ays-survey-conditions-messages-input" name="ays_condition_add[<?php echo $condition_id;?>][messages][redirect][url]" value="<?php echo $condition_redirect_url; ?>" id="ays-survey-redirect-url-current-<?php echo $condition_id;?>">
                                                </div>                            
                                            </div>                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="ays-survey-action-add-condition appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',$this->plugin_name)?>">
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

        
        <!-- Condition to clone -->
        <div class="ays-survey-condition-containers-to-clone display_none" data-condition-id="1" data-condition-name="ays_condition_add">
            <div class="ays-survey-condition-delete-conteiner-box">
                <div class="ays-survey-answer-icon-box">
                    <div class="ays-survey-action-delete-all-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl-small" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',$this->plugin_name)?>">
                        <div class="ays-question-img-icon-content">
                            <div class="ays-question-img-icon-content-div">
                                <div class="ays-survey-icons">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ays-survey-condition-containers-ready">
                <div class="ays-survey-condition-containers-list-main-box">
                    <div class="ays-survey-condition-containers-list-main"></div>
                    <div class="ays-survey-condition-containers-add-list-button-box">
                        <div class="ays-survey-action-add-sub-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl-small" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',$this->plugin_name)?>">
                            <div class="ays-question-img-icon-content">
                                <div class="ays-question-img-icon-content-div">
                                    <div class="ays-survey-icons ays-survey-icons-small">
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline-small.svg">
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div> 
                <div class="ays-survey-condition-containers-conditions">
                    <div class="ays-survey-condition-containers-conditions-titles">
                        <div class="ays-survey-condition-containers-conditions-titles-nav-bar">
                            <div class="ays-survey-condition-containers-conditions-tabs nav-cond-tab-active" data-tab-id="tab1"><?php echo __("Page" , $this->plugin_name); ?></div>
                            <div class="ays-survey-condition-containers-conditions-tabs " data-tab-id="tab2"><?php echo __("Email" , $this->plugin_name); ?></div>
                            <div class="ays-survey-condition-containers-conditions-tabs " data-tab-id="tab3"><?php echo __("Redirect" , $this->plugin_name); ?></div>
                            <div class="ays-survey-condition-containers-conditions-tabs-disabled " style="background-color: #80808052;font-style: italic;padding: 0;" data-tab-id="tab4"><a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=developer" target="_blank" style="color: #0000008a;color: #0000008a;display: inline-block;width: 100%;height: 100%;padding: 5px 36px;"><?php echo __("Woocommerce" , $this->plugin_name); ?> <span style="font-size: 10px;">(Agency)</span></a></div>
                        </div>
                    </div>
                    <div class="ays-survey-condition-containers-conditions-content">
                        <div class="ays-survey-condition-containers-conditions-contents cond-tab1">
                            <div class="ays-survey-condition-containers-conditions-contents-messages-title">
                                <span class="ays-survey-messages-contnet-titles"><?php echo __("Result message" , $this->plugin_name); ?></span>
                            </div>
                            <hr>
                            <div>
                                <textarea class="ays-survey-page-message-editor wp-editor-area ays-survey-conditions-messages-textareas"></textarea>
                            </div>
                        </div>
                        <div class="ays-survey-condition-containers-conditions-contents cond-tab2" style="display:none;">
                            <div>
                                <div class="ays-survey-condition-containers-conditions-contents-messages-title">
                                    <span class="ays-survey-messages-contnet-titles"><?php echo __("Email content" , $this->plugin_name); ?></span>
                                </div>
                                <hr>
                                <div>
                                    <textarea class="ays-survey-email-message-editor wp-editor-area ays-survey-conditions-messages-textareas"></textarea>
                                </div>  
                            </div>
                            <hr>
                            <div class="ays-survey-email-message-files-main-contnet">
                                <div class="ays-survey-condition-containers-conditions-contents-messages-title">
                                    <span class="ays-survey-messages-contnet-titles"><?php echo __("Email Attachment file" , $this->plugin_name); ?></span>
                                </div>
                                <hr>
                                <div class="ays-survey-email-message-files">
                                    <div>
                                        <button class="button ays-survey-add-email-message-files" type="button"><?php echo __("Add File" , $this->plugin_name); ?></button>
                                        <input type="hidden" class="ays-survey-email-message-editor-file">
                                        <input type="hidden" class="ays-survey-email-message-editor-file-id">
                                    </div>
                                    <div class="ays-survey-email-message-files-body display_none_not_important">
                                        <div class="ays-survey-email-message-files-content">
                                            <a class="ays-survey-email-message-files-content-text"></a>
                                            <input type="hidden" class="ays-survey-email-message-editor-file-name">
                                        </div>
                                    </div>
                                    <div class="ays-survey-email-message-files-wrapper display_none_not_important">
                                        <div class="ays-survey-email-message-files-wrapper-delete-wrap">
                                            <div role="button" class="ays-survey-email-message-files-wrapper-delete-cont ays-survey-email-message-files-remove removeFile">
                                                <div class="ays-survey-icons">
                                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close-grey.svg">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <img class="ays-survey-img" src="<#?php echo $survey_logo; ?>" tabindex="0" /> -->
                                        
                                    </div>
                                </div>
                            </div>

                        </div>          
                        <div class="ays-survey-condition-containers-conditions-contents cond-tab3" style="display:none;">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <div class="ays-survey-conditions-redirect-messages-labels">
                                        <label class="ays-survey-redirect-message-delay-label">
                                            <?php echo __("Delay" , $this->plugin_name); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="ays-survey-conditions-redirect-messages">
                                        <input type="number" class="ays-survey-redirect-message-delay ays-survey-conditions-messages-input">
                                    </div>   
                                </div>
                            </div>
                            <hr>  
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <div class="ays-survey-conditions-redirect-messages-labels">
                                        <label class="ays-survey-redirect-message-url-label">
                                            <?php echo __("URL" , $this->plugin_name); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="ays-survey-conditions-redirect-messages">
                                        <input type="text" class="ays-survey-redirect-message-url ays-survey-conditions-messages-input">
                                    </div>                            
                                </div>                            
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="form-group row">
            <div class="col-sm-4">
                <label for="ays_survey_condition_show_all_results">
                    <?php echo __('Show all conditions results',$this->plugin_name)?>
                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option and the result messages of all true conditions will be displayed on the Front-end after the survey submission. Note: The option will work for only the Page tab (not for the Email and Redirect tabs).',$this->plugin_name)?>">
                        <i class="ays_fa ays_fa_info_circle"></i>
                    </a>
                </label>
            </div>
            <div class="col-sm-8">
                <input type="checkbox" class="ays-enable-timer1" id="ays_survey_condition_show_all_results" name="ays_survey_condition_show_all_results" value="on" <?php echo $survey_condition_show_all_results ? 'checked' : '' ?>/>
            </div>
        </div> <!-- Show all conditions results -->
    </div>
</div>