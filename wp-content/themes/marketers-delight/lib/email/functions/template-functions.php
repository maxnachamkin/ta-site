<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Feeds in data to build Email HTML form. You can use this function
 * to output an email form in your templates.
 *
 * If you leave the parameters empty while calling the function, the
 * default email form set in Dashboard > Tools > Email Forms will show.
 *
 * If you create a properly structured array to $fields you can overwrite
 * those values with your own. Whether it's something hardcoded or from
 * other options. See After Post, Email Widget, Email Lead files for example.
 *
 * @since 4.0
 */

function md_email_form( $fields = null, $atts = null ) {
	$email = get_option( 'md_email' );
	$data  = get_option( 'md_email_data' );
	$lists = md_email_lists();

	$email_list           = isset( $email['email_list'] ) ? esc_attr( $email['email_list'] ) : '';
	$fields['email_list'] = isset( $fields['email_list'] ) ? esc_attr( $fields['email_list'] ) : $email_list;

	$email_code           = isset( $email['email_code'] ) ? $email['email_code'] : '';
	$fields['email_code'] = isset( $fields['email_code'] ) ? $fields['email_code'] : $email_code;

	if (
		empty( $data ) || // no connection
		( empty( $fields['email_list'] ) && empty( $fields['email_code'] ) ) || // no email list or code
		( $data['service'] != 'custom_code' && ! in_array( $fields['email_list'], $lists['ids'] ) ) // check if list is valid in case of service change
	)
		return;

	$pass['service'] = $data['service'];

	$fields['email_title']   = isset( $fields['email_title'] ) ? stripslashes( $fields['email_title'] ) : stripslashes( $email['email_title'] );
	$fields['email_desc']    = isset( $fields['email_desc'] ) ? stripslashes( $fields['email_desc'] ) : stripslashes( $email['email_desc'] );
	$fields['email_classes'] = isset( $fields['email_classes'] ) ? esc_attr( $fields['email_classes'] ) : esc_attr( $email['email_classes'] );

	if ( ! empty( $fields['email_list'] ) ) {
		$fields['email_input']['name']    = isset( $fields['email_input']['name'] ) ? $fields['email_input']['name'] : $email['email_input']['name'];
		$fields['email_name_label']       = isset( $fields['email_name_label'] ) ? $fields['email_name_label'] : $email['email_name_label'];
		$fields['email_email_label']      = isset( $fields['email_email_label'] ) ? $fields['email_email_label'] : $email['email_email_label'];
		$fields['email_submit_text']      = isset( $fields['email_submit_text'] ) ? $fields['email_submit_text'] : $email['email_submit_text'];
		$fields['email_form_style']['attached'] = isset( $fields['email_form_style']['attached'] ) ? $fields['email_form_style']['attached'] : $email['email_form_style']['attached'];
		$fields['email_form_title']      = isset( $fields['email_form_title'] ) ? $fields['email_form_title'] : $email['email_form_title'];
		$fields['email_form_footer']     = isset( $fields['email_form_footer'] ) ? $fields['email_form_footer'] : $email['email_form_footer'];

		// mailchimp
		if ( $pass['service'] == 'mailchimp' ) {
			$lists = $data['list_data'];

			$lists_data = parse_url( $lists[$fields['email_list']]['url'] ); // convert URL params to array
			parse_str( $lists_data['query'] ); // convert query params to string vars (creates variables $u and $id)

			$pass['action']    = esc_url_raw( '//' . $lists_data['host'] . '/subscribe/post/' );
			$pass['att_name']  = 'MERGE1';
			$pass['att_email'] = 'MERGE0';
			$pass['u']         = $u;
			$pass['id']        = $id;
		}

		// aweber
		if ( $pass['service'] == 'aweber' ) {
			$pass['action']    = '//www.aweber.com/scripts/addlead.pl';
			$pass['att_name']  = 'name';
			$pass['att_email'] = 'email';
		}

		// activecampaign
		if ( $pass['service'] == 'activecampaign' ) {
			$lists = $data['list_data'];
			$list  = $lists[$fields['email_list']];

			$pass['action']    = $list['url'];
			$pass['att_name']  = 'fullname';
			$pass['att_email'] = 'email';
			$pass['nlbox']     = $list['lists'];
		}

		// convertkit
		if ( $pass['service'] == 'convertkit' ) {
			$lists = $data['list_data'];
			$list  = $lists[$fields['email_list']];

			$pass['action']    = $list['url'] . '/subscribe/';
			$pass['att_name']  = 'first_name';
			$pass['att_email'] = 'email';
		}
	}

	$fields = array_merge( $fields, $pass );

	return md_email_form_html( $fields, $atts );
}


/**
 * Loads email form template using data from function above.
 *
 * @since 4.0
 */

if ( ! function_exists( 'md_email_form_html' ) ) :

function md_email_form_html( $fields, $atts ) {
	$path = 'templates/email-form.php';

	if ( $template = locate_template( $path ) )
		include( $template );
	else
		include( MD_PLUGIN_DIR . "email/$path" );
}

endif;


/**
 * If no service is connected, display this message.
 *
 * @since 4.0
 */

function md_email_connect_notice() { ?>

	<p><?php echo sprintf( __( 'To display an email form here, first <a href="%s">connect to an email service</a>.', 'md' ), admin_url( 'themes.php?page=md_email' ) ); ?></p>

<?php }


/**
 * Get email lists in simple array format from MD Email Forms.
 *
 * @since 4.0
 */

function md_email_lists() {
	$email_data = get_option( 'md_email_data' );

	if ( $email_data['service'] == 'custom_code' )
		return array();

	$lists = array();

	if ( is_array( $email_data['list_data'] ) )
		foreach ( $email_data['list_data'] as $id => $fields ) {
			$lists['ids'][] = $id;
			$lists[$id] = $fields['name'];
		}

	return $lists;
}