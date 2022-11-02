<?php /**
 * @var array $args
 */

$html = $args['html'] ?? '';
$classes = $args['classes'] ?? [];

array_unshift($classes,'editor');

if ( $html ): ?>
<div class="<?= esc_attr(join(' ',$classes)) ?>">
	<?= wp_kses_post($html) ?>
</div>
<?php endif;
