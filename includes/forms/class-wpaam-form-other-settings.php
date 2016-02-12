<?php
/**
 * WP User Manager Forms: Invoices Edit Form
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

class WPAAM_Form_Other_Settings extends WPAAM_Form {

	public static $form_name = 'other-settings';

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
		
		}


	}

	/**
	 * Process the submission.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function process() {
		
	
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'other-settings' ) ) {
			return;
		}

		// Validate required
		// if ( is_wp_error( ( $return = self::validate_fields( $values, self::$form_name ) ) ) ) {
		// 	self::add_error( $return->get_error_message() );
		// 	return;
		// }

	

		// Proceed to update the othere settings field
		$user_data = array(
			'invoice_prefix' => esc_attr( $_POST['invoice_prefix'] ),
			'invoice_start' => esc_attr( $_POST['invoice_start'] ),
			'quotation_prefix' => esc_attr( $_POST['quotation_prefix'] ),
			'quotation_start' => esc_attr( $_POST['quotation_start'] ),
		);		

		
		update_user_meta( self::$user->ID, 'invoice_prefix', $user_data['invoice_prefix'] );
		update_user_meta( self::$user->ID, 'invoice_start', $user_data['invoice_start'] );
		update_user_meta( self::$user->ID, 'quotation_prefix', $user_data['quotation_prefix'] );
		update_user_meta( self::$user->ID, 'quotation_start', $user_data['quotation_start'] );

		if ( is_wp_error( self::$user->ID ) ) {

			self::add_error( self::$user->ID->get_error_message() );

		} else {

			self::add_confirmation( __('Settings has been successfully updated.', 'wpaam') );
	
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
		
	
		if( isset( $_POST['submit_wpaam_other_settings'] ) ) {
			
			// Show errors from fields
			self::show_errors();
			// Show confirmation messages
			self::show_confirmations();
			
		}
		
		// Display template
		if( is_user_logged_in() ) :

			get_wpaam_template( 'forms/other-settings-form.php',
				array(
					'atts'   => $atts,
					'form'   => self::$form_name,
					'user_id'  => self::$user->ID,
				)
		);

			// Show login form if not logged in
			else :

				echo wpaam_login_form();

			endif;
	

	}

}
