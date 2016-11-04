<?php if ( md_has_logo() ) : ?>

	<<?php md_logo_html(); ?> class="logo" itemprop="headline">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( get_bloginfo( 'name', 'display' ) ); ?>">

			<?php if ( get_theme_mod( 'md_logo' ) ) : ?>

				<img src="<?php echo esc_url( get_theme_mod( 'md_logo' ) ); ?>" alt="<?php esc_attr_e( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="logo">

			<?php else : ?>

				<?php bloginfo( 'name' ); ?>

			<?php endif; ?>

		</a>
	</<?php echo md_logo_html(); ?>>

<?php endif; ?>

<?php if ( md_has_tagline() ) : ?>
	<p class="tagline" itemprop="description"><?php bloginfo( 'description' ); ?></p>
<?php endif; ?>