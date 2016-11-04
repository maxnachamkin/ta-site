<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Builds After Post meta box options and displays HTML
 * by firing to the md_hook_content hook. This needs to
 * be reworked.
 *
 * @since 1.0
 */

class md_after_post extends md_api {

	/**
	 * Pseudo constructor creates meta box and fires hooks.
	 *
	 * @since 1.0
	 */

	public function construct() {
		$this->suite = $this->_id;

		$this->meta_box = array(
			'name'    => __( 'After Post', 'md' ),
			'context' => 'normal'
		);

		$this->button = new md_button( $this->_id );

		// add after post to the md_hook_content action on frontend (also on 404)
		add_action( 'md_hook_content', array( $this, 'display' ), 11 );
		add_action( 'md_hook_content', array( $this, 'content_item_404' ), 20 );
		// add design options to email fields on backend
		add_action( $this->_id . '_email_fields_top', array( $this, 'add_design_options' ) );
	}


	/**
	 * Outputs After Post HTML to be fired to MD hook.
	 *
	 * @since 1.0
	 */

	public function display() {
		$select = $this->meta( 'select' );

		if ( ! is_singular() || post_password_required() || $select == 'empty' )
			return;

		$email      = get_option( 'md_email' );
		$email_data = $this->email_data();

		$bg_color    = $email['bg_color'];
		$bg_image    = $email['bg_image'];
		$text_scheme = $email['text_color_scheme'];

		if ( $select == 'email' ) {
			$bg_color    = $this->meta( 'bg_color' );
			$bg_image    = $this->meta( 'bg_image' );
			$text_scheme = $this->meta( 'text_color_scheme' );
		}

		$bg_image_classes   = ! empty( $bg_image ) ? ' image-overlay text-white' : '';
		$text_color_classes = $text_scheme != 'white' ? 'text-dark' : 'text-white';
		$form_border        = $bg_color != '#ffffff' && $bg_color != '#FFFFFF' ? ' form-no-border' : '';

		$classes = "$bg_image_classes $text_color_classes{$form_border}";
		$bg      = md_design_background( $bg_color, $bg_image );
		$block   = md_design_block( 'md_hook_content' );
	?>

		<?php if ( $select == 'email' && ! empty( $email_data['email_list'] ) ) : ?>

			<div class="after-post inner<?php echo $classes; ?>"<?php echo $bg; ?>>
				<?php md_email_form( $email_data, array(
					'inner_classes' => "$block text-center"
				) ); ?>
			</div>

		<?php elseif ( $select == 'action' ) : ?>

			<?php $this->action_box(); ?>

		<?php elseif ( ( ! empty( $email['email_after_post']['display_posts'] ) && is_single() ) || ( $select == 'email' && is_page() ) ) : // default email form ?>

			<div class="after-post inner<?php echo $classes; ?>"<?php echo $bg; ?>>
				<?php md_email_form( null, array(
					'inner_classes' => "$block text-center"
				) ); ?>
			</div>

		<?php endif; ?>

	<?php }


	/**
	 * Place email form after post on 404 page.
	 *
	 * @since 1.0
	 */

	public function content_item_404() {
		if ( ! is_404() )
			return;

		$block = md_design_block( 'md_hook_content' );
		$form  = array(
			'title' => apply_filters( 'md_filter_404_email_title', __( 'Stay Up-to-Date With Latest Posts By Joining the Newsletter', 'md' ) ),
			'desc'  => apply_filters( 'md_filter_404_email_desc', __( 'Never get lost again! By joining the newsletter you\'ll receive only the best and most up-to-date content we have for you.', 'md' ) )
		);
		$args = array( 'classes' => "box-dark image-overlay links-dark text-white $block text-center" );
	?>

		<div class="after-post-email inner">
			<?php md_email_form( $form, $args ); ?>
		</div>

	<?php }


	/**
	 * Creates the Action Box HTML output.
	 *
	 * @since 1.0
	 */

