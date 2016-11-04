<?php

/** MD Email API **/


// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;


/**
 * This API is used to setup the email connection process.
 *
 * @since 4.1
 */

class md_email_api extends md_api {

	public $auth = array(
		'aweber'         => 'https://auth.aweber.com/1.0/oauth/authorize_app/e5957609',
		'mailchimp'      => 'http://admin.mailchimp.com/account/api-key-popup/',
		'activecampaign' => 'https://marketersdelight.net/connect-email-activecampaign/',
		'convertkit'     => 'https://app.convertkit.com/account/edit'
	);


	/**
	 * Psuedo constructor, sets up admin actions and fires off
	 * scripts/AJAX.
	 *
	 * @since 4.1
	 */

	public function construct() {
		$this->suite = 'md_email';

		add_action( 'admin_print_footer_scripts', array( $this, 'admin_footer_scripts' ) );
		add_action( 'wp_ajax_connect', array( $this, 'connect' ) );
		add_action( 'wp_ajax_disconnect', array( $this, 'disconnect' ) );
	}


	/**
	 * Loads required scripts into admin footer.
	 *
	 * @since 4.1
	 */

	public function admin_footer_scripts() {
		$slug = substr( get_current_screen()->base, -strlen( $this->suite ) ) === $this->suite;

		if ( ! empty( $slug ) ) {
			$data = get_option( 'md_email_data' );

			if ( ! empty( $data['service'] ) )
				$this->connect_scripts();
			else
				$this->disconnect_scripts();
		}
	}


	/**
	 * Scripts called on the connected screen.
	 *
	 * @since 4.1
	 */

	public function connect_scripts() {
		$data = get_option( 'md_email_data' );
	?>

		<script>

			jQuery( document ).ready( function( $ ) {

				$( '#md-email-disconnect' ).on( 'click', function( e ) {

					e.preventDefault();

					if ( ! confirm( '<?php _e( 'Disconnecting your email service will set your form settings back to default. All email forms on your website will be deleted until you reconnect to an email service. Are you sure you want to continue?', 'md' ); ?>' ) )
						return;

					$( '#md-email-disconnect' ).prop( 'disabled', true );

					$.post( ajaxurl, { action: 'disconnect', form: $( '#md-email-form' ).serialize() }, function( disconnected ) {
						$( '#md-email' ).html( disconnected );
					});

				});

			});

			<?php if ( $data['service'] != 'custom_code' ) : ?>

				// Toggle input fields if email list selected

				( function() {
					document.getElementById( '<?php echo $this->suite; ?>_email_list' ).onchange = function() {
						document.getElementById( '<?php echo $this->suite; ?>_email_form_fields' ).style.display = this.value != '' ? 'block' : 'none';
					}
					document.getElementById( '<?php echo $this->suite; ?>_email_input_name' ).onchange = function() {
						document.getElementById( '<?php echo $this->suite; ?>_email_name_label' ).style.display = this.checked ? 'table-row' : 'none';
					}
				})();

			<?php endif; ?>

		</script>

	<?php }


	/**
	 * Scripts called on the disconnect screen.
	 *
	 * @since 4.1
	 */

	public function disconnect_scripts() { ?>

		<script>

			jQuery( document ).ready( function( $ ) {

				$( '#md-email-connect' ).on( 'click', function( e ) {

					e.preventDefault();

					$( '#md-email-connect' ).prop( 'disabled', true );

					$.post( ajaxurl, { action: 'connect', form: $( '#md-email-form' ).serialize() }, function( connected ) {
						$( '#md-email' ).html( connected );
					});

				});

			});

			// Show API key input

			( function() {
				document.getElementById( '<?php echo $this->suite; ?>_service' ).onchange = function( e ) {
					document.getElementById( 'md-email-steps' ).style.display = this.value !== '' ? 'block' : 'none';
					document.getElementById( 'md-email-auth' ).style.display  = this.value !== 'custom_code' ? 'block' : 'none';
					document.getElementById( 'md-email-auth-btn' ).href       = this.value == 'aweber' ? '<?php echo $this->auth['aweber']; ?>' : this.value == 'mailchimp' ? '<?php echo $this->auth['mailchimp']; ?>' : this.value == 'activecampaign' ? '<?php echo $this->auth['activecampaign']; ?>' : '<?php echo $this->auth['convertkit']; ?>';
					document.getElementById( 'md-email-url' ).style.display   = this.value == 'activecampaign' ? 'table-row' : 'none';
				}
			})();

		</script>

	<?php }


