<?php

/**
 * Creates appropriate markup schema for <article>.
 *
 * @since 4.1
 */

function md_article_schema() {
	if ( is_home() || is_single() )
		echo 'itemscope itemtype="http://schema.org/BlogPosting"';
	else
		echo 'itemscope itemtype="http://schema.org/WebPage"';
}


/**
 * A list of classes to add to the header.
 *
 * @since 4.1
 */

function md_header_classes() {
	$menu = ( ! md_has_menu() || ! md_has_logo() ? ' header-simple' : ' header-standard' ) . ' block-single-tb';
	echo apply_filters( 'md_filter_header_classes', $menu );
}


/**
 * A list of classes to add to the content box container.
 *
 * @since 4.1
 */

function md_content_box_classes( $custom = null ) {
	$layout   = md_layout();
	$position = md_featured_image_position();
	$filter   = apply_filters( 'md_filter_content_box_classes', ' block-double-tb' );
	$classes  = '';

	$active_layout = ! empty( $layout['content_box']['single'] ) ? $layout['content_box']['single'] : $layout['content_box']['site'];

	if ( md_has_sidebar() ) {
		// content box layout
		if ( $active_layout == '' || $active_layout == 'content_sidebar' )
			$classes .= ' content-sidebar';
		elseif ( $active_layout == 'sidebar_content' )
			$classes .= ' sidebar-content';

		$classes .=  ' inner';

		// teasers
		if ( md_has_teasers() )
			$classes .= ' content-box-wide';
		else
			$classes .= ' content-box-slim';
	}

	echo $classes . $filter . $custom;
}


/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_content_classes() {
	echo apply_filters( 'md_filter_content_classes', ' format-text-main' );
}


/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_byline_classes() {
	echo apply_filters( 'md_filter_byline_classes', ' text-sec links-sec mb-half' );
}


/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_sidebar_classes() {
	echo apply_filters( 'md_filter_sidebar_classes', ' format-text-sec links-side' );
}


/**
 * A list of classes to add to the header.
 *
 * @since 4.5
 */

function md_footer_classes() {
	echo apply_filters( 'md_filter_footer_classes', ' format-text-sec block-double-tb box-sec links-main links-sec' );
}


/**
 * Determine the logo HTML tag (I guess this still matters?)
 *
 * @since 4.1
 */

function md_logo_html() {
	echo is_front_page() ? 'h1' : 'p';
}


/**
 * Returns content item HTML container.
 *
 * @since 4.1
 */

function md_content_item_headline_html() {
	echo md_has_headline_cover() ? 'div' : 'header';
}


/**
 * Active list of byline items. Compares preset byline items (can
 * also be filtered in/out) with Customizer user choices.
 *
 * @since 4.5
 */

function md_byline_items() {
	$items = apply_filters( 'md_filter_byline_items', array( 'author', 'date', 'comments', 'edit' ) );
	$user  = get_theme_mod( 'md_layout_byline_items' );

	if ( empty( $user ) )
		$user = array();

	return array_diff( $items, $user );
}


/**
 * Outputs main sidebar or custom sidebar.
 *
 * @since 4.1
 */

function md_sidebar() {
	global $wp_query;

	$id = $wp_query->get_queried_object_id();

	if ( md_has_custom_sidebar() )
		dynamic_sidebar( "sidebar-$id" );
	else
		dynamic_sidebar( 'sidebar-main' );
}


/**
 * Returns an array of active widget areas with the md-footer-col prefix.
 *
 * @since 4.0
*/

function md_footer_columns() {
	$columns = array();

	foreach ( array_filter( wp_get_sidebars_widgets() ) as $area => $widgets )
		if ( substr( $area, 0, 13 ) == 'md-footer-col' )
			$columns[] = $area;

	return count( $columns );
}


/**
 * Get image ID from URL.
 * https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @since 4.3.5
 */

function md_url_image_id( $url ) {
	global $wpdb;

	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) );

	if ( ! empty ( $attachment ) )
		return $attachment[0];
}


/**
 * Outputs the menu name assigned to the specified Menu area.
 *
 * @since 1.0
 */

function md_get_menu_name( $menu ) {
	$menus       = get_nav_menu_locations();
	$menu_object = wp_get_nav_menu_object( $menus[$menu] );
	$menu_name   = isset( $menu_object->name ) ? $menu_object->name : __( 'Menu', 'md' );

	return esc_html( $menu_name );
}


/**
 * Searches admin panel to check if current screen is on an Editor page.
 *
 * @since 4.3.5
 */

function md_is_editor() {
	$screen     = get_current_screen();
	$post_types = array_merge( array( 'post', 'page' ), apply_filters( 'md_post_type_meta', array() ) );

	return in_array( $screen->post_type, $post_types ) && ( $screen->base == 'post' || $screen->base == 'post-new' ) ? true : false;
}


/**
 * Returns data about any taxonomy option set.
 *
 * @since 4.3.5
 */

if ( ! function_exists( 'md_tax_data' ) ) :

function md_tax_data( $id ) {
	$term     = get_queried_object();
	$term_id  = isset( $term->term_id ) ? $term->term_id : '';
	$taxonomy = isset( $term->taxonomy ) ? $term->taxonomy : '';
	$tax_name = $taxonomy == 'category' ? 'tax' : $taxonomy;

	return array(
		'is_tax' => is_category() || is_tax() ? true : false,
		'fields' => get_option( "{$id}_{$tax_name}{$term_id}" )
	);
}

endif;


/**
 * Searches admin panel to check if current screen is
 * Edit Taxonomy.
 *
 * @since 4.3.5
 */

function md_is_admin_tax() {
	$screen     = get_current_screen();
 	$taxonomies = array_merge( array( 'category' ), apply_filters( 'md_taxonomy_meta', array() ) );

	return ! empty( $screen->taxonomy ) && in_array( $screen->taxonomy, $taxonomies ) && in_array( $screen->base, array( 'edit-tags', 'term' ) ) && isset( $_GET['tag_ID'] ) ? true : false;
}


/**
 * A helper function for use in the admin panel, displays different
 * option HTML formatting based on need for post meta vs. settings API.
 *
 * @since 4.3.5
 */

function md_option_name( $id, $group = null, $option = null ) {
	$is_editor = md_is_editor();
	$is_ajax   = ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? true : false;
	$group     = isset( $group ) ? ( $is_editor || $is_ajax ? "_{$group}" : "[$group]" ) : '';
	$option    = $option != '' ? "[$option]" : '';

	return "{$id}{$group}{$option}";
}


/**---DELETE BELOW DEPRECATED FUNCTIONS IN NEAR FUTURE @since 4.4.2---**/
/**
 * Creates appropriate markup schema for <html>.
 *
 * @since 4.1
 * @deprecated 4.4.2
 */
function md_html_schema() {
	$schema = 'http://schema.org/';
	if ( is_single() )
		$type = 'Article';
	elseif ( is_author() )
		$type = 'ProfilePage';
	elseif ( is_search() )
		$type = 'SearchResultsPage';
	else
		$type = 'WebPage';
	echo "itemscope=\"itemscope\" itemtype=\"$schema{$type}\"";
}