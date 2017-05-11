<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Noah Lite
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function noahlite_body_classes( $classes ) {

	$classes[] = 'body';
	$classes[] = 'u-content-background';

	$classes[] = 'u-static-header';

	$classes[] = 'u-full-width-header';
	
	$classes[] = 'u-footer-layout-stacked';

	if ( is_singular( 'jetpack-portfolio' ) ) {
		$classes[] = noahlite_get_single_project_class();
	}

	if ( is_customize_preview() ) {
		$classes[] = 'customizer-preview';
	}

	if ( is_page_template( 'page-templates/portfolio-page.php') && has_post_thumbnail() ) {
		$classes[] = 'has-hero';
	}

	return $classes;
}
add_filter( 'body_class', 'noahlite_body_classes', 10, 1 );

/**
 * Display the classes for the portfolio wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 */
function noahlite_portfolio_class( $class = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', noahlite_get_portfolio_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 *
 * @return array Array of classes.
 */
function noahlite_get_portfolio_class( $class = '' ) {

	$classes = array();

	$classes[] = 'o-grid';
	$classes[] = 'c-gallery c-gallery--portfolio';
	// layout
	$classes[]  = 'c-gallery--regular';

	// items per row
	$columns        = 3;
	$columns_at_lap = $columns >= 3 ? $columns - 1 : $columns;
	$columns_at_pad = $columns_at_lap >= 2 ? $columns_at_lap - 1 : $columns_at_lap;

	$columns_class = 'o-grid--' . $columns . 'col-@desk o-grid--' . $columns_at_lap . 'col-@lap o-grid--' . $columns_at_pad . 'col-@pad';
	// title position
	$title_position_class = 'c-gallery--title-below';
	$title_alignment_class = 'c-gallery--title-' . get_theme_mod( 'noahlite_portfolio_items_title_alignment_nearby', 'left' );

	$classes[] = $title_position_class;
	$classes[] = $title_alignment_class;
	$classes[] = $columns_class;

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
	 * Filters the list of CSS classes for the portfolio wrapper.
	 *
	 * @param array $classes An array of header classes.
	 * @param array $class An array of additional classes added to the portfolio wrapper.
	 */
	$classes = apply_filters( 'noahlite_portfolio_class', $classes, $class );

	return array_unique( $classes );
}

/**
 * Display the classes for the blog wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 */
function noahlite_blog_class( $class = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', noahlite_get_blog_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 *
 * @return array Array of classes.
 */
function noahlite_get_blog_class( $class = '' ) {

	$classes = array();

	$classes[] = 'o-grid';
	$classes[] = 'c-gallery  c-gallery--blog';
	// layout
	$classes[] = 'c-gallery--masonry js-masonry';

	// items per row
	$columns        = 3;
	$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
	$columns_at_pad = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;

	$columns_class = 'o-grid--' . $columns . 'col-@desk o-grid--' . $columns_at_lap . 'col-@lap o-grid--' . $columns_at_pad . 'col-@pad';
	// title position
	$title_position = 'below';
	$title_position_class = 'c-gallery--title-' . $title_position;
	$title_alignment_class = 'c-gallery--title-left';


	$classes[] = $title_position_class;
	$classes[] = $title_alignment_class;
	$classes[] = $columns_class;

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
	 * Filters the list of CSS classes for the portfolio wrapper.
	 *
	 * @param array $classes An array of header classes.
	 * @param array $class An array of additional classes added to the portfolio wrapper.
	 */
	$classes = apply_filters( 'noahlite_blog_class', $classes, $class );

	return array_unique( $classes );
}

/**
 * Add custom classes for individual posts
 *
 * @since Noah 1.0
 *
 * @param array $classes An array of post classes.
 * @param array $class   An array of additional classes added to the post.
 * @param int   $post_id The post ID.
 *
 * @return array
 */
function noahlite_post_classes( $classes, $class, $post_id ) {

	if ( is_page( $post_id ) ) {
		return $classes;
	}

	if ( is_category() || is_tag() || is_date() || is_author() || is_author() || is_home() || is_search() || is_post_type_archive( 'jetpack-portfolio' ) || is_category() || is_tag() || ( 'jetpack-portfolio' == get_post_type( $post_id ) && ! in_the_loop() ) ) {
		$classes[] = 'c-gallery__item';

		// get info about the width and height of the image
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		// if the image is landscape double its width
		$is_landscape = $image_data[1] > $image_data[2];

		if ( has_post_thumbnail() ) {
		    $classes[] = 'c-gallery__item--' . ( $is_landscape ? 'landscape' : 'portrait' );
        } else {
            $classes[] = 'c-gallery__item--no-image';
        }
	} elseif ( is_singular( 'jetpack-portfolio' ) && in_the_loop() ) {
		$classes[] = 'c-project';
	} else {
		$classes[] = 'c-article';
	}

	return $classes;
}
add_filter( 'post_class', 'noahlite_post_classes', 10, 3 );

/**=== HELPERS ===**/

if ( ! function_exists( 'noahlite_get_single_project_class' ) ) {

	/**
	 * Returns the layout type based on the amount of content and the number of [gallery] shortcodes
	 *
	 * @return string
	 */
	function noahlite_get_single_project_class() {
		global $post;

		// search for all the [gallery] shortcodes
		preg_match_all( '/' . get_shortcode_regex( array( 'gallery' ) ) . '/', $post->post_content, $matches, PREG_SET_ORDER );

		if ( empty( $matches ) ) {
			return 'has-mixed-content';
		}

		if ( count( $matches ) == 1 ) {

			$excludes = '';

			foreach ( $matches as $key => $match ) {
				if ( ! empty( $match[0] ) ) {
					$excludes .= preg_quote( $match[0] );

					if ( $key + 1 < count( $matches ) ) {
						$excludes .= '|';
					}
				}
			}

			$remains = preg_split( "/(" . $excludes . ")/", $post->post_content );

			$remains = array_diff( $remains, array( '', '&amp;nbsp;&#10;&#10;', '&#10;&#10;&amp;nbsp;' ) );

			// the $remains are blocks of text, the lack of them catalogs our project as a media-only type
			if ( count( $remains ) < 1 ) {
				return 'has-media-only fill-page js-project';
			}
		}

		return 'has-mixed-content';
	}
}

/**
 * We force that every gallery in a project or blog post uses the same settings and behaves the same.
 *
 * @param $out
 * @param $pairs
 * @param $atts
 * @param $shortcode
 *
 * @return mixed
 */
function noahlite_normalize_gallery_atts( $out, $pairs, $atts, $shortcode ) {
	if ( intval( $out['columns'] ) <= 3 ) {
		$out['size'] = 'large';
	} else {
		$out['size'] = 'medium';
	}

	return $out;
}
add_filter( 'shortcode_atts_gallery', 'noahlite_normalize_gallery_atts', 10, 4 );

if ( ! function_exists( 'noahlite_categorized_blog' ) ) {

	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @since Noah 1.0
	 *
	 * @return bool
	 */
	function noahlite_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'noahlite_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'noahlite_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so noahlite_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so noahlite_categorized_blog should return false.
			return false;
		}
	}

}

