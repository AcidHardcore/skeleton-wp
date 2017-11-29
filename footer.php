<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

$the_theme = wp_get_theme();
$container = get_theme_mod('understrap_container_type');
?>

<?php get_sidebar('footerfull'); ?>

<div class="wrapper" id="wrapper-footer">


    <footer class="page-footer">

        <?php if ('container' == $container) : ?>
        <div class="page-footer__container">
            <?php endif; ?>

            <div class="page-footer__site-info">
                <?php printf( // WPCS: XSS ok.
                /* translators:*/
                    esc_html__('Vitalii Ivanychko © 2107.', 'understrap')); ?>
            </div><!-- .site-info -->

            <?php if ('container' == $container) : ?>
        </div><!-- .container -->
    <?php endif; ?>

    </footer><!-- #footer -->


</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

