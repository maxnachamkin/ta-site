<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;


class md_email_fields extends md_api {

	public function fields() {
		$service = isset( $this->email_data['service'] ) ? $this->email_data['service'] : '';
		$screen  = get_current_screen();

		$list       = $this->module_field( 'email_list' );
		$input      = $this->module_field( 'email_input' );
		$input_name = isset( $input['name'] ) ? $input['name'] : '';
	?>

		<?php if ( ! empty( $this->email_data ) ) : ?>

			<!-- List -->

			<?php if ( ! empty( $this->email_data['list_data'] ) ) :
				$lists     = array();
				$lists[''] = __( 'Select a List&hellip;', 'md' );

				foreach ( $this->email_data['list_data'] as $id => $atts )
					$lists[$id] = $atts['name'];

				wp_enqueue_media();
				wp_enqueue_script( 'md-media', MD_PLUGIN_URL . 'media.js', array( 'jquery' ) );
			?>

				<table class="form-table">
					<tbody>

						<tr>
							<th scope="row"><?php $this->label( 'email_list', __( 'Set Default Email List', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'select', 'email_list', $lists ); ?>
							</td>
						</tr>

					</tbody>
				</table>

			<?php endif; ?>

			<div id="<?php echo $this->_id; ?>_email_form_fields" style="display: <?php echo ! empty( $list ) || $service == 'custom_code' ? 'block' : 'none'; ?>;">

				<?php do_action( "{$this->_id}_email_fields_top" ); ?>

				<h3 class="md-meta-h3"><?php _e( 'Email Form Content', 'page-leads' ); ?></h3>

				<!-- Form -->

				<table class="form-table">
					<tbody>

						<!-- Title -->

						<tr>
							<th scope="row"><?php $this->label( 'email_title', __( 'Title', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'text', 'email_title' ); ?>
							</td>
						</tr>

						<!-- Description -->

						<tr>
							<th scope="row"><?php $this->label( 'email_desc', __( 'Description', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'textarea', 'email_desc' ); ?>
							</td>
						</tr>

					</tbody>
				</table>

				<!-- Input Fields -->

				<?php if ( ! empty( $this->email_data['list_data'] ) ) : ?>

					<p class="md-title"><?php _e( 'Email Form Fields', 'md' ); ?></p>

					<table class="form-table md-sep">
						<tbody>

							<!-- Show Name Field -->

							<tr>
								<th scope="row"><?php $this->label( 'email_input', __( 'Show Name Field', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'checkbox', 'email_input', array(
										'name' => __( 'Ask for subscribers name in signup form', 'md' ),
									) ); ?>
								</td>
							</tr>

							<!-- Name Field Label -->

							<tr id="<?php echo $this->_id; ?>_email_name_label" style="display: <?php echo ! empty( $input_name ) ? 'table-row' : 'none'; ?>;">
								<th scope="row"><?php $this->label( 'email_name_label', __( 'Name Field Label', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'text', 'email_name_label', null, array(
										'atts' => array(
											'placeholder' => __( 'Enter your name&hellip;', 'md' )
										)
									) ); ?>
								</td>
							</tr>

							<!-- Email Field Label -->

							<tr>
								<th scope="row"><?php $this->label( 'email_email_label', __( 'Email Field Label', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'text', 'email_email_label', null, array(
										'atts' => array(
											'placeholder' => __( 'Enter your email&hellip;', 'md' )
										)
									) ); ?>
								</td>
							</tr>

							<!-- Submit Button Text -->

							<tr>
								<th scope="row"><?php $this->label( 'email_submit_text', __( 'Submit Button Text', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'text', 'email_submit_text', null, array(
										'atts' => array(
											'placeholder' => __( 'Join Now!', 'md' )
										)
									) ); ?>
								</td>
							</tr>

							<!-- Form Style -->

							<tr>
								<th scope="row"><?php $this->label( 'email_form_style', __( 'Form Style', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'checkbox', 'email_form_style', array(
										'attached' => __( 'Attach input fields to each other in a single line', 'md' )
									) ); ?>
								</td>
							</tr>

							<!-- Email Form Title -->

							<tr>
								<th scope="row"><?php $this->label( 'email_form_title', __( 'Email Form Title', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'text', 'email_form_title' ); ?>
								</td>
							</tr>

							<!-- Email Footer -->

							<tr>
								<th scope="row"><?php $this->label( 'email_form_footer', __( 'Email Form Footer', 'md' ) ); ?></th>

								<td>
									<?php $this->field( 'textarea', 'email_form_footer' ); ?>
								</td>
							</tr>

						</tbody>
					</table>

				<?php endif; ?>

				<!-- Advanced -->

				<h3 class="md-meta-h3"><?php _e( 'Customize Email Form', 'md' ); ?></h3>

				<table class="form-table">
					<tbody>

						<!-- Classes -->

						<tr>
							<th scope="row"><?php $this->label( 'email_classes', __( '<acronym title="Cascading Style Sheets">CSS</acronym> Classes', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'text', 'email_classes', null, array(
									'atts' => array(
										'placeholder' => 'form-full'
									)
								) ); ?>
							</td>
						</tr>

					</tbody>
				</table>

				<?php do_action( "{$this->_id}_email_fields_bottom" ); ?>

			</div>

			<!-- Custom HTML Forms -->

			<?php if ( $service == 'custom_code' ) : ?>

				<table class="form-table">
					<tbody>

						<tr>
							<th scope="row"><?php $this->label( 'email_code', __( '<acronym title="HyperText Markup Language">HTML</acronym> Form Code', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'code', 'email_code' ); ?>
								<?php $this->desc( sprintf( __( 'For best results on formatting your email form code, refer to the <a href="%s" target="_blank">MD style guide</a>.', 'md' ), 'https://kolakube.com/md-style-guide/#custom-email-form-code' ) ); ?>
							</td>
						</tr>

					</tbody>
				</table>

			<?php endif; ?>

		<?php else : ?>

			<?php md_email_connect_notice(); ?>

		<?php endif; ?>

		<?php if ( $service != 'custom_code' && ! empty( $this->email_data['list_data'] ) ) : ?>

			<script>
				( function() {
					document.getElementById( '<?php echo $this->_id; ?>_email_list' ).onchange = function() {
						document.getElementById( '<?php echo $this->_id; ?>_email_form_fields' ).style.display = this.value != '' ? 'block' : 'none';
					}
					document.getElementById( '<?php echo $this->_id; ?>_email_input_name' ).onchange = function() {
						document.getElementById( '<?php echo $this->_id; ?>_email_name_label' ).style.display = this.checked ? 'table-row' : 'none';
					}
				})();
			</script>

		<?php endif; ?>

	<?php }

}