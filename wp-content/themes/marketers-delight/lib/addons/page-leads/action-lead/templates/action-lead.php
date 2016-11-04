<?php
	$headline = action_lead_field( 'headline' );
	$subtitle = action_lead_field( 'subtitle' );
	$desc     = action_lead_field( 'desc' );
	$type     = action_lead_field( 'type' );

	$position = action_lead_field( 'position' );
	$bg_color = action_lead_field( 'bg_color' );
	$bg_image = action_lead_field( 'bg_image' );
	$text_scheme = action_lead_field( 'text_color_scheme' );

	$title        = action_lead_field( 'title' );
	$col_subtitle = action_lead_field( 'col_subtitle' );

	$button_action = action_lead_field( 'button_action' );
	$button_link   = action_lead_field( 'button_link' );
	$button_text   = action_lead_field( 'button_text' );
	$button_style  = action_lead_field( 'button_style' );

	$checkmarks       = action_lead_field( 'checkmarks' );
	$checkmarks_check = ! empty( $checkmarks ) ? array_filter( (array) $checkmarks[0] ) ? true : false : '';

	// design classes
	$bg_image_classes   = ! empty( $bg_image ) ? ' image-overlay' : '';
	$text_color_classes = $text_scheme == 'white' || $text_scheme == '' ? 'text-white' : 'text-dark';

	// helpers
	$action_classes = md_design_classes( $position ) . $bg_image_classes;
	$action_bg      = md_design_background( $bg_color, $bg_image );

	$content_box = action_lead_content_box_classes( $type, $position, $button_style );
	$content     = action_lead_content_classes( $type, $position, $button_style );
	$sidebar     = action_lead_sidebar_classes( $type, $position, $button_style );
	$block       = action_lead_block( $type, $position, $button_style, array(
		'headline' => $headline,
		'subtitle' => $subtitle,
		'desc'     => $desc
	) );
?>

<div id="action-lead-<?php the_ID(); ?>" class="action-lead<?php echo $action_classes; ?> <?php echo $block; ?> format-text-main"<?php echo $action_bg; ?>>
	<div class="inner">

		<div class="action-lead-content <?php echo $content_box; ?> clear">

			<?php if ( action_lead_has_intro() ) : ?>

				<!-- Intro -->

				<div class="content<?php echo $content; ?> <?php echo $text_color_classes; ?> links-white">

					<?php if ( ! empty( $subtitle ) ) : ?>
						<div class="mb-half caps">
							<small><?php esc_html_e( $subtitle ); ?></small>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $headline ) ) : ?>
						<h2><?php esc_html_e( $headline ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $desc ) ) :
						$spacing_tweak = $position != 'md_hook_content' && empty( $headline ) && empty( $subtitle ) ? ' mt-half' : ''
					?>

						<?php if ( empty( $type ) || $type == 'button_simple' ) : ?>
							<div class="small-title<?php echo $spacing_tweak; ?>"><?php echo wpautop( $desc ); ?></div>
						<?php else : ?>
							<?php echo wpautop( $desc ); ?>
						<?php endif; ?>

					<?php endif; ?>

				</div>

			<?php endif; ?>

			<!-- Sidebar -->

			<?php if ( $type == 'button_simple' && $button_text ) :
				$html  = $button_link ? 'a href="' . esc_url( $button_link ) . '"' : 'span';
				$_html = $button_link ? 'a' : 'span';
			?>

				<div class="sidebar-button<?php echo $sidebar; ?>">
					<?php md_button( array(
						'action'  => $button_action,
						'link'    => $button_link,
						'text'    => $button_text,
						'edd_id'  => action_lead_field( 'edd_button' ),
						'woo_id'  => action_lead_field( 'woo_button' ),
						'popup'   => action_lead_field( 'button_popup' ),
						'classes' => 'orange mb-half'
					) ); ?>
				</div>

			<?php elseif ( $type == 'button' ) : ?>

				<!-- Button Box -->

				<div class="sidebar sidebar-button-box<?php echo $sidebar; ?> text-dark">
					<div class="col-style col-featured box">

						<div class="content-item block-mid text-center">

							<?php if ( ! empty( $col_subtitle ) ) : ?>
								<small class="text-sec caps display-block mb-half"><?php esc_html_e( $col_subtitle ); ?></small>
							<?php endif; ?>

							<?php if ( ! empty( $title ) ) : ?>
								<h3 class="mb-single"><?php esc_html_e( $title ); ?></h3>
							<?php endif; ?>

							<?php md_button( array(
								'action'  => $button_action,
								'link'    => $button_link,
								'text'    => $button_text,
								'edd_id'  => action_lead_field( 'edd_button' ),
								'woo_id'  => action_lead_field( 'woo_button' ),
								'popup'   => action_lead_field( 'button_popup' ),
								'classes' => 'orange width-full mb-half'
							) ); ?>

						</div>

						<?php if ( $checkmarks_check ) :?>

							<ul class="format-text-sec block-single list text-left">
								<?php foreach ( $checkmarks as $checkmark ) : ?>
									<li><i class="md-icon md-icon-ok-circled"></i> <?php echo $checkmark['item']; ?></li>
								<?php endforeach; ?>
							</ul>

						<?php endif; ?>

					</div>
				</div>

			<?php endif; ?>

			<?php if ( $type == 'email' && ( action_lead_field( 'email_list' ) || action_lead_field( 'email_code' ) ) ) :
				$email_fields = action_lead_email_form();

				$email_classes = ! empty ( $email_fields['email_image'] ) ? ' box-dark image-overlay' : ' box col-featured col-style form-highlight';
				$args = array(
					'classes'      => "block-mid$email_classes",
					'before_title' => '<p class="med-title">',
					'after_title'  => '</p>'
				);
			?>

				<!-- Email Form -->

				<div class="sidebar sidebar-email<?php echo $sidebar; ?> text-dark">
					<div class="text-center">

						<?php md_email_form( $email_fields, $args ); ?>

					</div>
				</div>

			<?php endif; ?>

		</div>

	</div>
</div>