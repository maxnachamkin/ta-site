<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

// Constants

define( 'MD_API_DIR', MD_PLUGIN_DIR . 'api/' );

require_once( MD_API_DIR . 'template-functions.php' );
require_once( MD_API_DIR . 'sanitize.php' );
require_once( MD_API_DIR . 'fields/design/design.php' );
require_once( MD_API_DIR . 'fields/button/button.php' );


/**
 * Extend this class to create powerful WordPress settings pages
 * and meta boxes.
 *
 * This little API powers the Page Leads system from Kolak and
 * can be used to easily create other plugins that run in a
 * similar manner.
 *
 * @since 0.8
 */

class md_api {

	public $_id;
	public $_clean_id;
	public $_add_page;
	public $_get_option;
	public $_tab;
	public $_active_tab;
	public $_allowed_html = array(
		'div' => array(
			'class' => array(),
			'id'    => array(),
			'style' => array()
		),
		'ul' => array(
			'class' => array(),
			'id'    => array(),
			'style' => array()
		),
		'ol' => array(
			'class' => array(),
			'id'    => array(),
			'style' => array()
		),
		'li' => array(
			'class' => array(),
			'id'    => array(),
			'style' => array()
		),
		'p' => array(
			'class' => array(),
			'id'    => array(),
			'style' => array()
		),
		'a' => array(
			'href'  => array(),
			'class' => array(),
			'id'    => array()
		),
		'span' => array(
			'class' => array(),
			'id'    => array(),
			'style' => array()
		),
		'img' => array(
			'src'    => array(),
			'alt'    => array(),
			'height' => array(),
			'width'  => array(),
			'class'  => array(),
			'id'     => array()
		),
		'acronym' => array(
			'title' => array()
		),
		'br' => array(),
		'b'  => array(),
		'i'  => array(
			'class' => array()
		),
		'em'  => array(
			'class' => array()
		),
		'small' => array(
			'class' => array(),
			'style' => array()
		),
		's' => array()
	);

	public $suite;
	public $admin_page;
	public $admin_tab   = '';
	public $meta_box    = '';


	/**
	 * This constructor loads all potential features of
	 * an admin screen / meta box, as well as the subclasses
	 * pseudo constructor, if it has one.
	 *
	 * @since 0.8
	 */

