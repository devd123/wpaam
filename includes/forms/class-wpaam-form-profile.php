<?php
/**
 * WP User Manager Forms: Profile Edit Form
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
class WPAAM_Form_Profile extends WPAAM_Form {

	public static $form_name = 'profile';

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
			
			
			// add_filter( 'wpaam/form/validate=profile', array( __CLASS__, 'validate_email' ), 10, 3 );
			// add_filter( 'wpaam/form/validate=profile', array( __CLASS__, 'validate_nickname' ), 10, 3 );

		}

		// Store uploaded avatar
		if( wpaam_get_option( 'custom_avatars' ) ) {
			add_action( 'wpaam_after_user_update', array( __CLASS__, 'add_avatar' ), 10, 3 );
		}

	}

	

	// /**
	//  * Validate email field.
	//  *
	//  * @access public
	//  * @since 1.0.0
	//  * @return void
	//  */
	// public static function validate_email( $passed, $fields, $values ) {

	// 	$email = $values['profile'][ 'user_email' ];

	// 	// If current email hasn't changed - abort.
	// 	if( $email == self::$user->user_email )
	// 		return;

	// 	if( email_exists( $email ) && $email !== self::$user->user_email )
	// 		return new WP_Error( 'email-validation-error', __( 'Email address already exists.', 'wpaam' ) );

	// 	return $passed;

	// }

	

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

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'profile' ) ) {
			return;
		}

		// Validate required
		// if ( is_wp_error( ( $return = self::validate_fields( $values, self::$form_name ) ) ) ) {
		// 	self::add_error( $return->get_error_message() );
		// 	return;
		// }

		// Update the profile
		self::update_profile(  );

	}

	/**
	 * Trigger update process.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function update_profile(  ) {

		$user_data = array(
			
			'company_name' => esc_attr( $_POST['company_name'] ),
			'company_status' => esc_attr( $_POST['company_status'] ),
			'company_logo' => esc_attr( $_POST['company_logo'] ),
			'description' => esc_attr( $_POST['description'] ),
			'first_name' => esc_attr( $_POST['first_name'] ),
			'last_name' => esc_attr( $_POST['last_name'] ),
			'family_name' => esc_attr( $_POST['family_name'] ),
			'client_prefix' => esc_attr( $_POST['client_prefix'] ),

		
		);		
		//echo "<pre>"; print_r($user_data); die;
		do_action( 'wpaam_before_user_update', $user_data, $user_data, self::$user->ID );
		
		//$user_id = wp_update_user( $user_data ); print_r($user_id); die;

		update_user_meta( self::$user->ID, 'company_name', $user_data['company_name'] );
		update_user_meta( self::$user->ID, 'company_status', $user_data['company_status'] );
		update_user_meta( self::$user->ID, 'description', $user_data['description'] );
		update_user_meta( self::$user->ID, 'first_name', $user_data['last_name'] );
		update_user_meta( self::$user->ID, 'family_name', $user_data['family_name'] );
		update_user_meta( self::$user->ID, 'client_prefix', $user_data['client_prefix'] );



		do_action( 'wpaam_after_user_update', $user_data, $user_data, self::$user->ID );

		if ( is_wp_error( $user_id ) ) {

			$this_page = add_query_arg( array( 'updated' => 'error' ), get_permalink() );
			wp_redirect( esc_url( $this_page ) );
			exit();

		} else {

			self::add_confirmation( __('Payments settings successfully updated.', 'wpaam') );
			$this_page = add_query_arg( array( 'updated' => 'success' ), get_permalink() );
			wp_redirect( esc_url( $this_page ) );
			exit();

		}


	}


	/**
	 * Add avatar to user custom field.
	 * Also deletes previously selected avatar.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_avatar( $user_data ) {

	global $current_user;
	get_currentuserinfo();
	 
	$upload_dir = wp_upload_dir();
	$user_dirname = $upload_dir['basedir'].'/'.$current_user->user_login;
	if ( ! file_exists( $user_dirname ) ) {
	    wp_mkdir_p( $user_dirname );
	}
		
	 	$allowedExts = array("jpg", "jpeg", "gif", "png");
	    $extension = end(explode(".", $_FILES["file"]["company_logo"]));
	    if ((($_FILES["file"]["type"] == "image/gif")
	         || ($_FILES["file"]["type"] == "image/jpeg")
	         || ($_FILES["file"]["type"] == "image/png")
	         || ($_FILES["file"]["type"] == "image/pjpeg"))
	        && ($_FILES["file"]["size"] < 20000)
	        && in_array($extension, $allowedExts))
	    {
	        if ($_FILES["file"]["error"] > 0)
	        {
	            echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	        }
	        else
	        {
	            echo "Upload: " . $_FILES["file"]["company_logo"] . "<br>";
	            echo "Type: " . $_FILES["file"]["type"] . "<br>";
	            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

	            if (file_exists($user_dirname . $_FILES["file"]["company_logo"]))
	            {
	                echo $_FILES["file"]["company_logo"] . " already exists. ";
	            }
	            else
	            {
	                move_uploaded_file($_FILES["file"]["tmp_name"],
	                                   $user_dirname . $_FILES["file"]["company_logo"]);
	                echo "Stored in: " . $user_dirname . $_FILES["file"]["company_logo"];
	            }
	        }
	    }
	    else
	    {
	        echo "Invalid file";
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
	    $current_account_tab = wpaam_get_current_account_tab();
		$all_tabs = array_keys( wpaam_get_account_page_tabs() );

		
		// Display template
		if( is_user_logged_in() ) :

			if( isset( $_POST['submit_wpaam_profile'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}


			get_wpaam_template( 'account.php',
				array(
					'atts'        => $atts,
					'form'        => self::$form_name,
					'fields'      => self::get_fields( 'profile' ),
					'user_id'     => self::$user->ID,
					'current_tab' => $current_account_tab,
					'all_tabs'    => $all_tabs
				)
			);

		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;

	}

}
