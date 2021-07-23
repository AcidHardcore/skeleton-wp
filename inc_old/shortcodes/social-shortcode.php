<?php

add_shortcode( 'social', 'skeletonwp_social_shrortcode' );

function skeletonwp_social_shrortcode( $atts, $content, $tag ) {

	$social_links = carbon_get_theme_option( 'skeletonwp_social_urls' );

	$html = '<ul class="social">';
	foreach ( $social_links as $link ) {
		$html .= sprintf( '<li class="social__item">
                                   <i class="fa fa-%s"></i>
                                   <a class="social__link" href="%s" target="_blank">%s</a>
                                 </li>',
			$link['social-icon'],
			esc_url( $link['social-url'] ),
			esc_html( $link['social-name'] )
		);
	}
	$html .= '</ul>';

	return $html;
}
