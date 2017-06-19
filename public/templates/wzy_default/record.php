<?php

// Get the record ID.
if ( isset( $GLOBALS['record_id'] ) && $GLOBALS['record_id'] !== '' ) {
	$record_id = $GLOBALS['record_id'];
} else {
	$record_id = get_post()->ID;
}

$record = new wzy_Records_Template_Tags( '1.0.0' );


if ( ! post_password_required() ) {

	echo '<p>This is a protected record.</p>';

} else {

	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_name', 'h1', false );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_court', 'p', true );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_date_of_death', 'p', true );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_applicant', 'p', true );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_qualification', 'p', true );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_personalty', 'p', true );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_realty', 'p', true );
	echo $record->get_the_wzy_record_meta( $record_id, 'wzy_record_filed', 'p', true );

}