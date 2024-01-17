<?php
ob_start();
class Survey_Each_Submission_List_Table extends WP_List_Table {
    private $plugin_name;
    /** Class constructor */
    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
        parent::__construct( array(
            'singular' => __( 'Each result', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Each results', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );
        add_action( 'admin_notices', array( $this, 'each_results_notices' ) );
        add_filter( 'default_hidden_columns', array( $this, 'get_hidden_columns'), 10, 2 );

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
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    public function extra_tablenav( $which ){
        global $wpdb;

        if( is_super_admin() ){
            $users_sql = "SELECT s.user_id
                          FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions AS s
                          WHERE survey_id = " . esc_sql( absint( $_GET['survey'] ) ) . "
                          GROUP BY user_id";
            $users_res = $wpdb->get_results($users_sql, 'ARRAY_A');
            $users = array();

            $quiz_id = null;
            $user_id = null;

            if( isset( $_GET['filterbyuser'] )){
                $user_id = absint( sanitize_text_field( $_GET['filterbyuser'] ) );
            }

            $clear_url = "?page=" . sanitize_text_field( $_REQUEST['page'] ) . "&survey=" . absint( sanitize_text_field( $_REQUEST['survey'] ) ) . "&ays_survey_tab=poststuff";
            ?>
            <div id="user-filter-div-<?php echo esc_attr( $which ); ?>" class="alignleft actions bulkactions">
                <select name="filterbyuser-<?php echo esc_attr( $which ); ?>" id="bulk-action-selector-top2-<?php echo esc_attr( $which ); ?>">
                    <option value=""><?php echo __('Select User', $this->plugin_name); ?></option>
                    <?php
                        foreach($users_res as $key => $user){
                            $selected = "";
                            if($user_id === intval($user['user_id'])){
                                $selected = "selected";
                            }
                            if(intval($user['user_id']) == 0){
                                $name = __( 'Guest', $this->plugin_name );
                            }else{
                                $wpuser = get_userdata( intval($user['user_id']) );
                                $name = __( 'Guest', $this->plugin_name );
                                if($wpuser){
                                    if(isset($wpuser->data)){
                                        $name = isset($wpuser->data->display_name) ? $wpuser->data->display_name : $name;
                                        
                                    }
                                }
                                else{
                                    continue;
                                }
                            }
                            $users[$user['user_id']]['name'] = $name;
                            $users[$user['user_id']]['selected'] = $selected;
                            $users[$user['user_id']]['id'] = $user['user_id'];
                        }
                        sort($users);
                        foreach($users as $key => $user){                        
                            echo "<option ".$user['selected']." value='".$user['id']."'>".$user['name']."</option>";
                        }
                    ?>
                </select>
                <input type="button" id="doaction2-<?php echo esc_attr( $which ); ?>" class="user-filter-apply-<?php echo esc_attr( $which ); ?> button ays-survey-question-tab-all-filter-button-<?php echo esc_attr( $which ); ?> ays-survey-question-filter-each-submission" value="Filter">
            </div>
            <a style="display:inline-block;" href="<?php echo $clear_url; ?>" class="button"><?php echo __( "Clear filters", $this->plugin_name ); ?></a>
            <?php
        }
    }
    
    protected function get_views() {
        $published_count = $this->readed_records_count();
        $unpublished_count = $this->unread_records_count();
        $all_count = $this->all_record_count();
        $selected_all = "";
        $selected_0 = "";
        $selected_1 = "";
        if( isset( $_GET['fstatus'] ) ){
            switch( sanitize_text_field( $_GET['fstatus'] ) ){
                case "unread":
                    $selected_0 = " style='font-weight:bold;' ";
                    break;
                case "read":
                    $selected_1 = " style='font-weight:bold;' ";
                    break;
                default:
                    $selected_all = " style='font-weight:bold;' ";
                    break;
            }
        }else{
            $selected_all = " style='font-weight:bold;' ";
        }
        $link = "?page=" . esc_attr( sanitize_text_field( $_REQUEST['page'] ) ) . "&survey=" . esc_attr( sanitize_text_field( $_REQUEST['survey'] ) );
        $status_links = array(
            "all" => "<a ".$selected_all." href='".$link."&ays_survey_tab=poststuff'>". __("All", $this->plugin_name) ." (".$all_count.")</a>",
            "readed" => "<a ".$selected_1." href='".$link."&fstatus=read&ays_survey_tab=poststuff'>". __("Read", $this->plugin_name) ." (".$published_count.")</a>",
            "unreaded"   => "<a ".$selected_0." href='".$link."&fstatus=unread&ays_survey_tab=poststuff'>". __("Unread", $this->plugin_name) . " (".$unpublished_count.")</a>"
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
    public static function get_results( $per_page = 50, $page_number = 1 ) {

        global $wpdb;
        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        $sql .= self::get_where_condition();
        
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            if(isset($_GET['sub_password'])){
                unset($_GET['sub_password']);
            }
            $order_by  = ( isset( $_REQUEST['orderby'] ) && sanitize_text_field( $_REQUEST['orderby'] ) != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';
            $order_by .= ( ! empty( $_REQUEST['order'] ) && strtolower( $_REQUEST['order'] ) == 'asc' ) ? ' ASC' : ' DESC';

            $sql_orderby = sanitize_sql_orderby( $order_by );

            if ( $sql_orderby ) {
                $sql .= ' ORDER BY ' . $sql_orderby;
            } else {
                $sql .= ' ORDER BY id DESC';
            }
        }else{
            $sql .= ' ORDER BY id DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
    }

    public static function get_where_condition(){
        global $wpdb;
        $where = array();
        $sql = '';

        $search = ( isset( $_REQUEST['s'] ) ) ? sanitize_text_field( $_REQUEST['s'] ) : false;
        $remove_password_param = false;
        if( $search ){
            $remove_password_param = true;
            $s = array();
            $s[] = sprintf( "`user_name` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( "`user_email` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( "POSITION( '%%%s%%' IN `submission_date` )", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( "`unique_code` LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) );
            $s[] = sprintf( "`password` = '%s' ", esc_sql( $wpdb->esc_like( $search ) ) );
            // $s[] = ' `score` LIKE \'%'.$search.'%\' ';
            $where[] = ' ( ' . implode(' OR ', $s) . ' ) ';
        }
        
        $post_id = isset($_GET['filterbypost']) ? intval( sanitize_text_field( $_GET['filterbypost'] ) ) : null;
        if (isset($post_id)) {
            $where[] = ' `post_id` = '.$post_id.' ';
        }

        $start_date = isset($_GET['filterbystartdate']) ? sanitize_text_field( $_GET['filterbystartdate'] ) : null;
        $end_date = isset($_GET['filterbyenddate']) ? sanitize_text_field( $_GET['filterbyenddate'] ) : null;
        if (isset($start_date) && isset($end_date)) {
            $where[] = ' `submission_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
        } else if (isset($start_date)) {
            $where[] = ' `submission_date` >= "'.$start_date.'" ';
        } else if (isset($end_date)) {
            $where[] = ' `submission_date` <= "'.$end_date.'" ';
        }

        if ( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != ''){            
            $remove_password_param = true;
            if( $_GET['fstatus'] == 'read' ){
                $read = 1;
            }elseif( $_GET['fstatus'] == 'unread' ){
                $read = 2;
            }

            $where[] = ' `read` = ' . esc_sql( $read ) . ' ';
        }

        if( isset( $_REQUEST['filterbyuser'] ) ){            
            $remove_password_param = true;
            $user_id = absint( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $where[] = ' `user_id` = '.$user_id.' ';
        }
        
        if( isset( $_REQUEST['survey'] ) ){
            $quiz_id = absint( sanitize_text_field( $_REQUEST['survey'] ) );
            $where[] = ' `survey_id` = '.$quiz_id.' ';
        }

        if($remove_password_param){
            if(isset($_GET['sub_password'])){
                unset($_GET['sub_password']);
            }
        }
        
        if( ! empty($where) ){
            $sql = " WHERE " . implode( " AND ", $where );
        }
        return $sql;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_items( $id ) {
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array('id' => $id),
            array('%d')
        );
        
        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions",
            array('submission_id' => $id),
            array('%d')
        );
    }

    /**
     * Mark as read a customer record.
     *
     * @param int $id customer ID
     */
    public static function mark_as_read_reports( $id ) {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array('read' => 1),
            array('id' => $id),
            array('%d'),
            array('%d')
        );
    }

    /**
     * Mark as unread a customer record.
     *
     * @param int $id customer ID
     */
    public static function mark_as_unread_reports( $id ) {
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array('read' => 2),
            array('id' => $id),
            array('%d'),
            array('%d')
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $sql .= self::get_where_condition();
        return $wpdb->get_var( $sql );
    }
    
    public static function all_record_count() {
        global $wpdb;

        $survey_id = absint( sanitize_text_field( $_REQUEST['survey'] ) );
        $survey_id = ' WHERE `survey_id` = '.$survey_id.' ';        
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions ".$survey_id;

        return $wpdb->get_var( $sql );
    }
    
    public static function unread_records_count() {
        global $wpdb;

        $survey_id = absint( sanitize_text_field( $_REQUEST['survey'] ) );
        $survey_id = ' AND `survey_id` = '.$survey_id.' ';
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions WHERE ( `read` = 0 OR `read` = 2 ) ".$survey_id;

        return $wpdb->get_var( $sql );
    }
    
    public function readed_records_count() {
        global $wpdb;

        $survey_id = absint( sanitize_text_field( $_REQUEST['survey'] ) );
        $survey_id = ' AND `survey_id` = '.$survey_id.' ';
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions WHERE `read` = 1 ".$survey_id;

        return $wpdb->get_var( $sql );
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        Survey_Maker_Data::survey_no_items_list_tables('submissions');
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
        switch ($column_name) {
            case 'user_id':
            case 'user_ip':
            case 'user_name':
            case 'user_email':
            case 'submission_date':
            case 'unique_code':
            case 'id':
                return $item[$column_name];
                break;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" class="ays_result_delete ays-survey-no-trigger" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }
    
    public function column_user_id( $item ) {
        global $wpdb;

        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-each-result' );

        $sub_password = (isset($_GET['sub_password']) && $_GET['sub_password'] != '' ? $_GET['sub_password'] : '');
        $check_if_password_exists = '';
        if($sub_password){
            if(isset($item['password']) && $item['password'] != "" && $item['password'] == $sub_password){
                $check_if_password_exists = 'isTrigger';
            }
        }

        $sql = "SELECT * FROM ".$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" . " WHERE id=". absint( $item['id'] );
        $result = $wpdb->get_row($sql, "ARRAY_A");

        $user_id = absint( $item['user_id'] );
        $class_red = '';
        if($user_id == 0){
            $name = __( "Guest", $this->plugin_name );
        }else{
            $user = get_userdata($user_id);
            if($user !== false){
                $name = $user->data->display_name;
            }else{
                $name = __( "Deleted user", $this->plugin_name );
                $class_red = ' ays_color_red ';
            }
        }

        if( absint( $item['read'] ) == 1 ){
            $title = $name; //sprintf( '<a href="javascript:void(0)" data-result="%d" class="%s">%s</a><input type="hidden" value="%d" class="ays_result_read">', absint( $item['id'] ), 'ays-show-results'.$class_red, $name, $item['read']);
        }else{
            $title = sprintf( '%s<input type="hidden" value="%d" class="ays_result_read">', $name, absint( $item['read'] ) );
        }
        $title .= sprintf( '<input type="hidden" value="%d" class="ays_survey_result">', absint( $item['id']) );
        $actions = array(
            // 'view-details' => sprintf( '<a href="javascript:void(0);" data-result="%d" class="%s">%s</a>', absint( $item['id'] ), 'ays-show-results', __('Detailed report', $this->plugin_name)),
            // 'view_detailed' => sprintf( '<a class="ays-survey-no-trigger ays-survey-view-detailed-button" data-message="this submission" href="#">%s</a>', __('View Detailed', $this->plugin_name) ),
            'view_detailed' => sprintf( '<a class="ays-survey-no-trigger ays-survey-view-detailed-button" data-sub-password="'.$check_if_password_exists.'" data-message="this submission" href="#">%s</a>', __('View Detailed', $this->plugin_name) ),
            'delete' => sprintf( '<a class="ays_confirm_del ays-survey-no-trigger" data-message="this submission" href="?page=%s&action=%s&survey=%s&item=%s&_wpnonce=%s">%s</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $result['survey_id'] ), absint( $item['id'] ), $delete_nonce, __('Delete', $this->plugin_name) )
        );
        return $title . $this->row_actions( $actions ) ;
    }
    
    public function column_duration( $item ) {
        global $wpdb;

        $passed_time = (isset($item['duration'])) ? $item['duration'] : null;
        if($passed_time !== null){
            // $title = $passed_time . "s";
            $passed_time = Survey_Maker_Data::secondsToFormat( $passed_time );
            $title = $passed_time;
        }else{
            $title = __('No data', $this->plugin_name);
        }

        return $title;
    }

    public function column_unique_code( $item ) {
        global $wpdb;
        $unique_code = isset($item['unique_code']) && $item['unique_code'] != '' ? $item['unique_code'] : '<p style="text-align:center;">-</p>';
        $unique_code_html = "<strong style='text-transform:uppercase!important;white-space:nowrap;'>" . $unique_code . "</strong>";
        return $unique_code_html;
    }

    public function column_password( $item ) {
        global $wpdb;
        $user_password = isset($item['password']) && $item['password'] != '' ? $item['password'] : esc_attr($item['password']);
        // $unique_code_html = "<strong style='text-transform:uppercase!important;white-space:nowrap;'>" . $unique_code . "</strong>";
        return $user_password;
    }

    public function column_user_name( $item ) {
        global $wpdb;
        // $user_name = isset($item['user_name']) && $item['user_name'] != '' ? stripslashes( $item['user_name'] ) : '';
        $user_name = isset($item['user_name']) && $item['user_name'] != '' ? stripslashes(nl2br( htmlentities( $item['user_name'] ) ) ) : '';
        return $user_name;
    }
    
    public function column_changed( $item ) {
        global $wpdb;
        $changed_count = isset($item['changed']) && $item['changed'] != '' ? intval( $item['changed'] )  : 0;
        return $changed_count;
    }

    public function column_user_email( $item ) {
        global $wpdb;
        $user_email = isset($item['user_email']) && $item['user_email'] != '' ? stripslashes(nl2br( htmlentities( $item['user_email'] ) ) ) : '';
        return $user_email;
        
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    public function get_columns() {
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'user_id'           => __( 'WP User', $this->plugin_name ),
            'user_ip'           => __( 'User IP', $this->plugin_name ),
            'user_name'         => __( 'User Name', $this->plugin_name ),
            'user_email'        => __( 'User Email', $this->plugin_name ),
            'submission_date'   => __( 'Submission Date', $this->plugin_name ),
            'duration'          => __( 'Duration', $this->plugin_name ),
            'unique_code'       => __( 'Unique Code', $this->plugin_name ),
            'id'                => __( 'ID', $this->plugin_name ),
            'password'          => __( 'Password', $this->plugin_name ),
            'changed'           => __( 'Changed', $this->plugin_name ),
        );
        
        return $columns;
    }

    public function ays_see_all_results( $mark_as_read_ids ){
        global $wpdb;
        if( empty( $mark_as_read_ids ) ){
            return false;
        }
        $mark_as_read_ids = implode( ',', $mark_as_read_ids );
        $sql = "UPDATE {$wpdb->prefix}aysquiz_reports
                SET `read`=1
                WHERE `id` IN (".$mark_as_read_ids.");";
        $wpdb->query( $sql );
    }

    public function mark_as_read_all_results( $survey_id ){
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'read' => 1 ),
            array( 
                'read' => 0,
                'survey_id' => $survey_id,
            ),
            array( '%d' ),
            array( '%d', '%d' )
        );
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'id'  => array( 'id', true ),
            'user_id'           => array( 'user_id', true ),
            'user_ip'           => array( 'user_ip', true ),
            'user_name'         => array( 'user_name', true ),
            'user_email'        => array( 'user_email', true ),
            'submission_date'   => array( 'submission_date', true ),
            'score'             => array( 'score', true ),
            'unique_code'       => array( 'unique_code', true ),
        );

        return $sortable_columns;
    }

    /**
     * Columns to make hidden.
     *
     * @return array
     */
    public function get_hidden_columns() {
        $sortable_columns = array(
            'unique_code',
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
            'mark-as-read' => __( 'Mark as read', $this->plugin_name),
            'mark-as-unread' => __( 'Mark as unread', $this->plugin_name),
            'bulk-delete' => __( 'Delete', $this->plugin_name),
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

        $per_page     = $this->get_items_per_page( 'survey_each_submission_results_per_page', 50 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );

        $this->items = self::get_results( $per_page, $current_page);
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        $message = 'deleted';
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-each-result' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                self::delete_items( absint( $_GET['item'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg(array('action', 'item', '_wpnonce') ) );

                $url = add_query_arg( array(
                    'status' => $message,
                    'ays_survey_tab' => 'poststuff',
                ), $url );
                wp_redirect( $url );
            }

        }


        // If the mark-as-read bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'mark-as-read' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'mark-as-read' ) ) {

            $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::mark_as_read_reports( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url

            $url = esc_url_raw( remove_query_arg(array('action', 'item', '_wpnonce') ) );
            
            $message = 'marked-as-read';
            $url = add_query_arg( array(
                'status' => $message,
                'ays_survey_tab' => 'poststuff',
            ), $url );
            wp_redirect( $url );
        }

        // If the mark-as-unread bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'mark-as-unread' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'mark-as-unread' ) ) {

            $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::mark_as_unread_reports( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url

            $url = esc_url_raw( remove_query_arg(array('action', 'item', '_wpnonce') ) );

            $message = 'marked-as-unread';
            $url = add_query_arg( array(
                'status' => $message,
                'ays_survey_tab' => 'poststuff',
            ), $url );

            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) ) {

            $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $message = 'are-deleted';
            $url = esc_url_raw( remove_query_arg(array('action', 'item', '_wpnonce') ) );

            $url = add_query_arg( array(
                'status' => $message,
                'ays_survey_tab' => 'poststuff',
            ), $url );

            wp_redirect( $url );
        }
    }

    public function each_results_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;


        if ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Submission deleted.', $this->plugin_name ) );
        elseif ( 'seen' == $status )
            $updated_message = esc_html( __( 'Selected submissions have been marked as read.', $this->plugin_name ) );
        elseif ( 'marked-as-read' == $status )
            $updated_message = esc_html( __( 'Selected submissions have been marked as read.', $this->plugin_name ) );
        elseif ( 'marked-as-unread' == $status )
            $updated_message = esc_html( __( 'Selected submissions have been marked as unread.', $this->plugin_name ) );
        elseif ( 'are-deleted' == $status )
            $updated_message = esc_html( __( 'Selected submissions are deleted successfully.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }

    public function get_submision_line_chart($survey_id=0, $filters = array()){
        global $wpdb;

        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = $this->get_filters_where_condition($filters);
        }

        $sql = "SELECT DATE(`end_date`) AS date, COUNT(*) AS value FROM `{$wpdb->prefix}socialsurv_submissions` WHERE `survey_id` = ". absint( $survey_id ) . $filters_where_condition . " GROUP BY date";
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        foreach ($result as $key => &$value) {
            $value['value'] = intval($value['value']);
            $value = array_values($value);
        }

        return $result;        
    }

    public static function survey_users_count($filters = array()) {
        global $wpdb;
        global $wp_roles;

        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $where = array();

            $post_id = isset($filters['post_id']) ? intval( sanitize_text_field( $filters['post_id'] ) ) : null;
            if (isset($post_id)) {
                $where[] = ' `post_id` = '.$post_id.' ';
            }

            $start_date = isset($filters['start_date']) ? sanitize_text_field( $filters['start_date'] ) : null;
            $end_date = isset($filters['end_date']) ? sanitize_text_field( $filters['end_date'] ) : null;
            if (isset($start_date) && isset($end_date)) {
                $where[] = ' `submission_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
            } else if (isset($start_date)) {
                $where[] = ' `submission_date` >= "'.$start_date.'" ';
            } else if (isset($end_date)) {
                $where[] = ' `submission_date` <= "'.$end_date.'" ';
            }

            if( ! empty($where) ){
                $filters_where_condition = " AND " . implode( " AND ", $where );
            }
        }

        $survey_id = absint( sanitize_text_field( $_GET['survey'] ) );
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}socialsurv_submissions WHERE survey_id = ". $survey_id . $filters_where_condition. " AND user_id = 0";
        $guests = $wpdb->get_var( $sql );

        $sql = "SELECT COUNT(`{$wpdb->prefix}socialsurv_submissions`.`id`) AS q, `{$wpdb->prefix}usermeta`.`meta_value` AS v
                FROM `{$wpdb->prefix}socialsurv_submissions`
                JOIN `{$wpdb->prefix}usermeta` 
                    ON `{$wpdb->prefix}usermeta`.`user_id` = `{$wpdb->prefix}socialsurv_submissions`.`user_id`
                WHERE `{$wpdb->prefix}socialsurv_submissions`.`user_id` != 0
                  AND `{$wpdb->prefix}socialsurv_submissions`.`survey_id` = ". $survey_id ."
                  AND `{$wpdb->prefix}usermeta`.`meta_key` = '{$wpdb->prefix}capabilities'". $filters_where_condition."
                GROUP BY `{$wpdb->prefix}usermeta`.`meta_value`";
        $results = $wpdb->get_results( $sql );
        $user_roles = array();
        $logged_in = 0;
        foreach($results as $key => $value){
            $role = maybe_unserialize($value->v);
            if(is_array($role)){
                while ($fruit_name = current($role)) {
                    if(array_key_exists(key($role), $wp_roles->roles)){
                        $user_roles[$key]['type'] = $wp_roles->roles[ key($role) ]['name'];
                    }
                    next($role);
                }
            }else{
                $user_roles[$key]['type'] = $wp_roles->roles[ key($role) ]['name'];
            }
            
            $user_roles[$key]['percent'] = $value->q;
            
            $logged_in += intval($value->q);
        }
        
        return array(
            "guests" => $guests,
            "loggedIn" => $logged_in,
            "userRoles" => $user_roles
        );
    }
    
    public function get_devices_bar_chart($survey_id=0, $filters = array()){
        global $wpdb;

        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = $this->get_filters_where_condition($filters);
        }

        $sql = "SELECT * FROM `{$wpdb->prefix}socialsurv_submissions` WHERE `survey_id` = ".$survey_id . $filters_where_condition . " ";
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
        $desktopCount = 0;
        $mobileCount = 0;
        $tabletCount = 0;
        
        foreach ($result as $key => &$value) {
            $device = json_decode($value['options'], true);

            foreach ($device as $key2 => $value2) {
               if( isset($key2) && $key2 == 'device' ){
                    switch ($value2) {
                        case 'desktop':
                                $desktopCount = $desktopCount + 1;
                            break;
                        case 'mobile':
                                $mobileCount = $mobileCount + 1;
                            break;                        
                        case 'tablet':
                                $tabletCount = $tabletCount + 1;
                        break;
                        
                        default:
                            $desktopCount = $desktopCount + 1;
                            break;
                    }
                    
               }
            }  
        }

        $results = array(
            'Desktop' => $desktopCount,
            'Mobile' => $mobileCount,
            'Tablet' => $tabletCount,
        );

        return $results;        
    }

    public function get_countries_pie_chart($survey_id=0, $filters = array()){
        global $wpdb;

        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = $this->get_filters_where_condition($filters);
        }

        $sql = "SELECT country, count(*) as count FROM `{$wpdb->prefix}socialsurv_submissions` WHERE `survey_id` = ".$survey_id . $filters_where_condition . " AND country <> '' GROUP BY country";

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        $countries = array();

        foreach ($result as $key => $value) {
            $country = $value['country'];
            $count = intval($value['count']);

            $countries[$country] = $count;
        }
        
        return $countries;

    }

    public function get_filters_where_condition ($filters) {
        $where = array();
        $sql = "";

        $post_id = isset($filters['post_id']) ? intval( sanitize_text_field( $filters['post_id'] ) ) : null;
        if (isset($post_id)) {
            $where[] = ' `post_id` = '.$post_id.' ';
        }

        $start_date = isset($filters['start_date']) ? sanitize_text_field( $filters['start_date'] ) : null;
        $end_date = isset($filters['end_date']) ? sanitize_text_field( $filters['end_date'] ) : null;
        if (isset($start_date) && isset($end_date)) {
            $where[] = ' `submission_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
        } else if (isset($start_date)) {
            $where[] = ' `submission_date` >= "'.$start_date.'" ';
        } else if (isset($end_date)) {
            $where[] = ' `submission_date` <= "'.$end_date.'" ';
        }

        if( ! empty($where) ){
            $sql = " AND " . implode( " AND ", $where );
        }

        return $sql;
    }

}
