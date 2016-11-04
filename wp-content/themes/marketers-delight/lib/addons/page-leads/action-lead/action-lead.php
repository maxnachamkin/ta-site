<?php

/**
 * Builds the Action Lead frontend, and adds settings
 * into the Page Leads interface.
 *
 * @since 4.0
 */

if ( ! class_exists( 'action_lead' ) ) :

class action_lead extends md_api {

	/**
	 * Build the Action Lead.
	 *
	 * @since 4.0
	 */

	public function construct() {
		require_once( 'functions/template-functions.php' );

		$this->suite = 'page_leads';

		$this->admin_tab = array(
			'name' => __( 'Action Lead', 'md' )
		);

		$this->meta_box = $this->taxonomy = array(
			'name'   => __( 'Action Page Lead', 'md' ),
			'module' => true
		);

		$this->button = new md_button( $this->_id );
	}


	/**
	 * Load Action Lead to frontend when needed.
	 *
	 * @since 4.1
	 */

	public function template_redirect() {

		if ( has_action_lead() ) {
			$position = action_lead_field( 'position' );
			$order    = action_lead_field( 'order' );
			$priority = ! empty ( $order ) ? $order : 10;

			add_action( $position, array( $this, 'load_template' ), $priority );
		}

	}


	/**
	 * Loads the template where there's a Action Lead. Override this template in
	 * a child theme by creating /templates/action-lead.php and copying the
	 * original source from the plugin's template file into the new file.
	 *
	 * @since 4.1
	 */

	public function load_template() {
		$path = 'templates/action-lead.php';

		if ( $template = locate_template( $path ) )
			load_template( $template );
		else
			load_template( dirname( __FILE__ ) . "/$path" );
	}


	/**
	 * Register custom admin settings to be saved and sanitized.
	 *
	 * @since 4.0
	 */

	public function register_fields() {
		$button = $this->button->register_fields();
		$email  = ! empty( $this->email_data['fields'] ) ? $this->email_data['fields'] : array();

		return array_merge( array(
			'position' => array(
				'type'    => 'select',
				'options' => $this->page_leads['hooks']
			),
			'order' => array(
				'type' => 'number'
			),
			'display' => array(
				'type'    => 'checkbox',
				'options' => array(
					'site',
					'blog',
					'posts',
					'pages'
				)
			),
			'bg_color' => array(
				'type' => 'color'
			),
			'bg_image' => array(
				'type' => 'image'
			),
			'text_color_scheme' => array(
				'type' => 'select',
				'options' => array(
					'dark',
					'white'
				)
			),
			'subtitle' => array(
				'type' => 'text'
			),
			'headline' => array(
				'type' => 'text',
			),
			'desc' => array(
				'type' => 'textarea',
			),
			'type' => array(
				'type'    => 'select',
				'options' => array(
					'button',
					'button_simple',
					'email'
				)
			),
			'title' => array(
				'type' => 'text'
			),
			'col_subtitle' => array(
				'type' => 'text'
			),
			'button_style' => array(
				'type'    => 'checkbox',
				'options' => array(
					'left_right'
				)
			),
			'checkmarks' => array(
				'type'          => 'repeat',
				'repeat_fields' => array(
					'item' => array(
						'type'  => 'text'
					)
				)
			)
		 ), $email, $button );
	}


	/**
	 * Print admin footer scripts to meta box page only.
	 *
	 * @since 4.0
	 */

	public function admin_print_footer_scripts() { ?>

		<script>
			( function() {
				document.getElementById( '<?php echo $this->_id; ?>_type' ).onchange = function() {
					document.getElementById( '<?php echo $this->_id; ?>_action_buttons' ).style.display = this.value == 'button_simple' || this.value == 'button' ? 'block' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_action_button' ).style.display = this.value == 'button_simple' ? 'block' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_action_button_box' ).style.display = this.value == 'button' ? 'block' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_action_email' ).style.display  = this.value == 'email' ? 'block' : 'none';
				}
			})();
		</script>

