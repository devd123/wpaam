<?php
/*----------------------------------------------------------------------------*/
/* Actions & Hooks
/*----------------------------------------------------------------------------*/

register_activation_hook( __FILE__, 'aam_quotation_mgmt_activation', 10 );
//add_action( 'init', 'aam_quotation_services', 10 );
//add_action( 'init', 'aam_quotation_tags', 10 );
add_action( 'init', 'aam_quotation', 10 );

function aam_quotation_services() {

	$labels = array(
		'name'                       => _x( 'Services', 'taxonomy general name', 'quotation-mgmt' ),
		'singular_name'              => _x( 'Service', 'taxonomy singular name', 'quotation-mgmt' ),
		'search_items'               => __( 'Search Services', 'quotation-mgmt' ),
		'popular_items'              => __( 'Popular Services', 'quotation-mgmt' ),
		'all_items'                  => __( 'All Services', 'quotation-mgmt' ),
		'view_item'                  => __( 'View Service', 'quotation-mgmt' ),
		'parent_item'                => __( 'Parent Service', 'quotation-mgmt' ),
		'parent_item_colon'          => __( 'Parent Service:', 'quotation-mgmt' ),
		'edit_item'                  => __( 'Edit Service', 'quotation-mgmt' ),
		'update_item'                => __( 'Update Service', 'quotation-mgmt' ),
		'add_new_item'               => __( 'Add New Service', 'quotation-mgmt' ),
		'new_item_name'              => __( 'New Service', 'quotation-mgmt' ),
		'separate_items_with_commas' => __( 'Separate Services with commas', 'quotation-mgmt' ),
		'add_or_remove_items'        => __( 'Add or remove Services', 'quotation-mgmt' ),
		'choose_from_most_used'      => __( 'Choose from Most Used Services', 'quotation-mgmt' ),
		'not_found'                  => __( 'No Services found.', 'quotation-mgmt' ),
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'args'              => array(
			'orderby' => 'term_order'
			),
		'rewrite'           => array(
			'slug'       => 'quotation/services',
			'with_front' => false ),
		'query_var'         => true,
	);

	$args = apply_filters( 'quotation_mgmt_services_args', $args );

	// register services as a custom taxonomy
	register_taxonomy(
		'aam-services',  // unique handle to avoid potential conflicts
		'aam-quotation', // this custom taxonomy should only be associated with our custom post type registered in aam-quotation-registration.php
		$args             // array of arguments for this custom taxonomy
	);

}



function aam_quotation_tags() {

	$labels = array(
		'name'                       => _x( 'Portfolio Tags', 'taxonomy general name', 'quotation-mgmt' ),
		'singular_name'              => _x( 'Portfolio Tag', 'taxonomy singular name', 'quotation-mgmt'),
		'search_items'               => __( 'Search Portfolio Tags', 'quotation-mgmt' ),
		'popular_items'              => __( 'Popular Portfolio Tags', 'quotation-mgmt' ),
		'all_items'                  => __( 'All Portfolio Tags', 'quotation-mgmt' ),
		'view_item'                  => __( 'View Portfolio Tag', 'quotation-mgmt' ),
		'edit_item'                  => __( 'Edit Portfolio Tag', 'quotation-mgmt' ),
		'update_item'                => __( 'Update Portfolio Tag', 'quotation-mgmt' ),
		'add_new_item'               => __( 'Add New Portfolio Tag', 'quotation-mgmt' ),
		'new_item_name'              => __( 'New Portfolio Tag', 'quotation-mgmt' ),
		'separate_items_with_commas' => __( 'Separate Portfolio Tags with commas', 'quotation-mgmt' ),
		'add_or_remove_items'        => __( 'Add or Remove Portfolio Tags', 'quotation-mgmt' ),
		'choose_from_most_used'      => __( 'Choose from Most Used Portfolio Tags', 'quotation-mgmt' ),
		'not_found'                  => __( 'No Portfolio Tags found.', 'quotation-mgmt' ),
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'args'              => array(
			'orderby' => 'term_order'
			),
		'rewrite'           => array(
			'slug'       => 'quotation/quotation-tags',
			'with_front' => false,
			),
		'query_var'         => true,
	);

	$args = apply_filters( 'quotation_mgmt_quotation_tag_args', $args );

	// register quotation tags as a custom taxonomy
	register_taxonomy(
		'aam-quotation-tags', // unique handle to avoid potential conflicts
		'aam-quotation',      // this custom taxonomy should only be associated with our custom post type registered in aam-quotation-registration.php
		$args                  // array of arguments for this custom taxonomy
	);

}


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

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'revisions',
		'author',
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
			'slug'       => 'quotation',
			'with_front' => false
			),
		'capability_type'    => 'aam-quotation',
			'capabilities' => array(
				'publish_posts' => 'publish_quotation',
				'edit_posts' => 'edit_quotations',
				'edit_post' => 'edit_quotation',
				'delete_post' => 'delete_quotation',
				'read_post' => 'read_quotation',
			),
		'hierarchical'       => false,
		'has_archive'        => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-art',
		'supports'           => $supports,
	);

	$args = apply_filters( 'quotation_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-quotation', // unique post type handle to avoid any potential conflicts
		$args             // array of arguments for this custom post type
	);

}



function aam_quotation_mgmt_activation() {

	// aam-services custom taxonomy
	//aam_quotation_services();

	// aam-quotation-tgs custom taxonomy
	//aam_quotation_tags();

	// custom post type
	aam_quotation();

	// flush rewrite rules
	flush_rewrite_rules();

}

register_deactivation_hook( __FILE__, 'aam_quotation_mgmt_deactivation', 10 );


function aam_quotation_mgmt_deactivation() {
	flush_rewrite_rules();
}

/*-----------------------------------------------------------------------------------*/
/* Project Mgmt. Title Field Label
/*-----------------------------------------------------------------------------------*/

add_filter( 'enter_title_here', 'aam_quotation_mgmt_title_field_label', 10, 1 );

function aam_quotation_mgmt_title_field_label( $title ) {

	$screen = get_current_screen();

	if ( 'aam-quotation' == $screen->post_type ) {

		$quotation       = get_post_type_object( 'aam-quotation' );
		$quotation_label = $quotation->labels->singular_name;

		$title = $quotation_label . __( ' Title', 'quotation-mgmt' );

	}

	return $title;

}

/*----------------------------------------------------------------------------*/
/* Project Mgmt. Post Thumbnail
/*----------------------------------------------------------------------------*/

add_action( 'init', 'aam_quotation_mgmt_post_thumbnail', 10 );

function aam_quotation_mgmt_post_thumbnail() {

	if ( !current_theme_supports( 'post-thumbnails' ) ) { // if the currently active theme does not support post-thumbnails

		add_theme_support( 'post-thumbnail', array( 'aam-quotation' ) ); // add theme support for post-thumbnails for the custom post type only

	}

}