<?php 
/**
 * WPAAM Template: Client Invoices Template.
 * @since       1.0.0
 */
?>
	<div id="invoice_box" title="Invoice Preview"></div>
	<table class="wp-list-table" colspam="">
  		<thead>
	  		<th>Number</th>
	  		<th>Company</th>
	  		<th>Name</th>
	  		<th>Payment Date</th>
	  		<th>Total</th>
	  		<th>Created</th>
	  		<th>Action</th>
  		</thead>
	<?php
		global $wpdb;
		$author = get_current_user_id();  
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'post_type' => 'aam-invoice',  
			'posts_per_page' => 10, 
			'paged' => $paged,
			'meta_query' => array(
			       	array(
			           'key' => 'client',
			           'value' => $user_id,
			           'compare' => '=',
			        ),
   				),
			);
		$query = new WP_Query( $args);
		if ( $query->have_posts() ) : 
			$number = 1;
			while ( $query->have_posts() ) : $query->the_post(); 
			$client_id = get_post_meta( get_the_ID(), 'client' , true);
		?>
		
		
		<tbody>
  			
		  	<tr id="<?php echo get_the_ID();?>">
			    <td><?php echo $invoice_number = get_post_meta( get_the_ID(), 'invoice_number' , true ); ?></td> 
			    <td><?php echo $company_name = get_user_meta( $client_id, 'company_name' , true); ?></td>
			    <td><?php echo $first_name = get_user_meta( $client_id, 'first_name' , true);?></td>
			    <td><?php echo $payment_date = get_post_meta( get_the_ID(), 'payment_date' , true ); ?></td>
			    <td><?php echo $invoice_total = get_post_meta( get_the_ID(), 'invoice_total' , true ); ?></td>
			    <td><?php echo $invoice_date = get_the_date('Y-m-d',get_the_ID()); ?></td>
			    <td><a target="_blank" href="<?php echo site_url().'/wp-content/tcpdf/views/invoices.php?invoice_number='.$invoice_number.'&company_name='.$company_name.'&first_name='.$first_name.'&payment_date='.$payment_date.'&invoice_total='.$invoice_total.'&invoice_date='.$invoice_date;?>">Download</a></td>
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
				<p><?php _e( 'Sorry, no invoices matched your criteria.' ); ?></p>
		<?php endif; ?>
	</table> 


