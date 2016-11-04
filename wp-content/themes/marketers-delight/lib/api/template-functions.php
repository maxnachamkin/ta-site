<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Check if MD has a specific feature enabled.
 *
 * @since 4.5
 */

function md_has( $feature ) {
	$settings = get_option( 'md_settings' );
	$features = ! empty( $settings['features'] ) ? $settings['features'] : '';

	if ( empty( $features[$feature] ) )
		return true;
}


/**---DELETE BELOW DEPRECATED FUNCTIONS IN NEAR FUTURE @since 4.5---**/
/**
 * Returns data about any taxonomy option set.
 *
 * @since 4.3.8
 * @deprecated 4.5
 * @use md_tax_data
 */
function md_plugin_tax_data( $id ) {
	$term     = get_queried_object();
	$term_id  = isset( $term->term_id ) ? $term->term_id : '';
	$taxonomy = isset( $term->taxonomy ) ? $term->taxonomy : '';
	$tax_name = $taxonomy == 'category' ? 'tax' : $taxonomy;
	return array(
		'is_tax' => is_category() || is_tax() ? true : false,
		'fields' => get_option( "{$id}_{$tax_name}{$term_id}" )
	);
}