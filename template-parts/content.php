<?php
/**
 * The template used for displaying project content on archives
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class() ?>>

	<div class="c-card">
		<div class="c-card__link">

            <div class="u-card-thumbnail-background">
                <div class="c-card__frame">
                    <a class="c-card__content-link" href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                </div>
            </div>

			<div class="c-card__content"><?php
				$categories = get_the_terms( get_the_ID(), 'category' );
				$category   = "";
				if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
					$category .= '<ul class="o-inline o-inline-xs">' . PHP_EOL;
					foreach ( $categories as $this_category ) {
						$category .= '<li><a href="' . esc_url( get_category_link( $this_category->term_id ) ) . '">' . $this_category->name . '</a></li>' . PHP_EOL;
					};
					$category .= '</ul>' . PHP_EOL;
				}

				echo '<div class="c-card__meta h6">' . $category . "</div>";

				if ( get_theme_mod( 'noahlite_blog_items_title_visibility', true ) ) { ?>
					<h2 class="c-card__title h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php }

				if ( get_the_excerpt() ) { ?>
					<div class="c-card__excerpt entry-content">
						<?php the_excerpt(); ?>
					</div>
				<?php }

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
					'after'  => '</div>',
				) );

				echo '<div class="c-card__footer h6"><span class="posted-on">' . noahlite_date_link() . '</span></div>';

				noahlite_entry_footer( get_the_ID() );
				?></div><!-- .c-card__content -->
		</div><!-- .c-card__link -->
		<span class="c-card__badge u-color-accent"></span>
    </div>

</article><!-- #post-XX -->
