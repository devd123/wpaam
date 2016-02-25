<?php 
/**
 * wpaam Template: Quotation List Template.
 * @since       1.0.0
 */
?>

	<?php if ( is_user_logged_in() && !current_user_can( 'edit_quotation' ) ) : ?>

			<p class="log-in-out alert">
			<?php printf( __('You are logged in as <a href="%1$s" title="%2$s">%2$s</a>.  You are not allowed to add quotation !', 'wpaam'), get_author_posts_url( $curauth->ID ), $user_identity ); ?> <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Log out of this account', 'wpaam'); ?>"><?php _e('Logout &raquo;', 'wpaam'); ?></a>
			</p><!-- .log-in-out .alert -->
	
	<?php elseif ( is_user_logged_in() && current_user_can( 'edit_quotation' ) && current_user_can( 'publish_quotation' ) ) : ?>
	<table class="wp-list-table" colspam="">
  		<thead>
	  		<th width="10%">S.N.</th>
	  		<th>Company</th>
	  		<th>Client</th>
	  		<th>Product</th>
	  		<th>Price</th>
	  		<th>Date</th>
	  		<th>Action</th>
  		</thead>
		
	<?php
		global $wpdb;
		$author = get_current_user_id();  
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array('post_type' => 'aam-quotation' , 'author' => $author , 'posts_per_page' => 50 , 'paged' => $paged);
		$query = new WP_Query( $args);
		if ( $query->have_posts() ) : 
			$number = 1;
			while ( $query->have_posts() ) : $query->the_post(); 
			$quotationid = get_the_ID(); ?>
		<tbody>
  			
		  	<tr id="<?php echo get_the_ID();?>">
			    <td><?php echo $number; ?></td> 
			     <td><?php echo get_user_meta( $author, 'company_name' , true); ?></td>  
			    <td><?php echo get_post_meta( get_the_ID(), 'client_name' , true); ?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'product_name' , true); ?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'quotation_price' , true ); ?></td>
			    <td><?php echo get_the_date('Y-m-d',get_the_ID()); ?></td>
			    <td><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Quotations' ) ) ).'&quotation_tab=edit&quotation_id='.$quotationid; ?>"><span>Edit</span></a>|
			    <!-- <span><a href="javascript:void()" class="del-rpoduct">Delete</span></td> -->
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

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to see this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>