	/**
	 * This method loads and connects to the specified email service
	 * API and also builds out a carefully constructed data array
	 * for use in the MD API save methods and anywhere else you
	 * may want to integrate this email data.
	 *
	 * use data array: get_option( 'md_email_data' );
	 *
	 * @since 4.1
	 */

	public function connect() {
		parse_str( stripslashes( $_POST['form'] ), $form );

		if ( ! wp_verify_nonce( $form['_wpnonce'], $form['option_page'] . '-options' ) ) // use native nonce
			die ( __( 'Sorry, there was an error during the connection process. Please try again.', 'md' ) );

		$api_key = $form[$this->suite]['api_key']; // api key is NOT saved to database, only used on AJAX connection. save in future versions.
		$api_url = $form[$this->suite]['api_url']; // api url is NOT saved to database, only used on AJAX connection. save in future versions.
		$service = $form[$this->suite]['service'];

		$data            = array();
		$data['service'] = $service;
		$data['fields']  = isset( $data['fields'] ) ? $data['fields'] : array();

		// only needed if connected to an actual service like mailchimp or aweber.
		if ( $service != 'custom_code' )
			$data['fields'] = array(
				'email_input' => array(
					'type' => 'checkbox',
					'options' => array(
						'name'
					)
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
				),
				'email_form_title' => array(
					'type' => 'text'
				),
				'email_form_footer' => array(
					'type' => 'textarea'
				)
			);

		$data['fields'] = array_merge( array(
			// form fields. list values passed in service connection methods
			'email_title' => array(
				'type' => 'text'
			),
			'email_desc' => array(
				'type' => 'text'
			),
			// advanced
			'email_classes' => array(
				'type' => 'text'
			)
		), $data['fields'] );

		if ( ! empty( $api_key ) )
			if ( $service == 'aweber' )
				$this->aweber_connect( $api_key, $data );
			elseif ( $service == 'mailchimp' )
				$this->mailchimp_connect( $api_key, $data );
			elseif ( $service == 'activecampaign' )
				$this->activecampaign_connect( $api_key, $api_url, $data );
			elseif ( $service == 'convertkit' )
				$this->convertkit_connect( $api_key, $data );

		if ( $service == 'custom_code' )
			$this->custom_code_connect( $data );

		wp_cache_flush();

		$this->connect_scripts();
		$this->redirect();

		exit;
	}


	/**
	 * Load ConvertKit API and structure data array.
	 *
	 * @since 4.3.3
	 */

	public function convertkit_connect( $api_key, $data ) {
		$json = file_get_contents( "https://api.convertkit.com/v3/forms?api_key=$api_key" );

		if ( empty( $json ) )
			$this->error();

		$api_data = json_decode( $json );

		foreach ( $api_data->forms as $form ) {
			$id   = esc_attr( $form->id );
			$name = esc_attr( $form->name );

			$pass['list_ids'][]     = $id;
			$data['list_data'][$id] = array(
				'name' => $name,
				'url'  => esc_url_raw( $form->url )
			);
		}

		$data['fields'] = array_merge( array(
			'email_list' => array(
				'type'    => 'select',
				'options' => $pass['list_ids']
			)
		), $data['fields'] );

		// save structured data to WP database
		update_option( 'md_email_data', $data );
	}


	/**
	 * Disconnect from email service by deleting all data.
	 *
	 * @since 4.1
	 */

	public function disconnect() {
		// unset various email forms
		$options = array(
			'md_email',
			'email_lead',
			'action_lead'
		);

		foreach ( $options as $option ) {
			$email = get_option( $option );

			if ( ! empty( $email['email_list'] ) ) {
				unset( $email['email_list'] );
				update_option( $option, $email );
			}
		}

		// delete email data
		delete_option( 'md_email_data' );

		// redirect to admin page after connection
		$this->disconnect_scripts();
		$this->redirect();

		exit;
	}


	/**
	 * Load ActiveCampaign API and structure data array.
	 *
	 * @since 4.3.1
	 */

