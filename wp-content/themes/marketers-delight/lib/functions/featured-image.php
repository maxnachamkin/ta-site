<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $position = null, $size = null, $atts = null ) {
	$position = ! empty( $position ) ? $position : md_featured_image_position();
	$size     = ! empty( $size ) ? $size : md_featured_image_size();
	$style    = '';

	$src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );

	if ( $position == 'header_cover' || $position == 'headline_cover' )
		$style = md_featured_image_style();
	elseif ( $position == 'left' || $position == 'right' || $position == 'center' )
		$style = 'style="max-width: ' .  $src[1] . 'px;"';
?>

	<?php if ( $position == 'header_cover' && have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content/headline' ); ?>
		<?php endwhile; ?>

	<?php else : ?>

		<div class="featured-image box<?php echo md_featured_image_cover_classes() . md_featured_image_alignment_classes( $position, $atts ); ?>" <?php echo $style; ?>>

			<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'md' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php endif; ?>

				<?php the_post_thumbnail( $size ); ?>

			<?php if ( ! is_singular() ) : ?></a><?php endif; ?>

			<?php md_featured_image_caption(); ?>

		</div>

	<?php endif; ?>

	<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<meta itemprop="url" content="<?php echo $src[0]; ?>">
		<meta itemprop="width" content="<?php echo $src[1]; ?>">
		<meta itemprop="height" content="<?php echo $src[2]; ?>">
	</div>

<?php }


/**
 * Pass featured image data with defaults to cats/tax.
 *
 * @since 4.5
 */

function md_featured_image_tax_data() {
	$tax_data = md_tax_data( 'md_featured_image' );
	$tax      = $tax_data['fields'];
	$default  = get_theme_mod( 'md_featured_image_position_default' );
	$position = ( ! empty( $tax['position'] ) ? $tax['position'] : (
		! empty( $default ) ? $default : ''
	) );

	return array(
		'image'    => $tax['image'],
		'position' => $position
	);
}


/**
 * HTML for MD featured image on taxonomy pages.
 *
 * @since 4.3.5
 */

function md_featured_image_tax() {
	$tax      = md_featured_image_tax_data();
	$position = $tax['position'];

	$id    = md_url_image_id( $tax['image'] );
	$size  = md_featured_image_size( $position, 'md-thumbnail' );
	$image = wp_get_attachment_image_src( $id, $size );
?>

	<?php if ( $position == 'header_cover' ) : ?>

		<?php md_archives_title(); ?>

	<?php else :
		$atts['wrap'] = false;
		$align        = md_featured_image_alignment_classes( $position, $atts );
		$class        = in_array( $position, array( '', 'left', 'right', 'center' ) ) ? ' class="circle"' : '';
	?>

		<div class="featured-image-tax<?php echo $align; ?>">
			<img src="<?php echo esc_url( $image[0] ); ?>"<?php echo $class; ?> width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" />
		</div>

	<?php endif; ?>

<?php }


/**
 * Checks for inline Featured Image.
 *
 * @since 4.1
 */

function md_has_inline_featured_image() {
	$position = md_featured_image_position();

	if ( has_post_thumbnail() && in_array( $position, array( '', 'left', 'right', 'center' ) ) )
		return true;
}


/**
 * If any type of Featured Image cover is enabled, this function
 * outputs the needed classes to properly style it.
 *
 * @since 4.1
 */

function md_featured_image_cover_classes() {
	$position = md_featured_image_position();
	$classes  = '';

	if ( in_array( $position, array( 'header_cover', 'headline_cover' ) ) )
		$classes .= ' format-text-main text-center';

	return $classes;
}


/**
 * Overlay caption over image.
 *
 * @since 4.0
 */

