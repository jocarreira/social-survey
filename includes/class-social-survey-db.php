<?php
global $ays_survey_db_version;
$ays_survey_db_version = '1.0.0';

/**
 * Fired during plugin activation
 *
 * @link       https://jocartec.com/
 * @since      1.0.0
 *
 * @package    Social_Survey
 * @subpackage Social_Survey/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Social_Survey
 * @subpackage Social_Survey/includes
 * @author     Social Survey team <info@ays-pro.com>
 */
class Social_Survey_Db {

    private static function create_table($wpdb, $sql, $table_name) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
        WHERE table_schema = '".DB_NAME."' AND table_name = '".$table_name."' ";
        $results = $wpdb->get_results($sql_schema);

        if(empty($results)){
            $wpdb->query( $sql );
        }else{
            dbDelta( $sql );
        }
    }

}