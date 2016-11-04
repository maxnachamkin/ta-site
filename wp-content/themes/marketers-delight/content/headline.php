<<?php md_content_item_headline_html(); ?> class="content-item-headline<?php echo md_headline_classes(); ?>"<?php echo md_featured_image_cover(); ?>>
	<div class="<?php echo md_headline_inner_classes(); ?>">

		<?php md_hook_before_headline(); ?>

		<?php if ( is_singular() ) : ?>

			<h1 class="headline" itemprop="headline"><?php the_title(); ?></h1>

		<?php else : ?>

			<h2 class="headline" itemprop="headline">
				<a href="<?php the_permalink(); ?>" title="<?php echo sprintf( __( 'Permanent Link to %s', 'md' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a>
			</h2>

		<?php endif; ?>

		<?php md_hook_after_headline(); ?>

	</div>

	<?php md_hook_content_item_headline_bottom(); ?>

</<?php md_content_item_headline_html(); ?>>