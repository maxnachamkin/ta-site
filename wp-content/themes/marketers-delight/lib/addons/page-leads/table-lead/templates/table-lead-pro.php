<?php
	$headline = table_lead_field( 'headline' );
	$desc     = table_lead_field( 'desc' );

	$position    = table_lead_field( 'position' );
	$bg_color    = table_lead_field( 'bg_color' );
	$bg_image    = table_lead_field( 'bg_image' );
	$text_scheme = table_lead_field( 'text_color_scheme' );

	$bg_image_classes   = ! empty( $bg_image ) ? ' image-overlay' : '';
	$text_color_classes = $text_scheme != 'white' ? 'text-dark' : 'text-white';
	$padding_intro      = has_table_lead_columns() ? ' pb-none' : '';

	$block         = md_design_block( $position );
	$table_classes = md_design_classes( $position ) . $bg_image_classes;
	$table_bg      = md_design_background( $bg_color, $bg_image );

	$col1_listing       = table_lead_field( 'col1_listing' );
	$col1_headline      = table_lead_field( 'col1_headline' );
	$col1_subtitle      = table_lead_field( 'col1_subtitle' );
	$col1_price         = table_lead_field( 'col1_price' );
	$col1_price_term    = table_lead_field( 'col1_price_term' );
	$col1_button_action = table_lead_field( 'col1_button_action' );
	$col1_button_text   = table_lead_field( 'col1_button_text' );
	$col1_button_link   = table_lead_field( 'col1_button_link' );
	$col1_footnotes     = table_lead_field( 'col1_footnotes' );

	$col2_listing       = table_lead_field( 'col2_listing' );
	$col2_headline      = table_lead_field( 'col2_headline' );
	$col2_subtitle      = table_lead_field( 'col2_subtitle' );
	$col2_price         = table_lead_field( 'col2_price' );
	$col2_price_term    = table_lead_field( 'col2_price_term' );
	$col2_button_action = table_lead_field( 'col2_button_action' );
	$col2_button_text   = table_lead_field( 'col2_button_text' );
	$col2_button_link   = table_lead_field( 'col2_button_link' );
	$col2_footnotes     = table_lead_field( 'col2_footnotes' );

	$col3_listing       = table_lead_field( 'col3_listing' );
	$col3_headline      = table_lead_field( 'col3_headline' );
	$col3_subtitle      = table_lead_field( 'col3_subtitle' );
	$col3_price         = table_lead_field( 'col3_price' );
	$col3_price_term    = table_lead_field( 'col3_price_term' );
	$col3_button_action = table_lead_field( 'col3_button_action' );
	$col3_button_text   = table_lead_field( 'col3_button_text' );
	$col3_button_link   = table_lead_field( 'col3_button_link' );
	$col3_footnotes     = table_lead_field( 'col3_footnotes' );

	$payment      = table_lead_field( 'show_payment' );
	$show_payment = $payment['cards'];
	$notice_text  = table_lead_field( 'notice_text' );
?>

