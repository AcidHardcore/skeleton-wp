<?php
/**
 * Skeleton_WP\Skeleton_WP\Pagination\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Pagination;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;

/**
 * Class for Pagination that allows your user to page back and forth through multiple pages of content.
 *
 * @link https://developer.wordpress.org/themes/functionality/pagination/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'pagination';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_support' ) );
	}

}
