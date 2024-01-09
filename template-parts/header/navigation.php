<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

if ( ! skeleton_wp()->is_nav_menu_active('primary') ) {
	return;
}

?>

<nav id="site-navigation" class="main-nav main-nav--toggle-sub main-nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'skeleton-wp' ); ?>">

	<button class="main-nav__toggle burger" aria-label="<?php esc_attr_e( 'Open menu', 'skeleton-wp' ); ?>" aria-controls="primary-menu" aria-expanded="false">
		<span><?php esc_html_e( 'Menu', 'skeleton-wp' ); ?></span>
	</button>

  <?php skeleton_wp()->display_nav_menu( array(
    'menu_id'        => 'primary-menu',
    'theme_location' => 'primary'
  ) ); ?>

</nav><!-- #site-navigation -->
