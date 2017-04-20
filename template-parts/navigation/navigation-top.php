<?php
/**
 * Displays top navigation
 *
 * @package Noah Lite
 * @since 1.1.1
 */

?>
<div id="site-navigation" role="navigation" class="c-navbar  c-navbar--dropdown main-navigation">
	<input class="c-navbar__checkbox" id="menu-toggle" type="checkbox" aria-controls="primary-menu" aria-expanded="false">
	<label class="c-navbar__label" for="menu-toggle">
		<span class="c-navbar__label-icon"><?php get_template_part( 'template-parts/header/burger' ); ?></span>
		<span class="c-navbar__label-text screen-reader-text"><?php esc_html_e( 'Primary Menu', 'noah-lite' ); ?></span>
	</label><!-- .c-navbar__label -->

	<div class="c-navbar__content">

		<?php
		$has_left_menu   = has_nav_menu( 'primary-left' );
		$has_right_menu  = has_nav_menu( 'primary-right' );

		$menu_left_markup = noahlite_header_get_the_left_menu();
		// In case we have a fallback, we need to adjust
		if ( ! empty( $menu_left_markup ) ) {
			$has_left_menu = true;
		}
		$menu_right_markup = noahlite_header_get_the_right_menu();
		// In case we have a fallback, we need to adjust
		if ( ! empty( $menu_right_markup ) ) {
			$has_right_menu = true;
		}

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
		 * Also note that you can make use of the fact that we've used the noahlite_css_class() function to
		 * output the classes for each zone. You can use the `noahlite_css_class` filter and depending on
		 * the location received act accordingly.
		 */ ?>

		<div <?php noahlite_css_class( $zone_left_classes, 'header navbar zone left' ); ?>>
			<?php if ( $has_left_menu ) {
				echo $menu_left_markup;
			} elseif ( $has_right_menu ) { ?>
				<div <?php noahlite_css_class( 'header nav', 'header navbar zone left' ); ?>>
					<?php get_template_part( 'template-parts/header/branding' ); ?>
				</div>
			<?php } ?>
		</div><!-- .c-navbar__zone .c-navbar__zone--left -->

		<div <?php noahlite_css_class( $zone_middle_classes, 'header navbar zone middle' ); ?>>
			<?php if ( $has_left_menu || ! ( $has_left_menu || $has_right_menu ) ) { ?>
				<div <?php noahlite_css_class( 'header nav', 'header navbar zone middle' ); ?>>
					<?php get_template_part( 'template-parts/header/branding' ); ?>
				</div>
			<?php } else {
				echo $menu_right_markup;
			}
			?>
		</div><!-- .c-navbar__zone .c-navbar__zone--middle -->

		<div <?php noahlite_css_class( $zone_right_classes, 'header navbar zone right' ); ?>>
			<?php if ( $has_left_menu ) {
				echo $menu_right_markup;
			} ?>
		</div><!-- .c-navbar__zone .c-navbar__zone--right -->
	</div><!-- .c-navbar__content -->

	<?php if ( ( noahlite_is_frontpage() || ( is_home() && is_front_page() ) ) && has_custom_header() ) : ?>
		<a href="#content" class="menu-scroll-down"><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'noah-lite' ); ?></span></a>
	<?php endif; ?>

</div><!-- .c-navbar -->

<a href="#projectsArchive" class="c-scroll-arrow"><?php get_template_part( 'template-parts/svg/project-scroll-down' ); ?></a>
