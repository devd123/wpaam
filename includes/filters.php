<?php
/**
** Plugin Filters
** @since 1.0.0
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



function wpaam_add_settings_link( $links ) {
	$settings_link = '<a href="'.admin_url( 'users.php?page=wpaam-settings' ).'">'.__('Settings','wpaam').'</a>';
	array_push( $links, $settings_link );
	return $links;
}
add_filter( "plugin_action_links_".WPAAM_SLUG , 'wpaam_add_settings_link');

/**
 * Add links to plugin row
 *
 * @since 1.0.0
 * @access public
 * @return array
 */
function wpaam_plugin_row_meta( $input, $file ) {

	if ( $file != 'wp-user-manager/wp-user-manager.php' )
		return $input;

	$links = array(
		'<a href="https://neerusite.wordpress.com/" target="_blank">' . esc_html__( 'Documentation', 'wpaam' ) . '</a>',
		'<a href="https://neerusite.wordpress.com/" target="_blank">' . esc_html__( 'Extensions', 'wpaam' ) . '</a>',
	);

	$input = array_merge( $input, $links );

	return $input;
}
add_filter( 'plugin_row_meta', 'wpaam_plugin_row_meta', 10, 2 );

function wpaam_get_allowed_roles( $user ) {
    $allowed = array();

    if ( in_array( 'administrator', $user->roles ) ) { // Admin can edit all roles
        $allowed = array_keys( $GLOBALS['wp_roles']->roles );
    } elseif ( in_array( 'aam_user', $user->roles ) ) {
        $allowed[] = 'aam_client';
    } elseif ( in_array( 'aam_client', $user->roles ) ) {
        $allowed[] = '';
    }
    
    return $allowed;
}

function wpaam_editable_roles( $roles ) {
    if ( $user = wp_get_current_user() ) {
        $allowed = wpaam_get_allowed_roles( $user );

        foreach ( $roles as $role => $caps ) {
            if ( ! in_array( $role, $allowed ) )
                unset( $roles[ $role ] );
        }
    }
   
    return $roles;
}

add_filter( 'editable_roles', 'wpaam_editable_roles' );

function wpaam_map_meta_cap( $caps, $cap, $user_ID, $args ) {

    if ( ( $cap === 'edit_user' || $cap === 'delete_user' ) && $args ) {
        $the_user = get_userdata( $user_ID ); // The user performing the task
        $user     = get_userdata( $args[0] ); // The user being edited/deleted

        if ( $the_user && $user && $the_user->ID != $user->ID /* User can always edit self */ ) {
            $allowed = wpaam_get_allowed_roles( $the_user );
            
            if ( array_diff( $user->roles, $allowed ) ) {
                // Target user has roles outside of our limits

                $caps[] = 'not_allowed';
                exit;
            }
        }
    }

    return $caps;
}

add_filter( 'map_meta_cap', 'wpaam_map_meta_cap', 10, 4 );

/**
 * Filters the upload dir when $wpaam_upload is true
 */
function wpaam_upload_dir( $pathdata ) {
	global $wpaam_upload, $wpaam_uploading_file;

	if ( ! empty( $wpaam_upload ) ) {
		$dir = apply_filters( 'wpaam_upload_dir', 'wp-user-manager-uploads' );

		if ( empty( $pathdata['subdir'] ) ) {
			$pathdata['path']   = $pathdata['path'] . '/' . $dir;
			$pathdata['url']    = $pathdata['url'] . '/' . $dir;
			$pathdata['subdir'] = '/' . $dir;
		} else {
			$new_subdir         = '/' . $dir . $pathdata['subdir'];
			$pathdata['path']   = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['path'] );
			$pathdata['url']    = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['url'] );
			$pathdata['subdir'] = str_replace( $pathdata['subdir'], $new_subdir, $pathdata['subdir'] );
		}
	}

	return $pathdata;
}
add_filter( 'upload_dir', 'wpaam_upload_dir' );

/**
 * Add rating links to the admin panel
 */
function wpaam_admin_rate_us( $footer_text ) {

	$screen = get_current_screen();

	if ( $screen->base !== 'users_page_wpaam-settings' )
		return;

	$rate_text = sprintf( __( 'Please support the future of <a href="%1$s" target="_blank">WP User Manager</a> by <a href="%2$s" target="_blank">rating us</a> on <a href="%2$s" target="_blank">WordPress.org</a>', 'wprm', 'wpaam' ),
		'https://neerusite.wordpress.com/',
		'http://wordpress.org/support/view/plugin-reviews/'
	);

	return str_replace( '</span>', '', $footer_text ) . ' | ' . $rate_text . ' <span class="dashicons dashicons-star-filled footer-star"></span><span class="dashicons dashicons-star-filled footer-star"></span><span class="dashicons dashicons-star-filled footer-star"></span><span class="dashicons dashicons-star-filled footer-star"></span><span class="dashicons dashicons-star-filled footer-star"></span></span>';

}
add_filter( 'admin_footer_text', 'wpaam_admin_rate_us' );

/**
 * Retrieve custom avatar if any
 */
