<?php

/**
 * Outputs popup fields.
 *
 * @since 1.0
 */

function md_popup_field( $id, $field ) {
	return get_option( "md_popups_{$id}_{$field}" );
}


/**
 * Returns core popup settings based on location of input from
 * either the main settings panel or a single post/page/tax override.
 *
 * @since 1.0
 */

function md_popup_data() {
	global $wp_query;

	$id = $wp_query->get_queried_object_id();

	$option = get_option( 'md_popups' );

	$main_popup_meta = get_post_meta( $id, 'md_popups_meta_main_popup', true );

	$cat = get_query_var( 'cat' );
	$tax = get_option( "md_popups_meta_tax$cat" );

	$display = md_popup_display();

	if ( is_singular() && ! empty( $main_popup_meta ) ) {
		$data['main_popup'] = $main_popup_meta;
		$data['show']       = get_post_meta( $id, 'md_popups_meta_show', true );
		$data['delay_time'] = get_post_meta( $id, 'md_popups_meta_delay_time', true );
	}
	elseif ( is_category() && ! empty( $tax['main_popup'] ) ) {
		$data['main_popup'] = $tax['main_popup'];
		$data['show']       = $tax['show'];
		$data['delay_time'] = $tax['delay_time'];
	}
	elseif ( $display ) {
		$data['main_popup'] = $option['main_popup'];
		$data['show']       = $option['show'];
		$data['delay_time'] = $option['delay_time'];
	}

	$data['cookie'] = ! empty( $option['cookie'] ) || $option['cookie'] == 0 ? $option['cookie'] : 30;

	return $data;
}


/**
 * Choose various positions throughout site to show popup.
 *
 * @since 1.0
 */

function md_popup_display() {
	$data = get_option( 'md_popups' );

	if (
		! empty( $data['display']['site'] )                                                      || // sitewide
		( ! empty( $data['display']['blog'] ) && is_home() && ! get_option( 'page_for_posts' ) ) || // blog
		( ! empty( $data['display']['posts'] ) && is_single() )                                  || // posts
		( ! empty( $data['display']['pages'] ) && is_page() )                                    || // pages
		( ! empty( $data['display']['categories'] ) && is_category() )                              // category
	)
		return true;
}


/**
 * Check if Main Popup is set.
 *
 * @since 1.0
 */

function has_main_md_popup() {
	$data = md_popup_data();

	if ( ! is_customize_preview() && ( ! empty( $data['main_popup'] ) && $data['main_popup'] != '_none' ) && ! empty( $data['show'] ) )
		return true;
}


/**
 * Cleanly list array of popup field data for easy reference.
 *
 * @since 1.0
 */

function md_popup_fields( $id ) {
	return array(
		// custom content
		'show_custom_template' => md_popup_field( $id, 'show_custom_template' ),
		'custom_template'      => md_popup_field( $id, 'custom_template' ),
		'custom_css'           => md_popup_field( $id, 'custom_css' ),
		// text
		'show_text'   => md_popup_field( $id, 'show_text' ),
		'headline'    => md_popup_field( $id, 'headline' ),
		'description' => md_popup_field( $id, 'description' ),
		'bullets'     => md_popup_field( $id, 'bullets' ),
		'button_text' => md_popup_field( $id, 'button_text' ),
		'button_url'  => md_popup_field( $id, 'button_url' ),
		// image
		'image'      => md_popup_field( $id, 'image' ),
		'image_wrap' => md_popup_field( $id, 'image_wrap' ),
		// email
		'email_list'          => md_popup_field( $id, 'email_list' ),
		'email_code'          => md_popup_field( $id, 'email_code' ),
		'email_show_name'     => md_popup_field( $id, 'email_show_name' ),
		'email_name_label'    => md_popup_field( $id, 'email_name_label' ),
		'email_email_label'   => md_popup_field( $id, 'email_email_label' ),
		'email_submit_label'  => md_popup_field( $id, 'email_submit_label' ),
		'email_form_attached' => md_popup_field( $id, 'email_form_attached' ),
		'email_footer'        => md_popup_field( $id, 'email_footer' ),
		'email_title'         => md_popup_field( $id, 'email_title' ),
		// design
		'text_color'   => md_popup_field( $id, 'text_color' ),
		'link_color'   => md_popup_field( $id, 'link_color' ),
		'button_color' => md_popup_field( $id, 'button_color' ),
		'close_color'  => md_popup_field( $id, 'close_color' ),
		'bg_color'     => md_popup_field( $id, 'bg_color' ),
		'bg_image'     => md_popup_field( $id, 'bg_image' ),
		'bg_position_center' => md_popup_field( $id, 'bg_position_center' ),
		'secondary_color'  => md_popup_field( $id, 'secondary_color' ),
		'full_width'   => md_popup_field( $id, 'full_width' ),
		'classes'      => md_popup_field( $id, 'classes' )
	);
}


