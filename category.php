<?php
/**
 * Category archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area  u-blog_sides_spacing">
		<main id="main" class="o-wrapper  u-blog_grid_width  site-main  u-content-bottom-spacing" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="c-page-header">
					<?php the_archive_title( '<h1 class="c-page-header__title h1">', '</h1>' ); ?>
					<div class="c-page-header__meta h7">
						<span><?php _e( 'Show', 'noah-lite' ); ?></span>
						<span class="c-page-header__taxonomy  u-color-accent"><?php noahlite_the_taxonomy_dropdown( 'category', get_query_var( 'category_name' ) )?></span>
					</div>
					<?php if ( category_description() ) {
						echo category_description();
					} ?>
				</header><!-- .archive-header -->

				<div <?php noahlite_blog_class(); ?> id="posts-container">

					<?php
					while ( have_posts() ) : the_post();
						/**
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile; ?>

				</div><!-- #posts-container -->

				<?php the_posts_navigation(); ?>

			<?php else : ?>
				<div class="u-content-width entry-content">
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				</div>
			<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();
