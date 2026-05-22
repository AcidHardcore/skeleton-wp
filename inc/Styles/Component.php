<?php
/**
 * Skeleton_WP\Skeleton_WP\Styles\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Styles;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use Skeleton_WP\Skeleton_WP\Templating_Component_Interface;
use function Skeleton_WP\Skeleton_WP\skeleton_wp;
use function add_action;
use function add_filter;
use function wp_enqueue_style;
use function wp_register_style;
use function wp_style_add_data;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_styles;
use function esc_attr;
use function esc_url;
use function add_editor_style;
use function wp_style_is;
use function _doing_it_wrong;
use function esc_html;
use function wp_print_styles;
use function post_password_required;
use function is_singular;
use function comments_open;
use function get_comments_number;
use function apply_filters;
use function add_query_arg;

class Component implements Component_Interface, Templating_Component_Interface {

  protected array $css_files = [];
  protected array $google_fonts = [];

  public function get_slug() : string {
    return 'styles';
  }

  public function initialize(): void
  {
    add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ) );
    add_action( 'wp_head', array( $this, 'action_preload_styles' ) );
    // add_action( 'after_setup_theme', array( $this, 'action_add_editor_styles' ) );
    add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_scripts' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'gutenberg_scripts' ) );
    add_filter( 'wp_resource_hints', array( $this, 'filter_resource_hints' ), 10, 2 );
  }

  public function template_tags() : array {
    return array(
      'print_styles' => array( $this, 'print_styles' ),
    );
  }

  public function action_enqueue_styles(): void
  {
    $google_fonts_url = $this->get_google_fonts_url();
    if ( ! empty( $google_fonts_url ) ) {
      wp_enqueue_style( 'skeleton-wp-fonts', $google_fonts_url, array(), null );
    }

    $css_uri = get_theme_file_uri( '/assets/css/' );
    $css_dir = get_theme_file_path( '/assets/css/' );

    $preloading_styles_enabled = $this->preloading_styles_enabled();
    $css_files = $this->get_css_files();

    foreach ( $css_files as $handle => $data ) {
      $src     = $css_uri . $data['file'];
      $version = skeleton_wp()->get_asset_version( $css_dir . $data['file'] );

      if ( $data['global'] || ( ! $preloading_styles_enabled && is_callable( $data['preload_callback'] ) && call_user_func( $data['preload_callback'] ) ) ) {
        wp_enqueue_style( $handle, $src, array(), $version, $data['media'] );
      } else {
        wp_register_style( $handle, $src, array(), $version, $data['media'] );
      }

      wp_style_add_data( $handle, 'precache', true );
    }
  }

  public function action_preload_styles(): void
  {
    if ( ! $this->preloading_styles_enabled() ) {
      return;
    }

    $wp_styles = wp_styles();
    $css_files = $this->get_css_files();

    foreach ( $css_files as $handle => $data ) {
      if ( ! isset( $wp_styles->registered[ $handle ] ) || ! is_callable( $data['preload_callback'] ) ) {
        continue;
      }

      if ( ! call_user_func( $data['preload_callback'] ) ) {
        continue;
      }

      $preload_uri = $wp_styles->registered[ $handle ]->src . '?ver=' . $wp_styles->registered[ $handle ]->ver;

      echo '<link rel="preload" id="' . esc_attr( $handle ) . '-preload" href="' . esc_url( $preload_uri ) . '" as="style">' . "\n";
    }
  }

  public function action_add_editor_styles(): void
  {
    $google_fonts_url = $this->get_google_fonts_url();
    if ( ! empty( $google_fonts_url ) ) {
      add_editor_style( $this->get_google_fonts_url() );
    }
    add_editor_style( 'assets/css/editor/editor-styles.min.css' );
  }

  public function gutenberg_scripts(): void
  {
    $js_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/js/editor.min.js');
    wp_enqueue_script(
      'skeleton-wp-editor',
      get_stylesheet_directory_uri() . '/assets/js/editor.js',
      array( 'wp-blocks', 'wp-dom' ),
      $js_version,
      true
    );

    $css_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/css/global.min.css');
    wp_enqueue_style( 'skeleton-wp-admin-global',
      get_stylesheet_directory_uri() . '/assets/css/admin-global.min.css' ,
      array(),
      $css_version
    );

    $css_version = skeleton_wp()->get_asset_version(get_template_directory() . '/assets/css/content.min.css');
    wp_enqueue_style( 'skeleton-wp-admin-content',
      get_stylesheet_directory_uri() . '/assets/css/admin-content.min.css' ,
      array(),
      $css_version
    );
  }

  public function filter_resource_hints( array $urls, string $relation_type ) : array {
    if ( 'preconnect' === $relation_type && wp_style_is( 'skeleton-wp-fonts', 'queue' ) ) {
      $urls[] = array(
        'href' => 'https://fonts.gstatic.com',
        'crossorigin',
      );
      $urls[] = array(
        'href' => 'https://fonts.googleapis.com',
        'crossorigin',
      );
    }

    return $urls;
  }

  public function print_styles( string ...$handles ): void
  {
    if ( ! $this->preloading_styles_enabled() ) {
      return;
    }

    $css_files = $this->get_css_files();
    $handles   = array_filter(
      $handles,
      function( $handle ) use ( $css_files ) {
        $is_valid = isset( $css_files[ $handle ] ) && ! $css_files[ $handle ]['global'];
        if ( ! $is_valid ) {
          _doing_it_wrong( __CLASS__ . '::print_styles()', esc_html( sprintf( __( 'Invalid theme stylesheet handle: %s', 'skeleton-wp' ), $handle ) ), 'WP Rig 2.0.0' );
        }
        return $is_valid;
      }
    );

    if ( empty( $handles ) ) {
      return;
    }

    wp_print_styles( $handles );
  }

  protected function preloading_styles_enabled(): bool
  {
    return apply_filters( 'skeleton_wp_preloading_styles_enabled', true );
  }

  protected function get_css_files() : array {
    if ( ! empty( $this->css_files ) ) {
      return $this->css_files;
    }

    $css_files = array(
      'skeleton-wp-global'     => array(
        'file'   => 'global.min.css',
        'global' => true,
      ),
      'skeleton-wp-comments'   => array(
        'file'             => 'comments.min.css',
        'preload_callback' => function() {
          return ! post_password_required() && is_singular() && ( comments_open() || get_comments_number() );
        },
      ),
      'skeleton-wp-content'    => array(
        'file'             => 'content.min.css',
        'preload_callback' => '__return_true',
      ),
      'skeleton-wp-sidebar'    => array(
        'file'             => 'sidebar.min.css',
        'preload_callback' => function() {
          return skeleton_wp()->is_primary_sidebar_active();
        },
      ),
      'skeleton-wp-widgets'    => array(
        'file'             => 'widgets.min.css',
        'preload_callback' => function() {
          return skeleton_wp()->is_primary_sidebar_active();
        },
      ),
      'skeleton-wp-front-page' => array(
        'file' => 'front-page.min.css',
        'preload_callback' => function() {
          global $template;
          return 'front-page.php' === basename( $template );
        },
      ),
    );

    $css_files = apply_filters( 'skeleton_wp_css_files', $css_files );

    $this->css_files = array();
    foreach ( $css_files as $handle => $data ) {
      if ( is_string( $data ) ) {
        $data = array( 'file' => $data );
      }

      if ( empty( $data['file'] ) ) {
        continue;
      }

      $this->css_files[ $handle ] = array_merge(
        array(
          'global'           => false,
          'preload_callback' => null,
          'media'            => 'all',
        ),
        $data
      );
    }

    return $this->css_files;
  }

  protected function get_google_fonts() : array {
    // PHP 8.2 Fix: Use empty() instead of is_array()
    if ( ! empty( $this->google_fonts ) ) {
      return $this->google_fonts;
    }

    $google_fonts = array();

    $this->google_fonts = (array) apply_filters( 'skeleton_wp_google_fonts', $google_fonts );

    return $this->google_fonts;
  }

  protected function get_google_fonts_url() : string {
    $google_fonts = $this->get_google_fonts();

    if ( empty( $google_fonts ) ) {
      return '';
    }

    $font_families = array();

    foreach ( $google_fonts as $font_name => $font_variants ) {
      if ( ! empty( $font_variants ) ) {
        if ( ! is_array( $font_variants ) ) {
          $font_variants = explode( ',', str_replace( ' ', '', $font_variants ) );
        }

        $font_families[] = $font_name . ':' . implode( ',', $font_variants );
        continue;
      }

      $font_families[] = $font_name;
    }

    $query_args = array(
      'family'  => implode( '|', $font_families ),
      'display' => 'swap',
    );

    return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
  }
}
