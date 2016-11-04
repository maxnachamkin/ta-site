<?php

/**
 * Various helper methods for use as sanitization callbacks.
 *
 * @since 4.5
 */

class md_sanitize {

	/**
	 * Run text field through native WP function.
	 *
	 * @since 4.5
	 */

	public function text( $data ) {
		return wp_kses_post( $data );
	}


	/**
	 * A checkbox can only have 2 possible return values.
	 * Lock results to '' or true.
	 *
	 * @since 4.5
	 */

	public function checkbox( $data ) {
		if ( $data == 1 )
			return 1;
		else
			return '';
	}


	/**
	 * Sanitizing multiple checkbox options is really cleaning
	 * up a text field with data that represents checked boxes.
	 *
	 * @since 4.5
	 */

	function checkboxes( $data ) {
		$checkboxes = ! is_array( $data ) ? explode( ',', $data ) : $data;

		return ! empty( $checkboxes ) ? array_map( 'sanitize_text_field', $checkboxes ) : array();
	}


	/**
	 * Escape image URL for uploaded media.
	 *
	 * @since 4.5
	 */

	public function image( $data ) {
		return esc_url( $data );
	}


	/**
	 * Run through global MD email data and return list of IDs.
	 *
	 * @since 4.5
	 */

	public function email_list( $data ) {
		$email = get_option( 'md_email_data' );

		if ( empty( $email ) )
			return '';

		foreach ( $email['list_data'] as $id => $fields )
			$lists[] = $id;

		return in_array( $data, $lists ) ? $data : '';
	}


	/**
	 * Run through global MD popups data and return list of IDs.
	 *
	 * @since 4.5
	 */

	public function popups( $data ) {
		$popups = get_option( 'md_popups' );

		if ( empty( $popups ) )
			return '';

		foreach ( $popups['popups'] as $popup => $fields )
			$options[] = $fields['id'];

		return in_array( $data, $options ) ? $data : '';
	}

}