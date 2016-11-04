<?php

/**
 * Shorthand way to display a Video Lead field.
 *
 * @since 1.0
 */

function video_lead_field( $field ) {
	return page_lead_field( 'video_lead', $field );
}


/**
 * Shorthand way to display a Video Lead field.
 *
 * @since 1.0
 */

function video_lead_fields() {
	return array_merge( array(
		// content + video
		'subtitle'          => video_lead_field( 'subtitle' ),
		'headline'          => video_lead_field( 'headline' ),
		'desc'              => video_lead_field( 'desc' ),
		'service'           => video_lead_field( 'service' ),
		'youtube'           => video_lead_field( 'youtube' ),
		'vimeo'             => video_lead_field( 'vimeo' ),
		'embed'             => video_lead_field( 'embed' ),
		// design
		'layout'            => video_lead_field( 'layout' ),
		'position'          => video_lead_field( 'position' ),
		'bg_color'          => video_lead_field( 'bg_color' ),
		'bg_image'          => video_lead_field( 'bg_image' ),
		'text_color_scheme' => video_lead_field( 'text_color_scheme' ),
		'button_action'     => video_lead_field( 'button_action' ),
		'button_link'       => video_lead_field( 'button_link' ),
		'button_text'       => video_lead_field( 'button_text' )
	), apply_filters( 'md_video_lead_fields', array() ) );
}


/**
 * Create video output based on service.
 *
 * @since 1.0
 */

function video_lead_video( $service ) {
	if ( empty( $service ) )
		return;

	$service = ! empty( $service ) ? esc_attr( $service ) : '';
	$video   = video_lead_field( $service );
?>

	<div class="video-lead-embed video-wrap">

		<?php if ( $service != 'embed' ) : ?>

			<iframe width="640" height="480" src="<?php echo esc_url( $video ); ?>" frameborder="0" allowfullscreen></iframe>

		<?php elseif ( $service == 'embed' ) : ?>

			<?php echo $video; ?>

		<?php endif; ?>

	</div>

<?php }


/**
 * Check if there's enough data to output the Video Lead.
 *
 * @since 1.0
 */

function has_video_lead() {
	return has_video_lead_video();
}


/**
 * Check if Video Lead Content exists.
 *
 * @since 1.0
 */

function has_video_lead_content() {
	$fields = video_lead_fields();
	return ! empty( $fields['subtitle'] ) || ! empty( $fields['headline'] ) || ! empty( $fields['desc'] ) ? true : false;
}


/**
 * Check if Video Lead Video exists.
 *
 * @since 1.0
 */

function has_video_lead_video() {
	$fields = video_lead_fields();
	return ! empty( $fields['service'] ) && ( ! empty( $fields['youtube'] ) || ! empty( $fields['vimeo'] ) || ! empty( $fields['embed'] ) ) ? true : false;
}


/**
 * Check if columns are needed for layout.
 *
 * @since 1.0
 */

function has_video_lead_columns() {
	$fields = video_lead_fields();

	return $fields['layout'] != 'center' && has_video_lead_content() && has_video_lead_video() ? true : false;
}


/**
 * Check if play button is active alongside MD Popups. This function
 * is used to make critical layout decisions.
 *
 * @since 1.0
 */

function has_video_lead_play_button() {
	$fields = video_lead_fields();
	return class_exists( 'md_popup' ) && $fields['button_action'] == 'play' ? true : false;
}