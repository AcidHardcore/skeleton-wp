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

$class_name = 'block-full articles-filter ';

if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$show_filters    = get_field( 'show_filters' );
$post_type       = get_field( 'post_type' );
$background_type = get_field( 'background_type' );
if ( ! empty( $background_type ) ) {
	$class_name .= 'block-full--bg-' . $background_type;
}

$search_reset_class = 'search-reset js-reset';
if ( ! isset( $_REQUEST['search'] ) ) {
	$search_reset_class .= ' hidden';
}

if ( $show_filters ) {
	$args = array(
		'fields'         => 'ids',
		'posts_per_page' => - 1,
		'post_status'    => 'publish',
		'post_type'      => $post_type,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$category_ids = skeleton_wp()->get_not_empty_filters( $args, 'category', 'term_id' );
	$categories   = [];

	if ( $category_ids ) {
		foreach ( $category_ids as $cat_id ) {
			if($cat_id !== 1) { //uncategorized
				$categories[] = get_term( $cat_id );
			}
		}
	}

	$types = get_terms(
		[
			'taxonomy'   => 'resource-type',
			'hide_empty' => true,
		] );

	$industries = get_terms(
		[
			'taxonomy'   => 'industry',
			'hide_empty' => true,
		] );

	$type_ids_not_empty = [];
	$industry_slugs_not_empty = [];
	$category_not_empty = [];

	if ( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ) {
		$args['s'] = $_REQUEST['search'];
		//	$args['relevanssi'] = true;
	}

	if ( isset( $_REQUEST['resource_type'] ) && ! empty( $_REQUEST['resource_type'] ) ) {
		$args['tax_query'][] = [
			'taxonomy' => 'resource-type',
			'field'    => 'slug',
			'terms'    => $_REQUEST['resource_type']
		];
	}

	if ( isset( $_REQUEST['industry'] ) && ! empty( $_REQUEST['industry'] ) ) {
		$args['tax_query'][] = [
			'taxonomy' => 'industry',
			'field'    => 'slug',
			'terms'    => $_REQUEST['industry']
		];
	}

	if ( isset( $_REQUEST['category'] ) && ! empty( $_REQUEST['category'] ) ) {
		$args['category_name'] = $_REQUEST['category'];
	}

	$type_slugs_not_empty = skeleton_wp()->get_not_empty_filters( $args, 'resource-type', 'slug' );
	$industry_slugs_not_empty = skeleton_wp()->get_not_empty_filters( $args, 'industry', 'slug' );
	$category_slugs_not_empty = skeleton_wp()->get_not_empty_filters( $args, 'category', 'slug' );

}

global $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) ); ?>

<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
	<div class="block-full-content">

		<?php if ( $post_type == 'resources' ) : ?>

			<div class="gform_wrapper gravity-theme gform_wrapper__resources">
				<div class="wrapper">
					<div class="js-form form-custom">
						<div class="row">
							<div class="col-auto">
								<div class="form-item form-item-search">
									<input class="js-search" type="text" id="s" name="s" placeholder="Search" value="<?= esc_attr( $_REQUEST['search'] ?? '' ) ?>" autocomplete="off">
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
									<?php if ( ! $show_filters ) : ?>
										<button class="<?= esc_attr( $search_reset_class ) ?>">
											<span class="visually-hidden">Reset</span>
										</button>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<?php if ( $show_filters ) : ?>
							<div class="row">

								<?php if ( $categories ): ?>
									<div class="col-auto">
										<div class="form-item">
											<select name="category" class="js-filter" data-filter="category" autocomplete="off">
												<option value="">Select a Topic…</option>
												<?php foreach ( $categories as $category ): ?>
													<option
														value="<?php echo $category->slug; ?>"
														<?php selected( isset( $_REQUEST['category'] ) && $_REQUEST['category'] === $category->slug, true, true ) ?>
														<?php disabled( in_array($category->slug, $category_slugs_not_empty ) , false, true )?>
													>
														<?= $category->name ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( $industries ): ?>
									<div class="col-auto">
										<div class="form-item">
											<select name="industry" class="js-filter" data-filter="industry" autocomplete="off">
												<option value="">Select an Industry…</option>
												<?php foreach ( $industries as $industry ): ?>
													<option
														value="<?php echo $industry->slug; ?>"
														<?php selected( isset( $_REQUEST['industry'] ) && $_REQUEST['industry'] === $industry->slug, true, true ) ?>
														<?php disabled( in_array($industry->slug, $industry_slugs_not_empty ) , false, true )?>
													>
														<?= $industry->name ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( $types ): ?>
									<div class="col-auto">
										<div class="form-item">
											<select name="type" class="js-filter" data-filter="resource_type" autocomplete="off">
												<option value="">Select a Content Type…</option>
												<?php foreach ( $types as $type ): ?>
													<option
														value="<?php echo $type->slug; ?>"
														<?php selected( isset( $_REQUEST['resource_type'] ) && $_REQUEST['resource_type'] === $type->slug, true, true ) ?>
														<?php disabled(in_array($type->slug, $type_slugs_not_empty ) , false, true )?>
													>
														<?= $type->name ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								<?php endif; ?>

							</div>

							<div class="row--aic">
								<div class="col-auto text-center">
									<a class="as-b as-b--link js-reset" href="<?= esc_url( $current_url ) ?>">Reset All Filters</a>
								</div>
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>

		<?php endif; ?>
	</div>
</div>




