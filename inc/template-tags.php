<?php
/**
 * Custom template tags for this theme and functions that will result in html output.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Noah
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
 * @param string $location The place (template) where the classes are displayed. This is a hint for filters.
 */
function pixelgrade_header_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes for header element
	echo 'class="' . join( ' ', pixelgrade_get_header_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the header element as an array.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param string $location The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function pixelgrade_get_header_class( $class = '', $location = '' ) {
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
	$classes = apply_filters( 'pixelgrade_header_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Get the markup for the left menu.
 *
 * @return false|object
 */
function pixelgrade_header_get_the_left_menu() {
	return wp_nav_menu( apply_filters( 'pixelgrade_header_primary_left_nav_args', array(
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
function pixelgrade_header_get_the_right_menu() {
	return wp_nav_menu( apply_filters( 'pixelgrade_header_primary_right_nav_args', array(
		'theme_location'  => 'primary-right',
		'menu_id'         => 'menu-2',
		'container'       => 'nav',
		'container_class' => '',
		'fallback_cb'     => false,
		'echo'            => false,
	) ) );
}

/**
 * Display the classes for the footer element.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param string $location The place (template) where the classes are displayed. This is a hint for filters.
 */
function pixelgrade_footer_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes for footer element
	echo 'class="' . join( ' ', pixelgrade_get_footer_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the footer element as an array.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param string $location The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function pixelgrade_get_footer_class( $class = '', $location = '' ) {
	$classes = array();

	$classes[] = 'c-footer';
	$classes[] = 'u-container-sides-spacings';
	$classes[] = 'u-content_container_margin_top';

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
	 * Filters the list of CSS footer classes for the current post or page
	 *
	 * @param array $classes An array of footer classes.
	 * @param array $class   An array of additional classes added to the footer.
	 */
	$classes = apply_filters( 'pixelgrade_footer_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Display the classes for the hero element.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 */
function pixelgrade_hero_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes for hero element
	echo 'class="' . join( ' ', pixelgrade_get_hero_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the hero element as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function pixelgrade_get_hero_class( $class = '', $location = '' ) {
	$classes = array();

	$classes[] = 'c-hero';

	//add the hero height class
	$classes[] = pixelgrade_hero_get_height( $location );

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
	 * Filters the list of CSS hero classes for the current post or page
	 *
	 * @param array $classes An array of hero classes.
	 * @param array $class   An array of additional classes added to the hero.
	 * @param string|array $location   The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'pixelgrade_hero_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Display the attributes for the hero element.
 *
 * @param string|array $attribute One or more attributes to add to the attributes list.
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 *
 * @return bool
 */
function pixelgrade_hero_slider_attributes( $attribute = '', $post = null ) {
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	//get the attributes
	$attributes = pixelgrade_hero_get_slider_attributes( $attribute, $post->ID );

	//generate a string attributes array, like array( 'rel="test"', 'href="boom"' )
	$full_attributes = array();
	foreach ($attributes as $name => $value ) {
		//we really don't want numeric keys as attributes names
		if ( ! empty( $name ) && ! is_numeric( $name ) ) {
			//if we get an array as value we will add them comma separated
			if ( ! empty( $value ) && is_array( $value ) ) {
				$value = join( ', ', $value );
			}

			//if we receive an empty array entry (but with a key) we will treat it like an attribute without value (i.e. itemprop)
			if ( empty( $value ) ) {
				$full_attributes[] = $name;
			} else {
				$full_attributes[] = $name . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	if ( ! empty( $full_attributes ) ) {
		echo join( ' ', $full_attributes );
	}
}

/**
 * @param string|array $attribute One or more attributes to add to the attributes list.
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 *
 * @return array|bool
 */
function pixelgrade_hero_get_slider_attributes( $attribute = array(), $post = null ) {
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	$attributes = array();

	if ( ! empty( $attribute ) ) {
		$attributes = array_merge( $attributes, $attribute );
	} else {
		// Ensure that we always coerce class to being an array.
		$attribute = array();
	}

	// Should we autoplay the slideshow?
	$slider_autoplay = get_post_meta( $post->ID, '_hero_slideshow_options__autoplay', true );
	$slider_delay = $slider_autoplay ? get_post_meta( $post->ID, '_hero_slideshow_options__delay', true ) : false;

	if ( $slider_autoplay ) {
		$attributes['data-autoplay'] = '';
		$attributes['data-autoplay-delay'] = $slider_delay * 1000;
	}

	/**
	 * Filters the list of body attributes for the current post or page.
	 *
	 * @since 2.8.0
	 *
	 * @param array $attributes An array of attributes.
	 * @param array $attribute  An array of additional attributes added to the element.
	 */
	$attributes = apply_filters( 'pixelgrade_hero_slider_attributes', $attributes, $attribute );

	return array_unique( $attributes );
}

/**
 * Display the inline style based on the current post's hero background color setting
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 *
 * @return bool
 */
function pixelgrade_hero_background_color_style( $post = null ) {
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	$output = '';

	$background_color = trim( pixelgrade_hero_get_background_color( $post ) );
	if ( ! empty( $background_color ) ) {
		$output .= 'style="background-color: ' . $background_color . ';"';
	}

	//allow others to make changes
	$output = apply_filters( 'pixelgrade_hero_the_background_color_style', $output, $post );

	echo $output;
}

/**
 * Get the hero background color meta value. It will return the default color string in case the meta is empty or invalid (like '#')
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 * @param string $default Optional. The default hexa color string to return in case the meta value is empty
 *
 * @return bool|string
 */
function pixelgrade_hero_get_background_color( $post = null, $default = '#333' ){
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	if ( get_post_type( $post ) == 'page' ) {
		$color = get_post_meta( $post->ID, '_hero_background_color', true );
	} else {
		$color = get_post_meta( $post->ID, '_project_color', true );
	}

	//use a default color in case something went wrong - actually a gray
	if ( empty( $color ) || '#' == $color ) {
		$color = $default;
	}

	return $color;
}

/**
 * Display the classes for each hero wrapper element.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 * @param string $prefix Optional. A prefix to add to the provided classes.
 */
function pixelgrade_hero_wrapper_class( $class = '', $location = '', $prefix = 'c-hero__wrapper--' ) {
	// Separates classes with a single space, collates classes for hero element
	echo 'class="' . join( ' ', pixelgrade_get_hero_wrapper_class( $class, $location, $prefix ) ) . '"';
}

/**
 * Retrieve the classes for the hero element as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 * @param string $prefix Optional. A prefix to add to the provided classes.
 *
 * @return array Array of classes.
 */
function pixelgrade_get_hero_wrapper_class( $class = '', $location = '', $prefix = 'c-hero__wrapper--' ) {
	$classes = array();

	$classes[] = 'c-hero__wrapper';
	$classes[] = 'c-hero__layer';

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		//if we have a prefix then we need to add it to every class
		if ( ! empty( $prefix ) && is_string( $prefix ) ) {
			foreach ( $class as $key => $value ) {
				$class[ $key ] = $prefix . $value;
			}
		}

		//Finally merge the classes into the main array
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
	 * @param string|array $location   The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'pixelgrade_hero_wrapper_class', $classes, $class, $location, $prefix );

	return array_unique( $classes );
}

/**
 * Determine if we actually have data that can make up a hero. Prevent empty markup from being shown.
 *
 * @param string|array $location Optional. The place (template) where this is needed.
 *
 * @return bool
 */
function pixelgrade_hero_is_hero_needed( $location = '' ) {
	$is_needed = true;

	// get all the images/videos/featured projects ids that we will use as slides (we also cover for when there are none)
	$slides = pixelgrade_hero_get_slides_ids();

	if ( empty( $slides ) ) {
		$is_needed = false;
	}

	// Allow others to short-circuit us on this one
	return apply_filters( 'pixelgrade_hero_is_hero_needed', $is_needed, $location );
}

/**
 * Return the CSS class corresponding to the set height of the hero.
 *
 * @param string|array $location Optional. The place (template) where this is needed.
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 *
 * @return bool
 */
function pixelgrade_hero_get_height( $location = '', $post = null ) {
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	//handle the map hero separately
	if ( pixelgrade_in_location( 'map', $location ) ) {
		$hero_height = trim( get_post_meta( $post->ID, '_hero_map_height', true ) );
	} else {
		$hero_height = trim( get_post_meta( $post->ID, '_hero_height', true ) );
	}

	//by default we show a full-height hero/header
	if ( empty( $hero_height ) ) {
		$hero_height = 'c-hero--full';
	}

	return $hero_height;
}

/**
 * Returns the attachment ids corresponding to each slide.
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 *
 * @return array|bool|mixed
 */
function pixelgrade_hero_get_slides_ids( $post = null ){
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	$to_return = array();

	if ( has_post_thumbnail( $post ) ) {
		$to_return[] = get_post_thumbnail_id( $post );
	}

	//allow others to make changes
	$to_return = apply_filters( 'pixelgrade_hero_slides_ids', $to_return, $post );

	// now return the slides in this order: images, videos, projects
	return $to_return;
}

/**
 * Determine whether a post has a hero description.
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Defaults to current post.
 *
 * @return bool
 */
function pixelgrade_hero_has_description( $post = null ){
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( empty( $post ) && is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return false;
	}

	$has_desc = false;

	if ( is_page( $post->ID ) && get_page_template_slug( $post->ID ) == 'page-templates/contact.php' ) {
		$has_desc = true;
	} elseif ( is_single( $post->ID ) && 'post' === get_post_type( $post->ID ) ) {
		$has_desc = true;
	} else {
		$cover_description = get_post_meta( $post->ID, '_hero_content_description', true );
		if ( ! empty( $cover_description ) ) {
			$has_desc = true;
		}
	}

	return apply_filters( 'pixelgrade_hero_has_description', $has_desc, $post );
}

/**
 * Displays the hero slide background markup.
 *
 * @param null $attachment_ID
 * @param string $img_opacity
 *
 * @return bool
 */
function pixelgrade_hero_the_slide_background( $attachment_ID = null, $img_opacity = '100' ) {

	//do nothing if we have no ID
	if ( empty( $attachment_ID ) ) {
		return false;
	}

	$mime_type = get_post_mime_type( $attachment_ID );
	//bail if we couldn't get a mime type
	if ( empty( $mime_type ) ) {
		return false;
	}

	//sanitize the opacity
	//if it's empty (probably because someone hasn't saved the post with the new metas) give it the default value
	if ( '' === $img_opacity ) {
		$img_opacity = '100';
	}

	//get the attachment meta data
	$attachment_fields = get_post_custom( $attachment_ID );

	$type = false;
	if ( false !== strpos( $mime_type, 'video' ) ) {
		// this is for sure an video
		$type = 'video';
	} elseif ( false !== strpos( $mime_type, 'image' ) ) { //we have some sort of image mime type
		if ( ! empty( $attachment_fields['_video_url'][0] ) ) {
			// the cruel, but interesting, thing is that an image can be a video
			$type = 'video';
		} else {
			$type = 'image';
		}
	}

	//bail if we could not determine a type
	if ( empty( $type ) ) {
		return false;
	}

	switch ( $type ) {
		case 'video' :
			pixelgrade_hero_the_background_video( $attachment_ID, $img_opacity, false );
			break;

		case 'image' :
			pixelgrade_hero_the_background_image( $attachment_ID, $img_opacity );
			break;

		default :
			break;
	}

	//maybe someone is wondering if we have succeeded
	return true;
}

/**
 * Display a hero single image
 *
 * @param int $id (default: null)
 * @param int $opacity (default: 100)
 */
function pixelgrade_hero_the_background_image ($id = null, $opacity = 100 ) {

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
	$image_markup = '<img class="c-hero__image" itemprop="image" src="' . esc_url( $image_full_size[0] ) . '" alt="' . esc_attr( pixelgrade_hero_get_img_alt( $id ) ) . '" '. $opacity . '>';
	$output .= wp_image_add_srcset_and_sizes( $image_markup, $image_meta, $id ) . PHP_EOL;

	//allow others to make changes
	$output = apply_filters( 'pixelgrade_hero_the_background_image', $output, $id, $opacity );

	echo $output;
}

function pixelgrade_hero_get_img_alt( $image ) {
	$img_alt = trim( strip_tags( get_post_meta( $image, '_wp_attachment_image_alt', true ) ) );
	return $img_alt;
}

/**
 * Display a hero video
 *
 * @param int $id (default: null)
 * @param int $opacity (default: 100)
 * @param bool $ignore_video (default: false)
 */
function pixelgrade_hero_the_background_video ($id = null, $opacity = 100, $ignore_video = false ) {
	//do nothing if we have no ID
	if ( empty( $id ) ) {
		return;
	}

	$output = '';

	$mime_type = get_post_mime_type( $id );

	//sanitize the opacity
	if ( '' === $opacity ) {
		$opacity = 100;
	}

	$opacity = 'style="opacity: ' .(int) $opacity / 100 . ';"';

	$attachment = get_post( $id );

	if ( false !== strpos( $mime_type, 'video' ) ) {
		$image = "";
		if ( has_post_thumbnail( $id ) ) {
			$image = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
		}
		$output .= '<div class="c-hero__video video-placeholder" data-src="' . $attachment->guid . '" data-poster="' . $image . '" ' . $opacity . '"></div>';
	} elseif ( false !== strpos( $mime_type, 'image' ) ) {

		$attachment_fields = get_post_custom( $id );
		$image_meta = get_post_meta( $id, '_wp_attachment_metadata', true );
		$image_full_size = wp_get_attachment_image_src( $id, 'full-size' );

		//prepare the attachment fields
		if ( ! isset( $attachment_fields['_wp_attachment_image_alt'] ) ) {
			$attachment_fields['_wp_attachment_image_alt'] = array('');
		} else {
			$attachment_fields['_wp_attachment_image_alt'][0] = trim( strip_tags( $attachment_fields['_wp_attachment_image_alt'][0] ) );
		}
		if ( ! isset( $attachment_fields['_video_autoplay'][0] ) ) {
			$attachment_fields['_video_autoplay'] = array('');
		}

		// prepare the video url if there is one
		$video_url = ( isset( $attachment_fields['_link_media_to'][0] ) && $attachment_fields['_link_media_to'][0] == 'custom_video_url' && isset( $attachment_fields['_video_url'][0] ) && ! empty( $attachment_fields['_video_url'][0]) ) ? esc_url( $attachment_fields['_video_url'][0] ) : '';

		if ( ! $ignore_video && ! empty( $video_url ) ) {
			// should the video auto play?
			$video_autoplay = ( $attachment_fields['_link_media_to'][0] == 'custom_video_url' && $attachment_fields['_video_autoplay'][0] === 'on' ) ? 'on' : '';
			$output .= '<div class="' . ( ! empty( $video_url ) ? 'c-hero__video video' : '' ) . ( $video_autoplay == 'on' ? ' video_autoplay' : '' ) .'" itemscope itemtype="http://schema.org/ImageObject" ' . ( ! empty( $video_autoplay ) ? 'data-video_autoplay="'.$video_autoplay.'"' : '') . ' ' . $opacity . '>' . PHP_EOL;
			//the responsive image
			$image_markup = '<img data-rsVideo="'  . $video_url . '" class="rsImg" src="' . esc_url( $image_full_size[0] ) . '" alt="' . $attachment_fields['_wp_attachment_image_alt'][0] .'" />';
			$output .= wp_image_add_srcset_and_sizes( $image_markup, $image_meta, $id ) . PHP_EOL;
			$output .= '</div>';
		}
	}

	//allow others to make changes
	$output = apply_filters( 'pixelgrade_hero_the_background_video', $output, $id, $opacity, $ignore_video );

	echo $output;
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
	$author_details .= '<p class="c-author__name h2">' . get_the_author() . '</p>' . PHP_EOL;
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
		edit_post_link( __( 'Edit', 'noah-lite' ), '<span class="edit-link">', '</span>', $post_id );
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
					$markup .= '<a class="c-author__social-link" href="' . esc_url( $link['value'] ) . '" target="_blank">' . $link['title'] . '</a>' . PHP_EOL;
				}
			}
			$markup .= '</div><!-- .c-author__links -->' . PHP_EOL;
		}
		return $markup;
	} #function
endif;
