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


class WPAAM_Form_Edit_Quotation extends WPAAM_Form {

	public static $form_name = 'edit-quotation';

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

	public static function validate_quotation_fields(  ) {
		
			
		if ( !$_POST['client'] )
			return new WP_Error( 'quotation-validation-error', __('A client name is required for create new quotation.', 'wpaam') );
		elseif ( !$_POST['multi_products'])
			return new WP_Error( 'quotation-validation-error', __( 'A product is required for create new quotation.', 'wpaam' ) );
		
	}

	
	public static function process(){
		if(isset($_GET['quotation_id']) && $_GET['quotation_id'] != ''){
			self::update_process();
		}else{
			self::add_process();
		}
	}

	public static function add_process() {
		

		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-quotation' ) ) {
			return;
		}

		// Validate required
		if ( is_wp_error( ( $return = self::validate_quotation_fields(  ) ) ) ) {
			self::add_error( $return->get_error_message() );
			return;
		}

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
			$title = get_user_meta( self::$user->ID , 'quotation_prefix' , true ).'_'.time();
			$quotation_data = array(
				'post_title' => $title,
				'client' 	=> esc_attr($_POST['client']),
				'products'    => esc_attr($_POST['multi_products']),
				'quotation_total'   => $total,
				'post_author'   => self::$user->ID,
				'post_status'   => 'publish', 
				'post_type'     => 'aam-quotation',  
			);
			//echo "<pre>"; print_r($quotation_data); die;

			$newquotation = wp_insert_post( $quotation_data ); 
			// get invoice_start number of the aam user
			$qt_number = get_user_meta(self::$user->ID , 'quotation_start' , true).$newquotation;
			//updadate invoice post meta
			update_post_meta ( $newquotation, 'quotation_number', $qt_number );
	        update_post_meta ( $newquotation, 'client', $quotation_data['client'] );
	        update_post_meta ( $newquotation, 'products', $quotation_data['products'] );
	        update_post_meta ( $newquotation, 'quotation_total', $quotation_data['quotation_total'] );

	        if ( is_wp_error( $newquotation ) ) {

					self::add_error( $newquotation->get_error_message() );

			} else {

				self::add_confirmation( __('you have successfully added a new quotation.', 'wpaam') );

			}

	}

	public static function update_process() {
		
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-quotation' ) ) {
			return;
		}

		$quotation_id = $_GET['quotation_id'];
		// Add the content of the form to $post as an array
		$quotation_data = array(
			'ID'            	=> $quotation_id,
			'products'    	=> esc_attr($_POST['multi_products']),
			'quotation_total'   => esc_attr($_POST['quotation_price']),
		
		);

		$updatequotation = wp_update_post( $quotation_data ); 
        update_post_meta ( $updatequotation, 'products', $quotation_data['products'] );
        update_post_meta ( $updatequotation, 'quotation_total', $quotation_data['quotation_total'] );

        if ( is_wp_error( $updatequotation ) ) {

				self::add_error( $updatequotation->get_error_message() );

		} else {

			self::add_confirmation( __('you have successfully update  the quotation.', 'wpaam') );

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
		$current_tab = wpaam_get_current_quotations_tab();
		$all_tabs = array_keys( wpaam_get_quotations_page_tabs() );

		// Display template
		if ( is_user_logged_in() && current_user_can( 'edit_quotation' ) ) :

			if( isset( $_POST['submit_wpaam_add_quotations'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}elseif ( isset( $_POST['submit_wpaam_edit_quotations']) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}
			get_wpaam_template( 'forms/edit-quotation-form.php',
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

