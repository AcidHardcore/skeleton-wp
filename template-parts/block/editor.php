<?php /**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$html = $args['html'] ?? '';
$size = $args['size'] ?? '';
$classes = $args['classes'] ?? [];
$is_wow = $args['is_wow'] ?? false;
$wow_delay = $args['wow_delay'] ?? 0;
$wow_delay_mobile = $args['wow_delay_mobile'] ?? 0;
$wow_sync = $args['wow_sync'] ?? '';


array_unshift($classes,'editor');
if ( $size ) {
  $classes[] = 'editor--'.$size;
}
if ( $is_wow ) {
  $classes[] = 'wow wow--fade-in-up';
}


if ( $html ): ?>
<div class="<?= esc_attr(join(' ',$classes)) ?>"
  <?= $wow_delay ? 'data-wow-delay="'.$wow_delay.'"' : '' ?>
  <?= $wow_delay_mobile ? 'data-wow-delay-mobile="'.$wow_delay_mobile.'"' : '' ?>
  <?= $wow_sync ? 'data-wow-sync="'.$wow_sync.'"' : '' ?>
>
	<?= wp_kses_post($html) ?>
</div>
<?php endif;
