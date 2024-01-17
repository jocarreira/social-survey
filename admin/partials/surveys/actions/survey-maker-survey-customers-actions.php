<?php
    //CUSTOMERS
    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/survey-maker-survey-customers-actions-options.php" );
?>
<div class="wrap">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;"><?php echo __( "View Documentation", $this->plugin_name ); ?></span>
                </a>
            </div>
        </div>
        <form class="ays-survey-customer-form" id="ays-survey-customer-form" method="post">
            <div class="ays-settings-wrapper-tabs">
                <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                    <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_survey_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                        <?php echo __("Cadastro de Cliente", $this->plugin_name);
                        //require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/survey-maker-survey-customers-actions-tab1.php" );
                        ?>
                    </a>
                    <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_survey_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                        <?php echo __("Manutenção de Listas", $this->plugin_name);
                        //require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/survey-maker-survey-customers-actions-tab2.php" );
                        ?>
                    </a>
                </div>
            </div>              
            <?php
                require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/survey-maker-survey-customers-actions-tab1.php" );
                require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/survey-maker-survey-customers-actions-tab2.php" );
            ?>
            
        </form>
    </div>
</div>
