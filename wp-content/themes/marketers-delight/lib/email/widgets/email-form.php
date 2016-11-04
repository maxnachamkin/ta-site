<?php

// Prevent direct access.

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Build email form widget, integrated with MD Email
 * Forms data.
 *
 * @since 4.0
 */

class md_email_form extends WP_Widget {

	public $_allowed_html = array(
		'a' => array(
			'href'   => array(),
			'class'  => array(),
			'id'     => array(),
		),
		'span' => array(
			'class'  => array(),
			'id'     => array(),
		),
		'img' => array(
			'src'    => array(),
			'alt'    => array(),
			'height' => array(),
			'width'  => array(),
			'class'  => array(),
			'id'     => array(),
		),
		'br' => array(),
		'b'  => array(),
		'i'  => array()
	);


	public function __construct() {
		parent::__construct( 'md_email', __( 'Email Signup Form', 'md' ), array(
			'description' => __( 'Easily place email signup forms with AWeber, MailChimp, or your own custom code.', 'md' ) )
		);

		$this->email = get_option( 'md_email' );
		$this->data  = get_option( 'md_email_data' );

		add_action( 'load-widgets.php', array( $this, 'admin_enqueue' ) );
	}


	public function admin_enqueue() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'md-color-picker-widget', MD_PLUGIN_URL . 'admin/js/color-picker-widget.js', array( 'wp-color-picker' ) );
	}


	public function widget( $args, $val ) {
		$data = get_option( 'md_email_data' );

		if ( empty( $data ) )
			return;

		if ( ! empty( $val['custom'] ) ) {
			$fields['email_title']         = $val['title'];
			$fields['email_desc']          = $val['desc'];
			$fields['email_list']          = $val['list'];
			$fields['email_input']['name'] = $val['form_fields_name'];
			$fields['email_name_label']    = $val['name_label'];
			$fields['email_email_label']   = $val['email_label'];
			$fields['email_submit_text']   = $val['submit_text'];
			$fields['email_form_title']    = $val['email_form_title'];
			$fields['email_form_footer']   = $val['email_form_footer'];
			$fields['email_classes']       = $val['classes'];
			$fields['email_image']         = $val['image'];
			$fields['email_bg_color']      = $val['bg_color'];

			$fields['email_form_style']['attached'] = ! empty( $val['form_style_attached'] ) ? $val['form_style_attached'] : '';

			if ( $data['service'] == 'custom_code' )
				$fields['email_code'] = $val['custom_code'];
		}
		else
			$fields = get_option( 'md_email' );

		$classes = '';
		if ( ! empty( $fields['email_image'] ) || ! empty( $fields['email_bg_color'] ) ) {
			$classes .= 'block-mid';
			if ( ! empty( $fields['email_image'] ) )
				$classes .= ' box-dark image-overlay text-white';
		}

		$email_args = array(
			'classes'      => $classes,
			'before_title' => $args['before_title'],
			'after_title'  => $args['after_title']
		);
	?>

		<?php echo $args['before_widget']; ?>

			<?php md_email_form( $fields, $email_args ); ?>

		<?php echo $args['after_widget']; ?>

	<?php }


	public function update( $new, $val ) {
		foreach ( array( 'title', 'desc', 'email_form_title', 'email_form_footer' ) as $kses_field )
			$val[$kses_field] = wp_kses( $new[$kses_field], $this->_allowed_html );

		$val['list'] = in_array( $new['list'], $this->data['fields']['email_list']['options'] ) ? $new['list'] : '';

		foreach ( array( 'custom', 'form_fields_name', 'form_style_attached' ) as $check_field )
			$val[$check_field] = $new[$check_field] ? 1 : 0;

		foreach ( array( 'name_label', 'email_label', 'submit_text' ) as $text_field )
			$val[$text_field] = sanitize_text_field( $new[$text_field] );

		$val['image']    = esc_url( $new['image'] );
		$val['bg_color'] = $new['bg_color'];
		$val['classes']  = strip_tags( $new['classes'] );

		$val['custom_code'] = $new['custom_code'];

		return $val;
	}


	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title'               => '',
			'desc'                => '',
			'list'                => '',
			'form_fields_name'    => '',
			'name_label'          => '',
			'email_label'         => '',
			'submit_text'         => '',
			'form_style_attached' => '',
			'email_form_title'    => '',
			'email_form_footer'   => '',
			'custom_code'         => '',
			'image'               => '',
			'bg_color'            => '',
			'classes'             => '',
			'custom'              => ''
		) );

		$service = $this->data['service'];
		$lists   = isset( $this->data['list_data'] ) ? $this->data['list_data'] : '';

		$src     = ! empty( $val['image'] ) ? $val['image'] : MD_PLUGIN_URL . 'admin/images/add-image.jpg';
		$display = ! empty( $val['image'] ) ? 'block' : 'none';

	?>

		<?php if ( ! empty( $this->data ) ) : ?>

			<!-- Create Custom Form -->

			<p>
				<input id="<?php echo $this->get_field_id( 'custom' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'custom' ); ?>" value="1" <?php checked( $val['custom'] ); ?> />

				<label for="<?php echo $this->get_field_id( 'custom' ); ?>"><?php _e( 'Create Custom Email Form', 'md' ); ?></label>
			</p>

			<p id="<?php echo $this->get_field_id( 'md-email-custom-message' ); ?>" class="description" style="display: <?php echo empty( $val['custom'] ) ? 'block' : 'none'; ?>"><?php echo sprintf( __( 'Note: The email form you created on the <a href="%s" target="_blank">Email Forms</a> page is now showing on your site. If you wish to create a custom form, click the above checkbox and save to begin.', 'kol-email' ), admin_url( 'tools.php?page=md_email' ) ); ?></p>

			<!-- Custom Form -->

			<div id="<?php echo $this->get_field_id( 'md-email-custom' ); ?>" style="display: <?php echo ! empty( $val['custom'] ) ? 'block' : 'none'; ?>">

				<!-- Title -->

				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'md' ); ?>:</label>

					<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php esc_attr_e( $val['title'] ); ?>" class="widefat" />
				</p>

				<!-- Description -->

				<p>
					<label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e( 'Description', 'md' ); ?>:</label>

					<textarea id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" class="widefat" rows="4"><?php printf( '%s', esc_textarea( $val['desc'] ) ); ?></textarea>
				</p>

				<?php if ( $service == 'custom_code' ) : ?>

					<!-- Custom Code -->

					<p>
						<label for="<?php echo $this->get_field_id( 'custom_code' ); ?>"><?php _e( 'Custom HTML Code', 'md' ); ?>:</label>

						<textarea id="<?php echo $this->get_field_id( 'custom_code' ); ?>" name="<?php echo $this->get_field_name( 'custom_code' ); ?>" class="widefat" rows="7"><?php printf( '%s', esc_textarea( $val['custom_code'] ) ); ?></textarea>
					</p>

				<!-- Connected Service -->

				<?php else :
					foreach ( $this->data['list_data'] as $id => $atts )
						$lists[$id] = $atts['name'];
				?>

					<!-- Select List -->

					<p>
						<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e( 'Email List', 'md' ); ?>:</label><br />

						<select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" style="max-width: 100%;">
							<option value=""><?php _e( 'Select a List&hellip;', 'md' ); ?></option>

							<?php foreach ( $lists as $list => $name ) : ?>
								<option value="<?php esc_attr_e( $list ); ?>" <?php selected( $val['list'], $list, true ); ?>><?php echo $name; ?></option>
							<?php endforeach; ?>

						</select>
					</p>

					<div id="<?php echo $this->get_field_id( 'md-email' ); ?>-form" style="display: <?php echo $val['list'] ? 'block' : 'none'; ?>">

						<!-- Input Fields -->

						<h4><?php _e( 'Input Fields', 'md' ); ?></h4>

						<!-- Show Name Field -->

						<p>
							<input id="<?php echo $this->get_field_id( 'form_fields_name' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'form_fields_name' ); ?>" value="1" <?php checked( $val['form_fields_name'] ); ?> />

							<label for="<?php echo $this->get_field_id( 'form_fields_name' ); ?>"><?php _e( 'Ask for subscribers name in signup form', 'md' ); ?></label>
						</p>

						<!-- Name Label -->

						<div id="<?php echo $this->get_field_id( 'name_label' ); ?>-field" style="display: <?php echo $val['form_fields_name'] ? 'block' : 'none'; ?>">

							<label for="<?php echo $this->get_field_id( 'name_label' ); ?>"><?php _e( 'Name Field Label', 'md' ); ?>:</label>

							<input type="text" id="<?php echo $this->get_field_id( 'name_label' ); ?>" name="<?php echo $this->get_field_name( 'name_label' ); ?>" value="<?php esc_attr_e( $val['name_label'] ); ?>" placeholder="<?php _e( 'Enter your name&hellip;', 'md' ); ?>" class="widefat" />

						</div>

						<!-- Email Label -->

						<p>
							<label for="<?php echo $this->get_field_id( 'email_label' ); ?>"><?php _e( 'Email Field Label', 'md' ); ?>:</label>

							<input type="text" id="<?php echo $this->get_field_id( 'email_label' ); ?>" name="<?php echo $this->get_field_name( 'email_label' ); ?>" value="<?php esc_attr_e( $val['email_label'] ); ?>" placeholder="<?php _e( 'Enter your email&hellip;', 'md' ); ?>" class="widefat" />
						</p>

						<!-- Button Text -->

						<p>
							<label for="<?php echo $this->get_field_id( 'submit_text' ); ?>"><?php _e( 'Submit Button Text', 'md' ); ?>:</label>

							<input type="text" id="<?php echo $this->get_field_id( 'submit_text' ); ?>" name="<?php echo $this->get_field_name( 'submit_text' ); ?>" value="<?php esc_attr_e( $val['submit_text'] ); ?>" placeholder="<?php echo _e( 'Join Now!', 'md' ); ?>" class="widefat" />
						</p>

						<!-- Form Style -->

						<p>
							<input id="<?php echo $this->get_field_id( 'form_style_attached' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'form_style_attached' ); ?>" value="1" <?php checked( $val['form_style_attached'] ); ?> />

							<label for="<?php echo $this->get_field_id( 'form_style_attached' ); ?>"><?php _e( 'Attach input fields to each other', 'md' ); ?></label>
						</p>

						<!-- Form Title -->

						<p>
							<label for="<?php echo $this->get_field_id( 'email_form_title' ); ?>"><?php _e( 'Form Title', 'md' ); ?>:</label>

							<input type="text" id="<?php echo $this->get_field_id( 'email_form_title' ); ?>" name="<?php echo $this->get_field_name( 'email_form_title' ); ?>" value="<?php esc_attr_e( $val['email_form_title'] ); ?>" class="widefat" />
						</p>

						<!-- Form Footer -->

						<p>
							<label for="<?php echo $this->get_field_id( 'email_form_footer' ); ?>"><?php _e( 'Form Footer', 'md' ); ?>:</label>

							<textarea id="<?php echo $this->get_field_id( 'email_form_footer' ); ?>" name="<?php echo $this->get_field_name( 'email_form_footer' ); ?>" class="widefat" rows="4"><?php printf( '%s', esc_textarea( $val['email_form_footer'] ) ); ?></textarea>
						</p>

						<h4><?php _e( 'Form Design', 'md' ); ?></h4>

						<!-- BG Image -->

						<div class="md-media">

							<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Background Image', 'md' ); ?>:</label>

							<p><img class="md-media-preview-image" src="<?php echo $src; ?>" alt="<?php _e( 'Preview image', 'md' ); ?>" style="cursor: pointer; max-width: 100%;" /></p>

							<input type="text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo $val['image']; ?>" placeholder="<?php _e( 'http://', 'md' ); ?>" class="md-media-url widefat" />

							<p class="md-media-buttons">
								<input type="button" class="md-media-remove button" value="<?php _e( 'Remove Image', 'md' ); ?>" style="display: <?php echo $display; ?>;" />
							</p>

						</div>

						<!-- BG Color -->

						<p>
							<label for="<?php echo $this->get_field_id( 'bg_color' ); ?>"><?php _e( 'Background Color', 'md' ); ?></label><br />
							<input class="md-color-picker-widget" type="text" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" value="<?php echo esc_attr( $val['bg_color'] ); ?>" />
						</p>

						<!-- Classes -->

						<p>
							<label for="<?php echo $this->get_field_id( 'classes' ); ?>"><?php _e( 'HTML Classes', 'md' ); ?>:</label>

							<input type="text" id="<?php echo $this->get_field_id( 'classes' ); ?>" name="<?php echo $this->get_field_name( 'classes' ); ?>" value="<?php esc_attr_e( $val['classes'] ); ?>" placeholder="form-full" class="widefat" />
						</p>


					</div>

					<script>

						// Toggle conditional fields

						( function() {
							document.getElementById( '<?php echo $this->get_field_id( 'form_fields_name' ); ?>' ).onchange = function() {
								document.getElementById( '<?php echo $this->get_field_id( 'name_label' ); ?>-field' ).style.display = this.checked ? 'block' : 'none';
							}
						})();

					</script>

				<?php endif; ?>

			</div>

			<?php if ( $this->data['service'] != 'custom_code' ) : ?>

				<script>

					// Toggle conditional fields

					( function() {
						document.getElementById( '<?php echo $this->get_field_id( 'list' ); ?>' ).onchange = function() {
							document.getElementById( '<?php echo $this->get_field_id( 'md-email' ); ?>-form' ).style.display = this.value != '' ? 'block' : 'none';
						}
					})();

				</script>

			<?php endif; ?>

		<?php else : ?>

			<?php md_email_connect_notice(); ?>

		<?php endif; ?>

	<?php }
}