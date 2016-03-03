<?php
/**
** Shortcodes
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Shortcodes Class
 * @since 1.0.0
 */
class WPAAM_Shortcodes {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */


	public function __construct() {


		add_filter( 'widget_text', 'do_shortcode' );
		add_shortcode('wpaam_login_form' , array( $this , 'wpaam_login_form'));
		add_shortcode( 'wpaam_logout', array( $this, 'wpaam_logout' ) );
		add_shortcode( 'wpaam_login', array( $this, 'wpaam_login' ) );
		add_shortcode( 'wpaam_register', array( $this, 'wpaam_registration' ) );
		add_shortcode( 'wpaam_password_recovery', array( $this, 'wpaam_password' ) );
		add_shortcode( 'wpaam_account', array( $this, 'wpaam_account' ) );
		add_shortcode( 'wpaam_profile', array( $this, 'wpaam_profile' ) );
		add_shortcode('wpaam_clients' , array( $this , 'wpaam_clients'));
		add_shortcode('wpaam_products' , array( $this , 'wpaam_products'));
		add_shortcode('wpaam_quotations' , array( $this , 'wpaam_quotations'));
		add_shortcode('wpaam_invoices' , array( $this , 'wpaam_invoices'));
	

	}

	/**
	 * Login Form Shortcode
	*/
	public function wpaam_login_form( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'id'             => '',
			'label_username' => '',
			'label_password' => '',
			'label_remember' => '',
			'label_log_in'   => '',
			'login_link'     => '',
			'psw_link'       => '',
			'register_link'  => ''
		), $atts ) );

		// Set default values if options missing
		if(empty($id))
			$id = 'wpaam_loginform';
		if(empty($label_username))
			$label_username = wpaam_get_username_label();
		if(empty($label_password))
			$label_password = __('Password', 'wpaam');
		if(empty($label_remember))
			$label_remember = __('Remember Me', 'wpaam');
		if(empty($label_log_in))
			$label_log_in = __('Login', 'wpaam');

		$args = array(
			'echo'           => true,
			'redirect'       => wpaam_get_login_redirect_url(),
			'form_id'        => esc_attr($id),
			'label_username' => esc_attr($label_username),
			'label_password' => esc_attr($label_password),
			'label_remember' => esc_attr($label_remember),
			'label_log_in'   => esc_attr($label_log_in),
			'id_username'    => esc_attr($id).'user_login',
			'id_password'    => esc_attr($id).'user_pass',
			'id_remember'    => esc_attr($id).'rememberme',
			'id_submit'      => esc_attr($id).'wp-submit',
			'login_link'     => esc_attr($login_link),
			'psw_link'       => esc_attr($psw_link),
			'register_link'  => esc_attr($register_link)
		);

		ob_start();

		// Show already logged in message
		if( is_user_logged_in() ) :

			get_wpaam_template( 'already-logged-in.php',
				array(
					'args' => $args,
					'atts' => $atts,
				)
			);

		// Show login form if not logged in
		else :

			get_wpaam_template( 'forms/login-form.php',
				array(
					'args' => $args,
					'atts' => $atts,
				)
			);

			// Display helper links
			do_action( 'wpaam_do_helper_links', $login_link, $register_link, $psw_link );

		endif;

		$output = ob_get_clean();

		return $output;

	}

	/**
	 * Render Logout Shortcode
	*/
	public function wpaam_logout( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'redirect' => '',
			'label'    => __('Logout', 'wpaam')
		), $atts ) );

		$output = null;

		if( is_user_logged_in() )
			$output = sprintf( __('<a href="%s">%s</a>', 'wpaam'), wpaam_logout_url( $redirect ), esc_attr( $label ) );

		return $output;

	}

	/**
	 * Login Form Shortcode
	**/
	public function wpaam_login( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'redirect' => '',
			'label'    => esc_html__( 'Login', 'wpaam' )
		), $atts ) );

		$url = wpaam_get_core_page_url( 'login' );

		if( ! empty( $redirect ) ) {
			$url = add_query_arg( array( 'redirect_to' => urlencode( $redirect ) ), $url );
		}

		$output = '<a href="'. esc_url( $url ) .'" class="wpaam-login-link">'.esc_html( $label ).'</a>';

		return $output;

	}

	/**
	 * Registration form shortcode
	*/
	public function wpaam_registration( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'form_id'       => 'default_registration_form',
			'login_link'    => '',
			'psw_link'      => '',
			'register_link' => ''
		), $atts ) );

		// Set default values
		if( !array_key_exists('form_id', $atts) || empty($atts['form_id']) )
			$atts['form_id'] = 'default_registration_form';

		return WPAAM()->forms->get_form( 'register', $atts );
		//print_r($atts); die("deadline");
	}

	/**
	 * Password Recovery Form Shortcode
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $output shortcode output
	 */
	public function wpaam_password( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'form_id'       => 'default_password_form',
			'login_link'    => '',
			'psw_link'      => '',
			'register_link' => ''
		), $atts ) );

		// Set default values
		if( !array_key_exists('form_id', $atts) || empty($atts['form_id']) )
			$atts['form_id'] = 'default_password_form';

		return WPAAM()->forms->get_form( 'password', $atts );

	}

	// Set accoount page view and settings
	public function wpaam_account( $atts, $content=null ) {

		$user = wp_get_current_user();
		// Get the tabs
		$current_account_tab = wpaam_get_current_account_tab();
		$all_tabs = array_keys( wpaam_get_account_page_tabs() );

		// Display template
		if ( is_user_logged_in() ) :

			get_wpaam_template( 'account.php',
				array(
					'atts'        => $atts,
					'user_id'     => $user->ID,
					'current_tab' => $current_account_tab,
					'all_tabs'    => $all_tabs
				)
			);
	
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;
		
	}

	// Set profile page view and settings
	public function wpaam_profile( $atts, $content=null ) {

		ob_start();

		if( wpaam_can_access_profile() )
			get_wpaam_template( 'profile.php', array(
					'user_data' => wpaam_get_user_by_data(),
			)
		);

		$output = ob_get_clean();

		return $output;

	}

	// Set clients page view and settings
	public function wpaam_clients($atts, $content = null) {
		
		$user = wp_get_current_user();
		// Get the tabs
		$current_clinet_tab = wpaam_get_current_clients_tab();
		$all_tabs = array_keys( wpaam_get_clients_page_tabs() );

		// Display template
		if ( is_user_logged_in() && current_user_can( 'edit_product' ) ) :

			get_wpaam_template( 'clients.php',
				array(
					'atts'        => $atts,
					'user_id'     => $user->ID,
					'current_tab' => $current_clinet_tab,
					'all_tabs'    => $all_tabs
				)
			);
		
		elseif (is_user_logged_in() && !current_user_can( 'edit_product' )) :
			echo "You are not allowed to see this page";
		
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;
	
	}

	// Set products page view and settings
	public function wpaam_products( $atts = array()){
		
		$user = wp_get_current_user();
		// Get the tabs
		$current_tab = wpaam_get_current_products_tab();
		$all_tabs = array_keys( wpaam_get_products_page_tabs() );

		// Display template
		if ( is_user_logged_in() && current_user_can( 'edit_product' ) ) :

			get_wpaam_template( 'products.php',
				array(
					'atts'        => $atts,
					'user_id'     => $user->ID,
					'current_tab' => $current_tab,
					'all_tabs'    => $all_tabs
				)
			);
		elseif (is_user_logged_in() && !current_user_can( 'edit_product' )) :
			echo "You are not allowed to see this page";
		
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;
	}

	// Set quotations page view and settings
	public function wpaam_quotations($atts, $content = null) {
		
		$user = wp_get_current_user();
		//Get the tabs
		$current_quotations_tab = wpaam_get_current_quotations_tab();
		$all_tabs_qt = array_keys( wpaam_get_quotations_page_tabs() );

		// Display template
		if ( is_user_logged_in() && current_user_can( 'edit_quotation' ) && current_user_can( 'publish_quotation' ) ) : 

			get_wpaam_template( 'quotations.php',
				array(
					'atts'        => $atts,
					'user_id'     => $user->ID,
					'current_tab' => $current_quotations_tab,
					'all_tabs'    => $all_tabs_qt
				)
			);
		
		// Display Quotation list for client's
		elseif (is_user_logged_in() && current_user_can('aam_client')) :
		 	get_wpaam_template( 'client_quotations.php',
				array('user_id' => $user->ID)
			);
		// Show login form if not logged in
		else :
			echo wpaam_login_form();

		endif;
		
	}

	// Set invoices page view and settings
	public function wpaam_invoices($atts, $content = null) {
		
		$user = wp_get_current_user();
		//echo $user->roles[0]; die;
		//Get the tabs
		$current_invoices_tab = wpaam_get_current_invoices_tab();
		$all_tabs_inv = array_keys( wpaam_get_invoices_page_tabs() );

		// Display template for aam user's 
		if ( is_user_logged_in() && current_user_can( 'edit_invoice' ) && current_user_can( 'publish_invoice' ) ) : 

			get_wpaam_template( 'invoices.php',
				array(
					'atts'        => $atts,
					'form'		  => 'edit-invoice',
					'user_id'     => $user->ID,
					'current_tab' => $current_invoices_tab,
					'all_tabs'    => $all_tabs_inv
				)
			);
	
		// Display Invoices list for client's
		elseif (is_user_logged_in() && current_user_can('aam_client')) :
		 	get_wpaam_template( 'client_invoices.php',
				array('user_id' => $user->ID)
			);
		// Show login form if not logged in
		else :
			echo wpaam_login_form();

		endif;
		
	}

}

new WPAAM_Shortcodes;
