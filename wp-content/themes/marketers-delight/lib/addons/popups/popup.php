<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Fire this class to load popup HTML to the site. You must set
 * the ID as the array $atts['id'] to pass when being called.
 *
 * In MD Popups, this HTML is loaded in 3 different ways:
 *
 * 1. When shortcode is used on site
 * 2. When popup is currently being edited in Customizer
 * 3. Loads popup set in admin settings
 *
 * @since 1.0
 */

class md_popup {

	/**
	 * Ensure ID is set to pass to template, then add popup
	 * template to MD Popups hook area.
	 *
	 * @since 1.0
	 */

	public function __construct( $atts ) {
		if ( empty( $atts['id'] ) )
			return;

		$this->atts = $atts;

		add_action( 'md_popups', array( $this, 'template' ) );
	}


	/**
	 * Load popup template from either plugin or child theme.
	 *
	 * @since 1.0
	 */

	public function template() {
		$path = 'templates/popup.php';
		$atts = $this->atts;

		if ( $template = locate_template( $path ) )
			include( $template );
		else
			include( MD_POPUPS_DIR . $path );
	}

}