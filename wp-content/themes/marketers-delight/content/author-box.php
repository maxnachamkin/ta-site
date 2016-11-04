<?php
	$link       = get_the_author_meta( 'url' );
	$desc       = get_the_author_meta( 'description' );
	$visit_text = __( 'Visit my website', 'md' );
?>

<div class="author-box inner content-item links-main">
	<div class="<?php echo md_content_block(); ?> clear">

		<?php if ( get_option( 'show_avatars' ) ) : ?>
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 130 ); ?>
			</div>
		<?php endif; ?>

		<div class="author-content">

			<p class="author-name small-title mb-half">
				<?php _e( 'About', 'md' ); ?> <?php the_author_meta( 'display_name' ); ?>
			</p>

			<div class="author-bio">

				<?php if ( ! empty( $desc ) ) : ?>
					<?php echo wpautop( $desc ); ?>
				<?php endif; ?>

				<?php if ( ! empty( $link ) ) : ?>
					<a href="<?php echo esc_url( $link ); ?>" class="author-link button orange" title="<?php echo $visit_text; ?>"><?php echo "$visit_text &rarr;"; ?></a>
				<?php endif; ?>

			</div>

		</div>

	</div>
</div>