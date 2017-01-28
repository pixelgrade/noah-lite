<?php
/**
 * Require files that deal with various Pixelgrade components.
 *
 * @package Noah
 * @since Noah 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load our global Pixelgrade template tags
 */
require_once 'pixelgrade_template-tags.php';

/*==========================
	LOAD THE COMPONENTS
==========================*/


/*++++++++++++++++++++++++*/
/**
 * Load the Pixelgrade Gallery component.
 * https://pixelgrade.com/
 */

/**
 * Returns the main instance of Pixelgrade_Gallery_Settings to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Pixelgrade_Gallery_Settings
 */
function Pixelgrade_Gallery_Settings() {
	//only load if we have to
	if ( ! class_exists( 'Pixelgrade_Gallery_Settings') ) {
		pxg_load_component_file( 'gallery', 'class-gallery-settings' );
	}
	return Pixelgrade_Gallery_Settings::instance();
}

// Load The Gallery
$gallery_settings_instance = Pixelgrade_Gallery_Settings();
/*------------------------*/

/*=============================
FINISHED LOADING THE COMPONENTS
=============================*/