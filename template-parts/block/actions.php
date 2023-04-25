<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$ctas    = $args['ctas'] ?? [];
$classes = $args['classes'] ?? [];
$align   = $args['align'] ?? '';

$classes = array_merge( [ 'actions' ], $classes );
if ( $align ) {
	$classes[] = $align;
}

if ( count( $ctas ) ): ?>
	<div class="<?= join( ' ', $classes ) ?>">
		<?php foreach ( $ctas as $cta ): ?>
			<?php get_template_part( 'template-parts/block/cta', null, [
				'cta' => $cta,
			] ); ?>
		<?php endforeach; ?>
	</div>
<?php endif;
