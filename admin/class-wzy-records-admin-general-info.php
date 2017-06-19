<?php

/**
 * Saving the general record meta information.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin
 */

/**
 * Saving the general record meta information.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin
 * @author     wzyMedia <wzy@outlook.com>
 */

class wzy_Records_Admin_General_Info {
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 *
	 * @param   string $version The version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
	}

	/**
	 * Add a metabox for the book meta information.
	 *
	 * @since 1.0.0
	 */
	public function wzy_records_general_info_metabox() {
		// Add editor metaboxes for general record information.
		add_meta_box(
			'wzy_records_general_info_metabox',
			__( 'General Information', 'wzy-records' ),
			array( $this, 'do_wzy_records_general_info_metabox' ),
			'wzy_record',
			'normal',
			'high'
		);
	}

	/**
	 * Builds and display the metaboxes UI.
	 * @param $review
	 */
	public function do_wzy_records_general_info_metabox( $record ) {
		include __DIR__ . '/views/wzy-general-info-metabox.php';
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function wzy_save_record_general_info_metadata( $record_id, $data, $record = null ) {

		$field_names = array(
			'name',
			'court',
			'date_of_death',
			'applicant',
			'qualification',
			'personalty',
			'realty',
			'filed'
		);

		foreach ( $field_names as $field ) {

			// Saving record title post_meta field.
			if ( isset( $data['wzy_record_' . $field ] ) ) {
				$record_name = sanitize_text_field( $data['wzy_record_' . $field ] );
				update_post_meta( $record_id, 'wzy_record_' . $field, $record_name );
			}

		}

	}

}