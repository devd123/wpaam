<?php 
/**
 * wpaam Template: Invoices List Template.
 * @since       1.0.0
 */
?>

	<table class="wp-list-table" colspam="">
  		<thead>
	  		<th>Number</th>
	  		<th>Company</th>
	  		<th>Client</th>
	  		<th>Payment Date</th>
	  		<th>Total</th>
	  		<th>Created</th>
	  		<th width="10%">Act</th>
  		</thead>
	<?php
		global $wpdb;
		$author = get_current_user_id();  
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array('post_type' => 'aam-invoice' , 'author' => $author , 'posts_per_page' => 10 , 'paged' => $paged);
		$query = new WP_Query( $args);
		if ( $query->have_posts() ) : 
			$number = 1;
			while ( $query->have_posts() ) : $query->the_post(); 
			$client_id = get_post_meta( get_the_ID(), 'client' , true);
		?>
		
		
		<tbody>
  			
		  	<tr id="<?php echo get_the_ID();?>">
			    <td><?php echo get_post_meta( get_the_ID(), 'invoice_number' , true ); ?></td> 
			    <td><?php echo get_user_meta( $client_id, 'company_name' , true); ?></td>
			    <td><?php echo get_user_meta( $client_id, 'first_name' , true);?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'payment_date' , true ); ?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'invoice_total' , true ); ?></td>
			    <td><?php echo get_the_date('Y-m-d',get_the_ID()); ?></td>
			  	<td width="20%"><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Invoices' ) ) ).'&invoice_tab=edit&invoice_id='.get_the_ID(); ?>">
				    <img src="<?php echo WPAAM_PLUGIN_URL.'images/setting.png';?>"></a>
				    <img src="<?php echo WPAAM_PLUGIN_URL.'images/preview.png';?>"> 
				    <img src="<?php echo WPAAM_PLUGIN_URL.'images/notes.png';?>">
			    </td> 
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


