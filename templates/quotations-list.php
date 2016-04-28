<?php 
/**
 * wpaam Template: Quotation List Template.
 * @since       1.0.0
 */
?>
	<div id="dialog_box" title="Quotation Preview"></div>
	<form method="get" id="sul-searchform" action="<?php the_permalink() ?>">
		<p align="right"><input type="text" class="search field" name="as" id="aam-s" style="width:25% ! important" />
		<input type="submit" class="submit" name="submit" id="aam-searchsubmit" value="Submit" /></p>
	</form>
	<table class="wp-list-table" colspam="">
  		<thead>
	  		<th>Number</th>
	  		<th>Company</th>
	  		<th>Client</th>
	  		<th>total</th>
	  		<th>Created</th>
	  		<th>Action</th>
	  		<th>Copy Quotation</th>
  		</thead>
		
	<?php
		global $wpdb;
		$author = get_current_user_id();  
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array('post_type' => 'aam-quotation' , 'author' => $author , 'posts_per_page' => 10 , 'paged' => $paged);
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
			    <td>
				    <a target="_blank" href="<?php echo site_url().'/wp-content/tcpdf/views/quotation.php?quotation_number='.$quotation_number.'&company_name='.$company_name.'&first_name='.$first_name.'&date='.$date.'&quotation_total='.$quotation_total;?>">PDF</a>
			   		<?php if ( is_user_logged_in() && current_user_can( 'edit_quotation' ) ) : ?>
				   	<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Quotations' ) ) ).'&quotation_tab=edit&quotation_id='.get_the_ID(); ?>">Modify</a>
					<?php endif;?>
				   <a class="qt_preview" href="javascript:void()" qid="<?php echo get_the_ID();?>">Preview</a>
			    </td> 
			    <td><a href="javascript:void()" class="button" id="quotation_copy" data-id="<?php echo get_the_ID();?>">Invoice</td>
			    <td><a href="javascript:void()" class="button" id="" data-id="<?php echo get_the_ID();?>">Send Quotation</td>
			    
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

	

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

