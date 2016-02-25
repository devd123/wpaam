<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates the admin submenu pages under the Users menu and assigns their
 * links to global variables
 *
 * @since 1.0.0
 * @global $wpaam_settings_page
 * @return void
 */
function wpaam_add_options_link() {

	global $wpaam_settings_page;

	$wpaam_settings_page = add_users_page(  __( 'VAT Manager', 'wpaam' ), __( 'VAT Values ', 'wpaam' ), 'manage_options', 'wpaam_show_vats', 'wpaam_render_vats' );
	$wpaam_settings_page = add_users_page(  __( 'Product Manager', 'wpaam' ), __( 'Products', 'wpaam' ), 'manage_options', 'wpaam_show_products', 'wpaam_render_products' );
	$wpaam_settings_page = add_users_page(  __( 'Quotation Manager', 'wpaam' ), __( 'Quotations', 'wpaam' ), 'manage_options', 'wpaam_show_quotations', 'wpaam_render_quotations' );
	$wpaam_settings_page = add_users_page(  __( 'Invoices Manager', 'wpaam' ), __( 'Invoices', 'wpaam' ), 'manage_options', 'wpaam_show_invoices', 'wpaam_render_invoices' );
	$wpaam_settings_page = add_users_page( __('WPAAM Settings', 'wpaam'), __('WAAM Settings', 'wpaam'), 'manage_options', 'wpaam-settings', 'wpaam_options_page' );
	//$wpaam_settings_page = add_users_page( __('Vat Settings', 'wpaam'), __('Vat Settings', 'wpaam'), 'manage_options', 'wpaam-vat-setting', 'WPAAM_Vat_Values::add_vat_values' );
	
	
	
	
	add_action( 'admin_head', 'wpaam_hide_admin_pages' );

}
add_action( 'admin_menu', 'wpaam_add_options_link', 10 );


/**
 * Removes admin menu links that are masked.
 * @return      void
 */
function wpaam_hide_admin_pages() {
	remove_submenu_page( 'users.php', 'wpaam-edit-email' );
	remove_submenu_page( 'users.php', 'wpaam-edit-field' );
}
