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
use function wp_get_nav_menu_items;
use function get_option;

/**
 * Class for managing navigation menus.
 *
 * Menu locations are stored in the WP option `skeleton_wp_nav_menus` as an
 * associative array of slug => label pairs. Primary is always present as a fallback.
 *
 * To add locations programmatically (e.g. in a plugin or functions.php):
 *   update_option( 'skeleton_wp_nav_menus', [
 *       'primary' => 'Primary',
 *       'footer'  => 'Footer',
 *       'mobile'  => 'Mobile',
 *   ] );
 *
 * Or via filter:
 *   add_filter( 'skeleton_wp_nav_menus', function( array $menus ): array {
 *       $menus['footer'] = 'Footer';
 *       return $menus;
 *   } );
 *
 * Exposes template tags:
 * * `skeleton_wp()->is_nav_menu_active( string $slug )`
 * * `skeleton_wp()->display_nav_menu( array $args = [] )`
 * * `skeleton_wp()->wp_get_menu_array( string $current_menu )`
 */
class Component implements Component_Interface, Templating_Component_Interface {

  /**
   * Fallback used when the DB option is empty or missing.
   *
   * @var array<string, string>
   */
  protected const FALLBACK_MENUS = [
    'primary' => 'Primary',
  ];

  /**
   * @return string
   */
    public function get_slug(): string {
    return 'nav_menus';
  }

  public function initialize(): void {
    // Registration
    add_action( 'after_setup_theme', [ $this, 'action_register_nav_menus' ] );

    // Dropdown toggle button (converted to accessible button by navigation.js)
    add_filter( 'walker_nav_menu_start_el', [ $this, 'filter_nav_menu_dropdown_symbol' ], 10, 4 );

    // <li> classes
    add_filter( 'nav_menu_css_class', [ $this, 'filter_nav_menu_css_classes' ], 10, 4 );

    // <ul> submenu classes
    add_filter( 'nav_menu_submenu_css_class', [ $this, 'filter_submenu_classes' ], 10, 3 );

    // Extra items appended to the menu output
    add_filter( 'wp_nav_menu_items', [ $this, 'filter_main_menu_items' ], 10, 2 );

    // Link text / title wrapping
    add_filter( 'nav_menu_item_title', [ $this, 'filter_nav_menu_item_title' ], 10, 4 );
  }

  /**
   * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `skeleton_wp()`.
   *
   * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
   *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
   *               adding support for further arguments in the future.
   */
  public function template_tags(): array {
    return [
      'is_nav_menu_active' => [ $this, 'is_nav_menu_active' ],
      'display_nav_menu'   => [ $this, 'display_nav_menu' ],
      'wp_get_menu_array'  => [ $this, 'wp_get_menu_array' ],
    ];
  }

  // -------------------------------------------------------------------------
  // Actions
  // -------------------------------------------------------------------------

  /**
   * Registers all nav menu locations returned by nav_menus().
   *
   * @return void
   */
  public function action_register_nav_menus(): void {
    $locations = array_map(
      fn( string $label ) => esc_html__( $label, 'skeleton-wp' ),
      $this->nav_menus()
    );

    register_nav_menus( $locations );
  }

  // -------------------------------------------------------------------------
  // Filters
  // -------------------------------------------------------------------------

  /**
   * Appends a dropdown toggle button after menu items that have children.
   * The button is converted to a fully accessible toggle by navigation.js.
   *
   * To customise the button per location:
   *   if ( $args->theme_location === 'mobile' ) { return '...'; }
   *
   * @param string $item_output
   * @param WP_Post $item
   * @param int $depth
   * @param object $args
   * @return string
   */
  public function filter_nav_menu_dropdown_symbol( string $item_output, WP_Post $item, int $depth, object $args ): string {
    if ( ! $this->is_registered_location( $args ) ) {
      return $item_output;
    }

    if ( ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes, true ) ) {
      $item_output .= '<button class="dropdown-toggle" aria-expanded="false" aria-label="Expand child menu">'
        . '<i class="dropdown-symbol"></i>'
        . '</button>';
    }

