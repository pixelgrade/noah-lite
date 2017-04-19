<?php
/**
 * Functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Noah Lite
 * @since   Noah Lite 1.0.0
 */

if ( ! function_exists( 'noahlite_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function noahlite_setup() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Noah, use a find and replace
		 * to change 'noah-lite' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'noah-lite', get_template_directory() . '/languages' );

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
			'primary-left'  => esc_html__( 'Header Left', 'noah-lite' ),
			'primary-right' => esc_html__( 'Header Right', 'noah-lite' ),
		) );

		/**
		 * Add theme support for site logo
		 *
		 * First, it's the image size we want to use for the logo thumbnails
		 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
		 */
		add_theme_support( 'custom-logo', apply_filters( 'noahlite_header_site_logo', array(
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
		 * Enqueue the custom Google Fonts and self-hosted ones
		 * Add editor custom style to make it look more like the frontend
		 */
		add_editor_style( array( noahlite_ek_mukta_font_url(), 'editor-style.css' ) );

	}
} // noahlite_setup
add_action( 'after_setup_theme', 'noahlite_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function noahlite_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'noah-lite' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Widgets displayed in the Footer Area of the website.', 'noah-lite' ),
		'before_widget' => '<div id="%1$s" class="c-gallery__item  c-widget  %2$s"><div class="o-wrapper u-container-width">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="c-widget__title h3">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'noahlite_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function noahlite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'noahlite_content_width', 1280, 0 );
}

add_action( 'after_setup_theme', 'noahlite_content_width', 0 );


/**
 * Enqueue scripts and styles required by theme in front-end.
 */
function noahlite_load_assets() {
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
	wp_enqueue_style( 'noah-lite-fonts-arcamajora3', noahlite_arcamajora3_font_url() );
	$main_style_deps[] = 'noah-lite-fonts-arcamajora3';

	wp_enqueue_style( 'noah-lite-fonts-ek-mukta', noahlite_ek_mukta_font_url() );
	$main_style_deps[] = 'noah-lite-fonts-ek-mukta';

	wp_enqueue_style( 'noah-lite-style', get_template_directory_uri() . '/style.css', $main_style_deps, $theme->get( 'Version' ) );

	/*
	 * NOW THE SCRIPTS
	 */
	wp_enqueue_script( 'noah-lite-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'noah-lite-scripts', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'imagesloaded', 'masonry' ), $theme->get( 'Version' ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'noahlite_load_assets' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 * http://jetpack.me/
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Customization for the used Pixelgrade Components
 */
require get_template_directory() . '/inc/components.php';
