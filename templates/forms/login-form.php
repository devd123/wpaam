<?php
/**
 * wpaam Template: Login Form Template.
 *
 * Displays login form.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>

<?php do_action( 'wpaam_before_login_form', $args ); ?>

<div id="wpaam-form-<?php echo esc_attr( $args['form_id'] );?>" class="wpaam-login-form" data-redirect="<?php echo esc_attr( $args['redirect'] );?>">

	<?php do_action( 'wpaam_top_login_form', $args ); ?>

	<?php wp_login_form( apply_filters( 'wpaam_login_shortcode_args', $args ) ); ?>

	<?php do_action( 'wpaam_bottom_login_form', $args ); ?>

</div>

<?php do_action( 'wpaam_after_login_form', $args ); ?>