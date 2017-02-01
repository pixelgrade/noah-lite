<?php
/**
 * The template used for displaying project content on archives
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( '' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class() ?>>
	<div class="c-card">
		<div class="c-card__link">
			<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
			<a class="c-card__content-link" href="<?php the_permalink(); ?>">
				<div class="c-card__frame">
					<?php the_post_thumbnail(); ?>
				</div>
			</a>
			<?php } ?>

			<div class="c-card__content"><?php
				// Gather up all the meta we might need to display
				// But first initialize please
				$meta = array(
					'category' => false,
					'tags' => false,
					'author' => false,
					'date' => false,
					'comments' => false,
				);

				// And get the options
				$blog_items_primary_meta = get_theme_mod( 'noah_blog_items_primary_meta', 'category' );
				$blog_items_secondary_meta = get_theme_mod( 'noah_blog_items_secondary_meta', 'date' );

				if ( ( noah_blog_items_primary_meta_control_show() && 'category' == $blog_items_primary_meta )
				     || ( noah_blog_items_secondary_meta_control_show() && 'category' == $blog_items_secondary_meta ) ) {
					$categories = get_the_terms( get_the_ID(), 'category' );
					$category   = "";
					if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
						$category .= '<ul class="o-inline o-inline-xs cats">' . PHP_EOL;
						foreach ( $categories as $this_category ) {
							$category .= '<li><a href="' . esc_url( get_category_link( $this_category->term_id ) ) . '">' . $this_category->name . '</a></li>' . PHP_EOL;
						};
						$category .= '</ul>' . PHP_EOL;
					}
					$meta['category'] = $category;
				}

				if ( ( noah_blog_items_primary_meta_control_show() && 'tags' == $blog_items_primary_meta )
				     || ( noah_blog_items_secondary_meta_control_show() && 'tags' == $blog_items_secondary_meta ) ) {
					$tags = get_the_terms( get_the_ID(), 'tag' );
					$tag  = "";
					if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
						$tag .= '<ul class="o-inline o-inline-xs tags">' . PHP_EOL;
						foreach ( $tags as $my_tag ) {
							$tag .= '<li>' . $my_tag->name . '</li>' . PHP_EOL;
						};
						$tag .= '</ul>' . PHP_EOL;
					}
					$meta['tags'] = $tag;
				}

				$meta['author'] = '<span class="byline">' . get_the_author() . '</span>';
				$meta['date']   = '<span class="posted-on">' . noah_date_link() . '</span>';

				$comments_number = get_comments_number(); // get_comments_number returns only a numeric value
				if ( comments_open() ) {
					if ( $comments_number == 0 ) {
						$comments = __( 'No Comments', 'noah' );
					} elseif ( $comments_number > 1 ) {
						$comments = $comments_number . ' ' . __( 'Comments', 'noah' );
					} else {
						$comments = __( '1 Comment', 'noah' );
					}
					$meta['comments'] = '<a href="' . esc_url( get_comments_link() ) . '">' . $comments . '</a>';
				} else {
					$meta['comments'] = '';
				}

				// Display the post's details
				if ( noah_blog_items_primary_meta_control_show() && $blog_items_primary_meta !== 'none' && ! empty( $meta[ $blog_items_primary_meta ] ) ) {
					echo '<div class="c-card__meta h7">' . $meta[ $blog_items_primary_meta ] . "</div>";
				}

				if ( get_theme_mod( 'noah_blog_items_title_visibility', true ) ) { ?>
					<h2 class="c-card__title h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php }

				if ( noah_blog_items_excerpt_visibility_control_show() && get_theme_mod( 'noah_blog_items_excerpt_visibility', true ) && get_the_excerpt() ) { ?>
					<div class="c-card__excerpt entry-content">
						<?php the_excerpt(); ?>
					</div>
				<?php }

				if ( noah_blog_items_secondary_meta_control_show() && $blog_items_secondary_meta !== 'none' && ! empty( $meta[ $blog_items_secondary_meta ] ) ) {
					echo '<div class="c-card__footer h7">' . $meta[ $blog_items_secondary_meta ] . "</div>";
				}

				noah_entry_footer( get_the_ID() );
				?></div><!-- .c-card__content -->
		</div><!-- .c-card__link -->
		<span class="c-card__badge u-color-accent"></span>
	</div><!-- .c-card -->
</article><!-- #post-XX -->