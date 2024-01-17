<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ays-pro.com/wordpress/survey-maker
 * @since             1.0.0
 * @package           Social_Survey
 *
 * @wordpress-plugin
 * Plugin Name:       Social Survey
 * Plugin URI:        https://jocartec.com/social-survey
 * Description:       Social Survey plugin allows you to create unlimited surveys with unlimited sections and unlimited questions.
 * Version:           1.0.0
 * Author:            Social Surveyteam
 * Author URI:        https://jocartec.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       social-survey
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SURVEY_MAKER_VERSION', '1.0.0' );
define( 'SURVEY_MAKER_NAME_VERSION', '1.0.0' );
define( 'SURVEY_MAKER_NAME', 'social-survey' );
define( 'SURVEY_MAKER_DB_PREFIX', 'socialsurv_' );

if( ! defined( 'SURVEY_MAKER_BASENAME' ) )
    define( 'SURVEY_MAKER_BASENAME', plugin_basename( __FILE__ ) );

if( ! defined( 'SURVEY_MAKER_DIR' ) )
    define( 'SURVEY_MAKER_DIR', plugin_dir_path( __FILE__ ) );

if( ! defined( 'SURVEY_MAKER_BASE_URL' ) )
    define( 'SURVEY_MAKER_BASE_URL', plugin_dir_url(__FILE__ ) );

if( ! defined( 'SURVEY_MAKER_ADMIN_PATH' ) )
    define( 'SURVEY_MAKER_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin' );

if( ! defined( 'SURVEY_MAKER_ADMIN_URL' ) )
    define( 'SURVEY_MAKER_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin' );

if( ! defined( 'SURVEY_MAKER_PUBLIC_PATH' ) )
    define( 'SURVEY_MAKER_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public' );

if( ! defined( 'SURVEY_MAKER_PUBLIC_URL' ) )
    define( 'SURVEY_MAKER_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-survey-maker-activator.php
 */
function activate_survey_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-survey-maker-activator.php';
	Social_Survey_Activator::ays_survey_update_db_check();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-survey-maker-deactivator.php
 */
function deactivate_survey_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-survey-maker-deactivator.php';
	Social_Survey_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_survey_maker' );
register_deactivation_hook( __FILE__, 'deactivate_survey_maker' );

add_action( 'plugins_loaded', 'activate_survey_maker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-survey-maker.php';
require plugin_dir_path( __FILE__ ) . 'survey/survey-maker-block.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_survey_maker() {

    // add_action( 'activated_plugin', 'survey_maker_activation_redirect_method' );
    add_action( 'admin_notices', 'survey_maker_general_admin_notice' );
	$plugin = new Survey_Maker();
	$plugin->run();

}

function survey_maker_activation_redirect_method( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=' . SURVEY_MAKER_NAME ) ) );
    }
}

function survey_maker_general_admin_notice(){
    global $wpdb;
    if ( isset($_GET['page']) && strpos($_GET['page'], SURVEY_MAKER_NAME) !== false ) {
        ?>
         <div class="ays-notice-banner">
            <div class="navigation-bar">
                <div id="navigation-container">
                    <!-- <a class="logo-container" href="https://ays-pro.com/" target="_blank">
                        <img class="logo" src="<?php echo SURVEY_MAKER_ADMIN_URL . '/images/ays_pro.png'; ?>" alt="AYS Pro logo" title="AYS Pro logo"/>
                    </a> -->
                    <div class="logo-container">
                        <!-- <img  src="<?php echo plugin_dir_url( __FILE__ ) . 'survey/survey_logo.png'; ?>" alt="Social Survey" title="Social Survey"/> -->
                        <img  src="<?php echo plugin_dir_url( __FILE__ ) . 'survey/survey_logo.png'; ?>" alt="Social Survey" title="Social Survey"/>
                    </div>

                    <ul id="menu">
                        <!-- <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank">DOCUMENTATION</a></li> -->
                        <!-- <li class="modile-ddmenu-xs"><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/reviews/" target="_blank">RATE US</a></li> -->
                        <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://ays-demo.com/wordpress-survey-plugin-pro-demo/" target="_blank"><?php echo __( "DEMO", SURVEY_MAKER_NAME ); ?></a></li>
                        <li class="modile-ddmenu-lg take_survay"><a class="ays-btn" href="https://ays-demo.com/survey-maker-plugin-survey/" target="_blank"><?php echo __( "MAKE A SUGGESTION", SURVEY_MAKER_NAME ); ?></a></li>
                        <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/" target="_blank"><?php echo __( "SUPPORT FORUM", SURVEY_MAKER_NAME ); ?></a></li>
                        <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/" target="_blank"><?php echo __( "CONTACT US", SURVEY_MAKER_NAME ); ?></a></li>

                        <li class="modile-ddmenu-md">
                            <a class="toggle_ddmenu" href="javascript:void(0);"><i class="ays_fa ays_fa_ellipsis_h"></i></a>
                            <ul class="ddmenu" data-expanded="false">
                                <!-- <li><a class="ays-btn" href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank">DOCUMENTATION</a></li> -->
                                <!-- <li><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/reviews/" target="_blank">RATE US</a></li> -->
                                <li><a class="ays-btn" href="https://ays-demo.com/wordpress-survey-plugin-pro-demo/" target="_blank"><?php echo __( "DEMO", SURVEY_MAKER_NAME ); ?></a></li>
                                <li class="take_survay"><a class="ays-btn" href="https://ays-demo.com/survey-maker-plugin-survey/" target="_blank"><?php echo __( "MAKE A SUGGESTION", SURVEY_MAKER_NAME ); ?></a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/" target="_blank"><?php echo __( "SUPPORT FORUM", SURVEY_MAKER_NAME ); ?></a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/" target="_blank"><?php echo __( "CONTACT US", SURVEY_MAKER_NAME ); ?></a></li>

                            </ul>
                        </li>
                        <li class="modile-ddmenu-sm">
                            <a class="toggle_ddmenu" href="javascript:void(0);"><i class="ays_fa ays_fa_ellipsis_h"></i></a>
                            <ul class="ddmenu" data-expanded="false">
                                <!-- <li><a class="ays-btn" href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank">DOCUMENTATION</a></li> -->
                                <!-- <li><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/reviews/" target="_blank">RATE US</a></li> -->
                                <li><a class="ays-btn" href="https://ays-demo.com/wordpress-survey-plugin-pro-demo/" target="_blank"><?php echo __( "DEMO", SURVEY_MAKER_NAME ); ?></a></li>
                                <li class="take_survay"><a class="ays-btn" href="https://ays-demo.com/survey-maker-plugin-survey/" target="_blank"><?php echo __( "MAKE A SUGGESTION", SURVEY_MAKER_NAME ); ?></a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/" target="_blank"><?php echo __( "SUPPORT FORUM", SURVEY_MAKER_NAME ); ?></a></li>
                                <li><a class="ays-btn" href="https://wordpress.org/support/plugin/survey-maker/" target="_blank"><?php echo __( "CONTACT US", SURVEY_MAKER_NAME ); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
         </div>
     <?php
    }
}

run_survey_maker();
