<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Theme Options', 'crb' ) )->add_fields( array(
		Field::make( 'text', 'crb_text', 'Text Field' ),
	) );