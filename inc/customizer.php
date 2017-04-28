<?php
/**
 * Noah Lite Theme Customizer.
 *
 * @package noah
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function noahlite_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title',
			'render_callback' => 'noahlite_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description-text',
			'render_callback' => 'noahlite_customize_partial_blogdescription',
		) );
	}

	/* =========================
	 * Add custom Theme settings
	 * ========================= */


	// View PRO Version
	$wp_customize->add_section( 'noahlite_style_view_pro', array(
		'title'       => '' . esc_html__( 'View PRO Version', 'noah-lite' ),
		'priority'    => 2,
		'description' => sprintf(
			__( '<div class="upsell-container">
					<h2>Need More? Go PRO</h2>
					<p>Take it to the next level. See the features below:</p>
					<ul class="upsell-features">
                            <li>
                            	<h4>Personalize to Match Your Style</h4>
                            	<div class="description">Having different tastes and preferences might be tricky for users, but not with Silk onboard. It has an intuitive and catchy interface which allows you to change <strong>fonts, colors or layout sizes</strong> in a blink of an eye.</div>
                            </li>

                            <li>
                            	<h4>Featured Posts Slider</h4>
                            	<div class="description">Bring your best stories in the front of the world by adding them into the posts slider. It’s an extra opportunity to grab attention and to refresh some content that is still meaningful. Don’t miss any occasion to increase the value of your stories.</div>
                            </li>

                            <li>
                            	<h4>Custom Mega Menu</h4>
                            	<div class="description">Showcase the latest posts from a category under menu without losing precious time and money. Highlight those articles you feel proud about with no effort and let people know about your appetite for a topic or another.</div>
                            </li>

                            <li>
                            	<h4>Premium Customer Support</h4>
                            	<div class="description">You will benefit by priority support from a caring and devoted team, eager to help and to spread happiness. We work hard to provide a flawless experience for those who vote us with trust and choose to be our special clients.</div>
                            </li>
                            
                    </ul> %s </div>', 'noah-lite' ),
			sprintf( '<a href="%1$s" target="_blank" class="button button-primary">%2$s</a>', esc_url( noahlite_get_pro_link() ), esc_html__( 'View Noah PRO', 'noah-lite' ) )
		),
	) );

	$wp_customize->add_setting( 'noahlite_style_view_pro_desc', array(
		'default'           => '',
		'sanitize_callback' => 'noahlite_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'noahlite_style_view_pro_desc', array(
		'section' => 'noahlite_style_view_pro',
		'type'    => 'hidden',
	) );

	/* =============================
	 * Add custom Portfolio settings
	 * ============================= */

	$wp_customize->add_section( 'noahlite_portfolio_options', array(
		'title'             => esc_html__( 'Portfolio Grid', 'noah-lite' ),
		'description' => esc_html__( 'Here you can control the portfolio grid\'s layout and features.', 'noah-lite' ),
		'priority'          => 32,
	) );

	/* Portfolio Options */
	$wp_customize->add_setting( 'noahlite_portfolio_items_title_alignment_nearby', array(
		'default' => 'left',
		'sanitize_callback' => 'noahlite_sanitize_items_title_alignment_nearby',
	) );
	$wp_customize->add_control( 'noahlite_portfolio_items_title_alignment_nearby', array(
		'type'    => 'select',
		'label'   => esc_html__( 'Project Details Alignment', 'noah-lite' ),
		'desc'    => esc_html__( 'Adjust the alignment of your project details like title, author.', 'noah-lite' ),
		'choices' => array(
			'left'   => esc_html__( '← Left', 'noah-lite' ),
			'center' => esc_html__( '↔ Center', 'noah-lite' ),
			'right'  => esc_html__( '→ Right', 'noah-lite' ),
		),
		'section' => 'noahlite_portfolio_options',
	) );

	$wp_customize->add_setting( 'noahlite_portfolio_items_primary_meta', array(
		'default' => 'none',
		'sanitize_callback' => 'noahlite_sanitize_items_primary_meta',
	) );
	$wp_customize->add_control( 'noahlite_portfolio_items_primary_meta', array(
		'type'    => 'select',
		'label'   => esc_html__( 'Main Project Meta', 'noah-lite' ),
		'desc'    => esc_html__( 'Choose what is the main information you wish to display about a project, besides the title.', 'noah-lite' ),
		'choices' => array(
			'none'     => esc_html__( 'None', 'noah-lite' ),
			'category' => esc_html__( 'Categories', 'noah-lite' ),
			'author'   => esc_html__( 'Author', 'noah-lite' ),
			'date'     => esc_html__( 'Date', 'noah-lite' ),
			'tags'     => esc_html__( 'Tags', 'noah-lite' ),
			'comments' => esc_html__( 'Comments', 'noah-lite' ),
		),
		'section' => 'noahlite_portfolio_options',
	) );

	$wp_customize->add_setting( 'noahlite_portfolio_items_title_visibility', array(
		'default'           => '1',
		'sanitize_callback' => 'noahlite_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'noahlite_portfolio_items_title_visibility', array(
		'label'   => esc_html__( 'Show Title', 'noah-lite' ),
		'section'           => 'noahlite_portfolio_options',
		'type'              => 'checkbox',
	) );
}
add_action( 'customize_register', 'noahlite_customize_register', 11, 1 );

