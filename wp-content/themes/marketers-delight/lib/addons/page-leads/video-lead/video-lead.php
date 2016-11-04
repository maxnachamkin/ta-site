<?php
/**
 * Plugin Name: Video Page Lead
 * Plugin URI: https://marketersdelight.net/page-leads/video-lead/
 * Description: Add a video from YouTube, Vimeo, or your custom code with HTML content to the Page Leads system. Integrates with MD Popups to place video in a custom popup.
 * Author: Alex Mangini, Kolakube
 */

// Prevent direct access.

if ( ! defined( 'ABSPATH' ) ) exit;

// Load required files

require_once( 'functions/template-functions.php' );

// Run Video Lead

if ( ! class_exists( 'video_lead' ) ) :

class video_lead extends md_api {

	/**
	 * Build the Video Lead.
	 *
	 * @since 1.0
	 */

	public function construct() {
		$this->suite = 'page_leads';

		$this->admin_tab = array(
			'name' => __( 'Video Lead', 'md' )
		);

		$this->meta_box = $this->taxonomy = array(
			'name'   => __( 'Video Page Lead', 'md' ),
			'module' => true
		);

		$this->button = new md_button( $this->_id );

		if ( md_has( 'popups' ) )
			add_filter( 'md_button_actions', array( $this, 'video_button_action' ) );
	}


	/**
	 * Load Video Lead to frontend when needed.
	 *
	 * @since 1.0
	 */

	public function template_redirect() {
		if ( has_video_lead() ) {
			$position = video_lead_field( 'position' );
			$order    = video_lead_field( 'order' );
			$priority = ! empty ( $order ) ? $order : 10;

			add_action( $position, array( $this, 'load_template' ), $priority );

			if ( md_has( 'md_popups' ) ) {
				add_action( 'md_api_button_popup_js', array( $this, 'play_button_js' ) );
				add_action( 'md_popups', array( $this, 'popup_template' ) );
			}
		}
	}


	/**
	 * Load popup template if play button is set.
	 *
	 * @since 1.0
	 */

	public function popup_template() {
		$service = video_lead_field( 'service' );
	?>

		<div id="md_popup_video_lead" class="md-popup video-lead-popup">

			<?php video_lead_video( $service ); ?>

			<div class="md-popup-close md-popup-close-corner">&times;</div>

		</div>

	<?php }


	/**
	 * Loads the template where there's a Video Lead. Override this template in
	 * a child theme by creating /templates/video-lead.php and copying the
	 * original source from the plugin's template file into the new file.
	 *
	 * @since 1.1
	 */

	public function load_template() {
		$path = 'templates/video-lead.php';

		if ( $template = locate_template( $path ) )
			load_template( $template );
		else
			load_template( dirname( __FILE__ ) . "/$path" );
	}


	/**
	 * Register custom admin settings to be saved and sanitized.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		$button = $this->button->register_fields();

		return array_merge( array(
			'position' => array(
				'type'    => 'select',
				'options' => $this->page_leads['hooks']
			),
			'order' => array(
				'type' => 'number'
			),
			'display' => array(
				'type'    => 'checkbox',
				'options' => array(
					'site',
					'blog',
					'posts',
					'pages'
				)
			),
			'layout' => array(
				'type' => 'select',
				'options' => array(
					'video-left',
					'video-right',
					'center'
				)
			),
			'bg_color' => array(
				'type' => 'color'
			),
			'bg_image' => array(
				'type' => 'image'
			),
			'text_color_scheme' => array(
				'type' => 'select',
				'options' => array(
					'dark',
					'white'
				)
			),
			'subtitle' => array(
				'type' => 'text'
			),
			'headline' => array(
				'type' => 'text',
			),
			'desc' => array(
				'type' => 'textarea',
			),
			'service' => array(
				'type' => 'select',
				'options' => array(
					'youtube',
					'vimeo',
					'embed'
				)
			),
			'youtube' => array(
				'type' => 'url'
			),
			'vimeo' => array(
				'type' => 'url'
			),
			'embed' => array(
				'type' => 'code'
			)
		), $button );
	}


	/**
	 * Add custom button actions to select field.
	 *
	 * @since 1.0
	 */

	public function video_button_action() {
		return array(
			'play' => __( 'Play button', 'md' )
		);
	}


