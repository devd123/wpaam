<?php
/**
 * wpaam Template: User Links.
 * Displays helper links below the forms.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>
<div class="wpaam-helper-links">

	<?php if ( $login == 'yes' ) : ?>
	<p class="wpaam-login-url">
		<?php echo apply_filters( 'wpaam_login_link_label', sprintf( __( 'Already have an account? <a href="%s">Sign In &raquo;</a>', 'wpaam' ), esc_url( get_permalink( wpaam_get_option( 'login_page' ) ) ) ) ); ?>
	</p>
	<?php endif; ?>

	<?php if ( $register == 'yes' ) : ?>
	<p class="wpaam-register-url">
		<?php echo apply_filters( 'wpaam_registration_link_label', sprintf( __( 'Don\'t have an account? <a href="%s">Signup Now &raquo;</a>', 'wpaam' ), esc_url( get_permalink( wpaam_get_option( 'registration_page' ) ) ) ) ); ?>
	</p>
	<?php endif; ?>

	<?php if ( $password == 'yes' ) : ?>
	<p class="wpaam-password-recovery-url">
		<a href="<?php echo esc_url( get_permalink( wpaam_get_option( 'password_recovery_page' ) ) );?>">
			<?php echo apply_filters( 'wpaam_password_link_label', __( 'Lost your password?', 'wpaam' ) ); ?>
		</a>
	</p>
	<?php endif; ?>

</div>
