<?php
// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Load wpaam file
include_once( 'wp-aam.php' );

global $wpdb;

// Delete post type contents
$wpaam_post_types = array( 'wpaam_directory' );

foreach ( $wpaam_post_types as $post_type ) {
	$items = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );
	if ( $items ) {
		foreach ( $items as $item ) {
			wp_delete_post( $item, true);
		}
	}
}

// Delete created pages
$wpaam_pages = array( 'login_page', 'registration_page', 'account_page', 'profile_page', 'settings_page', 'clients_page', 'products_page', 'quotations_page', 'invoices_page', 'creditmemos_page' );
foreach ( $wpaam_pages as $p ) {
	$page = wpaam_get_option( $p, false );
	if ( $page ) {
		wp_delete_post( $page, false );
	}
}

// Delete options
delete_option( 'wpaam_settings' );
delete_option( 'wpaam_emails' );
delete_option( 'wpaam_permalink' );
delete_option( 'wpaam_custom_fields' );
delete_option( 'wpaam_version' );
delete_option( 'wpaam_version_upgraded_from' );
delete_transient( '_wpaam_activation_redirect' );
delete_option( 'wpaam_activation_date' );

// Remove user custom roles 
remove_role( 'aam_user');
remove_role( 'aam_client');

// Remove all database tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "wpaam_fields" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "wpaam_field_groups" );