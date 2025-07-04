<?php
/**
 * @var array $args
 */

namespace Skeleton_WP\Skeleton_WP;


$title = $args['title'] ?? '';
$tag = $args['tag'] ?? 'h2';
$as_tag = $args['as_tag'] ?? $tag;
$color = $args['color'] ?? '';
$classes = $args['classes'] ?? [];
$attr = $args['attrs'] ?? [];

if ($as_tag != $tag) {
  $classes[] = 'as-' . $as_tag;
}
if ($color) {
  $classes[] = $color;
}

if ($title): ?>
  <<?= $tag ?> class="<?= esc_attr(join(' ', $classes)) ?>"
  <?= skeleton_wp()->handle_attributes($attr) ?>
  >
  <?= wp_kses_post($title) ?>
  </<?= $tag ?>>
<?php endif;
