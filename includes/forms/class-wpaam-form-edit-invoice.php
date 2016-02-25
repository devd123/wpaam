<?php
/**
 * WPAAM FORMS : Quoation Edit Form
 *
 * @package     wp-user-manager
 * @author      Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class WPAAM_Form_Edit_Invoice extends WPAAM_Form {

	public static $form_name = 'edit-invoice';

	private static $user;

	/**
	 * Init the form.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {

		add_action( 'wp', array( __CLASS__, 'process' ) );

		// Set values to the fields
		if( ! is_admin() ) {

			self::$user = wp_get_current_user();
			

		}


	}

	public static function validate_invoice_fields(  ) {
		
			
		if ( !$_POST['client'] )
			return new WP_Error( 'quotation-validation-error', __('A client name is required for create new quotation.', 'wpaam') );
		elseif ( !$_POST['multi_products'])
			return new WP_Error( 'quotation-validation-error', __( 'A product is required for create new quotation.', 'wpaam' ) );
		
	}

	
	public static function process(){
		if(isset($_GET['invoice_id']) && $_GET['invoice_id'] != ''){
			self::update_process();
		}else{
			self::add_process();
		}
	}

	public static function add_process() {
		

		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-invoice' ) ) {
			return;
		}

		// Validate required
		// if ( is_wp_error( ( $return = self::validate_invoice_fields(  ) ) ) ) {
		// 	self::add_error( $return->get_error_message() );
		// 	return;
		// }

			
			$products = esc_attr($_POST['multi_products']);
			$product_title = explode(", ",$products);
		
			foreach ($product_title as  $titles) {
				$product_data[] = get_page_by_title( $titles, ARRAY_A, 'aam-product' );
			}
			$total = '';
			foreach ($product_data as  $product) {
				$price = get_post_meta($product['ID'] , 'product_price' , true);
				$total += $price;
			}
			

			// Add the content of the form to $post as an array
			$title =  self::$user->user_login.'_'.time();
			$invoice_data = array(
				'post_title' 	=> $title,
				'client' 	    => esc_attr($_POST['client']),
				'products'    	=> esc_attr($_POST['multi_products']),
				'payment_date'  => esc_attr($_POST['payment_date']),
				'invoice_total' => $total,
				'post_author'   => self::$user->ID,
				'post_status'   => 'publish', 
				'post_type'     => 'aam-invoice',  
			);
			//echo "<pre>"; print_r($invoice_data); die;

			$new_invoice_id = wp_insert_post( $invoice_data ); 
	        update_post_meta ( $new_invoice_id, 'client', $invoice_data['client'] );
	        update_post_meta ( $new_invoice_id, 'products', $invoice_data['products'] );
	        update_post_meta ( $new_invoice_id, 'payment_date', $invoice_data['payment_date'] );
	        update_post_meta ( $new_invoice_id, 'invoice_total', $invoice_data['invoice_total'] );

	        if ( is_wp_error( $new_invoice_id ) ) {

					self::add_error( $new_invoice_id->get_error_message() );

			} else {

				self::add_confirmation( __('you have successfully generated a new invoice.', 'wpaam') );

			}

	}

	public static function update_process() {
		
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-invoice' ) ) {
			return;
		}

		$invoice_id = $_GET['invoice_id'];
		// Add the content of the form to $post as an array
		$invoice_data = array(
			'ID'            => $invoice_id,
			'client' 	    => esc_attr($_POST['client']),
			'products'    	=> esc_attr($_POST['multi_products']),
			'payment_date'  => esc_attr($_POST['payment_date']),
			'invoice_total' => $total,
		
		);

		$update_id = wp_update_post( $invoice_data ); 
        update_post_meta ( $new_invoice_id, 'client', $invoice_data['client'] );
        update_post_meta ( $new_invoice_id, 'products', $invoice_data['products'] );
        update_post_meta ( $new_invoice_id, 'payment_date', $invoice_data['payment_date'] );
        update_post_meta ( $new_invoice_id, 'invoice_total', $invoice_data['invoice_total'] );

        if ( is_wp_error( $update_id ) ) {

				self::add_error( $update_id->get_error_message() );

		} else {

			self::add_confirmation( __('you have successfully update  the invoice.', 'wpaam') );

		}

	}

	

	/**
	 * Output the form.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public static function output( $atts = array() ) {

		// Get the tabs
		$current_tab = wpaam_get_current_invoices_tab();
		$all_tabs = array_keys( wpaam_get_invoices_page_tabs() );

		// Display template
		if ( is_user_logged_in()) :

			if( isset( $_POST['submit_wpaam_invoices'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}
			get_wpaam_template( 'forms/edit-invoice-form.php',
				array(
					'atts'        => $atts,
					'form'        => self::$form_name,
					'author_id'   => self::$user->ID,
					'current_tab' => $current_tab,
					'all_tabs'    => $all_tabs
				)
			);
	
		// Show login form if not logged in
		else :

			echo wpaam_login_form();

		endif;

	}

}