/* =========================
 * SANITIZATION FOR SETTINGS
 * ========================= */

/**
 * Sanitize the grid items title alignment (nearby) options.
 */
function noahlite_sanitize_items_title_alignment_nearby( $input ) {
	$valid = array(
		'left'   => esc_html__( '&larr; Left', 'noah-lite' ),
		'center' => esc_html__( '&harr; Center', 'noah-lite' ),
		'right'  => esc_html__( '&rarr; Right', 'noah-lite' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid items primary meta options.
 */
function noahlite_sanitize_items_primary_meta( $input ) {
	$valid = array(
		'none'     => esc_html__( 'None', 'noah-lite' ),
		'category' => esc_html__( 'Category', 'noah-lite' ),
		'author'   => esc_html__( 'Author', 'noah-lite' ),
		'date'     => esc_html__( 'Date', 'noah-lite' ),
		'tags'     => esc_html__( 'Tags', 'noah-lite' ),
		'comments' => esc_html__( 'Comments', 'noah-lite' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean true if is 1 or '1', false if anything else
 */
function noahlite_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/* ============================
 * Customizer rendering helpers
 * ============================ */

/**
 * Render the site title for the selective refresh partial.
 *
 * @see noahlite_customize_register()
 *
 * @return void
 */
function noahlite_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @see noahlite_customize_register()
 *
 * @return void
 */
function noahlite_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function noahlite_customize_preview_js() {
	/**
	 * Get theme details inside `$theme` object and later use it for cache busting
	 * with `$theme->get( 'Version' )` method
	 */
	$theme = wp_get_theme();

	wp_enqueue_script( 'noah-lite-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array( 'customize-preview' ), $theme->get( 'Version' ), true );
}
add_action( 'customize_preview_init', 'noahlite_customize_preview_js' );

/**
 * Assets that will be loaded for the customizer sidebar
 */
function noahlite_customizer_assets() {
	wp_enqueue_style( 'noahlite_customizer_style', get_template_directory_uri() . '/assets/css/admin/customizer.css', null, '1.0.4', false );

	wp_enqueue_script( 'noahlite_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'jquery' ), '1.0.4', false );

	// uncomment this to put back your dismiss notice
	// update_user_meta( get_current_user_id(), 'noahlite_upgrade_dismissed_notice', 0 );
	if ( isset( $_GET['noah-upgrade-dismiss'] ) && check_admin_referer( 'noah-upgrade-dismiss-' . get_current_user_id() ) ) {
		update_user_meta( get_current_user_id(), 'noahlite_upgrade_dismissed_notice', 'forever' );
		return;
	}

	$dismiss_user = get_user_meta( get_current_user_id(), 'noahlite_upgrade_dismissed_notice', true );
	if ( $dismiss_user === 'forever' ) {
		return;
	} elseif ( empty( $dismiss_user ) || ( is_numeric( $dismiss_user ) && $dismiss_user < 2  ) ) {

		$value = $dismiss_user + 1;
		update_user_meta( get_current_user_id(), 'noahlite_upgrade_dismissed_notice', $value );
		return;
	}

	$localized_strings = array(
		'upsell_link'     => noahlite_get_pro_link(),
		'upsell_label'    => esc_html__( 'Upgrade to Noah Pro', 'noah-lite' ),
		'pro_badge_label' => esc_html__( 'Pro', 'noah-lite' ) . '<span class="star"></span>',
		'dismiss_link' => esc_url( wp_nonce_url( add_query_arg( 'noah-upgrade-dismiss', 'forever' ), 'noah-upgrade-dismiss-' . get_current_user_id() ) )
	);

	wp_localize_script( 'noahlite_customizer', 'noahCustomizerObject', $localized_strings );
}
add_action( 'customize_controls_enqueue_scripts', 'noahlite_customizer_assets' );

/**
 * Generate a link to the noah Lite info page.
 */
function noahlite_get_pro_link() {
	return 'https://pixelgrade.com/themes/noah?utm_source=noah-lite-clients&utm_medium=customizer&utm_campaign=noah-lite#go-pro';
}
