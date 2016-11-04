<?php
/**
 * Plugin Name: MD Footnotes
 * Plugin URI: https://marketersdelight.net/footnotes/
 * Description: Add footnotes to your posts with a simple shortcode.
 * Author: Alex Mangini, Kolakube
 */

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants

define( 'MD_FOOTNOTES_DIR', MD_PLUGIN_DIR . 'addons/footnotes/' );
define( 'MD_FOOTNOTES_URL', MD_PLUGIN_URL . 'addons/footnotes/' );

// Include files

require_once( MD_FOOTNOTES_DIR . 'functions.php' );


/**
 * Setup MD Footnotes plugin & build various interface parts.
 *
 * @since 1.0
 */

class md_footnotes extends md_api {

	/**
	 * Start the engine.
	 *
	 * @since 1.0
	 */

	public function construct() {
		$this->meta_box = array(
			'name' => __( 'Footnotes', 'md' )
		);
	}


	/**
	 * Save & sanitize fields.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		return array(
			'after_post' => array(
				'type'    => 'checkbox',
				'options' => array( 'show' )
			),
			'footnotes' => array(
				'type' => 'repeat',
				'repeat_fields' => array(
					'footnote' => array(
						'type' => 'textarea'
					)
				)
			)
		);
	}


	/**
	 * Build admin fields for use in meta box.
	 *
	 * @since 1.0
	 */

	public function fields() { ?>

		<div class="md-footnotes-fields">

			<div class="md-spacer-large">

				<?php $this->field( 'checkbox', 'after_post', array(
					'show' => __( 'Show footnotes list (after post)', 'md' )
				) ); ?>

			</div>

			<?php $this->field( 'repeat', 'footnotes', null, array(
				'callback' => 'footnote_field',
				'add_new'  => __( 'New Footnote', 'md' )
			) ); ?>

		</div>

	<?php }


	/**
	 * Footnote repeater field data.
	 *
	 * @since 1.0
	 */

	public function footnote_field( $repeat ) { ?>

		<?php $this->field( 'textarea', 'footnote', null, array_merge( array( 'rows' => 2 ), $repeat ) ); ?>

		<span class="md-footnote-shortcode">[fn id="<span class="md-footnote-shortcode-count md-repeat-increment"><?php echo $repeat['count']; ?></span>"]</span>

	<?php }

}

$md_footnotes = new md_footnotes;