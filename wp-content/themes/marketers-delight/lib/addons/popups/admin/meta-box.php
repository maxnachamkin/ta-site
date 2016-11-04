<?php

class md_popups_meta extends md_api {

	public function construct() {
		$this->_id = $this->suite = 'md_popups_meta';

		$this->meta_box = array(
			'name'    => __( 'Popup', 'md-popups' ),
			'context' => 'side'
		);

		$this->taxonomy = array(
			'name'     => __( 'Popup', 'md-popups' ),
			'priority' => 47
		);

		$this->main = new md_popups_fields( $this->_id );
	}


	public function register_fields() {
		return $this->main->register_fields();
	}


	public function fields() {
		$this->main->fields();
	}

}

$md_popups_meta = new md_popups_meta;