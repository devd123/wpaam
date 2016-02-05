<?php
/**
 * WP User Manager Forms: Payments Settings Form
 *
 * @package     wp-user-manager
 * @author      Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
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
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {

		add_action( 'wp', array( __CLASS__, 'process' ) );

		// Set values to the fields
		if( ! is_admin() ) {

			self::$user = wp_get_current_user();
			add_filter( 'wpaam_profile_field_value', array( __CLASS__, 'set_payment_fields_values' ), 10, 3 );
			//add_filter( 'wpaam_profile_field_options', array( __CLASS__, 'set_fields_options' ), 10, 3 );
			//add_filter( 'wpaam/form/validate=profile', array( __CLASS__, 'validate_email' ), 10, 3 );
			//add_filter( 'wpaam/form/validate=profile', array( __CLASS__, 'validate_nickname' ), 10, 3 );

		}


	}

	/**
	 * Setup field values on the frontend based on the user
	 *
	 * @access public
	 * @since 1.0.0
	 * @return $value value of the field.
	 */
	public static function set_payment_fields_values( $default, $field ) {

		switch ( $field['meta'] ) {
			case 'paypal_username':
				return self::$user->paypal_username;
				break;
			case 'paypal_apikey':
				return self::$user->paypal_apikey;
				break;
			case 'paypal_signature':
				return self::$user->paypal_signature;
				break;
			case 'user_price_vat':
				return self::$user->user_price_vat;
				break;
			default:
				return apply_filters( 'wpaam_edit_payments_field_value', null, $field, self::$user->ID );
				break;
		}

	}

	/**
	 * Define payments fields
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function get_payments_fields() {

		self::$fields = array(
			'payments' => wpaam_get_payments_fields()
		);

	}

	/**
	 * Process the submission.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function process() {
		
		// Get fields
		self::get_payments_fields();

		// Get posted values
		$values = self::get_posted_fields();

		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'payments' ) ) {
			return;
		}

		// Validate required
		if ( is_wp_error( ( $return = self::validate_fields( $values, self::$form_name ) ) ) ) {
			self::add_error( $return->get_error_message() );
			return;
		}

		// Update the profile
		self::update_payments( $values );

	}

	/**
	 * Trigger update process.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function update_payments( $values ) {

		if( empty($values) || !is_array($values) )
			return;

		$user_data = array( 'ID' => self::$user->ID );

		foreach ( $values['payments'] as $meta_key => $meta_value ) {

			switch ( $meta_key ) {

				case 'paypal_username':
					$user_data += array( 'paypal_username' => $meta_value);
				break;
				case 'paypal_apikey':
					$user_data += array( 'paypal_apikey' =>  $meta_value);
				break;
				case 'paypal_signature':
					$user_data += array( 'paypal_signature' =>  $meta_value);
				break;
				case 'user_price_vat':
					$user_data += array( 'user_price_vat' =>  $meta_value);
				break;
				default:
					$user_data += array( $meta_key => $meta_value );
					break;

			}

		}

		do_action( 'wpaam_before_user_update', $user_data, $values, self::$user->ID );

		update_user_meta( self::$user->ID, 'paypal_username', $user_data['paypal_username'] );
		update_user_meta( self::$user->ID, 'paypal_apikey', $user_data['paypal_apikey'] );
		update_user_meta( self::$user->ID, 'paypal_signature', $user_data['paypal_signature'] );
		update_user_meta( self::$user->ID, 'user_price_vat', $user_data['user_price_vat'] );


		do_action( 'wpaam_after_user_update', $user_data, $values, self::$user->ID );

		if ( is_wp_error( self::$user->ID ) ) {

			self::add_error( self::$user->ID->get_error_message() );

		} else {

			self::add_confirmation( __('Payments settings successfully updated.', 'wpaam') );
	
		}


	}


	/**
	 * Output the form.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function output( $atts = array() ) {

		// Get fields
		self::get_payments_fields();

		// Display template
		if( is_user_logged_in() ) :

			if( isset( $_POST['submit_wpaam_payments'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}else{
				get_wpaam_template( 'forms/payments-form.php',
					array(
						'atts'        => $atts,
						'form'        => self::$form_name,
						'fields'      => self::get_fields( 'payments' ),
						'user_id'     => self::$user->ID,
					)
				);
			}
	
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;

	}

}
