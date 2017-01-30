<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Noah
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */

function noah_body_classes( $classes ) {

	$classes[] = 'body';
	$classes[] = 'u-content-background';

	if ( get_theme_mod( 'noah_header_position', 'static' ) == 'static' ) {
		$classes[] = 'u-static-header';
	}

	if ( get_theme_mod( 'noah_footer_layout' ) == 'stacked' ) {
		$classes[] = 'u-footer-layout-stacked';
	}

	if ( get_theme_mod( 'noah_header_width' ) == 'full' ) {
		$classes[] = 'u-full-width-header';
	}

	if ( is_singular( 'jetpack-portfolio' ) ) {
		$classes[] = noah_get_single_project_class();
	}

	if ( is_customize_preview() ) {
		$classes[] = 'customizer-preview';
	}

	if ( pixelgrade_hero_is_hero_needed() ) {
		$classes[] = 'has-hero';
	}

	return $classes;
}
add_filter( 'body_class', 'noah_body_classes', 10, 1 );

/**
 * Display the classes for the portfolio wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 */
function noah_portfolio_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', noah_get_portfolio_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function noah_get_portfolio_class( $class = '', $location = '' ) {

	$classes = array();

	$classes[] = 'o-grid';
	$classes[] = 'c-gallery c-gallery--portfolio';
	// layout
	$grid_layout       = get_theme_mod( 'noah_portfolio_grid_layout', 'packed' );
	$grid_layout_class = 'c-gallery--' . $grid_layout;
	$classes[]         = $grid_layout_class;

	if ( $grid_layout != 'regular' ) {
		$classes[] = 'js-masonry';
	}

	// items per row
	$columns        = 4;
	$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
	$columns_at_pad = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;

	$columns_class = 'o-grid--' . $columns . 'col-@desk o-grid--' . $columns_at_lap . 'col-@lap o-grid--' . $columns_at_pad . 'col-@pad';
	// title position
	$title_position       = get_theme_mod( 'noah_portfolio_items_title_position', 'below' );
	$title_position_class = 'c-gallery--title-' . $title_position;

	if ( $title_position == 'overlay' ) {
		$title_alignment_class = 'c-gallery--title-' . get_theme_mod( 'noah_portfolio_items_title_alignment_overlay', 'middle-center' );
	} else {
		$title_alignment_class = 'c-gallery--title-' . get_theme_mod( 'noah_portfolio_items_title_alignment_nearby', 'left' );
	}

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
	 * @param string|array $location The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'noah_portfolio_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Display the classes for the portfolio wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 */
function noah_blog_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', noah_get_blog_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function noah_get_blog_class( $class = '', $location = '' ) {

	$classes = array();

	$classes[] = 'o-grid';
	$classes[] = 'c-gallery  c-gallery--blog';
	// layout
	$grid_layout       = get_theme_mod( 'noah_blog_grid_layout', 'masonry' );
	$grid_layout_class = 'c-gallery--' . $grid_layout;
	$classes[]         = $grid_layout_class;

	if ( $grid_layout != 'regular' ) {
		$classes[] = 'js-masonry';
	}

	// items per row
	$columns        = 3;
	$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
	$columns_at_pad = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;

	$columns_class = 'o-grid--' . $columns . 'col-@desk o-grid--' . $columns_at_lap . 'col-@lap o-grid--' . $columns_at_pad . 'col-@pad';
	// title position
	$title_position       = pixelgrade_option( 'blog_items_title_position', 'below' );
	$title_position_class = 'c-gallery--title-' . $title_position;

	if ( $title_position == 'overlay' ) {
		$title_alignment_class = 'c-gallery--title-' . get_theme_mod( 'noah_blog_items_title_alignment_overlay', 'middle-center' );
	} else {
		$title_alignment_class = 'c-gallery--title-' . get_theme_mod( 'noah_blog_items_title_alignment_nearby', 'left' );
	}

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
	 * @param string|array $location The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'noah_blog_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Add our custom <body> tag data attributes
 *
 * @param array $attributes An array of body attributes.
 *
 * @return array
 */
