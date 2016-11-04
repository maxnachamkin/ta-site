<?php

/**
 * Creates an array used to determine which layout elements of a page to show.
 *
 * @since 4.1
 */

function md_layout( $id = null ) {
	global $wp_query;

	$id = isset( $id ) ? $id : $wp_query->get_queried_object_id();

	$tax_data = md_tax_data( 'md_layout' );
	$is_tax   = $tax_data['is_tax'];
	$tax      = $tax_data['fields'];

	return array_merge( array(
		'header'  => array(
			'single' => ( $is_tax ? $tax['header'] : get_post_meta( $id, 'md_layout_header', true ) )
		),
		'content' => array(
			'single' => ( $is_tax ? $tax['content'] : get_post_meta( $id, 'md_layout_content', true ) )
		),
		'content_box' => array(
			'site'   => get_theme_mod( 'md_layout_content_box' ),
			'single' => ( $is_tax ? ( isset( $tax['content_box'] ) ? $tax['content_box'] : '' ) : get_post_meta( $id, 'md_layout_content_box', true ) )
		),
		'byline' => array(
			'items' => md_byline_items()
		),
		'sidebar' => array(
			'site'  => array(
				'add' => get_theme_mod( 'md_layout_sidebar_enable' )
			),
			'single' => ( $is_tax ? $tax['sidebar'] : get_post_meta( $id, 'md_layout_sidebar', true ) )
		),
		'footer' => array(
			'single' => ( $is_tax ? $tax['footer'] : get_post_meta( $id, 'md_layout_footer', true ) )
		)
	), apply_filters( 'md_layout', array() ) );
}


/**
 * Checks if header is enabled.
 *
 * @since 4.1
 */

if ( ! function_exists( 'md_has_header' ) ) :

	function md_has_header() {
		$layout = md_layout();

		if ( empty( $layout['header']['single']['remove'] ) && ( md_has_logo() || md_has_menu() ) )
			return true;
	}

endif;


/**
 * Checks if logo is enabled.
 *
 * @since 4.1
 */

function md_has_logo() {
	$layout = md_layout();

	if ( empty( $layout['header']['single']['logo'] ) )
		return true;
}


/**
 * Checks if page has tagline.
 *
 * @since 4.4.2
 */

function md_has_tagline() {
	$layout = md_layout();

	if ( get_bloginfo( 'description' ) && get_theme_mod( 'md_show_tagline' ) && empty( $layout['header']['single']['tagline'] ) )
		return true;
}


/**
 * Checks if menu is enabled.
 *
 * @since 4.1
 */

function md_has_menu( $name = null ) {
	$layout    = md_layout();
	$name      = ! isset( $name ) ? 'header' : $name;
	$has_items = wp_nav_menu( array( 'theme_location' => $name, 'fallback_cb' => false, 'echo' => false ) );

	if ( empty( $layout['header']['single']['remove'] ) && empty( $layout['header']['single']['menu'] ) && has_nav_menu( $name ) && ! empty( $has_items ) )
		return true;
}


/**
 * Checks if byline is enabled.
 *
 * @since 4.1
 */

function md_has_content_box() {
	$layout = md_layout();

	if ( empty( $layout['content']['single']['remove_content_box'] ) )
		return true;
}


/**
 * Checks for content headline.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$position = md_featured_image_position();

	return $position == 'header_cover' && is_singular() ? true : false;
}


/**
 * Checks if headline is enabled.
 *
 * @since 4.1
 */

function md_has_headline() {
	$layout = md_layout();

	if ( empty( $layout['content']['single']['remove_headline'] ) )
		return true;
}


/**
 * Checks if byline is enabled.
 *
 * @since 4.1
 */

function md_has_byline() {
	$layout = md_layout();

	if ( get_post_type() == 'post' && empty( $layout['content']['single']['remove_byline'] ) )
		return true;
}


/**
 * Checks if post listings has excerpts enabled.
 *
 * @since 4.5
 */

function md_has_teasers() {
	return get_theme_mod( 'md_layout_teasers' ) ? true : false;
}


/**
 * Checks if author box.
 *
 * @since 4.5
 */

function md_has_author_box() {
	$display = get_theme_mod( 'md_author_box' );

	if ( is_single() && ! empty( $display ) )
		return true;
}


/**
 * Checks if comments are on page.
 *
 * @since 4.1
 */

function md_has_comments() {
	if ( ( comments_open() || get_comments_number() != 0 ) && ! post_password_required() )
		return true;
}


/**
 * Checks if a sidebar is active. This gets tricky.
 *
 * @since 4.1
 */

function md_has_sidebar() {
	$layout = md_layout();

	$site_add      = isset( $layout['sidebar']['site']['add'] ) ? $layout['sidebar']['site']['add'] : '';
	$single_add    = isset( $layout['sidebar']['single']['add'] ) ? $layout['sidebar']['single']['add'] : '';
	$single_remove = isset( $layout['sidebar']['single']['remove'] ) ? $layout['sidebar']['single']['remove'] : '';

	if ( ( is_single() || is_category() ) && ! empty( $site_add ) && ! empty( $single_remove ) )
		return false;
	elseif (
		(
			( ( ! empty( $site_add ) && ( is_single() || is_archive() ) ) || is_home() || is_search() ) ||
			( ! empty ( $single_add ) && ( is_singular() || is_archive() ) )
		) &&

		( is_active_sidebar( 'sidebar-main' ) || md_has_custom_sidebar() )
	)
		return true;
}


/**
 * Checks if main sidebar is active or if a custom sidebar was enabled and is active.
 *
 * @since 4.1
 */

function md_has_custom_sidebar() {
	global $wp_query;

	$id     = get_the_ID();
	$layout = md_layout();

	return ! empty( $layout['sidebar']['single']['custom'] ) && is_active_sidebar( "sidebar-$id" ) ? true : false;
}


/**
 * Checks if footer is enabled.
 *
 * @since 4.1
 */

function md_has_footer() {
	$layout = md_layout();

	if ( empty( $layout['footer']['single']['remove'] ) && ( md_has_footer_columns() || is_active_sidebar( 'footer-copy' ) ) )
		return true;
}


/**
 * Checks if footer columns are enabled.
 *
 * @since 4.1
 */

function md_has_footer_columns() {
	$layout = md_layout();

	if ( md_footer_columns() && empty( $layout['footer']['single']['columns'] ) )
		return true;
}