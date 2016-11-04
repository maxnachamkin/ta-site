<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Checks if Main Menu is active on page.
 *
 * @since 1.0
 */

function md_has_main_menu() {
	global $wp_query;

	$tax_data = md_tax_data( 'md_layout' );
	$is_tax   = $tax_data['is_tax'];
	$tax      = $tax_data['fields'];

	if ( $is_tax )
		$single = $tax['main_menu'];
	else
		$single = get_post_meta( $wp_query->get_queried_object_id(), 'md_layout_main_menu', true );

	$single['remove'] = isset( $single['remove'] ) ? $single['remove'] : '';

	if ( has_nav_menu( 'main' ) ) {
		if ( ( is_singular() || $is_tax ) && ! empty( $single['remove'] ) )
			return false;

		return true;
	}
}


/**
 * Counts how many fields are active in Main menu. Minimum to show = 2.
 *
 * @since 1.0
 */

function md_main_menu_items() {
	return count( array_filter( array(
		true, // menu
		true, // search
		has_nav_menu( 'social' ), // social
		apply_filters( 'md_filter_main_menu_items', '' )
	) ) );
}


/**
 * Load searchform template. The searchform template can be
 * overriden in your child/parent theme's folder by dropping
 * templates/search-form-main-menu.php into it.
 *
 * @since 1.0
 */

function md_main_menu_search() {
	$path = 'templates/searchform-main-menu.php';

	if ( $template = locate_template( $path ) )
		load_template( $template );
	else
		load_template( MD_MAIN_MENU_DIR . $path );
}


/**
 * Returns custom page nav menu.
 *
 * @since 1.0
 */

function md_main_menu_custom_menu() {
	$cat = get_query_var( 'cat' );

	if ( ! empty( $cat ) ) {
		$layout = get_option( "md_layout_tax{$cat}" );
		$menu   = $layout['main_menu_menu'];
	}
	else {
		global $wp_query;
		$menu = get_post_meta( $wp_query->get_queried_object_id(), 'md_layout_main_menu_menu', true );
	}

	return $menu;
}