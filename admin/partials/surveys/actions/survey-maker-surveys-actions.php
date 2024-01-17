<?php
    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/survey-maker-surveys-actions-options.php" );
    //SAMPLES
    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/survey-maker-survey-samples-actions-options.php" );
?>
<?php echo $survey_colors;?>
<div class="wrap">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
                </a>
            </div>
        </div>
        <form method="post" id="ays-survey-form" data-id="<?php echo $id ?>">
            <input type="hidden" name="ays_survey_tab" value="<?php echo $ays_tab; ?>">
            <h1 class="wp-heading-inline">
                <?php
                    echo $heading;
                    $other_attributes = array('id' => 'ays-button-save-top');
                    if( $action == 'edit' ){
                        $other_attributes['disabled'] = 'disabled';
                    }

                    submit_button(__('Salvar e fechar', $this->plugin_name), 'primary ays-button ays-survey-loader-banner', 'ays_submit_top', false, $other_attributes);
                    // $other_attributes = array('id' => 'ays-button-save-new-top');
                    // submit_button(__('Save and new', $this->plugin_name), 'primary ays-button', 'ays_save_new_top', false, $other_attributes);
                    $other_attributes = array(
                        'id' => 'ays-button-apply-top',
                        'title' => 'Ctrl + s',
                        'data-toggle' => 'tooltip',
                        'data-delay'=> '{"show":"1000"}'
                    );
                    if( $action == 'edit' ){
                        $other_attributes['disabled'] = 'disabled';
                    }
                    submit_button(__('Salvar', $this->plugin_name), 'ays-button ays-survey-loader-banner', 'ays_apply_top', false, $other_attributes);
                    submit_button(__('Cancelar', "survey-maker"), 'ays-button', 'ays_survey_cancel', false, array());
                    echo $loader_iamge;
                ?>
            </h1>
            <div class="ays-survey-subtitle-main-box">
                <p class="ays-subtitle">
                    <?php if(isset($id) && count($get_all_surveys) > 1):?>
                        <span class="ays-subtitle-inner-surveys-page">
                                <i class="ays_fa ays_fa_arrow_down ays-survey-open-surveys-list" style="font-size: 15px;"></i>   
                        </span>
                        <strong class="ays_survey_title_in_top"><?php echo esc_attr( stripslashes( $object['title'] ) ); ?></strong>

                    <?php endif; ?>
                </p>
                <?php if(isset($id) && count($get_all_surveys) > 1):?>
                    <div class="ays-survey-surveys-data">
                        <?php $var_counter = 0; foreach($get_all_surveys as $var => $var_name): if( intval($var_name['id']) == $id ){continue;} $var_counter++; ?>
                            <?php ?>
                            <label class="ays-survey-message-vars-each-data-label">
                                <input type="radio" class="ays-survey-surveys-each-data-checker" hidden id="ays_survey_message_var_count_<?php echo $var_counter?>" name="ays_survey_message_var_count">
                                <div class="ays-survey-surveys-each-data">
                                    <input type="hidden" class="ays-survey-surveys-each-var" value="<?php echo $var; ?>">
                                    <a href="?page=survey-maker&action=edit&id=<?php echo $var_name['id']?>" target="_blank" class="ays-survey-go-to-surveys"><span><?php echo stripslashes(esc_attr($var_name['title'])); ?></span></a>
                                </div>
                            </label>              
                        <?php endforeach ?>
                    </div>                        
                <?php endif; ?>
            </div>
            <!-- JEFERSON - TITULO DO QUESTIONÁRIO REMOVIDO E COLOCADO NA ABA QUESTIONARIO (TAB1) -->
            <!-- <div class="form-group row">
                <div class="col-sm-2">
                    <label for='ays-survey-title'>
                        <php echo __('Title', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<php echo __('Give a title to your survey.',$this->plugin_name); >">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <input type="text" class="ays-text-input" id='ays-survey-title' name='<?php echo $html_name_prefix; ?>title' value="<?php echo $title; ?>"/>
                </div>
            </div>  -->

            <!-- Survey Title -->
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for='ays-survey-title'>
                    <?php echo __('Título do Questionário:', $this->plugin_name); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Dê um título ao seu questionário.',$this->plugin_name); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>                    
                <div class="col-sm-8">
                    <input type="text" class="ays-text-input" id='ays-survey-title' name='<?php echo $html_name_prefix; ?>title' value="<?php echo $title; ?>"/>
                </div>                
            </div>            
            <!-- Survey Title -->
            <hr/>

            <div class="ays-top-menu-wrapper">
                <div class="ays_menu_left" data-scroll="0"><i class="ays_fa ays_fa_angle_left"></i></div>
                <div class="ays-top-menu">
                    <div class="nav-tab-wrapper ays-top-tab-wrapper">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Questionário", $this->plugin_name);?>
                        </a>
                        <a href="#tab10" data-tab="tab10" class="nav-tab <?php echo ($ays_tab == 'tab10') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Listas", $this->plugin_name);?>
                        </a>
                        <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Estilos", $this->plugin_name);?>
                        </a>
                        <a href="#tab6" data-tab="tab6" class="nav-tab <?php echo ($ays_tab == 'tab6') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Página Inicial", $this->plugin_name);?>
                        </a>
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Configurações", $this->plugin_name);?>
                        </a>
                        <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Config. de resultados", $this->plugin_name);?>
                        </a>
                        <a href="#tab9" data-tab="tab9" class="nav-tab <?php echo ($ays_tab == 'tab9') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Resultados Condicionais", $this->plugin_name);?>
                        </a>
                        <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Limitações de Usuários", $this->plugin_name);?>
                        </a>
                        <a href="#tab7" data-tab="tab7" class="nav-tab <?php echo ($ays_tab == 'tab7') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("E-Mail", $this->plugin_name);?>
                        </a>
                        <a href="#tab8" data-tab="tab8" class="nav-tab <?php echo ($ays_tab == 'tab8') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Integrações", $this->plugin_name);?>
                        </a>
                    </div>  
                </div>
                <div class="ays_menu_right" data-scroll="-1"><i class="ays_fa ays_fa_angle_right"></i></div>
            </div>
            
            <?php
                for($tab_ind = 1; $tab_ind <= 10; $tab_ind++){
                    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/survey-maker-surveys-actions-tab".$tab_ind.".php" );
                }
            ?>

            <div class="ays-modal" id="ays-survey-move-to-section">
                <div class="ays-modal-content">
                    <div class="ays-survey-preloader">
                        <img class="loader" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
                    </div>

                    <!-- Modal Header -->
                    <div class="ays-modal-header">
                        <span class="ays-close">&times;</span>
                        <h2><?php echo __('Move to section', $this->plugin_name); ?></h2>
                    </div>

                    <!-- Modal body -->
                    <div class="ays-modal-body">
                        <div class="ays-survey-move-to-section-sections-wrap">

                        </div>
                    </div>

                    <!-- Modal footer -->
                </div>
            </div>

            <!-- JEFERSON -->
            <input type="hidden" name='<?php echo $html_name_prefix; ?>survey_id' value="<?php echo $survey_id; ?>"/>

            <input type="hidden" name="<?php echo $html_name_prefix; ?>default_answers_count" value="<?php echo $survey_answer_default_count; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>default_question_type" value="<?php echo $survey_default_type; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>author_id" value="<?php echo $author_id; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>post_id" value="<?php echo $post_id; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>date_created" value="<?php echo $date_created; ?>">
            <input type="hidden" name="<?php echo $html_name_prefix; ?>date_modified" value="<?php echo $date_modified; ?>">
            <hr>
            <?php
                wp_nonce_field('survey_action', 'survey_action');
                $other_attributes = array();
                $buttons_html = '';
                $buttons_html .= '<div class="ays_save_buttons_content">';
                    $buttons_html .= '<div class="ays_save_buttons_box">';
                    echo $buttons_html;
                        $other_attributes = array('id' => 'ays-button-save');
                        if( $action == 'edit' ){
                            $other_attributes['disabled'] = 'disabled';
                        }

                        submit_button(__('Salvar e fechar', $this->plugin_name), 'primary ays-button ays-survey-loader-banner', 'ays_submit', false, $other_attributes);
                        // $other_attributes = array('id' => 'ays-button-save-new');
                        // submit_button(__('Save and new', $this->plugin_name), 'primary ays-button', 'ays_save_new', false, $other_attributes);
                        $other_attributes = array(
                            'id' => 'ays-button-apply',
                            'title' => 'Ctrl + s',
                            'data-toggle' => 'tooltip',
                            'data-delay'=> '{"show":"1000"}'
                        );

                        if( $action == 'edit' ){
	                        $other_attributes['disabled'] = 'disabled';
                        }

                        submit_button(__('Salvar', $this->plugin_name), 'ays-button ays-survey-loader-banner', 'ays_apply', false, $other_attributes);
                        submit_button(__('Cancelar', "survey-maker"), 'ays-button', 'ays_survey_cancel', false, array());
                        echo $loader_iamge;
                    $buttons_html = '</div>';
                    $buttons_html .= '<div class="ays_save_default_button_box">';
                    echo $buttons_html;
                        $buttons_html = '<a class="ays_help" data-toggle="tooltip" title="'. __( 'Saves the assigned settings of the current survey as default. After clicking on this button, each time creating a new survey, the system will take the settings and styles of the current survey. If you want to change and renew it, please click on this button on another survey.', $this->plugin_name ) .'">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>';
                        echo $buttons_html;
                        $other_attributes = array( 'data-message' => __( 'Are you sure that you want to save these parameters as default?', $this->plugin_name ) );
                        submit_button(__('Salvar como Default', $this->plugin_name), 'primary ays_default_btn', 'ays_default', false, $other_attributes);
                    $buttons_html = '</div>';
                $buttons_html .= "</div>";
                echo $buttons_html;
            ?>
        </form>

        <div class="ays-modal" id="ays-edit-question-content">
            <div class="ays-modal-content">
                <div class="ays-survey-preloader">
                    <img class="loader" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
                </div>

                <!-- Modal Header -->
                <div class="ays-modal-header">
                    <span class="ays-close">&times;</span>
                    <h2>
                        <div class="ays-survey-icons" style="width:36px;height:36px;line-height: 0;vertical-align: bottom;">
                            <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/edit-content.svg" style="vertical-align: initial;line-height: 0;margin: 0px;padding: 0;width: 36px;height: 36px;">
                        </div>
                        <span><?php echo __( 'Edit question', $this->plugin_name ); ?></span>
                    </h2>
                </div>

                <!-- Modal body -->
                <div class="ays-modal-body">
                    <form method="post" id="ays_export_filter">
                        <div style="padding: 15px 0;">
                        <?php
                            $content = '';
                            $editor_id = 'ays_survey_question_editor';
                            $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_question_editor', 'editor_class' => 'ays-textarea');
                            wp_editor($content, $editor_id, $settings);
                        ?>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="ays-modal-footer ays-modal-footer-textarea-editor">
                    <button type="button" class="button button-primary ays-survey-back-to-textarea" data-question-id="" data-question-name="" style="margin-right: 10px;"><?php echo __( 'Back to classic texarea', $this->plugin_name ); ?></button>
                    <button type="button" class="button button-primary ays-survey-apply-question-changes" data-question-id="" data-question-name=""><?php echo __( 'Apply changes', $this->plugin_name ); ?></button>
                </div>
            </div>
        </div>

        <!-- Questions library start -->
        <div id="ays-questions-modal" class="ays-modal">
            <!-- Modal content -->
            <div class="ays-modal-content">
                <form method="post" id="ays_add_question_rows">
                    <div class="ays-survey-preloader">
                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/cogs.svg">
                    </div>
                    <div class="ays-modal-header">
                        <span class="ays-close">&times;</span>
                        <h2><?php echo __('Insert questions', $this->plugin_name); ?></h2>
                    </div>
                    <div class="ays-modal-body">
                        <div style="margin-bottom: 5px; padding: 1rem 0;">
                            <p class="submit">
                                <input type="submit" name="add_question_rows_top" id="add_question_rows_top" class="button button-primary" value="<?php echo __( 'Insert questions', $this->plugin_name ); ?>">
                            </p>
                            <span style="font-size: 13px; font-style: italic;">
                                <?php echo __('Here you can find the questions from your other surveys. Tick the questions that you want to add to your survey and click the "Insert questions" button.', $this->plugin_name); ?>
                            </span>
                        </div>
                        <div class="row" style="margin:0;">
                            <div class="col-sm-12" id="quest_cat_container">
                                <label style="width:100%;" for="add_quest_category_filter">
                                    <p style="font-size: 13px; margin:0; font-style: italic;">
                                        <?php echo __( "Filter by survey", $this->plugin_name); ?>
                                        <button type="button" class="ays_filter_cat_clear button button-small wp-picker-default"><?php echo __( "Clear", $this->plugin_name ); ?></button>
                                    </p>
                                </label>
                                <select id="add_quest_category_filter" multiple="multiple" class='cat_filter custom-select custom-select-sm form-control form-control-sm'>
                                    <?php
                                        $filter_surveys = $this->get_all_surveys();
                                        foreach( $filter_surveys as $filter_survey ){
                                            if( $id == absint( $filter_survey->id ) ){
                                                continue;
                                            }
                                            echo "<option value='" . $filter_survey->id . "'>" . $filter_survey->title . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="ays-add-questions-table hover order-column" id="ays-question-table-add" data-survey-id="<?php echo $id == null ? 0 : $id; ?>" data-page-length='5'>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo __('Question', $this->plugin_name); ?></th>
                                    <th style="width:250px;"><?php echo __('Type', $this->plugin_name); ?></th>
                                    <th style="width:250px;"><?php echo __('Created', $this->plugin_name); ?></th>
                                    <th style="width:250px;"><?php echo __('Modified', $this->plugin_name); ?></th>
                                    <th style="width:50px;">ID</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ays-modal-footer" style="justify-content:flex-start;">
                        <p class="submit m-0 p-0">
                            <input type="submit" name="add_question_rows" id="ays-button" class="button button-primary" value="<?php echo __( 'Insert questions', $this->plugin_name ); ?>">
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <!-- Questions library end -->

        <div class="ays-modal" id="ays-survey-insert-into-section">
            <div class="ays-modal-content">
                <div class="ays-survey-preloader">
                    <img class="loader" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
                </div>

                <!-- Modal Header -->
                <div class="ays-modal-header">
                    <span class="ays-close">&times;</span>
                    <h2><?php echo __('Insert into section', $this->plugin_name); ?></h2>
                </div>

                <!-- Modal body -->
                <div class="ays-modal-body">
                    <div class="ays-survey-insert-into-section-sections-wrap">

                    </div>
                </div>

                <!-- Modal footer -->
            </div>
        </div>

        <!-- Question import start -->
        <div class="ays-modal" id="ays-survey-export-question-modal">
            <div class="ays-modal-content ays-modal-content-question-import">
                <div class="ays_survey_preloader" style="display:none;">
                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL ; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
                </div>
                <!-- Modal Header -->
                <div class="ays-modal-header">
                    <span class="ays-close">&times;</span>
                    <h2><?=__('Import questions', $this->plugin_name)?></h2>
                </div>
                <!-- Modal body -->
                <div class="ays-modal-body">
                    <form method="post" id="ays_survey_import_questions_form">
                        <div>
                            <p style="margin: 0;font-size: 15px;text-indent: 15px;"><?php echo __( 'You can import questions to your survey.', $this->plugin_name ); ?></p>
                            <p style="margin: 0;font-size: 15px;text-indent: 15px;"><?php 
                                echo sprintf(
                                    __("Click the %sChoose file%s button and select your .xlsx file to import. The file can include the question(s), answer(s), and type. Question types should be written properly: %sradio%s, %scheckbox%s, %sselect%s, %slinear_scale%s, %sstar%s, %stext%s, %sshort_text%s, %snumber%s, %sdate%s, %syesorno%s, %semail%s, %sname%s. Note, that you cannot import matrix scale, star list, slider list and range questions.",$this->plugin_name ),
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>',
                                    '<strong><em>',
                                    '</em></strong>'
                                );
                            ?></p>
                        </div>
                        <div class="filter-col-question-import">
                            <a href="<?php echo SURVEY_MAKER_ADMIN_URL;?>/partials/surveys/export_file/survey_example_questions_export.xlsx" download="survey_example_questions_export" type="button" class="button button-small ays-survey-export-question-example">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/export_example.svg" style='width:20px;'>
                                <?=__("Export example", $this->plugin_name)?>
                            </a>
                        </div>
                        <div class="filter-block">
                            <div class="filter-block filter-col">
                                <div>
                                    <label for="ays_survey_import_question_filter"><?= sprintf(__("Choose %s file to import", $this->plugin_name) , "<strong>.xlsx</strong>")?>
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/import.svg" style='width:20px;'>
                                    </label>
                                </div>
                                <div class="ays-survey-import-question-import-box">
                                    <div>
                                        <input type="file" accept=".xlsx" name="ays_survey_import_question_filter" id="ays_survey_import_question_filter">
                                    </div>
                                    <div>
                                        <button type="button" class="button button-primary ays-survey-questions-import-action" data-type="xlsx" disabled><?=__('Import', $this->plugin_name)?></button>
                                    </div>
                                </div>
                                <div class="ays-survey-question-import-modal-error-message display_none_not_important">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Question import end -->

        <!-- Survey Answers bulk add start -->
        <div class="ays-modal" id="ays-survey-maker-answers-bulk-add-modal">
            <div class="ays-modal-content">
                <!-- Modal Header -->
                <div class="ays-modal-header">
                    <div class="ays-survey-answers-bulk-add-header">
                        <span class="ays-close">&times;</span>
                        <h2><?php echo __('Bulk Add', 'survey-maker') ?></h2>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="ays-modal-body">
                    <div class="ays-survey-answers-bulk-add-content">
                        <div class="ays-survey-answers-bulk-add-container">
                            <div class="ays-survey-answers-bulk-add-hint">
                                <span class="ays-survey-answers-bulk-add"><?php echo __('Add Choices (one per line)', 'survey-maker') ?></span>
                                <span class="ays-survey-answers-bulk-edit display_none"><?php echo __('Edit or add field options (one per line)', 'survey-maker') ?></span>
                            </div>
                            <div class="ays-survey-answers-bulk-add-answers">
                                <textarea name="ays_survey_answers_bulk_add" id="ays-survey-answers-bulk-add" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="ays-modal-footer">
                    <div class="ays-survey-answers-bulk-add-footer">
                        <button type="button" id="ays-survey-answers-bulk-save"><?php echo __('Apply Changes', 'survey-maker') ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Survey Answers bulk add end -->

        <!-- Survey Templates start -->
            <div class="ays-modal" id="ays-survey-templates-modal" <?php echo $action_is_add ? 'style="display: flex"' : '' ?>>
                <div class="ays-modal-content ays-modal-content-survey-templates <?php echo $action_is_add ? 'no-confirmation' : '' ?>">
                    <div class="ays_survey_preloader" style="display:none">
                        <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) ; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
                    </div>
                    <!-- Modal Header -->
                    <div class="ays-modal-header ays-modal-content-survey-templates-header">
                        <span class="ays-close">&times;</span>
                        <h2><?=__('Survey Templates', "survey-maker")?></h2>
                    </div>
                    <!-- Modal body -->
                    <div class="ays-modal-body ays-modal-content-survey-templates-body">
                        <div class="ays-survey-templates-container">
                            <div class="ays-survey-templates-content">
                                <div class="ays-survey-templates-box ays-survey-templates-blank-box" data-template="blank-form">
                                    <div class="ays-survey-templates-box-text">
                                        <div class="ays-survey-templates-box-image ays-survey-templates-box-blank-images ays-survey-templates-box-image-blank">
                                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) ; ?>/images/templates/blank-plus.png">
                                        </div>
                                        <div class="ays-survey-templates-box-texts ays-survey-templates-box-blank-texts">
                                            <!-- <div class="ays-survey-templates-box-desc"><img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/add-circle-outline.svg"></div> -->
                                            <h4 class="ays-survey-templates-box-title"><?php echo __('Blank Survey', "survey-maker")?></h4>
                                        </div>
                                        <div class="ays-survey-templates-box-buttons">
                                            <button class="ays-survey-templates-box-apply-button-blank"><?php echo __("Choose" , "survey-maker"); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-templates-box" data-template="customer-feedback-form">
                                    <div class="ays-survey-templates-box-text">                                        
                                        <div class="ays-survey-templates-box-image ays-survey-templates-box-blank-images ays-survey-templates-box-image-customer-feedback">
                                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) ; ?>/images/templates/customer-feedback.png">
                                        </div>
                                        <div class="ays-survey-templates-box-texts">
                                            <h4 class="ays-survey-templates-box-title"><?php echo __('Customer Feedback Form Template', "survey-maker")?></h4>
                                            <div class="ays-survey-templates-box-desc"><?php echo __('Beautiful, fun, easy to complete. Comes with useful rating questions.', "survey-maker")?></div>
                                        </div>
                                        <div class="ays-survey-templates-box-buttons">
                                            <button class="ays-survey-templates-box-apply-button" data-template="customer-feedback-form"><?php echo __("Choose Template" , "survey-maker"); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-templates-box" data-template="employee-satisfaction-survey">
                                    <div class="ays-survey-templates-box-text">                                        
                                        <div class="ays-survey-templates-box-image ays-survey-templates-box-blank-images ays-survey-templates-box-image-employee-satisfaction">
                                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) ; ?>/images/templates/employee-satisfaction.png">
                                        </div>
                                        <div class="ays-survey-templates-box-texts">
                                            <h4 class="ays-survey-templates-box-title"><?php echo __('Employee Satisfaction Survey Template', "survey-maker")?></h4>
                                            <div class="ays-survey-templates-box-desc"><?php echo __('Great for honing in on specific things to improve.', "survey-maker")?></div>
                                        </div>
                                        <div class="ays-survey-templates-box-buttons">
                                            <button class="ays-survey-templates-box-apply-button" data-template="employee-satisfaction-survey"><?php echo __("Choose Template" , "survey-maker"); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-templates-box" data-template="event-evaluation-survey">
                                    <div class="ays-survey-templates-box-text">                                        
                                        <div class="ays-survey-templates-box-image ays-survey-templates-box-blank-images ays-survey-templates-box-image-event-evaluation">
                                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) ; ?>/images/templates/event-evaluation.png">
                                        </div>
                                        <div class="ays-survey-templates-box-texts">
                                            <h4 class="ays-survey-templates-box-title"><?php echo __('Event Evaluation Survey Template', "survey-maker")?></h4>
                                            <div class="ays-survey-templates-box-desc"><?php echo __('Get honest feedback from guests and use it to improve your upcoming events.', "survey-maker")?></div>
                                        </div>
                                        <div class="ays-survey-templates-box-buttons">
                                            <button class="ays-survey-templates-box-apply-button" data-template="event-evaluation-survey"><?php echo __("Choose Template" , "survey-maker"); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ays-survey-templates-box" data-template="product-research-survey">
                                    <div class="ays-survey-templates-box-text">                                        
                                        <div class="ays-survey-templates-box-image ays-survey-templates-box-blank-images ays-survey-templates-box-image-product-research">
                                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) ; ?>/images/templates/product.png">
                                        </div>
                                        <div class="ays-survey-templates-box-texts">
                                            <h4 class="ays-survey-templates-box-title"><?php echo __('Product Research Survey Template', "survey-maker")?></h4>
                                            <div class="ays-survey-templates-box-desc"><?php echo __('Developing a product? Find out more about your target audience with this survey', "survey-maker")?></div>
                                        </div>
                                        <div class="ays-survey-templates-box-buttons">
                                            <button class="ays-survey-templates-box-apply-button" data-template="product-research-survey"><?php echo __("Choose Template" , "survey-maker"); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Survey Templates end -->


    </div>
</div>
