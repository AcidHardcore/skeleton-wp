<?php
/**
* Block Template.
*
* @param array $block The block settings and attributes.
* @param string $content The block inner HTML (empty).
* @param bool $is_preview True during backend preview render.
* @param int $post_id The post ID the block is rendering content against.
*          This is either the post ID currently being displayed inside a query loop,
*          or the post ID of the post hosting this block.
* @param array $context The context provided to the block by the post or it's parent block.
*/

namespace Skeleton_WP\Skeleton_WP;

wp_print_styles('block-slider');

$anchor = '';
if (!empty($block['anchor'])) {
$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

$class_name = 'block block-slider';

if (!empty($block['className'])) {
$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
$class_name .= ' align' . $block['align'];
}

$slides = get_field('slides');
?>

<section <?= esc_attr($anchor) ?> class="<?= esc_attr($class_name); ?>">

	<div class="block__content">

		<?php if ($slides) : ?>
			<div class="swiper">
				<div class="swiper-wrapper">
					<?php foreach ($slides as $slide) : ?>
						<div class="swiper-slide block-slider__slide">
							<div class="block-slider__bg">

								<?php get_template_part('template-parts/block/img', null, [
									'image' => $slide['bg_image'],
									'size' => 'full',
									'is_cover' => true,
									'args' => array('class' => 'block-slider__image')
								]); ?>

							</div>

							<div class="container block-slider__content">

								<?php get_template_part('template-parts/block/title', null, [
									'title' => $slide['title'],
									'tag' => 'h2',
								]); ?>


								<?php get_template_part('template-parts/block/cta', null, [
									'cta' => $slide['link'],
									'classes' => ['btn--main']
								]); ?>

							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		<?php endif; ?>

	</div>
</section>

