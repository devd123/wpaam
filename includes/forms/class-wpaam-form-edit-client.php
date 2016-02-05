<?php
/**
 * WPAAM FORMS : Client Edit Form
 *
 * @package     wp-user-manager
 * @author      Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPAAM_Form_Edit_Client extends WPAAM_Form {

	public static $form_name = 'edit-client';
	
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
		
		if( ! is_admin() ) {

			self::$user = wp_get_current_user();
			//echo $user_id; die;

		}


	}


	// public static function validate_client_field(  ) {
		
		
	// 	$user_name = $_POST['user_name'];
	// 	$email = $_POST['email'];
		
	// 	if ( !$user_name )
	// 		return new WP_Error( 'client-validation-error', __('A username is required for registration.', 'wpaam') );
	// 	elseif ( !is_email($email, true) )
	// 		return new WP_Error( 'client-validation-error', __( 'You must enter a valid email address.', 'wpaam' ) );
	// 	elseif ( email_exists($email) )
	// 		return new WP_Error( 'client-validation-error', __('Sorry, that email address is already used!', 'wpaam') );

	// 	return $client;
		
	// }

	public static function uname_exists() {
    global $wpdb;
 		
 		$uname = $_POST['user_name'];
   		$sql = "SELECT ID FROM $wpdb->users WHERE user_login = '" . $uname . "' && post_author = '" .self::$user->ID. "' && post_type = 'aam-product' "; 
		$res =  $wpdb->get_var( $sql );
	 
	    return $res;
	}

	public static function process(){
		if(isset($_GET['client_id']) && $_GET['client_id'] !== ''){
			self::update_process();
		}else{
			self::add_process();
		}
	}
	
	public static function add_process() {
	
	
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-client' ) ) {
			return;
		}

		//Validate required
		// if ( is_wp_error( ( $return = self::validate_client_field(  ) ) ) ) {
		// 	self::add_error( $return->get_error_message() );
		// 	return;
		// }

		// Proceed to update the password
		$user_login = self::$user->user_login.'_'.current_time( 'timestamp', 1 ); 
		$user_pass = wp_generate_password();
		$userdata = array(
			'user_pass' => $user_pass,
			'user_login' => $user_login,
			'first_name' => esc_attr( $_POST['first_name'] ),
			'last_name' => esc_attr( $_POST['last_name'] ),
			'company_name' => esc_attr( $_POST['company_name'] ),
			'client_email' => esc_attr( $_POST['client_email'] ),
			//'user_url' => esc_attr( $_POST['website'] ),
			// 'street' => esc_attr( $_POST['street'] ),
			// 'zipcode' => esc_attr( $_POST['zipcode'] ),
			// 'city' => esc_attr( $_POST['city'] ),
			// 'country' => esc_attr( $_POST['country'] ),
			'role' => esc_attr('aam_client'),
		);		
			//print_r($userdata); die;		
		
			$newuser_id = wp_insert_user( $userdata ); 
			wpaam_new_user_notification( $newuser_id, $user_pass );
			
			update_user_meta( $newuser_id, 'parent_user', self::$user->user_login);
			update_user_meta( $newuser_id, 'company_name', $userdata['company_name']);
			update_user_meta( $newuser_id, 'client_email', $userdata['client_email']);
		

			

			if ( is_wp_error($newuser_id) ) {

				self::add_error( $newuser_id->get_error_message() );

			} else {

				self::add_confirmation( __('you have successfully added a new client.', 'wpaam') );

			}
		
	}

	public static function update_process() {
	
		
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-client' ) ) {
			return;
		}
		

		$client_id = $_GET['client_id'];
		
		$updateddata = array(
			'first_name' => esc_attr( $_POST['first_name'] ),
			'last_name' => esc_attr( $_POST['last_name'] ),
			'company_name' => esc_attr( $_POST['company_name'] ),
			'client_email' => esc_attr( $_POST['client_email'] ),
			//'user_url' => esc_attr( $_POST['website'] ),
			// 'street' => esc_attr( $_POST['street'] ),
			// 'zipcode' => esc_attr( $_POST['zipcode'] ),
			// 'city' => esc_attr( $_POST['city'] ),
			// 'country' => esc_attr( $_POST['country'] ),
			
		);		
			
			//global $wpdb;
			//$user_id = $wpdb->update($wpdb->users, array('user_login' => $userdata['user_login'] , 'user_email' => $userdata['user_email']) , array('ID' => $client_id));
			//$user_id = wp_update_user( array ( 'ID' => $client_id, 'user_login' => $userdata['user_login'] ) );
			
		if ( !$updateddata['first_name'] )
	 		return new WP_Error( 'client-validation-error', __('A client first name is required', 'wpaam') );
	 	elseif ( !$updateddata['last_name'] )
	 		return new WP_Error( 'client-validation-error', __('A client last name is required', 'wpaam') );
	 	elseif ( !$updateddata['client_email'] )
	 		return new WP_Error( 'client-validation-error', __('A client email is required', 'wpaam') );
	 	else{
				update_user_meta( $client_id, 'parent_user', self::$user->user_login);
				update_user_meta( $client_id, 'first_name', $updateddata['first_name']);
				update_user_meta( $client_id, 'last_name', $updateddata['last_name']);
				update_user_meta( $client_id, 'company_name', $updateddata['company_name']);
				update_user_meta( $client_id, 'client_email', $updateddata['client_email']);
			
				self::add_confirmation( __('you have updated client data.', 'wpaam') );
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

		// Get the tabs
		$current_clinet_tab = wpaam_get_current_clients_tab();
		$all_tabs = array_keys( wpaam_get_clients_page_tabs() );
	
		// Display template
		if ( is_user_logged_in() ) :

			if( isset( $_POST['submit_wpaam_edit_clients'] ) || isset( $_POST['submit_wpaam_add_clients'] ) ) {
			
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			
			}
			
			get_wpaam_template( 'forms/edit-client-form.php',
				array(
					'atts'        => $atts,
					'form'        => self::$form_name,
					'author_id'   => self::$user->ID,
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
