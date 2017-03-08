<?php
/**
 * Jetpack Compatibility File
 *
 * See: http://jetpack.me/
 *
 * @package Noah
 */

function noah_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'noah_infinite_scroll_render',
		'footer'    => 'page',
		'footer_widgets' => array(
			'sidebar-2',
		),
		'wrapper'   => false,
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add support for the Jetpack Portfolio Custom Post Type
	add_theme_support( 'jetpack-portfolio' );

	// Add support for content options, where it's appropriate
	add_theme_support( 'jetpack-content-options', array(
		'blog-display'       => false, // we only show the excerpt, not full post content on archives
		'author-bio'         => true, // display or not the author bio: true or false.
		'masonry'            => '.c-gallery--masonry', // a CSS selector matching the elements that triggers a masonry refresh if the theme is using a masonry layout.
		'post-details'       => array(
			'stylesheet'      => 'noah-style', // name of the theme's stylesheet.
			'date'            => '.posted-on', // a CSS selector matching the elements that display the post date.
			'categories'      => '.cats', // a CSS selector matching the elements that display the post categories.
			'tags'            => '.tags', // a CSS selector matching the elements that display the post tags.
			'author'          => '.byline', // a CSS selector matching the elements that display the post author.
		),
		'featured-images'    => array(
			'archive'         => true, // enable or not the featured image check for archive pages: true or false.
			'post'            => false, // we do not display the featured image on single posts
			'page'            => true, // enable or not the featured image check for single pages: true or false.
		),
	) );
}
add_action( 'after_setup_theme', 'noah_jetpack_setup', 30 );

/**
 * Custom render function for Infinite Scroll.
 *
 * @since Noah 1.2
 */
function noah_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}
} // end function noah_infinite_scroll_render

/**
 * Filter the theme page templates and remove the `page-templates/portfolio-page.php` when no jetpack-portfolio CPT
 *
 * @param array    $page_templates Page templates.
 *
 * @return array (Maybe) modified page templates array.
 */
function noah_filter_theme_page_templates( $page_templates ) {
	if ( ! post_type_exists( 'jetpack-portfolio' ) && isset( $page_templates['page-templates/portfolio-page.php'] ) ) {
		unset( $page_templates['page-templates/portfolio-page.php'] );
	}

	return $page_templates;
}
add_filter( 'theme_page_templates', 'noah_filter_theme_page_templates', 20, 1 );
