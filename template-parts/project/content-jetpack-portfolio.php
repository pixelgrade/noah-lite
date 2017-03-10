<?php
/**
 * The template used for displaying project content on archives
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="c-card">
		<div class="c-card__link">

			<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
			<a class="c-card__content-link" href="<?php the_permalink( get_the_ID() ); ?>">
				<div class="c-card__frame">
				<?php
				// Output the featured image
				echo get_the_post_thumbnail( get_the_ID(), 'post-thumbnail' ); ?>
				</div><!-- .c-card__frame -->
			</a>
			<?php } ?>

			<div class="c-card__content">
				<?php
				// Gather up all the meta we might need to display
				// But first initialize please
				$meta = array(
					'category' => false,
					'tags' => false,
					'author' => false,
					'date' => false,
					'comments' => false,
				);

				if ( 'category' == get_theme_mod( 'noahlite_portfolio_items_primary_meta', 'none' ) ) {
					$portfolio_categories = get_the_terms( get_the_ID(), 'jetpack-portfolio-type' );
					$category             = '';
					if ( ! is_wp_error( $portfolio_categories ) && ! empty( $portfolio_categories ) ) {
						$category .= '<ul class="o-inline">' . PHP_EOL;
						foreach ( $portfolio_categories as $portfolio_category ) {
							$category .= '<li><a href="' . esc_url( get_category_link( $portfolio_category->term_id ) ) . '">' . $portfolio_category->name . '</a></li>' . PHP_EOL;
						};
						$category .= '</ul>' . PHP_EOL;
					}
					$meta['category'] = $category;
				}

				if ( 'tags' == get_theme_mod( 'noahlite_portfolio_items_primary_meta', 'none' ) ) {
					$portfolio_tags = get_the_terms( get_the_ID(), 'jetpack-portfolio-tag' );
					$tags           = '';
					if ( ! is_wp_error( $portfolio_tags ) && ! empty( $portfolio_tags ) ) {
						$tags .= '<ul class="o-inline">' . PHP_EOL;
						foreach ( $portfolio_tags as $portfolio_tag ) {
							$tags .= '<li>' . $portfolio_tag->name . '</li>' . PHP_EOL;
						};
						$tags .= '</ul>' . PHP_EOL;
					}
					$meta['tags'] = $tags;
				}

				$meta['author'] = '<span class="byline">' . get_the_author() . '</span>';
				$meta['date']   = '<span class="posted-on">' . noahlite_date_link() . '</span>';

				$comments_number = get_comments_number(); // get_comments_number returns only a numeric value
				if ( comments_open() ) {
					if ( $comments_number == 0 ) {
						$comments = __( 'No Comments', 'noah-lite' );
					} elseif ( $comments_number > 1 ) {
						$comments = $comments_number . ' ' . __( 'Comments', 'noah-lite' );
					} else {
						$comments = __( '1 Comment', 'noah-lite' );
					}
					$meta['comments'] = '<a href="' . esc_url( get_comments_link() ) . '">' . $comments . '</a>';
				} else {
					$meta['comments'] = '';
				}

				// Display the project's details
				if ( get_theme_mod( 'noahlite_portfolio_items_primary_meta', 'none' ) !== 'none' && ! empty( $meta[ get_theme_mod( 'noahlite_portfolio_items_primary_meta' ) ] ) ) {
					echo '<div class="c-card__meta h7">' . $meta[ get_theme_mod( 'noahlite_portfolio_items_primary_meta' ) ] . '</div>';
				}

				if ( get_theme_mod( 'noahlite_portfolio_items_title_visibility', true ) ) { ?>
					<h2 class="c-card__title h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php } ?>
			</div><!-- .c-card__content -->
		</div><!-- .c-card__link -->
	</div><!-- .c-card -->
</div><!-- #post-XX -->