function noah_body_attributes( $attributes ) {
	if ( get_theme_mod( 'noah_use_ajax_loading', false ) ) {
		$attributes['data-ajaxloading'] = '';
	}

	if ( pixelgrade_option( 'main_color' ) ) {
		$attributes['data-color'] = pixelgrade_option( 'main_color' );
	}

	$attributes['data-parallax'] = pixelgrade_option( 'parallax_amount' );

	//we use this so we can generate links with post id
	//right now we use it to change the Edit Post link in the admin bar
	if ( ( get_theme_mod( 'noah_use_ajax_loading', true ) == 1 ) ) {
		global $wp_the_query;
		$current_object = $wp_the_query->get_queried_object();

		if ( ! empty( $current_object->post_type )
		     && ( $post_type_object = get_post_type_object( $current_object->post_type ) )
		     && current_user_can( 'edit_post', $current_object->ID )
		     && $post_type_object->show_ui && $post_type_object->show_in_admin_bar
		) {

			$attributes['data-curpostid'] = $current_object->ID;
			if ( isset( $post_type_object->labels ) && isset( $post_type_object->labels->edit_item ) ) {
				$attributes['data-curpostedit'] = $post_type_object->labels->edit_item;
			}
		} elseif ( ! empty( $current_object->taxonomy )
		           && ( $tax = get_taxonomy( $current_object->taxonomy ) )
		           && current_user_can( $tax->cap->edit_terms )
		           && $tax->show_ui
		) {
			$attributes['data-curpostid']   = $current_object->term_id;
			$attributes['data-curtaxonomy'] = $current_object->taxonomy;

			if ( isset( $tax->labels ) && isset( $tax->labels->edit_item ) ) {
				$attributes['data-curpostedit'] = $tax->labels->edit_item;
			}
		} elseif ( is_page_template( 'page-templates/portfolio-archive.php' ) ) {
			$post_type_object             = get_post_type_object( 'page' );
			$attributes['data-curpostid'] = $current_object->ID;
			if ( isset( $post_type_object->labels ) && isset( $post_type_object->labels->edit_item ) ) {
				$attributes['data-curpostedit'] = $post_type_object->labels->edit_item;
			}
		}
	}

	return $attributes;
}
add_filter( 'pixelgrade_body_attributes', 'noah_body_attributes' );

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
function noah_post_classes( $classes, $class, $post_id ) {
	//we first need to know the bigger picture - the location this template part was loaded from
	$location = pixelgrade_get_location();

	if ( is_post_type_archive( 'post' ) || is_author() || is_home() || is_search() || is_post_type_archive( 'jetpack-portfolio' ) || pixelgrade_in_location( 'portfolio jetpack', $location )
	     || pixelgrade_in_location( 'archive category', $location ) || pixelgrade_in_location( 'archive tag', $location )
	) {
		$classes[] = 'c-gallery__item';

		// get info about the width and height of the image
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

		// if the image is landscape double its width
		$is_landscape = $image_data[1] > $image_data[2];

		$is_featured = null;
		if ( taxonomy_exists( 'jetpack-portfolio-tag' ) ) {
			$is_featured = has_term( 'featured', 'jetpack-portfolio-tag' );
		}

		// again, if the images if featured double its width
		$is_featured = has_term( 'featured', 'tag' );

		if ( $is_featured && $is_landscape ) {
			$classes[] = 'u-span-full';
		}

		if ( $is_featured && ! $is_landscape ) {
			$classes[] = 'u-span2';
		}

		if ( ! $is_featured && $is_landscape ) {
			$classes[] = 'u-span-landscape';
		}

		$classes[] = 'c-gallery__item--' . ( $is_landscape ? 'landscape' : 'portrait' );
	} elseif ( is_singular( 'jetpack-portfolio' ) || pixelgrade_in_location( 'project jetpack', $location ) ) {
		$classes[] = 'c-project';
	} else {
		$classes[] = 'c-article';
	}

	return $classes;
}
add_filter( 'post_class', 'noah_post_classes', 10, 3 );

/**=== HELPERS ===**/

