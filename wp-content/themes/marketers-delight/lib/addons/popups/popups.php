<?php
/**
 * Plugin Name: MD Popups
 * Plugin URI: https://marketersdelight.net/popups/
 * Description: An addon for Marketers Delight that adds 2-step optin and exit intent capabilities to your site. Create custom forms on a post, page, and category basis easily with MD Popups.
 * Author: Alex Mangini, Kolakube
 */

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants

define( 'MD_POPUPS_DIR', MD_PLUGIN_DIR . 'addons/popups/' );
define( 'MD_POPUPS_URL', MD_PLUGIN_URL . 'addons/popups/' );


/**
 * Start the Popups Engine. This class needs organizational work.
 *
 * @since 1.0
 */

class md_popups {

	/**
	 * Initialize MD Popups plugin, load to plugins_loaded.
	 *
	 * @since 1.0
	 */

	public function init() {
		// loader
		require_once( MD_POPUPS_DIR . 'popup.php' );

		// admin
		require_once( MD_POPUPS_DIR . 'admin/admin-utilities.php' );
		require_once( MD_POPUPS_DIR . 'admin/main-popup-fields.php' );
		require_once( MD_POPUPS_DIR . 'admin/admin-popups.php' );
		require_once( MD_POPUPS_DIR . 'admin/meta-box.php' );

		// customize
		require_once( MD_POPUPS_DIR . 'customize/customize.php' );

		// functions
		require_once( MD_POPUPS_DIR . 'functions/shortcode.php' );
		require_once( MD_POPUPS_DIR . 'functions/template-functions.php' );
		require_once( MD_POPUPS_DIR . 'functions/main-menu.php' );

		// load frontend
		add_action( 'wp_footer', array( $this, 'html' ) );
		add_action( 'wp_footer', array( $this, 'script_init' ), 9999 );
		add_action( 'md_hook_byline_after_date', array( $this, 'byline' ) );

		// load backend
		add_action( 'init', array( $this, 'customizer_loader' ) );
		add_action( 'template_redirect', array( $this, 'admin_loader' ) );
	}


	/**
	 * Loads popup HTML + scripts if any popups have
	 * been added to the page.
	 *
	 * @since 1.0
	 */

	public function html() {
		$popups = get_option( 'md_popups' );

		if ( ! has_action( 'md_popups' ) )
			return;

		$data = md_popup_data();

		wp_enqueue_script( 'md-popups', MD_POPUPS_URL . 'js/popups.js', array(), false, true );

		if ( has_main_md_popup() )
			wp_localize_script( 'md-popups', 'mainPopup', array(
				'mainPopup'   => $data['main_popup'],
				'exitIntent'  => ( $data['show'] == 'exit_intent' ? true : false ),
				'showOnDelay' => ( $data['show'] == 'delay' ? true : false ),
				'delayTime'   => ( ! empty( $data['delay_time'] ) ? absint( $data['delay_time'] ) : 5 ),
				'cookie'      => ( ! empty( $data['cookie'] ) || $data['cookie'] == 0 ? absint( $data['cookie'] ) : 30 )
			) );

		if ( ! empty( $popups ) )
			foreach ( $popups['popups'] as $id => $popup_fields )
				if ( ! empty( $popup_fields['id'] ) )
					$ids[] = $popup_fields['id'];
	?>

		<!-- MD Popups -->

		<div id="md-popups" class="md-popups">

			<!-- CSS -->

			<style type="text/css">

				<?php foreach ( $ids as $id ) {
					$fields   = md_popup_fields( $id );
					$bg_image = wp_get_attachment_image_src( absint( $fields['bg_image'] ), 'full' );

					if ( ! empty( $fields['bg_color'] ) || ! empty( $fields['bg_image'] ) || ! empty( $fields['text_color'] ) )
						echo
							"#md_popup_$id{".
								( ! empty( $fields['bg_color'] ) ? 'background-color: ' . esc_attr( $fields['bg_color'] ) . ';' : '' ).
								( ! empty( $fields['bg_image'] ) ? 'background-image: url(' . esc_url( $bg_image[0] ) . ');' : '' ).
								( ! empty( $fields['bg_position_center'] ) ? 'background-position: center;' : '' ).
								( ! empty( $fields['text_color'] ) ? 'color: ' . esc_attr( $fields['text_color'] ) . ';' : '' ).
							"}\n";

					if ( ! empty( $fields['secondary_color'] ) )
						echo
							"#md_popup_$id .md-popup-sec{".
								'background-color: ' . esc_attr( $fields['secondary_color'] ) . ';'.
							"}\n";

					if ( ! empty( $fields['link_color'] ) )
						echo
							"#md_popup_$id a:not(.button){".
								'border-bottom-color: ' . esc_attr( $fields['link_color'] ) . ';'.
								'color: ' . esc_attr( $fields['link_color'] ) . ';'.
							"}\n";

					if ( ! empty( $fields['close_color'] ) )
						echo
							"#md_popup_$id .md-popup-close-corner{".
								'color: ' . esc_attr( $fields['close_color'] ) . ';'.
							"}\n";

					if ( ! empty( $fields['button_color'] ) )
						echo
							"#md_popup_$id .button,#md_popup_$id .form-submit,".
							"#md_popup_$id .md-popup-close-corner{".
								'background-color: ' . esc_attr( $fields['button_color'] ) . ';'.
							"}\n";

					if ( ! empty( $fields['custom_css' ] ) )
						echo esc_attr( $fields['custom_css'] );
				} ?>

			</style>

			<!-- HTML -->

			<?php do_action( 'md_popups' ); // popup templates hook ?>

			<div id="md_popup_bg" class="md-popup-bg"></div>

		</div>

	<?php }


