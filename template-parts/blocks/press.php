<?php
/**
 * Press Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'press-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = ' block--press';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$bg        = get_field( 'bg' ) ? get_field( 'bg' ) : 'white';
$className .= ' ' . $bg;
$title     = get_field( 'title' );
$subtitle  = get_field( 'subtitle' );


$press_per_page = get_field( 'press_per_page' );
$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args           = array(
	'post_type'      => 'press',
	'post_status'    => 'publish',
	'posts_per_page' => $press_per_page,
	'order'          => 'DESC',
	'orderby'        => 'date',
	'paged'          => $paged
);
$query          = new WP_Query( $args );

//pagination
$js_args = $args;
global $wp;
$js_args['current_url'] = home_url( add_query_arg( array(), $wp->request ) );

$total_pages = $query->max_num_pages;

if ( $total_pages > 1 ) {
	
	$current_page = max( 1, get_query_var( 'paged' ) );
	
	$args = wp_parse_args(
		$args,
		array(
			'mid_size'           => 2,
			'show_all'           => true,
			'prev_next'          => true,
			'prev_text'          => __( 'Previous', 'understrap' ),
			'next_text'          => __( 'Next', 'understrap' ),
			'screen_reader_text' => __( 'Posts navigation', 'understrap' ),
			'type'               => 'array',
			'current'            => $current_page,
			'total'              => $total_pages,
		)
	);
	
	$links = paginate_links( $args );
}

$is_hidden = get_field( 'is_hidden' );
$show = $is_hidden ? current_user_can( 'edit_posts' ) : true;
?>
<?php if ( $show ) : ?>

	<div id="<?= esc_attr( $id ); ?>" class="block-full js-scroll block-full-pad <?= esc_attr( $className ); ?>">

		<div class="block-content">
			<div class="wrapper wrapper-m30">
		  
		  <?php if ( $title ) : ?>
						<h2 class="as-h5 tac not-bold"><?= esc_html( $title ) ?></h2>
		  <?php endif; ?>
		  
		  <?php if ( $subtitle ) : ?>
						<h3 class="as-h2 tac"><?= esc_html( $subtitle ) ?></h3>
		  <?php endif; ?>
		  
		  <?php if ( $query->have_posts() ) : ?>
						<div class="press-wrap">
				<?php while ( $query->have_posts() ): $query->the_post(); ?>
					<?php get_template_part( 'loop-templates/content', 'press' ); ?>
				<?php endwhile; ?>
						</div>
		  <?php endif; ?>
		  <?php wp_reset_postdata(); ?>
		  
		  <?php if ( $total_pages > 1 ) : ?>
						<nav class="navigation" aria-label="<?php echo $args['screen_reader_text']; ?>" data-args='<?= json_encode( $js_args, JSON_NUMERIC_CHECK ) ?>'>
							<ul>
				  
				  <?php
				  foreach ( $links as $key => $link ) {
					  ?>
										<li class="<?php echo strpos( $link, 'current' ) ? 'active' : ''; ?>">
						<?php echo str_replace( 'page-numbers', 'page-link', $link ); ?>
										</li>
					  <?php
				  }
				  ?>

							</ul>
						</nav>
		  <?php endif; ?>

			</div>
		</div>
	</div>
<?php endif; ?>





