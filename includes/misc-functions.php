<?php
/**
 * Misc Functions
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Retrieve a list of all published pages
 *
 * On large sites this can be expensive, so only load if on the settings page or $force is set to true
 *
 * @since 1.0.0
 * @param bool  $force Force the pages to be loaded even if not on settings
 * @return array $pages_options An array of the pages
 */
function wpaam_get_pages( $force = false ) {

	$pages_options = array( 0 => '' ); // Blank option

	if ( ( ! isset( $_GET['page'] ) || 'wpaam-settings' != $_GET['page'] ) && ! $force ) {
		return $pages_options;
	}

	$pages = get_pages();
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}
	
	return $pages_options;
}


 /**
 * Retrieve a list of all user roles
 *
 * On large sites this can be expensive, so only load if on the settings page or $force is set to true
 *
 * @since 1.0.0
 * @param bool    $force Force the roles to be loaded even if not on settings
 * @return array $roles An array of the roles
 */
function wpaam_get_roles( $force = false ) {

	$roles_options = array( 0 => '' ); // Blank option

	if ( ( ! isset( $_GET['page'] ) || 'wpaam-settings' != $_GET['page'] ) && ! $force ) {
		return $roles_options;
	}

	global $wp_roles;

	$roles = $wp_roles->get_names();

	return apply_filters( 'wpaam_get_roles', $roles );
}

/**
 * Retrieve a list of allowed users role on the registration page
 *
 * @since 1.0.0
 * @return array $roles An array of the roles
 */
function wpaam_get_allowed_user_roles() {

	global $wp_roles;

	if ( ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();

	$user_roles         = array();
	$selected_roles     = wpaam_get_option( 'register_roles' );
	$allowed_user_roles = is_array( $selected_roles ) ? $selected_roles: array( $selected_roles );

	foreach ( $allowed_user_roles as $role ) {
		$user_roles[ $role ] = $wp_roles->roles[ $role ]['name'];
	}

	return $user_roles;

}

/**
 * Retrieve a list of disabled usernames
 *
 * @since 1.0.0
 * @return array $usernames An array of the usernames
 */
function wpaam_get_disabled_usernames() {

	$usernames = array();

	if ( wpaam_get_option( 'exclude_usernames' ) ) {

		$list = trim( wpaam_get_option( 'exclude_usernames' ) );
		$list = explode( "\n", str_replace( "\r", "", $list ) );

		foreach ( $list as $username ) {
			$usernames[] = $username;
		}

	}

	return array_flip( $usernames );

}

/**
 * Get a list of available permalink structures.
 *
 * @since 1.0.0
 * @return array of all the structures.
 */
function wpaam_get_permalink_structures() {

	$structures = array(
		'user_id' => array(
			'name'   => 'user_id',
			'label'  => _x( 'Display user ID', 'Permalink structure', 'wpaam' ),
			'sample' => '123'
		),
		'username' => array(
			'name'   => 'username',
			'label'  => _x( 'Display username', 'Permalink structure', 'wpaam' ),
			'sample' => _x( 'username', 'Example of permalink setting', 'wpaam' )
		),
		'nickname' => array(
			'name'   => 'nickname',
			'label'  => _x( 'Display nickname', 'Permalink structure', 'wpaam' ),
			'sample' => _x( 'nickname', 'Example of permalink setting', 'wpaam' )
		),
	);

	return apply_filters( 'wpaam_get_permalink_structures', $structures );
}

/**
 * Get ID of a core page.
 *
 * @since 1.0.0
 * @param string  $name the name of the page. Supports: login, register, password, account, profile.
 * @return int $id of the core page.
 */
function wpaam_get_core_page_id( $page ) {

	$id = null;

	switch ( $page ) {
		case 'login':
			$id = wpaam_get_option( 'login_page' );
			break;
		case 'register':
			$id = wpaam_get_option( 'registration_page' );
			break;
		case 'password':
			$id = wpaam_get_option( 'password_recovery_page' );
			break;
		case 'account':
			$id = wpaam_get_option( 'account_page' );
			break;
		case 'profile':
			$id = wpaam_get_option( 'profile_page' );
			break;
		case 'clients':
			$id = wpaam_get_option( 'clients_page' );
			break;
		case 'products':
			$id = wpaam_get_option( 'products_page' );
			break;
		case 'invoices':
			$id = wpaam_get_option( 'invoices_page' );
			break;
		case 'creditmemos':
			$id = wpaam_get_option( 'creditmemos_page' );
			break;

	}

	return $id;
}

/**
 * Get URL of a core page.
 *
 * @since 1.0.0
 * @param string  $name the name of the page. Supports: login, register, password, account, profile.
 * @return string $url of the core page.
 */
function wpaam_get_core_page_url( $page ) {

	$url = null;

	switch ( $page ) {
		case 'login':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'login' ) ) );
			break;
		case 'register':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'register' ) ) );
			break;
		case 'password':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'password' ) ) );
			break;
		case 'account':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'account' ) ) );
			break;
		case 'profile':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'profile' ) ) );
			break;
		case 'clients':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'clients' ) ) );
			break;
		case 'products':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'products' ) ) );
			break;
		case 'quotations':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'quotations' ) ) );
			break;
		case 'invoices':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'invoices' ) ) );
			break;
		case 'creditmemos':
			$url = esc_url( get_permalink( wpaam_get_core_page_id( 'creditmemos' ) ) );
			break;
	}

	return apply_filters( 'wpaam_get_core_page_url', $url, $page );
}

