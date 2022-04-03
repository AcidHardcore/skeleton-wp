<?php
/**
 * Skeleton_WP\Skeleton_WP\ACF\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\ACF;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use WP_Block_Editor_Context;
use function add_action;
use function add_filter;
use function acf_register_block_type;
use function array_merge;
use function Skeleton_WP\Skeleton_WP\skeleton_wp;
use function wp_enqueue_style;
use function wp_enqueue_script;
use function wp_localize_script;
use function get_template_directory;
use function get_template_directory_uri;
use function acf_add_options_page;
use function acf_add_options_sub_page;
use function admin_url;

/**
 * Class for managing ACF.
 *
 *
 * @link https://www.advancedcustomfields.com/resources/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'acf';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter('block_categories_all', array($this, 'action_block_category'), 10, 2);
		add_action('acf/init', array($this, 'action_register_acf_block_types'));
		add_action('acf/init', array($this, 'action_register_acf_option_page'));
	}


	/**
	 * Add Block categories
	 *
	 * @param array $block_categories The Categories array.
	 * @param WP_Block_Editor_Context $editor_context The current block editor context.
	 * * @return array Multidimensional associative array of Gutenberg block categories
	 */
	public function action_block_category($block_categories, $editor_context) {
		if(!empty($editor_context->post)) {
			array_push(
				$block_categories,
				array(
					'slug' => 'skeleton-wp',
					'title' => __('Skeleton WP', 'skeleton_wp'),
					'icon' => null,
				)
			);
		}
		return $block_categories;
	}

	/**
	 * Register ACF blocks
	 **/
	public function action_register_acf_block_types() {

		acf_register_block_type(array(
			'name' => 'block',
			'title' => __('Block'),
			'description' => __('Block description'),
			'render_template' => 'template-parts/blocks/block.php',
			'category' => 'skeleton-wp',
			'icon' => 'wordpress',
			'align'			=> 'full',
			'supports'		=> [
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> true,
			],
			'keywords' => array('news'),
			//'mode' => 'edit',
			'enqueue_assets' => function() {

				$css_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/css/block.min.css');
				wp_enqueue_style('block', get_template_directory_uri() . '/assets/css/block.min.css', array(), $css_version);

				$js_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/js/block.min.js');
				wp_enqueue_script('block', get_template_directory_uri() . '/assets/js/block.min.js', array('jquery'), $js_version, true);

				wp_localize_script('block', 'jsData', array(
					'ajaxUrl' => admin_url('admin-ajax.php'),
				));
			},
		));

		acf_register_block_type(array(
			'name' => 'posts',
			'title' => __('Posts'),
			'description' => __('Posts with Load More button or AJAX pagination'),
			'render_template' => 'template-parts/blocks/posts.php',
			'category' => 'skeleton-wp',
			'icon' => 'wordpress',
			'align' => 'full',
			'keywords' => array('common', 'posts'),
			'mode' => 'edit',
			'enqueue_assets' => function() {
				$js_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/js/load-more.js');
				wp_enqueue_script('load-more-scripts', get_template_directory_uri() . '/assets/js/load-more.js', array('jquery'), $js_version, true);
			},
		));

		acf_register_block_type(array(
			'name' => 'slider',
			'title' => __('Slider'),
			'description' => __('Slider hero block'),
			'render_template' => 'template-parts/blocks/slider.php',
			'category' => 'skeleton-wp',
			'icon' => 'wordpress',
			'align' => 'full',
			'keywords' => array('common', 'slider'),
			'mode' => 'edit',
			'supports'		=> [
				'anchor'		=> true,
				'customClassName'	=> true,
				'jsx' 			=> true,
			],
			'enqueue_assets' => function() {
				$css_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/css/swiper.css');
				wp_enqueue_style('swiper-styles', get_template_directory_uri() . '/assets/css/swiper.css', array(), $css_version);

				$css_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/css/block-slider.css');
				wp_enqueue_style('block-slider-styles', get_template_directory_uri() . '/assets/css/block-slider.css', array(), $css_version);

				$js_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/js/swiper-bundle.js');
				wp_enqueue_script('swiper-scripts', get_template_directory_uri() . '/assets/js/swiper-bundle.js', array(), $js_version, true);

				$js_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/js/slider.js');
				wp_enqueue_script('slider-scripts', get_template_directory_uri() . '/assets/js/slider.js', array('jquery'), $js_version, true);
			},
		));
	}

	/**
	 * Add Global ACF Options page
	 */
	public function action_register_acf_option_page() {

		acf_add_options_page(array(
			'page_title' => 'Global Settings',
			'menu_title' => 'Global Settings',
			'menu_slug' => 'global-settings',
			'capability' => 'edit_posts',
			'redirect' => false
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Theme Header Settings',
			'menu_title' => 'Header',
			'parent_slug' => 'global-settings',
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Theme Footer Settings',
			'menu_title' => 'Footer',
			'parent_slug' => 'global-settings',
		));
	}
}
