<?php
    $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';

    $id = (isset($_GET['id'])) ? absint(intval($_GET['id'])) : null;

    $html_name_prefix = 'ays_';

    $name_prefix = 'survey_';

    $user_id = get_current_user_id();

    $options = array(

    );

/*
CREATE TABLE `wp_socialsurv_samples` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(16) unsigned NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `filepath` text NOT NULL DEFAULT '',
  `fileext` varchar(10) NOT NULL DEFAULT '.csv',
  `status` char(1) NOT NULL DEFAULT 'A',
  `date_created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`)
*/

    $object = array(
        'survey_id' => '',
        'title' => '',
        'description' => '',
        'filepath' => '',
        'fileext' => '',
        'status' => '',
        'date_created' => current_time( 'mysql' ),
        'date_modified' => current_time( 'mysql' ),
    );
    
    $gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode($this->settings_obj->ays_get_setting('options'), true);

    $heading = '';
    switch ($action) {        
        case 'add':
            $heading = __( 'Adicionar nova Lista', $this->plugin_name );
            break;
        case 'edit':
            $heading = __( 'Alterar Lista', $this->plugin_name );
            //$object = $this->surveys_obj->get_sample_by_id( $id );             
            break;
    }

    //$survey_id = 1;  // TODO : COLOCAR AQUI FUNÇÃO QUE TRAZ SURVEY_ID CORRENTE
    $lista_samples = $this->surveys_obj->get_all_samples( $survey_id );

    if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
        $_POST["id"] = $id;
        $this->surveys_obj->add_or_edit_item_sample( $_POST );
    }

    if(isset($_POST['ays_apply']) || isset($_POST['ays_apply_top'])){
        $_POST["id"] = $id;
        $_POST['save_type'] = 'apply';
        $this->surveys_obj->add_or_edit_item_sample( $_POST );
    }

    if(isset($_POST['ays_save_new']) || isset($_POST['ays_save_new_top'])){
        $_POST["id"] = $id;
        $_POST['save_type'] = 'save_new';
        $this->surveys_obj->add_or_edit_item_sample( $_POST );
    }

    // Options
    $options = isset( $object['options'] ) && $object['options'] != '' ? $object['options'] : '';
    $options = json_decode( $options, true );
       
    //Jeferson Carreira
    // Title
    $title_sample = isset( $object['title'] ) && $object['title'] != '' ? stripslashes( htmlentities( $object['title'] ) ) : '';        
    // description
    $description_sample = isset( $object['description'] ) && $object['description'] != '' ? stripslashes( $object['description'] ) : '';
    // filepath
    $filepath_sample = isset( $object['filepath'] ) && $object['filepath'] != '' ? stripslashes( $object['filepath'] ) : '';
    // fileext
    $fileext_sample = isset( $object['fileext'] ) && $object['fileext'] != '' ? stripslashes( $object['fileext'] ) : '';
    // Caminho completo do logotipo
    $logotype_sample = isset( $object['logotype'] ) && $object['logotype'] != '' ? stripslashes( htmlentities( $object['logotype'] ) ) : '';

    // Date created
    $date_created_sample = isset( $object['date_created'] ) && Survey_Maker_Admin::validateDate( $object['date_created'] ) ? $object['date_created'] : current_time( 'mysql' );
    
    // Date modified
    $date_modified_sample = current_time( 'mysql' );

    // Status
    $status_sample = isset( $object['status'] ) && $object['status'] != '' ? stripslashes( $object['status'] ) : 'published';

    //$loader_iamge = '<span class="display_none ays_survey_loader_box"><img src="'. SURVEY_MAKER_ADMIN_URL .'/images/loaders/loading.gif"></span>';

    // WP Editor height
    $survey_wp_editor_height = (isset($gen_options[$name_prefix . 'wp_editor_height']) && $gen_options[$name_prefix . 'wp_editor_height'] != '' && $gen_options[$name_prefix . 'wp_editor_height'] != 0) ? absint( esc_attr($gen_options[$name_prefix . 'wp_editor_height']) ) : 100;