<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( MD_PLUGIN_DIR . 'api/fields/button/button-functions.php' );


/**
 * Use this class to build button fields into admin options.
 * Custom links, EDD buy buttons, MD Popup trigger.
 *
 * @since 4.3.5
 */

class md_button extends md_api {

	/**
	 * Save fields for button.
	 *
	 * @sine 4.3.5
	 */

	public function register_fields( $prefix = null, $fields = null ) {
		$has_edd    = class_exists( 'Easy_Digital_Downloads' ) ? true : false;
		$has_woo    = class_exists( 'WooCommerce' ) ? true : false;
		$has_popups = class_exists( 'md_popups' ) ? true : false;

		$posts = array(
			'edd_button' => array(
				'has' => $has_edd,
				'val' => $this->get_posts( 'Easy_Digital_Downloads', 'download' )
			),
			'woo_button' => array(
				'has' => $has_woo,
				'val' => $this->get_posts( 'WooCommerce', 'product' )
			)
		);

		$save = $products = $popups = $actions = array();

		$button_text = isset( $fields['button_text'] ) ? $fields['button_text'] : 'button_text';
		$button_link = isset( $fields['button_link'] ) ? $fields['button_link'] : 'button_link';

		$save = array(
			"{$prefix}$button_text" => array(
				'type' => 'text'
			),
			"{$prefix}$button_link" => array(
				'type' => 'url'
			)
		);

		// Action
		if ( $has_edd || $has_woo || $has_popups )
			$save["{$prefix}button_action"] = array(
				'type' => 'select',
				'options' => array(
					'link',
					'edd_button',
					'woo_button',
					'popup'
			) );

		// Posts
		foreach ( $posts as $id => $post ) {
			if ( $post['has'] && ! empty( $post['val'] ) ) {
				foreach( $post['val'] as $val_id => $name )
					if ( ! empty( $id ) )
						$products[] = $val_id;

				$save["{$prefix}{$id}"] = array(
					'type'    => 'select',
					'options' => $products
				);
			}
		}

		// Popups
		$md_popups = get_option( 'md_popups' );
		if ( $has_popups && ! empty( $md_popups ) ) {
			// custom actions
			$button_actions = apply_filters( 'md_button_actions', array() );
			foreach ( $button_actions as $id => $label )
				$actions[] = $id;

			$save["{$prefix}button_action"]['options'] = array_merge( $save["{$prefix}button_action"]['options'], $actions );

			// popups
			foreach ( $md_popups['popups'] as $popup => $fields )
				if ( ! empty( $fields['id'] ) )
					$popups[] = $fields['id'];

			$save["{$prefix}button_popup"] = array(
				'type'    => 'select',
				'options' => $popups
			);
		}

		return $save;
	}


	/**
	 * Build Button HTML fields.
	 *
	 * @since 4.3.5
	 */

