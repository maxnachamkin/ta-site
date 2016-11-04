<?php

class MD_Alpha_Color_Control extends WP_Customize_Control {

	public $type = 'alpha-color';

	public $palette;

	public $show_opacity;

	public function enqueue() {
		wp_enqueue_script( 'md-alpha-color-picker', MD_PLUGIN_URL . 'customize/js/alpha-color-picker.js', array( 'jquery', 'wp-color-picker' ), '1.0.0', true );
		wp_enqueue_style( 'md-alpha-color-picker', MD_PLUGIN_URL . 'customize/css/alpha-color-picker.css', array( 'wp-color-picker' ), '1.0.0' );
	}


	public function render_content() {
		if ( is_array( $this->palette ) )
			$palette = implode( '|', $this->palette );
		else
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';

		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
	?>

		<label>
			<?php if ( isset( $this->label ) && '' !== $this->label ) : ?>
				<span class="customize-control-title"><?php echo sanitize_text_field( $this->label ); ?></span>
			<?php endif; ?>

			<?php if ( isset( $this->description ) && '' !== $this->description ) : ?>
				<span class="description customize-control-description"><?php echo sanitize_text_field( $this->description ); ?></span>
			<?php endif; ?>

			<input class="alpha-color-control" type="text" data-show-opacity="<?php echo $show_opacity; ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
		</label>

	<?php }

}