<?php
/**
 * Sidebar - hero setup.
 *
 * @package skeletonwp
 */

?>

<?php if ( is_active_sidebar( 'hero' ) ) : ?>

  <!-- ******************* The Hero Widget Area ******************* -->

    <section class="slider">
    <div class="swiper-container">
<!--      Additional required wrapper-->
      <div class="swiper-wrapper">
<!--        Slides-->
		  <?php dynamic_sidebar( 'hero' ); ?>
      </div>
<!--      If we need pagination-->
      <div class="swiper-pagination"></div>

<!--      If we need navigation buttons-->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>

<!--      If we need scrollbar-->
<!--      <div class="swiper-scrollbar"></div>-->
    </div>
  </section>

<?php endif; ?>
