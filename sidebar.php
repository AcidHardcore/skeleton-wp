<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

if ( ! skeleton_wp()->is_primary_sidebar_active() ) {
	return;
}

skeleton_wp()->print_styles( 'wp-rig-sidebar', 'wp-rig-widgets' );

?>
<aside id="secondary" class="primary-sidebar widget-area">
	<h2 class="screen-reader-text"><?php esc_attr_e( 'Asides', 'wp-rig' ); ?></h2>
	<?php skeleton_wp()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
