<?php
/**
 * Template Name: Portfolio
 *
 * @package Noah Lite
 */

get_header(); ?>

	<div id="primary" data-section-name="<?php echo get_post_field( 'post_name', get_post() ); ?>"  class="content-area  u-side-padding">
		<main id="main" class="site-main" role="main">

			<?php
			// Start the loop but without a while as we only have one post, tops
			if ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail() ) : ?>

				<div class="c-hero">

                    <div class="c-hero__background  c-hero__layer">
                        <?php noahlite_hero_the_background_image( get_post_thumbnail_id() ); // Output the background image of the hero ?>
                    </div><!-- .c-hero__background -->

                    <div class="c-hero__wrapper c-hero__layer">

                        <div class="c-hero__content">
                            <?php the_title( '<h1 class="h0  entry-title">', '</h1>' ); ?>
                        </div><!-- .c-hero__content -->

                    </div><!-- .c-hero__wrapper -->

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
                    <?php endif;

                    // We need to make sure we haven't ended up here without Jetpack being activated
                    if ( post_type_exists( 'jetpack-portfolio') && class_exists( 'Jetpack_Portfolio' ) ) :

	                    // we are done with the post - it is time to begin our custom loop
	                    // to be sure that the main query's in_the_loop is properly set we call have_posts() one more time, just like `while` would do
	                    have_posts();

	                    // in case this is a static front page
	                    if ( get_query_var('page') ) {
		                    $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
	                    } else {
		                    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	                    }

	                    $projects = new WP_Query( array(
		                    'post_type' => 'jetpack-portfolio',
		                    'posts_per_page' => get_option( Jetpack_Portfolio::OPTION_READING_SETTING, '10' ),
		                    'paged' => $paged,
	                    ) );

	                    if ( $projects->have_posts() ) : ?>

	                        <div class="js-header-height-padding-top">

		                        <div class="u-content-background">
			                        <section class="c-archive-loop  u-full-width  u-portfolio_sides_spacing  u-content-bottom-spacing">
				                        <div class="o-wrapper u-portfolio_grid_width">
					                        <div <?php noahlite_portfolio_class( '' ); ?>>

						                        <?php while ( $projects->have_posts() ) : $projects->the_post();
							                        get_template_part( 'template-parts/project/content', 'jetpack-portfolio' );
						                        endwhile; ?>

					                        </div>
				                        </div><!-- .o-wrapper.u-portfolio_grid_width -->
			                        </section><!-- .c-archive-loop -->
		                        </div><!-- .u-content-background -->

	                            <?php noahlite_the_older_projects_button( $projects ); ?>

	                        </div><!-- .js-header-height-padding-top -->

	                    <?php else : ?>

							<div class="u-content-bottom-spacing  js-header-height-padding-top">
								<div class="u-content-width entry-content">
									<?php get_template_part( 'template-parts/content', 'none' ); ?>
		                        </div><!-- .u-content-width -->
							</div>

	                    <?php endif;

	                    wp_reset_postdata();

                    else : ?>

					<div class="o-wrapper  u-content-width u-content-bottom-spacing">
						<p>&nbsp;</p>
						<p><strong><?php esc_html_e( 'You need to make sure that Jetpack is installed and activated before you work with projects!', 'noah-lite'); ?></strong></p>
					</div>

                    <?php endif; ?>

				</div><!-- .c-article__content -->
			</article><!-- #post-## -->

			<?php endif; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