/**
 * We don't want comments on an attachment page
 *
 * @param $open
 * @param $post_id
 *
 * @return bool
 */
function noahlite_remove_att_comments( $open, $post_id ) {
	$post = get_post( $post_id );
	if ( $post->post_type == 'attachment' ) {
		return false;
	}

	return $open;
}
add_filter( 'comments_open', 'noahlite_remove_att_comments', 10, 2 );

/**
 * Filter whether comments are open for a given post type.
 *
 * @param string $status Default status for the given post type,
 *                             either 'open' or 'closed'.
 * @param string $post_type Post type. Default is `post`.
 * @param string $comment_type Type of comment. Default is `comment`.
 *
 * @return string (Maybe) filtered default status for the given post type.
 */
function noahlite_close_comments_for_projects( $status, $post_type, $comment_type ) {
	if ( 'jetpack-portfolio' !== $post_type ) {
		return $status;
	}

	// You could be more specific here for different comment types if desired
	return 'closed';
}
add_filter( 'get_default_comment_status', 'noahlite_close_comments_for_projects', 10, 3 );

/**
 * Modifies a relative URL to a URL that points to a certain page number.
 *
 * Inspired by the core function get_pagenum_link()
 *
 * @since Noah 1.0
 *
 * @global WP_Rewrite $wp_rewrite
 *
 * @param string $url The relative URL that you want modified.
 * @param int $pagenum Optional. Page ID. Default 1.
 * @param bool $escape Optional. Whether to escape the URL for display, with esc_url(). Defaults to true.
 *                        Otherwise, prepares the URL with esc_url_raw().
 *
 * @return string The full link URL for the given page number.
 */
