<?php
/**
 * Template Name: Portfolio
 *
 * @package Noah
 */

//let the template parts know about our location
$location = noahlite_set_location( 'page portfolio-page' );

get_header(); ?>

	<div id="primary" data-section-name="<?php echo get_post_field( 'post_name', get_post() ); ?>"  class="content-area  u-side-padding">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail() ) : ?>

				<div class="c-hero">

					<div class="c-hero__slider">

							<div class="c-hero__slide">

								<div class="c-hero__background  c-hero__layer">
									<?php noahlite_hero_the_background_image( get_post_thumbnail_id() ); // Output the background image of the hero ?>
								</div><!-- .c-hero__background -->

								<div class="c-hero__wrapper c-hero__layer">

									<div class="c-hero__content">
										<?php the_title( '<h1 class="h0  entry-title">', '</h1>' ); ?>
									</div><!-- .c-hero__content -->

								</div><!-- .c-hero__wrapper -->

							</div><!-- .c-hero__slide -->

					</div><!-- .c-hero__slider -->

				</div><!-- .c-hero -->

				<?php endif; ?>

				<div class="c-article__content  u-content-background  u-content_container_padding_top  u-container_sides_spacing">

                    <?php if ( get_the_content() ): ?>
                        <div class="o-wrapper  u-content-width  js-header-height-padding-top">
                            <?php the_content();

                            wp_link_pages( array(
                                'before' => '<div class="c-article__page-links  page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
                                'after'  => '</div>',
                            ) ); ?>
                        </div>
                    <?php endif; ?>


                    <?php
                    //We are displaying the loop so we need the proper location
                    $location = noahlite_set_location( 'portfolio jetpack' );

                    // in case this is a static front page
                    if ( get_query_var('page') ) {
	                    $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
                    } else {
	                    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                    }

                    $projects = new WP_Query( array(
	                    'post_type' => 'jetpack-portfolio',
	                    'posts_per_page' => get_option( Jetpack_Portfolio::OPTION_READING_SETTING, '10' ),
                    ) );

                    if ( $projects->have_posts() ) : ?>

                        <div class="js-header-height-padding-top">

	                        <div class="u-content-background">
		                        <section class="c-archive-loop  u-full-width  u-portfolio_sides_spacing  u-content-bottom-spacing">
			                        <div class="o-wrapper u-portfolio_grid_width">
				                        <div <?php noahlite_portfolio_class( '', $location ); ?>>

					                        <?php while ( $projects->have_posts() ) : $projects->the_post();
						                        get_template_part( 'template-parts/project/content', 'jetpack-portfolio' );
					                        endwhile; ?>

				                        </div>
			                        </div>
		                        </section>
	                        </div>

                            <?php noahlite_the_older_projects_button( $projects ); ?>

                        </div>

                    <?php else : ?>

	                    <div class="u-content-width">
		                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
	                    </div>

                    <?php endif;

                    wp_reset_postdata();

                    //Set the previous location back
                    $location = noahlite_set_location( 'page portfolio-page' ); ?>

				</div><!-- .c-article__content -->
			</article><!-- #post-## -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
