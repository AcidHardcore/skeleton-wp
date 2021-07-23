<?php
/**
 * The template for displaying all single posts and pages
 *
 * If posts and pages use the same template, singular.php can be used.
 * This template is ignored if single.php and/or page.php is present.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/post-template-files/#singular-php
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

get_header();

skeleton_wp()->print_styles( 'skeleton-wp-content' );

?>
	<main id="primary" class="site-main">
		<?php

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', get_post_type() );
		}
		?>
	</main><!-- #primary -->
<?php
get_sidebar();
get_footer();
