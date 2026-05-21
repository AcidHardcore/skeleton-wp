<?php
/**
 * Skeleton_WP\Skeleton_WP\Post_Thumbnails\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Post_Thumbnails;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use function add_theme_support;
use function add_image_size;

/**
 * Class for managing post thumbnail support.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
class Component implements Component_Interface
{

  private const SCREEN_MAX_SIZE = 2560;

  /**
   * Gets the unique identifier for the theme component.
   *
   * @return string Component slug.
   */
  public function get_slug(): string
  {
    return 'post_thumbnails';
  }

  /**
   * Adds the action and filter hooks to integrate with WordPress.
   */
  public function initialize(): void
  {
    add_action('after_setup_theme', array($this, 'action_add_post_thumbnail_support'));
    add_action('after_setup_theme', array($this, 'action_add_image_sizes'));
  }

  /**
   * Adds support for post thumbnails.
   */
  public function action_add_post_thumbnail_support(): void
  {
    add_theme_support('post-thumbnails');
  }

  /**
   * Adds custom image sizes.
   */
  public function action_add_image_sizes(): void
  {
    $raw_width = get_theme_mod('post_thumbnail_width');
    $raw_height = get_theme_mod('post_thumbnail_height');

    $width = filter_var($raw_width, FILTER_VALIDATE_INT, [
      'options' => ['min_range' => 1, 'max_range' => self::SCREEN_MAX_SIZE],
    ]);

    $height = filter_var($raw_height, FILTER_VALIDATE_INT, [
      'options' => ['min_range' => 0, 'max_range' => self::SCREEN_MAX_SIZE],
    ]);

    if ($width === false || $height === false) {
      return;
    }

    $crop = ($height > 0);

    add_image_size('skeleton-wp-featured', $width, $height, $crop);
  }
}
