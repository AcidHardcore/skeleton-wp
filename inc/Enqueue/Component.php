<?php
/**
 * Skeleton_WP\Skeleton_WP\Enqueue\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Enqueue;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function Skeleton_WP\Skeleton_WP\skeleton_wp;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function wp_register_script;
use function get_theme_file_uri;
use function get_theme_file_path;


class Component implements Component_Interface
{

  /**
   * Gets the unique identifier for the theme component.
   *
   * @return string Component slug.
   */
  public function get_slug(): string
  {
    return 'enqueue';
  }

  public function initialize()
  {
    add_action('wp_enqueue_scripts', [$this, 'action_enqueue']);
//    add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue' ) );
    add_action('enqueue_block_editor_assets', array($this, 'action_enqueue'));
  }

  /**
   * Define all frontend scripts to enqueue.
   *
   * @return Asset[]
   */
  private function scripts(): array
  {
    return [
      new Asset(
        handle: 'skeleton-wp-swiper',
        url: get_theme_file_uri('/assets/js/swiper-bundle.js'),
        path: get_theme_file_path('/assets/js/swiper-bundle.js'),
        args: ['strategy' => 'defer', 'in_footer' => true],
        enqueue: false
      ),
      new Asset(
        handle: 'skeleton-wp-slick',
        url: get_theme_file_uri('/assets/js/slick.min.js'),
        path: get_theme_file_path('/assets/js/slick.min.js'),
        dependencies: ['jquery'],
        args: ['strategy' => 'defer', 'in_footer' => true],
        enqueue: false
      ),
      new Asset(
        handle: 'skeleton-wp-gsap',
        url: get_theme_file_uri('/assets/js/gsap.min.js'),
        path: get_theme_file_path('/assets/js/gsap.min.js'),
        args: ['strategy' => 'defer', 'in_footer' => true],
        enqueue: false
      ),
      new Asset(
        handle: 'skeleton-wp-ScrollTrigger',
        url: get_theme_file_uri('/assets/js/ScrollTrigger.min.js'),
        path: get_theme_file_path('/assets/js/ScrollTrigger.min.js'),
        dependencies: ['skeleton-wp-gsap'],
        args: ['strategy' => 'defer', 'in_footer' => true],
        enqueue: false
      ),
      new Asset(
        handle: 'skeleton-wp-SplitText',
        url: get_theme_file_uri('/assets/js/SplitText.min.js'),
        path: get_theme_file_path('/assets/js/gsap-client.min.js'),
        dependencies: ['skeleton-wp-gsap'],
        args: ['strategy' => 'defer', 'in_footer' => true],
        enqueue: false
      ),
      new Asset(
        handle: 'skeleton-wp-gsap-client',
        url: get_theme_file_uri('/assets/js/gsap-client.min.js'),
        path: get_theme_file_path('/assets/js/gsap-client.min.js'),
        dependencies: [
          'skeleton-wp-gsap',
          'skeleton-wp-ScrollTrigger',
          'skeleton-wp-SplitText'
        ],
        args: ['strategy' => 'defer', 'in_footer' => true],
        enqueue: true
      )
    ];
  }

  /**
   * Define all frontend styles to enqueue.
   *
   * @return Asset[]
   */
  private function styles(): array
  {
    return [
      new Asset(
        handle: 'skeleton-wp-swiper',
        url: get_theme_file_uri('/assets/css/swiper.css'),
        path: get_theme_file_path('/assets/css/swiper.css'),
        enqueue: false
      ),
    ];
  }


  /**
   * Enqueue scripts and styles
   *
   * @return void
   */
  public function action_enqueue(): void
  {
    foreach ($this->scripts() as $asset) {
      $action = $asset->enqueue ? 'wp_enqueue_script' : 'wp_register_script';

      $action(
        $asset->handle,
        $asset->url,
        $asset->dependencies ?? [],
        skeleton_wp()->get_asset_version($asset->path),
        $asset->args ?? []
      );
    }

    foreach ($this->styles() as $asset) {
      $action = $asset->enqueue ? 'wp_enqueue_style' : 'wp_register_style';

      $action(
        $asset->handle,
        $asset->url,
        $asset->dependencies ?? [],
        skeleton_wp()->get_asset_version($asset->path)
      );
    }
  }

}
