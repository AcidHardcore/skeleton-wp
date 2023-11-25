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

$id = '';
if ( ! empty( $block['anchor'] ) ) {
  $id = esc_attr( $block['anchor'] );
}

$class_name = 'text';
if ( ! empty( $block['className'] ) ) {
  $class_name .= ' ' . $block['className'];
}

if ( $block['id'] === skeleton_wp()->get_first_block_id() ) {
  $class_name .= ' block-full--first';
}

$bg         = get_field( 'bg' );
$pad_t      = get_field( 'pad_t' );
$pad_b      = get_field( 'pad_b' );
$wrapper    = get_field( 'wrapper' );
$text_align = get_field( 'text_align' );
$text_bg    = get_field( 'text_bg' );

$is_dark_text = skeleton_wp()->mytheme_color_is_dark( $text_bg );
if($text_bg) {
  $text_bg = 'text__bg text__bg--' . $text_bg;
  $text_bg .= $is_dark_text ? ' bg-dark' : ' bg-light';
}

$is_dark = skeleton_wp()->mytheme_color_is_dark( $bg );

$template = [
  [ 'acf/intro', [] ],
];

ob_start(); ?>

  <div class="wrapper wrapper--<?= esc_attr( $wrapper ) ?> <?= esc_attr( $text_align ) ?> <?= esc_attr( $text_bg ) ?>">

    <InnerBlocks template="<?= esc_attr( wp_json_encode( $template ) ) ?>"/>

  </div>

  <?php $content = ob_get_clean();
get_template_part( 'template-parts/block/full', null, [
  'id'      => $id,
  'pad_t'   => $pad_t,
  'pad_b'   => $pad_b,
  'bg'      => $bg,
  'content' => $content,
  'classes' => [ $class_name, ( $is_dark ? 'bg-dark' : 'bg-light' ) ],
] );



