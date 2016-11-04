<?php

/**
 * Build the [md_popup] shortcode. Can customize trigger to be
 * a text link, image, or button with attributes.
 *
 * @since 1.0
 */

if ( ! function_exists( 'md_popup_shortcode' ) ) :

function md_popup_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'load'  => '',
		'id'    => '',
		'type'  => '',
		'text'  => '',
		'image' => '',
		'classes' => ''
	), $atts, 'md_popup' ) );

	if ( empty( $atts['id'] ) )
		return;

	ob_start();

	$edit = get_option( 'md_popups_edit' );

	if ( ( ! empty( $atts['load'] ) && $atts['load'] == true && ! is_customize_preview() ) || ( is_customize_preview() && empty( $edit ) ) )
		new md_popup( $atts );

	if ( empty( $atts['type'] ) )
		return;

	$type    = ! empty( $atts['type'] ) ? $atts['type'] : 'button';
	$text    = ! empty( $atts['text'] ) ? $atts['text'] : __( 'Open popup', 'md-popups' );
	$custom  = ! empty( $atts['classes'] ) ? ' ' . $atts['classes'] : '';
	$html    = $type == 'link' ? 'a href="#"' : 'span';
	$html_c  = $type == 'link' ? 'a' : 'span';
	$classes = ( $type == 'button' ? ' button' : '' ) . $custom;
?>

	<?php if ( ! empty( $atts['id'] ) ) : ?>

		<?php if ( $type != 'image' ) : ?>
			<<?php echo $html; ?> data-popup="md_popup_<?php esc_attr_e( $atts['id'] ); ?>" class="md-popup-trigger<?php echo $classes; ?>"><?php esc_html_e( $text ); ?></<?php echo $html_c; ?>>
		<?php else : ?>
			<img src="<?php echo esc_url( $atts['image'] ); ?>" data-popup="md_popup_<?php esc_attr_e( $atts['id'] ); ?>" class="md-popup-trigger<?php echo $classes; ?>" alt="<?php esc_attr_e( $text ); ?>" />
		<?php endif; ?>

	<?php endif; ?>

<?php
	return ob_get_clean();
}

endif;

add_shortcode( 'md_popup', 'md_popup_shortcode' );