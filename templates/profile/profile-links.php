<?php
/**
 * wpaam Template: User profile links.
 * Displays links related to the user.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>
<ul class="wpaam-user-links">

	<?php do_action( 'wpaam_before_profile_links',  $user_data ); ?>

	<li class="wpaam-profile-link send-email">
		<a href="mailto:<?php echo antispambot( $user_data->user_email );?>" class="wpaam-button"><?php _e( 'Send Email', 'wpaam' );?></a>
	</li>
	<?php if ( !empty( $user_data->user_url ) ) : ?>
	<li class="wpaam-profile-link view-website">
		<a href="<?php echo esc_url( $user_data->user_url );?>" class="wpaam-button" rel="nofollow" target="_blank"><?php _e( 'Visit website', 'wpaam' );?></a>
	</li>
	<?php endif; ?>

	<?php do_action( 'wpaam_after_profile_links',  $user_data ); ?>

</ul>
