<?php
ob_start();
class Surveys_List_Table extends WP_List_Table {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The table name in database of the survey categories.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $table_name    The table name in database of the survey categories.
     */
    private $table_name;

    /**
     * The settings object of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $settings_obj    The settings object of this plugin.
     */
    private $settings_obj;

    private $title_length;

    /** Class constructor */
    public function __construct( $plugin_name ) {
        global $wpdb;

        $this->plugin_name = $plugin_name;

        $this->table_name = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);

        $this->title_length = Survey_Maker_Data::get_listtables_title_length('surveys');

        parent::__construct( array(
            'singular' => __( 'Survey', $this->plugin_name ), //singular name of the listed records
            'plural'   => __( 'Surveys', $this->plugin_name ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );

        add_action( 'admin_notices', array( $this, 'survey_notices' ) );
        add_filter( 'default_hidden_columns', array( $this, 'get_hidden_columns'), 10, 2 );

    }

    //SAMPLES
        /**
     * Jeferson Carreira
     */
    public static function get_sample_by_id( $id ) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "samples WHERE id=" . esc_sql( absint( $id ) );

        $result = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $result;
    }

    /**
     * Jeferson Carreira
     */
    public static function get_all_samples( $survey_id ) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "samples WHERE survey_id=" . esc_sql( absint( $survey_id ) );

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }
    

    /**
    * Jeferson Carreira
    */
    public static function delete_sample( $id ) {
        //if( isset( $data["survey_sample_action"] ) && wp_verify_nonce( $data["survey_sample_action"], 'survey_category_action' ) ){
            echo 'Deletando lista id : ' . $id;
            global $wpdb;
            $wpdb->delete(
                $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "samples",
                array( 'id' => $id ),
                array( '%d' )
            );
        //}
    }

    /**
     * Jeferson Carreira
     */
    public static function get_items_samples( $per_page = 20, $page_number = 1, $search = "") {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "samples";

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

    public function edita_sample($data) {
        $this->add_or_edit_item_sample($data);
    }

    public function add_or_edit_item_sample($data){
        global $wpdb;
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "samples";

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
        // description
        $description = isset( $data[ $name_prefix . 'description' ] ) && $data[ $name_prefix . 'description' ] != '' ? stripslashes( $data[ $name_prefix . 'description' ] ) : '';
        // filepath
        $filepath = isset( $data[ $name_prefix . 'filepath' ] ) && $data[ $name_prefix . 'filepath' ] != '' ? stripslashes( $data[ $name_prefix . 'filepath' ] ) : '';
        // fileext
        $fileext = isset( $data[ $name_prefix . 'fileext' ] ) && $data[ $name_prefix . 'fileext' ] != '' ? stripslashes( $data[ $name_prefix . 'fileext' ] ) : '';
        // status
        $status = isset( $data[ $name_prefix . 'status' ] ) && $data[ $name_prefix . 'status' ] != '' ? stripslashes( $data[ $name_prefix . 'status' ] ) : '';
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
                    'description'         => $description,
                    'filepath'            => $filepath,
                    'fileext'             => $fileext,
                    '$status'             => $status,
                    'date_created'        => $date_created,
                    'date_modified'       => $date_modified,
                ),
                array(
                    '%s', // title,
                    '%s', // description,
                    '%s', // filepath,
                    '%s', // fileext,
                    '%s', // status,
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
                    'description'         => $description,
                    'filepath'            => $filepath,
                    'fileext'             => $fileext,
                    '$status'             => $status,
                    'date_modified'       => $date_modified,
                ),
                array( 'id' => $id ),
                array(
                    '%s', // title,
                    '%s', // description,
                    '%s', // filepath,
                    '%s', // fileext,
                    '%s', // status,
                    '%s', // date_created
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
    public function extra_tablenav( $which ) {
        global $wpdb;
        global $wp_version;

        $version1 = $wp_version;
        $operator = '<=';
        $version2 = '5.0';
        $versionCompare = Survey_Maker_Data::aysSurveyMakerVersionCompare($version1, $operator, $version2);

        $titles_sql = "SELECT s.title, s.id
                       FROM " .$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories AS s
                       WHERE s.status = 'published'";
        $cat_titles = $wpdb->get_results($titles_sql);

        $users_sql = "SELECT `author_id`
                      FROM " .$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys
                      GROUP BY author_id";
        $users = $wpdb->get_results($users_sql,"ARRAY_A");


        $cat_id = null;
        if( isset( $_GET['filterby'] )){
            $cat_id = absint( sanitize_text_field( $_GET['filterby'] ) );
        }
        if( isset( $_GET['filterbyuser'] )){            
            $author_id = absint( sanitize_text_field( $_GET['filterbyuser'] ) );
        }

        $categories_select = array();
        foreach($cat_titles as $key => $cat_title){
            $selected = "";
            if( $cat_id === absint( $cat_title->id ) ){
                $selected = "selected";
            }
            $categories_select[$cat_title->id]['title'] = $cat_title->title;
            $categories_select[$cat_title->id]['selected'] = $selected;
            $categories_select[$cat_title->id]['id'] = $cat_title->id;
        }
        sort($categories_select);
        ?>
        <div id="category-filter-div-surveylist" class="alignleft actions bulkactions">
            <select name="filterby-<?php echo esc_attr( $which ); ?>" id="survey-category-filter-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo __('Select Category',$this->plugin_name)?></option>
                <?php
                    foreach($categories_select as $key => $cat_title){
                        echo "<option ".$cat_title['selected']." value='".$cat_title['id']."'>".$cat_title['title']."</option>";
                    }
                ?>
            </select>
        </div>
        <div id="user-filter-div-surveylist" class="alignleft actions bulkactions">
            <select name="filterbyuser-<?php echo esc_attr( $which ); ?>" id="survey-category-filter-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo __('Select Author',$this->plugin_name)?></option>
                <?php
                    foreach($users as $user_key => $user){
                        $user_selected = ( isset($author_id) && $user['author_id'] == $author_id ) ? "selected" : "";
                        $user_data = get_userdata($user['author_id']);
                        $user_name = ($user_data !== false) ? $user_data->data->display_name : "";
                        
                        echo "<option ".$user_selected." value='".$user['author_id']."'>".$user_name."</option>";
                    }
                ?>
            </select>
            <input type="button" id="doaction-<?php echo esc_attr( $which ); ?>" class="user-filter-apply-<?php echo esc_attr( $which ); ?> button ays-survey-question-tab-all-filter-button-<?php echo esc_attr( $which ); ?>" value="Filter">
        </div>        
        <a style="margin: <?php echo ( $versionCompare ? '3px' : '1px' ); ?> 8px 0 0;" href="?page=<?php echo esc_attr( sanitize_text_field( $_REQUEST['page'] ) ); ?>" class="button"><?php echo __( "Clear filters", $this->plugin_name ); ?></a>
        <?php
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
        if( isset( $_GET['fstatus'] ) ){
            switch( sanitize_text_field( $_GET['fstatus'] ) ){
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
    public static function get_items( $per_page = 20, $page_number = 1, $search = '' ) {

        global $wpdb;
        
        $sql = "SELECT * FROM ". $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        
        $where = array();

        if ( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != ''){
            $where[] = ' status = "' . esc_sql( sanitize_text_field( $_GET['fstatus'] ) ) . '" ';
        }else{
            $where[] = ' status != "trashed" ';
        }

        if( $search != '' ){
            $where[] = $search;
        }

        if(! empty( $_REQUEST['filterby'] ) && absint( $_REQUEST['filterby'] ) > 0){
            $cat_id = absint( sanitize_text_field( $_REQUEST['filterby'] ) );
            $where[] = ' FIND_IN_SET( "'.$cat_id.'", `category_ids` ) ';
        }

        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $where[] = ' author_id ='.$user_id;
        }

        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $where[] = " author_id = ".$current_user." ";
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
                $sql .= ' ORDER BY ordering DESC';
            }
        }else{
            $sql .= ' ORDER BY ordering DESC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public function get_categories(){
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories WHERE status='published' ORDER BY title ASC";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public static function get_item_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE id=" . absint( $id );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }

    public function add_or_edit_item(){
        global $wpdb;
        $table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );
        $sections_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections" );
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions" );
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers" );

        if( isset( $_POST["survey_action"] ) && wp_verify_nonce( sanitize_text_field( $_POST["survey_action"] ), 'survey_action' ) ){

            $name_prefix = 'ays_';
            
            // Save type
            $save_type = (isset($_POST['save_type'])) ? sanitize_text_field( $_POST['save_type'] ) : '';

            // Id of item
            $id = isset( $_POST['id'] ) ? absint( sanitize_text_field( $_POST['id'] ) ) : 0;

            // Ordering
            $max_id = $this->get_max_id();
            $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
            
            // Author ID
            $user_id = get_current_user_id();
            $author_id = isset( $_POST[ $name_prefix . 'author_id' ] ) && $_POST[ $name_prefix . 'author_id' ] != '' ? intval( sanitize_text_field( $_POST[ $name_prefix . 'author_id' ] ) ) : $user_id;

            // Title
            $title = isset( $_POST[ $name_prefix . 'title' ] ) && $_POST[ $name_prefix . 'title' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'title' ] ) ) : '';

            if($title == ''){
                $url = esc_url_raw( remove_query_arg( false ) );
                $url = esc_url_raw( add_query_arg( array(
                    'status' => 'empty-title'
                ), $url ) );
                wp_redirect( $url );
            }

            // Description
            $description = ''; //isset( $_POST[ $name_prefix . 'description' ] ) && $_POST[ $name_prefix . 'description' ] != '' ? stripslashes( wp_kses_post( $_POST[ $name_prefix . 'description' ] ) ) : '';

            // Status
            $status = isset( $_POST[ $name_prefix . 'status' ] ) && $_POST[ $name_prefix . 'status' ] != '' ? sanitize_text_field( $_POST[ $name_prefix . 'status' ] ) : 'published';

            // Trash status
            $trash_status = '';
            
            // Date created
            // $date_created = isset( $_POST[ $name_prefix . 'date_created' ] ) && Survey_Maker_Admin::validateDate( $_POST[ $name_prefix . 'date_created' ] ) ? sanitize_text_field( $_POST[ $name_prefix . 'date_created' ] ) : current_time( 'mysql' );
            $date_created = isset( $_POST[ $name_prefix . 'survey_change_creation_date' ] ) && Survey_Maker_Admin::validateDate( $_POST[ $name_prefix . 'survey_change_creation_date' ] ) ? sanitize_text_field( $_POST[ $name_prefix . 'survey_change_creation_date' ] ) : current_time( 'mysql' );
            
            // Date modified
            $date_modified = isset( $_POST[ $name_prefix . 'date_modified' ] ) && Survey_Maker_Admin::validateDate( $_POST[ $name_prefix . 'date_modified' ] ) ? sanitize_text_field( $_POST[ $name_prefix . 'date_modified' ] ) : current_time( 'mysql' );

            // Survey categories IDs
            $category_ids = isset( $_POST[ $name_prefix . 'category_ids' ] ) && $_POST[ $name_prefix . 'category_ids' ] != '' ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'category_ids' ] ) : array();
            $category_ids = empty( $category_ids ) ? '' : implode( ',', $category_ids );

            // Survey questions IDs
            $question_ids = isset( $_POST[ $name_prefix . 'question_ids' ] ) && $_POST[ $name_prefix . 'question_ids' ] != '' ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'question_ids' ] ) : array();
            // $question_ids = empty( $question_ids ) ? '' : implode( ',', $question_ids );

            // Survey image
            $image = isset( $_POST[ $name_prefix . 'image' ] ) && $_POST[ $name_prefix . 'image' ] != '' ? sanitize_text_field( $_POST[ $name_prefix . 'image' ] ) : '';

            // Section ids
            $section_ids = (isset( $_POST[ $name_prefix . 'sections_ids' ] ) && $_POST[ $name_prefix . 'sections_ids' ] != '') ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'sections_ids' ] ) : array();


            // =======================  //  ======================= // ======================= // ======================= // ======================= //

            // =============================================================
            // ======================    Styles Tab    =====================
            // ========================    START    ========================


                // Survey Theme
                $survey_theme = (isset( $_POST[ $name_prefix . 'survey_theme' ] ) && $_POST[ $name_prefix . 'survey_theme' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_theme' ] ) ) : 'classic_light';

                // Survey Color
                $survey_color = (isset( $_POST[ $name_prefix . 'survey_color' ] ) && $_POST[ $name_prefix . 'survey_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_color' ] ) ) : '#ff5722';

                // Background color
                $survey_background_color = (isset( $_POST[ $name_prefix . 'survey_background_color' ] ) && $_POST[ $name_prefix . 'survey_background_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_background_color' ] ) ) : '#fff';

                // Text Color
                $survey_text_color = (isset( $_POST[ $name_prefix . 'survey_text_color' ] ) && $_POST[ $name_prefix . 'survey_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_text_color' ] ) ) : '#333';

                // Buttons text Color
                $survey_buttons_text_color = (isset( $_POST[ $name_prefix . 'survey_buttons_text_color' ] ) && $_POST[ $name_prefix . 'survey_buttons_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_text_color' ] ) ) : '#333';

                // Width
                $survey_width = (isset( $_POST[ $name_prefix . 'survey_width' ] ) && $_POST[ $name_prefix . 'survey_width' ] != '') ? absint( intval( $_POST[ $name_prefix . 'survey_width' ] ) ) : '';

                // Survey Width by percentage or pixels
                $survey_width_by_percentage_px = (isset( $_POST[ $name_prefix . 'survey_width_by_percentage_px' ] ) && $_POST[ $name_prefix . 'survey_width_by_percentage_px' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_width_by_percentage_px' ] ) ) : 'pixels';

                // Mobile width
                $survey_mobile_width = (isset( $_POST[ $name_prefix . 'survey_mobile_width' ] ) && $_POST[ $name_prefix . 'survey_mobile_width' ] != '') ? absint( intval( $_POST[ $name_prefix . 'survey_mobile_width' ] ) ) : '';

                // Survey mobile width by percentage or pixels
                $survey_mobile_width_by_percentage_px = (isset( $_POST[ $name_prefix . 'survey_mobile_width_by_percentage_px' ] ) && $_POST[ $name_prefix . 'survey_mobile_width_by_percentage_px' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_mobile_width_by_percentage_px' ] ) ) : 'pixels';

                // Survey container Max-Width
                $survey_mobile_max_width = (isset( $_POST[ $name_prefix . 'mobile_max_width' ] ) && $_POST[ $name_prefix . 'mobile_max_width' ] != '') ? absint( intval( $_POST[ $name_prefix . 'mobile_max_width' ] ) ) : '';

                // Custom class for survey container
                $survey_custom_class = (isset( $_POST[ $name_prefix . 'survey_custom_class' ] ) && $_POST[ $name_prefix . 'survey_custom_class' ] != '') ? stripslashes( esc_attr( $_POST[ $name_prefix . 'survey_custom_class' ] ) ) : ''; 

                // Custom CSS
                $survey_custom_css = (isset( $_POST[ $name_prefix . 'survey_custom_css' ] ) && $_POST[ $name_prefix . 'survey_custom_css' ] != '') ? stripslashes( esc_attr( $_POST[ $name_prefix . 'survey_custom_css' ] ) ) : '';

                // Survey logo
                $survey_logo = (isset( $_POST[ $name_prefix . 'survey_logo' ]) && $_POST[ $name_prefix . 'survey_logo' ] != '') ? stripslashes ( esc_attr( $_POST[ $name_prefix . 'survey_logo' ] ) ) : '';

                // Survey Logo url
                $survey_logo_image_url = (isset( $_POST[ $name_prefix . 'survey_logo_image_url' ] ) && $_POST[ $name_prefix . 'survey_logo_image_url' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_logo_image_url' ] ) : '';
                $survey_logo_image_url_check = (isset( $_POST[ $name_prefix . 'survey_logo_enable_image_url' ] ) && $_POST[ $name_prefix . 'survey_logo_enable_image_url' ] == 'on') ? 'on' : 'off';
                $survey_logo_url_new_tab = (isset( $_POST[ $name_prefix . 'survey_logo_enable_image_url_new_tab' ] ) && $_POST[ $name_prefix . 'survey_logo_enable_image_url_new_tab' ] == 'on') ? "on" : 'off';

                // Survey Logo position
                $survey_logo_image_position = (isset( $_POST[ $name_prefix . 'survey_logo_pos' ] ) && $_POST[ $name_prefix . 'survey_logo_pos' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_logo_pos' ] ) : 'right';

                // Survey Logo title
                $survey_logo_title = (isset( $_POST[ $name_prefix . 'survey_logo_title' ] ) && $_POST[ $name_prefix . 'survey_logo_title' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_logo_title' ] ) : '';

                // Survey title alignment
                $survey_title_alignment = (isset( $_POST[ $name_prefix . 'survey_title_alignment' ] ) && $_POST[ $name_prefix . 'survey_title_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_title_alignment' ] ) : 'left';

                // Survey title font size
                $survey_title_font_size = (isset( $_POST[ $name_prefix . 'survey_title_font_size' ] ) && $_POST[ $name_prefix . 'survey_title_font_size' ] != '' && $_POST[ $name_prefix . 'survey_title_font_size' ] != '0' ) ? absint(intval(sanitize_text_field( $_POST[ $name_prefix . 'survey_title_font_size' ] ))) : '30';

                // Survey title font size mobile
                $survey_title_font_size_for_mobile = (isset( $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] ) && $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] != '' && $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] != '0' ) ? absint(intval(sanitize_text_field( $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] ))) : '30';


                // Survey title box shadow
                $survey_title_box_shadow_enable = (isset( $_POST[ $name_prefix . 'survey_title_box_shadow_enable' ] ) && $_POST[ $name_prefix . 'survey_title_box_shadow_enable' ] == 'on' ) ? 'on' : 'off';

                // Survey title box shadow color
                $survey_title_box_shadow_color = (isset( $_POST[ $name_prefix . 'survey_title_box_shadow_color' ] ) && $_POST[ $name_prefix . 'survey_title_box_shadow_color' ] != '' ) ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_title_box_shadow_color' ] ) ) : '#333';

                // === Survey title box shadow offsets start ===
                    // Survey title box shadow offset x
                    $survey_title_text_shadow_x_offset = (isset( $_POST[ $name_prefix . 'title_text_shadow_x_offset' ] ) && $_POST[ $name_prefix . 'title_text_shadow_x_offset' ] != '' ) ? intval( $_POST[ $name_prefix . 'title_text_shadow_x_offset' ] )  : 0;
                    // Survey title box shadow offset y
                    $survey_title_text_shadow_y_offset = (isset( $_POST[ $name_prefix . 'title_text_shadow_y_offset' ] ) && $_POST[ $name_prefix . 'title_text_shadow_y_offset' ] != '' ) ? intval( $_POST[ $name_prefix . 'title_text_shadow_y_offset' ] )  : 0;
                    // Survey title box shadow offset z
                    $survey_title_text_shadow_z_offset = (isset( $_POST[ $name_prefix . 'title_text_shadow_z_offset' ] ) && $_POST[ $name_prefix . 'title_text_shadow_z_offset' ] != '' ) ? intval( $_POST[ $name_prefix . 'title_text_shadow_z_offset' ] )  : 10;
                // === Survey title box shadow offsets end ===

                // Survey section title font size PC
                $survey_section_title_font_size = (isset( $_POST[ $name_prefix . 'survey_section_title_font_size' ] ) && $_POST[ $name_prefix . 'survey_section_title_font_size' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_title_font_size' ] )) : 32;

                // Survey section title font size Mobile
                $survey_section_title_font_size_mobile = (isset( $_POST[ $name_prefix . 'survey_section_title_font_size_mobile' ] ) && $_POST[ $name_prefix . 'survey_section_title_font_size_mobile' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_title_font_size_mobile' ] )) : 32;

                // Survey section title alignment
                $survey_section_title_alignment = (isset( $_POST[ $name_prefix . 'survey_section_title_alignment' ] ) && $_POST[ $name_prefix . 'survey_section_title_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_section_title_alignment' ] ) : 'left';

                // Survey section description alignment
                $survey_section_description_alignment = (isset( $_POST[ $name_prefix . 'survey_section_description_alignment' ] ) && $_POST[ $name_prefix . 'survey_section_description_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_section_description_alignment' ] ) : 'left';

                // Survey section description font size
                $survey_section_description_font_size = (isset( $_POST[ $name_prefix . 'survey_section_description_font_size' ] ) && $_POST[ $name_prefix . 'survey_section_description_font_size' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_description_font_size' ] )) : '14';

                // Survey section description font size mobile
                $survey_section_description_font_size_mobile = (isset( $_POST[ $name_prefix . 'survey_section_description_font_size_mobile' ] ) && $_POST[ $name_prefix . 'survey_section_description_font_size_mobile' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_description_font_size_mobile' ] )) : '14';

                // Survey cover photo
                $survey_cover_photo = (isset( $_POST[ $name_prefix . 'survey_cover_photo' ]) && $_POST[ $name_prefix . 'survey_cover_photo' ] != '') ? stripslashes ( esc_attr( $_POST[ $name_prefix . 'survey_cover_photo' ] ) ) : '';

                // Survey cover photo height
                $survey_cover_photo_height = (isset( $_POST[ $name_prefix . 'survey_cover_image_height' ]) && $_POST[ $name_prefix . 'survey_cover_image_height' ] != '') ? absint(intval(( sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_image_height' ])))) : 150;

                // Survey cover photo mobile height
                $survey_cover_photo_mobile_height = (isset( $_POST[ $name_prefix . 'survey_cover_photo_mobile_height' ]) && $_POST[ $name_prefix . 'survey_cover_photo_mobile_height' ] != '') ? absint(intval(( sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_photo_mobile_height' ])))) : 150;

                // Survey cover photo position
                $survey_cover_photo_position = (isset( $_POST[ $name_prefix . 'survey_cover_image_pos' ]) && $_POST[ $name_prefix . 'survey_cover_image_pos' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_image_pos' ]) : "center_center";

                // Survey cover photo object fit
                $survey_cover_photo_object_fit = (isset( $_POST[ $name_prefix . 'survey_cover_photo_object_fit' ]) && $_POST[ $name_prefix . 'survey_cover_photo_object_fit' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_photo_object_fit' ]) : "cover";

                // Survey cover only first section
                $survey_cover_only_first_section = (isset( $_POST[ $name_prefix . 'survey_cover_only_first_section' ] ) && $_POST[ $name_prefix . 'survey_cover_only_first_section' ] == 'on') ? 'on' : 'off';

                // =========== Questions Styles Start ===========

                    // Question font size
                    $survey_question_font_size = (isset( $_POST[ $name_prefix . 'survey_question_font_size' ] ) && $_POST[ $name_prefix . 'survey_question_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_font_size' ] ) ) : 16;

                    // Question font size mobile
                    $survey_question_font_size_mobile = (isset( $_POST[ $name_prefix . 'survey_question_font_size_mobile' ] ) && $_POST[ $name_prefix . 'survey_question_font_size_mobile' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_font_size_mobile' ] ) ) : 16;

                    // Question title alignment 
                    $survey_question_title_alignment = (isset( $_POST[ $name_prefix . 'survey_question_title_alignment' ] ) && $_POST[ $name_prefix . 'survey_question_title_alignment' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_title_alignment' ] ) ) : 'left';

                    // Question Image Width
                    $survey_question_image_width = (isset( $_POST[ $name_prefix . 'survey_question_image_width' ] ) && $_POST[ $name_prefix . 'survey_question_image_width' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_image_width' ] ) ) : '';

                    // Question Image Height
                    $survey_question_image_height = (isset( $_POST[ $name_prefix . 'survey_question_image_height' ] ) && $_POST[ $name_prefix . 'survey_question_image_height' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_image_height' ] ) ) : '';

                    // Question Image sizing
                    $survey_question_image_sizing = (isset( $_POST[ $name_prefix . 'survey_question_image_sizing' ] ) && $_POST[ $name_prefix . 'survey_question_image_sizing' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_image_sizing' ] ) ) : 'cover';

                    // Question padding
                    $survey_question_padding = (isset( $_POST[ $name_prefix . 'survey_question_padding' ] ) && $_POST[ $name_prefix . 'survey_question_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_padding' ] ) ) : 24;
                
                    // Question caption text color
                    $survey_question_caption_text_color = (isset( $_POST[ $name_prefix . 'survey_question_caption_text_color' ] ) && $_POST[ $name_prefix . 'survey_question_caption_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_text_color' ] ) ) : $survey_text_color;

                    // Question caption text alignment
                    $survey_question_caption_text_alignment = (isset( $_POST[ $name_prefix . 'survey_question_caption_text_alignment' ] ) && $_POST[ $name_prefix . 'survey_question_caption_text_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_text_alignment' ] ) : 'center';

                    // Question caption font size
                    $survey_question_caption_font_size = (isset( $_POST[ $name_prefix . 'survey_question_caption_font_size' ] ) && $_POST[ $name_prefix . 'survey_question_caption_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_font_size' ] ) ) : 16;

                    // Question caption font size on mobile
                    $survey_question_caption_font_size_on_mobile = (isset( $_POST[ $name_prefix . 'survey_question_caption_font_size_on_mobile' ] ) && $_POST[ $name_prefix . 'survey_question_caption_font_size_on_mobile' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_font_size_on_mobile' ] ) ) : 16;

                    // Question caption text transform
                    $survey_question_caption_text_transform = (isset( $_POST[ $name_prefix . 'survey_question_caption_text_transform' ] ) && $_POST[ $name_prefix . 'survey_question_caption_text_transform' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_text_transform' ] ) : 'none';

                // =========== Questions Styles End   ===========



                // =========== Answers Styles Start ===========

                    // Answer font size
                    $survey_answer_font_size = (isset( $_POST[ $name_prefix . 'survey_answer_font_size' ] ) && $_POST[ $name_prefix . 'survey_answer_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answer_font_size' ] ) ) : 15;

                    // Answer font size mobile
                    $survey_answer_font_size_on_mobile = (isset( $_POST[ $name_prefix . 'survey_answer_font_size_on_mobile' ] ) && $_POST[ $name_prefix . 'survey_answer_font_size_on_mobile' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answer_font_size_on_mobile' ] ) ) : 15;

                    // Answer view
                    $survey_answers_view = (isset( $_POST[ $name_prefix . 'survey_answers_view' ] ) && $_POST[ $name_prefix . 'survey_answers_view' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_view' ] ) ) : 'list';

                    // Answer view alignment
                    $survey_answers_view_alignment = (isset( $_POST[ $name_prefix . 'survey_answers_view_alignment' ] ) && $_POST[ $name_prefix . 'survey_answers_view_alignment' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_view_alignment' ] ) ) : 'space-around';

                    // Answer view grid column
                    $survey_grid_view_count = (isset( $_POST[ $name_prefix . 'survey_grid_view_count' ] ) && $_POST[ $name_prefix . 'survey_grid_view_count' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_grid_view_count' ] ) ) : 'list';

                    // Answer object-fit
                    $survey_answers_object_fit = (isset( $_POST[ $name_prefix . 'survey_answers_object_fit' ] ) && $_POST[ $name_prefix . 'survey_answers_object_fit' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_object_fit' ] ) ) : 'cover';

                    // Answer padding
                    $survey_answers_padding = (isset( $_POST[ $name_prefix . 'survey_answers_padding' ] ) && $_POST[ $name_prefix . 'survey_answers_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_padding' ] ) ) : 8;

                    // Answer Gap
                    $survey_answers_gap = (isset( $_POST[ $name_prefix . 'survey_answers_gap' ] ) && $_POST[ $name_prefix . 'survey_answers_gap' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_gap' ] ) ) : 0;

                    // Answer image size
                    $survey_answers_image_size = (isset( $_POST[ $name_prefix . 'survey_answers_image_size' ] ) && $_POST[ $name_prefix . 'survey_answers_image_size' ] != '' && $_POST[ $name_prefix . 'survey_answers_image_size' ] != 0) ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_image_size' ] ) ) : 195;
                    
                    // Stars Color
                    $survey_stars_color = (isset( $_POST[ $name_prefix . 'survey_stars_color' ] ) && $_POST[ $name_prefix . 'survey_stars_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_stars_color' ] ) ) : '#fc0';
                    
                    // Slider questions bubble colors
                    $survey_slider_question_bubble_bg_color   = (isset( $_POST[ $name_prefix . 'survey_slider_question_bubble_bg_color' ] ) && $_POST[ $name_prefix . 'survey_slider_question_bubble_bg_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_slider_question_bubble_bg_color' ] ) ) : '#ff5722';                    
                    $survey_slider_question_bubble_text_color = (isset( $_POST[ $name_prefix . 'survey_slider_question_bubble_text_color' ] ) && $_POST[ $name_prefix . 'survey_slider_question_bubble_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_slider_question_bubble_text_color' ] ) ) : '#333';

                // =========== Answers Styles End   ===========



                // =========== Buttons Styles Start ===========

                    // Buttons background color
                    $survey_buttons_bg_color = (isset( $_POST[ $name_prefix . 'survey_button_bg_color' ] ) && $_POST[ $name_prefix . 'survey_button_bg_color' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_button_bg_color' ] ) : '#fff';

                    // Buttons size
                    $survey_buttons_size = (isset( $_POST[ $name_prefix . 'survey_buttons_size' ] ) && $_POST[ $name_prefix . 'survey_buttons_size' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_size' ] ) ) : 'medium';

                    // Buttons font size
                    $survey_buttons_font_size = (isset( $_POST[ $name_prefix . 'survey_buttons_font_size' ] ) && $_POST[ $name_prefix . 'survey_buttons_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_font_size' ] ) ) : 14;

                    // Buttons mobile font size
                    $survey_buttons_mobile_font_size = (isset( $_POST[ $name_prefix . 'survey_buttons_mobile_font_size' ] ) && $_POST[ $name_prefix . 'survey_buttons_mobile_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_mobile_font_size' ] ) ) : 14;

                    // Buttons Left / Right padding
                    $survey_buttons_left_right_padding = (isset( $_POST[ $name_prefix . 'survey_buttons_left_right_padding' ] ) && $_POST[ $name_prefix . 'survey_buttons_left_right_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_left_right_padding' ] ) ) : 24;

                    // Buttons Top / Bottom padding
                    $survey_buttons_top_bottom_padding = (isset( $_POST[ $name_prefix . 'survey_buttons_top_bottom_padding' ] ) && $_POST[ $name_prefix . 'survey_buttons_top_bottom_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_top_bottom_padding' ] ) ) : 0;

                    // Buttons border radius
                    $survey_buttons_border_radius = (isset( $_POST[ $name_prefix . 'survey_buttons_border_radius' ] ) && $_POST[ $name_prefix . 'survey_buttons_border_radius' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_border_radius' ] ) ) : 4;

                    // Buttons alignment
                    $survey_buttons_alignment = (isset( $_POST[ $name_prefix . 'survey_buttons_alignment' ] ) && $_POST[ $name_prefix . 'survey_buttons_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_alignment' ] ) : 'left';

                    // Buttons top distance
                    $survey_buttons_top_distance = (isset( $_POST[ $name_prefix . 'survey_buttons_top_distance' ] ) && $_POST[ $name_prefix . 'survey_buttons_top_distance' ] != '') ? absint( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_top_distance' ] ) ) : 10;

                // ===========  Buttons Styles End  ===========

                // =========== Admin note Styles Start ===========

                    // Admin note color
                    $survey_admin_note_color = (isset( $_POST[ $name_prefix . 'survey_admin_note_color' ] ) && $_POST[ $name_prefix . 'survey_admin_note_color' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_admin_note_color' ] ) : '#000000';

                    // Admin note text transform size
                    $survey_admin_note_text_transform = (isset( $_POST[ $name_prefix . 'survey_admin_note_text_transform' ] ) && $_POST[ $name_prefix . 'survey_admin_note_text_transform' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_admin_note_text_transform' ] )  : 'none';

                    // Admin note font size
                    $survey_admin_note_font_size = (isset( $_POST[ $name_prefix . 'survey_admin_note_font_size' ] ) && $_POST[ $name_prefix . 'survey_admin_note_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_admin_note_font_size' ] ) ) : 11;

                // ===========  Admin note Styles End  ===========


            // =============================================================
            // ======================    Styles Tab    =====================
            // ========================     END     ========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // =====================  Start page Tab  ======================
            // ========================    START   =========================

                // Start page Title
                $survey_start_page_title = isset( $_POST[ $name_prefix . 'survey_start_page_title' ] ) && $_POST[ $name_prefix . 'survey_start_page_title' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_start_page_title' ] ) ) : '';

                // Start page description
                $survey_start_page_description = (isset( $_POST[ $name_prefix . 'survey_start_page_description' ] ) && $_POST[ $name_prefix . 'survey_start_page_description' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_start_page_description' ] ) : '';

                // Start page Background color
                $survey_start_page_background_color = (isset( $_POST[ $name_prefix . 'survey_start_page_background_color' ] ) && $_POST[ $name_prefix . 'survey_start_page_background_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_start_page_background_color' ] ) ) : '#fff';

                // Start page Text Color
                $survey_start_page_text_color = (isset( $_POST[ $name_prefix . 'survey_start_page_text_color' ] ) && $_POST[ $name_prefix . 'survey_start_page_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_start_page_text_color' ] ) ) : '#333';

                // Start page Custom class
                $survey_start_page_custom_class = (isset( $_POST[ $name_prefix . 'survey_start_page_custom_class' ] ) && $_POST[ $name_prefix . 'survey_start_page_custom_class' ] != '') ? stripslashes( esc_attr( $_POST[ $name_prefix . 'survey_start_page_custom_class' ] ) ) : '';
            

            // =============================================================
            // ====================    Start page Tab    ===================
            // ========================     END     ========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // ======================  Settings Tab  =======================
            // ========================    START   =========================

                // Show survey title
                $survey_show_title = (isset( $_POST[ $name_prefix . 'survey_show_title' ] ) && $_POST[ $name_prefix . 'survey_show_title' ] == 'on') ? 'on' : 'off';

                // Show survey section header
                $survey_show_section_header = (isset( $_POST[ $name_prefix . 'survey_show_section_header' ] ) && $_POST[ $name_prefix . 'survey_show_section_header' ] == 'on') ? 'on' : 'off';

                // Enable start page
                $survey_enable_start_page = (isset( $_POST[ $name_prefix . 'survey_enable_start_page' ] ) && $_POST[ $name_prefix . 'survey_enable_start_page' ] == 'on') ? 'on' : 'off';

                // Enable randomize answers
                $survey_enable_randomize_answers = (isset( $_POST[ $name_prefix . 'survey_enable_randomize_answers' ] ) && $_POST[ $name_prefix . 'survey_enable_randomize_answers' ] == 'on') ? 'on' : 'off';

                // Enable randomize questions
                $survey_enable_randomize_questions = (isset( $_POST[ $name_prefix . 'survey_enable_randomize_questions' ] ) && $_POST[ $name_prefix . 'survey_enable_randomize_questions' ] == 'on') ? 'on' : 'off';

                // Enable randomize questions
                $survey_enable_rtl_direction = (isset( $_POST[ $name_prefix . 'survey_enable_rtl_direction' ] ) && $_POST[ $name_prefix . 'survey_enable_rtl_direction' ] == 'on') ? 'on' : 'off';

                // Enable confirmation box for leaving the page
                $survey_enable_leave_page = (isset( $_POST[ $name_prefix . 'survey_enable_leave_page' ] ) && $_POST[ $name_prefix . 'survey_enable_leave_page' ] == 'on') ? 'on' : 'off';

                // Enable clear answer button
                $survey_enable_clear_answer = (isset( $_POST[ $name_prefix . 'survey_enable_clear_answer' ] ) && $_POST[ $name_prefix . 'survey_enable_clear_answer' ] == 'on') ? 'on' : 'off';

                // Enable previous button
                $survey_enable_previous_button = (isset( $_POST[ $name_prefix . 'survey_enable_previous_button' ] ) && $_POST[ $name_prefix . 'survey_enable_previous_button' ] == 'on') ? 'on' : 'off';

                // Enable Survey Start loader
                $survey_enable_survey_start_loader = (isset( $_POST[ $name_prefix . 'survey_enable_survey_start_loader' ] ) && $_POST[ $name_prefix . 'survey_enable_survey_start_loader' ] == 'on') ? 'on' : 'off';
                $survey_before_start_loader = (isset( $_POST[ $name_prefix . 'survey_before_start_loader' ] ) && $_POST[ $name_prefix . 'survey_before_start_loader' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_before_start_loader' ] ) ) : 'default';

                // Allow HTML in answers
                $survey_allow_html_in_answers = (isset( $_POST[ $name_prefix . 'survey_allow_html_in_answers' ] ) && $_POST[ $name_prefix . 'survey_allow_html_in_answers' ] == 'on') ? 'on' : 'off';

                // Allow HTML in section description
                $survey_allow_html_in_section_description = (isset( $_POST[ $name_prefix . 'survey_allow_html_in_section_description' ] ) && $_POST[ $name_prefix . 'survey_allow_html_in_section_description' ] == 'on') ? 'on' : 'off';

                // Auto numbering
                $survey_auto_numbering = (isset( $_POST[ $name_prefix . 'survey_show_answers_numbering' ] ) && $_POST[ $name_prefix . 'survey_show_answers_numbering' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_show_answers_numbering' ] ) : 'none';
                
                // Auto numbering questions
                $survey_auto_numbering_questions = (isset( $_POST[ $name_prefix . 'survey_show_question_numbering' ] ) && $_POST[ $name_prefix . 'survey_show_question_numbering' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_show_question_numbering' ] ) : 'none';
                
                // Auto numbering questions
                $survey_enable_question_numbering_by_sections = (isset( $_POST[ $name_prefix . 'survey_enable_question_numbering_by_sections' ] ) && $_POST[ $name_prefix . 'survey_enable_question_numbering_by_sections' ] == 'on') ? 'on' : 'off';

                // Autofill information
                $survey_i_autofill = (isset( $_POST[ $name_prefix . 'survey_enable_i_autofill' ] ) && $_POST[ $name_prefix . 'survey_enable_i_autofill' ] == 'on') ? 'on' : 'off';

                // Allow collecting information of logged in users
                $survey_allow_collecting_logged_in_users_data = (isset( $_POST[ $name_prefix . 'survey_allow_collecting_logged_in_users_data' ] ) && $_POST[ $name_prefix . 'survey_allow_collecting_logged_in_users_data' ] == 'on') ? 'on' : 'off';

                $survey_main_url = (isset( $_POST[ $name_prefix . 'survey_main_url' ] ) && $_POST[ $name_prefix . 'survey_main_url' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_main_url' ] ) : '';

                // Enable copy protection
                $enable_copy_protection = (isset( $_POST[ $name_prefix . 'survey_enable_copy_protection' ] ) && $_POST[ $name_prefix . 'survey_enable_copy_protection' ] == 'on') ? 'on' : 'off';

                // Enable expand/collapse question
                $enable_expand_collapse_question = (isset( $_POST[ $name_prefix . 'survey_enable_expand_collapse_question' ] ) && $_POST[ $name_prefix . 'survey_enable_expand_collapse_question' ] == 'on') ? 'on' : 'off';

                // Questions text to speech enable
                $survey_question_text_to_speech = (isset( $_POST[ $name_prefix . 'survey_question_text_to_speech' ] ) && $_POST[ $name_prefix . 'survey_question_text_to_speech' ] == 'on') ? 'on' : 'off';
                
                // Survey full screen mode
                $survey_full_screen = isset( $_POST[ $name_prefix . 'survey_enable_full_screen_mode' ] ) && $_POST[ $name_prefix . 'survey_enable_full_screen_mode' ] == 'on' ? 'on' : 'off';

                $survey_full_screen_button_color = isset( $_POST[ $name_prefix . 'survey_full_screen_button_color' ] ) && $_POST[ $name_prefix . 'survey_full_screen_button_color' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_full_screen_button_color' ] ) ) : '#333';

                // Survey progress bar
                $survey_enable_progress_bar = isset( $_POST[ $name_prefix . 'survey_enable_progres_bar' ] ) && $_POST[ $name_prefix . 'survey_enable_progres_bar' ] == 'on' ? 'on' : 'off';
                $survey_hide_section_pagination_text = isset( $_POST[ $name_prefix . 'survey_hide_section_pagination_text' ] ) && $_POST[ $name_prefix . 'survey_hide_section_pagination_text' ] == 'on' ? 'on' : 'off';
                $survey_pagination_positioning = isset( $_POST[ $name_prefix . 'survey_pagination_positioning' ] ) && $_POST[ $name_prefix . 'survey_pagination_positioning' ] != '' ? sanitize_text_field($_POST[ $name_prefix . 'survey_pagination_positioning' ]) : 'none';
                $survey_hide_section_bar = isset( $_POST[ $name_prefix . 'survey_hide_section_bar' ] ) && $_POST[ $name_prefix . 'survey_hide_section_bar' ] == 'on' ? 'on' : 'off';
                $survey_progress_bar_text = isset( $_POST[ $name_prefix . 'survey_progress_bar_text' ] ) && $_POST[ $name_prefix . 'survey_progress_bar_text' ] != '' ? sanitize_text_field($_POST[ $name_prefix . 'survey_progress_bar_text' ]) : 'Page';
                $survey_pagination_text_color = isset( $_POST[ $name_prefix . 'survey_pagination_text_color' ] ) && $_POST[ $name_prefix . 'survey_pagination_text_color' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_pagination_text_color' ] ) ) : '#333';

                // Survey show sections questions count
                $survey_show_sections_questions_count = isset( $_POST[ $name_prefix . 'survey_show_questions_count' ] ) && $_POST[ $name_prefix . 'survey_show_questions_count' ] == 'on' ? 'on' : 'off';

                // Survey required questions message
                $survey_required_questions_message = isset( $_POST[ $name_prefix . 'survey_required_questions_message' ] ) && $_POST[ $name_prefix . 'survey_required_questions_message' ] != '' ? sanitize_text_field($_POST[ $name_prefix . 'survey_required_questions_message' ]) : '';

                // Enable chat mode
                $enable_chat_mode = (isset( $_POST[ $name_prefix . 'survey_enable_chat_mode' ] ) && $_POST[ $name_prefix . 'survey_enable_chat_mode' ] == 'on') ? 'on' : 'off';

                // Change the author of the current quiz
                $survey_change_create_author = ( isset($_POST[$name_prefix . 'survey_change_create_author' ]) && $_POST[$name_prefix . 'survey_change_create_author'] != "" ) ? absint( sanitize_text_field( $_POST[$name_prefix . 'survey_change_create_author'] ) ) : '';

                if ( $survey_change_create_author != "" && $survey_change_create_author > 0 ) {
                    $user = get_userdata($survey_change_create_author);
                    if ( ! is_null( $user ) && $user ) {
                        $survey_author = array(
                            'id' => $user->ID."",
                            'name' => $user->data->display_name
                        );
                        $author_id = $survey_author['id'];

                    } else {
                        $author_data = json_decode($author, true);
                        $survey_change_create_author = (isset( $author_data['id'] ) && $author_data['id'] != "") ? absint( sanitize_text_field( $author_data['id'] ) ) : get_current_user_id();
                        $author_id = $survey_change_create_author;
                    }
                }

                // Terms and Conditions
                $enable_terms_and_conditions = (isset( $_POST[ $name_prefix . 'survey_terms_and_conditions' ] ) && $_POST[ $name_prefix . 'survey_terms_and_conditions' ] == 'on') ? 'on' : 'off';

                $terms_and_conditions = array();
                if (isset($_POST[ $name_prefix . 'terms_and_condition_add' ]) && !empty( $_POST[ $name_prefix . 'terms_and_condition_add' ] )) {
                    $survey_terms_condition_add = $_POST[ $name_prefix . 'terms_and_condition_add' ];
                    foreach($survey_terms_condition_add as $tc_add_key => &$tc_add_value){
                        $survey_terms_condition_messages     = (isset($tc_add_value['messages']) && $tc_add_value['messages'] != "") ? $tc_add_value['messages'] : array();
                        if(!empty($survey_terms_condition_messages)){
                            $tc_add_value['messages'] = isset($tc_add_value['messages']) && $tc_add_value['messages'] != "" ? wp_kses_post($tc_add_value['messages']) : "";
                        }

                        if(isset($tc_add_value)){
                            $terms_and_conditions[$tc_add_key] = $tc_add_value;
                        }
                    }
                }

                $terms_and_conditions = !empty($terms_and_conditions) ? $terms_and_conditions : array();    
                
                // Terms and Conditions required message
                $enable_terms_and_conditions_required_message = (isset( $_POST[ $name_prefix . 'survey_terms_and_conditions_required_message' ] ) && $_POST[ $name_prefix . 'survey_terms_and_conditions_required_message' ] == 'on') ? 'on' : 'off';

            // =============================================================
            // ======================  Settings Tab  =======================
            // ========================     END    =========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // =================== Results Settings Tab  ===================
            // ========================    START   =========================


                // Redirect after submit
                $survey_redirect_after_submit = (isset( $_POST[ $name_prefix . 'survey_redirect_after_submit' ] ) && $_POST[ $name_prefix . 'survey_redirect_after_submit' ] == 'on') ? 'on' : 'off';

                // Redirect URL
                $survey_submit_redirect_url = (isset( $_POST[ $name_prefix . 'survey_submit_redirect_url' ] ) && $_POST[ $name_prefix . 'survey_submit_redirect_url' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_submit_redirect_url' ] ) ) : '';

                // Redirect delay (sec)
                $survey_submit_redirect_delay = (isset( $_POST[ $name_prefix . 'survey_submit_redirect_delay' ] ) && $_POST[ $name_prefix . 'survey_submit_redirect_delay' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_submit_redirect_delay' ] ) ) : '';

                // Redirect in new tab
                $survey_submit_redirect_new_tab = (isset( $_POST[ $name_prefix . 'survey_submit_redirect_new_tab' ] ) && $_POST[ $name_prefix . 'survey_submit_redirect_new_tab' ] == 'on') ? 'on' : 'off';

                // Enable EXIT button
                $survey_enable_exit_button = (isset( $_POST[ $name_prefix . 'survey_enable_exit_button' ] ) && $_POST[ $name_prefix . 'survey_enable_exit_button' ] == 'on') ? 'on' : 'off';

                // Redirect URL
                $survey_exit_redirect_url = (isset( $_POST[ $name_prefix . 'survey_exit_redirect_url' ] ) && $_POST[ $name_prefix . 'survey_exit_redirect_url' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_exit_redirect_url' ] ) ) : '';
                
                // Enable restart button
                $survey_enable_restart_button = (isset( $_POST[ $name_prefix . 'survey_enable_restart_button' ] ) && $_POST[ $name_prefix . 'survey_enable_restart_button' ] == 'on') ? 'on' : 'off';

                // Show summary after submission
                $survey_show_summary_after_submission = (isset( $_POST[ $name_prefix . 'survey_show_summary_after_submission' ] ) && $_POST[ $name_prefix . 'survey_show_summary_after_submission' ] == 'on') ? 'on' : 'off';

                // Show only current users summary
                $survey_show_current_user_results = (isset( $_POST[ $name_prefix . 'survey_show_current_user_results' ] ) && $_POST[ $name_prefix . 'survey_show_current_user_results' ] == 'on') ? 'on' : 'off';

                // Show summary after submission reults
                $survey_show_submission_results = (isset( $_POST[ $name_prefix . 'survey_show_submission_results' ] ) && $_POST[ $name_prefix . 'survey_show_submission_results' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_show_submission_results' ] ) ) : 'summary';

                // Thank you message
                $survey_final_result_text = (isset( $_POST[ $name_prefix . 'survey_final_result_text' ] ) && $_POST[ $name_prefix . 'survey_final_result_text' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_final_result_text' ] ) : '';

                // Show questions as html
                $survey_show_questions_as_html = (isset( $_POST[ $name_prefix . 'survey_show_questions_as_html' ] ) && $_POST[ $name_prefix . 'survey_show_questions_as_html' ] == 'on') ? 'on' : 'off';

                // Select survey loader
                $survey_loader = (isset( $_POST[ $name_prefix . 'survey_loader' ] ) && $_POST[ $name_prefix . 'survey_loader' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_loader' ] ) ) : 'default';
                
                // Loader text
                $survey_loader_text = (isset( $_POST[ $name_prefix . 'survey_loader_text_value' ] ) && $_POST[ $name_prefix . 'survey_loader_text_value' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_loader_text_value' ] ) : '';

                // Loader Gif
                $survey_loader_gif = (isset( $_POST[ $name_prefix . 'survey_loader_custom_gif' ] ) && $_POST[ $name_prefix . 'survey_loader_custom_gif' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_loader_custom_gif' ] ) : '';
                if ($survey_loader_gif != '' && exif_imagetype( $survey_loader_gif ) != IMAGETYPE_GIF) {
                    $survey_loader_gif = '';
                }

                $survey_loader_gif_width = (isset( $_POST[ $name_prefix . 'survey_loader_custom_gif_width' ] ) && $_POST[ $name_prefix . 'survey_loader_custom_gif_width' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_loader_custom_gif_width' ] ) : '100';
                if($survey_loader_gif_width <= "0"){
                    $survey_loader_gif_width = "100";
                }

                // Social share buttons
                $survey_social_buttons   = ( isset( $_POST[ $name_prefix . 'survey_social_buttons' ] ) && $_POST[ $name_prefix . 'survey_social_buttons' ] == 'on' ) ? 'on' : 'off';
                $survey_social_button_ln = ( isset( $_POST[ $name_prefix . 'survey_enable_linkedin_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_linkedin_share_button' ] == 'on' ) ? 'on' : 'off';
                $survey_social_button_fb = ( isset( $_POST[ $name_prefix . 'survey_enable_facebook_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_facebook_share_button' ] == 'on' ) ? 'on' : 'off';
                $survey_social_button_tr = ( isset( $_POST[ $name_prefix . 'survey_enable_twitter_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_twitter_share_button' ] == 'on' ) ? 'on' : 'off';
                $survey_social_button_vk = ( isset( $_POST[ $name_prefix . 'survey_enable_vkontakte_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_vkontakte_share_button' ] == 'on' ) ? 'on' : 'off';

                //---- Schedule Start  ---- //

                    // Schedule the Survey
                    $survey_enable_schedule = (isset( $_POST[ $name_prefix . 'survey_enable_schedule' ] ) && $_POST[ $name_prefix . 'survey_enable_schedule' ] == 'on') ? 'on' : 'off';

                    // Start date
                    $survey_schedule_active = (isset( $_POST[ $name_prefix . 'survey_schedule_active' ] ) && $_POST[ $name_prefix . 'survey_schedule_active' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_schedule_active' ] ) ) : '';

                    // End date
                    $survey_schedule_deactive = (isset( $_POST[ $name_prefix . 'survey_schedule_deactive' ] ) && $_POST[ $name_prefix . 'survey_schedule_deactive' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_schedule_deactive' ] ) ) : '';

                    // Show timer
                    $survey_schedule_show_timer = (isset( $_POST[ $name_prefix . 'survey_schedule_show_timer' ] ) && $_POST[ $name_prefix . 'survey_schedule_show_timer' ] == 'on') ? 'on' : 'off';

                    // Show countdown / start date
                    $survey_show_timer_type = (isset( $_POST[ $name_prefix . 'survey_show_timer_type' ] ) && $_POST[ $name_prefix . 'survey_show_timer_type' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_show_timer_type' ] ) ) : 'countdown';

                    // Pre start message
                    $survey_schedule_pre_start_message = (isset( $_POST[ $name_prefix . 'survey_schedule_pre_start_message' ] ) && $_POST[ $name_prefix . 'survey_schedule_pre_start_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_schedule_pre_start_message' ] ) : __("The survey will be available soon!", $this->plugin_name);

                    // Expiration message
                    $survey_schedule_expiration_message = (isset( $_POST[ $name_prefix . 'survey_schedule_expiration_message' ] ) && $_POST[ $name_prefix . 'survey_schedule_expiration_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_schedule_expiration_message' ] ) : __("This survey has expired!", $this->plugin_name);

                    // Dont Show Survey
                    $survey_dont_show_survey_container = (isset( $_POST[ $name_prefix . 'survey_dont_show_survey_container' ] ) && $_POST[ $name_prefix . 'survey_dont_show_survey_container' ] == 'on') ? 'on' : 'off';

                //---- Schedule End  ---- //

                // Edit previous submission
                $survey_edit_previous_submission = (isset( $_POST[ $name_prefix . 'survey_edit_previous_submission' ] ) && $_POST[ $name_prefix . 'survey_edit_previous_submission' ] == 'on') ? 'on' : 'off';


            // =============================================================
            // =================== Results Settings Tab  ===================
            // ========================    END    ==========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // ===================    Limitation Tab     ===================
            // ========================    START   =========================

                // Maximum number of attempts per user
                $survey_limit_users = (isset( $_POST[ $name_prefix . 'survey_limit_users' ] ) && $_POST[ $name_prefix . 'survey_limit_users' ] == 'on') ? 'on' : 'off';

                // Detects users by IP / ID
                $survey_limit_users_by = (isset( $_POST[ $name_prefix . 'survey_limit_users_by' ] ) && $_POST[ $name_prefix . 'survey_limit_users_by' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_limit_users_by' ] ) ) : 'ip';

                // Attempts count
                $survey_max_pass_count = (isset( $_POST[ $name_prefix . 'survey_max_pass_count' ] ) && $_POST[ $name_prefix . 'survey_max_pass_count' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_max_pass_count' ] ) ) : 1;
                $survey_max_pass_count = $survey_max_pass_count == 0 ? 1 : $survey_max_pass_count;

                // Limitation Message
                $survey_limitation_message = (isset( $_POST[ $name_prefix . 'survey_limitation_message' ] ) && $_POST[ $name_prefix . 'survey_limitation_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_limitation_message' ] ) : '';
                
                // Redirect Url
                $survey_redirect_url = (isset( $_POST[ $name_prefix . 'survey_redirect_url' ] ) && $_POST[ $name_prefix . 'survey_redirect_url' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_redirect_url' ] ) ) : '';
                            
                // Redirect delay
                $survey_redirect_delay = (isset( $_POST[ $name_prefix . 'survey_redirection_delay' ] ) && $_POST[ $name_prefix . 'survey_redirection_delay' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_redirection_delay' ] ) ) : 0;
                
                /* ------------- Only for logged in users ----------- */
                
                // Only for logged in users
                $survey_enable_logged_users = (isset( $_POST[ $name_prefix . 'survey_enable_logged_users' ] ) && $_POST[ $name_prefix . 'survey_enable_logged_users' ] == 'on') ? 'on' : 'off';

                // Message - Only for logged in users
                $survey_logged_in_message = (isset( $_POST[ $name_prefix . 'survey_logged_in_message' ] ) && $_POST[ $name_prefix . 'survey_logged_in_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_logged_in_message' ] ) : '';
                
                // Show login form
                $survey_show_login_form = (isset( $_POST[ $name_prefix . 'survey_show_login_form' ] ) && $_POST[ $name_prefix . 'survey_show_login_form' ] == 'on') ? 'on' : 'off';

                // Only for selected user role
                $survey_enable_for_user_role = (isset( $_POST[ $name_prefix . 'survey_enable_restriction_pass' ] ) && $_POST[ $name_prefix . 'survey_enable_restriction_pass' ] == 'on') ? 'on' : 'off';

                // Access only selected users
                $survey_enable_for_user = (isset( $_POST[ $name_prefix . 'survey_enable_restriction_pass_users' ] ) && $_POST[ $name_prefix . 'survey_enable_restriction_pass_users' ] == 'on') ? 'on' : 'off';

                // Message - Only for logged in users
                $survey_logged_in_message = (isset( $_POST[ $name_prefix . 'survey_logged_in_message' ] ) && $_POST[ $name_prefix . 'survey_logged_in_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_logged_in_message' ] ) : '';
                    
                // User role
                $survey_user_roles = (isset( $_POST[ $name_prefix . 'survey_users_roles' ] ) && !empty($_POST[ $name_prefix . 'survey_users_roles' ]) ) ?  array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'survey_users_roles' ] ) : array();

                // Message - Only for user role
                $survey_user_roles_message = (isset( $_POST[ $name_prefix . 'survey_restriction_pass_message' ] ) && $_POST[ $name_prefix . 'survey_restriction_pass_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_restriction_pass_message' ] ) : '';

                // Users 
                $survey_user = (isset( $_POST[ $name_prefix . 'survey_users_search' ] ) && !empty($_POST[ $name_prefix . 'survey_users_search' ]) ) ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'survey_users_search' ] ) : array();

                // Message - Access only selected users 
                $survey_user_message = (isset( $_POST[ $name_prefix . 'survey_restriction_pass_users_message' ] ) && $_POST[ $name_prefix . 'survey_restriction_pass_users_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_restriction_pass_users_message' ] ) : '';

                //limitation takers count
                $survey_enable_takers_count = (isset( $_POST[ $name_prefix . 'survey_enable_tackers_count' ] ) && $_POST[ $name_prefix . 'survey_enable_tackers_count' ] == 'on') ? 'on' : 'off';

                // Takers count
                $survey_takers_count = (isset( $_POST[ $name_prefix . 'survey_tackers_count' ] ) && $_POST[ $name_prefix . 'survey_tackers_count' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_tackers_count' ] ) ) : 1;
                
                // Enable Password quiz
                $enable_password = (isset( $_POST[ $name_prefix . 'survey_enable_password' ] ) && $_POST[ $name_prefix . 'survey_enable_password' ] == 'on') ? 'on' : 'off';

                // Password quiz
                $password_survey = (isset( $_POST[ $name_prefix . 'survey_password_survey' ] ) && $_POST[ $name_prefix . 'survey_password_survey' ] != '') ?  $_POST[ $name_prefix . 'survey_password_survey' ]  : '';


                if( $survey_enable_for_user_role == 'on' || $survey_enable_for_user == 'on' ){
                    $survey_enable_logged_users = 'on';
                }elseif( isset( $_POST[ $name_prefix .'survey_enable_logged_users'] ) && $_POST[ $name_prefix .'survey_enable_logged_users'] == "on" ){
                    $survey_enable_logged_users = 'on';
                }else{
                    $survey_enable_logged_users = 'off';
                }

                // Password generate
                $survey_passwords_type = (isset($_POST[ $name_prefix . 'survey_psw_type']) && $_POST[ $name_prefix . 'survey_psw_type'] != '') ? $_POST[ $name_prefix . 'survey_psw_type' ] : 'general';
                
                $survey_created_passwords = (isset($_POST[ $name_prefix . 'survey_generated_psw']) && !empty($_POST[ $name_prefix . 'survey_generated_psw'])) ? $_POST[ $name_prefix . 'survey_generated_psw' ]: array();
                
                $survey_active_passwords = (isset($_POST[ $name_prefix . 'survey_active_gen_psw']) && !empty($_POST[ $name_prefix . 'survey_active_gen_psw'])) ? $_POST[ $name_prefix . 'survey_active_gen_psw' ] : array();

                $survey_used_passwords = (isset($_POST[$name_prefix . 'survey_used_psw']) && !empty($_POST[$name_prefix . 'survey_used_psw'])) ? $_POST[ $name_prefix . 'survey_used_psw' ]: array();

                $survey_generated_passwords = array(
                    'survey_created_passwords' => $survey_created_passwords,
                    'survey_active_passwords'  => $survey_active_passwords,
                    'survey_used_passwords'    => $survey_used_passwords
                );

                // Message - Password
                $survey_password_message = (isset( $_POST[ $name_prefix . 'survey_password_message' ] ) && $_POST[ $name_prefix . 'survey_password_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_password_message' ] ) : '';

                // limit user by country
                $survey_enable_limit_by_country = (isset( $_POST[ $name_prefix . 'survey_enable_limit_by_country' ] ) && $_POST[ $name_prefix . 'survey_enable_limit_by_country' ] == 'on') ? 'on' : 'off';

                $survey_limit_country = (isset($_POST[$name_prefix .'survey_limit_country']) && !empty($_POST[$name_prefix .'survey_limit_country'])) ? implode('***', $_POST[$name_prefix .'survey_limit_country']) : '';

            // =============================================================
            // ===================    Limitation Tab     ===================
            // ========================    END    ==========================

            // =======================  //  ======================= // ======================= // ======================= // ======================= //

            // =============================================================
            // =====================    E-Mail Tab     =====================
            // ========================    START   =========================


                // Send Mail To User
                $survey_enable_mail_user = (isset( $_POST[ $name_prefix . 'survey_enable_mail_user' ] ) && $_POST[ $name_prefix . 'survey_enable_mail_user' ] == 'on') ? 'on' : 'off';

                // Send email to user | Custom | SendGrid
                $survey_send_mail_type = (isset( $_POST[ $name_prefix . 'survey_send_mail_type' ] ) && sanitize_text_field( $_POST[ $name_prefix . 'survey_send_mail_type' ] ) != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_send_mail_type' ] ) ) : 'custom';

                // Email message
                $survey_mail_message = (isset( $_POST[ $name_prefix . 'survey_mail_message' ] ) && $_POST[ $name_prefix . 'survey_mail_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_mail_message' ] ) : '';
                
                // Send single summary to users
                $survey_summary_single_email_to_users = (isset( $_POST[ $name_prefix . 'survey_summary_single_email_to_users' ] ) && $_POST[ $name_prefix . 'survey_summary_single_email_to_users' ] == 'on') ? 'on' : 'off';

                // Send email to admin
                $survey_enable_mail_admin = (isset( $_POST[ $name_prefix . 'survey_enable_mail_admin' ] ) && $_POST[ $name_prefix . 'survey_enable_mail_admin' ] == 'on') ? 'on' : 'off';

                // Send email to site admin ( SuperAdmin )
                $survey_send_mail_to_site_admin = (isset( $_POST[ $name_prefix . 'survey_send_mail_to_site_admin' ] ) && $_POST[ $name_prefix . 'survey_send_mail_to_site_admin' ] == 'on') ? 'on' : 'off';


                // Additional emails
                $survey_additional_emails = "";
                if( isset( $_POST[ $name_prefix . 'survey_additional_emails' ] ) ) {
                    if(!empty( $_POST[ $name_prefix . 'survey_additional_emails' ] )) {
                        $additional_emails_arr = explode(",", $_POST[ $name_prefix . 'survey_additional_emails' ] );
                        foreach($additional_emails_arr as $email) {
                            $email = stripslashes(trim($email));
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $survey_additional_emails .= $email.", ";
                            }
                        }
                        $survey_additional_emails = substr($survey_additional_emails, 0, -2);
                    }
                }

                // Email message
                $survey_mail_message_admin = (isset( $_POST[ $name_prefix . 'survey_mail_message_admin' ] ) && $_POST[ $name_prefix . 'survey_mail_message_admin' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_mail_message_admin' ] ) : '';

                // Send submission report to admin
                $survey_send_submission_report = (isset( $_POST[ $name_prefix . 'survey_send_submission_report' ] ) && $_POST[ $name_prefix . 'survey_send_submission_report' ] == 'on') ? 'on' : 'off';

                //---- Email configuration Start  ---- //

                    // From email
                    $survey_email_configuration_from_email = (isset( $_POST[ $name_prefix . 'survey_email_configuration_from_email' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_from_email' ] != '') ? stripslashes ( sanitize_email( $_POST[ $name_prefix . 'survey_email_configuration_from_email' ] ) ) : '';

                    // From name
                    $survey_email_configuration_from_name = (isset( $_POST[ $name_prefix . 'survey_email_configuration_from_name' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_from_name' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_email_configuration_from_name' ] ) ) : '';

                    // Subject
                    $survey_email_configuration_from_subject = (isset( $_POST[ $name_prefix . 'survey_email_configuration_from_subject' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_from_subject' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_email_configuration_from_subject' ] ) ) : '';

                    // Reply to email
                    $survey_email_configuration_replyto_email = (isset( $_POST[ $name_prefix . 'survey_email_configuration_replyto_email' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_replyto_email' ] != '') ? stripslashes ( sanitize_email( $_POST[ $name_prefix . 'survey_email_configuration_replyto_email' ] ) ) : '';

                    // Reply to name
                    $survey_email_configuration_replyto_name = (isset( $_POST[ $name_prefix . 'survey_email_configuration_replyto_name' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_replyto_name' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_email_configuration_replyto_name' ] ) ) : '';

                //---- Email configuration End ---- //


                // Send Summary Email Start

                    // Send summary email to site admin ( SuperAdmin )
                    $survey_send_summary_email_to_site_admin = (isset( $_POST[ $name_prefix . 'survey_summary_emails_to_admin' ] ) && $_POST[ $name_prefix . 'survey_summary_emails_to_admin' ] == 'on') ? 'on' : 'off';

                    // Send summary email to users
                    $survey_send_summary_email_to_users = (isset( $_POST[ $name_prefix . 'survey_summary_emails_to_users' ] ) && $_POST[ $name_prefix . 'survey_summary_emails_to_users' ] == 'on') ? 'on' : 'off';

                    //Send summary to additional users
                    $survey_send_summary_email_to_additional_users = (isset( $_POST[ $name_prefix . 'survey_summary_emails_to_admins' ] ) && sanitize_text_field($_POST[ $name_prefix . 'survey_summary_emails_to_admins' ] != '') ) ? sanitize_text_field($_POST[ $name_prefix . 'survey_summary_emails_to_admins' ]) : '';

                // Send Summary Email End 

            // =============================================================
            // =====================    E-Mail Tab     =====================
            // ========================    END    ==========================

            // =======================  //  ======================= // ======================= // ======================= // ======================= //

            // =========================================================
            // ===================== Conditions Start ==================
            // =========================================================

                // --------------------------- Add Conditions Table ------------Start---------------
                $conditions = array();
                if (isset($_POST[ $name_prefix . 'condition_add' ]) && !empty( $_POST[ $name_prefix . 'condition_add' ] )) {
                    $survey_condition_add = $_POST[ $name_prefix . 'condition_add' ];
                    foreach($survey_condition_add as $c_add_key => &$c_add_value){
                        $counter = 0;
                        $survey_condition_question_add = (isset($c_add_value['condition_question_add']) && $c_add_value['condition_question_add'] != "") ? $c_add_value['condition_question_add'] : "";
                        $survey_condition_messages     = (isset($c_add_value['messages']) && $c_add_value['messages'] != "") ? $c_add_value['messages'] : array();
                        if(!empty($survey_condition_messages)){
                            if(isset($survey_condition_messages['page'])){
                                $c_add_value['messages']['page']['message'] = isset($c_add_value['messages']['page']['message']) && $c_add_value['messages']['page']['message'] != "" ? wp_kses_post($c_add_value['messages']['page']['message']) : "";
                            }
                            if(isset($survey_condition_messages['email'])){
                                $c_add_value['messages']['email']['message'] = isset($c_add_value['messages']['email']['message']) && $c_add_value['messages']['email']['message'] != "" ? wp_kses_post($c_add_value['messages']['email']['message']) : "";
                            }
                        }
                        
                        if($survey_condition_question_add){
                            foreach($survey_condition_question_add as $q_add_key => $q_add_value){
                                if((isset($q_add_value['question_id']) && $q_add_value['question_id'] != "0") && (isset($q_add_value['answer']))){
                                    $c_add_value['condition_question_add'][$q_add_key] = $q_add_value;
                                    $counter += intval($q_add_value['question_id']);
                                }else{
                                    unset($c_add_value['condition_question_add'][$q_add_key]);
                                }
                            }
                        }
                        if($counter == 0){
                            unset($c_add_value);
                        }
                        if(isset($c_add_value)){
                            $conditions[$c_add_key] = $c_add_value;
                        }
                    }
                }
                
                
                if(!empty($conditions)){
                    $conditions = json_encode($conditions , JSON_UNESCAPED_SLASHES);
                }else{
                    $conditions = "";
                }
                // --------------------------- Add Conditions Table ------------end---------------
                                                
                // Show all conditions results
                $survey_condition_show_all_results = (isset( $_POST[ $name_prefix . 'survey_condition_show_all_results' ] ) && $_POST[ $name_prefix . 'survey_condition_show_all_results' ] == 'on') ? 'on' : 'off';

            // =========================================================
            // ===================== Conditions end ====================
            // =========================================================


            // Options
            $options = array(
                'survey_version'                    => SURVEY_MAKER_VERSION,
                // Styles Tab
                'survey_theme'                      => $survey_theme,
                'survey_color'                      => $survey_color,
                'survey_background_color'           => $survey_background_color,
                'survey_text_color'                 => $survey_text_color,
                'survey_buttons_text_color'         => $survey_buttons_text_color,
                'survey_width'                      => $survey_width,
                'survey_width_by_percentage_px'     => $survey_width_by_percentage_px,
                'survey_mobile_width'               => $survey_mobile_width,
                'survey_mobile_width_by_percent_px' => $survey_mobile_width_by_percentage_px,
                'survey_mobile_max_width'           => $survey_mobile_max_width,
                'survey_custom_class'               => $survey_custom_class,
                'survey_custom_css'                 => $survey_custom_css,
                'survey_logo'                       => $survey_logo,
                'survey_logo_url'                   => $survey_logo_image_url,
                'survey_enable_logo_url'            => $survey_logo_image_url_check,
                'survey_logo_url_new_tab'           => $survey_logo_url_new_tab,
                'survey_logo_image_position'        => $survey_logo_image_position,
                'survey_logo_title'                 => $survey_logo_title,
                'survey_title_alignment'            => $survey_title_alignment,
                'survey_title_font_size'            => $survey_title_font_size,
                'survey_title_font_size_for_mobile' => $survey_title_font_size_for_mobile,
                'survey_title_box_shadow_enable'    => $survey_title_box_shadow_enable,
                'survey_title_box_shadow_color'     => $survey_title_box_shadow_color,
                'survey_title_text_shadow_x_offset' => $survey_title_text_shadow_x_offset,
                'survey_title_text_shadow_y_offset' => $survey_title_text_shadow_y_offset,
                'survey_title_text_shadow_z_offset' => $survey_title_text_shadow_z_offset,
                'survey_section_title_font_size'    => $survey_section_title_font_size,
                'survey_section_title_font_size_mobile' => $survey_section_title_font_size_mobile,
                'survey_section_title_alignment'    => $survey_section_title_alignment,
                'survey_section_description_alignment' => $survey_section_description_alignment,
                'survey_section_description_font_size' => $survey_section_description_font_size,
                'survey_section_description_font_size_mobile' => $survey_section_description_font_size_mobile,
                'survey_cover_photo'                => $survey_cover_photo,
                'survey_cover_photo_height'         => $survey_cover_photo_height,
                'survey_cover_photo_mobile_height'  => $survey_cover_photo_mobile_height,
                'survey_cover_photo_position'       => $survey_cover_photo_position,
                'survey_cover_photo_object_fit'     => $survey_cover_photo_object_fit,
                'survey_cover_only_first_section'   => $survey_cover_only_first_section,

                'survey_question_font_size'         => $survey_question_font_size,
                'survey_question_font_size_mobile'  => $survey_question_font_size_mobile,
                'survey_question_title_alignment'   => $survey_question_title_alignment,
                'survey_question_image_width'       => $survey_question_image_width,
                'survey_question_image_height'      => $survey_question_image_height,
                'survey_question_image_sizing'      => $survey_question_image_sizing,
                'survey_question_padding'           => $survey_question_padding,
                'survey_question_caption_text_color' => $survey_question_caption_text_color,
                'survey_question_caption_text_alignment' => $survey_question_caption_text_alignment,
                'survey_question_caption_font_size' => $survey_question_caption_font_size,
                'survey_question_caption_font_size_on_mobile' => $survey_question_caption_font_size_on_mobile,
                'survey_question_caption_text_transform' => $survey_question_caption_text_transform,

                'survey_answer_font_size'           => $survey_answer_font_size,
                'survey_answer_font_size_on_mobile' => $survey_answer_font_size_on_mobile,
                'survey_answers_view'               => $survey_answers_view,
                'survey_answers_view_alignment'     => $survey_answers_view_alignment,
                'survey_grid_view_count'            => $survey_grid_view_count,
                'survey_answers_object_fit'         => $survey_answers_object_fit,
                'survey_answers_padding'            => $survey_answers_padding,
                'survey_answers_gap'                => $survey_answers_gap,
                'survey_answers_image_size'         => $survey_answers_image_size,
                'survey_stars_color'                => $survey_stars_color,
                
                'survey_slider_question_bubble_bg_color'   => $survey_slider_question_bubble_bg_color,
                'survey_slider_question_bubble_text_color' => $survey_slider_question_bubble_text_color,

                'survey_buttons_bg_color'           => $survey_buttons_bg_color,
                'survey_buttons_size'               => $survey_buttons_size,
                'survey_buttons_font_size'          => $survey_buttons_font_size,
                'survey_buttons_mobile_font_size'   => $survey_buttons_mobile_font_size,
                'survey_buttons_left_right_padding' => $survey_buttons_left_right_padding,
                'survey_buttons_top_bottom_padding' => $survey_buttons_top_bottom_padding,
                'survey_buttons_border_radius'      => $survey_buttons_border_radius,
                'survey_buttons_alignment'          => $survey_buttons_alignment,
                'survey_buttons_top_distance'          => $survey_buttons_top_distance,

                'survey_admin_note_color'           => $survey_admin_note_color,                
                'survey_admin_note_text_transform'  => $survey_admin_note_text_transform,                
                'survey_admin_note_font_size'       => $survey_admin_note_font_size,                

                // Start page tab
                'survey_start_page_title'           => $survey_start_page_title,
                'survey_start_page_description'     => $survey_start_page_description,
                'survey_start_page_background_color'=> $survey_start_page_background_color,
                'survey_start_page_text_color'      => $survey_start_page_text_color,
                'survey_start_page_custom_class'    => $survey_start_page_custom_class,

                // Settings Tab
                'survey_show_title'                 => $survey_show_title,
                'survey_show_section_header'        => $survey_show_section_header,
                'survey_enable_start_page'          => $survey_enable_start_page,
                'survey_enable_randomize_answers'   => $survey_enable_randomize_answers,
                'survey_enable_randomize_questions' => $survey_enable_randomize_questions,
                'survey_enable_rtl_direction'       => $survey_enable_rtl_direction,
                'survey_enable_leave_page'          => $survey_enable_leave_page,
                'survey_enable_clear_answer'        => $survey_enable_clear_answer,
                'survey_enable_previous_button'     => $survey_enable_previous_button,
                'survey_enable_survey_start_loader' => $survey_enable_survey_start_loader,
                'survey_before_start_loader'        => $survey_before_start_loader,
                'survey_allow_html_in_answers'      => $survey_allow_html_in_answers,
                'survey_allow_html_in_section_description'  => $survey_allow_html_in_section_description,
                'survey_auto_numbering'             => $survey_auto_numbering,
                'survey_auto_numbering_questions'   => $survey_auto_numbering_questions,
                'survey_enable_question_numbering_by_sections' => $survey_enable_question_numbering_by_sections,
                'survey_enable_i_autofill'          => $survey_i_autofill,
                'survey_allow_collecting_logged_in_users_data' => $survey_allow_collecting_logged_in_users_data,
                'survey_main_url'                   => $survey_main_url,                
                'survey_enable_copy_protection'     => $enable_copy_protection,
                'survey_enable_expand_collapse_question' => $enable_expand_collapse_question,
                'survey_question_text_to_speech'    => $survey_question_text_to_speech,
                'survey_full_screen_mode'           => $survey_full_screen,
                'survey_full_screen_button_color'   => $survey_full_screen_button_color,
                'survey_enable_progress_bar'        => $survey_enable_progress_bar,
                'survey_hide_section_pagination_text' => $survey_hide_section_pagination_text,
                'survey_pagination_positioning'     => $survey_pagination_positioning,
                'survey_hide_section_bar'           => $survey_hide_section_bar,
                'survey_progress_bar_text'            => $survey_progress_bar_text,
                'survey_pagination_text_color'        => $survey_pagination_text_color,
                'survey_show_sections_questions_count'=> $survey_show_sections_questions_count,
                'survey_required_questions_message'  => $survey_required_questions_message,
                'survey_enable_chat_mode'            => $enable_chat_mode,
                'survey_change_create_author'        => $survey_change_create_author,
                'survey_enable_terms_and_conditions' => $enable_terms_and_conditions,
                'enable_terms_and_conditions_required_message' => $enable_terms_and_conditions_required_message,
                'survey_terms_and_conditions_data'   => $terms_and_conditions,

                'survey_enable_schedule'            => $survey_enable_schedule,
                'survey_schedule_active'            => $survey_schedule_active,
                'survey_schedule_deactive'          => $survey_schedule_deactive,
                'survey_schedule_show_timer'        => $survey_schedule_show_timer,
                'survey_show_timer_type'            => $survey_show_timer_type,
                'survey_schedule_pre_start_message' => $survey_schedule_pre_start_message,
                'survey_schedule_expiration_message'=> $survey_schedule_expiration_message,
                'survey_dont_show_survey_container' => $survey_dont_show_survey_container,

                'survey_edit_previous_submission'   => $survey_edit_previous_submission,

                // Result Settings Tab
                'survey_redirect_after_submit'      => $survey_redirect_after_submit,
                'survey_submit_redirect_url'        => $survey_submit_redirect_url,
                'survey_submit_redirect_delay'      => $survey_submit_redirect_delay,
                'survey_submit_redirect_new_tab'    => $survey_submit_redirect_new_tab,
                'survey_enable_exit_button'         => $survey_enable_exit_button,
                'survey_exit_redirect_url'          => $survey_exit_redirect_url,
                'survey_enable_restart_button'      => $survey_enable_restart_button,
                'survey_show_summary_after_submission' => $survey_show_summary_after_submission,
                'survey_show_current_user_results'  => $survey_show_current_user_results,
                'survey_show_submission_results'    => $survey_show_submission_results,
                'survey_final_result_text'          => $survey_final_result_text,
                'survey_show_questions_as_html'     => $survey_show_questions_as_html,
                'survey_loader'                     => $survey_loader,
                'survey_loader_text'                => $survey_loader_text,
                'survey_loader_gif'                 => $survey_loader_gif,
                'survey_loader_gif_width'           => $survey_loader_gif_width,
                'survey_social_buttons'             => $survey_social_buttons,
                'survey_social_button_ln'           => $survey_social_button_ln,
                'survey_social_button_fb'           => $survey_social_button_fb,
                'survey_social_button_tr'           => $survey_social_button_tr,
                'survey_social_button_vk'           => $survey_social_button_vk,

                
                // Condition Tab
                'survey_condition_show_all_results'  => $survey_condition_show_all_results,

                // Limitation Tab
                'survey_limit_users'                => $survey_limit_users,
                'survey_limit_users_by'             => $survey_limit_users_by,
                'survey_max_pass_count'             => $survey_max_pass_count,
                'survey_limitation_message'         => $survey_limitation_message,
                'survey_redirect_url'               => $survey_redirect_url,
                'survey_redirect_delay'             => $survey_redirect_delay,
                'survey_enable_logged_users'        => $survey_enable_logged_users,
                'survey_logged_in_message'          => $survey_logged_in_message,
                'survey_show_login_form'            => $survey_show_login_form,
                'survey_enable_for_user_role'       => $survey_enable_for_user_role,
                'survey_user_roles'                 => $survey_user_roles,
                'survey_user_roles_message'         => $survey_user_roles_message,
                'survey_enable_for_user'            => $survey_enable_for_user,
                'survey_user'                       => $survey_user,
                'survey_user_message'               => $survey_user_message,
                'survey_enable_takers_count'        => $survey_enable_takers_count,
                'survey_takers_count'               => $survey_takers_count,
                'survey_enable_password'            => $enable_password,
                'survey_password_survey'            => $password_survey,
                'survey_password_type'              => $survey_passwords_type,
                'survey_generated_passwords'        => $survey_generated_passwords,
                'survey_password_message'           => $survey_password_message,
                'survey_enable_limit_by_country'    => $survey_enable_limit_by_country,
                'survey_limit_country'              => $survey_limit_country,

                // E-mail Tab
                'survey_enable_mail_user'           => $survey_enable_mail_user,
                'survey_send_mail_type'             => $survey_send_mail_type,
                'survey_mail_message'               => $survey_mail_message,
                'survey_summary_single_email_to_users' => $survey_summary_single_email_to_users,
                'survey_enable_mail_admin'          => $survey_enable_mail_admin,
                'survey_send_mail_to_site_admin'    => $survey_send_mail_to_site_admin,
                'survey_additional_emails'          => $survey_additional_emails,
                'survey_mail_message_admin'         => $survey_mail_message_admin,
                'survey_send_submission_report'     => $survey_send_submission_report,

                'survey_email_configuration_from_email'    => $survey_email_configuration_from_email,
                'survey_email_configuration_from_name'     => $survey_email_configuration_from_name,
                'survey_email_configuration_from_subject'  => $survey_email_configuration_from_subject,
                'survey_email_configuration_replyto_email' => $survey_email_configuration_replyto_email,
                'survey_email_configuration_replyto_name'  => $survey_email_configuration_replyto_name,

                'survey_send_summary_email_to_site_admin'        => $survey_send_summary_email_to_site_admin,
                'survey_send_summary_email_to_users'             => $survey_send_summary_email_to_users,
                'survey_send_summary_email_to_additional_users'  => $survey_send_summary_email_to_additional_users,

            );

            if (isset($_POST['save_type_default_options']) && $_POST['save_type_default_options'] == 'save_type_default_options') {

                $survey_default_options = $options;
                $survey_default_options['survey_enable_schedule'] = 'off';
                unset($survey_default_options['survey_schedule_active']);
                unset($survey_default_options['survey_schedule_deactive']);

                $this->settings_obj->ays_update_setting( 'survey_default_options', json_encode( $survey_default_options ) );
            }
            
            // OLD PLACE FOR INTEGRATIONS FILTERS
            // $options = apply_filters( "ays_sm_survey_page_integrations_saves", $options, $_POST);
            
            $survey_old_questions = array();

            if (isset($_POST[ $name_prefix . 'sections_delete' ]) && ! empty( $_POST[ $name_prefix . 'sections_delete' ] )) {
                $sections_delete = array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'sections_delete' ] );
                foreach( $sections_delete as $key => $del_id ) {
                    if( in_array( $del_id, $section_ids ) ){
                        $del_index = array_search( $del_id, $section_ids );
                        unset($section_ids[$del_index]);
                    }
                    $wpdb->delete(
                        $sections_table,
                        array( 'id' => intval( $del_id ) ),
                        array( '%d' )
                    );
                }
            }

            if (isset($_POST[ $name_prefix . 'questions_delete' ]) && ! empty( $_POST[ $name_prefix . 'questions_delete' ] )) {
                $questions_delete = array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'questions_delete' ] );
                foreach ( $questions_delete as $key => $del_id ) {
                    if( in_array( $del_id, $question_ids ) ){
                        $del_index = array_search( $del_id, $question_ids );
                        unset($question_ids[$del_index]);
                    }
                    $wpdb->delete(
                        $questions_table,
                        array( 'id' => intval( $del_id ) ),
                        array( '%d' )
                    );
                }
            }

            if (isset($_POST[ $name_prefix . 'answers_delete' ]) && ! empty( $_POST[ $name_prefix . 'answers_delete' ] )) {
                $answers_delete = array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'answers_delete' ] );
                foreach ( $answers_delete as $key => $del_id ) {
                    $wpdb->delete(
                        $answers_table,
                        array( 'id' => intval( $del_id ) ),
                        array( '%d' )
                    );
                }
            }

            $question_ids_new = array();
            $section_ids_array = array();
            $section_ids_for_logic_jump_array = array();

            if (isset($_POST[ $name_prefix . 'section_add' ]) && ! empty( $_POST[ $name_prefix . 'section_add' ] )) {
                // --------------------------- Sections Table ------------Start--------------- //
                // $section_ordering = 1;
                $textareas = array(
                    'description',
                    'title'
                );

                // $section_add = Survey_Maker_Data::recursive_sanitize_text_field( $_POST[ $name_prefix . 'section_add' ], $textareas );
                $section_add = $_POST[ $name_prefix . 'section_add' ];

                foreach ( $section_add as $key => $section) {

                    //Section Title
                    $section_title = ( isset($section['title']) && $section['title'] != '' ) ? stripslashes( $section['title'] ) : '';

                    //Section Description
                    $section_description = ( isset($section['description']) && $section['description'] != '' ) ? stripslashes( $section['description'] ) : '';

                    //Section Ordering
                    $section_ordering = ( isset($section['ordering']) && $section['ordering'] != '' ) ? $section['ordering'] : '';

                    // Section collapsed
                    $section_collapsed = ( isset($section['options']['collapsed']) && $section['options']['collapsed'] != '' ) ? $section['options']['collapsed'] : 'expanded';

                    // Go to section
                    $sections_go_to_section = (isset($section['options']['go_to_section']) && $section['options']['go_to_section'] != '') ? $section['options']['go_to_section'] : '-1';

                    // Options
                    $section_options = array(
                        'collapsed' => $section_collapsed,
                        'go_to_section' => $sections_go_to_section
                    );

                    $result = $wpdb->insert(
                        $sections_table,
                        array(
                            'title'         => $section_title,
                            'description'   => $section_description,
                            'ordering'      => $section_ordering,
                            'options'       => json_encode( $section_options ),
                        ),
                        array(
                            '%s', // title
                            '%s', // description
                            '%d', // ordering
                            '%s', // options
                        )
                    );

                    $section_insert_id = $wpdb->insert_id;
                    $section_ids_array[] = $section_insert_id;
                    $section_ids_for_logic_jump_array['new_section_'.$key] = $section_insert_id;

                    if( strpos( $sections_go_to_section, 'new_section_' ) !== false ){
                        $sections_go_to_section = $section_ids_for_logic_jump_array[ $sections_go_to_section ];
                    }

                    $section_options['go_to_section'] = strval( $sections_go_to_section );

                    $result = $wpdb->update(
                        $sections_table,
                        array(
                            'options' => json_encode( $section_options ),
                        ),
                        array( 'id' => $section_insert_id ),
                        array(
                            '%s', // options
                        ),
                        array( '%d' )
                    );
                    // --------------------------- Question Table ------------Start--------------- //

                    // $question_ordering = 1;
                    if( isset( $section['questions'] ) && !empty( $section['questions'] ) ){
                        foreach ($section['questions'] as $question_id => $question) {

                            $question_id = absint(intval($question_id));

                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', $this->plugin_name );

                            if( ! isset( $survey_old_questions[$question_id] ) ){
                                $survey_old_questions[$question_id] = $ays_question;
                            }

                            $ays_question_description = ( isset($question['description']) && trim( $question['description'] ) != '' ) ? stripslashes( $question['description'] ) : '';
                            
                            if( ! isset( $survey_old_question_descriptions[$question_id] ) ){
                                $survey_old_question_descriptions[$question_id] = $ays_question_description;
                            }

                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';
                            
                            $linear_scale_1 = (isset( $question['options']['linear_scale_1'] ) && $question['options']['linear_scale_1'] != '') ? $question['options']['linear_scale_1'] : '';
                            $linear_scale_2 = (isset( $question['options']['linear_scale_2'] ) && $question['options']['linear_scale_2'] != '') ? $question['options']['linear_scale_2'] : '';
                            $linear_scale_length = (isset( $question['options']['scale_length'] ) && $question['options']['scale_length'] != '') ? $question['options']['scale_length'] : '5';
                            $star_1 = (isset( $question['options']['star_1'] ) && $question['options']['star_1'] != '') ? $question['options']['star_1'] : '';
                            $star_2 = (isset( $question['options']['star_2'] ) && $question['options']['star_2'] != '') ? $question['options']['star_2'] : '';
                            $star_scale_length = (isset( $question['options']['star_scale_length'] ) && $question['options']['star_scale_length'] != '') ? $question['options']['star_scale_length'] : '5';

                            $matrix_columns = (isset( $question['options']['columns'] )) ? $question['options']['columns'] : '';

                            // Star list options
                            $star_list_stars_length = (isset( $question['options']['star_list_stars_length'] )) ? $question['options']['star_list_stars_length'] : '';

                            // Slider list options
                            $slider_list_range_length = (isset( $question['options']['slider_list_range_length'] )) ? $question['options']['slider_list_range_length'] : '';
                            $slider_list_range_step_length = (isset( $question['options']['slider_list_range_step_length'] )) ? $question['options']['slider_list_range_step_length'] : '';
                            $slider_list_range_min_value = (isset( $question['options']['slider_list_range_min_value'] )) ? $question['options']['slider_list_range_min_value'] : '';
                            $slider_list_range_default_value = (isset( $question['options']['slider_list_range_default_value'] )) ? $question['options']['slider_list_range_default_value'] : '';
                            $slider_list_range_calculation_type = (isset( $question['options']['slider_list_range_calculation_type'] )) ? $question['options']['slider_list_range_calculation_type'] : 'seperatly';
                                                        
                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';
                            
                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';
                            
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            // Question Caption
                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // Is logic jump
                            $enable_logic_jump = isset($question['options']['is_logic_jump']) ? $question['options']['is_logic_jump'] : 'off';

                            $other_answer_logic_jump = ( $user_variant == 'on' && isset($question['options']['go_to_section']) && $enable_logic_jump == 'on' ) ? $question['options']['go_to_section'] : 'off';

                            if( strpos( $other_answer_logic_jump, 'new_section_' ) !== false ){
                                $other_answer_logic_jump = $section_ids_for_logic_jump_array[ $other_answer_logic_jump ];
                            }
                            
                            // User explanation
                            $enable_user_explanation = isset($question['options']['user_explanation']) ? $question['options']['user_explanation'] : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            // Range type
                            $range_type_length        = (isset( $question['options']['range_length'] ) && $question['options']['range_length'] != '') ? $question['options']['range_length'] : '';
                            $range_type_step_length   = (isset( $question['options']['range_step_length'] ) && $question['options']['range_step_length'] != '') ? $question['options']['range_step_length'] : '';
                            $range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? $question['options']['range_min_value'] : '';
                            $range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? $question['options']['range_default_value'] : '';

                            // File upload type
                            $file_upload_toggle     = (isset( $question['options']['toggle_types'] ) && $question['options']['toggle_types'] == 'on') ? 'on' : 'off';
                            $file_upload_types_pdf  = (isset( $question['options']['upload_pdf'] ) && $question['options']['upload_pdf'] == "on") ? "on" : 'off';
                            $file_upload_types_doc  = (isset( $question['options']['upload_doc'] ) && $question['options']['upload_doc'] == "on") ? "on" : 'off';
                            $file_upload_types_png  = (isset( $question['options']['upload_png'] ) && $question['options']['upload_png'] == "on") ? "on" : 'off';
                            $file_upload_types_jpg  = (isset( $question['options']['upload_jpg'] ) && $question['options']['upload_jpg'] == "on") ? "on" : 'off';
                            $file_upload_types_gif  = (isset( $question['options']['upload_gif'] ) && $question['options']['upload_gif'] == "on") ? "on" : 'off';
                            $file_upload_types_size = (isset( $question['options']['upload_size'] ) && $question['options']['upload_size'] != "") ? $question['options']['upload_size'] : '5';

                            //HTML Question TYpe
                            $html_types_content = (isset( $question['options']['html_type_editor'] ) && $question['options']['html_type_editor'] != "") ? stripslashes($question['options']['html_type_editor']) : '';

                            // Admin note
                            $enable_admin_note     = (isset( $question['options']['enable_admin_note'] )) ? $question['options']['enable_admin_note'] : 'off';
                            $admin_note            = (isset( $question['options']['admin_note'] ) && $question['options']['admin_note'] != '') ? $question['options']['admin_note'] : '';

                            // Checkbox type logic jump
	                        $other_logic_jump      = isset($question['options']['other_logic_jump']) && !empty($question['options']['other_logic_jump']) ? $question['options']['other_logic_jump'] : array();
	                        $other_logic_jump_otherwise = isset( $question['options']['other_logic_jump_otherwise'] ) && $question['options']['other_logic_jump_otherwise'] !== '' ? intval( $question['options']['other_logic_jump_otherwise'] ) : -1;

                            // URL Parameter
                            $enable_url_parameter     = (isset( $question['options']['enable_url_parameter'] )) ? $question['options']['enable_url_parameter'] : 'off';
                            $url_parameter            = (isset( $question['options']['url_parameter'] ) && $question['options']['url_parameter'] != '') ? $question['options']['url_parameter'] : '';

                            // Question Hide Results
                            $enable_hide_results     = (isset( $question['options']['enable_hide_results'] )) ? $question['options']['enable_hide_results'] : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'linear_scale_1' => $linear_scale_1,
                                'linear_scale_2' => $linear_scale_2,
                                'scale_length' => $linear_scale_length,
                                'star_1' => $star_1,
                                'star_2' => $star_2,
                                'star_scale_length' => $star_scale_length,
                                'matrix_columns' => $matrix_columns,
                                'star_list_stars_length' => $star_list_stars_length,
                                'slider_list_range_length' => $slider_list_range_length,
                                'slider_list_range_step_length' => $slider_list_range_step_length,
                                'slider_list_range_min_value' => $slider_list_range_min_value,
                                'slider_list_range_default_value' => $slider_list_range_default_value,
                                'slider_list_range_calculation_type' => $slider_list_range_calculation_type,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,                                
                                'min_selection_count' => $min_selection_count,

                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,

                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,  
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,

                                'survey_input_type_placeholder' => $survey_input_type_placeholder,

                                // Question caption
                                'image_caption'        => $question_image_caption,
                                'image_caption_enable' => $question_image_caption_enable,

                                'is_logic_jump' => $enable_logic_jump,
                                'other_answer_logic_jump' => $other_answer_logic_jump,
                                'user_explanation' => $enable_user_explanation,
                                'with_editor' => $with_editor,
                                'range_length'        => $range_type_length,
                                'range_step_length'   => $range_type_step_length,
                                'range_min_value'     => $range_type_min_value,
                                'range_default_value' => $range_type_default_value,
                                'enable_admin_note'   => $enable_admin_note,
                                'admin_note'          => $admin_note,
                                'enable_url_parameter' => $enable_url_parameter,
                                'enable_hide_results'  => $enable_hide_results,
                                'url_parameter'       => $url_parameter,
                                'file_upload_toggle'     => $file_upload_toggle,
                                'file_upload_types_pdf'  => $file_upload_types_pdf,
                                'file_upload_types_doc'  => $file_upload_types_doc,
                                'file_upload_types_png'  => $file_upload_types_png,
                                'file_upload_types_jpg'  => $file_upload_types_jpg,
                                'file_upload_types_gif'  => $file_upload_types_gif,
                                'file_upload_types_size' => $file_upload_types_size,
                                'other_logic_jump' => $other_logic_jump,
                                'other_logic_jump_otherwise' => $other_logic_jump_otherwise,
                                'html_types_content'     => $html_types_content,
                            );

                            $question_result = $wpdb->update(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_insert_id,
                                    'category_ids'      => '1',
                                    'question'          => $ays_question,
                                    'question_description'  => $ays_question_description,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
                                    'options'           => json_encode($question_options, JSON_UNESCAPED_SLASHES),
                                ),
                                array( 'id' => $question_id ),
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
                                ),
                                array( '%d' )
                            );
                            
                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                                foreach ($question['answers'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', $this->plugin_name ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';

                                    $ansopts = array();
                                    if( isset( $answer['options'] ) ){
                                        $go_to_section = (isset($answer['options']['go_to_section']) && $answer['options']['go_to_section'] != '') ? $answer['options']['go_to_section'] : '-1';
                                        if( strpos( $go_to_section, 'new_section_' ) !== false ){
                                            $go_to_section = $section_ids_for_logic_jump_array[ $go_to_section ];
                                        }
                                        $ansopts['go_to_section'] = strval( $go_to_section );
                                    }

                                    $ansopts = json_encode( $ansopts );

                                    $answer_result = $wpdb->update(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                            'options'           => $ansopts,
                                        ),
                                        array( 'id' => $answer_id ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                            '%s', // options
                                        ),
                                        array( '%d' )
                                    );

                                    // $answer_ordering++;
                                }
                            }

                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', $this->plugin_name ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';

                                    $ansopts = array();
                                    if( isset( $answer['options'] ) ){
                                        $go_to_section = (isset($answer['options']['go_to_section']) && $answer['options']['go_to_section'] != '') ? $answer['options']['go_to_section'] : '-1';
                                        if( strpos( $go_to_section, 'new_section_' ) !== false ){
                                            $go_to_section = $section_ids_for_logic_jump_array[ $go_to_section ];
                                        }
                                        $ansopts['go_to_section'] = strval( $go_to_section );
                                    }

                                    $ansopts = json_encode( $ansopts );

                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                            'options'           => $ansopts,
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

                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //

                            // $question_ordering++;
                        }
                    }

                    // $question_ordering = 1;
                    $question_id_array = array();
                    if ( isset( $section['questions_add'] ) && ! empty( $section['questions_add'] ) ) {
                        foreach ($section['questions_add'] as $question_id => $question) {
                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', $this->plugin_name );

                            $ays_question_description = ( isset($question['description']) && trim( $question['description'] ) != '' ) ? stripslashes( $question['description'] ) : '';
                            
                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            $linear_scale_1 = (isset( $question['options']['linear_scale_1'] ) && $question['options']['linear_scale_1'] != '') ? $question['options']['linear_scale_1'] : '';
                            $linear_scale_2 = (isset( $question['options']['linear_scale_2'] ) && $question['options']['linear_scale_2'] != '') ? $question['options']['linear_scale_2'] : '';
                            $linear_scale_length = (isset( $question['options']['scale_length'] ) && $question['options']['scale_length'] != '') ? $question['options']['scale_length'] : '5';
                            $star_1 = (isset( $question['options']['star_1'] ) && $question['options']['star_1'] != '') ? $question['options']['star_1'] : '';
                            $star_2 = (isset( $question['options']['star_2'] ) && $question['options']['star_2'] != '') ? $question['options']['star_2'] : '';
                            $star_scale_length = (isset( $question['options']['star_scale_length'] ) && $question['options']['star_scale_length'] != '') ? $question['options']['star_scale_length'] : '5';

                            $matrix_columns = (isset( $question['options']['columns'] )) ? $question['options']['columns'] : '';

                            // Star list options
                            $star_list_stars_length = (isset( $question['options']['star_list_stars_length'] )) ? $question['options']['star_list_stars_length'] : '';

                            // Slider list options
                            $slider_list_range_length = (isset( $question['options']['slider_list_range_length'] )) ? $question['options']['slider_list_range_length'] : '';
                            $slider_list_range_step_length = (isset( $question['options']['slider_list_range_step_length'] )) ? $question['options']['slider_list_range_step_length'] : '';
                            $slider_list_range_min_value = (isset( $question['options']['slider_list_range_min_value'] )) ? $question['options']['slider_list_range_min_value'] : '';
                            $slider_list_range_default_value = (isset( $question['options']['slider_list_range_default_value'] )) ? $question['options']['slider_list_range_default_value'] : '';
                            $slider_list_range_calculation_type = (isset( $question['options']['slider_list_range_calculation_type'] )) ? $question['options']['slider_list_range_calculation_type'] : 'seperatly';
                                                                    
                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';

                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';

                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            // Question caption
                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // Is logic jump
                            $enable_logic_jump = isset($question['options']['is_logic_jump']) ? $question['options']['is_logic_jump'] : 'off';
                            $other_answer_logic_jump = ( $user_variant == 'on' && isset($question['options']['go_to_section']) && $enable_logic_jump == 'on' ) ? $question['options']['go_to_section'] : 'off';

                            if( strpos( $other_answer_logic_jump, 'new_section_' ) !== false ){
                                $other_answer_logic_jump = $section_ids_for_logic_jump_array[ $other_answer_logic_jump ];
                            }
                            
                            // User explanation
                            $enable_user_explanation = isset($question['options']['user_explanation']) ? $question['options']['user_explanation'] : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            // Range type
                            $range_type_length        = (isset( $question['options']['range_length'] ) && $question['options']['range_length'] != '') ? $question['options']['range_length'] : '';
                            $range_type_step_length   = (isset( $question['options']['range_step_length'] ) && $question['options']['range_step_length'] != '') ? $question['options']['range_step_length'] : '';
                            $range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? $question['options']['range_min_value'] : '';
                            $range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? $question['options']['range_default_value'] : '';

                            // File upload type
                            $file_upload_toggle     = (isset( $question['options']['toggle_types'] ) && $question['options']['toggle_types'] == 'on') ? 'on' : 'off';
                            $file_upload_types_pdf  = (isset( $question['options']['upload_pdf'] ) && $question['options']['upload_pdf'] == "on") ? "on" : 'off';
                            $file_upload_types_doc  = (isset( $question['options']['upload_doc'] ) && $question['options']['upload_doc'] == "on") ? "on" : 'off';
                            $file_upload_types_png  = (isset( $question['options']['upload_png'] ) && $question['options']['upload_png'] == "on") ? "on" : 'off';
                            $file_upload_types_jpg  = (isset( $question['options']['upload_jpg'] ) && $question['options']['upload_jpg'] == "on") ? "on" : 'off';
                            $file_upload_types_gif  = (isset( $question['options']['upload_gif'] ) && $question['options']['upload_gif'] == "on") ? "on" : 'off';
                            $file_upload_types_size = (isset( $question['options']['upload_size'] ) && $question['options']['upload_size'] != "") ? $question['options']['upload_size'] : '5';
                            
                            //HTML Type Content
                            $html_types_content = (isset( $question['options']['html_type_editor'] ) && $question['options']['html_type_editor'] != "") ? stripslashes($question['options']['html_type_editor']) : '';

                            // Admin note
                            $enable_admin_note     = (isset( $question['options']['enable_admin_note'] )) ? $question['options']['enable_admin_note'] : 'off';
                            $admin_note            = (isset( $question['options']['admin_note'] ) && $question['options']['admin_note'] != '') ? $question['options']['admin_note'] : '';

                            // Checkbox type logic jump
                            $other_logic_jump      = isset($question['options']['other_logic_jump']) && !empty($question['options']['other_logic_jump']) ? $question['options']['other_logic_jump'] : array();
                            $other_logic_jump_otherwise = isset( $question['options']['other_logic_jump_otherwise'] ) && $question['options']['other_logic_jump_otherwise'] !== '' ? intval( $question['options']['other_logic_jump_otherwise'] ) : -1;

                            // URL Parameter
                            $enable_url_parameter     = (isset( $question['options']['enable_url_parameter'] )) ? $question['options']['enable_url_parameter'] : 'off';
                            $url_parameter            = (isset( $question['options']['url_parameter'] ) && $question['options']['url_parameter'] != '') ? $question['options']['url_parameter'] : '';

                            // Question Hide Results
                            $enable_hide_results     = (isset( $question['options']['enable_hide_results'] )) ? $question['options']['enable_hide_results'] : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'linear_scale_1' => $linear_scale_1,
                                'linear_scale_2' => $linear_scale_2,
                                'scale_length' => $linear_scale_length,
                                'star_1' => $star_1,
                                'star_2' => $star_2,
                                'star_scale_length' => $star_scale_length,
                                'matrix_columns' => $matrix_columns,
                                'star_list_stars_length' => $star_list_stars_length,
                                'slider_list_range_length' => $slider_list_range_length,
                                'slider_list_range_step_length' => $slider_list_range_step_length,
                                'slider_list_range_min_value' => $slider_list_range_min_value,
                                'slider_list_range_default_value' => $slider_list_range_default_value,
                                'slider_list_range_calculation_type' => $slider_list_range_calculation_type,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,

                                'survey_input_type_placeholder' => $survey_input_type_placeholder,
                                                                
                                'image_caption'               => $question_image_caption,
                                'image_caption_enable'        => $question_image_caption_enable,
                                
                                'is_logic_jump' => $enable_logic_jump,
                                'other_answer_logic_jump' => $other_answer_logic_jump,
                                'user_explanation' => $enable_user_explanation,
                                'with_editor' => $with_editor,
                                'range_length'        => $range_type_length,
                                'range_step_length'   => $range_type_step_length,
                                'range_min_value'     => $range_type_min_value,
                                'range_default_value' => $range_type_default_value,
                                'enable_admin_note'   => $enable_admin_note,
                                'admin_note'          => $admin_note,
                                'enable_url_parameter'   => $enable_url_parameter,
                                'enable_hide_results'    => $enable_hide_results,
                                'url_parameter'          => $url_parameter,
                                'file_upload_toggle'     => $file_upload_toggle,
                                'file_upload_types_pdf'  => $file_upload_types_pdf,
                                'file_upload_types_doc'  => $file_upload_types_doc,
                                'file_upload_types_png'  => $file_upload_types_png,
                                'file_upload_types_jpg'  => $file_upload_types_jpg,
                                'file_upload_types_gif'  => $file_upload_types_gif,
                                'file_upload_types_size' => $file_upload_types_size,
                                'other_logic_jump' => $other_logic_jump,
                                'other_logic_jump_otherwise' => $other_logic_jump_otherwise,
                                'html_types_content'     => $html_types_content,
                            );

                            $question_result = $wpdb->insert(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_insert_id,
                                    'category_ids'      => '1',
                                    'question'          => $ays_question,
                                    'question_description' => $ays_question_description,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
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
                            $question_ids_new[] = $question_insert_id;
                            $survey_old_questions[$question_insert_id] = $ays_question;

                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', $this->plugin_name ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';

                                    $ansopts = array();
                                    if( isset( $answer['options'] ) ){
                                        $go_to_section = (isset($answer['options']['go_to_section']) && $answer['options']['go_to_section'] != '') ? $answer['options']['go_to_section'] : '-1';
                                        if( strpos( $go_to_section, 'new_section_' ) !== false ){
                                            $go_to_section = $section_ids_for_logic_jump_array[ $go_to_section ];
                                        }
                                        $ansopts['go_to_section'] = strval( $go_to_section );
                                    }

                                    $ansopts = json_encode( $ansopts );

                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_insert_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                            'options'           => $ansopts,
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
                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //
                            // $question_ordering++;
                        }
                    }
                    // --------------------------- Question Table ------------End--------------- //
                    // $section_ordering++;
                }
                // --------------------------- Sections Table ------------End--------------- //
            }

            if (isset($_POST[ $name_prefix . 'sections' ]) && !empty( $_POST[ $name_prefix . 'sections' ] )) {
                // --------------------------- Sections Table ------------Start--------------- //

                // $section_ordering = 1;
                $textareas = array(
                    'description',
                    'title'
                );

                // $section_update = Survey_Maker_Data::recursive_sanitize_text_field( $_POST[ $name_prefix . 'sections' ], $textareas );
                $section_update = $_POST[ $name_prefix . 'sections' ];

                foreach ( $section_update as $section_id => $section) {
                    $section_id = absint(intval($section_id));
                    $section_title = $section['title'];
                    $section_description = $section['description'];
                    $section_ordering = $section['ordering'];

                    // Section collapsed
                    $section_collapsed = ( isset($section['options']['collapsed']) && $section['options']['collapsed'] != '' ) ? $section['options']['collapsed'] : 'expanded';

                    // Go to section
                    $sections_go_to_section = (isset($section['options']['go_to_section']) && $section['options']['go_to_section'] != '') ? $section['options']['go_to_section'] : '-1';

                    if( strpos( $sections_go_to_section, 'new_section_' ) !== false ){
                        $sections_go_to_section = $section_ids_for_logic_jump_array[ $sections_go_to_section ];
                    }

                    $sections_go_to_section = strval( $sections_go_to_section );

                    // Options
                    $section_options = array(
                        'collapsed' => $section_collapsed,
                        'go_to_section' => $sections_go_to_section
                    );

                    $result = $wpdb->update(
                        $sections_table,
                        array(
                            'title'             => $section_title,
                            'description'       => $section_description,
                            'ordering'          => $section_ordering,
                            'options'           => json_encode( $section_options ),
                        ),
                        array( 'id' => $section_id ),
                        array(
                            '%s', // title
                            '%s', // description
                            '%d', // ordering
                            '%s', // options
                        ),
                        array( '%d' )
                    );

                    // --------------------------- Question Table ------------Start--------------- //
                    // $question_ordering = 1;
                    if( isset( $section['questions'] ) && !empty( $section['questions'] ) ){
                        foreach ($section['questions'] as $question_id => $question) {
                            $question_id = absint(intval($question_id));

                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', $this->plugin_name );

                            if( ! isset( $survey_old_questions[$question_id] ) ){
                                $survey_old_questions[$question_id] = $ays_question;
                            }
                            
                            $ays_question_description = ( isset($question['description']) && trim( $question['description'] ) != '' ) ? stripslashes( $question['description'] ) : '';

                            if( ! isset( $survey_old_question_descriptions[$question_id] ) ){
                                $survey_old_question_descriptions[$question_id] = $ays_question_description;
                            }

                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            $linear_scale_1 = (isset( $question['options']['linear_scale_1'] ) && $question['options']['linear_scale_1'] != '') ? $question['options']['linear_scale_1'] : '';
                            $linear_scale_2 = (isset( $question['options']['linear_scale_2'] ) && $question['options']['linear_scale_2'] != '') ? $question['options']['linear_scale_2'] : '';
                            $linear_scale_length = (isset( $question['options']['scale_length'] ) && $question['options']['scale_length'] != '') ? $question['options']['scale_length'] : '5';
                            $star_1 = (isset( $question['options']['star_1'] ) && $question['options']['star_1'] != '') ? $question['options']['star_1'] : '';
                            $star_2 = (isset( $question['options']['star_2'] ) && $question['options']['star_2'] != '') ? $question['options']['star_2'] : '';
                            $star_scale_length = (isset( $question['options']['star_scale_length'] ) && $question['options']['star_scale_length'] != '') ? $question['options']['star_scale_length'] : '5';

                            $matrix_columns = (isset( $question['options']['columns'] )) ? $question['options']['columns'] : '';

                            // Star list options
                            $star_list_stars_length = (isset( $question['options']['star_list_stars_length'] )) ? $question['options']['star_list_stars_length'] : '';

                            // Slider list options
                            $slider_list_range_length = (isset( $question['options']['slider_list_range_length'] )) ? $question['options']['slider_list_range_length'] : '';
                            $slider_list_range_step_length = (isset( $question['options']['slider_list_range_step_length'] )) ? $question['options']['slider_list_range_step_length'] : '';
                            $slider_list_range_min_value = (isset( $question['options']['slider_list_range_min_value'] )) ? $question['options']['slider_list_range_min_value'] : '';
                            $slider_list_range_default_value = (isset( $question['options']['slider_list_range_default_value'] )) ? $question['options']['slider_list_range_default_value'] : '';
                            $slider_list_range_calculation_type = (isset( $question['options']['slider_list_range_calculation_type'] )) ? $question['options']['slider_list_range_calculation_type'] : 'seperatly';
                            
                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';

                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';
                            
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            // Question caption
                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // Is logic jump
                            $enable_logic_jump = isset($question['options']['is_logic_jump']) ? $question['options']['is_logic_jump'] : 'off';
                            $other_answer_logic_jump = ( $user_variant == 'on' && isset($question['options']['go_to_section']) && $enable_logic_jump == 'on' ) ? $question['options']['go_to_section'] : 'off';

                            if( strpos( $other_answer_logic_jump, 'new_section_' ) !== false ){
                                $other_answer_logic_jump = $section_ids_for_logic_jump_array[ $other_answer_logic_jump ];
                            }

                            // User explanation
                            $enable_user_explanation = isset($question['options']['user_explanation']) ? $question['options']['user_explanation'] : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            // Range type
                            $range_type_length        = (isset( $question['options']['range_length'] ) && $question['options']['range_length'] != '') ? $question['options']['range_length'] : '';
                            $range_type_step_length   = (isset( $question['options']['range_step_length'] ) && $question['options']['range_step_length'] != '') ? $question['options']['range_step_length'] : '';
                            $range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? $question['options']['range_min_value'] : '';
                            $range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? $question['options']['range_default_value'] : '';

                            // File upload type
                            $file_upload_toggle     = (isset( $question['options']['toggle_types'] ) && $question['options']['toggle_types'] == 'on') ? 'on' : 'off';
                            $file_upload_types_pdf  = (isset( $question['options']['upload_pdf'] ) && $question['options']['upload_pdf'] == "on") ? "on" : 'off';
                            $file_upload_types_doc  = (isset( $question['options']['upload_doc'] ) && $question['options']['upload_doc'] == "on") ? "on" : 'off';
                            $file_upload_types_png  = (isset( $question['options']['upload_png'] ) && $question['options']['upload_png'] == "on") ? "on" : 'off';
                            $file_upload_types_jpg  = (isset( $question['options']['upload_jpg'] ) && $question['options']['upload_jpg'] == "on") ? "on" : 'off';
                            $file_upload_types_gif  = (isset( $question['options']['upload_gif'] ) && $question['options']['upload_gif'] == "on") ? "on" : 'off';
                            $file_upload_types_size = (isset( $question['options']['upload_size'] ) && $question['options']['upload_size'] != "") ? $question['options']['upload_size'] : '5';
                            
                            //HTML Type Content
                            $html_types_content = (isset( $question['options']['html_type_editor'] ) && $question['options']['html_type_editor'] != "") ? stripslashes($question['options']['html_type_editor']) : '';

                            // Admin note
                            $enable_admin_note     = (isset( $question['options']['enable_admin_note'] )) ? $question['options']['enable_admin_note'] : 'off';
                            $admin_note            = (isset( $question['options']['admin_note'] ) && $question['options']['admin_note'] != '') ? $question['options']['admin_note'] : '';

                            // Checkbox type logic jump
	                        $other_logic_jump      = isset($question['options']['other_logic_jump']) && !empty($question['options']['other_logic_jump']) ? $question['options']['other_logic_jump'] : array();
	                        $other_logic_jump_otherwise = isset( $question['options']['other_logic_jump_otherwise'] ) && $question['options']['other_logic_jump_otherwise'] !== '' ? intval( $question['options']['other_logic_jump_otherwise'] ) : -1;

                            // URL Parameter
                            $enable_url_parameter     = (isset( $question['options']['enable_url_parameter'] )) ? $question['options']['enable_url_parameter'] : 'off';
                            $url_parameter            = (isset( $question['options']['url_parameter'] ) && $question['options']['url_parameter'] != '') ? $question['options']['url_parameter'] : '';                            

                            // Question Hide Results
                            $enable_hide_results     = (isset( $question['options']['enable_hide_results'] )) ? $question['options']['enable_hide_results'] : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'linear_scale_1' => $linear_scale_1,
                                'linear_scale_2' => $linear_scale_2,
                                'scale_length' => $linear_scale_length,
                                'star_1' => $star_1,
                                'star_2' => $star_2,
                                'star_scale_length' => $star_scale_length,
                                'matrix_columns' => $matrix_columns,
                                'star_list_stars_length' => $star_list_stars_length,
                                'slider_list_range_length' => $slider_list_range_length,
                                'slider_list_range_step_length' => $slider_list_range_step_length,
                                'slider_list_range_min_value' => $slider_list_range_min_value,
                                'slider_list_range_default_value' => $slider_list_range_default_value,
                                'slider_list_range_calculation_type' => $slider_list_range_calculation_type,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,

                                'survey_input_type_placeholder' => $survey_input_type_placeholder,
                                
                                'image_caption'     => $question_image_caption,
                                'image_caption_enable'     => $question_image_caption_enable,

                                'is_logic_jump' => $enable_logic_jump,
                                'other_answer_logic_jump' => $other_answer_logic_jump,
                                'user_explanation' => $enable_user_explanation,
                                'with_editor' => $with_editor,
                                'range_length'        => $range_type_length,
                                'range_step_length'   => $range_type_step_length,
                                'range_min_value'     => $range_type_min_value,
                                'range_default_value' => $range_type_default_value,
                                'enable_admin_note'   => $enable_admin_note,
                                'admin_note'          => $admin_note,
                                'enable_url_parameter'   => $enable_url_parameter,
                                'enable_hide_results'    => $enable_hide_results,
                                'url_parameter'          => $url_parameter,
                                'file_upload_toggle'     => $file_upload_toggle,
                                'file_upload_types_pdf'  => $file_upload_types_pdf,
                                'file_upload_types_doc'  => $file_upload_types_doc,
                                'file_upload_types_png'  => $file_upload_types_png,
                                'file_upload_types_jpg'  => $file_upload_types_jpg,
                                'file_upload_types_gif'  => $file_upload_types_gif,
                                'file_upload_types_size' => $file_upload_types_size,
                                'other_logic_jump' => $other_logic_jump,
	                            'other_logic_jump_otherwise' => $other_logic_jump_otherwise,
                                'html_types_content'     => $html_types_content,
                            );

                            $question_result = $wpdb->update(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_id,
                                    'category_ids'      => '1',
                                    'question'          => $ays_question,
                                    'question_description'  => $ays_question_description,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
                                    'options'           => json_encode($question_options, JSON_UNESCAPED_SLASHES),
                                ),
                                array( 'id' => $question_id ),
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
                                ),
                                array( '%d' )
                            );

                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                                foreach ($question['answers'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', $this->plugin_name ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';

                                    $ansopts = array();
                                    if( isset( $answer['options'] ) ){
                                        $go_to_section = (isset($answer['options']['go_to_section']) && $answer['options']['go_to_section'] != '') ? $answer['options']['go_to_section'] : '-1';
                                        if( strpos( $go_to_section, 'new_section_' ) !== false ){
                                            $go_to_section = $section_ids_for_logic_jump_array[ $go_to_section ];
                                        }
                                        $ansopts['go_to_section'] = strval( $go_to_section );
                                    }

                                    $ansopts = json_encode( $ansopts );

                                    $answer_result = $wpdb->update(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                            'options'           => $ansopts,
                                        ),
                                        array( 'id' => $answer_id ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                            '%s', // options
                                        ),
                                        array( '%d' )
                                    );

                                    // $answer_ordering++;
                                }
                            }

                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', $this->plugin_name ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';

                                    $ansopts = array();
                                    if( isset( $answer['options'] ) ){
                                        $go_to_section = (isset($answer['options']['go_to_section']) && $answer['options']['go_to_section'] != '') ? $answer['options']['go_to_section'] : '-1';
                                        if( strpos( $go_to_section, 'new_section_' ) !== false ){
                                            $go_to_section = $section_ids_for_logic_jump_array[ $go_to_section ];
                                        }
                                        $ansopts['go_to_section'] = strval( $go_to_section );
                                    }

                                    $ansopts = json_encode( $ansopts );

                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                            'options'           => $ansopts,
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

                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //

                            // $question_ordering++;
                        }
                    }

                    if( isset( $section['questions_add'] ) && !empty( $section['questions_add'] ) ){
                        foreach ($section['questions_add'] as $question_id => $question) {
                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', $this->plugin_name );

                            $ays_question_description = ( isset($question['description']) && trim( $question['description'] ) != '' ) ? stripslashes( $question['description'] ) : '';
                            
                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            $linear_scale_1 = (isset( $question['options']['linear_scale_1'] ) && $question['options']['linear_scale_1'] != '') ? $question['options']['linear_scale_1'] : '';
                            $linear_scale_2 = (isset( $question['options']['linear_scale_2'] ) && $question['options']['linear_scale_2'] != '') ? $question['options']['linear_scale_2'] : '';
                            $linear_scale_length = (isset( $question['options']['scale_length'] ) && $question['options']['scale_length'] != '') ? $question['options']['scale_length'] : '5';
                            $star_1 = (isset( $question['options']['star_1'] ) && $question['options']['star_1'] != '') ? $question['options']['star_1'] : '';
                            $star_2 = (isset( $question['options']['star_2'] ) && $question['options']['star_2'] != '') ? $question['options']['star_2'] : '';
                            $star_scale_length = (isset( $question['options']['star_scale_length'] ) && $question['options']['star_scale_length'] != '') ? $question['options']['star_scale_length'] : '5';

                            $matrix_columns = (isset( $question['options']['columns'] )) ? $question['options']['columns'] : '';
                            // Star list options
                            $star_list_stars_length = (isset( $question['options']['star_list_stars_length'] )) ? $question['options']['star_list_stars_length'] : '';

                            // Slider list options
                            $slider_list_range_length = (isset( $question['options']['slider_list_range_length'] )) ? $question['options']['slider_list_range_length'] : '';
                            $slider_list_range_step_length = (isset( $question['options']['slider_list_range_step_length'] )) ? $question['options']['slider_list_range_step_length'] : '';
                            $slider_list_range_min_value = (isset( $question['options']['slider_list_range_min_value'] )) ? $question['options']['slider_list_range_min_value'] : '';
                            $slider_list_range_default_value = (isset( $question['options']['slider_list_range_default_value'] )) ? $question['options']['slider_list_range_default_value'] : '';
                            $slider_list_range_calculation_type = (isset( $question['options']['slider_list_range_calculation_type'] )) ? $question['options']['slider_list_range_calculation_type'] : 'seperatly';

                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';

                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            // Question caption
                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // Is logic jump
                            $enable_logic_jump = isset($question['options']['is_logic_jump']) ? $question['options']['is_logic_jump'] : 'off';
                            $other_answer_logic_jump = ( $user_variant == 'on' && isset($question['options']['go_to_section']) && $enable_logic_jump == 'on' ) ? $question['options']['go_to_section'] : 'off';

                            if( strpos( $other_answer_logic_jump, 'new_section_' ) !== false ){
                                $other_answer_logic_jump = $section_ids_for_logic_jump_array[ $other_answer_logic_jump ];
                            }

                            
                            // User explanation
                            $enable_user_explanation = isset($question['options']['user_explanation']) ? $question['options']['user_explanation'] : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            // Range type
                            $range_type_length        = (isset( $question['options']['range_length'] ) && $question['options']['range_length'] != '') ? $question['options']['range_length'] : '';
                            $range_type_step_length   = (isset( $question['options']['range_step_length'] ) && $question['options']['range_step_length'] != '') ? $question['options']['range_step_length'] : '';
                            $range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? $question['options']['range_min_value'] : '';
                            $range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? $question['options']['range_default_value'] : '';

                            // File upload type
                            $file_upload_toggle     = (isset( $question['options']['toggle_types'] ) && $question['options']['toggle_types'] == 'on') ? 'on' : 'off';
                            $file_upload_types_pdf  = (isset( $question['options']['upload_pdf'] ) && $question['options']['upload_pdf'] == "on") ? "on" : 'off';
                            $file_upload_types_doc  = (isset( $question['options']['upload_doc'] ) && $question['options']['upload_doc'] == "on") ? "on" : 'off';
                            $file_upload_types_png  = (isset( $question['options']['upload_png'] ) && $question['options']['upload_png'] == "on") ? "on" : 'off';
                            $file_upload_types_jpg  = (isset( $question['options']['upload_jpg'] ) && $question['options']['upload_jpg'] == "on") ? "on" : 'off';
                            $file_upload_types_gif  = (isset( $question['options']['upload_gif'] ) && $question['options']['upload_gif'] == "on") ? "on" : 'off';
                            $file_upload_types_size = (isset( $question['options']['upload_size'] ) && $question['options']['upload_size'] != "") ? $question['options']['upload_size'] : '5';
                            
                            //HTML Type content
                            $html_types_content = (isset( $question['options']['html_type_editor'] ) && $question['options']['html_type_editor'] != "") ? stripslashes($question['options']['html_type_editor']) : '';

                            // Admin note
                            $enable_admin_note     = (isset( $question['options']['enable_admin_note'] )) ? $question['options']['enable_admin_note'] : 'off';
                            $admin_note            = (isset( $question['options']['admin_note'] ) && $question['options']['admin_note'] != '') ? $question['options']['admin_note'] : '';

                            // Checkbox type logic jump
	                        $other_logic_jump      = isset($question['options']['other_logic_jump']) && !empty($question['options']['other_logic_jump']) ? $question['options']['other_logic_jump'] : array();
	                        $other_logic_jump_otherwise = isset( $question['options']['other_logic_jump_otherwise'] ) && $question['options']['other_logic_jump_otherwise'] !== '' ? intval( $question['options']['other_logic_jump_otherwise'] ) : -1;

                            // URL Parameter
                            $enable_url_parameter     = (isset( $question['options']['enable_url_parameter'] )) ? $question['options']['enable_url_parameter'] : 'off';
                            $url_parameter            = (isset( $question['options']['url_parameter'] ) && $question['options']['url_parameter'] != '') ? $question['options']['url_parameter'] : ''; 

                            // Question Hide Results
                            $enable_hide_results     = (isset( $question['options']['enable_hide_results'] )) ? $question['options']['enable_hide_results'] : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'linear_scale_1' => $linear_scale_1,
                                'linear_scale_2' => $linear_scale_2,
                                'scale_length' => $linear_scale_length,
                                'star_1' => $star_1,
                                'star_2' => $star_2,
                                'star_scale_length' => $star_scale_length,
                                'matrix_columns' => $matrix_columns,
                                'star_list_stars_length' => $star_list_stars_length,
                                'slider_list_range_length' => $slider_list_range_length,
                                'slider_list_range_step_length' => $slider_list_range_step_length,
                                'slider_list_range_min_value' => $slider_list_range_min_value,
                                'slider_list_range_default_value' => $slider_list_range_default_value,
                                'slider_list_range_calculation_type' => $slider_list_range_calculation_type,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,

                                'survey_input_type_placeholder' => $survey_input_type_placeholder,

                                'image_caption'     => $question_image_caption,
                                'image_caption_enable'     => $question_image_caption_enable,
                                
                                'is_logic_jump' => $enable_logic_jump,
                                'other_answer_logic_jump' => $other_answer_logic_jump,
                                'user_explanation' => $enable_user_explanation,
                                'with_editor' => $with_editor,
                                'range_length'        => $range_type_length,
                                'range_step_length'   => $range_type_step_length,
                                'range_min_value'     => $range_type_min_value,
                                'range_default_value' => $range_type_default_value,
                                'enable_admin_note'   => $enable_admin_note,
                                'admin_note'          => $admin_note,
                                'enable_url_parameter'   => $enable_url_parameter,
                                'enable_hide_results'    => $enable_hide_results,
                                'url_parameter'          => $url_parameter,
                                'file_upload_toggle'     => $file_upload_toggle,
                                'file_upload_types_pdf'  => $file_upload_types_pdf,
                                'file_upload_types_doc'  => $file_upload_types_doc,
                                'file_upload_types_png'  => $file_upload_types_png,
                                'file_upload_types_jpg'  => $file_upload_types_jpg,
                                'file_upload_types_gif'  => $file_upload_types_gif,
                                'file_upload_types_size' => $file_upload_types_size,
                                'other_logic_jump' => $other_logic_jump,
                                'other_logic_jump_otherwise' => $other_logic_jump_otherwise,

                                'html_types_content'     => $html_types_content,
                            );

                            $question_result = $wpdb->insert(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_id,
                                    'category_ids'      => '1',
                                    'question'          => $ays_question,
                                    'question_description'  => $ays_question_description,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
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

                            $question_new_id = $wpdb->insert_id;
                            $question_ids_new[] = $question_new_id;
                            $survey_old_questions[$question_new_id] = $ays_question;

                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', $this->plugin_name ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $ansopts = array();
                                    if( isset( $answer['options'] ) ){
                                        $go_to_section = (isset($answer['options']['go_to_section']) && $answer['options']['go_to_section'] != '') ? $answer['options']['go_to_section'] : '-1';
                                        if( strpos( $go_to_section, 'new_section_' ) !== false ){
                                            $go_to_section = $section_ids_for_logic_jump_array[ $go_to_section ];
                                        }
                                        $ansopts['go_to_section'] = strval( $go_to_section );
                                    }

                                    $ansopts = json_encode( $ansopts );

                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_new_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                            'options'           => $ansopts,
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

                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //

                            // $question_ordering++;
                        }
                    }
                    // --------------------------- Question Table ------------End--------------- //

                    // $section_ordering++;
                }
                // --------------------------- Sections Table ------------End--------------- //
            }

            // Add post for survey
            $add_post_for_survey = (isset($_POST['ays_add_post_for_survey']) && $_POST['ays_add_post_for_survey'] == 'on') ? 'on' : 'off';
            $add_postcat_for_survey =  isset($_POST['ays_add_postcat_for_survey']) ? $_POST['ays_add_postcat_for_survey'] : array();
            $post_id_for_survey =  isset($_POST['ays_post_id_for_survey']) ? absint( sanitize_text_field( $_POST['ays_post_id_for_survey'] ) ) : null;
 

            // NEW PLACE FOR INTEGRATIONS FILTERS
            $_POST['all_questions'] = $survey_old_questions;
            $options = apply_filters( "ays_sm_survey_page_integrations_saves", $options, $_POST);

            $message = '';
            if( $id == 0 ){
                $sections_count = count( $section_ids_array );
                $questions_count = count( $question_ids_new );
                $section_ids = empty( $section_ids_array ) ? '' : implode( ',', $section_ids_array );
                $question_ids = empty( $question_ids_new ) ? '' : implode( ',', $question_ids_new );
                $result = $wpdb->insert(
                    $table,
                    array(
                        'author_id'         => $author_id,
                        'title'             => $title,
                        'description'       => $description,
                        'category_ids'      => $category_ids,
                        'question_ids'      => $question_ids,
                        'sections_count'    => $sections_count,
                        'questions_count'   => $questions_count,
                        'image'             => $image,
                        'status'            => $status,
                        'trash_status'      => $trash_status,
                        'date_created'      => $date_created,
                        'date_modified'     => $date_modified,
                        'ordering'          => $ordering,
                        'section_ids'       => $section_ids,
                        'conditions'        => $conditions,
                        'options'           => json_encode( $options, JSON_UNESCAPED_SLASHES ),
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
                $post_type_args = array(
                    'survey_id' => $inserted_id,
                    'author_id' => $author_id,
                    'survey_title'     => $title,
                );
                
                $custom_post_id = Survey_Maker_Custom_Post_Type::survey_add_custom_post($post_type_args);

                $message = 'created';
            }else{
                if( ! empty( $section_ids ) ){
                    if( ! empty( $section_ids_array ) ){
                        $section_ids = array_merge( $section_ids, $section_ids_array );
                    }
                }else{
                    if( ! empty( $section_ids_array ) ){
                        $section_ids = array_merge( $section_ids, $section_ids_array );
                    }
                }
                $sections_count = count( $section_ids );
                $section_ids = !empty( $section_ids ) ? implode(',', $section_ids) : '';
                // $question_ids = empty( $question_ids_new ) ? '' : implode( ',', $question_ids_new );
                if( ! empty( $question_ids ) ){
                    if( ! empty( $question_ids_new ) ){
                        $question_ids = array_merge( $question_ids, $question_ids_new );
                    }
                }else{
                    if( ! empty( $question_ids_new ) ){
                        $question_ids = array_merge( $question_ids, $question_ids_new );
                    }
                }
                $questions_count = count( $question_ids );
                $question_ids = empty( $question_ids ) ? '' : implode( ',', $question_ids );


                $result = $wpdb->update(
                    $table,
                    array(
                        'author_id'         => $author_id,
                        'title'             => $title,
                        'description'       => $description,
                        'category_ids'      => $category_ids,
                        'question_ids'      => $question_ids,
                        'sections_count'    => $sections_count,
                        'questions_count'   => $questions_count,
                        'date_created'      => $date_created,
                        'image'             => $image,
                        'status'            => $status,
                        'date_modified'     => $date_modified,
                        'section_ids'       => $section_ids,
                        'conditions'        => $conditions,
                        'options'           => json_encode( $options, JSON_UNESCAPED_SLASHES ),
                    ),
                    array( 'id' => $id ),
                    array(
                        '%d', // author_id
                        '%s', // title
                        '%s', // description
                        '%s', // category_ids
                        '%s', // question_ids
                        '%d', // sections_count
                        '%d', // questions_count
                        '%s', // date_created
                        '%s', // image
                        '%s', // status
                        '%s', // date_modified
                        '%s', // section_ids
                        '%s', // conditions
                        '%s', // options
                    ),
                    array( '%d' )
                );

                $inserted_id = $id;
                $message = 'updated';
            }

            /*
            ==========================================
            Adding post for survey and inserting
            survey shortcode into post
            ==========================================
            */

            if($id === 0){
                $survey_id = $inserted_id;
            }else{
                $survey_id = $id;
            }
            
            if( $post_id_for_survey === null ){
                if($add_post_for_survey == "on"){
                    global $user_id;

                    $post_content = '[ays_survey id="'.$survey_id.'"]';

                    if ( Survey_Maker_Admin::is_active_gutenberg() ) {
                        $post_content = '<!-- wp:survey-maker/survey {"metaFieldValue":'.$survey_id.',"shortcode":"[ays_survey id='.$survey_id.']"} -->
                        <div class="wp-block-survey-maker-survey">[ays_survey id="'.$survey_id.'"]</div>
                        <!-- /wp:survey-maker/survey -->';
                    }

                    $new_post = array(
                        'post_title' => $title,
                        'post_content' => $post_content,
                        'post_status' => 'publish',
                        'post_date' => current_time( 'mysql' ),
                        'post_author' => $user_id,
                        'post_type' => 'post',
                        'post_category' => $add_postcat_for_survey
                    );
                    $post_id = wp_insert_post($new_post);
                    if(! empty($image)){
                        $sql = "SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'attachment' AND guid = '".$image."'";
                        $attachment_id = intval($wpdb->get_var($sql));
                        if($attachment_id !== 0){
                            $featured_image = set_post_thumbnail($post_id, $attachment_id);
                        }
                    }
                    $survey_post_result = $wpdb->update(
                        $table,
                        array( 'post_id' => $post_id ),
                        array( 'id' => $survey_id ),
                        array( '%d' ),
                        array( '%d' )
                    );
                }
            }else{
                $current_status = get_post_status( $post_id_for_survey );
                if ( false === $current_status ) {
                    $survey_post_result = $wpdb->update(
                        $table,
                        array( 'post_id' => null ),
                        array( 'id' => $survey_id ),
                        array( '%d' ),
                        array( '%d' )
                    );
                }
            }

            /*
            ==========================================
                Creating post end
            ==========================================
            */

            $ays_survey_tab = isset($_POST['ays_survey_tab']) ? sanitize_text_field( $_POST['ays_survey_tab'] ) : 'tab1';

            if($message == 'created'){
                setcookie('ays_survey_created_new', $inserted_id, time() + 3600, '/');
            }

            if( $result >= 0  ) {
                if($save_type == 'apply'){
                    if($id == 0){
                        $url = esc_url_raw( add_query_arg( array(
                            "action"    => "edit",
                            "id"        => $inserted_id,
                            "tab"       => $ays_survey_tab,
                            "status"    => $message
                        ) ) );
                    }else{
                        $url = esc_url_raw( add_query_arg( array(
                            "tab"    => $ays_survey_tab,
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
                        "tab"    => $ays_survey_tab,
                        "status" => $message
                    ), $url ) );
                    wp_redirect( $url );
                }
            }

        }
    }

    private function get_max_id() {
        global $wpdb;
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $sql = "SELECT MAX(id) FROM {$table}";

        $result = $wpdb->get_var($sql);

        return $result;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_items( $id ) {
        global $wpdb;

        $survey_custom_post_id = Survey_Maker_Data::get_survey_current_column($id, 'custom_post_id', 'trashed');

        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'survey_id' => absint( $id ) ),
            array( '%d' )
        );

        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys",
            array( 'id' => absint( $id ) ),
            array( '%d' )
        );

        if(isset($survey_custom_post_id) && $survey_custom_post_id > 0){
            $survey_custom_post_id = intval($survey_custom_post_id);
            $check_custom_post_type = get_post_type($survey_custom_post_id);
            if(isset($check_custom_post_type) && $check_custom_post_type == 'ays-survey-maker'){
                wp_delete_post($survey_custom_post_id);
            }
        }


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
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'status' => 'trashed' ),
            array( 'survey_id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys",
            array( 
                'status' => 'trashed',
                'trash_status' => $db_item['status'],
            ),
            array( 'id' => absint( $id ) ),
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
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'status' => 'published' ),
            array( 'survey_id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys",
            array( 
                'status' => $db_item['trash_status'],
                'trash_status' => '',
            ),
            array( 'id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );
    }

    /**
     * Duplicate a customer record.
     *
     * @param int $id customer ID
     */
    public function duplicate_items( $id ){
        global $wpdb;
        $survey_table    = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        $sections_table  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";
        $questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $answers_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";

        $object = $this->get_item_by_id( $id );

        $question_id_arr = isset($object['question_ids']) && $object['question_ids'] != "" ? explode("," , $object['question_ids']) : array(); 
        $old_questions = array();
        $survey_id   = isset($object['id']) && $object['id'] != "" ? $object['id'] : "";
        $section_ids = isset($object['section_ids']) && $object['section_ids'] != "" ? $object['section_ids'] : array();

        // Get sections
        $old_sections = Survey_Maker_Data::get_sections_by_survey_id($section_ids);
        $section_new_ids = array();

        // Get questions
        $old_questions = Survey_Maker_Data::get_question_by_ids($question_id_arr, false, false, true);        
        $question_new_ids = array();

        // Get conditions
        $conditions = isset($object['conditions']) && $object['conditions'] != "" ? json_decode( $object['conditions'], true ) : array();
        $other_types = array(
            "number",
            "text",
            "range",
            "star",
            "linear_scale",
            "short_text",
            "date",           
            "name",
            "email"
        );

        $all_new_questions = array();
        foreach($old_sections as $section_key => $section_value){
            $section_id            = isset($section_value['id']) && $section_value['id'] != "" ? sanitize_text_field($section_value['id']) : "";
            $new_section_title     = isset($section_value['title']) && $section_value['title'] != "" ? sanitize_text_field($section_value['title']) : "";
            $new_section_desc      = isset($section_value['description']) && $section_value['description'] != "" ? stripslashes($section_value['description']) : "";
            $new_section_ordering  = isset($section_value['ordering']) && $section_value['ordering'] != "" ? intval(sanitize_text_field($section_value['ordering'])) : "";
            $new_section_options   = isset($section_value['options']) && $section_value['options'] != "" ? sanitize_text_field($section_value['options']) : "";

            
            $sresult = $wpdb->insert(
                $sections_table,
                array(
                    'title'       => $new_section_title,
                    'description' => $new_section_desc,
                    'ordering'    => $new_section_ordering,
                    'options'     => $new_section_options,
                ),
                array(
                    '%s', // title
                    '%s', // description
                    '%d', // ordering
                    '%s', // options
                    )
                );
            $section_new_ids[] = $wpdb->insert_id;
            $section_new_ids_for_question[$section_id] = $wpdb->insert_id;
        }
        
        foreach($old_questions as $question_key => $question_value){
            $question_id  = isset($question_value->id) && $question_value->id != "" ? intval(sanitize_text_field($question_value->id)) : "";
            $question_answers        = isset($question_value->answers) && $question_value->answers != "" ? $question_value->answers : array();
            $new_question_author_id  = isset($question_value->author_id) && $question_value->author_id != "" ? intval(sanitize_text_field($question_value->author_id)) : "";
            $new_question_section_id = isset($section_new_ids_for_question[$question_value->section_id]) && $section_new_ids_for_question[$question_value->section_id] != "" ? intval(sanitize_text_field($section_new_ids_for_question[$question_value->section_id])) : "";
            $new_question_cat_ids    = isset($question_value->category_ids) && $question_value->category_ids != "" ? sanitize_text_field($question_value->category_ids) : "";
            $new_question_title      = isset($question_value->question) && $question_value->question != "" ? sanitize_text_field($question_value->question) : "";
            $new_question_description = isset($question_value->question_description) && $question_value->question_description != "" ? sanitize_text_field($question_value->question_description) : "";
            $new_question_type       = isset($question_value->type) && $question_value->type != "" ? sanitize_text_field($question_value->type) : "";
            $new_question_status     = isset($question_value->status) && $question_value->status != "" ? sanitize_text_field($question_value->status) : "";
            $new_question_create_date      = current_time( 'mysql' );
            $new_question_modified_date    = current_time( 'mysql' );
            $new_question_user_variant     = isset($question_value->user_variant) && $question_value->user_variant != "" ? sanitize_text_field($question_value->user_variant) : "";
            $new_question_user_explanation = isset($question_value->user_explanation) && $question_value->user_explanation != "" ? sanitize_text_field($question_value->user_explanation) : "";
            $new_question_image        = isset($question_value->image) && $question_value->image != "" ? sanitize_text_field($question_value->image) : "";
            $new_question_ordering     = isset($question_value->ordering) && $question_value->ordering != "" ? intval(sanitize_text_field($question_value->ordering)) : "";
            $new_question_options      = isset($question_value->options) && $question_value->options != "" ? sanitize_text_field($question_value->options) : "";
            
            $question_result = $wpdb->insert(
                $questions_table,
                array(
                    'author_id'        => $new_question_author_id,
                    'section_id'       => $new_question_section_id,
                    'category_ids'     => $new_question_cat_ids,
                    'question'         => $new_question_title,
                    'question_description'   => $new_question_description,
                    'type'             => $new_question_type,
                    'status'           => $new_question_status,
                    'date_created'     => $new_question_create_date,
                    'date_modified'    => $new_question_modified_date,
                    'user_variant'     => $new_question_user_variant,
                    'user_explanation' => $new_question_user_explanation,
                    'image'            => $new_question_image,
                    'ordering'         => $new_question_ordering,
                    'options'          => $new_question_options,
                ),
                array(
                    '%d', // author id
                    '%d', // section id
                    '%s', // category ids
                    '%s', // title
                    '%s', // description
                    '%s', // type
                    '%s', // status
                    '%s', // date created
                    '%s', // date modified
                    '%s', // user variant
                    '%s', // user xplanation
                    '%s', // image
                    '%d', // ordering
                    '%s', // options
                )
            );
            $question_new_ids[] = $wpdb->insert_id;
            $question_new_ids_for_answers[$question_id] = $wpdb->insert_id;
            $all_new_questions[$wpdb->insert_id] = $new_question_title;
            if(!empty($question_answers)){
                foreach($question_answers as $answer_key => $answer_value){
                    $new_answer_question_id = isset($question_new_ids_for_answers[$answer_value->question_id]) && $question_new_ids_for_answers[$answer_value->question_id] != "" ? intval(sanitize_text_field($question_new_ids_for_answers[$answer_value->question_id])) : "";
                    $new_answer_title = isset($answer_value->answer) && $answer_value->answer != "" ? sanitize_text_field($answer_value->answer) : "";
                    $new_answer_image = isset($answer_value->image) && $answer_value->image != "" ? sanitize_text_field($answer_value->image) : "";
                    $new_answer_ordering = isset($answer_value->ordering) && $answer_value->ordering != "" ? sanitize_text_field($answer_value->ordering) : "";
                    $new_answer_placeholder = isset($answer_value->placeholder) && $answer_value->placeholder != "" ? sanitize_text_field($answer_value->placeholder) : "";
                    $new_answer_options = isset($answer_value->options) && $answer_value->options != "" ? json_decode($answer_value->options , true) : array();
                    if(intval($new_answer_options['go_to_section']) != -1 && intval($new_answer_options['go_to_section']) != -2){
                        $new_answer_options['go_to_section'] = isset($section_new_ids_for_question[$new_answer_options['go_to_section']]) ? $section_new_ids_for_question[$new_answer_options['go_to_section']] : '-1'; 
                    }
                    $new_answer_options = json_encode($new_answer_options);
                    $answer_result = $wpdb->insert(
                        $answers_table,
                        array(
                            'question_id' => $new_answer_question_id,
                            'answer'      => $new_answer_title,
                            'image'       => $new_answer_image,
                            'ordering'    => $new_answer_ordering,
                            'placeholder' => $new_answer_placeholder,
                            'options'     => $new_answer_options,
                        ),
                        array(
                            '%d', // question id
                            '%s', // title
                            '%s', // image
                            '%s', // ordering
                            '%d', // placeholder
                            '%s', // options
                        )
                    );
                    if( !empty($conditions) ){
                        foreach($conditions as $condition_key_main => &$condition_value_main){
                            foreach($condition_value_main['condition_question_add'] as $condition_key_answer => &$condition_value_answer){
                                if($question_id == intval($condition_value_answer['question_id'])){
                                    $condition_value_answer['question_id'] = $question_new_ids_for_answers[$condition_value_answer['question_id']];
                                }
                                if(!in_array($condition_value_answer['type'], $other_types)){
                                    if($answer_value->id == $condition_value_answer['answer']){
                                        $condition_value_answer['answer'] = $wpdb->insert_id;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else{
                if( !empty($conditions) ){
                    foreach($conditions as $condition_key_main => &$condition_value_main){
                        foreach($condition_value_main['condition_question_add'] as $condition_key_answer => &$condition_value_answer){
                            if($question_id == intval($condition_value_answer['question_id'])){
                                $condition_value_answer['question_id'] = $question_new_ids_for_answers[$condition_value_answer['question_id']];
                            }
                        }
                    }
                }
            }
        }
        
        $author_id = get_current_user_id();
        
        $max_id = $this->get_max_id();
        $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
        
        $options = json_decode( $object['options'], true );
        $additional_data = array(
            'ays_title' => "Copy - " . $object['title'],
            'all_questions' => $all_new_questions,
            // Google sheet
            'ays_enable_google' => $options['enable_google_sheets'],
            // Mailchimp
            'ays_enable_mailchimp' => $options['enable_mailchimp'],            
            'ays_mailchimp_list'   => $options['mailchimp_list'],
            // Camp Monitor
            'ays_enable_monitor' => $options['enable_monitor'],
            'ays_monitor_list'   => $options['monitor_list'],
            // Zapier
            'ays_enable_zapier' => $options['enable_zapier'],
            // Active Camp
            'ays_enable_active_camp'     => $options['enable_active_camp'],
            'ays_active_camp_list'       => $options['active_camp_list'],
            'ays_active_camp_automation' => $options['active_camp_automation'],
            // Slack
            'ays_enable_slack'       => $options['enable_slack'],
            'ays_slack_conversation' => $options['slack_conversation'],
            // Sendgrid
            'ays_survey_sendgrid_template_id' => $options['survey_sendgrid_template_id'],
            // Mad mimi
            'ays_survey_enable_mad_mimi' => $options['enable_mad_mimi'],
            'ays_survey_mad_mimi_list'   => $options['mad_mimi_list'],
            // getResponse
            'ays_survey_enable_getResponse' => $options['enable_getResponse'],
            'ays_survey_getResponse_list'   => $options['getResponse_list'],
            // convertkit
            'ays_survey_enable_convertkit' => $options['enable_convertKit'],
            'ays_survey_convertKit_list'   => $options['convertKit_form_id'],
            // convertkit
            'ays_survey_enable_sendinblue'  => $options['enable_sendinblue'],
            'ays_survey_sendinblue_list_id' => $options['sendinblue_list_id'],
            // mailerLite
            'ays_survey_enable_mailerLite'   => $options['enable_mailerLite'],
            'ays_survey_mailerLite_group_id' => $options['mailerLite_group_id'],

            // Paypal
            'ays_survey_enable_paypal'   => $options['survey_enable_paypal'],
            'ays_survey_paypal_amount'   => $options['survey_paypal_amount'],
            'ays_survey_paypal_currency' => $options['survey_paypal_currency'],
            'ays_survey_paypal_message'  => $options['survey_paypal_message'],
            // Stripe
            'ays_survey_enable_stripe'   => $options['survey_enable_stripe'],
            'ays_survey_stripe_amount'   => $options['survey_stripe_amount'],
            'ays_survey_stripe_currency' => $options['survey_stripe_currency'],
            'ays_survey_stripe_message'  => $options['survey_stripe_message'],

            // reCaptcha
            'ays_survey_enable_recaptcha' => $options['enable_recaptcha'],
            
        );
        $options = apply_filters( "ays_sm_survey_page_integrations_saves", $options, $additional_data);
        
        $result = $wpdb->insert(
            $survey_table,
            array(
                'author_id'         => $author_id,
                'title'             => "Copy - " . $object['title'],
                'description'       => $object['description'],
                'category_ids'      => $object['category_ids'],
                'question_ids'      => implode("," , $question_new_ids),
                'section_ids'       => implode("," , $section_new_ids),
                'sections_count'    => $object['sections_count'],
                'questions_count'   => $object['questions_count'],
                'date_created'      => current_time( 'mysql' ),
                'date_modified'     => current_time( 'mysql' ),
                'image'             => $object['image'],
                'status'            => $object['status'],
                'trash_status'      => $object['trash_status'],
                'ordering'          => $ordering,
                'post_id'           => 0,
                'options'           => json_encode( $options, JSON_UNESCAPED_SLASHES ),
                'conditions'        => json_encode($conditions, JSON_UNESCAPED_SLASHES),
            ),
            array(
                '%d', // author_id
                '%s', // title
                '%s', // description
                '%s', // category_ids
                '%s', // question_ids
                '%s', // section ids
                '%d', // sections count
                '%d', // questions count
                '%s', // date_created
                '%s', // date_modified
                '%s', // image
                '%s', // status
                '%s', // trash_status
                '%d', // ordering
                '%d', // post_id
                '%s', // options
                '%s', // conditions
            )
        );    
        
        $inserted_id = $wpdb->insert_id;
        $post_type_args = array(
            'survey_id' => $inserted_id,
            'author_id' => $author_id,
            'survey_title' => "Copy - " . $object['title'],
        );
        $custom_post_id = Survey_Maker_Custom_Post_Type::survey_add_custom_post($post_type_args);

    }



    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;
        $filter = array();
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        
        if( isset( $_GET['filterby'] ) && intval($_GET['filterby']) > 0){
            $cat_id = intval( sanitize_text_field( $_GET['filterby'] ) );
            $filter[] = ' FIND_IN_SET('.$cat_id.',category_ids) ';
        }
        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $filter[] = ' author_id ='.$user_id;
        }

        if( isset( $_REQUEST['fstatus'] ) ){
            $fstatus = sanitize_text_field( $_REQUEST['fstatus'] );
            if($fstatus !== null){
                $filter[] = " status = '". esc_sql( $fstatus ) ."' ";
            }
        }else{
            $filter[] = " status != 'trashed' ";
        }
        
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $filter[] = " author_id = ".$current_user." ";
        }
        
        if(count($filter) !== 0){
            $sql .= " WHERE ".implode(" AND ", $filter);
        }

        return $wpdb->get_var( $sql );
    }
    
    public static function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE status != 'trashed'";

        if( isset( $_GET['filterby'] ) && intval($_GET['filterby']) > 0){
            $cat_id = intval( sanitize_text_field( $_GET['filterby'] ) );
            $sql .= ' AND FIND_IN_SET('.$cat_id.',category_ids) ';
        }
        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $sql .= ' AND author_id ='.$user_id;
        }

        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $sql .= " AND author_id = ".$current_user." ";
        }

        return $wpdb->get_var( $sql );
    }

    public static function published_questions_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions WHERE status = 'published'";
        
        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $sql .= " AND author_id = ".$current_user." ";
        }

        return $wpdb->get_var( $sql );
    }

    public static function get_statused_record_count( $status ) {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE status='" . esc_sql( $status ) . "'";

        if( isset( $_GET['filterby'] ) && intval($_GET['filterby']) > 0){
            $cat_id = absint( sanitize_text_field( $_GET['filterby'] ) );
            $sql .= ' AND FIND_IN_SET('.$cat_id.',category_ids) ';
        }
        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $sql .= ' AND author_id ='.$user_id;
        }

        if( ! Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $current_user = get_current_user_id();
            $sql .= " AND author_id = ".$current_user." ";
        }

        return $wpdb->get_var( $sql );
    }

    public static function get_passed_users_count( $id ) {
        global $wpdb;
        $id = absint( $id );
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions WHERE survey_id=".$id;

        return $wpdb->get_var( $sql );
    }


    /** Text displayed when no customer data is available */
    public function no_items() {
        Survey_Maker_Data::survey_no_items_list_tables('surveys');
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
            case 'category_ids':
            case 'shortcode':
            case 'code_include':
            case 'items_count':
            case 'sections_count':
            case 'author_id':
            case 'submissions_count':
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

        $survey_title = stripcslashes( $item['title'] );
        $custom_post_id = isset($item['custom_post_id']) && $item['custom_post_id'] != 0 && $item['custom_post_id'] != '' ? esc_attr($item['custom_post_id']) : 0;

        $q = esc_attr( $survey_title );

        $restitle = Survey_Maker_Admin::ays_restriction_string( "word", $survey_title, $this->title_length );
        
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
            
            $actions['edit'] = sprintf( '<a href="?page=%s&action=%s&id=%d">'. __('Edit', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) );
            $actions['submissions'] = sprintf( '<a href="?page=%s&survey=%d">'. __('View submissions', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ) . '-each-submission', absint( $item['id'] ) );
            if($custom_post_id > 0){
                $actions['custom_posts'] = sprintf( '<a href="%s" target="_blank">'. __('Preview', "survey-maker") .'</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($item['custom_post_id']) ) ));
            }

            $actions['duplicate'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Duplicate', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'duplicate', absint( $item['id'] ), $delete_nonce );
            $actions['trash'] = sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Move to trash', $this->plugin_name) .'</a>', esc_attr( $_REQUEST['page'] ), 'trash', absint( $item['id'] ), $delete_nonce );
        }

        return $title . $this->row_actions( $actions );
    }

    function column_category_ids( $item ) {
        global $wpdb;

        // Survey categories IDs
        $category_ids = isset( $item['category_ids'] ) && $item['category_ids'] != '' ? $item['category_ids'] : '';
        // $category_ids = $category_ids == '' ? array() : explode( ',', $category_ids );
        
        if( ! empty( $category_ids ) ){
            $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories WHERE id IN (" . esc_sql( $category_ids ) . ")";

            $results = $wpdb->get_results($sql, 'ARRAY_A');
            
            $titles = array();
            foreach ($results as $key => $value) {
                $category_location[] = sprintf( '<a href="?page=%s&action=%s&id=%d" target="_blank">%s</a>', 'survey-maker-survey-categories', 'edit', $value['id'], $value['title']);
            }

            if(!empty($category_location)){
                $titles = implode( ', ', $category_location );
            }

            return $titles;
        }

        return '-';
    }

    function column_code_include( $item ) {
        $shortcode = htmlentities('[\'[ays_survey id="'.$item["id"].'"]\']');
        return '<input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="<?php echo do_shortcode('.$shortcode.'); ?>" style="max-width:100%;" />';
    }

    function column_shortcode( $item ) {
        $shortcode = htmlentities('[ays_survey id="'.$item["id"].'"]');
        return '<input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="'.$shortcode.'" />';
    }

    function column_status( $item ) {
        global $wpdb;
        $status_text = '';
        switch ( $item['status'] ) {
            case 'draft':
                $status_text = __('draft', $this->plugin_name);
                break;
            default:
                $status_text = __('published', $this->plugin_name);
                break;
        }
        $status = ucfirst( $status_text );
        $date = date( 'Y/m/d', strtotime( $item['date_modified'] ) );
        $title_date = date( 'l jS \of F Y h:i:s A', strtotime( $item['date_modified'] ) );
        $html = "<p style='font-size:14px;margin:0;'>" . $status . "</p>";
        $html .= "<p style=';font-size:14px;margin:0;text-decoration: dotted underline;' title='" . $title_date . "'>" . $date . "</p>";
        return $html;
    }

    function column_author_id( $item ) {
        $user = get_user_by( 'id', $item['author_id'] );
        $author_name = '';
        if($user->data->display_name == ''){
            if($user->data->user_nicename == ''){
                $author_name = $user->data->user_login;
            }else{
                $author_name = $user->data->user_nicename;
            }
        }else{
            $author_name = $user->data->display_name;
        }
        return $author_name;
    }

    function column_submissions_count( $item ) {
        $id = $item['id'];
        $passed_count = $this->get_passed_users_count( $id );
        $passed_count = sprintf( '<a href="?page=%s&survey=%d">'. $passed_count .'</a>', esc_attr( $_REQUEST['page'] ) . '-each-submission', absint( $item['id'] ) );
        $text = "<p style='font-size:14px;'>".$passed_count."</p>";
        return $text;
    }

    function column_items_count( $item ) {
        global $wpdb;
        if(empty($item['questions_count'])){
            $count = 0;
        }else{
            $count = intval($item['questions_count']);
        }
        return "<p style='font-size:14px;'>" . $count . "</p>";
    }

    function column_sections_count( $item ) {
        global $wpdb;
        if(empty($item['sections_count'])){
            $count = 0;
        }else{
            $count = intval($item['sections_count']);
        }
        return "<p style='font-size:14px;'>" . $count . "</p>";
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title', $this->plugin_name ),
        );

        if( Survey_Maker_Data::survey_maker_capabilities_for_editing() ){
            $columns['author_id'] = __( 'Author', $this->plugin_name );
        }

        $columns['category_ids'] = __( 'Categories', $this->plugin_name );
        $columns['shortcode'] = __( 'Shortcode', $this->plugin_name );
        $columns['code_include'] = __( 'Code include', $this->plugin_name );
        $columns['items_count'] = __( 'Questions', $this->plugin_name );
        $columns['sections_count'] = __( 'Sections', $this->plugin_name );
        $columns['submissions_count'] = __( 'Submissions', $this->plugin_name );
        $columns['status'] = __( 'Status', $this->plugin_name );
        $columns['id'] = __( 'ID', $this->plugin_name );

        if( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ){
            return array();
        }
        
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
     * Columns to make hidden.
     *
     * @return array
     */
    public function get_hidden_columns() {
        $sortable_columns = array(
            'category_ids',
            'code_include',
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
            // 'bulk-duplicate' => __( 'Duplicate', $this->plugin_name ),
            'bulk-trash' => __( 'Move to trash', $this->plugin_name ),
        );

        if(isset($_GET['fstatus']) && sanitize_text_field( $_GET['fstatus'] ) == 'trashed'){
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
        global $wpdb;
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'surveys_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items / $per_page )
        ) );

        $search = ( isset( $_REQUEST['s'] ) ) ? sanitize_text_field( $_REQUEST['s'] ) : false;

        $do_search = ( $search ) ? sprintf( " title LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) ) : '';

        $this->items = self::get_items( $per_page, $current_page, $do_search );
    }

    public function process_bulk_action() {
       
        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-survey' ) ) {
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
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
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

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-trash-survey' ) ) {
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
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
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

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-survey' ) ) {
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
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        //Detect when a bulk action is being triggered...
        if ( 'duplicate' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-trash-survey' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::duplicate_items( absint( $_GET['id'] ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'duplicated'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

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
            $add_query_args = array(
                "status" => 'all-deleted'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-trash' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-trash' ) ) {

            $trash_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $trash_ids as $id ) {
                self::trash_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-trashed'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-restore' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-restore' ) ) {

            $restore_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $restore_ids as $id ) {
                self::restore_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-restored'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-duplicate' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-duplicate' ) ) {

            $restore_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $restore_ids as $id ) {
                self::duplicate_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-duplicated'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }
    }

    public function survey_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        $error = false;
        switch ( $status ) {
            case 'created':
                $updated_message = esc_html( __( 'Survey created.', $this->plugin_name ) );
                break;
            case 'updated':
                $updated_message = esc_html( __( 'Survey saved.', $this->plugin_name ) );
                break;
            case 'duplicated':
                $updated_message = esc_html( __( 'Survey duplicated.', $this->plugin_name ) );
                break;
            case 'deleted':
                $updated_message = esc_html( __( 'Survey deleted.', $this->plugin_name ) );
                break;
            case 'trashed':
                $updated_message = esc_html( __( 'Survey moved to trash.', $this->plugin_name ) );
                break;
            case 'restored':
                $updated_message = esc_html( __( 'Survey restored.', $this->plugin_name ) );
                break;
            case 'all-duplicated':
                $updated_message = esc_html( __( 'Surveys are duplicated.', $this->plugin_name ) );
                break;
            case 'all-deleted':
                $updated_message = esc_html( __( 'Surveys are deleted.', $this->plugin_name ) );
                break;
            case 'all-trashed':
                $updated_message = esc_html( __( 'Surveys are moved to trash.', $this->plugin_name ) );
                break;
            case 'all-restored':
                $updated_message = esc_html( __( 'Surveys are restored.', $this->plugin_name ) );
                break;
            case 'empty-title':
                $error = true;
                $updated_message = esc_html( __( 'Error: Survey title can not be empty.', $this->plugin_name ) );
                break;
            default:
                break;
        }

        if ( empty( $updated_message ) )
            return;

        $notice_class = 'success';
        if( $error ){
            $notice_class = 'error';
        }
        ?>
        <div class="notice notice-<?php echo esc_attr( $notice_class ); ?> is-dismissible">
            <p> <?php echo $updated_message; ?> </p>
        </div>
        <?php
    }
}
