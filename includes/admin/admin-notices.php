<?php
/**
 * Admin Messages
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin Messages
 *
 * @since 1.0
 * @global $wpaam_options Array of all the wpaam Options
 * @return void
 */
function wpaam_admin_messages() {

	global $wpaam_options;
	$screen = get_current_screen();

	if (  isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true && !wpaam_get_option('custom_passwords') && wpaam_get_option('password_strength') ) {
		add_settings_error( 'wpaam-notices', 'custom-passwords-disabled', __( 'You have enabled the "Minimum Password Strength" option, the "Users custom passwords" is currently disabled and must be enabled for custom passwords to work.', 'wpaam' ), 'error' );
	}

	if (  isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true && !wpaam_get_option('custom_passwords') && wpaam_get_option('login_after_registration') ) {
		add_settings_error( 'wpaam-notices', 'custom-passwords-disabled', __( 'Error: the option "Login after registration" can only work when the option "Users custom passwords" is enabled too.', 'wpaam' ), 'error' );
	}

	if (  isset( $_GET['emails-updated'] ) && $_GET['emails-updated'] == true ) {
		add_settings_error( 'wpaam-notices', 'emails-updated', __( 'Email successfully updated.', 'wpaam' ), 'updated' );
	}

	// Display Errors in plugin settings page
	if ( $screen->base == 'users_page_wpaam-settings' ) {

		// Display error if no core page is setup
		if ( !wpaam_get_option('login_page') || !wpaam_get_option('registration_page') || !wpaam_get_option('account_page') || !wpaam_get_option('profile_page') || !wpaam_get_option('clients_page') || !wpaam_get_option('products_page') || !wpaam_get_option('quotations_page') || !wpaam_get_option('invoices_page') || !wpaam_get_option('creditmemos_page')) {
			add_settings_error( 'wpaam-notices', 'page-missing', __('One or more wpaam pages are not configured.', 'wpaam') . ' ' . sprintf( __('<a href="%s" class="button-primary">Click here to setup your pages</a>', 'wpaam'), admin_url( 'users.php?page=wpaam-settings&tab=general&wpaam_action=install_pages' ) ), 'error' );
		}

		// Display error if wrong permalinks
		if( get_option('permalink_structure' ) == '' ) {
			add_settings_error( 'wpaam-notices', 'permalink-wrong', sprintf(__( 'You must <a href="%s">change your permalinks</a> to anything else other than "default" for profiles to work.', 'wpaam' ), admin_url( 'options-permalink.php' ) ), 'error' );
		}

		if( isset( $_GET['setup_done'] ) && $_GET['setup_done'] == 'true' ) {
			add_settings_error( 'wpaam-notices', 'pages-updated', __( 'Pages setup completed.', 'wpaam' ), 'updated' );
		}

	}

	// Verify if upload folder is writable
	if( isset( $_GET['wpaam_action'] ) && $_GET['wpaam_action'] == 'check_folder_permission' ) {

		$upload_dir = wp_upload_dir();
		if( ! wp_is_writable( $upload_dir['path'] ) ) :
			add_settings_error( 'wpaam-notices', 'permission-error', sprintf( __( 'Your uploads folder in "%s" is not writable. <br/>Avatar uploads will not work, please adjust folder permission.<br/><br/> <a href="%s" class="button" target="_blank">Read More</a>', 'wpaam' ), $upload_dir['basedir'], 'http://www.wpbeginner.com/wp-tutorials/how-to-fix-image-upload-issue-in-wordpress/' ), 'error' );
		else :
			add_settings_error( 'wpaam-notices', 'permission-success', sprintf( __( 'No issues detected.', 'wpaam' ), admin_url( 'users.php?page=wpaam-settings&tab=profile' ) ), 'updated notice is-dismissible' );
		endif;
	}

	// messages for the groups and fields pages
	if( $screen->base == 'users_page_wpaam-profile-fields' ) {

		if( isset( $_GET['message'] ) && $_GET['message'] == 'group_success' ) :
			add_settings_error( 'wpaam-notices', 'group-updated', __( 'Field group successfully updated.', 'wpaam' ), 'updated' );
		endif;

		if( isset( $_GET['message'] ) && $_GET['message'] == 'group_delete_success' ) :
			add_settings_error( 'wpaam-notices', 'group-deleted', __( 'Field group successfully deleted.', 'wpaam' ), 'updated' );
		endif;

		if( isset( $_GET['message'] ) && $_GET['message'] == 'field_saved' ) :
			add_settings_error( 'wpaam-notices', 'field-saved', __( 'Field successfully updated.', 'wpaam' ), 'updated' );
		endif;

	}

	// messages for tools page
	if( $screen->base == 'users_page_wpaam-tools' ) {

		if( isset( $_GET['message'] ) && $_GET['message'] == 'settings_imported' ) :
			add_settings_error( 'wpaam-notices', 'settings-imported', __( 'Settings successfully imported.', 'wpaam' ), 'updated' );
		endif;

	}

	settings_errors( 'wpaam-notices' );

}
add_action( 'admin_notices', 'wpaam_admin_messages' );
