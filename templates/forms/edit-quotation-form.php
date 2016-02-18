<?php
/**
 * WPAAM Template: Quotation Form Template.
 */

 		
	if ( is_user_logged_in() && current_user_can( 'edit_quotation' ) && current_user_can( 'publish_quotation' ) ) : 
		
		if(isset( $_GET['product_id']) && !empty($_GET['product_id']) ) {
			// $product_id = $_GET['product_id'];
			// $product = get_post( $product_id );
	 	// 	$product_sku = get_post_meta( $product->ID, 'product_sku', true );
	 	// 	$product_price = get_post_meta( $product->ID, 'product_price', true );
		} 

	$auther_selected_vat  = get_user_meta( $author_id, 'user_vat_values', true ); 
	$aam_user = wp_get_current_user();
	// Generate the query \
	$my_users = new WP_User_Query( 
	  array( 
	    'role' => 'aam_client', 
	    'order' => 'DESC',
	    'meta_key' => 'parent_user', 
	    'meta_value' => $aam_user->user_login,
	    'compare' => '='
	  ));
	
	// The clients object. 
	$clients = $my_users->get_results();

	?>

		<form id="wpaam-quotations" name="wpaam-quotations" method="post" action="" class="wpaam-profile-form">
		 	<input type="hidden" id="author_id" name="author_id" value="<?php echo $author_id;?>">
			<fieldset data-name="client_name" data-required="1"  data-type="text" class="fieldset-client_name">
				<label for="client_name">Client Name<span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<select id="client_name" name="client_name" class="select">
					<option value="0">Select Client...</option>
				<?php
  					// get the client list
  					foreach ($clients as $client) : 
    	   			echo '<option  value='.$client->ID.'>'.$client->nickname.'</option>';
       				endforeach; 
   				?>
   					</select>
				</div>
			</fieldset>

		 	<fieldset data-name="product_name" data-required="1"  data-type="text" class="fieldset-product_name">
				<label for="product_name">Product Name <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<input type="text" required="" value="<?php if ( isset($product->product_name) ) echo $product->product_name; ?>" placeholder="" id="search_product" name="search_product" class="auto-selected-product">
					<div id="suggesstion-box"></div>
				</div>

			</fieldset>

			<fieldset data-name="product_price" data-required="1"  data-type="text" class="fieldset-product_price">
				<label for="product_price">Price <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<input type="text" required="" value="<?php if ( isset($product_price) ) echo $product_price; ?>" placeholder="" id="quotation_price" name="quotation_price" class="quotation_price_selected">
				</div>
			</fieldset>

			
			<?php wp_nonce_field( $form ); ?>
			<p class="wpaam-submit">
				<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
				<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $author_id; ?>" />
				<?php if(isset($_GET['product_id']) && $_GET['product_id'] != '') : ?>
				<input type="submit" id="submit_wpaam_edit_quotations" name="submit_wpaam_edit_quotations" class="button" value="<?php  _e( 'Update Product', 'wpaam' ); ?>" />
				<?php else :?>
				<input type="submit" id="submit_wpaam_add_quotations" name="submit_wpaam_add_quotations" class="button" value="<?php _e( 'Add Product', 'wpaam' ); ?>" />
				<?php endif; ?>
			</p>
		 
		</form><!-- #addproduct -->

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to see this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>