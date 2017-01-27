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

<?php
    /**
     * pixelgrade_before_main_content hook.
     *
     * @hooked nothing() - 10 (outputs nothing)
     */
    do_action( 'pixelgrade_before_main_content', $location );
?>

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

<?php
// Display the projects archive partial
query_posts( array(
    'post_type' => 'jetpack-portfolio',
//    'post__not_in' => array( get_the_ID() ),
) );
?>



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
                if ( have_posts() ) {
                    get_template_part('template-parts/jetpack-portfolio-loop');
                }
                ?>
            </div>

        </div><!-- .content-area -->

    </div><!-- .js-header-height-padding-top -->

</div><!-- #projectsArchive -->

<?php
wp_reset_query();
?>

<?php
    /**
     * pixelgrade_after_main_content hook.
     *
     * @hooked noah_callback_load_custom_page_css - 10 outputs page Custom CSS
     */
    do_action( 'pixelgrade_after_main_content', $location );
?>

<?php
get_sidebar();
get_footer(); ?>
