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

$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

$class_name = 'block-full articles-filter-aside';

if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$post_type       = get_field( 'post_type' );
$background_type = get_field( 'background_type' );
if ( ! empty( $background_type ) ) {
	$class_name .= ' block-full--bg-' . $background_type;
}

$search_reset_class = 'search-reset js-reset';
if ( ! isset( $_REQUEST['search'] ) ) {
	$search_reset_class .= ' hidden';
}

$args = array(
	'fields'         => 'ids',
	'posts_per_page' => - 1,
	'post_status'    => 'publish',
	'post_type'      => $post_type,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( $post_type === 'product' ) {

	$product_categories = skeleton_wp()->get_category_hierarchy();
	$brands             = get_terms(
		[
			'taxonomy'   => 'brand',
			'hide_empty' => true,
		] );
	$groups             = get_terms(
		[
			'taxonomy'   => 'group',
			'hide_empty' => true,
		] );

	if ( isset( $_REQUEST['category'] ) && ! empty( $_REQUEST['category'] ) ) {
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
		$args['tax_query']['relation'] = 'AND';
		foreach ( explode( ',', $_REQUEST['brand'] ) as $item ) {
			$args['tax_query'][] = [
				'taxonomy' => 'brand',
				'field'    => 'slug',
				'terms'    => $item
			];
		}
	}

	if ( isset( $_REQUEST['group'] ) && ! empty( $_REQUEST['group'] ) ) {
		$args['tax_query']['relation'] = 'AND';
		foreach ( explode( ',', $_REQUEST['group'] ) as $item ) {
			$args['tax_query'][] = [
				'taxonomy' => 'group',
				'field'    => 'slug',
				'terms'    => $item
			];
		}
	}

	if ( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ) {
		$args['meta_query'][] = [
			'key'     => '_sku',
			'value'   => $_REQUEST['search'],
			'compare' => 'LIKE'
		];
	}

	global $post;
	if ( wp_get_post_parent_id() !== 0 ) {

		$args['tax_query'][] = [
			'taxonomy' => 'group',
			'field'    => 'slug',
			'terms'    => $post->post_name
		];
	}

	$brands_not_empty             = skeleton_wp()->get_not_empty_filters( $args, 'brand', 'slug' );
	$groups_not_empty             = skeleton_wp()->get_not_empty_filters( $args, 'group', 'slug' );
	$product_categories_not_empty = skeleton_wp()->get_not_empty_filters( $args, 'product_cat', 'slug' );
}

?>

<div id="content-anchor"></div>
<!--<div class="phantom"></div>-->
<aside <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
	<div class="block-full-content">

		<div class="gform_wrapper gravity-theme gform_wrapper__aside">
			<div class="wrapper">
				<div class="js-form form-custom">
					<div class="row">

						<div class="col-12">
							<div class="form-item form-item-search">
								<input class="js-search" type="text" id="s" name="s" placeholder="<?= $post_type === 'product' ? 'Search by Part #…' : 'Search' ?>"
									   value="<?= esc_attr( $_REQUEST['search'] ?? '' ) ?>" autocomplete="off">
								<svg class="search-spinner hidden" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
<path transform-origin="32 32" fill="#B08C3C" d="M29.3,3.8v22.9L24,32l5.3,5.3v22.9h-5.6V38.8L10.3,52l-4-4l13.4-13.2H2v-5.6h17.7L6.3,16l4-4l13.3,13.2V3.8
	H29.3z M40.4,3.8v21.4L53.7,12l4,4L44.4,29.2H62v5.6H44.4L57.7,48l-4,4L40.4,38.8v21.4h-5.6V37.3l5.3-5.3l-5.3-5.3V3.8H40.4z">
	<animateTransform attributeName="transform"
					  type="rotate"
					  values="0;359"
					  begin="0"
					  dur="5s"
					  repeatCount="indefinite"
					  fill="freeze"
					  calcMode="linear"
	></animateTransform>
</path>
</svg>
								<button class="<?= esc_attr( $search_reset_class ) ?>">
									<span class="visually-hidden">Reset</span>
								</button>
							</div>
						</div>

						<?php if ( $post_type === 'product' && ! empty( $brands ) ): ?>
							<div class="col-12">
								<div class="form-item js-filter">
									<?php ob_start(); ?>
									<?php foreach ( $brands as $brand ): ?>
										<label class="check option">
											<input class="check__input" type="checkbox" data-filter="brand"
												   data-slug="<?= esc_attr( $brand->slug ?? '' ) ?>"

												<?php checked( isset( $_REQUEST['brand'] ) && strstr( $_REQUEST['brand'], $brand->slug ), true, true ) ?>
												<?php disabled( in_array( $brand->slug, $brands_not_empty ), false, true ) ?>
											>
											<span class="check__box"></span>
											<span class="check__text"><?= esc_html( $brand->name ?? '' ) ?></span>
										</label>
									<?php endforeach; ?>
									<?php $collapser_content = ob_get_clean(); ?>

									<?php get_template_part( 'template-parts/block/collapser', null, [
										'is_open'   => true,
										'title_tag' => 'h5',
										'group'     => 'aside-1',
										'title'     => 'Brand',
										'content'   => $collapser_content,
										'classes'   => [ 'collapser--aside' ],
									] ); ?>


								</div>
							</div>

						<?php endif; ?>

						<?php if ( $post_type === 'product' && ! empty( $groups ) ): ?>
							<div class="col-12">
								<div class="form-item js-filter">

									<?php ob_start(); ?>
									<?php foreach ( $groups as $group ): ?>
										<label class="check option">
											<input class="check__input" type="checkbox" data-filter="group"
												   data-slug="<?= esc_attr( $group->slug ?? '' ) ?>"
												<?php checked( $post->post_name === $group->slug || isset( $_REQUEST['group'] ) && strstr( $_REQUEST['group'], $group->slug ), true, true ) ?>
												<?php disabled( $post->post_name === $group->slug || in_array( $group->slug, $groups_not_empty ), false, true ) ?>
											>
											<span class="check__box"></span>
											<span class="check__text"><?= esc_html( $group->name ?? '' ) ?></span>
										</label>
									<?php endforeach; ?>
									<?php $collapser_content = ob_get_clean(); ?>

									<?php get_template_part( 'template-parts/block/collapser', null, [
										'is_open'   => true,
										'group'     => 'aside-2',
										'title_tag' => 'h5',
										'title'     => 'Category',
										'content'   => $collapser_content,
										'classes'   => [ 'collapser--aside' ],
									] ); ?>

								</div>
							</div>
						<?php endif; ?>

						<?php if ( $post_type === 'product' && ! empty( $product_categories ) ): ?>
							<div class="col-12">
								<div class="form-item js-filter">

									<?php ob_start(); ?>
									<?php foreach ( $product_categories as $category ): ?>
										<label class="check option level-1">
											<input class="check__input" type="checkbox" data-filter="category"
												   data-slug="<?= esc_attr( $category['category']->slug ?? '' ) ?>"
												<?php checked( isset( $_REQUEST['category'] ) && strstr( $_REQUEST['category'], $category['category']->slug ), true, true ) ?>
												<?php disabled( in_array( $category['category']->slug, $product_categories_not_empty ), false, true ) ?>
											>
											<span class="check__box"></span>
											<span class="check__text"><?= esc_html( $category['category']->name ?? '' ) ?></span>
										</label>

										<?php if ( ! empty( $category['children'] ) ) : ?>
											<?php foreach ( $category['children'] as $category_2 ): ?>
												<label class="check option level-2">
													<input class="check__input" type="checkbox" data-filter="category"
														   data-slug="<?= esc_attr( $category_2['category']->slug ?? '' ) ?>"
														<?php checked( isset( $_REQUEST['category'] ) && strstr( $_REQUEST['category'], $category_2['category']->slug ), true, true ) ?>
														<?php disabled( in_array( $category_2['category']->slug, $product_categories_not_empty ), false, true ) ?>
													>
													<span class="check__box"></span>
													<span class="check__text"><?= esc_html( $category_2['category']->name ?? '' ) ?></span>
												</label>

												<?php if ( ! empty( $category_2['children'] ) ) : ?>
													<?php foreach ( $category_2['children'] as $category_3 ): ?>
														<label class="check option level-3">
															<input class="check__input" type="checkbox" data-filter="category"
																   data-slug="<?= esc_attr( $category_3['category']->slug ?? '' ) ?>"
																<?php checked( isset( $_REQUEST['category'] ) && strstr( $_REQUEST['category'], $category_3['category']->slug ), true, true ) ?>
																<?php disabled( in_array( $category_3['category']->slug, $product_categories_not_empty ), false, true ) ?>
															>
															<span class="check__box"></span>
															<span class="check__text"><?= esc_html( $category_3['category']->name ?? '' ) ?></span>
														</label>
													<?php endforeach; ?>
												<?php endif; ?>

											<?php endforeach; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<?php $collapser_content = ob_get_clean(); ?>

									<?php get_template_part( 'template-parts/block/collapser', null, [
										'is_open'   => true,
										'group'     => 'aside-3',
										'title_tag' => 'h5',
										'title'     => 'Product Type',
										'content'   => $collapser_content,
										'classes'   => [ 'collapser--aside' ],
									] ); ?>

								</div>
							</div>
						<?php endif; ?>

					</div>

				</div>
			</div>
		</div>


	</div>
</aside>




