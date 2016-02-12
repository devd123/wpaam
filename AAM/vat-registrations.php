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
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'vat',
			'with_front' => false
			),
		'hierarchical'       => false,
		'has_archive'        => true,
		'menu_position'      => 65,
		'menu_icon'          => 'dashicons-art',
		'supports'           => array('title','thumbnail', 'custom-fields','author', 'aam_user')
	);

	$args = apply_filters( 'vat_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-vat',
		 $args     
	);

}

/* Show price meta in aam product list*/
function add_vat_columns($columns) {
   $columns['vat_value'] = 'Amount';
   return $columns;
}
add_filter('manage_aam-vat_posts_columns' , 'add_vat_columns');

function custom_vat_column( $column, $post_id ) {
    switch ( $column ) {
      case 'vat_value':
        echo get_post_meta( $post_id , 'vat_value' , true );
        break;
    }
}
add_action( 'manage_aam-vat_posts_custom_column' , 'custom_vat_column' , 10 , 2);

// register_deactivation_hook( __FILE__, 'aam_vat_mgmt_deactivation', 10 );


// function aam_vat_mgmt_deactivation() {
// 	flush_rewrite_rules();
// }

/*-----------------------------------------------------------------------------------*/
/* Project Mgmt. Title Field Label
/*-----------------------------------------------------------------------------------*/


