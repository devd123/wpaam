<?php
/**
 * wpaam Template: Profile tabs.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Get current tab slug
$current_tab_slug = wpaam_get_current_profile_tab();

// Get keys of the tabs
// Needed to show the first tab if on /profile/
$all_tabs = array_keys( $tabs );

?>

<div class="wpaam-profile-tabs-holder">

	<!-- Loop through each available tab -->
	<ul class="wpaam-profile-tabs">
		<?php foreach ( $tabs as $tab ) : ?>
			<li class="wpaam-tab-<?php echo $tab['id'];?> <?php echo $current_tab_slug == $tab['slug'] || $current_tab_slug == null && $all_tabs[0] == $tab['slug'] ? 'active' : ''?>">
				<a href="<?php echo wpaam_get_profile_tab_permalink( $user_data, $tab );?>"><?php echo $tab['title'];?></a>
			</li>
		<?php endforeach; ?>
	</ul>
	<!-- end tabs -->

</div>

<div class="wpaam-clearfix"></div>

<div class="wpaam-profile-tabs-content">

<?php

	// Display tabs content.
	// Check that the tab exists or - null if we're on /profile/ page.
	if ( $current_tab_slug === null || wpaam_profile_tab_exists( $current_tab_slug ) ) {

		switch ( $current_tab_slug ) {
			case null: // Return first tab if null - meaning we're on /profile/ page
				do_action( "wpaam_profile_tab_content_{$all_tabs[0]}", $user_data, $tabs, $current_tab_slug );
				break;
			case $current_tab_slug:
				do_action( "wpaam_profile_tab_content_{$current_tab_slug}", $user_data, $tabs, $current_tab_slug );
				break;
		}

		// Display not found error if tab doesn't exist
	} else {

		// Display error message
		$args = array(
			'id'   => 'wpaam-not-found',
			'type' => 'notice',
			'text' => __( 'Content not found.', 'wpaam' )
		);
		wpaam_message( $args );

	}

?>

</div>
