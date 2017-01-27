<?php
/**
 * The template used for displaying project content on archives
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'portfolio jetpack' );
?>

<div id="post-<?php the_ID(); ?>" <?php post_class() ?>>
	<div class="c-card">
		<div class="c-card__link">
			<a href="<?php the_permalink(); ?>" class="c-card__content-link c-card__frame">
				<?php
				// Output the featured image
				the_post_thumbnail();

				// Also output the markup for the hover image if we have it
                // Make sure that we have the Featured Image component loaded
                if ( function_exists( 'pixelgrade_featured_image_get_hover_id' ) ) {
	                $hover_image_id = pixelgrade_featured_image_get_hover_id();
	                if ( ! empty( $hover_image_id ) ) { ?>

		                <div class="c-card__frame-hover">
			                <?php echo wp_get_attachment_image( $hover_image_id, 'full' ); ?>
		                </div>

	                <?php }
                } ?>
			</a>
			<div class="c-card__content">
				<?php
				$meta                 = array();
				$portfolio_categories = get_the_terms( get_the_ID(), 'jetpack-portfolio-type' );
				$category             = "";
				if ( ! is_wp_error( $portfolio_categories ) && ! empty( $portfolio_categories ) ) {
					$category .= '<ul class="o-inline">' . PHP_EOL;
					foreach ( $portfolio_categories as $portfolio_category ) {
						$category .= '<li><a href="' . esc_url( get_category_link( $portfolio_category->term_id ) ) . '">' . $portfolio_category->name . '</a></li>' . PHP_EOL;
					};
					$category .= '</ul>' . PHP_EOL;
				}
				$meta['category'] = $category;
				$portfolio_tags   = get_the_terms( get_the_ID(), 'jetpack-portfolio-tag' );
				$tags             = "";
				if ( ! is_wp_error( $portfolio_tags ) && ! empty( $portfolio_tags ) ) {
					$tags .= '<ul class="o-inline">' . PHP_EOL;
					foreach ( $portfolio_tags as $portfolio_tag ) {
						$tags .= '<li>' . $portfolio_tag->name . '</li>' . PHP_EOL;
					};
					$tags .= '</ul>' . PHP_EOL;
				}
				$meta['tags']   = $tags;
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

				if ( pixelgrade_option( 'portfolio_items_primary_meta', 'none' ) !== 'none' && ! empty( $meta[ pixelgrade_option( 'portfolio_items_primary_meta' ) ] ) ) {
					echo "<div class='c-card__meta h7'>" . $meta[ pixelgrade_option( 'portfolio_items_primary_meta' ) ] . "</div>";
				}
				if ( pixelgrade_option( 'portfolio_items_title_visibility', true ) ) { ?>
					<h2 class="c-card__title h5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php }
				if ( pixelgrade_option( 'portfolio_items_excerpt_visibility', false ) && get_the_excerpt() ) { ?>
					<div class="c-card__excerpt entry-content">
						<?php the_excerpt(); ?>
					</div>
				<?php }
				if ( pixelgrade_option( 'portfolio_items_secondary_meta', 'none' ) !== 'none' && ! empty( $meta[ pixelgrade_option( 'portfolio_items_secondary_meta' ) ] ) ) {
					echo '<div class="c-card__footer h7">' . $meta[ pixelgrade_option( 'portfolio_items_secondary_meta' ) ] . "</div>";
				} ?>
				<a class="c-card__content-link" href="<?php the_permalink(); ?>"></a>
			</div><!-- .c-card__content -->
		</div><!-- .c-card__link -->
	</div><!-- .c-card -->
</div><!-- #post-XX -->