function noahlite_paginate_url( $url, $pagenum = 1, $escape = true, $query = null ) {
	global /** @var WP_Rewrite $wp_rewrite */
	$wp_rewrite;

	if ( empty( $query ) ) {
		global $wp_query;
		$query = $wp_query;
	}

	//first make sure that we can have that page
	if ( $pagenum > $query->max_num_pages ) {
		return false;
	}

	$pagenum = (int) $pagenum;

	$request = remove_query_arg( 'paged', $url );

	$home_root = parse_url( home_url() );
	$home_root = ( isset( $home_root['path'] ) ) ? $home_root['path'] : '';
	$home_root = preg_quote( $home_root, '|' );

	$request = preg_replace( '|^' . $home_root . '|i', '', $request );
	$request = preg_replace( '|^/+|', '', $request );

	if ( ! $wp_rewrite->using_permalinks() || is_admin() ) {
		$base = trailingslashit( home_url() );

		if ( $pagenum > 1 ) {
			$result = add_query_arg( 'paged', $pagenum, $base . $request );
		} else {
			$result = $base . $request;
		}
	} else {
		$qs_regex = '|\?.*?$|';
		preg_match( $qs_regex, $request, $qs_match );

		if ( ! empty( $qs_match[0] ) ) {
			$query_string = $qs_match[0];
			$request      = preg_replace( $qs_regex, '', $request );
		} else {
			$query_string = '';
		}

		$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request );
		$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request );
		$request = ltrim( $request, '/' );

		$base = trailingslashit( home_url() );

		if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) ) {
			$base .= $wp_rewrite->index . '/';
		}

		if ( $pagenum > 1 ) {
			$request = ( ( ! empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
		}

		$result = $base . $request . $query_string;
	}

	if ( $escape ) {
		return esc_url( $result );
	} else {
		return esc_url_raw( $result );
	}
}

/**
 * Add styles/classes to the "Styles" drop-down
 */
function noahlite_mce_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}
add_filter( 'mce_buttons_2', 'noahlite_mce_buttons' );

function noahlite_mce_before_init( $settings ) {

	$style_formats = array(
		array( 'title' => __( 'Intro', 'noah-lite' ), 'selector' => 'p', 'classes' => 'intro' ),
		array( 'title' => __( 'Dropcap', 'noah-lite' ), 'inline' => 'span', 'classes' => 'dropcap' ),
		array( 'title' => __( 'Display', 'noah-lite' ), 'inline' => 'span', 'classes' => 'h0' )
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'noahlite_mce_before_init' );

/**
 * Generate the Josefin Sans font URL
 *
 * @since Noah 1.0
 *
 * @return string
 */
function noahlite_josefin_sans_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Josefin Sans, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$josefin_sans = esc_html_x( 'on', 'Josefin Sans font: on or off', 'noah-lite' );
	if ( 'off' !== $josefin_sans ) {
		return str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Josefin+Sans:400,600,700&amp;subset=latin-ext' );
	}

	return '';
}

/**
 * Generate the Ek Mukta font URL
 *
 * @since Noah 1.0
 *
 * @return string
 */
