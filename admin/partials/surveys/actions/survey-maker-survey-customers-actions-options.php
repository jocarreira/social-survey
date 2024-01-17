<?php
    // $ays_tab = 'tab1';
    // if(isset($_GET['tab'])){
    //     $ays_tab = $_GET['tab'];
    // }

    $action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';

    $id = (isset($_GET['id'])) ? absint(intval($_GET['id'])) : null;

    $html_name_prefix = 'ays_';

    $name_prefix = 'survey_';

    $user_id = get_current_user_id();

    $options = array(

    );

/*
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `ether_address` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `trade_name` varchar(200) NOT NULL DEFAULT '',
  `business_name` varchar(100) NOT NULL DEFAULT '',
  `type_person` char(1) NOT NULL DEFAULT 'J',
  `cnpj_cpf` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(256) NOT NULL DEFAULT '',
  `zip_code` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state_acronym` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(3) NOT NULL DEFAULT 'BR',
  `status` varchar(100) NOT NULL DEFAULT 'published',
  `date_created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
*/

    $object = array(
        'user_id' => '',
        'ether_address' => '',
        'title' => '',
        'trade_name' => '',
        'business_name' => '',
        'type_person' => '',
        'cnpj_cpf' => '',
        'email' => '',
        'address' => '',
        'zip_code' => '',
        'city' => '',
        'state_acronym' => '',
        'country' => '',
        'status' => 'published',
        'date_created' => current_time( 'mysql' ),
        'date_modified' => current_time( 'mysql' ),
        'options' => json_encode( $options ),
    );

    $gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode($this->settings_obj->ays_get_setting('options'), true);

    $heading = '';
    switch ($action) {
        case 'add':
            $heading = __( 'Adicionar novo Cliente', $this->plugin_name );
            break;
        case 'edit':
            $heading = __( 'Alterar Cliente', $this->plugin_name );
            $object = $this->customers_obj->get_item_by_id( $id );
            break;
    }

    if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
        $_POST["id"] = $id;
        $this->customers_obj->add_or_edit_item_customer( $_POST );
    }

    if(isset($_POST['ays_apply']) || isset($_POST['ays_apply_top'])){
        $_POST["id"] = $id;
        $_POST['save_type'] = 'apply';
        $this->customers_obj->add_or_edit_item_customer( $_POST );
    }

    if(isset($_POST['ays_save_new']) || isset($_POST['ays_save_new_top'])){
        $_POST["id"] = $id;
        $_POST['save_type'] = 'save_new';
        $this->customers_obj->add_or_edit_item_customer( $_POST );
    }

    // Options
    $options = isset( $object['options'] ) && $object['options'] != '' ? $object['options'] : '';
    $options = json_decode( $options, true );
       
    //Jeferson Carreira
    // Title
    $title = isset( $object['title'] ) && $object['title'] != '' ? stripslashes( htmlentities( $object['title'] ) ) : '';
    // ether_address
    $ether_address = isset( $object['ether_address'] ) && $object['ether_address'] != '' ? stripslashes( htmlentities( $object['ether_address'] ) ) : '';
    // trade_name
    $trade_name = isset( $object['trade_name'] ) && $object['trade_name'] != '' ? stripslashes( htmlentities( $object['trade_name'] ) ) : '';
    // business_name
    $business_name = isset( $object['business_name'] ) && $object['business_name'] != '' ? stripslashes( htmlentities( $object['business_name'] ) ) : '';
    // type_person
    $type_person = isset( $object['type_person'] ) && $object['type_person'] != '' ? stripslashes( htmlentities( $object['type_person'] ) ) : '';
    // cnpj_cpf
    $cnpj_cpf = isset( $object['cnpj_cpf'] ) && $object['cnpj_cpf'] != '' ? stripslashes( htmlentities( $object['cnpj_cpf'] ) ) : '';
    // email
    $email = isset( $object['email'] ) && $object['email'] != '' ? stripslashes( htmlentities( $object['email'] ) ) : '';
    // address
    $address = isset( $object['address'] ) && $object['address'] != '' ? stripslashes( htmlentities( $object['address'] ) ) : '';
    // zip_code
    $zip_code = isset( $object['zip_code'] ) && $object['zip_code'] != '' ? stripslashes( htmlentities( $object['zip_code'] ) ) : '';
    // city
    $city = isset( $object['city'] ) && $object['city'] != '' ? stripslashes( htmlentities( $object['city'] ) ) : '';
    // state_acronym
    $state_acronym = isset( $object['state_acronym'] ) && $object['state_acronym'] != '' ? stripslashes( htmlentities( $object['state_acronym'] ) ) : '';
    // country
    $country = isset( $object['country'] ) && $object['country'] != '' ? stripslashes( htmlentities( $object['country'] ) ) : '';
        
    // Status
    $status = isset( $object['status'] ) && $object['status'] != '' ? stripslashes( $object['status'] ) : 'published';
    
    // Caminho completo do logotipo
    $logotype = isset( $object['logotype'] ) && $object['logotype'] != '' ? stripslashes( htmlentities( $object['logotype'] ) ) : '';

    // Date created
    $date_created = isset( $object['date_created'] ) && Survey_Maker_Admin::validateDate( $object['date_created'] ) ? $object['date_created'] : current_time( 'mysql' );
    
    // Date modified
    $date_modified = current_time( 'mysql' );

    $loader_iamge = '<span class="display_none ays_survey_loader_box"><img src="'. SURVEY_MAKER_ADMIN_URL .'/images/loaders/loading.gif"></span>';

    // WP Editor height
    $survey_wp_editor_height = (isset($gen_options[$name_prefix . 'wp_editor_height']) && $gen_options[$name_prefix . 'wp_editor_height'] != '' && $gen_options[$name_prefix . 'wp_editor_height'] != 0) ? absint( esc_attr($gen_options[$name_prefix . 'wp_editor_height']) ) : 100;