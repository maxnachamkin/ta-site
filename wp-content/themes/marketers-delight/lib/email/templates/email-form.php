<?php
	$name_label  = ! empty( $fields['email_name_label'] ) ? esc_attr( $fields['email_name_label'] ) : __( 'Enter your name&hellip;', 'md' );
	$email_label = ! empty( $fields['email_email_label'] ) ? esc_attr( $fields['email_email_label'] ) : __( 'Enter your email&hellip;', 'md' );
	$submit_text = ! empty( $fields['email_submit_text'] ) ? esc_attr( $fields['email_submit_text'] ) : __( 'Join Now!', 'md' );

	if ( ! empty( $fields['email_form_style']['attached'] ) )
		if ( ! empty( $fields['email_input']['name'] ) )
			$form_style = 'form-attached-2';
		else
			$form_style = 'form-attached';
	else
		$form_style = 'form-full';

	if ( ! empty( $atts['bg_style'] ) )
		$bg_style = $atts['bg_style'];
	else {
		$bg_color = ! empty( $fields['email_bg_color'] ) ? 'background-color: ' . esc_url( $fields['email_bg_color'] ) . ';' : '';
		$bg_image = ! empty( $fields['email_image'] ) ? 'background-image: url(' . esc_url( $fields['email_image'] ) . ');' : '';
		$bg_style = ! empty( $bg_color ) || ! empty( $bg_image ) ? " style=\"{$bg_color}{$bg_image}\"" : '';
	}

	$image_classes = ! empty( $fields['email_image'] ) ? ' image-overlay box-dark block-mid text-white' : '';

	$email_classes = ! empty( $fields['email_classes'] ) ? esc_attr( $fields['email_classes'] ) . " $form_style" : $form_style;
	$atts_classes  = isset( $atts['classes'] ) ? ' ' . esc_attr( $atts['classes'] ) : '';
	$inner_classes = isset( $atts['inner_classes'] ) ? ' ' . $atts['inner_classes'] : '';

	if ( empty( $atts_classes ) )
		$classes = $email_classes . $image_classes;
	else
		$classes = $email_classes . $atts_classes;

	$before_title = isset( $atts['before_title'] ) ? $atts['before_title'] : '<div class="email-form-intro-title large-title mb-half">';
	$after_title  = isset( $atts['after_title'] ) ? $atts['after_title'] : '</div>';
?>

<div class="email-form-wrap <?php echo $classes; ?>"<?php echo $bg_style; ?>>
	<div class="email-form-inner<?php echo $inner_classes; ?>">

		<?php if ( $fields['email_title'] || $fields['email_desc'] ) : ?>

			<div class="email-form-intro mb-single">

				<?php if ( $fields['email_title'] ) : ?>
					<?php echo $before_title . esc_html( $fields['email_title'] ) . $after_title; ?>
				<?php endif; ?>

				<?php if ( $fields['email_desc'] ) : ?>
					<div class="email-form-intro-desc">
						<?php echo wpautop( $fields['email_desc'] ); ?>
					</div>
				<?php endif; ?>

			</div>

		<?php endif; ?>

		<?php if ( $fields['service'] != 'custom_code' && ! empty( $fields['email_list'] ) ) : ?>

			<?php if ( $fields['service'] == 'convertkit' ) : ?>
				<script src="https://app.convertkit.com/assets/CKJS4.js?v=18"></script>
				<div id="ck_success_msg" style="display: none;">
					<p class="success"><i class="md-icon md-icon-ok-circled"></i> <?php _e( 'Success! Now check your email to confirm your subscription.', 'md' ); ?></p>
				</div>
			<?php endif; ?>

			<form<?php echo $fields['service'] == 'convertkit' ? ' id="ck_subscribe_form"' : ''; ?> method="post" action="<?php esc_attr_e( $fields['action'] ); ?>" class="email-form clear">

				<?php if ( ! empty( $fields['email_form_title'] ) ) : ?>
					<div class="email-form-title small-title mb-single text-center">
						<?php echo wpautop( $fields['email_form_title'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $fields['service'] == 'aweber' ) : ?>

					<input type="hidden" name="meta_split_id" value="" />
					<input type="hidden" name="listname" value="<?php esc_attr_e( $fields['email_list'] ); ?>" />
					<input type="hidden" name="meta_message" value="1" />
					<input type="hidden" name="meta_required" value="<?php echo ( ! empty( $fields['email_input']['name'] ) ? esc_attr( $fields['att_name'] ) . ',' : '' ) . esc_attr( $fields['att_email'] ); ?>" />
					<input type="hidden" name="meta_tooltip" value="" />

				<?php endif; ?>

				<?php if ( $fields['service'] == 'activecampaign' ) : ?>

					<input type="hidden" name="f" value="<?php esc_attr_e( $fields['email_list'] ); ?>">
					<input type="hidden" name="s" value="">
					<input type="hidden" name="c" value="0">
					<input type="hidden" name="m" value="0">
					<input type="hidden" name="act" value="sub">
					<input type="hidden" name="nlbox[]" value="<?php esc_attr_e( $fields['nlbox'] ); ?>">

				<?php endif; ?>

				<?php if ( $fields['service'] == 'convertkit' ) : ?>
					<input type="hidden" name="id" value="<?php esc_attr_e( $fields['email_list'] ); ?>" id="landing_page_id" />
					<div id="ck_error_msg" class="mb-half" style="display: none;">
						<p class="required"><i class="md-icon md-icon-cancel"></i> <?php _e( 'There was an error submitting your subscription. Please try again.', 'md' ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $fields['email_input']['name'] ) ) : ?>
					<input type="text" name="<?php esc_attr_e( $fields['att_name'] ); ?>" class="form-input form-input-name" placeholder="<?php echo $name_label; ?>" />
				<?php endif; ?>

				<input type="email" name="<?php esc_attr_e( $fields['att_email'] ); ?>" class="form-input form-input-email" placeholder="<?php echo $email_label; ?>" />

				<?php if ( $fields['service'] == 'mailchimp' ) : ?>
					<input type="hidden" name="u" value="<?php esc_attr_e( $fields['u'] ); ?>">
					<input type="hidden" name="id" value="<?php esc_attr_e( $fields['id'] ); ?>">
				<?php endif; ?>

				<button class="email-form-submit form-submit mb-half"><?php esc_html_e( $submit_text ); ?></button>

				<?php if ( ! empty( $fields['email_form_footer'] ) ) : ?>
					<div class="email-form-footer mb-single text-center">
						<?php echo wpautop( $fields['email_form_footer'] ); ?>
					</div>
				<?php endif; ?>

			</form>

		<?php else : ?>

			<?php if ( ! empty( $fields['email_code'] ) ) : ?>
				<?php echo $fields['email_code']; ?>
			<?php endif; ?>

		<?php endif; ?>

	</div>
</div>