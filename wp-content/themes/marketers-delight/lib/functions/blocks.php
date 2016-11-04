<?php

/**
 * Determines needed classes for a headline type element. Spacing,
 * padding, featured image styles, etc.
 *
 * @since 4.1
 */

function md_headline_classes( $pos = null, $image = null ) {
	$position = isset( $pos ) ? $pos : md_featured_image_position();
	$image    = isset( $image ) ? $image : has_post_thumbnail();
	$classes  = '';

	if ( ! empty( $image ) && ( $position == 'header_cover' || $position == 'headline_cover' ) )
		$classes .= ' image-overlay featured-image-cover';

	if ( $position != 'header_cover' || ( in_the_loop() && ! is_singular() ) )
		$classes .= ' inner';

	return apply_filters( 'md_filter_headline_classes', $classes . ' content-item' );
}


/**
 * Determines needed classes for inside a headline type element.
 * Spacing, padding, featured image styles, etc.
 *
 * @since 4.1
 */

function md_headline_inner_classes( $blocks = null ) {
	$position = md_featured_image_position();
	$classes  = '';

	if ( ! empty( $blocks ) )
		$classes .= $blocks;

	if ( $position == 'header_cover' || $position == 'headline_cover' ) {
		if ( empty( $blocks ) )
			$block = $position == 'header_cover' && ( is_singular() || is_category() || is_tax() ) ? 'block-quad inner' : md_headline_block();
		else
			$block = '';

		$classes .= $block . md_featured_image_cover_classes();
	}
	elseif ( empty( $blocks ) )
		$classes .= md_has_sidebar() ? 'block-double' : 'block-full text-center';

	return $classes;
}


/**
 * Headline area spacing.
 *
 * @since 4.1
 */

function md_headline_block() {
	if ( md_has_sidebar() )
		return 'block-triple-double';
	else
		return 'block-quad';
}


/**
 * Outputs classes for content text used in layouts where a sidebar
 * could exist.
 *
 * @since 4.1
 */

function md_content_text_classes() {
	$position = md_featured_image_position();
	$classes  = apply_filters( 'md_filter_content_text_classes', '' );

	if ( has_filter( 'md_filter_content_text_classes' ) )
		echo $classes;

 	if ( $position == 'header_cover' || $position == 'headline_cover' )
		echo md_content_block();
	else
		echo md_has_sidebar() ? 'block-double-content' : 'block-full-content';
}


/**
 * Returns HTML classes for different layouts.
 *
 * @since 4.1
 */

function md_content_block() {
	if ( md_has_sidebar() )
		return 'block-double';
	else
		return 'block-full';
}