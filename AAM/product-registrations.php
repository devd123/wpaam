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
		'show_in_menu'       => false,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'product',
			'with_front' => false
			),
		// 'capability_type'    => 'aam-product',
		// 	'capabilities' => array(
		// 		'publish_posts' => 'publish_product',
    //       'read_post' => 'read_product',
		// 		'edit_posts' => 'edit_products',
    //       'delete_post' => 'delete_product',
		// 		'edit_post' => 'edit_product',
    //     'edit_others_posts' => 'edit_others_products'
			
		// 	),
		'hierarchical'       => false,
		'has_archive'        => true,
		'supports'           => array('title','author', 'aam_user')
	);

	$args = apply_filters( 'product_mgmt_args', $args );

	// register the post type
	register_post_type(
		'aam-product', // unique post type handle to avoid any potential conflicts
		$args             // array of arguments for this custom post type
	);

}


// Add product submenu in the under the user page
function wpaam_render_products(){
  $url = admin_url().'edit.php?post_type=aam-product';
  ?>
   <script>location.href='<?php echo $url;?>';</script>
  <?php
}
function wpaam_render_new_product(){
  $url = admin_url().'post-new.php?post_type=aam-product';
  ?>
  <script>location.href='<?php echo $url;?>';</script>
  <?php
}

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'smashing_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'smashing_post_meta_boxes_setup' );

/* Meta box setup function. */
function smashing_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'smashing_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'smashing_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function smashing_add_post_meta_boxes() {

  add_meta_box(
    'product_price',      // Unique ID
    esc_html__( 'Price', 'wpaam' ),    // Title
    'smashing_post_class_meta_box',   // Callback function
    'aam-product',         // Admin page (or post type)
    'side',         // Context
    'default'         // Priority
  );
}

/* Display the post meta box. */
function smashing_post_class_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' ); ?>

  <p>
    <label for="product_price"><?php _e( "Enter product price as per the aam user", 'wpaam' ); ?></label>
    <br />
    <input class="widefat" type="text" name="product_price" id="product_price" value="<?php echo esc_attr( get_post_meta( $object->ID, 'product_price', true ) ); ?>" size="30" />
  </p>
<?php }

/* Save the meta box's post metadata. */
function smashing_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['smashing_post_class_nonce'] ) || !wp_verify_nonce( $_POST['smashing_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['product_price'] ) ? sanitize_html_class( $_POST['product_price'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'product_price';

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

/* Show price meta in AAM Product List*/
function add_product_columns($columns) {
    return array_merge($columns, 
              array('product_price' => __('Price'),
                    'product_sku' =>__( 'SKU')));
}
add_filter('manage_aam-product_posts_columns' , 'add_product_columns');

function custom_product_column( $column, $post_id ) {
    switch ( $column ) {
      case 'product_price':
        echo get_post_meta( $post_id , 'product_price' , true );
        break;

      case 'product_sku':
        echo get_post_meta( $post_id , 'product_sku' , true ); 
        break;
    }
}
add_action( 'manage_aam-product_posts_custom_column' , 'custom_product_column' , 10 , 2);

/* Add Auther Filter - Product List */
function add_author_filter() {
     
    // if ( isset( $_GET[ 'user' ] ) ) {
    //     $arguments[ 'selected' ] = $_GET[ 'user' ];
    // }

    $aam_users = get_users(array('role' => 'aam_user'));
    echo '<select id="author" name="author">
    	<option value="0">All AAM User...</option>';
        foreach ($aam_users as $aam_user) {
    	   	echo '<option  value='.$aam_user->ID.'>'.$aam_user->user_login.'</option>';
       	}

    //wp_dropdown_users( $arguments );
}
add_filter( 'restrict_manage_posts' ,   'add_author_filter' );


// register_deactivation_hook( __FILE__, 'aam_product_mgmt_deactivation', 10 );


// function aam_product_mgmt_deactivation() {
// 	flush_rewrite_rules();
// }

/*-----------------------------------------------------------------------------------*/
/* Project Mgmt. Title Field Label
/*-----------------------------------------------------------------------------------*/


