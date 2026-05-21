<?php
/**
 * Skeleton_WP\Skeleton_WP\Base_Support\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Base_Support;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use function add_filter;
use function add_theme_support;
use function is_singular;
use function pings_open;
use function esc_url;
use function get_bloginfo;
use function get_theme_mod;

/**
 * Class for adding basic theme support, most of which is mandatory to be implemented by all themes.
 *
 * Exposes template tags:
 * * `skeleton_wp()->get_version()`
 * * `skeleton_wp()->get_asset_version( string $filepath )`
 */
class Component implements Component_Interface
{

  /**
   * Gets the unique identifier for the theme component.
   *
   * @return string Component slug.
   */
  public function get_slug(): string
  {
    return 'base_support';
  }

  /**
   * Adds the action and filter hooks to integrate with WordPress.
   */
  public function initialize(): void
  {
    add_action('after_setup_theme', array($this, 'action_essential_theme_support'));
    add_action('wp_head', array($this, 'action_add_pingback_header'));
    add_filter('body_class', array($this, 'filter_body_classes_add_hfeed'));
    add_filter('embed_defaults', array($this, 'filter_embed_dimensions'));
    add_filter('theme_scandir_exclusions', array($this, 'filter_scan_dir_exclusions_for_optional_templates'));

    // Remove SVG Dutones
//		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

    // remove wp_footer actions which add's global inline styles
//		remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

    // remove render_block filters which adding unnecessary stuff
//		remove_filter('render_block', 'wp_render_duotone_support');
//		remove_filter('render_block', 'wp_restore_group_inner_container');
//		remove_filter('render_block', 'wp_render_layout_support_flag');

    //excerpt
    add_filter('excerpt_length', array($this, 'filter_excerpt_length'));
    add_filter('excerpt_more', array($this, 'filter_excerpt_more'));
  }

  /**
   * Adds theme support for essential features.
   * @return void
   */
  public function action_essential_theme_support(): void
  {
    // Add default RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Ensure WordPress manages the document title.
    add_theme_support('title-tag');

    // Ensure WordPress theme features render in HTML5 markup.
    add_theme_support(
      'html5',
      array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
      )
    );

    // Add support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

  }

  /**
   * Adds a pingback url auto-discovery header for singularly identifiable articles.
   *
   * @return void
   */
  public function action_add_pingback_header(): void
  {
    if (is_singular() && pings_open()) {
      printf(
        '<link rel="pingback" href="%s">',
        esc_url(get_bloginfo('pingback_url'))
      );
    }
  }

  /**
   * Adds a 'hfeed' class to the array of body classes for non-singular pages.
   *
   * @param array $classes Classes for the body element.
   * @return array Filtered body classes.
   */
  public function filter_body_classes_add_hfeed(array $classes): array
  {
    if (!is_singular()) {
      $classes[] = 'hfeed';
    }

    return $classes;
  }

  /**
   * Sets the embed width in pixels, based on the theme's design and stylesheet.
   *
   * @param array $dimensions An array of embed width and height values in pixels (in that order).
   * @return array Filtered dimensions array.
   */
  public function filter_embed_dimensions(array $dimensions): array
  {
    $dimensions['width'] = $GLOBALS['content_width'] ?? 720;
    return $dimensions;
  }

  /**
   * Excludes any directory named 'optional' from being scanned for theme template files.
   *
   * @link https://developer.wordpress.org/reference/hooks/theme_scandir_exclusions/
   *
   * @param array $exclusions the default directories to exclude.
   * @return array Filtered exclusions.
   */
  public function filter_scan_dir_exclusions_for_optional_templates(array $exclusions): array
  {
    return array_merge(
      $exclusions,
      array('optional')
    );
  }

  /**
   * Change post excerpt length
   * @param int $length The excerpt length.
   *
   * @return int
   */
  public function filter_excerpt_length(int $length): int
  {
    $custom_length = absint(get_theme_mod('excerpt_length'));

    return $custom_length > 0 ? $custom_length : $length;
  }

  /**
   * @param string $more The excerpt.
   * @return string
   */
  public function filter_excerpt_more(string $more): string
  {
    if (!is_admin()) {
      $more = '&hellip;';
    }
    return $more;
  }
}
