<?php

/**
 * Add mobile popup trigger to Main Menu.
 *
 * @since 4.5
 */

function md_popup_menu_triggers() {
	$popups = get_option( 'md_popups' );

	if ( empty( $popups['main_menu'] ) )
		return;
?>

	<!-- Popup Trigger -->

	<span id="menu-trigger-popup" data-popup="md_popup_<?php echo $popups['main_menu']; ?>" class="md-popup-trigger menu-trigger-popup menu-trigger col">
		<i class="md-icon md-icon-mail-alt"></i> <span class="menu-trigger-text close-on-mobile"><?php echo apply_filters( 'md_main_menu_popup_trigger', __( 'Get Updates', 'md' ) ); ?></span>
	</span>

<?php }

add_action( 'md_main_menu_triggers_bottom', 'md_popup_menu_triggers' );


/**
 * Add desktop popup trigger to Main Menu.
 *
 * @since 4.5
 */

function md_popup_menu_side_trigger() {
	$popups = get_option( 'md_popups' );

	if ( empty( $popups['main_menu'] ) )
		return;
?>

	<!-- Popup -->

	<span id="menu-desktop-trigger-popup" data-popup="md_popup_<?php echo $popups['main_menu']; ?>" class="md-popup-trigger menu-trigger-popup menu-trigger close-on-max">
		<i class="md-icon md-icon-mail-alt"></i>
	</span>

<?php }

add_action( 'md_main_menu_side_triggers', 'md_popup_menu_side_trigger' );


/**
 * Let the Main Menu know the status of its popup.
 *
 * @since 4.5
 */

function md_popup_menu_item() {
	$popups              = get_option( 'md_popups' );
	$popups['main_menu'] = ! empty( $popups['main_menu'] ) ? $popups['main_menu'] : '';

	return $popups['main_menu'] ? true : false;
}

add_filter( 'md_filter_main_menu_items', 'md_popup_menu_item' );