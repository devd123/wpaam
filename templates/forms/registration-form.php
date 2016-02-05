<?php
/**
 * wpaam Template: Default Registration Form Template.
 *
 * Displays login form.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>
<div id="wpaam-form-register-<?php echo esc_attr( $atts['form_id'] );?>" class="wpaam-registration-form-wrapper">

	<?php do_action( 'wpaam_before_register_form_template', $atts ); ?>

	<form action="#" method="post" id="wpaam-register-<?php echo esc_attr( $atts['form_id'] );?>" class="wpaam-registration-form" name="wpaam-register-<?php echo esc_attr( $atts['form_id'] );?>" enctype="multipart/form-data">

		<?php do_action( 'wpaam_before_inside_register_form_template', $atts ); ?>

		<?php foreach ( $register_fields as $key => $field ) : ?>
			<fieldset class="fieldset-<?php esc_attr_e( $key, 'wpaam' ); ?>" data-type="<?php echo esc_attr( $field['type'] );?>" data-label="<?php echo esc_attr( $field['label'] );?>" data-required="<?php echo esc_attr( $field['required'] );?>" data-name="<?php esc_attr_e( $key, 'wpaam' ); ?>">
				<label for="<?php esc_attr_e( $key, 'wpaam' ); ?>"><?php echo $field['label']; ?><?php if ( ! empty( $field['required'] ) ) echo '<span class="wpaam-required-star">*</span>'; ?></label>
				<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
					<?php do_action( "wpaam/form/{$form}/before/field={$key}", $field ); ?>
					<?php echo wpaam_get_field_input_html( $key, $field ); ?>
					<?php do_action( "wpaam/form/{$form}/after/field={$key}", $field ); ?>
				</div>
			</fieldset>
		<?php endforeach; ?>

		<?php do_action( 'wpaam_after_inside_register_form_template', $atts ); ?>

		<?php wp_nonce_field( $form ); ?>

		<p>
			<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
			<input type="submit" id="submit_wpaam_register" name="submit_wpaam_register" class="button" value="<?php _e( 'Register', 'wpaam' ); ?>" />
		</p>

	</form>

	<?php do_action( 'wpaam_after_register_form_template', $atts ); ?>

</div>
