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
//		extra menu items
		add_filter('wp_nav_menu_items', array($this, 'filter_main_menu'), 10, 2);
		//link text
		add_filter( 'nav_menu_item_title', array($this, 'filter_nav_menu_item_title'), 10, 4 );
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
			'is_nav_menu_active' => array($this, 'is_nav_menu_active'),
			'display_nav_menu' => array($this, 'display_nav_menu'),
			'wp_get_menu_array' => array($this, 'wp_get_menu_array'),
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
  public function is_nav_menu_active($slug): bool {
    return (bool)has_nav_menu($slug);
  }

	/**
	 * Displays the primary navigation menu.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
  public function display_nav_menu(array $args = array()) {
    if(!isset($args['container'])) {
      $args['container'] = '';
    }
    if(!isset($args['theme_location'])) {
      $args['theme_location'] = static::PRIMARY_NAV_MENU_SLUG;
    }
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

	/**
	 * @param $items
	 * @param $args
	 * @return mixed|string
	 */
	public function filter_main_menu($items, $args) {
		if ($args->theme_location === static::PRIMARY_NAV_MENU_SLUG) {
//			ob_start();
//			get_template_part('template-parts/header/branding');
//			$items .= ob_get_clean();
		}

		return $items;
	}

	function populate_children($menu_array, $menu_item)
	{
		$children = array();
		if (!empty($menu_array)){
			foreach ($menu_array as $k=>$m) {
				if ($m->menu_item_parent == $menu_item->ID) {
					$children[$m->ID] = array();
					$children[$m->ID]['ID'] = $m->ID;
					$children[$m->ID]['title'] = $m->title;
					$children[$m->ID]['url'] = $m->url;
					unset($menu_array[$k]);
					$children[$m->ID]['children'] = $this->populate_children($menu_array, $m);
				}
			}
		};
		return $children;
	}

	public function wp_get_menu_array($current_menu='primary') {

		$menu_array = wp_get_nav_menu_items($current_menu);

		$menu = array();

    if ($menu_array) {
      foreach ($menu_array as $m) {
        if (empty($m->menu_item_parent)) {
          $menu[$m->ID] = array();
          $menu[$m->ID]['ID'] = $m->ID;
          $menu[$m->ID]['title'] = $m->title;
          $menu[$m->ID]['url'] = $m->url;
          $menu[$m->ID]['children'] = $this->populate_children($menu_array, $m);
        }
      }
    }

		return $menu;
	}

	/**
	 * @param $title
	 * @param $item
	 * @param $args
	 * @param $depth
	 * @return string
	 */
	public function filter_nav_menu_item_title( $title, $item, $args, $depth ) {

//		if($args->theme_location === static::PRIMARY_NAV_MENU_SLUG) {
//			$title = '<span>' . $title . '</span><b>' . $title . '</b>';
//		}

		return $title;
	}
}
