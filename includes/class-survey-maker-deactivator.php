<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     Survey Maker team <info@ays-pro.com>
 */
class Social_Survey_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        global $wpdb;
        global $ays_survey_db_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        /*
        drop table wp_social_customers;
drop table wp_social_samples;
drop table wp_social_surveys;
drop table wp_social_submissions;
        */

        $installed_ver = get_option( "ays_survey_db_version" );
        
        $enterviewer_table              = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'enterviewer';
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
        $charset_collate = $wpdb->get_charset_collate();

        if($installed_ver != $ays_survey_db_version)  {
            $sql_drop = "DROP TABLE IF EXISTS `".$enterviewer_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$customers_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$surveys_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$sections_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$questions_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$survey_categories_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$question_categories_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$answers_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$submissions_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$submissions_questions_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$settings_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$popup_surveys_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$orders_table."`;";
            $wpdb->query($sql_drop);
            $sql_drop = "DROP TABLE IF EXISTS `".$requests_table."`;";
            $wpdb->query($sql_drop);
		}
	}

}
