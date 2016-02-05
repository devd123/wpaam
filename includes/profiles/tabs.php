<?php
/**
 * User profiles tabs.
 * Displays the tabs and content of each tab into the profile page.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Load profile tabs.
 * 
 * @since 1.0.0
 * @access public
 * @param object $user_data holds WP_User object
 * @return void
 */
function wpaam_load_profile_tabs( $user_data ) {

	$output = get_wpaam_template( 'profile/profile-tabs.php', array( 'user_data' => $user_data, 'tabs' => wpaam_get_user_profile_tabs() ) );

	echo $output;

}
add_action( 'wpaam_after_profile_details', 'wpaam_load_profile_tabs', 10 );

/**
 * Load content for the "overview" tab.
 * 
 * @since 1.0.0
 * @access public
 * @param object $user_data holds WP_User object
 * @param array $tabs holds all the registered tabs
 * @param string $current_tab_slug the slug of the current tab
 * @return void
 */
function wpaam_profile_tab_content_about( $user_data, $tabs, $current_tab_slug ) {

	echo get_wpaam_template( 'profile/profile-overview.php', array( 'user_data' => $user_data, 'tabs' => $tabs, 'slug' => $current_tab_slug ) );

}
add_action( 'wpaam_profile_tab_content_about', 'wpaam_profile_tab_content_about', 10, 3 );

/**
 * Load content for the "posts" tab.
 * 
 * @since 1.0.0
 * @access public
 * @param object $user_data holds WP_User object
 * @param array $tabs holds all the registered tabs
 * @param string $current_tab_slug the slug of the current tab
 * @return void
 */
function wpaam_profile_tab_content_posts( $user_data, $tabs, $current_tab_slug ) {

	echo get_wpaam_template( 'profile/profile-posts.php', array( 'user_data' => $user_data, 'tabs' => $tabs, 'slug' => $current_tab_slug ) );

}
add_action( 'wpaam_profile_tab_content_posts', 'wpaam_profile_tab_content_posts', 11, 3 );

/**
 * Load content for the "comments" tab.
 * 
 * @since 1.0.0
 * @access public
 * @param object $user_data holds WP_User object
 * @param array $tabs holds all the registered tabs
 * @param string $current_tab_slug the slug of the current tab
 * @return void
 */
function wpaam_profile_tab_content_comments( $user_data, $tabs, $current_tab_slug ) {

	echo get_wpaam_template( 'profile/profile-comments.php', array( 'user_data' => $user_data, 'tabs' => $tabs, 'slug' => $current_tab_slug ) );

}
add_action( 'wpaam_profile_tab_content_comments', 'wpaam_profile_tab_content_comments', 12, 3 );