<?php
/**
 * Skeleton_WP\Skeleton_WP\Editor\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Editor;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
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
    return 'editor';
  }

  /**
   * Adds the action and filter hooks to integrate with WordPress.
   */
  public function initialize()
  {
    add_action('after_setup_theme', array($this, 'action_add_editor_support'));
    //Disable Block CSS
    add_action('wp_enqueue_scripts', [$this, 'action_remove_block_css']);

    //Loading separate stylesheets for Core blocks, instead of a combined wp-block-library stylesheet (as the name indicates).
    add_filter('should_load_separate_core_block_assets', '__return_true');
    // Loading block scripts and stylesheets on demand only if the blocks are included in the page (not indicated by the name).
    add_filter('should_load_block_assets_on_demand', '__return_true');
  }

  /**
   * Disable Editor CSS.
   */

  public function action_remove_block_css()
  {

  }

  /**
   * Adds support for various editor features.
   */
  public function action_add_editor_support()
  {
    // Add support for editor styles.
//		add_theme_support( 'editor-styles' );

    // Add support for default block styles.
//		add_theme_support( 'wp-block-styles' );

    // Add support for wide-aligned images.
    add_theme_support('align-wide');

    // Themes can opt out of generated block layout styles that provide default structural styles
    // for core blocks including Group, Columns, Buttons, and Social Icons.
//    add_theme_support( 'disable-layout-styles' );

    add_theme_support('custom-units', 'rem', 'em', 'px', 'vw', 'vh');

    // WordPress comes with a number of block patterns built-in,
    // themes can opt-out of the bundled patterns and provide their own
    // remove_theme_support( 'core-block-patterns' );

    // Add support for responsive embedded content.
    add_theme_support('responsive-embeds');
  }
}
