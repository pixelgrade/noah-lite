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

	// We don't want the default carousel behaviour - this is why we are disabling it for projects
	if ( 'jetpack-portfolio' === get_post_type() ) {
		add_filter( 'jp_carousel_force_enable' , 'noah_force_enable_jetpack_carousel' );
		add_filter( 'jp_carousel_maybe_disable', 'noah_disable_jetpack_carousel' );
	}

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

/**
 * This forces the Jetpack Carousel to be active when loading a page in case it is not (this will not activate the option in the Jetpack's settings).
 *
 * @return bool
 */
function noah_force_enable_jetpack_carousel() {
	return true;
}

/**
 * This forces the Jetpack Carousel to stop doing it's logic in the frontend for portfolio. This way we can do our own custom thing.
 *
 * @return bool
 */
function noah_disable_jetpack_carousel() {
	return true;
}

/**
 * Class NoahGalleryCustomSlideshow
 *
 * Code borrowed from the Jetpack_Carousel class
 */
class NoahGalleryCustomSlideshow {

	public $first_run = true;

	public $in_gallery = false;

	function __construct() {
		add_action( 'after_setup_theme', array( $this, 'init' ) );
	}

	function init() {

		// If on front-end, do the Slideshow thing.
		add_filter( 'post_gallery', array( $this, 'enqueue_assets' ), 1000, 2 ); // load later than other callbacks hooked it
		add_filter( 'post_gallery', array( $this, 'set_in_gallery' ), -1000 );
		add_filter( 'gallery_style', array( $this, 'add_data_to_container' ) );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_data_to_images' ), 10, 2 );
	}

	function display_bail_message( $output= '' ) {
		// Displays a message on top of gallery if slideshow has bailed
		$message = '<div class="jp-carousel-msg"><p>';
		$message .= __( 'The Slideshow has been disabled, because another plugin or your theme is overriding the [gallery] shortcode.', 'noah' );
		$message .= '</p></div>';
		// put before gallery output
		$output = $message . $output;
		return $output;
	}

	function enqueue_assets( $output ) {
		if (
			! empty( $output ) &&
			/**
			 * Allow third-party plugins or themes to force-enable Slideshow.
			 *
			 * @module carousel
			 *
			 * @since 1.9.0
			 *
			 * @param bool false Should we force enable Slideshow? Default to false.
			 */
			! apply_filters( 'jp_carousel_force_enable', false )
		) {
			// Bail because someone is overriding the [gallery] shortcode.
			remove_filter( 'gallery_style', array( $this, 'add_data_to_container' ) );
			remove_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_data_to_images' ) );
			// Display message that carousel has bailed, if user is super_admin, and if we're not on WordPress.com.
			if (
				is_super_admin() &&
				! ( defined( 'IS_WPCOM' ) && IS_WPCOM )
			) {
				add_filter( 'post_gallery', array( $this, 'display_bail_message' ) );
			}
			return $output;
		}

		if ( $this->first_run ) {

			$localize_strings = array(
				'display_exif'         => $this->test_1or0_option( $this->get_option_and_ensure_autoload( 'carousel_display_exif', true ) ),
				'download_original'    => sprintf( __( 'View full size <span class="photo-size">%1$s<span class="photo-size-times">&times;</span>%2$s</span>', 'noah' ), '{0}', '{1}' ),
				'camera'               => __( 'Camera', 'noah' ),
				'aperture'             => __( 'Aperture', 'noah' ),
				'shutter_speed'        => __( 'Shutter Speed', 'noah' ),
				'focal_length'         => __( 'Focal Length', 'noah' ),
			);

			/**
			 * Filter the strings passed to the Slideshow's js file.
			 *
			 * @module carousel
			 *
			 * @since 1.6.0
			 *
			 * @param array $localize_strings Array of strings passed to the Jetpack js file.
			 */
			$localize_strings = apply_filters( 'noah_slideshow_localize_strings', $localize_strings );
			wp_localize_script( 'noah-scripts', 'noahSlideshowStrings', $localize_strings );

			$this->first_run = false;
		}

		return $output;
	}

	/**
	 * Returns the requested option, and ensures it's autoloaded in the future.
	 * This does _not_ adjust the prefix in any way (does not prefix jetpack_%)
	 *
	 * @param string $name Option name
	 * @param mixed $default (optional)
	 *
	 * @return mixed
	 */
	public function get_option_and_ensure_autoload( $name, $default ) {
		$value = get_option( $name );

		if ( $value === false && $default !== false ) {
			update_option( $name, $default );
			$value = $default;
		}

		return $value;
	}

