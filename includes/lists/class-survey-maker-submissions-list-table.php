<?php
ob_start();
class Submissions_List_Table extends WP_List_Table {
    private $plugin_name;
    private $title_length;
    /** Class constructor */
    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
        $this->title_length = Survey_Maker_Data::get_listtables_title_length('submissions');
        $this->current_user_can_edit = Survey_Maker_Data::survey_maker_capabilities_for_editing();
        parent::__construct( array(
            'singular' => __( 'Submissions', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Submissions', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );
        add_action( 'admin_notices', array( $this, 'submissions_notices' ) );

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
        
        $titles_sql = "SELECT s.title, s.id
                       FROM " .$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories AS s
                       WHERE s.status = 'published'";
        $cat_titles = $wpdb->get_results($titles_sql);

        $cat_id = null;
        if( isset( $_GET['filterby'] )){
            $cat_id = absint( sanitize_text_field( $_GET['filterby'] ) );
        }
        $categories_select = array();
        foreach($cat_titles as $key => $cat_title){
            $selected = "";
            if($cat_id === intval($cat_title->id)){
                $selected = "selected";
            }
            $categories_select[$cat_title->id]['title'] = $cat_title->title;
            $categories_select[$cat_title->id]['selected'] = $selected;
            $categories_select[$cat_title->id]['id'] = $cat_title->id;
        }
        sort($categories_select);
        ?>
        <div id="category-filter-div-surveylist-<?php echo $which;?>" class="alignleft actions bulkactions">
            <select name="filterby-<?php echo esc_attr( $which ); ?>" id="survey-category-filter-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo __('Select Category',$this->plugin_name)?></option>
                <?php
                    foreach($categories_select as $key => $cat_title){
                        echo "<option ".$cat_title['selected']." value='".$cat_title['id']."'>".$cat_title['title']."</option>";
                    }
                ?>
            </select>
            <input type="button" id="doaction-<?php echo esc_attr( $which ); ?>" class="cat-filter-apply-<?php echo esc_attr( $which ); ?> button ays-survey-question-tab-all-filter-button-<?php echo esc_attr( $which ); ?>" value="Filter">
        </div>
        
        <a style="margin: 0px 8px 0 0;" href="?page=<?php echo esc_attr( sanitize_text_field( $_REQUEST['page'] ) ); ?>" class="button"><?php echo __( "Clear filters", $this->plugin_name ); ?></a>
        <?php

    
    }

    
    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_items( $per_page = 50, $page_number = 1 ) {

        global $wpdb;
        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $sql .= self::get_where_condition();
        
        if ( ! empty( $_REQUEST['orderby'] ) ) {
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

        // $search = ( isset( $_REQUEST['s'] ) ) ? esc_sql( $_REQUEST['s'] ) : false;
        // if( $search ){
        //     $s = array();
            // $s[] = ' `user_name` LIKE \'%'.$search.'%\' ';
            // $s[] = ' `user_email` LIKE \'%'.$search.'%\' ';
            // $s[] = ' `user_phone` LIKE \'%'.$search.'%\' ';
            // $s[] = ' `score` LIKE \'%'.$search.'%\' ';
        //     $where[] = ' ( ' . implode(' OR ', $s) . ' ) ';
        // }

        // if(isset( $_REQUEST['fstatus'] )){            
            // $fstatus = intval($_REQUEST['fstatus']);
            // switch($fstatus){
            //     case 0:
            //         $where[] = ' `read` = 0 ';
            //         break;
            //     case 1:                    
            //         $where[] = ' `read` = 1 ';
            //         break;
            // }
        // }

        if(! empty( $_REQUEST['filterby'] ) && intval( $_REQUEST['filterby'] ) > 0){
            $cat_id = intval( sanitize_text_field( $_REQUEST['filterby'] ) );
            $where[] = ' FIND_IN_SET( "'.$cat_id.'", `category_ids` ) ';
        }

        if( isset( $_REQUEST['wpuser'] ) ){
            $user_id = absint( sanitize_text_field( $_REQUEST['wpuser'] ) );
            $where[] = ' `user_id` = '.$user_id.' ';
        }
        
        if( isset( $_REQUEST['survey'] ) ){
            $quiz_id = absint( sanitize_text_field( $_REQUEST['survey'] ) );
            $where[] = ' `survey_id` = '.$quiz_id.' ';
        }
        
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $where[] = " author_id = ".$current_user." ";
        }

        if( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != "" ){
            $each_survey = esc_attr( $_REQUEST['s'] );
            $do_search = ( $each_survey ) ? sprintf( " title LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $each_survey ) ) ) : '';
            $where[] = $do_search;
        }

        $where[] = ' `status` != "trashed" ';
        
        if( ! empty($where) ){
            $sql = " WHERE " . implode( " AND ", $where );
        }
        return $sql;
    }
    
    public static function get_report_by_quiz_id( $quiz_id ){
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories WHERE quiz_id=" . absint( $quiz_id );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }

    public function get_report_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_reports WHERE id=" . absint( $id );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }

    public function get_reports_titles(){
        global $wpdb;

        $author_id = get_current_user_id();
        
        $sql = "SELECT id, title FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        
        if( ! $this->current_user_can_edit ){
            $sql .= " WHERE author_id = ". $author_id;
        }

        $result = $wpdb->get_results($sql, 'ARRAY_A');
        
        $survey_ids = array();
        foreach($result as $key => $value){
            $survey_ids[] = $value['id'];
        }
        $survey_ids = implode(',' , $survey_ids);

        $submissions_count = Survey_Maker_Data::get_submissions_count_by_survey_ids($survey_ids);
        $ready_result = array();
        foreach($result as $keys => $surveys){
            $surveys['submissions_count'] = isset($submissions_count[$surveys['id']]) ? esc_attr($submissions_count[$surveys['id']]) : 0;
            $ready_result[$surveys['id']] = $surveys;
        }
        return $ready_result;
    }

    public static function get_results_dates( $survey_id ){
        global $wpdb;

        $current_user_can_edit = Survey_Maker_Data::survey_maker_capabilities_for_editing();
        $user_id = get_current_user_id();
        $sql = "SELECT COUNT(*) AS `count`, DATE(s.`submission_date`) AS `submission_date`
                FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions s";
                
        if( ! $current_user_can_edit ){
            $sql .= " LEFT JOIN " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys sr
                        ON s.survey_id = sr.id ";
        }
        $sql .= " WHERE YEAR(DATE(s.`submission_date`)) > 2000 AND `status` = 'published' ";
                
        if( ! $current_user_can_edit ){
            $sql .= " AND sr.author_id = {$user_id} ";
        }

        if( $survey_id !== 0 ){
            $sql .= " AND s.survey_id = {$survey_id} ";
        }
        
        $sql .= " GROUP BY DATE(s.`submission_date`) ";
        
        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }
    
    public function ays_see_all_results(){
        global $wpdb;
        $sql = "UPDATE {$wpdb->prefix}aysquiz_reports SET `read`=1";
        $wpdb->get_results($sql, 'ARRAY_A');
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
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_items( $id ) {
        global $wpdb;
        
        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'survey_id' => $id ),
            array( '%d' )
        );
        
        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions",
            array( 'survey_id' => $id ),
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

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        // if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
        //     $current_user = get_current_user_id();
        //     $sql .= " WHERE author_id = ".$current_user." ";
        // }

        $sql .= self::get_where_condition();
        
        return $wpdb->get_var( $sql );
    }    
    
    public static function unread_records_count( $id ) {
        global $wpdb;

        $sql = "SELECT COUNT(*)
                FROM ".$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" . " 
                WHERE ( `read` = 0 OR `read` = 2 ) AND survey_id=".absint( $id );
        // $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0 OR `read` = 2;";

        return $wpdb->get_var( $sql );
    }
    
    public static function record_complete_filter_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM (SELECT DISTINCT `quiz_id` FROM {$wpdb->prefix}aysquiz_reports ) AS quiz_ids";

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
        switch ( $column_name ) {
            case 'quiz_title':
            case 'quiz_rate':
            case 'score':
            case 'user_count':
            case 'unreads':
            case 'category_ids':
            case 'last_sub_date':
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
        global $wpdb;

        $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-result' );

        $survey_title = stripcslashes( $item['title'] );

        $q = esc_attr( $survey_title );

        $restitle = Survey_Maker_Admin::ays_restriction_string( "word", $survey_title, $this->title_length );
        $restitle = esc_attr($restitle);
        $result = $this->unread_records_count( $item['id'] );

        if( intval( $result ) > 0 ){
            $title = sprintf( '<a href="?page=%s&survey=%d" title="%s">%s</a><input type="hidden" value="%d" class="ays_result_read">', $this->plugin_name."-each-submission", absint( $item['id'] ), $q, $restitle, 0 );
        }else{
            $title = sprintf( '<a href="?page=%s&survey=%d" title="%s">%s</a>', $this->plugin_name."-each-submission", absint( $item['id'] ), $q, $restitle );
        }

        $actions = array(
            'view_details' => sprintf( '<a href="?page=%s&survey=%d">%s</a>', $this->plugin_name."-each-submission", absint( $item['id'] ), __('View details', $this->plugin_name)),
            'delete' => sprintf( '<a class="ays_confirm_del" data-message="%s" href="?page=%s&action=%s&item=%s&_wpnonce=%s" title="%s">%s</a>', $restitle."'s submissions", esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce, __( 'Delete all submissions of this survey', $this->plugin_name ), __("Delete", $this->plugin_name) )
        );

        return $title . $this->row_actions( $actions );
    }

    function column_quiz_rate( $item ) {
        global $wpdb;

        $sql = "SELECT AVG(`score`) AS avg_score FROM {$wpdb->prefix}aysquiz_rates WHERE quiz_id=". absint( $item['id'] );
        $result = $wpdb->get_var($sql);
        
        if($result == null){
            return;
        }
        
        $res = round($result,1);
        
        return $res;
    }
    
    function column_score($item){
        global $wpdb;

        $sql = "SELECT AVG(`score`) AS avg_score FROM {$wpdb->prefix}aysquiz_reports WHERE quiz_id=". absint( $item['id'] );
        $result = $wpdb->get_var($sql);
        
        if($result == null){
            return;
        }
        $res = round($result,2)."%";
        
        return $res;
    }

    function column_user_count($item) {
        global $wpdb;

        $sql = "SELECT COUNT(*)
                FROM ".$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" . " 
                WHERE survey_id=". absint( $item['id'] );

        $result = $wpdb->get_var( $sql );

        return $result;
    }

    function column_unreads($item) {
        global $wpdb;
        $sql = "SELECT COUNT(*)
                FROM ".$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" . " 
                WHERE ( `read` = 0 OR `read` = 2 ) AND survey_id=". absint( $item['id'] );

        $result = $this->unread_records_count( $item['id'] );
        $q = intval( $result );
        
        if($q != 0){
            $q = "<p style='font-size:16px;font-weight:900;color:blue'>$q</p>
            <input type='hidden' class='ays_quiz_results_unreads' value='0'>";
        }
        return $q;
    }

    function column_category_ids( $item ) {
        global $wpdb;

        // Survey categories IDs
        $category_ids = isset( $item['category_ids'] ) && $item['category_ids'] != '' ? $item['category_ids'] : '';
        
        if( ! empty( $category_ids ) ){
            $sql = "SELECT id,title FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories WHERE id IN (" . esc_sql( $category_ids ) . ")";

            $results = $wpdb->get_results($sql, 'ARRAY_A');
            $category_location = array();
            foreach ($results as $key => $value) {
                $category_location[] = sprintf( '<a href="?page=%s&action=%s&id=%d" target="_blank">%s</a>', 'survey-maker-survey-categories', 'edit', $value['id'], $value['title']);
            }
            
            $titles = implode( ', ', $category_location );

            return $titles;
        }

        return '-';

    }

    function column_last_sub_date($item) {
        global $wpdb;
        $survey_id = isset($item['id']) && $item['id'] != "" ? $item['id'] : false;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $res = '';
        if($survey_id){
            $sql = "SELECT submission_date
                    FROM ".$submissions_table."
                    WHERE survey_id=". absint( $survey_id ) ." ORDER BY id DESC LIMIT 1";
            $res = $wpdb->get_var( $sql );
        }
        return $res;
    }
    
    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Survey', $this->plugin_name ),
            // 'quiz_rate'     => __( 'Average Rate', $this->plugin_name ),
            // 'score'         => __( 'Average Score', $this->plugin_name ),
            'user_count'    => __( 'Passed Users Count', $this->plugin_name ),
            'unreads'       => __( 'Unread results', $this->plugin_name ),
            'category_ids'  => __( 'Categories', $this->plugin_name ),
            'last_sub_date' => __( 'Last submission date', $this->plugin_name ),
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
            'quiz_title' => array( 'title', true ),
            'id'         => array( 'id', true ),
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
            'bulk-delete' => 'Delete',
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

        $per_page = $this->get_items_per_page('survey_submissions_results_per_page', 20);

        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        // if(! empty( $_REQUEST['orderby'] ) &&  $_REQUEST['orderby'] == 'quiz_complete' ){
        //     $total_items = self::record_complete_filter_count();
        // }
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));
        $this->items = self::get_items( $per_page, $current_page );
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        $message = 'deleted';
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-result' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                self::delete_items( absint( $_GET['item'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg(array('action', 'survey', '_wpnonce') ) ) . '&status=' . $message;
                wp_redirect( $url );
            }

        }


        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url

            $url = esc_url_raw( remove_query_arg(array('action', 'survey', '_wpnonce') ) ) . '&status=' . $message;
            wp_redirect( $url );
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
    }
    
    public function results_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        if ( 'created' == $status )
            $updated_message = esc_html( __( 'Quiz created.', $this->plugin_name ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Quiz saved.', $this->plugin_name ) );
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Quiz deleted.', $this->plugin_name ) );

        if ( empty( $updated_message ) )
            return;

        ?>
        <div class="notice notice-success is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }

    /**
     * Mark as read a customer record.
     *
     * @param int $id customer ID
     */
    public static function mark_as_read_reports( $id ) {
        global $wpdb;
        $x = $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array('read' => 1),
            array('survey_id' => absint( $id ) ),
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
            array('survey_id' => absint( $id ) ),
            array('%d'),
            array('%d')
        );
        
    }
}
