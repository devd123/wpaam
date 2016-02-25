<?php
/*----------------------------------------------------------------------------*/
/* Actions & Hooks
/*----------------------------------------------------------------------------*/


add_action( 'init', 'aam_vat', 10 );

/**
 * VAT Registration
 */
function aam_vat() {

	$labels = array(
		'name'                => _x( 'AAM VATs', 'Post Type General Name', 'wpaam' ),
		'singular_name'       => _x( 'AAM VAT', 'Post Type Singular Name', 'wpaam' ),
		'menu_name'           => __( 'AAM VATs', 'wpaam' ),
		'name_admin_bar'      => __( 'AAM VATs', 'wpaam' ),
		'parent_item_colon'   => __( 'AAM VAT', 'wpaam' ),
		'all_items'           => __( 'VATs List', 'wpaam' ),
		'add_new_item'        => __( 'Add New VAT', 'wpaam' ),
		'add_new'             => __( 'Add New VAT', 'wpaam' ),
		'new_item'            => __( 'New VAT', 'wpaam' ),
		'edit_item'           => __( 'Edit VAT', 'wpaam' ),
		'update_item'         => __( 'Update VAT', 'wpaam' ),
		'view_item'           => __( 'View VAT', 'wpaam' ),
		'search_items'       => __( 'Search VAT', 'wpaam' ),
		'not_found'          => __( 'No VAT found', 'wpaam' ),
		'not_found_in_trash' => __( 'No VAT found in Trash', 'wpaam' ),
	);

	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'vat',
			'with_front' => false
			),
		'hierarchical'       => false,
		'has_archive'        => true,
		'supports'           => array('title', 'aam_user')
	);

	$args = apply_filters( 'vat_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-vat',
		 $args     
	);

}

// /* Show price meta in aam product list*/
// function add_vat_columns($columns) {
//    $columns['vat_value'] = 'Amount';
//    return $columns;
// }
// add_filter('manage_aam-vat_posts_columns' , 'add_vat_columns');

// function custom_vat_column( $column, $post_id ) {
//     switch ( $column ) {
//       case 'vat_value':
//         echo get_post_meta( $post_id , 'vat_value' , true );
//         break;
//     }
// }
// add_action( 'manage_aam-vat_posts_custom_column' , 'custom_vat_column' , 10 , 2);

//Add To Menu
// add_action( 'admin_menu', 'nerfherder_add_submenu_pages' );
// function nerfherder_add_submenu_pages() {
// 	add_submenu_page( 'nerfherder-plugin.php', __( 'All Nerfherders', 'nerfherders' ), __( 'All Nerfherders', 'nerfherders' ), 'manage_options', 'nerfherder_plugin_show_posts', 'nerfherder_render_nerferhders' );
// 	add_submenu_page( 'nerfherder-plugin.php', __( 'Add New Nerfherder', 'nerfherders' ), __( 'Add New Nerfherder', 'nerfherders' ), 'manage_options', 'nerfherder_plugin_add_post', 'nerfherder_render_new_nerferhders' );
// }

// Add vat submenu in the under the user page
function wpaam_render_vats(){
	$url = admin_url().'edit.php?post_type=aam-vat';
	?>
	 <script>location.href='<?php echo $url;?>';</script>
	<?php
}
function wpaam_render_new_vat(){
	$url = admin_url().'post-new.php?post_type=aam-vat';
	?>
	<script>location.href='<?php echo $url;?>';</script>
	<?php
}

/**
 * Add/Remove appropriate CSS classes to Menu so Submenu displays open and the Menu link is styled appropriately.
 */
function wpaam_correct_current_menu(){
	$screen = get_current_screen();
	if ( $screen->id == 'aam-vat' || $screen->id == 'edit-aam-vat' ) {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#toplevel_page_nerfherder-plugin').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
		$('#toplevel_page_nerfherder-plugin > a').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
	});
	</script>
	<?php
	}
	if ( $screen->id == 'aam-vat' ) {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('a[href$="nerfherder_plugin_add_post"]').parent().addClass('current');
		$('a[href$="nerfherder_plugin_add_post"]').addClass('current');
	});
	</script>
	<?php
	}
	if ( $screen->id == 'edit-aam-vat' ) {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('a[href$="nerfherder_plugin_show_posts"]').parent().addClass('current');
		$('a[href$="nerfherder_plugin_show_posts"]').addClass('current');
	});
	</script>
	<?php
	}
}
add_action('admin_head', 'wpaam_correct_current_menu', 50);

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'aamvat_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'aamvat_post_meta_boxes_setup' );

/* Meta box setup function. */
function aamvat_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'aamvat_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'aamvat_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function aamvat_add_post_meta_boxes() {

  add_meta_box(
    'vat_value',      // Unique ID
    esc_html__( 'Value', 'wpaam' ),    // Title
    'aamvat_post_class_meta_box',   // Callback function
    'aam-vat',         // Admin page (or post type)
    'normal',         // Context
    'default'         // Priority
  );
}

/* Display the post meta box. */
function aamvat_post_class_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'aamvat_post_class_nonce' ); ?>

  <p>
    <label for="vat_value"><?php _e( "Enter specific vat tax value ", 'wpaam' ); ?></label>
    <br />
    <input class="widefat" type="text" name="vat_value" id="vat_value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'vat_value', true ) ); ?>" size="30" />
  </p>
<?php }

/* Save the meta box's post metadata. */
function aamvat_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['aamvat_post_class_nonce'] ) || !wp_verify_nonce( $_POST['aamvat_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['vat_value'] ) ? sanitize_html_class( $_POST['vat_value'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'vat_value';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

/*-----------------------------------------------------------------------------------*/
/* Project Mgmt. Title Field Label
/*-----------------------------------------------------------------------------------*/


