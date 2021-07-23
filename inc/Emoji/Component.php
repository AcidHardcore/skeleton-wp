<?php
/**
 * WP_Rig\WP_Rig\Emoji\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Emoji;

use WP_Rig\WP_Rig\Component_Interface;
use function add_action;
use function add_filter;

/**
 * Class for managing Emoji.
 *
 *
 * @link https://www.advancedcustomfields.com/resources/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'emoji';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action('init', [$this, 'disable_emoji_feature']);
		add_filter( 'option_use_smilies', '__return_false' );
	}


	function disable_emoji_feature() {
		// Prevent Emoji from loading on the front-end
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		// Remove from admin area also
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		// Remove from RSS feeds also
		remove_filter( 'the_content_feed', 'wp_staticize_emoji');
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji');

		// Remove from Embeds
		remove_filter( 'embed_head', 'print_emoji_detection_script' );

		// Remove from emails
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// Disable from TinyMCE editor. Currently disabled in block editor by default
		add_filter( 'tiny_mce_plugins', [$this, 'filter_disable_emojis_tinymce'] );

		/** Finally, prevent character conversion too
		 ** without this, emojis still work
		 ** if it is available on the user's device
		 */
		add_filter( 'option_use_smilies', '__return_false' );
	}

	public function filter_disable_emojis_tinymce( $plugins ) {
		if( is_array($plugins) ) {
			$plugins = array_diff( $plugins, array( 'wpemoji' ) );
		}
		return $plugins;
	}
}
