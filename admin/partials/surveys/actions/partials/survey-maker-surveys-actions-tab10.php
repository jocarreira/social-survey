<?php
    $path = plugin_dir_path(dirname(__FILE__)) . 'includes/lists/class-survey-maker-surveys-list-table.php';
    $path_for_js = json_encode($path);
?>
<div id="tab10" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab10') ? 'ays-survey-tab-content-active' : ''; ?>">
<!-- <div id="tab2" class="ays-survey-tab-content ays-survey-tab-content-active"> -->

    <div class="form-group row">
        <div class="col-sm-2">
            <h1><?php echo $heading; ?></h1>
        </div>
    </div> <!-- Header -->

    <hr/>

        <!--
CREATE TABLE `wp_socialsurv_samples` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(16) unsigned NOT NULL DEFAULT 0,
  `title` VARCHAR(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `filepath` text NOT NULL DEFAULT '',
  `fileext` varchar(10) NOT NULL DEFAULT '.csv',
  `status` char(1) NOT NULL DEFAULT 'A',
  `date_created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
-->
    <!-- COLOCAR AQUI A LISTA DE SAMPLES ////////////////////////// -->

    <div class="form-group row">
        <div class="col-sm-12">
            <h2>Lista de Samples</h2>
            <!-- Verifique se há samples na lista antes de renderizar a tabela -->
            <?php if (!empty($lista_samples)) : ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_samples as $sample) : ?>
                            <?php
                            $sample_json = json_encode($sample, JSON_HEX_QUOT);
                            $sample_json = htmlspecialchars($sample_json, ENT_QUOTES, 'UTF-8');
                        ?>
                            <tr>
                                <td>
                                    <a href="#" class="btn btn-link" onclick="editar_sample(<?php echo $sample_json; ?>)">
                                        <?php echo $sample['title']; ?>
                                    </a>
                                </td>
                                <td><?php echo $sample['description']; ?></td>
                                <td><?php echo $sample['status']; ?></td>
                                <td>                            
									<div class="ays-survey-icons" >
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/edit-content.svg" 
                                                onclick="editar_sample(<?php echo $sample_json; ?>)"
                                                style="vertical-align: initial;line-height: 0;margin: 0px;padding: 0;width: 26px;height: 26px; cursor: pointer;">
									</div>                                    
									<div class="ays-survey-icons" >
                                        <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg" 
                                                onclick="excluir_sample(<?php echo $sample['id']; ?>)"
                                                style="vertical-align: initial;line-height: 0;margin: 0px;padding: 0;width: 26px;height: 26px; cursor: pointer;">
                                        <!-- <a href="?page=survey-maker-samples&amp;action=delete&amp;id=1">Excluir</a>	 -->
									</div>   
                                    <?php
                                    //wp_nonce_field('survey_sample_action', 'survey_sample_action');
                                    //$other_attributes = array('id' => 'ays-button-delete');
                                    //submit_button(__('Excluir', $this->plugin_name), 'primary ays-button ays-survey-loader-banner', 'ays_submit', false, $other_attributes);
                                    //echo $loader_iamge;
                                   ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>Nenhum sample disponível.</p>
            <?php endif; ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
        function editar_sample(obj) {
            var objJson = JSON.stringify(obj);
            document.getElementById("ays-title_sample").value = (obj.title !== null && obj.title !== undefined) ? obj.title : "";
            document.getElementById("ays-description_sample").value = (obj.description !== null && obj.description !== undefined) ? obj.description : "";
            document.getElementById("ays-filepath_sample").value = obj.filepath == null ? "" : obj.filepath;
            document.getElementById("ays-fileext_sample").value = obj.fileext == null ? "" : obj.fileext;
        }
        function excluir_sample(id) {          
            debugger;  
            //var pathClass = '<php echo esc_js($path); >';
            var pathClass = <?php echo $path_for_js; ?>;
            alert('path :' + pathClass);
            jQuery.noConflict();
            (function($) {                                
                jQuery.ajax({
                  type: 'POST',
                  url: pathClass, 
                  data: {
                      action: 'delete_sample',
                      id: id
                  },
                  success: function (response) {
                      alert('SUCESSO  :' + JSON.stringify(response) );
                      console.log(response);
                  },
                  error: function (error) {
                      alert('ERROR  :' + JSON.stringify(error) );
                      console.error(error);
                  }
             });
            })(jQuery);
        }
        </script>        
    </div>        
    
    <div class="form-group row">
        <div class="col-sm-2">
            <label for='ays-title'>
                <?php echo __('Título', $this->plugin_name); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Informe o título para a lista.',$this->plugin_name); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" maxlength="100" id='ays-title_sample' name='<?php echo $html_name_prefix; ?>title_sample' value="<?php echo $title_sample; ?>" />
        </div>
    </div> <!-- Title -->

    <hr/>

    <div class="form-group row">
        <label for="ays-trade_name" class="col-sm-2 col-form-label">Descrição:</label>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" maxlength="250" id="ays-description_sample" name="<?php echo $html_name_prefix; ?>description_sample"  value="<?php echo $description_sample; ?>" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="ays-filepath" class="col-sm-2 col-form-label">Path do arquivo:</label>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" maxlength="250" id="ays-filepath_sample" name="<?php echo $html_name_prefix; ?>filepath_sample"  value="<?php echo $filepath_sample; ?>" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="ays-fileext" class="col-sm-2 col-form-label">Extensão de arquivo:</label>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" maxlength="10" id="ays-fileext_sample" name="<?php echo $html_name_prefix; ?>fileext_sample"  value="<?php echo $fileext_sample; ?>" required>
        </div>
    </div>

    <div class="form-group row">
        <input type="hidden" id="<?php echo $html_name_prefix; ?>date_created_sample" name="<?php echo $html_name_prefix; ?>date_created_sample" value="<?php echo $date_created_sample; ?>">
        <input type="hidden" id="<?php echo $html_name_prefix; ?>date_modified_sample" name="<?php echo $html_name_prefix; ?>date_modified_sample" value="<?php echo $date_modified_sample; ?>">
        <hr/>
        <div class="col-sm-8">
            <?php
                wp_nonce_field('survey_sample_action', 'survey_sample_action');
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
