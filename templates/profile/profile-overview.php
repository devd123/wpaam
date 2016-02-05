<?php
/**
 * wpaam Template: "Overview" profile tab.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>

<div class="wpaam-user-details-list">

	<?php do_action( 'wpaam_before_user_details_list', $user_data, $tabs, $slug ); ?>

	<!-- Start fields loop -->
	<?php if ( wpaam_has_profile_fields() ) : ?>

		<?php while ( wpaam_profile_field_groups() ) : wpaam_the_profile_field_group(); ?>

			<?php if ( wpaam_field_group_has_fields() ) : ?>

				<?php if( wpaam_get_field_group_name() ) : ?>
					<h3 class="group-title"><?php echo esc_html( wpaam_get_field_group_name() ); ?></h3>
				<?php endif; ?>

				<?php if( wpaam_get_field_group_description() ) : ?>
					<p class="group-description"><?php echo esc_html( wpaam_get_field_group_description() ); ?><p>
				<?php endif; ?>

				<!-- loop through each field -->
				<dl>
				<?php while ( wpaam_profile_fields() ) : wpaam_the_profile_field(); ?>

					<?php if ( wpaam_field_has_data() ) : ?>
					<dt class="<?php wpaam_the_field_css_class(); ?>"><?php wpaam_the_field_name(); ?>:</dt>
			    <dd><?php wpaam_the_field_value(); ?></dd>
					<?php endif; ?>

				<?php endwhile; ?>
				</dl>
				<!-- end loop through each field -->

			<?php endif; ?>

		<?php endwhile; ?>

	<?php endif; ?>
	<!-- end fields loop -->

	<?php do_action( 'wpaam_after_user_details_list', $user_data, $tabs, $slug ); ?>

</div>
