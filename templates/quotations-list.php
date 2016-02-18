<?php 
/**
 * wpaam Template: Product List Template.
 * @since       1.0.0
 */
	
?>

	<!-- <p align="right"><a class="add-button" href="<?php echo esc_url( get_permalink( get_page_by_title( 'Add Product' ) ) ); ?>">Add Product</a></p> -->
	<table class="wp-list-table" colspam="">
  		
		
	<?php
	global $wpdb;
	
	$author = get_current_user_id();  
	// set pagination for the quotation list
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array('post_type' => 'aam-quotations' , 'author' => $author , 'posts_per_page' => 10 , 'paged' => $paged);
	$query = new WP_Query( $args);
	
	
	
	if ( $query->have_posts() ) : 
		$number = 1;
		while ( $query->have_posts() ) : $query->the_post(); 
		$productid = get_the_ID();
	
	
	// end php
 	?>
		<thead>
	  		<th width="10%">S.N.</th>
	  		<th>Name</th>
	  		<th>SKU</th>
	  		<th>Price</th>
	  		<th>Vat Tax</th>	
	  		<th>Created</th>
	  		<th>Action</th>
  		</thead>

		<tbody>
		  	<tr id="<?php echo get_the_ID();?>">
			  	<td><?php echo $number; ?></td>   
			    <td><?php echo the_title(); ?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'product_sku' , true); ?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'product_price' , true ); ?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'product_vat' , true); ?></td>
			    <td><?php echo the_date(); ?></td>
			    <td><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Products' ) ) ).'&product_tab=edit&product_id='.$productid; ?>"><span>Edit</span></a></td>
	      	<?php $number++;  ?>
		   </tr>
	   </tbody> 		
	
		<?php   endwhile; wp_reset_postdata(); ?>
		<!-- pagination -->
		<p  class="post-pagination">
		<?php $big = 999999999; // need an unlikely integer
		echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $query->max_num_pages
		) ); ?>
		</p>
		<?php else : ?>
				<p><?php _e( 'Sorry, no quotation matched your criteria.' ); ?></p>
		<?php endif; ?>
	</table> 

	