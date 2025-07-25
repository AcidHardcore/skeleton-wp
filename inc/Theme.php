<?php
/**
 * Skeleton_WP\Skeleton_WP\Theme class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

use InvalidArgumentException;
use function is_plugin_active;

/**
 * Main class for the theme.
 *
 * This class takes care of initializing theme features and available template tags.
 */
class Theme {

	/**
	 * Associative array of theme components, keyed by their slug.
	 *
	 * @var array
	 */
	protected $components = array();

	/**
	 * The template tags instance, providing access to all available template tags.
	 *
	 * @var Template_Tags
	 */
	protected $template_tags;

	/**
	 * Constructor.
	 *
	 * Sets the theme components.
	 *
	 * @param array $components Optional. List of theme components. Only intended for custom initialization, typically
	 *                          the theme components are declared by the theme itself. Each theme component must
	 *                          implement the Component_Interface interface.
	 *
	 * @throws InvalidArgumentException Thrown if one of the $components does not implement Component_Interface.
	 */
	public function __construct( array $components = array() ) {
		if ( empty( $components ) ) {
			$components = $this->get_default_components();
		}

		// Set the components.
		foreach ( $components as $component ) {

			// Bail if a component is invalid.
			if ( ! $component instanceof Component_Interface ) {
				throw new InvalidArgumentException(
					sprintf(
						/* translators: 1: classname/type of the variable, 2: interface name */
						__( 'The theme component %1$s does not implement the %2$s interface.', 'skeleton-wp' ),
						gettype( $component ),
						Component_Interface::class
					)
				);
			}

			$this->components[ $component->get_slug() ] = $component;
		}

		// Instantiate the template tags instance for all theme templating components.
		$this->template_tags = new Template_Tags(
			array_filter(
				$this->components,
				function( Component_Interface $component ) {
					return $component instanceof Templating_Component_Interface;
				}
			)
		);
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 *
	 * This method must only be called once in the request lifecycle.
	 */
	public function initialize() {
		array_walk(
			$this->components,
			function( Component_Interface $component ) {
				$component->initialize();
			}
		);
	}

	/**
	 * Retrieves the template tags instance, the entry point exposing template tag methods.
	 *
	 * Calling `skeleton_wp()` is a short-hand for calling this method on the main theme instance. The instance then allows
	 * for actual template tag methods to be called. For example, if there is a template tag called `posted_on`, it can
	 * be accessed via `skeleton_wp()->posted_on()`.
	 *
	 * @return Template_Tags Template tags instance.
	 */
	public function template_tags() : Template_Tags {
		return $this->template_tags;
	}

	/**
	 * Retrieves the component for a given slug.
	 *
	 * This should typically not be used from outside of the theme classes infrastructure.
	 *
	 * @param string $slug Slug identifying the component.
	 * @return Component_Interface Component for the slug.
	 *
	 * @throws InvalidArgumentException Thrown when no theme component with the given slug exists.
	 */
	public function component( string $slug ) : Component_Interface {
		if ( ! isset( $this->components[ $slug ] ) ) {
			throw new InvalidArgumentException(
				sprintf(
					/* translators: %s: slug */
					__( 'No theme component with the slug %s exists.', 'skeleton-wp' ),
					$slug
				)
			);
		}

		return $this->components[ $slug ];
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have ACF PRO installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function action_admin_notice_missing_required_plugin() {


		$message = sprintf(
		/* translators: 1: Plugin name 2: ACF PRO */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'skeleton-wp' ),
			'<strong>' . esc_html__( 'Current Theme', 'skeleton-wp' ) . '</strong>',
			'<strong>' . esc_html__( 'ACF PRO', 'skeleton-wp' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Gets the default theme components.
	 *
	 * This method is called if no components are passed to the constructor, which is the common scenario.
	 *
	 * @return array List of theme components to use by default.
	 */
	protected function get_default_components() : array {
		$components = array(
			new Localization\Component(),
			new Base_Support\Component(),
			new Helpers\Component(),
			new Editor\Component(),
			new Accessibility\Component(),
			new Image_Sizes\Component(),
			new PWA\Component(),
			new Comments\Component(),
			new Nav_Menus\Component(),
			new Sidebars\Component(),
			new Custom_Logo\Component(),
			new Post_Thumbnails\Component(),
			new Customizer\Component(),
			new Styles\Component(),
			new Emoji\Component(),
			new Shortcodes\Component(),
			new Enqueue\Component(),
			new Header\Component()
		);

		if ( defined( 'JETPACK__VERSION' ) ) {
			$components[] = new Jetpack\Component();
		}

    if ( class_exists( 'GFCommon' ) ) {
      $components[] =new Gravity\Component();
    }

		if ( class_exists( 'ACF' ) ) {
			$components[] = new ACF\Component();
			$components[] = new Load_More\Component();
		} else {
			add_action( 'admin_notices', [ $this, 'action_admin_notice_missing_required_plugin' ] );
		}

		return $components;
	}
}
