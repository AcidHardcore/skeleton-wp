<?php
/*
 *  Удаляем стили css-класса .recentcomments
 *
 * https://sheensay.ru/?p=2044
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'widgets_init', 'skeletonwp_remove_recent_comments_style' );

function skeletonwp_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array(
		$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
		'recent_comments_style'
	) );
}