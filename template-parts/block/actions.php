<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$ctas    = $args['ctas'] ?? [];
$classes = $args['classes'] ?? [];
$text_align  = $args['align'] ?? '';
$attr = $args['attrs'] ?? [];

$classes = array_merge( [ 'actions' ], $classes );
if ( $text_align ) {
	$classes[] = $text_align;
}

if ( count( $ctas ) ): ?>
	<div class="<?= join( ' ', $classes ) ?>"
    <?= skeleton_wp()->handle_attributes($attr) ?>
  >
		<?php foreach ( $ctas as $cta ): ?>
			<?php get_template_part( 'template-parts/block/cta', null, [
				'cta' => $cta['cta'] ?? [],
        'type' => $cta['type'] ?? '',
        'position' => $cta['position'] ?? ''
			] ); ?>
		<?php endforeach; ?>
	</div>
<?php endif;
