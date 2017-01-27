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

<div id="post-<?php the_ID(); ?>" <?php post_class() ?>>
	<div class="c-card">
		<div class="c-card__link">
			<a class="c-card__content-link" href="<?php the_permalink(); ?>">
				<div class="c-card__frame">
					<?php the_post_thumbnail(); ?>
				</div>
			</a>
			<div class="c-card__content"><?php
				$meta       = array();
				$categories = get_the_terms( get_the_ID(), 'category' );
				$category   = "";
				if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
					$category .= '<ul class="o-inline o-inline-xs">' . PHP_EOL;
					foreach ( $categories as $this_category ) {
						$category .= '<li><a href="' . esc_url( get_category_link( $this_category->term_id ) ) . '">' . $this_category->name . '</a></li>' . PHP_EOL;
					};
					$category .= '</ul>' . PHP_EOL;
				}
				$meta['category'] = $category;
				$tags             = get_the_terms( get_the_ID(), 'tag' );
				$tag              = "";
				if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
					$tag .= '<ul class="o-inline o-inline-xs">' . PHP_EOL;
					foreach ( $tags as $my_tag ) {
						$tag .= '<li>' . $my_tag->name . '</li>' . PHP_EOL;
					};
					$tag .= '</ul>' . PHP_EOL;
				}
				$meta['tags']   = $tag;
				$meta['author'] = get_the_author();
				$meta['date']   = get_the_time( 'j F' );

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

				if ( pixelgrade_option( 'blog_items_primary_meta', 'category' ) !== 'none' && ! empty( $meta[ pixelgrade_option( 'blog_items_primary_meta' ) ] ) ) {
					echo '<div class="c-card__meta h7">' . $meta[ pixelgrade_option( 'blog_items_primary_meta' ) ] . "</div>";
				}
				if ( pixelgrade_option( 'blog_items_title_visibility', true ) ) { ?>
					<h2 class="c-card__title h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php }
				if ( pixelgrade_option( 'blog_items_excerpt_visibility', true ) && get_the_excerpt() ) { ?>
					<div class="c-card__excerpt entry-content">
						<?php the_excerpt(); ?>
					</div>
				<?php }
				if ( pixelgrade_option( 'blog_items_secondary_meta', 'date' ) !== 'none' && ! empty( $meta[ pixelgrade_option( 'blog_items_secondary_meta' ) ] ) ) {
					echo '<div class="c-card__footer h7">' . $meta[ pixelgrade_option( 'blog_items_secondary_meta' ) ] . "</div>";
				}
				?></div>
		</div>
		<span class="c-card__badge u-color-accent"></span>
	</div>
</div>