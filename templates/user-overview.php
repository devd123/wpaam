<?php
/**
 * wpaam Template: Current user overview.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

$user_profile_user = wpaam_get_user_profile_url( $current_user );

?>

<div id="wpaam-current-user-overview-<?php echo $current_user->ID; ?>" class="wpaam-user-overview">

	<div class="wpaam_one_fourth user-avatar">
		<a href="<?php echo esc_url( $user_profile_user ); ?>"><?php echo get_avatar( $current_user->ID, 48 ); ?></a>
	</div>

	<div class="wpaam_three_fourth user-content last">

		<a href="<?php echo esc_url( $user_profile_user ); ?>"><?php echo $current_user->display_name; ?></a>

		<ul class="wpaam-overview-links">
			<li><a href="<?php echo esc_url( wpaam_get_core_page_url('account') ); ?>"><?php _e('Edit Account', 'wpaam'); ?></a></li>
			<li>|</li>
			<li><a href="<?php echo esc_url( wpaam_logout_url( get_permalink() ) ); ?>"><?php _e('Logout', 'wpaam'); ?></a></li>
		</ul>

	</div>

	<div class="wpaam-clearfix"></div>

</div>
