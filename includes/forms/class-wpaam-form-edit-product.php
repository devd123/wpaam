<?php
/**
 * WPAAM FORMS : Product Edit Form
 *
 * @package     wp-user-manager
 * @author      Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class WPAAM_Form_Edit_Product extends WPAAM_Form {

	public static $form_name = 'edit-product';

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

	public static function validate_product_fields(  ) {
		
		
		$title = $_POST['post_title'];
		$title_exists = self::title_exists();
		$sku = $_POST['product_sku'];
		$sku_exists = self::sku_exists();
		$price = $_POST['product_price'];
		
		if ( !$title )
			return new WP_Error( 'product-validation-error', __('A product name is required for create new product.', 'wpaam') );
		elseif ( $title_exists )
			return new WP_Error( 'product-validation-error', __('This product is already exists.', 'wpaam') );
		elseif ( !sku )
			return new WP_Error( 'product-validation-error', __( 'A product sku is required for create new product.', 'wpaam' ) );
		elseif ( $sku_exists )
			return new WP_Error( 'product-validation-error', __( 'This product sku is already used please try other', 'wpaam' ) );
		elseif ( !price )
			return new WP_Error( 'product-validation-error', __( 'A product price is required for create new product.', 'wpaam' ) );

		

		return $product;
		
	}

	public static function title_exists() {
    global $wpdb;
 		
 		$title = $_POST['post_title'];
   		$sql = "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $title . "' && post_author = '" .self::$user->ID. "' && post_type = 'aam-product' "; 
		$res =  $wpdb->get_var( $sql );
	 
	    return $res;
	}

	public static function sku_exists() {
    global $wpdb;
 		
 		$sku = $_POST['product_sku'];
 		// args to query for your key
		 $args = array(
		   'post_type' => 'aam-product',
		   'meta_query' => array(
		       array(
		           'key' => 'product_sku',
		           'value' => $sku
		       )
		   ),
		   'fields' => 'ids'
		 );
		 // perform the query
		 $query = new WP_Query( $args );

		 $res = $query->posts; 

		 // do something if the meta-key-value-pair exists in another post
		 if ( ! empty( $res ) ) {
		     return $res;
		 }else{
		 	return 0;
		 }
	 
	    
	}

	public static function process(){
		if(isset($_GET['product_id']) && $_GET['product_id'] != ''){
			self::update_process();
		}else{
			self::add_process();
		}
	}

	public static function add_process() {
		

		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-product' ) ) {
			return;
		}

		// Validate required
		if ( is_wp_error( ( $return = self::validate_product_fields(  ) ) ) ) {
			self::add_error( $return->get_error_message() );
			return;
		}

		
			// Add the content of the form to $post as an array
			$product_data = array(
				'post_title'    => esc_attr($_POST['post_title']),
				'product_sku'   => esc_attr($_POST['product_sku']),
				'product_price' => esc_attr($_POST['product_price']),
				'product_vat'   => esc_attr($_POST['product_vat']),
				'post_author'   => self::$user->ID,
				'post_status'   => 'publish', 
				'post_type'     => 'aam-product',  
			);

			$newproduct = wp_insert_post( $product_data , $wp_error); 
	        update_post_meta ( $newproduct, 'product_sku', $product_data['product_sku'] );
	        update_post_meta ( $newproduct, 'product_price', $product_data['product_price'] );
	        update_post_meta ( $newproduct, 'product_vat', $product_data['product_vat'] );

	        if ( is_wp_error( $newproduct ) ) {

					self::add_error( $newproduct->get_error_message() );

			} else {

				self::add_confirmation( __('you have successfully added a new product.', 'wpaam') );

			}

	}

	public static function update_process() {
		
		if ( empty( $_POST['wpaam_submit_form'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-product' ) ) {
			return;
		}

		$product_id = $_GET['product_id'];
		// Add the content of the form to $post as an array
		$product_data = array(
			'ID'            => $product_id,
			'post_title'    => esc_attr($_POST['post_title']),
			'product_sku'   => esc_attr($_POST['product_sku']),
			'product_price' => esc_attr($_POST['product_price']),
			'product_vat'   => esc_attr($_POST['product_vat']),
			'post_author'   => self::$user->ID,
			'post_status'   => 'publish', 
			'post_type'     => 'aam-product',  
		);

		$updateproduct = wp_update_post( $product_data );
        update_post_meta ( $updateproduct, 'product_sku', $product_data['product_sku'] );
        update_post_meta ( $updateproduct, 'product_price', $product_data['product_price'] );
        update_post_meta ( $updateproduct, 'product_vat', $product_data['product_vat'] );

        if ( is_wp_error( $updateproduct ) ) {

				self::add_error( $updateproduct->get_error_message() );

		} else {

			self::add_confirmation( __('you have successfully update  the product.', 'wpaam') );

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
		$current_tab = wpaam_get_current_products_tab();
		$all_tabs = array_keys( wpaam_get_products_page_tabs() );

		// Display template
		if ( is_user_logged_in() && current_user_can( 'edit_product' ) ) :

			if( isset( $_POST['submit_wpaam_add_products'] ) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}elseif ( isset( $_POST['submit_wpaam_edit_products']) ) {
				// Show errors from fields
				self::show_errors();
				// Show confirmation messages
				self::show_confirmations();
			}
			get_wpaam_template( 'forms/edit-product-form.php',
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