/**
 * Display a message loading the message.php template file.
 *
 * @since 1.0.0
 * @param string  $id   html ID attribute.
 * @param string  $type message type: success/notice/error.
 * @param string  $text the text of the message.
 * @return void
 */
function wpaam_message( $args ) {

	$defaults = array(
		'id'   => 'wpaam-notice', // html ID attribute
		'type' => 'success', // message type: success/notice/error.
		'text' => '' // the text of the message.
	);

	// Parse incoming $args into an array and merge it with $defaults
	$args = wp_parse_args( $args, $defaults );

	echo get_wpaam_template( 'message.php', array(
			'id'   => $args['id'],
			'type' => $args['type'],
			'text' => $args['text']
		)
	);

}

/**
 * Gets a list of users orderded by most recent registration date.
 *
 * @since 1.0.0
 * @param int     $amount amount of users to load.
 * @return void
 */
function wpaam_get_recent_users( $amount ) {

	$args = array(
		'number'  => $amount,
		'order'   => 'DESC',
		'orderby' => 'registered'
	);

	// The Query
	$user_query = new WP_User_Query( apply_filters( 'wpaam_get_recent_users', $args ) );

	// Get the results
	$users = $user_query->get_results();

	return $users;
}

/**
 * Check if a given nickname already exists.
 *
 * @since 1.0.0
 * @param string  $nickname
 * @return bool
 */
function wpaam_nickname_exists( $nickname ) {

	$exists = false;

	$args = array(
		'fields'         => 'user_nicename',
		'search'         => $nickname,
		'search_columns' => array( 'user_nicename' )
	);

	// The Query
	$user_query = new WP_User_Query( $args );

	// Get the results
	$users = $user_query->get_results();

	if ( !empty( $users ) )
		$exists = true;

	return $exists;

}

/**
 * Force 404 error headers.
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_trigger_404() {

	global $wp_query;

	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();

}

/**
 * Given $user_data checks against $method_type if the user exists.
 *
 * @since 1.0.0
 * @param string  $user_data   Either ID/Username/Nickname
 * @param string  $method_type Either user_id/username/nickname - usually retrieve thorugh get_option('wpaam_permalink')
 * @return bool
 */
function wpaam_user_exists( $user_data, $method_type ) {

	$exists = false;

	// Check if user exists by ID
	if ( !empty( $user_data ) && $method_type == 'user_id' && get_user_by( 'id', intval( $user_data ) ) ) {
		$exists = true;
	}

	// Check if user exists by username
	if ( !empty( $user_data ) && $method_type == 'username' && get_user_by( 'login', esc_attr( $user_data ) ) ) {
		$exists = true;
	}

	// Check if user exists by nickname
	if ( !empty( $user_data ) && $method_type == 'nickname' && wpaam_nickname_exists( $user_data ) ) {
		$exists = true;
	}

	return $exists;

}

