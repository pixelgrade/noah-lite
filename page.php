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

	<div id="primary" class="content-area  u-container-sides-spacings  u-content-bottom-spacing">
		<div class="o-wrapper  u-container-width">
			<main id="main" class="o-wrapper  site-main" role="main">

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

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php get_footer(); ?>
