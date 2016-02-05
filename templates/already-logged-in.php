<?php
/**
 * wpaam Template: Already Logged In.
 *
 * Displays a message telling the user he is already logged in.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

global $current_user;
get_currentuserinfo();

?>

<?php do_action( 'wpaam_before_logged_in_template', $current_user, $args ); ?>

<div id="wpaam-form-<?php echo $args['form_id'];?>" class="wpaam-login-form loggedin">

	<p><?php printf( __( 'You are currently logged in as %s. <a href="%s">Logout &raquo;</a>', 'wpaam' ), $current_user->display_name, wpaam_logout_url() );?></p>

</div>

<?php do_action( 'wpaam_after_logged_in_template', $current_user, $args ); ?>
