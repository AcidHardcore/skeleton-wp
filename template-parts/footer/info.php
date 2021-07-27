<?php
/**
 * Template part for displaying the footer info
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

?>

<div class="site-info">
	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'skeleton-wp' ) ); ?>">
		<?php
		/* translators: %s: CMS name, i.e. WordPress. */
		printf( esc_html__( 'Proudly powered by %s', 'skeleton-wp' ), 'WordPress' );
		?>
	</a>
	<span class="sep"> | </span>
	<?php
	/* translators: Theme name. */
	printf( esc_html__( 'Theme: %s by the contributors.', 'skeleton-wp' ), '<a href="' . esc_url( 'https://github.com/AcidHardcore/skeleton-wp' ) . '">SKeleton WP</a>' );

	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '<span class="sep"> | </span>' );
	}
	?>
</div><!-- .site-info -->
