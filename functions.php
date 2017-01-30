<?php
/**
 * Functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Noah
 * @since   Noah 1.0.0
 */

if ( ! function_exists( 'noah_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function noah_setup() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Noah, use a find and replace
		 * to change 'noah' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'noah', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add image sizes used by theme.Here some examples:
		 */
		// Used for blog archive(the height is flexible)
		add_image_size( 'noah-card-image', 450, 9999, false );
		// Used for sliders(fixed height)
		add_image_size( 'noah-slide-image', 9999, 800, false );
		// Used for hero image
		add_image_size( 'noah-hero-image', 2700, 9999, false );


		// Register the navigation menus
		register_nav_menus( array(
			'primary-left'  => esc_html__( 'Header Left', 'noah' ),
			'primary-right' => esc_html__( 'Header Right', 'noah' ),
			'footer_menu' => esc_html__( 'Footer', 'noah' ),
		) );

		/**
		 * Add theme support for site logo
		 *
		 * First, it's the image size we want to use for the logo thumbnails
		 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
		 */
		add_theme_support( 'custom-logo', apply_filters( 'pixelgrade_header_site_logo', array(
			'height'      => 600,
			'width'       => 1360,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array(
				'site-title',
				'site-description-text',
			)
		) ) );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/**
		 * Remove themes' post formats support
		 */
		remove_theme_support( 'post-formats' );

		/**
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts and self-hosted ones
		 */
		add_editor_style( array( 'editor-style.css' ) );
		add_editor_style( noah_ek_mukta_font_url() );

	}
} // noah_setup
add_action( 'after_setup_theme', 'noah_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function noah_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Area', 'noah' ),
		'id'            => 'sidebar-footer',
		'description'   => esc_html__( 'Widgets displayed in the Footer Area of the website.', 'noah' ),
		'before_widget' => '<div id="%1$s" class="c-gallery__item  c-widget  %2$s"><div class="o-wrapper u-container-width">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="c-widget__title h3">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'noah_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function noah_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'noah_content_width', 1080, 0 );
}

add_action( 'after_setup_theme', 'noah_content_width', 0 );


/**
 * Enqueue scripts and styles required by theme in front-end.
 */
