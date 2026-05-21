<?php
/**
 * Skeleton_WP\Skeleton_WP\Custom_code\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Custom_Code;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;

/**
 * Class for managing Custom_code integration.
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
    return 'custom_code';
  }

  /**
   * Adds the action and filter hooks to integrate with WordPress.
   */
  public function initialize(): void
  {
    add_action('wp_head', array($this, 'above_head'));
    add_action('wp_body_open', array($this, 'after_body'));
    add_action('wp_footer', array($this, 'above_body'));
  }

  /**
   * Add a custom code above the head close tag
   */
  public function above_head(): void
  {
    $above_head = get_theme_mod('above_head');

    if ($above_head) {
      echo $above_head;
    }
  }

  /**
   * Add a custom code after the body open tag
   */
  public function after_body(): void
  {
    $after_body = get_theme_mod('after_body');

    if ($after_body) {
      echo $after_body;
    }
  }

  /**
   * Add a custom code before the body close tag
   */
  public function above_body(): void
  {
    $above_body = get_theme_mod('above_body');

    if ($above_body) {
      echo $above_body;
    }
  }
}
