<?php
/**
 * wpaam Template: Profile.
 * Displays the user profile.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Display error message if no user has been found.
if ( !is_object( $user_data ) ) {
	$args = array(
		'id'   => 'wpaam-profile-not-found',
		'type' => 'error',
		'text' => __( 'User not found.', 'wpaam' )
	);
	wpaam_message( $args );
	return;
}

do_action( "wpaam_before_profile", $user_data );

?>

<!-- start profile -->
<div class="wpaam-single-profile" id="wpaam-profile-<?php echo $user_data->ID;?>">

	<?php do_action( "wpaam_before_profile_details", $user_data ); ?>

	<!-- Profile details wrapper -->
	<div class="wpaam-user-details">

		<!-- First column -->
		<div class="wpaam_three_fourth wpaam-main-profile-details">

			<div class="wpaam-avatar-img wpaam_one_sixth">
				<a href="<?php echo wpaam_get_user_profile_url( $user_data ); ?>"><?php echo get_avatar( $user_data->ID , 128 ); ?></a>
				<?php do_action( "wpaam_profile_after_avatar", $user_data ); ?>
			</div>

			<div class="wpaam-inner-details wpaam_five_sixth last">
				<?php do_action( 'wpaam_main_profile_details', $user_data ); ?>
			</div>

		</div>
		<!-- end first column -->

		<!-- last column -->
		<div class="wpaam_one_fourth last wpaam-secondary-profile-details">
			<?php do_action( 'wpaam_secondary_profile_details', $user_data ); ?>
		</div>
		<!-- end last column -->

		<div class="wpaam-clearfix"></div>

	</div>
	<!-- end profile details wrapper -->

	<?php do_action( "wpaam_after_profile_details", $user_data ); ?>

</div>
<!-- end profile -->

<?php do_action( "wpaam_after_profile", $user_data ); ?>
