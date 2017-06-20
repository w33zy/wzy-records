<?php

class wzy_Records_Template_Tags {



	/** ****************************************************************************
	 * REVIEW BOOK META TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Generated the required markup for the requested stored book metadata,
	 * accessed via specific meta-keys.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @param string $meta_key
	 * @param string $wrapper
	 * @param bool $label
	 *
	 * @return string|null
	 */
	public function get_the_wzy_record_meta( $record_id, $meta_key = '', $wrapper = '', $label = true ) {

		$review = get_post_custom( $record_id );

		$meta_keys = array(
			'wzy_record_name'          => 'Name',
			'wzy_record_court'         => 'Court',
			'wzy_record_date_of_death' => 'Date of Death',
			'wzy_record_applicant'     => 'Applicant',
			'wzy_record_qualification' => 'Qualification',
			'wzy_record_personalty'    => 'Personalty',
			'wzy_record_realty'        => 'Realty',
			'wzy_record_filed'         => 'Filed',
		);

		$wrappers = array(
			'',
			'span',
			'div',
			'p',
			'h1',
			'h2',
			'h3',
		);

		if ( '' === $meta_key || ! array_key_exists( $meta_key, $meta_keys ) || ! in_array( $wrapper, $wrappers, true ) ) {
			return null;
		}

		if ( isset( $review[ $meta_key ] ) ) {
			if ( strlen( $review[ $meta_key ][0] ) > 0 ) {
				$out = '';
				if ( '' === $wrapper ) {
					$out .= '';
				} else {
					$out .= '<' . $wrapper . ' ' . 'class="' . sanitize_html_class( $meta_key ) . '"' . '>';
				}

				if ( $label ) {
					$out .= __( $meta_keys[ $meta_key ], 'wzy-records' ) . ': ';
				}

				$out .= sanitize_text_field( $review[ $meta_key ][0] );

				if ( '' === $wrapper ) {
					$out .= '';
				} else {
					$out .= '</' . $wrapper . '>';
				}

				return $out;
			} else {
				return '';
			}
		}

		return null;
	}
	
	/**
	 * @param        $taxonomy
	 * @param bool   $label
	 * @param string $sep
	 *
	 * @return null|string
	 */
	public function get_the_wzy_taxonomy_terms( $record_id, $taxonomy, $label = false, $sep = ', ' ) {

		$out = '';

		$terms = get_the_term_list( $record_id, $taxonomy, '<span class="rcno-tax-term">', $sep, '</span>' );
		$tax   = get_taxonomy( $taxonomy );

		if ( false === $tax || false === $terms ) {
			return null;
		}

		$counts = wp_get_post_terms( $record_id, $taxonomy ); // Get the number of terms in a category, per post.
		$tax_label = $tax->labels->name;

		if ( count( $counts ) === 1 ) { // If we have only 1 term singularize the label name.
			$tax_label = wzy_Pluralize_Helper::singularize( $tax_label );
		}


		$prefix = '';

		if ( $label ) {
			$prefix = '<span class="wzy-tax-name">' . $tax_label . ': ' . '</span>';
		}

		if ( $terms && ! is_wp_error( $terms ) ) {
			$out .= sprintf(
				'<div class="wzy-term-list">%1s%2s</div>',
				$prefix,
				$terms
			);
			$out .= '';
		}

		return $out;
	}
}