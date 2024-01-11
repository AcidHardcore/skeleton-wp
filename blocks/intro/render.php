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

$classes = '';
if ( ! empty( $block['className'] ) ) {
  $classes .= ' ' . $block['className'];
}

$title_pre     = get_field( 'title_pre' );
$title         = get_field( 'title' );
$title_tag     = get_field( 'title_tag' ) ?? 'h2';
$title_as_tag  = get_field( 'title_as_tag' );
$font          = get_field( 'font' );
$font_weight   = get_field( 'font_weight' );
$title_classes = get_field( 'title_classes' ) ?? '';
$html_size     = get_field( 'html_size' );
$html          = get_field( 'html' ) ?? '';
$cta           = get_field( 'cta' ) ?? [];
$is_wow        = get_field( 'is_wow' );

$classes .= ' intro';
if($font) {
  $title_classes .= ' ' . $font;
}

if($font_weight) {
  $title_classes .= ' ' . $font_weight;
}

global $intro_wrap_i;
$intro_wrap_i = $intro_wrap_i ?? 0;
$intro_wrap_i ++;
$id = 'intro-wrap-' . $intro_wrap_i;

if ( $title_pre || $title || $html || $cta ) : ?>
  <div id="<?= $id ?>" class="<?= esc_attr( $classes ) ?>">

    <?php get_template_part( 'template-parts/block/title', null, [
      'title'    => $title_pre,
      'tag'      => 'div',
      'as_tag'   => 'h5',
      'is_wow'   => $is_wow,
      'wow_sync' => $id,
    ] ); ?>

    <?php get_template_part( 'template-parts/block/title', null, [
      'title'     => $title,
      'tag'       => $title_tag,
      'as_tag'    => $title_as_tag,
      'classes'   => [ $title_classes ],
      'is_wow'    => $is_wow,
      'wow_sync'  => $id,
      'wow_delay' => $title_pre ? 150 : 0,
    ] ); ?>

    <?php get_template_part( 'template-parts/block/editor', null, [
      'html'      => $html,
      'size'      => $html_size,
      'is_wow'    => $is_wow,
      'wow_delay' => ( $title_pre ? 2 : 1 ) * 150,
      'wow_sync'  => $id,
    ] ); ?>

    <?php if ( $cta ) : ?>
      <?php get_template_part( 'template-parts/block/actions', null, [
        'ctas'      => [
          [
            'cta' => $cta,
          ]
        ],
        'is_wow'    => $is_wow,
        'wow_delay' => ( $title_pre ? 3 : 2 ) * 150,
        'wow_sync'  => $id,
      ] ); ?>
    <?php endif; ?>

    <div><!-- last --></div>

  </div>
<?php endif;








