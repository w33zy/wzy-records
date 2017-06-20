<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/includes
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
 * @package    wzy_Records
 * @subpackage wzy_Records/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class wzy_Records {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      wzy_Records_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'wzy-records';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - wzy_Records_Loader. Orchestrates the hooks of the plugin.
	 * - wzy_Records_i18n. Defines internationalization functionality.
	 * - wzy_Records_Admin. Defines all hooks for the admin area.
	 * - wzy_Records_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wzy-records-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wzy-records-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wzy-records-admin.php';

		/**
		 * The class responsible for the pluralization and singularization of common nouns.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wzy-pluralize-helper.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-wzy-records-public.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wzy-records-option.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-wzy-records-callback-helper.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-wzy-records-meta-box.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-wzy-records-sanitization-helper.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-wzy-records-settings-definition.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-wzy-records-settings.php';

		$this->loader = new wzy_Records_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the wzy_Records_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new wzy_Records_i18n();

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

		$plugin_admin = new wzy_Records_Admin( $this->get_plugin_name(), $this->get_version() );

		// Creates the book review custom post type.
		$this->loader->add_action( 'init', $plugin_admin, 'rcno_review_posttype' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add the options page and menu item.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( __DIR__ ) ) . $this->plugin_name . '.php' );
		$this->loader->add_action( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Built the option page.
		$settings_callback = new wzy_Records_Callback_Helper( $this->plugin_name );
		$settings_sanitization = new wzy_Records_Sanitization_Helper( $this->plugin_name );
		$plugin_settings = new wzy_Records_Settings( $this->get_plugin_name(), $settings_callback, $settings_sanitization);
		$this->loader->add_action( 'admin_init' , $plugin_settings, 'register_settings' );

		$plugin_meta_box = new wzy_Records_Meta_Box( $this->get_plugin_name() );
		$this->loader->add_action( 'load-toplevel_page_' . $this->get_plugin_name() , $plugin_meta_box, 'add_meta_boxes' );

		// Load the 'Book Description' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->description, 'wzy_records_description_metabox' );
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->general_info, 'wzy_records_general_info_metabox' );


		// Save record.
		$this->loader->add_action( 'save_post', $plugin_admin, 'wzy_save_record', 10, 2 );

		// Add messages on the book review editor screen.
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin,  'wzy_updated_record_messages' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new wzy_Records_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Manipulate the query to include records to home page (if set).
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'wzy_record_query' );

		// Get the rendered content of a record and forward it to the theme as the_content().
		$this->loader->add_filter( 'the_content', $plugin_public, 'wzy_get_record_content' );

		$this->loader->add_filter( 'template_include', $plugin_public, 'wzy_get_archive_template', 99 );

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
	 * @return    wzy_Records_Loader    Orchestrates the hooks of the plugin.
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
