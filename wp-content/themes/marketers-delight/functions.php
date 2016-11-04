<?php

// Define MD constants

define( 'MD_DIR', trailingslashit( get_template_directory() ) );
define( 'MD_URL', trailingslashit( get_template_directory_uri() ) );

define( 'MD_THEME_NAME', 'Marketers Delight 4' );
define( 'MD_THEME_AUTHOR', 'Alex Mangini, Kolakube' );
define( 'MD_VERSION', '4.5.1' );
define( 'MD_THEME_UPDATER_URL', 'https://marketersdelight.net' );

// library

require_once( MD_DIR . 'lib/lib.php' );

// build layout

require_once( MD_DIR . 'lib/build/build.php' );

// admin

require_once( MD_DIR . 'lib/customize/customize.php' );
require_once( MD_DIR . 'lib/admin/meta/metaboxes.php' );
require_once( MD_DIR . 'lib/admin/meta/featured-image.php' );

// functions

require_once( MD_DIR . 'lib/functions/featured-image.php' );
require_once( MD_DIR . 'lib/functions/template-functions.php' );
require_once( MD_DIR . 'lib/functions/blocks.php' );
require_once( MD_DIR . 'lib/functions/layout.php' );
require_once( MD_DIR . 'lib/functions/format.php' );
require_once( MD_DIR . 'lib/functions/walker.php' );
require_once( MD_DIR . 'lib/functions/extensions.php' );

// updater

require_once( MD_DIR . 'lib/updater/theme-updater.php' );


/**
 * Include some important theme files, register menus and add
 * support for other WordPress features.
 *
 * @since 4.0
 */

function md_setup() {

	// load textdomain

	load_theme_textdomain( 'md', MD_DIR . 'languages' );

	// set content width

	$GLOBALS['content_width'] = apply_filters( 'md_content_width', 624 );

	// use WP title tag
	// @since 4.5

	add_theme_support( 'title-tag' );

	// add default posts and comments RSS feed links to head

	add_theme_support( 'automatic-feed-links' );

	// enable featured image

	add_theme_support( 'post-thumbnails' );

	// register nav menus

	register_nav_menus( md_filter_register_nav_menus() );

	// Add image sizes

	add_image_size( 'md-full', 1118, 350, true );
	add_image_size( 'md-banner', 600, 250, true );
	add_image_size( 'md-image', 325, 425, true );
	add_image_size( 'md-thumbnail', 80, 80, true );

	// editor style CSS

	add_editor_style( 'lib/admin/css/editor-style.css' );

}

add_action( 'after_setup_theme', 'md_setup' );


/**
 * Enqueue scripts and styles.
 *
 * @since 4.0
 */

function md_enqueue() {

	// Load styles

	wp_enqueue_style( 'marketers-delight', get_stylesheet_uri() );

	// Apollo JS (will be deprecated eventually)

	wp_enqueue_script( 'md-apollo', MD_URL . 'js/apollo.js', '', '', true );

	// Comment reply JS

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}

add_action( 'wp_enqueue_scripts', 'md_enqueue', 10 );


/**
 * Register various widget areas.
 *
 * @since 4.0
 */

function md_widgets() {

	// sidebar

	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'md' ),
		'description'   => __( 'The main sidebar is added to your blog\'s homepage', 'md' ),
		'id'            => 'sidebar-main',
		'before_widget' => '<section id="%1$s" class="widget %2$s mb-double">',
		'after_widget'  => '</section>',
		'before_title'  => '<p class="small-title">',
		'after_title'   => '</p>'
	) );

	// footer columns

	foreach ( md_filter_footer_columns() as $w ) {
		register_sidebar( array(
			'name'          => __( "Footer $w", 'md' ),
			'description'   => sprintf( __( 'You can create up to 3 columns of content in your site\'s footer. This is column %s.', 'md' ), $w ),
			'id'            => "md-footer-col-$w",
			'before_widget' => '<div id="%1$s" class="widget %2$s mb-double">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="small-title">',
			'after_title'   => '</p>'
		) );
	}

	// footer copyright

	register_sidebar( array(
		'name'          => __( 'Footer Copy', 'md' ),
		'description'   => __( 'Add text and site links to the bottom of the site footer.', 'md' ),
		'id'            => 'footer-copy',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="small-title">',
		'after_title'   => '</p>'
	) );

	// custom sidebars

	$custom_sidebars = md_custom_sidebar( 'md_layout_sidebar' );

	if ( ! empty( $custom_sidebars ) )
		foreach ( $custom_sidebars as $id ) {
			$layout = md_layout( $id );

			if ( ! empty( $layout['sidebar']['single']['custom'] ) )
				register_sidebar( array(
					'name'          => 'Sidebar: ' . esc_html( get_the_title( $id ) ),
					'id'            => 'sidebar-' . absint( $id ),
					'before_widget' => '<section id="%1$s" class="widget %2$s mb-double">',
					'after_widget'  => '</section>',
					'before_title'  => '<p class="small-title">',
					'after_title'   => '</p>'
				) );
		}
}

