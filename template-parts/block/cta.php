<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$link = $args['cta'];
if ($link) {
	$link_url = $link['url'];
	$link_title = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';
}
$classes = $args['classes'] ?? [];

array_unshift($classes, 'btn');

if ($link): ?>
	<a href="<?php echo esc_url($link_url); ?>" class="<?= join(' ', $classes) ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
<?php endif; ?>
