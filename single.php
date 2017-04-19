<?php
/**
 * The template for displaying all single posts and all the single post_types without a single-<post_type>.php template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area  u-container-sides-spacings">
		<div class="o-wrapper  u-container-width   u-content-bottom-spacing">
			<main id="main" class="o-wrapper  site-main" role="main">

				<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', 'single' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile; // End of the loop. ?>

			</main><!-- #main -->
		</div><!-- .o-wrapper -->
	</div><!-- #primary -->

<?php get_footer();
