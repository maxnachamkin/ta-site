<?php

/**
 * MD Customizer settings.
 *
 * @since 4.0
 */

function md_customizer( $wp_customize ) {

	$sanitize = new md_sanitize;

	// Include custom controls

	require_once( MD_PLUGIN_DIR . 'customize/controls/checkboxes.php' );
	require_once( MD_PLUGIN_DIR . 'customize/controls/alpha-color-picker.php' );
	require_once( MD_PLUGIN_DIR . 'customize/controls/md-textarea.php' );
	require_once( MD_PLUGIN_DIR . 'customize/controls/email-connect.php' );

	// Show Tagline

	$wp_customize->add_setting( 'md_show_tagline', array(
		'sanitize_callback' => array( $sanitize, 'checkbox' )
	) );

	$wp_customize->add_control( 'md_show_tagline', array(
		'type'    => 'checkbox',
		'label'   => 'Show tagline',
		'section' => 'title_tagline'
	) );

	// Logo upload

	$wp_customize->add_setting( 'md_logo', array(
		'sanitize_callback' => array( $sanitize, 'image' )
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'md_logo', array(
		'label'    => __( 'Logo', 'md' ),
		'section'  => 'title_tagline',
		'settings' => 'md_logo',
	) ) );


	/**
	 * Layout Options
	 *
	 * @since 4.1.1
	 */

	$wp_customize->add_section( 'md_layout' , array(
		'title'    => __( 'Layout', 'md' ),
		'priority' => 25
	) );

	// Layout

	$wp_customize->add_setting( 'md_layout_content_box', array(
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'md_sanitize_content_box'
	) );

	$wp_customize->add_control( 'md_layout_content_box', array(
		'type'    => 'select',
		'label'   => __( 'Content Box', 'md' ),
		'section' => 'md_layout',
		'choices' => array(
			''                => __( 'Select content box layout&hellip;', 'md' ),
			'content_sidebar' => __( 'Content / Sidebar (default)', 'md' ),
			'sidebar_content' => __( 'Sidebar / Content', 'md' )
		)
	) );

	// Featured Image

	$wp_customize->add_setting( 'md_featured_image_position_default', array(
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'md_sanitize_featured_image_position'
	) );

	$wp_customize->add_control( 'md_featured_image_position_default', array(
		'type'    => 'select',
		'label'   => __( 'Default Featured Image Position', 'md' ),
		'section' => 'md_layout',
		'choices' => array(
			''               => __( 'Select image position&hellip;', 'md' ),
			'right'          => __( 'Right, text wrap', 'md' ),
			'left'           => __( 'Left, text wrap', 'md' ),
			'center'         => __( 'Center, no text wrap', 'md' ),
			'below_headline' => __( 'Full-width, below headline', 'md' ),
			'above_headline' => __( 'Full-width, above headline', 'md' ),
			'headline_cover' => __( 'Headline cover', 'md' ),
			'header_cover'   => __( 'Header cover', 'md' )
		)
	) );

	// Enable Sidebar on all Posts

	$wp_customize->add_setting( 'md_layout_sidebar_enable', array(
		'sanitize_callback' => array( $sanitize, 'checkbox' )
	) );

	$wp_customize->add_control( 'md_layout_sidebar_enable', array(
		'type'    => 'checkbox',
		'label'   => 'Enable sidebar (posts + archives)',
		'description' => __( 'Add/edit Widgets in <b>Main Sidebar</b>', 'md' ),
		'section' => 'md_layout'
	) );

	// Blog Teasers

	$wp_customize->add_setting( 'md_layout_teasers', array(
		'sanitize_callback' => array( $sanitize, 'checkbox' )
	) );

	$wp_customize->add_control( 'md_layout_teasers', array(
		'type'    => 'checkbox',
		'label'   => 'Enable blog teasers',
		'section' => 'md_layout'
	) );

	// Author Box

	$wp_customize->add_setting( 'md_author_box', array(
		'sanitize_callback' => array( $sanitize, 'checkbox' )
	) );

	$wp_customize->add_control( 'md_author_box', array(
		'type'    => 'checkbox',
		'label'   => 'Enable author box (after post)',
		'description' => __( 'Edit user profile to add/edit author bio.', 'md' ),
		'section' => 'md_layout'
	) );

	// Byline Items

	$wp_customize->add_setting( 'md_layout_byline_items', array(
		'sanitize_callback' => array( $sanitize, 'checkboxes' )
	) );

	$wp_customize->add_control( new Md_Checkboxes( $wp_customize, 'md_layout_byline_items', array(
		'section' => 'md_layout',
		'label'   => __( 'Byline Items', 'md' ),
		'choices' => array(
			'author'   => __( 'Remove <b>Author</b>', 'md' ),
			'date'     => __( 'Remove <b>Date</b>', 'md' ),
			'comments' => __( 'Remove <b>Comments</b>', 'md' ),
			'edit'     => __( 'Remove <b>Edit</b>', 'md' )
		)
	) ) );

}

add_action( 'customize_register', 'md_customizer' );


/**
 * Check custom select fields to save safe data.
 *
 * @since 4.0
 */

function md_sanitize_content_box( $input ) {
	return in_array( $input, array( 'content_sidebar', 'sidebar_content' ) ) ? $input : '';
}

/**
 * Check custom select fields to save safe data.
 *
 * @since 4.5
 */

function md_sanitize_featured_image_position( $input ) {
	return in_array( $input, array( 'right', 'left', 'below_headline', 'above_headline', 'headline_cover', 'header_cover' ) ) ? $input : '';
}


/**---DELETE BELOW DEPRECATED FUNCTIONS IN NEAR FUTURE @since 4.5---**/
/**
 * Checkboxes are easy: it's either true, or it's nothing.
 *
 * @since 4.1.1
 * @deprecated 4.5
 */
function md_sanitize_checkbox( $input ) {
	if ( $input == 1 )
		return 1;
	else
		return '';
}
/**
 * MD Customizer image upload. Is this even necessary?
 *
 * @since 4.0
 * @deprecated 4.5
 */
function md_sanitize_image( $input ) {
	return esc_url( $input );
}