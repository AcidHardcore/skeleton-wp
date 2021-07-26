<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

if ( ! skeleton_wp()->is_primary_nav_menu_active() ) {
	return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'skeleton-wp' ); ?>">

	<button class="menu-toggle burger" aria-label="<?php esc_attr_e( 'Open menu', 'skeleton-wp' ); ?>" aria-controls="primary-menu" aria-expanded="false">
		<span><?php esc_html_e( 'Menu', 'skeleton-wp' ); ?></span>
	</button>

	<?php skeleton_wp()->display_primary_nav_menu( array(
		'menu_id' => 'primary-menu'
	) ); ?>

</nav><!-- #site-navigation -->
