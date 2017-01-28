<?php
/**
 * The template for displaying archive pages for portfolio.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//let the template parts know about our location
$location = 'archive portfolio jetpack';
pixelgrade_set_location( $location );

get_header(); ?>

	<header class="c-page-header content-area">
		<h1 class="c-page-header__title h1">
			<?php _e( 'Projects', 'noah' ); ?>
		</h1>
		<div class="c-page-header__meta h7">
			<span><?php _e( 'Show', 'noah' ); ?></span>
			<span class="c-page-header__taxonomy  u-color-accent"><?php noah_the_taxonomy_dropdown( 'jetpack-portfolio-type', get_query_var( 'term' ) ); ?></span>
		</div>
		<?php if ( term_description() ) {
			echo term_description();
		} ?>
	</header><!-- .archive-header -->

	<?php if ( have_posts() ) : ?>

		<div class="u-content-background">
			<section class="c-archive-loop  u-full-width  u-portfolio_sides_spacing  u-content-bottom-spacing">
				<div class="o-wrapper u-portfolio_grid_width">
					<div <?php noah_portfolio_class( '', $location ); ?>>

						<?php while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/content', 'jetpack-portfolio' );
						endwhile; ?>

					</div>
				</div>
			</section>
		</div>

	<?php else : ?>

		<div class="u-content-width">
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		</div>

	<?php endif; ?>

    <?php the_posts_navigation(); ?>

<?php
get_sidebar();
get_footer(); ?>
