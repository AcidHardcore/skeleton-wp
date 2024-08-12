<?php
/**
 * @var array $args
 */

namespace Skeleton_WP\Skeleton_WP;

$id = $args['id'] ?? null;
$classes = $args['classes'] ?? [];
$is_pad = $args['is_pad'] ?? false;
$is_fullscreen = $args['is_fullscreen'] ?? false;

$bg = $args['bg'] ?? 'white';
$bg_image = $args['bg_image'] ?? null;

$is_dark = skeleton_wp()->mytheme_color_is_dark($bg);

$content_bg = $args['content_bg'] ?? null;

if ($bg_image) {
  ob_start();
  get_template_part('template-parts/block/img', null, [
    'image' => $bg_image,
    'is_cover' => true,
    'is_lazy' => false,
  ]);
  $content_bg = ob_get_clean();
}

$content = $args['content'] ?? null;

array_unshift($classes, 'block-full');
if ($is_pad) {
  $classes[] = 'block-full--pad';
}

if ($is_fullscreen) {
  $classes[] = 'block-full--fullscreen';
}
if ($bg) {
  $classes[] = 'block-full--bg-' . $bg;
}
if ($content_bg) {
  $classes[] = 'block-full--with-bg';
}
if ($is_dark) {
  $classes[] = 'bg-dark';
}
?>
<section <?= $id ? 'id="' . $id . '"' : '' ?>
  class="<?= join(' ', $classes) ?>"
>

  <?php if ($content_bg): ?>
    <div class="block-full__bg">
      <?= $content_bg ?>
    </div>
  <?php endif; ?>

  <?php if ($content): ?>
    <div class="block-full__content">
      <?= $content ?>
    </div>
  <?php endif; ?>

</section>
