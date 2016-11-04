<?php

/**
 * Add classes to specified WordPress Widgets (saves sooo much CSS).
 *
 * @since 4.0
 */

function md_widget_classes( $params ) {
	global $wp_registered_widgets;

	$classes = apply_filters( 'md_widget_classes', array(
		'list box-style-list' => array(
			'recent-posts',
			'recent-comments',
			'archives',
			'meta',
			'categories'
		),
		'list list-large box-style-list' => array(
			'rss'
		)
	) );

	foreach ( $classes as $class => $widgets )
		foreach ( $widgets as $widget )
			if ( $params[0]['widget_id'] == "$widget-" . $wp_registered_widgets[$params[0]['widget_id']]['params'][0]['number'] )
				$params[0]['before_widget'] = preg_replace( '/class="([^"]*)"/', 'class="$1 ' . $class . '"', $params[0]['before_widget'] );

	return $params;
}

add_filter( 'dynamic_sidebar_params', 'md_widget_classes' );


/**---DELETE BELOW DEPRECATED FUNCTIONS IN NEAR FUTURE @since 4.5---**/
/**
 * Sets the <title> tag. Props to Tom McFarlin's tutorial - https://tommcfarlin.com/filter-wp-title/
 *
 * @since 4.0
 * @deprecated 4.5
 */
function md_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name' );
	$desc = get_bloginfo( 'description', 'display' );
	if ( $desc && ( is_home() || is_front_page() ) )
		$title = "$title $sep $desc";
	if ( $paged >= 2 || $page >= 2 )
		$title = sprintf( __( 'Page %s', 'md' ), max( $paged, $page ) ) . " $sep $title";
	return $title;
}
//add_filter( 'wp_title', 'md_wp_title', 10, 2 );

/**
 * Strips pingbacks count from comment count.
 *
 * @since 4.0
 * @deprecated 4.5
 */
function md_real_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$status           = get_comments( "status=approve&post_id=$id" );
		$comments_by_type = separate_comments( $status );
		return count( $comments_by_type['comment'] );
	}
	else
		return $count;
}