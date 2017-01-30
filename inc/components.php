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

