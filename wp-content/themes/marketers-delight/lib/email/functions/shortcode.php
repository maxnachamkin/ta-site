<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * This function passes settings data to the Email Signup Form template.
 * The template is located in this file:
 *
 * /marketers-delight/email/templates/email-form.php
 *
 * You can make changes to the HTML of the shortcode  by duplicating
 * that file from this plugin and pasting it into a /templates/ folder
 * in your MD Child Theme, like:
 *
 * /child-theme-name/templates/email-form.php
 *
 * @since 4.3
 */

if ( ! function_exists( 'md_email_shortcode' ) ) :

function md_email_shortcode( $atts ) {
	$email = get_option( 'md_email' );
	$data  = get_option( 'md_email_data' );

	// extract $atts

	extract( shortcode_atts( array(
		'title'      => '',
		'desc'       => '',
		'bg_color'   => '',
		'bg_image'   => '',
		'text_color' => '',
		'classes'    => '',
		'attached'   => ''
	), $atts, 'md_email' ) );

	// set email form data

	$email_connected = ( $data['service'] != 'custom_code' ? array(
		'email_form_style' => array(
			'attached' => isset( $atts['attached'] ) && ( $atts['attached'] == 1 || $atts['attached'] == 0 ) ? $atts['attached'] : $email['email_form_style']['attached']
		)
	) : array() );

	$fields = array_merge( array(
		'email_title'      => ! empty( $atts['title'] ) ? $atts['title'] : $email['email_title'],
		'email_desc'       => ! empty( $atts['desc'] ) ? $atts['desc'] : $email['email_desc'],
		'email_classes'    => ! empty( $atts['classes'] ) ? $atts['classes'] : $email['email_classes']
	), $email_connected );

	$fields_atts = array(
		'before_title' => '<div class="small-title mb-half">',
		'after_title'  => '</div>'
	);

	// output buffer email form

	$bg_color    = ! empty( $atts['bg_color'] ) ? $atts['bg_color'] : $email['bg_color'];
	$bg_image    = ! empty( $atts['bg_image'] ) ? $atts['bg_image'] : $email['bg_image'];
	$text_color  = ! empty( $atts['text_color'] ) ? $atts['text_color'] : $email['text_color_scheme'];
	$block       = ( ! empty( $bg_color ) || ! empty( $bg_image ) ) && $bg_color != 'transparent' ? ' block-mid' : '';
	$form_border = ! in_array( $bg_color, array( 'transparent', '#ffffff', '#FFFFFF' ) ) ? ' form-no-border' : '';
	$classes     = ( ! empty( $bg_image ) ? ' image-overlay text-white' : '' ) . "$block{$form_border} text-$text_color";

	$bg = md_design_background( $bg_color, $bg_image );

	ob_start();
	echo "<div class=\"mb-mid{$classes}\"{$bg}>";
	md_email_form( $fields, $fields_atts );
	echo '</div>';

	return ob_get_clean();
}

endif;

add_shortcode( 'md_email', 'md_email_shortcode' );