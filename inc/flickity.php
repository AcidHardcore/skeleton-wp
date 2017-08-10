<?php
function understrap_flickity() {

	$the_theme = wp_get_theme();
	if (is_front_page()) {
		wp_enqueue_style( 'flickity-css', 'https://unpkg.com/flickity@2/dist/flickity.min.css', array(), $the_theme->get( 'Version' ) );

		wp_enqueue_script( 'flickity-js', 'https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js', array(), $the_theme->get( 'Version' ), true );
	}
}