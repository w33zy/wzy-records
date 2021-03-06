<?php
/**
 *
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/includes
 */
/**
 * The get_option functionality of the plugin.
 *
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/includes
 * @author     wzyMedia <wzy@outlook.com>
 */


class wzy_Records_Option {

	/**
	 * Get an option
	 *
	 * Looks to see if the specified setting exists, returns default if not.
	 *
	 * @since 	1.0.0
	 * @return 	mixed 	$value 	Value saved / $default if key if not exist
	 */
	static public function get_option( $key, $default = false ) {

		if ( empty( $key ) ) {
			return $default;
		}

		$plugin_options = get_option( 'wzy_records_settings', array() );

		$value = isset( $plugin_options[ $key ] ) ? $plugin_options[ $key ] : $default;

		return $value;
	}

	/**
	 * Update an option
	 *
	 * Updates the specified option.
	 * This is for developers to update options outside the settings page.
	 *
	 * WARNING: Hooks and filters will be triggered!!
	 * @TODO: Trigger hooks & filters, pull requests welcomed
	 *
	 * @since 1.0.0
	 * @return true if the option was saved or false if not
	 */
	static public function update_option( $key, $value ) {

		if ( empty( $key ) ) {
			return false;
		}

		// Load the options
		$plugin_options = get_option( 'wzy_records_settings', array() );

		// Update the specified value in the array
		$plugin_options[ $key ] = $value;

		// Save the options back to the DB
		return update_option( 'wzy_records_settings', $plugin_options );
	}

	/**
	 * Delete an option
	 *
	 * Deletes the specified option.
	 * This is for developers to delete options outside the settings page.
	 *
	 * WARNING: Hooks and filters will be triggered!!
	 * @TODO: Trigger hooks & filters, pull requests welcomed
	 *
	 * @since 1.0.0
	 * @return true if the option was deleted or false if not
	 */
	static public function delete_option( $key ) {

		if ( empty( $key ) ) {
			return false;
		}

		// Load the options
		$plugin_options = get_option( 'wzy_records_settings', array() );

		// Delete the specified key
		unset($plugin_options[ $key ]);

		// Save the options back to the DB
		return update_option( 'wzy_records_settings', $plugin_options );
	}
}
