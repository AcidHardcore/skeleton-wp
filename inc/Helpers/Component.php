<?php
/**
 * Skeleton_WP\Skeleton_WP\Helpers\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Helpers;

use WP_Query;
use Skeleton_WP\Skeleton_WP\Component_Interface;
use Skeleton_WP\Skeleton_WP\Templating_Component_Interface;
use function add_action;
use function add_filter;


/**
 * Class for adding basic theme support, most of which is mandatory to be implemented by all themes.
 *
 * Exposes template tags:
 * * `skeleton_wp()->get_version()`
 * * `skeleton_wp()->get_asset_version( string $filepath )`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'helpers';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {

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
			'get_version'            => array( $this, 'get_version' ),
			'get_asset_version'      => array( $this, 'get_asset_version' ),
			'inline_svg'             => array( $this, 'inline_svg' ),
			'youtube_id'             => array( $this, 'youtube_id' ),
			'youtube_short_id'       => array( $this, 'youtube_short_id' ),
			'get_adjacent_post_link' => array( $this, 'get_adjacent_post_link' ),
			'calendly_check'         => array( $this, 'calendly_check' ),
			'mytheme_color_is_dark'         => array( $this, 'mytheme_color_is_dark' ),
		);
	}

	/**
	 * Gets the theme version.
	 *
	 * @return string Theme version number.
	 */
	public function get_version(): string {
		static $theme_version = null;

		if ( null === $theme_version ) {
			$theme_version = wp_get_theme( get_template() )->get( 'Version' );
		}

		return $theme_version;
	}

	/**
	 * Gets the version for a given asset.
	 *
	 * Returns filemtime when WP_DEBUG is true, otherwise the theme version.
	 *
	 * @param string $filepath Asset file path.
	 *
	 * @return string Asset version number.
	 */
	public function get_asset_version( string $filepath ): string {
			return (string) filemtime( $filepath );
	}

	/**
	 * Convert image id to an image content
	 *
	 * @param $id
	 *
	 * @return false|string
	 */
	public function inline_svg( $id, $comment=false ) {
		if ( $id ) {
			static $i = 0;
			$i ++;
			$path = wp_get_original_image_path( $id );
			$svg  = file_get_contents( $path );
			$svg  = preg_replace( '#^.*<svg#si', '<svg', $svg );
			if ( preg_match_all( '#id="([^"]+)"#si', $svg, $m ) ) {
				foreach ( $m[1] as $id ) {
					$id_new = $id . '--' . $i;
					$svg    = str_replace( $id, $id_new, $svg );
				}
			}

			if($comment) {
				$svg = '<!-- inline SVG ' . $path . ' -->' . PHP_EOL . $svg;
			}

			return $svg;
		} else {
			return false;
		}
	}

	/**
	 * @param string $post_type
	 * @param bool $previous
	 *
	 * @return array
	 */

	public function get_adjacent_post_link( $post_type, $previous = true ) {
		$post = get_adjacent_post( false, '', $previous, 'category' );

		$first_post    = new WP_Query(
			[
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => 1,
				'fields'         => 'ids'
			]
		);
		$first_post_id = $first_post->posts[0];

		$last_post    = new WP_Query(
			[
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'ASC',
				'posts_per_page' => 1,
				'fields'         => 'ids'
			]
		);
		$last_post_id = $last_post->posts[0];

		if ( $post ) {

			$output['link'] = get_permalink( $post );
			$output['title'] = get_the_title( $post );

		} else {
			if ( $previous ) {
				$output['link'] = get_permalink( $first_post_id );
				$output['title'] = get_the_title( $first_post_id );

			} else {
				$output['link'] = get_permalink( $last_post_id );
				$output['title'] = get_the_title( $last_post_id );
			}

		}

		return $output;
	}

	/**
	 * Get ID from Youtube URL
	 *
	 * @param $url
	 *
	 * @return false|mixed
	 */
	public function youtube_id( $url ) {
		$id = false;
		if ( preg_match( '#^https?://(www.)?youtube.com/watch\?v=([a-z0-9_-]+)#si', $url, $m ) ) {
			$id = $m[2];
		}
		if ( preg_match( '#^https?://(www.)?youtu\.be/([a-z0-9_-]+)#si', $url, $m ) ) {
			$id = $m[2];
		}

		return $id;
	}

	/**
	 * Get ID from Youtube Shorts URL
	 *
	 * @param $url
	 *
	 * @return false|mixed
	 */
	public function youtube_short_id( $url ) {
		$id = false;
		if ( preg_match( '#^https?://(www.)?youtube.com/shorts/([a-z0-9_-]+)#si', $url, $m ) ) {
			$id = $m[2];
		}

		return $id;
	}

	/**
	 * @param $link_url
	 *
	 * @return mixed
	 */
	public function calendly_check( $link_url ) {
		if ( ( stristr( $link_url, '%%calendly%%' ) === '%%calendly%%' ) ) {

			$calendly_url = get_field( 'calendly_url', 'option' );

			if ( $calendly_url ) {
				$link_url = $calendly_url;
			}
		}

		return $link_url;
	}

	/**
	 * @param $color
	 *
	 * @return bool
	 */
	public function mytheme_color_is_dark( $color ): bool {
		return in_array( $color, [
			'charcoal',
			'black',
			'gold',
			'red',
			'green',
			'blue',
		] );
	}




}
