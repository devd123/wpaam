<?php 
/**
 * WPAAM Template: Client List Template.
 * @since       1.0.0
 */
$current_user = wp_get_current_user();
$role = 'aam_client';
$number = 20;
$serial = 1;
// Get the Search Term
$search = ( isset($_GET["as"]) ) ? sanitize_text_field($_GET["as"]) : false ;

// Get Query Var for pagination. This already exists in WordPress
$page = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Calculate the offset (i.e. how many users we should skip)
$offset = ($page - 1) * $number;

if ($search){
// Generate the query based on search field
$my_users = new WP_User_Query( 
  array( 
    'role' => $role, 
    'search' => '*' . $search . '*' 
  ));
} else {
// Generate the query 
$my_users = new WP_User_Query( 
  array( 
    'role' => 'aam_client', 
    'order' => 'DESC',
    'offset' => $offset ,
    'number' => $number,
    'meta_key' => 'parent_user', 
    'meta_value' => $current_user->user_login,
    'compare' => '='
  ));
}

// Get the total number of clients. Based on this, offset and number 
// per page, we'll generate our pagination. 
$total_clients = $my_users->total_users;

// Calculate the total number of pages for the pagination
$total_pages = intval($total_clients / $number) + 1;

// The clients object. 
$clients = $my_users->get_results();
?>
			  
			<!-- <div class="aam-client-search">
				<form method="get" id="sul-searchform" action="<?php the_permalink() ?>">
					<p align="right"><input type="text" class="field" name="as" id="aam-s" style="width:25% ! important" />
					<input type="submit" class="submit" name="submit" id="aam-searchsubmit" value="Submit" /></p>
				</form>
				<hr>	
				<?php  if($search){ ?>
				<h2 >Search results for: <em><?php echo $search; ?></em></h2>
				<a href="<?php the_permalink(); ?>">Back to the listing ....</a>
				<?php } ?>
			</div>  -->

			<div class="client-list">  
			<?php if (!empty($clients))   { ?>
			<table class="wp-list-table" colspam="">
			  	<thead>
			  		<th width="10%">S.N.</th>
			  		<th>Full Name</th>
			  		<th>Email</th>		
			  		<th>Company</th>	
			  		<th>Date</th>
			  		<th>Action</th>

			  	</thead>
			  	<tbody>
			  		<?php
					// loop through each client
					
					foreach($clients as $client){
			    	$client_info = get_userdata($client->ID); ?>
				  	
				  	<tr id="<?php echo $client_info->ID; ?>">
					  <td><?php echo $serial; ?></td>   
					  <td><?php echo get_user_meta($client_info->ID, 'first_name', true).'&nbsp'.get_user_meta($client->ID, 'last_name', true); ?></td>
				      <td><?php echo get_user_meta($client_info->ID, 'client_email', true) ?></td>
				      <td><?php echo get_user_meta($client_info->ID, 'company_name', true); ?></a></td>
				      <td><?php echo date("d/m/Y", strtotime($client_info->user_registered)); ?></td>
				      <td><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Clients' ) ) ).'&client_tab=edit&client_id='.$client->ID; ?>"><span>Edit</span></a></td>
			      	<?php $serial++; } ?>
				   </tr>
			   </tbody> 	
		  	</table> 

			<?php } else { ?>
			  <h2>No clients found</h2>
			<?php } //endif ?>
			</div><!-- .client-list -->

	<nav id="nav-single" style="clear:both; float:none; margin-top:20px;">
		<!--  <h3 class="assistive-text">Page navigation</h3> -->
		<?php if ($page != 1) { ?>
		<span class="nav-previous"><a rel="prev" href="<?php get_permalink( get_page_by_title( 'Clients' ) ) ?>page/<?php echo $page - 1; ?>/"><span class="meta-nav">←</span> Previous</a></span>
		<?php } ?>

		<?php if ($page < $total_pages ) { ?>
		<span class="nav-next"><a rel="next" href="<?php get_permalink( get_page_by_title( 'Clients' ) ) ?>page/<?php echo $page + 1; ?>/">Next <span class="meta-nav">→</span></a></span>
		<?php } ?>
	</nav>

