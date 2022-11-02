<?php /**
 * @var array $args

 */

$image = $args['image'] ?? false;
$is_lazy = $args['is_lazy'] ?? true;
$is_cover = $args['is_cover'] ?? false;
$is_contain = $args['is_contain'] ?? false;
$classes = $args['classes'] ?? [];
$size = $args['size'] ?? 'full';
$args = $args['args'] ?? [];

array_unshift($classes,'image-wrap');
if ( $is_cover ) {
	$classes[] = 'image-wrap--cover';
}
if ( $is_contain ) {
	$classes[] = 'image-wrap--contain';
}
$args = [];
if(!$is_lazy) {
$args[] = ['loading' => 'eager'];
}


if ( $image ): ?>
<figure class="<?= join(' ', $classes ) ?>">
	<?= wp_get_attachment_image($image, $size, false, $args) ?>
</figure>
<?php endif;
