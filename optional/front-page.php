<?php
/**
 * Render your site front page, whether the front page displays the blog posts index or a static page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

get_header();

// Use grid layout if blog index is displayed.
if ( is_home() ) {
	skeleton_wp()->print_styles( 'skeleton-wp-content', 'skeleton-wp-front-page' );
} else {
	skeleton_wp()->print_styles( 'skeleton-wp-content' );
}

?>
	<main id="primary" class="site-main">
		<?php

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', get_post_type() );
		}

		get_template_part( 'template-parts/content/pagination' );
		?>
	</main><!-- #primary -->
<?php
get_footer();
