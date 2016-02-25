<?php
/**
 * WPAAM Template: Clients Form Template.
 *
 * Displays settings edit form.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

?>

	<?php if ( is_user_logged_in() && !current_user_can( 'create_users' ) ) : ?>
		<p class="alert">
		<?php _e('You are not allow to see this page !', 'wpaam'); ?>
		</p><!-- .log-in-out .alert -->
	<?php endif;?>
	

	<!-- REGISTER FORM STARTS HERE -->
	<?php if ( current_user_can( 'create_users' ) ) : ?>
 		<?php 
 		if(isset($_GET['client_id'])) {
 		$client_id = $_GET['client_id'];
 		$client = get_userdata( $client_id );

 		/* get user meta values */
 		$civilite = get_user_meta($client->ID, 'civilite', true );
 		$firstname = get_user_meta( $client->ID, 'first_name', true );
 		$lastname = get_user_meta( $client->ID, 'last_name', true );
 		$company_name = get_user_meta( $client->ID, 'company_name', true );
 		$siret = get_user_meta( $client->ID, 'siret', true );
 		$street = get_user_meta( $client->ID, 'street', true );
 		$zip_code = get_user_meta( $client->ID, 'zip_code', true );
 		$city = get_user_meta( $client->ID, 'city', true );
 		$country = get_user_meta( $client->ID, 'country', true );
 		$phone = get_user_meta( $client->ID, 'phone', true );
 		$client_email = get_user_meta( $client->ID, 'client_email', true ); 
 		} ?>
		<form action="#" method="post" id="wpaam-clients" class="wpaam-profile-form" name="wpaam-clients" enctype="multipart/form-data">

			<fieldset data-name="civilite" data-required="1" data-label="Name" data-type="text" class="fieldset-civilite">
				<label for="civilite"><?php _e('Civilite', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="civilite" type="text" id="civilite" value="<?php if ( !empty($civilite) ) echo $civilite; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="first_name" data-required="1" data-label="Name" data-type="text" class="fieldset-first_name">
				<label for="first_name"><?php _e('First Name', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="first_name" type="text" id="first_name" value="<?php if ( !empty($firstname) ) echo $firstname; ?>" required=""/>
				</div>
			</fieldset>
			
			<fieldset data-name="last_name" data-required="1" data-label="Name" data-type="text" class="fieldset-last_name">
				<label for="last_name"><?php _e('Last Name', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="last_name" type="text" id="last_name" value="<?php if ( !empty($lastname) ) echo $lastname; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="company_name" data-required="1" data-label="Name" data-type="text" class="fieldset-post_title">
				<label for="company_name"><?php _e('Company name', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="company_name" type="text" id="company_name" value="<?php if ( !empty($company_name) ) echo $company_name; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="siret" data-required="1" data-label="Name" data-type="text" class="fieldset-siret">
				<label for="siret"><?php _e('SIRET', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="siret" type="text" id="siret" value="<?php if ( !empty($siret) ) echo $siret; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="street" data-required="1" data-label="Name" data-type="text" class="fieldset-street">
				<label for="street"><?php _e('Street', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="street" type="text" id="street" value="<?php if ( !empty($street) ) echo $street; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="zip_code" data-required="1" data-label="Name" data-type="text" class="fieldset-zip_code">
				<label for="zip_code"><?php _e('Zip Code', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="zip_code" type="text" id="zip_code" value="<?php if ( !empty($zip_code) ) echo $zip_code; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="city" data-required="1" data-label="Name" data-type="text" class="fieldset-city">
				<label for="city"><?php _e('City', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="city" type="text" id="city" value="<?php if ( !empty($city) ) echo $city; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="country" data-required="1" data-label="Name" data-type="text" class="fieldset-country">
				<label for="country"><?php _e('Country', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="country" type="text" id="country" value="<?php if ( !empty($country) ) echo $country; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="phone" data-required="1" data-label="Name" data-type="text" class="fieldset-phone">
				<label for="phone"><?php _e('Phone', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="phone" type="text" id="phone" value="<?php if ( !empty($phone) ) echo $phone; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="client_email" data-required="1" data-label="Email" data-type="text" class="fieldset-post_title">
				<label for="email"><?php _e('E-mail', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="client_email" type="email" id="client_email" value="<?php if ( !empty($client_email) ) echo $client_email;?>" required=""/>
				</div>
			</fieldset>

			
			<?php wp_nonce_field( $form ); ?>
			<p class="wpaam-submit">
				<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
				<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $author_id; ?>" />
				<?php if(isset($_GET['client_id']) && $_GET['client_id'] != '') : ?>
				<input type="submit" id="submit_wpaam_edit_clients" name="submit_wpaam_edit_clients" class="button" value="<?php  _e( 'Update Client', 'wpaam' ); ?>" />
				<?php else :?>
				<input type="submit" id="submit_wpaam_add_clients" name="submit_wpaam_add_clients" class="button" value="<?php _e( 'Add Client', 'wpaam' ); ?>" />
				<?php endif; ?>
			</p>

		</form><!-- #adduser -->

	<?php endif; ?>
	<!-- REGISTER FORM ENDS HERE -->