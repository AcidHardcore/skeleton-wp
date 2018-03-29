<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package skeletonwp
 */

$container = get_theme_mod( 'skeletonwp_container_type' );
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >
<script>
    // Маркер работающего javascript
    document.documentElement.className = document.documentElement.className.replace('no-js', 'js');
</script>
<div class="hfeed site" id="page">

  <!-- ******************* The Navbar Area ******************* -->
  <div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

    <a class="skip-link screen-reader-text sr-only"
       href="#content"><?php esc_html_e( 'Skip to content', 'skeletonwp' ); ?></a>

    <header class="page-header">

		<?php if ( 'container' == $container ) : ?>
      <div class="page-header__container">
        <!--      <div class="container">-->
		  <?php endif; ?>

        <!-- Your site title as branding in the menu -->
		  <?php if ( ! has_custom_logo() ) { ?>

	  <?php if ( is_front_page() && is_home() ) : ?>

        <h1 class="logo"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                            title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
        </h1>

	  <?php else : ?>

        <a class="logo" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>"
           title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>

	  <?php endif; ?>


		  <?php } else {
			  the_custom_logo();
		  } ?><!-- end custom logo -->
        <!-- The WordPress Menu goes here -->
        <nav id="main-nav" class="main-nav" role="navigation">
          <button id="main-nav-toggler" class="main-nav__toggler  burger"><span></span></button>

			<?php wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'main-nav__list',
				'fallback_cb'    => '',
				'menu_id'        => ' ',
				'walker'         => new WP_BEM_Navwalker(),

			) ); ?>
        </nav><!-- .site-navigation -->

		  <?php if ( 'container' == $container ) : ?>
      </div><!-- .container -->
	<?php endif; ?>
    </header>

  </div><!-- .wrapper-navbar end -->
