<?php
/**
 * Skeleton_WP\Skeleton_WP\Customizer\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Customizer;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use function Skeleton_WP\Skeleton_WP\skeleton_wp;
use WP_Customize_Manager;
use function add_action;
use function bloginfo;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;

/**
 * Class for managing Customizer integration.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'customizer';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register' ) );
		add_action( 'customize_preview_init', array( $this, 'action_enqueue_customize_preview_js' ) );
		add_action('wp_head', array($this, 'above_head'));
		add_action('wp_body_open', array($this, 'after_body'));
		add_action('wp_footer', array($this, 'above_body'));
	}

	/**
	 * Adds postMessage support for site title and description, plus a custom Theme Options section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => function() {
						bloginfo( 'name' );
					},
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => function() {
						bloginfo( 'description' );
					},
				)
			);
		}

		/**
		 * Theme options.
		 */
		$wp_customize->add_section(
			'theme_options',
			array(
				'title'    => __( 'Theme Options', 'skeleton-wp' ),
				'priority' => 120, // Before Custom Code.
			)
		);

		/**
		 * Sticky header
		 */
		$setting = 'sticky_header';

		$wp_customize->add_setting( $setting, [
			'default'            => true,
			'transport'          => 'postMessage'
		] );

		$wp_customize->add_control( $setting, [
			'section'  => 'theme_options',
			'label'    => 'Enable Sticky Header?',
			'type'     => 'checkbox'
		] );

		/**
		 * Custom code options.
		 */
		$wp_customize->add_section(
			'custom_code',
			array(
				'title'    => __( 'Custom Code', 'skeleton-wp' ),
				'priority' => 130, // Before Additional CSS.
			)
		);

		/**
		 * Custom code
		 */
		$setting = 'above_head';

		$wp_customize->add_setting( $setting, [
			'default'            => '',
			'transport'          => 'postMessage'
		] );

		$wp_customize->add_control( $setting, [
			'section'  => 'custom_code',
			'label'    => 'Add a custom code above the head close tag',
			'type'     => 'textarea'
		] );

		$setting = 'after_body';

		$wp_customize->add_setting( $setting, [
			'default'            => '',
			'transport'          => 'postMessage'
		] );

		$wp_customize->add_control( $setting, [
			'section'  => 'custom_code',
			'label'    => 'Add a custom code after the body open tag',
			'type'     => 'textarea'
		] );

		$setting = 'above_body';

		$wp_customize->add_setting( $setting, [
			'default'            => '',
			'transport'          => 'postMessage'
		] );

		$wp_customize->add_control( $setting, [
			'section'  => 'custom_code',
			'label'    => 'Add a custom code before the body close tag',
			'type'     => 'textarea'
		] );
	}

	/**
	 * Enqueues JavaScript to make Customizer preview reload changes asynchronously.
	 */
	public function action_enqueue_customize_preview_js() {
		wp_enqueue_script(
			'skeleton-wp-customizer',
			get_theme_file_uri( '/assets/js/customizer.min.js' ),
			array( 'customize-preview' ),
			skeleton_wp()->get_asset_version( get_theme_file_path( '/assets/js/customizer.min.js' ) ),
			true
		);
	}

	/**
	 * Add a custom code above the head close tag
	 */
	public function above_head() {
		$above_head = get_theme_mod('above_head');

		if($above_head) {
			echo $above_head;
		}
	}

	/**
	 * Add a custom code after the body open tag
	 */
	public function after_body() {
		$after_body = get_theme_mod('after_body');

		if($after_body) {
			echo $after_body;
		}
	}

	/**
	 * Add a custom code before the body close tag
	 */
	public function above_body() {
		$above_body = get_theme_mod('above_body');

		if($above_body) {
			echo $above_body;
		}
	}
}
