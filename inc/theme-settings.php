<?php
/**
 * Check and setup theme's default settings
 *
 * @package skeletonwp
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'setup_theme_default_settings' ) ) :
	function setup_theme_default_settings() {

		// check if settings are set, if not set defaults.
		// Caution: DO NOT check existence using === always check with == .
		// Latest blog posts style.
		$skeletonwp_posts_index_style = get_theme_mod( 'skeletonwp_posts_index_style' );
		if ( '' == $skeletonwp_posts_index_style ) {
			set_theme_mod( 'skeletonwp_posts_index_style', 'default' );
		}

		// Sidebar position.
		$skeletonwp_sidebar_position = get_theme_mod( 'skeletonwp_sidebar_position' );
		if ( '' == $skeletonwp_sidebar_position ) {
			set_theme_mod( 'skeletonwp_sidebar_position', 'right' );
		}

		// Container width.
		$skeletonwp_container_type = get_theme_mod( 'skeletonwp_container_type' );
		if ( '' == $skeletonwp_container_type ) {
			set_theme_mod( 'skeletonwp_container_type', 'container' );
		}
	}
endif;
