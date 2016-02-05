<?php
/**
 * Handles loading of css and js files.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Loads the plugin admin assets files
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_admin_cssjs() {

	$js_dir  = WPAAM_PLUGIN_URL . 'assets/js/';
	$css_dir = WPAAM_PLUGIN_URL . 'assets/css/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Styles & scripts
	wp_register_style( 'wpaam-admin', $css_dir . 'wp_user_manager' . $suffix . '.css', WPAAM_VERSION );
	wp_register_style( 'wpaam-admin-general', WPAAM_PLUGIN_URL . 'assets/css/wp_user_manager_admin_general.css', WPAAM_VERSION );
	wp_register_style( 'wpaam-select2', WPAAM_PLUGIN_URL . 'assets/select2/css/select2.css', WPAAM_VERSION );
	wp_register_script( 'wpaam-select2', WPAAM_PLUGIN_URL . 'assets/select2/js/select2.min.js', 'jQuery', WPAAM_VERSION, true );
	wp_register_script( 'wpaam-serializeJSON', WPAAM_PLUGIN_URL . 'assets/js/vendor/jquery.serializeJSON.js', 'jQuery', WPAAM_VERSION, true );
	wp_register_script( 'wpaam-admin-js', $js_dir . 'wp_user_manager_admin' . $suffix . '.js', 'jQuery', WPAAM_VERSION, true );

	// Enquery styles and scripts anywhere needed
	wp_enqueue_style( 'wpaam-admin-general' );

	// Enqueue styles & scripts on admin page only
	$screen = get_current_screen();

	wp_enqueue_script( 'wpaam-admin-js' );

	// Load styles only on required pages.
	if ( $screen->base == 'users_page_wpaam-settings' || $screen->id == 'wpaam_directory' || $screen->base == 'users_page_wpaam-edit-field' || $screen->base == 'users_page_wpaam-profile-fields' ):

		wp_enqueue_script( 'wpaam-select2' );
		wp_enqueue_style( 'wpaam-admin' );
		wp_enqueue_style( 'wpaam-select2' );
		wp_enqueue_script( 'accordion' );
		wp_enqueue_media();

		if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'default_fields' && $screen->base == 'users_page_wpaam-settings' )
			wp_enqueue_script( 'jquery-ui-sortable' );

		if ( $screen->base == 'users_page_wpaam-custom-fields-editor' )
			wp_enqueue_script( 'wpaam-serializeJSON' );

	endif;

	// Backend JS Settings
	wp_localize_script( 'wpaam-admin-js', 'wpaam_admin_js', array(
		'ajax'          => admin_url( 'admin-ajax.php' ),
		'confirm'       => __( 'Are you sure you want to do this? This action cannot be reversed.', 'wpaam' ),
		'use_this_file' => __( 'Use This File', 'wpaam' ),
		'upload_title'  => __( 'Upload or select a file', 'wpaam' ),
	) );

}
add_action( 'admin_enqueue_scripts', 'wpaam_admin_cssjs' );


/**
 * Loads the plugin frontend assets files
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_frontend_cssjs() {

	$js_dir  = WPAAM_PLUGIN_URL . 'assets/js/';
	$css_dir = WPAAM_PLUGIN_URL . 'assets/css/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Default URL
	$url = $css_dir . 'wp_user_manager_frontend' . $suffix . '.css';

	$file          = 'wp_user_manager_frontend' . $suffix . '.css';
	$templates_dir = 'wpaam/';
	$child_theme_style_sheet    = trailingslashit( get_stylesheet_directory() ) . $templates_dir . $file;
	$child_theme_style_sheet_2  = trailingslashit( get_stylesheet_directory() ) . $templates_dir . 'wp_user_manager_frontend.css';
	$parent_theme_style_sheet   = trailingslashit( get_template_directory()   ) . $templates_dir . $file;
	$parent_theme_style_sheet_2 = trailingslashit( get_template_directory()   ) . $templates_dir . 'wp_user_manager_frontend.css';
	$wpaam_plugin_style_sheet     = trailingslashit( wpaam_get_templates_dir()    ) . $file;

	// Look in the child theme directory first, followed by the parent theme, followed by the wpaam core templates directory
	// Also look for the min version first, followed by non minified version, even if SCRIPT_DEBUG is not enabled.
	// This allows users to copy just wp_user_manager_frontend.css to their theme
	if ( file_exists( $child_theme_style_sheet ) || ( ! empty( $suffix ) && ( $nonmin = file_exists( $child_theme_style_sheet_2 ) ) ) ) {
		if( ! empty( $nonmin ) ) {
			$url = trailingslashit( get_stylesheet_directory_uri() ) . $templates_dir . 'wp_user_manager_frontend.css';
		} else {
			$url = trailingslashit( get_stylesheet_directory_uri() ) . $templates_dir . $file;
		}
	} elseif ( file_exists( $parent_theme_style_sheet ) || ( ! empty( $suffix ) && ( $nonmin = file_exists( $parent_theme_style_sheet_2 ) ) ) ) {
		if( ! empty( $nonmin ) ) {
			$url = trailingslashit( get_template_directory_uri() ) . $templates_dir . 'wp_user_manager_frontend.css';
		} else {
			$url = trailingslashit( get_template_directory_uri() ) . $templates_dir . $file;
		}
	} elseif ( file_exists( $wpaam_plugin_style_sheet ) || file_exists( $wpaam_plugin_style_sheet ) ) {
		$url = trailingslashit( wpaam_get_templates_url() ) . $file;
	}

	// Styles & scripts registration
	wp_register_script( 'wpaam-frontend-js', $js_dir . 'wp_user_manager' . $suffix . '.js', array( 'jquery' ), WPAAM_VERSION, true );
	wp_register_style( 'wpaam-frontend-css', $url , WPAAM_VERSION );

	// Enqueue everything
	wp_enqueue_script( 'jQuery' );
	wp_enqueue_script( 'wpaam-frontend-js' );

	// Allows developers to disable the frontend css in case own file is needed.
	if ( !defined( 'wpaam_DISABLE_CSS' ) )
		wp_enqueue_style( 'wpaam-frontend-css' );

	// Display password meter only if enabled
	if ( wpaam_get_option( 'display_password_meter_registration' ) ) :

		wp_enqueue_script( 'password-strength-meter' );

		wp_localize_script( 'password-strength-meter', 'pwsL10n', array(
			'empty'  => __( 'Strength indicator', 'wpaam' ),
			'short'  => __( 'Very weak', 'wpaam' ),
			'bad'    => __( 'Weak', 'wpaam' ),
			'good'   => _x( 'Medium', 'password strength', 'wpaam' ),
			'strong' => __( 'Strong', 'wpaam' )
		) );

	endif;

	// Frontend jS Settings
	wp_localize_script( 'wpaam-frontend-js', 'wpaam_frontend_js', array(
		'ajax'                 => admin_url( 'admin-ajax.php' ),
		'checking_credentials' => __( 'Checking credentials...', 'wpaam' ),
		'pwd_meter'            => wpaam_get_option( 'display_password_meter_registration' ),
		'disable_ajax'         => wpaam_get_option( 'disable_ajax' )
	) );

}
add_action( 'wp_enqueue_scripts', 'wpaam_frontend_cssjs' );
