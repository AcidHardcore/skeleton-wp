<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 *Enable/Disable Woocommerce styles through theme options
 */
add_action( 'init', 'skeletonwp_disable_woo_sripts', 99 );

if ( ! function_exists( 'skeletonwp_disable_woo_sripts' ) ) {

	function skeletonwp_disable_woo_sripts() {

		if ( 0 == carbon_get_theme_option( 'skeletonwp_woo_scripts' ) ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		}

	}
}
//remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
//remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );