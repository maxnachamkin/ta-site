<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( MD_PLUGIN_DIR . 'api/fields/design/design-functions.php' );


/**
 * Design Options meta box that can be used to quickly output
 * Design Options into post meta boxes, admin settings pages,
 * and taxonomy settings.
 *
 * See its use in any Page Lead class.
 *
 * @since 4.3
 */

class md_design_options extends md_api {

	public function __construct( $id = null, $bg_color = '', $bg_image = null, $text_color = null ) {
		parent::__construct( $id );

		$this->bg_color   = $bg_color;
		$this->text_color = $text_color;
	}


	public function fields() {
		$screen     = get_current_screen();
		$text_color = $this->text_color == 'white' ?
			array(
				'white' => __( 'White (default)', 'md' ),
				'dark'  => __( 'Dark', 'md' ),
			)
		:
			array(
				'dark'  => __( 'Dark (default)', 'md' ),
				'white' => __( 'White', 'md' )
			);
	?>

		<div id="poststuff" class="meta-box-sortables postbox-container mb-large">
			<div class="postbox closed">
				<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Design Options', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
				<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Design Options', 'md' ); ?></span></h2>
				<div class="inside">

					<table class="form-table">
						<tbody>

							<?php do_action( "{$this->_id}_design_options_top" ); ?>

							<!-- BG Color -->

							<tr>
								<th scope="row">
									<?php $this->label( 'bg_color', __( 'Background Color', 'md' ) ); ?>
								</th>

								<td>
									<?php $this->field( 'color', 'bg_color', null, array(
										'default' => $this->bg_color
									) ); ?>
								</td>
							</tr>

							<!-- BG Image -->

							<tr>
								<th scope="row"><?php $this->label( 'bg_image', __( 'Background Image', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'media', 'bg_image' ); ?>
								</td>
							</tr>

							<!-- Text Color -->

							<tr>
								<th scope="row">
									<?php $this->label( 'text_color_scheme', __( 'Text Color Scheme', 'md' ) ); ?>
								</th>

								<td>
									<?php $this->field( 'select', 'text_color_scheme', $text_color ); ?>
								</td>
							</tr>

							<?php do_action( "{$this->_id}_design_options_bottom" ); ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>

	<?php }

}