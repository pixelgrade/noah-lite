<?php
/**
 * WordPress.com-specific functions and definitions.
 *
 * @package Noah
 * @since   Noah 1.1.1
 */

/**
 * Adds support for wp.com-specific theme functions.
 *
 * @global array $themecolors
 */
function noah_wpcom_setup() {
	global $themecolors;

	// Set theme colors for third party services.
	if ( ! isset( $themecolors ) ) {
		$themecolors = array(
			'bg'     => 'ffffff',
			'border' => 'ebebeb',
			'text'   => 'aaafb3',
			'link'   => '80c9dd',
			'url'    => '80c9dd',
		);
	}
}
add_action( 'after_setup_theme', 'noah_wpcom_setup' );

/**
 * De-queue Self-Hosted fonts if custom fonts are being used instead.
 *
 * @return void
 */
function noah_dequeue_fonts() {
	if ( class_exists( 'TypekitData' ) && class_exists( 'CustomDesign' ) && CustomDesign::is_upgrade_active() ) {
		$custom_fonts = TypekitData::get( 'families' );
		if ( $custom_fonts && $custom_fonts['headings']['id'] && $custom_fonts['body-text']['id'] ) {
			wp_dequeue_style( 'noah-fonts-arcamajora3' );
			wp_dequeue_style( 'noah-fonts-ek-mukta' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'noah_dequeue_fonts' );

/**
 * Disable the widont filter on WP.com to avoid stray &nbsps
 *
 * @link https://vip.wordpress.com/functions/widont/
 */
function noah_wido() {
	remove_filter( 'the_title', 'widont' );
}
add_action( 'init', 'noah_wido' );
