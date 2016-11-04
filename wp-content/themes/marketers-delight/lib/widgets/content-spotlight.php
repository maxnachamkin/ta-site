<?php

// Prevent direct access.

if ( ! defined( 'ABSPATH' ) ) exit;

class md_content_spotlight extends WP_Widget {

	public $_allowed_html = array(
		'span' => array(
			'class'  => array(),
			'id'     => array(),
		),
		'br' => array(),
		'b'  => array(),
		'i'  => array()
	);


	public function __construct() {
		parent::__construct( 'md_content_spotlight', __( 'Content Spotlight', 'md' ), array(
			'description' => __( 'Custom image boxes', 'md' )
		) );

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}


	public function scripts( $hook ){
		if ( $hook == 'widgets.php' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'md-media' );
		}
	}


	public function widget( $args, $val ) {
		$widget_title = ! empty( $val['widget_title'] ) ? $val['widget_title'] : '';
		$title        = $val['title'];
		$intro        = $val['intro'];
		$link         = $val['link'];
		$link_new     = ! empty( $val['link_new'] ) ? $val['link_new'] : '';
		$image        = $val['image'];

		$html   = ! empty( $link ) ? 'a href="' . esc_url( $link ) . '"' . ( ! empty( $link_new ) ? ' target="_blank"' : '' ) : 'div';
		$html_c = ! empty( $link ) ? 'a' : 'div';

		$src           = ! empty( $image ) ? " style=\"background-image: url('$image');\"" : '';
		$image_classes = ! empty( $image ) ? 'image-overlay' : '';

		if ( ! $intro && ! $title && ! $link && ! $image )
			return false;
	?>

		<?php echo $args['before_widget']; ?>

			<!-- Outer Title -->

			<?php if ( $widget_title ) : ?>
				<?php echo $args['before_title']; ?><?php echo $widget_title; ?><?php echo $args['after_title']; ?>
			<?php endif; ?>

			<<?php echo $html; ?> class="<?php echo $image_classes; ?> box-dark block-double text-center caps text-white no-border"<?php echo $src; ?>>

				<?php if ( $intro ) : ?>
					<!-- Intro -->
					<small class="display-block mb-half"><?php echo esc_html( $intro ); ?></small>
				<?php endif; ?>

				<?php if ( $title ) : ?>
					<!-- Title -->
					<?php echo $args['before_title']; ?><?php echo $title; ?><?php echo $args['after_title']; ?>
				<?php endif; ?>

			</<?php echo $html_c; ?>>

		<?php echo $args['after_widget']; ?>

	<?php }


	public function update( $new, $val ) {
		$val['title']        = wp_kses( $new['title'], $this->_allowed_html );
		$val['widget_title'] = wp_kses( $new['widget_title'], $this->_allowed_html );
		$val['intro']        = wp_kses( $new['intro'], $this->_allowed_html );
		$val['link']         = esc_url_raw( $new['link'] );
		$val['image']        = ! empty( $new['image'] ) ? strip_tags( $new['image'] ) : '';
		$val['link_new']     = ! empty( $new['link_new'] ) ? 1 : 0;

		return $val;
	}


	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'widget_title' => '',
			'title'        => '',
			'intro'        => '',
			'link'         => '',
			'image'        => '',
			'link_new'     => ''
		) );

		$src     = ! empty( $val['image'] ) ? $val['image'] : MD_PLUGIN_URL . 'admin/images/add-image.jpg';
		$display = ! empty( $val['image'] ) ? 'block' : 'none';
	?>

		<!-- Widget Title -->

		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Outer Widget Title', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php esc_attr_e( $val['widget_title'] ); ?>" class="widefat" />
		</p>

		<!-- Intro -->

		<p>
			<label for="<?php echo $this->get_field_id( 'intro' ); ?>"><?php _e( 'Box Intro', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'intro' ); ?>" name="<?php echo $this->get_field_name( 'intro' ); ?>" value="<?php esc_attr_e( $val['intro'] ); ?>" class="widefat" />
		</p>

		<!-- Title -->

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Box Title', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php esc_attr_e( $val['title'] ); ?>" class="widefat" />
		</p>

		<!-- Link -->

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link', 'md' ); ?>:</label>

			<input type="url" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php esc_attr_e( $val['link'] ); ?>" class="widefat" placeholder="<?php _e( 'http://', 'md' ); ?>" />
		</p>

		<!-- Link new window -->

		<p>
			<input id="<?php echo $this->get_field_id( 'link_new' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_new' ); ?>" value="1" <?php checked( $val['link_new'] ); ?> />

			<label for="<?php echo $this->get_field_id( 'link_new' ); ?>"><?php _e( 'Open link in new window', 'md' ); ?></label>
		</p>

		<!-- Image -->

		<div class="md-media">

			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Background Image', 'md' ); ?>:</label>

			<img class="md-media-preview-image" src="<?php echo $src; ?>" alt="<?php _e( 'Preview image', 'md' ); ?>" style="cursor: pointer; max-width: 100%;" />

			<input type="text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo $val['image']; ?>" placeholder="<?php _e( 'http://', 'md' ); ?>" class="md-media-url widefat" />

			<p class="md-media-buttons">
				<input type="button" class="md-media-remove button" value="<?php _e( 'Remove Image', 'md' ); ?>" style="display: <?php echo $display; ?>;" />
			</p>

		</div>

	<?php }

}