/**
 * Triggers the mechanism to upload files.
 *
 * @copyright mikejolley
 * @since 1.0.0
 * @param array   $file_data Array of $_FILE data to upload.
 * @return array|WP_Error Array of objects containing either file information or an error
 */
function wpaam_trigger_upload_file( $field_key, $field ) {

	if ( isset( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ] ) && ! empty( $_FILES[ $field_key ]['name'] ) ) {

		if( $field_key == 'user_avatar' ) {
			add_filter( 'upload_mimes' , 'wpaam_adjust_mime_types' );
		}

		$allowed_mime_types = get_allowed_mime_types();

		$file_urls       = array();
		$files_to_upload = wpaam_prepare_uploaded_files( $_FILES[ $field_key ] );

		foreach ( $files_to_upload as $file_key => $file_to_upload ) {

			// Trigger validation rules for avatar only.
			if( $field_key == 'user_avatar' ) {

				if ( !in_array( $file_to_upload['type'] , $allowed_mime_types ) )
					return new WP_Error( 'validation-error', sprintf( __( 'Allowed files types are: %s', 'wpaam' ), implode( ', ', array_keys( $allowed_mime_types ) ) ) );

				if ( defined( 'wpaam_MAX_AVATAR_SIZE' ) && $field_key == 'user_avatar' && $file_to_upload['size'] > wpaam_MAX_AVATAR_SIZE )
					return new WP_Error( 'avatar-too-big', __( 'The uploaded file is too big.', 'wpaam' ) );

			} else {

				// Trigger verification for other file fields.
				if( array_key_exists( 'allowed_extensions' , $field ) && is_array( $field['allowed_extensions'] ) ) {

					$allowed_field_extensions = $field['allowed_extensions'];
					$uploaded_file_extension  = pathinfo( $file_to_upload['name'] );
					$uploaded_file_extension  = $uploaded_file_extension['extension'];

					if( ! in_array( $uploaded_file_extension , $allowed_field_extensions ) ) {
						return new WP_Error( 'validation-error', sprintf( esc_html__( 'Error: the "%s" field allows only %s files to be uploaded.', 'wpaam' ), $field['label'], implode ( ", ", $allowed_field_extensions ) ) );
					}

				}

			}

			$uploaded_file = wpaam_upload_file( $file_to_upload, array( 'file_key' => $file_key ) );

			if ( is_wp_error( $uploaded_file ) ) {

				return new WP_Error( 'validation-error', $uploaded_file->get_error_message() );

			} else {

				$file_urls[] = array(
					'url'  => $uploaded_file->url,
					'path' => $uploaded_file->path,
					'size' => $uploaded_file->size
				);

			}

		}

		if ( ! empty( $field['multiple'] ) ) {
			return $file_urls;
		} else {
			return current( $file_urls );
		}

		if( $field_key == 'user_avatar' ) {
			remove_filter( 'upload_mimes' , 'wpaam_adjust_mime_types' );
		}

		return $files_to_upload;
	}

}

/**
 * Prepare the files to upload.
 *
 * @copyright mikejolley
 * @since 1.0.0
 * @param array   $file_data Array of $_FILE data to upload.
 * @return array|WP_Error Array of objects containing either file information or an error
 */
function wpaam_prepare_uploaded_files( $file_data ) {
	$files_to_upload = array();

	if ( is_array( $file_data['name'] ) ) {
		foreach ( $file_data['name'] as $file_data_key => $file_data_value ) {

			if ( $file_data['name'][ $file_data_key ] ) {
				$files_to_upload[] = array(
					'name'     => $file_data['name'][ $file_data_key ],
					'type'     => $file_data['type'][ $file_data_key ],
					'tmp_name' => $file_data['tmp_name'][ $file_data_key ],
					'error'    => $file_data['error'][ $file_data_key ],
					'size'     => $file_data['size'][ $file_data_key ]
				);
			}
		}
	} else {
		$files_to_upload[] = $file_data;
	}

	return $files_to_upload;
}

