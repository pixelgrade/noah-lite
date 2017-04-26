<?php
/**
 * Search results archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area  u-blog_sides_spacing">
		<main id="main" class="o-wrapper  u-blog_grid_width  site-main  u-content-bottom-spacing" role="main">

			<div class="c-page-header">
				<h1 class="c-page-header__title h1">
					<?php printf( __( 'Search results for: %s', 'noah-lite' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>
			</div>

			<?php if ( have_posts() ) : ?>

				<div <?php noahlite_blog_class(); ?> id="posts-container">
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
						/**
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() ); ?>
					<?php endwhile; ?>
				</div>
				<?php the_posts_navigation(); ?>

			<?php else : ?>
				<div class="u-content-width">
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				</div>
			<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();
