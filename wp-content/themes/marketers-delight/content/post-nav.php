<?php if ( is_single() && ( get_previous_post() || get_next_post() ) ) : ?>

	<div class="page-nav inner content-item format-sec text-sec links-main">
		<div class="block">

			<?php previous_post_link( '<p class="previous mb-half"><span>' . __( 'Previous Post: ', 'md' ) . '</span>%link</p>' ); ?>

			<?php next_post_link( '<p class="next"><span>' . __( 'Next Post: ', 'md' ) . '</span>%link</p>' ); ?>

		</div>
	</div>

<?php endif; ?>