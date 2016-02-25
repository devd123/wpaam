<?php
/*----------------------------------------------------------------------------*/
/* Actions & Hooks
/*----------------------------------------------------------------------------*/


add_action( 'init', 'aam_invoices', 10 );

/**
 * Invoices
 */

function aam_invoices() {

	$labels = array(
		'name'                => _x( 'AAM Invoices', 'Post Type General Name', 'wpaam' ),
		'singular_name'       => _x( 'AAM Invoice', 'Post Type Singular Name', 'wpaam' ),
		'menu_name'           => __( 'AAM Invoices', 'wpaam' ),
		'name_admin_bar'      => __( 'AAM Invoices', 'wpaam' ),
		'parent_item_colon'   => __( 'AAM Invoice', 'wpaam' ),
		'all_items'           => __( 'Invoices List', 'wpaam' ),
		'add_new_item'        => __( 'Add New Invoice', 'wpaam' ),
		'add_new'             => __( 'Add New Invoice', 'wpaam' ),
		'new_item'            => __( 'New Invoice', 'wpaam' ),
		'edit_item'           => __( 'Edit Invoice', 'wpaam' ),
		'update_item'         => __( 'Update Invoice', 'wpaam' ),
		'view_item'           => __( 'View Invoice', 'wpaam' ),
		'search_items'       => __( 'Search Invoice', 'wpaam' ),
		'not_found'          => __( 'No Invoice found', 'wpaam' ),
		'not_found_in_trash' => __( 'No Invoice found in Trash', 'wpaam' ),
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
			'slug'       => 'invoice',
			'with_front' => false
			),
		// 'capability_type'    => 'aam-invoice',
		// 	'capabilities' => array(
		// 		'publish_posts' => 'publish_invoice',
		// 		'edit_posts' => 'edit_invoices',
		// 		'edit_post' => 'edit_invoice',
		// 		'delete_post' => 'delete_invoice',
		// 		'read_post' => 'read_invoice',
		// 	),
		'hierarchical'       => false,
		'has_archive'        => true,
		'supports'           => array('title','author', 'aam_user')
	);

	$args = apply_filters( 'invoice_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-invoice', // unique post type handle to avoid any potential conflicts
		$args             // array of arguments for this custom post type
	);

}

function aam_invoice_mgmt_activation() {

	// custom post type
	aam_invoice();

	// flush rewrite rules
	flush_rewrite_rules();

}

// Add invoice submenu in the under the user page
function wpaam_render_invoices(){
  $url = admin_url().'edit.php?post_type=aam-invoice';
  ?>
   <script>location.href='<?php echo $url;?>';</script>
  <?php
}
function wpaam_render_new_invoice(){
  $url = admin_url().'post-new.php?post_type=aam-invoice';
  ?>
  <script>location.href='<?php echo $url;?>';</script>
  <?php
}

