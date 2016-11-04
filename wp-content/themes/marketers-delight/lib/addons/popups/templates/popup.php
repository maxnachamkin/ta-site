<?php
	$id = $atts['id']; // passed from template loader

	$fields  = md_popup_fields( $id );
	$content = md_popup_content( $id );

	// custom
	$show_custom_template = $fields['show_custom_template'];
	$custom_template      = $fields['custom_template'];

	// text
	$show_text   = $fields['show_text'];
	$headline    = $fields['headline'];
	$description = $fields['description'];
	$bullets     = $fields['bullets'];
	$button_url  = $fields['button_url'];
	$button_text = $fields['button_text'];

	// image
	$image      = $fields['image'];
	$image_wrap = $fields['image_wrap'];

	// email
	$email_data   = get_option( 'md_email_data' );
	$email_fields = md_popups_email_fields( $id );
	$email_list   = $fields['email_list'];
	$email_code   = $fields['email_code'];

	// design
	$text_color    = ! empty( $fields['text_color'] ) ? $fields['text_color'] : '#1e1e1e';
	$secondary_color   = ! empty( $fields['secondary_color'] ) ? $fields['secondary_color'] : 'rgba(10, 0, 0, 0.1)';
	$bg_color      = ! empty( $fields['bg_color'] ) ? $fields['bg_color'] : '#ddd';
	$bg_image      = $fields['bg_image'];
	$bg_image_url  = wp_get_attachment_image_src( absint( $bg_image ), 'full' );
	$close_color   = ! empty( $fields['close_color'] ) ? $fields['close_color'] : '#ae2525';
	$full_width    = $fields['full_width'];
	$classes       = $fields['classes'];

	// conditionals
	$has_text  = md_popup_has_text( $id );
	$has_image = md_popup_has_image( $id );
	$has_email = md_popup_has_email( $id );

	// columns
	if ( ! empty( $full_width ) )
		$text_col = $image_col = $email_col = ' width-full';
	else {
		$text_col  = $content > 1 ? ' width-wide' : '';
		$image_col = $content == 2 && ! $has_text ? ' width-wide' : ' width-small';
		$email_col = $content == 2 ? ' width-small' : ' width-full';
	}

	// blocks
	$text_block  = ' block-double';
	$email_block = $content == 2 && empty( $full_width ) ? ' block-mid block-double-tb' : ' block-mid';
	$image_block = empty( $image_wrap ) ? ' block-mid' : '';

	// classes
	$custom_classes = ! empty( $classes ) ? ' ' . esc_attr( $classes ) : '';
	$bg_classes     = ! empty( $bg_image ) ? ' image-overlay' : '';
	$text_classes   = "$text_col{$text_block}" . md_popup_customizer_display( $show_text );
	$image_classes  = "$image_col{$image_block}" . md_popup_customizer_display( $image );
	$email_classes  = "$email_col{$email_block}" . md_popup_customizer_display( $email_list );
?>

<!-- Popup -->

<div id="md_popup_<?php echo $id; ?>" class="md-popup format-text-main<?php echo $custom_classes; ?>">

	<div class="md-popup-content md-popup-content-<?php echo $content . $bg_classes; ?>">

		<?php if ( ! empty( $show_custom_template ) ) : ?>

			<div class="md-popup-custom<?php echo md_popup_customizer_display( $show_custom_template ); ?>">
				<?php
					$custom_template = wpautop( $custom_template );
					echo do_shortcode( $custom_template );
				?>
			</div>

		<?php else : ?>

			<?php if ( $has_image || is_customize_preview() ) : ?>

				<!-- Image -->

				<div class="md-popup-image<?php echo $image_classes; ?>">
					<img src="<?php echo esc_url( $image ); ?>" class="md-popup-featured-image" alt="" />
				</div>

			<?php endif; ?>

			<?php if ( $has_text || is_customize_preview() ) : ?>

				<!-- Text -->

				<div class="md-popup-text<?php echo $text_classes; ?>">

					<?php if ( ! empty( $headline ) || is_customize_preview() ) : ?>
						<!-- Headline -->
						<h3 class="md-popup-headline<?php echo md_popup_customizer_display( $headline ); ?>"><?php echo stripslashes( $headline ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $description ) || is_customize_preview() ) : ?>
						<!-- Description -->
						<div class="md-popup-description small-title mb-single<?php echo md_popup_customizer_display( $description ); ?>">
							<?php echo wpautop( $description ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $bullets ) || is_customize_preview() ) : ?>
						<!-- Bullets -->
						<div class="md-popup-bullets mb-single<?php echo md_popup_customizer_display( $bullets ); ?>">
							<?php echo wpautop( $bullets ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $button_url ) || is_customize_preview() ) : ?>
						<!-- Button -->
						<a href="<?php echo esc_url( $button_url ); ?>" class="md-popup-button button<?php echo md_popup_customizer_display( $button_text ); ?>"><?php echo esc_html( $button_text ); ?></a>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( $has_email || is_customize_preview() ) : ?>

				<!-- Email -->

				<div class="md-popup-email md-popup-sec text-center<?php echo $email_classes; ?>">

					<?php md_email_form( $email_fields, array(
						'classes' => 'mb-half'
					) ); ?>

				</div>

			<?php endif; ?>

		<?php endif; ?>

	</div>

	<div class="md-popup-close md-popup-close-corner">&times;</div>

</div>