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

wp_print_styles( 'block-head' );

$id = '';
if ( ! empty( $block['anchor'] ) ) {
  $id = esc_attr( $block['anchor'] );
}

$class_name = 'head';
if ( ! empty( $block['className'] ) ) {
  $class_name .= ' ' . $block['className'];
}

if ( $block['id'] === skeleton_wp()->get_first_block_id() ) {
  $class_name .= ' block-full--first';
}

$bg           = get_field( 'bg' );
$bg_image     = get_field( 'bg_image' );
$wrapper      = get_field( 'wrapper' );
$title        = get_field( 'title' );
$title_tag    = get_field( 'title_tag' );
$title_as_tag = get_field( 'title_as_tag' );
$is_dark      = skeleton_wp()->mytheme_color_is_dark( $bg );
$title_words  = get_field( 'title_words' );
if ( $title_words ) {
  $words = wp_list_pluck( $title_words, 'word' );
  ob_start();
  get_template_part( 'template-parts/block/word-slider', null, [
    'words' => $words,
    'is_infinite' => true,
  ] );
  $words_render = ob_get_clean();
  $title        = $title . $words_render;
}
$html = get_field( 'html' );

if( get_post_type( get_the_ID() == 'recipe') && empty($bg_image) ) {
  $bg_image = get_post_thumbnail_id(get_the_ID());
}

ob_start(); ?>

  <div class="wrapper wrapper--<?= $wrapper ?> tac">

    <?php get_template_part( 'template-parts/block/title', null, [
      'title'  => $title,
      'tag'    => $title_tag,
      'as_tag' => $title_as_tag,
    ] ); ?>

    <?php get_template_part( 'template-parts/block/editor', null, [
      'html'      => $html,
      'is_wow'    => true,
      'wow_delay' => 150,
    ] ); ?>

    <InnerBlocks/>


  </div>

  <?php $content = ob_get_clean();
get_template_part( 'template-parts/block/full', null, [
  'id'            => $id,
  'pad'           => 'full',
  'bg'            => $bg,
  'bg_image'      => $bg_image,
  'bg_image_lazy' => false,
  'content'       => $content,
  'classes'       => [ $class_name, ( $is_dark ? 'bg-dark' : 'bg-light' ) ],
] );



