<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'page' );

get_header(); ?>

<?php
	/**
	 * pixelgrade_before_main_content hook.
	 *
	 * @hooked nothing() - 10 (outputs nothing)
	 */
	do_action( 'pixelgrade_before_main_content', $location );
?>

	<div id="primary" class="content-area  u-container-sides-spacings">
		<div class="o-wrapper  u-container-width">
			<main id="main" class="o-wrapper  site-main" role="main">

				<div class="c-page-header">
					<h1 class="c-page-header__title h0"><?php _e( 'Oups!', 'noah'); ?></h1>
					<p class="c-page-header__meta h4"><?php _e( "We can't seem to find the page you're looking for.", 'noah'); ?></p>
				</div>
			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
	/**
	 * pixelgrade_after_main_content hook.
	 *
	 * @hooked nothing - 10 (outputs nothing)
	 */
	do_action( 'pixelgrade_after_main_content', $location );
?>

<?php get_footer(); ?>