/**
 * Count active elements in popup for frontend purposes.
 *
 * @since 1.0
 */

function md_popup_content( $id ) {
	$data = md_popup_fields( $id );

	$text  = md_popup_has_text( $id );
	$image = md_popup_has_image( $id );
	$email = md_popup_has_email( $id );

	return count( array_filter( array( $text, $image, $email ) ) );
}


/**
 * Check if Text exists.
 *
 * @since 1.0
 */

function md_popup_has_text( $id ) {
	$data = md_popup_fields( $id );

	if ( ! empty( $data['show_text'] ) && ( $data['headline'] || $data['description'] || $data['bullets'] || $data['button_text'] ) )
		return true;
}


/**
 * Check if Image exists.
 *
 * @since 1.0
 */

function md_popup_has_image( $id ) {
	$data = md_popup_fields( $id );

	if ( ! empty( $data['image'] ) )
		return true;
}


/**
 * Check if Email exists.
 *
 * @since 1.0
 */

function md_popup_has_email( $id ) {
	$data    = md_popup_fields( $id );
	$email   = get_option( 'md_email_data' );
	$service = $email['service'];

	if ( ! empty( $email ) && (
		( $service == 'custom_code' && ! empty( $data['email_code'] ) ) ||
		( $service != 'custom_code' && ! empty( $data['email_list'] ) )
	) )
		return true;
}


/**
 * Check if popup has any popup content.
 *
 * @since 1.0
 */

function md_has_popup( $id ) {
	if ( md_popup_has_text( $id ) || md_popup_has_image( $id ) || md_popup_has_eail( $id ) )
		return true;
}


/**
 * Build email data from Customizer options to pass to frontend.
 *
 * @since 1.0
 */

function md_popups_email_fields( $id ) {
	$data       = md_popup_fields( $id );
	$email      = get_option( 'md_email' );
	$email_list = ! empty( $data['email_list'] ) ? $data['email_list'] : $email['email_list'];

	return array(
		'email_title' => '',
		'email_desc'  => '',
		'email_list'  => ! empty( $email_list ) ? $email_list : '',
		'email_code'  => $data['email_code'],
		'email_input' => array(
			'name' => $data['email_show_name']
		),
		'email_form_style' => array(
			'attached' => $data['email_form_attached']
		),
		'email_name_label'  => $data['email_name_label'],
		'email_email_label' => $data['email_email_label'],
		'email_submit_text' => $data['email_submit_label'],
		'email_form_title'  => $data['email_title'],
		'email_form_footer' => $data['email_footer']
	);
}


/**
 * Output a helper class to hide empty elements in
 * the Customizer for live preview.
 *
 * @since 1.0
 */

function md_popup_customizer_display( $content ) {
	return empty( $content ) && is_customize_preview() ? ' display-none' : '';
}


/**
 * If no service is connected, display this message.
 *
 * @since 4.5
 */

function md_popup_connect_notice() { ?>

	<p class="description"><?php echo sprintf( __( 'You must <a href="%s">create at least one popup</a> before you can add one here.', 'md' ), admin_url( 'themes.php?page=md_popups' ) ); ?></p>

<?php }