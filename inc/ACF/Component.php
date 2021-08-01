<?php
/**
 * Skeleton_WP\Skeleton_WP\ACF\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\ACF;

use Skeleton_WP\Skeleton_WP\Component_Interface;
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
		add_filter('block_categories_all', array($this, 'action_acf_block_category', 10, 2));
		add_action('acf/init', array($this, 'action_register_acf_block_types'));
		add_action('acf/init', array($this, 'action_register_acf_option_page'));
		add_action('wp_head', array($this, 'above_head'));
		add_action('wp_body_open', array($this, 'after_body'));
		add_action('wp_footer', array($this, 'above_body'));
	}


	/**
	 * Add Block categories
	 *
	 * @param array $categories The Categories array.
	 * @param WP_Post $post The current post item.
	 * * @return array Multidimensional associative array of ACF Gutenberg block categories
	 */
	public function action_acf_block_category($categories, $post) {
		return array_merge($categories, array(
			array(
				'slug' => 'skeleton-wp',
				'title' => __('Skeleton-WP Blocks', 'skeleton-wp'),
			),
		));
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
			'category' => 'common',
			'icon' => 'editor-ul',
			'align' => 'full',
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
			'category' => 'common',
			'icon' => 'editor-ul',
			'align' => 'full',
			'keywords' => array('common', 'posts'),
			'mode' => 'edit',
			'enqueue_assets' => function() {
				$js_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/js/load-more.js');
				wp_enqueue_script('load-more-scripts', get_template_directory_uri() . '/assets/js/load-more.js', array('jquery'), $js_version, true);
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

	/**
	 * Add a custom code above the head close tag
	 */
	public function above_head() {
		$above_head = get_field('above_head', 'option');

		if($above_head) {
			echo $above_head;
		}
	}

	/**
	 * Add a custom code after the body open tag
	 */
	public function after_body() {
		$after_body = get_field('after_body', 'option');

		if($after_body) {
			echo $after_body;
		}
	}

	/**
	 * Add a custom code before the body close tag
	 */
	public function above_body() {
		$above_body = get_field('above_body', 'option');

		if($above_body) {
			echo $above_body;
		}
	}


}
