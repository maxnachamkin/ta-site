<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * Easily output a button with different kind of action.
 *
 * @since 4.3.5
 */

function md_button( $button ) {
	$button['classes'] = ! empty( $button['classes'] ) ? ' ' . $button['classes'] : '';
?>

	<?php if ( class_exists( 'Easy_Digital_Downloads' ) && $button['action'] == 'edd_button' ) : ?>

		<?php echo edd_get_purchase_link( array(
			'download_id' => $button['edd_id'],
			'price'       => false,
			'text'        => $button['text'],
			'class'       => $button['classes']
		) ); ?>

	<?php elseif ( class_exists( 'WooCommerce' ) && $button['action'] == 'woo_button' ) : ?>

		<a href="<?php echo do_shortcode( '[add_to_cart_url id="' . $button['woo_id'] . '"]' ); ?>" class="button<?php echo $button['classes']; ?>"><?php esc_html_e( $button['text'] ); ?></a>

	<?php elseif ( class_exists( 'md_popups' ) && $button['action'] == 'popup' ) : ?>

		<?php echo do_shortcode( '[md_popup id="' . $button['popup'] . '" load="true" type="button" text="' . $button['text'] . '" classes="' . $button['classes'] . '"]' ); ?>

	<?php elseif ( $button['text'] && $button['link'] ) : ?>
		<a href="<?php echo $button['link']; ?>" class="button<?php echo $button['classes']; ?>">
			<?php echo $button['text']; ?>
		</a>
	<?php endif; ?>

<?php }