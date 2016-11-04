<?php
	$twitter = get_the_author_meta( 'twitter' );
	$layout  = md_layout();
	$byline  = $layout['byline']['items'];
?>

<div class="byline<?php echo md_byline_classes(); ?>">

	<?php if ( in_array( 'author', $byline ) ) : ?>

		<!-- Author -->

		<span class="byline-author byline-item">

			<em><?php _e( 'by', 'md' ); ?></em>

			<span class="links-rgb-dark" itemprop="author" itemscope itemtype="http://schema.org/Person">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" itemprop="url" rel="author"><span class="byline-author-name" itemprop="name"><?php esc_html( the_author() ); ?></span></a>
			</span>

			<?php if ( ! empty( $twitter ) ) : ?>
				&nbsp;&nbsp;<a href="//twitter.com/<?php echo esc_html( $twitter ); ?>/" class="byline-twitter" target="_blank"><i class="byline-item-icon md-icon md-icon-twitter"></i></a>
			<?php endif; ?>

		</span>

	<?php endif; ?>

	<?php if ( in_array( 'date', $byline ) ) : ?>

		<!-- Date -->

		<span class="byline-date byline-item"><i class="byline-item-icon md-icon md-icon-clock"></i> <time datetime="<?php the_date( 'c' ); ?>" itemprop="datePublished"><?php the_time( get_option( 'date_format' ) ); ?></time></span>

	<?php endif; ?>

	<?php do_action( 'md_hook_byline_after_date' ); ?>

	<?php if ( md_has_comments() && in_array( 'comments', $byline ) ) : ?>

		<!-- Comments -->

		<span class="byline-comments byline-item"><a href="<?php comments_link(); ?>"><i class="byline-item-icon md-icon md-icon-chat"></i> <?php comments_number( '0', '1', '%' ); ?></a></span>

	<?php endif; ?>

	<?php if ( in_array( 'edit', $byline ) ) : ?>

		<!-- Edit -->

		<?php edit_post_link( '<i class="byline-item-icon md-icon md-icon-pencil"></i>', '<span class="byline-edit byline-item">', '</span>' ); ?>

	<?php endif; ?>

	<?php md_hook_byline_bottom(); ?>

</div>