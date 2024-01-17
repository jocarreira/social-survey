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
class Social_Survey_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    private static function activate() {
        global $wpdb;
        global $ays_survey_db_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-survey-db.php';

        /*
        drop table wp_social_customers;
        drop table wp_social_samples;
        drop table wp_social_surveys;
        drop table wp_social_submissions;
        */

        $installed_ver = get_option( "ays_survey_db_version" );
        
        $interviewers_table             = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'interviewers';
        $customers_table                = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'customers';
        $samples_table                  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'samples';
        $sample_records_table           = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sample_records';
        $sample_quotas_table            = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sample_quotas';
        $queues_table                   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'queues';
        
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

        //if($installed_ver != $ays_survey_db_version)  {
        if (true)  {
            // $sql_drop = "DROP TABLE IF EXISTS `".$customers_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$interviewer_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$surveys_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$sections_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$questions_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$survey_categories_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$question_categories_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$answers_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$submissions_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$submissions_questions_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$settings_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$popup_surveys_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$orders_table."`;";
            // $wpdb->query($sql_drop);
            // $sql_drop = "DROP TABLE IF EXISTS `".$requests_table."`;";
            // $wpdb->query($sql_drop);

            $sql = "CREATE TABLE `".$customers_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
                `ether_address` VARCHAR(100) NOT NULL DEFAULT '',
                `title` VARCHAR(100) NOT NULL DEFAULT '',
                `trade_name` VARCHAR(100) NOT NULL DEFAULT '',
                `business_name` VARCHAR(100) NOT NULL DEFAULT '',                
                `type_person` CHAR(1) NOT NULL DEFAULT 'J',
                `cnpj_cpf` VARCHAR(20) NOT NULL DEFAULT '',
                `email` VARCHAR(100) NOT NULL DEFAULT '',
                `address` VARCHAR(256) NOT NULL DEFAULT '',
                `zip_code` VARCHAR(10) NOT NULL DEFAULT '',
                `city` VARCHAR(100) NOT NULL DEFAULT '',
                `state_acronym` VARCHAR(10) NOT NULL DEFAULT '',
                `country` VARCHAR(3) NOT NULL DEFAULT 'BR',
                `logotype` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(100) NOT NULL DEFAULT 'published',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)                
            )$charset_collate;";
            //CONSTRAINT `customer_user_fk` FOREIGN KEY(`user_id`) REFERENCES `wp_users`(`ID`)
            //CONSTRAINT `customer_check_type_person` CHECK (type_person IN ('F', 'J'))

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
            WHERE table_schema = '".DB_NAME."' AND table_name = '".$customers_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $customers_table);

            $sql = "CREATE TABLE `".$surveys_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `author_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
                `customer_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `ether_address` VARCHAR(50) NOT NULL DEFAULT '',
                `title` VARCHAR(100) NOT NULL DEFAULT '',
                `description` VARCHAR(256) NOT NULL DEFAULT '',
                `category_ids` TEXT NOT NULL DEFAULT '',
                `question_ids` TEXT NOT NULL DEFAULT '',
                `section_ids` TEXT NOT NULL DEFAULT '',
                `sections_count` INT(11) NOT NULL DEFAULT '0',
                `questions_count` INT(11) NOT NULL DEFAULT '0',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `image` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `ordering` INT(16) NOT NULL,
                `post_id` INT(16) UNSIGNED DEFAULT NULL,
                `custom_post_id` INT(16) UNSIGNED DEFAULT NULL,
                `conditions` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)                
            )$charset_collate;";
            //CONSTRAINT `survey_customer_fk` FOREIGN KEY(`customer_id`) REFERENCES `".$customers_table."`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$surveys_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $surveys_table);

            $sql = "CREATE TABLE `".$interviewers_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
                `title` VARCHAR(100) NOT NULL DEFAULT '',
                `ether_address` VARCHAR(50) NOT NULL DEFAULT '',
                `fname` VARCHAR(50) NOT NULL DEFAULT '',
                `lname` VARCHAR(100) NOT NULL DEFAULT '',
                `type_person` CHAR(1) NOT NULL DEFAULT 'F',
                `cnpj_cpf` VARCHAR(20) NOT NULL DEFAULT '',
                `email` VARCHAR(100) NOT NULL DEFAULT '',
                `phone1` VARCHAR(20) NOT NULL DEFAULT '',
                `phone2` VARCHAR(20) NOT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `interviewer_user_fk` FOREIGN KEY(`user_id`) REFERENCES `wp_users`(`ID`)
            //CONSTRAINT `interviewer_check_type_person` CHECK (type_person IN ('F', 'J'))

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
            WHERE table_schema = '".DB_NAME."' AND table_name = '".$interviewers_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $interviewers_table);

            $sql = "CREATE TABLE `".$samples_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `survey_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `title` VARCHAR(100) NOT NULL DEFAULT '',
                `description` VARCHAR(250) NOT NULL DEFAULT '',
                `filepath` VARCHAR(1000) NOT NULL DEFAULT '',
                `fileext` VARCHAR(10) NOT NULL DEFAULT '.csv',
                `status` CHAR(1) NOT NULL DEFAULT 'A',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `sample_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$surveys_table."`(`id`)
            //CONSTRAINT `sample_check_status` CHECK (status IN ('I', 'P', 'E', 'S'))                

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$samples_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $samples_table);

            $sql = "CREATE TABLE `".$sample_records_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `sample_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `fname` VARCHAR(100) NOT NULL DEFAULT '',
                `lname` VARCHAR(100) NOT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `phone1` VARCHAR(20) NOT NULL DEFAULT '',
                `phone2` VARCHAR(20) NOT NULL DEFAULT '',
                `email` VARCHAR(100) NOT NULL DEFAULT '',
                `status` CHAR(1) NOT NULL DEFAULT 'A',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `sample_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$surveys_table."`(`id`)
            //CONSTRAINT `sample_check_status` CHECK (status IN ('I', 'P', 'E', 'S'))                

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$samples_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $samples_table);

            $sql = "CREATE TABLE `".$sample_quotas_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `sample_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `qlabel` VARCHAR(100) NOT NULL DEFAULT '',
                `qqtde` INT(8) NOT NULL DEFAULT '0',
                `qperc_of_total` DECIMAL(5, 4) NOT NULL DEFAULT '0.0',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `sample_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$surveys_table."`(`id`)
            //CONSTRAINT `sample_check_status` CHECK (status IN ('I', 'P', 'E', 'S'))                

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$samples_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $samples_table);


            $sql = "CREATE TABLE `".$queues_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `queue_name` VARCHAR(50) NOT NULL DEFAULT '',
                `qtd_of_numbers` INT(8) NOT NULL DEFAULT '0',
                `number_of_attempts` INT(8) NOT NULL DEFAULT '0',
                `scheduled_to` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `status` CHAR(1) NOT NULL DEFAULT 'I',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `queue_check_status` CHECK (status IN ('A', 'I'))
            
            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$samples_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $samples_table);

            $sql = "CREATE TABLE `".$sections_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `survey_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `title` VARCHAR(256) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
                `ordering` INT(11) NOT NULL DEFAULT '1',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)                
            )$charset_collate;";
            //CONSTRAINT `sections_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$surveys_table."`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$sections_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $sections_table);

            $sql = "CREATE TABLE `".$questions_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `author_id` INT(20) UNSIGNED NOT NULL DEFAULT '0',
                `section_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `category_ids` TEXT NOT NULL DEFAULT '',
                `question` TEXT NOT NULL DEFAULT '',
                `question_description` TEXT NOT NULL DEFAULT '',
                `type` VARCHAR(256) NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `user_variant` TEXT NULL DEFAULT '',
                `user_explanation` TEXT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `ordering` INT(11) NOT NULL DEFAULT '1',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)                
            )$charset_collate;";
            //CONSTRAINT `question_section_fk` FOREIGN KEY(`section_id`) REFERENCES `".$sections_table."`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$questions_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $questions_table);

            $sql = "CREATE TABLE `".$survey_categories_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(256) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$survey_categories_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $survey_categories_table);
 
            $sql = "CREATE TABLE `".$question_categories_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(256) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$question_categories_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $question_categories_table);

            $sql = "CREATE TABLE `".$answers_table."` (
                `id` INT(150) UNSIGNED NOT NULL AUTO_INCREMENT,
                `question_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `answer` TEXT NOT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `ordering` INT(11) NOT NULL DEFAULT '1',
                `placeholder` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)                
            )$charset_collate;";
            //CONSTRAINT `answer_question_fk` FOREIGN KEY(`question_id`) REFERENCES `".$questions_table."`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$answers_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $answers_table);

            $sql = "CREATE TABLE `".$submissions_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `survey_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `interviewer_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `sample_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `questions_ids` TEXT NOT NULL DEFAULT '',
                `user_id` BIGINT(16) UNSIGNED NOT NULL DEFAULT '0',
                `user_ip` VARCHAR(256) NOT NULL DEFAULT '',
                `user_name` TEXT NOT NULL DEFAULT '',
                `user_email` TEXT NOT NULL DEFAULT '',
                `start_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `end_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `submission_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `country` VARCHAR(256) NOT NULL DEFAULT '',
                `city` VARCHAR(256) NOT NULL DEFAULT '',
                `duration` VARCHAR(256) NOT NULL DEFAULT '0',
                `questions_count` VARCHAR(256) NOT NULL DEFAULT '0',
                `unique_code` VARCHAR(256) NOT NULL DEFAULT '',
                `read` tinyint(3) NOT NULL DEFAULT 0,
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `options` TEXT NOT NULL DEFAULT '',
                `password` VARCHAR(256) NOT NULL DEFAULT '',
                `changed` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `post_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `submissions_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$surveys_table."`(`id`),
            //CONSTRAINT `submissions_interviewer_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$interviewers_table."`(`id`),
            //CONSTRAINT `submissions_sample_fk` FOREIGN KEY(`survey_id`) REFERENCES `".$samples_table."`(`id`)


            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$submissions_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $submissions_table);

            $sql = "CREATE TABLE `".$submissions_questions_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `submission_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `question_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `section_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `survey_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `user_id` INT(16) NOT NULL DEFAULT '0',
                `answer_id` INT(16) NOT NULL DEFAULT '0',
                `user_answer` TEXT NOT NULL DEFAULT '',
                `user_variant` TEXT NOT NULL DEFAULT '',
                `user_explanation` TEXT NOT NULL DEFAULT '',
                `type` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `submission_question_submission_fk` FOREIGN KEY(`submission_id`) REFERENCES `".$submissions_table."`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$submissions_questions_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $submissions_questions_table);

            $sql = "CREATE TABLE `".$settings_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `meta_key` TEXT NOT NULL DEFAULT '',
                `meta_value` TEXT NOT NULL DEFAULT '',
                `note` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$settings_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $settings_table);

            $sql = "CREATE TABLE `".$popup_surveys_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `survey_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `title` TEXT NOT NULL,
                `show_all` VARCHAR(20) NOT NULL,
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `author_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";
            //CONSTRAINT `popup_surveys_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `wp_socialsurv_surveys`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$popup_surveys_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $popup_surveys_table);

            $sql="CREATE TABLE `".$orders_table."` (
                `id` INT(16) NOT NULL AUTO_INCREMENT,
                `order_id` TEXT NOT NULL,
                `survey_id` INT(16) NOT NULL,
                `user_id` INT(16) NOT NULL,
                `order_full_name` TEXT NULL DEFAULT NULL,
                `order_email` TEXT NULL DEFAULT NULL,
                `amount` TEXT NOT NULL,
                `payment_date` DATETIME NOT NULL,
                `type` TEXT DEFAULT NULL,
                `status` TEXT DEFAULT NULL,
                `options` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`)                
            )$charset_collate;";
            //CONSTRAINT `orders_survey_fk` FOREIGN KEY(`survey_id`) REFERENCES `wp_socialsurv_surveys`(`id`)

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$orders_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $orders_table);

            $sql = "CREATE TABLE `".$requests_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `survey_id` INT(16) UNSIGNED DEFAULT NULL,
                `category_id` INT(16) UNSIGNED DEFAULT NULL,
                `user_id` INT(16) UNSIGNED DEFAULT NULL,
                `user_ip` TEXT NULL DEFAULT NULL,
                `survey_title` TEXT NULL DEFAULT NULL,
                `request_data` LONGTEXT NULL DEFAULT NULL,
                `request_date` DATETIME DEFAULT NULL,
                `status` TEXT NULL DEFAULT NULL,
                `approved` TEXT NULL DEFAULT NULL,
                `unread`  INT(1) DEFAULT 1,
                `options` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$requests_table."' ";
            $results = $wpdb->get_results($sql_schema);
            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            //Social_Survey_Db::create_table($wpdb, $sql, $requests_table);

            update_option( 'ays_survey_db_version', $ays_survey_db_version );
            
            $survey_categories = $wpdb->get_var( "SELECT COUNT(*) FROM " . $survey_categories_table );            
            if( intval($survey_categories) == 0 ){
                $wpdb->query("TRUNCATE TABLE `{$survey_categories_table}`");
                $wpdb->insert( $survey_categories_table, array(
                    'title' => 'Sem Categoria',
                    'description' => '',
                    'status' => 'published',
                    'date_created' => current_time( 'mysql' ),
                    'date_modified' => current_time( 'mysql' ),
                ) );
            }

            $question_categories = $wpdb->get_var( "SELECT COUNT(*) FROM " . $question_categories_table );            
            if( intval($question_categories) == 0 ){
                $wpdb->query("TRUNCATE TABLE `{$question_categories_table}`");
                $wpdb->insert( $question_categories_table, array(
                    'title' => 'Sem Categoria',
                    'description' => '',
                    'status' => 'published',
                    'date_created' => current_time( 'mysql' ),
                    'date_modified' => current_time( 'mysql' ),
                ) );
            }

            // $sql_schema = "SELECT `AUTO_INCREMENT`
            //     FROM  INFORMATION_SCHEMA.TABLES
            //     WHERE TABLE_SCHEMA = '".DB_NAME."'
            //     AND   TABLE_NAME   = '".$popup_surveys_table."';";

            // $auto_incement = $wpdb->get_var( $sql_schema );
            // $auto_incement = absint( $auto_incement );

            // if( $auto_incement <= 1 ){
                self::create_default_survey();
            // }
          
        }
        
        $metas = array(
            "user_roles",
            "mailchimp",
            "monitor",
            "slack",
            "active_camp",
            "zapier",
            'google',
            "sendgrid",
            "mad_mimi",
            "get_response",
            "convertKit",
            "sendinblue",
            "paypal",
            "stripe",
            "mailerLite",
            "recaptcha",
            "buttons_texts",
            "survey_default_options",
            "aweber",
            "klaviyo",
            "options",
            "ai",
            "front_request_options"
        );
        
        foreach($metas as $meta_key){
            $meta_val = "";
            if($meta_key == "user_roles"){
                $meta_val = json_encode(array('administrator'));
            }
            $sql = "SELECT COUNT(*) FROM `".$settings_table."` WHERE `meta_key` = '". esc_sql( $meta_key ) ."'";
            $result = $wpdb->get_var($sql);
            if(intval($result) == 0){
                $result = $wpdb->insert(
                    $settings_table,
                    array(
                        'meta_key'    => $meta_key,
                        'meta_value'  => $meta_val,
                        'note'        => "",
                        'options'     => ""
                    ),
                    array( '%s', '%s', '%s', '%s' )
                );
            }
        }
        
    }

    public static function ays_survey_update_db_check() {
        global $ays_survey_db_version;
        //if ( get_site_option( 'ays_survey_db_version' ) != $ays_survey_db_version ) {
            self::activate();
        //}
    }

    public static function create_default_survey(){
        global $wpdb;
        global $ays_survey_db_version;
        
        $customers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'customers';
        $samples_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'samples';
        $interviewers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'interviewers';
        $queues_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'queues';
        $sample_records_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sample_records';
        $sample_quotas_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sample_quotas';

        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
        $questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'questions';
        $sections_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sections';
        $answers_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'answers';

        $survey_questions = $wpdb->get_var("SELECT COUNT(*) FROM " . $questions_table);
        $surveys_count    = $wpdb->get_var("SELECT COUNT(*) FROM " . $surveys_table);
        $sections_count   = $wpdb->get_var("SELECT COUNT(*) FROM " . $sections_table);
        if($survey_questions == 0 && $surveys_count == 0 && $sections_count == 0){
            $user_id = get_current_user_id();
            $default_options = array(
                // Styles Tab
                'survey_theme' => 'classic_light',
                'survey_color' => 'rgb(255, 87, 34)', // #673ab7
                'survey_background_color' => '#fff',
                'survey_text_color' => '#333',
                'survey_buttons_text_color' => '#333',
                'survey_width' => '',
                'survey_width_by_percentage_px' => 'pixels',
                'survey_mobile_max_width' => '',
                'survey_custom_class' => '',
                'survey_custom_css' => '',
                'survey_logo' => '',
        
                'survey_question_font_size' => 16,
                'survey_question_image_width' => '',
                'survey_question_image_height' => '',
                'survey_question_image_sizing' => 'cover',
                'survey_question_padding' => 24,
                'survey_question_caption_text_color' => '#333',
                'survey_question_caption_text_alignment' => 'center',
                'survey_question_caption_font_size' => 16,
                'survey_question_caption_font_size_on_mobile' => 16,
                'survey_question_caption_text_transform' => 'none',
        
                'survey_answer_font_size' => 15,
                'survey_answer_font_size_on_mobile' => 15,
                'survey_answers_view' => 'grid',
                'survey_answers_view_alignment' => 'space_around',
                'survey_answers_object_fit' => 'cover',
                'survey_answers_padding' => 8,
                'survey_answers_gap' => 0,
        
                'survey_buttons_size' => 'medium',
                'survey_buttons_font_size' => 14,
                'survey_buttons_left_right_padding' => 24,
                'survey_buttons_top_bottom_padding' => 0,
                'survey_buttons_border_radius' => 4,
                'survey_buttons_alignment' => 'left',
                'survey_buttons_top_distance' => 10,

                // Settings Tab
                'survey_show_title' => 'off',
                'survey_show_section_header' => 'on',
                'survey_enable_randomize_answers' => 'off',
                'survey_enable_randomize_questions' => 'off',
                'survey_enable_clear_answer' => 'on',
                'survey_enable_previous_button' => 'on',
                'survey_enable_survey_start_loader' => 'on',
                'survey_before_start_loader' => 'default',
                'survey_allow_html_in_answers' => 'off',
                'survey_enable_leave_page' => 'on',
                'survey_enable_info_autofill' => 'off',
                'survey_enable_schedule' => 'off',
                'survey_schedule_active' => current_time( 'mysql' ),
                'survey_schedule_deactive' => current_time( 'mysql' ),
                'survey_schedule_show_timer' => 'off',
                'survey_show_timer_type' => 'countdown',
                'survey_schedule_pre_start_message' =>  __("A pesquisa estará disponível em breve!", SURVEY_MAKER_NAME),
                'survey_schedule_expiration_message' => __("Esta pesquisa expirou!", SURVEY_MAKER_NAME),
                'survey_dont_show_survey_container' => 'off',
                'survey_edit_previous_submission' => 'off',
                'survey_auto_numbering' => 'none',

                // Result Settings Tab
                'survey_redirect_after_submit' => 'off',
                'survey_submit_redirect_url' => '',
                'survey_submit_redirect_delay' => '',
                'survey_submit_redirect_new_tab' => 'off',
                'survey_enable_exit_button' => 'off',
                'survey_exit_redirect_url' => '',
                'survey_enable_restart_button' => 'on',
                'survey_show_questions_as_html' => 'on',
                'survey_final_result_text' => '',
                'survey_loader' => 'ripple',

                // Limitation Tab
                'survey_limit_users' => 'off',
                'survey_limit_users_by' => 'ip',
                'survey_max_pass_count' => 1,
                'survey_limitation_message' => '',
                'survey_redirect_url' => '',
                'survey_redirect_delay' => 0,
                'survey_enable_logged_users' => 'off',
                'survey_logged_in_message' => '',
                'survey_show_login_form' => 'off',
                'survey_enable_takers_count' => 'off',
                'survey_takers_count' => 1,

                // E-Mail Tab
                'survey_enable_mail_user' => 'off',
                'survey_mail_message' => '',
                'survey_enable_mail_admin' => 'off',
                'survey_send_mail_to_site_admin' => 'on',
                'survey_additional_emails' => '',
                'survey_mail_message_admin' => '',
                'survey_email_configuration_from_email' => '',
                'survey_email_configuration_from_name' => '',
                'survey_email_configuration_from_subject' => '',
                'survey_email_configuration_replyto_email' => '',
                'survey_email_configuration_replyto_name' => '',            
            );
            // Queues
            $queue_default = array(
                array(
                    'queue_name'         => 'initial',
                    'number_of_attempts' => 0,
                    'scheduled_to'       => current_time( 'mysql' ),
                    'status'             => 'A',
                    'date_created'       => current_time( 'mysql' ),
                    'date_modified'      => current_time( 'mysql' ),
                ),
                array(
                    'queue_name'         => 'scheduled',
                    'number_of_attempts' => 0,
                    'scheduled_to'       => current_time( 'mysql' ),
                    'status'             => 'A',
                    'date_created'       => current_time( 'mysql' ),
                    'date_modified'      => current_time( 'mysql' ),
                ),
                array(
                    'queue_name'         => 'discarded',
                    'number_of_attempts' => 0,
                    'scheduled_to'       => current_time( 'mysql' ),
                    'status'             => 'A',
                    'date_created'       => current_time( 'mysql' ),
                    'date_modified'      => current_time( 'mysql' ),
                ),
                array(
                    'queue_name'         => 'acting',
                    'number_of_attempts' => 0,
                    'scheduled_to'       => current_time( 'mysql' ),
                    'status'             => 'A',
                    'date_created'       => current_time( 'mysql' ),
                    'date_modified'      => current_time( 'mysql' ),
                ),
                array(
                    'queue_name'         => 'succeeded',
                    'number_of_attempts' => 0,
                    'scheduled_to'       => current_time( 'mysql' ),
                    'status'             => 'A',
                    'date_created'       => current_time( 'mysql' ),
                    'date_modified'      => current_time( 'mysql' ),
                )
            );
            // Customers
            $customer_default = array(
                array(
                    'user_id'         => $user_id,
                    'ether_address'   => '0x5B38Da6a701c568545dCfcB03FcB875f56beddC4',
                    'title'           => 'JocarTec',
                    'trade_name'      => 'JEFERSON CARREIRA TECNOLOGIA DA INFORMACAO',
                    'business_name'   => 'JEFERSON DE OLIVEIRA CARREIRA CONSULTORIA EM TECNOLOGIA DA INFORMACAO LTDA',
                    'type_person'     => 'J',
                    'cnpj_cpf'        => '36.692.201/0001-44',
                    'email'           => 'jocarreira@terra.com.br',
                    'address'         => 'Rua João Navarro Botelho, 109, Santana',
                    'zip_code'        => '02032008',
                    'city'            => 'São Paulo',
                    'state_acronym'   => 'SP',
                    'country'         => 'BR',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                )
            );    
/*
            $sql = "CREATE TABLE `".$samples_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `survey_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `name` VARCHAR(100) NOT NULL DEFAULT '',
                `description` VARCHAR(250) NOT NULL DEFAULT '',
                `filepath` TEXT NOT NULL DEFAULT '',
                `fileext` VARCHAR(10) NOT NULL DEFAULT '.csv',
                `status` CHAR(1) NOT NULL DEFAULT 'A',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
*/
            //Samples
            $sample_default  = array(
                array(
                    'survey_id'           => 1,
                    'title'               => 'Lista1',
                    'description'         => 'Lista1 para testes',
                    'filepath'            => '',
                    'fileext'             => '.csv',
                    'status'              => 'A',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                )
            ); 
/*
            $sql = "CREATE TABLE `".$sample_records_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `sample_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `fname` VARCHAR(100) NOT NULL DEFAULT '',
                `lname` VARCHAR(100) NOT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `phone1` VARCHAR(20) NOT NULL DEFAULT '',
                `phone2` VARCHAR(20) NOT NULL DEFAULT '',
                `email` VARCHAR(100) NOT NULL DEFAULT '',
                `status` CHAR(1) NOT NULL DEFAULT 'A',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
*/
            //$sample_records_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sample_records';
            // sample_records_table
            $sample_records_default  = array(
                array(
                    'sample_id'       => 1,
                    'fname'           => 'Jeferson',
                    'lname'           => 'Carreira 1',
                    'image'           => '',
                    'phone1'          => '+5511989766135',
                    'phone2'          => '+5511989766135',
                    'email'           => 'jocarreira@terra.com.br',
                    'status'          => 'A',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'fname'           => 'Jeferson',
                    'lname'           => 'Carreira 2',
                    'image'           => '',
                    'phone1'          => '+5511989766135',
                    'phone2'          => '+5511989766135',
                    'email'           => 'jocarreira@terra.com.br',
                    'status'          => 'A',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'fname'           => 'Jeferson',
                    'lname'           => 'Carreira 3',
                    'image'           => '',
                    'phone1'          => '+5511989766135',
                    'phone2'          => '+5511989766135',
                    'email'           => 'jocarreira@terra.com.br',
                    'status'          => 'A',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                )
            ); 
/*
            $sql = "CREATE TABLE `".$sample_quotas_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `sample_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `qlabel` VARCHAR(100) NOT NULL DEFAULT '',
                `qqtde` INT(8) NOT NULL DEFAULT '0',
                `qperc_of_total` DECIMAL(5, 4) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                PRIMARY KEY (`id`)
            )$charset_collate;";
*/
            //$sample_quotas_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sample_quotas';
            // sample_records_table
            $sample_quotas_default  = array(
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Homem',
                    'qqtde'           => 100,
                    'qperc_of_total'  => 50.0,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Mulher',
                    'qqtde'           => 100,
                    'qperc_of_total'  => 50.0,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Homem entre 10 e 20 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Homem entre 21 e 30 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Homem entre 31 e 40 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Homem acima de 41 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Mulher entre 10 e 20 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Mulher entre 21 e 30 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Mulher entre 31 e 40 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'sample_id'       => 1,
                    'qlabel'          => 'Mulher acima de 41 anos de idade',
                    'qqtde'           => 25,
                    'qperc_of_total'  => 12.5,
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
            );     

            //interviewers
            $interviewer_default = array(
                array(
                    'user_id'         => $user_id,
                    'ether_address'   => '0x5B38Da6a701c568545dCfcB03FcB875f56beddC4',
                    'title'           => 'Entrevistador Jeferson Carreira 1',
                    'fname'           => 'Jeferson',
                    'lname'           => 'Carreira (interviewer1)',
                    'type_person'     => 'F',
                    'cnpj_cpf'        => '049.507.188/94',
                    'email'           => 'jocarreira@terra.com.br',
                    'phone1'          => '+5511989756135',
                    'phone2'          => '+5511989756135',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                ),
                array(
                    'user_id'         => $user_id,
                    'ether_address'   => '0x5B38Da6a701c568545dCfcB03FcB875f56beddC4',
                    'title'           => 'Entrevistador Jeferson Carreira 2',
                    'fname'           => 'Jeferson',
                    'lname'           => 'Carreira (interviewer2)',
                    'type_person'     => 'F',
                    'cnpj_cpf'        => '049.507.188/94',
                    'email'           => 'jocarreira@terra.com.br',
                    'phone1'          => '+5511989756135',
                    'phone2'          => '+5511989756135',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                )                
            ); 

            // Survey
            $survey_default = array(
                array(
                    'author_id'       => $user_id,
                    'customer_id'     => 1,
                    'ether_address'   => '0x5B38Da6a701c568545dCfcB03FcB875f56beddC4',
                    'title'           => 'Modelo de pesquisa de satisfação do cliente',
                    'description'     => '',
                    'category_ids'    => '1',
                    'question_ids'    => '1,2,3,4,5,6,7',
                    'section_ids'     => '1,2,3,4',
                    'sections_count'  => '4',
                    'questions_count' => '7',
                    'date_created'    => current_time( 'mysql' ),
                    'date_modified'   => current_time( 'mysql' ),
                    'image'    => '',
                    'status'   => 'published',
                    'ordering' => '1',
                    'post_id'  => '0',
                    'options'  => json_encode($default_options),
                )
            );
            // Sections 
            $sections_default = array(
                array(
                    array(
                        'survey_id'     => 1,
                        'title'         => "Modelo de pesquisa de satisfação do cliente",
                        'description'   => "Por favor, ajude-nos a melhorar nossos produtos/serviços preenchendo este questionário.",
                        'ordering'      => 1,
                        'options'       => json_encode( array("collapses" => "expanded") ),
                    ),
                    array(
                        'survey_id'     => 1,
                        'title'         => "Parte 2/4: Avaliação de Serviço/Produto",
                        'description'   => "",
                        'ordering'      => 2,
                        'options'       => json_encode( array("collapses" => "expanded") ),
                    ),
                    array(
                        'survey_id'     => 1,
                        'title'         => "Parte 3/4: Atendimento ao Cliente",
                        'description'   => "",
                        'ordering'      => 3,
                        'options'       => json_encode( array("collapses" => "expanded") ),
                    ),
                    array(
                        'survey_id'     => 1,
                        'title'         => "Parte 4/4: Feedback Adicional",
                        'description'   => "",
                        'ordering'      => 4,
                        'options'       => json_encode( array("collapses" => "expanded") ),
                    ),
                )
            );
            // Questions options 
            $question_options = array(
                'required'  => "off",
                'collapsed' => "expanded",
                'enable_max_selection_count' => "off",
                'max_selection_count' => "",
                'min_selection_count' => "",
                'with_editor' => "off",
            );
            $question_options_req_on = array(
                'required'  => "on",
                'collapsed' => "expanded",
                'enable_max_selection_count' => "off",
                'max_selection_count' => "",
                'min_selection_count' => "",
                'with_editor' => "off",
            );
            // Questions
            $questions_default = array(
                array(
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 1,
                        'category_ids'      => "1",
                        'question'          => "Você recomendaria esta empresa a um amigo ou colega?",
                        'type'              => "yesorno",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "off",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 1,
                        'options'           => json_encode($question_options),
                    ),
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 1,
                        'category_ids'      => "1",
                        'question'          => "No geral, quão satisfeito ou insatisfeito você está com nossa empresa?",
                        'type'              => "radio",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "off",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 2,
                        'options'           => json_encode($question_options),
                    ),
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 2,
                        'category_ids'      => "1",
                        'question'          => "Qual das seguintes palavras você usaria para descrever nossos produtos/serviços? Selecione tudo que se aplica.",
                        'type'              => "checkbox",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "on",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 1,
                        'options'           => json_encode($question_options),
                    ),
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 2,
                        'category_ids'      => "1",
                        'question'          => "Como você avaliaria a qualidade do site? (de 1 a 10)",
                        'type'              => "number",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "off",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 2,
                        'options'           => json_encode($question_options_req_on),
                    ),
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 3,
                        'category_ids'      => "1",
                        'question'          => "Quão receptivos temos sido às suas perguntas ou preocupações sobre nossos produtos/serviços?",
                        'type'              => "select",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "off",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 1,
                        'options'           => json_encode($question_options),
                    ),
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 3,
                        'category_ids'      => "1",
                        'question'          => "Em qual endereço de e-mail você gostaria de ser contatado?",
                        'type'              => "email",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "off",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 1,
                        'options'           => json_encode($question_options),
                    ),
                    array(
                        'author_id'         => $user_id,
                        'section_id'        => 4,
                        'category_ids'      => "1",
                        'question'          => "Você tem algum outro comentário, pergunta ou preocupação?",
                        'type'              => "text",
                        'status'            => "published",
                        'trash_status'      => "",
                        'date_created'      => current_time( 'mysql' ),
                        'date_modified'     => current_time( 'mysql' ),
                        'user_variant'      => "off",
                        'user_explanation'  => "",
                        'image'             => "",
                        'ordering'          => 2,
                        'options'           => json_encode($question_options),
                    ),
                )
            );

            //Answers
            $answers_default = array(
                array(
                    // question 1
                    array(
                        'question_id'       => 1,
                        'answer'            => "Sim",
                        'image'             => "",
                        'ordering'          => 1,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 1,
                        'answer'            => "Não",
                        'image'             => "",
                        'ordering'          => 2,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 1,
                        'answer'            => "Talvez",
                        'image'             => "",
                        'ordering'          => 3,
                        'placeholder'       => "",
                    ),
                    // question 2
                    array(
                        'question_id'       => 2,
                        'answer'            => "Muito satisfeiro",
                        'image'             => "",
                        'ordering'          => 1,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 2,
                        'answer'            => "Um pouco satisfeito",
                        'image'             => "",
                        'ordering'          => 2,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 2,
                        'answer'            => "Um pouco insatisfeito",
                        'image'             => "",
                        'ordering'          => 3,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 2,
                        'answer'            => "Muito insatisfeito",
                        'image'             => "",
                        'ordering'          => 4,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 2,
                        'answer'            => "Nem satisfeito nem insatisfeito",
                        'image'             => "",
                        'ordering'          => 5,
                        'placeholder'       => "",
                    ),
                    // question 3
                    array(
                        'question_id'       => 3,
                        'answer'            => "Confiável",
                        'image'             => "",
                        'ordering'          => 1,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Alta qualidade",
                        'image'             => "",
                        'ordering'          => 2,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Útil",
                        'image'             => "",
                        'ordering'          => 3,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Bom valor para o dinheiro",
                        'image'             => "",
                        'ordering'          => 4,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Exclusivo(a)",
                        'image'             => "",
                        'ordering'          => 5,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Caro demais",
                        'image'             => "",
                        'ordering'          => 6,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Ineficaz",
                        'image'             => "",
                        'ordering'          => 7,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Má qualidade",
                        'image'             => "",
                        'ordering'          => 8,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Não confiável",
                        'image'             => "",
                        'ordering'          => 9,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 3,
                        'answer'            => "Impraticável",
                        'image'             => "",
                        'ordering'          => 10,
                        'placeholder'       => "",
                    ),
                    // question 4 is a short type
                    // question 5
                    array(
                        'question_id'       => 5,
                        'answer'            => "Extremamente responsivo",
                        'image'             => "",
                        'ordering'          => 1,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 5,
                        'answer'            => "Muito responsivo",
                        'image'             => "",
                        'ordering'          => 2,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 5,
                        'answer'            => "Um pouco responsivo",
                        'image'             => "",
                        'ordering'          => 3,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 5,
                        'answer'            => "Não tão responsivo",
                        'image'             => "",
                        'ordering'          => 4,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 5,
                        'answer'            => "Nem um pouco responsivo",
                        'image'             => "",
                        'ordering'          => 5,
                        'placeholder'       => "",
                    ),
                    array(
                        'question_id'       => 5,
                        'answer'            => "Não aplicável",
                        'image'             => "",
                        'ordering'          => 6,
                        'placeholder'       => "",
                    ),
                    // question 6 is an email type
                    // question 7 is a paragraph type
                )
            );

            foreach($customer_default as $key => $customer){
                $wpdb->insert($customers_table, $customer);
            }
            foreach($queue_default as $key => $queue){
                $wpdb->insert($queues_table, $queue);
            }
            foreach($interviewer_default as $key => $interviewer){
                $wpdb->insert($interviewers_table, $interviewer);
            }
            foreach($sample_default as $key => $sample){
                $wpdb->insert($samples_table, $sample);
            }
            foreach($sample_records_default as $key => $sample_records){
                $wpdb->insert($sample_records_table, $sample_records);
            }            
            foreach($sample_quotas_default as $key => $sample_quotas){
                $wpdb->insert($sample_quotas_table, $sample_quotas);
            }
            foreach($survey_default as $key => $survey){
                $wpdb->insert($surveys_table, $survey);
                foreach($sections_default[$key] as $section_key => $section){
                   $wpdb->insert($sections_table, $section);
                }
                foreach($questions_default[$key] as $section_key => $section){
                   $wpdb->insert($questions_table, $section);
                }
                foreach($answers_default[$key] as $answer_key => $answer){
                   $wpdb->insert($answers_table, $answer);
                }
                $inserted_id = $wpdb->insert_id;
                $post_type_args = array(
                    'survey_id' => $inserted_id,
                    'author_id' => $user_id,
                    'survey_title' => $survey['title'],
                );
                $custom_post_id = Survey_Maker_Custom_Post_Type::survey_add_custom_post($post_type_args);
            }
        }
    }
}
