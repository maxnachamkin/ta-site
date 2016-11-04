<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add Featured Image meta box to taxonomy pages. MD Theme hooks in
 * Featured Image display settings as well.
 *
 * @since 4.3.5
 */

class md_featured_image extends md_api {

	/**
	 * Pseudo constructor to build options.
	 *
	 * @since 4.3.5
	 */

	public function construct() {
		$this->taxonomy = array(
			'name' => __( 'Featured Image', 'md' ),
			'priority' => 45
		);
	}


	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function register_fields() {
		return array(
			'image' => array(
				'type' => 'media'
			)
		);
	}


	/**
	 * Create featured image uploader and set hook for theme to add
	 * presentation options.
	 *
	 * @since 4.3.5
	 */

	public function fields() { ?>

		<table>
			<tbody>
				<tr valign="top">
					<td>
						<p><?php $this->label( 'image', __( 'Set featured image', 'md' ) ); ?></p>
						<p><?php $this->field( 'media', 'image' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php do_action( 'md_featured_image_options' ); ?>

	<?php }

}

$md_featured_image = new md_featured_image;