function md_featured_image_caption() {
	$caption = get_post_meta( get_the_ID(), 'md_featured_image_caption', true );
	$image   = get_posts( array(
		'p'         => get_post_thumbnail_id( get_the_ID() ),
		'post_type' => 'attachment'
	) );
?>

	<?php if ( ! empty( $caption['add'] ) && ! empty( $image ) && $image[0]->post_excerpt ) : ?>
		<span class="featured-image-caption links-dark"><?php echo $image[0]->post_excerpt; ?></span>
	<?php endif; ?>

<?php }


/**
 * If image is set above headline, add Featured Image above headline.
 *
 * @since 4.1
 */

function md_featured_image_above_headline() {
	$position = md_featured_image_position();
?>

	<?php if ( $position == 'above_headline' ) : ?>
		<?php md_featured_image(); ?>
	<?php endif; ?>

<?php }


/**
 * If image is set below headline, add Featured Image below headline.
 *
 * @since 4.1
 */

function md_featured_image_below_headline() {
	$position = md_featured_image_position();
?>

	<?php if ( $position == 'below_headline' ) : ?>
		<?php md_featured_image(); ?>
	<?php endif; ?>

<?php }


/**
 * If image is set to header cover, add Featured Image below header.
 *
 * @since 4.1
 */

function md_featured_image_header_cover() {
	$tax = md_featured_image_tax_data();

	if ( is_singular() && md_has_content_box() && md_has_headline() && md_featured_image_position() == 'header_cover' )
		md_featured_image();

	if ( ( is_category() || is_tax() ) && $tax['position'] == 'header_cover' )
		md_featured_image_tax();
}

add_action( 'md_hook_after_header', 'md_featured_image_header_cover' );


/**
 * Returns position meta value.
 *
 * @since 4.1
 */

function md_featured_image_position( $position = null ) {
	$default = get_theme_mod( 'md_featured_image_position_default' );
	$meta    = get_post_meta( get_the_ID(), 'md_featured_image_position', true );

	return isset( $position ) ? $position : (
		! empty( $meta ) ? $meta : (
				! empty( $default ) && $meta == '' ? $default : ''
			)
		);
}


/**
 * Returns image size. Use anywhere you need to set a the_post_thumbnail size.
 *
 * @since 4.1
 */

function md_featured_image_size( $pos = null, $thumb = null ) {
	$position = md_featured_image_position( $pos );

	if ( $position == '' || $position == 'left' || $position == 'right' )
		return ( ! isset( $thumb ) ? 'md-image' : $thumb );

	if ( $position == 'below_headline' || $position == 'above_headline' )
		return 'md-full';

	if ( $position == 'center' )
		return ( ! isset( $thumb ) ? 'full' : $thumb );

	return 'full';
}


/**
 * Featured Image position class.
 *
 * @since 4.1
 */

function md_featured_image_alignment_classes( $position, $atts ) {
	$wrap = ! isset( $atts['wrap'] ) ? ' wrap' : '';

	if ( $position == '' || $position == 'right' )
		return " alignright$wrap";
	elseif ( $position == 'left' )
		return " alignleft$wrap";
	elseif ( $position == 'center' )
		return ' aligncenter';
	elseif ( $position == 'below_headline' || $position == 'above_headline' )
		return ' inner';
	else
		return;
}


/**
 * Outputs inline style CSS of featured image.
 *
 * @since 4.1
 */

function md_featured_image_style( $url = null, $size = null ) {
	$size = isset( $size ) ? $size : 'large';
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );

	if ( empty( $url ) && empty( $image[0] ) )
		return;

	$url = ! empty( $url ) ? $url : $image[0];

	return ' style="background-image: url(\'' . $url . '\');"';
}


/**
 * Outputs inline style CSS to add featured image to element if
 * image position is set to header cover or headline cover.
 *
 * @since 4.1
 */

function md_featured_image_cover( $url = null, $pos = null ) {
	$position = md_featured_image_position( $pos );

	if ( $position == 'header_cover' || $position == 'headline_cover' )
		return md_featured_image_style( $url );
}