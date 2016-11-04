<?php

/**
 * Default nav menus.
 *
 * @since 4.1
 */

function md_filter_register_nav_menus() {
	return apply_filters( 'md_filter_register_nav_menus', array(
		'header' => __( 'Header Menu', 'md' )
	) );
}


/**
 * Default screens MD theme metaboxes are added to.
 *
 * @since 4.3.5
 */

function md_post_types_meta() {
	return apply_filters( 'md_post_types_meta', array() );
}


/**
 * Manage number of footer columns with an array of digits.
 *
 * @since 4.5
 */

function md_filter_footer_columns() {
	return apply_filters( 'md_filter_footer_columns', array( 1, 2, 3 ) );
}