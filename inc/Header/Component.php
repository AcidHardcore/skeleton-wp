<?php
/**
 * Skeleton_WP\Skeleton_WP\Header\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Header;

use function Skeleton_WP\Skeleton_WP\skeleton_wp;
use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use function add_filter;


/**
 * Class for managing navigation menus.
 *
 * Exposes template tags:
 * * `skeleton_wp()->is_primary_nav_menu_active()`
 * * `skeleton_wp()->display_primary_nav_menu( array $args = array() )`
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'header';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

    add_action('wp_enqueue_scripts', array($this, 'action_enqueue_header_script'));
	}

  /**
   * Enqueues a script that adding classes on scroll to manipulate a header.
   */
  public function action_enqueue_header_script() {
    $sticky_header = get_theme_mod('sticky_header');
    if($sticky_header) {
      // Enqueue the navigation script.
      wp_enqueue_script(
        'skeleton-wp-header',
        get_theme_file_uri('/assets/js/header.min.js'),
        array('jquery'),
        skeleton_wp()->get_asset_version(get_theme_file_path('/assets/js/header.min.js')),
        false
      );
      wp_script_add_data('skeleton-wp-navigation', 'async', true);
      wp_script_add_data('skeleton-wp-navigation', 'precache', true);
    }
  }
}
