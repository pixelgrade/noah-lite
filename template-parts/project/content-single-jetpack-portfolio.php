<?php
/**
 * Template part for displaying single projects.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="u-full-width u-container-sides-spacings">
		<div class="o-wrapper u-container-width">
			<div class="c-project__content  o-wrapper  js-project-content">
				<header class="c-page-header  entry-header">

					<?php the_title( '<h1 class="c-page-header__title">', '</h1>' ); ?>

					<div class="c-page-header__meta h7">

						<?php
						$the_term_list = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type' );
						if ( ! empty( $the_term_list ) ) { ?>
						<span class="c-page-header__taxonomy  u-color-accent"><?php echo $the_term_list ?></span>
						<?php } ?>

					</div><!-- .c-page-header__meta -->
				</header><!-- .c-page-header -->

				<div class="entry-content  u-content-width">
					<?php the_content(); ?>
				</div><!-- .entry-content -->
				<?php wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
					'after'  => '</div>',
				) ); ?>
			</div><!-- .entry-content -->
		</div><!-- .o-wrapper -->
	</div><!-- .u-full-width -->

	<div class="c-mask c-mask--project js-project-mask"></div>

</div><!-- #post-## -->
