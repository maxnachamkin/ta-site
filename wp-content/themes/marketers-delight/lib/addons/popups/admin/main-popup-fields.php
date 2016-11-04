<?php

/**
 * This class holds fields used to set the Main Popup on admin page,
 * post, page, taxonomy, and any CPT it's fired on.
 *
 * @since 1.0
 */

class md_popups_fields extends md_api {

	/**
	 * Register fields for sanitization on save.
	 *
	 * @since 1.0
	 */

	public function register_fields() {
		$popups  = get_option( 'md_popups' );
		$options = array();

		if ( ! empty( $popups ) )
			foreach ( $popups['popups'] as $popup => $fields )
				if ( ! empty( $fields['id'] ) )
					$options[] = $fields['id'];

		return array(
			'main_popup' => array(
				'type'    => 'select',
				'options' => array_merge( array( '_none' ), $options )
			),
			'delay_time' => array(
				'type' => 'number'
			),
			'show' => array(
				'type'    => 'select',
				'options' => array(
					'exit_intent',
					'delay'
				)
			)
		);
	}


	/**
	 * Popup display + other options.
	 *
	 * @since 1.0
	 */

	public function fields() {
		$screen     = get_current_screen();
		$admin_page = $screen->base == "appearance_page_md_popups" ? true : false;

		$popups = get_option( 'md_popups' );
	?>

		<?php if ( ! empty( $popups ) ) :
			$main_popup = $this->module_field( 'main_popup' );

			foreach ( $popups['popups'] as $popup => $fields )
				$options[$fields['id']] = $fields['name'];
			$options = array_merge( array( '' => __( 'Select a popup&hellip;', 'md' ) ), $options );

			if ( ! $admin_page )
				$options['_none'] = __( 'Don\'t show popup', 'md' );
		?>

			<!-- Select Popup -->

			<table class="form-table">
				<tbody>
					<tr>
						<td>
							<?php $this->field( 'select', 'main_popup', $options ); ?>
						</td>
					</tr>
				</tbody>
			</table>

			<!-- Popup Options -->

			<table id="<?php echo $this->_id; ?>_options" class="form-table" style="display: <?php echo ! empty( $main_popup ) && $main_popup != '_none' ? 'table' : 'none'; ?>">
				<tbody>

					<!-- Show Method -->

					<tr>
						<th scope="row">
							<?php $this->label( 'show', __( 'Show Method', 'md' ) ); ?>
						</th>

						<td>
							<?php $this->field( 'select', 'show', array(
								''            => __( 'Select show method&hellip;', 'md' ),
								'exit_intent' => __( 'Exit Intent', 'md' ),
								'delay'       => __( 'Show on Delay', 'md' )
							) ); ?>
						</td>
					</tr>

					<?php if ( $admin_page ) : ?>

						<!-- Display Settings -->

						<tr class="md-check-list">
							<th scope="row">
								<?php $this->label( 'display', __( 'Display', 'md' ) ); ?>
							</th>

							<td>
								<?php $this->field( 'checkbox', 'display', array(
									'site'  => __( 'Show <b>sitewide</b>', 'md' )
								) ); ?>

								<div id="<?php echo $this->_id; ?>_display_conditional" style="display: <?php echo empty( $popups['display']['site'] ) ? 'block' : 'none'; ?>">
									<?php $this->field( 'checkbox', 'display', array(
										'blog'       => __( 'Show on <b>blog posts page</b>', 'md' ),
										'posts'      => __( 'Show on all <b>posts</b>', 'md' ),
										'pages'      => __( 'Show on all <b>pages</b>', 'md' ),
										'categories' => __( 'Show on all <b>categories</b>', 'md' )
									) ); ?>
								</div>
							</td>
						</tr>

					<?php endif; ?>

					<!-- Delay Time -->

					<tr>
						<th scope="row">
							<?php $this->label( 'delay_time', __( 'Delay Time', 'md-popups' ) ); ?>
						</th>

						<td>
							<p>
								<?php $this->field( 'number', 'delay_time', null, array(
									'atts' => array(
										'placeholder' => 5
									)
								) ); ?>
								<span class="description"><?php _e( 'seconds', 'md-popups' ); ?></span>
							</p>

							<?php $this->desc( __( 'How long before Exit Intent is detected, or popup is shown on delay.', 'md-popups' ) ); ?>
						</td>
					</tr>

					<!-- Cookie Expiration -->

					<tr>
						<th scope="row">
							<?php $this->label( 'cookie', __( 'Cookie Expiration', 'md' ) ); ?>
						</th>

						<td>
							<?php if ( $admin_page ) : ?>

								<p>
									<?php $this->field( 'number', 'cookie', null, array(
										'atts' => array(
											'placeholder' => 30
										)
									) ); ?>
									<span class="description"><?php _e( 'days', 'md' ); ?></span>
								</p>

								<?php $this->desc( __( 'The number of days to show the popup again to a visitor who has already seen it. Set 0 to never store cookie and show on every page load.<br />Popup not displaying? Clear your browser cookies for your site.', 'md' ) ); ?>

							<?php else : ?>

								<?php $this->desc( sprintf( __( 'Uses default <a href="%s" target="_blank">cookie expiration</a> days.', 'md' ), admin_url( 'themes.php?page=md_popups#md-popups-main' ) ) ); ?>

							<?php endif; ?>
						</td>
					</tr>

				</tbody>
			</table>

			<script>
				( function() {
					<?php if ( $admin_page ) : ?>
						document.getElementById( '<?php echo $this->_id; ?>_display_site' ).onchange = function() {
							document.getElementById( '<?php echo $this->_id; ?>_display_conditional' ).style.display = this.checked == '' ? 'block' : 'none';
						}
					<?php endif; ?>
					document.getElementById( '<?php echo $this->_id; ?>_main_popup' ).onchange = function() {
						document.getElementById( '<?php echo $this->_id; ?>_options' ).style.display = this.value != '' && this.value != '_none' ? 'table' : 'none';
					}
				})();
			</script>

		<?php else : ?>

			<?php md_popup_connect_notice(); ?>

		<?php endif; ?>

	<?php }

}