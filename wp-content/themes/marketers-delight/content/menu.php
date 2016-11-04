<?php if ( md_has_menu() ) : ?>

	<span id="header-menu-trigger" class="header-menu-trigger md-icon md-icon-menu"><?php _e( 'Menu', 'md' ); ?></span>

	<div id="header-menu" class="header-menu-wrap">

		<?php md_hook_before_header_menu(); ?>

		<nav class="header-menu links-sec" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">

			<?php wp_nav_menu( array(
				'theme_location' => 'header',
				'container'      => false,
				'fallback_cb'    => false,
				'menu_class'     => 'menu menu-header',
				'walker'         => new md_menu_walker( true, false )
			) ); ?>

		</nav>

	</div>

<?php endif; ?>