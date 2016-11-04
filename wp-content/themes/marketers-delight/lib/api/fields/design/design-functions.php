<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Return HTML for Page Lead background color/image.
 *
 * @since 4.3
 */

function md_design_background( $bg_color, $bg_image ) {
	$bg_color_prop = ! empty( $bg_color ) ? 'background-color: ' . esc_url( $bg_color ) . ';' : '';
	$bg_image_prop = ! empty( $bg_image ) ? 'background-image: url(' . esc_url( $bg_image ) . ');"' : '';

	return ! empty( $bg_color ) || ! empty( $bg_image ) ? ' style="' . $bg_color_prop . $bg_image_prop . '"' : '';
}


/**
 * Use these functions to determine whether to show a more spaced
 * layout or a smaller width (generally needed for when Lead is
 * used as full-width or inside a content-sidebar type layout).
 *
 * @since 4.3
 */

function md_design_block( $position ) {
	if ( ! function_exists( 'md_has_sidebar' ) )
		return;

	if ( ! empty( $position ) && $position == 'md_hook_content' && md_has_sidebar() )
		return 'block-double';
	else
		return 'block-full-quad';
}


function md_design_columns_block( $position ) {
	if ( ! function_exists( 'md_has_sidebar' ) )
		return;

	if ( ! empty( $position ) && $position == 'md_hook_content' && md_has_sidebar() )
		return ' block-single-flex-lr block-mid-top';
	elseif ( $position == 'md_hook_content' )
		return ' block-double';
	else
		return ' block-double-tb';
}


function md_design_classes( $position, $design = null ) {
	if ( ! function_exists( 'md_has_sidebar' ) )
		return;

	$classes = '';

	if ( $position == 'md_hook_content' && ! md_has_sidebar() )
		$classes .= ' inner';

	return $classes;
}