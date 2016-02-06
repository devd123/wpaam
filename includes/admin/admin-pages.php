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

	//$wpaam_settings_page = add_users_page( __( 'WPAAM Profile Fields Editor', 'wpaam' ), __( 'Profile Fields', 'wpaam' ), 'manage_options', 'wpaam-profile-fields', 'WPAAM_Fields_Editor::editor_page' );
	//$wpaam_settings_page = add_users_page( __( 'WPAAM Edit Field', 'wpaam' ), __( 'Edit Field', 'wpaam' ), 'manage_options', 'wpaam-edit-field', 'WPAAM_Fields_Editor::edit_field_page' );
	$wpaam_settings_page = add_users_page( __('WP Application Manager Settings', 'wpaam'), __('AAM Settings', 'wpaam'), 'manage_options', 'wpaam-settings', 'wpaam_options_page' );
	//$wpaam_settings_page = add_users_page( __('WPAAM Email Editor', 'wpaam'), __('WPAAM Email Editor', 'wpaam'), 'manage_options', 'wpaam-edit-email', 'WPAAM_Emails_Editor::get_emails_editor_page' );
	// $wpaam_settings_page = add_menu_page( __('Advance Accountability Manager', 'wpaam'), __('AAM Manager', 'wpaam'), 'manage_options', 'wpaam-manager', 'wpaam_Getting_Started::getting_started_screen' );
	// $wpaam_settings_page = add_submenu_page( 'wpaam-manager', __('My Account Detials', 'wpaam'), __('My Account', 'wpaam'), 'manage_options', 'wpaam-myaccount', 'wpaam_user_profile' );
	// $wpaam_settings_page = add_submenu_page( 'wpaam-manager', __('Users List Detials', 'wpaam'), __('Users List', 'wpaam'), 'manage_options', 'wpaam-userlist', 'wpaam_Users_List::wpaam_get_user_list' );
	// $wpaam_settings_page = add_submenu_page( 'wpaam-manager', __('Application Manager Settings', 'wpaam'), __('Application Settings', 'wpaam'), 'manage_options', 'wpaam-settings', 'wpaam_options_page' );
	
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