	function set_in_gallery( $output ) {
		$this->in_gallery = true;
		return $output;
	}

	function add_data_to_images( $attr, $attachment = null ) {

		// not in a gallery?
		if ( ! $this->in_gallery ) {
			return $attr;
		}

		$attachment_id   = intval( $attachment->ID );
		$orig_file       = wp_get_attachment_image_src( $attachment_id, 'full' );
		$orig_file       = isset( $orig_file[0] ) ? $orig_file[0] : wp_get_attachment_url( $attachment_id );
		$meta            = wp_get_attachment_metadata( $attachment_id );
		$size            = isset( $meta['width'] ) ? intval( $meta['width'] ) . ',' . intval( $meta['height'] ) : '';
		$img_meta        = ( ! empty( $meta['image_meta'] ) ) ? (array) $meta['image_meta'] : array();
		$comments_opened = intval( comments_open( $attachment_id ) );

		/*
		* Note: Cannot generate a filename from the width and height wp_get_attachment_image_src() returns because
		* it takes the $content_width global variable themes can set in consideration, therefore returning sizes
		* which when used to generate a filename will likely result in a 404 on the image.
		* $content_width has no filter we could temporarily de-register, run wp_get_attachment_image_src(), then
		* re-register. So using returned file URL instead, which we can define the sizes from through filename
		* parsing in the JS, as this is a failsafe file reference.
		*
		* EG with Twenty Eleven activated:
		* array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(584) [2]=> int(435) [3]=> bool(true) }
		*
		* EG with Twenty Ten activated:
		* array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(640) [2]=> int(477) [3]=> bool(true) }
		*/

		$medium_file_info = wp_get_attachment_image_src( $attachment_id, 'medium' );
		$medium_file      = isset( $medium_file_info[0] ) ? $medium_file_info[0] : '';

		$large_file_info  = wp_get_attachment_image_src( $attachment_id, 'large' );
		$large_file       = isset( $large_file_info[0] ) ? $large_file_info[0] : '';

		$attachment       = get_post( $attachment_id );
		$attachment_title = wptexturize( $attachment->post_title );
		$attachment_desc  = wpautop( wptexturize( $attachment->post_content ) );

		// Not yet providing geo-data, need to "fuzzify" for privacy
		if ( ! empty( $img_meta ) ) {
			foreach ( $img_meta as $k => $v ) {
				if ( 'latitude' == $k || 'longitude' == $k )
					unset( $img_meta[$k] );
			}
		}

		// See https://github.com/Automattic/jetpack/issues/2765
		if ( isset( $img_meta['keywords'] ) ) {
			unset( $img_meta['keywords'] );
		}

		$img_meta = json_encode( array_map( 'strval', $img_meta ) );

		$attr['data-attachment-id']     = $attachment_id;
		$attr['data-orig-file']         = esc_attr( $orig_file );
		$attr['data-orig-size']         = $size;
		$attr['data-comments-opened']   = $comments_opened;
		$attr['data-image-meta']        = esc_attr( $img_meta );
		$attr['data-image-title']       = esc_attr( $attachment_title );
		$attr['data-image-description'] = esc_attr( $attachment_desc );
		$attr['data-medium-file']       = esc_attr( $medium_file );
		$attr['data-large-file']        = esc_attr( $large_file );

		return $attr;
	}

	function add_data_to_container( $html ) {
		global $post;

		if ( isset( $post ) ) {
			$blog_id = (int) get_current_blog_id();

			$extra_data = array(
				'data-carousel-extra' => array(
					'blog_id' => $blog_id,
					'permalink' => get_permalink( $post->ID ),
				)
			);

			/**
			 * Filter the data added to the Gallery container.
			 *
			 * @module carousel
			 *
			 * @since 1.6.0
			 *
			 * @param array $extra_data Array of data about the site and the post.
			 */
			$extra_data = apply_filters( 'jp_carousel_add_data_to_container', $extra_data );
			foreach ( (array) $extra_data as $data_key => $data_values ) {
				$html = str_replace( '<div ', '<div ' . esc_attr( $data_key ) . "='" . json_encode( $data_values ) . "' ", $html );
			}
		}

		return $html;
	}

	function test_1or0_option( $value, $default_to_1 = true ) {
		if ( true == $default_to_1 ) {
			// Binary false (===) of $value means it has not yet been set, in which case we do want to default sites to 1
			if ( false === $value )
				$value = 1;
		}
		return ( 1 == $value ) ? 1 : 0;
	}

	function sanitize_1or0_option( $value ) {
		return ( 1 == $value ) ? 1 : 0;
	}
}
new NoahGalleryCustomSlideshow;