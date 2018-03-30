<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Theme Options', 'skeletonwp' ) )
	->add_tab( 'Copyright', array(
		Field::make( 'text', 'skeletonwp_footer_copyright', 'Copyright' )
		     ->set_help_text('Enter your copyright'),
	) )
	->add_tab( 'Social', array(
		Field::make( 'complex', 'skeletonwp_social_urls', 'Social Links' )
		     ->add_fields( array(
			     Field::make( 'text', 'social-name', 'Social name' )
			          ->set_width( 33 ) // condense layout so field takes only 50% of the available width
			          ->set_required(),
			     Field::make( 'text', 'social-icon', 'social-icon' ) // We're only changing the label field to an image one
			          ->set_width( 33 )
				     ->set_help_text('Add some font awesome icon')
			          ->set_required(),
			     Field::make( 'text', 'soical-url', 'Social URL' )
			          ->set_width( 33 )
			          ->set_required(),
		     ) ),
	) )
	->add_tab('Header scripts',array(
			Field::make( 'header_scripts', 'skeletonwp_header_script' )
				->set_hook('wp_head', 99),
		    ))
	->add_tab('Footer scripts',array(
			Field::make( 'footer_scripts', 'skeletonwp_footer_script' )
			     ->set_hook('wp_footer', 99),

    ))
;
