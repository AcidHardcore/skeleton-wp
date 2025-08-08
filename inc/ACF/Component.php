<?php
/**
 * Skeleton_WP\Skeleton_WP\ACF\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\ACF;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use Skeleton_WP\Skeleton_WP\Templating_Component_Interface;
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
class Component implements Component_Interface, Templating_Component_Interface {

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
		add_action('acf/init', array($this, 'action_load_blocks'), 5);
		add_action('acf/init', array($this, 'action_register_acf_option_page'));
    //    add ID to the block
    add_filter( 'acf/pre_save_block',
      function( $attributes ) {
        if ( empty( $attributes['id'] ) ) {
          $attributes['id'] = 'block_acf-block-' . uniqid();
        }
        return $attributes;
      }
    );
	}

  /**
   * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `skeleton_wp()`.
   *
   * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
   *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
   *               adding support for further arguments in the future.
   */
  public function template_tags(): array {
    return array(
      'get_first_block_id' => array( $this, 'get_first_block_id' ),
    );
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
	 * Load Blocks
	 */
	function action_load_blocks() {
		$blocks = $this->get_blocks();

		if(!empty($blocks)) {
			foreach ($blocks as $block) {
				if (file_exists($this->set_file_path($block, 'block.json'))) {
					register_block_type($this->set_file_path($block, 'block.json'));

					if (file_exists($this->set_file_path($block, 'style.css'))) {
						$css_version = skeleton_wp()->get_asset_version($this->set_file_path($block, 'style.min.css'));
						file_exists($this->set_file_path($block, 'style.asset.php'))
							? $deps = require_once $this->set_file_path($block, 'style.asset.php')
							: $deps['dependencies'] = array();
						wp_register_style(
							'block-' . $block,
							$this->set_file_uri($block, 'style.min.css'),
							$deps['dependencies'],
							$css_version
						);
					}

          if (file_exists($this->set_file_path($block, 'editor.css'))) {
            $css_version = skeleton_wp()->get_asset_version($this->set_file_path($block, 'editor.min.css'));
            file_exists($this->set_file_path($block, 'editor.asset.php'))
              ? $deps = require_once $this->set_file_path($block, 'editor.asset.php')
              : $deps['dependencies'] = array();
            wp_register_style(
              'editor-' . $block,
              $this->set_file_uri($block, 'editor.min.css'),
              $deps['dependencies'],
              $css_version
            );
          }

					if (file_exists($this->set_file_path($block, 'script.min.js'))) {
						$js_version = skeleton_wp()->get_asset_version($this->set_file_path($block, 'script.min.js'));
						file_exists($this->set_file_path($block, 'script.asset.php'))
							? $deps = require_once $this->set_file_path($block, 'script.asset.php')
							: $deps['dependencies'] = array();

						wp_register_script(
							'block-' . $block,
							$this->set_file_uri($block, 'script.min.js'),
							$deps['dependencies'],
							$js_version,
							true
						);
					}

					if (file_exists($this->set_file_path($block, 'init.php'))) {
						require_once $this->set_file_path($block, 'init.php');
					}
				}
			}
		}
	}

	/**
	 * Get Blocks
	 */
	function get_blocks() {
		$blocks  = get_option( 'skeleton-wp_blocks' );
		$version = get_option( 'skeleton-wp_blocks_version' );
		if ( empty( $blocks ) || version_compare( skeleton_wp()->get_asset_version($this->set_file_path()), $version )) {
			$blocks = scandir( $this->set_file_path() );
			$blocks = array_values( array_diff( $blocks, array( '..', '.', '.DS_Store', 'block' ) ) );

			update_option( 'skeleton-wp_blocks', $blocks );
			update_option( 'skeleton-wp_blocks_version', skeleton_wp()->get_asset_version($this->set_file_path()) );
		}
		return $blocks;
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
      'redirect' => false,
      'post_id' => 'option'
    ));

    acf_add_options_sub_page(array(
      'page_title' => 'Theme Header Settings',
      'menu_title' => 'Header',
      'parent_slug' => 'global-settings',
      'post_id' => 'header'
    ));

    acf_add_options_sub_page(array(
      'page_title' => 'Theme Footer Settings',
      'menu_title' => 'Footer',
      'parent_slug' => 'global-settings',
      'post_id' => 'footer'
    ));
  }


	protected function set_file_path(string $block_name = '', string $file_name = ''): string
	{
		if(!empty($file_name)) {
			$file_name = '/' . $file_name;
		}
		return get_theme_file_path('blocks/' . $block_name . $file_name);

	}

	protected function set_file_uri(string $block_name, string $file_name): string
	{
		return get_theme_file_uri('blocks/' . $block_name . '/' . $file_name);

	}

  /**
   * Get ID of the first ACF block on the page
   */
  public function get_first_block_id() {
    $post = get_post();
    if(has_blocks($post->post_content)) {
      $blocks = parse_blocks($post->post_content);
      $first_block_attrs = $blocks[0]['attrs'];
//      error_log(print_r($blocks[0], true));
      if(array_key_exists('id', $first_block_attrs)) {
        return $first_block_attrs['id'];
      }
    }
    return null;
  }
}