add_action( 'widgets_init', 'md_widgets' );


/**
 * Conditionally add hooks.
 *
 * @since 4.1.1
 */

function md_template_redirect() {
	$position  = md_featured_image_position();

	// featured image caption
	$has_cover = $position == 'header_cover' || $position == 'headline_cover' ? true : false;
	if ( is_singular() && $has_cover )
		add_action( 'md_hook_content_item_headline_bottom', 'md_featured_image_caption' );

	// category featured image
	if ( ( is_category() || is_tax() ) ) {
		$tax           = md_featured_image_tax_data();
		$tax_image     = $tax['image'];
		$tax_image_pos = $tax['position'];

		if ( $tax_image_pos == 'header_cover' )
			remove_action( 'md_hook_content', 'md_archives_title', 5 );

		if ( ! empty( $tax_image ) )
			if ( $tax_image_pos == 'below_headline' )
				add_action( 'md_hook_content', 'md_featured_image_tax', 7 );
			elseif ( $tax_image_pos == 'above_headline' )
				add_action( 'md_hook_content', 'md_featured_image_tax', 3 );
	}

	// author box
	if ( is_single() && md_has_author_box() )
		add_action( 'md_hook_content', 'md_author_box', 10 );
}

add_action( 'template_redirect', 'md_template_redirect' );


/**
 * Filter in post classes.
 *
 * @since 4.1
 */

function md_post_classes( $classes ) {
	$position = md_featured_image_position();

	if ( in_array( $position, array( '', 'right', 'left', 'center', 'above_headline' ) ) )
		$classes[] = 'has-inline-post-thumbnail';

	return $classes;
}

add_filter( 'post_class', 'md_post_classes' );


/**
 * Filter length of excerpts + more text on teasers.
 *
 * @since 4.5
 */

function md_excerpt_length() {
	if ( md_has_teasers() )
		return apply_filters( 'md_filter_excerpt_length', 30 );
}

add_filter( 'excerpt_length', 'md_excerpt_length' );

function md_excerpt_more() {
	if ( md_has_teasers() )
		return apply_filters( 'md_filter_excerpt_more', '&hellip;' );
}

add_filter( 'excerpt_more', 'md_excerpt_more' );


/**
 * Registers a Twitter user profile field. If Yoast SEO is enabled,
 * this field will not register and we'll just use the Twitter field
 * Yoast registers in their plugin. The user will never notice the
 * transition or need to reinsert their Twitter username to the field.
 *
 * @since 4.1
 */

function md_profile_fields( $fields ) {
	if ( ! defined( 'WPSEO_VERSION' ) )
		$fields['twitter'] = __( 'Twitter username (without @)', 'md' );

	return $fields;
}

add_filter( 'user_contactmethods', 'md_profile_fields' );


/**
 * Outputs inline JavaScript to footer. Pluggable.
 *
 * @since 4.0
 */

if ( ! function_exists( 'md_inline_js' ) ) :

	function md_inline_js() { ?>

		<?php if ( md_has_menu() ) : ?>
			<script>
				document.getElementById( 'header-menu-trigger' ).onclick = function( e ) {
					apollo.toggleClass( document.getElementById( 'header-menu' ), 'close-on-tablet' );
					apollo.toggleClass( document.getElementById( 'header' ), 'has-mobile-menu' );
					apollo.toggleClass( this, 'md-icon-cancel' );
				}
			</script>
		<?php endif; ?>

	<?php }

endif;

add_action( 'md_hook_js', 'md_inline_js' );


/**
 * Removes "Protected:" text from the title of password protected posts.
 *
 * @since 4.1
 */

function md_remove_protected_title( $title ) {
	return '%s';
}

add_filter( 'private_title_format', 'md_remove_protected_title' );
add_filter( 'protected_title_format', 'md_remove_protected_title' );