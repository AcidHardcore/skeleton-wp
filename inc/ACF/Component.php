<?php
/**
 * WP_Rig\WP_Rig\ACF\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\ACF;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;
use function add_filter;
use function acf_register_block_type;


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
		add_filter( 'block_categories', array( $this, 'action_acf_block_category', 10, 2 ) );
		add_action( 'acf/init', array( $this, 'action_register_acf_block_types' ) );
		add_action( 'acf/init', array( $this, 'action_register_acf_option_page' ) );
	}


	/**
	 * Add Block categories
	 *
	 * @param array $categories The Categories array.
	 * @param WP_Post $post The current post item.
	 * * @return array Multidimensional associative array of ACF Gutenberg block categories
	 */
	public function action_acf_block_category( $categories, $post ) {
		return array_merge( $categories, array(
				array(
					'slug'  => 'common',
					'title' => __( 'Common Blocks', 'common' ),
				),
			) );
	}

	/**
	 * Register ACF blocks
	 **/
	public function action_register_acf_block_types() {

		acf_register_block_type( array(
			'name'            => 'block',
			'title'           => __( 'Block' ),
			'description'     => __( 'Block description' ),
			'render_template' => 'template-parts/blocks/block.php',
			'category'        => 'common',
			'icon'            => 'editor-ul',
			'align'           => 'full',
			'keywords'        => array( 'news' ),
			//'mode' => 'edit',
			'enqueue_assets'  => function () {
				$the_theme         = wp_get_theme();
				$theme_version     = $the_theme->get( 'Version' );

				$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/assets/css/block.min.css' );
				wp_enqueue_style( 'block', get_template_directory_uri() . '/assets/css/block.min.css', array(), $css_version );

				$load_more_version = $theme_version . '.' . filemtime( get_template_directory() . '/assets/js/block.min.js' );
				wp_enqueue_script( 'block', get_template_directory_uri() . '/assets/js/block.min.js', array('jquery'), $load_more_version, true );

				wp_localize_script( 'block', 'jsData', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				) );
			},
		) );
	}

	/**
	 * Add Global ACF Options page
	 */
	public function action_register_acf_option_page() {

		acf_add_options_page( array(
			'page_title' => 'Global Settings',
			'menu_title' => 'Global Settings',
			'menu_slug'  => 'global-settings',
			'capability' => 'edit_posts',
			'redirect'   => false
		) );

		acf_add_options_sub_page( array(
			'page_title'  => 'Theme Header Settings',
			'menu_title'  => 'Header',
			'parent_slug' => 'global-settings',
		) );

		acf_add_options_sub_page( array(
			'page_title'  => 'Theme Footer Settings',
			'menu_title'  => 'Footer',
			'parent_slug' => 'global-settings',
		) );
	}

}
