<?php
/**
 * Template part for displaying the header branding
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

?>

<?php if ( ! has_custom_logo() ) { ?>

	<?php if ( is_front_page() && is_home() ) : ?>

		<h1 class="site-title"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

	<?php else : ?>

		<p class="site-title"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>

	<?php endif; ?>

	<?php
} else {
	the_custom_logo();
}
?>