	/**
	 * Print popups init script with localized options as far
	 * down the page as possible to ensure the MD Popups
	 * main JS file has loaded before init.
	 *
	 * @since 1.0
	 */

	public function script_init() {
		if ( ! has_action( 'md_popups' ) )
			return;
	?>

		<script>
			<?php if ( has_main_md_popup() ) : ?>

				mdPopups.init({
					mainPopup:   mainPopup.mainPopup,
					exitIntent:  mainPopup.exitIntent,
					showOnDelay: mainPopup.showOnDelay,
					delay:       mainPopup.delayTime,
				    cookieExp:   mainPopup.cookie
				});

			<?php elseif ( is_customize_preview() ) :
				$edit = get_option( 'md_popups_edit' );
			?>

				mdPopups.init({
					mainPopup:   '<?php echo $edit['id']; ?>',
					showOnDelay: 1,
					delay:       0,
				    cookieExp:   0
				});

			<?php else : ?>

				mdPopups.init({});

			<?php endif; ?>
		</script>

	<?php }


	/**
	 * Loads popup set by admin settings.
	 *
	 * @since 1.0
	 */

	public function admin_loader() {
		// load main popup
		$data = md_popup_data();
		if ( has_main_md_popup() )
			new md_popup( array( 'id' => $data['main_popup'] ) );

		// load 2 step optins
		$popups = get_option( 'md_popups' );

		foreach ( array( 'main_menu', 'byline' ) as $area ) {
			$popups[$area] = ! empty( $popups[$area] ) ? $popups[$area] : '';

			if ( ! empty( $popups[$area] ) )
				new md_popup( array(
					'id' => $popups[$area]
				) );
		}
	}


	/**
	 * Loads popup in Customizer.
	 *
	 * @since 1.0
	 */

	public function customizer_loader() {
		$this->edit = get_option( 'md_popups_edit' );

		// prevent popups from showing on every Customizer instance, only their unique edit

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'md_popups_return' || ( ! is_customize_preview() && ! empty( $this->edit ) ) )
			delete_option( 'md_popups_edit' );

		if ( is_customize_preview() && ! empty( $this->edit ) ) {
			new md_popups_customize;
			new md_popup( $this->edit );
		}
	}


	/**
	 * Add byline HTML to trigger popup.
	 *
	 * @since 4.5
	 */

	public function byline() {
		$popups           = get_option( 'md_popups' );
		$popups['byline'] = ! empty( $popups['byline'] ) ? $popups['byline'] : '';

		if ( empty( $popups['byline'] ) )
			return;

		$byline_text = ! empty( $popups['byline_text'] ) ? $popups['byline_text'] : __( 'Get updates', 'md' );
	?>

		<span class="byline-popup byline-item links-rgb-dark">
			<i class="byline-item-icon md-icon md-icon-mail-alt"></i> <a href="#" class="md-popup-trigger" data-popup="md_popup_<?php esc_attr_e( $popups['byline'] ); ?>"><?php echo esc_html( $byline_text ); ?></a>
		</span>

	<?php }

}

$md_popups = new md_popups;
$md_popups->init();