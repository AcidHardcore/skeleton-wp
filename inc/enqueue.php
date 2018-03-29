<?php
/**
 * skeletonwp enqueue scripts
 *
 * @package skeletonwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'skeletonwp_scripts' ) ) {
	/**
	 * Load theme's JavaScript sources.
	 */
	function skeletonwp_scripts() {
		// Get the theme data.
		$the_theme = wp_get_theme();
		if ( function_exists( 'skeletonwp_fonts_url' ) ) {
			// Enqueue Google Fonts: Source Sans Pro and PT Serif
			wp_enqueue_style( 'skeletonwp-fonts', skeletonwp_fonts_url() );
		}
		//main css file
		wp_enqueue_style( 'skeletonwp-styles', get_stylesheet_directory_uri() . '/css/style.min.css', array(), $the_theme->get( 'Version' ) );
		//font-awesome css
		wp_enqueue_style( 'skeletonwp-font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css', array(), $the_theme->get( 'Version' ) );
		//jquery of WordPress 1.12
//		wp_enqueue_script( 'jquery' );
		wp_deregister_script( 'jquery' );
		//main js file
		wp_enqueue_script( 'skeletonwp-scripts', get_template_directory_uri() . '/js/script.min.js', array(), $the_theme->get( 'Version' ), true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // endif function_exists( 'skeletonwp_scripts' ).

add_action( 'wp_enqueue_scripts', 'skeletonwp_scripts' );
