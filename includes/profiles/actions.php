<?php
/**
 * User profiles actions.
 * Holds templating actions to display various components of the layout.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Force 404 error if user or tabs do not exist.
 *
 * @since 1.0.0
 * @access public
 * @return void
 */
function wpaam_profile_force_404_error() {

	// Bail if not on the profile page
	if( !is_page( wpaam_get_core_page_id('profile') ) )
		return;

	// Bail if viewing single profile only and not another user profile
	if( !wpaam_is_single_profile() )
		return;

	// Trigger if tab is set and does not exist
	if( wpaam_get_current_profile_tab() !== null && !wpaam_profile_tab_exists( wpaam_get_current_profile_tab() ) )
		wpaam_trigger_404();

	// Trigger if profile is set and does not exist
	if( wpaam_is_single_profile() && !wpaam_user_exists( wpaam_is_single_profile(), get_option( 'wpaam_permalink' ) ) )
		wpaam_trigger_404();

}
add_action( 'template_redirect', 'wpaam_profile_force_404_error' );

/**
 * Display user name in profile.php template.
 *
 * @since 1.0.0
 * @param object $user_data holds WP_User object
 * @access public
 * @return void
 */
function wpaam_profile_show_user_name( $user_data ) {

	$output = '<div class="wpaam-user-display-name">';
		$output .= '<a href="'. wpaam_get_user_profile_url( $user_data ) .'">'. esc_attr( $user_data->display_name ) .'</a>';

		// Show edit account only when viewing own profile
		if( $user_data->ID == get_current_user_id() )
			$output .= '<small><a href="'. wpaam_get_core_page_url('account') .'" class="wpaam-profile-account-edit">'. __(' (Edit Account)', 'wpaam') .'</a></small>';

	$output .= '</div>';

	echo $output;

}
add_action( 'wpaam_main_profile_details', 'wpaam_profile_show_user_name', 10 );

/**
 * Display user description in profile.php template.
 *
 * @since 1.0.0
 * @param object $user_data holds WP_User object
 * @access public
 * @return void
 */
function wpaam_profile_show_user_description( $user_data ) {

	$output = '<div class="wpaam-user-description">';
		$output .= wpautop( esc_attr( get_user_meta( $user_data->ID, 'description', true) ), true );
	$output .= '</div>';

	echo $output;

}
add_action( 'wpaam_main_profile_details', 'wpaam_profile_show_user_description', 10 );

/**
 * Display user name in profile.php template.
 *
 * @since 1.0.0
 * @param object $user_data holds WP_User object
 * @access public
 * @return void
 */
function wpaam_profile_show_user_links( $user_data ) {

	$output = get_wpaam_template( 'profile/profile-links.php', array( 'user_data' => $user_data ) );

	echo $output;

}
add_action( 'wpaam_secondary_profile_details', 'wpaam_profile_show_user_links', 10 );
