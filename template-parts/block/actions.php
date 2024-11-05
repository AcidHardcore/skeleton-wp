<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$is_wow = $args['is_wow'] ?? false;
$wow_delay = $args['wow_delay'] ?? '';
$wow_delay_mobile = $args['wow_delay_mobile'] ?? 0;
$wow_sync = $args['wow_sync'] ?? '';

$ctas    = $args['ctas'] ?? [];
$classes = $args['classes'] ?? [];
$text_align  = $args['align'] ?? '';

$classes = array_merge( [ 'actions' ], $classes );
if ( $text_align ) {
	$classes[] = $text_align;
}
if ( $is_wow ) {
  $classes[] = 'wow wow--fade-in-up';
}

if ( count( $ctas ) ): ?>
	<div class="<?= join( ' ', $classes ) ?>"
    <?= $wow_delay ? 'data-wow-delay="'.$wow_delay.'"' : '' ?>
    <?= $wow_delay_mobile ? 'data-wow-delay-mobile="'.$wow_delay_mobile.'"' : '' ?>
    <?= $wow_sync ? 'data-wow-sync="'.$wow_sync.'"' : '' ?>
  >
		<?php foreach ( $ctas as $cta ): ?>
			<?php get_template_part( 'template-parts/block/cta', null, [
				'cta' => $cta['cta'],
        'type' => $cta['type'] ?? '',
        'position' => $cta['position'] ?? ''
			] ); ?>
		<?php endforeach; ?>
	</div>
<?php endif;
