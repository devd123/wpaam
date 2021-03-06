<?php
/**
 * WPAAM Template: Quotation Form Template.
 */

 		
	if ( is_user_logged_in() && current_user_can( 'edit_invoice' ) && current_user_can( 'publish_invoice' ) ) : 
		
		if(isset( $_GET['invoice_id']) && !empty($_GET['invoice_id']) ) {
			$quotation_id = $_GET['invoice_id'];
			$quotation = get_post( $quotation_id );
		 	$client_id = get_post_meta( $quotation->ID, 'client', true );
		 	$client = get_userdata($client_id);
		 	$products = get_post_meta( $quotation->ID, 'products', true );
		 	$status = get_post_meta( $quotation->ID, 'status', true ); 
		 	$payment_date = get_post_meta( $quotation->ID, 'payment_date', true ); 
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

		<form id="wpaam-invoices" name="wpaam-invoices" method="post" action="" class="wpaam-profile-form">
		 	<input type="hidden" id="author_id" name="author_id" value="<?php echo $author_id;?>">
			

			<fieldset data-name="client_name" data-required="1"  data-type="text" class="fieldset-client_name">
				<label for="client_name">Client Name<span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<?php if(isset( $_GET['invoice_id']) && !empty($_GET['invoice_id']) ) : ?>
						<input type="text" value="<?php if ( !empty($client) ) echo $client->display_name;?>" disabled>
					<?php else : ?>	
					<select id="client" name="client" class="select">
						<option value="0">Select Client...</option>
					<?php
	  					// get the client list
	  					foreach ($clients as $client) : 
	    	   			echo '<option value='.$client->ID.'>'.$client->display_name.'</option>';
	       				endforeach; 
	   				?>
					</select>
					<?php endif; ?>
				</div>
			</fieldset>

		 	<fieldset data-name="multi_products" data-required="1"  data-type="text" class="fieldset-multi_products">
				<label for="multi_products">Product Name <span class="wpaam-required-star">*</span></label>
				<div class="field required-field ui-widget">
				<?php if(isset( $_GET['invoice_id']) && !empty($_GET['invoice_id']) ) : ?>
					<input type="text" value="<?php if ( !empty($products) ) echo $products;?>" disabled>
				<?php else : ?>	
				  <input type="text" required name="multi_products" id="multi_products" class="select_product" autocomplete="off">
			  	<?php endif; ?>
				</div>
			</fieldset>
			

			<fieldset data-name="status" data-required="1"  data-type="text" class="fieldset-status">
				<label for="status">Invoice Status <span class="wpaam-required-star">*</span></label>
				<div class="field required-field ui-widget">
				  <input type="text" required name="status" id="status" value="<?php if ( !empty($status) ) echo $status;?>">
				</div>
			</fieldset>

			<fieldset data-name="payment_date" data-required="1"  data-type="text" class="fieldset-payment_date">
				<label for="payment_date">Payment Date <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<input type="text" required value="<?php if ( !empty($payment_date) ) echo $payment_date; ?>" placeholder="" id="datepicker" name="payment_date" class="input">
				</div>
			</fieldset>

			
			<?php wp_nonce_field( $form ); ?>
			<p class="wpaam-submit">
				<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
				<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $author_id; ?>" />
				<?php if(isset($_GET['invoice_id']) && $_GET['invoice_id'] != '') : ?>
				<input type="submit" id="submit_wpaam_invoices" name="submit_wpaam_invoices" class="button" value="<?php  _e( 'Update Quotation', 'wpaam' ); ?>" />
				<?php else :?>
				<input type="submit" id="submit_wpaam_invoices" name="submit_wpaam_invoices" class="button" value="<?php _e( 'Add Quotation', 'wpaam' ); ?>" />
				<?php endif; ?>
			</p>
		 
		</form><!-- #addproduct -->

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to see this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>	
