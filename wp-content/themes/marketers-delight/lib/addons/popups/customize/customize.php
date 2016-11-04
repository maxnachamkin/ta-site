<?php

/**
 * Builds Customizer Panels + Settings for each popup
 * using the option ID created on the Customizer redirect
 * screen.
 *
 * @since 1.0
 */

class md_popups_customize {

	/**
	 * Get Edit options array and set up/load what's needed
	 * for Customizer experience.
	 *
	 * @since 1.0
	 */

	public function __construct() {
		$this->edit          = get_option( 'md_popups_edit' );
		$this->customizer_id = 'md_popups_' . $this->edit['id'];

		add_action( 'customize_register', array( $this, 'sections' ) );
		add_action( 'wp_head', array( $this, 'customize_css' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_js' ) );
	}


	/**
	 * Prevent the popup from being closed on Customizer.
	 *
	 * @since 1.0
	 */

	public function customize_css() { ?>

		<style type="text/css">
			.display-none {
				display: none !important;
			}
		</style>

	<?php }


	/**
	 * Load Customizer JS for live preview.
	 *
	 * @since 1.0
	 */

	public function customize_js() {
		$id   = $this->edit['id'];
		$data = md_popup_fields( $id );

		wp_enqueue_script( 'md-popups-customizer', MD_POPUPS_URL . 'customize/customize.js', array( 'jquery', 'customize-preview' ), '1.0', true );
		wp_localize_script( 'md-popups-customizer', 'mdPopupPreview', array(
			'id'      => $id,
			'content' => md_popup_content( $id )
		) );
	}


	/**
	 * A giant mess of the Customizer popups settings.
	 *
	 * @since 1.0
	 */

	public function sections( $wp_customize ) {
		$sanitize = new md_sanitize;


		/**
		 * Popups Panel
		 */

		$wp_customize->add_panel( $this->customizer_id, array(
			'title'         => __( $this->edit['name'], 'md' ),
			'description'   => sprintf( __( 'To use this popup on your site, either place the <code>[md_popup]</code> shortcode to create a 2-step process or automatically display it with the settings on the Main Popups screen or post editor. <a href="%s" target="_blank">Read more&rarr;</a>', 'md' ), 'https://marketersdelight.net/popups/getting-started/' )
		) );


		/**
		 * Popup Content
		 */

		$wp_customize->add_section( "{$this->customizer_id}_content", array(
			'title' => __( 'Headline, Text, Button', 'md' ),
			'panel' => $this->customizer_id
		) );


		// Show Text

		$wp_customize->add_setting( "{$this->customizer_id}_show_text", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( $sanitize, 'checkbox' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_show_text", array(
			'type'    => 'checkbox',
			'label'   => __( 'Enable Headline + Text Area', 'md' ),
			'section' => "{$this->customizer_id}_content",
		) );


		// Headline

		$wp_customize->add_setting( "{$this->customizer_id}_headline", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( $sanitize, 'text' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_headline", array(
			'type'    => 'text',
			'label'   => __( 'Headline', 'md' ),
			'section' => "{$this->customizer_id}_content"
		) );


		// Description

		$wp_customize->add_setting( "{$this->customizer_id}_description", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( $sanitize, 'text' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_description", array(
			'type'    => 'textarea',
			'label'   => __( 'Description', 'md' ),
			'section' => "{$this->customizer_id}_content"
		) );


		// Bullets

		$wp_customize->add_setting( "{$this->customizer_id}_bullets", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( $sanitize, 'text' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_bullets", array(
			'type'        => 'textarea',
			'label'       => __( 'Bullet Points', 'md' ),
			'description' => sprintf( __( 'Refer to the <a href="%s" target="_blank">MD Style Guide</a> for formatting tips.', 'md' ), 'https://kolakube.com/md-style-guide/#lists' ),
			'section'     => "{$this->customizer_id}_content"
		) );


		// Button Text

		$wp_customize->add_setting( "{$this->customizer_id}_button_text", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_html'
		) );

		$wp_customize->add_control( "{$this->customizer_id}_button_text", array(
			'type'    => 'text',
			'label'   => __( 'Button Text', 'md' ),
			'section' => "{$this->customizer_id}_content"
		) );


		// Button URL

		$wp_customize->add_setting( "{$this->customizer_id}_button_url", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url'
		) );

		$wp_customize->add_control( "{$this->customizer_id}_button_url", array(
			'type'    => 'text',
			'label'   => __( 'Button URL', 'md' ),
			'section' => "{$this->customizer_id}_content"
		) );


		/**
		 * Popup Image
		 */

		$wp_customize->add_section( "{$this->customizer_id}_featured_image", array(
			'title' => __( 'Featured Image', 'md' ),
			'panel' => $this->customizer_id
		) );


		// Image

		$wp_customize->add_setting( "{$this->customizer_id}_image", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage'
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "{$this->customizer_id}_image", array(
		    'label'       => __( 'Add/Edit Your Featured Image', 'md' ),
		    'section' => "{$this->customizer_id}_featured_image"
		) ) );


