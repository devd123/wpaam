<?php
/**
 * WPAAM Template: Account Form Template.
 */
	$user  = get_userdata( $user_id );
	$company_name  = get_user_meta( $user_id, 'company_name', true );
	$company_status    = get_user_meta( $user_id, 'company_status', true );
	$company_logo = get_user_meta( $user_id, 'company_logo', true );
	$description = get_user_meta( $user_id, 'description', true );
	$first_name   = get_user_meta( $user_id, 'first_name', true );
	$family_name  = get_user_meta( $user_id, 'last_name', true ); 
	$client_prefix  = get_user_meta( $user_id, 'client_prefix', true ); 
							
?>

<div id="wpaam-form-profile" class="wpaam-profile-form-wrapper">

	<form id="wpaam-account" name="wpaam-account" method="post" action="" class="wpaam-profile-form">
			 	
			 	<fieldset  class="fieldset-user_name">
					<label for="user_name">User Name </label>
					<div class="field required-field">
						<input type="text" value="<?php if ( isset($user) ) echo $user->user_login; ?>" placeholder="" id="user_name" name="user_name" class="input-name" disabled>
					</div> 
				</fieldset>

			 	<fieldset  class="fieldset-company_name"> 
					<label for="company_name">Company Name </label>
					<div class="field required-field">
						<input type="text" value="<?php if ( isset($company_name) ) echo $company_name; ?>" placeholder="" id="company_name" name="company_name" class="input-name">
					</div>
				</fieldset>

				<fieldset class="fieldset-company_status">
					<label for="company_status">Company Status </label>
					<div class="field ">
						<input type="text" value="<?php if ( $company_status ) echo $company_status; ?>" placeholder="" id="company_status" name="company_status" class="input-name">
					</div>
				</fieldset>

			
			
				<fieldset class="fieldset-description">
					<label for="description"> Description </label>
					<div class="field">
						<textarea cols="20" rows="3" class="input-text " name="description" id="description" placeholder="" maxlength=""><?php if ( $description ) echo $description; ?></textarea>
					</div>
				</fieldset>

				<fieldset class="fieldset-first_name">
					<label for="first_name">First Name </label>
					<div class="field">
						<input type="text" value="<?php if ( $first_name ) echo $first_name; ?>" placeholder="" id="first_name" name="first_name" class="input-name">
					</div>
				</fieldset>

				<fieldset class="fieldset-family_name">
					<label for="family_name">Responsible Family Name </label>
					<div class="field">
						<input type="text" value="<?php if ( $family_name ) echo $family_name; ?>" placeholder="" id="family_name" name="family_name" class="input-name">
					</div>
				</fieldset>

				<fieldset class="fieldset-client_prefix">
					<label for="client_prefix">Set Client Prefix </label>
					<div class="field">
						<input type="text" value="<?php if ( $client_prefix ) echo $client_prefix; ?>" placeholder="" id="client_prefix" name="client_prefix" class="input-name">
					</div>
				</fieldset>
				<?php if( wpaam_get_option( 'custom_avatars' ) ) : ?>				
				<fieldset class="fieldset-company_logo" data-type="file" data-label="Profile Picture" data-required="0" data-name="company_logo">
				<label for="company_logo">Company Logo </label>
				<div class="field ">
					<div class="wpaam-uploaded-files"> </div>
					<input id="company_logo" class="input-upload" type="file" name="company_logo">
					<small class="description"> Maximum file size: 2 MB.</small>
				</div>
				</fieldset>
			<?php endif;?>
			
				<?php wp_nonce_field( $form ); ?>
				<p class="wpaam-submit">
					<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
					<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $user_id; ?>" />
					<input type="submit" id="submit_wpaam_profile" name="submit_wpaam_profile" class="button" value="<?php  _e( 'Update Account', 'wpaam' ); ?>" />
				</p>
			 
	</form>
</div>
