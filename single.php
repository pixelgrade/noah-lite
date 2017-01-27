<?php
/**
 * The template for displaying all single posts and all the single post_types without a single-<post_type>.php template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'single post' );

get_header(); ?>

<?php
/**
 * pixelgrade_before_main_content hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_before_main_content', $location );
?>

	<div id="primary" class="content-area  u-container-sides-spacings">
		<div class="o-wrapper  u-container-width   u-content-bottom-spacing">
			<main id="main" class="o-wrapper  site-main" role="main">

				<?php while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'single' );

					 ?>

					<div class="u-content-width">

						<?php
						noah_the_author_info_box();

						if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'related-posts' ) ) {
							echo do_shortcode( '[jetpack-related-posts]' );
						}

						noah_the_post_navigation();

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						} ?>

					</div>

				<?php endwhile; // End of the loop. ?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
	/**
	 * pixelgrade_after_main_content hook.
	 *
	 * @hooked nothing - 10 (outputs nothing)
	 */
	do_action( 'pixelgrade_after_main_content', $location );
?>

<?php
//get_sidebar();
get_footer(); ?>
