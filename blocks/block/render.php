<?php
/**
 * Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or it's parent block.
 */

namespace Skeleton_WP\Skeleton_WP;

wp_print_styles('block-block');

$anchor = '';
if (!empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

$class_name = 'block';

if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/image' );

$template = array(
	array('core/heading', array(
		'level' => 2,
		'content' => 'Title Goes Here',
	)),
	array( 'core/paragraph', array(
		'content' => '<strong>Colorway:</strong> <br /><strong>Style Code:</strong>  <br /><strong>Release Date:</strong> <br /><strong>MSRP:</strong> ',
	) ),
	array('core/image', array(

	))
);

?>
?>

<div <?php echo $anchor; ?> class="<?php echo esc_attr($class_name); ?>">
	<InnerBlocks template="<?= esc_attr( wp_json_encode( $template ) ) ?>" allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>" templateLock="insert"/>
</div>








