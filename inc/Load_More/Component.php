<?php
/**
 * Skeleton_WP\Skeleton_WP\Load_More\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Load_More;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use WP_Query;
use WP_REST_Server;
use WP_REST_Request;
use WP_Error;
use WP_REST_Response;
use function Skeleton_WP\Skeleton_WP\skeleton_wp;

/**
 * Class for AJAX load more post and pagination via the REST API.
 */
class Component implements Component_Interface
{

  /**
   * The validated and sanitized query arguments.
   * @var array
   */
  private array $query_args = [];

  /**
   * Gets the unique identifier for the theme component.
   *
   * @return string Component slug.
   */
  public function get_slug(): string
  {
    return 'load_more';
  }

  /**
   * Adds the action and filter hooks to integrate with WordPress.
   */
  public function initialize()
  {
    add_action('rest_api_init', array($this, 'register_rest_route'));
    add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
  }

  public function enqueue_scripts()
  {
    $script_handle = 'skeleton-wp-load-more';

    wp_register_script(
      $script_handle,
      get_theme_file_uri('/assets/js/load-more.min.js'),
      array(),
      skeleton_wp()->get_asset_version(get_theme_file_path('/assets/js/load-more.min.js')),
      array(
        'strategy' => 'defer',
        'in_footer' => true
      )
    );

    $inline_data = [
      'nonce' => wp_create_nonce('wp_rest'),
      'api_url' => esc_url_raw(rest_url('load-more/v1/posts')),
    ];

    $inline_script = 'const load_more_vars = ' . wp_json_encode($inline_data) . ';';

    wp_add_inline_script($script_handle, $inline_script, 'before');
  }

