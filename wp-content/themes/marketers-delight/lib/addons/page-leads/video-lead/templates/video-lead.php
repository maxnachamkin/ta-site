<?php
	$fields = video_lead_fields();

	$headline = $fields['headline'];
	$subtitle = $fields['subtitle'];
	$desc     = $fields['desc'];

	$service = $fields['service'];

	$position    = $fields['position'];
	$layout      = $fields['layout'];
	$bg_color    = $fields['bg_color'];
	$bg_image    = $fields['bg_image'];
	$text_scheme = $fields['text_color_scheme'];

	$button_action = $fields['button_action'];
	$button_link   = $fields['button_link'];
	$button_text   = $fields['button_text'];

	$has_play   = has_video_lead_play_button();
	$play_class = $has_play ? ' has-play-button' : '';

	$bg_image_classes   = ! empty( $bg_image ) ? ' image-overlay' : '';
	$text_color_classes = $text_scheme != 'white' ? ' text-dark' : ' text-white';

	$classes = md_design_classes( $position ) . "$play_class{$bg_image_classes}$text_color_classes";
	$bg      = md_design_background( $bg_color, $bg_image );

	$has_columns = has_video_lead_columns();
?>

<div id="video-lead-<?php the_ID(); ?>" class="video-lead<?php echo $classes; ?>"<?php echo $bg; ?>>
	<div class="inner">

		<div class="<?php echo $has_columns ? 'columns-video-lead columns-double block-double-tb format-text-sec' : 'block-quad text-center format-text-main'; ?> ">

			<?php if ( has_video_lead_content() ) : ?>

				<!-- Text Box -->

				<div class="video-lead-text<?php echo ( $has_columns ? ' col col1' . ( $layout == 'video-right' ? ' col-right' : '' ) : ' mb-double' ); ?>">

					<?php if ( ! empty( $subtitle ) ) : ?>
						<p class="video-lead-subtitle mb-half caps"><?php esc_html_e( $subtitle ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $headline ) ) : ?>
						<h2 class="video-lead-headline"><?php esc_html_e( $headline ); ?></h2>
					<?php endif; ?>

					<?php if ( $desc ) : ?>
						<div class="video-lead-desc small-title mb-single text-sec">
							<?php echo wpautop( $desc ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $button_action != 'play' ) : ?>
						<?php md_button( array(
							'action' => $button_action,
							'link'   => $button_link,
							'text'   => $button_text,
							'edd_id' => video_lead_field( 'edd_button' ),
							'popup'  => video_lead_field( 'button_popup' )
						) ); ?>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( has_video_lead_video() ) : ?>

				<!-- Video -->

				<div class="video-lead-video<?php echo ( $has_columns ? ' col col2 text-center' . ( $has_play ? ' block-single-tb' : '' ) : '' ); ?>">

					<?php if ( $has_play ) : ?>

						<div data-popup="md_popup_video_lead" class="video-lead-play-button md-popup-trigger<?php echo $text_color_classes; ?>">
							<span class="play-button mb-small"></span>
							<?php if ( ! empty( $button_text ) ) : ?>
								<p class="play-button-text"><?php esc_html_e( $button_text ); ?></p>
							<?php endif; ?>
						</div>

					<?php else : ?>

						<?php video_lead_video( $service ); ?>

					<?php endif; ?>

				</div>

			<?php endif; ?>

		</div>

	</div>
</div>