/**
 * Upload a file using WordPress file API.
 *
 * @since 1.0.0
 * @copyright mikejolley
 * @param array   $file_data Array of $_FILE data to upload.
 * @param array   $args      Optional arguments
 * @return array|WP_Error Array of objects containing either file information or an error
 */
function wpaam_upload_file( $file, $args = array() ) {
	global $wpaam_upload, $wpaam_uploading_file;

	include_once ABSPATH . 'wp-admin/includes/file.php';
	include_once ABSPATH . 'wp-admin/includes/media.php';

	$args = wp_parse_args( $args, array(
			'file_key'           => '',
			'file_label'         => '',
			'allowed_mime_types' => get_allowed_mime_types()
		) );

	$wpaam_upload         = true;
	$wpaam_uploading_file = $args['file_key'];
	$uploaded_file              = new stdClass();

	if ( ! in_array( $file['type'], $args['allowed_mime_types'] ) ) {
		if ( $args['file_label'] ) {
			return new WP_Error( 'upload', sprintf( __( '"%s" (filetype %s) needs to be one of the following file types: %s', 'wpaam' ), $args['file_label'], $file['type'], implode( ', ', array_keys( $args['allowed_mime_types'] ) ) ) );
		} else {
			return new WP_Error( 'upload', sprintf( __( 'Uploaded files need to be one of the following file types: %s', 'wpaam' ), implode( ', ', array_keys( $args['allowed_mime_types'] ) ) ) );
		}
	} else {
		$upload = wp_handle_upload( $file, apply_filters( 'submit_wpaam_handle_upload_overrides', array( 'test_form' => false ) ) );
		if ( ! empty( $upload['error'] ) ) {
			return new WP_Error( 'upload', $upload['error'] );
		} else {
			$uploaded_file->url       = $upload['url'];
			$uploaded_file->name      = basename( $upload['file'] );
			$uploaded_file->path      = $upload['file'];
			$uploaded_file->type      = $upload['type'];
			$uploaded_file->size      = $file['size'];
			$uploaded_file->extension = substr( strrchr( $uploaded_file->name, '.' ), 1 );
		}
	}

	$wpaam_upload         = false;
	$wpaam_uploading_file = '';

	return $uploaded_file;
}

/**
 * Wrapper function for size_format - checks the max size of the avatar field.
 *
 * @since 1.0.0
 * @param array   $field
 * @param string  $size  in bytes
 * @return string
 */
function wpaam_max_upload_size( $field_name = '' ) {

	// Default max upload size
	$output = size_format( wp_max_upload_size() );

	// Check if the field is the avatar upload field and max size is defined
	if ( $field_name == 'user_avatar' && defined( 'wpaam_MAX_AVATAR_SIZE' ) )
		$output = size_format( wpaam_MAX_AVATAR_SIZE );

	return $output;
}

