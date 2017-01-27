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

	<div id="primary" class="content-area  u-container-sides-spacings  u-content-bottom-spacing">
		<div class="o-wrapper  u-container-width">
			<main id="main" class="o-wrapper  site-main" role="main">

			<?php
				/**
				 * pixelgrade_before_loop hook.
				 *
				 * @hooked noah_custom_page_css - 10 (outputs the page's custom css)
				 */
				do_action( 'pixelgrade_before_loop', $location );
			?>

			<?php while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

			?>

				<div class="u-content-width">
					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					} ?>
				</div>

			<?php endwhile; // End of the loop. ?>

			<?php
				/**
				 * pixelgrade_after_loop hook.
				 *
				 * @hooked nothing - 10 (outputs nothing)
				 */
				do_action( 'pixelgrade_after_loop', $location );
			?>

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
