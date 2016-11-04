<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds meta box settings on Edit screen to hide the Main Menu on a page-per-page basis.
 * Field save data is added to md_addons array upon plugin activation.
 *
 * @since 1.0
 */

class md_main_menu_meta extends md_api {

	/**
	 * Creates options environment and adds fields to Layout Meta Box in theme.
	 *
	 * @since 1.0
	 */

	public function construct() {
		$this->suite = $this->_id = 'md_layout';

		$this->meta_box = $this->taxonomy = array();

		add_action( 'md_layout_after_header', array( $this, 'fields' ) );
		add_action( 'md_filter_theme_meta_boxes_save', array( $this, 'filter_fields' ) );
	}


	/**
	 * Print toggle script to admin footer.
	 *
	 * @since 1.0
	 */

	public function admin_print_footer_scripts() { ?>

		<script>
			( function() {
				document.getElementById( 'md_layout_main_menu_remove' ).onchange = function( e ) {
					document.getElementById( 'main-menu-options' ).style.display = this.checked ? 'none' : 'block';
				}
			} )();
		</script>

	<?php }


	/**
	 * Filter in meta options to Layout meta box from MD theme.
	 *
	 * @since 1.1
	 */

	public function filter_fields() {
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		$menus     = array();

		if ( ! empty( $nav_menus ) )
			foreach ( $nav_menus as $menu )
				$menus[] = $menu->slug;

		return array(
			'main_menu' => array(
				'type'    => 'checkbox',
				'options' => array( 'remove' )
			),
			'main_menu_menu' => array(
				'type'    => 'select',
				'options' => $menus
			)
		);
	}


	/**
	 * Build meta box form settings.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$screen    = get_current_screen();
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		$menus     = array( '' => __( 'Select a custom menu&hellip;', 'md' ) );

		foreach ( $nav_menus as $menu )
			$menus[$menu->slug] = $menu->name;

		$cat = isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : '';

		if ( ! empty( $cat ) ) {
			$tax_name  = $screen->taxonomy == 'category' ? 'tax' : $screen->taxonomy;
			$layout    = get_option( "md_layout_{$tax_name}{$cat}" );
			$main_menu = $layout['main_menu'];
		}
		else
			$main_menu = get_post_meta( get_the_ID(), 'md_layout_main_menu', true );

		$main_menu['remove'] = isset( $main_menu['remove'] ) ? $main_menu['remove'] : '';
		$display             = empty( $main_menu['remove'] ) ? 'block' : 'none';
	?>

		<!-- Add / Remove -->

		<tr>
			<td>

				<p class="md-title"><?php _e( 'Main Menu', 'md' ); ?></p>

				<?php $this->field( 'checkbox', 'main_menu', array(
					'remove' => __( 'Remove <b>Main Menu</b>', 'md' )
				) ); ?>

				<p id="main-menu-options" style="display: <?php echo $display; ?>; margin-top: 10px;">
					<?php $this->field( 'select', 'main_menu_menu', array_merge( array( '' => __( 'Set a custom menu', 'md' ) ), $menus ) ); ?>
				</p>

			</td>
		</tr>

	<?php }

}

new md_main_menu_meta;