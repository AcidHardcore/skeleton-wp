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

	private string $googleAPIkey = 'AIzaSyDezR6niyVzkb9X5PSpM_f2os20kyV3iHE';
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
		/**
		 * MANDATORY
		 * https://wordpress.stackexchange.com/questions/391862/how-to-support-lazy-loading-assets-in-a-wordpress-theme
		 */
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );
	}


	public function action_enqueue() {

		// If the AMP plugin is active, return early.
		if ( skeleton_wp()->is_amp() ) {
			return;
		}

		wp_enqueue_script(
			'skeleton-wp-jquery-once',
			get_theme_file_uri( '/assets/js/jquery.once.js' ),
			array('jquery'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/jquery.once.js' ) ),
			true
		);

		wp_enqueue_script(
			'skeleton-wp-js-cookie',
			get_theme_file_uri( '/assets/js/js.cookie.min.js' ),
			array('jquery'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/js.cookie.min.js' ) ),
			true
		);

//		wp_enqueue_script(
//			'skeleton-wp-jquery-mask',
//			get_theme_file_uri( '/assets/js/jquery.mask.js' ),
//			array('jquery'),
//			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/jquery.mask.js' ) ),
//			true
//		);

		wp_enqueue_script(
			'skeleton-wp-jquery-mobile',
			get_theme_file_uri( '/assets/js/jquery.mobile.min.js' ),
			array('jquery'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/jquery.mobile.min.js' ) ),
			true
		);

//		wp_enqueue_script(
//			'skeleton-wp-chosen-jquery',
//			get_theme_file_uri( '/assets/js/chosen.jquery.js' ),
//			array('jquery'),
//			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/chosen.jquery.js' ) ),
//			true
//		);

		wp_enqueue_script(
			'skeleton-wp-wow',
			get_theme_file_uri( '/assets/js/wow.js' ),
			array(),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/wow.js' ) ),
			true
		);

		wp_register_script(
			'skeleton-wp-slick',
			get_theme_file_uri( '/assets/js/slick.js' ),
			array(),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/slick.js' ) ),
			true
		);

		wp_register_script(
			'skeleton-wp-wistia',
			'//fast.wistia.com/assets/external/E-v1.js',
			array(),
			'1.0',
			true
		);

		wp_register_script(
			'skeleton-wp-google-api-map',
			'https://maps.googleapis.com/maps/api/js?language=en&key=' . $this->googleAPIkey,
			array(),
			'1.0',
			true
		);

		wp_register_script(
			'block-script-wistia',
			get_theme_file_uri( '/assets/js/wistia.js' ),
			array('jquery', 'skeleton-wp-wistia'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/wistia.js' ) ),
			true
		);

		if(is_singular('post')) {
			wp_enqueue_script('block-script-wistia');
		}

		wp_register_script(
			'block-script-industries',
			get_theme_file_uri( '/assets/js/industries.js' ),
			array('jquery', 'skeleton-wp-slick'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/industries.js' ) ),
			true
		);

		wp_enqueue_script(
			'skeleton-wp-common',
			get_theme_file_uri( '/assets/js/common.js' ),
			array('jquery', 'skeleton-wp-wow'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/common.js' ) ),
			true
		);

		wp_register_script(
			'skeleton-wp-collapser',
			get_theme_file_uri( '/assets/js/collapser.js' ),
			array('jquery'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/collapser.js' ) ),
			true
		);

		wp_register_script(
			'skeleton-wp-scroll-magic',
			get_theme_file_uri( '/assets/js/ScrollMagic.js' ),
			array('jquery'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/ScrollMagic.js' ) ),
			true
		);

		wp_register_script(
			'skeleton-wp-load-more',
			get_theme_file_uri( '/assets/js/load-more.js' ),
			array('jquery', 'skeleton-wp-scroll-magic'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/load-more.js' ) ),
			true
		);

		wp_register_script(
			'skeleton-wp-tabs',
			get_theme_file_uri( '/assets/js/tabs.js' ),
			array('jquery'),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/tabs.js' ) ),
			true
		);

	}

}
