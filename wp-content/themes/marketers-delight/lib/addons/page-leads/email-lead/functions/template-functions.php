<?php

/**
 * Shorthand way to display an Email Lead field.
 *
 * @since 1.0
 */

function email_lead_field( $field ) {
	return page_lead_field( 'email_lead', $field );
}


/**
 * Check if page has the Email Lead. This function can be used
 * like any WordPress conditional (like is_page(), is_single(), etc.)
 * and is used in this plugin to load styles.
 *
 * @since 1.0
 */

function has_email_lead() {
	if (
	 	email_lead_field( 'headline' )   ||
	 	email_lead_field( 'desc' )       ||
	 	email_lead_field( 'email_list' ) ||
	 	email_lead_field( 'email_code' )
	)
		return true;
}


/**
 * Create options array to feed to md_email_form().
 *
 * @since 1.0
 */

function email_lead_email_data() {
	$data   = get_option( 'md_email_data' );
	$fields = array();

	$fields['email_title'] = email_lead_field( 'email_title' );
	$fields['email_desc']  = email_lead_field( 'email_desc' );
	$fields['email_list']  = email_lead_field( 'email_list' );

	$fields['email_input']         = email_lead_field( 'email_input' );
	$fields['email_input']['name'] = isset( $fields['email_input']['name'] ) ? $fields['email_input']['name'] : '';
	$fields['email_name_label']    = email_lead_field( 'email_name_label' );
	$fields['email_email_label']   = email_lead_field( 'email_email_label' );
	$fields['email_submit_text']   = email_lead_field( 'email_submit_text' );

	$fields['email_form_style'] = email_lead_field( 'email_form_style' );
	$fields['email_form_style']['attached'] = isset( $fields['email_form_style']['attached'] ) ? $fields['email_form_style']['attached'] : '';

	$fields['email_form_title']  = email_lead_field( 'email_form_title' );
	$fields['email_form_footer'] = email_lead_field( 'email_form_footer' );

	$fields['email_image']   = email_lead_field( 'email_image' );
	$fields['email_classes'] = email_lead_field( 'email_classes' );

	if ( $data['service'] == 'custom_code' )
		$fields['email_code'] = email_lead_field( 'email_code' );
/*
	if ( $data['service'] == 'aweber' ) {
		$fields['aweber']['tracking_image'] = email_lead_field( 'tracking_image' );
		$fields['aweber']['form_id']        = email_lead_field( 'form_id' );
		$fields['aweber']['thank_you']      = email_lead_field( 'thank_you' );
		$fields['aweber']['already_sub']    = email_lead_field( 'already_sub' );
		$fields['aweber']['ad_tracking']    = email_lead_field( 'ad_tracking' );
	}
*/
	return $fields;
}