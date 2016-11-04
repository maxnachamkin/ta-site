<?php

// Prevent direct access.

if ( ! defined( 'ABSPATH' ) ) exit;

class md_quote_widget extends WP_Widget {

	public function __construct() {
		parent::__construct( 'md_quote_widget', __( 'Testimonial', 'md' ), array(
			'description' => __( 'Add a testimonial/quote to any designated widget area!', 'md' )
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
		$title  = $val['title'];
		$quote  = $val['quote'];
		$author = $val['author'];

		$image_url = $val['image'];
		$image_id  = md_url_image_id( $image_url );
		$image     = wp_get_attachment_image_src( $image_id, 'md-thumbnail' );
	?>

		<?php echo $args['before_widget']; ?>

			<!-- Title -->

			<?php if ( ! empty( $title ) ) : ?>
				<?php echo $args['before_title']; ?><?php echo $title; ?><?php echo $args['after_title']; ?>
			<?php endif; ?>

			<!-- Quote -->

			<div class="quote-box">

				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image[0] ); ?>" class="quote-box-image alignright circle" alt="<?php echo $title; ?>" width="<?php esc_attr_e( $image[1] ); ?>" height="<?php esc_attr_e( $image[2] ); ?>" />
				<?php endif; ?>

				<?php echo wpautop( $quote ); ?>

			</div>

			<?php if ( ! empty( $author ) ) : ?>
				<p class="quote-box-author"><?php esc_html_e( $author ); ?></p>
			<?php endif; ?>

		<?php echo $args['after_widget']; ?>

	<?php }


	public function update( $new, $val ) {
		$val['title']  = sanitize_text_field( $new['title'] );
		$val['quote']  = esc_textarea( $new['quote'] );
		$val['author'] = sanitize_text_field( $new['author'] );
		$val['image']  = ! empty( $new['image'] ) ? esc_url( $new['image'] ) : '';

		return $val;
	}


	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title'  => '',
			'quote'  => '',
			'author' => '',
			'image'  => ''
		) );

		$src     = ! empty( $val['image'] ) ? $val['image'] : MD_PLUGIN_URL . 'admin/images/add-image.jpg';
		$display = ! empty( $val['image'] ) ? 'block' : 'none';
	?>

		<!-- Title -->

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php esc_attr_e( $val['title'] ); ?>" class="widefat" />
		</p>

		<!-- Quote -->

		<p>
			<label for="<?php echo $this->get_field_id( 'quote' ); ?>"><?php _e( 'Quoted Text', 'md' ); ?>:</label>

			<textarea id="<?php echo $this->get_field_id( 'quote' ); ?>" name="<?php echo $this->get_field_name( 'quote' ); ?>" rows="8" class="large-text"><?php esc_attr_e( $val['quote'] ); ?></textarea>
		</p>

		<!-- Author -->

		<p>
			<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Quote Author', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" value="<?php esc_attr_e( $val['author'] ); ?>" class="widefat" />
		</p>

		<!-- Image -->

		<div class="md-media">

			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Author Image', 'md' ); ?>:</label>

			<p class="description" style="padding-left:0"><?php _e( 'Image will be cropped to 80x80 pixels.', 'md' ); ?></p>

			<img class="md-media-preview-image" src="<?php echo $src; ?>" alt="<?php _e( 'Preview image', 'md' ); ?>" style="cursor: pointer; max-width: 100%;" />

			<input type="text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo $val['image']; ?>" placeholder="<?php _e( 'http://', 'md' ); ?>" class="md-media-url widefat" />

			<p class="md-media-buttons">
				<input type="button" class="md-media-remove button" value="<?php _e( 'Remove Image', 'md' ); ?>" style="display: <?php echo $display; ?>;" />
			</p>

		</div>

	<?php }

}