<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$image = $args['image'] ?? false;
$is_lazy = $args['is_lazy'] ?? true;
$is_cover = $args['is_cover'] ?? false;
$is_contain = $args['is_contain'] ?? false;
$is_transparent = $args['is_transparent'] ?? false;
$is_responsive = $args['is_responsive'] ?? false;
$classes = $args['classes'] ?? [];
$size = $args['size'] ?? 'full';
$image_args = $args['args'] ?? [];
$image_srcset = $args['image_srcset'] ?? '';

array_unshift($classes,'image-wrap');
if ( $is_cover ) {
	$classes[] = 'image-wrap--cover';
}
if ( $is_contain ) {
	$classes[] = 'image-wrap--contain';
}
if ( $is_transparent ) {
  $classes[] = 'image-wrap--transparent';
}

if(!$is_lazy) {
  $image_args['loading'] =  'eager' ;
}

if($is_responsive) {
  $image_args['class'] =  'responsive';
}

if($image_srcset) {
  $image_args['srcset'] = $image_srcset;
}

if ( $image ): ?>
	<figure class="<?= join(' ', $classes ) ?>">
		<?= wp_get_attachment_image($image, $size, false, $image_args) ?>
	</figure>
<?php endif;
