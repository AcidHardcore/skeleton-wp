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

wp_print_styles( 'block-team-slider' );

$id = '';
if ( ! empty( $block['anchor'] ) ) {
  $id = $block['anchor'];
}

$class_name = 'team-slider';
if ( ! empty( $block['className'] ) ) {
  $class_name .= ' ' . $block['className'];
}

if ( $block['id'] === skeleton_wp()->get_first_block_id() ) {
  $class_name .= ' block-full--first';
}

$bg       = get_field( 'bg' );
$pad_t    = get_field( 'pad_t' );
$pad_b    = get_field( 'pad_b' );
$team     = get_field( 'team' );
$cta_all  = get_field( 'cta_all' );
$cta_less = get_field( 'cta_less' );
$is_dark  = skeleton_wp()->mytheme_color_is_dark( $bg );

ob_start(); ?>

  <div class="wrapper">
    <div class="team-slider__wrap">
      <?php if ( $team ) : ?>
        <div class="team-slider__slider">
          <?php foreach ( $team as $member ):
            ?>
            <div class="team-slider__member">
              <div class="team-slider__member__image-border">
                <div class="team-slider__member__image">
                  <?php get_template_part( 'template-parts/block/img', null, [
                    'image'    => $member['image'],
                    'is_cover' => true,
                  ] ); ?>
                </div>
              </div>
              <div class="team-slider__member__name">
                <?php get_template_part( 'template-parts/block/title', null, [
                  'title'  => $member['name'],
                  'tag'    => 'h2',
                  'as_tag' => 'h5',
                  'is_wow' => false,
                ] ); ?>
              </div>
              <div class="team-slider__member__text">
                <?php get_template_part( 'template-parts/block/editor', null, [
                  'html' => $member['html'],
                ] ); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php /*get_template_part( 'template-parts/block/actions', null, [
      'ctas'    => [
        [
          'cta'     => $cta_all,
          'classes' => [ 'team-slider__show-all' ],
        ],
        [
          'cta'     => $cta_less,
          'classes' => [ 'team-slider__show-less' ],
        ],
      ],
      'classes' => [ 'tac' ],
      'is_wow'  => false,
    ] ); */?>

  </div>

  <?php $content = ob_get_clean();
get_template_part( 'template-parts/block/full', null, [
  'id'      => $id,
  'pad_t'   => $pad_t,
  'pad_b'   => $pad_b,
  'bg'      => $bg,
  'content' => $content,
  'classes' => [
    $class_name,
    ( $is_dark ? 'bg-dark' : 'bg-light' )
  ]
] );



