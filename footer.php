<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package skeletonwp
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'skeletonwp_container_type' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

  <div class="<?php echo esc_attr( $container ); ?>">

    <div class="row">

      <div class="col-md-12">

        <footer class="site-footer page-footer" id="colophon">

          <div class="page-footer__site-info">

            <!--							<a href="-->
			  <?php // echo esc_url( __( 'http://wordpress.org/','skeletonwp' ) ); ?><!--">--><?php //printf(
			  //							/* translators:*/
			  //							esc_html__( 'Proudly powered by %s', 'skeletonwp' ),'WordPress' ); ?><!--</a>-->

              <?php $copyright = carbon_get_theme_option( 'skeletonwp_footer_copyright' ); ?>

            <span class="page-footer__copyright"><?php echo esc_html__( $copyright, 'skeletonwp' ); ?></span>

            <span class="page-footer__sep"> | </span>

			  <?php printf( // WPCS: XSS ok.
			  /* translators:*/
				  esc_html__( 'Theme: %1$s by %2$s.', 'skeletonwp' ), $the_theme->get( 'Name' ), '<a href="' . esc_url( __( 'http://vit.makeevka.com', 'skeletonwp' ) ) . '">vit.makeevka.com</a>' ); ?>

            (<?php printf( // WPCS: XSS ok.
			  /* translators:*/
				  esc_html__( 'Version: %1$s', 'skeletonwp' ), $the_theme->get( 'Version' ) ); ?>)
          </div><!-- .site-info -->
          <?php echo do_shortcode('[social]');?>
        </footer><!-- #colophon -->

      </div><!--col end -->

    </div><!-- row end -->

  </div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

