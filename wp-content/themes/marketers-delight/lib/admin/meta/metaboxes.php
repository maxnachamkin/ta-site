<?php

/**
 * Create data array of options.
 *
 * @since 4.3.5
 */

function md_meta_data( $id = null ) {
	$addons    = apply_filters( 'md_filter_theme_meta_boxes_save', array() );
	$layout_id = isset( $id ) ? 'md_layout_' : '';
	$image_id  = isset( $id ) ? 'md_featured_image_' : '';

	// append prefix to addons if ID
	if ( ! empty( $addons ) && isset( $id ) )
		foreach ( $addons as $addon => $fields ) {
			$addons["$layout_id{$addon}"] = $fields;
			unset( $addons[$addon] );
		}

	$layout = array_merge( array(
		"{$layout_id}header" => array(
			'type' => 'checkbox',
			'options' => array(
				'remove',
				'logo',
				'tagline',
				'menu'
			)
		),
		"{$layout_id}content" => array(
			'type' => 'checkbox',
			'options' => array(
				'remove_content_box',
				'remove_headline',
				'remove_byline'
			)
		),
		"{$layout_id}content_box" => array(
			'type' => 'select',
			'options' => array(
				'content_sidebar',
				'sidebar_content'
			)
		),
		"{$layout_id}sidebar" => array(
			'type' => 'checkbox',
			'options' => array(
				'add',
				'custom',
				'remove'
			)
		),
		"{$layout_id}footer" => array(
			'type' => 'checkbox',
			'options' => array(
				'remove',
				'columns'
			)
		)
	), $addons );

	$image = array(
		"{$image_id}position" => array(
			'type' => 'select',
			'options' => array(
				'right',
				'left',
				'center',
				'below_headline',
				'above_headline',
				'headline_cover',
				'header_cover'
			)
		),
		"{$image_id}caption" => array(
			'type' => 'checkbox',
			'options' => array( 'add' )
		)
	);

	$keys = array_merge( $image, $layout );

	return $keys;
}


/**
 * Add meta boxes for output and save into the Edit screen.
 *
 * @since 4.1
 */

function md_meta_boxes() {
	add_action( 'add_meta_boxes', 'md_add_meta_boxes' );
	add_action( 'save_post', 'md_save_meta_boxes', 10, 2 );
}

add_action( 'load-post.php', 'md_meta_boxes' );
add_action( 'load-post-new.php', 'md_meta_boxes' );


/**
 * Register meta boxes to Edit screen.
 *
 * @since 4.1
 */

function md_add_meta_boxes() {
	$post_types = array_merge( array( 'post', 'page' ), apply_filters( 'md_post_type_meta', array() ) );

	foreach ( $post_types as $post_type )
		add_meta_box( 'md_layout_meta', __( 'Layout', 'md' ), 'md_layout_meta', $post_type, 'side' );
}


/**
 * Add meta boxes + save data to taxonomy hooks.
 *
 * @since 4.3.5
 */

function md_add_taxonomies() {
	$taxonomies = array_merge( array( 'category' ), apply_filters( 'md_taxonomy_meta', array() ) );

	foreach ( $taxonomies as $tax ) {
		add_action( "{$tax}_edit_form_fields", 'md_layout_meta', 40 );
		add_action( "edited_{$tax}", 'md_save_taxonomy', 10, 2 );
	}
}

add_action( 'init', 'md_add_taxonomies' );


/**
 * Add custom Featured Image meta to existing Featured Image.
 *
 * @since 4.1
 */

function add_md_featured_image_meta( $content ) {
	ob_start();
	echo $content;
	md_featured_image_meta();
	return ob_get_clean();
}

add_filter( 'admin_post_thumbnail_html', 'add_md_featured_image_meta', 1, 5 );


/**
 * Build custom Featured Image meta box controls.
 *
 * @since 4.1
 */

