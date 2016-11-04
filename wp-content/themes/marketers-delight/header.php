<?php get_template_part( 'html' ); ?>

<?php md_hook_before_html(); ?>

<?php if ( md_has_header() ) : ?>

	<header id="header" class="header<?php md_header_classes(); ?>" role="banner" itemscope itemtype="http://schema.org/WPHeader">
		<div class="inner">

			<?php md_hook_header(); ?>

		</div>
	</header>

<?php endif; ?>

<?php md_hook_after_header(); ?>

<?php md_hook_before_content_box(); ?>