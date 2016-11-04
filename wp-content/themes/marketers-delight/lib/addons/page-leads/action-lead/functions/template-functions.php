<?php

/**
 * Shorthand way to display a Action Lead field.
 *
 * @since 4.0
 */

function action_lead_field( $field ) {
	return page_lead_field( 'action_lead', $field );
}


/**
 * Check if page has the Action Lead. This function can be used
 * like any WordPress conditional (like is_page(), is_single(), etc.)
 * and is used in this plugin to load styles.
 *
 * @since 4.0
 */

function has_action_lead() {
	$checkmarks       = action_lead_field( 'checkmarks' );
	$checkmarks_check = ! empty( $checkmarks ) ? array_filter( (array) $checkmarks[0] ) ? true : false : '';

	$type = action_lead_field( 'type' );

	if (
		action_lead_field( 'headline' ) ||
		action_lead_field( 'subtitle' ) ||
		action_lead_field( 'desc' )     ||
		( ! empty( $type ) && (
			( $type == 'email' && ( action_lead_field( 'email_list' ) || action_lead_field( 'email_code' ) ) ) ||
			( $type != 'email' && action_lead_field( 'button_link' ) )
		) )
	)
		return true;
}


/**
 * Create options array to feed to the email form function
 * (used in display() method).
 *
 * @since 4.0
 */

function action_lead_email_form() {
	$data   = get_option( 'md_email_data' );
	$fields = array();

	$fields['email_title'] = action_lead_field( 'email_title' );
	$fields['email_desc']  = action_lead_field( 'email_desc' );
	$fields['email_list']  = action_lead_field( 'email_list' );

	$fields['email_input']         = action_lead_field( 'email_input' );
	$fields['email_input']['name'] = isset( $fields['email_input']['name'] ) ? $fields['email_input']['name'] : '';
	$fields['email_name_label']    = action_lead_field( 'email_name_label' );
	$fields['email_email_label']   = action_lead_field( 'email_email_label' );
	$fields['email_submit_text']   = action_lead_field( 'email_submit_text' );

	$fields['email_form_style']['attached'] = isset( $fields['email_form_style']['attached'] ) ? $fields['email_form_style']['attached'] : '';

	$fields['email_form_title']  = action_lead_field( 'email_form_title' );
	$fields['email_form_footer'] = action_lead_field( 'email_form_footer' );

	$fields['email_classes'] = action_lead_field( 'email_classes' );

	if ( $data['service'] == 'custom_code' )
		$fields['email_code'] = action_lead_field( 'email_code' );
/*
	if ( $data['service'] == 'aweber' ) {
		$fields['aweber']['tracking_image'] = action_lead_field( 'tracking_image' );
		$fields['aweber']['form_id']        = action_lead_field( 'form_id' );
		$fields['aweber']['thank_you']      = action_lead_field( 'thank_you' );
		$fields['aweber']['already_sub']    = action_lead_field( 'already_sub' );
		$fields['aweber']['ad_tracking']    = action_lead_field( 'ad_tracking' );
	}
*/
	return $fields;
}


function action_lead_has_intro() {
	$subtitle = action_lead_field( 'subtitle' );
	$headline = action_lead_field( 'headline' );
	$desc     = action_lead_field( 'desc' );

	if ( ! empty( $headline ) || ! empty( $subtitle ) || ! empty( $desc ) )
		return true;
}


function action_lead_block( $type, $position, $button_style, $fields ) {
	if ( $type == 'button_simple' && empty( $button_style['left_right'] ) && $position != 'md_hook_content' )
		$classes = 'block-full-content';
	elseif ( $type == 'button_simple' && ! empty( $button_style['left_right'] ) && $position != 'md_hook_content' ) {
		if ( empty( $fields['headline'] ) && empty( $fields['subtitle'] ) )
			$classes = 'block-single-tb';
		else
			$classes = 'block-double-tb';
	}
	elseif ( $position != 'md_hook_content' && ! empty( $type ) )
		$classes = 'block-double-top';
	else
		$classes = 'block-double';

	return $classes;
}


function action_lead_content_box_classes( $type, $position, $button_style ) {
	if ( $position != 'md_hook_content' && action_lead_has_intro() && ! empty( $type ) && ( $type != 'button_simple' || ! empty( $button_style['left_right'] ) ) )
		$classes = 'content-sidebar content-box-slim';
	else
		$classes = 'text-center';

	if ( $type == 'button_simple' && ! empty( $button_style['left_right'] ) && $position != 'md_hook_content' )
		$classes .= ' alignvertical';

	return $classes;
}


function action_lead_content_classes( $type, $position, $button_style ) {
	$classes = '';

	if ( $type != 'button_simple' && $position != 'md_hook_content' )
		return ' mt-single block-double-r mb-double';

	if ( ( $type == 'button_simple' && empty( $button_style['left_right'] ) ) || $position == 'md_hook_content' )
		return ' mb-mid';
}


function action_lead_sidebar_classes( $type, $position, $button_style ) {
	$classes = '';

	if ( ! action_lead_has_intro() )
		$classes .= ' inline-block';
	elseif ( $position != 'md_hook_content' && ! empty( $button_style['left_right'] ) )
		$classes .= ' text-right';

	if ( $type == 'button_simple' && ! empty( $button_style['left_right'] ) && $position != 'md_hook_content' )
		$classes .= ' aligncontent';

	return $classes;
}