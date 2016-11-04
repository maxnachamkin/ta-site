<?php

/**
 * Builds the Funnel Lead frontend, and adds settings
 * into the Page Leads interface.
 *
 * @since 1.0
 */

if ( ! class_exists( 'funnel_lead' ) ) :

class funnel_lead extends md_api {

	/**
	 * Build the Funnel Lead.
	 *
	 * @since 1.0
	 */

	public function construct() {
		require_once( 'functions/template-functions.php' );

		$this->suite = 'page_leads';

		$this->admin_tab = array(
			'name' => __( 'Funnel Lead', 'md' )
		);

		$this->meta_box = $this->taxonomy = array(
			'name'   => __( 'Funnel Page Lead', 'md' ),
			'module' => true
		);

		$this->button = new md_button( $this->_id );
	}


	/**
	 * Load Funnel Lead to frontend when needed.
	 *
	 * @since 1.1
	 */

	public function template_redirect() {
		if ( has_funnel_lead() ) {
			$position = funnel_lead_field( 'position' );
			$order    = funnel_lead_field( 'order' );
			$priority = ! empty ( $order ) ? $order : 10;

			add_action( $position, array( $this, 'load_template' ), $priority );
		}
	}


	/**
	 * Loads the template where there's a Funnel Lead. Override this template in
	 * a child theme by creating /templates/funnel-lead.php and copying the
	 * original source from the plugin's template file into the new file.
	 *
	 * @since 1.1
	 */

	public function load_template() {
		$path = 'templates/funnel-lead.php';

		if ( $template = locate_template( $path ) )
			load_template( $template );
		else
			load_template( dirname( __FILE__ ) . "/$path" );
	}


	/**
	 * Register custom admin settings to be saved and sanitized.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		$columns = array();

		foreach ( array( 1, 2, 3 ) as $c ) {
			$columns[] = array_merge( array(
				"col{$c}_image" => array(
					'type' => 'media'
				),
				"col{$c}_headline" => array(
					'type' => 'text'
				),
				"col{$c}_desc" => array(
					'type' => 'textarea'
				)
			), $this->button->register_fields( "col{$c}_" ) );
		}

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
			'headline' => array(
				'type' => 'text',
			),
			'subtitle' => array(
				'type' => 'text'
			),
			'desc' => array(
				'type' => 'textarea',
			)
		), call_user_func_array( 'array_merge', $columns ) );
	}


	/**
	 * Creates admin settings.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$screen = get_current_screen();
		$design = new md_design_options( $this->_id, '#DDDDDD' );
	?>

		<h3 class="md-meta-h3"><?php _e( 'Display Options', 'md' ); ?></h3>

		<table class="form-table">
			<tbody>

				<!-- Position + Order -->

				<tr>
					<th scope="row">
						<?php $this->label( 'position', __( 'Funnel Position', 'md' ) ); ?>
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

		<!-- Design -->

		<?php $design->fields(); ?>

		<hr class="md-hr" />

		<h3 class="md-meta-h3"><?php _e( 'Funnel Content', 'md' ); ?></h3>

		<table class="form-table">
			<tbody>

				<!-- Headline -->

				<tr>

					<th scope="row">
						<?php $this->label( 'headline', __( 'Headline', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', 'headline' ); ?>
					</td>

				</tr>

				<!-- Subtitle -->

				<tr>

					<th scope="row">
						<?php $this->label( 'subtitle', __( 'Subtitle', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', 'subtitle' ); ?>
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

				<!-- Columns -->

				<?php $columns = array(
					1 => __( 'Left Column', 'md' ),
					2 => __( 'Middle Column', 'md' ),
					3 => __( 'Right Column', 'md' )
				); ?>

				<?php foreach ( $columns as $c => $label ) : ?>

					<tr>
						<td colspan="2">

							<div id="poststuff" class="meta-box-sortables postbox-container">
								<div class="postbox closed">
									<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php echo sprintf( __( 'Toggle panel: %s', 'md' ), $label ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
									<h2 class="hndle ui-sortable-handle"><span><?php _e( $label ); ?></span></h2>
									<div class="inside">

										<table class="form-table">
											<tbody>

												<!-- Headline -->

												<tr>
													<th scope="row">
														<?php $this->label( "col{$c}_headline", __( 'Headline', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'text', "col{$c}_headline" ); ?>
													</td>
												</tr>

												<!-- Description -->

												<tr>
													<th scope="row">
														<?php $this->label( "col{$c}_desc", __( 'Description', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'textarea', "col{$c}_desc" ); ?>
													</td>
												</tr>

											</tbody>
										</table>

										<?php $this->button->fields( "col{$c}_" ); ?>

										<table class="form-table">
											<tbody>

												<!-- Image -->

												<tr valign="top">
													<th scope="row">
														<?php $this->label( "col{$c}_image", __( 'Upload an image', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'media', "col{$c}_image" ); ?>
														<?php $this->desc( __( 'Recommended image size: 400x200', 'md' ) ); ?>
													</td>
												</tr>

											</tbody>
										</table>

									</div><!-- .inside -->
								</div>
							</div>

						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php }

}

endif;

$funnel_lead = new funnel_lead;