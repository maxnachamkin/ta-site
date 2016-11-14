<?php get_template_part( 'html' ); ?>

<?php md_hook_before_html(); ?>

<?php if ( md_has_header() ) : ?>


<?php if ( is_page ( 15 ) ) : ?>
	<header id="header" class="header header-standard" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
		<div class="inner">



	              <a href="/who/" class="header-nav-item">WHO</a>
	              <a href="/why/" class="header-nav-item">WHY</a>
	              <a href="/what/" class="header-nav-item">WHAT</a>
	              <a href="/contact/" class="header-nav-item">CONTACT</a>


		</div>
	</header>

<?php else : ?>

	<header id="pageheader" class="pageheader header-standard" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
		<div class="inner">
			<div align="center">
								<a href="/"><img class="page-header-logo" src="/wp-content/themes/ta-child-theme/img/logo.png" height="100px" /></a>
								<br />
								<a href="/who/" class="page-header-nav-item">WHO</a>
								<a href="/why/" class="page-header-nav-item">WHY</a>
								<a href="/what/" class="page-header-nav-item">WHAT</a>
								<a href="/contact/" class="page-header-nav-item">CONTACT</a>
			</div>
		</div>
	</header>


<?php endif; ?>



<?php endif; ?>

<?php md_hook_after_header(); ?>

<?php md_hook_before_content_box(); ?>
