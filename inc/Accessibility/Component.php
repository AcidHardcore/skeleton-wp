<?php
/**
 * Skeleton_WP\Skeleton_WP\Accessibility\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Accessibility;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function Skeleton_WP\Skeleton_WP\skeleton_wp;
use WP_Post;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for improving accessibility among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'accessibility';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_navigation_script' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'action_print_skip_link_focus_fix' ) );
		add_filter( 'nav_menu_link_attributes', array( $this, 'filter_nav_menu_link_attributes_aria_current' ), 10, 2 );
		add_filter( 'page_menu_link_attributes', array( $this, 'filter_nav_menu_link_attributes_aria_current' ), 10, 2 );
	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_navigation_script() {

		// Enqueue the navigation script.
		wp_enqueue_script(
			'skeleton-wp-navigation',
			get_theme_file_uri( '/assets/js/navigation.min.js' ),
			array(),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/navigation.min.js' ) ),
			false
		);
		wp_script_add_data( 'skeleton-wp-navigation', 'async', true );
		wp_script_add_data( 'skeleton-wp-navigation', 'precache', true );
		wp_localize_script(
			'skeleton-wp-navigation',
			'skeletonWpScreenReaderText',
			array(
				'expand'   => __( 'Expand child menu', 'skeleton-wp' ),
				'collapse' => __( 'Collapse child menu', 'skeleton-wp' ),
			)
		);
	}

	/**
	 * Prints an inline script to fix skip link focus in IE11.
	 *
	 * The script is not enqueued because it is tiny and because it is only for IE11,
	 * thus it does not warrant having an entire dedicated blocking script being loaded.
	 *
	 * Since it will never need to be changed, it is simply printed in its minified version.
	 *
	 * @link https://git.io/vWdr2
	 */
	public function action_print_skip_link_focus_fix() {

		// Print the minified script.
		?>
		<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
		</script>
		<?php
	}

	/**
	 * Filters the HTML attributes applied to a menu item's anchor element.
	 *
	 * Checks if the menu item is the current menu item and adds the aria "current" attribute.
	 *
	 * @param array   $atts The HTML attributes applied to the menu item's `<a>` element.
	 * @param WP_Post $item The current menu item.
	 * @return array Modified HTML attributes
	 */
	public function filter_nav_menu_link_attributes_aria_current( array $atts, WP_Post $item ) : array {
		if ( isset( $item->current ) ) {
			if ( $item->current ) {
				$atts['aria-current'] = 'page';
			}
		} elseif ( ! empty( $item->ID ) ) {
			global $post;

			if ( ! empty( $post->ID ) && (int) $post->ID === (int) $item->ID ) {
				$atts['aria-current'] = 'page';
			}
		}

		return $atts;
	}
}
