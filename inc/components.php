<?php
/**
 * This file is responsible for adjusting the Pixelgrade Components to this theme's specific needs.
 *
 * @package Noah
 * @since Noah 1.0
 */

function noah_prevent_hero( $is_needed, $location ) {
	//we only have a hero on the portfolio page
	if ( ! pixelgrade_in_location( 'portfolio-page', $location ) ) {
		return false;
	}

	// also we don't want a hero on any non-pages
	if ( ! is_page() ) {
		return false;
	}

	return $is_needed;
}
add_filter( 'pixelgrade_hero_is_hero_needed', 'noah_prevent_hero', 10, 2 );

/**
 * We want to make sure that we don't mistakenly account for the hero content when we are not using a template where we use it.
 * The content might have been saved in the database, even if we are not using it (displaying it in the WP admin).
 *
 * @param $has_desc
 * @param $post
 *
 * @return bool
 */
function noah_prevent_hero_description( $has_desc, $post ) {
	if ( is_page( $post->ID ) && in_array( get_page_template_slug( $post->ID ), array( '', 'default', 'page-templates/split.php' ) ) ) {
		return false;
	}

	if ( is_single( $post->ID ) && 'post' === get_post_type( $post->ID ) ) {
		return false;
	}

	return $has_desc;
}
add_filter( 'pixelgrade_hero_has_description', 'noah_prevent_hero_description', 10, 2 );

/**
 * Display the attributes for the body element.
 *
 * @param string|array $attribute One or more attributes to add to the attributes list.
 */
function pixelgrade_body_attributes( $attribute = '' ) {
	//get the attributes
	$attributes = pixelgrade_get_body_attributes( $attribute );

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

function pixelgrade_get_body_attributes( $attribute = array() ) {
	$attributes = array();

	if ( ! empty( $attribute ) ) {
		$attributes = array_merge( $attributes, $attribute );
	} else {
		// Ensure that we always coerce class to being an array.
		$attribute = array();
	}

	/**
	 * Filters the list of body attributes for the current post or page.
	 *
	 * @since 2.8.0
	 *
	 * @param array $attributes An array of body attributes.
	 * @param array $attribute  An array of additional attributes added to the body.
	 */
	$attributes = apply_filters( 'pixelgrade_body_attributes', $attributes, $attribute );

	return array_unique( $attributes );
}

/**
 * Display the classes for a element.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 * @param string $prefix Optional. Prefix to prepend to all of the provided classes
 * @param string $suffix Optional. Suffix to append to all of the provided classes
 */
function pixelgrade_css_class( $class = '', $location = '', $prefix = '', $suffix = '' ) {
	// Separates classes with a single space, collates classes for element
	echo 'class="' . join( ' ', pixelgrade_get_css_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for a element as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 * @param string $prefix Optional. Prefix to prepend to all of the provided classes
 * @param string $suffix Optional. Suffix to append to all of the provided classes
 *
 * @return array Array of classes.
 */
function pixelgrade_get_css_class( $class = '', $location = '', $prefix = '', $suffix = '' ) {
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
	 * @param string|array $location   The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'pixelgrade_css_class', $classes, $class, $location, $prefix, $suffix );

	return array_unique( $classes );
}

function pixelgrade_get_location( $default = '', $force = true ) {
	$location = get_query_var( 'pixelgrade_location' );

	if ( empty( $location ) ) {
		$location = $default;

		//we will force the query var to have the default value, in case it was empty
		if ( true === $force ) {
			//DO NOT put the second parameter of pixelgrade_set_location() to 'true' because you will cause an infinite loop!!!
			$location = pixelgrade_set_location( $default );
		}
	}

	return pixelgrade_standardize_location( $location );
}

function pixelgrade_set_location( $location = '', $merge = false ) {
	//In case one wants to add to the current location, not replace it
	if ( true === $merge ) {
		//The current location is already standardized
		$current_location = pixelgrade_get_location();
		$location = pixelgrade_standardize_location( $location );

		$location = array_merge( $current_location, $location );
	}

	//Make sure we have a standardized (Array) location
	$location = pixelgrade_standardize_location( $location );

	set_query_var( 'pixelgrade_location', $location );

	//allow others to chain this
	return $location;
}

/**
 * Searches for hints in a location string or array. If either of them is empty, returns false.
 *
 * @param string|array $search
 * @param string |array $location
 * @param bool $and Optional. Whether to make a AND search (the default) or a OR search. Anything other that 'true' means OR search.
 *
 * @return bool
 */
function pixelgrade_in_location( $search, $location, $and = true ) {
	// First make sure that we have a standard location format (i.e. array with each location)
	$location = pixelgrade_standardize_location( $location );

	// Also make sure that $search is standard
	$search = pixelgrade_standardize_location( $search );

	// Bail if either of them is empty
	if ( empty( $location ) || empty( $search ) ) {
		return false;
	}

	// Now let's handle the search
	// First we need to see if $search is an array with multiple values,
	// in which case we will do a AND search for each values if $and is true
	// else we will do a OR search
	$found = false;
	foreach ( $search as $item ) {
		if ( in_array( $item, $location ) ) {
			if ( true !== $and ) {
				// we are doing a OR search, so if we find any of the search locations, we are fine with that
				$found = true;
				break;
			} else {
				$found = true;
				continue;
			}
		} else {
			if ( true === $and ) {
				// we are doing a AND search, so if we haven't found one of the search locations, we can safely bail
				$found = false;
				break;
			} else {
				continue;
			}
		}
	}

	return $found;
}

/**
 * Takes a location hint and returns it in a standard format: an array with each hint separate
 *
 * @param string|array $location The location hints
 *
 * @return array|string
 */
function pixelgrade_standardize_location( $location ) {

	if ( is_string( $location ) ) {
		//the location might be a space separated series of hints
		//make sure we don't have white spaces at the beginning or the end
		$location = trim( $location );
		//some may use commas to separate
		$location = str_replace( ',', ' ', $location );
		//make sure we collapse multiple whitespaces into one space
		$location = preg_replace( '!\s+!', ' ', $location );
		//explode by space
		$location = explode( ' ', $location );
	}

	if ( empty( $location ) ) {
		$location = array();
	}

	return $location;
}

/**
 * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
 * to the end of the array.
 *
 * @param array $array
 * @param string $key
 * @param array $insert
 *
 * @return array
 */
function pixelgrade_array_insert_after( $array, $key, $insert ) {
	$keys = array_keys( $array );
	$index = array_search( $key, $keys );
	$pos = false === $index ? count( $array ) : $index + 1;
	return array_merge( array_slice( $array, 0, $pos ), $insert, array_slice( $array, $pos ) );
}

