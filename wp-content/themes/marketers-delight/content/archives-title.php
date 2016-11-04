<?php if ( is_category() || is_tax() ) : $layout = md_layout(); ?>

	<?php if ( empty( $layout['content']['single']['remove_headline'] ) ) :
		$tax      = md_featured_image_tax_data();
		$image    = $tax['image'];
		$position = $tax['position'];

		$is_cover = ( $position == 'header_cover' || $position == 'headline_cover' ) ? true : false;
		$block    = $is_cover ? ( ! md_has_sidebar() || $position == 'header_cover' ? ' block-full-lr' : '' ) . ' block-double small-title text-center' : 'block';
	?>
		<div class="archives-title content-item format-text-main<?php echo md_headline_classes( $position, $image ); ?>"<?php echo md_featured_image_cover( $image, $position ); ?>>
			<div class="<?php echo $block; ?>">
				<?php if ( ! empty( $image ) && ( in_array( $position, array( '', 'right', 'left', 'center' ) ) ) ) : ?>
					<?php md_featured_image_tax(); ?>
				<?php endif; ?>

				<div class="mb-half">
					<h1<?php echo ( ! $is_cover ? ' class="small-title' . ( $position == 'center' ? ' text-center' : '' ) . '"' : '' ); ?>><?php single_cat_title(); ?></h1>
				</div>

				<?php echo category_description(); ?>
			</div>
		</div>
	<?php endif; ?>

<?php elseif ( is_search() ) : ?>

	<div class="archives-title content-item inner">
		<div class="block">
			<p class="small-title"><?php _e( 'Search Results For: ', 'md' ); the_search_query(); ?></p>
		</div>
	</div>

<?php elseif ( is_author() ) : ?>

	<?php md_author_box(); ?>

<?php elseif ( is_tag() ) : ?>

	<div class="archives-title content-item inner">
		<div class="block">
			<p class="small-title"><?php single_tag_title(); ?></p>
		</div>
	</div>

<?php elseif ( is_day() ) : ?>

	<div class="archives-title content-item inner">
		<div class="block">
			<p class="small-title"><?php echo get_the_date(); ?></p>
		</div>
	</div>

<?php elseif ( is_month() ) : ?>

	<div class="archives-title content-item inner">
		<div class="block">
			<p class="small-title"><?php echo get_the_date( 'F Y' ); ?></p>
		</div>
	</div>

<?php elseif ( is_year() ) : ?>

	<div class="archives-title content-item inner">
		<div class="block">
			<p class="small-title"><?php echo get_the_date( 'Y' ); ?></p>
		</div>
	</div>

<?php elseif ( is_post_type_archive() ) : ?>

	<div class="archives-title content-item inner">
		<div class="block">
			<p class="small-title"><?php post_type_archive_title(); ?></p>
		</div>
	</div>

<?php endif; ?>