<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Empty
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once 'includes/functions.php';

/**
 * Manually load the theme being tested.
 */

function _manually_load_environment() {

	// Add your theme …
	switch_theme('kunst');
}
tests_add_filter( 'muplugins_loaded', '_manually_load_environment' );

require 'includes/bootstrap.php';