	<?php }


	/**
	 * Creates admin settings.
	 *
	 * @since 4.0
	 */

	public function fields() {
		$screen = get_current_screen();
		$type   = $this->module_field( 'type' );

		$email  = new md_email_fields( $this->_id );
		$design = new md_design_options( $this->_id, '#0094F0', null, 'white' );
	?>

		<h3 class="md-meta-h3"><?php _e( 'Display Options', 'md' ); ?></h3>

		<table class="form-table">
			<tbody>

				<!-- Position + Order -->

				<tr>
					<th scope="row">
						<?php $this->label( 'position', __( 'Position', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'select', 'position', $this->page_leads['positions' ] ); ?>

						<?php $this->field( 'number', 'order', null, array(
							'atts' => array(
								'placeholder' => '10'
							)
						) ); ?>

						<?php $this->desc( __( 'Reorder any Page Leads you add to the same position above or below each other by setting higher/lower numbers.', 'md' ) ); ?>
					</td>
				</tr>

				<?php if ( $screen->base == 'appearance_page_page_leads' ) : ?>

					<!-- Display Settings -->

					<tr>

						<th scope="row">
							<?php $this->label( 'display', __( 'Display', 'md' ) ); ?>
						</th>

						<td>
							<?php $this->field( 'checkbox', 'display', array(
								'site'  => __( 'Show sitewide', 'md' )
							) ); ?>

							<div id="<?php echo $this->_id; ?>_display_conditional" style="display: <?php echo empty( $this->_get_option['display']['site'] ) ? 'block' : 'none'; ?>">
								<?php $this->field( 'checkbox', 'display', array(
									'blog'  => __( 'Show on blog posts page', 'md' ),
									'posts' => __( 'Show on all posts', 'md' ),
									'pages' => __( 'Show on all pages', 'md' )
								) ); ?>
								<?php $this->desc( __( 'You can add custom Page Leads by editing any post, page, or category.', 'md' ) ); ?>
							</div>

							<script>
								( function() {
									document.getElementById( '<?php echo $this->_id; ?>_display_site' ).onchange = function() {
										document.getElementById( '<?php echo $this->_id; ?>_display_conditional' ).style.display = this.checked == '' ? 'block' : 'none';
									}
								})();
							</script>

						</td>

					</tr>

				<?php endif; ?>

			</tbody>
		</table>

		<!-- Design Options -->

		<div class="md-sep-large">
			<?php echo $design->fields(); ?>
		</div>

		<h3 class="md-meta-h3"><?php _e( 'Lead Content', 'md' ); ?></h3>

		<table class="form-table">
			<tbody>

				<!-- Subtitle -->

				<tr>
					<th scope="row">
						<?php $this->label( 'subtitle', __( 'Subtitle', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', 'subtitle' ); ?>
					</td>
				</tr>

				<!-- Headline -->

				<tr>

					<th scope="row">
						<?php $this->label( 'headline', __( 'Headline', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', 'headline' ); ?>
					</td>

				</tr>

				<!-- Description -->

				<tr>

					<th scope="row">
						<?php $this->label( 'desc', __( 'Description', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'textarea', 'desc' ); ?>
					</td>

				</tr>

			</tbody>
		</table>

		<table class="form-table">
			<tbody>

				<!-- Action Type -->

				<tr>
					<th scope="row">
						<?php $this->label( 'type', __( 'Action Type', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'select', 'type', array(
							''              => __( 'Select an action&hellip;', 'md' ),
							'button_simple' => __( 'Button', 'md' ),
							'button'        => __( 'Button Box', 'md' ),
							'email'         => __( 'Email Form', 'md' ),
						) ); ?>
					</td>
				</tr>

			</tbody>
		</table>

		<!-- Buttons -->

		<div id="<?php echo $this->_id; ?>_action_buttons" style="display: <?php echo ( $type == 'button_simple' || $type == 'button' ? 'block' : 'none' ); ?>;">

			<!-- Button (simple) -->

			<div id="<?php echo $this->_id; ?>_action_button" style="display: <?php echo ( $type == 'button_simple' ? 'block' : 'none' ); ?>;">

				<table class="form-table">
					<tbody>

						<!-- Button Style -->

						<tr>

							<th scope="row">
								<?php $this->label( 'button_style', __( 'Button Style', 'md' ) ); ?>
							</th>

							<td>
								<?php $this->field( 'checkbox', 'button_style', array(
									'left_right' => __( 'Show title + description on left, button on right', 'md' )
								) ); ?>
							</td>

						</tr>

					</tbody>
				</table>

			</div>

			<!-- Button Box -->

			<div id="<?php echo $this->_id; ?>_action_button_box" style="display: <?php echo ( $type == 'button' ? 'block' : 'none' ); ?>;">

				<table class="form-table">
					<tbody>

						<!-- Title -->

						<tr>
							<th scope="row">
								<?php $this->label( 'title', __( 'Title', 'md' ) ); ?>
							</th>

							<td>
								<?php $this->field( 'text', 'title' ); ?>
							</td>
						</tr>

						<!-- Subtitle -->

						<tr>
							<th scope="row">
								<?php $this->label( 'col_subtitle', __( 'Subtitle', 'md' ) ); ?>
							</th>

							<td>
								<?php $this->field( 'text', 'col_subtitle' ); ?>
							</td>
						</tr>

						<!-- Checkmarks -->

						<tr>
							<th scope="row">
								<?php _e( 'Checkmarks', 'md' ); ?>
							</th>

							<td>

								<?php $this->desc( 'Write a short bullet point list about what\'s included by joining/doing X. Each bullet will have a green checkmark next to it.', 'md' ); ?><br />

								<?php $this->field( 'repeat', 'checkmarks', null, array(
									'callback' => 'checkmarks'
								) ); ?>

							</td>
						</tr>

					</tbody>
				</table>

			</div>

			<!-- Button -->

			<?php $this->button->fields(); ?>

		</div>

		<!-- Email Form Action -->

		<div id="<?php echo $this->_id; ?>_action_email" class="form-table" style="display: <?php echo ( $type == 'email' ? 'block' : 'none' ); ?>;">

			<?php $email->fields(); ?>

		</div>

	<?php }


	/**
	 * These settings are repeated and called in fields().
	 *
	 * @since 4.0
	 */

	public function checkmarks( $repeat ) { ?>

		<?php $this->field( 'text', 'item', null, $repeat ); ?>

	<?php }

}

endif;

$action_lead = new action_lead;