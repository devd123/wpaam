<?php
/**
** Admin Actions
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display links next to title in settings panel
 *
 * @since 1.0.0
 * @return array
*/
function wpaam_add_links_to_settings_title() {
	echo '<a href="http://neerusite.wordpress.com" class="add-new-h2" target="_blank">'.__('Documentation', 'wpaam').'</a>';
	echo '<a href="http://neerusite.wordpress.com" class="add-new-h2" target="_blank">'.__('Add Ons', 'wpaam').'</a>';
}
add_action( 'wpaam_next_to_settings_title', 'wpaam_add_links_to_settings_title' );

/**
 * Function to display content of the "registration_status" option.
 *
 * @since 1.0.0
 * @return array
*/
function wpaam_option_registration_status() {

	$output = null;

	if( get_option( 'users_can_register' ) ) {
		$output = '<div class="wpaam-admin-message">'.sprintf( __( '<strong>Enabled.</strong> <br/> <small>Registrations can be disabled in <a href="%s" target="_blank">Settings -> General</a>.</small>', 'wpum' ), admin_url( 'options-general.php#users_can_register' ) ).'</div>';
	} else {
		$output = '<div class="wpaam-admin-message">'.sprintf( __( 'Registrations are disabled. Enable the "Membership" option in <a href="%s" target="_blank">Settings -> General</a>.', 'wpum' ), admin_url( 'options-general.php#users_can_register' ) ).'</div>';
	}

	echo $output;

}
add_action( 'wpaam_registration_status', 'wpaam_option_registration_status' );

function wpaam_add_admin_capability() {
    // gets the author role
    $role = get_role( 'administrator' );
    //This only works, because it accesses the class instance.
 	$role->add_cap( 'publish_product' ); 
 	$role->add_cap('edit_products');
	$role->add_cap('edit_product');
	$role->add_cap('delete_product');
	$role->add_cap('read_product');
	$role->add_cap('publish_quotation');
	$role->add_cap('edit_quotations');
	$role->add_cap('edit_quotation');
	$role->add_cap('delete_quotation');
	$role->add_cap('read_quotation');
	$role->add_cap('publish_invoice');
	$role->add_cap('edit_invoices');
	$role->add_cap('edit_invoice');
	$role->add_cap('delete_invoice');
	$role->add_cap('read_invoice');
}
add_action( 'admin_init', 'wpaam_add_admin_capability');

// remove user roles button
function wpaam_option_remove_roles() {

	$output = '<a id="wpaam-remove-roles" href="'.esc_url( add_query_arg( array('tool' => 'remove-roles') , admin_url( 'users.php?page=wpaam-settings&tab=tools' ) ) ).'" class="button">'.__('Remoce custom roles', 'wpaam').'</a>';
	$output .= '<br/><p class="description">' . __('Click the button to remove the custom user roles of the site.', 'wpaam') . '</p>';
	$output .= wp_nonce_field( "wpaam_nonce_roles_remove", "wpaam_user_roles_security" );

	echo $output;
	
}
add_action( 'wpaam_remove_roles', 'wpaam_option_remove_roles' );

/**
 * Function to display content of the "registration_role" option.
 *
 * @since 1.0.0
 * @return array
*/
function wpaam_option_registration_role() {

	$role = get_option( 'default_role' );

	$output = '<span class="wpaam-role-option">'.$role.'.</span>';
	$output .= '<br/><small>'.sprintf( __('The default user role for registrations can be changed in <a href="%s">Settings -> General</a>', 'wpum'), admin_url( 'options-general.php#default_role' ) ).'</small>';

	echo $output;

}
add_action( 'wpaam_registration_role', 'wpaam_option_registration_role' );

