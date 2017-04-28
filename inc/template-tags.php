<?php
/**
 * Custom template tags for this theme and functions that will result in html output.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Noah Lite
 */

if ( ! function_exists( 'noahlite_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function noahlite_posted_on() {

		echo '<span class="posted-on">' . noahlite_date_link() . '</span>';
	}
endif;

if ( ! function_exists( 'noahlite_date_link' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function noahlite_date_link() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" hidden datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			get_the_date( DATE_W3C ),
			get_the_date(),
			get_the_modified_date( DATE_W3C ),
			get_the_modified_date()
		);

		// Wrap the time string in a link, and preface it with 'Posted on'.
		return sprintf(
		/* translators: %s: post date */
			__( '<span class="screen-reader-text">Posted on</span> %s', 'noah-lite' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
	} #function
endif;

if ( ! function_exists( 'noahlite_get_cats_list' ) ) {

	/**
	 * Returns HTML with comma separated category links
	 *
	 * @since Noah 1.0
	 *
	 * @param int|WP_Post $post_ID Optional. Post ID or post object.
	 *
	 * @return string
	 */
	function noahlite_get_cats_list( $post_ID = null ) {

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
		$categories_list = get_the_category_list( esc_html__( ', ', 'noah-lite' ), '', $post_ID );
		if ( $categories_list && noahlite_categorized_blog() ) {
			$cats = '<span class="cat-links">' . $categories_list . '</span>';
		}

		return $cats;

	} #function

}

if ( ! function_exists( 'noahlite_cats_list' ) ) {

	/**
	 * Prints HTML with comma separated category links
	 *
	 * @since Noah 1.0
	 *
	 * @param int|WP_Post $post_ID Optional. Post ID or post object.
	 */
	function noahlite_cats_list( $post_ID = null ) {

		echo noahlite_get_cats_list( $post_ID );

	} #function

}

if ( ! function_exists( 'noahlite_the_first_category' ) ) {
	/**
	 * Prints an anchor of the first category of post
	 *
	 * @since Noah 1.0
	 *
	 * @param int|WP_Post $post_ID Optional. Post ID or post object.
	 * @param string $tag_class Optional. A CSS class that the tag will receive.
	 */
	function noahlite_the_first_category( $post_ID = null, $tag_class = '' ) {

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

if ( ! function_exists( 'noahlite_the_older_projects_button' ) ) {
	/**
	 * Prints an anchor to the second page of the jetpack-portfolio archive
	 */
	function noahlite_the_older_projects_button( $query = null ) {
		$older_posts_link = noahlite_paginate_url( wp_make_link_relative( get_post_type_archive_link( 'jetpack-portfolio' ) ), 2, false, $query );

		if ( ! empty( $older_posts_link ) ) : ?>

			<nav class="navigation posts-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Projects navigation', 'noah-lite' ); ?></h2>
				<div class="nav-links">
					<div class="nav-previous">
						<a href="<?php echo esc_url( $older_posts_link ); ?>"><?php esc_html_e( 'Older projects', 'noah-lite' ); ?></a>
					</div>
				</div>
			</nav>
		<?php endif;
	} #function
}

if ( ! function_exists( 'noahlite_the_taxonomy_dropdown' ) ) {

	function noahlite_the_taxonomy_dropdown( $taxonomy, $selected = '' ) {
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
		$output .= '<option value="' . esc_attr( $archive_link ) . '" ' . esc_attr( $selected_attr ) . '>' . esc_html__( 'Everything', 'noah-lite' ) . '</option>';

		foreach ( $terms as $term ) {
			$selected_attr = '';
			if ( ! empty( $selected ) && $selected == $term->slug ) {
				$selected_attr = 'selected';
			}
			$output .= '<option value="' . esc_attr( get_term_link( intval( $term->term_id ), $taxonomy ) ) . '" ' . esc_attr( $selected_attr ) . '>' . esc_html( $term->name ) . '</option>';
		}
		$output .= '</select>';

		// Allow others to have a go at it
		$output = apply_filters( 'noahlite_the_taxonomy_dropdown', $output, $taxonomy, $selected );

		// Display it
		echo $output;
	} #function
}

if ( ! function_exists( 'noahlite_add_no_js_hook_to_footer' ) ) {
	function noahlite_add_no_js_hook_to_footer() { ?>
		<script>
			document.documentElement.className = document.documentElement.className.replace("no-js", "js");
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'noahlite_add_no_js_hook_to_footer' );

/**
 * Check if we are on blog
 */
function noahlite_is_blog() {
	global $post;
	$posttype = get_post_type( $post );

	return ( ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && ( $posttype == 'post' ) ) ? true : false;
}

if ( ! function_exists( 'noahlite_the_comments_navigation' ) ) :
	/**
	 * Display navigation to next/previous comments when applicable.
	 *
	 * @since Noah 1.0
	 */
	function noahlite_the_comments_navigation() {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<nav class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'noah-lite' ); ?></h2>
				<div class="nav-links">
					<?php
					if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'noah-lite' ) ) ) :
						printf( '<div class="c-btn nav-previous">%s</div>', $prev_link );
					endif;

					if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'noah-lite' ) ) ) :
						printf( '<div class="c-btn nav-next">%s</div>', $next_link );
					endif;
					?>
				</div><!-- .nav-links -->
			</nav><!-- .comment-navigation -->
			<?php
		endif;
	}