function md_featured_image_meta() {
	$screen     = get_current_screen();
	$is_tax     = md_is_admin_tax();
	$screen_tax = ! empty( $screen->taxonomy ) ? $screen->taxonomy : '';
	$tax_name   = ! empty( $screen_tax ) && $screen_tax == 'category' ? 'tax' : $screen_tax;
	$id         = 'md_featured_image' . ( $is_tax ? "_{$tax_name}" . $_GET['tag_ID'] : '' );
	$option     = get_option( $id );

	$option['position'] = ! empty( $option['position'] ) ? $option['position'] : '';
	$position           = $is_tax ? $option['position'] : get_post_meta( get_the_ID(), "{$id}_position", true );
	$circle_label       = $is_tax ? __( ' (100x100 circle)', 'md' ) : '';
	$position_values    = array(
		''               => __( 'Select image position&hellip;', 'md' ),
		'right'          => __( 'Right, text wrap', 'md' ) . $circle_label,
		'left'           => __( 'Left, text wrap', 'md' ) . $circle_label,
		'center'         => __( 'Center, no text wrap', 'md' ),
		'below_headline' => __( 'Full-width, below headline', 'md' ),
		'above_headline' => __( 'Full-width, above headline', 'md' ),
		'headline_cover' => __( 'Headline cover', 'md' ),
		'header_cover'   => __( 'Header cover', 'md' )
	);
	$position_default   = get_theme_mod( 'md_featured_image_position_default' );
	$position_label     = ! empty( $position_default ) && $position_default != '' ? $position_values[$position_default] : $position_values['right'];

	$caption     = get_post_meta( get_the_ID(), 'md_featured_image_caption', true );
	$caption_add = isset( $caption['add'] ) ? $caption['add'] : '';
?>

	<?php if ( ! md_is_admin_tax() ) : ?>
		<?php wp_nonce_field( basename( __FILE__ ), 'md_featured_image_nonce' ); ?>
	<?php endif; ?>

	<table class="md-featured-image-meta">
		<tbody>

			<!-- Position -->

			<tr valign="top">

				<td>
					<p><label for="<?php echo $id; ?>_position"><b><?php _e( 'Image Position', 'md' ); ?></b></label></p>
					<select name="<?php echo md_option_name( $id, 'position', null ); ?>" id="<?php echo $id; ?>_position">
						<?php foreach ( $position_values as $val => $label ) : ?>
							<option value="<?php esc_attr_e( $val ); ?>"<?php echo selected( $position, $val, false ); ?>><?php esc_html_e( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php if ( empty( $position ) ) : ?>
						<p class="description"><?php echo sprintf( __( 'The default image position is: %s', 'md' ), '<b>' . esc_html( $position_label ) . '</b>' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>

			<?php if ( ! $is_tax ) : ?>

				<!-- Caption -->

				<tr id="md-featured-image-caption" valign="top">

					<td>
						<p><label for="md_featured_image_caption"><b><?php _e( 'Image Caption', 'md' ); ?></b></label></p>

						<p><input type="checkbox" name="md_featured_image_caption[add]" id="md_featured_image_caption_add" value="1"<?php echo checked( $caption_add, 1, false ); ?> /> <label for="md_featured_image_caption_add"><?php _e( 'Add caption over image', 'md' ); ?></label></p>

						<p class="description"><?php _e( 'Edit your Featured Image to add a caption.', 'md' ); ?></p>

					</td>

				</tr>

			<?php endif; ?>

		</tbody>
	</table>

<?php }

add_action( 'md_featured_image_options', 'md_featured_image_meta' );


/**
 * Build Layout meta box controls.
 *
 * @since 4.1
 */

function md_layout_meta() {
	$screen     = get_current_screen();
	$is_tax     = md_is_admin_tax();
	$tax_name   = $screen->taxonomy == 'category' ? 'tax' : $screen->taxonomy;
	$id         = 'md_layout' . ( $is_tax ? "_{$tax_name}" . $_GET['tag_ID'] : '' );
	$option     = get_option( $id );

	// header
	$option['header']  = isset( $option['header'] ) ? $option['header'] : '';
	$header            = $is_tax ? $option['header'] : get_post_meta( get_the_ID(), "{$id}_header", true );
	$header['remove']  = isset( $header['remove'] ) ? $header['remove'] : '';
	$header['logo']    = isset( $header['logo'] ) ? $header['logo'] : '';
	$header['tagline'] = isset( $header['tagline'] ) ? $header['tagline'] : '';
	$header['menu']    = isset( $header['menu'] ) ? $header['menu'] : '';
	$header_display    = empty( $header['remove'] ) ? 'block' : 'none';
	// content
	$option['content']             = isset( $option['content'] ) ? $option['content'] : '';
	$content                       = $is_tax ? $option['content'] : get_post_meta( get_the_ID(), "{$id}_content", true );
	$content['remove_content_box'] = isset( $content['remove_content_box'] ) ? $content['remove_content_box'] : '';
	$content['remove_headline']    = isset( $content['remove_headline'] ) ? $content['remove_headline'] : '';
	$content['remove_byline']      = isset( $content['remove_byline'] ) ? $content['remove_byline'] : '';
	$content_box_display           = empty( $content['remove_content_box'] ) ? 'block' : 'none';
	$headline_display              = empty( $content['remove_headline'] ) ? 'block' : 'none';
	// content box
	$option['content_box'] = isset( $option['content_box'] ) ? $option['content_box'] : '';
	$content_box           = $is_tax ? $option['content_box'] : get_post_meta( get_the_ID(), "{$id}_content_box", true );
	$content_box_values    = array(
		''                => __( 'Select content layout...', 'md' ),
		'content_sidebar' => __( 'Content / Sidebar (default)', 'md' ),
		'sidebar_content' => __( 'Sidebar / Content', 'md' )
	);
	// sidebar
	$option['sidebar'] = isset( $option['sidebar'] ) ? $option['sidebar'] : '';
	$sidebar           = $is_tax ? $option['sidebar'] : get_post_meta( get_the_ID(), "{$id}_sidebar", true );
	$sidebar['add']    = isset( $sidebar['add'] ) ? $sidebar['add'] : '';
	$sidebar['remove'] = isset( $sidebar['remove'] ) ? $sidebar['remove'] : '';
	$sidebar['custom'] = isset( $sidebar['custom'] ) ? $sidebar['custom'] : '';
	$sidebar_site      = get_theme_mod( 'md_layout_sidebar_enable' );
	$sidebar_display   =
		( $screen->post_type == 'post' && empty( $sidebar_site ) && ! empty( $sidebar['add'] ) ) || // posts, post/archives sidebars off, post added sidebar
		( $screen->post_type == 'post' && ! empty ( $sidebar_site ) && empty( $sidebar['remove'] ) ) || // posts, post/archives sidebars on, post doesn't add sidebar
		( $screen->post_type != 'post' && ! empty( $sidebar['add'] ) ) // anything post type, content type doesn't add sidebar
	? 'block' : 'none';
	// footer
	$option['footer']   = isset( $option['footer'] ) ? $option['footer'] : '';
	$footer            = $is_tax ? $option['footer'] : get_post_meta( get_the_ID(), "{$id}_footer", true );
	$footer['remove']  = isset( $footer['remove'] ) ? $footer['remove'] : '';
	$footer['columns'] = isset( $footer['columns'] ) ? $footer['columns'] : '';
	$footer_display    = empty( $footer['remove'] ) ? 'block' : 'none';
?>

	<?php if ( $is_tax ) : ?>

		<tr class="form-field term-md-wrap md">
			<td colspan="2">
				<div id="poststuff" class="meta-box-sortables postbox-container md-content-wrap">
					<div class="postbox closed">
						<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php echo sprintf( __( 'Toggle panel: %s', 'md' ), __( 'Layout', 'md' ) ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
						<h2 class="hndle ui-sortable-handle"><span><?php _e( 'Layout', 'md' ); ?></span></h2>
						<div class="inside">

	<?php else : ?>

		<?php wp_nonce_field( basename( __FILE__ ), 'md_layout_nonce' ); ?>

	<?php endif; ?>

	<table class="form-table">
		<tbody>

			<!-- Header -->

			<tr>
				<td>
					<p class="md-title"><?php _e( 'Header', 'md' ); ?></p>

					<p id="<?php echo $id; ?>_header[remove]" class="md-field">

						<input type="checkbox" name="<?php echo md_option_name( $id, 'header', 'remove' ); ?>" id="<?php echo $id; ?>_header_remove" value="1"<?php echo checked( $header['remove'], 1, false ); ?> />

						<label for="<?php echo $id; ?>_header_remove"><?php _e( 'Remove <b>Header</b>', 'md' ); ?></label>

					</p>

					<div id="md-layout-header-options" style="display: <?php echo $header_display; ?>;">

						<p id="<?php echo $id; ?>_header[logo]" class="md-field">

							<input type="checkbox" name="<?php echo md_option_name( $id, 'header', 'logo' ); ?>" id="<?php echo $id; ?>_header_logo" value="1"<?php echo checked( $header['logo'], 1, false ); ?> />

							<label for="<?php echo $id; ?>_header_logo"><?php _e( 'Remove <b>Logo</b>', 'md' ); ?></label>

						</p>

						<?php if ( md_has_tagline() ) : ?>

							<p id="<?php echo $id; ?>_header[tagline]" class="md-field">

								<input type="checkbox" name="<?php echo md_option_name( $id, 'header', 'tagline' ); ?>" id="<?php echo $id; ?>_header_tagline" value="1"<?php echo checked( $header['tagline'], 1, false ); ?> />

								<label for="<?php echo $id; ?>_header_tagline"><?php _e( 'Remove <b>Tagline</b>', 'md' ); ?></label>

							</p>

						<?php endif; ?>

						<p id="<?php echo $id; ?>_header[menu]" class="md-field">

							<input type="checkbox" name="<?php echo md_option_name( $id, 'header', 'menu' ); ?>" id="<?php echo $id; ?>_header_menu" value="1"<?php echo checked( $header['menu'], 1, false ); ?> />

							<label for="<?php echo $id; ?>_header_menu"><?php _e( 'Remove <b>Menu</b>', 'md' ); ?></label>

						</p>

					</div>

				</td>
			</tr>

			<?php do_action( 'md_layout_after_header' ); ?>

			<!-- Content -->

			<tr>
				<td>

					<p class="md-title"><?php _e( 'Content', 'md' ); ?></p>

					<p id="<?php echo $id; ?>_content[remove_content_box]" class="md-field">

						<input type="checkbox" name="<?php echo md_option_name( $id, 'content', 'remove_content_box' ); ?>" id="<?php echo $id; ?>_content_remove_content_box" value="1"<?php echo checked( $content['remove_content_box'], 1, false ); ?> />

						<label for="<?php echo $id; ?>_content_remove_content_box"><?php _e( 'Remove <b>Content Box</b>', 'md' ); ?></label>

					</p>

					<?php if ( ! $is_tax ) : ?>

						<div id="md-layout-content-box-options" style="display: <?php echo $content_box_display; ?>;">

							<!-- Remove Headline -->

							<p id="<?php echo $id; ?>_content[remove_headline]" class="md-field">

								<input type="checkbox" name="<?php echo md_option_name( $id, 'content', 'remove_headline' ); ?>" id="<?php echo $id; ?>_content_remove_headline" value="1"<?php echo checked( $content['remove_headline'], 1, false ); ?> />

								<label for="<?php echo $id; ?>_content_remove_headline"><?php _e( 'Remove <b>Headline</b>', 'md' ); ?></label>

							</p>

							<?php if ( $screen->post_type == 'post' ) : ?>

								<!-- Headline -->

								<div id="md-layout-headline-options" style="display: <?php echo $headline_display; ?>;">

									<p id="<?php echo $id; ?>_content[remove_byline]" class="md-field">

										<input type="checkbox" name="<?php echo md_option_name( $id, 'content', 'remove_byline' ); ?>" id="<?php echo $id; ?>_content_remove_byline" value="1"<?php echo checked( $content['remove_byline'], 1, false ); ?> />

										<label for="<?php echo $id; ?>_content_remove_byline"><?php _e( 'Remove <b>Byline</b>', 'md' ); ?></label>

									</p>

								</div>

							<?php endif; ?>

						</div>

					<?php endif; ?>

				</td>
			</tr>

			<!-- Sidebar -->

			<tr id="<?php echo $id; ?>_sidebar_row" style="display: <?php echo $content_box_display; ?>;">
				<td>

					<p class="md-title"><?php _e( 'Sidebar', 'md' ); ?></p>

					<?php if ( ( $is_tax || $screen->post_type == 'post' ) && ! empty( $sidebar_site ) ) : ?>

						<p id="<?php echo $id; ?>_sidebar[remove]" class="md-field">

							<input type="checkbox" name="<?php echo md_option_name( $id, 'sidebar', 'remove' ); ?>" id="<?php echo $id; ?>_sidebar_remove" value="1"<?php echo checked( $sidebar['remove'], 1, false ); ?> />

							<label for="<?php echo $id; ?>_sidebar_remove"><?php _e( 'Remove <b>Sidebar</b>', 'md' ); ?></label>

						</p>

					<?php else : ?>

						<p id="<?php echo $id; ?>_sidebar[add]" class="md-field">

							<input type="checkbox" name="<?php echo md_option_name( $id, 'sidebar', 'add' ); ?>" id="<?php echo $id; ?>_sidebar_add" value="1"<?php echo checked( $sidebar['add'], 1, false ); ?> />

							<label for="<?php echo $id; ?>_sidebar_add"><?php _e( 'Add <b>Main Sidebar</b>', 'md' ); ?></label>

						</p>

					<?php endif; ?>

					<div id="md-layout-sidebar-options" style="display: <?php echo $sidebar_display; ?>;">

						<?php if ( ( $screen->post_type == 'post' || $screen->post_type == 'page' ) && ! $is_tax ) : ?>

							<!-- Custom -->

							<p id="<?php echo $id; ?>_sidebar[custom]" class="md-field">

								<input type="checkbox" name="<?php echo md_option_name( $id, 'sidebar', 'custom' ); ?>" id="<?php echo $id; ?>_sidebar_custom" value="1"<?php echo checked( $sidebar['custom'], 1, false ); ?> />

								<label for="<?php echo $id; ?>_sidebar_custom"><?php _e( 'Create <b>Custom Sidebar</b>', 'md' ); ?></label>

							</p>

							<p class="description"><?php echo sprintf( __( 'Edit custom sidebar in <a href="%s" target="_blank">Widgets</a>.', 'md' ), admin_url( 'widgets.php' ) ); ?></p>

						<?php endif; ?>

						<!-- Layout -->

						<p id="<?php echo $id; ?>_content_box" class="md-field">

							<select name="<?php echo md_option_name( $id, 'content_box', '' ); ?>" id="<?php echo $id; ?>_content_box">

								<?php foreach ( $content_box_values as $val => $label ) : ?>
									<option value="<?php esc_attr_e( $val ); ?>"<?php echo selected( $content_box, $val, false ); ?>><?php esc_html_e( $label ); ?></option>
								<?php endforeach; ?>

							</select>

						</p>

					</div>

				</td>
			</tr>

			<!-- Footer -->

			<tr>
				<td>

					<p class="md-title"><?php _e( 'Footer', 'md' ); ?></p>

					<p id="<?php echo $id; ?>_footer[remove]" class="md-field">

						<input type="checkbox" name="<?php echo md_option_name( $id, 'footer', 'remove' ); ?>" id="<?php echo $id; ?>_footer_remove" value="1"<?php echo checked( $footer['remove'], 1, false ); ?> />

						<label for="<?php echo $id; ?>_footer_remove"><?php _e( 'Remove <b>Footer</b>', 'md' ); ?></label>

					</p>

					<div id="md-layout-footer-options" style="display: <?php echo $footer_display; ?>;">

						<p id="<?php echo $id; ?>_footer[columns]" class="md-field">

							<input type="checkbox" name="<?php echo md_option_name( $id, 'footer', 'columns' ); ?>" id="<?php echo $id; ?>_footer_columns" value="1"<?php echo checked( $footer['columns'], 1, false ); ?> />

							<label for="<?php echo $id; ?>_footer_columns"><?php _e( 'Remove <b>Footer Columns</b>', 'md' ); ?></label>

						</p>

					</div>

				</td>
			</tr>

		</tbody>
	</table>

	<?php if ( $is_tax ) : ?>
						</div>
					</div>
				</div>
			</td>
		</tr><!-- end category html -->

	<?php endif; ?>

<?php }


/**
 * Adds admin footer scripts that toggle different Layout options based on selections.
 *
 * @since 4.1
 */

function md_layout_meta_scripts() {
	$screen     = get_current_screen();
	$post_types = array_merge( array( 'post', 'page' ), apply_filters( 'md_post_type_meta', array() ) );
	$is_editor  = in_array( $screen->post_type, $post_types ) && ( $screen->base == 'post' || $screen->base == 'post-new' ) ? true : false;
	$is_tax     = md_is_admin_tax();
	$tax_name   = $screen->taxonomy == 'category' ? 'tax' : $screen->taxonomy;
	$prefix     = 'md_layout' . ( $is_tax ? "_{$tax_name}" . $_GET['tag_ID'] : '' );

	if ( ! $is_editor && ! $is_tax )
		return;

	$sidebar_site = get_theme_mod( 'md_layout_sidebar_enable' );
?>

	<script>

		( function() {

			document.getElementById( '<?php echo $prefix; ?>_header_remove' ).onchange = function( e ) {
				document.getElementById( 'md-layout-header-options' ).style.display = this.checked ? 'none' : 'block';
			}

			document.getElementById( '<?php echo $prefix; ?>_content_remove_content_box' ).onchange = function( e ) {
				<?php if ( ! $is_tax ) : ?>
					document.getElementById( 'md-layout-content-box-options' ).style.display = this.checked ? 'none' : 'block';
				<?php endif; ?>
				document.getElementById( '<?php echo $prefix; ?>_sidebar_row' ).style.display = this.checked ? 'none' : 'block';
			}

			<?php if ( $screen->post_type == 'post' && ! $is_tax ) : ?>
				document.getElementById( '<?php echo $prefix; ?>_content_remove_headline' ).onchange = function( e ) {
					document.getElementById( 'md-layout-headline-options' ).style.display = this.checked ? 'none' : 'block';
				}
			<?php endif; ?>

			<?php if ( ( $is_tax || $screen->post_type == 'post' ) && ! empty( $sidebar_site ) ) : ?>
				document.getElementById( '<?php echo $prefix; ?>_sidebar_remove' ).onchange = function( e ) {
					document.getElementById( 'md-layout-sidebar-options' ).style.display = this.checked ? 'none' : 'block';
				}
			<?php else : ?>
				document.getElementById( '<?php echo $prefix; ?>_sidebar_add' ).onchange = function( e ) {
					document.getElementById( 'md-layout-sidebar-options' ).style.display = this.checked ? 'block' : 'none';
				}
			<?php endif; ?>

			document.getElementById( '<?php echo $prefix; ?>_footer_remove' ).onchange = function( e ) {
				document.getElementById( 'md-layout-footer-options' ).style.display = this.checked ? 'none' : 'block';
			}

		} )();

	</script>

<?php }

add_action( 'admin_print_footer_scripts', 'md_layout_meta_scripts' );


/**
 * This function (and idea) was taken from Volatyl and written
 * by Sean Davis (http://volatylthemes.com/) & Andrew Norcross
 * (http://reaktivstudios.com/).
 *
 * @since 4.1
 */

function md_custom_sidebar( $meta = '' ) {
	if ( empty( $meta ) )
		return false;

	$screens = array_merge( array( 'post', 'page' ), md_post_types_meta() );

	if ( get_transient( "{$meta}_md_custom_sidebar" ) === false ) {
		$items = get_posts( array(
			'fields'         => 'ids',
			'post_type'      => $screens,
			'meta_key'	     => $meta, // is array, use meta_value_num to find 'add' key (if its the first key in array)
			'meta_value_num' => 1,
			'nopaging'       => true
		) );

		if ( ! $items )
			return false;

		set_transient( "{$meta}_md_custom_sidebar", $items, 0 );
	}

	return get_transient( "{$meta}_md_custom_sidebar" );
}


/**
 * Sanitize data for taxonomy options.
 *
 * @since 4.3.5
 */

function md_save_taxonomy( $term_id ) {
	$tax_name = $_POST['taxonomy'] == 'category' ? 'tax' : $_POST['taxonomy'];

	foreach ( array( 'layout', 'featured_image' ) as $meta_box ) {
		$id = "md_{$meta_box}_{$tax_name}{$term_id}";

		if ( ! isset( $_POST[$id] ) )
			return;

		$val    = $_POST[$id];
		$meta   = get_option( $id );
		$fields = md_meta_data();

		foreach ( $fields as $option => $field ) {
			$type         = isset( $field['type'] ) ? $field['type'] : '';
			$options      = isset( $field['options'] ) ? $field['options'] : '';
			$val[$option] = isset( $val[$option] ) ? $val[$option] : '';

			if ( $type == 'checkbox' && $options )
				foreach ( $options as $check )
					$meta[$option][$check] = ! empty( $val[$option][$check] ) ? 1 : 0;

			if ( $type == 'select' )
				$meta[$option] = in_array( $val[$option], $options ) ? $val[$option] : '';
		}

		update_option( $id, $meta );
	}
}


/**
 * Save meta box settings created above to database.
 *
 * @since 4.1
 */

function md_save_meta_boxes( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if (
		( ! isset( $_POST['md_featured_image_nonce'] ) || ! wp_verify_nonce( $_POST['md_featured_image_nonce'], basename( __FILE__ ) ) ) ||
		( ! isset( $_POST['md_layout_nonce'] ) || ! wp_verify_nonce( $_POST['md_layout_nonce'], basename( __FILE__ ) ) )
	)
		return;

	$post_type = get_post_type_object( $post->post_type );

	 if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) )
		return;

	// organize meta keys by type for easier validation

	$keys = md_meta_data( true );

	// run keys through save meta functions

	foreach ( $keys as $key => $fields ) {
		$type    = isset( $fields['type'] ) ? $fields['type'] : '';
		$options = isset( $fields['options'] ) ? $fields['options'] : '';

		$new = isset( $_POST[$key] ) ? $_POST[$key] : '';

		if ( $type == 'checkbox' )
			foreach ( $options as $check )
				$new[$check] = isset( $new[$check] ) ? 1 : 0;

		elseif ( $type == 'select' )
			$new = in_array( $new, $options ) ? $new : '';

		$value = get_post_meta( $post_id, $key, true );

		if ( $new && $new != $value )
			update_post_meta( $post_id, $key, $new );
		elseif ( $new == '' && $value )
			delete_post_meta( $post_id, $key, $value );
	}

	delete_transient( 'md_layout_sidebar_md_custom_sidebar' ); // todo: transient not deleteing because of reason ?
}