<?php
/**
 * Skeleton_WP\Skeleton_WP\Load_More\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Load_More;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use WP_Query;
use function ob_start;
use function ob_get_clean;
use function get_template_part;
use function max;
use function paginate_links;
use function strpos;
use function str_replace;
use function wp_send_json_success;
use WP_REST_Server;
use WP_REST_Request;
use function json_decode;
//use function json_encode;
use WP_Error;

/**
 * Class for AJAX load more post and pagination
 *
 * @link https://rudrastyh.com/wordpress/load-more-posts-ajax.html
 */
class Component implements Component_Interface {

	/**
	 * Associative array of $_POST super array.
	 *
	 * @var array
	 */
	protected object $input;

	/**
	 * WP_Query instance.
	 *
	 * @var object
	 */
	protected object $query;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'load_more';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action('rest_api_init', array($this, 'custom_route'));
	}

	/**
	 * Add a custom route the the WP REST API
	 */
	public function custom_route() {
		register_rest_route('load-more/v1', '/posts/', array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => array($this, 'custom_response'),
			'args' => array(
				'data' => array(
					'type' => 'string',
					'required' => true,
					'validate_callback' => function($param, $request, $key) {
						return is_object( json_decode($param) );
					}
				),
			),
			'permission_callback' => '__return_true'
		));
	}

	/**
	 * Validate object items
	 *
	 * @param string $data
	 */
		public function validate_input(string $data) {

		//convert a string to  an object after JSON.stringify
		$this->input = json_decode($data);

		$this->input->paged = !empty($this->input->paged) ? esc_attr($this->input->paged) : 0;
		$this->input->post_type = !empty($this->input->post_type) ? esc_attr($this->input->post_type) : 0;
		$this->input->orderby = !empty($this->input->orderby) ? esc_attr($this->input->orderby) : 0;
		$this->input->order = !empty($this->input->order) ? esc_attr($this->input->order) : 0;
		$this->input->posts_per_page = !empty($this->input->posts_per_page) ? esc_attr($this->input->posts_per_page) : 0;
//		$this->input->category = !empty($this->input->category) ? array_map('esc_attr', $this->input->category) : array();
		$this->input->load_more_type = !empty($this->input->load_more_type) ? esc_attr($this->input->load_more_type) : 0;
		$this->input->current_url = !empty($this->input->current_url) ? esc_attr($this->input->current_url) : 0;
	}

	/**
	 * Retrieve posts
	 *
	 * @return false|string
	 */

	public function retrieve_posts($data) {

		$this->validate_input($data);

//		error_log(print_r($this->input, true));
		$args = array(
			'post_type' => $this->input->post_type,
			'post_status' => 'publish',
			'posts_per_page' => $this->input->posts_per_page,
			'order' => $this->input->order,
			'orderby' => $this->input->orderby,
			'paged' => $this->input->paged,
		);

		if(!empty($this->input->cat)) {
			$args['cat'] = intval($this->input->cat);
		}


		$this->query = new WP_Query($args);

		if($this->query->have_posts()) {
			ob_start();

			while($this->query->have_posts()): $this->query->the_post();

				if($this->input->post_type == 'post') {
					get_template_part('template-parts/content/entry');
				}

			endwhile;

			return ob_get_clean();

		} else {
			return false;
		}
	}


	/**
	 * Update pagination
	 *
	 * @return false|string
	 */
	public function pagination() {
		$this->input->total_pages = $this->query->max_num_pages;

		$this->input->current_page = max(1, $this->input->paged);

		$args = array(
			'base' => $this->input->current_url,
			'format' => '/page/%#%/',
			'mid_size' => 2,
			'show_all' => false, //show all pages links
			'prev_next' => true,
			'prev_text' => __('Previous', 'understrap'),
			'next_text' => __('Next', 'understrap'),
			'screen_reader_text' => __('Posts navigation', 'understrap'),
			'type' => 'array',
			'current' => $this->input->current_page,
			'total' => $this->input->total_pages,
		);

		$links = paginate_links($args);

		ob_start();

		foreach($links as $key => $link) {
			?>
			<li class="<?php echo strpos($link, 'current') ? 'active' : ''; ?>">
				<?php echo str_replace('page-numbers', 'page-link', $link); ?>
			</li>
			<?php
		}

		return ob_get_clean();

	}

	public function custom_response(WP_REST_Request $request) {

		$info = array();
		$info['posts'] = $this->retrieve_posts($request['data']);
		if($this->input->load_more_type === 'pagination') {
			$info['pagination'] = $this->pagination();
		}
    $info['maxPage'] = $this->query->max_num_pages;
    $info['found'] = $this->query->found_posts;

		if(!empty($info)) {
			$response = rest_ensure_response($info);
			$response->set_status(200);
		} else {
			$response = new WP_Error('no_posts', "Invalid data", array('status' => 404));
		}

		return $response;
	}

}
