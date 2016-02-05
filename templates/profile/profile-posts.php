<?php
/**
 * wpaam Template: "Posts" profile tab.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Query arguments
$args = array( 'author' => $user_data->ID );

// The Query
$posts_query = new WP_Query( apply_filters( 'wpaam_profile_posts_query_args', $args ) );
?>

<div class="wpaam-user-posts-list">

	<!-- the loop -->
	<?php

		if ( $posts_query->have_posts() ) :

			while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>

				<div class="wpaam-post" id="wpaam-post-<?php echo the_id();?>">

					<a href="<?php the_permalink();?>" class="wpaam-post-title"><?php the_title();?></a>

					<ul class="wpaam-post-meta">
						<li>
							<strong><?php _e( 'Posted on:', 'wpaam' ); ?></strong>
							<?php echo get_the_date(); ?> -
						</li>
						<li>
							<strong><?php _e( 'Comments:', 'wpaam' ); ?></strong>
							<?php comments_popup_link( __( 'No Comments', 'wpaam' ), __( '1 Comment', 'wpaam' ), __( '% Comments', 'wpaam' ) ); ?>
						</li>
					</ul>

				</div>

			<?php endwhile;

		else :

			// Display error message
			$args = array(
				'id'   => 'wpaam-posts-not-found',
				'type' => 'notice',
				'text' => sprintf( __( '%s did not submit any posts yet.', 'wpaam' ), $user_data->display_name )
			);
			wpaam_message( $args );

		endif;

		// Reset the original query - do not remove this.
		wp_reset_postdata();

	?>
	<!-- end loop -->

</div>
