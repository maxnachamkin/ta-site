<?php

class MD_Customize_Control_Textarea extends WP_Customize_Control {

    public $type = 'md_textarea';

    public function render_content() { ?>
		<label>

			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

			<textarea rows="15" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>

			<?php if ( ! empty( $this->description ) ) : ?>
				<div class="description"><?php echo $this->description; ?></div>
			<?php endif; ?>

		</label>

		<br /><hr />

	<?php }

}