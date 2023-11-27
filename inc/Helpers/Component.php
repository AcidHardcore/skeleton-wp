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
			'mytheme_color_is_dark'  => array( $this, 'mytheme_color_is_dark' ),
			'paginate_links_data'    => array( $this, 'paginate_links_data' ),
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

  /**
   * Generates array of pagination links.
   *
   * @author Kama (wp-kama.com)
   * @varsion 2.5
   *
   * @param array $args {
   *
   *     @type int    $total        Maximum allowable pagination page.
   *     @type int    $current      Current page number.
   *     @type string $url_base     URL pattern. Use `{pagenum}` placeholder.
   *     @type string $first_url    URL to first page. Default: '' - taken automaticcaly from $url_base.
   *     @type int    $mid_size     Number of links before/after current: 1 ... 1 2 [3] 4 5 ... 99. Default: 2.
   *     @type int    $end_size     Number of links at the edges: 1 2 ... 3 4 [5] 6 7 ... 98 99. Default: 1.
   *     @type bool   $show_all     true - Show all links. Default: false.
   *     @type string $a_text_patt  `%s` will be replaced with number of pagination page. Default: `'%s'`.
   *     @type bool   $is_prev_next Whether to show prev/next links. « Previou 1 2 [3] 4 ... 99 Next ». Default: false.
   *     @type string $prev_text    Default: `« Previous`.
   *     @type string $next_text    Default: `Next »`.
   * }
   *
   * @return array
   */
  public function paginate_links_data( array $args ): array {
    global $wp_query;

    $args += [
      'total'        => 1,
      'current'      => 0,
      'url_base'     => '/{pagenum}',
      'first_url'    => '',
      'mid_size'     => 2,
      'end_size'     => 1,
      'show_all'     => false,
      'a_text_patt'  => '%s',
      'is_prev_next' => false,
      'prev_text'    => '« Previous',
      'next_text'    => 'Next »',
    ];

    $rg = (object) $args;

    $total_pages = max( 1, (int) ( $rg->total ?: $wp_query->max_num_pages ) );

    if( $total_pages === 1 ){
      return [];
    }

    // fix working parameters

    $rg->total = $total_pages;
    $rg->current = max( 1, abs( $rg->current ?: get_query_var( 'paged', 1 ) ) );

    $rg->url_base = $rg->url_base ?: str_replace( PHP_INT_MAX, '{pagenum}', get_pagenum_link( PHP_INT_MAX ) );
    $rg->url_base = wp_normalize_path( $rg->url_base );

    if( ! $rg->first_url ){
      // /foo/page(d)/2 >>> /foo/ /foo?page(d)=2 >>> /foo/
      $rg->first_url = preg_replace( '~/paged?/{pagenum}/?|[?]paged?={pagenum}|/{pagenum}/?~', '', $rg->url_base );
      $rg->first_url = user_trailingslashit( $rg->first_url );
    }

    // core array

    if( $rg->show_all ){
      $active_nums = range( 1, $rg->total );
    }
    else {

      if( $rg->end_size > 1 ){
        $start_nums = range( 1, $rg->end_size );
        $end_nums = range( $rg->total - ($rg->end_size - 1), $rg->total );
      }
      else {
        $start_nums = [ 1 ];
        $end_nums = [ $rg->total ];
      }

      $from = $rg->current - $rg->mid_size;
      $to = $rg->current + $rg->mid_size;

      if( $from < 1 ){
        $to = min( $rg->total, $to + absint( $from ) );
        $from = 1;

      }
      if( $to > $rg->total ){
        $from = max( 1, $from - ($to - $rg->total) );
        $to = $rg->total;
      }

      $active_nums = array_merge( $start_nums, range( $from, $to ), $end_nums );
      $active_nums = array_unique( $active_nums );
      $active_nums = array_values( $active_nums ); // reset keys
    }

    // fill by core array

    $pages = [];

    if( 1 === count( $active_nums ) ){
      return $pages;
    }

    $item_data = static function( $num ) use ( $rg ){

      $data = [
        'is_current'   => false,
        'page_num'     => null,
        'url'          => null,
        'link_text'    => null,
        'is_prev_next' => false,
        'is_dots'      => false,
      ];

      if( 'dots' === $num ){

        return (object) ( [
            'is_dots' => true,
            'link_text' => '…',
          ] + $data );
      }

      $is_prev = 'prev' === $num && ( $num = max( 1, $rg->current - 1 ) );
      $is_next = 'next' === $num && ( $num = min( $rg->total, $rg->current + 1 ) );

      $data = [
          'is_current'   => ! ( $is_prev || $is_next ) && $num === $rg->current,
          'page_num'     => $num,
          'url'          => 1 === $num ? $rg->first_url : str_replace( '{pagenum}', $num, $rg->url_base ),
          'is_prev_next' => $is_prev || $is_next,
        ] + $data;

      if( $is_prev ){
        $data['link_text'] = $rg->prev_text;
      }
      elseif( $is_next ) {
        $data['link_text'] = $rg->next_text;
      }
      else {
        $data['link_text'] = sprintf( $rg->a_text_patt, $num );
      }

      return (object) $data;
    };

    foreach( $active_nums as $indx => $num ){

      $pages[] = $item_data( $num );

      // set dots
      $next = $active_nums[ $indx + 1 ] ?? null;
      if( $next && ($num + 1) !== $next ){
        $pages[] = $item_data( 'dots' );
      }
    }

    if( $rg->is_prev_next ){
      $rg->current !== 1 && array_unshift( $pages, $item_data( 'prev' ) );
      $rg->current !== $rg->total && $pages[] = $item_data( 'next' );
    }

    return $pages;
  }

}
