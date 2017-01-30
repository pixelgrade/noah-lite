<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php
	/**
	 * This function is required, here the assets are loaded and lots of plugins are hooking their meta or scripts here.
	 * Usually without wp_head() a theme will break.
	 */
	wp_head(); ?>
</head>
<?php
/**
 * The `body_class()` function is required. WordPress core and our theme should add or remove classes through this hook
 * The `pixelgrade_body_attributes()` is of our own making. It does what `body_class()` does, but for attributes
 */?>
<body <?php body_class(); ?> <?php pixelgrade_body_attributes(); ?>>

<div id="barba-wrapper" class="hfeed site">

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'noah' ); ?></a>

	<div id="content" class="site-content barba-container js-header-height-padding-top">

		<header id="masthead" <?php pixelgrade_header_class(); ?> role="banner">
			<div class="u-header_sides_spacing">
				<div class="o-wrapper  u-container-width  c-navbar__wrapper">

					<?php get_template_part( 'template-parts/navigation/navigation-top' ); ?>

				</div><!-- .o-wrapper  .u-container-width -->
			</div><!-- .u-header_sides_spacing -->
		</header><!-- #masthead .site-header -->
