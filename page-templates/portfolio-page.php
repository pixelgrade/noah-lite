<?php
/**
 * Template Name: Portfolio
 *
 * @package Noah
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'page portfolio-page' );

get_header(); ?>

	<div id="primary" data-section-name="<?php echo get_post_field( 'post_name', get_post() ); ?>"  class="content-area  u-side-padding">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'c-article' ); ?>>

				<div <?php pixelgrade_hero_class( '', $location ); pixelgrade_hero_background_color_style( get_the_ID() ); ?>>

					<div class="c-hero__slider"	<?php pixelgrade_hero_slider_attributes( '', get_the_ID() ); ?>>

						<?php
						// get all the images/videos/featured projects ids that we will use as slides (we also cover for when there are none)
						$slides = pixelgrade_hero_get_slides_ids( get_the_ID() );

						// First go through all the attachments (images and/or videos) and add them as slides
						// the first slide we encounter is obviously the first one
						$first_slide = true;

						// Loop through each slide ( the first one is kinda special )
						foreach ( $slides as $key => $attachment_id ) : ?>

							<div class="c-hero__slide" <?php pixelgrade_hero_background_color_style( get_the_ID() ); ?>>

								<div class="c-hero__background  c-hero__layer">

									<?php
									$hero_image_opacity = get_post_meta( get_the_ID(), '_hero_image_opacity', true );
									pixelgrade_hero_the_slide_background( $attachment_id, $hero_image_opacity ); // Output the background image of the slide
									?>

								</div><!-- .c-hero__background -->

								<?php
								// we only show the hero description on the first slide
								if ( true === $first_slide ) :

									$description = '<h1 class="h0">' . get_the_title() . '</h1>';
									$description_alignment = '';

									if ( ! empty( $description ) ) { ?>

										<div <?php pixelgrade_hero_wrapper_class( $description_alignment ); ?>>

											<div class="c-hero__content">
												<?php the_title( '<h1 class="h0  entry-title">', '</h1>' ); ?>
											</div><!-- .c-hero__content -->

										</div><!-- .c-hero__wrapper -->

									<?php }

									// remember that we are done with the first slide
									$first_slide = false;
								endif; ?>

							</div><!-- .c-hero__slide -->

						<?php endforeach; ?>

					</div><!-- .c-hero__slider -->

				</div><!-- .c-hero -->

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
                    $location = pixelgrade_set_location( 'portfolio jetpack' );

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
                    $location = pixelgrade_set_location( 'page portfolio-page' ); ?>

				</div><!-- .c-article__content -->
			</article><!-- #post-## -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