if ( ! function_exists( 'noah_get_single_project_class' ) ) {

	/**
	 * Returns the layout type based on the amount of content and the number of [gallery] shortcodes
	 *
	 * @return string
	 */
	function noah_get_single_project_class() {
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
				add_action( 'the_content', 'noah_remove_single_project_gallery' );

				return 'has-media-only fill-page js-project';
//			} elseif ( count( $remains ) == 1 ) {
//				add_action( 'the_content', 'noah_remove_single_project_gallery' );
//
//				return 'has-media-and-content fill-page js-project';
			}
		}

		return 'has-mixed-content';
	}
}

if ( ! function_exists( 'noah_remove_single_project_gallery' ) ) {

	/**
	 * Hook called only when we have only one gallery shortcode in the content.
	 * It removes the shortcode and attaches `noah_display_project_gallery` to the `the_noah_gallery` action which outputs the gallery markup in a different place.
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	function noah_remove_single_project_gallery( $content ) {

		if ( 'jetpack-portfolio' === get_post_type() ) {

			// get the gallery shortcode
			$content = preg_replace( '/' . get_shortcode_regex( array( 'gallery' ) ) . '/', '', $content );

			//Make sure we don't pass a null
			if ( empty( $content ) ) {
				$content = '';
			}

			//Since we have removed the [gallery] shortcode we need to hook so we output the gallery in a different place
			add_action( 'the_noah_gallery', 'noah_display_project_gallery', 10, 1 );
		}

		return wp_kses_post( $content );
	}
}
if ( ! function_exists( 'noah_display_project_gallery' ) ) {
	/**
	 * Hook called only on projects with one gallery shortcode in the content
	 *
	 * @param int|false $post_id
	 *
	 * @return bool
	 */
	function noah_display_project_gallery( $post_id ) {
		$post = get_post( $post_id );

		if ( empty( $post ) || is_wp_error( $post ) ) {
			return false;
		}

		// get the gallery shortcode
		preg_match_all( '/' . get_shortcode_regex( array( 'gallery' ) ) . '/', $post->post_content, $matches, PREG_SET_ORDER );

		if ( empty( $matches[0] ) ) {
			return false;
		}

		$shortcode = $matches[0][0];

		echo do_shortcode( $shortcode );

		return true;
	}
}

if ( ! function_exists( 'noah_post_gallery' ) ) {
	/**
	 * Responds to the [gallery] shortcode, but not an actual shortcode callback.
	 *
	 * @param $value string An empty string if nothing has modified the gallery output, the output html otherwise
	 * @param $attr  array The shortcode attributes array
	 *
	 * @return string The (un)modified $value
	 */
	function noah_post_gallery( $value, $attr ) {
		// Bail if somebody else has done something
		if ( ! empty( $value ) ) {
			return $value;
		}

		// If [gallery type="slideshow"] have it behave just like [gallery] but remember the user intent to be first a slideshow
		if ( ! empty( $attr['type'] ) && 'slideshow' == $attr['type'] ) {
			//remember that we should start as a slideshow - since we have no other way we will use the fact that it's a wrong value (0) as a hint
			//the thumbnail gallery should be 3 columns
			$attr['columns'] = 0;
			unset( $attr['type'] );

			//now do the regular thing
			return gallery_shortcode( $attr );
		}

		return $value;
	}
}
add_filter( 'post_gallery', 'noah_post_gallery', 1001, 2 );

/**
 * We force that every gallery in a project uses the same settings
 *
 * @param $out
 * @param $pairs
 * @param $atts
 * @param $shortcode
 *
 * @return mixed
 */
function noah_normalize_gallery_atts( $out, $pairs, $atts, $shortcode ) {
	if ( intval( $out['columns'] ) <= 3 ) {
		$out['size'] = 'large';
	} else {
		$out['size'] = 'medium';
	}

	return $out;
}
add_filter( 'shortcode_atts_gallery', 'noah_normalize_gallery_atts', 10, 4 );