    return $item_output;
  }

  /**
   * Filters CSS classes on each <li> menu item.
   *
   * @param array $classes
   * @param WP_Post $item
   * @param stdClass $args
   * @param int $depth
   * @return array
   *
   * Customisation examples (uncomment as needed):
   *   $classes[] = 'main-nav__item';
   *   if ( $item->current ) { $classes[] = 'active'; }
   *
   */
  public function filter_nav_menu_css_classes( array $classes, WP_Post $item, stdClass $args, int $depth ): array {
    if ( ! $this->is_registered_location( $args ) || $depth !== 0 ) {
      return $classes;
    }

    // $classes[] = 'main-nav__item';

    return $classes;
  }

  /**
   * Filters CSS classes on each <ul> submenu element.
   *
   * @param array $classes
   * @param stdClass $args
   * @param int $depth
   * @return array
   *
   * Customisation example:
   *   $classes[] = 'main-nav__dropdown';
   */
  public function filter_submenu_classes( array $classes, stdClass $args, int $depth ): array {
    if ( ! $this->is_registered_location( $args ) ) {
      return $classes;
    }

    // $classes[] = 'main-nav__dropdown';

    return $classes;
  }

  /**
   * Appends or prepends extra HTML to a menu's item list.
   *
   * @param string $items
   * @param object $args
   *
   * Customisation example — inject a template part into the primary menu:
   *   ob_start();
   *   get_template_part( 'template-parts/header/branding' );
   *   $items .= ob_get_clean();
   */
  public function filter_main_menu_items( string $items, object $args ): string {
    if ( ! $this->is_registered_location( $args ) ) {
      return $items;
    }

    return $items;
  }

  /**
   * Wraps or transforms a menu item's link text.
   *
   * @param string $title
   * @param WP_Post $item
   * @param object $args
   * @param int $depth
   * @return string
   *
   * Customisation example — duplicate title for CSS hover effects:
   *   $title = '<span>' . $title . '</span><b>' . $title . '</b>';
   */
  public function filter_nav_menu_item_title( string $title, WP_Post $item, object $args, int $depth ): string {
    if ( ! $this->is_registered_location( $args ) ) {
      return $title;
    }

    return $title;
  }


  /**
   * Checks whether a registered nav menu location has a menu assigned.
   *
   * @param string $slug Menu location slug.
   * @return bool
   */
  public function is_nav_menu_active( string $slug ): bool {
    return has_nav_menu( $slug );
  }

  /**
   * Renders a nav menu via wp_nav_menu().
   *
   * @param array $args wp_nav_menu() arguments. Defaults to the first location in nav_menus().
   *
   * Usage examples:
   *   skeleton_wp()->display_nav_menu();                                 // primary (default)
   *   skeleton_wp()->display_nav_menu(['theme_location' => 'footer']);   // footer
   *   skeleton_wp()->display_nav_menu([
   *       'menu_id'        => 'primary-menu',
   *       'theme_location' => 'primary',
   *   ]);
   */
  public function display_nav_menu( array $args = [] ): void {
    $args = array_merge(
      [
        'container'      => '',
        'theme_location' => array_key_first( $this->nav_menus() ),
      ],
      $args
    );

    wp_nav_menu( $args );
  }

  /**
   * Menu array builder
   *
   * @param $menu_array
   * @param $menu_item
   * @return array
   */
  public function populate_children( $menu_array, $menu_item ): array {
    $children = [];
    if ( ! empty( $menu_array ) ) {
      foreach ( $menu_array as $k => $m ) {
        if ( $m->menu_item_parent == $menu_item->ID ) {
          $children[ $m->ID ]             = [];
          $children[ $m->ID ]['ID']       = $m->ID;
          $children[ $m->ID ]['title']    = $m->title;
          $children[ $m->ID ]['url']      = $m->url;
          $children[ $m->ID ]['target']   = $m->target;
          unset( $menu_array[ $k ] ); // recursion guard: prevents infinite loop on circular parent references
          $children[ $m->ID ]['children'] = $this->populate_children( $menu_array, $m );
        }
      }
    }
    return $children;
  }

  /**
   * Returns a nested array representation of a nav menu.
   *
   * @param string $current_menu Menu slug or location.
   * @return array
   */
  public function wp_get_menu_array( string $current_menu = 'primary' ): array {
    $menu_array = wp_get_nav_menu_items( $current_menu );

    $menu = [];

    if ( $menu_array ) {
      foreach ( $menu_array as $m ) {
        if ( empty( $m->menu_item_parent ) ) {
          $menu[ $m->ID ]             = [];
          $menu[ $m->ID ]['ID']       = $m->ID;
          $menu[ $m->ID ]['title']    = $m->title;
          $menu[ $m->ID ]['url']      = $m->url;
          $menu[ $m->ID ]['target']   = $m->target;
          $menu[ $m->ID ]['children'] = $this->populate_children( $menu_array, $m );
        }
      }
    }

    return $menu;
  }

  /**
   * Returns the active nav menu locations.
   *
   * Reads from the `skeleton_wp_nav_menus` WP option, falls back to FALLBACK_MENUS,
   * and always ensures 'primary' is present. Passes through the `skeleton_wp_nav_menus`
   * filter so locations can be added at runtime without touching the DB.
   *
   * @return array<string, string> slug => label
   */
  protected function nav_menus(): array {
    $from_db = get_option( 'skeleton_wp_nav_menus', [] );

    $menus = ( is_array( $from_db ) && ! empty( $from_db ) )
      ? $from_db
      : static::FALLBACK_MENUS;

    /**
     * Filters the nav menu locations at runtime.
     *
     * @param array<string, string> $menus slug => label
     */
    return (array) apply_filters( 'skeleton_wp_nav_menus', $menus );
  }

  /**
   * Returns true when the given $args object targets one of our registered locations.
   *
   * @param object $args
   * @return bool
   */
  private function is_registered_location( object $args ): bool {
    return ! empty( $args->theme_location )
      && array_key_exists( $args->theme_location, $this->nav_menus() );
  }
}
