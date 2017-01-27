<?php
/**
 *  This file is taken from Jetpack and it's here for the case in which Jetpack is not active
 */

// Get the current request action
$action = pxg_current_action();

// Don't try and load the fallback class if we are activating a plugin
// This way Jetpack doesn't burn us down with it's lack of checking if the class exists
if ( ! empty( $action ) && $action == 'activate' ) {
    return;
}

if ( ! class_exists( 'Jetpack_Gallery_Settings' ) && ! class_exists( 'Jetpack_Gallery_Settings_Fallback' ) ) {
	/**
	 * Renders extra controls in the Gallery Settings section of the new media UI.
	 */
	class Jetpack_Gallery_Settings_Fallback {
		function __construct() {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

		function admin_init() {
			/**
			 * Filter the available gallery types.
			 *
			 * @module shortcodes, tiled-gallery
			 *
			 * @since 2.5.1
			 *
			 * @param array $value Array of the default thumbnail grid gallery type. Default array contains one key, 'default'.
			 *
			 */
			$this->gallery_types = apply_filters( 'jetpack_gallery_types', array( 'default' => __( 'Thumbnail Grid', 'noah' ) ) );

			// Enqueue the media UI only if needed.
			if ( count( $this->gallery_types ) > 1 ) {
				add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );
				add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
			}
		}

		/**
		 * Registers/enqueues the gallery settings admin js.
		 */
		function wp_enqueue_media() {
			if ( ! wp_script_is( 'jetpack-gallery-settings', 'registered' ) ) {
				/**
				 * This only happens if we're not in Jetpack, but on WPCOM instead.
				 * This is the correct path for WPCOM.
				 */
				wp_register_script( 'jetpack-gallery-settings', trailingslashit( get_template_directory_uri() ) . 'components/gallery/jetpack-fallback/gallery-settings.js', array( 'media-views' ), '20121225' );
			}

			wp_enqueue_script( 'jetpack-gallery-settings' );
		}

		/**
		 * Outputs a view template which can be used with wp.media.template
		 */
		function print_media_templates() {
			/**
			 * Filter the default gallery type.
			 *
			 * @module tiled-gallery
			 *
			 * @since 2.5.1
			 *
			 * @param string $value A string of the gallery type. Default is 'default'.
			 *
			 */
			$default_gallery_type = apply_filters( 'jetpack_default_gallery_type', 'default' );

			?>
            <script type="text/html" id="tmpl-jetpack-gallery-settings">
                <label class="setting">
                    <span><?php _e( 'Type', 'noah' ); ?></span>
                    <select class="type" name="type" data-setting="type">
						<?php foreach ( $this->gallery_types as $value => $caption ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_gallery_type ); ?>><?php echo esc_html( $caption ); ?></option>
						<?php endforeach; ?>
                    </select>
                </label>
            </script>
			<?php
		}
	}

	new Jetpack_Gallery_Settings_Fallback;
}
