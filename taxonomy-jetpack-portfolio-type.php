<?php
/**
 * The template for displaying archive pages for the jetpack-portfolio-type taxonomy.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

get_header(); ?>

<header class="c-page-header content-area">
	<h1 class="c-page-header__title h1">
		<?php esc_html_e( 'Projects', 'noah-lite' ); ?>
	</h1>
	<div class="c-page-header__meta h7">
		<span><?php esc_html_e( 'Show', 'noah-lite' ); ?></span>
		<span class="c-page-header__taxonomy  u-color-accent"><?php noahlite_the_taxonomy_dropdown( 'jetpack-portfolio-type', get_query_var( 'term' ) ); ?></span>
	</div>

	<?php if ( term_description() ) {
		echo term_description();
	} ?>

</header><!-- .archive-header -->

		<?php if ( have_posts() ) : ?>

	<div class="u-content-background">
		<section class="c-archive-loop  u-full-width  u-portfolio_sides_spacing  u-content-bottom-spacing">
			<div class="o-wrapper u-portfolio_grid_width">
				<div <?php noahlite_portfolio_class(); ?>>

					<?php while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/project/content', 'jetpack-portfolio' );
					endwhile; ?>

						</div>
					</div><!-- .o-wrapper -->
				</section><!-- .c-archive-loop -->
			</div><!-- .u-content-background -->

		<?php else : ?>
			<div class="u-content-width entry-content">
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			</div>
		<?php endif; ?>
	</div>

<?php the_posts_navigation(); ?>

<?php get_footer();
