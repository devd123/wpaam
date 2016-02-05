<?php
/**
 * wpaam Template: Edit Product Form Template.
 * @since       1.0.0
 */
	/*  product registered, input info. */
ob_start();
$product_id = $_GET['product-id'];
	
if(isset($product_id) && $product_id != '') :
			$product = get_post($product_id);
			$product_sku = get_post_meta( $product_id, 'product_sku' , true);
			$product_price = get_post_meta( $product_id, 'product_price' , true);
			$product_vat = get_post_meta( $product_id, 'product_vat' , true);


	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'edit_product' ) {
	

		// Add the content of the form to $post as an array
		$product_data = array(
			'ID' => $product_id,
			'post_title' => $_POST['post_title'],
			'product_sku' =>$_POST['product_sku'],
			'product_price' => $_POST['product_price'],
			'product_vat' => $_POST['product_vat'],
			'post_author' => get_current_user_id( ),
			'post_status' => 'publish', // Choose: publish, preview, future, etc.
			'post_type' => 'aam-product',  // Use a custom post type if you want to
		);

			// update product data method
			wp_update_post( $product_data);
		 	update_post_meta ( $product_id, 'product_sku', $product_data['product_sku'] );
	        update_post_meta ( $product_id, 'product_price', $product_data['product_price'] );
	        update_post_meta ( $product_id, 'product_vat', $product_data['product_vat'] );
			// set redirection
			//wp_redirect(site_url('product-list'));
		
	} //endif; 

	if ( is_user_logged_in() && current_user_can( 'edit_product' ) && current_user_can( 'publish_product' ) ) : ?>
	
		<form id="aam-product-type" name="aam-product-type" method="post" action="">
		 
			<p><label for="name">Product Name</label><br />
			 
			<input type="text" id="post_title" tabindex="3" name="post_title" value="<?php echo $product->post_title;?>" /></textarea>
			 
			</p>

			<p><label for="sku">Product SKU</label><br />
			 
			<input type="text" id="product_sku"  tabindex="1" name="product_sku" value="<?php echo $product_sku;?>"/>
			 
			</p>
		
			<!-- <p><?php //wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=category' ); ?></p> -->
			 
			<p><label for="price">Price HT </label><br />
			 
			<input type="text"  tabindex="4" name="product_price" id="product_price" value="<?php echo $product_price;?>" /></p>

			<p align="right"><label for="vat">Vat Tax </label><br />
			 
			<select name="product_vat" id="product_vat">
			<option value="<?php echo $product_vat;?>" selected><?php echo $product_vat;?>%</option>
			<option value="10">10%</option>
			<option value="20">20%</option>
			<option value="30">20%</option>
				
			</select> 
			</p>
			 
			<p align="right"><input type="submit" value="Update Product" tabindex="6" id="submit" name="submit" /></p>
			 
			<input type="hidden" name="post-type" id="post-type" value="aam_product" />
			 
			<input type="hidden" name="action" value="edit_product" />
			 
			<?php wp_nonce_field( 'aam_product_nonce','aam_product_nonce' ); ?>
		 
		</form><!-- #addproduct -->

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to do this action at this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>

<?php else: ?>
		<p class="alert">
					<?php _e(' Please select any product to edit', 'wpaam'); ?>
		</p><!-- .alert -->
<?php endif; ?>
		