	public function __construct( $id = null ) {

		$this->_id = isset( $id ) ? $id : get_class( $this );

		// Load subclass' psuedo contructor, if it exists

		if ( method_exists( $this, 'construct' ) )
			$this->construct();

		// Set core properties

		$this->_clean_id    = preg_replace( '/^' . preg_quote( "{$this->suite}_", '/' ) . '/', '', $this->_id );
		$this->_get_option  = get_option( $this->_id );
		$this->_tab         = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
		$this->_active_tab  = $this->_tab ? $this->_tab : '';
		$this->_page        = isset( $_GET['page'] ) ? $_GET['page'] : '';
		$this->_active_page = $this->_page ? $this->_page : '';

		// Kolakube

		$this->email      = get_option( 'md_email' );
		$this->email_data = get_option( 'md_email_data' );

		$this->page_leads          = get_option( 'page_leads' );
		$this->page_leads['leads'] = isset( $this->page_leads['leads'] ) ? $this->page_leads['leads'] : array();
		$this->page_leads['hooks'] = isset( $this->page_leads['hooks'] ) ? $this->page_leads['hooks'] : array();

		if ( is_admin() ) {
			if ( ! empty( $this->email_data ) && ! isset( $this->email_data['fields']['email_form_title'] ) ) { // hack, need dynamic email data (email refresh)
				$this->email_data['fields']['email_form_title']  = array( 'type' => 'text' );
				$this->email_data['fields']['email_form_footer'] = array( 'type' => 'textarea' );
			}

			// Register Settings

			if ( $this->admin_page || $this->admin_tab )
				add_action( 'admin_init', array( $this, '_register_setting' ) );

			// Admin page

			if ( $this->admin_page ) {
				add_action( 'admin_menu', array( $this, 'add_menu' ) );

				if ( isset( $this->admin_page['default_tab'] ) && $this->_active_page == $this->suite && empty( $this->_tab ) )
					add_action( "{$this->suite}_admin_tab_content", array( $this, 'admin_tab_content' ) );
			}

			// Admin tab / content

			if ( is_array( $this->admin_tab ) && isset( $this->admin_tab['name'] ) ) {
				if ( ! isset( $this->admin_tab['priority'] ) )
					$this->admin_tab['priority'] = 10;

				add_action( "{$this->suite}_admin_tabs", array( $this, 'admin_tab' ), $this->admin_tab['priority'] );
			}

			if ( method_exists( $this, 'fields' ) && $this->_active_tab == $this->_clean_id )
				add_action( "{$this->suite}_admin_tab_content", array( $this, 'admin_tab_content' ) );

			// Meta box

			if ( isset( $this->meta_box ) && isset( $this->meta_box['name'] ) ) {
				add_action( 'load-post.php', array( $this, 'meta_boxes' ) );
				add_action( 'load-post-new.php', array( $this, 'meta_boxes' ) );
				add_filter( 'is_protected_meta', array( $this, 'hide_meta_keys' ), 10, 2 );
			}

			// Taxonomy

			if ( isset( $this->taxonomy ) && isset( $this->taxonomy['name'] ) )
				add_action( 'init', array( $this, 'taxonomy_meta' ) );

			// Scripts

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'admin_inline_scripts' ) );
		}

		// Utilities

		if ( method_exists( $this, 'template_redirect' ) )
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );
	}


	/**
	 * Adds new admin page to admin panel.
	 *
	 * @since 0.8
	 */

	public function add_menu() {
		$parent_slug = isset( $this->admin_page['parent_slug'] ) ? $this->admin_page['parent_slug'] : 'options-general.php';
		$capability  = isset( $this->admin_page['capability'] ) ? $this->admin_page['capability'] : 'manage_options';
		$callback    = isset( $this->admin_page['callback'] ) ? $this->admin_page['callback'] : array( $this, 'admin_page' );
		$menu_slug   = isset( $this->admin_page['menu_slug'] ) ? $this->admin_page['menu_slug'] : $this->suite;
		$icon        = isset( $this->admin_page['icon'] ) ? $this->admin_page['icon'] : '';
		$position    = isset( $this->admin_page['position'] ) ? $this->admin_page['position'] : 65;
		$menu_title  = isset( $this->admin_page['menu_title'] ) ? $this->admin_page['menu_title'] : $this->admin_page['name'];

		if ( ! empty( $this->admin_page['toplevel'] ) )
			$this->_add_page = add_menu_page( $this->admin_page['name'], $menu_title, $capability, $menu_slug, $callback, $icon, $position );
		else
			$this->_add_page = add_submenu_page( $parent_slug, $this->admin_page['name'], $this->admin_page['name'], $capability, $menu_slug, $callback );

		if ( ! empty( $this->admin_page['hide_menu'] ) )
			remove_submenu_page( $parent_slug, $menu_slug );
	}


	/**
	 * Registers settings of an entire class into single
	 * array.
	 *
	 * @since 1.0
	 */

	public function _register_setting() {
		register_setting( $this->_id, $this->_id, array( $this, 'admin_save' ) );
	}


	/**
	 * Outputs MD header for use around different admin pages.
	 * Hard-coded to show active tab on specific pages.
	 *
	 * @since 4.3.2
	 */

	public function admin_header() {
		$screen = get_current_screen();
	?>

		<div class="md-header md-clear">

			<div class="md-header-title">

				<h1 class="md-inline"><?php _e( 'Marketers Delight', 'md' ); ?> <span class="title-count theme-count"><?php echo MD_VERSION; ?></span></h1>

			</div>

			<h2 class="nav-tab-wrapper md-header-nav">

				<?php do_action( 'md_hook_panel_tab' ); ?>

				<span class="md-links alignright">
					<a href="<?php echo KOL_ACCOUNT; ?>" target="_blank"><?php _e( 'MD Account', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="<?php echo KOL_GUIDES; ?>" target="_blank"><?php _e( 'Tutorials', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="<?php echo KOL_SUPPORT; ?>" target="_blank"><?php _e( 'Forums', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="<?php echo KOL_AFFILIATES; ?>" target="_blank"><?php _e( 'Affiliates', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="<?php echo KOL_NEWSLETTER; ?>" target="_blank"><?php _e( 'Newsletter', 'md' ); ?></a>
				</span>

			</h2>

			<?php if ( has_action( "{$this->suite}_admin_tabs" ) ) : ?>
				<div class="md-tabs">
					<?php do_action( "{$this->suite}_admin_tabs" ); ?>
				</div>
			<?php endif; ?>

		</div>

<?php }


	/**
	 * Builds out individual tab for use in tabbed navigation.
	 *
	 * @since 0.8
	 */

	public function admin_tab() { ?>

		<a href="?page=<?php echo urlencode( $this->suite ); ?>&tab=<?php echo $this->_clean_id; ?>" class="md-tab<?php echo $this->_clean_id == $this->_active_tab ? ' md-tab-active' : ''; ?>" title="<?php esc_attr_e( $this->admin_tab['name'] ); ?>">

			<?php if ( isset( $this->admin_tab['dashicon'] ) ) : ?>
				<i class="md-tab-dashicon dashicons dashicons-admin-<?php esc_attr_e( $this->admin_tab['dashicon'] ); ?>"></i>
			<?php endif; ?>

			<?php _e( $this->admin_tab['name'] ); ?>

		</a>

	<?php }


	/**
	 * Builds admin tab content using the Settings API. Goes with admin_tab().
	 *
	 * @todo Load postboxes script only when needed
	 * @since 0.8
	 */

	public function admin_tab_content() {
		$save      = isset( $this->admin_tab['save'] ) ? $this->admin_tab['save'] : true;
		$save_text = ! empty( $this->admin_tab['save_text'] ) ? $this->admin_tab['save_text'] : '';
	?>

		<?php settings_fields( $this->_id ); ?>

		<?php $this->fields(); ?>

		<?php if ( $save ) : ?>
			<?php submit_button( $save_text ); ?>
		<?php endif; ?>

	<?php }


	/**
	 * Saves all types of fields from the Settings API.
	 *
	 * @todo trickle down data so saving repeatable fields isn't so tedious
	 * @since 1.0
	 */

	public function admin_save( $input ) {
		$valid = '';
		foreach ( $this->register_fields() as $option => $field ) {
			$type           = isset( $field['type'] ) ? $field['type'] : '';
			$options        = isset( $field['options'] ) ? $field['options'] : '';
			$input[$option] = isset( $input[$option] ) ? $input[$option] : '';

			if ( $type == 'text' || $type == 'textarea' )
				$valid[$option] = wp_kses( $input[$option], $this->_allowed_html );

			elseif ( $type == 'number' )
				$valid[$option] = preg_replace( '/\D/', '', $input[$option] );

			elseif ( $type == 'code' )
				$valid[$option] = $input[$option];

			elseif ( $type == 'url' )
				$valid[$option] = wp_kses_bad_protocol( $input[$option], array( 'http', 'https' ) );

			elseif ( $type == 'checkbox' && $options )
				foreach ( $options as $check )
					$valid[$option][$check] = ! empty( $input[$option][$check] ) ? 1 : 0;

			elseif ( $type == 'select' )
				$valid[$option] = in_array( $input[$option], $options ) ? $input[$option] : '';

			elseif ( $type == 'media' || $type == 'image' )
				$valid[$option] = esc_url( $input[$option] );

			elseif ( $type == 'repeat' ) {
				if ( is_array( $input[$option] ) ) {
					foreach ( $input[$option] as $repeat_count => $repeat_input )
						foreach ( $field['repeat_fields'] as $repeat_id => $repeat_field ) {
							if ( $repeat_field['type'] == 'text' ) {
								$valid[$option][$repeat_count][$repeat_id] = wp_kses( $input[$option][$repeat_count][$repeat_id], $this->_allowed_html );
							}
							elseif ( $repeat_field['type'] == 'checkbox' && $repeat_field['options'] ) {
								foreach ( $repeat_field['options'] as $check )
									$valid[$option][$repeat_count][$repeat_id][$check] = ! empty( $input[$option][$repeat_count][$repeat_id][$check] ) ? 1 : 0;
							}
							elseif ( $repeat_field['type'] == 'media' ) {
								$valid[$option][$repeat_count][$repeat_id] = esc_url( $input[$option][$repeat_count][$repeat_id] );
							}
						}
				}
				else
					$valid[$option] = false;
			}

			elseif ( $type == 'color' )
				$valid[$option] = preg_match( '/^#[a-f0-9]{6}$/i', $input[$option] ) ? $input[$option] : '';
		}

		return $valid;
	}


	/**
	 * Loads meta boxes data to WordPress.
	 *
	 * @since 0.8
	 */

	public function meta_boxes() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'meta_save' ), 10, 2 );
	}


	/**
	 * Adds meta boxes to interface.
	 *
	 * @since 0.8
	 */

	public function add_meta_boxes() {
		if ( isset( $this->meta_box['callback'] ) )
			$callback = $this->meta_box['callback'];
		elseif ( isset( $this->meta_box['module'] ) )
			$callback = array( $this, 'module_fields' );
		else
			$callback = array( $this, 'meta_fields' );

		$post_types = isset( $this->meta_box['post_type'] ) ? $this->meta_box['post_type'] : array_merge( array( 'post', 'page' ), apply_filters( 'md_post_type_meta', array() ) );
		$context    = isset( $this->meta_box['context'] ) ? $this->meta_box['context'] : 'advanced';
		$priority   = isset( $this->meta_box['priority'] ) ? $this->meta_box['priority'] : 'default';

		foreach ( $post_types as $post_type )
			add_meta_box( $this->_id, $this->meta_box['name'], $callback, $post_type, $context, $priority );
	}


	/**
	 * Default meta box callback method.
	 *
	 * @since 0.8
	 */

	public function meta_fields() { ?>

		<?php wp_nonce_field( basename( __FILE__ ), "{$this->_id}_nonce" ); ?>

		<div class="md-meta-box md">

			<?php $this->fields(); ?>

		</div>

	<?php }


	/**
	 * If creating modules, use this callback. This activates
	 * the module to pull from global settings and allows user's
	 * to override them by creating custom modules.
	 *
	 * See Page Leads for examples.
	 *
	 * @since 0.8
	 */

	public function module_fields() {
		$screen        = get_current_screen();
		$module_enable = (
			isset( $this->meta_box['module'] ) && ! isset( $this->meta_box['module']['enable'] ) ||
			isset( $this->taxonomy['module'] ) && ! isset( $this->taxonomy['module']['enable'] )
		) ? true : false;

		if ( ! empty( $screen->taxonomy ) ) {
			$tax_name                  = $screen->taxonomy == 'category' ? 'tax' : $screen->taxonomy;
			$tax                       = get_option( "{$this->_id}_{$tax_name}" . $_GET['tag_ID'] );
			$tax['activate']['enable'] = ! empty( $tax['activate']['enable'] ) ? $tax['activate']['enable'] : '';
			$tax['custom']['enable']   = ! empty( $tax['custom']['enable'] ) ? $tax['custom']['enable'] : '';
			$enable_field              = $tax['activate']['enable'];
			$custom_field              = $tax['custom']['enable'];
		}
		else {
			$meta_activate = get_post_meta( get_the_ID(), "{$this->_id}_activate", true );
			$meta_custom   = get_post_meta( get_the_ID(), "{$this->_id}_custom", true );
			$enable_field = ! empty( $meta_activate['enable'] ) ? $meta_activate['enable'] : '';
			$custom_field = ! empty( $meta_custom['enable'] ) ? $meta_custom['enable'] : '';
		}

		$enable = ! empty( $enable_field ) || ! $module_enable ? 'block' : 'none';
		$custom = ! empty( $custom_field ) ? 'block' : 'none';
	?>

		<div class="md-meta-box md">

			<?php if ( empty( $screen->taxonomy ) ) : ?>
				<?php wp_nonce_field( basename( __FILE__ ), "{$this->_id}_nonce" ); ?>
			<?php endif; ?>

			<!-- Enable -->

			<?php if ( $module_enable ) : ?>
				<?php $this->field( 'checkbox', 'activate', array(
					'enable' => sprintf( __( 'Enable <b>%s</b>', 'md' ), $this->meta_box['name'] )
				) ); ?>
			<?php endif; ?>

			<div id="<?php echo "{$this->_id}_module"; ?>" style="display: <?php echo $enable; ?>">

				<!-- Custom -->

				<div id="<?php echo "{$this->_id}_custom"; ?>">

					<?php $this->field( 'checkbox', 'custom', array(
						'enable' => sprintf( __( 'Create Custom <b>%s</b>', 'md' ), str_replace( 'Custom ', '', $this->meta_box['name'] ) )
					) ); ?>

				</div>

				<!-- Fields -->

				<div id="<?php echo $this->_id; ?>_fields" style="display: <?php echo $custom; ?>">

					<hr />

					<?php $this->fields(); ?>

				</div>

			</div>

		</div>

	<?php }


	/**
	 * Creates toggle functionality for each module activation
	 * setting.
	 *
	 * @since 0.8
	 */

	public function module_inline_scripts() {
		$module_enable = (
			isset( $this->meta_box['module'] ) && ! isset( $this->meta_box['module']['enable'] ) ||
			isset( $this->taxonomy['module'] ) && ! isset( $this->taxonomy['module']['enable'] )
		) ? true : false;

		if ( ! $module_enable )
			return;
	?>

		<script>
			( function() {
				document.getElementById( '<?php echo "{$this->_id}_activate_enable"; ?>' ).onchange = function( e ) {
					document.getElementById( '<?php echo "{$this->_id}_module"; ?>' ).style.display = this.checked ? 'block' : 'none';
				}
				document.getElementById( '<?php echo "{$this->_id}_custom_enable"; ?>' ).onchange = function( e ) {
					document.getElementById( '<?php echo "{$this->_id}_fields"; ?>' ).style.display = this.checked ? 'block' : 'none';
				}
			})();
		</script>

	<?php }


	/**
	 * Saves all types of post meta fields.
	 *
	 * @todo trickle down data so saving repeatable fields isn't so tedious
	 * @since 1.0
	 */

	public function meta_save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! isset( $_POST["{$this->_id}_nonce"] ) || ! wp_verify_nonce( $_POST["{$this->_id}_nonce"], basename( __FILE__ ) ) )
			return $post_id;

		if ( ! current_user_can( get_post_type_object( $post->post_type )->cap->edit_post, $post_id ) )
			return $post_id;

		$fields = $this->register_fields();

		// add module activation settings before we save

		if ( isset( $this->meta_box['module'] ) )
			$fields['activate'] = $fields['custom'] = array(
				'type'    => 'checkbox',
				'options' => array( 'enable' )
			);

		// loop through settings and save them suckers

		foreach ( $fields as $option => $field ) {
			$type    = isset( $field['type'] ) ? $field['type'] : '';
			$options = isset( $field['options'] ) ? $field['options'] : '';

			$key = "{$this->_id}_$option";
			$new = isset( $_POST[$key] ) ? $_POST[$key] : '';

			if ( $type == 'text' || $type == 'textarea' )
				$new = wp_kses( $new, $this->_allowed_html );

			elseif ( $type == 'number' )
				$new = preg_replace( '/\D/', '', $new );

			elseif ( $type == 'code' )
				$new = $new;

			elseif ( $type == 'url' )
				$new = wp_kses_bad_protocol( $new, array( 'http', 'https' ) );

			elseif ( $type == 'checkbox' )
				foreach ( $options as $check )
					$new[$check] = isset( $new[$check] ) ? 1 : 0;

			elseif ( $type == 'select' )
				$new = in_array( $new, $options ) ? $new : '';

			elseif ( $type == 'media' || $type == 'image' )
				$new = esc_url( $new );

			elseif ( $type == 'repeat' ) {
				if ( is_array( $new ) ) {
					foreach ( $new as $repeat_count => $repeat_input )
						foreach ( $field['repeat_fields'] as $repeat_id => $repeat_field ) {
							if ( $repeat_field['type'] == 'text' || $repeat_field['type'] == 'textarea' )
								$new[$repeat_count] = wp_kses( $new[$repeat_count], $this->_allowed_html );
						}
				}
				else
					$new = false;
			}

			$value = get_post_meta( $post_id, $key, true );

			if ( $new && $new != $value )
				update_post_meta( $post_id, $key, $new );
			elseif ( $new == '' && $value )
				delete_post_meta( $post_id, $key, $value );
		}
	}


	/**
	 * So no meta values show up in the Custom Fields meta
	 * box, loop through all fields to hide them.
	 *
	 * @since 0.8
	 */

	public function hide_meta_keys( $protected, $meta_key ) {
		foreach ( $this->register_fields() as $key => $fields )
			if ( "{$this->_id}_$key" == $meta_key )
				return true;

		return $protected;
	}


	/**
	 * Load taxonomy data.
	 *
	 * @since 4.3.5
	 */

	public function taxonomy_meta() {
		$taxonomies = array_merge( array( 'category' ), apply_filters( 'md_taxonomy_meta', array() ) );
		$priority   = isset( $this->taxonomy['priority'] ) ? $this->taxonomy['priority'] : 50;

		foreach ( $taxonomies as $tax ) {
			add_action( "{$tax}_edit_form_fields", array( $this, 'taxonomy_fields' ), $priority );
			add_action( "edited_{$tax}", array( $this, 'taxonomy_save' ), 10, 2 );

			if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == $tax && isset( $_GET['tag_ID'] ) ) {
				add_action( 'admin_footer-term.php', array( $this, 'module_inline_scripts' ) );
				add_action( 'admin_footer-edit-tags.php', array( $this, 'module_inline_scripts' ) ); #WP4.5 and below
			}
		}
	}


	public function taxonomy_fields() { ?>

		<tr class="form-field term-md-wrap md">
			<td colspan="2">

				<div id="poststuff" class="meta-box-sortables postbox-container md-content-wrap">
					<div class="postbox closed">
						<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?php echo sprintf( __( 'Toggle panel: %s', 'md' ), $this->taxonomy['name'] ); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
						<h2 class="hndle ui-sortable-handle"><span><?php echo $this->taxonomy['name']; ?></span></h2>
						<div class="inside">
							<?php if ( isset( $this->taxonomy['module'] ) ) : ?>
								<?php $this->module_fields(); ?>
							<?php else : ?>
								<?php $this->fields(); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>

			</td>
		</tr>

	<?php }


	/**
	 * Saves and sanitizes taxonomy fields. I need to combine all
	 * these different damn save methods.
	 *
	 * @since 1.1
	 */

	public function taxonomy_save( $term_id ) {
		$tax_name = $_POST['taxonomy'] == 'category' ? 'tax' : $_POST['taxonomy'];
		$_id      = "{$this->_id}_{$tax_name}{$term_id}";

		if ( ! isset( $_POST[$_id] ) )
			return;

		$val = $_POST[$_id];

		$meta = get_option( $_id );

		$fields = $this->register_fields();

		if ( isset( $this->taxonomy['module'] ) )
			$fields['activate'] = $fields['custom'] = array(
				'type'    => 'checkbox',
				'options' => array( 'enable' )
			);

		foreach ( $fields as $option => $field ) {
			$type         = isset( $field['type'] ) ? $field['type'] : '';
			$options      = isset( $field['options'] ) ? $field['options'] : '';
			$val[$option] = isset( $val[$option] ) ? $val[$option] : '';

			if ( $type == 'text' || $type == 'textarea' )
				$meta[$option] = wp_kses( $val[$option], $this->_allowed_html );

			if ( $type == 'number' )
				$meta[$option] = preg_replace( '/\D/', '', $val[$option] );

			elseif ( $type == 'code' )
				$meta[$option] = $val[$option];

			elseif ( $type == 'url' )
				$meta[$option] = wp_kses_bad_protocol( $val[$option], array( 'http', 'https' ) );

			elseif ( $type == 'checkbox' && $options )
				foreach ( $options as $check )
					$meta[$option][$check] = ! empty( $val[$option][$check] ) ? 1 : 0;

			elseif ( $type == 'select' )
				$meta[$option] = in_array( $val[$option], $options ) ? $val[$option] : '';

			elseif ( $type == 'media' || $type == 'image' )
				$meta[$option] = esc_url( $val[$option] );

			elseif ( $type == 'repeat' ) {
				if ( array_filter( $val[$option][0] ) )
					foreach ( $val[$option] as $repeat_count => $repeat_input )
						foreach ( $field['repeat_fields'] as $repeat_id => $repeat_field )
							if ( $repeat_field['type'] == 'text' )
								$meta[$option][$repeat_count][$repeat_id] = wp_kses( $val[$option][$repeat_count][$repeat_id], $this->_allowed_html );
				else
					$meta[$option] = false;
			}

			elseif ( $type == 'color' )
				$meta[$option] = preg_match( '/^#[a-f0-9]{6}$/i', $val[$option] ) ? $val[$option] : '';

		}

		update_option( $_id, $meta, true );
	}


	/**
	 * Loads all scripts and styles properly passed through various
	 * methods of enqueuing from subclasses.
	 *
	 * @since 0.8
	 */

	public function admin_scripts() {
		$screen = get_current_screen();
		$module = ( $this->admin_tab && $this->_tab == $this->_clean_id ) || ( $this->meta_box && $screen->base == 'post' || $screen->base == 'post-new' ) || ( ! empty( $screen->taxonomy ) ) ? true : false; // loads on admin tab + meta box screens

		// register scripts

		wp_register_style( 'md-admin', MD_PLUGIN_URL . 'admin/css/admin.css' );
		wp_register_script( 'md-media', MD_PLUGIN_URL . 'admin/js/media.js', array( 'jquery' ) );
		wp_register_script( 'md-color-picker', MD_PLUGIN_URL . 'admin/js/color-picker.js', array( 'wp-color-picker' ) );
		wp_register_script( 'md-repeat', MD_PLUGIN_URL . 'admin/js/repeat.js', array( 'jquery' ) );

		// enqueue

		wp_enqueue_style( 'md-admin' );

		// admin page

		if ( $this->admin_page && $screen->base == $this->_add_page && method_exists( $this, 'admin_page_scripts' ) )
			$this->admin_page_scripts();

		// module (admin page + taxonomy)

		if ( ( $this->admin_page && $screen->base == $this->_add_page ) || ! empty( $screen->taxonomy ) ) {
			// meta box scripts
			wp_enqueue_script( 'postbox' );
			wp_enqueue_script( 'md-postbox', MD_PLUGIN_URL . 'admin/js/postbox.js', array( 'postbox' ), '', true );
		}
	}


	/**
	 * Loads printed admin scripts from base class and subclasses.
	 *
	 * @since 4.3.5
	 */

	public function admin_inline_scripts() {
		$screen     = get_current_screen();
		$post_types = array_merge( array( 'post', 'page' ), apply_filters( 'md_post_type_meta', array() ) );
		$taxonomies = array_merge( array( 'category' ), apply_filters( 'md_taxonomy_meta', array() ) );
		$slug       = substr( $screen->base, -strlen( $this->suite ) ) === $this->suite; // check end of screen base for suite
		$is_editor  = in_array( $screen->post_type, $post_types ) && ( $screen->base == 'post' || $screen->base == 'post-new' ) ? true : false;
		$is_tax     = in_array( $screen->base, array( 'term', 'edit-tags' ) ) && isset( $_GET['tag_ID'] ) && ! empty( $screen->taxonomy ) && in_array( $screen->taxonomy, $taxonomies ) ? true : false;

		// footer scripts from extended class
		if (
			method_exists( $this, 'admin_print_footer_scripts' ) && (
			( $slug && $this->admin_tab && $this->_active_tab == $this->_clean_id ) || // admin tab
			( isset( $this->meta_box ) && $is_editor ) || // editor
			( isset( $this->taxonomy ) && $is_tax ) // taxonomy
		) )
			$this->admin_print_footer_scripts();

		// module scripts
		if ( isset( $this->meta_box['module'] ) && $is_editor )
			$this->module_inline_scripts();
	}


	public function register_fields() {
		return array();
	}


	public function module_field( $field ) {
		$screen = get_current_screen();

		if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ( $screen->base == 'post' || $screen->base == 'post-new' ) )
			return get_post_meta( get_the_ID(), "{$this->_id}_$field", true );
		elseif ( ! empty ( $screen->taxonomy ) ) {
			$tax_name = $screen->taxonomy == 'category' ? 'tax' : $screen->taxonomy;
			$tax      = get_option( "{$this->_id}_{$tax_name}" . $_GET['tag_ID'] );
			return isset( $tax[$field] ) ? $tax[$field] : '';
		}
		else
			return isset( $this->_get_option[$field] ) ? $this->_get_option[$field] : '';
	}


	/**
	 * This outputs various settings fields like text, checkboxes,
	 * select, etc. For use in modules, this method detects where
	 * it is being loaded and loads either Settings API fields or
	 * meta fields.
	 *
	 * @since 0.8
	 */

	public function field( $type, $field, $values = null, $args = null ) {
		$screen = get_current_screen();
		$_id    = isset( $args['_id'] ) ? $args['_id'] : $this->_id;
		$id     = esc_attr( "{$_id}_$field" );

		if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ( $screen->base == 'post' || $screen->base == 'post-new' ) ) {
			$name   = esc_attr( "{$_id}_$field" );
			$option = get_post_meta( get_the_ID(), $name, true );

			if ( $args && isset( $args['parent'] ) && isset( $args['count'] ) && isset( $args['value'] ) ) {
				$name               = esc_attr( "{$_id}_" . $args['parent'] . '[' . $args['count'] . "][$field]" );
				$args['atts']['id'] = $id = esc_attr( "{$_id}_" . $args['parent'] . '_' . $args['count'] . "_$field" );
				$option = $args['value'][$field] = isset( $args['value'][$field] ) ? esc_attr( $args['value'][$field] ) : $option;
			}
		}
		else {
			$taxonomy   = ! empty( $screen->taxonomy ) ? $screen->taxonomy : '';
			$tax_name   = ! empty( $taxonomy ) && $taxonomy == 'category' ? 'tax' : $taxonomy;
			$tax_id     = ! empty( $taxonomy ) ? "{$_id}_{$tax_name}" . $_GET['tag_ID'] : $_id;
			$get_option = ! empty( $taxonomy ) ? get_option( $tax_id ) : get_option( $_id );

			$name   = esc_attr( "{$tax_id}[$field]" );
			$option = isset( $get_option[$field] ) ? $get_option[$field] : '';

			if ( $args && isset( $args['parent'] ) && isset( $args['count'] ) ) {
				$name   = esc_attr( "{$tax_id}[" . $args['parent'] . '][' . $args['count'] . "][$field]" );
				$id     = esc_attr( "{$tax_id}_" . $args['parent'] . '_' . $args['count'] . "_$field" );
				$option = isset( $get_option[$args['parent']][$args['count']][$field] ) ? esc_attr( $get_option[$args['parent']][$args['count']][$field] ) : '';
			}
		}

		if ( $type == 'text' )
			$this->text( $field, $name, $id, $option, $args );

		if ( $type == 'textarea' )
			$this->textarea( $field, $name, $id, $option, $args );

		if ( $type == 'number' )
			$this->number( $field, $name, $id, $option, $args );

		if ( $type == 'code' )
			$this->code( $field, $name, $id, $option );

		if ( $type == 'url' )
			$this->url( $field, $name, $id, $option, $args );

		if ( $type == 'checkbox' )
			$this->checkbox( $field, $name, $id, $option, $values, $args );

		if ( $type == 'select' )
			$this->select( $field, $name, $id, $option, $values, $args );

		if ( $type == 'media' )
			$this->media( $field, $name, $id, $option, $args );

		if ( $type == 'repeat' && method_exists( $this, $args['callback'] ) )
			$this->repeat( $field, $name, $option, $values, $args );

		if ( $type == 'color' )
			$this->color( $field, $name, $id, $option, $args );
	}


	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 0.8
	 */

	public function text( $field, $name, $id, $option, $args ) {
		$value       = isset( $args['value'][$field] ) ? $args['value'][$field] : $option;
		$class_size  = isset( $args['atts']['size'] ) ? 'size="' . $args['atts']['size'] . '"' : 'class="regular-text"';
		$placeholder = isset( $args['atts']['placeholder'] ) ? ' placeholder="' . esc_attr( $args['atts']['placeholder'] ) . '"' : '';
		$readonly    = isset( $args['atts']['readonly_after_save'] ) && ! empty( $option ) ? ' readonly' : '';
	?>

		<input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo stripslashes( $option ); ?>"<?php echo $placeholder; ?> <?php echo $class_size; ?><?php echo $readonly; ?> />

	<?php }


	/**
	 * Outputs a simple textarea.
	 *
	 * @since 0.8
	 */

	public function textarea( $field, $name, $id, $option, $args ) {
		$rows = ! empty( $args['rows'] ) ? intval( $args['rows'] ) : 6;
	?>

		<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="large-text" rows="<?php echo $rows; ?>"><?php echo stripslashes( stripslashes( $option ) ); ?></textarea>

	<?php }


	/**
	 * Outputs a simple number input field with attributes.
	 *
	 * @since 0.8
	 */

	public function number( $field, $name, $id, $option, $args ) {
		$value       = isset( $args['value'][$field] ) ? $args['value'][$field] : $option;
		$class_size  = isset( $args['atts']['size'] ) ? 'size="' . $args['atts']['size'] . '"' : 'class="regular-text"';
		$placeholder = isset( $args['atts']['placeholder'] ) ? ' placeholder="' . esc_attr( $args['atts']['placeholder'] ) . '"' : '';
	?>

		<input type="number" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo $option; ?>"<?php echo $placeholder; ?> style="width: 60px;" />

	<?php }


	/**
	 * Outputs a simple textarea to paste code into.
	 *
	 * @since 0.8
	 */

	public function code( $field, $name, $id, $option ) { ?>

		<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="large-text" rows="15"><?php echo esc_textarea( $option ); ?></textarea>

	<?php }


	/**
	 * Outputs a simple text field for URL entry.
	 *
	 * @since 0.8
	 */

	public function url( $field, $name, $id, $option ) { ?>

		<input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo $option; ?>" class="regular-text" />

	<?php }


	/**
	 * Outputs a multi-checkbox field.
	 *
	 * @since 0.8
	 */

	public function checkbox( $field, $name, $id, $option, $values ) { ?>

		<?php foreach( $values as $val => $label ) :
			$nameval = esc_attr( "{$name}[$val]" );
			$idval   = esc_attr( "{$id}_$val" );
			$check   = isset( $option[$val] ) ? $option[$val] : '';
		?>
			<p id="<?php esc_attr_e( $nameval ); ?>" class="md-field">
				<input type="checkbox" name="<?php echo $nameval; ?>" id="<?php echo $idval; ?>" value="1"<?php echo checked( $check, 1, false ); ?> />

				<label for="<?php echo $idval; ?>"><?php echo $label; ?></label>
			</p>
		<?php endforeach; ?>

	<?php }


	/**
	 * Outputs a simple select field.
	 *
	 * @since 0.8
	 */

	public function select( $field, $name, $id, $option, $values ) { ?>

		<select name="<?php echo $name; ?>" id="<?php echo $id; ?>">
			<?php foreach ( $values as $val => $label ) : ?>
				<option value="<?php esc_attr_e( $val ); ?>"<?php echo selected( $option, $val, false ); ?>><?php esc_html_e( $label ); ?></option>
			<?php endforeach; ?>
		</select>

	<?php }


	/**
	 * Outputs repeatable fields.
	 *
	 * @since 0.8
	 */

	public function repeat( $field, $name, $option, $values, $args ) {
		$r      = 0;
		$repeat = array(
			'parent' => $field,
			'count'  => $r,
			'value'  => $option
		);

		$add_new = ! empty( $args['add_new'] ) ? $args['add_new'] : __( 'Add New', 'md' );
		$columns = isset( $args['columns'] ) ? ' columns-single columns-' . $args['columns'] : '';
		$col     = isset( $args['columns'] ) ? ' col' : '';
	?>

		<div class="md-repeat">

			<?php if ( isset( $args['title'] ) ) : ?>
				<h3 class="md-button-title"><?php echo esc_html( $args['title'] ); ?></h3>
			<?php endif; ?>

			<a href="#" class="md-repeat-add button md-spacer"><?php echo $add_new; ?></a>

			<div class="md-repeat-fields<?php echo $columns; ?>">

				<?php if ( ! is_array( $option ) ) : ?>

					<div class="md-repeat-field<?php echo $col; ?>">

						<?php call_user_func( array( $this, $args['callback'] ), $repeat ); ?>

						<a href="#" class="md-repeat-delete md-circle-badge">&times;</a>

					</div>

				<?php else : ?>

					<?php foreach ( $option as $field ) :
						$repeat['value'] = $field;
						$repeat['count'] = $r;
					?>

						<div class="md-repeat-field<?php echo $col; ?>">

							<?php call_user_func( array( $this, $args['callback'] ), $repeat ); ?>

							<?php if ( ! isset( $args['show_delete'] ) ) : ?>
								<a href="#" class="md-repeat-delete md-circle-badge">&times;</a>
							<?php endif; ?>

						</div>

					<?php $r++; endforeach; ?>

				<?php endif; ?>

			</div>

		</div>

		<?php wp_enqueue_script( 'md-repeat' ); ?>

	<?php }


	/**
	 * Outputs image upload field.
	 *
	 * @since 0.8
	 */

	public function media( $field, $name, $id, $option, $args ) {
		$value       = isset( $args['value'][$field] ) ? $args['value'][$field] : $option;
		$placeholder = isset( $args['atts']['placeholder'] ) ? ' placeholder="' . esc_attr( $args['atts']['placeholder'] ) . '"' : '';

		$src     = ! empty( $option ) ? $option : MD_PLUGIN_URL . 'admin/images/add-image.jpg';
		$display = ! empty( $option ) ? 'block' : 'none';
	?>

		<div class="md-media">

			<p><img class="md-media-preview-image" src="<?php echo $src; ?>" alt="<?php _e( 'Preview image', 'md' ); ?>" style="cursor: pointer; width: 30%;" /></p>

			<input type="url" class="md-media-url regular-text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo $option; ?>" placeholder="<?php _e( 'http://', 'md' ); ?>">

			<p class="md-media-buttons">
				<input type="button" class="md-media-remove button" value="<?php _e( 'Remove Image', 'md' ); ?>" style="display: <?php echo $display; ?>;" />
			</p>

		</div>

		<?php wp_enqueue_media(); ?>
		<?php wp_enqueue_script( 'md-media' ); ?>

	<?php }


	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 0.8
	 */

	public function color( $field, $name, $id, $option, $args ) {
		$value       = isset( $args['value'][$field] ) ? $args['value'][$field] : $option;
		$placeholder = isset( $args['atts']['placeholder'] ) ? ' placeholder="' . esc_attr( $args['atts']['placeholder'] ) . '"' : '';
		$option      = ! empty( $option ) ? $option : $args['default'];
	?>

		<input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" value="<?php echo $option; ?>"<?php echo $placeholder; ?> class="md-color-picker" />

		<?php wp_enqueue_style( 'wp-color-picker' ); ?>
		<?php wp_enqueue_script( 'md-color-picker' ); ?>

	<?php }


	/**
	 * Easily create a label for your fields.
	 *
	 * @since 0.8
	 */

	public function label( $field, $name, $_id = null ) {
		$id = isset( $_id ) ? $_id : $this->_id;
	?>

		<label for="<?php esc_attr_e( "{$id}_$field" ); ?>" class="md-label"><?php echo $name; ?></label>

	<?php }


	/**
	 * Easily create a description for your fields.
	 *
	 * @since 0.8
	 */

	public function desc( $desc ) { ?>

		<p class="description"><?php echo $desc; ?></p>

	<?php }

}