/**
 * Displays a button to check uploads folder permissions.
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_check_permissions_button() {

	$output = '<br/><br/>';
	$output .= '<a class="button" href="'.admin_url( 'users.php?page=wpaam-settings&tab=profile&wpaam_action=check_folder_permission' ).'">'.__( 'Verify upload permissions', 'wpaam' ).'</a>';
	$output .= '<p class="description">'.__( 'Press the button above if avatar uploads does not work.', 'wpaam' ).'</p>';

	return $output;

}

/**
 * Generates core pages and updates settings panel with the newly created pages.
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_generate_pages( $redirect = false ) {

	
	// Generate login page
	if ( ! wpaam_get_option( 'login_page' ) ) {

		$login = wp_insert_post(
			array(
				'post_title'     => __( 'Login', 'wpaam' ),
				'post_content'   => '[wpaam_login_form]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'login_page', $login );

	}

	// Generate registration page
	if ( ! wpaam_get_option( 'registration_page' ) ) {

		$register = wp_insert_post(
			array(
				'post_title'     => __( 'Register', 'wpaam' ),
				'post_content'   => '[wpaam_register form_id="" login_link="yes" psw_link="yes" register_link="no" ]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'registration_page', $register );

	}

	// Generate user account page
	if ( ! wpaam_get_option( 'account_page' ) ) {

		$account = wp_insert_post(
			array(
				'post_title'     => __( 'Account', 'wpaam' ),
				'post_content'   => '[wpaam_account]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'account_page', $account );

	}

	// Generate user profile  page
	if ( ! wpaam_get_option( 'profile_page' ) ) {

		$profile = wp_insert_post(
			array(
				'post_title'     => __( 'Profile', 'wpaam' ),
				'post_content'   => '[wpaam_profile]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'profile_page', $profile );

	}


	if ( ! wpaam_get_option( 'clients_page' ) ) {

		$clients = wp_insert_post(
			array(
				'post_title'     => __( 'Clients', 'wpaam' ),
				'post_content'   => '[wpaam_clients]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'clients_page', $clients );

	}

	// Generate user products management  page
	if ( ! wpaam_get_option( 'products_page' ) ) {

		$products = wp_insert_post(
			array(
				'post_title'     => __( 'Products', 'wpaam' ),
				'post_content'   => '[wpaam_products]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'products_page', $products );

	}

	// Generate user quotations management  page
	if ( ! wpaam_get_option( 'quotations_page' ) ) {

		$quotations = wp_insert_post(
			array(
				'post_title'     => __( 'Quotations', 'wpaam' ),
				'post_content'   => '[wpaam_quotations]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'quotations_page', $quotations );

	}

	// Generate user invoices management  page
	if ( ! wpaam_get_option( 'invoices_page' ) ) {

		$invoices = wp_insert_post(
			array(
				'post_title'     => __( 'Invoices', 'wpaam' ),
				'post_content'   => '[wpaam_invoices]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'invoices_page', $invoices );

	}

	// Generate user creditmemos management  page
	if ( ! wpaam_get_option( 'creditmemos_page' ) ) {

		$creditmemos = wp_insert_post(
			array(
				'post_title'     => __( 'Credit Memo', 'wpaam' ),
				'post_name'      => 'creditmemos',
				'post_content'   => '[wpaam_creditmemos]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		wpaam_update_option( 'creditmemos_page', $creditmemos );

	}





	if ( $redirect ) {
		wp_redirect( admin_url( 'users.php?page=wpaam-settings&tab=general&setup_done=true' ) );
		exit;
	}
}
///////////////////////////////////////////////////// Account Page tabs & settings /////////////////////////////////////////////
/**
 * Generates tabs for the account page.
 * Tabs are needed to split content in multiple parts,
 * and not produce a very long form.
 *
 * @since 1.0.0
 * @todo  sort by priority for addon.
 * @return void
 */
function wpaam_get_account_page_tabs() {

	$tabs = array();

	$tabs['details'] = array(
		'id'    => 'details',
		'title' => __('Edit Account', 'wpaam'),
	);
	$tabs['change-password'] = array(
		'id'    => 'change-password',
		'title' => __('Change Password', 'wpaam'),
	);
	$tabs['payments'] = array(
		'id'    => 'payments',
		'title' => __('Payments & Tax', 'wpaam'),
	);
	$tabs['others'] = array(
		'id'    => 'others',
		'title' => __('Other Settings', 'wpaam'),
	);

	return apply_filters( 'wpaam_get_account_page_tabs', $tabs );

}

/**
 * Generates url of a single account tab.
 *
 * @since 1.0.0
 * @return string $tab_url url of the tab.
 */
function wpaam_get_account_tab_url( $tab ) {

	if( get_option( 'permalink_structure' ) == '' ) :
		$tab_url = add_query_arg( 'account_tab', $tab, wpaam_get_core_page_url( 'account' ) );
	else :
		$tab_url = wpaam_get_core_page_url( 'account' ) . $tab;
	endif;
	
	return esc_url( $tab_url );

}

