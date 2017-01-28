<?php
/**
 * The template for displaying single projects.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'single project jetpack' );

get_header(); ?>

<div id="primary" data-section-name="<?php echo get_post_field( 'post_name', get_post() ); ?>" class="content-area  u-container-sides-spacings  u-content_container_padding_top">
    <div class="o-wrapper  u-container-width">
        <main id="main" class="o-wrapper  u-content-width  site-main" role="main">

            <?php while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content-single', 'jetpack-portfolio' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) {
                    ob_start();
                    comments_template();
                    $comments = ob_get_clean();
                }

            endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div>
</div><!-- #primary -->

<div id="projectsArchive" data-section-name="archive" class="c-project__more  js-project-more-content">

    <div class="js-header-height-padding-top">

        <div class="content-area">

            <?php if ( ! empty( $comments ) ) { ?>
                <div class="c-separator"></div>
                <div class="u-content-width  u-content-bottom-spacing  js-header-height-padding-top">
                    <?php echo $comments; ?>
                </div>
            <?php } ?>

            <div class="u-align-center js-share-target c-page-header__side"></div>
            <div class="c-separator"></div>

            <header class="u-align-center">
                <?php the_archive_title( '<h1 class="c-project__more-title  h4">', '</h1>' ); ?>
            </header><!-- .page-header -->

            <div class="u-content-bottom-spacing">
                <?php
                //We are displaying the loop so we need the proper location
                $location = pixelgrade_set_location( 'portfolio jetpack' );

                $projects = new WP_Query( array(
	                'post_type' => 'jetpack-portfolio',
	                'posts_per_page' => -1, //we want all the projects here
                ) );

                if ( $projects->have_posts() ) : ?>

	                <div class="u-content-background">
		                <section class="c-archive-loop  u-full-width  u-portfolio_sides_spacing  u-content-bottom-spacing">
			                <div class="o-wrapper u-portfolio_grid_width">
				                <div <?php noah_portfolio_class( '', $location ); ?>>

					                <?php while ( $projects->have_posts() ) : $projects->the_post();
						                get_template_part( 'template-parts/content', 'jetpack-portfolio' );
					                endwhile; ?>

				                </div>
			                </div><!-- .o-wrapper -->
		                </section><!-- .c-archive-loop -->
	                </div><!-- .u-content-background -->

                <?php else : ?>

	                <div class="u-content-width">
		                <?php get_template_part( 'template-parts/content', 'none' ); ?>
	                </div>

                <?php endif;

                wp_reset_postdata();

                //Set the previous location back
                $location = pixelgrade_set_location( 'single project jetpack' ); ?>

            </div><!-- .u-content-bottom-spacing -->

        </div><!-- .content-area -->

    </div><!-- .js-header-height-padding-top -->

</div><!-- #projectsArchive -->

<?php
wp_reset_query();
?>

<?php
get_sidebar();
get_footer(); ?>
