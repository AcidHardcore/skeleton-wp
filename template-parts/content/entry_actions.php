<?php
/**
 * Template part for displaying a post's comment and edit links
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

?>
<div class="entry-actions">
	<?php
	if ( ! is_singular( get_post_type() ) && ! post_password_required() && post_type_supports( get_post_type(), 'comments' ) && comments_open() ) {
		?>
		<span class="comments-link">
			<?php
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'skeleton-wp' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			?>
		</span>
		<?php
	}

	edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: post title */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'skeleton-wp' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		),
		'<span class="edit-link">',
		' </span>'
	);
	?>
</div><!-- .entry-actions -->
