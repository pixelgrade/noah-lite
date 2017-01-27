<?php
/**
 * The template used for displaying page content
 *
 * @package Noah
 * @since   Noah 1.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'page' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( apply_filters( 'pixelgrade_display_entry_header', true, $location ) ) : //allow others to prevent this from displaying ?>
	<header class="c-page-header  entry-header">
		<?php the_title( '<h1 class="c-page-header__title  entry-title"><span>', '</span></h1>' ); ?>
	</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-content  u-content-width  u-content-bottom-spacing">

		<?php the_content();

		wp_link_pages( array(
			'before' => '<div class="c-article__page-links  page-links">' . esc_html__( 'Pages:', 'noah' ),
			'after'  => '</div>',
		) ); ?>

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
				edit_post_link(
					sprintf(
					/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'noah' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
