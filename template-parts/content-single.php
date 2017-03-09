<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'post single' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="c-page-header  entry-header">
		<?php the_title( '<h1 class="c-page-header__title  entry-title"><span>', '</span></h1>' ); ?>

		<div class="c-page-header__meta h7">
			<?php noahlite_posted_on(); ?>

			<span class="c-page-header__taxonomy  u-color-accent cats"><?php noahlite_the_first_category(); ?></span>

		</div><!-- .entry-meta -->
	</header><!-- .c-page-header -->

	<div class="entry-content  u-content-width">

		<?php the_content();

		wp_link_pages( array(
			'before' => '<div class="c-article__page-links  page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
			'after'  => '</div>',
		) ); ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer  u-content-width">
		<div>

		<?php noahlite_single_entry_footer(); ?>

		<?php noahlite_the_post_navigation(); ?>

		</div>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
