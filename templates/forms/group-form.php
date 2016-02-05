<?php
/**
 * wpaam Template: General form template used to display fields of groups into the account page.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>
<div id="wpaam-form-group" class="wpaam-group-form-wrapper">

	<form action="#" method="post" id="wpaam-group-form" class="wpaam-profile-form" name="wpaam-group-form" enctype="multipart/form-data">

		<?php foreach ( $group_fields as $key => $field ) : ?>
			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?>" data-type="<?php echo esc_attr( $field['type'] );?>" data-label="<?php echo esc_attr( $field['label'] );?>" data-required="<?php echo esc_attr( $field['required'] );?>" data-name="<?php echo esc_attr( $key ); ?>">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?><?php if ( ! empty( $field['required'] ) ) echo '<span class="wpaam-required-star">*</span>'; ?></label>
				<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
					<?php do_action( "wpaam/form/{$form}/before/field={$key}", $field ); ?>
					<?php echo wpaam_get_field_input_html( $key, $field ); ?>
					<?php do_action( "wpaam/form/{$form}/after/field={$key}", $field ); ?>
				</div>
			</fieldset>
		<?php endforeach; ?>

		<?php wp_nonce_field( $form ); ?>

		<p>
			<input type="hidden" name="wpaam_submit_form" value="<?php echo $form; ?>" />
			<input type="hidden" name="wpaam_group_form_id" value="<?php echo $group_id; ?>" />
			<input type="submit" id="submit_wpaam_group_form" name="submit_wpaam_group_form" class="button" value="<?php esc_html_e( 'Update Profile', 'wpaam' ); ?>" />
		</p>

	</form>

</div>
