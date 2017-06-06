<?php
function understrap_fonts_url() {
$fonts_url = '';

/**
* Translators: If there are characters in your language that are not
* supported by Source Sans Pro and PT Serif, translate this to 'off'. Do not translate
* into your own language.
*/
$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'understrap' );
$pt_serif = _x( 'on', 'PT Serif font: on or off', 'understrap' );

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
'subset' => urlencode( 'latin' ),
);

$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
}

return esc_url_raw( $fonts_url );
}
