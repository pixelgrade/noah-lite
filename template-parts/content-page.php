<?php
/**
 * The template used for displaying page content
 *
 * @package Noah Lite
 * @since   Noah 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="c-page-header  entry-header">
		<?php the_title( '<h1 class="c-page-header__title  entry-title"><span>', '</span></h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content  u-content-width  u-content-bottom-spacing">

		<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
			<div class="entry-featured  aligncenter"><?php the_post_thumbnail(); ?></div>
		<?php }

		the_content();

		wp_link_pages( array(
			'before' => '<div class="c-article__page-links  page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
			'after'  => '</div>',
		) ); ?>

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
				edit_post_link(
					sprintf(
					/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'noah-lite' ),
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
