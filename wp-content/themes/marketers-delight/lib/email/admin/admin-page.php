<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;


class md_email extends md_api {

	/**
	 * Pesuedo constructor, registers the admin page.
	 *
	 * @since 4.1
	 */

	public function construct() {
		$this->suite = $this->_id;

		$this->admin_page = array(
			'parent_slug' => 'themes.php',
			'name'        => __( 'Email Forms', 'md' ),
			'hide_menu'   => true
		);

		add_action( 'md_hook_panel_tab', array( $this, 'panel_tab' ), 20 );
	}


	/**
	 * Add Email Forms panel tab.
	 *
	 * @since 4.5
	 */

	public function panel_tab() {
		$screen = get_current_screen();
	?>

		<a href="<?php echo admin_url( "themes.php?page={$this->_id}" ); ?>" class="nav-tab<?php echo ! empty( $screen->base ) && $screen->base == 'appearance_page_md_email' || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? ' nav-tab-active' : ''; ?>"><?php _e( 'Email Forms', 'md' ); ?></a>

	<?php }


	/**
	 * Registers fields so we can properly save them.
	 *
	 * @since 4.1
	 */

	public function register_fields() {
		$email_fields = ! empty( $this->email_data['fields'] ) ? $this->email_data['fields'] : array();

		return array_merge( array(
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
			'email_after_post' => array(
				'type'    => 'checkbox',
				'options' => array(
					'display_posts'
				)
			)
		), $email_fields );
	}


	/**
	 * Load admin scripts on page.
	 *
	 * @since 4.1
	 */

	public function admin_page_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'md-media' );
	}


	/**
	 * Build out the admin page and include fields.
	 *
	 * @since 4.1
	 */

	public function admin_page() {
		$email = get_option( 'md_email' );
		$data  = get_option( 'md_email_data' );

		$lists   = isset( $data['list_data'] ) ? $data['list_data'] : '';
		$service = isset( $data['service'] ) ? $data['service'] : '';

		$email_form = new md_email_fields( $this->_id );

		$custom = $service == 'custom_code' ? true : false;

		// hook custom settings into email fields
		add_action( $this->_id . '_email_fields_top', array( $this, 'admin_settings' ) );
	?>

		<div id="md-email" class="wrap md">

			<?php $this->admin_header(); ?>

			<div class="md-content-wrap">

				<form id="md-email-form" method="post" action="options.php">

					<?php settings_fields( $this->suite ); ?>

					<?php if ( empty( $service ) ) : ?>

						<div class="md-email-setup" style="display: <?php echo empty( $lists ) ? 'block' : 'none'; ?>;">

							<!-- Select Service -->

							<div id="md-email-service" class="md-content-wrap">

								<h2><?php _e( 'Step 1: Connect to Your Email Service', 'md' ); ?></h2>

								<p><?php _e( 'Before you can insert any email forms to your site, you need to connect to your email service provider below.', 'md' ); ?></p>

								<table class="form-table">
									<tbody>

										<tr>
											<th scope="row"><?php $this->label( 'service', __( 'Select Service', 'md' ) ); ?></th>

											<td>
												<?php $this->field( 'select', 'service', array(
													''               => __( 'Select an email service&hellip;', 'md' ),
													'aweber'         => __( 'AWeber', 'md' ),
													'mailchimp'      => __( 'MailChimp', 'md' ),
													'activecampaign' => __( 'ActiveCampaign', 'md' ),
													'convertkit'     => __( 'ConvertKit', 'md' ),
													'custom_code'    => __( 'Custom HTML Form Code', 'md' )
												) ); ?>
											</td>
										</tr>

									</tbody>
								</table>

							</div>

							<!-- API Authorization -->

							<div id="md-email-steps" style="display: none;">

								<div id="md-email-auth" style="display: none;">

									<hr class="md-hr" />

									<div class="md-content-wrap">

										<h2><?php _e( 'Step 2: Get Your Authorization Code', 'md' ); ?></h2>

										<p><a href="#" id="md-email-auth-btn" class="button" target="_blank"><?php _e( 'Get Authorization Code', 'md' ); ?></a></p>

										<?php $this->desc( __( 'Click the "Get Authorization Code" button above and login to your account and get your authorization code. Once you have it, copy it and paste it into the text field below and click "Connect."', 'md' ) ); ?>

									</div>

									<!-- URL -->

									<table id="md-email-url" class="form-table" style="display: none;">
										<tbody>

											<tr>
												<th scope="row"><?php $this->label( 'api_url', __( 'API URL', 'md' ) ); ?></th>

												<td>
													<?php $this->field( 'text', 'api_url' ); ?>
												</td>
											</tr>

										</tbody>
									</table>

									<!-- Key -->

									<table id="md-email-key" class="form-table">
										<tbody>

											<tr>
												<th scope="row"><?php $this->label( 'api_key', __( 'API Key', 'md' ) ); ?></th>

												<td>
													<?php $this->field( 'textarea', 'api_key' ); ?>
												</td>
											</tr>

										</tbody>
									</table>

								</div>

								<?php submit_button( __( 'Connect', 'md' ), 'primary', 'md-email-connect' ); ?>

							</div>

						</div>

					<?php else : ?>

						<?php if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) : ?>
							<input type="hidden" name="_wp_http_referer" value="<?php echo admin_url( 'themes.php?page=md_email' ); ?>" />
							<script>
								jQuery( document ).ready( function() {
									postboxes.add_postbox_toggles( pagenow );
								} );
							</script>
							<script src="<?php echo MD_API_URL_JS . 'color-picker.js'; ?>" />
						<?php endif; ?>

						<p class="md-content-wrap"><?php $this->desc( sprintf( __( 'Fill out the fields below to create a <i>default email form</i> that will be used throughout your site. You may also override this form to create custom email forms on any post, page, or category (and the <a href="%s">Email Signup Form Widget</a>).', 'md' ), admin_url( 'widgets.php' ) ) ); ?></p>

						<?php $email_form->fields(); ?>

						<p>
							<?php submit_button( __( 'Save Changes', 'md' ), 'primary', 'submit', false ); ?> &nbsp;
							<?php submit_button( __( 'Disconnect', 'md' ), 'delete', 'md-email-disconnect', false ); ?>
						</p>

					<?php endif; ?>

				</form>

			</div>
		</div>

	<?php }


	/**
	 * Add admin only options to form (hooked in $this->construct()).
	 *
	 * @since 4.3
	 */

	public function admin_settings() {
		$design = new md_design_options( $this->_id, '#DDDDDD' );
	?>

		<!-- After Post -->

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row"><?php $this->label( 'email_after_post', __( 'After Post', 'md' ) ); ?></th>

					<td>
						<?php $this->field( 'checkbox', 'email_after_post', array(
							'display_posts' => __( 'Add default email form to end of all posts', 'md' )
						) ); ?>
					</td>
				</tr>

			</tbody>
		</table>

		<!-- Design -->

		<?php $design->fields(); ?>

	<?php }

}