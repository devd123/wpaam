<?php
/**
 * Registration Email
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPAAM_register_Email Class
 * This class registers a new email for the editor.
 *
 * @since 1.0.0
 */
class WPAAM_register_Email extends WPAAM_Emails {

	/**
	 * This function sets up a custom email.
	 *
	 * @since 1.0.0
	 * @return  void
	 */
	function __construct() {
		
		// Configure Email
		$this->name        = 'register';
		$this->title       = __( "Registration Email", 'wpaam' );
		$this->description = __( "This is the email that is sent to the user upon successful registration.", 'wpaam' );
		$this->subject     = $this->subject();
		$this->message     = $this->message();

		// do not delete!
		parent::__construct();
	}

	/**
	 * The default subject of the email.
	 *
	 * @since 1.0.0
	 * @return  void
	 */
	public static function subject() {

		$subject = sprintf( __('Your %s Account', 'wpaam'), get_option( 'blogname' ) );

		return $subject;

	}

	/**
	 * The default message of the email.
	 *
	 * @since 1.0.0
	 * @return  void
	 */
	public static function message() {

		$message = __( "Hello {username}, \n\n", 'wpaam' );
		$message .= __( "Welcome to {sitename}, \n\n", 'wpaam' );
		$message .= __( "These are your account details \n\n", 'wpaam');
		$message .= __( "Username: {username},\n", 'wpaam' );
		$message .= __( "Password: {password}", 'wpaam' );

		return $message;

	}

}

new WPAAM_register_Email();