function noah_load_assets() {
	/**
	 * Get theme details inside `$theme` object and later use it for cache busting
	 * with `$theme->get( 'Version' )` method
	 */
	$theme = wp_get_theme();

	/*
	 * FIRST THE STYLING
	 */
	$main_style_deps = array();

	/* Handle the FONTS */
	wp_enqueue_style( 'noah-fonts-arcamajora3', noah_arcamajora3_font_url() );
	$main_style_deps[] = 'noah-fonts-arcamajora3';

	wp_enqueue_style( 'noah-fonts-ek-mukta', noah_ek_mukta_font_url() );
	$main_style_deps[] = 'noah-fonts-ek-mukta';

	if ( ! is_rtl() ) {
		wp_enqueue_style( 'noah-style', get_template_directory_uri() . '/style.css', $main_style_deps, $theme->get( 'Version' ) );
	}

	/*
	 * NOW THE SCRIPTS
	 */
	$main_script_deps = array( 'jquery', 'imagesloaded', 'masonry' );

	wp_register_script( 'noah-tweenmax', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js', array(), '1.19.0' );
	$main_script_deps[] = 'noah-tweenmax';

	wp_register_script( 'noah-tweenmax-scrollto', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/plugins/ScrollToPlugin.min.js', array(), '1.19.0' );
	$main_script_deps[] = 'noah-tweenmax-scrollto';

	wp_enqueue_script( 'noah-scripts', get_template_directory_uri() . '/assets/js/main.js', $main_script_deps, $theme->get( 'Version' ), true );

	$translation_array = array(
		'prev_slide'   => esc_html__( 'Prev', 'noah' ),
		'next_slide'   => esc_html__( 'Next', 'noah' ),
		'close_slider' => esc_html__( 'X', 'noah' ),
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'noah-scripts', 'noah_js_strings', $translation_array );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'noah_load_assets' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load widgets logic
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load various plugin integrations
 */
require get_template_directory() . '/inc/integrations.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Pixelgrade Components.
 */
require get_template_directory() . '/components/power-up.php';

/**
 * Customization for the used Pixelgrade Components
 */
require get_template_directory() . '/inc/components.php';

/**
 * Displays the navigation to next/previous post, when applicable.
 *
 * @since 4.1.0
 *
 * @param array $args Optional. See get_the_post_navigation() for available arguments.
 *                    Default empty array.
 */
function noah_the_post_navigation( $args = array() ) {
	echo noah_get_the_post_navigation( $args );
}

/**
 * Retrieves the navigation to next/previous post, when applicable.
 *
 * @since 4.1.0
 * @since 4.4.0 Introduced the `in_same_term`, `excluded_terms`, and `taxonomy` arguments.
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
function noah_get_the_post_navigation( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'prev_text'          => '%title',
		'next_text'          => '%title',
		'in_same_term'       => false,
		'excluded_terms'     => '',
		'taxonomy'           => 'category',
		'screen_reader_text' => __( 'Post navigation', 'noah' ),
	) );

	$navigation = '';

	$previous = get_previous_post_link(
		'<div class="nav-previous"><span class="h3 nav-previous-title">%link</span>' . '<span class="h7 u-color-accent">' . __( 'Previous', 'noah' ) . '</span></div>',
		$args['prev_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	$next = get_next_post_link(
		'<div class="nav-next"><span class="h3 nav-next-title">%link</span>' . '<span class="h7 u-color-accent">' . __( 'Next', 'noah' ) . '</span></div>',
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

function noah_the_author_info_box() {
	echo noah_get_the_author_info_box();
}

function noah_get_the_author_info_box() {

	global $post;

	$author_details = '';

	// Detect if it is a single post with a post author
	if ( is_single() && isset( $post->post_author ) ) {

		// Get author's display name
		$display_name = get_the_author_meta( 'display_name', $post->post_author );

		// If display name is not available then use nickname as display name
		if ( empty( $display_name ) ) {
			$display_name = get_the_author_meta( 'nickname', $post->post_author );
		}

		// Get author's biographical information or description
		$user_description = get_the_author_meta( 'user_description', $post->post_author );


		if ( ! empty( $user_description ) ) {
			$author_details .= '<div class="c-author  has-description">';
		} else {
			$author_details .= '<div class="c-author">';
		}

		// Get author's website URL
		$user_website = get_the_author_meta( 'url', $post->post_author );

		// Get link to the author archive page
		$user_posts = get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) );

		$author_avatar = get_avatar( get_the_author_meta( 'user_email' ), 100 );

		if ( ! empty( $author_avatar ) ) {
			$author_details .= '<div class="c-author__avatar">' . $author_avatar . '</div>';
		}

		$author_details .= '<div class="c-author__details">';

		if ( ! empty( $display_name ) ) {
			$author_details .= '<span class="c-author__label h7">' . __( 'Posted by', 'noah' ) . '</span>';
			$author_details .= '<p class="c-author__name h2">' . $display_name . '</p>';
		}

		// Author avatar and bio
		if ( ! empty( $user_description ) ) {
			$author_details .= '<p class="c-author__description">' . nl2br( $user_description ) . '</p>';
		}

		$author_details .= '<div class="o-inline o-inline-xs h7">';

		$author_details .= '<a class="_color-inherit" href="' . esc_url( $user_posts ) . '">' . __( 'All posts', 'noah' ) . '</a>';

		$author_details .= '<div class="c-author__links o-inline o-inline-s u-color-accent">';

		// Check if author has a website in their profile
		if ( ! empty( $user_website ) ) {
			// Display author website link
			$author_details .= '<a href="' . esc_url( $user_website ) . '" target="_blank" rel="nofollow">Website</a>';
		}

		$author_details .= '</div><!-- .c-author__links -->';

		$author_details .= '</div>';
		$author_details .= '</div><!-- .c-author__details -->';
		$author_details .= '</div><!-- .c-author -->';
	}

	return $author_details;
}