if ( ! function_exists( 'noah_categorized_blog' ) ) {

	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @since Noah 1.0
	 *
	 * @return bool
	 */
	function noah_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'noah_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'noah_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so noah_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so noah_categorized_blog should return false.
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
function noah_remove_att_comments( $open, $post_id ) {
	$post = get_post( $post_id );
	if ( $post->post_type == 'attachment' ) {
		return false;
	}

	return $open;
}
add_filter( 'comments_open', 'noah_remove_att_comments', 10, 2 );

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
function noah_close_comments_for_projects( $status, $post_type, $comment_type ) {
	if ( 'jetpack-portfolio' !== $post_type ) {
		return $status;
	}

	// You could be more specific here for different comment types if desired
	return 'closed';
}
add_filter( 'get_default_comment_status', 'noah_close_comments_for_projects', 10, 3 );

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
function noah_paginate_url( $url, $pagenum = 1, $escape = true, $query = null ) {
	global $wp_rewrite;

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
function noah_mce_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}
add_filter( 'mce_buttons_2', 'noah_mce_buttons' );

function noah_mce_before_init( $settings ) {

	$style_formats = array(
		array( 'title' => __( 'Intro', 'noah' ), 'selector' => 'p', 'classes' => 'intro' ),
		array( 'title' => __( 'Dropcap', 'noah' ), 'inline' => 'span', 'classes' => 'dropcap' ),
		array( 'title' => __( 'Display', 'noah' ), 'inline' => 'span', 'classes' => 'h0' )
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'noah_mce_before_init' );

function noah_ajax_get_content_callback() {
	if ( isset( $_POST['page_url'] ) ) {
		$id      = absint( url_to_postid( $_POST['page_url'] ) );
		$page    = get_post( $id );
		$title   = '<div class="c-page-header__title h1 u-align-center">' . $page->post_title . '</div>';
		$content = $title . apply_filters( 'the_content', $page->post_content );
		echo $content;
	}
	die();
}
add_action( 'wp_ajax_nopriv_noah_ajax_get_content', 'noah_ajax_get_content_callback' );
add_action( 'wp_ajax_noah_ajax_get_content', 'noah_ajax_get_content_callback' );

/**
 * Generate the Arca Majora 3 font URL
 *
 * @since Noah 1.0
 *
 * @return string
 */
function noah_arcamajora3_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Arca Majora 3, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$arcamajora3 = esc_html_x( 'on', 'Arca Majora 2 font: on or off', 'noah' );
	if ( 'off' !== $arcamajora3 ) {
		return get_template_directory_uri() . '/assets/fonts/arcamajora3/stylesheet.css';
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
function noah_ek_mukta_font_url() {

	/* Translators: If there are characters in your language that are not
	* supported by Ek Mukta, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$ekmukta = esc_html_x( 'on', 'Ek Mukta font: on or off', 'noah' );
	if ( 'off' !== $ekmukta ) {
		return str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Ek+Mukta&amp;subset=devanagari,latin-ext' );
	}

	return '';
}

/**
 * Prints HTML with meta information for the tags.
 */
function noah_tags_list( $content ) {

	$tags_content = '';

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list();

		if ( $tags_list && is_singular( 'post' ) ) {
			$tags_content .= '<div class="o-inline o-inline-xs tags h6"><div class="tags__title">' . __( 'Tags', 'noah' ) . sprintf( '</div>' . esc_html__( '%1$s', 'noah' ) . '</div>', $tags_list ); // WPCS: XSS OK.
		}
	}

	return $content . $tags_content;
}
// add this filter with a priority smaller than sharedaddy - it has 19
add_filter( 'the_content', 'noah_tags_list', 18 );

function noah_custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'noah_custom_excerpt_length', 999 );

function noah_custom_archive_title( $title ) {
	if ( is_home() ) {
		$object = get_queried_object();

		if ( isset( $object->post_title ) ) {
			$title = $object->post_title;
		} else {
			$title = esc_html__( 'News', 'noah' );
		}
	} elseif ( $title == esc_html__( 'Archives', 'noah' ) ) {
		$title = esc_html__( 'All Projects', 'noah' );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'noah_custom_archive_title', 11 );

/**
 * Add a data attribute to the menu items depending on the background color
 *
 * @param array $atts {
 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
 *
 *     @type string $title  Title attribute.
 *     @type string $target Target attribute.
 *     @type string $rel    The rel attribute.
 *     @type string $href   The href attribute.
 * }
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 *
 * @return array
 */
function noah_menu_item_color($atts, $item, $args, $depth) {
	$atts['data-color'] = trim( pixelgrade_hero_get_background_color( $item->object_id ) );

	return $atts;
}
add_filter('nav_menu_link_attributes', 'noah_menu_item_color', 10, 4);