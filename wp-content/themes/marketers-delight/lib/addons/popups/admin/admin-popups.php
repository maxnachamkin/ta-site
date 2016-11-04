<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Builds admin settings panel and add it to MD interface.
 *
 * @since 1.0
 */

class md_popups_admin extends md_api {

	/**
	 * Build MD Popups admin page.
	 *
	 * @since 1.0
	 */

	public function construct() {
		$this->_id = $this->suite = 'md_popups';

		$this->admin_page = array(
			'parent_slug' => 'themes.php',
			'name'        => __( 'Popups', 'md' ),
			'hide_menu'   => true
		);

		$this->admin_tab = array(
			'save' => false
		);

		$this->main = new md_popups_fields( $this->_id );

		add_action( 'md_hook_panel_tab', array( $this, 'panel_tab' ), 40 );
		add_action( 'init', array( $this, 'admin_redirects' ) );
	}


	/**
	 * Run Admin Utilities.
	 *
	 * @since 1.0
	 */

	public function admin_redirects() {
		$this->admin_utilities = new md_popups_utilities( $this->_id );
	}


	/**
	 * Add Popups panel tab.
	 *
	 * @since 1.0
	 */

	public function panel_tab() {
		$screen = get_current_screen();
	?>

		<a href="<?php echo admin_url( "themes.php?page={$this->_id}" ); ?>" class="nav-tab<?php echo ! empty( $screen->base ) && $screen->base == "appearance_page_$this->_id" ? ' nav-tab-active' : ''; ?>"><?php _e( 'Popups', 'md' ); ?></a>

	<?php }


	/**
	 * Register admin settings for validation & sanitization.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		$popups  = get_option( 'md_popups' );
		$options = array();

		if ( ! empty( $popups ) )
			foreach ( $popups['popups'] as $popup => $fields )
				if ( ! empty( $fields['id'] ) )
					$options[] = $fields['id'];

		return array_merge( array(
			'popups' => array(
				'type'          => 'repeat',
				'repeat_fields' => array(
					'name' => array(
						'type' => 'text'
					)
				)
			),
			'cookie' => array(
				'type' => 'number'
			),
			'display' => array(
				'type'    => 'checkbox',
				'options' => array(
					'site',
					'blog',
					'posts',
					'pages',
					'categories'
				)
			),
			'main_menu' => array(
				'type'    => 'select',
				'options' => $options
			),
			'byline' => array(
				'type'    => 'select',
				'options' => $options
			),
			'byline_text' => array(
				'type' => 'text'
			)
		), $this->main->register_fields() );
	}


	/**
	 * Create Popups admin page.
	 *
	 * @since 1.0
	 */

	public function admin_page() { ?>

		<div class="wrap md">

			<?php $this->admin_header(); ?>

			<form id="md-form" method="post" action="options.php">

				<?php settings_fields( $this->_id ); ?>

				<div class="md-popups">

					<div class="md-box-style">

						<!-- Popups Manager -->

						<?php $this->field( 'repeat', 'popups', null, array(
							'title'    => __( 'Popups Manager', 'md' ),
							'callback' => 'manager',
							'columns'  => 3
						) ); ?>

					</div>

					<!-- Popup Options -->

					<?php $this->options(); ?>

				</div>

			</form>

		</div>

	<?php }


	/**
	 * Build out repeatable fields.
	 *
	 * @since 1.0
	 */

	public function manager( $repeat ) {
		$option      = get_option( $this->_id );
		$count       = $repeat['count'];
		$name        = ! empty( $option['popups'][$count]['name'] ) ? $option['popups'][$count]['name'] : '';
		$name_encode = urlencode( $name );
		$id          = ! empty( $option['popups'][$count]['id'] ) ? $option['popups'][$count]['id'] : '';

		$bg_color   = get_option( "{$this->_id}_{$id}_bg_color" );
		$bg_image   = wp_get_attachment_url( get_option( "{$this->_id}_{$id}_bg_image" ) );
		$text_color = get_option( "{$this->_id}_{$id}_text_color" );
		$bg_style   = ! empty( $bg_color ) || ! empty( $bg_image ) || ! empty( $text_color ) ? ' style="'.
			( ! empty( $text_color ) ? 'color: ' . esc_attr( $text_color ) . ';' : '' ).
			( ! empty( $bg_color ) ? 'background-color: ' . esc_attr( $bg_color ) . ';' : '' ).
			( ! empty( $bg_image ) ? 'background-image: url(' . esc_url( $bg_image ) . ');' : '' ).
		'"' : '';
		$bg_classes = ! empty( $bg_image ) ? ' md-image-overlay' : '';
	?>

		<div class="md-popup<?php echo $bg_classes; ?>"<?php echo $bg_style; ?>>

			<div class="md-sep">
				<?php $this->field( 'text', 'name', null, array_merge( $repeat, array(
					'atts' => array(
						'placeholder'         => __( 'Enter new popup name&hellip;', 'md' ),
						'readonly_after_save' => true
					)
				) ) ); ?>
			</div>

			<?php if ( ! empty( $name ) ) : ?>
				<a href="<?php echo admin_url( "themes.php?page={$this->_id}_load&popup={$id}&popup_name={$name_encode}" ); ?>" class="button md-popup-button-edit"><?php _e( 'edit popup', 'md' ); ?></a>
				&nbsp;
				<a class="button md-popup-button-shortcode" onClick="window.prompt( 'Copy (ctrl + c + enter) and paste shortcode anywhere on your site.', '[md_popup id=&quot;<?php echo $id; ?>&quot; load=&quot;true&quot; type=&quot;button&quot; text=&quot;Open popup&quot;]' )"><?php _e( 'copy shortcode', 'md' ); ?></a>
			<?php endif; ?>

		</div>

	<?php }


