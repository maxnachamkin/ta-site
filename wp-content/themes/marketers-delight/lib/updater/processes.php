<?php

class md_update_processes {

	public function __construct() {
		$this->version           = MD_VERSION;
		$this->marketers_delight = get_option( 'marketers_delight' );

		add_action( 'admin_init', array( $this, 'updaters' ) );
	}


	/**
	 * Determines whether to run any additional updater processes.
	 *
	 * @since 4.1
	 */

	public function updaters() {
		if ( ! $this->marketers_delight || ! empty( $this->marketers_delight['db_version'] ) || $this->marketers_delight['db_version'] < $this->version ) {
			// if this process has never been run, create the default array.
			if ( ! is_array( $this->marketers_delight ) )
				$this->marketers_delight = array();

			// set the current DB version. accounts for nonexistent db_version as this process was introduced in 1.2.1.
			$current_db_version = ! empty( $this->marketers_delight['db_version'] ) ? $this->marketers_delight['db_version'] : '1.2.1';

			// run needed processes by version
			$this->processes( $current_db_version );

			// update version in database so we can go through this cycle again when a new version comes out.
			$this->marketers_delight['db_version'] = $this->version;
			update_option( 'marketers_delight', $this->marketers_delight );
		}
	}


	/**
	 * Load update processes when needed.
	 *
	 * @since 4.3
	 */

	public function processes( $current_db_version ) {
		if ( $current_db_version < '1.1' )
			$this->update_11();
		if ( $current_db_version < '1.2' )
			$this->update_12();
		if ( $current_db_version < '4.3' )
			$this->process_43();
		if ( $current_db_version < '4.3.1' )
			$this->process_431();
		if ( $current_db_version < '4.3.2' )
			$this->process_432();
		if ( $current_db_version < '4.5' )
			$this->process_45();
	}


	/**
	 * Process 4.5
	 * delete old license keys
	 * Move main menu popup setting to md_popups
	 * Move md_site_tools to md_settings
	 */

	public function process_45() {
		// delete obsolete license keys
		$licenses = get_option( 'md_licenses' );

		if ( ! empty( $licenses ) )
			delete_option( 'md_licenses' );

		// rename site tools option array
		$site_tools = get_option( 'md_site_tools' );

		if ( ! empty( $site_tools ) ) {
			update_option( 'md_settings', $site_tools );
			delete_option( 'md_site_tools' );
		}

		// move main menu popup selection
		$popups = get_option( 'md_popups' );
		$popup  = get_option( 'md_main_menu_popup' );

		if ( ! empty( $popups ) && ! empty( $popup ) ) {
			$popup_new  = array( 'main_menu' => $popup );
			$popups_new = array_merge( $popups, $popup_new );
			update_option( 'md_popups', $popups_new );
//			delete_option( 'md_main_menu_popup' );
		}

		wp_cache_flush();
	}


	/**
	 * Process 4.3.2
	 * Move plugin license key from old option to new.
	 */

	public function process_432() {
		$licenses = get_option( 'md_licenses' );

		$licenses['plugin'] = get_option( 'md_plugin_license_key' );

		update_option( 'md_licenses', $licenses );
		delete_option( 'md_plugin_license_key' );
		wp_cache_flush();
	}


	/**
	 * Process 4.3.1
	 * Moves setting to show x Page Lead on blog into new option set.
	 */

	public function process_431() {
		$new = array();

		foreach ( array( 'email_lead', 'funnel_lead', 'table_lead', 'action_lead' ) as $lead ) {
			$old = get_option( $lead );

			if ( ! empty( $old['blog']['enable'] ) ) {
				$new['display']['blog'] = $old['blog']['enable'];
				$new_value = array_merge( $old, $new );

				update_option( $lead, $new_value );
			}
		}
	}


	/**
	 * Process 4.3 (upped # from 1.x to 4.x to match the plugin)
	 *
	 * Add new Page Leads position, move Email Lead BG image, rename
	 * Table Lead Design to Template.
	 */

