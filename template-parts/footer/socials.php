<?php
/**
 * Template part for displaying the footer socials
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;
$socials = get_field('socials', 'footer');
?>
<?php if (is_array($socials)) : ?>
	<ul class="social">
		<?php foreach ($socials as $social) : ?>
			<?php
			$title = $social['name'] ?? '';
			if (isset($social['image'])) {
				$image_source = file_get_contents(wp_get_original_image_path($social['image']));
			}
			?>
			<li class="btn btn--link social__link-wrap " title="<?= esc_attr($title) ?>">
				<?php if (isset($social['name'])) : ?>
					<span class="social__text"><?= esc_html($title) ?></span>
				<?php endif; ?>
				<?php if (isset($social['link'])) : ?>
					<a class="social__link" href="<?= esc_url($social['link']) ?>" target="_blank" rel="noopener">
						<?php if (isset($social['image'])) : ?>
							<?php echo $image_source ?>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
