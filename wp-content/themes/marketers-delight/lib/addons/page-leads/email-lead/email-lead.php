<?php

/**
 * Builds the Email Lead frontend, and adds settings
 * into the Page Leads interface.
 *
 * @since 1.0
 */

if ( ! class_exists( 'email_lead' ) ) :

class email_lead extends md_api {

	/**
	 * Build the Email Lead.
	 *
	 * @since 1.0
	 */

	public function construct() {
		require_once( 'functions/template-functions.php' );

		$this->suite = 'page_leads';

		$this->admin_tab = array(
			'name' => __( 'Email Lead', 'md' )
		);

		$this->meta_box = $this->taxonomy = array(
			'name'   => __( 'Email Page Lead', 'md' ),
			'module' => true
		);

		// add design options to email fields on backend
		add_action( "{$this->_id}_email_fields_top", array( $this, 'add_display_options' ) );
		add_action( "{$this->_id}_design_options_top", array( $this, 'add_to_design_options' ) );

	}


	/**
	 * Load Email Lead to frontend when needed.
	 *
	 * @since 1.1
	 */

	public function template_redirect() {
		if ( has_email_lead() ) {
			$position = email_lead_field( 'position' );
			$order    = email_lead_field( 'order' );
			$priority = ! empty ( $order ) ? $order : 10;

			add_action( $position, array( $this, 'load_template' ), $priority );
		}
	}


	/**
	 * Loads the template where there's a Email Lead. Override this template in
	 * a child theme by creating /templates/email-lead.php and copying the
	 * original source from the plugin's template file into the new file.
	 *
	 * @since 1.1
	 */

	public function load_template() {
		$path = 'templates/email-lead.php';

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
		$email_fields = ! empty( $this->email_data['fields'] ) ? $this->email_data['fields'] : array();

		return array_merge( array(
			'position' => array(
				'type'    => 'select',
				'options' => $this->page_leads['hooks']
			),
			'order' => array(
				'type' => 'number'
			),
			'style' => array(
				'type' => 'checkbox',
				'options' => array( 'left-right' )
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
			'blog' => array(
				'type'    => 'checkbox',
				'options' => array(
					'enable'
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
			)
		), $email_fields );
	}


	/**
	 * Creates admin settings.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$email  = new md_email_fields( $this->_id );
		$screen = get_current_screen();
	?>

		<!-- Email Form -->

		<?php if ( ! empty( $this->email_data ) ) : ?>

			<?php $email->fields(); ?>

		<?php else : ?>

			<?php md_email_connect_notice(); ?>

		<?php endif; ?>

	<?php }


	/**
	 * Adds display options to the top of the Email Fields so
	 * options don't show until after Email List is selected.
	 *
	 * @since 1.2.2
	 */

	public function add_to_design_options() { ?>

		<!-- Layout -->

		<tr>
			<th scope="row">
				<?php $this->label( 'style', __( 'Layout', 'md' ) ); ?>
			</th>

			<td>
				<?php $this->field( 'checkbox', 'style', array(
					'left-right' => __( 'Show title + description on left, email form on right', 'md' )
				) ); ?>
			</td>
		</tr>

	<?php }


	public function add_display_options() {
		$screen = get_current_screen();
		$design = new md_design_options( $this->_id, '#DDDDDD' );
	?>

		<h3 class="md-meta-h3"><?php _e( 'Display Settings', 'md' ); ?></h3>

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

					<!-- Display -->

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

	<?php }

}

endif;

$email_lead = new email_lead;