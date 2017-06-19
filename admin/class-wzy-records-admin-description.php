<?php

/**
 * Saving the general descriptions meta information.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin
 */

/**
 * Saving the general meta information.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin
 * @author     wzyMedia <wzy@outlook.com>
 */
class wzy_Records_Admin_Description {

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
	 * Add a metabox for the book description
	 *
	 * @since 1.0.0
	 */
	public function wzy_records_description_metabox() {
		// Add editor metabox for description
		add_meta_box(
			'wzy_records_description_metabox',
			__( 'Record Description', 'wzy-records' ),
			array( $this, 'do_wzy_record_description_metabox' ),
			'wzy_record',
			'normal',
			'high'
		);
	}

	public function do_wzy_record_description_metabox( $record ) {
		$description              = get_post_meta( $record->ID, 'wzy_record_description', true );
		$options                  = array(
			'textarea_rows' => 8,
		);
		$options['media_buttons'] = false;
		$options['teeny'] = true;
		$options['quicktags'] = false;

		wp_editor( $description, 'wzy_record_description', $options );
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function wzy_save_record_description_metadata( $record_id, $data ) {

		// Saving and sanitizing the description.
		if ( isset( $data['wzy_record_description'] ) ) {

			$description = sanitize_post_field( 'wzy_record_description', $data['wzy_record_description'], $record_id );
			update_post_meta( $record_id, 'wzy_record_description', $description );
		}
	}

}
