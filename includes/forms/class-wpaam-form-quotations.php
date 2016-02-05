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

class WPAAM_Form_Quotations extends WPAAM_Form {

	public static $form_name = 'quotations';

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
			add_filter( 'wpaam_quotations_field_value', array( __CLASS__, 'set_quotations_fields_values' ), 10, 3 );
		
		}


	}

	/**
	 * Setup field values on the frontend based on the user
	 *
	 * @access public
	 * @since 1.0.0
	 * @return $value value of the field.
	 */
	public static function set_quotations_fields_values( $default, $field ) {

		switch ( $field['meta'] ) {
			case 'quotations_prefix':
				return self::$user->quotations_prefix;
				break;
			case 'quotations_start':
				return self::$user->quotations_start;
				break;
			default:
				return apply_filters( 'wpaam_edit_quotations_field_value', null, $field, self::$user->ID );
				break;
		}

	}

	/**
	 * Define quotations update form fields
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function get_quotations_fields() {

		self::$fields = apply_filters( 'wpaam_quotations_fields', array(
			'quotations' => array(
				'quotations_prefix' => array(
					'label'       => __( 'Quotations Prefix', 'wpaam' ),
					'type'        => 'text',
					'required'    => true,
					'placeholder' => '',
					'priority'    => 1
				),
				'quotations_start' => array(
					'label'       => __( 'Quotations Start', 'wpaam' ),
					'type'        => 'text',
					'required'    => true,
					'placeholder' => '',
					'priority'    => 2
				),
			),
		) );

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
		self::get_quotations_fields();

		// Get posted values
		$values = self::get_posted_fields();
		//print_r($values); die;
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'quotations' ) ) {
			return;
		}

		// Validate required
		if ( is_wp_error( ( $return = self::validate_fields( $values, self::$form_name ) ) ) ) {
			self::add_error( $return->get_error_message() );
			return;
		}

		// Proceed to update the password
		$user_data = array(
			'quotations_prefix' => $values['quotations']['quotations_prefix'],
			'quotations_start' => $values['quotations']['quotations_start']
		);

		do_action( 'wpaam_before_user_update', $user_data, $values, self::$user->ID );

		// Proceed to update the invoices field
		update_user_meta( self::$user->ID, 'quotations_prefix', $user_data['quotations_prefix'] );
		update_user_meta( self::$user->ID, 'quotations_start', $user_data['quotations_start'] );

		do_action( 'wpaam_after_user_update', $user_data, $values, self::$user->ID );

		if ( is_wp_error( self::$user->ID ) ) {

			self::add_error( self::$user->ID->get_error_message() );

		} else {

			self::add_confirmation( __('Quotations settings successfully updated.', 'wpaam') );
	
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
		self::get_quotations_fields();

		if( isset( $_POST['submit_wpaam_quotations'] ) ) {
			
			// Show errors from fields
			self::show_errors();
			// Show confirmation messages
			self::show_confirmations();
			
		}else{
			// Display template
			if( is_user_logged_in() ) :

				get_wpaam_template( 'forms/quotations-form.php',
					array(
						'atts'   => $atts,
						'form'   => self::$form_name,
						'fields' => self::get_fields( 'quotations' ),
						'user_id' => self::$user->ID,
					)
				);

			// Show login form if not logged in
			else :

				echo wpaam_login_form();

			endif;
		}

	}

}
