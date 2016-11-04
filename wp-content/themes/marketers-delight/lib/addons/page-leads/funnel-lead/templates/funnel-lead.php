<?php
	$headline = funnel_lead_field( 'headline' );
	$subtitle = funnel_lead_field( 'subtitle' );
	$desc     = funnel_lead_field( 'desc' );

	$position    = funnel_lead_field( 'position' );
	$block       = md_design_block( $position );
	$bg_color    = funnel_lead_field( 'bg_color' );
	$bg_image    = funnel_lead_field( 'bg_image' );
	$text_scheme = funnel_lead_field( 'text_color_scheme' );

	$bg_image_classes   = ! empty( $bg_image ) ? ' image-overlay' : '';
	$text_color_classes = $text_scheme != 'white' ? 'text-dark' : 'text-white';
	$columns_padding    = has_funnel_lead_columns() ? ' pb-none' : '';

	$funnel_classes   = md_design_classes( $position ) . $bg_image_classes;
	$funnel_bg        = md_design_background( $bg_color, $bg_image );

	$col1_headline    = funnel_lead_field( 'col1_headline' );
	$col1_desc        = funnel_lead_field( 'col1_desc' );
	$col1_button_action = funnel_lead_field( 'col1_button_action' );
	$col1_button_text = funnel_lead_field( 'col1_button_text' );
	$col1_button_link = funnel_lead_field( 'col1_button_link' );
	$col1_image       = funnel_lead_field( 'col1_image' );

	$col2_headline    = funnel_lead_field( 'col2_headline' );
	$col2_desc        = funnel_lead_field( 'col2_desc' );
	$col2_button_action = funnel_lead_field( 'col2_button_action' );
	$col2_button_text = funnel_lead_field( 'col2_button_text' );
	$col2_button_link = funnel_lead_field( 'col2_button_link' );
	$col2_image       = funnel_lead_field( 'col2_image' );

	$col3_headline    = funnel_lead_field( 'col3_headline' );
	$col3_desc        = funnel_lead_field( 'col3_desc' );
	$col3_button_action = funnel_lead_field( 'col3_button_action' );
	$col3_button_text = funnel_lead_field( 'col3_button_text' );
	$col3_button_link = funnel_lead_field( 'col3_button_link' );
	$col3_image       = funnel_lead_field( 'col3_image' );
?>