endif;

/**
 * Display the classes for the header element.
 *
 * @param string|array $class One or more classes to add to the class list.
 */
function noahlite_header_class( $class = '' ) {
	// Separates classes with a single space, collates classes for header element
	echo 'class="' . join( ' ', noahlite_get_header_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for the header element as an array.
 *
 * @param string|array $class One or more classes to add to the class list.
 *
 * @return array Array of classes.
 */
function noahlite_get_header_class( $class = '' ) {
	$classes = array();

	$classes[] = 'site-header';

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the list of CSS header classes for the current post or page
	 *
	 * @param array $classes An array of header classes.
	 * @param array $class   An array of additional classes added to the header.
	 */
	$classes = apply_filters( 'noahlite_header_class', $classes, $class );

	return array_unique( $classes );
}

/**
 * Get the markup for the left menu.
 *
 * @return false|object
 */
function noahlite_header_get_the_left_menu() {
	return wp_nav_menu( apply_filters( 'noahlite_header_primary_left_nav_args', array(
		'theme_location'  => 'primary-left',
		'menu_id'         => 'menu-1',
		'container'       => 'nav',
		'container_class' => '',
		'fallback_cb'     => false,
		'echo'            => false,
	) ) );
}

/**
 * Get the markup for the right menu.
 *
 * @return false|object
 */
function noahlite_header_get_the_right_menu() {
	return wp_nav_menu( apply_filters( 'noahlite_header_primary_right_nav_args', array(
		'theme_location'  => 'primary-right',
		'menu_id'         => 'menu-2',
		'container'       => 'nav',
		'container_class' => '',
		'fallback_cb'     => 'wp_page_menu', // at least one of the menus needs to have this fallback
		'echo'            => false,
	) ) );
}

/**
 * Display a hero single image
 *
 * @param int $id (default: null)
 * @param int $opacity (default: 100)
 */
function noahlite_hero_the_background_image ($id = null, $opacity = 100 ) {

	// @todo move this in the loop function
	//if we have no ID then use the post thumbnail, if present
	if ( empty( $id ) ) {
		$id = get_post_thumbnail_id( get_the_ID() );
	}

	//do nothing if we have no ID
	if ( empty( $id ) ) {
		return;
	}

	//sanitize the opacity
	if ( '' === $opacity ) {
		$opacity = 100;
	}

	$opacity = 'style="opacity: ' .(int) $opacity / 100 . ';"';

	$output = '';

	$image_meta = get_post_meta( $id, '_wp_attachment_metadata', true );
	$image_full_size = wp_get_attachment_image_src( $id, 'full-size' );

	//the responsive image
	$image_markup = '<img class="c-hero__image" itemprop="image" src="' . esc_url( $image_full_size[0] ) . '" alt="' . esc_attr( noahlite_hero_get_img_alt( $id ) ) . '" '. $opacity . '>';
	$output .= wp_image_add_srcset_and_sizes( $image_markup, $image_meta, $id ) . PHP_EOL;

	//allow others to make changes
	$output = apply_filters( 'noahlite_hero_the_background_image', $output, $id, $opacity );

	echo $output;
}

function noahlite_hero_get_img_alt( $image ) {
	$img_alt = trim( strip_tags( get_post_meta( $image, '_wp_attachment_image_alt', true ) ) );
	return $img_alt;
}

/**
 * Checks to see if we're on the homepage or not.
 */
function noahlite_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}

/**
 * Displays the navigation to next/previous post, when applicable.
 *
 * @param array $args Optional. See get_the_post_navigation() for available arguments.
 *                    Default empty array.
 */
function noahlite_the_post_navigation( $args = array() ) {
	echo noahlite_get_the_post_navigation( $args );
}

/**
 * Retrieves the navigation to next/previous post, when applicable.
 *
 * @param array $args {
 *     Optional. Default post navigation arguments. Default empty array.
 *
 * @type string $prev_text Anchor text to display in the previous post link. Default '%title'.
 * @type string $next_text Anchor text to display in the next post link. Default '%title'.
 * @type bool $in_same_term Whether link should be in a same taxonomy term. Default false.
 * @type array|string $excluded_terms Array or comma-separated list of excluded term IDs. Default empty.
 * @type string $taxonomy Taxonomy, if `$in_same_term` is true. Default 'category'.
 * @type string $screen_reader_text Screen reader text for nav element. Default 'Post navigation'.
 * }
 * @return string Markup for post links.
 */
function noahlite_get_the_post_navigation( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'prev_text'          => '%title',
		'next_text'          => '%title',
		'in_same_term'       => false,
		'excluded_terms'     => '',
		'taxonomy'           => 'category',
		'screen_reader_text' => __( 'Post navigation', 'noah-lite' ),
	) );

	$navigation = '';

	$previous = get_previous_post_link(
		'<div class="nav-previous"><span class="h3 nav-previous-title">%link</span>' . '<span class="h7 u-color-accent">' . __( 'Previous', 'noah-lite' ) . '</span></div>',
		$args['prev_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	$next = get_next_post_link(
		'<div class="nav-next"><span class="h3 nav-next-title">%link</span>' . '<span class="h7 u-color-accent">' . __( 'Next', 'noah-lite' ) . '</span></div>',
		$args['next_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	// Only add markup if there's somewhere to navigate to.
	if ( $previous || $next ) {
		$navigation = _navigation_markup( $previous . $next, 'post-navigation', $args['screen_reader_text'] );
	}

	return $navigation;
}

/*
 * Return the HTML markup of the author bio.
 */
function noahlite_get_the_author_info_box() {
	$author_details = '';

	$author_details .= '<aside class="c-author" itemscope itemtype="http://schema.org/Person">' . PHP_EOL;
	$author_details .= '<div class="c-author__avatar">' . PHP_EOL;

	/**
	 * Filter the author bio avatar size, the Jetpack content options way.
	 *
	 * @param int $size The avatar height and width size in pixels.
	 */
	$author_bio_avatar_size = apply_filters( 'jetpack_author_bio_avatar_size', 100 );

	$author_details .= get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );

	$author_details .= '</div><!-- .c-author__avatar -->' . PHP_EOL;
	$author_details .= '<div class="c-author__details">' . PHP_EOL;
	$author_details .= '<span class="c-author__label h7">' . esc_html__( 'Posted by', 'noah-lite' ) . '</span>' . PHP_EOL;
	$author_details .= '<span class="c-author__name h2">' . get_the_author() . '</span>' . PHP_EOL;
	$author_details .= '<p class="c-author__description" itemprop="description">' . get_the_author_meta( 'description' ) . '</p>' . PHP_EOL;
	$author_details .= '<div class="o-inline o-inline-xs h7">' .PHP_EOL;
	$author_details .= '<a class="_color-inherit" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author" title="' . esc_attr( sprintf( __( 'View all posts by %s', 'noah-lite' ), get_the_author() ) ) . '">' . esc_html__( 'All posts', 'noah-lite' ) . '</a>' . PHP_EOL;
	$author_details .= noahlite_get_author_bio_links();

	$author_details .= '</div>' . PHP_EOL;
	$author_details .= '</div><!-- .c-author__details -->' . PHP_EOL;
	$author_details .= '</aside><!-- .c-author -->' . PHP_EOL;

	return $author_details;
}

if ( ! function_exists( 'noahlite_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for posts on archives.
	 */
	function noahlite_entry_footer( $post_id ) {
		edit_post_link( __( 'Edit', 'noah-lite' ), '<p><span class="edit-link">', '</span></p>', $post_id );
	}
endif;


if ( ! function_exists( 'noahlite_single_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags, Jetpack likes, shares, related, and comments.
	 */
	function noahlite_single_entry_footer() {
		edit_post_link( __( 'Edit', 'noah-lite' ), '<span class="edit-link">', '</span>' );
	} #function
endif;

if ( ! function_exists( 'noahlite_get_author_bio_links' ) ) :
	/**
	 * Return the markup for the author bio links.
	 * These are the links/websites added by one to it's Gravatar profile
	 *
	 * @param int|WP_Post $post_id Optional. Post ID or post object.
	 * @return string The HTML markup of the author bio links list.
	 */
	function noahlite_get_author_bio_links( $post_id = null ) {
		$post = get_post( $post_id );
		$markup = '';
		if ( empty( $post ) ) {
			return $markup;
		}
		$str = wp_remote_fopen( 'https://www.gravatar.com/' . md5( strtolower( trim( get_the_author_meta( 'user_email' ) ) ) ) . '.php' );
		$profile = unserialize( $str );
		if ( is_array( $profile ) && ! empty( $profile['entry'][0]['urls'] ) ) {
			$markup .= '<div class="c-author__links o-inline o-inline-s u-color-accent">' . PHP_EOL;
			foreach ( $profile['entry'][0]['urls'] as $link ) {
				if ( ! empty( $link['value'] ) && ! empty( $link['title'] ) ) {
					$markup .= '<a class="c-author__social-link" href="' . esc_url( $link['value'] ) . '" target="_blank">' . esc_html( $link['title'] ) . '</a>' . PHP_EOL;
				}
			}
			$markup .= '</div><!-- .c-author__links -->' . PHP_EOL;
		}
		return $markup;
	} #function
endif;
