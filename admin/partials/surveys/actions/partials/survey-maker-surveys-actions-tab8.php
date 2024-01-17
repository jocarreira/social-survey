<div id="tab8" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab8') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Integrations settings',$this->plugin_name)?></p>
    <hr/>
    <?php 
        $args = apply_filters( 'ays_sm_survey_page_integrations_options', array(), $options );
        do_action( 'ays_sm_survey_page_integrations', $args );
    ?>
</div>
