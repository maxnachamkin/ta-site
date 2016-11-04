<?php

/**
 * Runs various admin actions to build popups and interact with
 * the WordPress Customizer.
 *
 * @since 1.0
 */

class md_popups_utilities {

	/**
	 * Load available utilities.
	 *
	 * @since 1.0
	 */

	public function __construct( $id ) {
		$this->_id = $id;

		$this->save();
		$this->edit();
		$this->delete();
		$this->customizer_return();
	}


	/**
	 * Update main options array with safe ID on save.
	 *
	 * @since 1.0
	 */

	public function save() {
		if (
			( isset( $_GET['page'] ) && $_GET['page'] == 'md_popups' ) &&
			( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] )
		) {
			$popups = get_option( $this->_id );
			$total  = count( $popups['popups'] );

			for ( $c = 0; $c < $total; $c++ )
				if ( empty( $popups['popups'][$c]['id'] ) )
					$popups['popups'][$c]['id'] = preg_replace( '/[^a-z0-9_]/i', '', strtolower( $popups['popups'][$c]['name'] ) );

			update_option( $this->_id, $popups );
		}
	}


	/**
	 * Create data for popup being currently edited in Customizer.
	 *
	 * @since 1.0
	 */

	public function edit() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'md_popups_load' )
			if ( isset( $_GET['popup'] ) && $_GET['popup'] != '' ) {
				update_option( 'md_popups_edit', array(
					'id'   => $_GET['popup'],
					'name' => stripslashes( $_GET['popup_name'] )
				) );

				$edit = get_option( "{$this->_id}_edit" );

				wp_redirect( esc_url_raw( add_query_arg( array(
					'return'    => admin_url( "themes.php?page={$this->_id}_return" ),
					'autofocus' => array( 'panel' => $this->_id . '_' . $edit['id'] )
				), wp_customize_url() ) ) );

				die();
			}
	}


	/**
	 * Delete popup data in a very inefficient way.
	 * Need better solution.
	 *
	 * @since 1.0
	 */

	public function delete() {
		if (
			( isset( $_GET['page'] ) && $_GET['page'] == 'md_popups' ) &&
			( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] )
		) {
			$popups_data = get_option( $this->_id );

			if ( empty( $popups_data ) )
				return;

			// get current popups IDs
			$popups = array();

			foreach ( $popups_data['popups'] as $popup_id => $fields )
				$popups[] = $fields['id'];

			// get all popup options
			$options = array();

			foreach ( array_filter( wp_load_alloptions() ) as $option => $option_fields )
				if (
					( substr( $option, 0, strlen( "{$this->_id}_" ) ) == "{$this->_id}_" ) &&
					! ( substr( $option, 0, strlen( "{$this->_id}_edit" ) ) == "{$this->_id}_edit" ) &&
					! ( substr( $option, 0, strlen( "{$this->_id}_meta" ) ) == "{$this->_id}_meta" ) &&
					! ( substr( $option, 0, strlen( "{$this->_id}_license_status" ) ) == "{$this->_id}_license_status" )
				)
					$options[] = $option;

			// get all popups options to keep
			$total = count( $popups_data['popups'] );
			$keep  = array();

			for ( $c = 0; $c < $total; $c++ ) {
				foreach ( array_filter( wp_load_alloptions() ) as $option => $option_fields ) {
					$option_name       = "{$this->_id}_" . $popups[$c];
					$option_name_count = strlen( $option_name );

					if ( substr( $option, 0, $option_name_count ) == $option_name )
						$keep[] = $option;
				}
			}

			// delete
			$delete = array_diff( $options, $keep );

			if ( ! empty( $delete ) )
				foreach ( $delete as $d )
					delete_option( $d );
		}
	}


	/**
	 * Create data for popup being currently edited in Customizer.
	 *
	 * @since 1.0
	 */

	public function customizer_return() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'md_popups_return' ) {
			wp_redirect( esc_url_raw( admin_url( "themes.php?page={$this->_id}&tab={$this->_id}" ) ) );
			die();
		}
	}

}