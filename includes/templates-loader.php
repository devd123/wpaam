<?php
/**
 * Templates Manager
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get and include template files.
 *
 * @param mixed   $template_name
 * @param array   $args          (default: array())
 * @param string  $template_path (default: '')
 * @param string  $default_path  (default: '')
 * @return void
 */
function get_wpaam_template( $template_name, $args = array(), $template_path = 'wpaam', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}
	include locate_wpaam_template( $template_name, $template_path, $default_path );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *  yourtheme  / $template_path / $template_name
 *  yourtheme  / $template_name
 *  $default_path / $template_name
 *
 * @param string  $template_name
 * @param string  $template_path (default: 'wpaam')
 * @param string|bool $default_path  (default: '') False to not load a default
 * @return string
 */
function locate_wpaam_template( $template_name, $template_path = 'wpaam', $default_path = '' ) {
	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template
	if ( ! $template && $default_path !== false ) {
		$default_path = $default_path ? $default_path : WPAAM_PLUGIN_DIR . '/templates/';
		if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
			$template = trailingslashit( $default_path ) . $template_name;
		}
	}

	// Return what we found
	return apply_filters( 'wpaam_locate_template', $template, $template_name, $template_path );
}

/**
 * Get template part (for templates in loops).
 *
 * @param string  $slug
 * @param string  $name          (default: '')
 * @param string  $template_path (default: 'wpaam')
 * @param string|bool $default_path  (default: '') False to not load a default
 */
function get_wpaam_template_part( $slug, $name = '', $template_path = 'wpaam', $default_path = '' ) {
	if ( ! $template_path )
		$template_path = 'wprm';
	if ( ! $default_path )
		$default_path = WPAAM_PLUGIN_DIR . '/templates/';

	$template = '';

	if ( $name ) {
		$template = locate_wpaam_template( "{$slug}-{$name}.php", $template_path, $default_path );
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/wpaam/slug.php
	if ( ! $template ) {
		$template = locate_wpaam_template( "{$slug}.php", $template_path, $default_path );
	}

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Returns the path to the wpaam templates directory
 *
 * @since 1.0.2
 * @return string
 */
function wpaam_get_templates_dir() {
	return WPAAM_PLUGIN_DIR . 'templates';
}

/**
 * Returns the URL to the wpaam templates directory
 *
 * @since 1.0.2
 * @return string
 */
function wpaam_get_templates_url() {
	return WPAAM_PLUGIN_URL . 'templates';
}
