<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class wzy_Records_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wzy-records-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wzy-records-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add the 'rcno_review' CPT to the WP query.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $query
	 */
	public function wzy_record_query( WP_Query $query ) {
		// Don't change query on admin page.
		if ( is_admin() ) {
			return;
		}

		// Check on all public pages.
		if ( ! is_admin() && $query->is_main_query() ) {
			// Post archive page.
			if ( is_post_type_archive( 'wzy_record' ) ) {
				// set post type to only record.
				$query->set( 'post_type', 'wzy_record' );

				return;
			}



			// Add 'wzy_record' CPT to homepage if set in options.
			if ( true === true ) {
				if ( is_home() || $query->is_home() || $query->is_front_page() ) {
					$this->wzy_add_record_to_query( $query );
				}
			}
			// Every other page.
			if ( is_category() || is_tag() || is_author() ) {
				$this->wzy_add_record_to_query( $query );

				return;
			}
		}
	}

	/**
	 * Change the query and add reviews to query object.
	 *
	 * @since 1.0.0
	 * @param type WP_Query object.
	 * @return void
	 */
	private function wzy_add_record_to_query( WP_Query $query ) {
		// Add CPT to query.
		$post_type = $query->get( 'post_type' );

		if ( is_array( $post_type ) && ! array_key_exists( 'wzy_record', $post_type ) ) {
			$post_type[] = 'wzy_record';
		} else {
			$post_type = array( 'post', $post_type, 'wzy_record' );
		}

		$query->set( 'post_type', $post_type );
	}

	/**
	 * Get the rendered content of a record and forward it to the theme as the_content()
	 *
	 * @since 1.0.0
	 * @param string $content
	 * @return string $content
	 */
	public function wzy_get_record_content( $content ) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		// Only render specifically if we have a record.
		if ( 'wzy_record' === get_post_type() ) {
			// Remove the filter
			remove_filter( 'the_content', array( $this, 'wzy_get_record_content' ) );

			$record_post          = get_post();
			$record               = get_post_custom( $record_post->ID );
			$GLOBALS['record_id'] = $record_post->ID;

			if ( is_single() || true === true ) {
				$content = $this->rcno_render_review_content( $record_post );
			} else {
				$content = $this->rcno_render_review_content( $record_post ); //TODO: Normally the excerpt should go here.
			}

			// Add the filter again.
			add_filter( 'the_content', array( $this, 'wzy_get_record_content' ), 10 );
		}

		// Return the rendered content.
		return $content;
	}

	/**
	 * Do the actual rendering using the review.php file provided by the layout
	 *
	 * @since 1.0.0
	 * @param object $record_post
	 * @return string $content
	 */
	public function rcno_render_review_content( $record_post ) {

		// Get the layout's include path.
		$include_path = __DIR__ . 'templates/wzy_default/review.php';

		if ( ! file_exists( $include_path ) ) {
			// If the layout does not provide a review template file, use the default one
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/wzy_default/record.php';
		}

		// Get the record data.
		$record = get_post_custom( $record_post->ID );

		// Start rendering.
		ob_start();

		// Include the book review template tags.
		require_once( __DIR__ . '/class-wzy-template-tags.php' );

		include( $include_path );
		// and render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		ob_end_clean();

		// return the rendered content.
		return $content;
	}


	public function wzy_get_archive_template( $template ) {

		if ( is_archive() && is_category() ) {
			$new_template = __DIR__ . '/templates/wzy_default/test.php';

			if ( is_readable( $new_template ) ) {
				return $new_template;
			}
		}

		return $template;
	}

}
