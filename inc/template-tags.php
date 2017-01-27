<?php
/**
 * Custom template tags for this theme and functions that will result in html output.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Noah
 */

if ( ! function_exists( 'noah_get_cats_list' ) ) {

	/**
	 * Returns HTML with comma separated category links
	 *
	 * @since Noah 1.0
	 *
	 * @param int|WP_Post $post_ID Optional. Post ID or post object.
	 *
	 * @return string
	 */
	function noah_get_cats_list( $post_ID = null ) {

		//use the current post ID is none given
		if ( empty( $post_ID ) ) {
			$post_ID = get_the_ID();
		}

		//obviously pages don't have categories
		if ( 'page' == get_post_type( $post_ID ) ) {
			return '';
		}

		$cats = '';
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'noah' ), '', $post_ID );
		if ( $categories_list && noah_categorized_blog() ) {
			$cats = '<span class="cat-links">' . $categories_list . '</span>';
		}

		return $cats;

	} #function

}

if ( ! function_exists( 'noah_cats_list' ) ) {

	/**
	 * Prints HTML with comma separated category links
	 *
	 * @since Noah 1.0
	 *
	 * @param int|WP_Post $post_ID Optional. Post ID or post object.
	 */
	function noah_cats_list( $post_ID = null ) {

		echo noah_get_cats_list( $post_ID );

	} #function

}

if ( ! function_exists( 'noah_the_first_category' ) ) {
	/**
	 * Prints an anchor of the first category of post
	 *
	 * @since Noah 1.0
	 *
	 * @param int|WP_Post $post_ID Optional. Post ID or post object.
	 * @param string $tag_class Optional. A CSS class that the tag will receive.
	 */
	function noah_the_first_category( $post_ID = null, $tag_class = '' ) {

		$categories = get_the_category();

		if ( empty( $categories ) ) {
			return;
		}
		$category = $categories[0];

		$class_markup = null;

		if ( '' !== $tag_class ) {
			$class_markup = 'class="' . $tag_class . '" ';
		}
		echo '<a ' . $class_markup . 'href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( $category->name ) . '">' . $category->name . '</a>';

	} #function
}

if ( ! function_exists( 'noah_the_older_projects_button' ) ) {
	/**
	 * Prints an anchor to the second page of the jetpack-portfolio archive
	 */
	function noah_the_older_projects_button() {
		$older_posts_link = noah_paginate_url( wp_make_link_relative( get_post_type_archive_link( 'jetpack-portfolio' ) ), 2 );

		if ( ! empty( $older_posts_link ) ) : ?>

			<nav class="navigation posts-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Projects navigation', 'noah' ); ?></h2>
				<div class="nav-links">
					<div class="nav-previous">
						<a href="<?php echo esc_url( $older_posts_link ); ?>"><?php esc_html_e( 'Older projects', 'noah' ); ?></a>
					</div>
				</div>
			</nav>
		<?php endif;
	} #function
}

if ( ! function_exists( 'noah_the_taxonomy_dropdown' ) ) {

	function noah_the_taxonomy_dropdown( $taxonomy, $selected = '' ) {
		$output = '';

		$id = $taxonomy . '-dropdown';

		$terms = get_terms( $taxonomy );

		$taxonomy_obj = get_taxonomy( $taxonomy );
		// bail if we couldn't get the taxonomy object or other important data
		if ( empty( $taxonomy_obj ) || empty( $taxonomy_obj->object_type ) ) {
			return false;
		}

		// get the first post type
		$post_type = reset( $taxonomy_obj->object_type );
		// get the post type's archive URL
		$archive_link = get_post_type_archive_link( $post_type );

		$output .= '<select class="taxonomy-select js-taxonomy-dropdown" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '">';

		$selected_attr = '';
		if ( empty( $selected ) ) {
			$selected_attr = 'selected';
		}
		$output .= '<option value="' . esc_attr( $archive_link ) . '" ' . esc_attr( $selected_attr ) . '>' . esc_html__( 'Everything', 'noah' ) . '</option>';

		foreach ( $terms as $term ) {
			$selected_attr = '';
			if ( ! empty( $selected ) && $selected == $term->slug ) {
				$selected_attr = 'selected';
			}
			$output .= '<option value="' . esc_attr( get_term_link( intval( $term->term_id ), $taxonomy ) ) . '" ' . esc_attr( $selected_attr ) . '>' . esc_html( $term->name ) . '</option>';
		}
		$output .= '</select>';

		// Allow others to have a go at it
		$output = apply_filters( 'noah_the_taxonomy_dropdown', $output, $taxonomy, $selected );

		// Display it
		echo $output;
	} #function
}

if ( ! function_exists( 'noah_add_no_js_hook_to_footer' ) ) {
	function noah_add_no_js_hook_to_footer() { ?>
		<script>
			document.documentElement.className = document.documentElement.className.replace("no-js", "js");
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'noah_add_no_js_hook_to_footer' );

/**
 * Check if we are on blog
 */
function noah_is_blog() {
	global $post;
	$posttype = get_post_type( $post );

	return ( ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && ( $posttype == 'post' ) ) ? true : false;
}

/**
 * Custom template tags for Twenty Fifteen
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

if ( ! function_exists( 'noah_the_comments_navigation' ) ) :
	/**
	 * Display navigation to next/previous comments when applicable.
	 *
	 * @since Noah 1.0
	 */
	function noah_the_comments_navigation() {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<nav class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'noah' ); ?></h2>
				<div class="nav-links">
					<?php
					if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'noah' ) ) ) :
						printf( '<div class="c-btn nav-previous">%s</div>', $prev_link );
					endif;

					if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'noah' ) ) ) :
						printf( '<div class="c-btn nav-next">%s</div>', $next_link );
					endif;
					?>
				</div><!-- .nav-links -->
			</nav><!-- .comment-navigation -->
			<?php
		endif;
	}
endif;