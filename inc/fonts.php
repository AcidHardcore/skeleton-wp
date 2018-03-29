<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function skeletonwp_fonts_url() {
$fonts_url = '';

/**
* Translators: If there are characters in your language that are not
* supported by Source Sans Pro and PT Serif, translate this to 'off'. Do not translate
* into your own language.
*/
$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'skeletonwp' );
$pt_serif = _x( 'on', 'PT Serif font: on or off', 'skeletonwp' );

$font_families = array();

if ( 'off' !== $source_sans_pro ) {
$font_families[] = 'Source Sans Pro:400,400i,700,900';
}

if ( 'off' !== $pt_serif ) {
$font_families[] = 'PT Serif:400,400i,700,700i';
}


if ( in_array( 'on', array($source_sans_pro, $pt_serif) ) ) {

$query_args = array(
'family' => urlencode( implode( '|', $font_families ) ),
'subset' => urlencode( 'latin,cyrillic' ),
);

$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
}

return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function skeletonwp_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'skeletonwp-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'skeletonwp_resource_hints', 10, 2 );
