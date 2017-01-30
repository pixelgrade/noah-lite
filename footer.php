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

<footer <?php pixelgrade_footer_class(); ?>>
	<div class="o-wrapper u-container-width content-area">

		<?php get_template_part( 'template-parts/footer/footer-widgets' ); ?>

		<div class="c-footer__content">
			<?php
			get_template_part( 'template-parts/footer/site-info' );

			if ( ! get_theme_mod( 'noah_footer_hide_back_to_top_link' ) ) { ?>
			<a class="back-to-top" href="#"><?php esc_html_e( 'Back to Top', 'noah' ); ?></a>
			<?php } ?>
		</div><!-- .c-footer__content -->
	</div><!-- .o-wrapper.u-container-width.content-area -->
</footer>

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