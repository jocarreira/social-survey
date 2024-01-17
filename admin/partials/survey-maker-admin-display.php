<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/admin/partials
 */

$survey_page_url = sprintf('?page=%s', 'survey-maker');
$add_new_url = sprintf('?page=%s&action=%s', 'survey-maker', 'add');

?>
<div class="wrap">
    <!-- <div class="ays-survey-maker-wrapper" style="position:relative;">
        <h1 class="ays_heart_beat"><?php echo __(esc_html(get_admin_page_title()),$this->plugin_name); ?> <i class="ays_fa ays_fa_heart_o animated"></i></h1>
    </div> -->
    <div class="ays-survey-heart-beat-main-heading ays-survey-heart-beat-main-heading-container">
        <h1 class="ays-survey-maker-wrapper ays_heart_beat">
            <?php echo __(esc_html(get_admin_page_title()),$this->plugin_name); ?> <i class="ays_fa ays_fa_heart_o animated"></i>
        </h1>
    </div>
    <div class="ays-survey-faq-main">
        <h2>
            <?php echo __("How to create a simple survey in 3 steps with the help of the", $this->plugin_name ) .
            ' <strong>'. __("Social Survey", $this->plugin_name ) .'</strong> '.
            __("plugin.", $this->plugin_name ); ?>
            
        </h2>
        <fieldset>
            <div class="ays-survey-ol-container">
                <ol>
                    <li>
                        <?php echo __( "Go to the", $this->plugin_name ) . ' <a href="'. $survey_page_url .'" target="_blank">'. __( "Surveys" , $this->plugin_name ) .'</a> ' .  __( "page and build your first survey by clicking on the", $this->plugin_name ) . ' <a href="'. $add_new_url .'" target="_blank">'. __( "Add New" , $this->plugin_name ) .'</a> ' .  __( "button", $this->plugin_name ); ?>,
                    </li>
                    <li>
                        <?php echo __( "Fill out the information by adding a title, creating questions and so on.", $this->plugin_name ); ?>
                    </li>
                    <li>
                        <?php echo __( "Copy the", $this->plugin_name ) . ' <strong>'. __( "shortcode" , $this->plugin_name ) .'</strong> ' .  __( "of the survey and paste it into any post․", $this->plugin_name ); ?> 
                    </li>
                </ol>
            </div>
            <div class="ays-survey-p-container">
                <p><?php echo __("Congrats! You have already created your first survey." , $this->plugin_name); ?></p>
            </div>
        </fieldset>
    </div>
    <br>

    <div class="ays-survey-community-wrap">
        <div class="ays-survey-community-title">
            <h4><?php echo __( "Community", $this->plugin_name ); ?></h4>
        </div>
        <div class="ays-survey-community-youtube-video">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/EMN9MlMGlbo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
        </div>
        <div class="ays-survey-community-container">
            <div class="ays-survey-community-item">
                <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="ays-survey-community-item-cover" >
                    <i class="ays-survey-community-item-img ays_fa ays_fa_youtube_play"></i>
                </a>
                <h3 class="ays-survey-community-item-title"><?php echo __( "YouTube community", $this->plugin_name ); ?></h3>
                <p class="ays-survey-community-item-desc"><?php echo __("Our YouTube community  guides you to step by step tutorials about our products and not only...", $this->plugin_name); ?></p>
                <div class="ays-survey-community-item-footer">
                    <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="button"><?php echo __( "Subscribe", $this->plugin_name ); ?></a>
                </div>
            </div>
            <div class="ays-survey-community-item">
                <a href="https://wordpress.org/support/plugin/survey-maker/" target="_blank" class="ays-survey-community-item-cover" >
                    <i class="ays-survey-community-item-img ays_fa ays_fa_wordpress"></i>
                </a>
                <h3 class="ays-survey-community-item-title"><?php echo __( "Best Free support", $this->plugin_name ); ?></h3>
                <p class="ays-survey-community-item-desc"><?php echo __( "With the Free version, you get a lifetime usage for the plugin, however, you will get new updates and support for only 1 month.", $this->plugin_name ); ?></p>
                <div class="ays-survey-community-item-footer">
                    <a href="https://wordpress.org/support/plugin/survey-maker/" target="_blank" class="button"><?php echo __( "Join", $this->plugin_name ); ?></a>
                </div>
            </div>
            <div class="ays-survey-community-item">
                <a href="https://ays-pro.com/contact" target="_blank" class="ays-survey-community-item-cover" >
                    <i class="ays-survey-community-item-img ays_fa ays_fa_users" aria-hidden="true"></i>
                </a>
                <h3 class="ays-survey-community-item-title"><?php echo __( "Premium support", $this->plugin_name ); ?></h3>
                <p class="ays-survey-community-item-desc"><?php echo __( "Get 12 months updates and support for the Business package and lifetime updates and support for the Developer package.", $this->plugin_name ); ?></p>

                <div class="ays-survey-community-item-footer">
                    <a href="https://ays-pro.com/contact" target="_blank" class="button"><?php echo __( "Contact", $this->plugin_name ); ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="ays-survey-faq-main">
        <p class="ays-survey-faq-footer">
            <?php echo __( "For more advanced needs, please take a look at our" , $this->plugin_name ); ?> 
            <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank"><?php echo __( "Survey Maker plugin User Manual." , $this->plugin_name ); ?></a>
            <br>
            <?php echo __( "If none of these guides help you, ask your question by contacting our" , $this->plugin_name ); ?>
            <a href="https://ays-pro.com/contact" target="_blank"><?php echo __( "support specialists." , $this->plugin_name ); ?></a> 
            <?php echo __( "and get a reply within a day." , $this->plugin_name ); ?>
        </p>
    </div>
</div>
<script>
    // var acc = document.getElementsByClassName("ays-survey-asked-question__header");
    // var i;
    // for (i = 0; i < acc.length; i++) {
    //   acc[i].addEventListener("click", function() {
        
    //     var panel = this.nextElementSibling;
        
        
    //     if (panel.style.maxHeight) {
    //       panel.style.maxHeight = null;
    //       this.children[1].children[0].style.transform="rotate(0deg)";
    //     } else {
    //       panel.style.maxHeight = panel.scrollHeight + "px";
    //       this.children[1].children[0].style.transform="rotate(180deg)";
    //     } 
    //   });
    // }
</script>
