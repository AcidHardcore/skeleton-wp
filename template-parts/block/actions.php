<?php /**
 * @var array $args
 */

$ctas = $args['ctas'] ?? [];

if ( count($ctas)): ?>
<div class="actions">
	<?php foreach ( $ctas as $cta ): ?>
		<?php get_template_part('template-parts/block/cta', null, [
			'cta' => $cta,
		]); ?>
	<?php endforeach; ?>
</div>
<?php endif;