  /**
   * Registers the custom REST API route.
   */
  public function register_rest_route()
  {
    register_rest_route('load-more/v1', '/posts', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => [$this, 'handle_rest_request'],
      'permission_callback' => '__return_true',
      'args' => [
        'query_args' => [
          'required' => true,
          'validate_callback' => [$this, 'validate_query_args_structure'],
          'sanitize_callback' => [$this, 'sanitize_query_args_recursively'],
        ],
        'current_url' => [
          'required' => true,
          'type' => 'string',
          'validate_callback' => 'wp_http_validate_url',
          'sanitize_callback' => 'esc_url_raw',
        ],
      ],
    ]);
  }

  /**
   * Handles the incoming REST API request.
   * The parameters are already validated and sanitized by the REST API.
   *
   * @param WP_REST_Request $request The request object.
   *
   * @return WP_REST_Response|WP_Error
   */
  public function handle_rest_request(WP_REST_Request $request): WP_REST_Response|WP_Error
  {
    $query_args = $request->get_param('query_args');
    $current_url = $request->get_param('current_url');

    $response_data = $this->retrieve_posts($query_args, $current_url);

    return new WP_REST_Response($response_data, 200);
  }

  /**
   * Retrieves posts and pagination based on the sanitized query arguments.
   *
   * @param array $query_args The sanitized query arguments.
   * @param string $current_url The sanitized current URL for pagination.
   *
   * @return array An array containing the rendered posts HTML and query metadata.
   */
  private function retrieve_posts(array $query_args, string $current_url): array
  {
    $query = new WP_Query($query_args);

    $posts_html = '';
    if ($query->have_posts()) {
      ob_start();
      while ($query->have_posts()) {
        $query->the_post();
        get_template_part('template-parts/content/entry');
      }
      $posts_html = ob_get_clean();
    }
    wp_reset_postdata();

    return [
      'posts_html' => $posts_html,
      'pagination' => $this->render_pagination_html($query->max_num_pages, $query_args['paged'] ?? 1, $current_url),
      'maxNumPages' => $query->max_num_pages,
      'foundPosts' => $query->found_posts,
    ];
  }

  /**
   * Validation callback for the 'query_args' REST API parameter.
   *
   * @param mixed $value The value of the parameter.
   * @param WP_REST_Request $request The request object.
   * @param string $param The name of the parameter.
   *
   * @return true|WP_Error True if the value is valid, WP_Error otherwise.
   */
  public function validate_query_args_structure($value, $request, $param)
  {
    if (!is_array($value)) {
      return new WP_Error('invalid_param', sprintf('%s must be an object.', $param), ['status' => 400]);
    }

    if (empty($value['post_type'])) {
      return new WP_Error('missing_required_param', sprintf('%s must contain a "post_type".', $param), ['status' => 400]);
    }

    $post_types = is_array($value['post_type']) ? $value['post_type'] : [$value['post_type']];
    foreach ($post_types as $pt) {
      if (!post_type_exists($pt)) {
        return new WP_Error('invalid_post_type', sprintf('Post type "%s" does not exist.', esc_html($pt)), ['status' => 400]);
      }
    }

    return true;
  }

  /**
   * Sanitization callback for the 'query_args' REST API parameter.
   *
   * @param array $query_args The raw query_args object.
   *
   * @return array The sanitized query_args array.
   */
  public function sanitize_query_args_recursively(array $query_args): array
  {
    $allowed_params = [
      'paged' => ['type' => 'int', 'default' => 1],
      'post_type' => ['type' => 'string_list', 'default' => 'post'],
      'orderby' => ['type' => 'string', 'default' => 'date'],
      'order' => ['type' => 'string', 'default' => 'DESC'],
      's' => ['type' => 'string', 'default' => null],
      'posts_per_page' => ['type' => 'int', 'default' => 10],
      'cat' => ['type' => 'int_list', 'default' => null],
      'tag' => ['type' => 'string', 'default' => null],
      'category__in' => ['type' => 'int_list', 'default' => null],
      'category__not_in' => ['type' => 'int_list', 'default' => null],
      'tag__in' => ['type' => 'int_list', 'default' => null],
      'tag__not_in' => ['type' => 'int_list', 'default' => null],
      'tax_query' => ['type' => 'tax_query', 'default' => null],
      'meta_query' => ['type' => 'meta_query', 'default' => null],
    ];

    $sanitized_args = [];
    foreach ($allowed_params as $param => $config) {
      $value = $query_args[$param] ?? $config['default'];
      if ($value !== null) {
        $sanitized_args[$param] = $this->sanitize_query_var($value, $config['type']);
      }
    }

    return $sanitized_args;
  }

  /**
   * Sanitize a single WP_Query parameter based on its expected type.
   *
   * @param mixed $value The raw value from the input.
   * @param string $type The expected data type.
   *
   * @return mixed The sanitized value.
   */
  private function sanitize_query_var($value, string $type)
  {
    switch ($type) {
      case 'int':
        return absint($value);
      case 'string':
        return sanitize_key($value);
      case 'int_list':
        $items = is_array($value) ? $value : explode(',', $value);

        return array_map('absint', $items);
      case 'string_list':
        $items = is_array($value) ? $value : explode(',', $value);

        return array_map('sanitize_key', $items);
      case 'tax_query':
      case 'meta_query':
        return is_array($value) ? $this->recursively_sanitize_query_array($value) : null;
      default:
        return null;
    }
  }

  /**
   * Recursively walk through a nested array and sanitize every key and value.
   *
   * @param array $array The array to sanitize.
   *
   * @return array The sanitized array.
   */
  private function recursively_sanitize_query_array(array $array): array
  {
    $sanitized_array = [];
    foreach ($array as $key => $value) {
      $sanitized_key = sanitize_key($key);
      if (is_array($value)) {
        $sanitized_array[$sanitized_key] = $this->recursively_sanitize_query_array($value);
      } else {
        $sanitized_array[$sanitized_key] = sanitize_text_field($value);
      }
    }

    return $sanitized_array;
  }

  /**
   * Update pagination
   *
   * @return null|string
   */
  public function render_pagination_html($total_pages, $current_page, $current_url)
  {

    if ($total_pages <= 1) {
      return null;
    }

    $args = array(
      'base' => strtok($current_url, '?') . '%_%',
      'format' => '?pages="%#%"',
      'mid_size' => 2,
      'prev_next' => true,
      'prev_text' => __('prev', 'skeleton_wp'),
      'next_text' => __('next', 'skeleton_wp'),
      'current' => $current_page,
      'total' => $total_pages,
    );

    $links = skeleton_wp()->paginate_links_data($args);


    if (empty($links)) {
      return null;
    }

    ob_start();

    get_template_part('template-parts/content/pagination', null, [
      'links' => $links
    ]);

    return ob_get_clean();
  }
}

