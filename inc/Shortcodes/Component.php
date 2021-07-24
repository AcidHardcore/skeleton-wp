<?php
/**
 * Skeleton_WP\Skeleton_WP\Shortcodes\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Shortcodes;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_shortcode;

/**
 * Class for Shortcodes
 *
 * @link https://codex.wordpress.org/Shortcode_API
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'shortcodes';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_shortcode('year', array($this, 'year_shortcode'));
	}

	/**
	 * Year short code function
	 */
	public function year_shortcode() {
		return date('Y');
	}
}
