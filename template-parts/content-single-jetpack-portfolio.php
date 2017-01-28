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
$location = pixelgrade_get_location( 'single project jetpack' );
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="u-full-width u-container-sides-spacings">
		<div class="o-wrapper u-container-width">
			<div class="c-project__content  o-wrapper  js-project-content">
				<header class="c-page-header">

					<?php the_title( '<h1 class="c-page-header__title">', '</h1>' ); ?>

					<div class="c-page-header__meta h7">

						<?php if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'sharedaddy' ) ) { ?>
							<a class="c-meta__share-link" href="#"><?php _e( 'Share', 'noah' ); ?></a>
						<?php } ?>

						<?php
							$the_term_list = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type' );
							if ( ! empty( $the_term_list ) ) {
						?>
						<span class="c-page-header__taxonomy  u-color-accent"><?php echo $the_term_list ?></span>
						<?php } ?>

					</div>

				</header>

				<div class="entry-content  u-content-width">
					<?php the_content(); ?>
				</div>
				<?php wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'noah' ),
					'after'  => '</div>',
				) ); ?>
			</div><!-- .entry-content -->

			<div class="c-project__media  js-project-media">
				<?php do_action( 'the_noah_gallery', get_the_ID() ); ?>
			</div><!-- .c-project__media -->
		</div><!-- .o-wrapper -->
	</div><!-- .u-full-width -->

	<div class="c-mask c-mask--project js-project-mask"></div>

</div><!-- #post-## -->
