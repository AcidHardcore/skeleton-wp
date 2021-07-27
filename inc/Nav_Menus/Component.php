<?php
/**
 * Skeleton_WP\Skeleton_WP\Nav_Menus\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Nav_Menus;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use Skeleton_WP\Skeleton_WP\Templating_Component_Interface;
use stdClass;
use WP_Post;
use function add_action;
use function add_filter;
use function register_nav_menus;
use function esc_html__;
use function has_nav_menu;
use function wp_nav_menu;

/**
 * Class for managing navigation menus.
 *
 * Exposes template tags:
 * * `skeleton_wp()->is_primary_nav_menu_active()`
 * * `skeleton_wp()->display_primary_nav_menu( array $args = array() )`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const PRIMARY_NAV_MENU_SLUG = 'primary';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'nav_menus';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action('after_setup_theme', array($this, 'action_register_nav_menus'));
		add_filter('walker_nav_menu_start_el', array($this, 'filter_primary_nav_menu_dropdown_symbol'), 10, 4);
//		li classes
		add_filter('nav_menu_css_class', array($this, 'filter_nav_menu_css_classes'), 10, 4);
//		submenu classes
		add_filter('nav_menu_submenu_css_class', array($this, 'filter_submenu_classes'), 10, 3);
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `skeleton_wp()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return array(
			'is_primary_nav_menu_active' => array($this, 'is_primary_nav_menu_active'),
			'display_primary_nav_menu' => array($this, 'display_primary_nav_menu'),
		);
	}

	/**
	 * Registers the navigation menus.
	 */
	public function action_register_nav_menus() {
		register_nav_menus(
			array(
				static::PRIMARY_NAV_MENU_SLUG => esc_html__('Primary', 'skeleton-wp'),
			)
		);
	}

	/**
	 * Adds a dropdown symbol to nav menu items with children.
	 *
	 * Adds the dropdown markup after the menu link element,
	 * before the submenu.
	 *
	 * Javascript converts the symbol to a toggle button.
	 *
	 *
	 * @param string $item_output The menu item's starting HTML output.
	 * @param WP_Post $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args An object of wp_nav_menu() arguments.
	 * @return string Modified nav menu HTML.
	 */
	public function filter_primary_nav_menu_dropdown_symbol(string $item_output, WP_Post $item, int $depth, $args): string {

		// Only for our primary menu location.
		if(empty($args->theme_location) || static::PRIMARY_NAV_MENU_SLUG !== $args->theme_location) {
			return $item_output;
		}

		// Add the dropdown for items that have children.
		if(!empty($item->classes) && in_array('menu-item-has-children', $item->classes)) {
			return $item_output . '<button class="dropdown-toggle" aria-expanded="false" aria-label="Expand child menu"><i class="dropdown-symbol"></i></button>';
		}
//        example
//		if(array_search("menu-item-has-children", $item->classes) &&  $args->theme_location === 'mobile') {
//			return  $item_output . ' <button type="button" class="mobile-nav__toggle"></button>';
//		}

		return $item_output;
	}

	/**
	 * Checks whether the primary navigation menu is active.
	 *
	 * @return bool True if the primary navigation menu is active, false otherwise.
	 */
	public function is_primary_nav_menu_active(): bool {
		return (bool)has_nav_menu(static::PRIMARY_NAV_MENU_SLUG);
	}

	/**
	 * Displays the primary navigation menu.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_primary_nav_menu(array $args = array()) {
		if(!isset($args['container'])) {
			$args['container'] = '';
		}

		$args['theme_location'] = static::PRIMARY_NAV_MENU_SLUG;

		wp_nav_menu($args);
	}

	/**
	 * Update li classes
	 *
	 * @param string[] $classes Array of the CSS classes that are applied to the menu item's <li> element.
	 * @param WP_Post $item The current menu item.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @return array
	 *
	 */
	public function filter_nav_menu_css_classes($classes, $item, $args, $depth) {
		if($args->theme_location === static::PRIMARY_NAV_MENU_SLUG && $depth == 0) {
//			$classes[] = 'main-nav__item';

			if($item->current) {
//				$classes[] = 'active';
			}
		}

		return $classes;
	}

	/**
	 * @param string[] $classes Array of the CSS classes that are applied to the menu <ul> element.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @return array
	 */
	function filter_submenu_classes($classes, $args, $depth) {
		if($args->theme_location === static::PRIMARY_NAV_MENU_SLUG) {
//		$classes[] = 'main-nav__dropdown';
		}

		return $classes;
	}
}
