<?php
/**
 * The general record info metabox view of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    wzy_Records
 * @subpackage wzy_Records/admin/views
 */

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
?>

<div class="record-general-info-metaboxes">

	<div class="record-general-metabox-1">

		<?php foreach( $field_names as $field ) : ?>

		<div class="record-<?php echo $field; ?> record-container">
			<?php $value = get_post_meta( $record->ID, 'wzy_record_' . $field, true ); ?>
			<label class="wzy_record_<?php echo $field; ?>_label" for="wzy_record_<?php echo $field; ?>"><?php _e( ucfirst( $field ), 'wzy-records' ) ?></label>
			<input type="text" name="wzy_record_<?php echo $field; ?>" id="wzy_record_<?php echo $field; ?>" value="<?php echo sanitize_text_field( $value ); ?>" />
			<?php wp_nonce_field( 'wzy_save_record_' . $field .'_metadata', 'wzy_general_' . $field . '_nonce' ); ?>
		</div>

		<?php endforeach; ?>

	</div>

</div>
