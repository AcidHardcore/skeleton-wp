<?php /**
 * @var array $args
 */

$icon = $args['icon'] ?? '';
$title = $args['title'] ?? '';
$tag = $args['tag'] ?? 'h2';
$as_tag = $args['as_tag'] ?? $tag;
$color = $args['color'] ?? '';
$classes = $args['classes'] ?? [];
if ( $as_tag != $tag ) {
	$classes[] = 'as-'.$as_tag;
}
if ( $color ) {
	$classes[] = $color;
}

if ( $title ): ?>
<<?= $tag ?> class="<?= esc_attr(join(' ',$classes)) ?>">
	<?php if ( $icon ): ?>
	<div class="title-icon"><span class="icon <?= esc_attr($icon) ?>"></span></div>
	<?php endif; ?>
	<?= wp_kses_post($title) ?>
</<?= $tag ?>>
<?php endif;
