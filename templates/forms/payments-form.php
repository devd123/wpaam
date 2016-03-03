<?php
/**
 * WPAAM Template: Payments Form Template.
 */

	$paypal_username  = get_user_meta( $user_id, 'paypal_username', true );
	$paypal_apikey    = get_user_meta( $user_id, 'paypal_apikey', true );
	$paypal_signature = get_user_meta( $user_id, 'paypal_signature', true );
	$user_allow_vat   = get_user_meta( $user_id, 'user_allow_vat', true );
	$user_vat_values  = get_user_meta( $user_id, 'user_vat_values', true ); 
	
if ( is_user_logged_in() && current_user_can( 'edit_invoice' ) && current_user_can( 'edit_quotation' ) ) : 							
?>

<div id="wpaam-form-profile" class="wpaam-profile-form-wrapper">

	<form id="wpaam-products" name="wpaam-products" method="post" action="" class="wpaam-profile-form">
			 	<fieldset  class="fieldset-paypal_username">
					<label for="paypal_username">Paypal Username <span class="wpaam-required-star">*</span></label>
					<div class="field required-field">
						<input type="text" value="<?php if ( isset($paypal_username) ) echo $paypal_username; ?>" placeholder="" id="paypal_username" name="paypal_username" class="input-name">
					</div>
				</fieldset>

				<fieldset class="fieldset-paypal_apikey">
					<label for="paypal_apikey">Paypal API Key </label>
					<div class="field ">
						<input type="text" value="<?php if ( $paypal_apikey ) echo $paypal_apikey; ?>" placeholder="" id="paypal_apikey" name="paypal_apikey" class="input-name">
					</div>
				</fieldset>

				<fieldset class="fieldset-paypal_signature">
					<label for="paypal_signature">Paypal API Signature </label>
					<div class="field">
						<input type="text" value="<?php if ( $paypal_signature ) echo $paypal_signature; ?>" placeholder="" id="paypal_signature" name="paypal_signature" class="input-name">
					</div>
				</fieldset>
				
				<p><strong>Tax Settings</strong></p>
				<fieldset class="fieldset-user_allow_vat">
					<label for="user_allow_vat">Allow VAT </label>
					<div class="field">
						<?php
						if($user_allow_vat == 1) :
							$checked = "checked";
						else : 
							$checked = '';
						endif;
						?>
						<input type="checkbox"  value="1" id="user_allow_vat" name="user_allow_vat" class="input-checkbox" <?php echo $checked; ?> />
					</div>
				</fieldset>
				<fieldset class="fieldset-product_vat">
					<label for="product_vat">VAT Values</label>
					<div class="field">
						<select name="user_vat_values" id="user_vat_values" class="select-vat-values">
							<option value="0">Select VAT Value</option>
							<?php 
							$vat_values =  wpaam_get_vat_values();

							foreach ($vat_values as $vat) :
								$vat_value = get_post_meta($vat->ID, 'vat_value', true);
								
								if($user_vat_values == $vat_value) :
									$selected = "selected";
								else : 
									$selected = '';
								endif;
							echo '<option '.$selected.' value='.$vat_value.'>' .$vat->post_title.' ( '.$vat_value.'% ) </option>';
							endforeach;?>
						</select> 
					</div>
				</fieldset>
			
				<?php wp_nonce_field( $form ); ?>
				<p class="wpaam-submit">
					<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
					<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $user_id; ?>" />
					<input type="submit" id="submit_wpaam_payments" name="submit_wpaam_payments" class="button" value="<?php  _e( 'Save Settings', 'wpaam' ); ?>" />
				</p>
			 
	</form>
</div>
<?php endif; ?>