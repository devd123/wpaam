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
			
		}

		// Store uploaded avatar
		if( wpaam_get_option( 'custom_avatars' ) ) {
			//add_action( 'wpaam_after_user_update', array( __CLASS__, 'add_avatar' ), 10, 3 );
		}

	}

	public static function check_client_prefix(){
		global $wpdb;
		$user_id = self::$user->ID;
		$prefix = $_POST['client_prefix'];
		$rows = $wpdb->get_row("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'client_prefix' AND meta_value = '$prefix' AND user_id != '$user_id' ");
		//print_r($rows); die;
	
		if( count($rows) > 0){
			return new WP_Error( 'client-validation-error', __('The client prefix value is already exists.', 'wpaam') );
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

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'profile' ) ) {
			return;
		}
		
		// Validate required
		if ( is_wp_error( ( $return = self::check_client_prefix(  ) ) ) ) {
			self::add_error( $return->get_error_message() );
			return;
		}

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
			'ID'           => self::$user->ID,
			'company_name' => esc_attr( $_POST['company_name'] ),
			'company_status' => esc_attr( $_POST['company_status'] ),
			//'company_logo' => esc_attr( $_POST['company_logo'] ),
			'description' => esc_attr( $_POST['description'] ),
			'first_name' => esc_attr( $_POST['first_name'] ),
			'last_name' => esc_attr( $_POST['family_name'] ),
			'client_prefix' => esc_attr( $_POST['client_prefix'] ),

		
		);		
		//echo "<pre>"; print_r($user_data); die;
		do_action( 'wpaam_before_user_update', $user_data, $user_data, self::$user->ID );
		
		$user_id = wp_update_user( $user_data ); //print_r($user_id); die;

		update_user_meta( self::$user->ID, 'company_name', $user_data['company_name'] );
		update_user_meta( self::$user->ID, 'company_status', $user_data['company_status'] );
		update_user_meta( self::$user->ID, 'description', $user_data['description'] );
		update_user_meta( self::$user->ID, 'first_name', $user_data['first_name'] );
		update_user_meta( self::$user->ID, 'last_name', $user_data['last_name'] );
		update_user_meta( self::$user->ID, 'client_prefix', $user_data['client_prefix'] );



		do_action( 'wpaam_after_user_update', $user_data, $user_data, self::$user->ID );

		if ( is_wp_error( $user_id ) ) {

			self::add_error( $user_id->get_error_message() );

		} else {

			self::add_confirmation( __('you have successfully updated  the account settings.', 'wpaam') );

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
		if( is_user_logged_in() && current_user_can( 'create_users' ) ) :

			if( isset( $_POST['submit_wpaam_profile'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}

			get_wpaam_template( 'forms/account-form.php',
				array(
					'atts'        => $atts,
					'form'        => self::$form_name,
					'user_id'     => self::$user->ID,
					'current_tab' => $current_account_tab,
					'all_tabs'    => $all_tabs
				)
			);

		elseif(is_user_logged_in() && current_user_can('aam_client')) :
		 	
		 	get_wpaam_template( 'client_account.php',
				array(
					'atts'        => $atts,
					'form'        => self::$form_name,
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
