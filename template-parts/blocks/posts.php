<?php
/**
 * Posts Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'posts-' . $block['id'];
if(!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' block--posts';
if(!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
if(!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

$bg = get_field('bg') ? get_field('bg') : 'white';
$className .= ' ' . $bg;
$title = get_field('title');
$subtitle = get_field('subtitle');
$type = get_field('load_more_type');
$posts_per_page = get_field('posts_per_page');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => $posts_per_page,
	'order' => 'DESC',
	'orderby' => 'date',
	'paged' => $paged
);
$query = new WP_Query($args);

$js_args = $args;
$js_args['load_more_type'] = $type;
$total_pages = $query->max_num_pages;

//pagination
if($type === 'pagination') {

	global $wp;
	$js_args['current_url'] = home_url(add_query_arg(array(), $wp->request));



	if($total_pages > 1) {

		$current_page = max(1, get_query_var('paged'));

		$args = array(
			'mid_size' => 2,
			'show_all' => true,
			'prev_next' => true,
			'prev_text' => __('Previous', 'understrap'),
			'next_text' => __('Next', 'understrap'),
			'screen_reader_text' => __('Posts navigation', 'understrap'),
			'type' => 'array',
			'current' => $current_page,
			'total' => $total_pages,
		);

		$links = paginate_links($args);
	}
}
?>

<div id="<?= esc_attr($id); ?>" class="block <?= esc_attr($className); ?>">

	<div class="block-content">
		<div class="wrapper ">

			<?php if($title) : ?>
				<h2 class="as-h5 tac"><?= esc_html($title) ?></h2>
			<?php endif; ?>

			<?php if($subtitle) : ?>
				<h3 class="as-h2 tac"><?= esc_html($subtitle) ?></h3>
			<?php endif; ?>

			<?php if($query->have_posts()) : ?>
				<div class="posts-wrap" data-type="<?= json_encode($type, JSON_NUMERIC_CHECK)?>">
					<?php while($query->have_posts()): $query->the_post(); ?>
						<?php get_template_part('template-parts/content/entry'); ?>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>

			<!--				Load More button -->
			<?php if(($query->found_posts > $posts_per_page) && $type === "load_more") : ?>
				<div class="block-actions tac">
					<button class="load-more"
						  data-args='<?= json_encode($js_args, JSON_NUMERIC_CHECK) ?>'>Load More</button>
				</div>
			<?php endif; ?>

			<!--				Pagination-->
			<?php if(($total_pages > 1) && ($type === 'pagination')) : ?>
				<nav class="navigation" aria-label="<?php echo $args['screen_reader_text']; ?>"
					 data-args='<?= json_encode($js_args, JSON_NUMERIC_CHECK) ?>'>
					<ul>

						<?php
						foreach($links as $key => $link) {
							?>
							<li class="<?php echo strpos($link, 'current') ? 'active' : ''; ?>">
								<?php echo str_replace('page-numbers', 'page-link', $link); ?>
							</li>
							<?php
						}
						?>

					</ul>
				</nav>
			<?php endif; ?>

		</div>
	</div>
</div>






