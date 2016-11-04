<div class="content-item-text content-item inner links-main" itemprop="text">
	<div class="<?php md_content_text_classes(); ?> clear">

		<?php md_hook_content_item_text_top(); ?>

		<?php if ( md_has_inline_featured_image() ) : ?>
			<?php md_featured_image(); ?>
		<?php endif; ?>

		<?php the_content( __( 'Continue reading &rarr;', 'md' ) ); ?>

	</div>
</div>