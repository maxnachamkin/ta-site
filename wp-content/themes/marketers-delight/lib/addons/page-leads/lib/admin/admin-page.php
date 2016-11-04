<?php

/**
 * This is the base of the Page Leads admin screen. All Page Leads hook their
 * content back to this class, including other fields.
 *
 * @since 4.2
 */

class page_leads_settings extends md_api {

	/**
	 * Pesuedo constructor, registers the admin page and tab.
	 *
	 * @since 4.2
	 */

	public function construct() {
		$this->suite = 'page_leads';

		$this->admin_page = array(
			'parent_slug' => 'themes.php',
			'name'        => __( 'Page Leads', 'md' ),
			'hide_menu'   => true
		);

		add_action( 'md_hook_panel_tab', array( $this, 'panel_tab' ), 30 );
	}


	/**
	 * Add Page Leads panel tab.
	 *
	 * @since 4.5
	 */

	public function panel_tab() {
		$screen = get_current_screen();
	?>

		<a href="<?php echo admin_url( "themes.php?page=page_leads&tab=email_lead" ); ?>" class="nav-tab<?php echo ! empty( $screen->base ) && $screen->base == 'appearance_page_page_leads' || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? ' nav-tab-active' : ''; ?>"><?php _e( 'Page Leads', 'md' ); ?></a>


	<?php }


	/**
	 * Page Leads admin pagel callback function. This is
	 * where admin tabs and content are hooked to.
	 *
	 * @since 4.2
	 */

	public function admin_page() { ?>

		<div class="wrap md">

			<?php $this->admin_header(); ?>

			<form id="md-form" method="post" action="options.php" class="md-content-wrap">
				<?php do_action( "{$this->suite}_admin_tab_content" ); ?>
			</form>

		</div>

	<?php }

}

new page_leads_settings;