<?php get_template_part( 'html' ); ?>

<?php md_hook_before_html(); ?>

<?php if ( md_has_header() ) : ?>

	<header id="header" class="header header-standard" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
		<div class="inner">
      			<span id="header-menu-trigger" class="header-menu-trigger close-on-desktop md-icon md-icon-menu">&nbsp;&nbsp;Menu</span>

      			<span id="header-nav" class="header-nav header-menu menu close-on-tablet">

              <a href="/who/" class="header-nav-item">WHO</a>
              <a href="/why/" class="header-nav-item">WHY</a>
              <a href="/what/" class="header-nav-item">WHAT</a>
              <a href="/contact/" class="header-nav-item">CONTACT</a>

      			</span>

		</div>
	</header>

<?php endif; ?>

<?php md_hook_after_header(); ?>

<?php md_hook_before_content_box(); ?>
