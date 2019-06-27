<?php
/**
 * Displays footer site info
 *
 * @package Noah Lite

 * @since 1.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="site-info">
	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'noah-lite' ) ); ?>"><?php
		/* translators: %s: WordPress */
		printf( esc_html__( 'Proudly powered by %s', 'noah-lite' ), 'WordPress' ); ?></a>
	<span class="sep"> | </span>
	<?php
	/* translators: %1$s: The theme name, %2$s: The theme author name. */
	printf( esc_html__( 'Theme: %1$s by %2$s.', 'noah-lite' ), 'Noah Lite', '<a href="https://pixelgrade.com/?utm_source=noah-lite-clients&utm_medium=footer&utm_campaign=noah-lite" title="' . esc_html__( 'The Pixelgrade Website', 'noah-lite' ) . '" rel="nofollow">Pixelgrade</a>' ); ?>
</div><!-- .site-info -->
