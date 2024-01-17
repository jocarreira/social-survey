<?php
ob_start();
class Customers_List_Table extends WP_List_Table {
    private $plugin_name;
    private $table_name;
    private $title_length;
    
    /** Class constructor */
    public function __construct($plugin_name) {
        global $wpdb;
        $this->plugin_name = $plugin_name;
        $this->table_name = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers";
        $this->title_length = Survey_Maker_Data::get_listtables_title_length('customers');

        parent::__construct( array(
            'singular' => __( 'Customer', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Customers', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );
        add_action( 'admin_notices', array( $this, 'customers_notices' ) );

    }

    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            
            <div class="alignleft actions">
                <?php  $this->bulk_actions( $which ); ?>
            </div>
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

 /**
     * Disables the views for 'side' context as there's not enough free space in the UI
     * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
     *
     * @see WP_List_Table::extra_tablenav()
     */
    public function extra_tablenav($which) {

        $survey_cat_description = array(
            "with"    => __( "Com Nome Fantasia", "survey-maker"),
            "without" => __( "Sem Nome Fantasia", "survey-maker"),
        );

        $description_key = null;

        if( isset( $_GET['filterbyTradeName'] ) && sanitize_text_field( $_GET['filterbyTradeName'] ) != ""){
            $description_key = sanitize_text_field( $_GET['filterbyTradeName'] );
        }

        ?>

        <div id="survey-filter-div-<?php echo esc_attr( $which ); ?>" class="alignleft actions bulkactions">

            <select name="filterbyTradeName-<?php echo esc_attr( $which ); ?>" id="bulk-action-survey-cat-trade-name-selector-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo __('Com/Sem Nome Fantasia',"survey-maker"); ?></option>
                <?php
                    foreach($survey_cat_description as $key => $cat_description) {
                        $selected = "";
                        if( $description_key === sanitize_text_field($key) ) {
                            $selected = "selected";
                        }
                        echo "<option ".$selected." value='".esc_attr( $key )."'>".$cat_description."</option>";
                    }
                ?>
            </select>
            <input type="button" id="doaction-survey-<?php echo esc_attr( $which ); ?>" class="ays-survey-question-tab-all-filter-button-<?php echo esc_attr( $which ); ?> button" value="<?php echo __( "Filtro", "survey-maker" ); ?>">
        </div>

        <a style="" href="?page=<?php echo esc_attr( sanitize_text_field( $_REQUEST['page'] ) ); ?>" class="button"><?php echo __( "Limpar filtros", "survey-maker" ); ?></a>
        <?php
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
            /*
            case 'user_id';
            case 'ether_address';
            case 'title';
            case 'trade_name';
            case 'business_name';
            case 'type_person';
            case 'cnpj_cpf';
            case 'email';
            case 'address',
            case 'zip_code',
            case 'city';
            case 'state_acronym';
            case 'country';
            case 'status';
            */                
        $columns = array(
            'cb'                    => '<input type="checkbox" />',
            'id'                    => __( 'Id', $this->plugin_name ),            
            'title'                 => __( 'Título', $this->plugin_name ),
            'trade_name'            => __( 'Nome Fantasia', $this->plugin_name ),
            //'business_name'         => __( 'Razão Social', $this->plugin_name ),
            //'type_person'           => __( 'Tipo Pessoa', $this->plugin_name ),
            'cnpj_cpf'              => __( 'Cnpj/Cpf', $this->plugin_name ),
            'email'                 => __( 'Email', $this->plugin_name ),
            //'address'               => __( 'Endereço', $this->plugin_name ),
            'zip_code'              => __( 'Cep', $this->plugin_name ),
            'city'                  => __( 'Cidade', $this->plugin_name ),
            'state_acronym'         => __( 'Estado', $this->plugin_name ),
            'country'               => __( 'País', $this->plugin_name ),
            'status'                => __( 'Status', $this->plugin_name ),
            'user_id'               => __( 'Usuário WP', $this->plugin_name ),
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
            'id'                => array( 'id', true ),
            'title'             => array( 'title', true ),
            'email'             => array( 'email', true ),
            'user_id'           => array( 'user_id', false ),
        );

        return $sortable_columns;
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
            /*
            case 'user_id';
            case 'ether_address';
            case 'title';
            case 'trade_name';
            case 'business_name';
            case 'type_person';
            case 'cnpj_cpf';
            case 'email';
            case 'address',
            case 'zip_code',
            case 'city';
            case 'state_acronym';
            case 'country';
            case 'status';
            */        
        switch ( $column_name ) {
            case 'user_id';
            case 'ether_address';
            case 'title';
            case 'trade_name';
            case 'business_name';
            case 'type_person';
            case 'cnpj_cpf';
            case 'email';
            case 'address';
            case 'zip_code';
            case 'city';
            case 'state_acronym';
            case 'country';
            case 'status';
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
            $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-survey' );
        }else{
            $delete_nonce = wp_create_nonce( $this->plugin_name . '-trash-survey' );
        }

        $title_name = stripcslashes( $item['title'] );
        //$custom_post_id = isset($item['custom_post_id']) && $item['custom_post_id'] != 0 && $item['custom_post_id'] != '' ? esc_attr($item['custom_post_id']) : 0;

        $q = esc_attr( $title_name );

        $restitle = Survey_Maker_Admin::ays_restriction_string( "word", $title_name, $this->title_length );
        
        $fstatus = '';
        if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
            $fstatus = '&fstatus=' . sanitize_text_field( $_GET['fstatus'] );
        }
        $title = sprintf( '<a href="?page=%s&action=%s&id=%d" title="%s">%s</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ), $q, stripcslashes($item['title']));
        $actions = array();
        if($item['status'] == 'trashed'){
            $title = sprintf( '<strong><a>%s</a></strong>', $restitle );
            $actions['restore'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Restore', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'restore', absint( $item['id'] ), $delete_nonce );
            $actions['delete'] = sprintf( '<a class="ays_confirm_del" data-message="%s" href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Delete Permanently', $this->plugin_name) .'</a>', $restitle, esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce );
        }else{
            $draft_text = '';
            if( $item['status'] == 'draft' && !( isset( $_GET['fstatus'] ) && $_GET['fstatus'] == 'draft' )){
                $draft_text = ' — ' . '<span class="post-state">' . __( "Draft", $this->plugin_name ) . '</span>';
            }
            $title = sprintf( '<strong><a href="?page=%s&action=%s&id=%d" title="%s">%s</a>%s</strong>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ), $q, $restitle, $draft_text );            
            $actions['edit'] = sprintf( '<a href="?page=%s&action=%s&id=%d">'. __('Alterar', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) );
            //$actions['submissions'] = sprintf( '<a href="?page=%s&survey=%d">'. __('View submissions', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ) . '-each-submission', absint( $item['id'] ) );
            //if($custom_post_id > 0){
            //    $actions['custom_posts'] = sprintf( '<a href="%s" target="_blank">'. __('Preview', "survey-maker") .'</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($item['custom_post_id']) ) ));
            //}
            $actions['duplicate'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Duplicar', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'duplicate', absint( $item['id'] ), $delete_nonce );
            $actions['trash'] = sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Mover para lixeira', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'trash', absint( $item['id'] ), $delete_nonce );
        }

        return $title . $this->row_actions( $actions );
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

    public static function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers WHERE status != 'inactive'"; // DIFERENTE DE INATIVO

        return $wpdb->get_var( $sql );
    }

    public static function get_statused_record_count( $status ) {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers WHERE status='" . esc_sql( $status ) . "'";

        return $wpdb->get_var( $sql );
    }
    

    /** Text displayed when no customer data is available */
    // public function no_items() {
    //     Survey_Maker_Data::survey_no_items_list_tables('question categories');
    // }

    public static function get_items_customers( $per_page = 20, $page_number = 1, $search = "") {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers";

        $where = array();

        if ( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != ''){
            $where[] = ' status = "' . esc_sql( sanitize_text_field( $_GET['fstatus'] ) ) . '" ';
        }else{
            $where[] = ' status != "inactive" ';
        }

        if( isset( $_GET['filterbyTradeName'] ) && sanitize_text_field( $_GET['filterbyTradeName'] ) != ""){
            $description_key = sanitize_text_field( $_GET['filterbyTradeName'] );
            
            switch ( $description_key ) {
                case 'with':
                    $where[] = ' `trade_name` != "" ';
                    break;
                case 'without':
                default:
                    $where[] = ' `trade_name` = "" ';
                    break;
            }
        }

        if( $search != '' ){
            $where[] = $search;
        }


        if ( ! empty( $where ) ){
            $sql .= ' WHERE ' . implode( ' AND ', $where );
        }

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


    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_items( $per_page = 20, $page_number = 1, $search = "") {

        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers";

        $where = array();

        if ( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != ''){
            $where[] = ' status = "' . esc_sql( sanitize_text_field( $_GET['fstatus'] ) ) . '" ';
        }else{
            $where[] = ' status != "inactive" ';
        }

        if( isset( $_GET['filterbyTradeName'] ) && sanitize_text_field( $_GET['filterbyTradeName'] ) != ""){
            $description_key = sanitize_text_field( $_GET['filterbyTradeName'] );
            
            switch ( $description_key ) {
                case 'with':
                    $where[] = ' `trade_name` != "" ';
                    break;
                case 'without':
                default:
                    $where[] = ' `trade_name` = "" ';
                    break;
            }
        }

        if( $search != '' ){
            $where[] = $search;
        }


        if ( ! empty( $where ) ){
            $sql .= ' WHERE ' . implode( ' AND ', $where );
        }

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
    
    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {
        global $wpdb;

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $search = ( isset( $_REQUEST['s'] ) ) ? sanitize_text_field( $_REQUEST['s'] ) : false;
        
        $do_search = ( $search ) ? sprintf( " title LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) ) : '';

        $per_page = $this->get_items_per_page('customers_per_page', 20);

        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));
        //$this->items = self::get_orders( $per_page, $current_page );
        $this->items = self::get_items_customers( $per_page, $current_page, $do_search);
    }


    /**
     * Jeferson Carreira
     */
    public static function get_item_by_id( $id ) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers WHERE id=" . esc_sql( absint( $id ) );

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $result;
    }

    public function add_or_edit_item_customer($data){
        global $wpdb;
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers";

        $name_prefix = 'ays_';
        // Save type
        $save_type = (isset($data['save_type'])) ? $data['save_type'] : '';

        // Id of item
        $id = isset( $data['id'] ) ? absint( intval( $data['id'] ) ) : 0;
        error_log( "ID para alteração : " . $id);
        // Title
        $title = isset( $data[ $name_prefix . 'title' ] ) && $data[ $name_prefix . 'title' ] != '' ? stripslashes( sanitize_text_field( $data[ $name_prefix . 'title' ] ) ) : '';

        if($title == ''){
            $url = esc_url_raw( remove_query_arg( false ) );
            wp_redirect( $url );
        }
        // ether_address
        $ether_address = isset( $data[ $name_prefix . 'ether_address' ] ) && $data[ $name_prefix . 'ether_address' ] != '' ? stripslashes( $data[ $name_prefix . 'ether_address' ] ) : '';
        // trade_name
        $trade_name = isset( $data[ $name_prefix . 'trade_name' ] ) && $data[ $name_prefix . 'trade_name' ] != '' ? stripslashes( $data[ $name_prefix . 'trade_name' ] ) : '';
        // business_name
        $business_name = isset( $data[ $name_prefix . 'business_name' ] ) && $data[ $name_prefix . 'business_name' ] != '' ? stripslashes( $data[ $name_prefix . 'business_name' ] ) : '';                        
        // type_person
        $type_person = isset( $data[ $name_prefix . 'type_person' ] ) && $data[ $name_prefix . 'type_person' ] != '' ? $data[ $name_prefix . 'type_person' ] : '';
        // cnpj_cpf
        $cnpj_cpf = isset( $data[ $name_prefix . 'cnpj_cpf' ] ) && $data[ $name_prefix . 'cnpj_cpf' ] != '' ? $data[ $name_prefix . 'cnpj_cpf' ] : '';            
        // email
        $email = isset( $data[ $name_prefix . 'email' ] ) && $data[ $name_prefix . 'email' ] != '' ? $data[ $name_prefix . 'email' ] : '';
        // address
        $address = isset( $data[ $name_prefix . 'address' ] ) && $data[ $name_prefix . 'address' ] != '' ? $data[ $name_prefix . 'address' ] : '';
        // zip_code
        $zip_code = isset( $data[ $name_prefix . 'zip_code' ] ) && $data[ $name_prefix . 'zip_code' ] != '' ? $data[ $name_prefix . 'zip_code' ] : '';
        // city
        $city = isset( $data[ $name_prefix . 'city' ] ) && $data[ $name_prefix . 'city' ] != '' ? $data[ $name_prefix . 'city' ] : '';
        // state_acronym
        $state_acronym = isset( $data[ $name_prefix . 'state_acronym' ] ) && $data[ $name_prefix . 'state_acronym' ] != '' ? $data[ $name_prefix . 'state_acronym' ] : '';
        // country
        $country = isset( $data[ $name_prefix . 'country' ] ) && $data[ $name_prefix . 'country' ] != '' ? $data[ $name_prefix . 'country' ] : '';            
        // user_id
        //$user_id = isset( $data[ $name_prefix . 'user_id' ] ) && $data[ $name_prefix . 'user_id' ] != '' ? $data[ $name_prefix . 'user_id' ] : '';
        $user_id = get_current_user_id();
        // Status
        $status = isset( $data[ $name_prefix . 'status' ] ) && $data[ $name_prefix . 'status' ] != '' ? $data[ $name_prefix . 'status' ] : '';

        // Logotipo
        $logotype = isset( $data[ $name_prefix . 'logotype' ] ) && $data[ $name_prefix . 'logotype' ] != '' ? $data[ $name_prefix . 'logotype' ] : '';

        // Date created
        $date_created = isset( $data[ $name_prefix . 'date_created' ] ) && Survey_Maker_Admin::validateDate( $data[ $name_prefix . 'date_created' ] ) ? $data[ $name_prefix . 'date_created' ] : current_time( 'mysql' );

        // Date modified
        $date_modified = isset( $data[ $name_prefix . 'date_modified' ] ) && Survey_Maker_Admin::validateDate( $data[ $name_prefix . 'date_modified' ] ) ? $data[ $name_prefix . 'date_modified' ] : current_time( 'mysql' );
        
        $message = '';
        if( $id == 0 ){

            $result = $wpdb->insert(
                $table,
                array(
                    'title'               => $title,
                    'ether_address'       => $ether_address,
                    'trade_name'          => $trade_name,
                    'business_name'       => $business_name,
                    'type_person'         => $type_person,
                    'cnpj_cpf'            => $cnpj_cpf,
                    'email'               => $email,
                    'address'             => $address,
                    'zip_code'            => $zip_code,
                    'city'                => $city,
                    'state_acronym'       => $state_acronym,
                    'country'             => $country,
                    'user_id'             => $user_id,
                    'logotype'            => $logotype,
                    'status'              => $status,
                    'date_created'        => $date_created,
                    'date_modified'       => $date_modified,
                ),
                array(
                    '%s', // title
                    '%s', // ether_address
                    '%s', // trade_name
                    '%s', // business_name
                    '%s', // type_person
                    '%s', // cnpj_cpf
                    '%s', // email
                    '%s', // address
                    '%s', // zip_code
                    '%s', // city
                    '%s', // state_acronym
                    '%s', // country
                    '%d', // user_id (assumindo que user_id seja um número inteiro)
                    '%s', // status
                    '%s', // logotipo
                    '%s', // date_created
                    '%s', // date_modified
                )
            );

            $inserted_id = $wpdb->insert_id;
            $message = 'created';                
            if ($result === false) {                    
                error_log( "Erro na inserção: " . $wpdb->last_error );
            } else {
                error_log( "ID Inserido: " . $wpdb->insert_id );
            }
        }else{
            $result = $wpdb->update(
                $table,
                array(
                    'title'               => $title,
                    'ether_address'       => $ether_address,
                    'trade_name'          => $trade_name,
                    'business_name'       => $business_name,
                    'type_person'         => $type_person,
                    'cnpj_cpf'            => $cnpj_cpf,
                    'email'               => $email,
                    'address'             => $address,
                    'zip_code'            => $zip_code,
                    'city'                => $city,
                    'state_acronym'       => $state_acronym,
                    'country'             => $country,
                    'user_id'             => $user_id,
                    'logotype'            => $logotype,
                    'status'              => $status,
                    'date_modified'       => $date_modified,
                ),
                array( 'id' => $id ),
                array(
                    '%s', // title
                    '%s', // ether_address
                    '%s', // trade_name
                    '%s', // business_name
                    '%s', // type_person
                    '%s', // cnpj_cpf
                    '%s', // email
                    '%s', // city
                    '%s', // address
                    '%s', // zip_code
                    '%s', // state_acronym
                    '%s', // country
                    '%d', // user_id
                    '%s', // logotipo
                    '%s', // status
                    '%s', // date_modified
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



    /**
     * Jeferson Carreira
     */
    public function add_or_edit_item($data){
        if( isset( $data["survey_customer_action"] ) && wp_verify_nonce( $data["survey_customer_action"], 'survey_customer_action' ) ){
            $this->add_or_edit_item_customer($data);
        }
    }

    /**
     * Customers
     * Jeferson Carreira
     */
    public function customers_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        $idx = (isset($_REQUEST['id'])) ? sanitize_text_field( $_REQUEST['id'] ) : '';

        if ( empty( $status ) )
            return;

        if ( 'created' == $status )
            $updated_message = esc_html( __( 'Cliente criado. ['.$idx.']', $this->plugin_name ) );
        elseif ( 'updated' == $status )
            $updated_message = esc_html( __( 'Cliente salvo. ['.$idx.']', $this->plugin_name ) );    
        elseif ( 'deleted' == $status )
            $updated_message = esc_html( __( 'Cliente excluído. ['.$idx.']', $this->plugin_name ) );
        elseif ( 'duplicated' == $status )
            $updated_message =  esc_html(__( 'Cliente duplicado.', "survey-maker" ));


        if ( empty( $updated_message ) )
            return;

        ?>
            <div class="notice notice-success is-dismissible">
                <p> <?php echo $updated_message; ?> </p>
            </div>
        <?php        
    }
    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_items( $id ) {
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "customers",
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

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}socialsurv_customers";

        $current_user = get_current_user_id();
        
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $sql = "SELECT COUNT(*) 
                    FROM {$wpdb->prefix}socialsurv_customers AS c
                    WHERE c.user_id = {$current_user}";
        }
        
        return $wpdb->get_var( $sql );
    }

    public function duplicate_customers($id) {
    }

    public function views() {
    }

    public function search_box($search, $plugin_name) {
    }
    

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_reports( $id ) {
        global $wpdb;
        $wpdb->delete(
            "{$wpdb->prefix}socialsurv_customers",
            array( 'id' => $id ),
            array( '%d' )
        );
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        Survey_Maker_Data::survey_no_items_list_tables('customers');
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
        if($item['user_id'] == 0){
            $title = __( "Guest", $this->plugin_name );
        }else{
            $user = get_userdata($item['user_id']);
            $title = $user->data->display_name;
        }
        
        return $title;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'bulk-delete' => 'Delete'
        );

        return $actions;
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        $message = 'deleted';
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-result' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_reports( absint( $_GET['result'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $url = esc_url_raw( remove_query_arg(array('action', 'order', '_wpnonce') ) ) . '&status=' . $message;
                wp_redirect( $url );
            }

        }elseif('see-all' === $this->current_action()){
            $this->ays_see_all_results();
        }


        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_reports( $id );

            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url

            $url = esc_url_raw( remove_query_arg(array('action', 'order', '_wpnonce') ) ) . '&status=' . $message;
            wp_redirect( $url );
        }
    }
    

}
