<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Carbon_Fields\Container;
use Carbon_Fields\Field;

//About Theme option container: https://carbonfields.net/docs/containers-theme-options/?crb_version=2-2-0
Container::make( 'theme_options', __( 'Theme Options', 'skeletonwp' ) )
	->add_tab( 'Copyright', array(
		Field::make( 'text', 'skeletonwp_footer_copyright', 'Copyright' )
		     ->set_help_text('Enter your copyright'),
	) )
	->add_tab( 'Social', array(
		Field::make( 'complex', 'skeletonwp_social_urls', 'Social Links' )
			->set_layout( 'grid' ) // https://carbonfields.net/docs/complex-field-config-methods/?crb_version=2-2-0
			->set_help_text('Use by Social shortcode')
		     ->add_fields( array(
			     Field::make( 'text', 'social-name', 'Social name' )
			          ->set_width( 25 ) // condense layout so field takes only 50% of the available width
			          ->set_required(),
			     Field::make( 'text', 'social-icon', 'social-icon' ) // We're only changing the label field to an image one
			          ->set_width( 25 )
				      ->set_help_text('Add some font awesome icon'),
				 Field::make( 'image', 'social-image', 'Social image' )
				     ->set_width( 25 )
					 ->set_help_text('or Add some image'),
			     Field::make( 'text', 'social-url', 'Social URL' )
			          ->set_width( 25 )
			          ->set_required(),
		     ) ),
	) )
	->add_tab('Scripts',array(
			Field::make( 'header_scripts', 'skeletonwp_header_script' )
				->set_hook('wp_head', 99),
		Field::make( 'footer_scripts', 'skeletonwp_footer_script' )
		     ->set_hook('wp_footer', 99),
		    ));


//$basic_options_container = Container::make( 'theme_options', 'Basic Options' )
//                                    ->add_fields( array(
//	                                    Field::make( 'header_scripts', 'crb_header_script' ),
//	                                    Field::make( 'footer_scripts', 'crb_footer_script' ),
//                                    ) );
//
//// Add second options page under 'Basic Options'
//Container::make( 'theme_options', 'Social Links' )
//         ->set_page_parent( $basic_options_container ) // reference to a top level container
//         ->add_fields( array(
//		Field::make( 'text', 'crb_facebook_link' ),
//		Field::make( 'text', 'crb_twitter_link' ),
//	) );
//
//// Add third options page under "Appearance"
//Container::make( 'theme_options', 'Customize Background' )
//         ->set_page_parent( 'themes.php' ) // identificator of the "Appearance" admin section
//         ->add_fields( array(
//		Field::make( 'color', 'crb_background_color' ),
//		Field::make( 'image', 'crb_background_image' ),
//	) );