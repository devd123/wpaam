<?php 
/**
 * WPAAM Template: Client Quotations Template.
 * @since       1.0.0
 */
?>
	<div id="dialog_box" title="Quotation Preview"></div>
	<table class="wp-list-table" colspam="">
  		<thead>
	  		<th>Number</th>
	  		<th>Company</th>
	  		<th>Name</th>
	  		<th>total</th>
	  		<th>Created</th>
	  		<th>Action</th>
  		</thead>
		
	<?php
		global $wpdb;
		$author = get_current_user_id();  
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array('post_type' => 'aam-quotation'  , 'posts_per_page' => 50 , 'paged' => $paged);
		$query = new WP_Query( $args);
		if ( $query->have_posts() ) : 
			$number = 1;
			while ( $query->have_posts() ) : $query->the_post(); 
			$client_id = get_post_meta( get_the_ID(), 'client' , true);
		?>
		<tbody>
  			
		  	<tr id="<?php echo $qtid = get_the_ID();?>">
			    <td><?php echo $quotation_number = get_post_meta( get_the_ID(), 'quotation_number' , true); ?></td>
			    <td><?php echo $company_name = get_user_meta( $client_id, 'company_name' , true); ?></td>  
			    <td><?php echo $first_name = get_user_meta( $client_id, 'first_name' , true);?></td>
			    <td><?php echo $quotation_total = get_post_meta( get_the_ID(), 'quotation_total' , true ); ?></td>
			    <td><?php echo $date = get_the_date('Y-m-d',get_the_ID()); ?></td>
			    <td><a target="_blank" href="<?php echo site_url().'/wp-content/tcpdf/views/quotation.php?quotation_number='.$quotation_number.'&company_name='.$company_name.'&first_name='.$first_name.'&date='.$date.'&quotation_total='.$quotation_total;?>">Download</a></td>
	      	<?php $number++;  ?>
		   </tr>
	   </tbody> 		
	
		<?php   endwhile; wp_reset_postdata(); ?>
		<!-- pagination -->
		<!-- <p  class="post-pagination">
		<?php $big = 999999999; // need an unlikely integer
		echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $query->max_num_pages
		) ); ?>
		</p> -->
		<?php else : ?>
				<p><?php _e( 'Sorry, no quotation matched your criteria.' ); ?></p>
		<?php endif; ?>
	</table> 


