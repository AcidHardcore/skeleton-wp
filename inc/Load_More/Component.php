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
	protected array $input;

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
		add_action('wp_ajax_load_more_button', array($this, 'send_data'));
		add_action('wp_ajax_nopriv_load_more_button', array($this, 'send_data'));
	}

	/**
	 * Check input data
	 * @return array
	 */
	public function check_input(): array {

		$this->input['paged'] = !empty($_POST['paged']) ? esc_attr($_POST['paged']) : 0;
		$this->input['post_type'] = !empty($_POST['post_type']) ? esc_attr($_POST['post_type']) : 0;
		$this->input['orderby'] = !empty($_POST['orderby']) ? esc_attr($_POST['orderby']) : 0;
		$this->input['order'] = !empty($_POST['order']) ? esc_attr($_POST['order']) : 0;
		$this->input['posts_per_page'] = !empty($_POST['posts_per_page']) ? esc_attr($_POST['posts_per_page']) : 0;
		$this->input['category'] = !empty($_POST['category']) ? array_map('esc_attr', $_POST['category']) : array();
		$this->input['load_more_type'] = !empty($_POST['load_more_type']) ? esc_attr($_POST['load_more_type']) : 0;
		$this->input['current_url'] = !empty($_POST['current_url']) ? esc_attr($_POST['current_url']) : 0;

		return $this->input;
	}

	/**
	 * Retrieve posts
	 *
	 * @return false|string
	 */

	public function retrieve_posts() {

		$this->check_input();

		$args = array(
			'post_type' => $this->input['post_type'],
			'post_status' => 'publish',
			'posts_per_page' => $this->input['posts_per_page'],
			'order' => $this->input['order'],
			'orderby' => $this->input['orderby'],
			'paged' => $this->input['paged'],
		);

		if(!empty($category)) {
			$args['category__in'] = $this->input['category'];
		}


		$this->query = new WP_Query($args);

		if($this->query->have_posts()) {
			ob_start();

			while($this->query->have_posts()): $this->query->the_post();

				if($this->input['post_type'] == 'post') {
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
		$this->input['total_pages'] = $this->query->max_num_pages;

		$this->input['current_page'] = max(1, $this->input['paged']);

		$args = array(
			'base' => $this->input['current_url'],
			'format' => '/page/%#%/',
			'mid_size' => 2,
			'show_all' => false, //show all pages links
			'prev_next' => true,
			'prev_text' => __('Previous', 'understrap'),
			'next_text' => __('Next', 'understrap'),
			'screen_reader_text' => __('Posts navigation', 'understrap'),
			'type' => 'array',
			'current' => $this->input['current_page'],
			'total' => $this->input['total_pages'],
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

	/**
	 * Send data to front-end
	 */
	function send_data() {

		$result = array();
		$result['posts'] = $this->retrieve_posts();
		if($this->input['load_more_type'] === 'pagination') {
			$result['pagination'] = $this->pagination();
		}
		wp_send_json_success($result);

		die; // here we exit the script and even no wp_reset_query() required!
	}
}
