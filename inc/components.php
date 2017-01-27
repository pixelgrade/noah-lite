<?php
/**
 * This file is responsible for adjusting the Pixelgrade Components to this theme's specific needs.
 *
 * @package Noah
 * @since Noah 1.0
 */

function noah_hero_metaboxes_config( $config ) {
	//we only want the hero on the Portfolio page, not on all pages
	$config['hero_area_background__page']['show_on'] = array(
		'key'   => 'page-template',
		'value' => array( 'page-templates/portfolio-page.php', ), //the page templates to show on ie. 'page-templates/page-builder.php'
	);
	$config['hero_area_content__page']['show_on'] = array(
		'key'   => 'page-template',
		'value' => array( 'page-templates/portfolio-page.php', ), //the page templates to show on ie. 'page-templates/page-builder.php'
	);

	//for now we don't want any metaboxes related to maps
	unset( $config['hero_area_map__page'] );

	return $config;
}
add_filter( 'pixelgrade_hero_metaboxes_config', 'noah_hero_metaboxes_config', 10, 1 );

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

function noah_header_scroll_arrow( $location = '' ) { ?>
	<a href="#projectsArchive" class="c-scroll-arrow"><?php get_template_part( 'template-parts/svg/project-scroll-down' ); ?></a>
<?php }
add_action( 'pixelgrade_header_after_navbar' ,'noah_header_scroll_arrow', 10, 1 );

// Disable Header component support for Jetpack Social Menu
add_filter( 'pixelgrade_header_use_jetpack_social_menu', '__return_false' );
