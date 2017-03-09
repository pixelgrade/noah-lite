<?php
/**
 * This file is responsible for adjusting the Pixelgrade Components to this theme's specific needs.
 *
 * @package Noah
 * @since Noah 1.0
 */

/**
 * We want to make sure that we don't mistakenly account for the hero content when we are not using a template where we use it.
 * The content might have been saved in the database, even if we are not using it (displaying it in the WP admin).
 *
 * @param $has_desc
 * @param $post
 *
 * @return bool
 */
function noahlite_prevent_hero_description( $has_desc, $post ) {
	if ( is_page( $post->ID ) && in_array( get_page_template_slug( $post->ID ), array( '', 'default', 'page-templates/split.php' ) ) ) {
		return false;
	}

	if ( is_single( $post->ID ) && 'post' === get_post_type( $post->ID ) ) {
		return false;
	}

	return $has_desc;
}
add_filter( 'noahlite_hero_has_description', 'noahlite_prevent_hero_description', 10, 2 );

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

