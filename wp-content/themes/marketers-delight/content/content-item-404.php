<article id="content-404" class="has-inline-post-thumbnail" <?php md_article_schema(); ?>>

	<header class="content-item-headline content-item<?php echo md_headline_classes(); ?>"<?php echo md_featured_image_cover(); ?>>
		<div class="<?php echo md_headline_inner_classes(); ?>">

			<h1 class="headline"><?php _e( 'Nothing Found', 'md' ); ?></h1>

		</div>
	</header>

	<div class="content-item-text content-item inner links-main" itemprop="text">
		<div class="<?php md_content_text_classes(); ?> clear">

			<?php if ( md_has_inline_featured_image() ) : ?>
				<?php md_featured_image(); ?>
			<?php endif; ?>

			<p><?php _e( 'It looks like the page you\'re looking for isn\'t here! See if the tools below can help you find what you\'re looking for:', 'md' ); ?></p>

			<div class="content-item-404-search mb-double">

				<h4><?php _e( 'Search the Website', 'md' ); ?></h4>

				<p><?php _e( 'If you have an idea of what you\'re looking for, try typing in a few of the best keywords you can think of to get the most relevant search results possible.', 'md' ); ?></p>

				<?php get_search_form(); ?>

			</div>

			<?php
				$instance = array(
					'title' => __( 'Browse Latest Posts', 'md' )
				);
				$args = array(
					'before_widget' => '<div class="content-item-404-recent-posts mb-double">',
					'after_widget'  => '</div>',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>'
				);

				the_widget( 'WP_Widget_Recent_Posts', $instance, $args );
			?>

			<?php
				$instance = array(
					'title'    => __( 'Browse By Month', 'md' ),
					'dropdown' => 1
				);
				$args = array(
					'before_widget' => '<div class="content-item-404-archives mb-double">',
					'after_widget'  => '</div>',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>'
				);

				the_widget( 'WP_Widget_Archives', $instance, $args );
			?>

		</div>
	</div>

</article>