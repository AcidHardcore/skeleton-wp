<?php
/**
 * @var array $args
 */

namespace Skeleton_WP\Skeleton_WP;

$id = $args['id'] ?? null;
$classes = $args['classes'] ?? [];
$pad = $args['pad'] ?? '';
$pad_t = $args['pad_t'] ?? $pad;
$pad_b = $args['pad_b'] ?? $pad;
$pad_mt = $args['pad_mt'] ?? null;
$pad_mb = $args['pad_mb'] ?? null;
$bg = $args['bg'] ?? '';
$bg_grad = $args['bg_grad'] ?? false;
$bg_image = $args['bg_image'] ?? null;
$bg_image_m = $args['bg_image_m'] ?? null;
$content = $args['content'] ?? '';
$content_bg = $args['content_bg'] ?? '';
$image_srcset = $args['image_srcset'] ?? '';
$is_dark = skeleton_wp()->mytheme_color_is_dark($bg);

array_unshift($classes,'block-full');
if ( $pad_t ) {
  $classes[] = 'block-full--pad-top-'.$pad_t;
}
if ( $pad_b ) {
  $classes[] = 'block-full--pad-bot-'.$pad_b;
}
if ( $pad_mt ) {
  $classes[] = 'block-full--pad-mobile-top-'.$pad_mt;
}
if ( $pad_mb ) {
  $classes[] = 'block-full--pad-mobile-bot-'.$pad_mb;
}

if ( $bg_image ) {
  ob_start ();
  ?>

  <?php get_template_part ('template-parts/block/img', null, [
    'image' => $bg_image,
    'is_cover' => true,
    'is_transparent' => true,
    'classes' => $bg_image_m ? ['desktop'] : [],
    'image_srcset' => $image_srcset
  ]); ?>
  <?php get_template_part ('template-parts/block/img', null, [
    'image' => $bg_image_m,
    'is_cover' => true,
    'is_transparent' => true,
    'classes' => ['mobile'],
  ]); ?>

  <?= $content_bg ?>
  <?php
  $content_bg = ob_get_clean ();
}

if ( $bg ) {
  $classes[] = 'block-full--bg-'.$bg;
}
if ( $bg_grad ) {
  $classes[] = 'block-full--bg-grad-top';
}
if ( $content_bg ) {
  $classes[] = 'block-full--with-bg';
}

?><section <?= $id ? 'id="'.$id.'"' : '' ?> class="<?= join(' ', $classes) ?>">

  <?php if ( $content_bg ): ?>
    <div class="block-full__bg">
      <?= $content_bg ?>
    </div>
  <?php endif; ?>

  <?php if ( $content ): ?>
    <div class="block-full__content">
      <?= $content ?>
    </div>
  <?php endif; ?>

</section>