function noahlite_ek_mukta_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Ek Mukta, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$ekmukta = esc_html_x( 'on', 'Ek Mukta font: on or off', 'noah-lite' );
	if ( 'off' !== $ekmukta ) {
		return str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Ek+Mukta&amp;subset=devanagari,latin-ext' );
	}

	return '';
}

/**
 * Prints HTML with the single post pages.
 */
function noahlite_single_post_pages( $content ) {

	$pages_content = '';

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() && is_single() ) {
		$pages_content = 	wp_link_pages( array(
			'before' => '<div class="c-article__page-links  page-links">' . esc_html__( 'Pages:', 'noah-lite' ),
			'after'  => '</div>',
			'echo'   => 0,
		) );
	}

	return $content . $pages_content;
}
// add this filter with a priority smaller than tags - it has 18
// This way the post pages will appear before the tags
add_filter( 'the_content', 'noahlite_single_post_pages', 16 );


/**
 * Prints HTML with meta information for the tags.
 */
function noahlite_tags_list( $content ) {

	$tags_content = '';

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() && is_single() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list();

		if ( $tags_list && is_singular( 'post' ) ) {
			$tags_content .= '<div class="o-inline o-inline-xs tags h6"><span class="tags__title">' . esc_html__( 'Tags', 'noah-lite' ) . '</span><span class="tags-links">' . $tags_list . '</span></div>'; // WPCS: XSS OK.
		}
	}

	return $content . $tags_content;
}
// add this filter with a priority smaller than sharedaddy - it has 19
// This way the tags will appear before the share buttons
add_filter( 'the_content', 'noahlite_tags_list', 18 );

/**
 * Prints HTML with the author bio, before the related posts and after the tags and share buttons
 * We don't use the jetpack_author_bio() function because we want to use our own custom markup, but we respect the content options.
 */
function noahlite_author_bio( $content ) {

	$author_bio_content = '';

	// Only show the other bio for posts
	if ( 'post' == get_post_type() && is_single() ) {
		// Respect the content options
		if ( ! function_exists( 'jetpack_author_bio' ) || get_option( 'jetpack_content_author_bio', true ) ) {
			$author_bio_content .= noahlite_get_the_author_info_box();
		}
	}

	return $content . $author_bio_content;
}
// add this filter with a priority smaller than related posts - it has 40
// This way the the author biop will appear before the related posts
add_filter( 'the_content', 'noahlite_author_bio', 30 );

function noahlite_custom_excerpt_length( $length ) {
	return is_admin() ? $length : 35;
}
add_filter( 'excerpt_length', 'noahlite_custom_excerpt_length', 999 );

function noahlite_custom_archive_title( $title ) {
	if ( is_home() ) {
		$object = get_queried_object();

		if ( isset( $object->post_title ) ) {
			$title = $object->post_title;
		} else {
			$title = esc_html__( 'News', 'noah-lite' );
		}
	} elseif ( $title == esc_html__( 'Archives', 'noah-lite' ) ) {
		$title = esc_html__( 'All Projects', 'noah-lite' );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'noahlite_custom_archive_title', 11 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function noahlite_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'noahlite_pingback_header' );

/**
 * Display the classes for a element.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string $prefix Optional. Prefix to prepend to all of the provided classes
 * @param string $suffix Optional. Suffix to append to all of the provided classes
 */
function noahlite_css_class( $class = '', $prefix = '', $suffix = '' ) {
	// Separates classes with a single space, collates classes for element
	echo 'class="' . join( ' ', noahlite_get_css_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for a element as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string $prefix Optional. Prefix to prepend to all of the provided classes
 * @param string $suffix Optional. Suffix to append to all of the provided classes
 *
 * @return array Array of classes.
 */
function noahlite_get_css_class( $class = '', $prefix = '', $suffix = '' ) {
	$classes = array();

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

		//if we have a suffix then we need to add it to every class
		if ( ! empty( $suffix ) && is_string( $suffix ) ) {
			foreach ( $class as $key => $value ) {
				$class[ $key ] = $value . $suffix;
			}
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
	$classes = apply_filters( 'noahlite_css_class', $classes, $class, $prefix, $suffix );

	return array_unique( $classes );
}
