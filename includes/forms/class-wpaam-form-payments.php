<?php
/**
 * WPAAM Forms: Payments Settings Form
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Form_Password Class
 *
 * @since 1.0.0
 */

class WPAAM_Form_Payments extends WPAAM_Form {

	public static $form_name = 'payments';

	private static $user;

	/**
	 * Init the form.
	 */
	public static function init() {

		add_action( 'wp', array( __CLASS__, 'process' ) );

		// Set values to the fields
		if( ! is_admin() ) {

			self::$user = wp_get_current_user();

		}


	}

	/**
	 * Process the submission.
	 */
	public static function process() {
		

		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'payments' ) ) {
			return;
		}

		// Validate required
		// if ( is_wp_error) {
		// 	self::add_error( $return->get_error_message() );
		// 	return;
		// }

		// Update the profile
		self::update_payments(  );

	}

	/**
	 * Trigger update process.
	 *
	 */
	public static function update_payments(  ) {

	
		$user_data = array(
			'paypal_username' => esc_attr( $_POST['paypal_username'] ),
			'paypal_apikey' => esc_attr( $_POST['paypal_apikey'] ),
			'paypal_signature' => esc_attr( $_POST['paypal_signature'] ),
			'user_allow_vat' => esc_attr( $_POST['user_allow_vat'] ),
			'user_vat_values' => esc_attr( $_POST['user_vat_values'] ),
		);		

		
		update_user_meta( self::$user->ID, 'paypal_username', $user_data['paypal_username'] );
		update_user_meta( self::$user->ID, 'paypal_apikey', $user_data['paypal_apikey'] );
		update_user_meta( self::$user->ID, 'paypal_signature', $user_data['paypal_signature'] );
		update_user_meta( self::$user->ID, 'user_allow_vat', $user_data['user_allow_vat'] );
		update_user_meta( self::$user->ID, 'user_vat_values', $user_data['user_vat_values'] );


		if ( is_wp_error( self::$user->ID ) ) {

			self::add_error( self::$user->ID->get_error_message() );

		} else {

			self::add_confirmation( __('Payments settings successfully updated.', 'wpaam') );
	
		}


	}


	/**
	 * Output the form.
	 *
	 */
	public static function output( $atts = array() ) {

		// Display template
		if( is_user_logged_in() ) :

			if( isset( $_POST['submit_wpaam_payments'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}
			
			get_wpaam_template( 'forms/payments-form.php',
				array(
					'atts'        => $atts,
					'form'        => self::$form_name,
					'user_id'     => self::$user->ID,
				)
			);
		
	
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;

	}

}
