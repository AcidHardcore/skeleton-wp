<?php
/*
 *  Удаляем стили css-класса .recentcomments
 *
 * https://sheensay.ru/?p=2044
 */
add_action( 'widgets_init', 'sheensay_remove_recent_comments_style' );

function sheensay_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array(
		$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
		'recent_comments_style'
	) );
}