/**
 * Checks the current active account tab (if any).
 *
 * @since 1.0.0
 * @return bool|string
 */
function wpaam_get_current_account_tab() {

	$tab = ( get_query_var( 'account_tab' ) ) ? get_query_var( 'account_tab' ) : null;
	return $tab;
	
}

/**
 * Checks the given account tab is registered.
 *
 * @since 1.0.0
 * @param string  $tab the key value of the array in wpaam_get_account_page_tabs() must match slug
 * @return bool
 */
function wpaam_account_tab_exists( $tab ) {

	$exists = false;

	if ( array_key_exists( $tab, wpaam_get_account_page_tabs() ) )
		$exists = true;

	return $exists;

}

/////////////////////////////////////////////////////////// Products Page tabs & settings  /////////////////////////////

function wpaam_get_products_page_tabs() {
	$tabs = array();

	$tabs['list'] = array(
		'id'    => 'list',
		'title' => __('Products List', 'wpaam'),
	);
	$tabs['edit'] = array(
		'id'    => 'edit',
		'title' => __('Add Product', 'wpaam'),
	);
	return apply_filters( 'wpaam_get_products_page_tabs', $tabs );	
}

// products tab url
function wpaam_get_products_tab_url( $tab ) {

	if( get_option( 'permalink_structure' ) == '' ) :
		$tab_url = add_query_arg( 'product_tab', $tab, wpaam_get_core_page_url( 'products' ) );
	else :
		$tab_url = wpaam_get_core_page_url( 'products' ) . $tab;
	endif;

	return esc_url( $tab_url );

}

// current activate products tab (if any)
function wpaam_get_current_products_tab() {

	$tab = ( get_query_var( 'product_tab' ) ) ? get_query_var( 'product_tab' ) : null;
	return $tab;
	
}

// given products tab is registered
function wpaam_products_tab_exists( $tab ) {

	$exists = false;

	if ( array_key_exists( $tab, wpaam_get_products_page_tabs() ) )
		$exists = true;

	return $exists;

}

/////////////////////////////////////////////////////////// Clients Page tabs & settings  /////////////////////////////

// generate tabs for clients page
function wpaam_get_clients_page_tabs() {
	$tabs = array();

	$tabs['list'] = array(
		'id'    => 'list',
		'title' => __('Clients List', 'wpaam'),
	);
	$tabs['edit'] = array(
		'id'    => 'edit',
		'title' => __('Add Client', 'wpaam'),
	);
	return apply_filters( 'wpaam_get_clients_page_tabs', $tabs );	
}

function wpaam_get_current_clients_tab() {

	$tab = ( get_query_var( 'client_tab' ) ) ? get_query_var( 'client_tab' ) : null;
	return $tab;
	
}

function wpaam_clients_tab_exists( $tab ) {

	$exists = false;

	if ( array_key_exists( $tab, wpaam_get_clients_page_tabs() ) )
		$exists = true;

	return $exists;

}

function wpaam_get_clients_tab_url( $tab ) {

	if( get_option( 'permalink_structure' ) == '' ) :
		$tab_url = add_query_arg( 'client_tab', $tab, wpaam_get_core_page_url( 'clients' ) );
	else :
		$tab_url = wpaam_get_core_page_url( 'clients' ) . $tab;
	endif;

	return esc_url( $tab_url );

}

/////////////////////////////////////////////////////////// Quotations Page tabs & settings  /////////////////////////////

// generate tabs for quotations page
function wpaam_get_quotations_page_tabs() {
	$tabs = array();

	$tabs['list'] = array(
		'id'    => 'list',
		'title' => __('Quotations List', 'wpaam'),
	);
	$tabs['edit'] = array(
		'id'    => 'edit',
		'title' => __('Generate Quotations', 'wpaam'),
	);
	return apply_filters( 'wpaam_get_quotations_page_tabs', $tabs );	
}

function wpaam_get_current_quotations_tab() {

	$tab = ( get_query_var( 'quotation_tab' ) ) ? get_query_var( 'quotation_tab' ) : null;
	return $tab;
	
}

