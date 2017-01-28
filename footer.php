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

		<?php if ( is_active_sidebar( 'sidebar-footer' ) ): ?>
			<div class="c-gallery c-gallery--footer o-grid o-grid--4col-@lap">
				<?php dynamic_sidebar( 'sidebar-footer' ); ?>
			</div><!-- .c-gallery--footer -->
		<?php endif; ?>

		<div class="c-footer__content">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'noah' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'noah' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'noah' ), 'Noah', '<a href="https://pixelgrade.com/" rel="designer">PixelGrade</a>' ); ?>
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