<?php
/**
 * Displays footer site info
 *
 * @package Noah

 * @since 1.1.1
 */

?>
<div class="site-info">
	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'noah-lite' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'noah-lite' ), 'WordPress' ); ?></a>
	<span class="sep"> | </span>
	<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'noah-lite' ), 'Noah', '<a href="https://pixelgrade.com/" rel="designer">PixelGrade</a>' ); ?>
</div><!-- .site-info -->
