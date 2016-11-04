<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content/content-item', get_post_format() ); ?>
	<?php endwhile; ?>

<?php else : ?>

	<?php get_template_part( 'content/content-item-404' ); ?>

<?php endif; ?>