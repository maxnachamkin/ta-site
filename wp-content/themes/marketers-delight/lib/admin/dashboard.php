<?php
/**
 * Create the Marketers Delight admin page with the
 * MD Dashboard as the homepage. Also holds the license key
 * field, hooked in from the Theme Updater.
 *
 * @since 4.5
 */

class md_dashboard extends md_api {

	/**
	 * Pesuedo constructor, registers the admin page and tab.
	 *
	 * @since 4.5
	 */

	public function construct() {
		$this->suite = 'md';

		$this->admin_page = array(
			'parent_slug' => 'themes.php',
			'name'        => __( 'Marketers Delight', 'md' )
		);

		add_action( 'md_hook_panel_tab', array( $this, 'panel_tab' ), 10 );
	}


	/**
	 * Add Dashboard panel tab.
	 *
	 * @since 4.5
	 */

	public function panel_tab() {
		$screen = get_current_screen();
	?>

		<a href="<?php echo admin_url( "themes.php?page=$this->suite" ); ?>" class="nav-tab<?php echo ! empty( $screen->base ) && $screen->base == "appearance_page_$this->suite" ? ' nav-tab-active' : ''; ?>"><?php _e( 'Dashboard', 'md' ); ?></a>

	<?php }


	/**
	 * Build admin page canvas.
	 *
	 * @since 4.5
	 */

	public function admin_page() { ?>

		<div class="wrap md">

			<?php $this->admin_header(); ?>

			<form id="md-form" method="post" action="options.php">

				<?php settings_fields( $this->_id ); ?>

				<?php $this->dashboard(); ?>

			</form>

		</div>

	<?php }


	/**
	 * Build admin fields.
	 *
	 * @since 4.5
	 */

