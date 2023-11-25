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

use function Skeleton_WP\Skeleton_WP\skeleton_wp;

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class_name = 'block-full articles articles--list block-full--bg-white';

if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

global $wp;
$current_url    = home_url( add_query_arg( array(), $wp->request ) );
$title          = get_field( 'title' );
$title_tag      = get_field( 'title_tag' );
$title_as_tag   = get_field( 'title__as_tag' );
$posts_per_page = get_field( 'posts_per_page' ) ?? 3;
$divider        = get_field( 'category_divider', 'option' );

$post_type     = get_field( 'post_type' );
$resource_type = '';
if ( $post_type === 'resources' ) {
	$resource_type = get_field( 'resource_type' );
}

$articles   = [];
$pagination = '';
$paged      = intval( $_REQUEST['pages'] ?? 1 );

$args = array(
	'fields'         => 'ids',
	'posts_per_page' => $posts_per_page,
	'post_status'    => 'publish',
	'post_type'      => $post_type,
	'orderby'        => [ 'date' => 'desc' ],
	'paged'          => $paged,
);

if ( $post_type === 'resources' || is_page( 'Resources' ) ) {

	$args['meta_key'] = 'weight';
	$args['orderby']  = [ 'meta_value_num' => 'DESC', 'date' => 'desc' ];

	if ( ! empty( $resource_type ) ) {
		$args['tax_query'][] = [
			'taxonomy' => 'resource-type',
			'field'    => 'slug',
			'terms'    => $resource_type
		];
	}

	if ( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ) {
		$args['post_type'] = 'resources';
		$args['s']         = $_REQUEST['search'];
	}

//	if ( isset( $_REQUEST['location'] ) && ! empty( $_REQUEST['location'] ) ) {
//		$args['post_type']   = 'resources';
//		$args['tax_query'][] = [
//			'taxonomy' => 'location',
//			'field'    => 'slug',
//			'terms'    => $_REQUEST['location']
//		];
//	}

	if ( isset( $_REQUEST['resource_type'] ) && ! empty( $_REQUEST['resource_type'] ) ) {
		$args['post_type']   = 'resources';
		$args['tax_query'][] = [
			'taxonomy' => 'resource-type',
			'field'    => 'slug',
			'terms'    => $_REQUEST['resource_type']
		];
	}

	if ( isset( $_REQUEST['industry'] ) && ! empty( $_REQUEST['industry'] ) ) {
		$args['post_type']   = 'resources';
		$args['tax_query'][] = [
			'taxonomy' => 'industry',
			'field'    => 'slug',
			'terms'    => $_REQUEST['industry']
		];
	}

	if ( isset( $_REQUEST['category'] ) && ! empty( $_REQUEST['category'] ) ) {
		$args['post_type']     = 'resources';
		$args['category_name'] = $_REQUEST['category'];
	}

}

if ( $post_type === 'product' || is_page( 'Products' ) ) {

	$page_id          = get_field( 'product_page', 'option' );
	$product_page_url = get_the_permalink( $page_id );

	$product_group = get_field( 'product_group' );
	if ( $product_group ) {
		$args['tax_query'][] = [
			'taxonomy' => 'group',
			'field'    => 'slug',
			'terms'    => $product_group
		];
	}

	if ( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ) {
		$args['post_type'] = 'product';
	}

	if ( isset( $_REQUEST['category'] ) && ! empty( $_REQUEST['category'] ) ) {
		$args['post_type'] = 'product';
		$args['tax_query']['relation'] = 'AND';
		foreach ( explode( ',', $_REQUEST['category'] ) as $item ) {
			$args['tax_query'][] = [
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $item
			];
		}
	}

	if ( isset( $_REQUEST['brand'] ) && ! empty( $_REQUEST['brand'] ) ) {
		$args['post_type'] = 'product';
		$args['tax_query']['relation'] = 'AND';
		foreach ( explode( ',', $_REQUEST['brand'] ) as $item ) {
			$args['tax_query'][] = [
				'taxonomy' => 'brand',
				'field'    => 'slug',
				'terms'    => $item
			];
		}
	}

	if ( isset( $_REQUEST['group'] ) && ! empty( $_REQUEST['group'] ) && ! $product_group ) {
		$args['post_type'] = 'product';
		$args['tax_query']['relation'] = 'AND';
		foreach ( explode( ',', $_REQUEST['group'] ) as $item ) {
			$args['tax_query'][] = [
				'taxonomy' => 'group',
				'field'    => 'slug',
				'terms'    => $item
			];
		}
	}

}

if ( $args['post_type'] ) {
	$query      = skeleton_wp()->handle_query( $args, $_REQUEST['search'], $args['post_type'] );
	$articles   = $query->posts;
	$pagination = skeleton_wp()->pagination( $query->max_num_pages, $paged, $current_url );
	$class_name .= ' block-full--pad';
}

if ( ! empty( $post_type ) ) {
	$args['post_type'] = $post_type;
}
if ( empty( $post_type ) && is_page( 'Resources' ) ) {
	$args['post_type'] = 'resources';
}
if ( empty( $post_type ) && is_page( 'Products' ) ) {
	$args['post_type'] = 'product';
}

