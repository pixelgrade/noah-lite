<?php
/**
 * The template used for displaying project content on archives
 *
 * @package Noah
 * @since   Noah 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class() ?>>

		<div class="c-card__link">
			<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
			<a class="c-card__content-link" href="<?php the_permalink(); ?>">
				<div class="c-card__frame">
					<?php the_post_thumbnail(); ?>
				</div>
			</a>
			<?php } ?>

			<div class="c-card__content"><?php
				if ( get_theme_mod( 'noahlite_blog_items_title_visibility', true ) ) { ?>
					<h2 class="c-card__title h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php }

				if ( get_the_excerpt() ) { ?>
					<div class="c-card__excerpt entry-content">
						<?php the_excerpt(); ?>
					</div>
				<?php }

				noahlite_entry_footer( get_the_ID() );
				?></div><!-- .c-card__content -->
		</div><!-- .c-card__link -->
		<span class="c-card__badge u-color-accent"></span>

</article><!-- #post-XX -->