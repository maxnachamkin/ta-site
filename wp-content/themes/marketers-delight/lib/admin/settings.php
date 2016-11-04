<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;


class md_settings extends md_api {

	/**
	 * Pesuedo constructor, registers the admin page and tab.
	 *
	 * @since 4.3.2
	 */

	public function construct() {
		$this->suite = $this->_id;

		$this->admin_page = array(
			'parent_slug' => 'themes.php',
			'name'        => __( 'MD Settings', 'md' ),
			'hide_menu'   => true
		);

		add_action( 'md_hook_panel_tab', array( $this, 'panel_tab' ), 50 );
	}


	/**
	 * Add Settings panel tab.
	 *
	 * @since 4.5
	 */

	public function panel_tab() {
		$screen = get_current_screen();
	?>

		<a href="<?php echo admin_url( "themes.php?page=$this->_id" ); ?>" class="nav-tab<?php echo ! empty( $screen->base ) && $screen->base == "appearance_page_$this->_id" ? ' nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'md' ); ?></a>

	<?php }


	/**
	 * Admin page template for Settings page.
	 *
	 * @since 4.5
	 */

	public function admin_page() { ?>

		<div class="wrap md">

			<?php $this->admin_header(); ?>

			<form id="md-form" method="post" action="options.php"<?php echo ( ! empty( $this->_active_tab ) ? ' class="md-content-wrap"' : '' ); ?>>

				<?php settings_fields( $this->suite ); ?>

				<div class="md-content-wrap">

					<?php $this->fields(); ?>

					<?php submit_button(); ?>

				</div>

			</form>

		</div>

	<?php }


	/**
	 * Register settings fields.
	 *
	 * @since 4.5
	 */

	public function register_fields() {
		return array(
			'features' => array(
				'type'    => 'checkbox',
				'options' => array(
					'page_leads',
					'popups',
					'main_menu',
					'footnotes',
					'tracking_scripts'
				)
			),
			'header_scripts' => array(
				'type' => 'code'
			),
			'footer_scripts' => array(
				'type' => 'code'
			),
			'schema_logo' => array(
				'type' => 'media'
			)
		);
	}


	/**
	 * Builds MD admin panel and adds hooks for other pages.
	 *
	 * @since 4.3.2
	 */

	public function fields() { ?>

		<!-- Features Manager -->

		<div id="poststuff" class="meta-box-sortables postbox-container">
			<div class="postbox closed">
				<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Features Manager', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
				<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Features Manager', 'md' ); ?></span></h2>
				<div class="inside">

					<?php $this->desc( __( 'To better tailor Marketers Delight for your exact needs per site, you can disable major features you don\'t need here. Admin controls and existing content related to any features you disable will no longer appear around your site, and in some cases, get deleted. Use at your own risk.', 'md' ) ); ?>

					<?php $this->field( 'checkbox', 'features', array(
						'page_leads'       => __( 'Disable <b>Page Leads</b>', 'md' ),
						'popups'           => __( 'Disable <b>Popups</b>', 'md' ),
						'main_menu'        => __( 'Disable <b>Main Menu</b>', 'md' ),
						'tracking_scripts' => __( 'Disable <b>Tracking Scripts</b>', 'md' ),
						'footnotes'        => __( 'Disable <b>Footnotes</b>', 'md' )
					) ); ?>

				</div>
			</div>
		</div>

		<?php if ( md_has( 'tracking_scripts' ) ) : ?>

			<!-- Tracking Scripts -->

			<?php $scripts = new md_scripts( $this->_id ); ?>

			<div id="poststuff" class="meta-box-sortables postbox-container">
				<div class="postbox closed">
					<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Tracking Scripts', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Tracking Scripts', 'md' ); ?></span></h2>
					<div class="inside">

						<?php $scripts->fields(); ?>

					</div>
				</div>
			</div>

		<?php endif; ?>

		<!-- Schema Logo Upload -->

		<div id="poststuff" class="meta-box-sortables postbox-container">
			<div class="postbox closed">
				<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Site Tools', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
				<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Site Tools', 'md' ); ?></span></h2>
				<div class="inside">

					<table class="form-table">
						<tbody>

							<tr>
								<th scope="row">
									<?php $this->label( 'schema_logo', 'Schema Logo Upload', 'md' ); ?>
								</th>

								<td>
									<?php $this->field( 'media','schema_logo' ); ?>
									<?php $this->desc( sprintf( __( 'Upload your site\'s logo for use as the Schema Publisher Logo.<br /><a href="%s" target="_blank">See logo requirements &rarr;</a>', 'md' ), 'https://marketersdelight.net/schema-publisher-logo/' ) ); ?>
								</td>
							</tr>

						</tbody>
					</table>

				</div>
			</div>
		</div>

	<?php }

}

$md_settings = new md_settings;