function wpaam_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

	$safe_alt = esc_attr( $alt );
	$custom_avatar = false;

	if( is_object( $id_or_email ) ) {

		$comment_email = $id_or_email->comment_author_email;

		$user = get_user_by( 'email', $comment_email );

		if( $user ) {
			$custom_avatar = get_user_meta( $user->ID , 'current_user_avatar', true );
		}

	} elseif ( is_email( $id_or_email ) && email_exists( $id_or_email ) || is_numeric( $id_or_email ) ) {
		$custom_avatar = get_user_meta( $id_or_email, 'current_user_avatar', true );
	}

	if ( !empty( $custom_avatar ) ) {
		$avatar = "<img alt='{$safe_alt}' src='{$custom_avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	}

	return $avatar;

}
add_filter('get_avatar', 'wpaam_get_avatar', 1, 5);

/**
 * Adjust body class on admin panel
 *
 * @since 1.0.0
 * @return array
 */
function wpaam_admin_body_classes( $classes ) {

	$screen = get_current_screen();

	if( $screen->base == 'plugin-install' && isset( $_GET['tab'] ) && $_GET['tab'] == 'wpaam_addons' ) {
		$classes .= 'wpaam_addons_page';
	}

	return $classes;

}
add_filter( 'admin_body_class', 'wpaam_admin_body_classes' );

/**
 * Filter allowed file types on upload forms.
 *
 * @since 1.0.0
 * @param  array $upload_mimes list of file types
 * @return array $upload_mimes list of file types
 */
function wpaam_adjust_mime_types( $upload_mimes ) {

	$allowed_types = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png'
	);

	$upload_mimes = array_intersect_key( $upload_mimes, $allowed_types );

	return $upload_mimes;
}


/**
 * Allows login form to redirect to an url specified into a query string.
 *
 * @since 1.1.0
 * @param  string $url url
 * @return string      url specified into the query string
 */
function wpaam_login_redirect_detection( $url ) {

	if( isset( $_GET[ 'redirect_to' ] ) && $_GET['redirect_to'] !== '' ) {
		$url = urldecode( $_GET['redirect_to'] );
	} elseif ( isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] !== '' && ! wpaam_get_option( 'always_redirect' ) ) {
		$url = $_SERVER['HTTP_REFERER'];
	} elseif( wpaam_get_option( 'login_redirect' ) ) {
		$url = get_permalink( wpaam_get_option( 'login_redirect' ) );
	}

	return esc_url( $url );

}
add_filter( 'wpaam_login_redirect_url', 'wpaam_login_redirect_detection', 99, 1 );

/* 
***	Add custom column 'Parent User' in User List Table WP-ADMIN
*/

function wpaam_add_user_perent_column( $columns ) {
    $columns['parent_user'] = __( 'Parent User', 'wpaam' );
    return $columns;
}
add_filter( 'manage_users_columns', 'wpaam_add_user_perent_column' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    $user = get_userdata( $user_id );

    switch ($column_name) {
        case 'parent_user' :
            return get_the_author_meta( 'parent_user', $user_id );
            break;
        default:
    }
    return $return;

}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );


/* 
***	Removed column 'Email' in User List Table WP-ADMIN
*/

add_filter('manage_users_columns','remove_users_columns');
function remove_users_columns($column_headers) {
    global $current_user;
 
    $users = get_users();
 
    if (in_array($current_user->ID, $users)) {
        unset($column_headers['email']);
    }    
 
    return $column_headers;
}

/* 
***	Add Custom 'Parent User' Filter in User List Table 
*/

function add_parent_user_filter() {
  
    $aam_users = get_users(array('role' => 'aam_user'));
   // echo "<pre>"; print_r($aam_users); die;

    if ( isset( $_GET[ 'parent_user' ]) ) {
        $section = $_GET[ 'parent_user' ];
        $section = !empty( $section[ 0 ] ) ? $section[ 0 ] : $section[ 1 ];
    } else {
        $section = -1;
    }
    if(!empty($aam_users)):
        echo ' <select name="parent_user[]" style="float:none;"><option value="">Parent User...</option>';
        foreach ($aam_users as $aam_user) {
        	$selected = $aam_user->user_login == $section ? ' selected="selected"' : '';
    	   	echo '<option value='.$aam_user->user_login.'>'.$aam_user->user_login.'</option>';
       	}
        echo '<input type="submit" class="button" value="Filter">';
    endif;
}
add_action( 'restrict_manage_users', 'add_parent_user_filter' );

function filter_users_by_parent_user( $query ) {
    global $pagenow;

    if ( is_admin() && 
         'users.php' == $pagenow && 
         isset( $_GET[ 'parent_user' ] ) && 
         is_array( $_GET[ 'parent_user' ] )
        ) {
        $section = $_GET[ 'parent_user' ];
        $section = !empty( $section[ 0 ] ) ? $section[ 0 ] : $section[ 1 ];
        $meta_query = array(
            array(
                'key' => 'parent_user',
                'value' => $section
            )
        );
        $query->set( 'meta_key', 'parent_user' );
        $query->set( 'meta_query', $meta_query );
    }
}
add_filter( 'pre_get_users', 'filter_users_by_parent_user' );





