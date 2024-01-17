<?php
class Survey_Requests_List_Table extends WP_List_Table{
    private $plugin_name;
    private $title_length;
   
    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
        $this->title_length = Survey_Maker_Data::get_listtables_title_length('');
        parent::__construct( array(
            'singular' => __( 'Result', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Results', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );
        add_action( 'admin_notices', array( $this, 'results_notices' ) );
    }

    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

            <div class="alignleft actions">
                <?php $this->bulk_actions( $which ); ?>
            </div>

            <?php
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }
    
    protected function get_views() {
        $approved_count = $this->approved_records_count();
        $not_approved_count = $this->not_approved_records_count();
        $all_count = $this->all_record_count();
        $selected_all = "";
        $selected_0 = "";
        $selected_1 = "";
        if(isset($_GET['fstatus'])){
            switch($_GET['fstatus']){
                case "not-approved":
                    $selected_0 = " style='font-weight:bold;' ";
                    break;
                case "approved":
                    $selected_1 = " style='font-weight:bold;' ";
                    break;
                default:
                    $selected_all = " style='font-weight:bold;' ";
                    break;
            }
        }else{
            $selected_all = " style='font-weight:bold;' ";
        }

        $status_links = array(
            "all" => "<a ".$selected_all." href='?page=".esc_attr( $_REQUEST['page'] )."'>". __( 'All', $this->plugin_name )." (".$all_count.")</a>",
            "approved" => "<a ".$selected_1." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=approved'>". __( 'Approved', $this->plugin_name )." (".$approved_count.")</a>",
            "not-approved"   => "<a ".$selected_0." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=not-approved'>". __( 'Not Approved', $this->plugin_name )." (".$not_approved_count.")</a>"
        );
        return $status_links;
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_requests( $per_page = 20, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}socialsurv_requests";

        $where = array();

        if( isset( $_REQUEST['fstatus'] ) ){
            $fstatus = $_REQUEST['fstatus'];
            if($fstatus !== null){
                $where[] = " `approved` = '".$fstatus."' ";
            }
        }

        if( ! empty($where) ){
            $sql .= " WHERE " . implode( " AND ", $where );
        }
        
        
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
        }
        else{
            $sql .= ' ORDER BY id DESC';
        }
        
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    // public function store_data(){
    //     global $wpdb;

    //     $ays_survey_settings_table = $wpdb->prefix . "socialsurv_settings";
    //     if(isset($_POST['ays_fr_save']) && !empty($_POST['ays_fr_save'])){
    //         $ays_survey_fr_auto_approve = ( isset($_POST['ays_survey_front_request_auto_aprove']) && $_POST['ays_survey_front_request_auto_aprove'] != "") ? $_POST['ays_survey_front_request_auto_aprove'] : 'off';

    //         $options = array(
    //             "ays_survey_fr_auto_approve" => $ays_survey_fr_auto_approve,
    //         );

    //         $value = array(
    //             'meta_value'  => json_encode( $options ),
    //         );
    //         $value_s = array( '%s' );

    //         $result = $wpdb->update(
    //             $ays_survey_settings_table,
    //             $value,
    //             array( 'meta_key' => 'front_requests' ),
    //             $value_s,
    //             array( '%s' )
    //         );
    //     }
    // }

    public function get_requests_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}socialsurv_requests WHERE id=" . absint( intval( $id ) );
        
        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }

    public function get_requests_survey_id(){
        global $wpdb;

        $sql = "SELECT survey_id FROM {$wpdb->prefix}socialsurv_requests WHERE approved='approved'";
        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public function get_surveys_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}socialsurv_surveys WHERE id=" . absint( intval( $id ) );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }
    
    public function check_if_survey_exists( $id ){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}socialsurv_surveys WHERE id=" . absint( intval( $id ) );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result ? true : false;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_requests( $id ) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}socialsurv_requests",
            array( 'id' => $id ),
            array( '%d' )
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}socialsurv_requests";

        return $wpdb->get_var( $sql );
    }

    public static function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}socialsurv_requests";

        return $wpdb->get_var( $sql );
    }

    public static function not_approved_records_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}socialsurv_requests";

        $where = array();

        $where[] = " `approved` = 'not-approved' ";

        if( ! empty($where) ){
            $sql .= " WHERE " . implode( " AND ", $where );
        }
        
        return $wpdb->get_var( $sql );
    }

    public function approved_records_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}socialsurv_requests ";

        $where = array();

        $where[] = " `approved` = 'approved' ";

        if( ! empty($where) ){
            $sql .= " WHERE " . implode( " AND ", $where );
        }

        return $wpdb->get_var( $sql );
    }

    /**
     * Mark as read a customer record.
     *
     * @param int $id customer ID
     */
    public static function mark_as_approved_requests( $id ) {
        global $wpdb;

        ini_set("xdebug.var_display_max_children", '-1');
        ini_set("xdebug.var_display_max_data", '-1');
        ini_set("xdebug.var_display_max_depth", '-1');

        $approved_id = (isset($id) && $id != '') ? intval($id) : null;
        if ($approved_id == null) {
            return;
        }

        $requests_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'requests';
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
        $questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'questions';
        $sections_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sections';
        $answers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'answers';

        $sql = "SELECT * FROM {$requests_table} WHERE id=".$approved_id;
        $results = $wpdb->get_results( $sql, 'ARRAY_A' )[0];

        if (!empty($results)) {
            $category_ids = isset($results['category_id']) && intval($results['category_id']) > 0 ? $results['category_id'] : '1';
            $author_id = isset($results['user_id']) && $results['user_id'] != '' ? intval($results['user_id']) : 0;
            $user_ip = isset($results['user_ip']) && $results['user_ip'] != '' ? $results['user_ip'] : '';
            $title = isset($results['survey_title']) && $results['survey_title'] != '' ? sanitize_text_field($results['survey_title']) : 'Survey';
            $date_created = isset($results['request_date']) && $results['request_date'] != '' ? $results['request_date'] : current_time( 'mysql' );
            
            $data = isset($results['request_data']) && $results['request_data'] != '' ? json_decode($results['request_data'], true) : array();

            /* Sections start */
                $section_ids = array();
                $section_options = array(
                    'collapsed' => "expanded",
                    'go_to_section' => '-1'
                );

                $sections_default = array(
                    'title'         => "",
                    'description'   => "",
                    'ordering'      => 1,
                    'options'       => json_encode($section_options),
                );

                $result = $wpdb->insert(
                    $sections_table,
                    $sections_default,
                    array(
                        '%s', // title
                        '%s', // description
                        '%d', // ordering
                        '%s', // options
                    )
                );
                $section_insert_id = $wpdb->insert_id;
                $section_ids[] = $section_insert_id;
            /* Sections end */

            /* Questions start */
                $question_options = array(
                    'required' => 'off',
                    'collapsed' => 'expanded',
                    'linear_scale_1' => '',
                    'linear_scale_2' => '',
                    'scale_length' => '5',
                    'star_1' => '',
                    'star_2' => '',
                    'star_scale_length' => '5',
                    'matrix_columns' => '',
                    'star_list_stars_length' => '',
                    'slider_list_range_length' => '',
                    'slider_list_range_step_length' => '',
                    'slider_list_range_min_value' => '',
                    'slider_list_range_default_value' => '',
                    'slider_list_range_calculation_type' => 'seperatly',
                    'enable_max_selection_count' => 'off',
                    'max_selection_count' => '',                                
                    'min_selection_count' => '',

                    // Text Limitations
                    'enable_word_limitation' => 'off',
                    'limit_by' => '',
                    'limit_length' => '',
                    'limit_counter' => 'off',

                    // Number Limitations
                    'enable_number_limitation'    => 'off',
                    'number_min_selection'        => '',
                    'number_max_selection'        => '',
                    'number_error_message'        => '',
                    'enable_number_error_message' => 'off',  
                    'number_limit_length'         => '',
                    'enable_number_limit_counter' => 'off',

                    'survey_input_type_placeholder' => '',

                    // Question caption
                    'image_caption'        => '',
                    'image_caption_enable' => 'off',

                    'is_logic_jump' => 'off',
                    'other_answer_logic_jump' => 'off',
                    'user_explanation' => 'off',
                    'with_editor' => 'off',
                    'range_length'        => '',
                    'range_step_length'   => '',
                    'range_min_value'     => '',
                    'range_default_value' => '',
                    'enable_admin_note'   => 'off',
                    'admin_note'          => '',
                    'enable_url_parameter' => 'off',
                    'enable_hide_results'  => 'off',
                    'url_parameter'       => '',
                    'file_upload_toggle'     => 'off',
                    'file_upload_types_pdf'  => 'off',
                    'file_upload_types_doc'  => 'off',
                    'file_upload_types_png'  => 'off',
                    'file_upload_types_jpg'  => 'off',
                    'file_upload_types_gif'  => 'off',
                    'file_upload_types_size' => '5',
                    'other_logic_jump' => array(),
                    'other_logic_jump_otherwise' => -1,
                    'html_types_content'     => '',
                );
                
                $question_ids = array();
                foreach ($data as $question_id => $question) {
                    $question_title = isset($question['question']) && $question['question'] != '' ? sanitize_Text_field($question['question']) : 'Question';
                    $question_type = isset($question['type']) && $question['type'] != '' ? sanitize_Text_field($question['type']) : 'radio';

                    $question_result = $wpdb->insert(
                        $questions_table,
                        array(
                            'author_id'         => $author_id,
                            'section_id'        => $section_insert_id,
                            'category_ids'      => $category_ids,
                            'question'          => $question_title,
                            'question_description'  => '',
                            'type'              => $question_type,
                            'status'            => 'published',
                            'trash_status'      => '',
                            'date_created'      => $date_created,
                            'date_modified'     => $date_created,
                            'user_variant'      => 'off',
                            'user_explanation'  => '',
                            'image'             => '',
                            'ordering'          => $question_id,
                            'options'           => json_encode($question_options, JSON_UNESCAPED_SLASHES),
                        ),
                        array(
                            '%d', // author_id
                            '%d', // section_id
                            '%s', // category_ids
                            '%s', // question
                            '%s', // question_description
                            '%s', // type
                            '%s', // status
                            '%s', // trash_status
                            '%s', // date_created
                            '%s', // date_modified
                            '%s', // user_variant
                            '%s', // user_explanation
                            '%s', // image
                            '%d', // ordering
                            '%s', // options
                        )
                    );
                    $question_insert_id = $wpdb->insert_id;
                    $question_ids[] = $question_insert_id;

                    /* Answers start */
                        $answers = isset($question['answers']) && $question['answers'] != '' ? $question['answers'] : array();
                        foreach ($answers as $answer_id => $answer) {
                            $answer_title = isset($answer['answer']) && $answer['answer'] != '' ? sanitize_Text_field($answer['answer']) : 'Option '.$answer_id;

                            $answer_result = $wpdb->insert(
                                $answers_table,
                                array(
                                    'question_id'       => $question_insert_id,
                                    'answer'            => $answer_title,
                                    'image'             => '',
                                    'ordering'          => $answer_id,
                                    'placeholder'       => '',
                                    'options'           => json_encode( array("go_to_section" => -1) )
                                ),
                                array(
                                    '%d', // question_id
                                    '%s', // answer
                                    '%s', // image
                                    '%d', // ordering
                                    '%s', // placeholder
                                    '%s', // options
                                )
                            );
                        }
                    /* Answers end */
                }

            /* Questions end */

            // Survey options 
            $default_options = array(
                // Styles Tab
                'survey_theme' => 'classic_light',
                'survey_color' => 'rgb(255, 87, 34)', // #673ab7
                'survey_background_color' => '#fff',
                'survey_text_color' => '#333',
                'survey_buttons_text_color' => '#333',
                'survey_width' => '',
                'survey_width_by_percentage_px' => 'pixels',
                'survey_mobile_max_width' => '',
                'survey_custom_class' => '',
                'survey_custom_css' => '',
                'survey_logo' => '',
        
                'survey_question_font_size' => 16,
                'survey_question_image_width' => '',
                'survey_question_image_height' => '',
                'survey_question_image_sizing' => 'cover',
                'survey_question_padding' => 24,
                'survey_question_caption_text_color' => '#333',
                'survey_question_caption_text_alignment' => 'center',
                'survey_question_caption_font_size' => 16,
                'survey_question_caption_font_size_on_mobile' => 16,
                'survey_question_caption_text_transform' => 'none',
        
                'survey_answer_font_size' => 15,
                'survey_answer_font_size_on_mobile' => 15,
                'survey_answers_view' => 'grid',
                'survey_answers_view_alignment' => 'space_around',
                'survey_answers_object_fit' => 'cover',
                'survey_answers_padding' => 8,
                'survey_answers_gap' => 0,
        
                'survey_buttons_size' => 'medium',
                'survey_buttons_font_size' => 14,
                'survey_buttons_left_right_padding' => 24,
                'survey_buttons_top_bottom_padding' => 0,
                'survey_buttons_border_radius' => 4,
                'survey_buttons_alignment' => 'left',
                'survey_buttons_top_distance' => 10,

                // Settings Tab
                'survey_show_title' => 'off',
                'survey_show_section_header' => 'on',
                'survey_enable_randomize_answers' => 'off',
                'survey_enable_randomize_questions' => 'off',
                'survey_enable_clear_answer' => 'on',
                'survey_enable_previous_button' => 'on',
                'survey_allow_html_in_answers' => 'off',
                'survey_enable_leave_page' => 'on',
                'survey_enable_info_autofill' => 'off',
                'survey_enable_schedule' => 'off',
                'survey_schedule_active' => current_time( 'mysql' ),
                'survey_schedule_deactive' => current_time( 'mysql' ),
                'survey_schedule_show_timer' => 'off',
                'survey_show_timer_type' => 'countdown',
                'survey_schedule_pre_start_message' =>  __("The survey will be available soon!", SURVEY_MAKER_NAME),
                'survey_schedule_expiration_message' => __("This survey has expired!", SURVEY_MAKER_NAME),
                'survey_dont_show_survey_container' => 'off',
                'survey_edit_previous_submission' => 'off',
                'survey_auto_numbering' => 'none',

                // Result Settings Tab
                'survey_redirect_after_submit' => 'off',
                'survey_submit_redirect_url' => '',
                'survey_submit_redirect_delay' => '',
                'survey_submit_redirect_new_tab' => 'off',
                'survey_enable_exit_button' => 'off',
                'survey_exit_redirect_url' => '',
                'survey_enable_restart_button' => 'on',
                'survey_show_questions_as_html' => 'on',
                'survey_final_result_text' => '',
                'survey_loader' => 'ripple',

                // Limitation Tab
                'survey_limit_users' => 'off',
                'survey_limit_users_by' => 'ip',
                'survey_max_pass_count' => 1,
                'survey_limitation_message' => '',
                'survey_redirect_url' => '',
                'survey_redirect_delay' => 0,
                'survey_enable_logged_users' => 'off',
                'survey_logged_in_message' => '',
                'survey_show_login_form' => 'off',
                'survey_enable_takers_count' => 'off',
                'survey_takers_count' => 1,

                // E-Mail Tab
                'survey_enable_mail_user' => 'off',
                'survey_mail_message' => '',
                'survey_enable_mail_admin' => 'off',
                'survey_send_mail_to_site_admin' => 'on',
                'survey_additional_emails' => '',
                'survey_mail_message_admin' => '',
                'survey_email_configuration_from_email' => '',
                'survey_email_configuration_from_name' => '',
                'survey_email_configuration_from_subject' => '',
                'survey_email_configuration_replyto_email' => '',
                'survey_email_configuration_replyto_name' => '',            
            );

            $sections_count = count( $section_ids );
            $questions_count = count( $question_ids );
            $section_ids_str = empty( $section_ids ) ? '' : implode( ',', $section_ids );
            $question_ids_str = empty( $question_ids ) ? '' : implode( ',', $question_ids );

            $max_id = Survey_Maker_Admin::get_max_id('surveys');
            $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;

            $result = $wpdb->insert(
                $surveys_table,
                array(
                    'author_id'         => $author_id,
                    'title'             => $title,
                    'description'       => '',
                    'category_ids'      => $category_ids,
                    'question_ids'      => $question_ids_str,
                    'sections_count'    => $sections_count,
                    'questions_count'   => $questions_count,
                    'image'             => '',
                    'status'            => 'published',
                    'trash_status'      => '',
                    'date_created'      => $date_created,
                    'date_modified'     => $date_created,
                    'ordering'          => $ordering,
                    'section_ids'       => $section_ids_str,
                    'conditions'        => '',
                    'options'           => json_encode( $default_options, JSON_UNESCAPED_SLASHES ),
                ),
                array(
                    '%d', // author_id
                    '%s', // title
                    '%s', // description
                    '%s', // category_ids
                    '%s', // question_ids
                    '%d', // sections_count
                    '%d', // questions_count
                    '%s', // image
                    '%s', // status
                    '%s', // trash_status
                    '%s', // date_created
                    '%s', // date_modified
                    '%d', // ordering
                    '%s', // section_ids
                    '%s', // conditions
                    '%s', // options
                )
            );

            $inserted_id = $wpdb->insert_id;

            $request_res = $wpdb->update(
                $requests_table,
                array(
                    'survey_id'     => $inserted_id,
                    'status'        => 'Published',
                    'approved'      => 'approved',
                ),
                array( 'id' => $approved_id ),
                array( '%d', '%s', '%s'),
                array( '%d' )
            );
        }        
    }

    private function get_max_id() {
        global $wpdb;
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $sql = "SELECT MAX(id) FROM {$table}";

        $result = $wpdb->get_var($sql);

        return $result;
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        echo __( 'There are no results yet.', $this->plugin_name );
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'user_id': 
            case 'survey_title':
            case 'request_date':
            case 'unread':
            case 'status':
            case 'approved':
            case 'id':
                return $item[ $column_name ];
                break;
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" class="ays_result_delete" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_survey_title( $item ) {
        global $wpdb;

        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-requests' );
        $ays_survey_frontend_requests_survey_title = stripcslashes($item['survey_title']);
        
        $title = sprintf('<a href="?page=%s-each&survey=%d">' . $ays_survey_frontend_requests_survey_title . '</a>', esc_attr($_REQUEST['page']), absint($item['id']), $ays_survey_frontend_requests_survey_title);

        $actions = array(
            'edit' => sprintf('<a href="?page=%s-each&survey=%d">' . __('View', $this->plugin_name) . '</a>', esc_attr($_REQUEST['page']), absint($item['id']), $ays_survey_frontend_requests_survey_title),
            'delete' => sprintf( '<a class="ays_confirm_del" data-message="this report" href="?page=%s&action=%s&requests=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_user_id( $item ) {
        global $wpdb;

        $user = get_user_by('id',$item['user_id']);
        $user_name = $user->user_nicename;

        return $user_name;
    }

    function column_approved( $item ) {
        $request_id = (isset($item['id']) && $item['id'] != null) ? absint(intval($item['id'])) : null;
		if ($request_id == null) {
			return false;
		}
		$approved = (isset($item['approved']) && $item['approved'] == 'approved' ) ? true : false;
		$survey_id  = (isset($item['survey_id']) && $item['survey_id'] != null) ? absint(intval($item['survey_id'])) : null;

		if ($approved) {
			if ($survey_id != null) {
				$check_if_survey_exists = $this->check_if_survey_exists($survey_id);
				if ($check_if_survey_exists) {
					return sprintf('<a href="?page=%s&action=%s&id=%s" target="_blank">%s</a>', 'survey-maker', 'edit', absint($survey_id) , __( 'Go to Survey' , $this->plugin_name ) );
				}else{
					return sprintf('<input type="button" class="button primary ays_survey_approve_button" value="%s" data-id="%d" data-type="%s" />', __("Create Again", $this->plugin_name) , $request_id , "create" );
				}
			}
		}else{
			return sprintf('<input type="button" class="button primary ays_survey_approve_button" value="%s" data-id="%d" data-type="%s"/>', __("Approve", $this->plugin_name) , $request_id , "approve" );
		}
    }

    function column_status( $item ) {
        global $wpdb;
        $request_id = (isset($item['id']) && $item['id'] != null) ? absint(intval($item['id'])) : null;

		if ($request_id == null) {
			return false;
		}

        $survey_ids = $this->get_requests_survey_id();

        $survey_status = '';

        foreach ($survey_ids as $key => $survey_id) {
            $id  = (isset($survey_id['survey_id']) && $survey_id['survey_id'] != null) ? absint(intval($survey_id['survey_id'])) : null;
            
            $requests_table = $wpdb->prefix."socialsurv_requests";
            $surveys_table = $wpdb->prefix."socialsurv_surveys";
            
            $survey_status = '';
            
            if($id != null){
                $survey_status_sql = "SELECT `status` FROM {$surveys_table} WHERE id=".$id;
                $survey_status_result = $wpdb->get_var($survey_status_sql);
            }

            $status = (isset($survey_status_result) && $survey_status_result == 'published') ? $survey_status_result : 'unpublished';
            
            if ($status == 'published') {
                $survey_status = 'Published';
            } else if ($status == 'unpublished') {
                $survey_status = 'Unpublished';
            }
                            
            $wpdb->update(
                $requests_table,
                array(
                    'status' => $survey_status,
                ),
                array( 'survey_id' => $id ),
				array( '%s'),
				array( '%d' )
            );
        }
        return $item['status'];
    }

    function column_unread($item) {
        global $wpdb;
        $sql = "SELECT `unread` FROM {$wpdb->prefix}socialsurv_requests WHERE `unread` = 1 AND `id` = ".$item['id'];
        $result = intval($wpdb->get_row($sql, "ARRAY_A"));
        $unread = ($result == 1) ? "unread-result" : "";

        return "<div data-id='".$item['id']."' class='unread-result-badge ".$unread."'></div>";
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'                    => '<input type="checkbox" />',
            'survey_title'          => __( 'Survey Title', $this->plugin_name ),
            'user_id'               => __( 'User Name', $this->plugin_name ),
            'request_date'          => __( 'Request Date', $this->plugin_name ),
            'unread'            	=> __( 'Read Status', $this->plugin_name ),
            'status'                => __( 'Status', $this->plugin_name ),
            'approved'              => __( 'Approved', $this->plugin_name ),
            'id'                    => __( 'ID', $this->plugin_name ),
        );

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'survey_title'  => array( 'survey_title', true ),
            'user_id'       => array( 'user_id', true ),
            'request_date'  => array( 'request_date', true ),
            'id'            => array( 'id', true ),
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'mark-as-approved' => __( 'Mark as approved', $this->plugin_name),
            // 'mark-as-not-approved' => __( 'Mark as not approved', $this->plugin_name),
            'bulk-delete' => 'Delete'
        );

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'survey_frontend_requests_per_page', 20 );

        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );

        $this->items = self::get_requests( $per_page, $current_page );
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        $message = 'deleted';
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-requests' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_requests( absint( $_GET['requests'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg(array('action', 'requests', '_wpnonce')  ) ) . '&status=' . $message;
                wp_redirect( $url );
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_requests( $id );

            }

            $url = esc_url_raw( remove_query_arg(array('action', 'requests', '_wpnonce')  ) ) . '&status=' . $message;
            wp_redirect( $url );
        }

        // If the mark-as-approved bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'mark-as-approved' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'mark-as-approved' ) ) {
            $approved = esc_sql( $_POST['bulk-delete'] );
            // loop over the array of record IDs and delete them
            foreach ( $approved as $id ) {
                self::mark_as_approved_requests( $id );
            }

            $url = esc_url_raw( remove_query_arg(array('action', 'requests', '_wpnonce') ) );

            $message = 'marked-as-approved';
            $url = add_query_arg( array(
                'status' => $message,
            ), $url );
            wp_redirect( $url );
        }

        // If the mark-as-unread bulk action is triggered
        // if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'mark-as-not-approved' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'mark-as-not-approved' ) ) {

        //     $delete_ids = esc_sql( $_POST['bulk-delete'] );

        //     // loop over the array of record IDs and delete them
        //     foreach ( $delete_ids as $id ) {
        //         self::mark_as_not_approved_requests( $id );
        //     }

        //     $url = esc_url_raw( remove_query_arg(array('action', 'result', '_wpnonce') ) );

        //     $message = 'mark-as-not-approved';
        //     $url = add_query_arg( array(
        //         'status' => $message,
        //     ), $url );

        //     wp_redirect( $url );
        // }

    }

    public function results_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        if ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Requests deleted.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }
}