		// Image Wrap

		$wp_customize->add_setting( "{$this->customizer_id}_image_wrap", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage',
			'sanitize_callback' => array( $sanitize, 'checkbox' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_image_wrap", array(
			'type'    => 'checkbox',
			'label'   => __( 'Wrap image to edge of popup', 'md' ),
			'section' => "{$this->customizer_id}_featured_image",
		) );



		/**
		 * Popup Email Form
		 */

		$email_data = get_option( 'md_email_data' );

		$wp_customize->add_section( "{$this->customizer_id}_email", array(
			'title' => __( 'Email Form', 'md' ),
			'panel' => $this->customizer_id
		) );


		// Integrated Form

		if ( ! empty( $email_data ) && $email_data['service'] != 'custom_code' ) {
			$lists     = array();
			$lists[''] = __( 'Select a List&hellip;', 'md' );

			foreach( $email_data['list_data'] as $id => $atts )
				$lists[$id] = $atts['name'];

			// Select List

			$wp_customize->add_setting( "{$this->customizer_id}_email_list", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $sanitize, 'email_list' )
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_list", array(
				'type' => 'select',
				'label' => __( 'Email List', 'md' ),
				'section' => "{$this->customizer_id}_email",
				'choices' => $lists
			) );

		}

		// Email Title

		$wp_customize->add_setting( "{$this->customizer_id}_email_title", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => array( $sanitize, 'text' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_email_title", array(
			'type'    => 'textarea',
			'label'   => __( 'Email Title', 'md' ),
			'section' => "{$this->customizer_id}_email"
		) );


		// Email Footer

		$wp_customize->add_setting( "{$this->customizer_id}_email_footer", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => array( $sanitize, 'text' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_email_footer", array(
			'type'    => 'textarea',
			'label'   => __( 'Email Footer', 'md' ),
			'section' => "{$this->customizer_id}_email"
		) );

		// Integrated Form

		if ( ! empty( $email_data ) && $email_data['service'] != 'custom_code' ) {
			// Show Name Field

			$wp_customize->add_setting( "{$this->customizer_id}_email_show_name", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $sanitize, 'checkbox' )
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_show_name", array(
				'type'    => 'checkbox',
				'label'   => __( 'Ask for subscribers name in signup form', 'md' ),
				'section' => "{$this->customizer_id}_email",
			) );


			// Name Label

			$wp_customize->add_setting( "{$this->customizer_id}_email_name_label", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_attr'
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_name_label", array(
				'type'    => 'text',
				'label'   => __( 'Name Field Label', 'md' ),
				'section' => "{$this->customizer_id}_email",
				'input_attrs' => array(
					'placeholder' => __( 'Enter your name&hellip;', 'md' )
				)
			) );


			// Email Label

			$wp_customize->add_setting( "{$this->customizer_id}_email_email_label", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
					'sanitize_callback' => 'esc_attr'
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_email_label", array(
				'type'    => 'text',
				'label'   => __( 'Email Field Label', 'md' ),
				'section' => "{$this->customizer_id}_email",
				'input_attrs' => array(
					'placeholder' => __( 'Enter your email&hellip;', 'md' )
				)
			) );


			// Submit Label

			$wp_customize->add_setting( "{$this->customizer_id}_email_submit_label", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_attr'
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_submit_label", array(
				'type'    => 'text',
				'label'   => __( 'Submit Button Label', 'md' ),
				'section' => "{$this->customizer_id}_email",
				'input_attrs' => array(
					'placeholder' => __( 'Join Now!', 'md' )
				)
			) );


			// Form Style

			$wp_customize->add_setting( "{$this->customizer_id}_email_form_attached", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $sanitize, 'checkbox' )
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_form_attached", array(
				'type'    => 'checkbox',
				'label'   => __( 'Attach input fields to each other', 'md' ),
				'section' => "{$this->customizer_id}_email"
			) );
		}

		// Custom Code

		if ( ! empty( $email_data ) && $email_data['service'] == 'custom_code' ) {
			$wp_customize->add_setting( "{$this->customizer_id}_email_code", array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options'
			) );

			$wp_customize->add_control( "{$this->customizer_id}_email_code", array(
				'type'    => 'textarea',
				'label'   => __( 'Custom HTML Form Code', 'md' ),
				'description' => sprintf( __( 'For best results on formatting your email form code, refer to the <a href="%s" target="_blank">MD style guide</a>.', 'md' ), 'https://kolakube.com/md-style-guide/#custom-email-form-code' ),
				'section' => "{$this->customizer_id}_email"
			) );
		}


		// Connect Notice

		if ( empty( $email_data ) ) {
			$wp_customize->add_setting( "{$this->customizer_id}_email_connect", array(
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			) );

			$wp_customize->add_control( new MD_Email_Connect_Notice( $wp_customize, "{$this->customizer_id}_email_connect", array(
				'section' => "{$this->customizer_id}_email",
			) ) );
		}


		/**
		 * Custom Template
		 */

		$wp_customize->add_section( "{$this->customizer_id}_custom_template", array(
			'title' => __( 'Custom Template', 'md' ),
			'panel' => $this->customizer_id
		) );


		// Show Custom Template

		$wp_customize->add_setting( "{$this->customizer_id}_show_custom_template", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => array( $sanitize, 'checkbox' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_show_custom_template", array(
			'type'    => 'checkbox',
			'label'   => __( 'Override popup with custom template', 'md' ),
			'section' => "{$this->customizer_id}_custom_template",
		) );


		// Template

		$wp_customize->add_setting( "{$this->customizer_id}_custom_template", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage'
		) );

		$wp_customize->add_control( new MD_Customize_Control_Textarea( $wp_customize, "{$this->customizer_id}_custom_template", array(
			'type'    => 'textarea',
			'label'   => __( 'Custom Template', 'md' ),
			'description' => sprintf( __( '<p>Use HTML to build a custom popup template that formats spacing, columns, and even <code>[shortcodes]</code> from MD helper classes.<p><b>LEARN MORE:</b> <a href="%s" target="_blank">MD Style Guide</a></p><p><b>GET TEMPLATES:</b> <a href="%2s" target="_blank">Free Popups Templates</a></p><p><b>VIDEO EMBEDS:</b> <a href="%3s" target="_blank">Read more</a></p>', 'md' ), 'https://kolakube.com/md-style-guide/', 'https://kolakube.com/md-popups/templates/', 'https://kolakube.com/md-popups/video-template/' ),
			'section' => "{$this->customizer_id}_custom_template"
		) ) );


		// Template CSS

		$wp_customize->add_setting( "{$this->customizer_id}_custom_css", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => array( $sanitize, 'text' )
		) );

		$wp_customize->add_control( new MD_Customize_Control_Textarea( $wp_customize, "{$this->customizer_id}_custom_css", array(
			'type'        => 'textarea',
			'label'       => __( 'Custom CSS', 'md' ),
			'description' => sprintf( __( 'Use:<br /><code>#md_popup_%s</code><br />&hellip;to target this popup with CSS.', 'md' ), $this->edit['id'] ),
			'section'     => "{$this->customizer_id}_custom_template"
		) ) );


		/**
		 * Popup Design
		 */

		$wp_customize->add_section( "{$this->customizer_id}_design", array(
			'title' => __( 'Design Options', 'md' ),
			'panel' => $this->customizer_id
		) );


		// Text Color

		$wp_customize->add_setting( "{$this->customizer_id}_text_color", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage',
			'default'    => '#1e1e1e'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "{$this->customizer_id}_text_color", array(
			'label'      => __( 'Text Color', 'md' ),
			'section'    => "{$this->customizer_id}_design"
		) ) );


		// Link Color

		$wp_customize->add_setting( "{$this->customizer_id}_link_color", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage',
			'default'    => '#ae2525'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "{$this->customizer_id}_link_color", array(
			'label'      => __( 'Link Color', 'md' ),
			'section'    => "{$this->customizer_id}_design"
		) ) );


		// Button color

		$wp_customize->add_setting( "{$this->customizer_id}_button_color", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage',
			'default'    => '#22A340'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "{$this->customizer_id}_button_color", array(
			'label'      => __( 'Button Color', 'md' ),
			'section'    => "{$this->customizer_id}_design"
		) ) );


		// Close (x) color

		$wp_customize->add_setting( "{$this->customizer_id}_close_color", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage',
			'default'    => '#fff'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "{$this->customizer_id}_close_color", array(
			'label'      => __( 'Close (&times;) Color', 'md' ),
			'section'    => "{$this->customizer_id}_design"
		) ) );


		// Secondary BG color

		$wp_customize->add_setting( "{$this->customizer_id}_secondary_color", array(
			'default'     => 'rgba(10, 0, 0, 0.1)',
			'type'        => 'option',
			'capability'  => 'edit_theme_options',
			'transport'   => 'postMessage'
		) );

		$wp_customize->add_control( new MD_Alpha_Color_Control( $wp_customize, "{$this->customizer_id}_secondary_color", array(
			'label'        => __( 'Secondary Background Color', 'md' ),
			'section'      => "{$this->customizer_id}_design",
			'show_opacity' => true
		) ) );


		// BG Color

		$wp_customize->add_setting( "{$this->customizer_id}_bg_color", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options',
			'transport'  => 'postMessage',
			'default'    => '#ddd'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "{$this->customizer_id}_bg_color", array(
			'label'      => __( 'Popup Background Color', 'md' ),
			'section'    => "{$this->customizer_id}_design"
		) ) );


		// BG Image

		$wp_customize->add_setting( "{$this->customizer_id}_bg_image", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options'
		) );

		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, "{$this->customizer_id}_bg_image", array(
		    'section'     => "{$this->customizer_id}_design",
		    'label'       => __( 'Popup Background Image', 'md' ),
		    'flex_width'  => false,
		    'flex_height' => false,
		    'width'       => 1500,
		    'height'      => 750
		) ) );


		// BG Position Center

		$wp_customize->add_setting( "{$this->customizer_id}_bg_position_center", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => array( $sanitize, 'checkbox' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_bg_position_center", array(
			'type'    => 'checkbox',
			'label'   => __( 'Background Image Position: Center', 'md' ),
			'section' => "{$this->customizer_id}_design",
		) );


		// Full-Width

		$wp_customize->add_setting( "{$this->customizer_id}_full_width", array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( $sanitize, 'checkbox' )
		) );

		$wp_customize->add_control( "{$this->customizer_id}_full_width", array(
			'type'    => 'checkbox',
			'label'   => __( 'Make columns 100% full-width', 'md' ),
			'section' => "{$this->customizer_id}_design"
		) );


		// CSS Classes

		$wp_customize->add_setting( "{$this->customizer_id}_classes", array(
			'type'       => 'option',
			'capability' => 'edit_theme_options'
		) );

		$wp_customize->add_control( "{$this->customizer_id}_classes", array(
			'type'    => 'text',
			'label'   => __( 'CSS Classes', 'md' ),
			'section' => "{$this->customizer_id}_design"
		) );

	}

}