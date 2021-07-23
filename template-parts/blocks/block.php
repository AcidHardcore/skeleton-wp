<?php
/**
 * Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
namespace WP_Rig\WP_Rig;

// Create id attribute allowing for custom "anchor" value.
$id = 'block-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' block';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$title            = get_field( 'title' );
$content          = get_field( 'content' );

$link = get_field( 'link' );
if ( $link ):
	$link_url    = trim($link['url']);
	$link_title  = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';
endif;

?>

<section id="<?= esc_attr( $id ); ?>" class="block <?= esc_attr( $className ); ?>">
	<div class="block-content">

			<?php if ( $title ): ?>
				<h1 class="">
					<?php echo wp_kses_post( $title ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( $content ): ?>
				<div class="">
					<?php echo wp_kses_post( $content ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $link ): ?>
				<div class="block-actions">
					<a class="btn" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
						<?php echo esc_html( $link_title ); ?>
					</a>
				</div>
			<?php endif; ?>

	</div>
</section>

