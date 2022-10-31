<?php /**
 * @var array $args
 */

$text_align = $args['text_align'] ?? '';
$title_pre = $args['title_pre'] ?? '';
$title_pre_color = $args['title_pre_color'] ?? 'gold';
$title = $args['title'] ?? '';
$title_tag = $args['title_tag'] ?? 'h2';
$html = $args['html'] ?? '';
$cta = $args['cta'] ?? [];
$classes = $args['classes'] ?? [];

$title_pre_tag = $title_tag;
$title_as_tag = $title_tag;
if ($title_pre) {
	$title_tag = preg_replace_callback('#^h([0-9])$#', function ($m) {
		return 'h' . ($m[1] + 1);
	}, $title_tag);
}

if ($title_pre || $title || $html || $cta) : ?>
	<div class="wow wow--fade-in <?= esc_attr($text_align) ?> <?= esc_attr(join(' ', $classes)) ?>">

		<?php get_template_part('template-parts/block/title', null, [
			'title' => $title_pre,
			'tag' => $title_pre_tag,
			'as_tag' => 'h6',
			'color' => $title_pre_color,
		]); ?>

		<?php get_template_part('template-parts/block/title', null, [
			'title' => $title,
			'tag' => $title_tag,
			'as_tag' => $title_as_tag,
		]); ?>

		<?php get_template_part('template-parts/block/editor', null, [
			'html' => $html,
		]); ?>

		<?php if ($cta) : ?>
			<?php get_template_part('template-parts/block/actions', null, [
				[
					'cta' => $cta,
				]
			]); ?>
		<?php endif; ?>

		<div><!-- last --></div>

	</div>
<?php endif;
