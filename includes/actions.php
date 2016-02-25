<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function wpaam_add_aamuser_capability() {
    //add user roles
    add_role( 'aam_user',__('AAM User', 'wpaam'), array( 'read' => true ));
 	add_role( 'aam_client', __('AAM Client' , 'wpaam') , array( 'read' => true ) );

    // gets the user role
    $aamuserrole = get_role( 'aam_user' );
    $aamuserrole->add_cap( 'create_users' );
    $aamuserrole->add_cap( 'list_users' );
    $aamuserrole->add_cap( 'edit_users' );
    $aamuserrole->add_cap( 'delete_users' );
    $aamuserrole->add_cap( 'publish_product' ); 
    $aamuserrole->add_cap('edit_products');
	$aamuserrole->add_cap('edit_product');
	$aamuserrole->add_cap('delete_product');
	$aamuserrole->add_cap('read_product');
	$aamuserrole->add_cap('publish_quotation');
	$aamuserrole->add_cap('edit_quotations');
	$aamuserrole->add_cap('edit_quotation');
	$aamuserrole->add_cap('delete_quotation');
	$aamuserrole->add_cap('read_quotation');
	$aamuserrole->add_cap('publish_invoice');
	$aamuserrole->add_cap('edit_invoices');
	$aamuserrole->add_cap('edit_invoice');
	$aamuserrole->add_cap('delete_invoice');
	$aamuserrole->add_cap('read_invoice');
    //echo "<pre>"; print_r($aamuserrole); die;
   
    // This only works, because it accesses the class instance.
   
}

add_action( 'admin_init', 'wpaam_add_aamuser_capability');




function wpaam_restrict_wp_register() {

	if ( wpaam_get_option( 'wp_login_signup_redirect' ) ):
		$permalink = wpaam_get_option( 'wp_login_signup_redirect' );
		wp_redirect( esc_url( get_permalink( $permalink ) ) );
		exit();
	endif;

}
add_action( 'login_form_register', 'wpaam_restrict_wp_register' );

/**
 * Stops users from accessing wp-login.php?action=lostpassword
 *
 * @since 1.1.0
 * @access public
 * @return void
 */
function wpaam_restrict_wp_lostpassword() {

	if ( wpaam_get_option( 'wp_login_password_redirect' ) ):
		$permalink = wpaam_get_option( 'wp_login_password_redirect' );
		wp_redirect( esc_url( get_permalink( $permalink ) ) );
		exit();
	endif;

}
add_action( 'login_form_lostpassword', 'wpaam_restrict_wp_lostpassword' );

/**
 * Stops users from seeing the admin bar on the frontend.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function wpaam_remove_admin_bar() {

	$excluded_roles = wpaam_get_option( 'adminbar_roles' );
	$user = wp_get_current_user();

	if ( !empty( $excluded_roles ) && array_intersect( $excluded_roles, $user->roles ) && !is_admin() ) {
		if ( current_user_can( $user->roles[0] ) ) {
			show_admin_bar( false );
		}
	}

}
add_action( 'after_setup_theme', 'wpaam_remove_admin_bar' );

/**
 * Stops users from seeing the profile.php page in wp-admin.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function wpaam_remove_profile_wp_admin() {

	if ( !current_user_can( 'administrator' ) && IS_PROFILE_PAGE && wpaam_get_option( 'backend_profile_redirect' ) ) {
		wp_redirect( esc_url( get_permalink( wpaam_get_option( 'backend_profile_redirect' ) ) ) );
		exit;
	}

}
add_action( 'load-profile.php', 'wpaam_remove_profile_wp_admin' );

/**
 * Show content of the User ID column in user list page
 *
 * @since 1.0.0
 * @access public
 * @return array
 */
function wpaam_show_user_id_column_content( $value, $column_name, $user_id ) {
	if ( 'user_id' == $column_name )
		return $user_id;
	return $value;
}
add_action( 'manage_users_custom_column',  'wpaam_show_user_id_column_content', 10, 3 );

/**
 * Add hidden field into login form to identify login
 * has been made from a wpaam login form
 *
 * @since 1.0.5
 * @access public
 * @return mixed
 */
function wpaam_add_field_to_login( $content, $args ) {

	$output = '';

	if( is_array( $args ) && in_array( 'login_link' , $args ) ) {
		$output .= '<input type="hidden" name="wpaam_is_login_form" value="wpaam">';
	}

	// Store redirect url if specified
	$redirect = ( isset( $_GET['redirect_to'] ) && $_GET['redirect_to'] !=='' ) ? urlencode( $_GET['redirect_to'] ) : false;
	$output .= '<input type="hidden" name="wpaam_test" value="'.$redirect.'">';

	return $output;

}
add_action( 'login_form_middle', 'wpaam_add_field_to_login', 10, 2 );

