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
        <?php echo __(esc_html(get_admin_page_title()),$this->plugin_name); ?>
    </h1>
    <div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form method="post">
                            <?php
                                $this->requests_obj->prepare_items();
                                $this->requests_obj->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
