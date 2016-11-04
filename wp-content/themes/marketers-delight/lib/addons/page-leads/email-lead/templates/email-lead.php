<?php
	$data     = email_lead_email_data();
	$id       = get_the_ID();
	$position = email_lead_field( 'position' );

	$bg_color    = email_lead_field( 'bg_color' );
	$bg_image    = email_lead_field( 'bg_image' );
	$text_scheme = email_lead_field( 'text_color_scheme' );

	$bg_image_classes   = ! empty( $bg_image ) ? ' box-dark links-main links-sec image-overlay text-white' : 'box-sec';
	$text_color_classes = $text_scheme != 'white' && empty( $bg_image ) ? 'text-dark' : 'text-white';
	$form_border        = $bg_color != '#ffffff' && $bg_color != '#FFFFFF' ? ' form-no-border' : '';

	$email_classes = md_design_classes( $position ) . "$bg_image_classes $text_color_classes{$form_border}";
	$email_bg      = md_design_background( $bg_color, $bg_image );

	$style            = email_lead_field( 'style' );
	$style_left_right = ! empty( $style['left-right'] ) ? 'email-lead-left-right' : 'text-center';
	$block            = ! empty( $style['left-right'] ) ? 'block-double' : md_design_block( $position );
?>

<?php md_email_form( $data, array(
	'classes'       => "email-lead email-lead-$id $style_left_right $block $email_classes format-text-main",
	'inner_classes' => 'inner',
	'bg_style'      => $email_bg
) ); ?>