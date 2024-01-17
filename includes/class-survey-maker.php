<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     Survey Maker team <info@ays-pro.com>
 */
class Survey_Maker {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Survey_Maker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SURVEY_MAKER_VERSION' ) ) {
			$this->version = SURVEY_MAKER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'survey-maker';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_integrations_hooks();
		$this->define_custom_post_type_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Survey_Maker_Loader. Orchestrates the hooks of the plugin.
	 * - Survey_Maker_i18n. Defines internationalization functionality.
	 * - Survey_Maker_Admin. Defines all hooks for the admin area.
	 * - Survey_Maker_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

        if ( ! class_exists( 'WP_List_Table' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }

		/**
		 * The class responsible for defining all functions for getting all survey data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-data.php';

		/**
		 * The class responsible for defining all functions for getting all custom post type functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-custom-post-type.php';


		/**
		 * The class responsible for defining all functions for generating content in PDF format
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-pdfapi.php';

		/**
		 * The class responsible for defining all functions for getting all survey integrations
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-integrations.php';

		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-survey-maker-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-survey-maker-public.php';

		/**
		 * The class responsible for extra shortcodes on the front page
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-extra-shortcode.php';


		/*
		 * The class is responsible for showing surveys in wordpress default WP_LIST_TABLE style
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-surveys-list-table.php';

		/*
		 * CustomersJeferson Carreira
		 * The class is responsible for showing surveys in wordpress default WP_LIST_TABLE style
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-customers-list-table.php';

		/*
         * The class is responsible for showing popup surveys in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-popup-survey-list-table.php';

        /*
         * The class is responsible for showing survey categories in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-survey-categories-list-table.php';

        /*
         * The class is responsible for showing survey submissions in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-submissions-list-table.php';
        
        /*
         * The class is responsible for showing each survey submissions in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-each-submission-list-table.php';
        
		/*
         * The class is responsible for showing front requests in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-front-requests-list-table.php';
        
        /*
         * The class is responsible for showing survey submissions in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/settings/survey-maker-settings-actions.php';

		/**
		 * The class responsible for defining all functions for generating General Settings shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-submissions-summary-shortcode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-most-popular-shortcode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-survey-links-by-category.php';

		/**
		 * The class responsible for defining all functions for survey front end request shortcode
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-front-request-shortcode.php';

		/**
		 * The class responsible for defining all functions for getting all survey submissions results shortecode
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-all-submissions-shortcode.php';
		
		/**
		 * The class responsible for defining all functions for getting all survey submissions results shortecode of user
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-user-history-shortcode.php';

		/**
		 * The class  is responsible for defining all functions for generating General Settings shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-question-summary-shortcode.php';

		/**
		 * The class  is responsible for defining all functions for generating recent surveys shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-recent-surveys-shortcode.php';

		/**
		 * The class  is responsible for defining all functions for generating survey categories shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-category-shortcode.php';

		/**
		 * The class  is responsible for defining all functions for generating user activity shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-user-activity-shortcode.php';

		/**
		 * The class  is responsible for defining all functions for generating survey activity shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-survey-activity-shortcode.php';



		/**
		 * The class  is responsible for showing payment orders in wordpress default WP_LIST_TABLE style
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-orders-list-table.php';


		$this->loader = new Survey_Maker_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Survey_Maker_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Survey_Maker_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Survey_Maker_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'disable_scripts', 100 );

        // Add menu item
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_customers_submenu', 84 );  // JEFERSON : CLIENTES
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_interviewers_submenu', 85 );  // JEFERSON : CLIENTES
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_surveys_submenu', 86 );		
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_survey_categories_submenu', 90 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_survey_front_requests_submenu', 95 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_popup_surveys_submenu', 100 );
        // $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_questions_categories_submenu', 100 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_submissions_submenu', 105 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_export_import_submenu', 110 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_orders_submenu', 115 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_general_settings_submenu', 120 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_dashboard_submenu', 125 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_affiliate_submenu', 130 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_featured_plugins_submenu', 135 );
		
        // Admin AJAX action
        $this->loader->add_action( 'wp_ajax_ays_survey_admin_ajax', $plugin_admin, 'ays_survey_admin_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_ays_survey_admin_ajax', $plugin_admin, 'ays_survey_admin_ajax' );

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
        $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// CodeMirror Editor
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'codemirror_enqueue_scripts');

        $this->loader->add_action( 'in_admin_footer', $plugin_admin, 'survey_maker_admin_footer', 1 );

        $this->loader->add_action( 'elementor/widgets/widgets_registered', $plugin_admin, 'survey_maker_el_widgets_registered' );

		// $this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_admin, 'sm_enqueue_elementor_styles' );

		// Add aditional links to the plugin
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'add_survey_row_meta' , 10 , 2 );
		$this->loader->add_action('wp_ajax_survey_maker_mark_requests_as_read', $plugin_admin, 'survey_maker_mark_requests_as_read');
        $this->loader->add_action('wp_ajax_nopriv_survey_maker_mark_requests_as_read', $plugin_admin, 'survey_maker_mark_requests_as_read');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Survey_Maker_Public( $this->get_plugin_name(), $this->get_version() );

		$plugin_public_submissions_summary 	= new Survey_Maker_Submissions_Summary( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_extra_shortcodes     = new Ays_Survey_Maker_Extra_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_submission 			= new Survey_Maker_All_Submissions( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_user_history 		= new Survey_Maker_History_Page( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_question_summary 	= new Survey_Maker_Question_Summary( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_frontend_request 	= new Survey_Maker_Frontend_Requests_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_most_popular_shortcodes = new Ays_Survey_Maker_Most_Popular_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_survey_links_by_category_shortcodes = new Ays_Survey_Maker_Links_By_Category_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_recent_surveys_page  = new Survey_Maker_Recent_Surveys( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_survey_category      = new Survey_Maker_Survey_Category( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_user_activity_per_day 	= new Survey_Maker_User_Activity_Per_Day( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_survey_activity_per_day 	= new Survey_Maker_Survey_Activity_Per_Day( $this->get_plugin_name(), $this->get_version() );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles_early' );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'ays_shortcodes_show_all');
		
        // Public AJAX action
        $this->loader->add_action( 'wp_ajax_ays_survey_ajax', $plugin_public, 'ays_survey_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_ays_survey_ajax', $plugin_public, 'ays_survey_ajax' );

		// Custom hook for getting finished survey results
		$this->loader->add_filter( 'ays_survey_get_submission_results', $plugin_public, 'ays_survey_get_submission_results_by_unique_code' );

	}

	/**
	 * Register all of the hooks related to the integrations functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_integrations_hooks() {

		$plugin_integrations = new Survey_Maker_Integrations( $this->get_plugin_name(), $this->get_version() );

		// Survey Maker Integrations / survey page
		$this->loader->add_action( 'ays_sm_survey_page_integrations', $plugin_integrations, 'ays_survey_page_integrations_content' );		
		
		// Survey Maker Integrations / settings page
		$this->loader->add_action( 'ays_sm_settings_page_integrations', $plugin_integrations, 'ays_settings_page_integrations_content' );

		// ===== MailChimp integration ====
			// MailChimp integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mailchimp_content', 1, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_mailchimp_save', 1, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_mailchimp_options', 1, 2 );

			// MailChimp integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mailchimp_content', 1, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_mailchimp_save', 1, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_mailchimp_options', 1, 2 );

			// MailChimp integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_mailchimp_options', 1, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_mailchimp_functional', 1, 3 );
		// ===== MailChimp integration ====

		// ===== Campaign Monitor integration =====
			// Campaign Monitor integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_camp_monitor_content', 20, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_camp_monitor_save', 20, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_camp_monitor_options', 20, 2 );

			// Campaign Monitor integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_campaign_monitor_content', 20, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_campaign_monitor_save', 20, 2 );
			
			// Campaign Monitor integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_campaign_monitor_options', 20, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_campaign_monitor_functional', 20, 3 );
		// ===== Campaign Monitor integration =====

		// ===== Zapier integration =====
			// Zapier integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_zapier_content', 60, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_zapier_save', 60, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_zapier_options', 60, 2 );

			// Zapier integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_zapier_content', 60, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_zapier_save', 60, 2 );

			// Zapier integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_zapier_options', 60, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_zapier_functional', 60, 3 );
		// ===== Zapier integration =====

		// ===== Active Campaign integration =====
			// Active Campaign integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_active_camp_content', 30, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_active_camp_save', 30, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_active_camp_options', 30, 2 );

			// Active Campaign integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_active_camp_content', 30, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_active_camp_save', 30, 2 );

			// Active Campaign integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_active_camp_options', 30, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_active_camp_functional', 30, 3 );
		// ===== Active Campaign integration =====
		
		// ===== Slack integration =====
			// Slack integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_slack_content', 40, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_slack_save', 40, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_slack_options', 40, 2 );

			// Slack integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_slack_content', 40, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_slack_save', 40, 2 );

			// Slack integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_slack_options', 40, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_slack_functional', 40, 3 );
		// ===== Slack integration =====

		// ===== Google integration =====
			// Google integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_google_sheet_content', 50, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_google_sheet_save', 50, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_google_sheet_options', 50, 2 );
			// Google integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_google_sheet_content', 50, 2 );
			// $this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_google_sheet_save', 50, 2 );
			// Google integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_google_sheet_options', 50, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_google_sheet_functional', 50, 3 );
		// ===== Google integration =====
		
		// ===== GamiPress integration =====
			// GamiPress integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_gamipress_content', 80, 2 );

			//GamiPress / admin
			$this->loader->add_filter( 'gamipress_activity_triggers', $plugin_integrations, 'gamipress_ays_survey_maker_activity_triggers', 1, 2 );
			$this->loader->add_filter( 'gamipress_trigger_get_user_id', $plugin_integrations, 'gamipress_ays_survey_maker_trigger_get_user_id', 10, 3 );
			$this->loader->add_action( 'ays_sm_survey_before_save_entry', $plugin_integrations, 'gamipress_ays_survey_maker_submit_survey' );

			//GamiPress / front-end
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'gamipress_ays_survey_maker_finish_survey', 80 );
		// ===== GamiPress integration =====

        // ===== SendGrid integration =====
			// SendGrid integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_sendgrid_save', 70, 2 );
			// SendGrid integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_sendgrid_content', 70, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_sendgrid_save', 70, 2 );
			// SendGrid integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_sendgrid_options', 70, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_sendgrid_functional', 70, 3 );
        // ===== SendGrid integration =====

		// ===== Mad Mimi integration ====
			// Mad Mimi integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mad_mimi_content', 90, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_mad_mimi_save', 90, 2 );

			// Mad Mimi integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mad_mimi_content', 90, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_mad_mimi_options', 90, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_mad_mimi_save', 90, 2 );

			// Mad Mimi integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_mad_mimi_options', 90, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_mad_mimi_functional', 90, 3 );
		// ===== Mad Mimi integration ====
		
		// ===== GetResponse integration ====
			// GetResponse integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_get_response_content', 100, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_get_response_save', 100, 2 );

			// GetResponse integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_get_response_content', 100, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_get_response_options', 100, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_get_response_save', 100, 2 );

			// GetResponse integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_get_response_options', 100, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_get_response_functional', 100, 3 );
		// ===== GetResponse integration ====

		// ===== ConvertKit integration ====
			// ConvertKit integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_convert_kit_content', 110, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_convert_kit_save', 110, 2 );

			// ConvertKit integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_convert_kit_content', 110, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_convert_kit_options', 110, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_convert_kit_save', 110, 2 );

			// ConvertKit integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_convert_kit_options', 110, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_convert_kit_functional', 110, 3 );
		// ===== ConvertKit integration ====

		// ===== Sendinblue integration ====
			// Sendinblue integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_sendinblue_content', 115, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_sendinblue_save', 115, 2 );

			// Sendinblue integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_sendinblue_content', 115, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_sendinblue_options', 115, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_sendinblue_save', 115, 2 );

			// Sendinblue integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_sendinblue_options', 115, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_sendinblue_functional', 115, 3 );
		// ===== Sendinblue integration ====

		// ===== MailerLite integration ====
			// MailerLite integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mailerLite_content', 120, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_mailerLite_save', 120, 2 );

			// MailerLite integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mailerLite_content', 120, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_mailerLite_options', 120, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_mailerLite_save', 120, 2 );

			// MailerLite integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_mailerLite_options', 120, 2 );
			$this->loader->add_action( 'ays_sm_front_end_integrations', $plugin_integrations, 'ays_front_end_mailerLite_functional', 120, 3 );
		// ===== MailerLite integration ====

		// ===== PayPal integration ====
			// PayPal integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_PayPal_content', 125, 2 );

			// PayPal integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_PayPal_content', 125, 2 );
		// ===== PayPal integration ====
				
		// ===== Stripe integration ====
			// Stripe integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_Stripe_content', 130, 2 );
				
			// Stripe integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_Stripe_content', 130, 2 );
		// ===== Stripe integration ====


		// ===== reCAPTCHA integration ====
			// reCAPTCHA integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_recaptcha_content', 135, 2 );
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_saves', $plugin_integrations, 'ays_settings_page_recaptcha_save', 135, 2 );

			// reCAPTCHA integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_recaptcha_content', 135, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_options', $plugin_integrations, 'ays_survey_page_recaptcha_options', 135, 2 );
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_saves', $plugin_integrations, 'ays_survey_page_recaptcha_save', 135, 2 );

			// reCAPTCHA integration / front-end
			$this->loader->add_filter( 'ays_sm_front_end_integrations_options', $plugin_integrations, 'ays_front_end_recaptcha_options', 135, 2 );
			$this->loader->add_filter( 'ays_sm_front_end_recaptcha', $plugin_integrations, 'ays_front_end_recaptcha_functional', 135, 3 );
		// ===== reCAPTCHA integration ====

		// // ===== Aweber integration ====
			// Aweber integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_aweber_settings_page_content', 140, 2 );

			// Aweber integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_aweber_content', 140, 2 );
		// // ===== Aweber integration ====

		// // ===== MailPoet integration ====
			// MailPoet integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mailpoet_content', 145, 2 );

			// MailPoet integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mailpoet_content', 145, 2 );
		// // ===== MailPoet integration ====

		// // ===== MyCred integration ====
			// MyCred integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_mycred_settings_page_content', 150, 2 );
		// // ===== MyCred integration ====
		
		// // ===== Klaviyo integration ====
			// Klaviyo integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_klaviyo_content', 155, 2 );

			// Klaviyo integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_klaviyo_content', 155, 2 );
		// // ===== Klaviyo integration ====
		
		// // ===== Chatgpt integration ====
			// Chatgpt integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_survey_ai_content', 160, 2 );
		// // ===== Chatgpt integration ====

			
		// Payment finish
		$this->loader->add_action( 'ays_sm_front_end_integrations_after_voteing', $plugin_integrations, 'ays_front_end_payment_finish', 120);

	}

	private function define_custom_post_type_hooks(){
		$plugin_custom_post_type = new Survey_Maker_Custom_Post_Type( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Survey_Maker_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