	public function dashboard() {
		global $_wp_admin_css_colors;

		$admin_color = get_user_option( 'admin_color' );
		$colors      = $_wp_admin_css_colors[$admin_color]->colors;

		$articles = array(
			'getting_started' => array(
				'subtitle' => __( 'The Ultimate MD', 'md' ),
				'title'    => __( 'Getting Started Guide', 'md' ),
				'link'     => 'https://marketersdelight.net/getting-started/',
				'color'    => $colors[2]
			),
			'style_guide' => array(
				'subtitle' => __( 'Better post formatting', 'md' ),
				'title'    => __( 'MD Style Guide', 'md' ),
				'link'     => 'https://marketersdelight.net/styles/',
				'color'    => $colors[0]
			),
			'build_md' => array(
				'subtitle' => __( 'Developer-Friendly', 'md' ),
				'title'    => __( 'Custom Templates &amp; Child Themes', 'md' ),
				'link'     => 'https://marketersdelight.net/child-theme-basics-principles/',
				'color'    => $colors[1]
			),
			'get_to_know' => array(
				'subtitle' => __( 'The complete MD toolkit', 'md' ),
				'title'    => __( 'MD Features Explorer', 'md' ),
				'link'     => 'https://marketersdelight.net/features/',
				'color'    => $colors[3]
			)
		);
	?>

		<div class="md-dashboard columns-3 columns-single md-format-text-sec">

			<!-- Column 1 -->

			<div class="col col1">

				<!-- Widgets Hook -->

				<?php do_action( 'md_hook_dashboard_widgets' ); ?>

				<!-- Articles -->

				<div class="md-blocks">

					<hr class="md-sep" />

					<?php foreach ( $articles as $article ) : ?>

						<a href="<?php echo $article['link']; ?>" class="content-spotlight md-sep" target="_blank" style="background-color: <?php echo $article['color']; ?>">
							<span><?php echo $article['subtitle']; ?></span>
							<p><?php echo $article['title']; ?></p>
						</a>

					<?php endforeach; ?>

					<p><a href="https://marketersdelight.net/tutorials/" class="button" target="_blank"><?php _e( 'Browse all MD Tutorials &rarr;', 'md' ); ?></a></p>
				</div>

			</div>

			<!-- Column 2 -->

			<div class="col">

				<!-- New in MD -->

				<div class="col-style md-widget md-widget-highlight md-sep">

					<h3><span><?php echo sprintf( __( 'New in MD%s', 'md' ), MD_VERSION ); ?></span> <?php echo sprintf( __( '<a href="%s" class="button" target="_blank">read more &rarr;</a>', 'md' ), 'https://marketersdelight.net/marketers-delight-451/' ); ?></h3>

					<div class="md-widget-item">
						<p class="md-widget-desc"><?php _e( '<strong>MD4.5.1</strong> fixes the Popups editor crashing when adding custom CSS, Video Lead display fixes, new helper classes and other bug fixes.', 'md' ); ?></p>
					</div>

					<p style="text-align: center;margin: 0 0 -11px;"><a target="_blank" class="md-badge" href="https://marketersdelight.net/marketers-delight-45/"><?php _e( 'New in MD4.5:', 'md' ); ?></a></p>

					<div class="md-widget-item" style="padding-top:22px">
						<p class="md-widget-title"><?php _e( 'MD is now a single theme!', 'md' ); ?></p>
						<p class="md-widget-desc"><?php echo sprintf( __( 'No more configuring multiple plugins, all addons have been merged into the MD theme! Manage features from the new <a href="%s">MD Settings</a> page.', 'md' ), admin_url( 'themes.php?page=md_settings' ) ); ?></p>
					</div>

					<div class="md-widget-item">
						<p class="md-widget-title"><?php _e( '1 License Key, All Updates', 'md' ); ?></p>
						<p class="md-widget-desc"><?php _e( 'You now only need 1 license key to enable auto-updates on your site(s). Manage your license key from the handy new Dashboard widget!', 'md' ); ?></p>
					</div>

					<div class="md-widget-item">
						<p class="md-widget-title"><?php _e( 'Popup Hotspots', 'md' ); ?></p>
						<p class="md-widget-desc"><?php echo sprintf( __( 'Easily set 2-step popup forms to hotspots around your site from the updated <a href="%s">Popups Settings</a>.', 'md' ), admin_url( 'themes.php?page=md_popups' ) ); ?></p>
					</div>

				</div>

				<!-- Version History -->

				<div class="col-style md-widget md-sep">

					<h3><span><?php _e( 'Version History', 'md' ); ?></span> <?php echo sprintf( __( '<a href="%s" class="button" target="_blank">My MD downloads &rarr;</a>', 'md' ), 'https://marketersdelight.net/downloads/' ); ?></h3>

					<ul class="md-bullet-list">
						<li><b><a href="https://marketersdelight.net/marketers-delight-451/" target="_blank">MD4.5.1</a></b> <small>September 20, 2016</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-45/" target="_blank">MD4.5</a> <small>September 20, 2016</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-442/" target="_blank">MD4.4.2</a> <small>June 15, 2016</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-441/" target="_blank">MD4.4.1</a> <small>May 30, 2016</small></li>
						<li><a href="https://marketersdelight.net/introducing-mdnet/" target="_blank">MD4.4 + MDNET</a> <small>May 24, 2016</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-438/" target="_blank">MD4.3.8</a> <small>April 20, 2016</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-435/" target="_blank">MD4.3.5</a> <small>April 1, 2016</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-432/" target="_blank">MD4.3.2</a> <small>September 14, 2015</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-431/" target="_blank">MD4.3.1</a> <small>September 5, 2015</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-43/" target="_blank">MD4.3</a> <small>August 14, 2015</small></li>
						<li><a href="https://marketersdelight.net/marketers-delight-411/" target="_blank">MD4.1.1</a> <small>April 23, 2015</small></li>
					</ul>

				</div>

			</div>

			<!-- Column 3 -->

			<div class="col">

				<!-- Showcase -->

				<div class="col-style md-sep">

					<h3><?php _e( 'Need Inspiration?', 'md' ); ?></h3>

					<p><?php _e( 'The Site Showcase features sites from around the community personalized with MD. Browse the showcase for ideas on building your own website.', 'md' ); ?></p>

					<p><a href="<?php echo MD_SHOWCASE; ?>" class="button" target="_blank"><?php _e( 'Visit the MD Showcase &rarr;', 'md' ); ?></a></p>

				</div>

				<!-- Forum -->

				<div class="col-style md-sep">

					<h3><?php _e( 'Join the MD Members-only Forums!', 'md' ); ?></h3>

					<p><?php _e( 'Get access to exclusive tips, tutorials, and free downloads regularly posted to the MD members-only forums.' ,'md' ); ?></p>

					<p><a href="http://kolakube.net/index.php?/forum/40-md-tutorials/" class="button" target="_blank"><?php echo _e( 'Go to MD Tutorials forum' ); ?> &rarr;</a></p>

					<p><small><?php echo sprintf( __( 'not a member yet? <a href="%s">register</a> and your account will be approved within 24hrs.', 'md' ), 'http://kolakube.net/index.php?app=core&module=global&section=register' ); ?></small></p>

				</div>

				<!-- Page Leads -->

				<div class="col-style md-sep">

					<h3><?php _e( 'How do Page Leads work?', 'md' ); ?></h3>

					<p><?php echo sprintf( __( 'From the <a href="%s">Page Leads</a> panel you can create the default Page Leads to be displayed throughout your site.', 'md' ), admin_url( 'themes.php?page=page_leads&tab=email_lead' )  ); ?></p>

					<p><?php _e( 'You can also create a custom Leads on any post, page, or category to create more specialized content and offers:' ,'md' ); ?></p>

					<ul class="md-bullet-list">
						<li><?php echo sprintf( __( '<a href="%s">Browse posts</a>', 'md' ), admin_url( 'edit.php' ) ); ?></li>
						<li><?php echo sprintf( __( '<a href="%s">Browse pages</a>', 'md' ), admin_url( 'edit.php?post_type=page' ) ); ?></li>
						<li><?php echo sprintf( __( '<a href="%s">Browse categories</a>', 'md' ), admin_url( 'edit-tags.php?taxonomy=category' ) ); ?></li>
					</ul>

				</div>

				<!-- Email Forms -->

				<div class="col-style md-sep">

					<h3><?php _e( 'Adding Email Forms', 'md' ); ?></h3>

					<p><?php echo sprintf( __( 'The form you create on the <a href="%s">Email Forms</a> page will be used as the email form you enable anywhere throughout your site.', 'md' ), admin_url( 'themes.php?page=md_email' ) ); ?></p>

					<p><?php _e( 'You can create custom email forms in the widget or on any individual post, page, or category.' ,'md' ); ?></p>

				</div>

			</div>

		</div>

	<?php }

}

$md_dashboard = new md_dashboard;