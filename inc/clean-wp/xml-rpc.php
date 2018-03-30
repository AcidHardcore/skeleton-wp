<?php
/*
 *  Удаляем опасные методы работы XML-RPC Pingback
 *
 *  https://sheensay.ru/?p=2044
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'xmlrpc_methods', 'sheensay_block_xmlrpc_attacks' );

function sheensay_block_xmlrpc_attacks( $methods ) {
	unset( $methods['pingback.ping'] );
	unset( $methods['pingback.extensions.getPingbacks'] );

	return $methods;
}

add_filter( 'wp_headers', 'sheensay_remove_x_pingback_header' );

function sheensay_remove_x_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );

	return $headers;
}