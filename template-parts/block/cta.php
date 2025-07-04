<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$link     = $args['cta'];
if ( $link ) {
  $link_url    = $link['url'];
  $link_title  = $link['title'];
  $link_target = $link['target'] ? $link['target'] : '_self';
}
$classes  = $args['classes'] ?? [];
$type     = $args['type'] ?? '';
$position = $args['position'] ?? '';
$attr = $args['attrs'] ?? [];

if ( $type != 'link' ) {
  array_unshift( $classes, 'as-b' );
}
if ( $type ) {
  $classes[] = 'as-b--' . $type;
}
if ( $type == 'arrow' ) {
  $classes = [ 'cta-arrow' ];
}
if ( $type == 'arrow-back' ) {
  $classes = [ 'cta-arrow', 'cta-arrow--back' ];
}

if ( $type == 'modal' ) {
  $classes[] =  'js-pop-open-pdf';
}

if ( $position ) {
  $classes[] = 'as-b--' . $position;
}

if ( $link ): ?>
  <a href="<?php echo esc_url( $link_url ); ?>"
     class="<?= join( ' ', $classes ) ?>"
     target="<?php echo esc_attr( $link_target ); ?>"
    <?= skeleton_wp()->handle_attributes($attr) ?>
  >
    <?php echo esc_html( $link_title ); ?></a>
<?php endif; ?>
