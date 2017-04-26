<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area  u-blog_sides_spacing">
		<main id="main" class="o-wrapper  u-blog_grid_width  site-main  u-content-bottom-spacing" role="main">

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<div class="c-page-header">
					<h1 class="c-page-header__title h1">
						<?php
						if ( get_option( 'page_for_posts' ) ) {
							echo get_the_title( get_option( 'page_for_posts' ) );
						} else {
							esc_html_e( 'News', 'noah-lite' );
						} ?>
					</h1>
					<div class="c-page-header__meta h7">
						<span><?php _e( 'Show', 'noah-lite' ); ?></span>
						<span class="c-page-header__taxonomy  u-color-accent"><?php noahlite_the_taxonomy_dropdown( 'category' ); ?></span>
					</div>
					<?php if ( term_description() ) {
						echo term_description();
					} ?>
				</div>
			<?php endif; ?>

			<?php if ( have_posts() ) : /* Start the Loop */ ?>

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

			<?php
			$is_infinite = class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' );
			if ( false === $is_infinite ) : ?>
			<?php the_posts_navigation(); ?>
			<?php endif; ?>

			<?php else : ?>
				<div class="u-content-width  entry-content">
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				</div>
			<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();
