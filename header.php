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

					<?php
					/**
					 * pixelgrade_header_before_navbar hook.
					 */
					do_action( 'pixelgrade_header_before_navbar', 'header' );
					?>

					<div class="c-navbar  c-navbar--dropdown">
						<input class="c-navbar__checkbox" id="menu-toggle" type="checkbox" aria-controls="primary-menu" aria-expanded="false">
						<label class="c-navbar__label" for="menu-toggle">
							<span class="c-navbar__label-icon"><?php get_template_part( 'template-parts/burger' ); ?></span>
							<span class="c-navbar__label-text screen-reader-text"><?php esc_html_e( 'Primary Menu', 'noah' ); ?></span>
						</label><!-- .c-navbar__label -->

						<div class="c-navbar__content">

							<?php
							$has_left_menu   = has_nav_menu( 'primary-left' );
							$has_right_menu  = has_nav_menu( 'primary-right' );
							$has_social_menu = has_nav_menu( 'jetpack-social-menu' );

							$menu_left_markup = pixelgrade_header_get_the_left_menu();
							$menu_right_markup = pixelgrade_header_get_the_right_menu();

							// Setup the classes for the various areas
							// First the left area
							$zone_left_classes  = array( 'c-navbar__zone', 'c-navbar__zone--left' );
							if ( $has_left_menu && $has_right_menu ) {
								$zone_left_classes[] = 'c-navbar__zone--push-right';
							}

							// Then the middle area
							$zone_middle_classes  = array( 'c-navbar__zone', 'c-navbar__zone--middle' );

							// Then the right area
							$zone_right_classes = array( 'c-navbar__zone', 'c-navbar__zone--right' );
							if ( ! $has_right_menu || ( ! $has_left_menu && $has_right_menu ) ) {
								$zone_right_classes[] = 'c-navbar__zone--push-right';
							}

							/*
							 * All the conditionals bellow follow the logic outlined in the component's guides
							 * @link http://pixelgrade.github.io/guides/components/header
							 * They try to automatically adapt to the existence or non-existence of navbar components: the menus and the logo.
							 *
							 * Also note that you can make use of the fact that we've used the pixelgrade_css_class() function to
							 * output the classes for each zone. You can use the `pixelgrade_css_class` filter and depending on
							 * the location received act accordingly.
							 */ ?>

							<div <?php pixelgrade_css_class( $zone_left_classes, 'header navbar zone left' ); ?>>
								<?php if ( $has_left_menu ) {
									echo $menu_left_markup;
								} elseif ( $has_right_menu ) { ?>
									<div <?php pixelgrade_css_class( 'header nav', 'header navbar zone left' ); ?>>
										<?php get_template_part( 'template-parts/branding' ); ?>
									</div>
								<?php } ?>
							</div><!-- .c-navbar__zone .c-navbar__zone--left -->

							<div <?php pixelgrade_css_class( $zone_middle_classes, 'header navbar zone middle' ); ?>>
								<?php if ( $has_left_menu || ! ( $has_left_menu || $has_right_menu ) ) { ?>
									<div <?php pixelgrade_css_class( 'header nav', 'header navbar zone middle' ); ?>>
										<?php get_template_part( 'template-parts/branding' ); ?>
									</div>
								<?php } else {
									echo $menu_right_markup;
								}
								?>
							</div><!-- .c-navbar__zone .c-navbar__zone--middle -->

							<div <?php pixelgrade_css_class( $zone_right_classes, 'header navbar zone right' ); ?>>
								<?php if ( $has_left_menu ) {
									echo $menu_right_markup;
								}

								if ( function_exists( 'jetpack_social_menu' ) ) {
									jetpack_social_menu();
								} ?>
							</div><!-- .c-navbar__zone .c-navbar__zone--right -->
						</div><!-- .c-navbar__content -->
					</div><!-- .c-navbar -->

					<?php
					/**
					 * pixelgrade_header_after_navbar hook.
					 */
					do_action( 'pixelgrade_header_after_navbar', 'header' );
					?>

				</div><!-- .o-wrapper  .u-container-width -->
			</div><!-- .u-header_sides_spacing -->
		</header><!-- #masthead .site-header -->