<div id="funnel-lead-<?php the_ID(); ?>" class="funnel-lead<?php echo $funnel_classes; ?> box-sec links-main text-center"<?php echo $funnel_bg; ?>>
	<div class="inner">

		<!-- Intro -->

		<?php if ( $headline || $desc ) : ?>

			<div class="funnel-lead-intro <?php echo $block; ?> <?php echo "$text_color_classes{$columns_padding}"; ?> format-text-main">

				<?php if ( $subtitle ) : ?>
					<p class="text-sec caps"><?php echo $subtitle; ?></p>
				<?php endif; ?>

				<?php if ( $headline ) : ?>
					<h1 class="funnel-headline headline mb-half"><?php echo $headline; ?></h1>
				<?php endif; ?>

				<?php if ( stripslashes( $desc ) ) : ?>
					<div class="funnel-lead-desc small-title text-sec">
						<?php echo wpautop( $desc ); ?>
					</div>
				<?php endif; ?>

				<i class="md-icon md-icon-angle-down text-sec large-title"></i>

			</div>

		<?php endif; ?>

		<!-- Columns -->

		<?php if ( has_funnel_lead_columns() ) :
			$columns_block = md_design_columns_block( $position );
			$columns_count = funnel_lead_columns();
			$columns       = $position == 'md_hook_content' ? $columns_count : 3;
		?>

			<div class="funnel-lead-columns page-leads-columns<?php echo "$columns_block{$columns_padding}"; ?> columns-flex columns-single columns-<?php echo $columns; ?> format-text-sec">

				<!-- Column 1 -->

				<?php if ( has_funnel_lead_col( 1 ) ) : ?>

					<div class="col col1">
						<div class="box-style">

							<?php if ( $col1_headline || $col1_desc || $col1_button_text ) : ?>

								<div class="col-content<?php echo ( ( $col1_button_action || $col1_button_text ) && $col1_image ? ' has-col-button' : '' ); ?> block-mid">

									<!-- Headline -->

									<?php if ( $col1_headline ) : ?>
										<h4 class="col-title box-title"><?php echo $col1_headline; ?></h4>
									<?php endif; ?>

									<!-- Description -->

									<?php if ( $col1_desc ) : ?>
										<div class="col-desc mb-single">
											<?php echo wpautop( stripslashes( $col1_desc ) ); ?>
										</div>
									<?php endif; ?>

									<!-- Button -->

									<?php md_button( array(
										'action'  => $col1_button_action,
										'link'    => $col1_button_link,
										'text'    => $col1_button_text,
										'edd_id'  => funnel_lead_field( 'col1_edd_button' ),
										'woo_id'  => funnel_lead_field( 'col1_woo_button' ),
										'popup'   => funnel_lead_field( 'col1_button_popup' ),
										'classes' => 'width-full mb-half'
									) ); ?>

								</div>

							<?php endif; ?>

							<!-- Image -->

							<?php if ( $col1_image ) : ?>

								<div class="col-image">

									<?php funnel_lead_col_image( $col1_image, 'md-banner', $col1_button_link ); ?>

								</div>

							<?php endif; ?>

						</div>
					</div>

				<?php endif; ?>

				<!-- Column 2 -->

				<?php if ( has_funnel_lead_col( 2 ) ) : ?>

					<div class="col col2">
						<div class="box-style">

							<?php if ( $col2_headline || $col2_desc || $col2_button_text ) : ?>

								<div class="col-content<?php echo ( ( $col2_button_action || $col2_button_text ) && $col2_image ? ' has-col-button' : '' ); ?> block-mid">

									<!-- Headline -->

									<?php if ( $col2_headline ) : ?>
										<h4 class="col-title box-title"><?php echo $col2_headline; ?></h4>
									<?php endif; ?>

									<!-- Description -->

									<?php if ( $col2_desc ) : ?>
										<div class="col-desc mb-single">
											<?php echo wpautop( stripslashes( $col2_desc ) ); ?>
										</div>
									<?php endif; ?>

									<!-- Button -->

									<?php md_button( array(
										'action'  => $col2_button_action,
										'link'    => $col2_button_link,
										'text'    => $col2_button_text,
										'edd_id'  => funnel_lead_field( 'col2_edd_button' ),
										'woo_id'  => funnel_lead_field( 'col2_woo_button' ),
										'popup'   => funnel_lead_field( 'col2_button_popup' ),
										'classes' => 'width-full mb-half'
									) ); ?>

								</div>

							<?php endif; ?>

							<!-- Image -->

							<?php if ( $col2_image ) : ?>

								<div class="col-image">

									<?php funnel_lead_col_image( $col2_image, 'md-banner', $col2_button_link ); ?>

								</div>

							<?php endif; ?>

						</div>
					</div>

				<?php endif; ?>

				<!-- Column 3 -->

				<?php if ( has_funnel_lead_col( 3 ) ) : ?>

					<div class="col col3">
						<div class="box-style">

							<?php if ( $col3_headline || $col3_desc || $col3_button_text ) : ?>

								<div class="col-content<?php echo ( ( $col3_button_action || $col3_button_text ) && $col3_image ? ' has-col-button' : '' ); ?> block-mid">

									<!-- Headline -->

									<?php if ( $col3_headline ) : ?>
										<h4 class="col-title box-title"><?php echo $col3_headline; ?></h4>
									<?php endif; ?>

									<!-- Description -->

									<?php if ( $col3_desc ) : ?>
										<div class="col-desc mb-single">
											<?php echo wpautop( stripslashes( $col3_desc ) ); ?>
										</div>

									<?php endif; ?>

									<!-- Button -->

									<?php md_button( array(
										'action'  => $col3_button_action,
										'link'    => $col3_button_link,
										'text'    => $col3_button_text,
										'edd_id'  => funnel_lead_field( 'col3_edd_button' ),
										'woo_id'  => funnel_lead_field( 'col3_woo_button' ),
										'popup'   => funnel_lead_field( 'col3_button_popup' ),
										'classes' => 'width-full mb-half'
									) ); ?>

								</div>

							<?php endif; ?>

							<!-- Image -->

							<?php if ( $col3_image ) : ?>

								<div class="col-image">

									<?php funnel_lead_col_image( $col3_image, 'md-banner', $col3_button_link ); ?>

								</div>

							<?php endif; ?>

						</div>
					</div>

				<?php endif; ?>

			</div>

		<?php endif; ?>

	</div>
</div>