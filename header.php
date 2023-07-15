<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">

  <?php
  if (!skeleton_wp()->is_amp()) {
    ?>
    <script>
      document.documentElement.classList.remove('no-js');
      document.documentElement.classList.add('js');
    </script>
    <?php
  }
  ?>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
  <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'skeleton-wp'); ?></a>

  <?php
  $sticky_header = get_theme_mod('sticky_header');
  $header_class = 'site-header';
  if($sticky_header) {
    $header_class .= ' site-header--sticky';
  }
  ?>

  <header id="masthead" class="<?= esc_attr($header_class) ?>">
    <div class="container">
      <div class="site-header__grid">
        <?php get_template_part('template-parts/header/branding'); ?>

        <?php get_template_part('template-parts/header/navigation'); ?>
      </div>
    </div>
  </header><!-- #masthead -->


  <?php if ($sticky_header) : ?>
    <div class="site-header-pad"></div>
  <?php endif; ?>
