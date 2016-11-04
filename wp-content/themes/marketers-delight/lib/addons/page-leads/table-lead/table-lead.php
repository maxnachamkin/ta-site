<?php

/**
 * Builds the Funnel Lead frontend, and adds settings
 * into the Page Leads interface.
 *
 * @since 1.0
 */

if ( ! class_exists( 'table_lead' ) ) :

class table_lead extends md_api {

	/**
	 * Pesudo constructor adds Table Lead to the Page Leads
	 * suite and creates an admin tab and meta box for settings.
	 *
	 * @since 1.0
	 */

	public function construct() {
		require_once( 'functions/template-functions.php' );
		require_once( 'functions/js.php' );

		$this->suite = 'page_leads';

		$this->admin_tab = array(
			'name' => __( 'Table Lead', 'md' )
		);

		$this->meta_box = $this->taxonomy = array(
			'name'   => __( 'Table Page Lead', 'md' ),
			'module' => true
		);

		$this->button = new md_button( $this->_id );

		add_action( "{$this->_id}_design_options_top", array( $this, 'add_to_design_options' ) );
	}


	/**
	 * Load Table Lead to frontend when needed.
	 *
	 * @since 1.1
	 */

	public function template_redirect() {
		if ( has_table_lead() ) {
			$position = table_lead_field( 'position' );
			$order    = table_lead_field( 'order' );
			$priority = ! empty ( $order ) ? $order : 10;

			add_action( $position, array( $this, 'load_template' ), $priority );
			add_action( 'md_hook_js', 'table_lead_js' );
		}
	}


	/**
	 * Loads the template where there's a Table Lead. Override this template in
	 * a child theme by creating /templates/table-lead.php and copying the
	 * original source from the plugin's template file into the new file.
	 *
	 * @since 1.1
	 */

	public function load_template() {
		$template      = table_lead_field( 'template' );
		$template_name = ! empty( $template ) ? $template : 'featured';
		$path          = "templates/table-lead-$template_name.php";

		if ( $template = locate_template( $path ) )
			load_template( $template );
		else
			load_template( dirname( __FILE__ ) . "/$path" );
	}


	/**
	 * Registers fields to be saved.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		$columns = array();

		foreach ( array( 1, 2, 3 ) as $c ) {
			$columns[] = array_merge( array(
				"col{$c}_listing" => array(
					'type'          => 'repeat',
					'repeat_fields' => array(
						'list_item' => array(
							'type'  => 'text'
						)
					)
				),
				"col{$c}_headline" => array(
					'type' => 'text'
				),
				"col{$c}_subtitle" => array(
					'type' => 'text'
				),
				"col{$c}_price" => array(
					'type' => 'text'
				),
				"col{$c}_price_term" => array(
					'type' => 'text'
				),
				"col{$c}_footnotes" => array(
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
			'template' => array(
				'type' => 'select',
				'options' => array(
					'pro',
					'featured'
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
			'display' => array(
				'type'    => 'checkbox',
				'options' => array(
					'site',
					'blog',
					'posts',
					'pages'
				)
			),
			'headline' => array(
				'type' => 'text',
			),
			'desc' => array(
				'type' => 'textarea',
			),
			'notice_text' => array(
				'type' => 'textarea'
			),
			'show_payment' => array(
				'type'    => 'checkbox',
				'options' => array( 'cards' )
			),
		 ), call_user_func_array( 'array_merge', $columns ) );
	}


	/**
	 * Creates settings in admin tab and meta box.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$screen = get_current_screen();
		$design = new md_design_options( $this->_id, '#FFFFFF' );
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

		<?php $design->fields(); ?>

		<hr class="md-hr" />

		<h3 class="md-meta-h3"><?php _e( 'Lead Content', 'md' ); ?></h3>

		<table class="form-table md-sep">
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
					1 => __( 'Left Table', 'md' ),
					2 => __( 'Middle Table', 'md' ),
					3 => __( 'Right Table', 'md' )
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

												<!-- Title -->

												<tr>
													<th scope="row">
														<?php $this->label( "col{$c}_headline", __( 'Headline', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'text', "col{$c}_headline" ); ?>
													</td>
												</tr>

												<!-- Subtitle -->

												<tr>
													<th scope="row">
														<?php $this->label( "col{$c}_subtitle", __( 'Subtitle', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'text', "col{$c}_subtitle" ); ?>
													</td>
												</tr>

												<!-- Price -->

												<tr>
													<th scope="row">
														<?php $this->label( "col{$c}_price", __( 'Price', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'text', "col{$c}_price", null, array(
															'atts' => array(
																'placeholder' => __( '$20', 'md' ),
																'size'        => 5
															)
														) ); ?>

														<?php $this->field( 'text', "col{$c}_price_term", null, array(
															'atts' => array(
																'placeholder' => __( '/month', 'md' ),
																'size'        => 10
															)
														) ); ?>
													</td>

												</tr>

												<!-- Listing -->

												<tr>
													<th scope="row">
														<?php _e( 'Table Listing', 'md' ); ?>
													</th>

													<td>
														<?php $this->field( 'repeat', "col{$c}_listing", null, array(
															'callback' => 'col_listing'
														) ); ?>
													</td>
												</tr>

											</tbody>
										</table>

										<?php $this->button->fields( "col{$c}_" ); ?>

										<table class="form-table">
											<tbody>

												<!-- Footnotes -->

												<tr>
													<th scope="row">
														<?php $this->label( "col{$c}_footnotes", __( 'Footnotes', 'md' ) ); ?>
													</th>

													<td>
														<?php $this->field( 'textarea', "col{$c}_footnotes" ); ?>
													</td>
												</tr>

											</tbody>
										</table>
									</div>
								</div>
							</div>

						</td>
					</tr>

				<?php endforeach; ?>

				<!-- Show Payment -->

				<tr>
					<th scope="row">
						<?php _e( 'Show payment methods', 'md' ); ?>
					</th>

					<td>
						<?php $this->field( 'checkbox', 'show_payment', array(
							'cards' => __( 'Show credit card / PayPal icons underneath table', 'md' )
						) ); ?>
					</td>
				</tr>

				<!-- Notice Text -->

				<tr>
					<th scope="row">
						<?php $this->label( 'notice_text', __( 'Notice Text', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'textarea', 'notice_text' ); ?>
						<?php $this->desc( __( 'Display a short message underneath the table. For example, a money-back guarantee or a short customer testimonial.', 'md'  ) ); ?>
					</td>
				</tr>

			</tbody>
		</table>

	<?php }


	public function add_to_design_options() { ?>

		<!-- Template -->

		<tr>

			<th scope="row">
				<?php $this->label( 'template', __( 'Template', 'md' ) ); ?>
			</th>

			<td>
				<?php $this->field( 'select', 'template', array(
					'featured' => __( 'Featured (default)', 'md' ),
					'pro'      => __( 'Professional', 'md' ),
				) ); ?>
			</td>

		</tr>

	<?php }


	/**
	 * These settings are repeated and called in $this->fields().
	 *
	 * @since 1.0
	 */

	public function col_listing( $repeat ) { ?>

		<?php $this->field( 'text', 'list_item', null, $repeat ); ?>

	<?php }

}

endif;

$table_lead = new table_lead;