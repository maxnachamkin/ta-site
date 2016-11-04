<?php
/**
 * Plugin Name: Marketers Delight Plugin
 * Plugin URI: https://marketersdelight.net/
 * Description: Supercharge Marketers Delight with Page Leads, email forms, custom widgets, and other killer functionality.
 * Author: Alex Mangini, Kolakube
 */

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants

define( 'MD_PLUGIN_DIR', MD_DIR . 'lib/' );
define( 'MD_PLUGIN_URL', MD_URL . 'lib/' );

define( 'KOL_URL',        'https://marketersdelight.net' );
define( 'KOL_ACCOUNT',    'https://marketersdelight.net/downloads/' );
define( 'KOL_SUPPORT',    'http://kolakube.net' );
define( 'KOL_GUIDES',     'https://marketersdelight.net/start-here/' );
define( 'KOL_NEWSLETTER', 'https://marketersdelight.net/newsletter/' );
define( 'KOL_AFFILIATES', 'https://marketersdelight.net/affiliates/' );
define( 'MD_SHOWCASE',    'https://marketersdelight.net/showcase/' );

// Core MD files

require_once( MD_PLUGIN_DIR . 'api/api.php' );
require_once( MD_PLUGIN_DIR . 'admin/dashboard.php' );
require_once( MD_PLUGIN_DIR . 'admin/settings.php' );
require_once( MD_PLUGIN_DIR . 'email/email.php' );
require_once( MD_PLUGIN_DIR . 'admin/meta/after-post.php' );

if ( is_admin() )
	require_once( MD_PLUGIN_DIR . 'updater/processes.php' );

// Load addons

if ( md_has( 'page_leads' ) )
	require_once( MD_PLUGIN_DIR . 'addons/page-leads/page-leads.php' );
if ( md_has( 'popups' ) )
	require_once( MD_PLUGIN_DIR . 'addons/popups/popups.php' );
if ( md_has( 'main_menu' ) )
	require_once( MD_PLUGIN_DIR . 'addons/main-menu/main-menu.php' );
if ( md_has( 'footnotes' ) )
	require_once( MD_PLUGIN_DIR . 'addons/footnotes/footnotes.php' );
if ( md_has( 'tracking_scripts' ) )
	require_once( MD_PLUGIN_DIR . 'addons/tracking-scripts.php' );


/**
 * Oh, whatever will I do with you?
 *
 * @since 4.0
 */

final class marketers_delight {

	public function __construct() {
		add_action( 'widgets_init', array( $this, 'widgets' ) );
	}

	public function widgets() {
		require_once( MD_PLUGIN_DIR . 'widgets/content-spotlight.php' );
		require_once( MD_PLUGIN_DIR . 'widgets/text-image.php' );
		require_once( MD_PLUGIN_DIR . 'widgets/quote.php' );

		register_widget( 'md_content_spotlight' );
		register_widget( 'md_text_image' );
		register_widget( 'md_quote_widget' );
	}

}

$md = new marketers_delight;