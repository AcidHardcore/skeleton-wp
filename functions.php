<?php
/**
 * skeletonwp functions and definitions
 *
 * @package skeletonwp
 */

/**
 * CARBON FIELDS
 */
add_action( 'carbon_fields_register_fields', 'skeletonwp_register_custom_fields' );
function skeletonwp_register_custom_fields() {
	require get_template_directory() . '/inc/custom-field-options/metabox.php';
	require get_template_directory() . '/inc/custom-field-options/theme-options.php';
}

/**
 * Initialize theme default settings
 */
require get_template_directory() . '/inc/theme-settings.php';
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
require get_template_directory() . '/inc/helpers.php';
/**
 *
 * CLEANING
 *
 */

/** Remove amoji.
 */
require get_template_directory() . '/inc/clean-wp/amoji.php';
/**
 * Remove rest-api.
 */
//require get_template_directory() . '/inc/clean-wp/rest-api.php';
/**
 * Remove xml-rpc.
 */
//require get_template_directory() . '/inc/clean-wp/xml-rpc.php';
/**
 * Remove pingback, canonical, meta generator, wlwmanifest, EditURI, shortlink, prev, next, RSS, feed, profile from head
 */
//require get_template_directory() . '/inc/clean-wp/remove-other.php';
/**
 * Remove recentcomments css
 */
require get_template_directory() . '/inc/clean-wp/recentcomments.php';
/***
 *
 * SHORTCODES
 *
 */
require get_template_directory() . '/inc/shortcodes/social-shortcode.php';
/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/woocommerce/inc/wc-functions-remove.php';
}

function vimeo_url_params( $block_content, $block ) {
	if ( "core-embed/vimeo" === $block['blockName'] ) {
		$block_content = str_replace( '?dnt=1', '?dnt=1&amp;title=0&amp;byline=0&amp;portrait=0', $block_content );
	}

	return $block_content;
}

add_filter( 'render_block', 'vimeo_url_params', 10, 3 );


Class API_JWT_WP {
	private $args = array();
	private $token = '';
	public $headers = '';
	public $url = '';

	public function __construct( $url, $args ) {
		$this->url  = $url;
		$this->args = $args;
		$this->get_token();
	}

	public function request( $method, $url, $data, $headers = false ) {
		$curl = curl_init();
		switch ( $method ) {
			case "POST":
				curl_setopt( $curl, CURLOPT_POST, 1 );
				if ( $data ) {
					curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
				}
				break;
			case "PUT":
				curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PUT" );
				if ( $data ) {
					curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
				}
				break;
			default:
				if ( $data ) {
					$url = sprintf( "%s?%s", $url, http_build_query( $data ) );
				}
		}
		// OPTIONS:
		curl_setopt( $curl, CURLOPT_URL, $url );
		if ( ! $headers ) {
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Content-Type: application/json',
			) );
		} else {
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Content-Type: application/json',
				$headers
			) );
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		// EXECUTE:
		$result = curl_exec( $curl );
		if ( ! $result ) {
			die( "Connection Failure" );
		}
		curl_close( $curl );

		return json_decode( $result );
	}

	private function get_token() {
		if ( isset( $_COOKIE['token'] ) && ! empty( $_COOKIE['token'] ) ) {
			$this->token   = $_COOKIE['token'];
			$this->headers = 'Authorization: Bearer ' . $this->token;

			if ( $this->token_validation() == false ) {
				$this->update_token();
			}
		} else {
			$this->update_token();
		}
	}

	private function update_token() {
		$response = $this->request( 'POST', $this->url . '/wp-json/jwt-auth/v1/token', json_encode( $this->args ) );
		if ( $response && $response != null ) {
			$this->token   = $response->token;
			$this->headers = 'Authorization: Bearer ' . $response->token;
			setcookie( 'token', $this->token, time() + 24 * 60 * 60, '/' );
		}
	}

	private function token_validation() {

		if ( $this->headers == '' ) {
			return false;
		}

		$response = $this->request( 'POST', $this->url . '/wp-json/jwt-auth/v1/token/validate', false, $this->headers );
		if ( $response && $response != null ) {
			return $response->data->status == 200 ? true : false;
		}
	}

}

//$JWT_WP = new API_JWT_WP( 'http://mkz', array( 'username' => 'mkz_site', 'password' => 'FIb30tWFDH7M' ) );

$data = array(
	'title'   => 'title',
	'content' => 'content',
	'status'  => 'publish'
);
//var_dump( $JWT_WP->request( 'POST', 'http://mkz/wp-json/jwt-auth/v1/token/validate', $data, $JWT_WP->headers ) );
//var_dump( $JWT_WP->request( 'POST', 'http://mkz/wp-json/wp/v2/posts', json_encode( $data ), $JWT_WP->headers ) );

