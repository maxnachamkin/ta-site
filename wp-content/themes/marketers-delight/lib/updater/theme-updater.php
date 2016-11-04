<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => MD_THEME_UPDATER_URL, // Site where EDD is hosted
		'item_name'      => MD_THEME_NAME, // Name of theme
		'theme_slug'     => 'marketers-delight', // Theme slug
		'version'        => MD_VERSION, // The current version of this theme
		'author'         => MD_THEME_AUTHOR, // The author of this theme
		'download_id'    => 63289, // Optional, used for generating a license renewal link
		'renew_url'      => '', // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'md' ),
		'enter-key'                 => sprintf( __( 'Enter your <a href="%s" target="_blank">MD license key</a> to enable auto updates.', 'md' ), KOL_ACCOUNT ),
		'license-key'               => __( 'License Key', 'md' ),
		'license-action'            => __( 'License Action', 'md' ),
		'deactivate-license'        => __( 'deactivate', 'md' ),
		'activate-license'          => __( 'activate', 'md' ),
		'renew'                     => __( 'Renew now, save 40%.', 'md' ),
		'unlimited'                 => __( 'unlimited', 'md' ),
		'license-key-is-active'     => __( 'active', 'md' ),
		'expires%s'                 => __( 'Expires: <b>%s</b>.', 'md' ),
		'%1$s/%2$-sites'            => __( 'Active sites: <b>%1$s/%2$s</b>', 'md' ),
		'license-key-expired-%s'    => __( 'Your license key expired on  <b>%s</b>.', 'md' ),
		'license-key-expired'       => __( 'expires', 'md' ),
		'license-keys-do-not-match' => sprintf( __( 'Your license key does not match an active key, please enter an active key from your account. Need help? <a href="%s" target="_blank">Contact support</a>.', 'md' ), KOL_SUPPORT ),
		'license-is-inactive'       => sprintf( __( 'Activate this license key to enable auto-updates. Not activating? <a href="%s" target="_blank">Contact support</a>.', 'md' ), KOL_SUPPORT ),
		'license-key-is-disabled'   => __( 'disabled', 'md' ),
		'site-is-inactive'          => __( 'Activate this license key to enable auto-updates.', 'md' ),
		'license-status-unknown'    => sprintf( __( 'License status unknown. Check internet connection or <a href="%s" target="_blank">contact support</a>', 'md' ), KOL_ACCOUNT ),
		'update-notice'             => __( "Updating MD will lose any customizations you\'ve made to the core files. Be sure to backup any changes to a Child Theme before updating. 'Cancel' to stop, 'OK' to update.", 'md' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'md' ),
	)

);