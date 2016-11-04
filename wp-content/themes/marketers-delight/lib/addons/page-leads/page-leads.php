<?php
/**
 * Plugin Name: Page Leads
 * Plugin URI: https://marketersdelight.net/page-leads/
 * Description: The official Page Builder of Marketers Delight that let's you create unique content elements on your posts, pages, categories, and custom taxonomy pages.
 * Author: Alex Mangini, Kolakube
 */

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

// Constants

define( 'PAGE_LEADS_DIR', MD_PLUGIN_DIR . 'addons/page-leads/' );
define( 'PAGE_LEADS_URL', MD_PLUGIN_URL . 'addons/page-leads/' );

// Required files

require_once( PAGE_LEADS_DIR . 'lib/admin/admin-page.php' );
require_once( PAGE_LEADS_DIR . 'lib/functions/template-functions.php' );

$leads = apply_filters( 'page_leads_load', array( 'email', 'video', 'funnel', 'table', 'action' ) );

foreach ( $leads as $lead )
	require_once( PAGE_LEADS_DIR . "$lead-lead/$lead-lead.php" );

//print_r( $leads[array_rand($leads)] );


/**
 * Builds the Page Leads data option upon activation.
 *
 * @since 1.0
 */

function page_leads_activate() {
	$data = get_option( 'page_leads' );

	$data['leads'] = array(
		'funnel_lead' => __( 'Funnel Lead', 'md' ),
		'action_lead' => __( 'Action Lead', 'md' ),
		'table_lead'  => __( 'Table Lead', 'md' ),
		'email_lead'  => __( 'Email Lead', 'md' ),
		'video_lead'  => __( 'Video Lead', 'md' )
	);

	$data['positions'] = array(
		'md_hook_after_header'  => __( 'After Header (default)', 'md' ),
		'md_hook_before_html'   => __( 'Before Header', 'md' ),
		'md_hook_content'       => __( 'After Post', 'md' ),
		'md_hook_before_footer' => __( 'Before Footer', 'md' ),
		'md_hook_after_footer'  => __( 'After Footer', 'md' )
	);

	$data['hooks'] = array(
		'md_hook_after_header',
		'md_hook_before_html',
		'md_hook_content',
		'md_hook_before_footer',
		'md_hook_after_footer'
	);

	update_option( 'page_leads', $data );
}

register_activation_hook( __FILE__, 'page_leads_activate' );