<?php
ob_start();
class Survey_Question_Categories_List_Table extends WP_List_Table {
    
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The table name in database of the question categories.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $table_name    The table name in database of the question categories.
     */
    private $table_name;
    
    /** Class constructor */
    public function __construct( $plugin_name ) {
        global $wpdb;

        $this->plugin_name = $plugin_name;

        $this->table_name = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories";

        parent::__construct( array(
            'singular' => __( 'Question Category', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Question Categories', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );

        add_action( 'admin_notices', array( $this, 'question_category_notices' ) );
    }

    
    protected function get_views() {
        $published_count = $this->get_statused_record_count( 'published' );
        $draft_count = $this->get_statused_record_count( 'draft' );
        $trashed_count = $this->get_statused_record_count( 'trashed' );
        $all_count = $this->all_record_count();
        $selected_all = "";
        $selected_published = "";
        $selected_draft = "";
        $selected_trashed = "";
        if(isset($_GET['fstatus'])){
            switch($_GET['fstatus']){
                case "published":
                    $selected_published = " style='font-weight:bold;' ";
                    break;
                case "draft":
                    $selected_draft = " style='font-weight:bold;' ";
                    break;
                case "trashed":
                    $selected_trashed = " style='font-weight:bold;' ";
                    break;
                default:
                    $selected_all = " style='font-weight:bold;' ";
                    break;
            }
        }else{
            $selected_all = " style='font-weight:bold;' ";
        }
        $status_links = array(
            "all" => "<a ".$selected_all." href='?page=".esc_attr( $_REQUEST['page'] )."'>" . __( "All", $this->plugin_name ) . " (".$all_count.")</a>",
        );
        if( intval( $published_count ) > 0 ){
            $status_links["published"] = "<a ".$selected_published." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=published'>" . __( "Published", $this->plugin_name ) . " (".$published_count.")</a>";
        }
        if( intval( $draft_count ) > 0 ){
            $status_links["draft"] = "<a ".$selected_draft." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=draft'>" . __( "Draft", $this->plugin_name ) . " (".$draft_count.")</a>";
        }
        if( intval( $trashed_count ) > 0 ){
            $status_links["trashed"] = "<a ".$selected_trashed." href='?page=".esc_attr( $_REQUEST['page'] )."&fstatus=trashed'>" . __( "Trash", $this->plugin_name ) . " (".$trashed_count.")</a>";
        }
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
    public static function get_items( $per_page = 20, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories";

        $where = array();

        if ( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != ''){
            $where[] = ' status = "' . esc_sql( $_GET['fstatus'] ) . '" ';
        }else{
            $where[] = ' status != "trashed" ';
        }

        if ( ! empty( $where ) ){
            $sql .= ' WHERE ' . implode( ' AND ', $where );
        }

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
        }else{
            $sql .= ' ORDER BY id DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public static function get_item_by_id( $id ) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories WHERE id=" . esc_sql( intval( $id ) );

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $result;
    }

    public function add_or_edit_item( $data ){
        global $wpdb;
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories";


        if( isset( $data["question_category_action"] ) && wp_verify_nonce( $data["question_category_action"], 'question_category_action' ) ){

            $name_prefix = 'ays_';
            
            // Save type
            $save_type = (isset($data['save_type'])) ? $data['save_type'] : '';

            // Id of item
            $id = isset( $data['id'] ) ? absint( intval( $data['id'] ) ) : 0;

            // Title
            $title = isset( $data[ $name_prefix . 'title' ] ) && $data[ $name_prefix . 'title' ] != '' ? stripslashes( sanitize_text_field( $data[ $name_prefix . 'title' ] ) ) : '';

            if($title == ''){
                $url = esc_url_raw( remove_query_arg( false ) );
                wp_redirect( $url );
            }

            // Description
            $description = isset( $data[ $name_prefix . 'description' ] ) && $data[ $name_prefix . 'description' ] != '' ? stripslashes( $data[ $name_prefix . 'description' ] ) : '';

            // Status
            $status = isset( $data[ $name_prefix . 'status' ] ) && $data[ $name_prefix . 'status' ] != '' ? $data[ $name_prefix . 'status' ] : 'published';
            
            // Date created
            $date_created = isset( $data[ $name_prefix . 'date_created' ] ) && Survey_Maker_Admin::validateDate( $data[ $name_prefix . 'date_created' ] ) ? $data[ $name_prefix . 'date_created' ] : current_time( 'mysql' );
            
            // Date modified
            $date_modified = isset( $data[ $name_prefix . 'date_modified' ] ) && Survey_Maker_Admin::validateDate( $data[ $name_prefix . 'date_modified' ] ) ? $data[ $name_prefix . 'date_modified' ] : current_time( 'mysql' );

            // Options
            $options = array(

            );

            $message = '';
            if( $id == 0 ){
                $result = $wpdb->insert(
                    $table,
                    array(
                        'title'             => $title,
                        'description'       => $description,
                        'status'            => $status,
                        'date_created'      => $date_created,
                        'date_modified'     => $date_modified,
                        'options'           => json_encode( $options ),
                    ),
                    array(
                        '%s', // title
                        '%s', // description
                        '%s', // status
                        '%s', // date_created
                        '%s', // date_modified
                        '%s', // options
                    )
                );

                $inserted_id = $wpdb->insert_id;
                $message = 'created';
            }else{
                $result = $wpdb->update(
                    $table,
                    array(
                        'title'             => $title,
                        'description'       => $description,
                        'status'            => $status,
                        'date_modified'     => $date_modified,
                        'options'           => json_encode( $options ),
                    ),
                    array( 'id' => $id ),
                    array(
                        '%s', // title
                        '%s', // description
                        '%s', // status
                        '%s', // date_modified
                        '%s', // options
                    ),
                    array( '%d' )
                );

                $inserted_id = $id;
                $message = 'updated';
            }

            if( $result >= 0  ) {
                if($save_type == 'apply'){
                    if($id == 0){
                        $url = esc_url_raw( add_query_arg( array(
                            "action"    => "edit",
                            "id"        => $inserted_id,
                            "status"    => $message
                        ) ) );
                    }else{
                        $url = esc_url_raw( add_query_arg( array(
                            "status" => $message
                        ) ) );
                    }
                    wp_redirect( $url );
                }elseif($save_type == 'save_new'){
                    $url = remove_query_arg( array('id') );
                    $url = esc_url_raw( add_query_arg( array(
                        "action" => "add",
                        "status" => $message
                    ), $url ) );
                    wp_redirect( $url );
                }else{
                    $url = remove_query_arg( array('action', 'id') );
                    $url = esc_url_raw( add_query_arg( array(
                        "status" => $message
                    ), $url ) );
                    wp_redirect( $url );
                }
            }
        }
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_items( $id ) {
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories",
            array( 'id' => $id ),
            array( '%d' )
        );
    }

    /**
     * Move to trash a customer record.
     *
     * @param int $id customer ID
     */
    public static function trash_items( $id ) {
        global $wpdb;
        $db_item = self::get_item_by_id( $id );
        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories",
            array( 
                'status' => 'trashed',
                'trash_status' => $db_item['status'],
            ),
            array( 'id' => $id ),
            array( '%s', '%s' ),
            array( '%d' )
        );
    }

    /**
     * Restore a customer record.
     *
     * @param int $id customer ID
     */
    public static function restore_items( $id ) {
        global $wpdb;
        $db_item = self::get_item_by_id( $id );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories",
            array( 
                'status' => $db_item['trash_status'],
                'trash_status' => '',
            ),
            array( 'id' => $id ),
            array( '%s', '%s' ),
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

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories";

        return $wpdb->get_var( $sql );
    }

    public static function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories WHERE status != 'trashed'";

        return $wpdb->get_var( $sql );
    }

    public static function get_statused_record_count( $status ) {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories WHERE status='" . esc_sql( $status ) . "'";

        return $wpdb->get_var( $sql );
    }
    

    /** Text displayed when no customer data is available */
    public function no_items() {
        Survey_Maker_Data::survey_no_items_list_tables('question categories');
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
            case 'title':
            case 'description':
            case 'items_count':
            case 'status':
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
        
        if(intval($item['id']) === 1){
            return;
        }
        
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_title( $item ) {
        if($item['status'] == 'trashed'){
            $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-question-category' );
        }else{
            $delete_nonce = wp_create_nonce( $this->plugin_name . '-trash-question-category' );
        }

        $restitle = Survey_Maker_Admin::ays_restriction_string( "word", stripcslashes( $item['title'] ), 5);
        
        $fstatus = '';
        if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
            $fstatus = '&fstatus='.$_GET['fstatus'];
        }
        $actions = array();
        if($item['status'] == 'trashed'){
            $title = sprintf( '<a><strong>%s</strong></a>', $restitle );
            $actions['restore'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Restore', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'restore', absint( $item['id'] ), $delete_nonce );
        }else{
            $title = sprintf( '<a href="?page=%s&action=%s&id=%d"><strong>%s</strong></a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ), $restitle );
            $actions['edit'] = sprintf( '<a href="?page=%s&action=%s&id=%d">'. __('Edit', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) );
        }
        
        if(intval($item['id']) !== 1){
            if($item['status'] == 'trashed'){
                $actions['delete'] = sprintf( '<a class="ays_confirm_del" data-message="%s" href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Delete Permanently', $this->plugin_name) .'</a>', $restitle, esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce );
            }else{
                $actions['trash'] = sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Move to trash', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'trash', absint( $item['id'] ), $delete_nonce );
            }
        }

        return $title . $this->row_actions( $actions );
    }

    function column_items_count( $item ) {
        global $wpdb;
        $sql = "SELECT COUNT(*) 
                FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "question_categories c
                JOIN " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions s
                    ON c.id IN ( s.category_ids )
                WHERE c.id = " . esc_sql( $item['id'] );
        $result = $wpdb->get_var($sql);
        return "<p style='font-size:14px;'>" . $result . "</p>";
    }

    function column_status( $item ) {
        global $wpdb;
        $status = ucfirst( $item['status'] );
        $date = date( 'Y/m/d', strtotime( $item['date_modified'] ) );
        $title_date = date( 'l jS \of F Y h:i:s A', strtotime( $item['date_modified'] ) );
        $html = "<p style='font-size:14px;margin:0;'>" . $status . "</p>";
        $html .= "<p style=';font-size:14px;margin:0;text-decoration: dotted underline;' title='" . $title_date . "'>" . $date . "</p>";
        return $html;
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Title', $this->plugin_name ),
            'description'   => __( 'Description', $this->plugin_name ),
            'items_count'   => __( 'Questions Count', $this->plugin_name ),
            'status'        => __( 'Status', $this->plugin_name ),
            'id'            => __( 'ID', $this->plugin_name ),
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
            'title'         => array( 'title', true ),
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
            'bulk-trash' => __( 'Move to trash', $this->plugin_name ),
        );

        if(isset($_GET['fstatus']) && $_GET['fstatus'] == 'trashed'){
            $actions = array(
                'bulk-restore' => __( 'Restore', $this->plugin_name ),
                'bulk-delete' => __( 'Delete Permanently', $this->plugin_name ),
            );
        }

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'question_categories_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );

        $this->items = self::get_items( $per_page, $current_page );
    }

    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-question-category' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_items( absint( $_GET['id'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'deleted'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = $_GET['fstatus'];
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        //Detect when a bulk action is being triggered...
        if ( 'trash' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-trash-question-category' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::trash_items( absint( $_GET['id'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'trashed'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = $_GET['fstatus'];
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        //Detect when a bulk action is being triggered...
        if ( 'restore' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-question-category' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::restore_items( absint( $_GET['id'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'restored'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = $_GET['fstatus'];
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'deleted'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = $_GET['fstatus'];
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-trash' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-trash' ) ) {

            $trash_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $trash_ids as $id ) {
                self::trash_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'trashed'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = $_GET['fstatus'];
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-restore' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-restore' ) ) {

            $restore_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $restore_ids as $id ) {
                self::restore_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'restored'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = $_GET['fstatus'];
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }
    }



    public function question_category_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        if ( 'created' == $status )
            $updated_message = esc_html( __( 'Questions category created.', $this->plugin_name ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Questions category saved.', $this->plugin_name ) );
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Questions category deleted.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
            <div class="notice notice-success is-dismissible">
                <p> <?php echo $updated_message; ?> </p>
            </div>
        <?php
    }
}
