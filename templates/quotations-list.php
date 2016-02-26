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
	  		<th>Number</th>
	  		<th>Company</th>
	  		<th>Client</th>
	  		<th>total</th>
	  		<th>Created</th>
	  		<th width="10%">Act</th>
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
			$client_id = get_post_meta( get_the_ID(), 'client' , true);
		?>
		<tbody>
  			
		  	<tr id="<?php echo get_the_ID();?>">
			    <td><?php echo get_post_meta( get_the_ID(), 'quotation_number' , true); ?></td>
			    <td><?php echo get_user_meta( $client_id, 'company_name' , true); ?></td>  
			    <td><?php echo get_user_meta( $client_id, 'first_name' , true);?></td>
			    <td><?php echo get_post_meta( get_the_ID(), 'quotation_total' , true ); ?></td>
			    <td><?php echo get_the_date('Y-m-d',get_the_ID()); ?></td>
			   <td width="20%"><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Quotations' ) ) ).'&quotation_tab=edit&quotation_id='.get_the_ID(); ?>">
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
				<p><?php _e( 'Sorry, no quotation matched your criteria.' ); ?></p>
		<?php endif; ?>
	</table> 

	<?php else : ?>
		<p class="alert">
					<?php _e(' you are not allowed to see this page', 'wpaam'); ?>
		</p><!-- .alert -->

	<?php endif; ?>

