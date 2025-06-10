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


class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'enqueue';
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue' ) );
//    add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue' ) );
    add_action( 'enqueue_block_editor_assets', array( $this, 'action_enqueue' ) );
	}


	public function action_enqueue() {

//		wp_enqueue_script(
//			'skeleton-wp-wow',
//			get_theme_file_uri( '/assets/js/wow.js' ),
//			array(),
//			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/wow.js' ) ),
//			array(
//        'strategy'  => 'defer',
//        'in_footer' => true
//      )
//		);

		wp_register_style(
			'skeleton-wp-swiper',
			get_theme_file_uri( '/assets/css/swiper.css' ),
			array(),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/css/swiper.css' ) ),
		);

		wp_register_script(
			'skeleton-wp-swiper',
			get_theme_file_uri( '/assets/js/swiper-bundle.js' ),
			array(),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/swiper-bundle.js' ) ),
      array(
        'strategy'  => 'defer',
        'in_footer' => true
      )
		);

    wp_register_script(
      'skeleton-wp-slick',
      get_theme_file_uri( '/assets/js/slick.min.js' ),
      array(),
      skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/slick.min.js' ) ),
      array(
        'strategy'  => 'defer',
        'in_footer' => true
      )
    );

    wp_register_script(
      'skeleton-wp-gsap',
      get_theme_file_uri( '/assets/js/gsap.min.js' ),
      array(),
      skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/gsap.min.js' ) ),
      array(
        'strategy'  => 'defer',
        'in_footer' => true
      )
    );

    wp_register_script(
      'skeleton-wp-ScrollTrigger',
      get_theme_file_uri( '/assets/js/ScrollTrigger.min.js' ),
      array('skeleton-wp-gsap'),
      skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/ScrollTrigger.min.js' ) ),
      array(
        'strategy'  => 'defer',
        'in_footer' => true
      )
    );

    wp_register_script(
      'skeleton-wp-SplitText',
      get_theme_file_uri( '/assets/js/SplitText.min.js' ),
      array('skeleton-wp-gsap'),
      skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/SplitText.min.js' ) ),
      array(
        'strategy'  => 'defer',
        'in_footer' => true
      )
    );

    wp_enqueue_script(
      'skeleton-wp-gsap-client',
      get_theme_file_uri( '/assets/js/gsap-client.min.js' ),
      array('skeleton-wp-gsap', 'skeleton-wp-ScrollTrigger', 'skeleton-wp-SplitText'),
      skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/gsap-client.min.js' ) ),
      array(
        'strategy'  => 'defer',
        'in_footer' => true
      )
    );

	}

}
