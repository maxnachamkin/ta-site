<?php

/**
 * A useful function that can be used to output Page Leads
 * setting fields. This function evaluates whether or not to
 * display a field as post meta or the Settings API.
 *
 * @since 4.0
 */

function page_lead_field( $group, $field ) {
	global $wp_query;

	$id       = $wp_query->get_queried_object_id();
	$display  = page_lead_display( $group );
	$tax_data = md_tax_data( $group );
	$is_tax   = $tax_data['is_tax'];
	$tax      = $tax_data['fields'];

	if ( $is_tax ) {
		$activate = ! empty( $tax['activate'] ) ? $tax['activate'] : '';
		$custom   = ! empty( $tax['custom'] ) ? $tax['custom'] : '';
	}
	else {
		$activate = get_post_meta( $id, "{$group}_activate", true );
		$custom   = get_post_meta( $id, "{$group}_custom", true );
	}

	if ( ! empty( $activate['enable'] ) && ! empty( $custom['enable'] ) ) {
		if ( is_singular() )
			return get_post_meta( $id, "{$group}_$field", true );
		elseif ( $is_tax ) {
			$tax[$field] = ! empty( $tax[$field] ) ? $tax[$field] : '';
			return $tax[$field];
		}
	}
	elseif ( ! empty( $activate['enable'] ) || $display ) {
		$option = get_option( $group );
		$option[$field] = ! empty( $option[$field] ) ? $option[$field] : '';
		return $option[$field];
	}
}


/**
 * Checks if Page Lead should be output on blog.
 *
 * @since 4.0
 */

function page_lead_display( $lead ) {
	$data = get_option( $lead );

	if (
		! empty( $data['display']['site'] )                                                      || // sitewide
		( ! empty( $data['display']['blog'] ) && is_home() && ! get_option( 'page_for_posts' ) ) || // blog
		( ! empty( $data['display']['posts'] ) && is_singular( 'post' ) )                        || // posts
		( ! empty( $data['display']['pages'] ) && is_page() )                                       // pages
	)
		return true;
}