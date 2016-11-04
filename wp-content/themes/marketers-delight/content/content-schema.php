<div id="content-<?php the_id(); ?>-schema" class="content-schema">

	<?php
		$settings = get_option( 'md_settings' );
		$logo     = ! empty( $settings['schema_logo'] ) ? $settings['schema_logo'] : '';

		if ( ! empty( $logo ) ) :
			$id    = md_url_image_id( $logo );
			$image = wp_get_attachment_image_src( $id, 'full' ); ?>

		<div itemscope itemprop="publisher" itemtype="https://schema.org/Organization">
			<div itemscope itemprop="logo" itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo esc_url( $image[0] ); ?>" />
				<meta itemprop="width" content="<?php esc_attr_e( $image[1] ); ?>" />
				<meta itemprop="height" content="<?php esc_attr_e( $image[2] ); ?>" />
			</div>
			<meta itemprop="name" content="<?php bloginfo( 'name' ); ?>"/>
		</div>

	<?php endif; ?>

	<meta itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/WebPage" itemid="<?php the_permalink(); ?>" />

	<meta itemprop="dateModified" content="<?php the_modified_date( 'Y-m-d '); ?>" />

</div>