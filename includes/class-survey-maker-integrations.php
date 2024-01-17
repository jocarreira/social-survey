<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Survey_Maker_Integrations
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $settings_obj;

    private $capability;

    private $blockquote_content;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);

        $settings_url = sprintf(
            __( "For enabling this option, please go to %s page and fill all options.", $this->plugin_name ),
            "<a style='color:blue;text-decoration:underline;font-size:20px;' href='?page=".$this->plugin_name."-settings&ays_survey_tab=tab2' target='_blank'>". __( "this", $this->plugin_name ) ."</a>"
        );
        $blockquote_content = '<blockquote class="error_message">'. $settings_url .'</blockquote>';
        $this->blockquote_content = $blockquote_content;
    }

    // ===== INTEGRATIONS HOOKS =====

    // Integrations survey page action hook
    public function ays_survey_page_integrations_content( $args ){

        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $settings_url = __( "This functionality is disabled.", $this->plugin_name );
            $blockquote_content = '<blockquote class="error_message">'. $settings_url .'</blockquote>';
            $this->blockquote_content = $blockquote_content;
        }

        $integrations_contents = apply_filters( 'ays_sm_survey_page_integrations_contents', array(), $args );
        
        $integrations = array();

        foreach ($integrations_contents as $key => $integrations_content) {
            $content = '<fieldset>';
            if(isset($integrations_content['title'])){
                $content .= '<legend>';
                if(isset($integrations_content['icon'])){
                    $content .= '<img class="ays_integration_logo" src="'. $integrations_content['icon'] .'" alt="">';
                }
                $content .= '<h5>'. $integrations_content['title'] .'</h5></legend>';
            }
            $content .= $integrations_content['content'];

            $content .= '</fieldset>';

            $integrations[] = $content;
        }

        echo implode('<hr/>', $integrations);

    }

    // Integrations settings page action hook
    public function ays_settings_page_integrations_content( $args ){

        $integrations_contents = apply_filters( 'ays_sm_settings_page_integrations_contents', array(), $args );
        
        $integrations = array();

        foreach ($integrations_contents as $key => $integrations_content) {
            $content = '<fieldset>';
            if(isset($integrations_content['title'])){
                $content .= '<legend>';
                if(isset($integrations_content['icon'])){
                    $content .= '<img class="ays_integration_logo" src="'. $integrations_content['icon'] .'" alt="">';
                }
                $content .= '<h5>'. $integrations_content['title'] .'</h5></legend>';
            }
            if(isset($integrations_content['content'])){
                $content .= $integrations_content['content'];
            }

            $content .= '</fieldset>';

            $integrations[] = $content;
        }

        echo implode('<hr/>', $integrations);
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== MailChimp integration start =====

        // MailChimp integration / survey page

        // MailChimp integration in survey page content
        public function ays_survey_page_mailchimp_content( $integrations, $args ){
            
            $survey_settings = $this->settings_obj;
            $mailchimp_res = ($survey_settings->ays_get_setting('mailchimp') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('mailchimp');
            $mailchimp = json_decode($mailchimp_res, true);
            $mailchimp_username = isset($mailchimp['username']) ? $mailchimp['username'] : '' ;
            $mailchimp_api_key = isset($mailchimp['apiKey']) ? $mailchimp['apiKey'] : '' ;
            $mailchimp_lists = $this->ays_get_mailchimp_lists($mailchimp_username, $mailchimp_api_key);
            $enable_mailchimp = $args['enable_mailchimp'];
            $mailchimp_list = $args['mailchimp_list'];
            // $enable_mailchimp = isset($args['enable_mailchimp']) && $args['enable_mailchimp'] == "on" ? true : false;
            // $mailchimp_list = isset($args['mailchimp_list']) && $args['mailchimp_list'] != '' ? $args['mailchimp_list'] : '';


            $mailchimp_select = array();
            if(isset($mailchimp_lists['total_items']) && $mailchimp_lists['total_items'] > 0){
                foreach($mailchimp_lists['lists'] as $list){
                    $mailchimp_select[] = array(
                        'listId' => $list['id'],
                        'listName' => $list['name']
                    );
                }
            }else{
                $mailchimp_select = __( "There are no lists", $this->plugin_name );
            }

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mailchimp_logo.png';
            $title = __('MailChimp Settings',$this->plugin_name);

            $content = '';
            if(count($mailchimp) > 0){
                if($mailchimp_username == "" || $mailchimp_api_key == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = ($mailchimp_username == "" || $mailchimp_api_key == "") ? "disabled" : '';
                    $checked = ($enable_mailchimp == true) ? "checked" : '';
                
                    $content .= '<div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_enable_mailchimp">'. __('Enable MailChimp',$this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_mailchimp" name="ays_enable_mailchimp" value="on" '.$checked.' '.$disabled.'>';
                    $content .= '
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_mailchimp_list">'. __('MailChimp list',$this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-8">';
                    if(is_array($mailchimp_select)){
                        $content .= '<select name="ays_mailchimp_list" id="ays_mailchimp_list" '.$disabled.'>';
                        $content .= '<option value="" disabled selected>'. __( "Select list", $this->plugin_name ) .'</option>';
                        foreach($mailchimp_select as $mlist){
                            $mselected = ($mailchimp_list == $mlist['listId']) ? 'selected' : '';
                            $content .= '<option '. $mselected .' value="'. $mlist['listId'] .'">'. $mlist['listName'] .'</option>';
                        }
                        $content .= '</select>';
                    }else{
                        $content .= '<span>'. $mailchimp_select .'</span>';
                    }
                    $content .= '</div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['mailchimp'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;        
        }

        // MailChimp integration in survey page options
        public function ays_survey_page_mailchimp_options( $args, $options ){

            // MailChimp
            $args['enable_mailchimp'] = ( isset($options['enable_mailchimp'] ) && $options['enable_mailchimp'] == 'on') ? true : false;
            $args['mailchimp_list'] = (isset($options['mailchimp_list'])) ? $options['mailchimp_list'] : '';

            return $args;
        }

        // MailChimp integration in survey page data saver
        public function ays_survey_page_mailchimp_save( $options, $data ){

            // MailChimp
            $options['enable_mailchimp'] = ( isset( $data['ays_enable_mailchimp'] ) && $data['ays_enable_mailchimp'] == 'on' ) ? 'on' : 'off';
            $options['mailchimp_list'] = !isset( $data['ays_mailchimp_list'] ) ? "" : $data['ays_mailchimp_list'];

            return $options;
        }

        // MailChimp integration / settings page

        // MailChimp integration in General settings page content
        public function ays_settings_page_mailchimp_content( $integrations, $args ){

            $actions = $this->settings_obj;

            $mailchimp_res = ($actions->ays_get_setting('mailchimp') === false) ? json_encode(array()) : $actions->ays_get_setting('mailchimp');
            $mailchimp = json_decode($mailchimp_res, true);
            $mailchimp_username = isset($mailchimp['username']) ? $mailchimp['username'] : '' ;
            $mailchimp_api_key = isset($mailchimp['apiKey']) ? $mailchimp['apiKey'] : '' ;

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mailchimp_logo.png';
            $title = __( 'MailChimp', $this->plugin_name );

            $content = '';
            $content .= '<div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_mailchimp_username">'. __( 'MailChimp Username', $this->plugin_name ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" 
                                class="ays-text-input" 
                                id="ays_mailchimp_username" 
                                name="ays_mailchimp_username"
                                value="'. $mailchimp_username .'"
                            />
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_mailchimp_api_key">'. __( 'MailChimp API Key', $this->plugin_name ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" 
                                class="ays-text-input" 
                                id="ays_mailchimp_api_key" 
                                name="ays_mailchimp_api_key"
                                value="'. $mailchimp_api_key .'"
                            />
                        </div>
                    </div>
                    <blockquote>';
            $content .= sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://us20.admin.mailchimp.com/account/api/", __( "Account Extras menu", $this->plugin_name ) );
            $content .= '</blockquote>
                </div>
            </div>';

            $integrations['mailchimp'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;        
        }

        // MailChimp integration in General settings page data saver
        public function ays_settings_page_mailchimp_save( $fields, $data ){

            $mailchimp_username = isset($data['ays_mailchimp_username']) ? $data['ays_mailchimp_username'] : '';
            $mailchimp_api_key = isset($data['ays_mailchimp_api_key']) ? $data['ays_mailchimp_api_key'] : '';
            $mailchimp = array(
                'username' => $mailchimp_username,
                'apiKey' => $mailchimp_api_key
            );

            // MailChimp
            $fields['mailchimp'] = $mailchimp;

            return $fields;
        }

        // MailChimp integration / front-end
        
        // MailChimp integration in front-end functional
        public function ays_front_end_mailchimp_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_mailchimp'] ){
                if( !empty( $data['user_email'] ) ){

                    $mailchimp_fname = "";
                    $mailchimp_lname = "";
                    if( !empty( $data['user_name'] ) ){
                        $user_name = explode( " ", $data['user_name'] );
                        $mailchimp_fname = (isset($user_name[0]) && $user_name[0] != "") ? $user_name[0] : "";
                        $mailchimp_lname = (isset($user_name[1]) && $user_name[1] != "") ? $user_name[1] : "";
                    }
                    
                    $args = array(
                        "email" => $data['user_email'],
                        "fname" => $mailchimp_fname,
                        "lname" => $mailchimp_lname
                    );

                    $survey_settings = $this->settings_obj;
                    $mailchimp_list = $arguments['mailchimp_list'];
                    $mailchimp_res = ($survey_settings->ays_get_setting('mailchimp') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('mailchimp');
                    $mailchimp = json_decode($mailchimp_res, true);
                    $mailchimp_username = isset($mailchimp['username']) ? $mailchimp['username'] : '' ;
                    $mailchimp_api_key = isset($mailchimp['apiKey']) ? $mailchimp['apiKey'] : '' ;
                    
                    $mresult = $this->ays_add_mailchimp_transaction($mailchimp_username, $mailchimp_api_key, $mailchimp_list, $args);
                }
            }
        }

        // MailChimp integration in front-end options
        public function ays_front_end_mailchimp_options( $args, $settings ){
            $options = $settings['options'];
            // MailChimp
            $args['enable_mailchimp'] = ( isset($options['enable_mailchimp'] ) && $options['enable_mailchimp'] == 'on') ? true : false;
            $args['mailchimp_list'] = (isset($options['mailchimp_list'])) ? $options['mailchimp_list'] : '';

            return $args;
        }

    // ===== MailChimp integration end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Campaign Monitor start =====

        // Campaign Monitor integration / survey page

        // Campaign Monitor integration in survey page content
        public function ays_survey_page_camp_monitor_content($integrations, $args){

            $survey_settings = $this->settings_obj;
            $monitor_res     = ($survey_settings->ays_get_setting('monitor') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('monitor');
            $monitor         = json_decode($monitor_res, true);
            $monitor_client  = isset($monitor['client']) ? $monitor['client'] : '';
            $monitor_api_key = isset($monitor['apiKey']) ? $monitor['apiKey'] : '';
            $monitor_lists   = $this->ays_get_monitor_lists($monitor_client, $monitor_api_key);
            $monitor_select  = !isset($monitor_lists['Code']) ? $monitor_lists : __("There are no lists", $this->plugin_name);

            // $saved_options = apply_filters("ays_sm_survey_page_integrations_options", $integrations, $args);
            // $monitor_list = isset($saved_options['monitor_list']) && $saved_options['monitor_list'] != '' ? $saved_options['monitor_list'] : '';

            $enable_monitor  = isset($args['enable_monitor']) && $args['enable_monitor'] == "on" ? true : false;  
            $monitor_list    = isset($args['monitor_list']) && $args['monitor_list'] != '' ? $args['monitor_list'] : '';

            $enable_disabled = '';
            $enable_checked  = '';
            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/campaignmonitor_logo.png';
            $title = __('Campaign Monitor Settings',$this->plugin_name);
            $content = '';

            if(count($monitor) > 0){
                if($monitor_client == "" || $monitor_api_key == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $enable_disabled = ($monitor_client == "" || $monitor_api_key == "") ? "disabled" : '';
                    $checked = (isset($enable_monitor) && $enable_monitor) ? "checked" : '';
                    $enable_checked  = (isset($enable_disabled) && $enable_disabled != "disabled") ? $checked : ''; 
                    $content .= '<hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_monitor">'.__('Enable Campaign Monitor', $this->plugin_name).'</label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_enable_monitor"
                                    name="ays_enable_monitor" value="on" '.$enable_disabled.' '.$enable_checked.'/>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_monitor_list">'.__('Campaign Monitor list', $this->plugin_name).'</label>
                            </div>
                            <div class="col-sm-8">';
                        if(is_array($monitor_select)){
                            $content .= '<select name="ays_monitor_list" id="ays_monitor_list" '.$enable_disabled.'>
                            <option disabled>'.__("Select List", $this->plugin_name).'</option>';
                            $selected_list = '';
                            foreach($monitor_select as $mlist){
                                $selected_list = ($monitor_list == $mlist['ListID']) ? "seleted" : '';
                                $content .= '<option '.$selected_list.' value='.$mlist["ListID"].'>'.$mlist["Name"].'</option>';
                            }
                            $content .= '</select>';
                        }else{
                            $content .= '<span>'.$monitor_select.'</span>';
                        }
                    $content .= '</div>';    
                }
            }else{
                $content .= $this->blockquote_content;
            }
            
            $integrations['monitor'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Campaign Monitor integration in survey page data saver
        public function ays_survey_page_camp_monitor_save( $options, $data ){
            $options['enable_monitor'] = ( isset( $data['ays_enable_monitor'] ) && $data['ays_enable_monitor'] == 'on' ) ? 'on' : 'off';
            $options['monitor_list'] = !isset( $data['ays_monitor_list'] ) ? "" : $data['ays_monitor_list'];

            return $options;
        }

        // Campaign Monitor integration in survey page options
        public function ays_survey_page_camp_monitor_options($args, $options){
            $args['enable_monitor'] = (isset($options['enable_monitor']) && $options['enable_monitor'] == 'on') ? true : false;
            $args['monitor_list'] = (isset($options['monitor_list'])) ? $options['monitor_list'] : '';

            return $args;
        }
        
        // Campaign Monitor integration / settings page

        // Campaign Monitor integration in General settings page
        public function ays_settings_page_campaign_monitor_content( $integrations, $args ){
            $actions = $this->settings_obj;
            
            $monitor_res     = ($actions->ays_get_setting('monitor') === false) ? json_encode(array()) : $actions->ays_get_setting('monitor');
            $monitor         = json_decode($monitor_res, true);
            $monitor_client  = isset($monitor['client']) ? $monitor['client'] : '';
            $monitor_api_key = isset($monitor['apiKey']) ? $monitor['apiKey'] : '';
            
            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/campaignmonitor_logo.png';
            $title = __( 'Campaign Monitor', $this->plugin_name );

            $content = '';
            $content .= '<div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_monitor_client">'. __( 'Campaign Monitor Client ID', $this->plugin_name ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" 
                                class="ays-text-input" 
                                id="ays_monitor_client" 
                                name="ays_monitor_client"
                                value="'. $monitor_client .'"
                            />
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_monitor_api_key">'. __( 'Campaign Monitor API Key', $this->plugin_name ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" 
                                class="ays-text-input" 
                                id="ays_monitor_api_key" 
                                name="ays_monitor_api_key"
                                value="'. $monitor_api_key .'"
                            />
                        </div>
                    </div>
                    <blockquote>';
            $content .= __( "You can get your API key and Client ID from your Account Settings page.", $this->plugin_name);
            $content .= '</blockquote>
                </div>
            </div>';

            $integrations['monitor'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // Campaign Monitor integration in General settings data saver
        public function ays_settings_page_campaign_monitor_save( $fields, $data ){

            $monitor_client  = isset($data['ays_monitor_client']) ? $data['ays_monitor_client'] : '';
            $monitor_api_key = isset($data['ays_monitor_api_key']) ? $data['ays_monitor_api_key'] : '';
            $monitor         = array(
                'client' => $monitor_client,
                'apiKey' => $monitor_api_key
            );
                
            $fields['monitor'] = $monitor;

            return $fields;
        }

        // Campaign Monitor integration / front-end
        
        // Campaign Monitor integration in front-end functional
        public function ays_front_end_campaign_monitor_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_monitor'] ){
                if( !empty( $data['user_email'] ) ){

                    $mailchimp_fname = "";
                    $mailchimp_lname = "";
                    
                    $args = array(
                        "EmailAddress" => $data['user_email'],
                        "Name" => $data['user_name']
                    );

                    $monitor_list  = $arguments['monitor_list'];
                    $survey_settings = $this->settings_obj;
                    $monitor_res     = ($survey_settings->ays_get_setting('monitor') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('monitor');
                    $monitor         = json_decode($monitor_res, true);
                    $monitor_client  = isset($monitor['client']) ? $monitor['client'] : '';
                    $monitor_api_key = isset($monitor['apiKey']) ? $monitor['apiKey'] : '';

                    $mresult = $this->ays_add_monitor_transaction($monitor_client, $monitor_api_key, $monitor_list, $args);
                }
            }
        }

        // Campaign Monitor integration in front-end options
        public function ays_front_end_campaign_monitor_options( $args, $settings ){
            $options = $settings['options'];
            // Campaign Monitor
            $args['enable_monitor'] = ( isset($options['enable_monitor'] ) && $options['enable_monitor'] == 'on') ? true : false;
            $args['monitor_list'] = (isset($options['monitor_list'])) ? $options['monitor_list'] : '';

            return $args;
        }

    // ===== Campaign Monitor end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Zapier start =====

        // Zapier integration / survey page

        // Zapier integration in survey page content
        public function ays_survey_page_zapier_content($integrations, $args){
            $survey_settings = $this->settings_obj;
            $zapier_res  = ($survey_settings->ays_get_setting('zapier') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('zapier');
            $zapier      = json_decode($zapier_res, true);
            $zapier_hook = isset($zapier['hook']) ? $zapier['hook'] : '';
            
            $enable_zapier = (isset($args['enable_zapier']) && $args['enable_zapier'] == 'on') ? true : false;
            $enable_disabled = '';
            $enable_checked  = '';
            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/zapier_logo.png';
            $title = __('Zapier Settings',$this->plugin_name);
            $content = '';

            if (count($zapier) > 0){           
                if ($zapier_hook == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $enable_disabled = (isset($zapier_hook) && $zapier_hook == "") ? "disabled" : '';
                    $checked = isset($enable_zapier) && $enable_zapier == "on" ? "checked" : '';
                    $enable_checked  = isset($enable_disabled) && $enable_disabled != "disabled" ? $checked : ''; 
                    $content .= '<hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_enable_zapier">'.__('Enable Zapier Integration', $this->plugin_name).'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_zapier" name="ays_enable_zapier" value="on" '.$checked.' >   
                        </div>
                        <div class="col-sm-3">
                            <button type="button" data-url='.$zapier_hook.' '.$enable_disabled.' id="testZapier" class="btn btn-outline-secondary">'.__("Send test data", $this->plugin_name).'</button>
                            <a class="ays_help" data-toggle="tooltip" style="font-size: 16px;"
                            title="'.__("We will send you a test data, and you can catch it in your ZAP for configure it.", $this->plugin_name).'">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['zapier'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );
            return $integrations;
        }

        // Zapier integration in survey page data saver
        public function ays_survey_page_zapier_save( $options, $data ){

            $options['enable_zapier'] = ( isset( $data['ays_enable_zapier'] ) && $data['ays_enable_zapier'] == 'on' ) ? 'on' : 'off';
            return $options;
        }

        // Zapier integration in survey page options
        public function ays_survey_page_zapier_options($args, $options){

            // $args['hook'] = (isset($options['hook']) && $options['hook'] != '') ? $options['hook'] : '';
            $args['enable_zapier'] = (isset($options['enable_zapier']) && $options['enable_zapier'] != '') ? $options['enable_zapier'] : '';

            return $args;
        }

        // Zapier integration / settings page

        // Zapier integration in General settings page content
        public function ays_settings_page_zapier_content( $integrations, $args ){
            $actions = $this->settings_obj;
            
            $zapier_res  = ($actions->ays_get_setting('zapier') === false) ? json_encode(array()) : $actions->ays_get_setting('zapier');
            $zapier      = json_decode($zapier_res, true);
            $zapier_hook = isset($zapier['hook']) ? $zapier['hook'] : '';
            
            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/zapier_logo.png';
            $title = __( 'Zapier', $this->plugin_name );

            $content = '';
            $content .= '<div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_zapier_hook">'. __( 'Zapier Webhook URL', $this->plugin_name ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" 
                                class="ays-text-input"
                                id="ays_zapier_hook" 
                                name="ays_zapier_hook"
                                value="'. $zapier_hook .'"
                            />
                        </div>
                    </div>
                    <blockquote>';
            $content .= sprintf( __( "If you don't have any ZAP created, go", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://zapier.com/app/editor/", __( "here...", $this->plugin_name ) );
            $content .= '</blockquote>
                        <blockquote>
                        '.__("We will send you all data from survey information form with the “AysSurvey” key by POST method.", $this->plugin_name).'
                        </blockquote>
                </div>
            </div>';

            $integrations['zapier'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // Zapier integration in General settings page data saver
        public function ays_settings_page_zapier_save( $fields, $data ){

            $zapier_hook = isset($data['ays_zapier_hook']) ? sanitize_text_field( $data['ays_zapier_hook'] ) : '';
            $zapier      = array(
                'hook' => $zapier_hook
            );
                
            $fields['zapier'] = $zapier;

            return $fields;
        }

        // Zapier integration / front-end
        
        // Zapier integration in front-end functional
        public function ays_front_end_zapier_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_zapier'] ){
                if( !empty( $data['user_email'] ) ){
                    
                    // Zapier | Hook
                    $zapier_hook = $arguments[ 'zapier_hook' ];

                    // Zapier | Question Data 
                    $questions_all_data = isset($arguments['questions_all_data']) ? $arguments['questions_all_data'] : array();

                    if( ! empty($questions_all_data) ){

                        $answered_questions = ( isset( $questions_all_data['answered_questions'] ) &&  ! empty( $questions_all_data['answered_questions'] ) ) ? $questions_all_data['answered_questions'] : array();
                        $questions_data     = ( isset( $questions_all_data['questions_data'] ) &&  ! empty( $questions_all_data['questions_data'] ) ) ? $questions_all_data['questions_data'] : array();
                        $questions_all_data = (isset($data['questions_all_data']) && !empty($data['questions_all_data'])) ? $data['questions_all_data'] : array();
                        $survey_title = (isset($data['survey']->title) && !empty($data['survey']->title)) ? $data['survey']->title : '';
                        $end_date = (isset($data['end_date']) && !empty($data['end_date'])) ? $data['end_date'] : '';
                        $all_questions      = isset($arguments['all_questions']) ? $arguments['all_questions'] : array();
                        $all_questions_data = array_keys($all_questions);

                        $zapier_data_obj = array(
                            "all_questions"      => $all_questions_data,
                            "questions_data"     => $questions_data,
                            "answered_questions" => $answered_questions,
                        );

                        $get_zapier_ready_data = $this->ays_survey_get_answer_data( $zapier_data_obj, 'zapier' );

                        if ( ! empty( $get_zapier_ready_data ) ) {
                            $zapier_data = array();
                            $zapier_data['survey_title'] = $survey_title;
                            $zapier_data['date'] = $end_date;
                            // Zapier | Answerd Data
                            foreach ($get_zapier_ready_data as $question_id => $value) {
                                $zapier_data[ 'question_id_' . $question_id ] = $value;
                            }


                            if ( ! empty( $zapier_hook ) && ! empty( $zapier_data ) ) {
                                $zresult = $this->ays_add_zapier_transaction( $zapier_hook, $zapier_data );
                            }
                        }
                    }
                }
            }
        }

        // Zapier integration in front-end options
        public function ays_front_end_zapier_options( $args, $settings ){
            $options = $settings['options'];
            // Zapier
            
            // Zapier | Enabled
            $args['enable_zapier'] = ( isset($options['enable_zapier'] ) && $options['enable_zapier'] == 'on') ? true : false;

            // Zapier | General settings
            $setting_zapier = Survey_Maker_Data::get_setting_data( 'zapier' );

            // Zapier | hook
            $args[ 'zapier_hook' ] = ( isset($setting_zapier['hook']) && $setting_zapier['hook'] != '' ) ? sanitize_text_field( $setting_zapier['hook'] ) : '';

            $args['all_questions'] = ( isset($options['all_questions'] ) ) ? $options['all_questions'] : array();

            $args['questions_all_data'] = ( isset($settings['questions_all_data'] ) && ! empty($settings['questions_all_data'] ) ) ? $settings['questions_all_data'] : array();

            return $args;
        }

    // ===== Zapier end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Active Campaign start =====

        // Active Campaign integration / survey page
        
        // Active Campaign integration in survey page content
        public function ays_survey_page_active_camp_content($integrations, $args){

            $survey_settings = $this->settings_obj;
            $active_camp_res               = ($survey_settings->ays_get_setting('active_camp') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('active_camp');
            $active_camp                   = json_decode($active_camp_res, true);
            $active_camp_url               = isset($active_camp['url']) ? $active_camp['url'] : '';
            $active_camp_api_key           = isset($active_camp['apiKey']) ? $active_camp['apiKey'] : '';
            $active_camp_lists             = $this->ays_get_active_camp_data('lists', $active_camp_url, $active_camp_api_key);
            $active_camp_automations       = $this->ays_get_active_camp_data('automations', $active_camp_url, $active_camp_api_key);
            
            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/activecampaign_logo.png';
            $title = __('ActiveCampaign Settings', $this->plugin_name);

            if ( isset($active_camp_lists['errors']) && ! empty($active_camp_lists['errors']) ) {
                // Your account has been expired for over a month.
                $integrations['active_camp'] = array(
                    'content' => $active_camp_lists['errors'][0]['title'], // Account expired
                    'icon'    => $icon,
                    'title'   => $title,
                );

                return $integrations;
            }

            $active_camp_list_select       = !isset($active_camp_lists['Code']) ? $active_camp_lists['lists'] : __("There are no lists", $this->plugin_name);
            $active_camp_automation_select = !isset($active_camp_automations['Code']) ? $active_camp_automations['automations'] : __("There are no automations", $this->plugin_name);
            $enable_disabled = '';
            $enable_checked  = '';
            $content = '';
            
            // $saved_options = apply_filters("ays_sm_survey_page_integrations_options",$integrations,$args);
            $enable_active_camp = (isset($args['enable_active_camp']) && $args['enable_active_camp'] == 'on') ? true : false;
            $active_camp_list = isset($args['active_camp_list']) && $args['active_camp_list'] != '' ? $args['active_camp_list'] : '';
            $active_camp_automation = isset($args['active_camp_automation']) && $args['active_camp_automation'] != '' ? $args['active_camp_automation'] : '';

            $content = '';
            if (count($active_camp) > 0){
                if ($active_camp_url == "" || $active_camp_api_key == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $enable_disabled = ($active_camp_api_key != '' && $active_camp_url == "") ? "disabled" : '';
                    $checked = ($enable_active_camp) ? 'checked' : '';
                    $enable_checked  = isset($enable_disabled) && $enable_disabled != "disabled" ? $checked : ''; 
                    $content .= '<hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_enable_active_camp">'. __('Enable ActiveCampaign', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_active_camp" name="ays_enable_active_camp" value="on" '.$enable_disabled.' '.$enable_checked.'>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_active_camp_list">'.__('ActiveCampaign list', $this->plugin_name).'</label>
                        </div>
                        <div class="col-sm-8">';
                        if(is_array($active_camp_list_select)){
                            $content .= '<select name="ays_active_camp_list" id="ays_active_camp_list" '.$enable_disabled.'>
                                            <option value="" disabled selected>'. __("Select List", $this->plugin_name) .'</option>
                                            <option value="">'.__("Just create contact", $this->plugin_name).'</option>';
                                            $selected = '';
                                            foreach( $active_camp_list_select as $list ){
                                                $selected = ($active_camp_list == $list["id"]) ? "selected" : '';
                                                $content .= '<option  '.$selected.' value='.$list["id"].'>'.$list["name"].'</option>';
                                            }
                                            $content .= '</select></div>';
                        }else{
                            $content .= '<span>'.$active_camp_list_select.'</span></div>';
                        }
                        $content .= '</div><hr>';
                    $content .= '
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_active_camp_automation">'.__("ActiveCampaign automation", $this->plugin_name).'</label>
                        </div>
                        <div class="col-sm-8">';
                            
                    if(is_array($active_camp_automation_select)){
                        $content .= '<select name="ays_active_camp_automation" id="ays_active_camp_automation" '.$enable_disabled.'>
                            <option value="" disabled selected>'.__("Select List", $this->plugin_name).'</option>
                            <option value="">'.__("Just create contact", $this->plugin_name).'</option>';
                            $selected_auto = '';
                                foreach ( $active_camp_automation_select as $automation ){
                                    $selected_auto = ($active_camp_automation == $automation['id']) ? 'selected' : '';
                                    $content .= '<option '.$selected_auto.' value='.$automation["id"].'>'.$automation["name"].'</option>';
                                }
                                $content .= '</select></div>';
                    }else{
                        $content .= '<span>'.$active_camp_automation_select.'</span></div>';
                    }
                    $content .= '</div>';
                }
            
            }else{
                $content .= $this->blockquote_content;
            }
            $integrations['active_camp'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Active Campaign integration in survey page options
        public function ays_survey_page_active_camp_options($args, $options){

            $args['enable_active_camp']     = (isset($options['enable_active_camp']) && $options['enable_active_camp'] == 'on') ? true : false;
            $args['active_camp_list']       = (isset($options['active_camp_list'])) ? $options['active_camp_list'] : '';
            $args['active_camp_automation'] = (isset($options['active_camp_automation'])) ? $options['active_camp_automation'] : '';
            return $args;
        }

        // Active Campaign integration in survey page data saver
        public function ays_survey_page_active_camp_save( $options, $data){

            $options['enable_active_camp']     = ( isset( $data['ays_enable_active_camp'] ) && $data['ays_enable_active_camp'] == 'on' ) ? 'on' : 'off';
            $options['active_camp_list']       = !isset( $data['ays_active_camp_list'] ) ? "" : $data['ays_active_camp_list'];
            $options['active_camp_automation'] = !isset( $data['ays_active_camp_automation'] ) ? "" : $data['ays_active_camp_automation'];

            return $options;
        }

        
        // Active Campaign integration / settings page

        // Active Campaign integration in Gengeral settings page content
        public function ays_settings_page_active_camp_content( $integrations, $args ){
            $actions = $this->settings_obj;
            
            $active_camp_res     = ($actions->ays_get_setting('active_camp') === false) ? json_encode(array()) : $actions->ays_get_setting('active_camp');
            $active_camp         = json_decode($active_camp_res, true);
            $active_camp_url     = isset($active_camp['url']) ? $active_camp['url'] : '';
            $active_camp_api_key = isset($active_camp['apiKey']) ? $active_camp['apiKey'] : '';
            
            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/activecampaign_logo.png';
            $title = __( 'ActiveCampaign', $this->plugin_name );

            $content = '';
            $content .= '<div class="form-group row">
                            <div class="col-sm-12">
                            <div class="form-group row" aria-describedby="aaa">
                                <div class="col-sm-3">
                                    <label for="ays_active_camp_url">'. __( 'API Access URL', $this->plugin_name ) .'</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" 
                                        class="ays-text-input" 
                                        id="ays_active_camp_url" 
                                        name="ays_active_camp_url"
                                        value="'. $active_camp_url .'"
                                    />
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" aria-describedby="aaa">
                                <div class="col-sm-3">
                                    <label for="ays_active_camp_api_key">'. __( 'API Access Key', $this->plugin_name ) .'</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" 
                                        class="ays-text-input" 
                                        id="ays_active_camp_api_key" 
                                        name="ays_active_camp_api_key"
                                        value="'. $active_camp_api_key .'"
                                    />
                                </div>
                            </div>
                    <blockquote>';
            $content .= __( "Your API URL and Key can be found in your account on the My Settings page under the “Developer” tab.", $this->plugin_name);
            $content .= '</blockquote>
                </div>
            </div>';

            $integrations['active_camp'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // Active Campaign integration in Gengeral settings page data saver
        public function ays_settings_page_active_camp_save( $fields, $data ){
            $active_camp_url     = isset($data['ays_active_camp_url']) ? $data['ays_active_camp_url'] : '';
            $active_camp_api_key = isset($data['ays_active_camp_api_key']) ? $data['ays_active_camp_api_key'] : '';
            $active_camp         = array(
                'url'    => $active_camp_url,
                'apiKey' => $active_camp_api_key
            );
                
            $fields['active_camp'] = $active_camp;

            return $fields;
        }

        // Active Campaign integration / front-end
        
        // Active Campaign integration in front-end functional
        public function ays_front_end_active_camp_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_active_camp'] ){
                if( !empty( $data['user_email'] ) ){

                    $active_camp_fname = "";
                    $active_camp_lname = "";
                    if( !empty( $data['user_name'] ) ){
                        $user_name = explode( " ", $data['user_name'] );
                        $active_camp_fname = (isset($user_name[0]) && $user_name[0] != "") ? $user_name[0] : "";
                        $active_camp_lname = (isset($user_name[1]) && $user_name[1] != "") ? $user_name[1] : "";
                    }

                    $active_camp_list  = $arguments['active_camp_list'];
                    $active_camp_automation  = $arguments['active_camp_automation'];
                    $survey_settings = $this->settings_obj;

                    $active_camp_res     = ($survey_settings->ays_get_setting('active_camp') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('active_camp');
                    $active_camp         = json_decode($active_camp_res, true);
                    $active_camp_url     = isset($active_camp['url']) ? $active_camp['url'] : '';
                    $active_camp_api_key = isset($active_camp['apiKey']) ? $active_camp['apiKey'] : '';
                    
                    $args = array(
                        "email"     => $data['user_email'],
                        "firstName" => $active_camp_fname,
                        "lastName"  => $active_camp_lname
                    );
                    $mresult = $this->ays_add_active_camp_transaction($active_camp_url, $active_camp_api_key, $args, $active_camp_list, $active_camp_automation);
                }
            }
        }

        // Active Campaign integration in front-end options
        public function ays_front_end_active_camp_options( $args, $settings ){
            $options = $settings['options'];
            // Active Campaign
            
            $args['enable_active_camp'] = ( isset($options['enable_active_camp'] ) && $options['enable_active_camp'] == 'on') ? true : false;
            $args['active_camp_list'] = (isset($options['active_camp_list'])) ? $options['active_camp_list'] : '';
            $args['active_camp_automation'] = (isset($options['active_camp_automation'])) ? $options['active_camp_automation'] : '';

            return $args;
        }

    // ===== Active Campaign end =====
    
    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Slack start =====
    
        // Slack integration / survey page

        // Slack integration in survey page content    
        public function ays_survey_page_slack_content($integrations, $args){
            $survey_settings = $this->settings_obj;

            $slack_res           = ($survey_settings->ays_get_setting('slack') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('slack');
            $slack               = json_decode($slack_res, true);
            $slack_client        = isset($slack['client']) ? $slack['client'] : '';
            $slack_secret        = isset($slack['secret']) ? $slack['secret'] : '';
            $slack_token         = isset($slack['token']) ? $slack['token'] : '';
            $slack_conversations = $this->ays_get_slack_conversations($slack_token);
            $slack_select        = !isset($slack_conversations['Code']) ? $slack_conversations : __("There are no conversations", $this->plugin_name);
            // if( isset( $slack_select['channels'] ) && !empty( $slack_select['channels'] ) ){
            //     $slack_select = $slack_select['channels'];
            // }else{
            //     $slack_select = array();
            // }

            $enable_disabled = '';
            $enable_checked  = '';
            $content = '';
            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/slack_logo.png';
            $title = __('Slack Settings',$this->plugin_name);
            $content = '';

            $enable_slack       = (isset($args['enable_slack']) && $args['enable_slack'] == 'on') ? true : false;
            $slack_conversation = isset($args['slack_conversation']) && $args['slack_conversation'] != '' ? $args['slack_conversation'] : '';
            
            if (count($slack) > 0){
                if ($slack_token == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $enable_disabled = (isset($slack_token) && $slack_token) == '' ? "disabled" : ''; 
                    $enable_checked  = $enable_slack ? "checked" : ''; 
                    $content .= '
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_enable_slack">'.__("Enable Slack integration", $this->plugin_name).'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_slack" name="ays_enable_slack" value="on" '.$enable_disabled.' '.$enable_checked.'>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_slack_conversation">'.__("Slack conversation", $this->plugin_name).'</label>
                        </div>
                        <div class="col-sm-8">';
                        if (is_array($slack_select)){
                            $content .= '
                                <select name="ays_slack_conversation" id="ays_slack_conversation" '.$enable_disabled.'>
                                    <option value="" disabled selected>'.__("Select Channel", $this->plugin_name).'</option>';
                                    $selected_slack = '';
                                    foreach( $slack_select as $conversation ){
                                        $selected_slack = $slack_conversation == $conversation['id'] ? 'selected' : '';
                                        $content .= '<option value="'.$conversation["id"].'" '.$selected_slack.'>'.$conversation["name"].'</option>';
                                    }
                            $content .= '</select>';                            
                        }else{
                            $content .= '<span>'.$slack_select.'</span>';
                        }
                    $content .= '</div></div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['slack'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Slack integration in survey page options    
        public function ays_survey_page_slack_options($args, $options){
            $args['enable_slack']       = (isset($options['enable_slack']) && $options['enable_slack'] == 'on') ? true : false;
            $args['slack_conversation'] = (isset($options['slack_conversation'])) ? $options['slack_conversation'] : '';

            return $args;
        }

        // Slack integration in survey page data saver
        public function ays_survey_page_slack_save( $options, $data ){
            $options['enable_slack']       = ( isset( $data['ays_enable_slack'] ) && $data['ays_enable_slack'] == 'on' ) ? 'on' : 'off';
            $options['slack_conversation'] = !isset( $data['ays_slack_conversation'] ) ? "" : $data['ays_slack_conversation'];

            return $options;
        }
        
        // Slack integration / settings page

        // Slack integration in General settings page content
        public function ays_settings_page_slack_content( $integrations, $args ){
            $actions = $this->settings_obj;
            
            $slack_res    = ($actions->ays_get_setting('slack') === false) ? json_encode(array()) : $actions->ays_get_setting('slack');
            $slack        = json_decode($slack_res, true);
            $slack_client = isset($slack['client']) ? $slack['client'] : '';
            $slack_secret = isset($slack['secret']) ? $slack['secret'] : '';
            $slack_token  = isset($slack['token']) ? $slack['token'] : '';
            $slack_oauth  = !empty($_GET['oauth']) && $_GET['oauth'] == 'slack';
            
            $data_code = '';
            $code_content = sprintf(__("1. You will need to " . "<a href='%s' target='_blank'>%s</a>" . " new Slack App.", $this->plugin_name), "https://api.slack.com/apps?new_app=1", "create");
            $server_http = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://")) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "&oauth=slack";
            $slack_readonly = $slack_oauth ? '' : 'readonly';
            if ($slack_oauth) {
                $slack_temp_code = !empty($_GET['code']) ? $_GET['code'] : "";
                $slack_client    = !empty($_GET['state']) ? $_GET['state'] : "";
                $data_code       = !empty($slack_temp_code) ? $slack_temp_code : "";
                $ays_survey_tab  = 'tab2';
            }
            
            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/slack_logo.png';
            $title = __( 'Slack', $this->plugin_name );

            $content = '';
            $content .= '<div class="form-group row">
                            <div class="col-sm-12">';
            if(!$slack_oauth){
                $content .= '<div class="form-group row" aria-describedby="aaa">
                                <div class="col-sm-3">
                                    <button id="slackInstructionsPopOver" type="button" class="btn btn-info" title="'.__("Slack Integration Setup Instructions", $this->plugin_name).'">'.__("Instructions", $this->plugin_name).'</button>
                                    <div class="d-none" id="slackInstructions">
                                        <p>'.$code_content.'</p>
                                        <p>'.__("2. Complete Project creation for get App credentials.", $this->plugin_name).'</p>
                                        <p>'.__("3. Next, go to the Features > OAuth & Permissions > Redirect URLs section.", $this->plugin_name).'</p>
                                        <p>'.__("4. Click Add a new Redirect URL.", $this->plugin_name).'</p>
                                        <p>'.__("5. In the shown input field, put this value below", $this->plugin_name).'</p>
                                        <p>
                                            <code>'.$server_http.'</code>
                                        </p>
                                        <p>'.__("6. Then click the Add button.", $this->plugin_name).'</p>
                                        <p>'.__("7. Then click the Save URLs button.", $this->plugin_name).'</p>
                                    </div>
                                </div>
                            </div>';
            }
            $content .= '<div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_slack_client">
                                    '.__("App Client ID", $this->plugin_name).'
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_slack_client" name="ays_slack_client" value='.$slack_client.'>
                            </div>
                        </div>
                        <hr/>';
            $content .= '<div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_slack_oauth">'.__("Slack Authorization", $this->plugin_name).'</label>
                            </div>
                            <div class="col-sm-9">';
                            if($slack_oauth){
                                $content .= '<span class="btn btn-success pointer-events-none">'.__("Authorized", $this->plugin_name).'</span>';
                            }
                            else{
                                $content .= '<button type="button" id="slackOAuth2" class="btn btn-outline-secondary disabled">'.__("Authorize", $this->plugin_name).'</button>';
                            }

            $content .= '</div>
                        </div>
                        <hr/>';
            $content .= '<div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_slack_secret">'.__('App Client Secret', $this->plugin_name).'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_slack_secret" name="ays_slack_secret" value="'.$slack_secret.'" '.$slack_readonly.'>
                            </div>
                        </div>
                        <hr/>';                    
            $content .= '<div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_slack_oauth">'.__('App Access Token', $this->plugin_name).'</label>
                            </div>
                            <div class="col-sm-9">';
            if($slack_oauth){
                $content .= '<button type="button" data-code='.$data_code.' id="slackOAuthGetToken" data-success='.__("Access granted", $this->plugin_name).' class="btn btn-outline-secondary disabled">'.__("Get it", $this->plugin_name).'</button>';
                $content .= '<input type="hidden" id="ays_slack_token" name="ays_slack_token" value="">';
            }else{
                $content .= '<button type="button" class="btn btn-outline-secondary disabled">'.__("Need Authorization", $this->plugin_name).'</button>';
                $content .= '<input type="hidden" id="ays_slack_token" name="ays_slack_token" value="'.$slack_token.'">';
            }
            $content .= '</div></div>';

            $content .= '<blockquote>
                            '.__( "You can get your App Client ID and Client Secret from your App's Basic Information page.", $this->plugin_name).'
                        </blockquote>
                </div>
            </div>';

            $integrations['slack'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // Slack integration in General settings page data saver
        public function ays_settings_page_slack_save( $fields, $data ){
            $slack_client = isset($data['ays_slack_client']) ? $data['ays_slack_client'] : '';
            $slack_secret = isset($data['ays_slack_secret']) ? $data['ays_slack_secret'] : '';
            $slack_token  = !empty($data['ays_slack_token']) ? $data['ays_slack_token'] : '';
            $slack        = array(
                'client' => $slack_client,
                'secret' => $slack_secret,
                'token'  => $slack_token,
            );
                
            $fields['slack'] = $slack;

            return $fields;
        }

        // Slack integration / front-end
        
        // Slack integration in front-end functional
        public function ays_front_end_slack_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_slack'] ){
                if( !empty( $data['user_email'] ) || !empty( $data['user_name'] ) ){

                    $survey_settings = $this->settings_obj;
                    
                    // Slack
                    $slack_res          = ($survey_settings->ays_get_setting('slack') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('slack');
                    $slack              = json_decode($slack_res, true);
                    $slack_token        = isset($slack['token']) ? $slack['token'] : '';
                    $slack_conversation = $arguments['slack_conversation'];
                    $slack_data         = array();

                    $slack_data['Email'] = ( isset( $data['user_email'] ) && !empty( $data['user_email'] ) ) ? $data['user_email'] : "";
                    $slack_data['Name']  = ( isset( $data['user_name'] ) && !empty( $data['user_name'] ) ) ? $data['user_name']  : "";

                    $sresult = $this->ays_add_slack_transaction($slack_token, $slack_conversation, $slack_data, $options['title']);
                }
            }
        }

        // Slack integration in front-end options
        public function ays_front_end_slack_options( $args, $settings ){
            $options = $settings['options'];
            
            // Slack
            $args['enable_slack'] = ( isset($options['enable_slack'] ) && $options['enable_slack'] == 'on') ? true : false;
            $args['slack_conversation'] = ( isset($options['slack_conversation'] ) && $options['slack_conversation'] != '') ? $options['slack_conversation'] : '';

            return $args;
        }

    // ===== Slack end =====
    
    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Google sheet starts =====

        // Google sheet integration / survey page
        // Google sheet integration / survey page content
        public function ays_survey_page_google_sheet_content($integrations, $args){
            $actions = $this->settings_obj;
            $google_res           = ($actions->ays_get_setting('google') === false) ? json_encode(array()) : $actions->ays_get_setting('google');
            $google               = json_decode($google_res, true);
            $google_client        = isset($google['client']) ? $google['client'] : '';
            $google_secret        = isset($google['secret']) ? $google['secret'] : '';
            $google_token         = isset($google['token']) ? $google['token'] : '';
            $google_refresh_token = isset($google['refresh_token']) ? $google['refresh_token'] : '';
            $enable_google_sheets = (isset($args['enable_google_sheets']) && $args['enable_google_sheets'] == 'on') ? true : false;
            $content = "";        
            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/sheets_logo.png';
            $title = __('Google Sheet Settings',$this->plugin_name);
            $check_checked  = ($enable_google_sheets) ? 'checked' : '';
            $check_desabled = ($google_token == "") ? "disabled" : $check_checked;
            if (count($google) > 0){
                if ($google_token == ""){
                    $content .= $this->blockquote_content;
                }
                else{
                    $content .= '<hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_google">
                                            '. __('Enable Google Sheet integration', $this->plugin_name) .' 
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_google"
                                            name="ays_enable_google"
                                            value="on" '.$check_desabled.'/>
                                    </div>
                                </div>
                                <hr>';
                }
            }
            else{
                $content .= $this->blockquote_content;
            }

            $integrations['google'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        public function ays_survey_page_google_sheet_options($args, $options){
            $args['enable_google_sheets'] = (isset($options['enable_google_sheets']) && $options['enable_google_sheets'] == 'on') ? true : false;

            return $args;
        }

        // Google sheet integration / survey page save
        public function ays_survey_page_google_sheet_save($options, $data){
            $actions = $this->settings_obj;
            $this_id = isset( $data['id'] ) ? absint( sanitize_text_field( $data['id'] ) ) : 0;
            $title = isset( $data['ays_title'] ) && $data['ays_title'] != '' ? stripslashes( sanitize_text_field( $data['ays_title'] ) ) : '';
            $all_questions = isset($data['all_questions']) ? $data['all_questions'] : array();
            if (!empty($all_questions)) {
                foreach ($all_questions as $q_key => $q_val) {
                    $all_questions[$q_key] = strip_tags($q_val);
                }
            }
            $enable_google_sheets = "off";
            if(isset($data['ays_enable_google']) && $data['ays_enable_google'] == "on"){
                $enable_google_sheets = "on";
                $sheet_id = $this->ays_survey_get_sheet_id($this_id);
                $current_curvey = Survey_Maker_Data::get_survey_by_id($this_id);
                $old_sheet_id         = $sheet_id;
                $check_sheet_id       = $sheet_id !== null ? true : false;
                $google_res           = ($this->settings_obj->ays_get_setting('google') === false) ? json_encode(array()) : $this->settings_obj->ays_get_setting('google');
                $google               = json_decode($google_res, true);
                $google_client        = isset($google['client']) ? $google['client'] : '';
                $google_secret        = isset($google['secret']) ? $google['secret'] : '';
                $google_token         = isset($google['token']) ? $google['token'] : '';
                $google_refresh_token = isset($google['refresh_token']) ? $google['refresh_token'] : '';
                $this_survey_title    = isset($current_curvey->title) && $current_curvey->title != '' ? sanitize_text_field($current_curvey->title) : $title;

                $spreadsheet_id = '';

                $google_sheet_data = array(
                    "refresh_token" => $google_refresh_token,
                    "client_id"     => $google_client,
                    "client_secret" => $google_secret,
                    "survey_title"  => $this_survey_title,
                    "all_questions" => $all_questions,
                    "sheet_id"      => $old_sheet_id,
                    'id'            => $this_id
                );
                if(!$check_sheet_id){
                    $spreadsheet_id = $this->ays_survey_get_google_sheet_id($google_sheet_data);                    
                }
                else{
                    if($old_sheet_id != ''){
                        $spreadsheet_id = $old_sheet_id;
                        $this->ays_survey_update_google_spreadsheet( $google_sheet_data );
                    }
                }
                $options['spreadsheet_id'] = $spreadsheet_id;
                
            }
            $options['enable_google_sheets'] = $enable_google_sheets;
            $options['all_questions'] = $all_questions;

            return $options;
        }
        // Google sheet integration / settings page
        // Google sheet integration / settings page content    
        public function ays_settings_page_google_sheet_content($integrations, $args){
            $actions = $this->settings_obj;

            // Google sheets Xcho
            $google_res          = ($actions->ays_get_setting('google') === false) ? json_encode(array()) : $actions->ays_get_setting('google');
            $google_sheets       = json_decode($google_res, true);
            $google_client       = isset($google_sheets['client']) ? $google_sheets['client'] : '';
            $google_secret       = isset($google_sheets['secret']) ? $google_sheets['secret'] : '';
            $google_redirect_uri = isset($google_sheets['redirect_uri']) ? $google_sheets['redirect_uri'] : '';
            $google_token        = isset($google_sheets['token']) ? $google_sheets['token'] : '';
            $google_redirect_url = menu_page_url("survey-maker-settings", false);

            $google_code         = !empty($_GET['code']) ? $_GET['code'] : "";
            $google_scope        = !empty($_GET['scope']) ? $_GET['scope'] : "";
            $google_code_check   = !empty($_GET['code']) && !isset($_GET['oauth']) ? true : false;

            if( $google_code && $google_scope ){
                $ays_survey_tab = 'tab2';
            }

            // Disconect account and redirect back
            if( isset( $_REQUEST['ays_disconnect_google_sheets'] ) ){
                $result = $actions->ays_update_setting('google', '');
                $this->delete_quiz_sheet_ids();
        
                $url = menu_page_url("survey-maker-settings", false);
                $url = add_query_arg( array(
                    'ays_survey_tab' => 'tab2',
                    'status' => 'gdisconnected'
                ), $url );
                wp_redirect( $url );
                exit();
            }
            
            // Save credentials and account , redirect back
            if( isset( $_REQUEST['ays_googleOAuth2'] ) ){
                // Google sheets
                $gclient_id = isset($_REQUEST['ays_google_client']) && $_REQUEST['ays_google_client'] != '' ? $_REQUEST['ays_google_client'] : '';
                $gclient_secret = isset($_REQUEST['ays_google_secret']) && $_REQUEST['ays_google_secret'] != '' ? $_REQUEST['ays_google_secret'] : '';
                $gredirect_url = isset($_REQUEST['ays_google_redirect']) && $_REQUEST['ays_google_redirect'] != '' ? $_REQUEST['ays_google_redirect'] : '';
                $google_sheets = array(
                    'client' => $gclient_id,
                    'secret' => $gclient_secret,
                    'redirect_uri' => $gredirect_url
                );
                $result = $actions->ays_update_setting('google', json_encode($google_sheets));
        
                $scopes = array(
                    'https://www.googleapis.com/auth/spreadsheets',
                    'https://www.googleapis.com/auth/userinfo.profile',
                    'https://www.googleapis.com/auth/userinfo.email',
                );
                $glogin_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' .
                    urlencode( implode( ' ', $scopes ) ) .
                    '&redirect_uri=' . urlencode( $gredirect_url ) . '&response_type=code&client_id=' . $gclient_id . '&access_type=offline&prompt=consent';
        
                wp_redirect( $glogin_url );
                exit();
            }
        
            $gerror_message = '';
            // Google passes a parameter 'code' in the Redirect Url
            if( $google_code && $google_scope ){
                try {
                    // Get the access token
                    $gtokens = $this->GetGoogleUserToken_RefreshToken($google_client, $google_redirect_url, $google_secret, $_GET['code']);
        
                    // Access Token
                    $gaccess_token = $gtokens['access_token'];
        
                    // Get user information
                    $google_user_info = $this->GetGoogleUserProfileInfo( $gaccess_token );
        
                    $google_sheets = array(
                        'client'        => $google_client,
                        'secret'        => $google_secret,
                        'redirect_uri'  => $google_redirect_uri,
                        'token'         => $gaccess_token,
                        'refresh_token' => $gtokens['refresh_token'],
                        'user_email'    => $google_user_info['email'],
                        'user_name'     => $google_user_info['name'],
                        'user_picture'  => $google_user_info['picture'],
                        'user_gid'      => $google_user_info['id']
                    );
        
                    $result = $actions->ays_update_setting('google', json_encode($google_sheets));
                    $url = menu_page_url("survey-maker-settings", false);
                    $url = add_query_arg( array(
                        'ays_survey_tab' => 'tab2',
                        'status' => 'gconnected'
                    ), $url );
                    wp_redirect( $url );
                    exit();
                }catch(Exception $e) {
                    $gerror_message = $e->getMessage();
                }
            }
        
            $google_res     = ($actions->ays_get_setting('google') === false) ? json_encode(array()) : $actions->ays_get_setting('google');
            $google_sheets  = json_decode($google_res, true);
            $google_email   = isset($google_sheets['user_email']) ? $google_sheets['user_email'] : '';
            $google_name    = isset($google_sheets['user_name']) ? $google_sheets['user_name'] : '';
            $google_picture = isset($google_sheets['user_picture']) ? $google_sheets['user_picture'] : '';
            $google_token   = isset($google_sheets['token']) ? $google_sheets['token'] : '';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/sheets_logo.png';
            $title = __( 'Google Sheets', $this->plugin_name );
            $not_connected = sprintf(__( "You are connected to Google Sheets with %s (%s) account.", $this->plugin_name ),"<strong><em>" . $google_name . "</em></strong>","<a href='mailto:" . $google_email . "'><strong><em>" . $google_email . "</em></strong></a>");
            $content = "";

            if( $google_token ){
                $content .= '<div class="form-group row">
                                <div class="col-sm-12">
                                    <blockquote>
                                        <span style="margin:0;font-weight:normal;font-style:normal;">'.$not_connected.'</span>
                                    </blockquote>
                                    <br>
                                    <input type="submit" class="btn btn-outline-danger" name="ays_disconnect_google_sheets" value="'.__( 'Disconnect', $this->plugin_name ).'">
                                </div>
                            </div>';
            } else {
                $content .= '<div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-group row" aria-describedby="aaa">
                                        <div class="col-sm-3">
                                            <button id="googleInstructionsPopOver" type="button" class="btn btn-info" data-original-title="Google Integration Setup Instructions" >'. __("Instructions", $this->plugin_name). '</button>
                                            <div class="d-none" id="googleInstructions">
                                                <p>1. '. __("Turn on Your Google Sheet API", $this->plugin_name) .'
                                                    <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>
                                                </p>
                                                <p>2. <a href="https://console.developers.google.com/apis/credentials" target="_blank">'. __("Create ", $this->plugin_name) .'</a>'. __("new Google Oauth client ID credentials (if you do not still have)", $this->plugin_name).'</p>
                                                <p>3. '. sprintf(__("Choose the application type as %s Web application %s", $this->plugin_name) , "<strong>" , "</strong>" ) .'</p>
                                                <p>4. '. sprintf(__("Add the following link in the %s Authorized redirect URIs %s field", $this->plugin_name) , "<strong>" , "</strong>") .'</p>
                                                <p>
                                                    <code>'. $google_redirect_url .'</code>
                                                </p>
                                                <p>5. '. sprintf(__("Click on the %s Create %s button", $this->plugin_name) , "<strong>", "</strong>") .'</p>
                                                <p>6. '. sprintf(__("Copy the %s Your Client ID %s and %s Your Client Secret %s from the opened popup and paste them in the corresponding fields.", $this->plugin_name) , "<strong>", "</strong>", "<strong>", "</strong>") .'</p>
                                                <p>7. '. sprintf(__("Click on the %s Connect %s button to complete authorization", $this->plugin_name), "<strong>", "</strong>") .'</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_google_client">
                                                '. __("Google Client ID", $this->plugin_name) .'
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_google_client" name="ays_google_client" value="'. $google_client.'">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_google_secret">
                                                '. __("Google Client Secret", $this->plugin_name) .'
                                            </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" id="ays_google_secret" name="ays_google_secret" value="">
                                            <input type="hidden" id="ays_google_redirect" name="ays_google_redirect" value="'.$google_redirect_url.'">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <button type="submit" name="ays_survey_googleOAuth2" id="ays_survey_googleOAuth2" class="btn btn-outline-info">
                                                '. __("Connect", $this->plugin_name) .'
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }
            $integrations['google'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );
            return $integrations;        
        }

        // Google sheet integration / front-end
        public function ays_front_end_google_sheet_functional($arguments, $options, $data){
            $actions = $this->settings_obj;
            // Get google sheet general settings
            $google_res           = ($actions->ays_get_setting('google') === false) ? json_encode(array()) : $actions->ays_get_setting('google');
            $google               = json_decode($google_res, true);
            $google_token         = isset($google['token']) ? $google['token'] : '';
            $google_refresh_token = isset($google['refresh_token']) ? $google['refresh_token'] : '';
            $google_client_id     = isset($google['client']) ? $google['client'] : '';
            $google_client_secret = isset($google['secret']) ? $google['secret'] : '';

            // Get google sheet options
            $enable_google        = isset($arguments['enable_google_sheets']) && $arguments['enable_google_sheets'] ? true : false;
            $all_questions        = isset($arguments['all_questions']) ? $arguments['all_questions'] : array();
            $questions_all_data   = isset($arguments['questions_all_data']) ? $arguments['questions_all_data'] : array();
            $sheet_id             = isset($arguments['spreadsheet_id']) && $arguments['spreadsheet_id'] != '' ? $arguments['spreadsheet_id'] : '';
            $survey_id            = isset($arguments['id']) && $arguments['id'] != '' ? $arguments['id'] : '';
            $res_last_id          = isset($arguments['result_last_id']) && $arguments['result_last_id'] != '' ? $arguments['result_last_id'] : '';
            $google_data = array(
                "refresh_token" => $google_refresh_token,
                "client_id"     => $google_client_id,
                "client_secret" => $google_client_secret,
                "sheed_id"      => $sheet_id,
                "all_questions" => $all_questions,
                "questions_all_data" => $questions_all_data,
                "result_last_id" => $res_last_id,
                'id'            => $survey_id
            );

            $this->ays_survey_add_google_sheets($google_data);
        }
        public function ays_front_end_google_sheet_options($args, $settings){
            $options = $settings['options'];
            
            // Google sheet
            $args['enable_google_sheets'] = ( isset($options['enable_google_sheets'] ) && $options['enable_google_sheets'] == 'on') ? true : false;
            $args['all_questions'] = ( isset($options['all_questions'] ) ) ? $options['all_questions'] : array();
            $args['spreadsheet_id'] = ( isset($options['spreadsheet_id'] ) && $options['spreadsheet_id'] != "" ) ? $options['spreadsheet_id'] : "";
            $args['result_last_id'] = ( isset($settings['result_last_id'] ) && $settings['result_last_id'] != "" ) ? $settings['result_last_id'] : "";
            $args['questions_all_data'] = ( isset($settings['questions_all_data'] ) && $settings['questions_all_data'] != "" ) ? $settings['questions_all_data'] : "";

            return $args;
        }
        
    // =====  Google sheet end   =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // =====  AI Survey Builder start   =====

    // AI Survey Builder integration / settings page
    // AI Survey Builder integration / settings page content
    public function ays_settings_page_survey_ai_content( $integrations, $args ){

		// AI Settings
        $icon  = SURVEY_MAKER_ADMIN_URL . '/images/icons/ai-survey-builder.png';
        $title = __( 'ChatGPT Question Builder', $this->plugin_name );

		$content = '';
        $content = '<div class="form-group row" style="margin:0px;">';
        $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
        $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
            $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                    $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                    $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                        $content .= __("Upgrade to Agency" , "survey-maker");
                    $content .= '</div>';
                $content .= '</a>';
            $content .= '</div>';
            $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_ai_client_secret">'. __('API Key', $this->plugin_name) .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';	

        $integrations['ai'] = array(
            'content' => $content,
            'icon'    => $icon,
            'title'   => $title,
        );
        return $integrations;
    }

    // =====  AI Survey Builder end   =====
    
    
    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== GamiPress start =====

        // GamiPress integration / survey page | GamiPress Nooo :D | Aro | Start
        
        // GamiPress integration / settings page

        // GamiPress integration in Gengeral settings page content
        public function ays_settings_page_gamipress_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/gamipress_logo.png';
            $title = __( 'GamiPress', $this->plugin_name );

            $content = '';
            if(in_array('gamipress/gamipress.php', apply_filters('active_plugins', get_option('active_plugins')))){
                $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <blockquote>' .
                            __( "Install the GamiPress plugin to use the integration. Configure the settings from the Automatic Points Awards section from the GamiPres plugin.", $this->plugin_name ) . '
                            <br>' .
                            __( "After enabling the integration, the Survey Maker will automatically be added to the event list.", $this->plugin_name ) . '
                        </blockquote>';
                $content .= '
                    </div>
                </div>';
            }else{
                $content = '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <blockquote style="border-color:red;">' . 
                            __('To enable the integration, please install the GamiPress plugin.' , $this->plugin_name) . 
                        '</blockquote>
                    </div>
                </div>';
            }

            $integrations['gamipress'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // GamiPress integration / admin

        // GamiPress
        public function gamipress_ays_survey_maker_activity_triggers( $triggers ) {

            $triggers[__( 'Social Survey', 'gamipress-ays-survey-maker-integration' )] = array(
                'gamipress_ays_survey_maker_submit_survey' => __( 'Submit Survey', 'gamipress-ays-survey-maker-integration' ),
            );

            return $triggers;
        }

        // GamiPress
        public function gamipress_ays_survey_maker_submit_survey() {

            $name_prefix = 'ays-survey-';
            $unique_id = isset($_REQUEST['unique_id']) ? sanitize_key( $_REQUEST['unique_id'] ) : null;

            if($unique_id === null){
                return;
            }

            // Get the Survey ID
            $survey_id = isset( $_REQUEST[ $name_prefix . 'id-' . $unique_id ] ) ? absint( $_REQUEST[ $name_prefix . 'id-' . $unique_id ] ) : null;
            
            if( is_null( $survey_id ) ) return;

            $user_id = get_current_user_id();

            // Guests not allowed yet
            if( $user_id === 0 ) return;

            // Award user for submit survey
            do_action( 'gamipress_ays_survey_maker_submit_survey', $survey_id, $user_id );
        }

        // GamiPress
        public function gamipress_ays_survey_maker_trigger_get_user_id( $user_id, $trigger, $args ) {

            switch ( $trigger ) {
                case 'gamipress_ays_survey_maker_submit_survey':
                    $user_id = $args[1];
                    break;
            }

            return $user_id;
        }

        // GamiPress integration / front-end
        public function gamipress_ays_survey_maker_finish_survey(){
            // GamiPress
            if(in_array('gamipress/gamipress.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
                do_action( 'ays_sm_survey_before_save_entry' );
            }
        }

        // GamiPress | Aro | End

    // ===== GamiPress end =====
    
    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== SendGrid start =====

        // SendGrid integration / survey page

        // SendGrid integration in survey page options

        // SendGrid integration in survey page data saver
        public function ays_survey_page_sendgrid_save( $options, $data){

            // SendGrid template id
            $options['survey_sendgrid_template_id'] = ( isset( $data['ays_survey_sendgrid_template_id'] ) && $data['ays_survey_sendgrid_template_id'] != '' ) ? sanitize_text_field( $data['ays_survey_sendgrid_template_id'] ) : '';

            return $options;
        }

        // SendGrid integration / settings page

        // SendGrid integration in Gengeral settings page content
        public function ays_settings_page_sendgrid_content( $integrations, $args ){
            $actions = $this->settings_obj;
            
            $sendgrid_res     = ($actions->ays_get_setting('sendgrid') === false) ? json_encode(array()) : $actions->ays_get_setting('sendgrid');
            $sendgrid         = json_decode($sendgrid_res, true);
            $sendgrid_api_key = isset($sendgrid['apiKey']) ? $sendgrid['apiKey'] : '';
            
            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/sendgrid_logo.png';
            $title = __( 'SendGrid', $this->plugin_name );

            $content = '';
            $content .= '
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_sendgrid_username">'. __('SendGrid API Key',$this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text"
                                class="ays-text-input"
                                id="ays_sendgrid_api_key"
                                name="ays_sendgrid_api_key"
                                value="'. $sendgrid_api_key .'"
                            />
                        </div>
                    </div>
                    <hr/>
                    <blockquote>';
            $content .= sprintf( __( "You can get your API key from", $this->plugin_name ) . " <a href='%s' target='_blank'> %s.</a>", "https://app.sendgrid.com/settings/api_keys", "sendgrid.com" );
            $content .= '
                    </blockquote>
                </div>
            </div>';

            $integrations['sendgrid'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // SendGrid integration in Gengeral settings page data saver
        public function ays_settings_page_sendgrid_save( $fields, $data ){
            $sendgrid_api_key = isset($data['ays_sendgrid_api_key']) ? $data['ays_sendgrid_api_key'] : '';
            $sendgrid         = array(
                'apiKey' => $sendgrid_api_key,
            );
                
            $fields['sendgrid'] = $sendgrid;

            return $fields;
        }

        // SendGrid integration / front-end
        
        // SendGrid integration in front-end functional
        public function ays_front_end_sendgrid_functional( $arguments, $options, $data ){

            if( ! empty( $data['user_email'] ) && filter_var( $data['user_email'], FILTER_VALIDATE_EMAIL ) ){

                // User E-mail
                $user_email = $data['user_email'];

                // User name
                $user_name  = $data['user_name'];

                // SendGrid | Api Key
                $survey_sendgrid_api_key = $arguments[ 'survey_sendgrid_api_key' ];

                // SendGrid email name
                $survey_sendgrid_email_name = $arguments[ 'survey_sendgrid_email_name' ];

                // SendGrid email from
                $survey_sendgrid_email_from = $arguments[ 'survey_sendgrid_email_from' ];

                // SendGrid Reply to name
                $survey_sendgrid_reply_to_name = $arguments[ 'survey_sendgrid_reply_to_name' ];

                // SendGrid Reply to email
                $survey_sendgrid_reply_to_email = $arguments[ 'survey_sendgrid_reply_to_email' ];

                // SendGrid email subject
                $sendgrid_email_subject =  $arguments[ 'sendgrid_email_subject' ];

                // SendGrid template id
                $survey_sendgrid_template_id = $arguments[ 'survey_sendgrid_template_id' ];

                if ( $survey_sendgrid_api_key != '' && $survey_sendgrid_template_id != '') {

                    $args = array(
                        "email_name"    => $survey_sendgrid_email_name,
                        "email_from"    => $survey_sendgrid_email_from,
                        "email_to"      => $user_email,
                        "subject"       => $sendgrid_email_subject,                                
                        "name"          => $user_name,                              
                        "template"      => $survey_sendgrid_template_id,
                        "reply_to_name" => $survey_sendgrid_reply_to_name,
                        "reply_to_email"=> $survey_sendgrid_reply_to_email,
                    );                          
                    $sgresult = $this->ays_add_sendgrid_transaction( $survey_sendgrid_api_key, $args );
                }
            }

        }

        // SendGrid integration in front-end options
        public function ays_front_end_sendgrid_options( $args, $options ){
            $valid_name_prefix = 'survey_';

            // SendGrid

            // SendGrid | General settings
            $setting_sendgrig = Survey_Maker_Data::get_setting_data( 'sendgrid' );

            // SendGrid | Api Key
            $args[ 'survey_sendgrid_api_key' ] = ( isset($setting_sendgrig['apiKey']) && $setting_sendgrig['apiKey'] != '' ) ? sanitize_text_field( $setting_sendgrig['apiKey'] ) : '';

            // SendGrid email name
            $args[ 'survey_sendgrid_email_name' ] = ( isset( $options[ $valid_name_prefix . 'sendgrid_email_name' ] ) && $options[ $valid_name_prefix . 'sendgrid_email_name' ] != '' ) ? sanitize_text_field( $options[ $valid_name_prefix . 'sendgrid_email_name' ] ) : '';

            // SendGrid email from
            $args[ 'survey_sendgrid_email_from' ] = ( isset( $options[ $valid_name_prefix . 'sendgrid_email_from' ] ) && $options[ $valid_name_prefix . 'sendgrid_email_from' ] != '' ) ? sanitize_text_field( $options[ $valid_name_prefix . 'sendgrid_email_from' ] ) : '';

            // SendGrid Reply to name
            $args[ 'survey_sendgrid_reply_to_name' ] = ( isset( $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_name' ] ) && $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_name' ] != '' ) ? sanitize_text_field( $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_name' ] ) : '';

            // SendGrid Reply to email
            $args[ 'survey_sendgrid_reply_to_email' ] = ( isset( $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_email' ] ) && $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_email' ] != '' ) ? sanitize_text_field( $options[ $valid_name_prefix . 'survey_sendgrid_reply_to_email' ] ) : '';

            // SendGrid email subject
            $args[ 'sendgrid_email_subject' ] = ( isset( $options[ $valid_name_prefix . 'sendgrid_email_subject' ] ) && $options[ $valid_name_prefix . 'sendgrid_email_subject' ] != '' ) ? sanitize_text_field( $options[ $valid_name_prefix . 'sendgrid_email_subject' ] ) : '';

            // SendGrid template id
            $args[ 'survey_sendgrid_template_id' ] = ( isset( $options[ $valid_name_prefix . 'sendgrid_template_id' ] ) && $options[ $valid_name_prefix . 'sendgrid_template_id' ] != '' ) ? sanitize_text_field( $options[ $valid_name_prefix . 'sendgrid_template_id' ] ) : '';

            return $args;
        }

    // ===== SendGrid end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Mad mimi start =====

        // Mad mimi integration

        // Mad mimi integration in survey page content
        public function ays_survey_page_mad_mimi_content( $integrations, $args ){

            $survey_settings = $this->settings_obj;
            // Mad Mimi
            $mad_mimi_res  = ($survey_settings->ays_get_setting('mad_mimi') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('mad_mimi');
            $mad_mimi      = json_decode($mad_mimi_res, true);
            $mad_mimi_user_name = isset($mad_mimi['user_name']) ? $mad_mimi['user_name'] : '';
            $mad_mimi_api_key   = isset($mad_mimi['api_key']) ? $mad_mimi['api_key'] : '';
            $mad_mimi_lists = $this->ays_survey_mad_mimi_lists($mad_mimi_user_name , $mad_mimi_api_key);

            $enable_mad_mimi = $args['enable_mad_mimi'];
            $mad_mimi_list = $args['mad_mimi_list'];

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mad-mimi-logo.png';
            $title = __('Mad Mimi Settings',$this->plugin_name);

            $content = '';
            if(count($mad_mimi) > 0){
                if($mad_mimi_user_name == "" || $mad_mimi_api_key == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = ($mad_mimi_user_name == "" || $mad_mimi_api_key == "") ? "disabled" : '';
                    $checked = ($enable_mad_mimi == true) ? "checked" : '';

                    $content .= '<div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_enable_mad_mimi">'. __('Enable Mad Mimi', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_mad_mimi" name="ays_survey_enable_mad_mimi" value="on" '.$checked.' '.$disabled.'/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_mad_mimi_list">'. __('Select List', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-8">';
                            if(!empty($mad_mimi_lists)){
                                $mad_mimi_select  = "<select name='ays_survey_mad_mimi_list' id='ays_survey_mad_mimi_list'>";
                                $mad_mimi_select .= "<option value='' disabled>Select list</option>";
                                foreach($mad_mimi_lists as $key => $mad_mimi_list){
                                    $list_name = isset($mad_mimi_list['name']) && $mad_mimi_list['name'] != "" ? esc_attr($mad_mimi_list['name']) : "";
                                    $selected = isset($mad_mimi_db_list) && $mad_mimi_db_list == $list_name ? "selected" : "";
                                    $mad_mimi_select .= "<option value='".$list_name."' ".$selected.">".$list_name."</option>";
                                }
                                $mad_mimi_select .= "</select>";
                                $content .= $mad_mimi_select;
                            }else{
                                $content .= __("There are no lists" , $this->plugin_name);
                            }
                    $content .= '</div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['mad_mimi'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // Mad mimi integration in survey page options
        public function ays_survey_page_mad_mimi_options( $args, $options ){

            // Mad Mimi
            $args['enable_mad_mimi']  = (isset($options['enable_mad_mimi']) && $options['enable_mad_mimi'] == 'on') ? true : false;
            $args['mad_mimi_list'] = (isset($options['mad_mimi_list'])) ? $options['mad_mimi_list'] : '';

            return $args;
        }

        // Mad mimi integration in survey page data saver
        public function ays_survey_page_mad_mimi_save( $options, $data ){

            $options['enable_mad_mimi'] = ( isset( $data['ays_survey_enable_mad_mimi'] ) && $data['ays_survey_enable_mad_mimi'] == 'on' ) ? 'on' : 'off';
            $options['mad_mimi_list'] = !isset( $data['ays_survey_mad_mimi_list'] ) ? "" : sanitize_text_field( $data['ays_survey_mad_mimi_list'] );

            return $options;
        }

        // Mad mimi integration / settings page

        // Mad mimi integration in General settings page content
        public function ays_settings_page_mad_mimi_content( $integrations, $args ){

            $actions = $this->settings_obj;

            // Mad mimi
            $mad_mimi_options = ($actions->ays_get_setting('mad_mimi') === false) ? json_encode(array()) : $actions->ays_get_setting('mad_mimi');
            $mad_mimi_options = json_decode($mad_mimi_options, true);
            $mad_mimi_user_name = isset($mad_mimi_options['user_name']) && $mad_mimi_options['user_name'] != "" ? esc_attr($mad_mimi_options['user_name']) : '';
            $mad_mimi_api_key   = isset($mad_mimi_options['api_key']) && $mad_mimi_options['api_key'] != "" ? esc_attr($mad_mimi_options['api_key']) : '';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mad-mimi-logo.png';
            $title = __( 'Mad Mimi', $this->plugin_name );

            $content = '';
            $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_mad_mimi_user_name">'. __('Username', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_mad_mimi_user_name" name="ays_survey_mad_mimi_user_name" value="'. $mad_mimi_user_name .'" >
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_mad_mimi_api_key">'. __('API Key', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_mad_mimi_api_key" name="ays_survey_mad_mimi_api_key" value="'. $mad_mimi_api_key .'" >
                            </div>
                        </div>';
            $content .= '<blockquote>';
            $content .= sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://madmimi.com/user/edit?account_info_tabs=account_info_personal", "Account" );
            $content .= '</blockquote>';
            $content .= '
                </div>
            </div>';

            $integrations['mad_mimi'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // Mad mimi integration in General settings page data saver
        public function ays_settings_page_mad_mimi_save( $fields, $data ){

            $mad_mimi_user_name = isset($data['ays_survey_mad_mimi_user_name']) && $data['ays_survey_mad_mimi_user_name'] != "" ? sanitize_text_field($data['ays_survey_mad_mimi_user_name']) : '';
            $mad_mimi_api_key   = isset($data['ays_survey_mad_mimi_api_key']) && $data['ays_survey_mad_mimi_api_key'] != "" ? sanitize_text_field($data['ays_survey_mad_mimi_api_key']) : '';

            $mad_mimi_options   = array(
                "user_name" => $mad_mimi_user_name,
                "api_key"   => $mad_mimi_api_key
            );

            $fields['mad_mimi'] = $mad_mimi_options;

            return $fields;
        }


        // Mad mimi integration / front-end

        // Mad mimi integration in front-end functional
        public function ays_front_end_mad_mimi_functional( $arguments, $options, $data ){
            if( $arguments['enable_mad_mimi'] ){
                if( !empty( $data['user_email'] ) ){
                    
                    $survey_settings = $this->settings_obj;
                    $mad_mimi_res = ($survey_settings->ays_get_setting('mad_mimi') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('mad_mimi');
                    $mad_mimi = json_decode($mad_mimi_res, true);
                    $mad_mimi_user_name = isset($mad_mimi['user_name']) ? $mad_mimi['user_name'] : '' ;
                    $mad_mimi_api_key   = isset($mad_mimi['api_key']) ? $mad_mimi['api_key'] : '' ;
                    
                    
                    $mad_mimi_email  = (isset($data['user_email']) && $data['user_email'] != "") ? sanitize_email( $data['user_email'] ) : "";
                    $user_name       = explode(" ", sanitize_text_field( $data['user_name'] ), 2 );
                    $mad_mimi_fname  = (isset($user_name[0]) && $user_name[0] != "") ? $user_name[0] : "";
                    $mad_mimi_lname  = (isset($user_name[1]) && $user_name[1] != "") ? $user_name[1] : "";

                    $mad_mimi_data   = array(
                        "mad_mimi_user_name" => $mad_mimi_user_name,
                        "api_key"            => $mad_mimi_api_key,
                        "list"               => $arguments['mad_mimi_list'],
                        "user_email"         => $mad_mimi_email,
                        "user_first_name"    => $mad_mimi_fname,
                        "user_last_name"     => $mad_mimi_lname
                    );

                    $mresult = $this->ays_survey_add_mad_mimi_email( $mad_mimi_data );
                }
            }
        }

        // Mad mimi integration in front-end options
        public function ays_front_end_mad_mimi_options( $args, $setting ){
            $options = $setting['options'];
            // Mad mimi
            $args['enable_mad_mimi'] = ( isset($options['enable_mad_mimi'] ) && $options['enable_mad_mimi'] == 'on') ? true : false;
            $args['mad_mimi_list'] = (isset($options['mad_mimi_list'])) ? $options['mad_mimi_list'] : '';

            return $args;
        }

    // ===== Mad mimi end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== GetResponse start =====

        // GetResponse integration

        // GetResponse integration in survey page content
        public function ays_survey_page_get_response_content( $integrations, $args ){

            $survey_settings = $this->settings_obj;

            // GetResponse
            $getResponse_res = ($survey_settings->ays_get_setting('get_response') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('get_response');
            $getResponse = json_decode($getResponse_res, true);
            $getResponse_api_key = isset($getResponse['api_key']) ? $getResponse['api_key'] : '';
            $getResponse_lists = $this->ays_survey_getResposne_lists($getResponse_api_key);
            $getResponse_status  = isset($getResponse_lists['status']) && $getResponse_lists['status'] ? true : false;
            $getResponse_message = isset($getResponse_lists['message']) && $getResponse_lists['message'] ? esc_attr($getResponse_lists['message']) : __("Something went wrong", $this->plugin_name);

            $enable_getResponse = $args['enable_getResponse'];
            $getResponse_db_list = $args['getResponse_list'];

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/get_response.png';
            $title = __('GetResponse Settings',$this->plugin_name);

            $content = '';
            if(count($getResponse) > 0){
                if( $getResponse_api_key == "" ){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = !$getResponse_status ? "disabled" : '';
                    $checked = ($enable_getResponse == true) ? "checked" : '';

                    $content .= '<div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_enable_getResponse">'. __('Enable GetResponse', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_getResponse" name="ays_survey_enable_getResponse" value="on" '.$checked.' '.$disabled.'/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_getResponse_list">'. __('GetResponse List', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-8">';
                            if( isset( $getResponse_lists ) && !empty( $getResponse_lists ) ){
                                if( $getResponse_status ){
                                    $getResponse_select  = "<select name='ays_survey_getResponse_list' id='ays_survey_getResponse_list'>";
                                    $getResponse_select .= "<option value='' disabled>Select list</option>";
                                    foreach($getResponse_lists as $key => $getResponse_list){
                                        if(isset($getResponse_list) && is_array($getResponse_list)){
                                            $list_id   = isset($getResponse_list['campaignId']) && $getResponse_list['campaignId'] != "" ? esc_attr($getResponse_list['campaignId']) : "";
                                            $list_name = isset($getResponse_list['name']) && $getResponse_list['name'] != "" ? esc_attr($getResponse_list['name']) : "";
                                            $selected_list = ($list_id == $getResponse_db_list) ? "selected" : "";
                                            $getResponse_select .= "<option value='".$list_id."' ".$selected_list.">".$list_name."</option>";
                                        }
                                    }
                                    $getResponse_select .= "</select>";
                                    $content .= $getResponse_select;
                                }else{
                                    $content .= "<blockquote style='border-left:2px solid red;font-size: 16px;'>" . $getResponse_message . "</blockquote>";
                                }
                            }else{
                                $content .= __("There are no forms" , $this->plugin_name);
                            }
                    $content .= '</div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['get_response'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // GetResponse integration in survey page options
        public function ays_survey_page_get_response_options( $args, $options ){

            // GetResponse options
            $args['enable_getResponse']  = (isset($options['enable_getResponse']) && $options['enable_getResponse'] == 'on') ? true : false;
            $args['getResponse_list'] = (isset($options['getResponse_list'])) ? esc_attr( $options['getResponse_list'] ) : '';

            return $args;
        }

        // GetResponse integration in survey page data saver
        public function ays_survey_page_get_response_save( $options, $data ){

            $options['enable_getResponse'] = ( isset( $data['ays_survey_enable_getResponse'] ) && $data['ays_survey_enable_getResponse'] == 'on' ) ? 'on' : 'off';
            $options['getResponse_list'] = !isset( $data['ays_survey_getResponse_list'] ) ? "" : sanitize_text_field( $data['ays_survey_getResponse_list'] );

            return $options;
        }

        // GetResponse integration / settings page

        // GetResponse integration in General settings page content
        public function ays_settings_page_get_response_content( $integrations, $args ){

            $actions = $this->settings_obj;

            // GetResponse
            $getResponse_res  = ($actions->ays_get_setting('get_response') === false) ? json_encode(array()) : $actions->ays_get_setting('get_response');
            $getResponse      = json_decode($getResponse_res, true);
            $getResponse_api_key = isset($getResponse['api_key']) ? $getResponse['api_key'] : '';



            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/get_response.png';
            $title = __( 'GetResponse', $this->plugin_name );

            $content = '';
            $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_getresponse_api_key">'. __('GetResponse API Key', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_getresponse_api_key" name="ays_survey_getresponse_api_key" value="'. $getResponse_api_key .'" >
                            </div>
                        </div>';
            $content .= '<blockquote>';
            $content .= sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://app.getresponse.com/api", "account" );
            $content .= '</blockquote>';
            $content .= '<blockquote>';
            $content .= __( "For security reasons, unused API keys expire after 90 days. When that happens, you’ll need to generate a new key.", $this->plugin_name );
            $content .= '</blockquote>';
            $content .= '
                </div>
            </div>';

            $integrations['get_response'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // GetResponse integration in General settings page data saver
        public function ays_settings_page_get_response_save( $fields, $data ){

            $getResponse_api_key = isset($data['ays_survey_getresponse_api_key']) && $data['ays_survey_getresponse_api_key'] != "" ? sanitize_text_field($data['ays_survey_getresponse_api_key']) : '';
            $getResponse_options = array(
                "api_key" => $getResponse_api_key
            );

            $fields['get_response'] =  $getResponse_options;

            return $fields;
        }


        // GetResponse integration / front-end

        // GetResponse integration in front-end functional
        public function ays_front_end_get_response_functional( $arguments, $options, $data ){

            if( $arguments['enable_getResponse'] ){
                if( !empty( $data['user_email'] ) ){

                    $survey_settings = $this->settings_obj;

                    $getResponse_res = ($survey_settings->ays_get_setting('get_response') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('get_response');
                    $getResponse     = json_decode($getResponse_res, true);

                    $getResponse_api_key    = isset($getResponse['api_key']) ? $getResponse['api_key'] : '';
                    $getResponse_new_email  = (isset($data['user_email']) && $data['user_email'] != "") ? sanitize_email($data['user_email']) : "";
                    $getResponse_user_name  = isset($data['user_name']) ? explode(" ", $data['user_name'], 2) : array();
                    $getResponse_fname      = (isset($getResponse_user_name[0]) && $getResponse_user_name[0] != "") ? $getResponse_user_name[0] : "";
                    $getResponse_lname      = (isset($getResponse_user_name[1]) && $getResponse_user_name[1] != "") ? $getResponse_user_name[1] : "";
                    $getResponse_data = array(
                        "api_key" => $getResponse_api_key,
                        "list_id" => $arguments['getResponse_list'],
                        "email"   => $getResponse_new_email,
                        "fname"   => $getResponse_fname,
                        "lname"   => $getResponse_lname,
                    );
                    $mresult = $this->ays_survey_add_getResponse_contact( $getResponse_data );
                }
            }
        }

        // GetResponse integration in front-end options
        public function ays_front_end_get_response_options( $args, $settings ){
            $options = $settings['options'];
            // ConvertKit Settings
            $args['enable_getResponse'] = ( isset($options['enable_getResponse'] ) && $options['enable_getResponse'] == 'on') ? true : false;
            $args['getResponse_list'] = (isset($options['getResponse_list'])) ? $options['getResponse_list'] : '';

            return $args;
        }

    // ===== GetResponse end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== ConvertKit Settings start =====

        // ConvertKit Settings integration

        // ConvertKit Settings integration in survey page content
        public function ays_survey_page_convert_kit_content( $integrations, $args ){

            $survey_settings = $this->settings_obj;

            // ConvertKit Settings
            $convertKit_res      = ($survey_settings->ays_get_setting('convertKit') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('convertKit');
            $convertKit          = json_decode($convertKit_res, true);
            $convertKit_api_key  = isset($convertKit['api_key']) && $convertKit['api_key'] != "" ? esc_attr($convertKit['api_key']) : '';
            $convertKit_forms    = $this->ays_get_convertKit_forms($convertKit_api_key);

            $convertKit_forms_list      = isset($convertKit_forms['forms']) && !empty($convertKit_forms['forms']) ? $convertKit_forms['forms'] : array();
            $convertKit_response_status = isset($convertKit_forms['status']) && $convertKit_forms['status'] ? true : false;

            $enable_convertKit  = $args['enable_convertKit'];
            $convertKit_form_id = $args['convertKit_form_id'];

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/convertkit_logo.png';
            $title = __('ConvertKit Settings',$this->plugin_name);

            $content = '';
            if(count($convertKit) > 0){
                if( $convertKit_api_key == "" ){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = !$convertKit_response_status ? "disabled" : '';
                    $checked = ($enable_convertKit == true) ? "checked" : '';

                    $content .= '<div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_enable_convertkit">'. __('Enable ConvertKit', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_convertkit" name="ays_survey_enable_convertkit" value="on" '.$checked.' '.$disabled.'/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_convertKit_list">'. __('ConvertKit List', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-8">';
                            if(isset($convertKit_forms) && !empty($convertKit_forms)){
                                if( $convertKit_response_status ){
                                    $convertKit_select  = "<select name='ays_survey_convertKit_list' id='ays_survey_convertKit_list'>";
                                    $convertKit_select .= "<option value='' disabled>Select list</option>";
                                    foreach($convertKit_forms_list as $key => $convertKit_form){
                                        $response_form_id = isset($convertKit_form['id']) && $convertKit_form['id'] != "" ? $convertKit_form['id'] : "";
                                        $response_form_name = isset($convertKit_form['name']) && $convertKit_form['name'] != "" ? $convertKit_form['name'] : "";

                                        $selected = ($convertKit_form_id == $response_form_id) ? 'selected' : '';
                                        $convertKit_select .= "<option value='".$response_form_id."' ".$selected.">".$response_form_name."</option>";
                                    }
                                    $convertKit_select .= "</select>";
                                    $content .= $convertKit_select;
                                }else{
                                    $content .= __("There are no forms" , $this->plugin_name);
                                }
                            }else{
                                $content .= __("There are no forms" , $this->plugin_name);
                            }
                    $content .= '</div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['convertKit'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // ConvertKit Settings integration in survey page options
        public function ays_survey_page_convert_kit_options( $args, $options ){

            // ConvertKit Settings
            $args['enable_convertKit']  = (isset($options['enable_convertKit']) && $options['enable_convertKit'] == 'on') ? true : false;
            $args['convertKit_form_id'] = (isset($options['convertKit_form_id'])) ? esc_attr( $options['convertKit_form_id'] ) : '';

            return $args;
        }

        // ConvertKit Settings integration in survey page data saver
        public function ays_survey_page_convert_kit_save( $options, $data ){

            $options['enable_convertKit'] = ( isset( $data['ays_survey_enable_convertkit'] ) && $data['ays_survey_enable_convertkit'] == 'on' ) ? 'on' : 'off';
            $options['convertKit_form_id'] = !isset( $data['ays_survey_convertKit_list'] ) ? "" : sanitize_text_field( $data['ays_survey_convertKit_list'] );

            return $options;
        }

        // ConvertKit Settings integration / settings page

        // ConvertKit Settings integration in General settings page content
        public function ays_settings_page_convert_kit_content( $integrations, $args ){

            $actions = $this->settings_obj;

            // ConvertKit Settings
            $convertKit_res         = ($actions->ays_get_setting('convertKit') === false) ? json_encode(array()) : $actions->ays_get_setting('convertKit');
            $convertKit             = json_decode($convertKit_res, true);
            $convertKit_account_id  = isset($convertKit['api_key']) ? esc_attr($convertKit['api_key']) : '';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/convertkit_logo.png';
            $title = __( 'ConvertKit', $this->plugin_name );

            $content = '';
            $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_convert_kit">'. __('API Key', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_convert_kit" name="ays_survey_convert_kit" value="'. $convertKit_account_id .'" >
                            </div>
                        </div>';
            $content .= '<blockquote>';
            $content .= sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://app.convertkit.com/account/edit", "Account" );
            $content .= '</blockquote>';
            $content .= '
                </div>
            </div>';

            $integrations['convertKit'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // ConvertKit Settings integration in General settings page data saver
        public function ays_settings_page_convert_kit_save( $fields, $data ){

            $convertKit_account_id = isset($data['ays_survey_convert_kit']) && $data['ays_survey_convert_kit'] != "" ? sanitize_text_field($data['ays_survey_convert_kit']) : '';

            $convertKit_options = array(
                "api_key" => $convertKit_account_id,
            );

            $fields['convertKit'] = $convertKit_options;

            return $fields;
        }


        // ConvertKit Settings integration / front-end

        // ConvertKit Settings integration in front-end functional
        public function ays_front_end_convert_kit_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_convertKit'] ){
                if( !empty( $data['user_email'] ) ){

                    $survey_settings = $this->settings_obj;

                    $convertKit_data     = array();

                    $covertKit_res       = ($survey_settings->ays_get_setting('convertKit') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('convertKit');
                    $covertKit           = json_decode($covertKit_res, true);
                    $convertKit_api_key  = isset($covertKit['api_key']) && $covertKit['api_key'] != "" ? $covertKit['api_key'] : '';
                    $covertKit_email     = (isset($data['user_email']) && $data['user_email'] != "") ? sanitize_email( $data['user_email'] ) : "";
                    $covertKit_name      = isset($data['user_name']) && $data['user_name'] != "" ? explode(" ", sanitize_text_field( $data['user_name'] ), 2 ) : array();
                    
                    $covertKit_fname = (isset($covertKit_name[0]) && $covertKit_name[0] != "") ? $covertKit_name[0] : "";
                    $covertKit_lname = (isset($covertKit_name[1]) && $covertKit_name[1] != "") ? $covertKit_name[1] : "";

                    $convertKit_data = array(
                        "api_key" => $convertKit_api_key,
                        "form_id" => $arguments['convertKit_form_id'],
                        "email"   => $covertKit_email,
                        "fname"   => $covertKit_fname,
                        "lname"   => $covertKit_lname
                    );
                    $mresult = $this->ays_survey_convertKit_add_user( $convertKit_data );
                }
            }
        }

        // ConvertKit Settings integration in front-end options
        public function ays_front_end_convert_kit_options( $args, $settings ){
            $options = $settings['options'];
            // ConvertKit Settings
            $args['enable_convertKit']  = (isset($options['enable_convertKit'] ) && $options['enable_convertKit'] == 'on') ? true : false;
            $args['convertKit_form_id'] = (isset($options['convertKit_form_id'])) ? $options['convertKit_form_id'] : '';

            return $args;
        }

    // ===== ConvertKit Settings end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Sendinblue Settings start =====

        // Sendinblue Settings integration

        // Sendinblue Settings integration in survey page content
        public function ays_survey_page_sendinblue_content( $integrations, $args ){

            $survey_settings = $this->settings_obj;

            // Sendinblue Settings
            $sendinblue_res      = ($survey_settings->ays_get_setting('sendinblue') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('sendinblue');
            $sendinblue          = json_decode($sendinblue_res, true);
            $sendinblue_api_key  = isset($sendinblue['api_key']) && $sendinblue['api_key'] != "" ? esc_attr($sendinblue['api_key']) : '';
            $sendinblue_lists    = $this->ays_survey_get_sendinblue_lists($sendinblue_api_key);

            $sendinblue_get_lists = isset($sendinblue_lists['lists']) && !empty($sendinblue_lists['lists']) ? $sendinblue_lists['lists'] : array();
            $sendinblue_response_status = isset($sendinblue_lists['status']) && $sendinblue_lists['status'] ? true : false;

            $enable_sendinblue  = $args['enable_sendinblue'];
            $sendinblue_list_id = $args['sendinblue_list_id'];

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/sendinblue.png';
            $title = __('Sendinblue Settings',$this->plugin_name);

            $content = '';
            if(count($sendinblue) > 0){
                if( $sendinblue_api_key == "" ){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = !$sendinblue_response_status ? "disabled" : '';
                    $checked = ($enable_sendinblue == true) ? "checked" : '';

                    $content .= '<div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_enable_sendinblue">'. __('Enable Sendinblue', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_sendinblue" name="ays_survey_enable_sendinblue" value="on" '.$checked.' '.$disabled.'/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_sendinblue_list_id">'. __('Sendinblue Lists', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-8">';
                            if(isset($sendinblue_get_lists) && !empty($sendinblue_get_lists)){
                                if( $sendinblue_response_status ){
                                    $sendinblue_select  = "<select name='ays_survey_sendinblue_list_id' id='ays_survey_sendinblue_list_id'>";
                                    $sendinblue_select .= "<option value='' disabled>Select list</option>";
                                    foreach($sendinblue_get_lists as $key => $sendinblue_list){
                                        $response_form_id = isset($sendinblue_list['id']) && $sendinblue_list['id'] != "" ? $sendinblue_list['id'] : "";
                                        $response_form_name = isset($sendinblue_list['name']) && $sendinblue_list['name'] != "" ? $sendinblue_list['name'] : "";

                                        $selected = ($sendinblue_list_id == $response_form_id) ? 'selected' : '';
                                        $sendinblue_select .= "<option value='".$response_form_id."' ".$selected.">".$response_form_name."</option>";
                                    }
                                    $sendinblue_select .= "</select>";
                                    $content .= $sendinblue_select;
                                }else{
                                    $content .= __("There are no forms" , $this->plugin_name);
                                }
                            }else{
                                $content .= __("There are no forms" , $this->plugin_name);
                            }
                    $content .= '</div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['sendinblue'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // Sendinblue Settings integration in survey page options
        public function ays_survey_page_sendinblue_options( $args, $options ){

            // Sendinblue Settings
            $args['enable_sendinblue']  = (isset($options['enable_sendinblue']) && $options['enable_sendinblue'] == 'on') ? true : false;
            $args['sendinblue_list_id'] = (isset($options['sendinblue_list_id'])) ? esc_attr( $options['sendinblue_list_id'] ) : '';

            return $args;
        }

        // Sendinblue Settings integration in survey page data saver
        public function ays_survey_page_sendinblue_save( $options, $data ){

            $options['enable_sendinblue']  = (isset( $data['ays_survey_enable_sendinblue'] ) && $data['ays_survey_enable_sendinblue'] == 'on') ? 'on' : 'off';
            $options['sendinblue_list_id'] = (isset( $data['ays_survey_sendinblue_list_id'] ) && $data['ays_survey_sendinblue_list_id'] != "") ? sanitize_text_field( $data['ays_survey_sendinblue_list_id'] ) : "";

            return $options;
        }

        // Sendinblue Settings integration / settings page

        // Sendinblue Settings integration in General settings page content
        public function ays_settings_page_sendinblue_content( $integrations, $args ){

            $actions = $this->settings_obj;

            // Sendinblue Settings
            $sendinblue_res         = ($actions->ays_get_setting('sendinblue') === false) ? json_encode(array()) : $actions->ays_get_setting('sendinblue');
            $sendinblue             = json_decode($sendinblue_res, true);
            $sendinblue_api_key     = isset($sendinblue['api_key']) && $sendinblue['api_key'] != "" ? esc_attr($sendinblue['api_key']) : '';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/sendinblue.png';
            $title = __( 'Sendinblue', $this->plugin_name );

            $content = '';
            $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_sendinblue">'. __('API Key', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_sendinblue" name="ays_survey_sendinblue" value="'. $sendinblue_api_key .'" >
                            </div>
                        </div>';
            $content .= '<blockquote>';
                $content .= sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://account.sendinblue.com/advanced/api", "Account" );
            $content .= '</blockquote>';
            $content .= '
                </div>
            </div>';

            $integrations['sendinblue'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // Sendinblue Settings integration in General settings page data saver
        public function ays_settings_page_sendinblue_save( $fields, $data ){

            $sendinblue_api_key = isset($data['ays_survey_sendinblue']) && $data['ays_survey_sendinblue'] != "" ? sanitize_text_field($data['ays_survey_sendinblue']) : '';

            $sendinblue_options = array(
                "api_key" => $sendinblue_api_key,
            );

            $fields['sendinblue'] = $sendinblue_options;

            return $fields;
        }


        // Sendinblue Settings integration / front-end

        // Sendinblue Settings integration in front-end functional
        public function ays_front_end_sendinblue_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_sendinblue'] ){
                if( !empty( $data['user_email'] ) ){

                    $survey_settings = $this->settings_obj;

                    $sendinblue_data     = array();

                    $sendinblue_res       = ($survey_settings->ays_get_setting('sendinblue') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('sendinblue');
                    $sendinblue           = json_decode($sendinblue_res, true);
                    $sendinblue_api_key   = isset($sendinblue['api_key']) && $sendinblue['api_key'] != "" ? $sendinblue['api_key'] : '';
                    $sendinblue_email     = (isset($data['user_email']) && $data['user_email'] != "") ? sanitize_email( $data['user_email'] ) : "";
                    $sendinblue_name      = isset($data['user_name']) && $data['user_name'] != "" ? explode(" ", sanitize_text_field( $data['user_name'] ), 2 ) : array();
                    
                    $sendinblue_fname = (isset($sendinblue_name[0]) && $sendinblue_name[0] != "") ? $sendinblue_name[0] : "";
                    $sendinblue_lname = (isset($sendinblue_name[1]) && $sendinblue_name[1] != "") ? $sendinblue_name[1] : "";

                    $sendinblue_data = array(
                        "api_key" => $sendinblue_api_key,
                        "list_id" => $arguments['sendinblue_list_id'],
                        "email"   => $sendinblue_email,
                        "fname"   => $sendinblue_fname,
                        "lname"   => $sendinblue_lname
                    );
                    $mresult = $this->ays_survey_sendinblue_add_contact_to_list( $sendinblue_data );
                }
            }
        }

        // Sendinblue Settings integration in front-end options
        public function ays_front_end_sendinblue_options( $args, $settings ){
            $options = $settings['options'];
            // Sendinblue Settings
            $args['enable_sendinblue']  = (isset($options['enable_sendinblue'] ) && $options['enable_sendinblue'] == 'on') ? true : false;
            $args['sendinblue_list_id'] = (isset($options['sendinblue_list_id'])) ? $options['sendinblue_list_id'] : '';

            return $args;
        }

    // ===== Sendinblue Settings end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== MailerLite Settings start =====

        // MailerLite Settings integration

        // MailerLite Settings integration in survey page content
        public function ays_survey_page_mailerLite_content( $integrations, $args ){

            $survey_settings = $this->settings_obj;

            // MailerLite Settings
            $mailerLite_res     = ($survey_settings->ays_get_setting('mailerLite') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('mailerLite');
            $mailerLite         = json_decode($mailerLite_res, true);
            $mailerLite_api_key = isset($mailerLite['api_key']) && $mailerLite['api_key'] != "" ? esc_attr($mailerLite['api_key']) : '';
            $mailerLite_groups  = $this->ays_survey_get_mailerLite_groups($mailerLite_api_key);
            
            $mailerLite_get_groups      = isset($mailerLite_groups['groups']) && !empty($mailerLite_groups['groups']) ? $mailerLite_groups['groups'] : array();
            $mailerLite_response_status = isset($mailerLite_groups['status']) && $mailerLite_groups['status'] ? true : false;

            $enable_mailerLite  = $args['enable_mailerLite'];
            $mailerLite_group_id = $args['mailerLite_group_id'];

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mailerlite.png';
            $title = __('MailerLite Settings',$this->plugin_name);

            $content = '';
            if(count($mailerLite) > 0){
                if( $mailerLite_api_key == "" ){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = !$mailerLite_response_status ? "disabled" : '';
                    $checked = ($enable_mailerLite == true) ? "checked" : '';

                    $content .= '<div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_enable_mailerLite">'. __('Enable MailerLite', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_mailerLite" name="ays_survey_enable_mailerLite" value="on" '.$checked.' '.$disabled.'/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_survey_mailerLite_group_id">'. __('MailerLite Lists', $this->plugin_name) .'</label>
                        </div>
                        <div class="col-sm-8">';
                            if(isset($mailerLite_get_groups) && !empty($mailerLite_get_groups)){
                                if( $mailerLite_response_status ){
                                    $mailerLite_select  = "<select name='ays_survey_mailerLite_group_id' id='ays_survey_mailerLite_group_id'>";
                                    $mailerLite_select .= "<option value='' disabled>Select list</option>";
                                    foreach($mailerLite_get_groups as $key => $mailerLite_group){
                                        $response_form_id   = isset($mailerLite_group['id'])   && $mailerLite_group['id'] != ""   ? $mailerLite_group['id'] : "";
                                        $response_form_name = isset($mailerLite_group['name']) && $mailerLite_group['name'] != "" ? $mailerLite_group['name'] : "";

                                        $selected = ($mailerLite_group_id == $response_form_id) ? 'selected' : '';
                                        $mailerLite_select .= "<option value='".$response_form_id."' ".$selected.">".$response_form_name."</option>";
                                    }
                                    $mailerLite_select .= "</select>";
                                    $content .= $mailerLite_select;
                                }else{
                                    $content .= __("There are no forms" , $this->plugin_name);
                                }
                            }else{
                                $content .= __("There are no forms" , $this->plugin_name);
                            }
                    $content .= '</div>
                    </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['mailerLite'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // MailerLite Settings integration in survey page options
        public function ays_survey_page_mailerLite_options( $args, $options ){

            // MailerLite Settings
            $args['enable_mailerLite']  = (isset($options['enable_mailerLite']) && $options['enable_mailerLite'] == 'on') ? true : false;
            $args['mailerLite_group_id'] = (isset($options['mailerLite_group_id'])) ? esc_attr( $options['mailerLite_group_id'] ) : '';

            return $args;
        }

        // MailerLite Settings integration in survey page data saver
        public function ays_survey_page_mailerLite_save( $options, $data ){

            $options['enable_mailerLite']  = (isset( $data['ays_survey_enable_mailerLite'] ) && $data['ays_survey_enable_mailerLite'] == 'on') ? 'on' : 'off';
            $options['mailerLite_group_id'] = (isset( $data['ays_survey_mailerLite_group_id'] ) && $data['ays_survey_mailerLite_group_id'] != "") ? sanitize_text_field( $data['ays_survey_mailerLite_group_id'] ) : "";

            return $options;
        }

        // MailerLite Settings integration / settings page

        // MailerLite Settings integration in General settings page content
        public function ays_settings_page_mailerLite_content( $integrations, $args ){

            $actions = $this->settings_obj;

            // MailerLite Settings
            $mailerLite_res     = ($actions->ays_get_setting('mailerLite') === false) ? json_encode(array()) : $actions->ays_get_setting('mailerLite');
            $mailerLite         = json_decode($mailerLite_res, true);
            $mailerLite_api_key = isset($mailerLite['api_key']) && $mailerLite['api_key'] != "" ? esc_attr($mailerLite['api_key']) : '';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mailerlite.png';
            $title = __( 'MailerLite', $this->plugin_name );

            $content = '';
            $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_mailerLite">'. __('API Key', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_mailerLite" name="ays_survey_mailerLite" value="'. $mailerLite_api_key .'" >
                            </div>
                        </div>';
            $content .= '<blockquote>';
                $content .= sprintf( __( "You can get your API key from your ", $this->plugin_name ) . "<a href='%s' target='_blank'> %s.</a>", "https://app.mailerlite.com/integrations/api", "Account" );
            $content .= '</blockquote>';
            $content .= '
                </div>
            </div>';

            $integrations['mailerLite'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // MailerLite Settings integration in General settings page data saver
        public function ays_settings_page_mailerLite_save( $fields, $data ){

            $mailerLite_api_key = isset($data['ays_survey_mailerLite']) && $data['ays_survey_mailerLite'] != "" ? sanitize_text_field($data['ays_survey_mailerLite']) : '';

            $mailerLite_options = array(
                "api_key" => $mailerLite_api_key
            );

            $fields['mailerLite'] = $mailerLite_options;

            return $fields;
        }


        // MailerLite Settings integration / front-end

        // MailerLite Settings integration in front-end functional
        public function ays_front_end_mailerLite_functional( $arguments, $options, $data ){
            
            if( $arguments['enable_mailerLite'] ){
                if( !empty( $data['user_email'] ) ){
                    $survey_settings = $this->settings_obj;

                    $mailerLite_data     = array();

                    $mailerLite_res     = ($survey_settings->ays_get_setting('mailerLite') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('mailerLite');
                    $mailerLite         = json_decode($mailerLite_res, true);
                    $mailerLite_api_key = isset($mailerLite['api_key']) && $mailerLite['api_key'] != "" ? $mailerLite['api_key'] : '';
                    $mailerLite_email   = (isset($data['user_email']) && $data['user_email'] != "") ? sanitize_email( $data['user_email'] ) : "";
                    $mailerLite_name    = isset($data['user_name']) && $data['user_name'] != "" ? $data['user_name']  : array();

                    $mailerLite_data = array(
                        "api_key"  => $mailerLite_api_key,
                        "group_id" => $arguments['mailerLite_group_id'],
                        "email"    => $mailerLite_email,
                        "name"     => $mailerLite_name
                    );
                    $mresult = $this->ays_survey_malerLite_add_contact_to_group( $mailerLite_data );
                }
            }
        }

        // MailerLite Settings integration in front-end options
        public function ays_front_end_mailerLite_options( $args, $settings ){
            $options = $settings['options'];
            // MailerLite Settings
            $args['enable_mailerLite']   = (isset($options['enable_mailerLite'] ) && $options['enable_mailerLite'] == 'on') ? true : false;
            $args['mailerLite_group_id'] = (isset($options['mailerLite_group_id'])) ? $options['mailerLite_group_id'] : '';

            return $args;
        }

    // ===== MailerLite Settings end =====

    // ===== Paypal Settings start =====

        // PayPal Settings integration

        // PayPal Settings integration in survey page content
        public function ays_survey_page_PayPal_content( $integrations, $args ){
            $editor_id = 'ays_survey_paypal_message';
            $settings = array(
                'editor_height' => 150,
                'textarea_name' => 'ays_survey_paypal_message',
                'editor_class' => 'ays-textarea',
                'media_elements' => false,
                
            );

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/paypal_logo.png';
            $title = __('PayPal Settings',$this->plugin_name);
            ob_start();
            $content = '';
            wp_editor("", $editor_id, $settings);
            $content .= '<div class="form-group row" style="padding: 10px;">
                        <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
                            <div class="ays-pro-features-v2-small-buttons-box">
                                <div class="ays-pro-features-v2-video-button"></div>
                                    <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                        <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                        <div class="ays-pro-features-v2-upgrade-text">
                                            '.__("Upgrade to Developer/Agency" , "survey-maker").'
                                        </div>
                                    </a>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_paypal">
                                            '.__('Enable PayPal',$this->plugin_name).'
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" value="on" checked>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_paypal_amount">
                                            '.__('Amount',$this->plugin_name).'
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input ays-text-input-short">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_paypal_currency">
                                            '.__('Currency',$this->plugin_name).'
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="ays-text-input ays-text-input-short">';                                        
                                            $content .= '<option selected >-Currency-</option>';
                            $content .= '</select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_paypal_currency">
                                            '.__('Payment details',$this->plugin_name).'
                                        </label>
                                    </div>
                                    <div class="col-sm-8">';
                                    $editor_contents = ob_get_clean();
                                    $content .= $editor_contents;
                                    $content .= '</div>
                                </div>
                            </div>
                        </div>';
            
            
            $integrations['PayPal'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // ConverPayPaltKit Settings integration / settings page

        // PayPal Settings integration in General settings page content
        public function ays_settings_page_PayPal_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/paypal_logo.png';
            $title = __( 'PayPal', $this->plugin_name );
            $blockquote_content = sprintf( __( "You can get your Client ID from %s", $this->plugin_name ), "<a href='https://developer.paypal.com/developer/applications' target='_blank'> Developer Paypal.</a>");
            $content = '<div class="form-group row" style="margin: 0;">
                            <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
                            <div class="ays-pro-features-v2-small-buttons-box">
                                <div class="ays-pro-features-v2-video-button"></div>
                                    <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                        <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                        <div class="ays-pro-features-v2-upgrade-text">
                                            '.__("Upgrade to Developer/Agency" , "survey-maker").'
                                        </div>
                                    </a>
                                </div>
                                <hr>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label for="ays_survey_paypal_client_id">'.__('Paypal Client ID',$this->plugin_name).'</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="ays-text-input" >
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label>'.__('Payment terms',$this->plugin_name).'</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <label class="ays_survey_loader" style="display:inline-block;">
                                                <input type="radio" value="lifetime" checked/>
                                                <span>'.__('Lifetime payment',$this->plugin_name).'</span>
                                            </label>
                                            <label class="ays_survey_loader" style="display:inline-block;">
                                                <input type="radio" value="onetime" />
                                                <span>'.__('Onetime payment',$this->plugin_name).'</span>
                                            </label>
                                        </div>
                                    </div>
                                    <blockquote>
                                        '.$blockquote_content.'
                                    </blockquote>
                                </div>
                            </div>
                        </div>';

            $integrations['PayPal'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // PayPal Settings integration in General settings page data saver
        public function ays_settings_page_PayPal_save( $fields, $data ){

            $paypal_account_id = isset($data['ays_survey_paypal_client_id']) && $data['ays_survey_paypal_client_id'] != "" ? sanitize_text_field($data['ays_survey_paypal_client_id']) : '';
            $paypal_terms      = isset($data['ays_survey_paypal_payment_terms']) && $data['ays_survey_paypal_payment_terms'] != "" ? sanitize_text_field($data['ays_survey_paypal_payment_terms']) : '';

            $paypal_options = array(
                "paypal_client_id" => $paypal_account_id,
                "payment_terms" => $paypal_terms
            );

            $fields['paypal'] = $paypal_options;

            return $fields;
        }


        // PayPal Settings integration / front-end

        // PayPal Settings integration in front-end functional
        public function ays_front_end_PayPal_functional( $arguments, $id, $html ){
            // General options
            global $wpdb;
            if( ! session_id() ){
                session_start();
            }
            $paypal_res       = ($this->settings_obj->ays_get_setting('paypal') === false) ? json_encode(array()) : $this->settings_obj->ays_get_setting('paypal');
            $paypal           = json_decode($paypal_res, true);
            $paypal_client_id = isset($paypal['paypal_client_id']) ? esc_attr($paypal['paypal_client_id']) : '';
            $payment_terms    = isset($paypal['payment_terms']) && $paypal['payment_terms'] != "" ? esc_attr($paypal['payment_terms']) : 'lifetime';

            // Survey page options
            // PayPal Settings
            $survey_paypal   = (isset($arguments['options']['survey_enable_paypal']) && $arguments['options']['survey_enable_paypal'] == 'on') ? true : false;
            $paypal_amount   = (isset($arguments['options']['survey_paypal_amount'])   && $arguments['options']['survey_paypal_amount'] != "") ? esc_attr($arguments['options']['survey_paypal_amount']) : "";  
            $paypal_currency = (isset($arguments['options']['survey_paypal_currency']) && $arguments['options']['survey_paypal_currency'] != "") ? esc_attr($arguments['options']['survey_paypal_currency']) : "";
            $paypal_message  = (isset($arguments['options']['survey_paypal_message'])  && $arguments['options']['survey_paypal_message'] != "") ? stripslashes( wpautop($arguments['options']['survey_paypal_message']) ) : __('You need to pay to pass this survey.', $this->plugin_name);

            $all_payments = (isset($_SESSION['ays_survey_all_purchases'][$id]) && $_SESSION['ays_survey_all_purchases'][$id]) ? true : false;
            $paypal_connection = null;
            if(is_user_logged_in()){
                $html['paypal']['is_user_logged_in'] = true;
                if($payment_terms == "onetime"){
                    if(isset($_SESSION['ays_survey_paypal_purchase']) && isset( $_SESSION['ays_survey_paypal_purchase'][$id]) ||
                      (isset($_SESSION['ays_survey_all_purchases'])) && isset($_SESSION['ays_survey_all_purchases'][$id])){
                        if((isset($_SESSION['ays_survey_paypal_purchase'][$id]) && $_SESSION['ays_survey_paypal_purchase'][$id] == true) || $all_payments){
                            $paypal_connection = false;
                        }else{
                            $paypal_connection = true;
                        }
                    }else{
                        $_SESSION['ays_survey_paypal_purchase'][$id] = false;
                        $_SESSION['ays_survey_all_purchases'][$id] = false;
                        $paypal_connection = true;
                    }
                }elseif($payment_terms == "lifetime"){
                    $current_user = wp_get_current_user();
                    $current_usermeta = get_user_meta($current_user->data->ID, "survey_paypal_purchase");
                    $current_usermeta_all_payed = get_user_meta($current_user->data->ID, "survey_payment_purchase_all");
                    $survey_payment_all_usermeta = false;
                    if(($current_usermeta !== false && !empty($current_usermeta)) || ($current_usermeta_all_payed != false && !empty($current_usermeta_all_payed))){

                        foreach($current_usermeta as $usermeta){
                            if($id == json_decode($usermeta, true)['surveyId']){
                                $survey_paypal_usermeta = json_decode($usermeta, true);
                                break;
                            }else{
                                $survey_paypal_usermeta = false;
                            }
                        }
                        
                        foreach($current_usermeta_all_payed as $usermeta_all){
                            if($id == json_decode($usermeta_all, true)['surveyId']){
                                $survey_payment_all_usermeta = json_decode($usermeta_all, true);
                                break;
                            }else{
                                $survey_payment_all_usermeta = false;
                            }
                        }

                        if($survey_paypal_usermeta !== false || $survey_payment_all_usermeta !== false){
                            if(( isset($survey_paypal_usermeta['purchased']) && $survey_paypal_usermeta['purchased'] == true ) ||
                               ( isset($survey_payment_all_usermeta['purchasedAll']) && $survey_payment_all_usermeta['purchasedAll'] == true )){
                                $paypal_connection = false;
                            }else{
                                $paypal_connection = true;
                            }
                        }else{
                            $opts = json_encode(array(
                                'surveyId' => $id,
                                'purchased' => false
                            ));
                            add_user_meta($current_user->data->ID, "survey_paypal_purchase", $opts);
                            $paypal_connection = true;
                        }
                    }else{
                        $opts = json_encode(array(
                            'surveyId' => $id,
                            'purchased' => false
                        ));
                        add_user_meta($current_user->data->ID, "survey_paypal_purchase", $opts);
                        $paypal_connection = true;
                    }
                    
                }
            }else{
                $html['paypal']['is_user_logged_in'] = false;
                $html['paypal']['is_lifetime'] = false;
                if($payment_terms == "lifetime"){
                    $paypal_connection = false;
                    $html['paypal']['is_lifetime'] = true;
                }
                else{
                    $paypal_connection = true;
                }
                if(isset($_SESSION['ays_survey_paypal_purchase']) && isset( $_SESSION['ays_survey_paypal_purchase'][$id]) ||
                      (isset($_SESSION['ays_survey_all_purchases'])) && isset($_SESSION['ays_survey_all_purchases'][$id])){
                    if((isset($_SESSION['ays_survey_paypal_purchase'][$id]) && $_SESSION['ays_survey_paypal_purchase'][$id] == true) || $all_payments){
                        $html['paypal']['survey_paypal'] = null;
                        $html['paypal']['show_paypal'] = false;
                        $paypal_connection = false;
                    }else{
                        $paypal_connection = true;
                    }
                }else{
                    $_SESSION['ays_survey_paypal_purchase'][$id] = false;
                    $_SESSION['ays_survey_all_purchases'][$id] = false;
                    $paypal_connection = true;
                    $html['paypal']['show_paypal'] = true;
                }
            }

            if($survey_paypal && $paypal_connection === true){
                if($paypal_client_id == null || $paypal_client_id == ''){
                    $html['paypal']['survey_paypal'] = null;
                    $html['paypal']['show_paypal'] = false;
                }else{
                    if($html['paypal']['is_user_logged_in'] || (!$html['paypal']['is_user_logged_in'] && !$html['paypal']['is_lifetime'])){
                        $html['paypal']['show_paypal'] = true;
                        wp_enqueue_script(
                            $this->plugin_name . '-paypal',
                            "https://www.paypal.com/sdk/js?client-id=".$paypal_client_id."&currency=".$paypal_currency."",
                            array('jquery'),
                            null,
                            true
                        );
                        $html['paypal']['survey_paypal'] = '
                            <div class="ays-survey-paypal-wrap-div" id="ays-survey-paypal-box-'.$id.'">
                                <div class="ays-survey-paypal-details-div">
                                    '.$paypal_message.'
                                </div>
                                <div class="ays_paypal_div">
                                    <div id="ays_survey_paypal_button_container_'.$id.'"></div>
                                </div>
                            </div>
                            <script>
                                window.addEventListener("DOMContentLoaded", function() {
                                    (function($){
                                        $(document).ready(function(){
                                            if(typeof aysSurveyPayPal != "undefined"){
                                                aysSurveyPayPal.Buttons({
                                                    createOrder: function(data, actions) {
                                                        return actions.order.create({
                                                            purchase_units: [{
                                                                amount: {
                                                                    value: "'.$paypal_amount.'"
                                                                }
                                                            }]
                                                        });
                                                    },
                                                    onApprove: function(data, actions) {
                                                        return actions.order.capture().then(function(details) {
                                                            return fetch("'. SURVEY_MAKER_PUBLIC_URL .'/partials/paypal-transaction-complete.php", {
                                                                method: "post",
                                                                headers: {
                                                                    "Content-Type": "application/json"
                                                                },
                                                                body: JSON.stringify({
                                                                    data: data,
                                                                    details: details,
                                                                    surveyId: '.$id.'
                                                                }),
                                                                credentials: "same-origin"
                                                            }).then(response => response.json())
                                                            .then(data => {
                                                                Swal.fire({
                                                                    title:"Your payment successfuly finished.",
                                                                    type: "success",
                                                                    showCancelButton: false,
                                                                    allowOutsideClick: false,
                                                                    allowEscapeKey: false,
                                                                    allowEnterKey: false,
                                                                    width: "450px",
                                                                }).then((result) => {
                                                                    location.reload();
                                                                });
                                                            }).catch(error => console.error(error));
                                                        });
                                                    }
                                                }).render("#ays_survey_paypal_button_container_'.$id.'");
                                            }
                                            else{
                                                var errorMessage = "'.__("Survey Maker PayPal Message: `Wrong Client ID initialized`").'";
                                                console.error(errorMessage);
                                            }
                                        });
                                    })(jQuery);
                                });
                            </script>';
                    }
                    else{
                        $html['paypal']['show_paypal'] = true;
                        $html['paypal']['survey_paypal'] = "<div class='ays-survey-paypal-wrap-div '><span>".__('You need to log in to pass this survey.', $this->plugin_name)."</span></div>";
                    }
                }
            }else{
                $html['paypal']['survey_paypal'] = null;
                $html['paypal']['show_paypal'] = false;
            }

            return $html;
        }

        // PayPal Settings integration in front-end options
        public function ays_front_end_PayPal_options( $args, $settings ){
            $options = $settings['options'];
            // ConvertKit Settings
            $args['enable_convertKit']  = (isset($options['enable_convertKit'] ) && $options['enable_convertKit'] == 'on') ? true : false;
            $args['convertKit_form_id'] = (isset($options['convertKit_form_id'])) ? $options['convertKit_form_id'] : '';

            return $args;
        }

    // ===== Paypal Settings end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Stripe Settings start =====

        // Stripe Settings integration

        // Stripe Settings integration in survey page content
        public function ays_survey_page_Stripe_content( $integrations, $args ){
            // WP editor settings
            $editor_id = 'ays_survey_stripe_message';
            $settings = array(
                'editor_height' => 150,
                'textarea_name' => 'ays_survey_stripe_message',
                'editor_class' => 'ays-textarea',
                'media_elements' => false,
                
            );

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/stripe_logo.png';
            $title = __('Stripe Settings',$this->plugin_name);
            ob_start();
            $content = '';
                wp_editor("", $editor_id, $settings);
                $content .= '<div class="form-group row" style="padding: 10px;">
                                <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
                                <div class="ays-pro-features-v2-small-buttons-box">
                                    <div class="ays-pro-features-v2-video-button"></div>
                                        <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                            <div class="ays-pro-features-v2-upgrade-text">
                                                '.__("Upgrade to Developer/Agency" , "survey-maker").'
                                            </div>
                                        </a>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_enable_stripe">
                                                '.__('Enable Stripe',$this->plugin_name).'
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_stripe"
                                                value="off" checked>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_stripe_amount">
                                                '.__('Amount',$this->plugin_name).'
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input ays-text-input-short">
                                                <span class="ays_option_description">'. __( "Specify the amount of the payment.", $this->plugin_name ) .'</span>
                                                <span class="ays_option_description">'. __( "This field doesn't accept an empty value or a value less than 1.", $this->plugin_name ).'</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_stripe_currency">
                                                '.__('Currency',$this->plugin_name).'
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-text-input ays-text-input-short">';
                                                    $content .= '<option seleted value="" >-Currency-</option>';
                                $content .= '</select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_stripe_currency">
                                                '.__('Payment details',$this->plugin_name).'
                                            </label>
                                        </div>
                                        <div class="col-sm-8">';
                                            $editor_contents = ob_get_clean();
                                            $content .= $editor_contents;
                                            $content .= '</div>
                                        </div>
                                    </div>
                            </div>';
            
            
            $integrations['stripe'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // Stripe Settings integration / settings page

        // Stripe Settings integration in General settings page content
        public function ays_settings_page_Stripe_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/stripe_logo.png';
            $title = __( 'Stripe', $this->plugin_name );
            $blockquote_content = __( "You can get your Publishable and Secret keys on API Keys page on your Stripe dashboard.", $this->plugin_name );
            $content = '<div class="form-group row" style="margin: 0;">
                        <div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">
                        <div class="ays-pro-features-v2-small-buttons-box">
                            <div class="ays-pro-features-v2-video-button"></div>
                                <a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                    <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                    <div class="ays-pro-features-v2-upgrade-text">
                                        '.__("Upgrade to Developer/Agency" , "survey-maker").'
                                    </div>
                                </a>
                            </div>
                            <hr>
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_survey_stripe_api_key">'.__('Stripe Publishable Key',$this->plugin_name).'</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" 
                                            class="ays-text-input">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_survey_stripe_secret_key">'.__('Stripe Secret Key',$this->plugin_name).'</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" 
                                            class="ays-text-input"
                                            value="">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label>'.__('Payment terms',$this->plugin_name).'</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <label class="ays_survey_loader" style="display:inline-block;">
                                            <input type="radio" value="lifetime" checked/>
                                            <span>'.__('Lifetime payment',$this->plugin_name).'</span>
                                        </label>
                                        <label class="ays_survey_loader" style="display:inline-block;">
                                            <input type="radio" value="onetime" />
                                            <span>'.__('Onetime payment',$this->plugin_name).'</span>
                                        </label>
                                    </div>
                                </div>
                                <blockquote>
                                    '.$blockquote_content.'
                                </blockquote>
                            </div>
                            </div>
                        </div>';

            $integrations['stripe'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // Stripe Settings integration in General settings page data saver
        public function ays_settings_page_Stripe_save( $fields, $data ){

            $stripe_api_key    = isset($data['ays_survey_stripe_api_key']) && $data['ays_survey_stripe_api_key'] != "" ? sanitize_text_field($data['ays_survey_stripe_api_key']) : '';
            $stripe_secret_key = isset($data['ays_survey_stripe_secret_key']) && $data['ays_survey_stripe_secret_key'] != "" ? sanitize_text_field($data['ays_survey_stripe_secret_key']) : '';
            $stripe_terms      = isset($data['ays_survey_stripe_payment_terms']) && $data['ays_survey_stripe_payment_terms'] != "" ? sanitize_text_field($data['ays_survey_stripe_payment_terms']) : '';

            $stripe_options = array(
                "api_key"       => $stripe_api_key,
                "secret_key"    => $stripe_secret_key,
                "payment_terms" => $stripe_terms
            );

            $fields['stripe'] = $stripe_options;

            return $fields;
        }


        // Stripe Settings integration / front-end

        // Stripe Settings integration in front-end functional
        public function ays_front_end_Stripe_functional( $arguments, $id, $html ){
            // General options
            global $wpdb;
            if( ! session_id() ){
                session_start();
            }
            $stripe_res        = ($this->settings_obj->ays_get_setting('stripe') === false) ? json_encode(array()) : $this->settings_obj->ays_get_setting('stripe');
            $stripe            = json_decode($stripe_res, true);
            $stripe_api_key    = isset($stripe['api_key']) ? $stripe['api_key'] : '';
            $stripe_secret_key = isset($stripe['secret_key']) && $stripe['secret_key'] != "" ? $stripe['secret_key'] : 'lifetime';
            $payment_terms     = isset($stripe['payment_terms']) && $stripe['payment_terms'] != "" ? esc_attr($stripe['payment_terms']) : 'lifetime';

            // Survey page options
            // Survey Settings
            $survey_stripe   = (isset($arguments['options']['survey_enable_stripe'])   && $arguments['options']['survey_enable_stripe'] == 'on') ? true : false;
            $stripe_amount   = (isset($arguments['options']['survey_stripe_amount'])   && $arguments['options']['survey_stripe_amount'] != "") ? esc_attr($arguments['options']['survey_stripe_amount']) : "";  
            $stripe_currency = (isset($arguments['options']['survey_stripe_currency']) && $arguments['options']['survey_stripe_currency'] != "") ? esc_attr($arguments['options']['survey_stripe_currency']) : "";
            $stripe_message  = (isset($arguments['options']['survey_stripe_message'])  && $arguments['options']['survey_stripe_message'] != "") ? stripslashes( wpautop($arguments['options']['survey_stripe_message']) ) : __('You need to pay to pass this survey.', $this->plugin_name);

            $all_payments = (isset($_SESSION['ays_survey_all_purchases'][$id]) && $_SESSION['ays_survey_all_purchases'][$id]) ? true : false;
            $stripe_connection = null;
            if(is_user_logged_in()){
                $html['stripe']['is_user_logged_in'] = true;
                if($payment_terms == "onetime"){
                    if( isset($_SESSION['ays_survey_stripe_purchase']) && isset( $_SESSION['ays_survey_stripe_purchase'][$id] ) ||
                    (isset($_SESSION['ays_survey_all_purchases']) && isset($_SESSION['ays_survey_all_purchases'][$id]))){
                        if((isset($_SESSION['ays_survey_stripe_purchase'][$id]) && $_SESSION['ays_survey_stripe_purchase'][$id] == true) || $all_payments){
                            $stripe_connection = false;
                        }else{
                            $stripe_connection = true;
                        }
                    }else{
                        $_SESSION['ays_survey_stripe_purchase'][$id] = false;
                        $_SESSION['ays_survey_all_purchases'][$id] = false;
                        $stripe_connection = true;
                    }
                }elseif($payment_terms == "lifetime"){
                    $current_user = wp_get_current_user();
                    $current_usermeta = get_user_meta($current_user->data->ID, "survey_stripe_purchase");
                    $current_usermeta_all_payed = get_user_meta($current_user->data->ID, "survey_payment_purchase_all");
                    $survey_payment_all_usermeta = false;
                    if($current_usermeta !== false && !empty($current_usermeta) || ($current_usermeta_all_payed != false && !empty($current_usermeta_all_payed))){
                        foreach($current_usermeta as $usermeta){
                            if($id == json_decode($usermeta, true)['surveyId']){
                                $survey_stripe_usermeta = json_decode($usermeta, true);
                                break;
                            }else{
                                $survey_stripe_usermeta = false;
                            }
                        }

                        foreach($current_usermeta_all_payed as $usermeta_all){
                            if($id == json_decode($usermeta_all, true)['surveyId']){
                                $survey_payment_all_usermeta = json_decode($usermeta_all, true);
                                break;
                            }else{
                                $survey_payment_all_usermeta = false;
                            }
                        }

                        if($survey_stripe_usermeta !== false || $survey_payment_all_usermeta !== false){
                            if(( isset($survey_stripe_usermeta['purchased']) && $survey_stripe_usermeta['purchased'] == true ) ||
                               ( isset($survey_payment_all_usermeta['purchasedAll']) && $survey_payment_all_usermeta['purchasedAll'] == true)){
                                $stripe_connection = false;
                            }else{
                                $stripe_connection = true;
                            }
                        }else{
                            $opts = json_encode(array(
                                'surveyId' => $id,
                                'purchased' => false
                            ));
                            add_user_meta($current_user->data->ID, "survey_stripe_purchase", $opts);
                            $stripe_connection = true;
                        }
                    }else{
                        $opts = json_encode(array(
                            'surveyId' => $id,
                            'purchased' => false
                        ));
                        add_user_meta($current_user->data->ID, "survey_stripe_purchase", $opts);
                        $stripe_connection = true;
                    }
                }
            }else{
                $html['stripe']['is_user_logged_in'] = false;
                $html['stripe']['is_lifetime'] = false;
                if($payment_terms == "lifetime"){
                    $stripe_connection = false;
                    $html['stripe']['is_lifetime'] = true;
                }
                else{
                    $stripe_connection = true;
                }
                
                if( isset( $_SESSION['ays_survey_stripe_purchase'] ) && isset( $_SESSION['ays_survey_stripe_purchase'][$id] ) ||
                  (isset($_SESSION['ays_survey_all_purchases']) && isset($_SESSION['ays_survey_all_purchases'][$id]))){
                    if((isset($_SESSION['ays_survey_stripe_purchase'][$id]) && $_SESSION['ays_survey_stripe_purchase'][$id] == true) || $all_payments){
                        $html['stripe']['survey_stripe'] = null;
                        $html['stripe']['show_stripe'] = false;
                        $stripe_connection = false;
                    }else{
                        $stripe_connection = true;
                    }
                }else{
                    $_SESSION['ays_survey_stripe_purchase'][$id] = false;
                    $_SESSION['ays_survey_all_purchases'][$id] = false;
                    $stripe_connection = true;
                    $html['stripe']['show_stripe'] = true;
                }
            }

            if($survey_stripe && $stripe_connection === true){
                $stripe_data = array(
                    'survey_id'            => $id,
                    'stripe_api_key'       => $stripe_api_key,
                    'stripe_secret_key'    => $stripe_secret_key,
                    'stripe_payment_terms' => $payment_terms,
                    'stripe_amount'        => $stripe_amount,
                    'stripe_currency'      => $stripe_currency,
                    'stripe_message'       => $stripe_message,
                    'is_user_logged_in'    => $html['stripe']['is_user_logged_in'],
                    'is_lifetime'          => isset($html['stripe']['is_lifetime']) && $html['stripe']['is_lifetime'] ? true : false,
                );
                $new_html = $this->ays_survey_stripe_content($stripe_data);
                
                $html['stripe']['survey_stripe'] = $new_html['survey_stripe'];
                $html['stripe']['show_stripe']   = $new_html['show_stripe'];
            }else{
                $html['stripe']['survey_stripe'] = null;
                $html['stripe']['show_stripe'] = false;
            }

            return $html;
        }

        // Stripe integration in front-end in finish
        // public function ays_front_end_Stripe_finish( $id ){
        //     if(!session_id()) {
        //         session_start();
        //     }
        //     if(isset($_SESSION)){
        //         if(isset($_SESSION['ays_survey_paypal_purchase']) && isset( $_SESSION['ays_survey_paypal_purchase'][$id] ) ){
        //             $_SESSION['ays_survey_paypal_purchase'][$id] = false;
        //             unset($_SESSION['ays_survey_paypal_purchase'][$id]);
        //         }
        //         if(array_key_exists('ays_survey_paypal_purchase', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_paypal_purchase'])){
        //             $_SESSION['ays_survey_paypal_purchase'][$id] = false;
        //             unset($_SESSION['ays_survey_paypal_purchase'][$id]);
        //         }
        //         if(isset($_SESSION['ays_survey_paypal_purchased_item']) && isset( $_SESSION['ays_survey_paypal_purchased_item'][$id] ) ){
        //             $_SESSION['ays_survey_paypal_purchased_item'][$id]['status'] = 'finished';
        //             unset($_SESSION['ays_survey_paypal_purchased_item'][$id]);
        //         }
        //         if(array_key_exists('ays_survey_paypal_purchased_item', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_paypal_purchased_item'])){
        //             $_SESSION['ays_survey_paypal_purchased_item'][$id]['status'] = 'finished';
        //             unset($_SESSION['ays_survey_paypal_purchased_item'][$id]);
        //         }

        //         if(isset($_SESSION['ays_survey_stripe_purchase']) && isset( $_SESSION['ays_survey_stripe_purchase'][$id] ) ){
        //             $_SESSION['ays_survey_stripe_purchase'][$id] = false;
        //             unset($_SESSION['ays_survey_stripe_purchase'][$id]);
        //         }
        //         if(array_key_exists('ays_survey_stripe_purchase', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_stripe_purchase'])){
        //             $_SESSION['ays_survey_stripe_purchase'][$id] = false;
        //             unset($_SESSION['ays_survey_stripe_purchase'][$id]);
        //         }
        //     }
        // }

    // ===== Stripe Settings end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

	// ===== reCAPTCHA start =====

        // reCAPTCHA integration

        // reCAPTCHA integration in survey page content
        public function ays_survey_page_recaptcha_content( $integrations, $args ){

            $survey_settings = $this->settings_obj;
            // reCAPTCHA
            $recaptcha_res  = ($survey_settings->ays_get_setting('recaptcha') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('recaptcha');
            $recaptcha      = json_decode($recaptcha_res, true);
            $recaptcha_site_key = isset($recaptcha['site_key']) && $recaptcha['site_key'] != "" ? esc_attr($recaptcha['site_key']) : '';
            $recaptcha_secret_key = isset($recaptcha['secret_key']) && $recaptcha['secret_key'] != "" ? esc_attr($recaptcha['secret_key']) : '';

            $enable_recaptcha = $args['enable_recaptcha'];

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/recaptcha_logo.png';
            $title = __('reCAPTCHA Settings',$this->plugin_name);

            $content = '';
            if(count($recaptcha) > 0){
                if($recaptcha_site_key == "" || $recaptcha_secret_key == ""){
                    $content .= $this->blockquote_content;
                }else{
                    $disabled = ($recaptcha_site_key == "" || $recaptcha_secret_key == "") ? "disabled" : '';
                    $checked = ($enable_recaptcha == true) ? "checked" : '';

                    $content .= '<div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_survey_enable_recaptcha">'. __('Enable reCAPTCHA', $this->plugin_name) .'</label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_recaptcha" name="ays_survey_enable_recaptcha" value="on" '.$checked.' '.$disabled.'/>
                            </div>
                        </div>';
                }
            }else{
                $content .= $this->blockquote_content;
            }

            $integrations['recaptcha'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // reCAPTCHA integration in survey page options
        public function ays_survey_page_recaptcha_options( $args, $options ){

            // reCAPTCHA
            $args['enable_recaptcha'] = (isset($options['enable_recaptcha']) && $options['enable_recaptcha'] == 'on') ? true : false;

            return $args;
        }

        // reCAPTCHA integration in survey page data saver
        public function ays_survey_page_recaptcha_save( $options, $data ){

            $options['enable_recaptcha'] = ( isset( $data['ays_survey_enable_recaptcha'] ) && $data['ays_survey_enable_recaptcha'] == 'on' ) ? 'on' : 'off';

            return $options;
        }

        // reCAPTCHA integration / settings page

        // reCAPTCHA integration in General settings page content
        public function ays_settings_page_recaptcha_content( $integrations, $args ){

            $actions = $this->settings_obj;

            // reCAPTCHA
            $recaptcha_options = ($actions->ays_get_setting('recaptcha') === false) ? json_encode(array()) : $actions->ays_get_setting('recaptcha');
            $recaptcha_options = json_decode($recaptcha_options, true);
            $recaptcha_site_key = isset($recaptcha_options['site_key']) && $recaptcha_options['site_key'] != "" ? esc_attr($recaptcha_options['site_key']) : '';
            $recaptcha_secret_key = isset($recaptcha_options['secret_key']) && $recaptcha_options['secret_key'] != "" ? esc_attr($recaptcha_options['secret_key']) : '';
            $recaptcha_language = isset($recaptcha_options['language']) && $recaptcha_options['language'] != "" ? esc_attr($recaptcha_options['language']) : '';
            $recaptcha_theme = isset($recaptcha_options['theme']) && $recaptcha_options['theme'] != "" ? esc_attr($recaptcha_options['theme']) : 'light';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/recaptcha_logo.png';
            $title = __( 'reCAPTCHA', $this->plugin_name );

            $content = '';
            $content .= '
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_survey_recaptcha_site_key">'. __('reCAPTCHA v2 Site Key', $this->plugin_name) .'</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input" id="ays_survey_recaptcha_site_key" name="ays_survey_recaptcha_site_key" value="'. $recaptcha_site_key .'" >
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_survey_recaptcha_secret_key">'. __('reCAPTCHA v2 Secret Key', $this->plugin_name) .'</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input" id="ays_survey_recaptcha_secret_key" name="ays_survey_recaptcha_secret_key" value="'. $recaptcha_secret_key .'" >
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_survey_recaptcha_language">'. __('reCAPTCHA Language', $this->plugin_name) .'</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="ays-text-input" id="ays_survey_recaptcha_language" name="ays_survey_recaptcha_language" value="'. $recaptcha_language .'" >
                                    <span class="ays_survey_small_hint_text">
                                        <span>' . sprintf(
                                            __( "e.g. en, de - Language used by reCAPTCHA. To get the code for your language click %s here %s", $this->plugin_name ),
                                            '<a href="https://developers.google.com/recaptcha/docs/language" target="_blank">',
                                            "</a>"
                                        ) . '</span>
                                    </span>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="ays_survey_recaptcha_theme">'. __('reCAPTCHA Theme', $this->plugin_name) .'</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="ays-text-input" id="ays_survey_recaptcha_theme" name="ays_survey_recaptcha_theme" >
                                        <option value="light" '. ( $recaptcha_theme == 'light' ? 'selected' : '' ) .'>'. __('Light', $this->plugin_name) .'</option>
                                        <option value="dark" '. ( $recaptcha_theme == 'dark' ? 'selected' : '' ) .'>'. __('Dark', $this->plugin_name) .'</option>
                                    </select>
                                </div>
                            </div>
                            ';
            $content .= '<blockquote>';
            $content .= sprintf( __( "You need to set up reCAPTCHA in your Google account to generate the required keys and get them by %s Google's reCAPTCHA admin console %s.", $this->plugin_name ), "<a href='https://www.google.com/recaptcha/admin/create' target='_blank'>", "</a>");
            $content .= '</blockquote>';
            $content .= '
                    </div>
                </div>';

            $integrations['recaptcha'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // reCAPTCHA integration in General settings page data saver
        public function ays_settings_page_recaptcha_save( $fields, $data ){

            $recaptcha_site_key = isset($data['ays_survey_recaptcha_site_key']) && $data['ays_survey_recaptcha_site_key'] != "" ? sanitize_text_field($data['ays_survey_recaptcha_site_key']) : '';
            $recaptcha_secret_key = isset($data['ays_survey_recaptcha_secret_key']) && $data['ays_survey_recaptcha_secret_key'] != "" ? sanitize_text_field($data['ays_survey_recaptcha_secret_key']) : '';
            $recaptcha_language = isset($data['ays_survey_recaptcha_language']) && $data['ays_survey_recaptcha_language'] != "" ? sanitize_text_field($data['ays_survey_recaptcha_language']) : '';
            $recaptcha_theme = isset($data['ays_survey_recaptcha_theme']) && $data['ays_survey_recaptcha_theme'] != "" ? sanitize_text_field($data['ays_survey_recaptcha_theme']) : '';

            $recaptcha_options = array(
                "site_key" => $recaptcha_site_key,
                "secret_key" => $recaptcha_secret_key,
                "language" => $recaptcha_language,
                "theme" => $recaptcha_theme,
            );

            $fields['recaptcha'] = $recaptcha_options;

            return $fields;
        }

        // reCAPTCHA integration / front-end

        // reCAPTCHA integration in front-end functional
        public function ays_front_end_recaptcha_functional( $arguments, $options, $data ){
            if( isset($options['enable_recaptcha']) && $options['enable_recaptcha']){

                $survey_settings = $this->settings_obj;

                // reCAPTCHA
                $recaptcha_options = ($survey_settings->ays_get_setting('recaptcha') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('recaptcha');
                $recaptcha_options = json_decode($recaptcha_options, true);
                $recaptcha_site_key = isset($recaptcha_options['site_key']) && $recaptcha_options['site_key'] != "" ? esc_attr($recaptcha_options['site_key']) : '';
                $recaptcha_secret_key = isset($recaptcha_options['secret_key']) && $recaptcha_options['secret_key'] != "" ? esc_attr($recaptcha_options['secret_key']) : '';
                $recaptcha_language = isset($recaptcha_options['language']) && $recaptcha_options['language'] != "" ? esc_attr($recaptcha_options['language']) : '';
                $recaptcha_theme = isset($recaptcha_options['theme']) && $recaptcha_options['theme'] != "" ? esc_attr($recaptcha_options['theme']) : '';

                if( $recaptcha_language != '' ){
                    $hl = "&hl=".$recaptcha_language;
                }

                wp_enqueue_script(
                    $this->plugin_name . '-grecaptcha',
                    // 'https://www.google.com/recaptcha/api.js?onload=wpformsRecaptchaLoad&render=explicit',
                    'https://www.google.com/recaptcha/api.js?render=explicit' . $hl,
                    array('jquery'),
                    null,
                    true
                );

                wp_enqueue_script(
                    $this->plugin_name . '-grecaptcha-js',
                    SURVEY_MAKER_PUBLIC_URL . '/js/partials/grecaptcha.js',
                    array('jquery'),
                    $this->version,
                    true
                );

                $unique_key = uniqid();

                $options = array(
                    'uniqueKey' => $unique_key,
                    'siteKey' => $recaptcha_site_key,
                    'secretKey' => $recaptcha_secret_key,
                    'language' => $recaptcha_language,
                    'theme' => $recaptcha_theme,
                );

                $inline_js = "
                    if(typeof aysSurveyRecaptchaObj === 'undefined'){
                        var aysSurveyRecaptchaObj = [];
                    }
                    aysSurveyRecaptchaObj['" . $unique_key . "']  = '" . base64_encode( json_encode( $options ) ) . "';
                ";
                wp_add_inline_script( $this->plugin_name . '-grecaptcha', $inline_js, 'before' );

                $data_content = '';
                $data_content .= '<div class="ays-survey-section ays-survey-recaptcha-section">';
                    $data_content .= '<div class="ays-survey-section-header">';
                        $data_content .= '<div class="ays-survey-recaptcha-wrap">';
                            $data_content .= '<div class="ays-survey-g-recaptcha" data-unique-key="'. $unique_key .'"></div>';
                            $data_content .= '<div class="ays-survey-g-recaptcha-hidden-error ays-survey-question-validation-error">'. __( "reCAPTCHA field is required please complete!", $this->plugin_name ) .'</div>';
                        $data_content .= '</div>';
                    $data_content .= '</div>';
                $data_content .= '</div>';
    
                $arguments[] = $data_content;
            }

            return $arguments;
        }

        // reCAPTCHA integration in front-end options
        public function ays_front_end_recaptcha_options( $args, $setting ){
            $options = $setting['options'];
            // reCAPTCHA
            $args['enable_recaptcha'] = ( isset( $options['enable_recaptcha'] ) && $options['enable_recaptcha'] == 'on') ? true : false;

            return $args;
        }

    // ===== reCAPTCHA end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== Aweber start =====

        // Aweber integration

        // Aweber integration in survey page content
        public function ays_survey_page_aweber_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/aweber-logo.png';
            $title = __('Aweber Settings',"survey-maker");
            
            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-4">';
                            $content .= '<label for="ays_survey_enable_aweber">'. __('Enable Aweber', "survey-maker") .'</label>';
                        $content .= '</div>';
                        $content .='<div class="col-sm-1">';
                            $content .= '<input type="checkbox" class="ays-enable-timer1" />';
                        $content .= '</div>';
                    $content .= '</div>';	
                    $content .= '<hr>';
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-4">';
                            $content .= '<label for="ays_survey_aweber_list_id">'. __('Aweber Lists', "survey-maker") .'</label>';
                        $content .= '</div>';
                        $content .= '<div class="col-sm-8">';
                            $content .= '<select  class="ays-text-input">';
                                $content .= '<option value="" >Select list</option>';
                            $content .= '</select>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['aweber'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Aweber integration / settings page

        // Aweber integration in General settings page content
        public function ays_aweber_settings_page_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/aweber-logo.png';
            $title = __( 'Aweber', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-12">';
                            $content .= '<div class="form-group row">
                                            <div class="col-sm-3">
                                                <button id="aweberInstructionsPopOver" type="button" class="btn btn-info" data-original-title="Aweber Integration Setup Instructions" >'.__('Instructions', "survey-maker").'</button>                                                
                                            </div>
                                        </div>';
                            $content .= '<div class="form-group row">';
                                $content .= '<div class="col-sm-3">';
                                    $content .= '<label for="ays_survey_aweber_client_id">'. __('Client ID', "survey-maker") .'</label>';
                                $content .= '</div>';
                                $content .= '<div class="col-sm-9">';
                                    $content .= '<input type="text" class="ays-text-input">';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<hr>';
                            $content .= '<div class="form-group row">';
                                $content .= '<div class="col-sm-3">';
                                    $content .= '<label for="ays_survey_aweber_client_secret">'. __('Client Secret', "survey-maker") .'</label>';
                                $content .= '</div>';
                                $content .= '<div class="col-sm-9">';
                                    $content .= '<input type="text" class="ays-text-input">';							
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<hr>';
                            $content .= '<div class="form-group row">';
                                $content .= '<div class="col-sm-3"></div>';
                                $content .= '<div class="col-sm-9">';
                                    $content .= '<button type="submit" class="btn btn-outline-info">'.__("Connect", "survey-maker").'</button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['aweber'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== Aweber end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== MailPoet start =====

        // MailPoet integration

        // MailPoet integration in survey page content
        public function ays_survey_page_mailpoet_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mail_poet.png';
            $title = __('MailPoet Settings',"survey-maker");
            
            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_mailpoet">'. __('Enable MailPoet',"survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1">';
                                $content .= '
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_mailpoet_list">'. __('MailPoet list',"survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">';                                
                            $content .= '<select class="ays-text-input">';
                                $content .= '<option value="" >'. __( "Select list", "survey-maker" ) .'</option>';                                    
                            $content .= '</select>';
                        $content .= '</div>
                                </div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['mailpoet'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // MailPoet integration / settings page

        // MailPoet integration in General settings page content
        public function ays_settings_page_mailpoet_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mail_poet.png';
            $title = __( 'MailPoet', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<blockquote style="width: 50%; margin: initial;">'.__('To choose a list, please go to the <strong>Integration</strong> tab of the given survey.' , "survey-maker").'</blockquote>'; 
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['mailpoet'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== MailPoet end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== MyCred start =====

        // MyCred integration / settings page

        // MyCred integration in General settings page content
        public function ays_mycred_settings_page_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mycred_icon.png';
            $title = __( 'MyCred', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<blockquote style="width: 50%; margin: initial;">';
                        $content .= __( "Setup your first point type and go to the Hook page. Choose Survey Maker from the Available Hooks list.", "survey-maker" );
                    $content .= '<br>';
                        $content .= __( " Configure the settings and save the hook.", "survey-maker"  );
                    $content .= '</blockquote>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['mycred'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== MyCred end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////
    
    // ===== Klaviyo start =====

        // Klaviyo integration

        // Klaviyo integration in survey page content
        public function ays_survey_page_klaviyo_content( $integrations, $args ){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/klaviyo-logo.png';
            $title = __('Klaviyo Settings',"survey-maker");
            
            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_klaviyo">'. __('Enable Klaviyo', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_klaviyo_list">'. __('Select List', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="ays-text-input">
                                            <option>Select list</option>
                                        </select>
                                    </div>';
                    $content .= '</div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['klaviyo'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Klaviyo integration / settings page

        // Klaviyo integration in General settings page content
        public function ays_settings_page_klaviyo_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/klaviyo-logo.png';
            $title = __( 'Klaviyo', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 ays-pro-features-v2-main-box ays-pro-features-v2-main-box-small">';
                $content .= '<div class="ays-pro-features-v2-small-buttons-box">';
                    $content .= '<div class="ays-pro-features-v2-video-button"></div>';
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker" target="_blank" class="ays-pro-features-v2-upgrade-button">';
                            $content .= '<div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="'.esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/icons/pro-features-icons/Locked_24x24.svg"></div>';
                            $content .= '<div class="ays-pro-features-v2-upgrade-text">';
                                $content .= __("Upgrade to Agency" , "survey-maker");
                            $content .= '</div>';
                        $content .= '</a>';
                    $content .= '</div>';
                    $content .= '<hr>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">                        
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_Klaviyo_api_key">'. __('API Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>';
                            $content .= '<blockquote>';
                                $content .= __( "You can get your API key from your Account.", "survey-maker" );
                            $content .= '</blockquote>';
                            $content .= '
                                    </div>
                                </div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['klaviyo'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== Klaviyo end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Integration calls for admin dashboard start =====

        // Mailchimp - Get mailchimp lists
        public function ays_get_mailchimp_lists($username, $api_key){
            if($username == ""){
                return array(
                    'total_items' => 0
                );
            }
            if($api_key == ""){
                return array(
                    'total_items' => 0
                );
            }

            $explode = explode("-",$api_key);
            $api_prefix = isset( $explode[1] ) ? $explode[1] : '';
            
            if(! $api_prefix ){
                return array(
                    'total_items' => 0
                );
            }
            $headers = array(
                "headers" => array(
                    "Authorization" => 'Basic ' . base64_encode( $username.':'.$api_key ),            
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                ),
            );
            $headers['sslverify'] = false;

            $url = "https://".$api_prefix.".api.mailchimp.com/3.0/lists";
            $response = wp_remote_get($url, $headers);        
            $body     = wp_remote_retrieve_body( $response );
            
            if( empty( $body ) ){
                return array(
                    'total_items' => 0
                );
            }else{
                $body = json_decode($body,true);
            }
            return $body;


            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => "https://".$api_prefix.".api.mailchimp.com/3.0/lists",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => "",
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 30,
            //     CURLOPT_SSL_VERIFYPEER => false,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => "GET",
            //     CURLOPT_USERPWD => "$username:$api_key",
            //     CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            //     CURLOPT_HTTPHEADER => array(
            //         "Content-Type: application/json",
            //         "cache-control: no-cache"
            //     ),
            // ));

            // $response = curl_exec($curl);
            // $err = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            //     return array(
            //         'Code'       => 0,
            //         'cURL Error' => $err
            //     );
            // } else {
            //     return json_decode($response, true);
            // }
        }
        
        // Campaign Monitor - Get subscribe lists
        public function ays_get_monitor_lists($client, $api_key){
            if ($client == "" || $api_key == "") {
                return array(
                    'Code' => 0
                );
            }

            $body = '';
            $headers = array(
                "headers" => array(    
                    "Authorization" => 'Basic ' . base64_encode( $api_key.":x" ),            
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                )
            );

            $headers['sslverify'] = false;

            $url = "https://api.createsend.com/api/v3.2/clients/".$client."/lists.json";
            $response = wp_remote_get($url, $headers);
            $body     = wp_remote_retrieve_body( $response );
            
            if( empty( $body ) ){
                return array(
                    'Code' => 0
                );
            }else{
                $body = json_decode($body,true);
            }
            return $body;


            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => "https://api.createsend.com/api/v3.2/clients/$client/lists.json",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => "",
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 30,
            //     CURLOPT_SSL_VERIFYPEER => false,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => "GET",
            //     CURLOPT_USERPWD => "$api_key:x",
            //     CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            //     CURLOPT_HTTPHEADER => array(
            //         "Content-Type: application/json",
            //         "cache-control: no-cache"
            //     ),
            // ));

            // $response = curl_exec($curl);
            // $err = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            //     return array(
            //         'Code'       => 0,
            //         'cURL Error' => $err
            //     );
            // } else {
            //     return json_decode($response,true);
            // }
        }

        // Slack - Get channels
        public function ays_get_slack_conversations( $token ) {
            if ($token == "") {
                return array(
                    'Code' => 0
                );
            }

            // $body = '';
            // $headers = array(
            //     "headers" => array(
            //         "Content-Type"  => "application/x-www-form-urlencoded",
            //         "cache-control" => "no-cache",
            //         )
            // );
            // $headers['sslverify'] = false;

            // $url = "https://slack.com/api/conversations.list?token=".$token;
            
            // $response = wp_remote_get( $url, $headers );        
            // $body     = wp_remote_retrieve_body( $response );
            
            // if( empty( $body ) ){
            //     return false;
            // }else{
            //     $body = json_decode($body,true);
            // }
            // return $body;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://slack.com/api/conversations.list",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Bearer $token",
                    "cache-control: no-cache"
                ),
            ));
    
            $response = curl_exec($curl);
            $err      = curl_error($curl);
    
            curl_close($curl);
    
            if ($err) {
                return array(
                    'Code'       => 0,
                    'cURL Error' => $err
                );
            } else {
                return json_decode($response, true)['channels'];
            }
        }

        // ActiveCampaign - Get subscribe lists
        public function ays_get_active_camp_data( $data, $url, $api_key ) {
            if(empty($data) || $url == '' || $api_key == ''){
                return array(
                    "Code" => 0
                );
            }
            $body = '';

            $headers = array(
                "headers" => array(
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                    "Api-Token"     => $api_key
                )
            );
            $headers['sslverify'] = false;

            $url = $url."/api/3/". $data ."?limit=1000";

            $response = wp_remote_get($url, $headers);      
            $body     = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return array(
                    "Code" => 0
                );
            }else{
                $body = json_decode($body,true);
            }
            return $body;


            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL            => "$url/api/3/$data",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING       => "",
            //     CURLOPT_MAXREDIRS      => 10,
            //     CURLOPT_TIMEOUT        => 30,
            //     CURLOPT_SSL_VERIFYPEER => false,
            //     CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST  => "GET",
            //     CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            //     CURLOPT_HTTPHEADER     => array(
            //         "Content-Type: application/json",
            //         "cache-control: no-cache",
            //         "Api-Token: $api_key"
            //     ),
            // ));

            // $response = curl_exec($curl);
            // $err      = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            //     return array(
            //         'Code'       => 0,
            //         'cURL Error' => $err
            //     );
            // } else {
            //     return json_decode($response, true);
            // }
        }

        // SendGrid - Get templates
        public static function ays_survey_get_sendgrid_templates( $api_key ){
            if( $api_key == '' ){
                return array(
                    "Code" => 0
                );
            }
            $body = '';

            $headers = array(
                "headers" => array(
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                    "Authorization" => "Bearer " . $api_key
                )
            );
            $headers['sslverify'] = false;

            $url = "https://api.sendgrid.com/v3/templates";

            $response = wp_remote_get($url, $headers);      
            $body     = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return array(
                    "Code" => 0
                );
            }else{
                $body = json_decode($body,true);
            }
            return $body;


            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //   CURLOPT_URL => "https://api.sendgrid.com/v3/templates",
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_ENCODING => "",
            //   CURLOPT_MAXREDIRS => 10,
            //   CURLOPT_TIMEOUT => 30,
            //   CURLOPT_SSL_VERIFYPEER => false,
            //   // CURLOPT_FOLLOWLOCATION => true,
            //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //   CURLOPT_CUSTOMREQUEST => "GET",
            //   CURLOPT_HTTPHEADER => array(
            //     "Content-Type: application/json",
            //     "Authorization: Bearer ".$api_key
            //   ),
            // ));

            // $response = curl_exec($curl);
            // $err      = curl_error($curl);
            // curl_close($curl);
            // if ($err) {
            //     echo "cURL Error #:" . $err;
            // } else {
            //     return json_decode($response, true);
            // }
        }

        // Mad Mimi - Get lists
        public function ays_survey_mad_mimi_lists($user_name, $api_key){
            $bad_request = array();

            if ($user_name == "" || $api_key == "") {
                return $bad_request;
            }

            $url = "https://madmimi.com/api/v3/subscriberLists?";
            $data = array(
                "username" => $user_name,
                "api_key"  => $api_key
            );

            $url .= http_build_query($data);

            $headers = array(
                "headers" => array(
                    "Accept"  => "application/json",
                ),
            );

            $api_call = wp_remote_get( $url , $headers);
            if(wp_remote_retrieve_response_code( $api_call ) == 200){
                $subscriber_lists = wp_remote_retrieve_body($api_call);
                if($subscriber_lists == ""){
                    return $bad_request;
                }
                else{
                    $response = json_decode($subscriber_lists , true);
                    $lists = isset($response['subscriberLists']) ? $response['subscriberLists'] : array();
                    return $lists;
                }
            }
            else{
                return $bad_request;
            }
        }

        // GetResponse
        public function ays_survey_getResposne_lists($api_key){
            $bad_request = array();
            if($api_key == ""){
                return $bad_request;
            }

            $url = "https://api.getresponse.com/v3/campaigns";
            $headers = array(
                "headers" => array(
                    "X-Auth-Token" => "api-key ".$api_key,
                )
            );
            $api_call = wp_remote_get($url , $headers);
            $response = wp_remote_retrieve_body( $api_call );
            $new_response = array();
            if($response != ""){
                $new_response = json_decode($response , true);
            }
            if(wp_remote_retrieve_response_code( $api_call ) == 200){
                $new_response['status'] = true;
            }
            else{
                $new_response['status'] = false;
            }
            return $new_response;
        }
        
        // ConvertKit Lists
        public function ays_get_convertKit_forms( $api_key ) {
            if ($api_key == "") {
                return array();
            }

            $url = "https://api.convertkit.com/v3/forms?api_key=".$api_key;
            $api_call = wp_remote_get($url);
            $body = array();
            $response = array();
            if ( wp_remote_retrieve_response_code( $api_call ) === 200 ){
                $body = wp_remote_retrieve_body( $api_call );
                if($body != ""){
                    $body = json_decode($body , true);
                    $response['forms'] = isset($body['forms']) && !empty($body['forms']) ? $body['forms'] : array();
                    $response['status'] = true;
                }else{
                    $response['forms'] = array();
                    $response['status'] = false;
                }
            }else{
                $response['forms'] = array();
                $response['status'] = false;
            }
            return $response;

        }
        
        // Sendinblue Lists
        public function ays_survey_get_sendinblue_lists( $api_key ) {
            if ($api_key == "") {
                return array();
            }

            $url = "https://api.sendinblue.com/v3/contacts/lists";

            $settings = array(
                'blocking'    => true,
                'headers'     => array(
                    "content-type" => "application/json",
                    "api-key"      => $api_key
                ),
                'sslverify'   => true,
            );

            $api_call = wp_remote_get($url, $settings);
            $body = array();
            $response = array();
            
            
            if ( wp_remote_retrieve_response_code( $api_call ) === 200 ){
                $body = wp_remote_retrieve_body( $api_call );
                if($body != ""){
                    $body = json_decode($body , true);
                    $response['lists'] = isset($body['lists']) && !empty($body['lists']) ? $body['lists'] : array();
                    $response['status'] = true;
                }else{
                    $response['lists'] = array();
                    $response['status'] = false;
                }
            }else{
                $response['lists'] = array();
                $response['status'] = false;
            }
            return $response;

        }    
        
        // MailerLite Lists
        public function ays_survey_get_mailerLite_groups( $api_key ) {
            if ($api_key == "") {
                return array();
            }

            $url = "https://api.mailerlite.com/api/v2/groups";

            $settings = array(
                'blocking'    => true,
                'headers'     => array(
                    "content-type"        => "application/json",
                    "X-MailerLite-ApiKey" => $api_key
                ),
                'sslverify'   => true,
            );

            $api_call = wp_remote_get($url, $settings);
            $body = array();
            $response = array();
            if ( wp_remote_retrieve_response_code( $api_call ) === 200 ){
                $body = wp_remote_retrieve_body( $api_call );
                if($body != ""){
                    $body = json_decode($body , true);
                    $response['groups'] = isset($body) && !empty($body) ? $body : array();
                    $response['status'] = true;
                }else{
                    $response['groups'] = array();
                    $response['status'] = false;
                }
            }else{
                $response['groups'] = array();
                $response['status'] = false;
            }
            return $response;

        }    
    // ===== Integration calls for admin dashboard end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Front end calls start =====
    
        // Mailchimp
        public function ays_add_mailchimp_transaction($username, $api_key, $list_id, $args){
            if($username == "" || $api_key == ""){
                return false;
            }
            
            $email = isset($args['email']) ? $args['email'] : null;
            $fname = isset($args['fname']) ? $args['fname'] : "";
            $lname = isset($args['lname']) ? $args['lname'] : "";
            
            $api_prefix = explode("-",$api_key)[1];
            
            $fields = array(
                "email_address" => $email,
                "status" => "subscribed",
                "merge_fields" => array(
                    "FNAME" => $fname,
                    "LNAME" => $lname
                )
            );

            $data = array(
                'sslverify' => false,
                'body' => json_encode( $fields ),
                "headers" => array(
                    "Authorization" => 'Basic ' . base64_encode( $username.':'.$api_key ),
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                    "Api-Token"     => $api_key
                )
            );

            $url = "https://".$api_prefix.".api.mailchimp.com/3.0/lists/".$list_id."/members/";

            $response = wp_remote_post($url, $data);

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }

            $body = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return false;
            }else{
                $body = json_decode($body,true);
            }
            return $body;

            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => "https://".$api_prefix.".api.mailchimp.com/3.0/lists/".$list_id."/members/",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => "",
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 30,
            //     CURLOPT_SSL_VERIFYPEER => false,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_USERPWD => "$username:$api_key",
            //     CURLOPT_CUSTOMREQUEST => "POST",
            //     CURLOPT_POSTFIELDS => json_encode($fields),
            //     CURLOPT_HTTPHEADER => array(
            //         "Content-Type: application/json",
            //         "cache-control: no-cache"
            //     ),
            // ));

            // $response = curl_exec($curl);
            
            // $err = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            //     return "cURL Error #: " . $err;
            // } else {
            //     return $response;
            // }
        }

        // Campaign Monitor
        public function ays_add_monitor_transaction( $client, $api_key, $list_id, $args ) {
            if ($client == "" || $api_key == "") {
                return false;
            }

            $default_options = array(
                "CustomFields" => array(
                    array(
                        "Key"   => "from",
                        "Value" => $this->plugin_name
                    ),
                    array(
                        "Key"   => "date",
                        "Value" => date("Y/m/d", current_time('timestamp'))
                    )
                ),
                "Resubscribe"                            => true,
                "RestartSubscriptionBasedAutoresponders" => true,
                "ConsentToTrack"                         => "Yes"
            );

            $default_options = array_merge($args, $default_options);
            $default_options = json_encode($default_options);

            $auth = base64_encode( $api_key . ':x' );
            
            $data = array(
                'sslverify' => false,
                'body' => $default_options,
                "headers" => array(
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                    'Authorization' => "Basic $auth"
                )
            );

            $url = "https://api.createsend.com/api/v3.2/subscribers/$list_id.json";

            $response = wp_remote_post($url, $data);      

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }

            $body = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return false;
            }else{
                $body = json_decode($body,true);
            }
            return $body;

            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL            => "https://api.createsend.com/api/v3.2/subscribers/$list_id.json",
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING       => "",
            //     CURLOPT_MAXREDIRS      => 10,
            //     CURLOPT_TIMEOUT        => 30,
            //     CURLOPT_SSL_VERIFYPEER => false,
            //     CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_USERPWD        => "$api_key:x",
            //     CURLOPT_CUSTOMREQUEST  => "POST",
            //     CURLOPT_POSTFIELDS     => json_encode(array_merge($args, $default_options)),
            //     CURLOPT_HTTPHEADER     => array(
            //         "Content-Type: application/json",
            //         "cache-control: no-cache"
            //     ),
            // ));

            // $response = curl_exec($curl);

            // $err = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            //     return "cURL Error #: " . $err;
            // } else {
            //     return $response;
            // }
        }

        // ActiveCampaign
        public function ays_add_active_camp_transaction( $url, $api_key, $args, $list_id, $automation_id, $data = "contact" ) {
            if ($url == "" || $api_key == "") {
                return false;
            }

            // $datas = array(
            //     "headers" => array(
            //         "Content-Type"  => "application/json",
            //         "cache-control" => "no-cache",
            //         "Api-Token" => $api_key,
            //     ),
            //     'sslverify' => false,
            //     // 'method' => 'POST',
            //     'timeout' => 45,
            //     // 'redirection' => 10,
            //     'httpversion' => '1.0',
            //     // "cache-control" => "no-cache",
            //     'cookies'     => array(),
            //     // "encoding"       => "",
            //     // 'blocking'    => false,
            //     // "returntransfer" => true,
            //     'body' => json_encode( array(
            //         "$data" => $args
            //     ) ),
            // );

            $final_url = "$url/api/3/{$data}s";

            if ($data == "contact") {
                $final_url = "$url/api/3/{$data}/sync";
            }

            // $response = wp_remote_post($url, $datas);

            // if ( is_wp_error( $response ) ) {
            //     $error_message = $response->get_error_message();
            //     return $error_message;
            // }
            
            // $body = wp_remote_retrieve_body( $response );
            
            // if( empty( $body )){
            //     return false;
            // }else{
            //     $body = json_decode($body, true);
            //     $res = $body["$data"];
            // }

            // if ($data == "contactList" || $data == "contactAutomation") {
            //     return $res;
            // } else {
            //     if ($list_id) {
            //         $list_args = array(
            //             "list"    => $list_id,
            //             "contact" => $res['id'],
            //             "status"  => 1
            //         );

            //         return $this->ays_add_active_camp_transaction($url, $api_key, $list_args, $list_id, $automation_id, 'contactList');
            //     }
            //     if ($automation_id) {
            //         $automation_args = array(
            //             "automation" => $automation_id,
            //             "contact"    => $res['id']
            //         );

            //         return $this->ays_add_active_camp_transaction($url, $api_key, $automation_args, $list_id, $automation_id, 'contactAutomation');
            //     }
            //     return $res;
            // }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL            => $final_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => json_encode(array("$data" => $args)),
                CURLOPT_HTTPHEADER     => array(
                    "Content-Type: application/json",
                    "cache-control: no-cache",
                    "Api-Token: $api_key"
                ),
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            $res = $err ? array(
                'Code'       => 0,
                'cURL Error' => $err
            ) : json_decode($response, true)["$data"];

            if ($data == "contactList" || $data == "contactAutomation") {
                return $res;
            } else {
                if ($list_id) {
                    $list_args = array(
                        "list"    => $list_id,
                        "contact" => $res['id'],
                        "status"  => 1
                    );

                    $this->ays_add_active_camp_transaction($url, $api_key, $list_args, $list_id, $automation_id, 'contactList');
                }
                if ($automation_id) {
                    $automation_args = array(
                        "automation" => $automation_id,
                        "contact"    => $res['id']
                    );

                    $this->ays_add_active_camp_transaction($url, $api_key, $automation_args, $list_id, $automation_id, 'contactAutomation');
                }

                return $res;
            }

        }

        // Zapier
        public function ays_add_zapier_transaction( $hook, $data ) {
            if ($hook == "") {
                return false;
            }

            $zapier_data = array(
                'sslverify' => false,
                'body' => json_encode( array(
                    "AysSurvey" => $data
                ) ),
                "headers" => array(
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                )
            );

            $url = $hook;

            $response = wp_remote_post($url, $zapier_data);

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }
            
            $body = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return false;
            }else{
                $body = json_decode($body,true);
            }
            return $body;
            
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //     CURLOPT_URL            => $hook,
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING       => "",
            //     CURLOPT_MAXREDIRS      => 10,
            //     CURLOPT_TIMEOUT        => 30,
            //     CURLOPT_SSL_VERIFYPEER => false,
            //     CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST  => "POST",
            //     CURLOPT_POSTFIELDS     => json_encode(array("AysQuiz" => $data)),
            //     CURLOPT_HTTPHEADER     => array(
            //         "Content-Type: application/json",
            //         "cache-control: no-cache"
            //     ),
            // ));

            // $response = curl_exec($curl);

            // $err = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            //     return "cURL Error #: " . $err;
            // } else {
            //     return $response;
            // }
        }

        // Slack
        public function ays_add_slack_transaction( $token, $channel, $data, $title = "" ) {
            if ($token == "" || $channel == "") {
                return false;
            }
            
            $text = __("Your `" . stripslashes($title) . "` Survey was survived by", $this->plugin_name) . "\n";
            foreach ( $data as $key => $value ) {
                if($value == ""){
                    continue;
                }
                $text .= __(ucfirst($key) . ":", $this->plugin_name) . " `$value`\n";
            }
            
            $args = array(
                "channel"  => $channel,
                "text"     => $text,
                "username" => "Social Survey"
            );
            
            // $data = array(
            //     'sslverify' => false,
            //     'body' => json_encode( $args ),
            //     "headers" => array(
            //         "Content-Type"  => "application/json; charset=utf-8",
            //         "cache-control" => "no-cache",
            //         "Authorization" => "Bearer $token",
            //     )
            // );

            // $url = "https://slack.com/api/chat.postMessage";

            // $response = wp_remote_post($url, $data);      
            
            // if ( is_wp_error( $response ) ) {
            //     $error_message = $response->get_error_message();
            //     return $error_message;
            // }
            
            // $body = wp_remote_retrieve_body( $response );
            
            // if( empty( $body )){
            //     return false;
            // }else{
            //     $body = json_decode($body,true);
            // }
            // return $body;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://slack.com/api/chat.postMessage",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => json_encode($args),
                CURLOPT_HTTPHEADER     => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer $token",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);
            
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #: " . $err;
            } else {
                return $response;
            }
        }

        // SendGrid
        public function ays_add_sendgrid_transaction( $api_key, $args ){

            if( $api_key == '' ){
                return array(
                    "Code" => 0
                );
            }
            $body = '';

            $fields = array(
                "personalizations" => array(
                    array(
                        "to" => array(
                            array(
                                "email" => $args['email_to'],
                                "name"  => $args['name']
                            )
                        )
                    )
                ),
                "from" => array(
                    "email" => $args['email_from'],
                    "name"  => $args['email_name']
                ),
                "reply_to" => array(
                    "email" => $args['reply_to_email'],
                    "name"  => $args['reply_to_name']
                ),
                "subject" => $args['subject'],
                "content" => array(
                    array(
                        "type" => "text/html",
                        "value"  => "<p></p>"
                    )
                ),
                "template_id" => $args['template']
            );

            $headers = array(
                'sslverify' => false,
                'body' => json_encode( $fields ),
                "headers" => array(
                    "Content-Type"  => "application/json",
                    "cache-control" => "no-cache",
                    "Authorization" => "Bearer " . $api_key
                )
            );

            $url = "https://api.sendgrid.com/v3/mail/send";

            $response = wp_remote_post($url, $headers);      
            $body     = wp_remote_retrieve_body( $response );

            return $body;
        }

        // Mad mimi
        public function ays_survey_add_mad_mimi_email($data){
            if(empty($data)){
                return false;
            }

            $mad_mimi_user_name = isset($data['mad_mimi_user_name']) && $data['mad_mimi_user_name'] != "" ? $data['mad_mimi_user_name'] : "";
            $api_key            = isset($data['api_key']) && $data['api_key'] != "" ? $data['api_key'] : "";
            $list               = isset($data['list']) && $data['list'] != "" ? $data['list'] : "";
            $user_email         = isset($data['user_email']) && $data['user_email'] != "" ? $data['user_email'] : "";
            $user_first_name    = isset($data['user_first_name']) && $data['user_first_name'] != "" ? $data['user_first_name'] : "";
            $user_last_name     = isset($data['user_last_name']) && $data['user_last_name'] != "" ? $data['user_last_name'] : "";

            if($mad_mimi_user_name == "" || $api_key == "" || $list == ""){
                return false;
            }

            $url = "https://api.madmimi.com/audience_lists/".$list."/add?";

            $data = array(
                "username"   => $mad_mimi_user_name,
                "api_key"    => $api_key,
                "email"      => $user_email,
                "first_name" => $user_first_name,
                "last_name"  => $user_last_name
            );

            $url .= http_build_query($data);

            $headers = array(
                "headers" => array(
                    "Accept"  => "application/json",
                )
            );

            $api_call = wp_remote_post( $url , $headers);
            if(wp_remote_retrieve_response_code( $api_call ) == 200){
                $result = wp_remote_retrieve_body($api_call);
                return $result;
            }else{
                return false;
            }
        }

        // GetResponse
        public function ays_survey_add_getResponse_contact($data){
            if(empty($data)){
                return false;
            }
            
            $api_key = isset($data['api_key']) && $data['api_key'] != "" ? $data['api_key'] : "";
            $list_id = isset($data['list_id']) && $data['list_id'] != "" ? $data['list_id'] : "";
            if($api_key == "" || $list_id == ""){
                return false;
            }
            $user_email = isset($data['email']) && $data['email'] != "" ? $data['email'] : "";
            $user_fname = isset($data['fname']) && $data['fname'] != "" ? $data['fname'] : "";
            $user_lname = isset($data['lname']) && $data['lname'] != "" ? $data['lname'] : "";

            $url = "https://api.getresponse.com/v3/contacts";
            $headers = array(
                "headers" => array(
                    "X-Auth-Token" => "api-key ".$api_key
                ),
                "body"    => array(
                    "name" => $user_fname." ".$user_lname,
                    "campaign" => array(
                        "campaignId" => $list_id
                    ),
                    "email" => $user_email
                )
            );
            $api_call = wp_remote_post($url , $headers);

            $response = wp_remote_retrieve_body($api_call);
            if(wp_remote_retrieve_response_code($api_call) != 200){
                return false;
            }
        }

        // ConvertKit
        public function ays_survey_convertKit_add_user($data) {
            if (empty($data)) {
                return false;
            }

            $api_key = isset($data['api_key']) && $data['api_key'] != '' ? $data['api_key'] : '';
            $convertKit_fname   = (isset($data['fname']) && $data['fname'] != "") ? $data['fname'] : "";
            $convertKit_lname   = (isset($data['lname']) && $data['lname'] != "") ? $data['lname'] : "";
            $convertKit_email   = (isset($data['email']) && $data['email'] != "") ? $data['email'] : "";
            $convertKit_form_id = (isset($data['form_id']) && $data['form_id'] != "") ? $data['form_id'] : "";

            if($api_key == "" || $convertKit_form_id == "" || $convertKit_email == ""){
                return false;
            }

            $url = "https://api.convertkit.com/v3/forms/".$convertKit_form_id."/subscribe?";
            $url .= http_build_query(array(
                    "email"      => $convertKit_email,
                    "api_key"    => $api_key,
                    "first_name" => $convertKit_fname
                )
            );

            $api_call = wp_remote_post($url);
        }

        // Sendinblue add contact to a list
        public function ays_survey_sendinblue_add_contact_to_list($data) {
            if (empty($data)) {
                return false;
            }

            $sendinblue_api_key = isset($data['api_key']) && $data['api_key'] != '' ? $data['api_key'] : '';
            $sendinblue_fname   = (isset($data['fname']) && $data['fname'] != "") ? $data['fname'] : "";
            $sendinblue_lname   = (isset($data['lname']) && $data['lname'] != "") ? $data['lname'] : "";
            $sendinblue_email   = (isset($data['email']) && $data['email'] != "") ? $data['email'] : "";
            $sendinblue_list_id = (isset($data['list_id']) && $data['list_id'] != "") ? intval($data['list_id']) : "";
            
            if($sendinblue_api_key == "" || $sendinblue_list_id == "" || $sendinblue_email == ""){
                return false;
            }

            $url = "https://api.sendinblue.com/v3/contacts";
            $headers = array(
                'content-type' => 'application/json',
                'api-key'      => $sendinblue_api_key
            );
            $body = array(
                "attributes" => array(
                    "FIRSTNAME" => $sendinblue_fname,
                    "LASTNAME"  => $sendinblue_lname
                ),
                "updateEnabled" => false,
                "email"   => $sendinblue_email,
                "listIds" => array($sendinblue_list_id)
            );
            
            $settings = array(
                "headers" => $headers,
                "body"    => json_encode($body)
            );

            $api_call = wp_remote_post($url, $settings);
        }

        // MalerLite add contact to a group
        public function ays_survey_malerLite_add_contact_to_group($data) {
            if (empty($data)) {
                return false;
            }

            $malerLite_api_key  = isset($data['api_key'])   && $data['api_key'] != '' ? $data['api_key'] : '';
            $malerLite_name     = (isset($data['name'])     && $data['name'] != "") ? $data['name'] : "";
            $malerLite_email    = (isset($data['email'])    && $data['email'] != "") ? $data['email'] : "";
            $malerLite_group_id = (isset($data['group_id']) && $data['group_id'] != "") ? intval($data['group_id']) : "";
            
            if($malerLite_api_key == "" || $malerLite_group_id == "" || $malerLite_email == ""){
                return false;
            }

            $url = "https://api.mailerlite.com/api/v2/groups/".$malerLite_group_id."/subscribers";
            $headers = array(
                'content-type'        => 'application/json',
                'X-MailerLite-ApiKey' => $malerLite_api_key
            );
            $body = array(
                "name"    => $malerLite_name,
                "email"   => $malerLite_email
            );
            
            $settings = array(
                "headers" => $headers,
                "body"    => json_encode($body)
            );
            $api_call = wp_remote_post($url, $settings);
            
        }

         // Payments finish
         public function ays_front_end_payment_finish( $id ){
            if(!session_id()) {
                session_start();
            }
            if(isset($_SESSION)){
                if(isset($_SESSION['ays_survey_paypal_purchase']) && isset( $_SESSION['ays_survey_paypal_purchase'][$id] ) ){
                    $_SESSION['ays_survey_paypal_purchase'][$id] = false;
                    unset($_SESSION['ays_survey_paypal_purchase'][$id]);
                }
                if(array_key_exists('ays_survey_paypal_purchase', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_paypal_purchase'])){
                    $_SESSION['ays_survey_paypal_purchase'][$id] = false;
                    unset($_SESSION['ays_survey_paypal_purchase'][$id]);
                }
                if(isset($_SESSION['ays_survey_paypal_purchased_item']) && isset( $_SESSION['ays_survey_paypal_purchased_item'][$id] ) ){
                    $_SESSION['ays_survey_paypal_purchased_item'][$id]['status'] = 'finished';
                    unset($_SESSION['ays_survey_paypal_purchased_item'][$id]);
                }
                if(array_key_exists('ays_survey_paypal_purchased_item', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_paypal_purchased_item'])){
                    $_SESSION['ays_survey_paypal_purchased_item'][$id]['status'] = 'finished';
                    unset($_SESSION['ays_survey_paypal_purchased_item'][$id]);
                }

                if(isset($_SESSION['ays_survey_stripe_purchase']) && isset( $_SESSION['ays_survey_stripe_purchase'][$id] ) ){
                    $_SESSION['ays_survey_stripe_purchase'][$id] = false;
                    unset($_SESSION['ays_survey_stripe_purchase'][$id]);
                }
                if(array_key_exists('ays_survey_stripe_purchase', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_stripe_purchase'])){
                    $_SESSION['ays_survey_stripe_purchase'][$id] = false;
                    unset($_SESSION['ays_survey_stripe_purchase'][$id]);
                }

                if(isset($_SESSION['ays_survey_all_purchases']) && isset( $_SESSION['ays_survey_all_purchases'][$id] ) ){
                    $_SESSION['ays_survey_stripe_purchase'][$id] = false;
                    unset($_SESSION['ays_survey_stripe_purchase'][$id]);
                }
                if(array_key_exists('ays_survey_all_purchases', $_SESSION) && array_key_exists($id, $_SESSION['ays_survey_all_purchases'])){
                    $_SESSION['ays_survey_all_purchases'][$id] = false;
                    unset($_SESSION['ays_survey_all_purchases'][$id]);
                }
            }
        }

    // ===== Front end calls end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ==== Google Sheet integration API Calls start ====

        // Google sheet == Create == 
        public function ays_survey_get_google_sheet_id( $data ) {
            error_reporting(0);
            if (empty($data)) {
                return array(
                    'Code' => 0
                );
            }
            $new_token = '';
            $get_this_quiz = array();
            $question = '';
            $refresh_token = isset($data['refresh_token']) && $data['refresh_token'] != '' ? $data['refresh_token'] : '';
            $survey_title  = isset($data['survey_title']) && $data['survey_title'] != '' ? $data['survey_title'] : '';
            $all_questions = isset($data['all_questions']) && !empty($data['all_questions']) ? $data['all_questions'] : array();
            if($refresh_token != ''){
                $new_token = self::ays_survey_get_refreshed_token($data);
            }

            $url = "https://sheets.googleapis.com/v4/spreadsheets?access_token=".$new_token;
            // Add to sheet resent values
            $properties = array(
                "properties" => array(
                    "title" => $survey_title
                ),
                "sheets" => array(
                    "data" => array(
                        "rowData" => array(
                            "values" => array(
                                array(
                                    "userEnteredValue" => array(
                                        "stringValue" => 'User',
                                    )
                                ),
                                array(
                                    "userEnteredValue" => array(
                                        "stringValue" => "User IP"
                                    )
                                ),
                                array(
                                    "userEnteredValue" => array(
                                        "stringValue" => "User Email"
                                    )
                                ),
                                array(
                                    "userEnteredValue" => array(
                                        "stringValue" => "Submition Date"
                                    )
                                )
                            )
                        )
                    )
                )
            );

            foreach( $all_questions as $q_id => $q_title ) {
                $properties['sheets']['data']['rowData']['values'][] = array(
                    "userEnteredValue" => array(
                        "stringValue" => $q_title
                    )
                );
            }

            // ===== API CALL =====
            $url = "https://sheets.googleapis.com/v4/spreadsheets?access_token=".$new_token;
            $properties = json_encode($properties);
            $data = array(
                'sslverify' => false,
                'body' => $properties,
                "headers" => array(
                    "Content-Type"  => "application/json; charset=utf-8",
                    "cache-control" => "no-cache"
                    // "Authorization" => "Bearer $token",
                )
            );

            $response = wp_remote_post($url, $data);      
            
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }
            
            $body = wp_remote_retrieve_body( $response );
            $spreadsheet_id = "";
            if( empty( $body )){
                return false;
            }else{
                $body = json_decode($body,true);
                $spreadsheet_id = isset($body['spreadsheetId']) ? $body['spreadsheetId'] : "";
            }
            return $spreadsheet_id;
        }

        // Google sheet == Update == data in dashboard
        public function ays_survey_update_google_spreadsheet( $data ) {
            error_reporting(0);
            if (empty($data)) {
                return array(
                    'Code' => 0
                );
            }
            $new_token = '';
            $get_this_quiz = array();
            $question = '';
            $refresh_token = isset($data['refresh_token']) && $data['refresh_token'] != '' ? $data['refresh_token'] : '';
            $quiz_title    = isset($data['survey_title']) && $data['survey_title'] != '' ? $data['survey_title'] : '';
            $sheet_id      = isset($data['sheet_id']) && $data['sheet_id'] != '' ? $data['sheet_id'] : '';
            $all_questions = isset($data['all_questions']) && !empty($data['all_questions']) ? $data['all_questions'] : array();

            if( $sheet_id == '' ){
                return array(
                    'Code' => 0
                );
            }

            if($refresh_token != ''){
                $new_token = $this->ays_survey_get_refreshed_token($data);
            }

            // Add to sheet resent values
            $properties = array(
                "valueInputOption" => "RAW",
                "data" => array(

                )
            );

            $titles_for_ranges = array(
                "User",
                "User IP",
                "User Email",
                "Submition Date"
            );
            
            foreach( $all_questions as $q_id => $q_title ) {
                $titles_for_ranges[] = $q_title;
            }
            
            $ranges = Survey_Maker_Data::ays_survey_generate_keyword_array( 100 );
            $last_keyboard = 'Y1';
            
            foreach( $titles_for_ranges as $key => $value ) {
                $properties['data'][] = array(
                    "range" => $ranges[$key] . "1",
                    "values" => array(
                        array(
                            $value
                        )
                    )
                );
                $last_keyboard = ( $ranges[$key + 1 ]) . "1";
            }
    
            $url = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheet_id . "/values:batchUpdate";
            $properties = json_encode($properties);
            // ==== API CALL ====
            $data = array(
                'sslverify' => false,
                'body' => $properties,
                "headers" => array(
                    "Content-Type"  => "application/json; charset=utf-8",
                    "cache-control" => "no-cache",
                    "Authorization" => "Bearer ".$new_token
                )
            );

            $response = wp_remote_post($url, $data);            
                        
            $url = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheet_id . "/" . "values" . "/" . $last_keyboard . ":Z1:clear";
            
            $clear_properties = array(
                "range" => $last_keyboard.":Z1"
            );
            $clear_properties = json_encode($clear_properties);
            // ==== API CALL ====
            $data = array(
                'sslverify' => false,
                'body' => $clear_properties,
                "headers" => array(
                    "Content-Type"  => "application/json; charset=utf-8",
                    "cache-control" => "no-cache",
                    "Authorization" => "Bearer ".$new_token
                )
            );

            $response_clear = wp_remote_post($url, $data);
            
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }
            
            $body = wp_remote_retrieve_body( $response );
            $spreadsheet_id = "";
            if( empty( $body )){
                return false;
            }else{
                $body = json_decode($body,true);
                $spreadsheet_id = isset($body['spreadsheetId']) ? sanitize_text_field($body['spreadsheetId']) : "";
            }
            return $spreadsheet_id;
        }

        // Google sheet == Update == in front-end
        public function ays_survey_add_google_sheets($data) {
            if (empty($data)) {
                return false;
            }
            $new_token = '';

            $user       = '';
            $user_ip    = '';
            $user_email = '';
            $submition_date   = '';

            $last_id = isset($data['result_last_id']) && $data['result_last_id'] != '' ? $data['result_last_id'] : '';
            $questions_all_data = (isset($data['questions_all_data']) && !empty($data['questions_all_data'])) ? $data['questions_all_data'] : array();
            $all_questions = (isset($data['all_questions']) && !empty($data['all_questions'])) ? $data['all_questions'] : array();  
            $questions_all_data['all_questions'] = array_keys($all_questions);

            $user_ip    = isset($questions_all_data['user_ip']) && $questions_all_data['user_ip'] != '' ? sanitize_text_field( $questions_all_data['user_ip'] ) : '';
            $user_name  = isset($questions_all_data['user_name']) && $questions_all_data['user_name'] != '' ? esc_attr( $questions_all_data['user_name'] ) : '';
            $user_id = isset( $questions_all_data['user_id'] ) && absint( $questions_all_data['user_id'] ) != 0 ? absint( $questions_all_data['user_id'] ) : get_current_user_id();
            $user = get_userdata( $user_id );
            if( $user !== false ){
                if( $user_id == 0 ){
                    $user_name = __( 'Guest', $this->plugin_name );
                }else{
                    $user_name = $user->data->display_name;
                }
            }else{
                $user_name = '';
            }

            $user_email      = isset($questions_all_data['user_email']) && $questions_all_data['user_email'] != '' ? esc_attr( $questions_all_data['user_email'] ) : '';
            $submission_date = isset($questions_all_data['end_date']) && $questions_all_data['end_date'] != '' ? esc_attr( $questions_all_data['end_date'] ) : '';           

            $sheet_id      = isset($data['sheed_id']) && $data['sheed_id'] != '' ? $data['sheed_id'] : '';
            $refresh_token = isset($data['refresh_token']) && $data['refresh_token'] != '' ? $data['refresh_token'] : '';
            if($refresh_token != ''){
                $new_token = $this->ays_survey_get_refreshed_token($data);
            }
            
            $props_values_arr = array(
                $user_name,
                $user_ip,
                $user_email,
                $submission_date
            );
            
            $get_sheet_ready_data = $this->ays_survey_get_answer_data($questions_all_data, 'google_sheet' );
            
            
            foreach($get_sheet_ready_data as $q_id => $q_title){
                $props_values_arr[] = $q_title;
            }

            $props = array(
                "range" => "A1",
                "majorDimension" => "ROWS",
                "values" => array(
                    $props_values_arr
                )
            );
            // ==== API CALL ====
            $properties = json_encode($props,true);
            $url = "https://sheets.googleapis.com/v4/spreadsheets/".$sheet_id."/values/A1:append?";

            $args = array(
                "valueInputOption" => "RAW",
                "insertDataOption" => "OVERWRITE",
                "responseValueRenderOption" => "FORMATTED_VALUE",
                "responseDateTimeRenderOption" => "SERIAL_NUMBER",
                "access_token" => $new_token
            );
            $url .= http_build_query( $args );
            
            $data_arg = array(
                'sslverify' => false,
                'body' => $properties,
                "headers" => array(
                    "Content-Type"  => "application/json; charset=utf-8",
                    "cache-control" => "no-cache",
                    "Authorization" => "Bearer ".$new_token,
                )
            );

            $response = wp_remote_post($url, $data_arg);      
            
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }
            
            $body = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return false;
            }else{
                $body = json_decode($body,true);
            }
            return $body;
        }

        // Google sheet == Get == refreshed token
        public function ays_survey_get_refreshed_token( $data ){
            if (empty($data)) {
                return array(
                    'Code' => 0
                );
            }
            $token = isset($data['refresh_token']) && $data['refresh_token'] != '' ? $data['refresh_token'] : '';
            $client_id = isset($data['client_id']) && $data['client_id'] != '' ? $data['client_id'] : '';
            $client_secret = isset($data['client_secret']) && $data['client_secret'] != '' ? $data['client_secret'] : '';
            // ==== API CALL ====
            $url = "https://accounts.google.com/o/oauth2/token?grant_type=refresh_token&refresh_token=".$token."&client_id=".$client_id."&client_secret=".$client_secret."&scope=https://www.googleapis.com/auth/spreadsheets";        

            $response = wp_remote_post($url);      
            
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return $error_message;
            }
            
            $body = wp_remote_retrieve_body( $response );
            
            if( empty( $body )){
                return false;
            }
            else{
                $body = json_decode($body,true);
                return $body['access_token'];
            }
        }

        // Googel sheet get spreadsheet id from DB
        public function ays_survey_get_sheet_id($id){
            global $wpdb;
            $sql = "SELECT options FROM {$wpdb->prefix}socialsurv_surveys WHERE id = " . $id;
            $results = $wpdb->get_var( $sql );
            $options = json_decode( $results, true );
            $spreadsheet_id = isset( $options['spreadsheet_id'] ) && $options['spreadsheet_id'] != '' ? $options['spreadsheet_id'] : null;
            return $spreadsheet_id;
        }

        // Googel sheet RefreshToken
        public function GetGoogleUserToken_RefreshToken( $client_id, $redirect_uri, $client_secret, $code ){
            // $url = 'https://www.googleapis.com/oauth2/v4/token';
            $url = 'https://accounts.google.com/o/oauth2/token';

            $curl = curl_init();
            $curlPost = array(
                'grant_type' => 'authorization_code',
                'client_id' => $client_id,
                'code' => $code,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri,
                'scope' => 'https://www.googleapis.com/auth/spreadsheets'
            );

            $curlPost = http_build_query( $curlPost );

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $curlPost,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $new_response = json_decode($response, true);
            $http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

            curl_close($curl);

            if($http_code != 200){
                throw new Exception( __( 'Error: Failed to get token', AYS_QUIZ_NAME ) );
            }

            return $new_response;
        }
        
        // Googel sheet == GET == User info
        public function GetGoogleUserProfileInfo( $access_token ){
            $url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture';

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => NULL,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ". $access_token,
                    "response_type: webapplications",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $new_response = json_decode($response, true);
            $http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

            curl_close($curl);

            if($http_code != 200){
                throw new Exception( __( 'Error: Failed to get user information', AYS_QUIZ_NAME ) );
            }

            return $new_response;
        }

    // ==== Google Sheet integration API Calls end ====

    // Google Sheet get answer data after finish survey
    public function ays_survey_get_answer_data($data, $integration = 'google_sheet' ){
        $answered_questions = $data['answered_questions'];
        $questions_data = $data['questions_data'];
        $question_ids_array = $data['all_questions'];
        $all_new_array = array();
        $all_new_array2 = array();
        $question_data = Survey_Maker_Data::get_question_by_ids(implode(',' , $question_ids_array) , true);
        foreach ($question_ids_array as $key => $qid) {
            $qid = strval($qid);
            $question_type = (isset($questions_data[$qid]['questionType']) && $questions_data[$qid]['questionType'] != '') ? stripslashes( sanitize_text_field( $questions_data[$qid]['questionType'] ) ) : 'radio';
            $all_new_array2[$qid] = '';
            if( isset( $answered_questions[$qid] ) ){
                switch ( $question_type ) {
                    case "radio":
                        if( is_array( $answered_questions[$qid] ) ){
                            if( isset( $answered_questions[$qid]['answer'] ) ){
                                if( intval( $answered_questions[$qid]['answer'] ) == 0 ){
                                    $all_new_array[$qid] = isset( $answered_questions[$qid]['other'] ) && $answered_questions[$qid]['other'] != '' ? $answered_questions[$qid]['other'] : '';
                                }else{
                                    $answer_title = Survey_Maker_Data::get_answer_by_id($answered_questions[$qid]['answer']);
                                    $all_new_array[$qid] = isset( $answer_title->answer ) ? $answer_title->answer : $answer_title;
                                }
                            }else{
                                $all_new_array[$qid] = isset( $answered_questions[$qid]['other'] ) && $answered_questions[$qid]['other'] != '' ? $answered_questions[$qid]['other'] : '';
                            }
                        }else{
                            if( $answered_questions[$qid] != '' ){
                                $answer_title = Survey_Maker_Data::get_answer_by_id($answered_questions[$qid]);
                                $all_new_array[$qid] = isset( $answer_title->answer ) ? $answer_title->answer : $answer_title;
                            }
                        }
                        break;
                    case "checkbox":
                        if( is_array( $answered_questions[$qid] ) ){
                            $answer_title_checkbox = array();
                            if( isset( $answered_questions[$qid]['answer'] ) ){
                                foreach($answered_questions[$qid]['answer'] as $c_key => $c_value){
                                    if( intval( $c_value ) == 0 ){
                                        $answer_title_checkbox[] = $answered_questions[$qid]['other'];
                                    }else{
                                        $answer_title = Survey_Maker_Data::get_answer_by_id($c_value);
                                        $answer_title_checkbox[] = isset( $answer_title->answer ) ? $answer_title->answer : $answer_title;
                                    }
                                }
                            }else{
                                $answer_title_checkbox[] = isset( $answered_questions[$qid]['other'] ) && $answered_questions[$qid]['other'] != '' ? $answered_questions[$qid]['other'] : '';
                            }
                            $all_new_array[$qid] = implode(",", $answer_title_checkbox);
                        }else{
                            if( $answered_questions[$qid] != '' ){
                                $answer_title = Survey_Maker_Data::get_answer_by_id($answered_questions[$qid]);
                                $all_new_array[$qid] = isset( $answer_title->answer ) ? $answer_title->answer : $answer_title;
                            }
                        }
                        break;    
                    case "select":
                        if( $answered_questions[$qid] ){
                            $answer_title = Survey_Maker_Data::get_answer_by_id($answered_questions[$qid]);
                            $all_new_array[$qid] = isset( $answer_title->answer ) ? $answer_title->answer : $answer_title;
                        }   
                        break;
                    case "text":
                    case "short_text":
                    case "number":
                    case "phone":
                    case "linear_scale":
                    case "star":
                    case "date":
                    case "time":
                    case "range":
                    case "upload":
                    case "name":
                    case "email":
                        if( isset( $answered_questions[$qid]['answer'] ) ){
                            $all_new_array[$qid] = $answered_questions[$qid]['answer'];
                        }else{
                            $all_new_array[$qid] = $answered_questions[$qid];
                        }
                        break;
                    case "date_time":
                        if( isset( $answered_questions[$qid]['answer'] ) && $answered_questions[$qid]['answer'] != ''){
                            $all_new_array[$qid] = implode(" ", $answered_questions[$qid]['answer']);
                        }else{
                            if($answered_questions[$qid] != ''){
                                $all_new_array[$qid] = implode(" ", $answered_questions[$qid]);
                            }
                        }
                        break;
                    case "star_list":
                        case "slider_list":
                            if( isset( $answered_questions[$qid]['answer'] ) ){
                                $answer_title_list = array();
                                foreach($answered_questions[$qid]['answer'] as $c_key => $c_value){
                                    $list_answer_titile = Survey_Maker_Data::get_answer_by_id($c_key);
                                    if ($list_answer_titile){
                                            $answer_title_list[] = $list_answer_titile->answer ? $list_answer_titile->answer." : ".$c_value : "";
                                    }
                                }
                                $all_new_array[$qid] = implode("\n", $answer_title_list);
                            }
                        break;    
                    case "matrix_scale":
                        if( isset( $answered_questions[$qid]['answer'] ) ){
                            $question_options = json_decode($question_data[$qid]->options , true);
                            $answer_title_matrix = array();
                            foreach($answered_questions[$qid]['answer'] as $c_key => $c_value){
                                $matrix_answer_titile = Survey_Maker_Data::get_answer_by_id($c_key);
                                if($matrix_answer_titile){
                                    if(!empty($question_options['matrix_columns']) && is_array($question_options['matrix_columns'])){
                                        if(isset($question_options['matrix_columns'][$c_value])){
                                            $answer_title_matrix[] = $matrix_answer_titile->answer ? $matrix_answer_titile->answer." : ".$question_options['matrix_columns'][$c_value] : "";
                                        }
                                    }
                                }
                            
                            }
                            $all_new_array[$qid] = implode("\n", $answer_title_matrix);
                        }
                    break;
                    case "matrix_scale_checkbox":
                        if( isset( $answered_questions[$qid]['answer'] ) ){
                            if(isset($question_data[$qid])){
                                $question_options = json_decode($question_data[$qid]->options , true);
                                $answer_title_matrix = array();
                                foreach($answered_questions[$qid]['answer'] as $c_key => $c_value){
                                    $matrix_answer_titile = Survey_Maker_Data::get_answer_by_id($c_key);
                                    $answer_title_matrix[] = $matrix_answer_titile->answer && $matrix_answer_titile->answer != "" ? $matrix_answer_titile->answer . ":" : "";
                                    $loop_iteration = 1;
                                    $columns_count = count($c_value);
                                    foreach($c_value as $new_key => $new_value){
                                        if(isset($question_options['matrix_columns'][$new_value])){
                                            $answer_title_matrix[] = $question_options['matrix_columns'][$new_value];
                                            
                                            if(($loop_iteration != ($columns_count)) && $columns_count != 1){
                                                $answer_title_matrix[] = ",";
                                            }
                                            $loop_iteration++;
                                        }
                                    }
                                    $answer_title_matrix[] = "\n";
                                
                                }
                                $all_new_array[$qid] = implode(" ", $answer_title_matrix);
                            }
                        }
                    break;
                    default:
                        $all_new_array[$qid] = '';
                        break;
                }
            }else{
                $all_new_array[$qid] = "";
            }
        }

        foreach( $all_new_array2 as $id => $val ){
            if( isset( $all_new_array[$id] ) ){
                $all_new_array2[$id] = $all_new_array[$id];
            }else{
                $all_new_array2[$id] = '';
            }
        }
        
        if ( $integration != 'zapier' ) {
            $all_new_array2 = array_values( $all_new_array2 );
        }
        
        return $all_new_array2;
    }
    
    // Google Sheet delete data from DB
    public function delete_quiz_sheet_ids(){
        global $wpdb;
        $table = $wpdb->prefix . 'socialsurv_surveys';
        $sql = "SELECT id, options FROM {$table}";
        $results = $wpdb->get_results( $sql, "ARRAY_A" );

        foreach( $results as $key => $result ){
            $id = intval( $result['id'] );
            $options = json_decode( $result['options'], true );

            if( array_key_exists( 'enable_google_sheets', $options ) ){
                unset( $options['enable_google_sheets'] );
            }else{
                continue;
            }

            if( array_key_exists( 'spreadsheet_id', $options ) ){
                unset( $options['spreadsheet_id'] );
            }

            $options = json_encode( $options );

            $wpdb->update(
                $table,
                array( 'options' => $options ),
                array( 'id' => $id ),
                array( '%s' ),
                array( '%d' )
            );
        }

        return true;
    }

    // ===== Integration calls end =====

    // Payment set script attribute
    public function ays_survey_add_data_attribute($tag, $handle) {
        if ( $this->plugin_name . '-paypal' == $handle ){
            return str_replace( ' src', ' data-namespace="aysSurveyPayPal" src', $tag );
        }

        if ( $this->plugin_name . '-stripe' == $handle ){
            return str_replace( ' src', ' data-namespace="aysSurveyStripe" src', $tag );
        }
        return $tag;
    }

    // Stripe enable content
    public function ays_survey_stripe_content($data) {
        $survey_id = $data['survey_id'];
        $stripe_api_key       = $data['stripe_api_key'];
        $stripe_secret_key    = $data['stripe_secret_key'];
        $stripe_payment_terms = $data['stripe_payment_terms'];
        $stripe_amount        = $data['stripe_amount'];
        $stripe_currency      = $data['stripe_currency'];
        $stripe_message       = $data['stripe_message'];
        $is_user_logged_in    = $data['is_user_logged_in'];
        $is_lifetime          = $data['is_lifetime'];

        $is_elementor_exists = Survey_Maker_Data::ays_survey_is_elementor();


        $html = array(
            'survey_stripe' => null,
            'show_stripe'   => false
        );
        if($stripe_secret_key == '' || $stripe_api_key == ''){
            $html['survey_stripe'] = null;
            $html['show_stripe'] = false;
        }else{
            if($is_user_logged_in || (!$is_user_logged_in && !$is_lifetime)){
                $html['show_stripe'] = true;
                $enqueue_stripe_scripts = true;
                if( !$is_user_logged_in && $stripe_payment_terms == "lifetime" ){
                    $enqueue_stripe_scripts = false;
                }

                if( $is_elementor_exists ){
                    $enqueue_stripe_scripts = false;
                }

                $html['survey_stripe'] = '';
                if( $enqueue_stripe_scripts ){
                    wp_enqueue_style( $this->plugin_name . '-stripe-client', SURVEY_MAKER_PUBLIC_URL . '/css/stripe-client.css', array(), $this->version, 'all');
                    wp_enqueue_script( $this->plugin_name . '-stripe', "https://js.stripe.com/v3/", array('jquery'), null, true );
                    wp_enqueue_script( $this->plugin_name . '-stripe-client', SURVEY_MAKER_PUBLIC_URL . "/js/stripe_client.js", array('jquery'), $this->version, true );

                    $stripe_stripe_js_options = array(
                        'ajax_url' => admin_url('admin-ajax.php'),
                        'fetchUrl' => SURVEY_MAKER_PUBLIC_URL .'/partials/stripe-before-transaction.php',
                        'transactionCompleteUrl' => SURVEY_MAKER_PUBLIC_URL .'/partials/stripe-transaction-complete.php',
                        'secretKey' => $stripe_secret_key,
                        'apiKey' => $stripe_api_key,
                        'paymentTerms' => $stripe_payment_terms,
                        'wrapClass' => '.ays-survey-stripe-div-'.$survey_id,
                        'containerId' => '#ays-survey-stripe-button-container-'.$survey_id,
                        'surveyId' => $survey_id,
                        'stripeOptions' => array(
                            'amount' => $stripe_amount,
                            'currency' => $stripe_currency,
                        ),
                    );
                    $html['survey_stripe'] .= '
                        <script>
                            if(typeof surveyMakerStripe === "undefined"){
                                var surveyMakerStripe = [];
                            }
                            surveyMakerStripe["'.$survey_id.'"]  = "' . base64_encode(json_encode($stripe_stripe_js_options)) . '";
                        </script>
                    ';
                }

                $html['survey_stripe'] .= '
                    <div class="ays-survey-stripe-wrap-div">
                        <div class="ays-survey-stripe-details-div">
                            '.$stripe_message.'
                        </div>
                        <div class="ays-survey-stripe-div-'.$survey_id.'" style="display: none;">
                            <div id="ays-survey-stripe-button-container-'.$survey_id.'"></div>
                            <button class="ays-survey-stripe-submit" type="button">
                                <div class="ays-survey-stripe-spinner ays-survey-stripe-hidden"></div>
                                <span class="ays-survey-stripe-button-text">' . __( "Pay now", $this->plugin_name ) . '</span>
                            </button>
                            <span class="ays-survey-stripe-card-error" role="alert"></span>
                        </div>
                    </div>';
            }
            else{
                $html['show_stripe'] = true;
                $html['survey_stripe'] = "<div class='ays-survey-stripe-wrap-div'><span>".__('You need to log in to pass this survey.', $this->plugin_name)."</span></div>";
            }
        }
        return $html;
    }
}
