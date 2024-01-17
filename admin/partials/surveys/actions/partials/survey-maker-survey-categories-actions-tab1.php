<div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active">
    <div class="form-group row">
        <div class="col-sm-2">
            <label for='ays-title'>
                <?php echo __('Título', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Informe o título da Categoria.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-10">
            <input type="text" class="ays-text-input" id='ays-title' name='<?php echo $html_name_prefix; ?>title' value="<?php echo $title; ?>" />
        </div>
    </div> <!-- Title -->
    <hr/>
    <div class='ays-field-dashboard'>
        <label for='ays-description'>
            <?php echo __('Descrição', $this->plugin_name); ?>
            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Forneça mais informações sobre a categoria do questionário. Anexe imagens ou qualquer outra mídia à sua descrição, se desejar.',$this->plugin_name)?>">
                <i class="ays_fa ays_fa_info_circle"></i>
            </a>
        </label>
        <?php
            $content = $description;
            $editor_id = 'ays-description';
            $settings = array( 
                'editor_height' => $survey_wp_editor_height,
                'textarea_name' => $html_name_prefix . 'description',
                'editor_class' => 'ays-textarea'
            );
            wp_editor( $content, $editor_id, $settings );
        ?>
    </div> <!-- Description -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-2">
            <label for="ays-status">
                <?php echo __('Category status', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo htmlspecialchars( __("Decide whether the survey category is active or not. If the category is a draft, it won't be shown anywhere on your website (you don't need to remove shortcodes).",$this->plugin_name) ); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-10">
            <select id="ays-status" name="<?php echo $html_name_prefix; ?>status">
                <option></option>
                <option <?php selected( $status, 'publicado' ); ?> value="published"><?php echo __( "Published", $this->plugin_name ); ?></option>
                <option <?php selected( $status, 'rascunho' ); ?> value="draft"><?php echo __( "Draft", $this->plugin_name ); ?></option>
            </select>
        </div>
    </div> <!-- Status -->
</div>
