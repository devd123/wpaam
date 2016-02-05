<?php
/*----------------------------------------------------------------------------*/
/* Actions & Hooks
/*----------------------------------------------------------------------------*/

//register_activation_hook( __FILE__, 'aam_product_mgmt_activation', 10 );


add_action( 'init', 'aam_product', 10 );

/**
 * Product Registration
 */
function aam_product() {

	$labels = array(
		'name'                => _x( 'AAM Products', 'Post Type General Name', 'wpaam' ),
		'singular_name'       => _x( 'AAM Product', 'Post Type Singular Name', 'wpaam' ),
		'menu_name'           => __( 'AAM Products', 'wpaam' ),
		'name_admin_bar'      => __( 'AAM Products', 'wpaam' ),
		'parent_item_colon'   => __( 'AAM Product', 'wpaam' ),
		'all_items'           => __( 'Products List', 'wpaam' ),
		'add_new_item'        => __( 'Add New Product', 'wpaam' ),
		'add_new'             => __( 'Add New Product', 'wpaam' ),
		'new_item'            => __( 'New Product', 'wpaam' ),
		'edit_item'           => __( 'Edit Product', 'wpaam' ),
		'update_item'         => __( 'Update Product', 'wpaam' ),
		'view_item'           => __( 'View Product', 'wpaam' ),
		'search_items'       => __( 'Search Product', 'wpaam' ),
		'not_found'          => __( 'No Product found', 'wpaam' ),
		'not_found_in_trash' => __( 'No Product found in Trash', 'wpaam' ),
	);

	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'product',
			'with_front' => false
			),
		'capability_type'    => 'aam-product',
			'capabilities' => array(
				'publish_posts' => 'publish_product',
				'edit_posts' => 'edit_products',
				'edit_post' => 'edit_product',
				'delete_post' => 'delete_product',
				'read_post' => 'read_product',
			),
		'hierarchical'       => false,
		'has_archive'        => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-art',
		'supports'           => array('title','editor','revisions','author', 'aam_user')
	);

	$args = apply_filters( 'product_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-product', // unique post type handle to avoid any potential conflicts
		$args             // array of arguments for this custom post type
	);

}


register_deactivation_hook( __FILE__, 'aam_product_mgmt_deactivation', 10 );


function aam_product_mgmt_deactivation() {
	flush_rewrite_rules();
}

/*-----------------------------------------------------------------------------------*/
/* Project Mgmt. Title Field Label
/*-----------------------------------------------------------------------------------*/


