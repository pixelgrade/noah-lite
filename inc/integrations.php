<?php
/**
 * Require files that deal with various plugin integrations.
 *
 * @package Noah
 */

/**
 * Load Customify compatibility file.
 * http://pixelgrade.com/
 */
require get_template_directory() . '/inc/integrations/customify.php';

/**
 * Load PixTypes compatibility file.
 * http://pixelgrade.com/
 */
require get_template_directory() . '/inc/integrations/pixtypes.php';

/**
 * Load Jetpack compatibility file.
 * http://jetpack.me/
 */
require get_template_directory() . '/inc/integrations/jetpack.php';

/**
 * Load WPForms compatibility file.
 */
require get_template_directory() . '/inc/integrations/wpforms.php';