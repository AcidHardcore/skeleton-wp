<?php /**
 * @var array $args
 */

namespace Skeleton_WP\Skeleton_WP;

$html = $args['html'] ?? '';
$size = $args['size'] ?? '';
$classes = $args['classes'] ?? [];
$attr = $args['attrs'] ?? [];

array_unshift($classes, 'editor');
if ($size) {
  $classes[] = 'editor--' . $size;
}


if ($html): ?>
  <div class="<?= esc_attr(join(' ', $classes)) ?>"
    <?= skeleton_wp()->handle_attributes($attr) ?>
  >
    <?= wp_kses_post($html) ?>
  </div>
<?php endif;
