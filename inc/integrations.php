<?php
/**
 * Require files that deal with various plugin integrations.
 *
 * @package Noah Lite
 */

/**
 * Load theme's configuration file (via Customify plugin)
 */
require_once trailingslashit( get_template_directory() ) . 'inc/integrations/customify.php';
