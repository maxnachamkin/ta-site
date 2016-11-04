<?php

// Prevent direct access.

if ( ! defined( 'ABSPATH' ) ) exit;

class md_text_image extends WP_Widget {

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
		parent::__construct( 'md_text_image', __( 'Text + Image', 'md' ), array(
			'description' => __( 'Create a Text widget with a small right-aligned image as well as a button.', 'md' )
		) );

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}


	public function scripts( $hook ){
		if ( $hook == 'widgets.php' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'md-media' );
		}
	}


	public function image( $url ) {
		$id = md_url_image_id( $url );

		return wp_get_attachment_image_src( $id, 'md-thumbnail' );
	}


	public function widget( $args, $val ) {
		$title         = $val['title'];
		$desc          = apply_filters( 'widget_text', $val['desc'] );
		$image         = $this->image( $val['image'] );
		$button_text   = $val['button_text'];
		$button_link   = $val['button_link'];
		$button_html   = ! empty( $button_link ) ? 'a href="' . esc_url( $button_link ) . '"' : 'span';
		$button_html_c = ! empty( $button_link ) ? 'a' : 'span';
	?>

		<?php echo $args['before_widget']; ?>

			<?php if ( $title ) : ?>
				<?php echo $args['before_title']; ?><?php echo $title; ?><?php echo $args['after_title']; ?>
			<?php endif; ?>

			<div class="box-style block-mid">

				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php esc_attr_e( $title ); ?>" class="about-avatar avatar alignright" width="<?php esc_attr_e( $image[1] ); ?>" height="<?php esc_attr_e( $image[2] ); ?>" />
				<?php endif; ?>

				<?php if ( $desc ) : ?>
					<?php echo wpautop( $desc ); ?>
				<?php endif; ?>

				<?php if ( $button_text || $button_link ) : ?>
					<p><<?php echo $button_html; ?> class="button width-full text-center"><?php esc_html_e( $button_text ); ?></<?php echo $button_html_c; ?>></p>
				<?php endif; ?>

			</div>

		<?php echo $args['after_widget']; ?>

	<?php }


	public function update( $new, $val ) {
		$val['title']       = wp_kses( $new['title'], $this->_allowed_html );
		$val['desc']        = $new['desc'];
		$val['image']       = ! empty( $new['image'] ) ? strip_tags( $new['image'] ) : '';
		$val['button_text'] = strip_tags( $new['button_text'] );
		$val['button_link'] = esc_url( $new['button_link'] );

		return $val;
	}


	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title'       => '',
			'desc'        => '',
			'image'       => '',
			'button_text' => '',
			'button_link' => ''
		) );

		$src     = ! empty( $val['image'] ) ? $val['image'] : MD_PLUGIN_URL . 'admin/images/add-image.jpg';
		$display = ! empty( $val['image'] ) ? 'block' : 'none';
	?>

		<!-- Title -->

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php esc_attr_e( $val['title'] ); ?>" class="widefat" />
		</p>

		<!-- Description -->

		<p>
			<label for="<?php echo $this->get_field_id( 'dec' ); ?>"><?php _e( 'Description', 'md' ); ?>:</label>

			<textarea id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" rows="8" class="large-text"><?php esc_attr_e( $val['desc'] ); ?></textarea>
		</p>

		<!-- Link -->

		<p>
			<label for="<?php echo $this->get_field_id( 'button_link' ); ?>"><?php _e( 'Link', 'md' ); ?>:</label>

			<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'button_link' ); ?>" id="<?php echo $this->get_field_id( 'button_link' ); ?>" value="<?php echo $val['button_link']; ?>">
		</p>

		<!-- Button Text -->

		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button Text', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php esc_attr_e( $val['button_text'] ); ?>" class="widefat" />
		</p>

		<!-- Image -->

		<div class="md-media">

			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image (100x100)', 'md' ); ?>:</label>

			<?php $image = $this->image( $src ); ?>

			<p><img class="md-media-preview-image" src="<?php echo esc_url( $image[0] ); ?>" alt="<?php _e( 'Preview image', 'md' ); ?>" style="cursor: pointer; background-color: #fff; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); padding: 3px;" width="<?php esc_attr_e( $image[1] ); ?>" height="<?php esc_attr_e( $image[2] ); ?>" /></p>

			<input type="text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo $val['image']; ?>" placeholder="<?php _e( 'http://', 'md' ); ?>" class="md-media-url widefat" />

			<p class="md-media-buttons">
				<input type="button" class="md-media-remove button" value="<?php _e( 'Remove Image', 'md' ); ?>" style="display: <?php echo $display; ?>;" />
			</p>

		</div>

	<?php }
}