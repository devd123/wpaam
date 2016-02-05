<?php
/**
 * WPAAM Template: Add Product Form Template.
 * @since       1.0.0
 */
	/* If product registered, input info. */
	

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'aam_product' ) {
	

		// Add the content of the form to $post as an array
		$product_data = array(
			'post_title' => $_POST['post_title'],
			'product_sku' =>$_POST['product_sku'],
			'product_price' => $_POST['product_price'],
			'product_vat' => $_POST['product_vat'],
			'post_author' => get_current_user_id( ),
			'post_status' => 'publish', // Choose: publish, preview, future, etc.
			'post_type' => 'aam-product',  // Use a custom post type if you want to
		);

			$title = $product_data['post_title'];
		    global $wpdb;
		    $sql = "SELECT ID FROM dd_posts WHERE post_title = '" . $title . "' && post_status = 'publish' && post_type = 'aam-product' "; 
		    $return = $wpdb->get_row( $sql );
		   
		    if( empty( $return ) ) {
		        $postid = wp_insert_post( $product_data , $wp_error); 
		        update_post_meta ( $postid, 'product_sku', $product_data['product_sku'] );
		        update_post_meta ( $postid, 'product_price', $product_data['product_price'] );
		        update_post_meta ( $postid, 'product_vat', $product_data['product_vat'] );
		        ?>
				<p class="alert">
					<?php _e(' you have added a new product', 'wpaam'); ?>
				</p><!-- .alert -->
		    <?php } else {
		        echo "This title already you have added";
		    }
	
	} //endif; 

?>
	
	<?php if ( is_user_logged_in() && current_user_can( 'edit_product' ) && current_user_can( 'publish_product' ) ) : ?>

		<form id="aam-product-type" name="aam-product-type" method="post" action="">
		 
			<p><label for="name">Product Name</label><br />
			 
			<input type="text" id="post_title" name="post_title" value="" /></textarea>
			 
			</p>

			<p><label for="sku">Product SKU</label><br />
			 
			<input type="text" id="product_sku" name="product_sku" value=""/>
			 
			</p>
		
			<!-- <p><?php //wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=category' ); ?></p> -->
			 
			<p><label for="price">Price HT </label><br />
			 
			<input type="text" name="product_price" id="product_price" value="" /></p>

			<p><label for="vat">Vat Tax </label><br />
			 
			<select name="product_vat" id="product_vat">
				<option value="0">Select Vat</option>
				<option value="10">10%</option>
				<option value="20">20%</option>
				<option value="30">30%</option>
				
			</select> 
			</p>
			 
			<p align="right"><input type="submit" value="Add Product" tabindex="6" id="submit" name="submit" /></p>
			 
			<input type="hidden" name="post-type" id="post-type" value="aam_product" />
			 
			<input type="hidden" name="action" value="aam_product" />
			 
			<?php wp_nonce_field( 'aam_product_nonce','aam_product_nonce' ); ?>
		 
		</form><!-- #addproduct -->

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to see this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>
		