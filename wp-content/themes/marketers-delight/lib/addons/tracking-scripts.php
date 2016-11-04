<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Header / Footer Scripts admin fields.
 *
 * @since 4.4.2
 */

class md_scripts extends md_api {

	/**
	 * Pesuedo constructor, adds admin tab to MD page.
	 *
	 * @since 4.4.2
	 */

	public function construct() {
		$this->suite = 'md';

		// add meta box
		$this->name = __( 'Tracking Scripts', 'md' );

		$this->meta_box = array(
			'name' => $this->name
		);

		$this->taxonomy = array(
			'name'     => $this->name,
			'priority' => 48
		);

		// settings
		$this->settings = get_option( 'md_settings' );

		// add head scripts
		add_action( 'wp_head', array( $this, 'wp_head' ) );

		// add footer scripts
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
	}


	/**
	 * Add scripts to header.
	 *
	 * @since 4.4.2
	 */

	function wp_head() {
		// global scripts
		if ( ! empty( $this->settings['header_scripts'] ) )
			echo $this->settings['header_scripts'] . "\n";

		// individual scripts
		$tax_data = md_tax_data( 'md_scripts' );
		$is_tax   = $tax_data['is_tax'];
		$tax      = $tax_data['fields'];

		if ( $is_tax && ! empty( $tax['header_scripts'] ) )
			echo $tax['header_scripts'] . "\n";

		if ( ! empty( $meta ) ) {
			global $wp_query;
			$meta = get_post_meta( $wp_query->get_queried_object_id(), 'md_scripts_header_scripts', true );
			echo "$meta\n";
		}
	}


	/**
	 * Add scripts to footer.
	 *
	 * @since 4.4.2
	 */

	function wp_footer() {
		// global scripts
		if ( ! empty( $this->settings['footer_scripts'] ) )
			echo $this->settings['footer_scripts'] . "\n";

		// individual scripts
		$tax_data = md_tax_data( 'md_scripts' );
		$is_tax   = $tax_data['is_tax'];
		$tax      = $tax_data['fields'];

		if ( $is_tax && ! empty( $tax['footer_scripts'] ) )
			echo $tax['footer_scripts'] . "\n";

		if ( ! empty( $meta ) ) {
			global $wp_query;
			$meta = get_post_meta( $wp_query->get_queried_object_id(), 'md_scripts_footer_scripts', true );
			echo "$meta\n";
		}
	}


	/**
	 * Register settings to save.
	 *
	 * @since 4.4.2
	 */

	public function register_fields() {
		return array(
			'header_scripts' => array(
				'type' => 'code'
			),
			'footer_scripts' => array(
				'type' => 'code'
			)
		);
	}


	/**
	 * Admin page content.
	 *
	 * @since 4.4.2
	 */

	public function fields() { ?>

		<table class="form-table">
			<tbody>

				<!-- Header Scripts -->

				<tr>
					<th scope="row">
						<p><?php $this->label( 'header_scripts', __( 'Header Scripts', 'md' ) ); ?></p>
					</th>

					<td>
						<?php $this->field( 'code', 'header_scripts' ); ?>
						<?php $this->desc( __( 'Print scripts to the <code>&lt;head></code> section (before opening <code>&lt;body></code> tag).', 'md' ) ); ?>
					</td>
				</tr>

				<!-- Footer Scripts -->

				<tr>
					<th scope="row">
						<p><?php $this->label( 'footer_scripts', __( 'Footer Scripts', 'md' ) ); ?></p>
					</th>

					<td>
						<?php $this->field( 'code', 'footer_scripts' ); ?>
						<?php $this->desc( __( 'Print scripts after the <code>&lt;footer></code> section (before closing <code>&lt;/body></code> tag).', 'md' ) ); ?>
					</td>
				</tr>

			</tbody>
		</table>

	<?php }

}

$md_scripts = new md_scripts;