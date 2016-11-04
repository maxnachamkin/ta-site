<?php
/**
 * Plugin Name: MD Main Menu
 * Plugin URI: https://marketersdelight.net/main-menu/
 * Description: Display a menu with dropdown navigation, social media icons, a search bar, and email popup to your site.
 * Author: Alex Mangini
*/

if ( ! defined( 'ABSPATH' ) ) exit;


// Constants

define( 'MD_MAIN_MENU_DIR', MD_PLUGIN_DIR . 'addons/main-menu/' );
define( 'MD_MAIN_MENU_URL', MD_PLUGIN_URL . 'addons/main-menu/' );


/**
 * Initializes the Main menu.
 *
 * @since 1.0
 */

class md_main_menu {

	/**
	 * Loads all the important stuff that make this plugin run.
	 *
	 * @since 1.0
	 */

	public function init() {
		require_once( 'admin/meta-box.php' );
		require_once( 'functions/template-functions.php' );

		add_action( 'init', array( $this, 'wp_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'md_hook_after_header', array( $this, 'display' ) );
	}


	/**
	 * Load to WP init.
	 *
	 * @since 1.1
	 */

	public function wp_init() {
		// updater
		$addons = get_option( 'md_addons' );
		if ( ! empty( $addons ) )
			$this->updater();

		// register WP nav menus
		register_nav_menus( array(
			'main'   => __( 'Main Menu', 'md' ),
			'social' => __( 'Social Media Menu', 'md' )
		) );
	}


	/**
	 * Cleans data on 1.1 update.
	 *
	 * @since 1.1
	 */

	public function updater() {
		$old = get_theme_mod( 'md_layout_main_menu_enable' );

		if ( ! empty( $old ) ) {
			set_theme_mod( 'md_main_menu_display', 1 );
			delete_option( 'md_layout_main_menu_enable' );
		}

		delete_option( 'md_addons' );
	}


	/**
	 * Enqueue stylesheet where it's needed.
	 *
	 * @since 1.0
	 */

	public function enqueue() {
		if ( ! md_has_main_menu() )
			return;

		$js  = ! file_exists( get_stylesheet_directory() . '/js/main-menu.js' ) ? MD_MAIN_MENU_URL . 'js/main-menu.js' : get_stylesheet_directory_uri() . '/js/main-menu.js';

		wp_enqueue_script( 'main-menu', $js, array( 'md-apollo' ), '1.0', true );
		wp_localize_script( 'main-menu', 'mdMainMenuHas', array(
			'main'   => has_nav_menu( 'main' ),
			'search' => true,
			'social' => has_nav_menu( 'social' ),
			'popup'  => apply_filters( 'md_filter_main_menu_items', '' ) // be careful
		) );
	}


	/**
	 * Load main menu template file. This template file can
	 * be overwritten in a parent/child theme by recreating
	 * the file structure and copying the code into your theme.
	 *
	 * @since 1.0
	 */

	public function display() {
		if ( ! md_has_main_menu() )
			return;

		$path = 'templates/main-menu.php';

		if ( $template = locate_template( $path ) )
			load_template( $template );
		else
			load_template( dirname( __FILE__ ) . "/$path" );
	}

}

$main_menu = new md_main_menu;
$main_menu->init();