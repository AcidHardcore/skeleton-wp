<?php
/**
 * Template part for displaying a pagination
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
	'post_type'=>'post', // Your post type name
	'posts_per_page' => 6,
	'paged' => $paged,);

$query = new WP_Query( $args );if ( $query->have_posts() ) {
	while ( $query->have_posts() ) : $query->the_post();

// YOUR CODE

	endwhile;

	$total_pages = $query->max_num_pages;

	if ($total_pages > 1){

		$current_page = max(1, get_query_var('paged'));

		echo paginate_links(array(
			'base' => get_pagenum_link(1) . '%_%',
			'format' => '/page/%#%',
			'current' => $current_page,
			'total' => $total_pages,
			'prev_text' => __('« prev'),
			'next_text' => __('next »'),
		));
	}
}
wp_reset_postdata();
