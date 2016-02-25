<?php
/*----------------------------------------------------------------------------*/
/* Actions & Hooks
/*----------------------------------------------------------------------------*/

register_activation_hook( __FILE__, 'aam_quotation_mgmt_activation', 10 );
add_action( 'init', 'aam_quotation', 10 );

/**
 * Quotation
 *
 * Register aam-quotation as a custom post type.
 *
 * @package Quotation Mgmt.
 * @version 1.0.0
 * @since 1.1.1 Updated the menu icon
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function aam_quotation() {

	$labels = array(
		'name'                => _x( 'AAM Quotations', 'Post Type General Name', 'wpaam' ),
		'singular_name'       => _x( 'AAM Quotation', 'Post Type Singular Name', 'wpaam' ),
		'menu_name'           => __( 'AAM Quotations', 'wpaam' ),
		'name_admin_bar'      => __( 'AAM Quotations', 'wpaam' ),
		'parent_item_colon'   => __( 'AAM Quotation', 'wpaam' ),
		'all_items'           => __( 'Quotations List', 'wpaam' ),
		'add_new_item'        => __( 'Add New Quotation', 'wpaam' ),
		'add_new'             => __( 'Add New Quotation', 'wpaam' ),
		'new_item'            => __( 'New Quotation', 'wpaam' ),
		'edit_item'           => __( 'Edit Quotation', 'wpaam' ),
		'update_item'         => __( 'Update Quotation', 'wpaam' ),
		'view_item'           => __( 'View Quotation', 'wpaam' ),
		'search_items'       => __( 'Search Quotation', 'wpaam' ),
		'not_found'          => __( 'No Quotation found', 'wpaam' ),
		'not_found_in_trash' => __( 'No Quotation found in Trash', 'wpaam' ),
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
			'slug'       => 'quotation',
			'with_front' => false
			),
		// 'capability_type'    => 'aam-quotation',
		// 	'capabilities' => array(
		// 		'publish_posts' => 'publish_quotation',
		// 		'edit_posts' => 'edit_quotations',
		// 		'edit_post' => 'edit_quotation',
		// 		'delete_post' => 'delete_quotation',
		// 		'read_post' => 'read_quotation',
		// 	),
		'hierarchical'       => false,
		'has_archive'        => true,
		// 'menu_position'      => 70,
		// 'menu_icon'          => 'dashicons-art',
		'supports'           => array('title','author', 'aam_user')
	);

	$args = apply_filters( 'quotation_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-quotation', // unique post type handle to avoid any potential conflicts
		$args             // array of arguments for this custom post type
	);

}

function aam_quotation_mgmt_activation() {

	// custom post type
	aam_quotation();

	// flush rewrite rules
	flush_rewrite_rules();

}

// Add quotation submenu in the under the user page
function wpaam_render_quotations(){
  $url = admin_url().'edit.php?post_type=aam-quotation';
  ?>
   <script>location.href='<?php echo $url;?>';</script>
  <?php
}
function wpaam_render_new_quotation(){
  $url = admin_url().'post-new.php?post_type=aam-quotation';
  ?>
  <script>location.href='<?php echo $url;?>';</script>
  <?php
}


/*----------------------------------------------------------------------------*/
/* Project Mgmt. Post Thumbnail
/*----------------------------------------------------------------------------*/

// add_action( 'init', 'aam_quotation_mgmt_post_thumbnail', 10 );

// function aam_quotation_mgmt_post_thumbnail() {

// 	if ( !current_theme_supports( 'post-thumbnails' ) ) { // if the currently active theme does not support post-thumbnails

// 		add_theme_support( 'post-thumbnail', array( 'aam-quotation' ) ); // add theme support for post-thumbnails for the custom post type only

// 	}

// }