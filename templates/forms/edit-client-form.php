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
 		$firstname = get_user_meta( $client->ID, 'first_name', true );
 		$lastname = get_user_meta( $client->ID, 'last_name', true );
 		$company_name = get_user_meta( $client->ID, 'company_name', true );
 		$client_email = get_user_meta( $client->ID, 'client_email', true ); 
 		} ?>
		<form action="#" method="post" id="wpaam-clients" class="wpaam-profile-form" name="wpaam-clients" enctype="multipart/form-data">

			
			<fieldset data-name="post_title" data-required="1" data-label="Name" data-type="text" class="fieldset-post_title">
				<label for="first_name"><?php _e('First Name', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="first_name" type="text" id="first_name" value="<?php if ( !empty($firstname) ) echo $firstname; ?>" required=""/>
				</div>
			</fieldset>
			
			<fieldset data-name="post_title" data-required="1" data-label="Name" data-type="text" class="fieldset-post_title">
				<label for="last_name"><?php _e('Last Name', 'wpaam'); ?></label>
				<div class="field required-field">
				<input class="text-input" name="last_name" type="text" id="last_name" value="<?php if ( !empty($lastname) ) echo $lastname; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="company_name" data-required="1" data-label="Name" data-type="text" class="fieldset-post_title">
				<label for="company_name"><?php _e('company_name', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="company_name" type="text" id="company_name" value="<?php if ( !empty($company_name) ) echo $company_name; ?>" required=""/>
				</div>
			</fieldset>

			<fieldset data-name="client_email" data-required="1" data-label="Email" data-type="text" class="fieldset-post_title">
				<label for="email"><?php _e('E-mail', 'wpaam'); ?><span class="wpaam-required-star">*</span></label>
				<div class="field required-field">
				<input class="text-input" name="client_email" type="email" id="client_email" value="<?php if ( !empty($client_email) ) echo $client_email;?>" required=""/>
				</div>
			</fieldset>

			
			<?php /*?>
			<p class="form-website">
				<label for="website"><?php _e('Website', 'wpaam'); ?></label>
				<input class="text-input" name="website" type="text" id="website" value="<?php if ( $error ) echo wp_specialchars( $_POST['website'], 1 ); ?>" />
			</p><!-- .form-website -->
			
			<p class="form-street">
				<label for="street"><?php _e('Street', 'wpaam'); ?></label>
				<input class="text-input" name="street" type="text" id="street" value="<?php if ( $error ) echo wp_specialchars( $_POST['street'], 1 ); ?>" />
			</p><!-- .form-aim -->
			
			<p class="form-zipcode">
				<label for="yim"><?php _e('Zip Code', 'wpaam'); ?></label>
				<input class="text-input" name="zipcode" type="text" id="zipcode" value="<?php if ( $error ) echo wp_specialchars( $_POST['zipcode'], 1 ); ?>" />
			</p><!-- .form-yim -->
			
			<p class="form-city">
				<label for="city"><?php _e('City', 'wpaam'); ?></label>
				<input class="text-input" name="city" type="text" id="city" value="<?php if ( $error ) echo wp_specialchars( $_POST['city'], 1 ); ?>" />
			</p><!-- .form-yim -->
			
			<p class="form-country">
				<label for="yim"><?php _e('Country', 'wpaam'); ?></label>
				<input class="text-input" name="country" type="text" id="country" value="<?php if ( $error ) echo wp_specialchars( $_POST['country'], 1 ); ?>" />
			</p><!-- .form-yim -->
			<?php */ ?>
	
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