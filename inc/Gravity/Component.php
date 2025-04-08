<?php
/**
 * Skeleton_WP\Skeleton_WP\Gravity\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Gravity;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'gravity';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
//    add_filter( 'gform_submit_button', array($this, 'contact_submit_button'), 10, 2 );
	}

  public function contact_submit_button( $button, $form ) {
    return "<button class='as-b' id='gform_submit_button_{$form['id']}'><span>Send</span></button>";
  }





}
