<div id="tab1" class="m2 ays-survey-tab-content <?php echo ($ays_tab == 'tab1') ? 'ays-survey-tab-content-active' : ''; ?>">
<!-- <div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active"> -->

    <div class="form-group row">
        <div class="col-sm-2">
            <h1><?php echo $heading; ?></h1>
        </div>
    </div> <!-- Header -->
        
    <hr/>

    <div class="form-group row">
        <div class="col-sm-2">
            <label for='ays-title'>
                <?php echo __('Título', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Informe o título do Cliente.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" maxlength="100" id='ays-title' name='<?php echo $html_name_prefix; ?>title' value="<?php echo $title; ?>" />
        </div>
    </div> <!-- Title -->

    <hr/>

    <!-- ////////////////////////// -->

        <div class="form-group row">
            <label for="ays-trade_name" class="col-sm-2 col-form-label">Nome Comercial:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="100" id="ays-trade_name" name="<?php echo $html_name_prefix; ?>trade_name"  value="<?php echo $trade_name; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-business_name" class="col-sm-2 col-form-label">Razão Social:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="100" id="ays-business_name" name="<?php echo $html_name_prefix; ?>business_name" value="<?php echo $business_name; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-type_person" class="col-sm-2 col-form-label">Tipo de Pessoa (Física/Jurídica):</label>
            <div class="col-sm-8">
                <select id="ays-type_person" name="<?php echo $html_name_prefix; ?>type_person">
                    <option <?php selected( $type_person, 'F' ); ?> value="F"><?php echo __( "F", $this->plugin_name ); ?></option>
                    <option <?php selected( $type_person, 'J' ); ?> value="J"><?php echo __( "J", $this->plugin_name ); ?></option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-cnpj_cpf" class="col-sm-2 col-form-label">CNPJ/CPF:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="20" id="ays-cnpj_cpf" name="<?php echo $html_name_prefix; ?>cnpj_cpf" value="<?php echo $cnpj_cpf; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-email" class="col-sm-2 col-form-label">E-mail:</label>
            <div class="col-sm-8">
                <input type="email" class="ays-text-input" maxlength="100" id="ays-email" name="<?php echo $html_name_prefix; ?>email" value="<?php echo $email; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-address" class="col-sm-2 col-form-label">Endereço:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="256" id="ays-address" name="<?php echo $html_name_prefix; ?>address" value="<?php echo $address; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-zip_code" class="col-sm-2 col-form-label">CEP:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="10" id="ays-zip_code" name="<?php echo $html_name_prefix; ?>zip_code" value="<?php echo $zip_code; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-city" class="col-sm-2 col-form-label">Cidade:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="100" id="ays-city" name="<?php echo $html_name_prefix; ?>city" value="<?php echo $city; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-state_acronym" class="col-sm-2 col-form-label">Estado:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="3" id="ays-state_acronym" name="<?php echo $html_name_prefix; ?>state_acronym" value="<?php echo $state_acronym; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-country" class="col-sm-2 col-form-label">País:</label>
            <div class="col-sm-8">
                <input type="text" class="ays-text-input" maxlength="3" id="ays-country" name="<?php echo $html_name_prefix; ?>country" value="<?php echo $country; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-logotype" class="col-sm-2 col-form-label">Logotipo:</label>
            <div class="col-sm-8">                
                <input type="text" class="ays-text-input" maxlength="100" id="ays-ether_logotype" name="<?php echo $html_name_prefix; ?>logotype"  value="<?php echo $logotype; ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="ays-ether_address" class="col-sm-2 col-form-label">Endereço Ether:</label>
            <div class="col-sm-8">                
                <input type="text" class="ays-text-input" maxlength="100" id="ays-ether_address" name="<?php echo $html_name_prefix; ?>ether_address"  value="<?php echo $ether_address; ?>" required>
            </div>
        </div>

    <!-- ////////////////////////// -->

    <hr/>

    <div class="form-group row">
        <div class="col-sm-2">
            <label for="ays-status">
                <?php echo __('Status do Cliente', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo htmlspecialchars( __("Status do cliente.",$this->plugin_name) ); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-10">
            <select id="ays-status" name="<?php echo $html_name_prefix; ?>status">
                <option></option>
                <option <?php selected( $status, 'published' ); ?> value="published"><?php echo __( "Published", $this->plugin_name ); ?></option>
                <option <?php selected( $status, 'draft' ); ?> value="draft"><?php echo __( "Draft", $this->plugin_name ); ?></option>
            </select>
        </div>
    </div> <!-- Status -->

    <div class="form-group row">
        <input type="hidden" name="<?php echo $html_name_prefix; ?>date_created" value="<?php echo $date_created; ?>">
        <input type="hidden" name="<?php echo $html_name_prefix; ?>date_modified" value="<?php echo $date_modified; ?>">
        <hr/>
        <div class="col-sm-8">
            <?php
                //wp_nonce_field('survey_category_action', 'survey_category_action');
                wp_nonce_field('survey_customer_action', 'survey_customer_action');
                $other_attributes = array('id' => 'ays-button-save');
                submit_button(__('Salvar e fechar', $this->plugin_name), 'primary ays-button ays-survey-loader-banner', 'ays_submit', false, $other_attributes);
                $other_attributes = array('id' => 'ays-button-save-new');
                submit_button(__('Salvar e Novo', $this->plugin_name), 'primary ays-button ays-survey-loader-banner', 'ays_save_new', false, $other_attributes);
                $other_attributes = array(
                    'id' => 'ays-button-apply',
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );

                submit_button(__('Salvar', $this->plugin_name), 'ays-button ays-survey-loader-banner', 'ays_apply', false, $other_attributes);

                echo $loader_iamge;
            ?>
        </div>
    </div> <!-- Botões -->

</div>
