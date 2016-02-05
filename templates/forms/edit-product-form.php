<?php
/**
 * WPAAM Template: Add Product Form Template.
 * @since       1.0.0
 */

 		
	if ( is_user_logged_in() && current_user_can( 'edit_product' ) && current_user_can( 'publish_product' ) ) : 
		if(isset($_GET['product_id'])) {
		$product_id = $_GET['product_id'];
		$product = get_post( $product_id );
 		$product_sku = get_post_meta( $product->ID, 'product_sku', true );
 		$product_price = get_post_meta( $product->ID, 'product_price', true );
 		$product_vat = get_post_meta( $product->ID, 'product_vat', true ); 
		} ?>

		<form id="wpaam-products" name="wpaam-products" method="post" action="" class="wpaam-profile-form">
		 	<fieldset data-name="post_title" data-required="1" data-label="Name" data-type="text" class="fieldset-post_title">
				<label for="post_title">Product Name <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<input type="text" required="" maxlength="" value="<?php if ( $product->post_title ) echo $product->post_title; ?>" placeholder="" id="post_title" name="post_title" class="input-name">
				</div>
			</fieldset>

			<fieldset data-name="product_sku" data-required="1" data-label="SKU" data-type="text" class="fieldset-product_sku">
				<label for="product_sku">Product SKU <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<input type="text" required="" maxlength="" value="<?php if ( $product_sku ) echo $product_sku; ?>" placeholder="" id="product_sku" name="product_sku" class="input-sku">
				</div>
			</fieldset>

			<fieldset data-name="product_price" data-required="1" data-label="Price" data-type="text" class="fieldset-product_price">
				<label for="product_price">Product Price <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
					<input type="text" required="" maxlength="" value="<?php if ( $product_price ) echo $product_price; ?>" placeholder="" id="product_price" name="product_price" class="input-price">
				</div>
			</fieldset>

			<fieldset data-name="product_vat" data-required="1" data-label="VAT" data-type="select" class="fieldset-product_vat">
				<label for="product_vat">Product VAT <span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<select name="product_vat" id="product_vat" class="select-vat">
					<option value="0">Select Vat</option>
					<option value="10">10%</option>
					<option value="20">20%</option>
					<option value="30">30%</option>
				</select> 
				</div>
			</fieldset>
		
			<?php wp_nonce_field( $form ); ?>
			<p class="wpaam-submit">
				<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
				<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $author_id; ?>" />
				<?php if(isset($_GET['product_id']) && $_GET['product_id'] != '') : ?>
				<input type="submit" id="submit_wpaam_edit_products" name="submit_wpaam_edit_products" class="button" value="<?php  _e( 'Update Product', 'wpaam' ); ?>" />
				<?php else :?>
				<input type="submit" id="submit_wpaam_add_products" name="submit_wpaam_add_products" class="button" value="<?php _e( 'Add Product', 'wpaam' ); ?>" />
				<?php endif; ?>
			</p>
		 
		</form><!-- #addproduct -->

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to see this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>
		