<div id="table-lead-<?php the_ID(); ?>" class="table-lead<?php echo $table_classes; ?> box-sec links-main"<?php echo $table_bg; ?>>
	<div class="inner">

		<!-- Intro -->

		<?php if ( $headline || $desc ) : ?>

			<div class="table-lead-intro <?php echo $block; ?> <?php echo "$text_color_classes{$padding_intro}"; ?> format-text-main text-center">

				<!-- Headline -->

				<?php if ( $headline ) : ?>
					<h1 class="table-lead-headline headline mb-half"><?php echo $headline; ?></h1>
				<?php endif; ?>

				<!-- Description -->

				<?php if ( $desc ) : ?>
					<div class="table-lead-desc small-title text-sec">
						<?php echo wpautop( $desc ); ?>
					</div>
				<?php endif; ?>

				<i class="md-icon md-icon-angle-down text-sec large-title"></i>

			</div>

		<?php endif; ?>

		<!-- Columns -->

		<?php if ( has_table_lead_columns() ) :
			$columns_block = md_design_columns_block( $position );
			$columns_count = table_lead_columns();
			$columns       = $position == 'md_hook_content' ? $columns_count : 3;
		?>

			<div class="table-lead-columns page-leads-columns columns-<?php echo $columns; ?> columns-flex<?php echo $columns_block; ?> pb-none format-text-sec text-center mb-half">

				<!-- Column 1 -->

				<?php if ( has_table_lead_col( 1 ) ) : ?>

					<div class="col col1 box-style mb-single">

						<?php if ( $col1_headline || $col1_price ) : ?>

							<!-- Head -->

							<?php if ( $col1_subtitle ) : ?>
								<p class="table-lead-featured caps block-half mb-none"><?php esc_html_e( $col1_subtitle ); ?></p>
							<?php endif; ?>

							<div class="table-lead-head box-dark block-double format-text-sec">

								<?php if ( $col1_headline ) : ?>
									<p class="table-lead-head-title caps"><?php esc_html_e( $col1_headline ); ?></p>
								<?php endif; ?>

								<?php if ( $col1_price ) : ?>
									<p class="table-lead-head-price">

										<span class="head-price large-title"><?php esc_html_e( $col1_price ); ?></span>

										<?php if ( $col1_price_term ) : ?>
											<span class="head-desc text-sec"><?php esc_html_e( $col1_price_term ); ?></span>
										<?php endif; ?>

									</p>
								<?php endif; ?>

								<span id="table-lead-control-1" class="table-lead-control">
									<?php _e( 'View Details', 'table-lead' ); ?> <i id="control-icon-1" class="md-icon md-icon-angle-down"></i>
								</span>

							</div>

						<?php endif; ?>

						<!-- Listing -->

						<div id="table-lead-content-1" class="table-lead-content block-mid close-on-mobile">

							<?php if ( ! empty( $col1_listing[0]['list_item'] ) ) : ?>

								<ul class="col-list list">

									<?php foreach ( $col1_listing as $listing ) : ?>
										<li><?php echo $listing['list_item']; ?></li>
									<?php endforeach; ?>

								</ul>

							<?php endif; ?>

							<?php if ( $col1_button_text || $col1_button_action || $col1_footnotes ) : ?>

								<!-- Button -->

								<?php md_button( array(
									'action'  => $col1_button_action,
									'link'    => $col1_button_link,
									'text'    => $col1_button_text,
									'edd_id'  => table_lead_field( 'col1_edd_button' ),
									'woo_id'  => table_lead_field( 'col1_woo_button' ),
									'popup'   => table_lead_field( 'col1_button_popup' ),
									'classes' => 'width-full mb-half'
								) ); ?>

								<!-- Footnotes -->

								<?php if ( $col1_footnotes ) : ?>
									<small class="text-sec links-sec"><?php echo $col1_footnotes; ?></small>
								<?php endif; ?>

							<?php endif; ?>

						</div>

					</div>

				<?php endif; ?>

				<!-- Column 2 -->

				<?php if ( has_table_lead_col( 2 ) ) : ?>

					<div class="col col2 box-style mb-single">

						<?php if ( $col2_headline || $col2_price ) : ?>

							<!-- Head -->

							<?php if ( $col2_subtitle ) : ?>
								<p class="table-lead-featured caps block-half mb-none"><?php esc_html_e( $col2_subtitle ); ?></p>
							<?php endif; ?>

							<div class="table-lead-head box-dark block-double format-text-sec">

								<?php if ( $col2_headline ) : ?>
									<p class="table-lead-head-title caps"><?php esc_html_e( $col2_headline ); ?></p>
								<?php endif; ?>

								<?php if ( $col2_price ) : ?>
									<p class="table-lead-head-price">

										<span class="head-price large-title"><?php esc_html_e( $col2_price ); ?></span>

										<?php if ( $col2_price_term ) : ?>
											<span class="head-desc text-sec"><?php esc_html_e( $col2_price_term ); ?></span>
										<?php endif; ?>

									</p>
								<?php endif; ?>

								<span id="table-lead-control-2" class="table-lead-control">
									<?php _e( 'View Details', 'table-lead' ); ?> <i id="control-icon-2" class="md-icon md-icon-angle-down"></i>
								</span>

							</div>

						<?php endif; ?>

						<!-- Listing -->

						<div id="table-lead-content-2" class="table-lead-content block-mid close-on-mobile">

							<?php if ( $col2_listing ) : ?>

								<ul class="col-list list">

									<?php foreach ( $col2_listing as $listing ) : ?>
										<li><?php echo $listing['list_item']; ?></li>
									<?php endforeach; ?>

								</ul>

							<?php endif; ?>

							<?php if ( $col2_button_text || $col2_button_action || $col2_footnotes ) : ?>

								<!-- Button -->

								<?php md_button( array(
									'action'  => $col2_button_action,
									'link'    => $col2_button_link,
									'text'    => $col2_button_text,
									'edd_id'  => table_lead_field( 'col2_edd_button' ),
									'woo_id'  => table_lead_field( 'col2_woo_button' ),
									'popup'   => table_lead_field( 'col2_button_popup' ),
									'classes' => 'width-full mb-half'
								) ); ?>

								<!-- Footnotes -->

								<?php if ( $col2_footnotes ) : ?>
									<small class="text-sec links-sec"><?php echo $col2_footnotes; ?></small>
								<?php endif; ?>

							<?php endif; ?>

						</div>

					</div>

				<?php endif; ?>

				<!-- Column 3 -->

				<?php if ( has_table_lead_col( 3 ) ) : ?>

					<div class="col col3 box-style mb-single">

						<?php if ( $col3_headline || $col3_price ) : ?>

							<!-- Head -->

							<?php if ( $col3_subtitle ) : ?>
								<p class="table-lead-featured caps block-half mb-none"><?php esc_html_e( $col3_subtitle ); ?></p>
							<?php endif; ?>

							<div class="table-lead-head box-dark block-double format-text-sec">

								<?php if ( $col3_headline ) : ?>
									<p class="table-lead-head-title caps"><?php esc_html_e( $col3_headline ); ?></p>
								<?php endif; ?>

								<?php if ( $col3_price ) : ?>
									<p class="table-lead-head-price">

										<span class="head-price large-title"><?php esc_html_e( $col3_price ); ?></span>

										<?php if ( $col3_price_term ) : ?>
											<span class="head-desc text-sec"><?php esc_html_e( $col3_price_term ); ?></span>
										<?php endif; ?>

									</p>
								<?php endif; ?>

								<span id="table-lead-control-3" class="table-lead-control">
									<?php _e( 'View Details', 'table-lead' ); ?> <i id="control-icon-3" class="md-icon md-icon-angle-down"></i>
								</span>

							</div>

						<?php endif; ?>

						<!-- Listing -->

						<div id="table-lead-content-3" class="table-lead-content block-mid close-on-mobile">

							<?php if ( $col3_listing ) : ?>

								<ul class="col-list list">

									<?php foreach ( $col3_listing as $listing ) : ?>
										<li><?php echo $listing['list_item']; ?></li>
									<?php endforeach; ?>

								</ul>

							<?php endif; ?>

							<?php if ( $col3_button_text || $col3_button_action || $col3_footnotes ) : ?>

								<!-- Button -->

								<?php md_button( array(
									'action'  => $col3_button_action,
									'link'    => $col3_button_link,
									'text'    => $col3_button_text,
									'edd_id'  => table_lead_field( 'col3_edd_button' ),
									'woo_id'  => table_lead_field( 'col3_woo_button' ),
									'popup'   => table_lead_field( 'col3_button_popup' ),
									'classes' => 'width-full mb-half'
								) ); ?>

								<!-- Footnotes -->

								<?php if ( $col3_footnotes ) : ?>
									<small class="text-sec links-sec"><?php echo $col3_footnotes; ?></small>
								<?php endif; ?>

							<?php endif; ?>

						</div>

					</div>

				<?php endif; ?>

			</div>

		<?php endif; ?>

		<!-- Footer -->

		<?php if ( $show_payment || $notice_text ) : ?>

			<div class="table-lead-foot block-mid-bot block-double-lr <?php echo $text_color_classes; ?> links-main<?php echo ( ! $show_payment || ! $notice_text ? ' text-center' : '' ); ?>">

				<?php if ( $notice_text ) :
					$align = $show_payment && $notice_text ? ' alignright' : '';
				?>
					<div class="table-lead-foot-desc<?php esc_attr_e( $align ); ?> text-sec">
						<?php echo wpautop( $notice_text ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $show_payment ) : ?>
					<?php $src = ( ! file_exists( get_stylesheet_directory() . '/images/table-lead-credit-cards.png' ) ? PAGE_LEADS_URL . 'table-lead/images/table-lead-credit-cards.png' : get_stylesheet_directory_uri() . '/images/table-lead-credit-cards.png' ); ?>

					<img src="<?php echo esc_url( $src ); ?>" class="table-lead-foot-payments" alt="<?php _e( 'Accepted payment methods', 'email-lead' ); ?>" />
				<?php endif; ?>

			</div>

		<?php endif; ?>

	</div>
</div>