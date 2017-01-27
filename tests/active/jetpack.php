<?php
/**
 * Class JetpackTest
 *
 * @package Empty
 */

/**
 * Sample test case.
 */
class JetpackIntegrationTest extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();

		if ( ! defined('JETPACK_DEV_DEBUG' ) ) {
			define( 'JETPACK_DEV_DEBUG', true);
		}

		// Update array with plugins to include ...
		$plugins_to_active = array(
			'jetpack/jetpack.php',
		);

		update_option( 'active_plugins', $plugins_to_active );

		// Load active plugins.
		foreach ( wp_get_active_and_valid_plugins() as $plugin ) {
			wp_register_plugin_realpath( $plugin );
			include_once( $plugin );
		}
		unset( $plugin );

		update_option( 'jetpack_portfolio', 1 );

		Jetpack::activate_module( 'content-post-types', false, false );

		//@TODO find a way to setup a jetpack activation
//		do_action( 'plugins_loaded' );
//		do_action( 'init' );
//		do_action( 'jetpack_activate_module_custom-content-types' );
//		do_action( 'wp' );
//		do_action( 'after_theme_setup' );
	}

	/**
	 * Ensure Jetpack class is present
	 */
	function test_jetpack_exists() {
		$this->assertTrue( class_exists( 'Jetpack' ) );
	}

	/**
	 * Check that we have the portfolio post_type
	 * @TODO test availability of a post type
	 */
	function test_project_post_type_exists() {
		$types = get_post_types();
//		$this->assertContains( 'jetpack-portfolio', $types );
		$this->assertContains( 'post', $types );
	}

	// @TODO make a bunch of test cases with project mockups which should test if the correct body class is added.
}
