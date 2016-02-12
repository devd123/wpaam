<?php
/**
 * WPAAM Template: Payments Form Template.
 */

	$invoice_prefix  = get_user_meta( $user_id, 'invoice_prefix', true );
	$invoice_start    = get_user_meta( $user_id, 'invoice_start', true );
	$quotation_prefix = get_user_meta( $user_id, 'quotation_prefix', true );
	$quotation_start   = get_user_meta( $user_id, 'quotation_start', true );
	
	
							
?>

<div id="wpaam-form-profile" class="wpaam-profile-form-wrapper">

	<form id="wpaam-products" name="wpaam-products" method="post" action="" class="wpaam-profile-form">
			 	<p><strong>Invoice Settings</strong></p>
			 	<fieldset  class="fieldset-paypal_username">
					<label for="invoice_prefix">Invoice Prefix <span class="wpaam-required-star">*</span></label>
					<div class="field required-field">
						<input type="text" value="<?php if ( isset($invoice_prefix) ) echo $invoice_prefix; ?>" placeholder="" id="invoice_prefix" name="invoice_prefix" class="input-name">
					</div>
				</fieldset>

				<fieldset class="fieldset-invoice_start">
					<label for="invoice_start">Start Invoice </label>
					<div class="field ">
						<input type="text" value="<?php if ( $invoice_start ) echo $invoice_start; ?>" placeholder="" id="invoice_start" name="invoice_start" class="input-name">
					</div>
				</fieldset>
				<p><strong>Quotation Settings</strong></p>
				<fieldset class="fieldset-quotation_prefix">
					<label for="quotation_prefix">Quotation Prefix</label>
					<div class="field">
						<input type="text" value="<?php if ( $quotation_prefix ) echo $quotation_prefix; ?>" placeholder="" id="quotation_prefix" name="quotation_prefix" class="input-name">
					</div>
				</fieldset>
				<fieldset class="fieldset-quotation_start">
					<label for="quotation_start">Start Quotation </label>
					<div class="field">
						<input type="text" value="<?php if ( $quotation_start ) echo $quotation_start; ?>" placeholder="" id="quotation_start" name="quotation_start" class="input-name">
					</div>
				</fieldset>
			
			
				<?php wp_nonce_field( $form ); ?>
				<p class="wpaam-submit">
					<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
					<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $user_id; ?>" />
					<input type="submit" id="submit_wpaam_other_settings" name="submit_wpaam_other_settings" class="button" value="<?php  _e( 'Save Settings', 'wpaam' ); ?>" />
				</p>
			 
	</form>
</div>
