<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;

class md_email_init {

	public $api;
	public $admin;

	public function __construct() {
		require_once( MD_PLUGIN_DIR . 'email/api/api.php' );
		require_once( MD_PLUGIN_DIR . 'email/functions/template-functions.php' );
		require_once( MD_PLUGIN_DIR . 'email/admin/email-fields.php' );
		require_once( MD_PLUGIN_DIR . 'email/admin/admin-page.php' );
		require_once( MD_PLUGIN_DIR . 'email/functions/shortcode.php' );

		$this->api   = new md_email_api;
		$this->email = new md_email;

		add_action( 'widgets_init', array( $this, 'widgets' ) );
	}


	public function widgets() {
		require_once( MD_PLUGIN_DIR . 'email/widgets/email-form.php' );
		register_widget( 'md_email_form' );
	}

}

new md_email_init;