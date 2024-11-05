<?php
/**
 * @var array $args
 */

namespace Skeleton_WP\Skeleton_WP;

$title_pre = $args['title_pre'] ?? '';
$title = $args['title'] ?? '';
$title_tag = $args['title_tag'] ?? 'h2';
$title_as_tag = $args['title_as_tag'] ?? null;
$title_color = $args['title_color'] ?? null;
$html_size = $args['html_size'] ?? '';
$html = $args['html'] ?? '';
$ctas = $args['ctas'] ?? [];
$classes = $args['classes'] ?? [];
$is_wow = $args['is_wow'] ?? true;

$classes[] = 'intro';

global $intro_wrap_i;
$intro_wrap_i = $intro_wrap_i ?? 0;
$intro_wrap_i ++;
$id = 'intro-wrap-' . $intro_wrap_i;

if ($title || $html || $ctas) : ?>
	<div id="<?= $id ?>" class="<?= esc_attr(join(' ', $classes)) ?>">

		<?php get_template_part('template-parts/block/title', null, [
      'title' => $title_pre,
      'tag' => 'div',
      'as_tag' => 'h6',
      'is_wow' => $is_wow,
      'wow_sync' => $id,
		]); ?>

		<?php get_template_part('template-parts/block/title', null, [
      'title' => $title,
      'tag' => $title_tag,
      'as_tag' => $title_as_tag,
      'color' => $title_color,
      'is_wow' => $is_wow,
      'wow_sync' => $id,
      'wow_delay' => 50,
		]); ?>

		<?php get_template_part('template-parts/block/editor', null, [
      'html' => $html,
      'size' => $html_size,
      'is_wow' => $is_wow,
      'wow_delay' => 100,
      'wow_sync' => $id,
		]); ?>

		<?php if ($ctas) : ?>
			<?php get_template_part('template-parts/block/actions', null, [
        'ctas' => $ctas,
        'is_wow' => $is_wow,
        'wow_delay' => 150,
        'wow_sync' => $id,
			]); ?>
		<?php endif; ?>

		<div><!-- last --></div>

	</div>
<?php endif;