$js_args                = $args;
$js_args['max_page']    = $query->max_num_pages ?? 0;
$js_args['current_url'] = $current_url;
$js_args['search']      = $_REQUEST['search'] ?? '';

//$js_args['location']    = $_REQUEST['location'] ?? '';

if ( $post_type == 'resources' || is_page( 'Resources' )) {
	$js_args['resource_type'] = $_REQUEST['resource_type'] ?? $resource_type;
	$js_args['industry']      = $_REQUEST['industry'] ?? '';
	$js_args['category']      = $_REQUEST['category'] ?? '';
}

if ( $post_type == 'product' || is_page( 'Products' )) {
	$js_args['category'] = isset( $_REQUEST['category'] ) ? explode( ',', $_REQUEST['category'] ) : [];
	$js_args['brand']    = isset( $_REQUEST['brand'] ) ? explode( ',', $_REQUEST['brand'] ) : [];
	$js_args['group']    = isset( $_REQUEST['group'] ) ? explode( ',', $_REQUEST['group'] ) : [];
}

wp_localize_script( 'skeleton-wp-load-more', 'args', $js_args );
wp_localize_script( 'skeleton-wp-filter-aside', 'args', $js_args );

?>

<?php if ( ! empty( $articles ) ) : ?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="block-full-content">
			<div class="wrapper">

				<?php get_template_part( 'template-parts/block/intro', null, [
					'text_align'   => 'tal',
					'title'        => $title,
					'title_tag'    => $title_tag,
					'title_as_tag' => $title_as_tag ?? 'h3',
					'classes'      => [ 'js-title' ]
				] ); ?>

				<div class="articles__wrap js-article-wrap">

					<div class="row">
						<?php $i = 0;

						foreach ( $articles as $i => $article ): ?>

							<?php if ( get_post_type( $article ) === 'resources' ) : ?>
								<?php
								$terms      = get_the_terms( $article, 'resource-type' );
								$term_slugs = wp_list_pluck( $terms, 'slug' )
								?>
								<?php if ( in_array( 'case-studies', $term_slugs ) ) : ?>

									<?php get_template_part( 'template-parts/block/case-study-teaser', null, [
										'term_id' => $article,
										'index'   => $i,
										'classes' => [ 'col-4' ],
										'sub_page'  => $resource_type
									] );
									?>

								<?php else : ?>

									<?php get_template_part( 'template-parts/block/article-teaser', null, [
										'term_id'   => $article,
										'index'     => $i,
										'classes'   => [ 'col-4' ],
										'divider'   => $divider,
										'wow_delay' => $i * 400,
										'sub_page'  => $resource_type
									] );
									?>

								<?php endif; ?>

							<?php endif; ?>

							<?php if ( $post_type === 'product' || is_page( 'Products' ) ) : ?>
								<?php get_template_part( 'template-parts/block/product-teaser', null, [
									'term_id' => $article,
									'index'   => $i,
									'classes' => [ 'col-3' ]
								] );
								?>
							<?php endif; ?>

							<?php $i ++; ?>
						<?php endforeach; ?>
					</div>

				</div>

				<div class="sep sep--hr js-sep">
					<hr class="wow wow--grow-left">
				</div>

				<?php if ( $query->found_posts > $posts_per_page && $pagination ) : ?>

					<nav class="pagination wow wow--fade-in">
						<?= $pagination ?>
					</nav>

				<?php endif; ?>

			</div>
		</div>
	</div>

<?php else : ?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="block-full-content">
			<div class="wrapper">

				<?php get_template_part( 'template-parts/block/intro', null, [
					'text_align' => 'tal',
					'title'      => $title,
					'title_tag'  => 'h3',
					'classes'    => [ 'js-title' ]
				] ); ?>

				<div class="articles__wrap js-article-wrap">
					<div class="row">
						<?php $i = 0;

						foreach ( $articles as $i => $article ):

							if ( $post_type === 'resources' || is_page( 'Resources' ) ) : ?>

								<?php if ( $resource_type === 'case-studies' ) : ?>
									<?php get_template_part( 'template-parts/block/case-study-teaser', null, [
										'term_id' => $article,
										'index'   => $i,
										'classes' => [ 'col-4' ]
									] );
									?>

								<?php else : ?>

									<?php get_template_part( 'template-parts/block/article-teaser', null, [
										'term_id'   => $article,
										'index'     => $i,
										'classes'   => [ 'col-4' ],
										'divider'   => $divider,
										'wow_delay' => $i * 400,
									] );
									?>

								<?php endif; ?>

							<?php endif; ?>

							<?php if ( $post_type === 'product' || is_page( 'Products' ) ) : ?>
							<?php get_template_part( 'template-parts/block/product-teaser', null, [
								'term_id' => $article,
								'index'   => $i,
								'classes' => [ 'col-3' ]
							] );
							?>
						<?php endif; ?>

							<?php $i ++; ?>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="sep sep--hr js-sep hidden">
					<hr class="wow wow--grow-left">
				</div>

				<nav class="pagination wow wow--fade-in">
					<?= $pagination ?>
				</nav>

			</div>
		</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>









