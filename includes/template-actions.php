<?php
/**
 * Plugin Template Actions
 * This file holds all the template actions
 * that have effects on the templating system of the plugin.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Action to display helper links.
 *
 * @since 1.0.0
 * @access public
 * @param string  $login    yes/no
 * @param string  $register yes/no
 * @param string  $password yes/no
 * @return void
 */
function wpaam_add_links_to_forms( $login, $register, $password ) {

	get_wpaam_template( 'helper-links.php',
		array(
			'login'    => esc_attr( $login ),
			'register' => esc_attr( $register ),
			'password' => esc_attr( $password )
		)
	);

}
add_action( 'wpaam_do_helper_links', 'wpaam_add_links_to_forms', 10, 3 );

/**
 * Add helper links to the password form.
 *
 * @since 1.0.0
 * @access public
 * @param array   $atts Settings of the shortcode.
 * @return void
 */
function wpaam_add_helper_links( $atts ) {

	$login_link    = $atts['login_link'];
	$psw_link      = $atts['psw_link'];
	$register_link = $atts['register_link'];

	// Display helper links
	do_action( 'wpaam_do_helper_links', $login_link, $register_link, $psw_link );

}
add_action( 'wpaam_after_password_form_template', 'wpaam_add_helper_links', 10, 1 );
add_action( 'wpaam_after_register_form_template', 'wpaam_add_helper_links', 10, 1 );








///////////////////////////////////////////// Account Tab //////////////////////////////////////////

// Adds tabs navigation on top of the account edit page.
function wpaam_add_account_tabs( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( "account-tabs.php", array( 'tabs'  => wpaam_get_account_page_tabs(), 'current_tab' => $current_tab, 'all_tabs' => $all_tabs ) );

}
add_action( 'wpaam_before_account', 'wpaam_add_account_tabs', 10, 6 );

// Display content of the first tab into the account page.
function wpaam_show_account_edit_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( 'forms/account-form.php',
		array(
			'atts'    => $atts,
			'form'    => $form,
			'fields'  => $fields,
			'user_id' => $user_id
		)
	);

}
add_action( 'wpaam_account_tab_details', 'wpaam_show_account_edit_form', 10, 6 );

// Second tab of account
function wpaam_show_psw_update_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	echo WPAAM()->forms->get_form( 'update-password' );

}
add_action( 'wpaam_account_tab_change-password', 'wpaam_show_psw_update_form', 10, 6 );

// Third tab of account
function wpaam_show_account_payments_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {
	
	echo WPAAM()->forms->get_form( 'payments' );

}
add_action( 'wpaam_account_tab_payments', 'wpaam_show_account_payments_form', 10, 6 );


// Fourth tab of account tab
function wpaam_show_account_others_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	echo WPAAM()->forms->get_form( 'other-settings' );

}
add_action( 'wpaam_account_tab_others', 'wpaam_show_account_others_form', 10, 6 );

/////////////////////////////////////////////////////////////////// Products Tabs ///////////////////////////////////

// Adds tabs navigation on top of the products page.
function wpaam_add_products_tabs( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( "products-tabs.php", array( 'tabs'  => wpaam_get_products_page_tabs(), 'current_tab' => $current_tab, 'all_tabs' => $all_tabs ) );

}
add_action( 'wpaam_before_products', 'wpaam_add_products_tabs', 10, 6 );

// First tab of the products page.
function wpaam_show_products_list( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( 'products-list.php',
		array(
			'atts'    => $atts,
			'fields'  => $fields,
			'user_id' => $user_id
		)
	);
}
add_action( 'wpaam_products_tab_list', 'wpaam_show_products_list', 10, 6 );

// Second tab of the products page.
function wpaam_show_products_edit_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {
	
	echo WPAAM()->forms->get_form( 'edit-product' );
}
add_action( 'wpaam_products_tab_edit', 'wpaam_show_products_edit_form', 10, 6 );

////////////////////////////////////////////////////////////////// Clients Tabs ///////////////////////////////

// Adds tabs navigation on top of the clients page.
function wpaam_add_clients_tabs( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( "clients-tabs.php", array( 'tabs'  => wpaam_get_clients_page_tabs(), 'current_tab' => $current_tab, 'all_tabs' => $all_tabs ) );

}
add_action( 'wpaam_before_clients', 'wpaam_add_clients_tabs', 10, 6 );

// First tab of the clients page.
function wpaam_show_clients_list( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( 'clients-list.php',
		array(
			'atts'    => $atts,
			'fields'  => $fields,
			'user_id' => $user_id
		)
	);
}
add_action( 'wpaam_clients_tab_list', 'wpaam_show_clients_list', 10, 6 );

// Second tab of the products page.
function wpaam_show_clients_edit_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {
	
	echo WPAAM()->forms->get_form( 'edit-client' );
}
add_action( 'wpaam_clients_tab_edit', 'wpaam_show_clients_edit_form', 10, 6 );

////////////////////////////////////////////////////////////////// Quotations Tabs ///////////////////////////////

// Adds tabs navigation on top of the quotations page.
function wpaam_add_quotations_tabs( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( "quotations-tabs.php", array( 'tabs'  => wpaam_get_quotations_page_tabs(), 'current_tab' => $current_tab, 'all_tabs' => $all_tabs ) );

}
add_action( 'wpaam_before_quotations', 'wpaam_add_quotations_tabs', 10, 6 );

// First tab of the quotations page.
function wpaam_show_quotations_list( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {

	get_wpaam_template( 'quotations-list.php',
		array(
			'atts'    => $atts,
			'fields'  => $fields,
			'user_id' => $user_id
		)
	);
}
add_action( 'wpaam_quotations_tab_list', 'wpaam_show_quotations_list', 10, 6 );

// Second tab of the quotations page.
function wpaam_show_quotations_edit_form( $current_tab, $all_tabs, $form, $fields, $user_id, $atts ) {
	
	echo WPAAM()->forms->get_form( 'edit-quotation' );
}
add_action( 'wpaam_quotations_tab_edit', 'wpaam_show_quotations_edit_form', 10, 6 );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function wpaam_show_failed_login_message() {

	if( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
		$args = array(
				'id'   => 'wpaam-login-failed',
				'type' => 'error',
				'text' => __( 'Login failed: You have entered incorrect login details, please try again.', 'wpaam' )
		);
		$warning = wpaam_message( $args, true );
	}

}
add_action( 'wpaam_before_login_form', 'wpaam_show_failed_login_message' );

// update user profile setting form 
function wpaam_profile_update_messages() {

	if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'success' ) :
		$args = array(
			'id'   => 'wpaam-profile-updated',
			'type' => 'success',
			'text' => apply_filters( 'wpaam_account_update_success_message', __( 'Profile successfully updated.', 'wpaam' ) )
		);
		wpaam_message( $args );
	endif;
	if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'error' ) :
		$args = array(
			'id'   => 'wpaam-profile-error',
			'type' => 'error',
			'text' => apply_filters( 'wpaam_account_update_error_message', __( 'Something went wrong.', 'wpaam' ) )
		);
		wpaam_message( $args );
	endif;
}
add_action( 'wpaam_before_account_form', 'wpaam_profile_update_messages' );


function wpaam_show_add_client_form(){
	get_wpaam_template( 'forms/add-client-form.php',
		array(
			'atts'    => $atts,
			'form'    => $form,
			'fields'  => $fields,
			'user_id' => $user_id
		)
	);
}
add_action( 'wpaam_add_client_form', 'wpaam_show_add_client_form' );