	public function fields( $prefix = null, $fields = null ) {
		$has_edd    = class_exists( 'Easy_Digital_Downloads' ) ? true : false;
		$has_woo    = class_exists( 'WooCommerce' ) ? true : false;
		$has_popups = class_exists( 'md_popups' ) ? true : false;

		$button_text = isset( $fields['button_text'] ) ? $fields['button_text'] : 'button_text';
		$button_link = isset( $fields['button_link'] ) ? $fields['button_link'] : 'button_link';
	?>

		<table class="form-table">
			<tbody>

				<?php if ( $has_edd || $has_woo || $has_popups ) :
					$button_action = $this->module_field( "{$prefix}button_action" );
					$actions       = array_merge( array(
						''         => __( 'Select button action&hellip;', 'md' ),
						'link'     => __( 'Custom link', 'md' ),
					), apply_filters( 'md_button_actions', array() ) );
					if ( $has_edd )
						$actions['edd_button'] = __( 'EDD Buy Now', 'md' );
					if ( $has_woo )
						$actions['woo_button'] = __( 'WooCommerce Buy Now', 'md' );
					if ( $has_popups )
						$actions['popup'] = __( 'MD Popup', 'md' );
				?>

					<!-- Button Action -->

					<tr>

						<th scope="row">
							<?php $this->label( "{$prefix}button_action", __( 'Button Action', 'md' ) ); ?>
						</th>

						<td>
							<?php $this->field( 'select', "{$prefix}button_action", $actions ); ?>
						</td>

					</tr>

				<?php endif;
					$style = $has_edd || $has_popups ? 'style="display: ' . ( $button_action == 'link' ? 'table-row' : 'none' ) . ';"' : '';
				?>

				<!-- Button Link -->

				<tr id="<?php echo "{$this->_id}_{$prefix}button_link_row"; ?>"<?php echo $style; ?>>

					<th scope="row">
						<?php $this->label( "{$prefix}$button_link", __( 'Button Link', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'url', "{$prefix}$button_link" ); ?>
					</td>

				</tr>

				<?php if ( $has_edd ) :
					$edd_posts = $this->get_posts( 'Easy_Digital_Downloads', 'download', true );
				?>

					<!-- EDD -->

					<tr id="<?php echo "{$this->_id}_{$prefix}edd_button_row"; ?>" style="display: <?php echo $button_action == 'edd_button' ? 'table-row' : 'none'; ?>">

						<th scope="row"><?php $this->label( "{$prefix}edd_button", __( 'EDD Buy Now Button', 'md' ) ); ?></th>

						<td>
							<?php $this->field( 'select', "{$prefix}edd_button", $edd_posts ); ?>
						</td>

					</tr>

				<?php endif; ?>

				<?php if ( $has_woo ) :
					$woo_posts = $this->get_posts( 'WooCommerce', 'product', true );
				?>

					<!-- WooCommerce -->

					<tr id="<?php echo "{$this->_id}_{$prefix}woo_button_row"; ?>" style="display: <?php echo $button_action == 'woo_button' ? 'table-row' : 'none'; ?>">

						<th scope="row"><?php $this->label( "{$prefix}woo_button", __( 'WooCommerce Buy Now Button', 'md' ) ); ?></th>

						<td>
							<?php $this->field( 'select', "{$prefix}woo_button", $woo_posts ); ?>
						</td>

					</tr>

				<?php endif; ?>

				<?php if ( $has_popups ) :
					$md_popups = get_option( 'md_popups' );
					foreach ( $md_popups['popups'] as $popup => $fields )
						$popups[$fields['id']] = $fields['name'];
					$popups = array_merge( array( '' => __( 'Select a popup&hellip;', 'md-popups' ) ), $popups );
				?>

					<!-- MD Popups -->

					<tr id="<?php echo "{$this->_id}_{$prefix}button_popup_row"; ?>" style="display: <?php echo $button_action == 'popup' ? 'table-row' : 'none'; ?>">

						<th scope="row"><?php $this->label( "{$prefix}button_popup", __( 'MD Popup', 'md' ) ); ?></th>

						<td>
							<?php $this->field( 'select', "{$prefix}button_popup", $popups ); ?>
						</td>

					</tr>

				<?php endif; ?>

				<!-- Button Text -->

				<tr id="<?php echo "{$this->_id}_{$prefix}button_text_row"; ?>">
					<th scope="row">
						<?php $this->label( "{$prefix}$button_text", __( 'Button Text', 'md' ) ); ?>
					</th>

					<td>
						<?php $this->field( 'text', "{$prefix}$button_text" ); ?>
					</td>
				</tr>

			</tbody>
		</table>

		<?php if ( $has_edd || $has_popups ) : ?>

			<script>
				( function() {
					document.getElementById( '<?php echo "{$this->_id}_{$prefix}"; ?>button_action' ).onchange = function() {
						document.getElementById( '<?php echo "{$this->_id}_{$prefix}"; ?>button_text_row' ).style.display = this.value != '' ? 'table-row' : 'none';
						document.getElementById( '<?php echo "{$this->_id}_{$prefix}"; ?>button_link_row' ).style.display = this.value == 'link' ? 'table-row' : 'none';
						<?php if ( $has_edd ) : ?>
							document.getElementById( '<?php echo "{$this->_id}_{$prefix}"; ?>edd_button_row' ).style.display = this.value == 'edd_button' ? 'table-row' : 'none';
						<?php endif; ?>
						<?php if ( $has_woo ) : ?>
							document.getElementById( '<?php echo "{$this->_id}_{$prefix}"; ?>woo_button_row' ).style.display = this.value == 'woo_button' ? 'table-row' : 'none';
						<?php endif; ?>
						<?php if ( $has_popups ) : ?>
							document.getElementById( '<?php echo "{$this->_id}_{$prefix}"; ?>button_popup_row' ).style.display = this.value == 'popup' ? 'table-row' : 'none';
						<?php endif; ?>
					}
				})();
			</script>

		<?php endif; ?>

	<?php }


	/**
	 * Returns array of EDD post titles and IDs.
	 *
	 * @since 1.0
	 */

	public function get_posts( $class, $post_type, $name = null ) {
		static $cache;

		if ( ! class_exists( $class ) )
			return array();

		$cache_key = serialize( func_get_args() );

		if ( isset( $cache[$cache_key] ) )
			return $cache[$cache_key];

		$data = array();

		$products = new WP_Query( array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1
		) );

		if ( isset( $name ) )
			$data[''] = sprintf( __( 'Select a %s&hellip;', 'md' ), $post_type );

		foreach ( $products->posts as $id => $fields )
			$data[$fields->ID] = $fields->post_title;

		$cache[$cache_key] = $data;

		return $data;
	}

}