<?php
/**
 * @var array $args
 */
namespace Skeleton_WP\Skeleton_WP;

$link     = $args['cta'];
$video_id = null;
if ( $link ) {
  $link_url    = $link['url'];
  $link_title  = $link['title'];
  $link_target = $link['target'] ? $link['target'] : '_self';
  $video_id    = skeleton_wp()->youTubeURLGetId( $link_url );
}
$classes  = $args['classes'] ?? [];
$type     = $args['type'] ?? '';
$position = $args['position'] ?? '';

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

if ( $video_id ) {
  $classes[] = 'js-pop-video';
}

if ( $position ) {
  $classes[] = 'as-b--' . $position;
}

if ( $link ): ?>
  <a href="<?php echo esc_url( $link_url ); ?>"
     class="<?= join( ' ', $classes ) ?>"
     target="<?php echo esc_attr( $link_target ); ?>"
    <?= $video_id ? 'data-video-id="'.$video_id.'"' : '' ?>
  >
    <?php echo esc_html( $link_title ); ?></a>
<?php endif; ?>
