<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin
 * @author     wzyMedia <wzy@outlook.com>
 */
class wzy_Records_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of the admin description class handling all general information related functions.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var    object  $description
	 */
	public $description;

	/**
	 * Instance of the admin general info class handling all general information related functions.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var    object  $general_info
	 */
	public $general_info;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		require_once __DIR__ . '/class-wzy-records-admin-description.php';
		$this->description = new wzy_Records_Admin_Description( $this->version );

		require_once __DIR__ . '/class-wzy-records-admin-general-info.php';
		$this->general_info = new wzy_Records_Admin_General_Info( $this->version );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wzy_Records_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wzy_Records_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wzy-records-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wzy_Records_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wzy_Records_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wzy-records-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Creates the book review custom post type.
	 *
	 * @since    1.0.0
	 * @access    public
	 * @uses    register_post_type()
	 * @return  void
	 */
	public function rcno_review_posttype() {
		$cap_type = 'post';
		$plural   = 'Records';
		$single   = 'Record';
		$cpt_name = 'wzy_record';
		$cpt_slug = 'record'; // @TODO: Create an option to select recipe slug.

		$opts['can_export']            = true;
		$opts['capability_type']       = $cap_type;
		$opts['description']           = '';
		$opts['exclude_from_search']   = false;
		$opts['has_archive']           = 'records';
		$opts['hierarchical']          = false;
		$opts['map_meta_cap']          = true;
		$opts['menu_icon']             = 'dashicons-portfolio';
		$opts['menu_position']         = 5;
		$opts['public']                = true;
		$opts['publicly_querable']     = true;
		$opts['query_var']             = true;
		$opts['register_meta_box_cb']  = '';
		$opts['rewrite']               = false;
		$opts['show_in_admin_bar']     = true;
		$opts['show_in_menu']          = true;
		$opts['show_in_nav_menu']      = true;
		$opts['show_ui']               = true;
		$opts['supports'] = array( 'title', 'thumbnail', 'featured' );
		$opts['taxonomies']            = array( 'category', 'post_tag' );

		$opts['capabilities']['delete_others_posts']    = "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']            = "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']           = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']   = "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']      = "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']              = "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']             = "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']     = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']   = "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']          = "publish_{$cap_type}s";
		$opts['capabilities']['read_post']              = "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']     = "read_private_{$cap_type}s";

		$opts['labels']['add_new']            = esc_html__( "New {$single}", 'wzy-records' );
		$opts['labels']['add_new_item']       = esc_html__( "Add New {$single}", 'wzy-records' );
		$opts['labels']['all_items']          = esc_html__( $plural, 'wzy-records' );
		$opts['labels']['edit_item']          = esc_html__( "Edit {$single}", 'wzy-records' );
		$opts['labels']['menu_name']          = esc_html__( $plural, 'wzy-records' );
		$opts['labels']['name']               = esc_html__( $plural, 'wzy-records' );
		$opts['labels']['name_admin_bar']     = esc_html__( $single, 'wzy-records' );
		$opts['labels']['new_item']           = esc_html__( "New {$single}", 'wzy-records' );
		$opts['labels']['not_found']          = esc_html__( "No {$plural} Found", 'wzy-records' );
		$opts['labels']['not_found_in_trash'] = esc_html__( "No {$plural} Found in Trash", 'wzy-records' );
		$opts['labels']['parent_item_colon']  = esc_html__( "Parent {$plural} :", 'wzy-records' );
		$opts['labels']['search_items']       = esc_html__( "Search {$plural}", 'wzy-records' );
		$opts['labels']['singular_name']      = esc_html__( $single, 'wzy-records' );
		$opts['labels']['view_item']          = esc_html__( "View {$single}", 'wzy-records' );

		$opts['rewrite']['ep_mask']    = EP_PERMALINK;
		$opts['rewrite']['feeds']      = false;
		$opts['rewrite']['pages']      = true;
		$opts['rewrite']['slug']       = __( $cpt_slug, 'wzy-records' );
		$opts['rewrite']['with_front'] = false;

		$opts = apply_filters( 'rcno_review_cpt_options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_menu_page(
			__( 'wzy Records', $this->plugin_name ),
			__( 'Records', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

		add_submenu_page(
			'edit.php?post_type=wzy_record',
			__( 'wzy Records', $this->plugin_name ),
			__( 'Settings', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

		remove_menu_page( $this->plugin_name );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 * @return   array 			Action links
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

		$tabs = wzy_Records_Settings_Definition::get_tabs();

		$default_tab = wzy_Records_Settings_Definition::get_default_tab_slug();

		$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET[ 'tab' ] : $default_tab;

		include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );

	}

	/**
	 * Saves all the data from review meta boxes
	 *
	 * @param   int $record_id post ID of record being saved.
	 * @param   mixed $record the record post object.
	 * @see     https://developer.wordpress.org/reference/functions/wp_update_post/#user-contributed-notes
	 *
	 * @since   1.0.0
	 * @return  int|bool
	 */
	public function wzy_save_record( $record_id, $record = null ) {

		if ( ! wp_is_post_revision( $record_id ) ) { // 'save_post' is fired twice, once for revisions, then to save post.

			remove_action( 'save_post', array( $this, 'wzy_save_record' ) );

			$data = $_POST;

			if ( null !== $record && $record->post_type === 'wzy_record' ) {
				$errors = false;

				// Verify if this is an auto save routine. If it is our form has not been submitted, so we don't want to do anything.
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					return $record_id;
				}

				// Check user permissions.
				if ( ! current_user_can( 'edit_post', $record_id ) ) {
					$errors = 'There was an error saving the record. Insufficient administrator rights.';
				}

				// If we have an error update the error_option and return.
				if ( $errors ) {
					update_option( 'rcno_admin_errors', $errors );
					return $record_id;
				}

				$this->description->wzy_save_record_description_metadata( $record_id, $data );
				$this->general_info->wzy_save_record_general_info_metadata( $record_id, $data );

				wp_update_post( $record );

				add_action( 'save_post', array( $this, 'wzy_save_record' ) );
			}
		}
		return true;
	}

	/**
	 * Book review update messages.
	 * See /wp-admin/edit-form-advanced.php
	 * @param array $messages Existing post update messages.
	 * @return array Amended post update messages with new review update messages.
	 */
	function wzy_updated_record_messages( $messages ) {
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['wzy_record'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Record updated.', 'wzy-records' ),
			2  => __( 'Custom field updated.', 'wzy-records' ),
			3  => __( 'Custom field deleted.', 'wzy-records' ),
			4  => __( 'Record updated.', 'wzy-records' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Record restored to revision from %s', 'wzy-records' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Record published.', 'wzy-records' ),
			7  => __( 'Record saved.', 'wzy-records' ),
			8  => __( 'Record submitted.', 'wzy-records' ),
			9  => sprintf(
				__( 'Record scheduled for: <strong>%1$s</strong>.', 'wzy-records' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', 'wzy-records' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Record draft updated.', 'wzy-records' )
		);

		if ( $post_type_object->publicly_queryable && 'wzy_record' === $post_type ) {
			$permalink = get_permalink( $post->ID );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View review', 'wzy-records' ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;

			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview review', 'wzy-records' ) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}
		return $messages;
	}

}
