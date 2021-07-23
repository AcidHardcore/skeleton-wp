<?php
/**
 * The template for displaying 500 pages (internal server errors)
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

get_header();

skeleton_wp()->print_styles( 'skeleton-wp-content' );

?>
	<main id="primary" class="site-main">
		<?php get_template_part( 'template-parts/content/error', '500' ); ?>
	</main><!-- #primary -->
<?php
get_footer();