/**
 * Authenticate the user and decide which login method to use.
 *
 * @since 1.0.3
 * @param  string $user     user object
 * @param  string $username typed username
 * @param  string $password typed password
 * @return void Results of autheticating via wp_authenticate_username_password(), using the username found when looking up via email.
 */
function wpaam_authenticate_login_method( $user, $username, $password ) {

	// Get default login method
	$login_method = wpaam_get_option( 'login_method', 'username' );

	// Authenticate via email only
	if( $login_method == 'email'  ) {

		if ( is_a( $user, 'WP_User' ) )
			return $user;

			if( !empty( $username ) && is_email( $username ) ) {

				$user = get_user_by( 'email', $username );

				if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
					$username = $user->user_login;

				return wp_authenticate_username_password( null, $username, $password );

			}

	} else if( $login_method == 'username_email' ) {

		if ( is_a( $user, 'WP_User' ) )
			return $user;

			$username = sanitize_user( $username );

			if( !empty( $username ) && is_email( $username ) ) {

				$user = get_user_by( 'email', $username );

				if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
					$username = $user->user_login;

				return wp_authenticate_username_password( null, $username, $password );

			} else {

				return wp_authenticate_username_password( null, $username, $password );

			}

	}

}

// Run filters only when alternative methods are selected
if( ( wpaam_get_option( 'login_method') == 'email' || wpaam_get_option( 'login_method') == 'username_email' ) && isset( $_POST['wpaam_is_login_form'] ) ) {
	remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
	add_filter( 'authenticate', 'wpaam_authenticate_login_method', 20, 3 );
}

/**
 * Authenticates the login form, if failed
 * returns back to the page where it came from.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function wpaam_authenticate_login_form( $user ) {

	if ( ! defined( 'DOING_AJAX' ) && isset( $_SERVER['HTTP_REFERER'] ) && isset( $_POST['log'] ) && isset( $_POST['pwd'] ) ) :

		// check what page the login attempt is coming from
		$referrer = $_SERVER['HTTP_REFERER'];

		// remove previously added query strings
		$referrer = add_query_arg( array(
			'login'       => false,
			'captcha'     => false
		), $referrer );

		$error = false;

		if ( $_POST['log'] == '' || $_POST['pwd'] == '' ) {
			$error = true;
		}

		// check that were not on the default login page
		if ( ! empty( $referrer ) && ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) && $error ) {

			$referrer =  add_query_arg( array(
				'login' => 'failed',
				'redirect_to' => ( isset( $_POST['redirect_to'] ) && $_POST['redirect_to'] !== '' ) ? urlencode( $_POST['redirect_to'] ): false
			), $referrer );

			wp_redirect( esc_url_raw( $referrer ) );
			exit;

		}

	endif;

}
add_action( 'authenticate', 'wpaam_authenticate_login_form' );

/**
 * Redirects wp_login_form when wrong credentials.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function wpaam_handle_failed_login( $user ) {

	if ( isset( $_SERVER['HTTP_REFERER'] ) && !defined( 'DOING_AJAX' ) ) :
		// check what page the login attempt is coming from
		$referrer = $_SERVER['HTTP_REFERER'];

		// remove previously added query strings
		$referrer = add_query_arg( array(
			'login'       => false,
			'captcha'     => false
		), $referrer );

		// check that were not on the default login page
		if ( ! empty( $referrer ) && ! strstr( $referrer, 'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) && $user != null ) {

			$referrer =  add_query_arg( array(
				'login'       => 'failed',
				'redirect_to' => ( isset( $_POST['redirect_to'] ) && $_POST['redirect_to'] !== '' ) ? urlencode( $_POST['redirect_to'] ): false
			), $referrer );

			wp_redirect( esc_url_raw( $referrer ) );
			exit;

		}
	endif;

}
add_action( 'wp_login_failed', 'wpaam_handle_failed_login' );

/**
 * Displays a message if php version is lower than required one.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function wpaam_php_is_old() {
	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) { ?>
		<div class="error">
			<p><?php echo sprintf( __( 'This plugin requires a minimum PHP Version 5.3 to be installed on your host. <a href="%s" target="_blank">Click here to read how you can update your PHP version</a>.', 'wpaam'), 'http://www.wpupdatephp.com/contact-host/' ); ?></p>
		</div>
	<?php
	}
}
add_action( 'admin_notices', 'wpaam_php_is_old' );


