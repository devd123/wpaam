<?php
/**
 * Edit Email Page
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$email_id    = esc_attr( $_GET['email-id'] );
$email_title = esc_attr( $_GET['email-title'] );
$get_emails  = get_option('wpaam_emails');

// Verify if this email is stored already
if( is_array( $get_emails ) && array_key_exists( $email_id , $get_emails ) ) {
	$this_email = $get_emails[ $email_id ];
} else {
	$this_email = array( 
		'subject' => call_user_func( "wpaam_{$email_id}_Email::subject" ),
		'message' => call_user_func( "wpaam_{$email_id}_Email::message" )
	);
}

// Editor Args
$editor_args = array( 
	'textarea_name' => 'message',
	'media_buttons' => false,
	'textarea_rows' => 10,
	'teeny'         => true,
	'dfw'           => false,
	'tinymce'       => false,
	'quicktags'     => 	array(
		'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,close'
	)
);

?>
<div class="wrap">

	<h2 class="wpaam-page-title"><?php printf( __( 'WPAAM - Editing "%s"', 'wpaam' ), $email_title ); ?> <a href="<?php echo admin_url( 'users.php?page=wpaam-settings&tab=emails' );?>" class="add-new-h2"><?php _e('Back to settings page &raquo;', 'wpaam');?></a></h2>

	<form id="wpaam-edit-email" action="" method="post">
		<table class="form-table">
			<tbody>
				
				<tr>
					<th scope="row" valign="top">
						<label for="wpaam-email-subject"><?php _e( 'Email Subject:', 'wpaam' ); ?></label>
					</th>
					<td>
						<input name="subject" id="wpaam-email-subject" type="text" value="<?php echo esc_attr( stripslashes( $this_email['subject'] ) ); ?>" style="width: 300px;"/>
						<p class="description"><?php _e( 'The subject line of the email', 'wpaam' ); ?></p>
					</td>
				</tr>
				<tr>
				<th scope="row" valign="top">
					<label for="wpaam-notice-message"><?php _e( 'Email Message:', 'wpaam' ); ?></label>
				</th>
				<td>
					<?php wp_editor( wp_kses_post( wptexturize( $this_email['message'] ) ), 'message', $editor_args ); ?>
					<p class="description"><?php _e( 'The email message to be sent into the notification. The following template tags can be used in the message:', 'wpaam' ); ?></p>
					<br/><p><?php echo wpaam_get_emails_tags_list(); ?></p>
				</td>
			</tr>
				
			</tbody>
		</table>
		
		<input type="hidden" name="wpaam-action" value="edit_email"/>
		<input type="hidden" name="email_id" value="<?php echo esc_attr( $email_id ); ?>"/>
		<input type="hidden" name="wpaam-email-nonce" value="<?php echo wp_create_nonce( 'wpaam_email_nonce' ); ?>"/>
		
		<?php submit_button(); ?>

	</form>

</div>