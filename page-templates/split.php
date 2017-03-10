<?php
/**
 * Template Name: Split
 *
 * @package Noah Lite
 */

get_header(); ?>

    <div id="primary" class="content-area  u-side-padding  u-content-background">
		<main id="main" class="site-main" role="main">

	        <?php while ( have_posts() ) : the_post(); ?>

	        <article id="post-<?php the_ID(); ?>" <?php post_class( 'c-article' ); ?>>

	            <div class="o-split">
	                <?php if ( has_post_thumbnail() ) { ?>
	                    <div class="o-split__img"><?php the_post_thumbnail( 'full' ); ?></div>
	                <?php } ?>

	                <div class="o-split__body u-container-sides-spacings">

		                <div class="o-wrapper u-content-width">

							<header class="c-page-header  entry-header">

								<?php the_title( '<h1 class="c-page-header__title  entry-title">', '</h1>' ); ?>

							</header><!-- .entry-header -->

							<div class="u-content-bottom-spacing  entry-content">

								<?php the_content();

								wp_link_pages( array(
									'before' => '<div class="c-article__page-links  page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
									'after'  => '</div>',
								) ); ?>

							</div><!-- .entry-content -->

						</div>

	                </div>
	            </div>

	        </article><!-- #post-## -->

	        <?php endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
