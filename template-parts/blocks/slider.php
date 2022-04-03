<?php
/**
 * Block Slider Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
namespace Skeleton_WP\Skeleton_WP;

// Create id attribute allowing for custom "anchor" value.
$id = $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' block-slider';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

$slides = get_field('slides');

?>

<section id="<?= esc_attr($id); ?>" class="block <?= esc_attr($className); ?>">

	<div class="block__content">

		<?php if ($slides) : ?>
			<div class="swiper">
				<div class="swiper-wrapper">
					<?php foreach ($slides as $slide) : ?>
						<div class="swiper-slide block-slider__slide">
							<div class="block-slider__bg">
								<?php if (isset($slide['bg_image'])) : ?>
									<?= wp_get_attachment_image($slide['bg_image'], 'full', false, array('class' => 'block-slider__image')) ?>
								<?php endif; ?>
							</div>

							<div class="container block-slider__content">

									<?php if (isset($slide['title'])) : ?>
										<?= esc_html($slide['title']) ?>
									<?php endif; ?>

									<?php if (isset($slide['link']) && !empty($slide['link'])) :
										$link = $slide['link'];
										$link_url = trim($link['url']);
										$link_title = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self'; ?>
										<a class="btn btn--main" href="<?php echo esc_url($link_url); ?>"
										   target="<?php echo esc_attr($link_target); ?>">
											<?php echo esc_html($link_title); ?>
										</a>
									<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		<?php endif; ?>

	</div>
</section>