	function action_box() {
		$title       = $this->meta( 'action_title' );
		$desc        = $this->meta( 'action_desc' );
		$button_text = $this->meta( 'action_button_text' );
		$classes     = $this->meta( 'action_classes' );

		if ( empty( $title ) && empty( $desc ) && empty( $button_text ) )
			return;

		$block = md_design_block( 'md_hook_content' );

		$button_link = $this->meta( 'action_button_link' );

		$style         = ! empty( $image ) ? ' style="background-image: url(' . $image . ');"' : '';
		$image_classes = ! empty( $style ) ? ' image-overlay text-white box-dark' : '';

		$html_classes = ! empty( $classes ) ? " $classes{$image_classes}" : $image_classes;

		$html   = ! empty( $button_link ) ? 'a href="' . esc_url( $button_link ) . '"' : 'span';
		$c_html = ! empty( $button_link ) ? 'a' : 'span';
	?>

		<div class="action-box inner content-item<?php esc_attr_e( $html_classes ); ?> text-center"<?php echo $style; ?>>
			<div class="<?php echo $block; ?>">

				<?php do_action( 'md_hook_action_box_top' ); ?>

				<?php if ( ! empty( $title ) ) : ?>
					<p class="large-title"><?php echo $title; ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $desc ) ) : ?>
					<?php echo wpautop( $desc ); ?>
				<?php endif; ?>

				<?php md_button( array(
					'action'  => $this->meta( 'button_action' ),
					'link'    => $button_link,
					'text'    => $button_text,
					'edd_id'  => $this->meta( 'edd_button' ),
					'woo_id'  => $this->meta( 'woo_button' ),
					'popup'   => $this->meta( 'button_popup' ),
					'classes' => 'width-full mb-half'
				) ); ?>

				<?php do_action( 'md_hook_action_box_bottom' ); ?>

			</div>
		</div>

	<?php }


	/**
	 * Helper method that retrieves post meta by ID without prefix.
	 *
	 * @since 1.0
	 */

	public function meta( $option ) {
		return get_post_meta( get_the_ID(), "md_after_post_$option", true );
	}


	/**
	 * Create options array to feed to the email form function
	 * (used in display() method).
	 *
	 * @since 1.0
	 */

	public function email_data() {
		$data   = get_option( 'md_email_data' );
		$fields = array();

		$fields['email_title'] = $this->meta( 'email_title' );
		$fields['email_desc']  = $this->meta( 'email_desc' );

		if ( $data['service'] == 'custom_code' )
			$fields['email_code'] = $this->meta( 'email_code' );
		else
			$fields['email_list'] = $this->meta( 'email_list' );

		$fields['email_input']         = $this->meta( 'email_input' );
		$fields['email_input']['name'] = isset( $fields['email_input']['name'] ) ? $fields['email_input']['name'] : '';
		$fields['email_name_label']    = $this->meta( 'email_name_label' );
		$fields['email_email_label']   = $this->meta( 'email_email_label' );
		$fields['email_submit_text']   = $this->meta( 'email_submit_text' );

		$fields['email_form_style']             = $this->meta( 'email_form_style' );
		$fields['email_form_style']['attached'] = isset( $fields['email_form_style']['attached'] ) ? $fields['email_form_style']['attached'] : '';

		$fields['email_form_title']  = $this->meta( 'email_form_title' );
		$fields['email_form_footer'] = $this->meta( 'email_form_footer' );

		$fields['email_classes']                = $this->meta( 'email_classes' );

/*
		if ( $data['service'] == 'aweber' ) {
			$fields['aweber']['tracking_image'] = $this->meta( 'tracking_image' );
			$fields['aweber']['form_id']        = $this->meta( 'form_id' );
			$fields['aweber']['thank_you']      = $this->meta( 'thank_you' );
			$fields['aweber']['already_sub']    = $this->meta( 'already_sub' );
			$fields['aweber']['ad_tracking']    = $this->meta( 'ad_tracking' );
		}
*/
		return $fields;
	}


	/**
	 * Register fields to be sanitized & saved to database.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		$email  = ! empty( $this->email_data['fields'] ) ? $this->email_data['fields'] : array();
		$button = $this->button->register_fields( null, array(
			'button_text' => 'action_button_text',
			'button_link' => 'action_button_link'
		) );

		return array_merge( array(
			'select' => array(
				'type'    => 'select',
				'options' => array(
					'email',
					'action',
					'empty'
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
			'action_title' => array(
				'type' => 'text'
			),
			'action_desc' => array(
				'type' => 'textarea'
			),
			'action_button_text' => array(
				'type' => 'text'
			),
			'action_button_link' => array(
				'type' => 'text'
			),
			'action_classes' => array(
				'type' => 'text'
			)
		), $email, $button );
	}


	/**
	 * Hook design options to top of Email Form. Needs to be accessible
	 * from Action Box at some point, setup kinda weird right now.
	 *
	 * @since 1.0
	 */

	public function add_design_options() {
		$design = new md_design_options( $this->_id, '#DDDDDD' );
		$design->fields();
	}


	/**
	 * Create meta box fields.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$email  = new md_email_fields( $this->_id );
		$email_form = get_option( 'md_email' );

		$select = $this->meta( 'select' );
		$post   = get_post_type() == 'post' && ! empty( $email_form['email_after_post']['display_posts'] ) ? array( 'empty'  => __( 'Leave empty', 'md' ) ) : array();
	?>

		<!-- Select -->

		<table class="form-table">
			<tbody>

				<tr>
					<td>
						<?php $this->field( 'select', 'select', array_merge( array(
							''       => __( 'Show at the end of this post&hellip;', 'md' ),
							'email'  => __( 'Email Form', 'md' ),
							'action' => __( 'Action Box', 'md' ),
						), $post ) ); ?>
					</td>
				</tr>

			</tbody>
		</table>

		<div id="<?php echo $this->_id; ?>_fields" style="display: <?php echo $select != '' ? 'block' : 'none'; ?>">

			<!-- Email Form -->

			<div id="<?php echo $this->_id; ?>_email_form" style="display: <?php echo $select == 'email' ? 'block' : 'none'; ?>">

				<?php if ( ! empty( $this->email_data['fields'] ) ) : ?>

					<p class="md-title"><?php _e( 'Create Custom Email Form', 'md' ); ?></p>

					<?php $this->desc( sprintf( __( 'Tip: If you leave the fields below empty, your <a href="%s" target="_blank">default email form</a> will appear at the end of this post.', 'md' ), admin_url( 'tools.php?page=md_email' ) ) ); ?>

					<?php $email->fields(); ?>

				<?php else : ?>

					<?php md_email_connect_notice(); ?>

				<?php endif; ?>

			</div>

			<!-- Action Box -->

			<div id="<?php echo $this->_id; ?>_action_box" style="display: <?php echo $select == 'action' ? 'block' : 'none'; ?>">

				<p class="md-title"><?php _e( 'Create Custom Action Box', 'md' ); ?></p>

				<table class="form-table">
					<tbody>

						<!-- Title -->

						<tr>
							<th scope="row"><?php $this->label( 'action_title', __( 'Title', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'text', 'action_title' ); ?>
							</td>
						</tr>

						<!-- Description -->

						<tr>
							<th scope="row"><?php $this->label( 'action_desc', __( 'Description', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'textarea', 'action_desc' ); ?>
							</td>
						</tr>

					</tbody>
				</table>

				<!-- Button -->

				<?php $this->button->fields( null, array(
					'button_text' => 'action_button_text',
					'button_link' => 'action_button_link'
				) ); ?>

				<table class="form-table">
					<tbody>

						<!-- CSS Classes -->

						<tr>
							<th scope="row"><?php $this->label( 'action_classes', __( 'CSS Classes', 'md' ) ); ?></th>

							<td>
								<?php $this->field( 'text', 'action_classes' ); ?>
								<?php $this->desc( __( 'Tip: Customize this box with <a href="https://kolakube.com/md-style-guide/" target="_blank">MD Styles</a> like the <b>box-dark</b> class.', 'md' ) ); ?>
							</td>
						</tr>

					</tbody>
				</table>

			</div>

		</div>

	<?php }


	/**
	 * Print admin footer scripts to meta box page only.
	 *
	 * @since 1.0
	 */

	public function admin_print_footer_scripts() { ?>

		<script>
			( function() {
				document.getElementById( '<?php echo $this->_id; ?>_select' ).onchange = function() {
					document.getElementById( '<?php echo $this->_id; ?>_fields' ).style.display     = this.value != '' ? 'block' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_email_form' ).style.display = this.value == 'email' ? 'block' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_action_box' ).style.display = this.value == 'action' ? 'block' : 'none';
				}
			})();
		</script>

	<?php }

}

$md_after_post = new md_after_post;