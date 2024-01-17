<?php

$action = ( isset($_GET['action']) ) ? sanitize_key( $_GET['action'] ) : '';
$id     = ( isset($_GET['id']) ) ? absint( sanitize_key( $_GET['id'] ) ) : null;

if( $action == 'duplicate' && !is_null($id) ){
    $this->customers_obj->duplicate_customers($id);
}
$plus_icon_svg = "<span class=''><img src='". SURVEY_MAKER_ADMIN_URL ."/images/icons/plus-icon.svg'></span>";
?>
<div class="wrap">
    <div class="ays-survey-heading-box">
        <div class="ays-survey-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                <i class="ays_fa ays_fa_file_text" ></i> 
                <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
            </a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
        <?php
        echo __( esc_html(get_admin_page_title()), $this->plugin_name );
        // echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">' . __( 'Add New', $this->plugin_name ) . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
        echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-survey-add-new-button-new-design"> %s ' . __( 'Add New', "survey-maker" ) . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);
        ?>
    </h1>

    <div id="poststuff" class="ays-survey-categroies-list-table-main-box">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->customers_obj->views();
                    ?>
                    <form method="post">
                        <?php
                            $this->customers_obj->prepare_items(); // clientes
                            $search = __( "Search", $this->plugin_name );
                            $this->customers_obj->search_box($search, $this->plugin_name);
                            $this->customers_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
