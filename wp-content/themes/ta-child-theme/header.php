<?php get_template_part( 'html' ); ?>

<?php md_hook_before_html(); ?>

<?php if ( md_has_header() ) : ?>

	<header id="header" class="header header-standard" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
		<div class="inner">

			<?php get_template_part( 'content/logo' ); ?>

			<span id="header-menu-trigger" class="header-menu-trigger close-on-desktop md-icon md-icon-menu">&nbsp;&nbsp;Menu</span>

			<span id="header-nav" class="header-nav header-menu menu close-on-tablet">
				<span class="header-left">
					<a href="/category/general/" class="header-nav-item<?php echo kol_menu_active( 'general' ); ?>">General</a>
					<a href="/cart/" class="header-nav-item<?php echo kol_menu_active( 'cart' ); ?>">Cart</a>
					<a href="/portfolio/" class="header-nav-item<?php echo kol_menu_active( 'portfolio' ); ?>">My Work</a>
				</span>

				<span class="header-right">
					<a href="/wordpress-themes/" class="header-nav-item<?php echo kol_menu_active( 'wordpress-themes' ); ?>">Themes</a>
					<a href="/wordpress-plugins/" class="header-nav-item<?php echo kol_menu_active( 'wordpress-plugins' ); ?>">Plugins</a>
					<a href="/services/" class="header-nav-item<?php echo kol_menu_active( 'services' ); ?>">Services</a>
				</span>
			</span>

		</div>
	</header>

<?php endif; ?>

<?php md_hook_after_header(); ?>

<?php md_hook_before_content_box(); ?>
