<?php
/**
 * Skeleton_WP\Skeleton_WP\Custom_Logo\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Custom_Logo;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use function add_theme_support;
use function apply_filters;

/**
 * Class for adding custom logo support.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_logo';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_logo_support' ) );
		add_filter( 'get_custom_logo', array( $this, 'change_logo_class') );
	}

	/**
	 * Adds support for the Custom Logo feature.
	 */
	public function action_add_custom_logo_support() {
		add_theme_support(
			'custom-logo',
			apply_filters(
				'skeleton_wp_custom_logo_args',
				array(
					'height'      => 250,
					'width'       => 250,
					'flex-width'  => false,
					'flex-height' => false,
				)
			)
		);
	}

	/**
	 * Replace CSS class of the logo
	 * @param $html
	 * @return array|string|string[]
	 */
	public function change_logo_class( $html ) {

		$html = str_replace( 'custom-logo-link', 'logo', $html );
		$html = str_replace( 'custom-logo', 'logo__image', $html );


		return $html;
	}
}
