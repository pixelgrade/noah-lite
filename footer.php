<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Noah
 * @since   Noah 1.0.0
 */
?>

<?php
/**
 * pixelgrade_before_footer hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_before_footer', 'main' );
?>

<?php
/**
 * pixelgrade_footer hook.
 *
 * @hooked pixelgrade_the_footer() - 10 (outputs the footer markup)
 */
do_action( 'pixelgrade_footer', 'main' );
?>

<?php
/**
 * pixelgrade_after_footer hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_footer', 'main' );
?>

</div><!-- #content -->
</div><!-- #page -->


<div class="c-mask js-page-mask"></div>
<div class="c-border"></div>

<?php
/**
 * This function is required since here the admin-bar is loaded and a lot of plugins load their assets through this function
 */
wp_footer(); ?>

</body>
</html>