	public function process_43() {

		// 1. Rebuild Page Leads data

		page_leads_activate();

		// 2. Move Email Lead BG Image to Design Options

		$new = array();

		foreach ( array_filter( wp_load_alloptions() ) as $setting => $fields ) {
			// Email
			if ( substr( $setting, 0, 10 ) == 'md_email' || substr( $setting, 0, 10 ) == 'email_lead' || substr( $setting, 0, 14 ) == 'email_lead_tax' ) {
				$old = get_option( $setting );

				if ( ! empty( $old['email_image'] ) ) {
					$new['bg_image'] = $old['email_image'];
					$new_value = array_merge( $old, $new );

					update_option( $setting, $new_value );
				}
			}

			// Table Lead
			if ( substr( $setting, 0, 10 ) == 'table_lead' || substr( $setting, 0, 14 ) == 'table_lead_tax' ) {
				$old = get_option( $setting );

				if ( ! empty( $old['design'] ) ) {
					$new['template'] = $old['design'];
					$new_value = array_merge( $old, $new );

					update_option( $setting, $new_value );
				}
			}
		}

		// 2a. Move post meta

		$this->update_meta_key( 'email_lead_email_image', 'email_lead_bg_image' );
		$this->update_meta_key( 'action_lead_email_image', 'action_lead_bg_image' );
		$this->update_meta_key( 'md_after_post_email_image', 'md_after_post_bg_image' );
		$this->update_meta_key( 'table_lead_design', 'table_lead_template' );
	}


	/**
	 * Process 1.2
	 *
	 * Build Page Leads data! Hello Page Leads!
	 */

	 function update_12() {
		 page_leads_activate();
	 }


	/**
	 * Process 1.1
	 *
	 * I did a poor job naming these fields in 1.0 so this update
	 * adds the 'email_' prefix to distinguish email fields from
	 * other option fields.
	 */

	public function update_11() {
		$email      = get_option( 'md_email' );
		$email_array = ! empty( $email ) ? $email : array();

		if ( ! array_key_exists( 'list', $email_array ) )
			return;

		$email_data = get_option( 'md_email_data' );

		// 1. Rebuild MD Email Data

		if ( ! empty( $email_data ) ) {
			$service = $email_data['service'];

			if ( $service != 'custom_code' )
				$email_data['fields'] = array(
					'email_input' => array(
						'type'    => 'checkbox',
						'options' => array( 'name' )
					),
					'email_name_label' => array(
						'type' => 'text'
					),
					'email_email_label' => array(
						'type' => 'text'
					),
					'email_submit_text' => array(
						'type' => 'text'
					),
					'email_form_style' => array(
						'type'    => 'checkbox',
						'options' => array( 'attached' )
					)
				);

			$list_ids = array();
			foreach( $email_data['list_data'] as $id => $fields )
				$list_ids[] = $id;

			$email_data['fields'] = array_merge( array(
				'email_title' => array(
					'type' => 'text'
				),
				'email_desc' => array(
					'type' => 'text'
				),
				'email_list' => array(
					'type'    => 'select',
					'options' => $list_ids
				),
				'email_image' => array(
					'type' => 'media'
				),
				'email_classes' => array(
					'type' => 'text'
				)
			), $email_data['fields'] );

			update_option( 'md_email_data', $email_data );
		}


		// 2. Reassign Post Meta values

		$meta = array(
			'md_after_post_title'             => 'md_after_post_email_title',
			'md_after_post_desc'              => 'md_after_post_email_desc',
			'md_after_post_list'              => 'md_after_post_email_list',
			'md_after_post_input_input_name'  => 'md_email_email_input_name',
			'md_after_post_input_name_label'  => 'md_after_post_email_name_label',
			'md_after_post_input_email_label' => 'md_after_post_email_email_label',
			'md_after_post_input_submit_text' => 'md_after_post_email_submit_text',
			'md_after_post_image'             => 'md_after_post_email_image',
			'md_after_post_classes'           => 'md_after_post_email_classes'
		);

		foreach ( $meta as $old => $new )
			$this->update_meta_key( $old, $new );


		// 3. Resassign Settings API

		if ( ! empty( $email ) ) {
			$email_new['email_title']                       = $email['title'];
			$email_new['email_desc']                        = $email['desc'];
			$email_new['email_list']                        = $email['list'];
			$email_new['email_input']['name']               = $email['input']['input_name'];
			$email_new['email_name_label']                  = $email['input_name_label'];
			$email_new['email_email_label']                 = $email['input_email_label'];
			$email_new['email_submit_text']                 = $email['input_submit_text'];
			$email_new['email_image']                       = $email['image'];
			$email_new['email_classes']                     = $email['classes'];
			$email_new['email_after_post']['display_posts'] = $email['after_post']['display_posts'];

			update_option( 'md_email', $email_new );
		}
	}


	/**
	 * Helper function, sends query to DB to update a post meta key.
	 *
	 * @since 4.1
	 */

	public function update_meta_key( $old, $new ) {
	    global $wpdb;

	    $query = 'UPDATE ' . $wpdb->prefix . "postmeta SET meta_key = '$new' WHERE meta_key = '$old'";

	    return $wpdb->get_results( $query, ARRAY_A );
	}

}

$md_update_processes = new md_update_processes;