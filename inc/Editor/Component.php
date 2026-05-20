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
  public function initialize(): void
  {
    add_action('after_setup_theme', array($this, 'action_add_editor_support'));

    // Loading block scripts and stylesheets on demand only if the blocks are included in the page (not indicated by the name).
    add_filter('should_load_block_assets_on_demand', '__return_true');
  }

  /**
   * Adds support for various editor features.
   */
  public function action_add_editor_support(): void
  {

    // Add support for default block styles.
		add_theme_support( 'wp-block-styles' );

    // Add support for wide-aligned images.
    add_theme_support('align-wide');

    add_theme_support('custom-units', 'rem', 'em', 'px', 'vw', 'vh');

    // Add support for responsive embedded content.
    add_theme_support('responsive-embeds');
  }
}
