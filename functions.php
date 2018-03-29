<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load functions to secure your WP install.
 */
require get_template_directory() . '/inc/security.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Comments file.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
//require get_template_directory() . '/inc/bootstrap-wp-navwalker.php';
require get_template_directory() . '/inc/bem_wp-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require get_template_directory() . '/inc/editor.php';
/**
 * Load Font functions.
 */
//require get_template_directory() . '/inc/fonts.php';
/**
 * Custom Header Underscore.
 */
require get_template_directory() . '/inc/custom-header.php';
/**
 * Disable wpautop.
 */
require get_template_directory() . '/inc/disable-wpautop.php';
/**
 * Optimisation
 */
require get_template_directory() . '/inc/optimisation.php';
/**
 * Help functions
 */
require get_template_directory() . '/inc/helper.php';
/**
 *
 * CLEANING
 *
 */

/** Remove amoji.
 */
require get_template_directory() . '/inc/amoji.php';
/**
 * Remove rest-api.
 */
require get_template_directory() . '/inc/rest-api.php';
/**
 * Remove xml-rpc.
 */
require get_template_directory() . '/inc/xml-rpc.php';
/**
 * Remove pingback, canonical, meta generator, wlwmanifest, EditURI, shortlink, prev, next, RSS, feed, profile from head
 */
require get_template_directory() . '/inc/remove-other.php';
/**
 * Remove recentcomments
 */
require get_template_directory() . '/inc/recentcomments.php';
