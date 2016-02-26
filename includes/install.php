<?php
/**
 * Installation Functions
 *
 * @package     wp-aam
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post types,
 * flushing rewrite rules and also populates the settings fields.
 * After successful install, the user is redirected to the wpaam Welcome screen.
 *
 * @since 1.0
 * @global $wpaam_options
 * @global $wp_version
 * @return void
 */
function wpaam_install() {

	global $wpaam_options, $wp_version;

	// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
	if ( version_compare(PHP_VERSION, '5.3', '<') ) {
		deactivate_plugins( plugin_basename( WPAAM_PLUGIN_FILE ) );
		wp_die( sprintf( __( 'This plugin requires a minimum PHP Version 5.3 to be installed on your host. <a href="%s" target="_blank">Click here to read how you can update your PHP version</a>.', 'wpaam'), 'http://www.wpupdatephp.com/contact-host/' ) . '<br/><br/>' . '<small><a href="'.admin_url().'">'.__('Back to your website.', 'wpaam').'</a></small>' );
	}
	
	// Install default pages
	wpaam_generate_pages();
	// Setup default emails content
	$default_emails = array();

	// Delete the option
	delete_option( 'wpaam_emails' );

	// Let's set some default options
	wpaam_update_option( 'enable_honeypot', true ); // enable antispam honeypot by default.
	wpaam_update_option( 'email_template', 'none' ); // set no template as default.
	wpaam_update_option( 'from_email', get_option( 'admin_email' ) ); // set admin email as default.
	wpaam_update_option( 'from_name', get_option( 'blogname' ) ); // set blogname as default mail from.
	wpaam_update_option( 'guests_can_view_profiles', true );
	wpaam_update_option( 'members_can_view_profiles', true );
	update_option( 'users_can_register', true ); // Enable registrations.
	update_option( 'wpaam_permalink', 'user_id' ); // Set default user permalinks

	// Clear the permalinks
	flush_rewrite_rules();

	// Create groups table and 1st group
	wpaam_install_groups();

	// Create fields table and primary fields
	wpaam_install_fields();


	wpaam_install_devemail();

	// Store plugin installation date
    add_option( 'wpaam_activation_date', strtotime( "now" ) );

	// Add Upgraded From Option
	$current_version = get_option( 'wpaam_version' );
	if ( $current_version ) {
		update_option( 'wpaam_version_upgraded_from', $current_version );
	}

	// Update current version
	update_option( 'wpaam_version', WPAAM_VERSION );
	update_option( 'wpaam_did_121_update', true );

	// Add the transient to redirect
	set_transient( '_wpaam_activation_redirect', true, 30 );

}
register_activation_hook( WPAAM_PLUGIN_FILE, 'wpaam_install' );

