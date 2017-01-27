<?php
/**
 * Template part for displaying single projects.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'portfolio jetpack' );

if ( have_posts() ) : ?>

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
