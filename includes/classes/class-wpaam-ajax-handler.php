<?php
/**
 * Ajax Handler
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Ajax_Handler Class
 * Handles all the ajax functionalities of the plugin.
 *
 * @since 1.0.0
 */
class wpaam_Ajax_Handler {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Restore Email
		add_action( 'wp_ajax_wpaam_restore_emails', array( $this, 'restore_emails' ) );

		// Remove User Roles
		add_action( 'wp_ajax_wpaam_remove_roles', array( $this, 'remove_roles' ) );

		// Avatar removal method
		add_action( 'wp_ajax_wpaam_remove_file', array( $this, 'remove_user_file' ) );
		add_action( 'wp_ajax_nopriv_wpaam_remove_file', array( $this, 'remove_user_file' ) );

		// Update custom fields order
		add_action( 'wp_ajax_wpaam_update_fields_order', array( $this, 'update_fields_order' ) );

		// Get product by AAM User
		add_action( 'wp_ajax_wpaam_get_product_by_aamuser', array( $this, 'get_product_by_aamuser' ) );

		// Get product price 
		add_action( 'wp_ajax_wpaam_get_product_price', array( $this, 'get_product_price' ) );

	}

	/**
	 * Restore email into the backend.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function restore_emails() {

		// Check our nonce and make sure it's correct.
		check_ajax_referer( 'wpaam_nonce_email_reset', 'wpaam_backend_security' );

		// Abort if something isn't right.
		if ( !is_admin() || !current_user_can( 'manage_options' ) ) {
			$return = array(
				'message' => __( 'Error.', 'wpaam' ),
			);

			wp_send_json_error( $return );
		}

		// Delete the option first
		delete_option( 'wpaam_emails' );

		// Get all registered emails
		wpaam_register_emails();

		$return = array(
			'message' => __( 'Emails successfully restored.', 'wpaam' ),
		);

		wp_send_json_success( $return );

	}

	public function remove_roles() {


		// Check our nonce and make sure it's correct.
		check_ajax_referer( 'wpaam_nonce_roles_remove', 'wpaam_user_roles_security' );

		// Abort if something isn't right.
		if ( !is_admin() || !current_user_can( 'manage_options' ) ) {
			$return = array(
				'message' => __( 'Error.', 'wpaam' ),
			);

			wp_send_json_error( $return );
		}

		// Delete the roles first
		remove_role( 'aam_user' );
		remove_role( 'aam_client' );

		

		$return = array(
			'message' => __( 'User roles successfully deleted.', 'wpaam' ),
		);

		wp_send_json_success( $return );

	}

	/**
	 * Remove the avatar of a user.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function remove_user_file() {

		$form = esc_attr( $_REQUEST['submitted_form'] );

		check_ajax_referer( $form, 'wpaam_removal_nonce' );

		$field_id = $_REQUEST['field_id'];
		$user_id = get_current_user_id();

		// Functionality to remove avatar.
		if( $field_id == 'user_avatar' ) {

			if( $field_id && is_user_logged_in() ) {

				delete_user_meta( $user_id, "current_{$field_id}" );

				// Deletes previously selected avatar.
				$previous_avatar = get_user_meta( $user_id, "_current_{$field_id}_path", true );
				if( $previous_avatar )
					wp_delete_file( $previous_avatar );

				delete_user_meta( $user_id, "_current_{$field_id}_path" );

				$return = array(
					'valid'   => true,
					'message' => apply_filters( 'wpaam_avatar_deleted_success_message', __( 'Your profile picture has been deleted.', 'wpaam' ) )
				);

				wp_send_json_success( $return );

			} else {

				$return = array(
					'valid'   => false,
					'message' => __( 'Something went wrong.', 'wpaam' )
				);

				wp_send_json_error( $return );

			}

		// This is needed for all the other field types.
		} else {

			if( $field_id && is_user_logged_in() ) {

				$field_files = get_user_meta( $user_id, $field_id, true );
				$field_files = maybe_unserialize( $field_files );

				if( is_array( $field_files ) ) {

					if( wpaam_is_multi_array( $field_files ) ) {

						foreach ( $field_files as $key => $file ) {
							wp_delete_file( $file['path'] );
						}

					} else {

						wp_delete_file( $field_files['path'] );

					}

				}

				delete_user_meta( $user_id, $field_id );

				$return = array(
					'valid'   => true,
					'message' => apply_filters( 'wpaam_files_deleted_success_message', __( 'Files successfully removed.', 'wpaam' ) )
				);

				wp_send_json_success( $return );

			}

		}

	}

	/**
	 * Updates custom fields order.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function update_fields_order() {

		// Check our nonce and make sure it's correct.
		check_ajax_referer( 'wpaam_fields_editor_nonce', 'wpaam_editor_nonce' );

		// Abort if something isn't right.
		if ( !is_admin() || !current_user_can( 'manage_options' ) ) {
			$return = array(
				'message' => __( 'Error.', 'wpaam' ),
			);
			wp_send_json_error( $return );
		}

		// Prepare the array.
		$fields = $_POST['items'];

		if( is_array( $fields ) ) {
			foreach ( $fields as $field ) {
				$args = array(
					'field_order' => (int) $field['priority'],
				);
				wpaam()->fields->update( (int) $field['field_id'], $args );
			}
		} else {
			$return = array(
				'message' => __( 'Error.', 'wpaam' ),
			);
			wp_send_json_error( $return );
		}

		// Send message
		$return = array(
			'message'   => __( 'Fields order successfully updated.', 'wpaam' ),
		);

		wp_send_json_success( $return );

	}

	public function get_product_by_aamuser() {
		
		global $wpdb;
		$author_id = get_current_user_id();
		$results = $wpdb->get_results( "SELECT ID , post_title FROM $wpdb->posts WHERE post_type = 'aam-product' AND post_author = '$author_id' AND post_status = 'publish' ");
		foreach ($results as $key => $value) {
			$post_title[] = $value->post_title;
		}
		echo json_encode($post_title);
		wp_die();
	}

	public function get_product_price() {
		// get the product list
		$product_name = $_POST['product_name']; 
		global $wpdb;
		$id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$product_name' ");
		$price = get_post_meta($id , 'product_price' , true);
		echo $price;
		wp_die();
	}

}

new wpaam_Ajax_Handler;
