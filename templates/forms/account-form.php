<?php
/**
 * WPAAM Template: Account Form Template.
 *
 * Displays account edit form.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>

<div id="wpaam-form-profile" class="wpaam-profile-form-wrapper">

	<?php do_action( 'wpaam_before_account_form', $atts ); ?>

	<form action="#" method="post" id="wpaam-profile" class="wpaam-profile-form" name="wpaam-profile" enctype="multipart/form-data">

		<?php do_action( 'wpaam_top_account_form', $atts ); ?>

		<!-- Start Name Fields -->
		<?php foreach ( $fields as $key => $field ) : ?>
			<fieldset class="fieldset-<?php esc_attr_e( $key, 'wpaam' ); ?>" data-type="<?php echo esc_attr( $field['type'] );?>" data-label="<?php echo esc_attr( $field['label'] );?>" data-required="<?php echo esc_attr( $field['required'] );?>" data-name="<?php esc_attr_e( $key, 'wpaam' ); ?>">
				<label for="<?php esc_attr_e( $key, 'wpaam' ); ?>"><?php echo $field['label']; ?> <?php if ( ! empty( $field['required'] ) ) echo '<span class="wpaam-required-star">*</span>'; ?></label>
				<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
					<?php do_action( "wpaam_before_single_{$field['type']}_field", $form, $field ); ?>
					<?php echo wpaam_get_field_input_html( $key, $field ); ?>
					<?php do_action( "wpaam_after_single_{$field['type']}_field", $form, $field ); ?>
				</div>
			</fieldset>
		<?php endforeach; ?>
		<!-- End Name Fields -->

		<?php do_action( 'wpaam_bottom_account_form', $atts ); ?>

		<?php wp_nonce_field( $form ); ?>

		<p class="wpaam-submit">
			<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
			<input type="hidden" name="wpaam_user_id" id="wpaam_user_id" value="<?php echo $user_id; ?>" />
			<input type="submit" id="submit_wpaam_profile" name="submit_wpaam_profile" class="button" value="<?php _e( 'Update Profile', 'wpaam' ); ?>" />
		</p>

	</form>

	<?php do_action( 'wpaam_after_account_form', $atts ); ?>

</div>
