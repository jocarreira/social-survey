<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if( get_option('ays_survey_maker_upgrade_plugin', 'false') === 'false' ){
    global $wpdb;

    $enterviewers_table             = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'enterviewers';
    $customers_table                = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'customers';
    $samples_table                  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'samples';

    $surveys_table                  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
    $questions_table                = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'questions';
    $sections_table                 = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sections';
    $survey_categories_table        = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'survey_categories';
    $question_categories_table      = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'question_categories';
    $answers_table                  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'answers';
    $submissions_table              = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'submissions';
    $submissions_questions_table    = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'submissions_questions';
    $settings_table                 = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'settings';
    $popup_surveys_table            = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'popup_surveys';
    $orders_table                   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'orders';
    $requests_table                 = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'requests';


    $wpdb->query("DROP TABLE IF EXISTS `".$enterviewers_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$customers_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$samples_table."`");

    $wpdb->query("DROP TABLE IF EXISTS `".$surveys_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$questions_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$sections_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$survey_categories_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$question_categories_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$answers_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$submissions_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$submissions_questions_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$settings_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$popup_surveys_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$orders_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$requests_table."`");

    delete_option( "ays_survey_db_version");
    delete_option( "ays_survey_maker_upgrade_plugin");
    unregister_post_type( 'ays-survey-maker' );
}