	/**
	 * Creates admin settings.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$screen  = get_current_screen();
		$design  = new md_design_options( $this->_id, '#DDDDDD' );
		$service = $this->module_field( 'service' );
		$videos  = array(
			'youtube' => array(
				'label'       => __( 'YouTube URL', 'md' ),
				'placeholder' => 'https://www.youtube.com/embed/xxx'
			),
			'vimeo' => array(
				'label'       => __( 'Vimeo URL', 'md' ),
				'placeholder' => 'https://player.vimeo.com/video/xxx'
			)
		);
	?>

		<h3 class="md-meta-h3"><?php _e( 'Display Options', 'md' ); ?></h3>

		<table class="form-table">
			<tbody>

				<?php if ( $screen->base == 'appearance_page_page_leads' ) : ?>

					<!-- Display Settings -->

					<tr>

						<th scope="row">
							<?php $this->label( 'display', __( 'Display', 'md' ) ); ?>
						</th>

						<td>
							<?php $this->field( 'checkbox', 'display', array(
								'site'  => __( 'Show sitewide', 'md' )
							) ); ?>

							<div id="<?php echo $this->_id; ?>_display_conditional" style="display: <?php echo empty( $this->_get_option['display']['site'] ) ? 'block' : 'none'; ?>">
								<?php $this->field( 'checkbox', 'display', array(
									'blog'  => __( 'Show on blog posts page', 'md' ),
									'posts' => __( 'Show on all posts', 'md' ),
									'pages' => __( 'Show on all pages', 'md' )
								) ); ?>
								<?php $this->desc( __( 'You can add custom Page Leads by editing any post, page, or category.', 'md' ) ); ?>
							</div>

						</td>

					</tr>

				<?php endif; ?>

				<!-- Position + Order -->

				<tr>
					<th scope="row">
						<?php $this->label( 'position', __( 'Position', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'select', 'position', $this->page_leads['positions' ] ); ?>

						<?php $this->field( 'number', 'order', null, array(
							'atts' => array(
								'placeholder' => '10'
							)
						) ); ?>

						<?php $this->desc( __( 'Reorder any Page Leads you add to the same position above or below each other by setting higher/lower numbers.', 'md' ) ); ?>
					</td>
				</tr>

				<!-- Layout -->

				<tr>
					<th scope="row">
						<?php $this->label( 'layout', __( 'Layout', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'select', 'layout', array(
							'video-left'  => __( 'Content / Video (default)', 'md' ),
							'video-right' => __( 'Video / Content', 'md' ),
							'center'      => __( '1 column, center', 'md' )
						) ); ?>
					</td>
				</tr>

			</tbody>
		</table>

		<!-- Design -->

		<?php $design->fields(); ?>

		<h3 class="md-meta-h3"><?php _e( 'Content', 'md' ); ?></h3>

		<table class="form-table">
			<tbody>

				<!-- Subtitle -->

				<tr>

					<th scope="row">
						<?php $this->label( 'subtitle', __( 'Subtitle', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', 'subtitle' ); ?>
					</td>

				</tr>

				<!-- Headline -->

				<tr>

					<th scope="row">
						<?php $this->label( 'headline', __( 'Headline', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', 'headline' ); ?>
					</td>

				</tr>

				<!-- Description -->

				<tr>

					<th scope="row">
						<?php $this->label( 'desc', __( 'Description', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'textarea', 'desc' ); ?>
					</td>

				</tr>

			</tbody>
		</table>

		<!-- Button -->

		<?php $this->button->fields(); ?>

		<h3 class="md-meta-h3"><?php _e( 'Video', 'md' ); ?></h3>

		<!-- Video -->

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<?php $this->label( 'service', __( 'Video Service', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'select', 'service', array(
							''        => __( 'Select video service&hellip;', 'md' ),
							'youtube' => __( 'YouTube', 'md' ),
							'vimeo'   => __( 'Vimeo', 'md' ),
							'embed'   => __( 'Embed Code', 'md' )
						) ); ?>
					</td>
				</tr>

				<!-- Video URLs -->

				<?php foreach( $videos as $id => $fields ) : ?>

					<tr id="<?php echo "{$this->_id}_{$id}_row"; ?>" style="display: <?php echo $service == $id ? 'table-row' : 'none'; ?>">
						<th scope="row">
							<?php $this->label( $id, $fields['label'] ); ?>
						</th>

						<td>
							<?php $this->field( 'text', $id, null, array(
								'atts' => array(
									'placeholder' => $fields['placeholder']
								)
							) ); ?>
						</td>

					</tr>

				<?php endforeach; ?>

				<!-- Embed Code -->

				<tr id="<?php echo "{$this->_id}_embed_row"; ?>" style="display: <?php echo $service == 'embed' ? 'table-row' : 'none'; ?>">

					<th scope="row">
						<?php $this->label( 'embed', __( 'Video Embed Code', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'code', 'embed' ); ?>
						<?php $this->desc( __( 'Paste your HTML video embed code here.', 'md' ) ); ?>
					</td>

				</tr>

			</tbody>
		</table>

	<?php }


	/**
	 * Load Video Lead scripts to admin page footer.
	 *
	 * @since 1.0
	 */

	public function admin_print_footer_scripts() {
		$screen = get_current_screen();
	?>

		<script>
			( function() {
				<?php if ( $screen->base == 'appearance_page_page_leads' ) : ?>
					// display
					document.getElementById( '<?php echo $this->_id; ?>_display_site' ).onchange = function() {
						document.getElementById( '<?php echo $this->_id; ?>_display_conditional' ).style.display = this.checked == '' ? 'block' : 'none';
					}
				<?php endif; ?>
				// videos
				document.getElementById( '<?php echo $this->_id; ?>_service' ).onchange = function() {
					document.getElementById( '<?php echo $this->_id; ?>_youtube_row' ).style.display   = this.value == 'youtube' ? 'table-row' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_vimeo_row' ).style.display     = this.value == 'vimeo' ? 'table-row' : 'none';
					document.getElementById( '<?php echo $this->_id; ?>_embed_row' ).style.display     = this.value == 'embed' ? 'table-row' : 'none';
				}
			})();
		</script>

	<?php }

}

endif;

$video_lead = new video_lead;