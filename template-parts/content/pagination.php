<?php
/**
 * Template part for displaying a pagination
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

the_posts_pagination(
	array(
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'skeleton-wp' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'skeleton-wp' ),
		'screen_reader_text' => __( 'Page navigation', 'skeleton-wp' ),
	)
);