function wpaam_quotations_tab_exists( $tab ) {

	$exists = false;

	if ( array_key_exists( $tab, wpaam_get_quotations_page_tabs() ) )
		$exists = true;

	return $exists;

}

function wpaam_get_quotations_tab_url( $tab ) {

	if( get_option( 'permalink_structure' ) == '' ) :
		$tab_url = add_query_arg( 'quotation_tab', $tab, wpaam_get_core_page_url( 'quotations' ) );
	else :
		$tab_url = wpaam_get_core_page_url( 'quotations' ) . $tab;
	endif;

	return esc_url( $tab_url );

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function wpaam_is_func_disabled( $function ) {
	$disabled = explode( ',',  ini_get( 'disable_functions' ) );
	return in_array( $function, $disabled );
}

/**
 * Gets file extension of a file.
 *
 * @since 1.2.0
 * @param  string $str file name
 * @return string      extension of the file
 */
function wpaam_get_file_extension( $str ) {
	$parts = explode( '.', $str );
	return end( $parts );
}

/**
 * Covert object data to array.
 *
 * @since 1.2.0
 * @param  array|object $data data to pass and convert.
 * @return array
 */
function wpaam_object_to_array( $data ) {
	if ( is_array( $data ) || is_object( $data ) ) {
		$result = array();
		foreach ( $data as $key => $value ) {
			$result[ $key ] = wpaam_object_to_array( $value );
		}
		return $result;
	}
	return $data;
}



/**
 * Get the login redirect url
 *
 * @since 1.0.0
 * @return mixed
 */
function wpaam_get_login_redirect_url() {

	$url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$url = add_query_arg( array(
		'login'       => false,
		'captcha'     => false,
	), $url );

	return apply_filters( 'wpaam_login_redirect_url', esc_url( $url ) );

}

/**
 * Returns the url where users are redirected after a successfull registration.
 *
 * @since 1.1.0
 * @return string redirect url
 */
function wpaam_registration_redirect_url() {

	$url = false;

	if( wpaam_get_option( 'registration_redirect' ) ) {
		$url = get_permalink( wpaam_get_option( 'registration_redirect' ) );
	}

	return apply_filters( 'wpaam_registration_redirect_url', $url );

}

/**
 * Wrapper function to install groups database table and install primary group.
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_install_groups() {
	
	if( ! get_option( 'wpaam_version_upgraded_from' ) ) {

		// Create database table for field groups
		@WPAAM()->field_groups->create_table();

		// Add primary group
		$field_profile_args = array(
			'id'         => 1,
			'name'       => 'Profile',
			'can_delete' => false,
			'is_primary' => true 
		);


		WPAAM()->field_groups->add( $field_profile_args );

	}

}

/**
 * Wrapper function to install fields database table and install primary fields.
 *
 * @since 1.0.0
 * @return void
 */
function wpaam_install_fields() {

	if( ! get_option( 'wpaam_version_upgraded_from' ) ) {

		// Create database table for field groups
		@WPAAM()->fields->create_table();

		// Get primary group id
		$primary_group = WPAAM()->field_groups->get_group_by( 'primary' );

		// Install fields
		$fields = array(
			array(
				'id'                   => 1,
				'group_id'             => $primary_group->id,
				'type'                 => 'username',
				'name'                 => 'Username',
				'is_required'          => true,
				'show_on_registration' => true,
				'can_delete'           => false,
				'meta'                 => 'username',
			),
			array(
				'id'                   => 2,
				'group_id'             => $primary_group->id,
				'type'                 => 'email',
				'name'                 => 'Email',
				'is_required'          => true,
				'show_on_registration' => true,
				'can_delete'           => false,
				'meta'                 => 'user_email',
			),
			array(
				'id'                   => 3,
				'group_id'             => $primary_group->id,
				'type'                 => 'password',
				'name'                 => 'Password',
				'is_required'          => true,
				'show_on_registration' => true,
				'can_delete'           => false,
				'meta'                 => 'password',
			),
			array(
				'id'                   => 4,
				'group_id'             => $primary_group->id,
				'type'                 => 'text',
				'name'                 => 'First Name',
				'is_required'          => false,
				'show_on_registration' => false,
				'can_delete'           => false,
				'meta'                 => 'first_name',
			),
			array(
				'id'                   => 5,
				'group_id'             => $primary_group->id,
				'type'                 => 'text',
				'name'                 => 'Last Name',
				'is_required'          => false,
				'show_on_registration' => false,
				'can_delete'           => false,
				'meta'                 => 'last_name',
			),
			array(
				'id'                   => 6,
				'group_id'             => $primary_group->id,
				'type'                 => 'nickname',
				'name'                 => 'Nickname',
				'is_required'          => true,
				'show_on_registration' => false,
				'can_delete'           => false,
				'meta'                 => 'nickname',
			),
			array(
				'id'                   => 8,
				'group_id'             => $primary_group->id,
				'type'                 => 'text',
				'name'                 => 'Website',
				'is_required'          => false,
				'show_on_registration' => false,
				'can_delete'           => false,
				'meta'                 => 'user_url',
			),
			array(
				'id'                   => 9,
				'group_id'             => $primary_group->id,
				'type'                 => 'textarea',
				'name'                 => 'Description',
				'is_required'          => false,
				'show_on_registration' => false,
				'can_delete'           => false,
				'meta'                 => 'description',
			),
			array(
				'id'                   => 10,
				'group_id'             => $primary_group->id,
				'type'                 => 'avatar',
				'name'                 => 'Profile Picture',
				'is_required'          => false,
				'show_on_registration' => false,
				'can_delete'           => false,
				'meta'                 => 'user_avatar',
			),

		);

		foreach ( $fields as $field ) {
			WPAAM()->fields->add( $field );
		}

	}

}

/**
 * Utility function to convert an array to an object.
 *
 * @since 1.2.0
 * @param  array $array the array to convert.
 * @return object        converted object.
 */
function wpaam_array_to_object( $array ) {

	$object = new stdClass();

	if ( is_array( $array ) && count( $array ) > 0) {
		foreach ( $array as $name => $value ) {
			$name = strtolower( trim( $name ) );
			if ( ! empty( $name ) ) {
	      $object->$name = $value;
	    }
		}
	}

	return $object;

}

/**
 * Utility function to check if an array is multidimensional.
 *
 * @since 1.2.0
 * @param  array  $array the array to check.
 * @return boolean
 */
function wpaam_is_multi_array( $array ) {
	return ( count( $array ) !== count( $array, COUNT_RECURSIVE ) );
}

/***
*** create and install wpaam-vat_values table and fields
**/
// function wpaam_install_vat_values( ) { 
	
// 	global $wpdb;
// 	$table_name  = $wpdb->prefix . 'wpaam_vat_values';
// 	$primary_key = 'id';
// 	$version     = '1.0';
		
// 		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// 		$sql = "CREATE TABLE " . $table_name . " (
// 			`id` int(20) NOT NULL AUTO_INCREMENT,
// 			`user_id` int(20) NOT NULL,
// 			`vat_name` varchar(255) NOT NULL,
// 			`vat_value` int(10) NOT NULL,
// 			PRIMARY KEY (`id`)
// 		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

// 		dbDelta( $sql );

// 		update_option( $table_name . '_db_version', $version );
	
// }

//get the vat vales from database tables
function wpaam_get_vat_values( ) { 
	
	// $vat_values  = array('5'=>5,'10'=>10,'15'=>15,'20'=>20,'25'=>25,'30'=>30,'35'=>35,'40'=>40,'45'=>45,'50'=>50,'55'=>55,'60'=>60,'65'=>65,'70'=>70,'75'=>75,'80'=>80,'90'=>90,'100'=>100);
	global $wpdb;
	$user_id = get_current_user_id(); 
	$vat_values = get_posts( array('orderby' => 'date' , 'post_type' => 'aam-vat' , 'author' => $user_id) ); 
	//echo "<pre>"; print_r($vat_values); die("testing");
	return apply_filters( 'wpaam_get_vat_values', $vat_values );
	
}

