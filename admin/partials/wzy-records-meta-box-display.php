<?php
/**
 * Provide a meta box view for the settings page
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin/partials
 * @author     wzyMedia <wzy@outlook.com>
 */

/**
 * Meta Box
 *
 * Renders a single meta box.
 *
 * @since       1.0.0
*/
?>

<form action="options.php" method="POST">
	<?php settings_fields( 'wzy_records_settings' ); ?>
	<?php do_settings_sections( 'wzy_records_settings_' . $active_tab ); ?>
	<?php submit_button(); ?>
</form>
<br class="clear" />
