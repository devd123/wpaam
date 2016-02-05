<?php
/**
 * wpaam Template: Profile Card.
 * Displays a preview of the user profile.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>

<div id="wpaam-profile-card<?php echo $wrapper_id; ?>" class="wpaam-profile-card">

	<div class="wpaam-profile-img">
		<?php echo wpaam_profile_avatar( $user_data, $link_to_profile, 100 ); ?>
	</div>

	<div class="wpaam-card-details">

		<h4 class="wpaam-card-name"><?php echo wpaam_profile_display_name( $user_data, $link_to_profile ); ?></h4>

		<?php echo wpautop( $user_data->user_description ); ?>

		<?php if ( $display_buttons ) : ?>

			<?php if ( $link_to_profile ) : ?>
				<a href="<?php echo wpaam_get_user_profile_url( $user_data ); ?>" class="wpaam-card-button"><?php _e( 'View Profile', 'wpaam' );?></a>
			<?php endif; ?>

			<a href="mailto:<?php echo antispambot( $user_data->user_email );?>" class="wpaam-card-button"><?php _e( 'Send Email', 'wpaam' );?></a>

		<?php endif; ?>

		<?php do_action( 'wpaam_profile_card_details', $user_data, $atts ); ?>

	</div>

</div>
