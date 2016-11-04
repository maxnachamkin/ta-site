<?php if ( md_has_sidebar() ) : ?>

	<aside class="sidebar<?php echo md_sidebar_classes(); ?>" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">

		<?php md_sidebar(); ?>

	</aside>

<?php endif; ?>