/**
 * Function to display content of the "restore_emails" option.
 *
 * @since 1.0.0
 * @return array
*/
function wpaam_option_restore_emails() {

	$output = '<a id="wpaam-restore-emails" href="'.esc_url( add_query_arg( array('tool' => 'restore-email') , admin_url( 'users.php?page=wpaam-settings&tab=tools' ) ) ).'" class="button">'.__('Restore default emails', 'wpaam').'</a>';
	$output .= '<br/><p class="description">' . __('Click the button to restore the default emails content and subject.', 'wpaam') . '</p>';
	$output .= wp_nonce_field( "wpaam_nonce_email_reset", "wpaam_backend_security" );

	echo $output;

}
add_action( 'wpaam_restore_emails', 'wpaam_option_restore_emails' );

/**
 * Function to display content of the "restore_default_pages" option.
 *
 * @since 1.0.0
 * @return array
*/
function wpaam_option_restore_pages() {

	$output = '<a id="wpaam-restore-pages" href="'.esc_url( add_query_arg( array('tool' => 'restore-pages') , admin_url( 'users.php?page=wpaam-settings&tab=tools' ) ) ).'" class="button">'.__('Restore default pages', 'wpaam').'</a>';
	$output .= '<br/><p class="description">' . __('Click the button to restore the default core pages of the plugin.', 'wpaam') . '</p>';
	$output .= wp_nonce_field( "wpaam_nonce_default_pages_restore", "wpaam_backend_pages_restore" );

	echo $output;

}
add_action( 'wpaam_restore_pages', 'wpaam_option_restore_pages' );


/**
 * Runs pages setup
 *
 * @since 1.0.0
 * @return void
*/
function wpaam_run_pages_setup() {

	if( is_admin() && current_user_can( 'manage_options' ) && isset( $_GET['wpaam_action'] ) && $_GET['wpaam_action'] == 'install_pages' || is_admin() && current_user_can( 'manage_options' ) && isset( $_GET['tool'] ) && $_GET['tool'] == 'restore-pages' ) :
		wpaam_generate_pages( true );
	endif;

}
add_action( 'admin_init', 'wpaam_run_pages_setup' );

/**
 * Add new quicktag when editing email.
 *
 * @since 1.0.0
 * @return void
*/
function wpaam_new_line_quicktag() {

	$screen = get_current_screen();

	if ( wp_script_is( 'quicktags' ) && $screen->base == 'users_page_wpaam-edit-email' ) {
	?>
	<script type="text/javascript">
	QTags.addButton( 'br', "<?php _e('Add New Line', 'wpaam');?>", '<br/>', '', 's', "<?php _e('Add New Line', 'wpaam');?>", 1 );
	</script>
	<?php
	}

}
add_action( 'admin_print_footer_scripts', 'wpaam_new_line_quicktag' );

/**
 * Check if plugin has been installed on this site for more than 14 days.
 *
 * @since 1.0.0
 * @return void
*/
function wpaam_check_installation_date() {

	$install_date = get_option( 'wpaam_activation_date' );
  $past_date = strtotime( '-14 days' );

 	// Delete the notice
  if( isset( $_GET['hide_rating_notice'] ) && $_GET['hide_rating_notice'] == 1 ) {
  	delete_option( 'wpaam_activation_date' );
  	wp_redirect( admin_url() );
  	exit();
  }

  // Display the notice
  if ( $install_date && $past_date >= $install_date ) {
      //add_action( 'admin_notices', 'wpaam_display_rating_notice' );
  }

}
add_action( 'admin_init', 'wpaam_check_installation_date' );

/**
 * Display rating notice.
 *
 * @since 1.0.0
 * @return void
*/

function wpaam_display_rating_notice() {

	$url_rate = 'http://wordpress.org/support/';
	$remove_url = add_query_arg( array( 'hide_rating_notice' => true ), admin_url() );

  ?>
  <div class="updated">
      <p><?php echo sprintf( __( "Hey, looks like you've been using the <b>Advance Accountability Manages</b> plugin for some time now - that's awesome! <br/> Could you please give it a review on wordpress.org ? Just to help us spread the word and boost our motivation :) <br/> <br/><a href='%s' class='button button-primary' target='_blank'>Yes, you deserve it!</a> - <a href='%s'>I've already done this!</a>", 'wpaam' ), $url_rate, esc_url( $remove_url ) ); ?></p>
  </div>
  <?php

}