	/**
	 * Add additional Popups options
	 *
	 * @since 4.5
	 */

	public function options() {
		$popups = get_option( $this->_id );

		// byline
		$byline_text         = $this->module_field( 'byline' );
		$byline_text_display = ! empty( $byline_text ) ? 'table-row' : 'none';
	?>

		<div class="md-content-wrap">

			<h3 class="md-button-title"><?php _e( 'Popups Settings', 'md' ); ?></h3>

			<!-- Main Popup -->

			<div id="poststuff" class="meta-box-sortables postbox-container">
				<div class="postbox closed">
					<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Main Popup', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Main Popup', 'md' ); ?></span></h2>
					<div class="inside">

						<?php $this->main->fields(); ?>

					</div>
				</div>
			</div>

			<!-- Hotspots -->

			<div id="poststuff" class="meta-box-sortables postbox-container">
				<div class="postbox closed">
					<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php _e( 'Toggle panel: Popup Hotspots', 'md' ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
					<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Popup Hotspots', 'md' ); ?></span></h2>
					<div class="inside">

						<?php if ( ! empty( $popups ) ) :
							// set popup options
							$options = array();
							if ( ! empty( $popups ) )
								foreach ( $popups['popups'] as $popup => $fields )
									$options[$fields['id']] = $fields['name'];
							$options = array_merge( array( '' => __( 'Select a popup&hellip;', 'md' ) ), $options );
						?>

							<?php $this->desc( __( '2-step popups are triggered when a user clicks any text link, image, or button. You can quickly add 2-step popups around your site by setting any of the options below.', 'md' ) ); ?>

							<table class="form-table">
								<tbody>

									<?php if ( md_has( 'main_menu' ) ) : ?>

										<!-- Main Menu -->

										<tr>
											<th scope="row">
												<?php $this->label( 'main_menu', __( 'Main Menu', 'md' ) ); ?>
											</th>

											<td>
												<?php $this->field( 'select', 'main_menu', $options ); ?>
												<?php $this->desc( __( 'Add an email icon to the Main Menu that opens a popup when clicked.', 'md' ) ); ?>
											</td>
										</tr>

									<?php endif; ?>

									<!-- Byline -->

									<tr>
										<th scope="row">
											<?php $this->label( 'byline', __( 'Byline', 'md' ) ); ?>
										</th>

										<td>
											<?php $this->field( 'select', 'byline', $options ); ?>
											<?php $this->desc( __( 'Add a link to blog post bylines that open a popup when clicked.', 'md' ) ); ?>
										</td>
									</tr>

									<!-- Byline Text -->

									<tr id="<?php echo $this->_id; ?>_byline_text_row" style="display: <?php echo $byline_text_display; ?>">
										<th scope="row">
											<?php $this->label( 'byline_text', __( 'Byline Text', 'md' ) ); ?>
										</th>

										<td>
											<?php $this->field( 'text', 'byline_text', null, array(
												'atts' => array(
													'placeholder' => __( 'Get updates', 'md' )
												)
											) ); ?>
										</td>
									</tr>

								</tbody>
							</table>

						<?php else : ?>

							<?php md_popup_connect_notice(); ?>

						<?php endif; ?>

					</div>
				</div>
			</div>

			<?php submit_button( __( 'Save Popups', 'md' ) ); ?>

		</div>

		<script>
			( function() {
				document.getElementById( '<?php echo $this->_id; ?>_byline' ).onchange = function() {
					document.getElementById( '<?php echo $this->_id; ?>_byline_text_row' ).style.display = this.value != '' ? 'table-row' : 'none';
				}
			})();
		</script>

	<?php }

}

$md_popups_admin = new md_popups_admin;