<?php

/**
 * Loads MD child theme styles
 */

function ta_child_theme_enqueue_style() {
    wp_enqueue_style( 'marketers-delight', MD_URL . 'style.css' );
    wp_enqueue_style( 'ta-child-theme', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'ta_child_theme_enqueue_style' );


/**
 * Nav Active Menu
 *
 * @since 1.0
 */

function kol_menu_active( $slug ) {
	global $wp_query;

	$active = '';

	if ( ! empty( $wp_query->query['pagename'] ) )
		$active = $wp_query->query['pagename'];

	if ( ! empty( $wp_query->query['category_name'] ) )
		$active = $wp_query->query['category_name'];

	return $active == str_replace( '/', '', $slug ) ? ' header-nav-item-active' : '';
}


/**
 * Header menu JS
 *
 * @since 1.0
 */

function md_inline_js() { ?>

	<script>
		document.getElementById( 'header-menu-trigger' ).onclick = function( e ) {
			apollo.toggleClass( document.getElementById( 'header-nav' ), 'close-on-tablet' );
			apollo.toggleClass( document.getElementById( 'header' ), 'has-mobile-menu' );
			apollo.toggleClass( this, 'md-icon-cancel' );
		}
	</script>

<?php }
