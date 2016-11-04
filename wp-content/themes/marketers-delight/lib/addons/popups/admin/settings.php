<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add admin tab for Settings panel.
 *
 * @since 4.5
 */

class md_popups_settings extends md_api {

	/**
	 * Build MD Popups Placement admin page.
	 *
	 * @since 4.5
	 */

	public function construct() {
		$this->suite = 'md_popups';

		$this->admin_tab = array(
			'name' => __( 'Settings', 'md' ),
			'save' => false
		);

		$this->main = new md_popups_fields( $this->_id );
	}


	public function register_fields() {
		return array_merge( array(
			'cookie' => array(
				'type' => 'number'
			),
			'display' => array(
				'type'    => 'checkbox',
				'options' => array(
					'site',
					'blog',
					'posts',
					'pages',
					'categories'
				)
			)
		), $this->main->register_fields() );
	}


	public function fields() {
		$popups = get_option( 'md_popups' );

		// set popup options
		foreach ( $popups['popups'] as $popup => $fields )
			$options[$fields['id']] = $fields['name'];
		$options = array_merge( array( '' => __( 'Select a popup&hellip;', 'md' ) ), $options );

		// byline
		$byline_text         = $this->module_field( 'byline' );
		$byline_text_display = ! empty( $byline_text ) ? 'table-row' : 'none';
	?>

		<div class="md-content-wrap">

			<!-- Default Popup -->

			<div id="poststuff" class="meta-box-sortables postbox-container">
				<div class="postbox closed">
					<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Set default popup', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Set Default Popup', 'md' ); ?></span></h2>
					<div class="inside">

						<?php if ( ! empty( $popups ) ) : ?>
							<?php $this->main->fields(); ?>
						<?php endif; ?>

					</div>
				</div>
			</div>

			<!-- 2-steps -->

			<div id="poststuff" class="meta-box-sortables postbox-container">
				<div class="postbox closed">
					<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: 2-steps', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					<h2 class="hndle ui-sortable-handle"><span><?php _e( '2-Step Popups', 'md' ); ?></span></h2>
					<div class="inside">

						<?php $this->desc( __( '2-step popups are triggered when a user clicks any text link, image, or button. You can quickly add 2-step popups around your site by setting any of the options below.', 'md' ) ); ?>

						<table class="form-table">
							<tbody>

								<!-- Main Menu -->

								<tr>
									<th valign="top"><?php $this->label( 'main_menu', __( 'Main Menu', 'md' ) ); ?></th>

									<td>
										<?php $this->field( 'select', 'main_menu', $options ); ?>
										<?php $this->desc( __( 'Add an email icon to the Main Menu that opens a popup when clicked.', 'md' ) ); ?>
									</td>
								</tr>

								<!-- Byline -->

								<tr>
									<th valign="top"><?php $this->label( 'byline', __( 'Byline', 'md' ) ); ?></th>

									<td>
										<?php $this->field( 'select', 'byline', $options ); ?>
										<?php $this->desc( __( 'Add a link to blog post bylines that open a popup when clicked.', 'md' ) ); ?>
									</td>
								</tr>

								<!-- Byline Text -->

								<tr id="<?php echo $this->_id; ?>_byline_text_row" style="display: <?php echo $byline_text_display; ?>">
									<th valign="top"><?php $this->label( 'byline_text', __( 'Byline Text', 'md' ) ); ?></th>

									<td>
										<?php $this->field( 'text', 'byline_text', null, array(
											'atts' => array(
												'placeholder' => __( 'Get email updates', 'md' )
											)
										) ); ?>
									</td>
								</tr>

							</tbody>
						</table>

					</div>
				</div>
			</div>

			<?php submit_button(); ?>

		</div>

	<?php }


	public function admin_print_footer_scripts() { ?>

		<script>
			( function() {
				document.getElementById( '<?php echo $this->_id; ?>_byline' ).onchange = function() {
					document.getElementById( '<?php echo $this->_id; ?>_byline_text_row' ).style.display = this.value != '' ? 'table-row' : 'none';
				}
			})();
		</script>

	<?php }

}

$md_popups_settings = new md_popups_settings;