	public function activecampaign_connect( $api_key, $api_url, $data ) {
		// load activecampaign API
		require_once( MD_PLUGIN_DIR . "email/api/services/activecampaign/ActiveCampaign.class.php");

		$ac      = new ActiveCampaign( $api_url, $api_key );
		$account = $ac->api( 'account/view' );
		$forms   = $ac->api( 'form/getforms' );

		if ( ! $account->result_code || ! $forms->result_code ) // if no connection, stop process and display error
			$this->error();

		// loop through API data and structure options array
		foreach ( $forms as $key => $list )
			if ( is_numeric( $key ) ) {
				$id   = esc_attr( $list->id );
				$name = esc_attr( $list->name );

				$pass['list_ids'][]     = $id;
				$data['list_data'][$id] = array(
					'name'  => $name,
					'url'   => esc_url_raw( "{$account->account}/proc.php" ),
					'lists' => $list->lists[0]
				);
			}

		$data['fields'] = array_merge( array(
			'email_list' => array(
				'type'    => 'select',
				'options' => $pass['list_ids']
			)
		), $data['fields'] );

		// save structured data to WP database
		update_option( 'md_email_data', $data );
	}


	/**
	 * Load MailChimp API and structure data array.
	 *
	 * @since 4.1
	 */

	public function mailchimp_connect( $api_key, $data ) {
		// load mailchimp API
		require_once( MD_PLUGIN_DIR . 'email/api/services/mailchimp.php' );

		$api       = new md_email_mailchimp( $api_key );
		$get_lists = $api->lists();

		if ( ! is_array( $get_lists['data'] ) ) // if no connection, stop process and display error
			$this->error();

		// loop through API data and structure options array
		foreach ( $get_lists['data'] as $list ) {
			$id   = esc_attr( $list['id'] );
			$name = esc_attr( $list['name'] );

			$pass['list_ids'][]     = $id;
			$data['list_data'][$id] = array(
				'name' => $name,
				'url'  => esc_url_raw( $list['subscribe_url_long'] )
			);
		}

		$data['fields'] = array_merge( array(
			'email_list' => array(
				'type'    => 'select',
				'options' => $pass['list_ids']
			)
		), $data['fields'] );

		// save structured data to WP database
		update_option( 'md_email_data', $data );
	}


	/**
	 * Load AWeber API and structure data array.
	 *
	 * @since 4.1
	 */

	public function aweber_connect( $api_key, $data ) {
		// load aweber API and structure special/secret keys. keys are NOT saved to database
		require_once( MD_PLUGIN_DIR . 'email/api/services/aweber/aweber_api.php' );

		$keys = array();

		try {
			list( $keys['consumer_key'], $keys['consumer_secret'], $keys['access_key'], $keys['access_secret'] ) = AWeberAPI::getDataFromAweberID( $api_key );
		}
		catch( AWeberAPIException $e ) {
			$this->error();
		}

		$aweber  = new AWeberAPI( $keys['consumer_key'], $keys['consumer_secret'] );
		$account = $aweber->getAccount( $keys['access_key'], $keys['access_secret'] );

		// loop through API data and structure options array
		foreach ( $account->lists->data['entries'] as $list ) {
			$id = $list['unique_list_id'];

			$pass['list_ids'][]     = esc_attr( $id );
			$data['list_data'][$id] = array(
				'name' => esc_attr( $list['name'] )
			);
		}

		$data['fields'] = array_merge( array(
			'email_list' => array(
				'type'    => 'select',
				'options' => $pass['list_ids']
			)
		), $data['fields'] );

		// save structured data to WP database
		update_option( 'md_email_data', $data );
	}


	/**
	 * Structures data array form Custom Email Forms.
	 *
	 * @since 4.1
	 */

	public function custom_code_connect( $data ) {
		$data['fields'] = array_merge( array(
			'email_code' => array(
				'type' => 'code'
			)
		), $data['fields'] );

		// save structured data to WP database
		update_option( 'md_email_data', $data );
	}


	/**
	 * This sets where the user is redirected to after a successful
	 * AJAX request.
	 *
	 * @since 4.1
	 */

	public function redirect() {
		$admin = new md_email;
		$admin->admin_page();
	}


 	/**
	 * Show error message if unable to connect to anything.
	 *
	 * @since 4.1
	 */

	public function error() {
		wp_die( __( 'Could not connect to your email service. Your Authorization code may be incorrect. Reload the page and try again.<br /><br /><b>A few things to check for:</b><br /><br />1) Make sure you have at least one email list created in your email service provider <em>before</em> starting the connection process.<br /> 2) Make sure you have an Internet connection<br />3) Make sure you didn\'t accidentally paste a blank space or blank line at the end of your authorization code.', 'md' ) );
	}

}