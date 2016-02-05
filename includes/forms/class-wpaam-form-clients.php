<?php
/**
 * WPAAM FORMS : Clients List
 *
 * @package     wp-user-manager
 * @author      Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPAAM_Form_Clients extends WPAAM_Form {

	public static $form_name = 'clietns';

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

	public static function process() {
		

		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'products' ) ) {
			return;
		}

		// Validate required
		if ( is_wp_error( ( $return = self::validate_fields( $values, self::$form_name ) ) ) ) {
			self::add_error( $return->get_error_message() );
			return;
		}

	}

	
	public static function output( $atts = array() ) {

		// Get the tabs
		$current_clinet_tab = wpaam_get_current_clients_tab();
		$all_tabs = array_keys( wpaam_get_clients_page_tabs() );

		// Display template
		if ( is_user_logged_in() ) :

			get_wpaam_template( 'clients.php',
				array(
					'atts'        => $atts,
					//'form'        => self::$form_name,
					'user_id'     => self::$user->ID,
					'current_tab' => $current_clinet_tab,
					'all_tabs'    => $all_tabs
				)
			);
	
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;

	}

}
