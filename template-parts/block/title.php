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
$is_wow = $args['is_wow'] ?? false;
$wow_sync = $args['wow_sync'] ?? '';
$wow_delay = $args['wow_delay'] ?? 0;
$wow_delay_mobile = $args['wow_delay_mobile'] ?? 0;

if ( $as_tag != $tag ) {
	$classes[] = 'as-'.$as_tag;
}
if ( $color ) {
	$classes[] = $color;
}

if ( $is_wow ) {
  $classes[] = 'wow wow--fade-in-up';
}

if ( $title ): ?>
<<?= $tag ?> class="<?= esc_attr(join(' ',$classes)) ?>"
  <?= $wow_delay ? 'data-wow-delay="'.$wow_delay.'"' : '' ?>
  <?= $wow_delay_mobile ? 'data-wow-delay-mobile="'.$wow_delay_mobile.'"' : '' ?>
  <?= $wow_sync ? 'data-wow-sync="'.$wow_sync.'"' : '' ?>
  >
	<?= wp_kses_post($title) ?>
</<?= $tag ?>>
<?php endif;
