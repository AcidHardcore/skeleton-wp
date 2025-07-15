<?php
/**
 * Template part for displaying a pagination
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

$links = $args['links'] ?? null;

if ( ! empty( $links ) ) : ?>

  <ul class="pagination" aria-label="<?php esc_attr_e( 'Pagination', 'skeleton_wp' ); ?>">
    <?php foreach ( $links as $link ) : ?>
      <li class="pagination__item">
        <?php if ( $link->is_dots ) : ?>
          <span><?= $link->link_text ?></span>
        <?php elseif ( $link->is_current ) : ?>
          <strong class="pagination__current"><?= $link->link_text ?></strong>
        <?php else : ?>
          <a class="pagination__link" href="<?= esc_attr( $link->url ) ?>"><?= wp_kses_post( $link->link_text ) ?></a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>

<?php endif; ?>
