<?php
/**
 * Noah Theme Customizer.
 *
 * @package noah
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function noah_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action( 'customize_register', 'noah_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function noah_customize_js() {
	wp_enqueue_script( 'noah_customizer', get_template_directory_uri() . '/assets/js/admin/customizer.js', array( 'wp-ajax-response' ), null, true );
	$translation_array = array(
		'import_failed'               => esc_html__( 'The import didn\'t work completely!', 'noah' ) . '<br/>' . esc_html__( 'Check out the errors given. You might want to try reloading the page and try again.', 'noah' ),
		'import_confirm'              => esc_html__( 'Importing the demo data will overwrite your current site content and options. Proceed anyway?', 'noah' ),
		'import_phew'                 => esc_html__( 'Phew...that was a hard one!', 'noah' ),
		'import_success_note'         => esc_html__( 'The demo data was imported without a glitch! Awesome! ', 'noah' ) . '<br/><br/>',
		'import_success_reload'       => esc_html__( '<i>We have reloaded the page on the right, so you can see the brand new data!</i>', 'noah' ),
		'import_success_warning'      => '<p>' . esc_html__( 'Remember to update the passwords and roles of imported users.', 'noah' ) . '</p><br/>',
		'import_all_done'             => esc_html__( 'All done!', 'noah' ),
		'import_working'              => esc_html__( 'Working...', 'noah' ),
		'import_widgets_failed'       => esc_html__( 'The setting up of the demo widgets failed...', 'noah' ),
		'import_widgets_error'        => esc_html__( 'The setting up of the demo widgets failed', 'noah' ) . '</i><br />(' . esc_html__( 'The script returned the following message', 'noah' ),
		'import_widgets_done'         => esc_html__( 'Finished setting up the demo widgets...', 'noah' ),
		'import_theme_options_failed' => esc_html__( 'The importing of the theme options has failed...', 'noah' ),
		'import_theme_options_error'  => esc_html__( 'The importing of the theme options has failed', 'noah' ) . '</i><br />(' . esc_html__( 'The script returned the following message', 'noah' ),
		'import_theme_options_done'   => esc_html__( 'Finished importing the demo theme options...', 'noah' ),
		'import_posts_failed'         => esc_html__( 'The importing of the theme options has failed...', 'noah' ),
		'import_posts_step'           => esc_html__( 'Importing posts | Step', 'noah' ),
		'import_error'                => esc_html__( 'Error:', 'noah' ),
		'import_try_reload'           => esc_html__( 'You can reload the page and try again.', 'noah' ),
	);
	wp_localize_script( 'noah_customizer', 'noah_admin_js_texts', $translation_array );
}
add_action( 'customize_controls_enqueue_scripts', 'noah_customize_js' );


// @todo CLEANUP refactor function names
/**
 * Imports the demo data from the demo_data.xml file
 */
if ( ! function_exists( 'wpGrade_ajax_import_posts_pages' ) ) {
	function wpGrade_ajax_import_posts_pages() {
		// initialize the step importing
		$stepNumber    = 1;
		$numberOfSteps = 1;

		// get the data sent by the ajax call regarding the current step
		// and total number of steps
		if ( ! empty( $_REQUEST['step_number'] ) ) {
			$stepNumber = wp_unslash( sanitize_text_field( $_REQUEST['step_number'] ) );
		}

		if ( ! empty( $_REQUEST['number_of_steps'] ) ) {
			$numberOfSteps = wp_unslash( sanitize_text_field( $_REQUEST['number_of_steps'] ) );
		}

		$response = array(
			'what'         => 'import_posts_pages',
			'action'       => 'import_submit',
			'id'           => 'true',
			'supplemental' => array(
				'stepNumber'    => $stepNumber,
				'numberOfSteps' => $numberOfSteps,
			)
		);

		// check if user is allowed to save and if its his intention with
		// a nonce check
		if ( function_exists( 'check_ajax_referer' ) ) {
			check_ajax_referer( 'wpGrade_nonce_import_demo_posts_pages' );
		}

		require_once( get_template_directory() . '/inc/import/import-demo-posts-pages.php' );

		$response = new WP_Ajax_Response( $response );
		$response->send();
	}

	// hook into wordpress admin.php
	add_action( 'wp_ajax_wpGrade_ajax_import_posts_pages', 'wpGrade_ajax_import_posts_pages' );
}

/**
 * Imports the theme options from the demo_data.php file
 */
if ( ! function_exists( 'wpGrade_ajax_import_theme_options' ) ) {
	function wpGrade_ajax_import_theme_options() {
		$response = array(
			'what'   => 'import_theme_options',
			'action' => 'import_submit',
			'id'     => 'true',
		);

		// check if user is allowed to save and if its his intention with
		// a nonce check
		if ( function_exists( 'check_ajax_referer' ) ) {
			check_ajax_referer( 'wpGrade_nonce_import_demo_theme_options' );
		}
		require_once( get_template_directory() . '/inc/import/import-demo-theme-options' . EXT );

		$response = new WP_Ajax_Response( $response );
		$response->send();
	}

	// hook into wordpress admin.php
	add_action( 'wp_ajax_wpGrade_ajax_import_theme_options', 'wpGrade_ajax_import_theme_options' );
}

/**
 * This function imports the widgets from the demo_data.php file and the menus
 */
if ( ! function_exists( 'wpGrade_ajax_import_widgets' ) ) {
	function wpGrade_ajax_import_widgets() {
		$response = array(
			'what'   => 'import_widgets',
			'action' => 'import_submit',
			'id'     => 'true',
		);

		// check if user is allowed to save and if its his intention with
		// a nonce check
		if ( function_exists( 'check_ajax_referer' ) ) {
			check_ajax_referer( 'wpGrade_nonce_import_demo_widgets' );
		}

		require_once( get_template_directory() . '/inc/import/import-demo-widgets.php' );

		$response = new WP_Ajax_Response( $response );
		$response->send();
	}

	//hook into wordpress admin.php
	add_action( 'wp_ajax_wpGrade_ajax_import_widgets', 'wpGrade_ajax_import_widgets' );
}
