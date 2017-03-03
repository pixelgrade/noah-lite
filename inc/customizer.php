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

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title',
			'render_callback' => 'noah_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description-text',
			'render_callback' => 'noah_customize_partial_blogdescription',
		) );
	}

	/* =========================
	 * Add custom Theme settings
	 * ========================= */


	// View PRO Version
	$wp_customize->add_section( 'noahlite_style_view_pro', array(
		'title'       => '' . esc_html__( 'View PRO Version', 'silk-lite' ),
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
                            
                    </ul> %s </div>', 'silk-lite' ),
			sprintf( '<a href="%1$s" target="_blank" class="button button-primary">%2$s</a>', esc_url( noahlite_get_pro_link() ), esc_html__( 'View Noah PRO', 'silk-lite' ) )
		),
	) );

	$wp_customize->add_setting( 'noahlite_style_view_pro_desc', array(
		'default'           => '',
		'sanitize_callback' => 'silklite_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'noahlite_style_view_pro_desc', array(
		'section' => 'noahlite_style_view_pro',
		'type'    => 'hidden',
	) );

	$wp_customize->add_section( 'noah_theme_options', array(
		'title'             => esc_html__( 'Theme', 'noah' ),
		'description' => esc_html__( 'Your theme\'s general settings.', 'noah' ),
		'priority'          => 30,
	) );

	/* General Options */
	$wp_customize->add_setting( 'noah_use_ajax_loading', array(
		'default'           => '1',
		'sanitize_callback' => 'noah_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'noah_use_ajax_loading', array(
		'label'             => esc_html__( '[Remove] Enable dynamic page content loading using AJAX.', 'noah' ),
		'section'           => 'noah_theme_options',
		'type'              => 'checkbox',
	) );

	/* Header Options */
	$wp_customize->add_setting( 'noah_header_position', array(
		'default'           => 'sticky',
		'sanitize_callback' => 'noah_sanitize_header_position',
	) );
	$wp_customize->add_control( 'noah_header_position', array(
		'label'   => esc_html__( '[Remove - Sa fie doar static] Header Position', 'noah' ),
		'section' => 'noah_theme_options',
		'type'    => 'select',
		'desc'    => esc_html__( 'Choose if you want a static menu or a fixed (sticky) one that stays visible no matter how much you scroll the page.', 'noah' ),
		'choices' => array(
			'static' => esc_html__( 'Static', 'noah' ),
			'sticky' => esc_html__( 'Sticky (fixed)', 'noah' ),
		),
	) );

	$wp_customize->add_setting( 'noah_header_width', array(
		'default' => 'full',
		'sanitize_callback' => 'noah_sanitize_header_width',
	) );
	$wp_customize->add_control( 'noah_header_width', array(
		'type'    => 'select',
		'label'   => esc_html__( 'Header Width', 'components' ),
		'desc'    => esc_html__( 'Choose if you want the header span to the full-browser or stay aligned with the site container width.', 'components' ),

		'choices' => array(
			'full'      => esc_html__( 'Full Browser Width', 'components' ),
			'container' => esc_html__( 'Container Width', 'components' ),
		),
	) );

	/* Footer Options */
	$wp_customize->add_setting( 'noah_footer_layout', array(
		'default'           => 'row',
		'sanitize_callback' => 'noah_sanitize_footer_layout',
	) );
	$wp_customize->add_control( 'noah_footer_layout', array(
		'label'       => esc_html__( '"[Remove] Footer Area" Widgets Layout', 'noah' ),
		'section'     => 'noah_theme_options',
		'type'        => 'select',
		'description' => __( 'Choose if you want the footer widgets stack into one column or spread to a row.', 'noah' ),
		'choices'     => array(
			'stacked' => esc_html__( 'Stacked', 'noah' ),
			'row'     => esc_html__( 'Row', 'noah' ),
		),
	) );

	$wp_customize->add_setting( 'noah_footer_hide_back_to_top_link', array(
		'default'           => '',
		'sanitize_callback' => 'noah_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'noah_footer_hide_back_to_top_link', array(
		'label'             => __( '[Removed - Sa scoate de tot butonul] Hide Footer "Back To Top" Link', 'noah' ),
		'section'           => 'noah_theme_options',
		'type'              => 'checkbox',
	) );

	/* =============================
	 * Add custom Portfolio settings
	 * ============================= */

	$wp_customize->add_section( 'noah_portfolio_options', array(
		'title'             => esc_html__( 'Portfolio Grid', 'noah' ),
		'description' => esc_html__( 'Here you can control the portfolio grid\'s layout and features.', 'noah' ),
		'priority'          => 32,
	) );

	/* Portfolio Options */
	$wp_customize->add_setting( 'noah_portfolio_grid_layout', array(
		'default' => 'regular',
		'sanitize_callback' => 'noah_sanitize_grid_layout',
	) );
	$wp_customize->add_control( 'noah_portfolio_grid_layout', array(
		'type'    => 'radio',
		'label'   => esc_html__( 'Portfolio Grid Layout', 'noah' ),
		'desc'    => esc_html__( 'Choose whether the items display in a fixed height regular grid, or in a mosaic style layout.', 'noah' ),
		'choices' => array(
			'regular' => esc_html__( 'Regular Grid', 'noah' ),
			// 'masonry' => esc_html__( 'Masonry', 'noah' ),
			// 'packed'  => esc_html__( 'Packed', 'noah' ),
		),
		'section' => 'noah_portfolio_options',
	) );

	// $wp_customize->add_setting( 'noah_portfolio_items_title_position', array(
	// 	'default' => 'below',
	// 	'sanitize_callback' => 'noah_sanitize_items_title_position',
	// ) );
	// $wp_customize->add_control( 'noah_portfolio_items_title_position', array(
	// 	'type'    => 'radio',
	// 	'label'   => esc_html__( 'Project Details Position', 'noah' ),
	// 	'desc'    => esc_html__( 'Choose whether the project details are placed nearby the thumbnail or show as an overlay cover on  mouse over.', 'noah' ),
	// 	'choices' => array(
	// 		'above'   => esc_html__( 'Above', 'noah' ),
	// 		'below'   => esc_html__( 'Below', 'noah' ),
	// 		'overlay' => esc_html__( 'Overlay', 'noah' ),
	// 	),
	// 	'section' => 'noah_portfolio_options',
	// ) );

	$wp_customize->add_setting( 'noah_portfolio_items_title_alignment_nearby', array(
		'default' => 'left',
		'sanitize_callback' => 'noah_sanitize_items_title_alignment_nearby',
	) );
	$wp_customize->add_control( 'noah_portfolio_items_title_alignment_nearby', array(
		'type'    => 'select',
		'label'   => esc_html__( 'Project Details Alignment', 'noah' ),
		'desc'    => esc_html__( 'Adjust the alignment of your project details like title, author.', 'noah' ),
		'choices' => array(
			'left'   => esc_html__( '← Left', 'noah' ),
			'center' => esc_html__( '↔ Center', 'noah' ),
			'right'  => esc_html__( '→ Right', 'noah' ),
		),
		'section' => 'noah_portfolio_options',
		'active_callback' => 'noah_portfolio_items_title_alignment_nearby_control_show',
	) );

	// $wp_customize->add_setting( 'noah_portfolio_items_title_alignment_overlay', array(
	// 	'default' => 'middle-center',
	// 	'sanitize_callback' => 'noah_sanitize_items_title_alignment_overlay',
	// ) );
	// $wp_customize->add_control( 'noah_portfolio_items_title_alignment_overlay', array(
	// 	'type'    => 'select',
	// 	'label'   => esc_html__( 'Project Details Alignment', 'noah' ),
	// 	'desc'    => esc_html__( 'Adjust the alignment of your hover project details like title, author.', 'noah' ),
	// 	'choices' => array(
	// 		'top-left'   => esc_html__( '↑ Top     ← Left', 'noah' ),
	// 		'top-center' => esc_html__( '↑ Top     ↔ Center', 'noah' ),
	// 		'top-right'  => esc_html__( '↑ Top     → Right', 'noah' ),

	// 		'middle-left'   => esc_html__( '↕ Middle     ← Left', 'noah' ),
	// 		'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'noah' ),
	// 		'middle-right'  => esc_html__( '↕ Middle     → Right', 'noah' ),

	// 		'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'noah' ),
	// 		'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'noah' ),
	// 		'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'noah' ),
	// 	),
	// 	'section' => 'noah_portfolio_options',
	// 	'active_callback' => 'noah_portfolio_items_title_alignment_overlay_control_show',
	// ) );

	$wp_customize->add_setting( 'noah_portfolio_items_primary_meta', array(
		'default' => 'none',
		'sanitize_callback' => 'noah_sanitize_items_primary_meta',
	) );
	$wp_customize->add_control( 'noah_portfolio_items_primary_meta', array(
		'type'    => 'select',
		'label'   => esc_html__( 'Main Project Meta', 'noah' ),
		'desc'    => esc_html__( 'Choose what is the main information you wish to display about a project, besides the title.', 'noah' ),
		'choices' => array(
			'none'     => esc_html__( 'None', 'noah' ),
			'category' => esc_html__( 'Categories', 'noah' ),
			'author'   => esc_html__( 'Author', 'noah' ),
			'date'     => esc_html__( 'Date', 'noah' ),
			'tags'     => esc_html__( 'Tags', 'noah' ),
			'comments' => esc_html__( 'Comments', 'noah' ),
		),
		'section' => 'noah_portfolio_options',
		'active_callback' => 'noah_portfolio_items_primary_meta_control_show',
	) );

	$wp_customize->add_setting( 'noah_portfolio_items_title_visibility', array(
		'default'           => '1',
		'sanitize_callback' => 'noah_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'noah_portfolio_items_title_visibility', array(
		'label'   => esc_html__( 'Show Title', 'noah' ),
		'section'           => 'noah_portfolio_options',
		'type'              => 'checkbox',
	) );

	// $wp_customize->add_setting( 'noah_portfolio_items_excerpt_visibility', array(
	// 	'default'           => '0',
	// 	'sanitize_callback' => 'noah_sanitize_checkbox',
	// ) );
	// $wp_customize->add_control( 'noah_portfolio_items_excerpt_visibility', array(
	// 	'label'   => esc_html__( 'Show Excerpt Text', 'noah' ),
	// 	'section'           => 'noah_portfolio_options',
	// 	'type'              => 'checkbox',
	// 	'active_callback' => 'noah_portfolio_items_excerpt_visibility_control_show',
	// ) );

	$wp_customize->add_setting( 'noah_portfolio_items_secondary_meta', array(
		'default' => 'none',
		'sanitize_callback' => 'noah_sanitize_items_secondary_meta',
	) );
	// $wp_customize->add_control( 'noah_portfolio_items_secondary_meta', array(
	// 	'type'    => 'select',
	// 	'label'   => esc_html__( 'Secondary Project Meta', 'noah' ),
	// 	'desc'    => esc_html__( 'Choose what is the secondary information you wish to display about a project.', 'noah' ),
	// 	'choices' => array(
	// 		'none'     => esc_html__( 'None', 'noah' ),
	// 		'category' => esc_html__( 'Categories', 'noah' ),
	// 		'author'   => esc_html__( 'Author', 'noah' ),
	// 		'date'     => esc_html__( 'Date', 'noah' ),
	// 		'tags'     => esc_html__( 'Tags', 'noah' ),
	// 		'comments' => esc_html__( 'Comments', 'noah' ),
	// 	),
	// 	'section' => 'noah_portfolio_options',
	// 	'active_callback' => 'noah_portfolio_items_secondary_meta_control_show',
	// ) );
	// 

	/* ========================
	 * Add custom Blog settings
	 * ======================== */

	$wp_customize->add_section( 'noah_blog_options', array(
		'title'             => esc_html__( 'Blog Grid', 'noah' ),
		'description' => esc_html__( 'Here you can control the blog grid\'s layout and features.', 'noah' ),
		'priority'          => 34,
	) );

	/* Blog Options */
	$wp_customize->add_setting( 'noah_blog_grid_layout', array(
		'default' => 'masonry',
		'sanitize_callback' => 'noah_sanitize_grid_layout',
	) );
	// $wp_customize->add_control( 'noah_blog_grid_layout', array(
	// 	'type'    => 'radio',
	// 	'label'   => esc_html__( 'Blog Grid Layout', 'noah' ),
	// 	'desc'    => esc_html__( 'Choose whether the posts are shown in a fixed height regular grid, or in a mosaic style layout.', 'noah' ),
	// 	'choices' => array(
	// 		'regular' => esc_html__( 'Regular Grid', 'noah' ),
	// 		'masonry' => esc_html__( 'Masonry', 'noah' ),
	// 		'packed'  => esc_html__( 'Packed', 'noah' ),
	// 	),
	// 	'section' => 'noah_blog_options',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_title_position', array(
	// 	'default' => 'below',
	// 	'sanitize_callback' => 'noah_sanitize_items_title_position',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_title_position', array(
	// 	'type'    => 'radio',
	// 	'label'   => esc_html__( 'Post Details Position', 'noah' ),
	// 	'desc'    => esc_html__( 'Choose whether the post details are placed nearby the thumbnail or show as an overlay cover on  mouse over.', 'noah' ),
	// 	'choices' => array(
	// 		'above'   => esc_html__( 'Above', 'noah' ),
	// 		'below'   => esc_html__( 'Below', 'noah' ),
	// 		'overlay' => esc_html__( 'Overlay', 'noah' ),
	// 	),
	// 	'section' => 'noah_blog_options',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_title_alignment_nearby', array(
	// 	'default' => 'left',
	// 	'sanitize_callback' => 'noah_sanitize_items_title_alignment_nearby',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_title_alignment_nearby', array(
	// 	'type'    => 'select',
	// 	'label'   => esc_html__( 'Post Details Alignment', 'noah' ),
	// 	'desc'    => esc_html__( 'Adjust the alignment of your title.', 'noah' ),
	// 	'choices' => array(
	// 		'left'   => esc_html__( '← Left', 'noah' ),
	// 		'center' => esc_html__( '↔ Center', 'noah' ),
	// 		'right'  => esc_html__( '→ Right', 'noah' ),
	// 	),
	// 	'section' => 'noah_blog_options',
	// 	'active_callback' => 'noah_blog_items_title_alignment_nearby_control_show',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_title_alignment_overlay', array(
	// 	'default' => 'middle-center',
	// 	'sanitize_callback' => 'noah_sanitize_items_title_alignment_overlay',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_title_alignment_overlay', array(
	// 	'type'    => 'select',
	// 	'label'   => esc_html__( 'Post Details Alignment', 'noah' ),
	// 	'desc'    => esc_html__( 'Adjust the alignment of your hover title.', 'noah' ),
	// 	'choices' => array(
	// 		'top-left'   => esc_html__( '↑ Top     ← Left', 'noah' ),
	// 		'top-center' => esc_html__( '↑ Top     ↔ Center', 'noah' ),
	// 		'top-right'  => esc_html__( '↑ Top     → Right', 'noah' ),

	// 		'middle-left'   => esc_html__( '↕ Middle     ← Left', 'noah' ),
	// 		'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'noah' ),
	// 		'middle-right'  => esc_html__( '↕ Middle     → Right', 'noah' ),

	// 		'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'noah' ),
	// 		'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'noah' ),
	// 		'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'noah' ),
	// 	),
	// 	'section' => 'noah_blog_options',
	// 	'active_callback' => 'noah_blog_items_title_alignment_overlay_control_show',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_primary_meta', array(
	// 	'default' => 'none',
	// 	'sanitize_callback' => 'noah_sanitize_items_primary_meta',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_primary_meta', array(
	// 	'type'    => 'select',
	// 	'label'   => esc_html__( 'Main Post Meta', 'noah' ),
	// 	'desc'    => esc_html__( 'Choose what is the main information you wish to display about a post, besides the title.', 'noah' ),
	// 	'choices' => array(
	// 		'none'     => esc_html__( 'None', 'noah' ),
	// 		'category' => esc_html__( 'Categories', 'noah' ),
	// 		'author'   => esc_html__( 'Author', 'noah' ),
	// 		'date'     => esc_html__( 'Date', 'noah' ),
	// 		'tags'     => esc_html__( 'Tags', 'noah' ),
	// 		'comments' => esc_html__( 'Comments', 'noah' ),
	// 	),
	// 	'section' => 'noah_blog_options',
	// 	'active_callback' => 'noah_blog_items_primary_meta_control_show',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_title_visibility', array(
	// 	'default'           => '1',
	// 	'sanitize_callback' => 'noah_sanitize_checkbox',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_title_visibility', array(
	// 	'label'   => esc_html__( 'Show Title', 'noah' ),
	// 	'section'           => 'noah_blog_options',
	// 	'type'              => 'checkbox',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_excerpt_visibility', array(
	// 	'default'           => '0',
	// 	'sanitize_callback' => 'noah_sanitize_checkbox',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_excerpt_visibility', array(
	// 	'label'   => esc_html__( 'Show Excerpt Text', 'noah' ),
	// 	'section'           => 'noah_blog_options',
	// 	'type'              => 'checkbox',
	// 	'active_callback' => 'noah_blog_items_excerpt_visibility_control_show',
	// ) );

	// $wp_customize->add_setting( 'noah_blog_items_secondary_meta', array(
	// 	'default' => 'none',
	// 	'sanitize_callback' => 'noah_sanitize_items_secondary_meta',
	// ) );
	// $wp_customize->add_control( 'noah_blog_items_secondary_meta', array(
	// 	'type'    => 'select',
	// 	'label'   => esc_html__( 'Secondary Post Meta', 'noah' ),
	// 	'desc'    => esc_html__( 'Choose what is the secondary information you wish to display about a post.', 'noah' ),
	// 	'choices' => array(
	// 		'none'     => esc_html__( 'None', 'noah' ),
	// 		'category' => esc_html__( 'Categories', 'noah' ),
	// 		'author'   => esc_html__( 'Author', 'noah' ),
	// 		'date'     => esc_html__( 'Date', 'noah' ),
	// 		'tags'     => esc_html__( 'Tags', 'noah' ),
	// 		'comments' => esc_html__( 'Comments', 'noah' ),
	// 	),
	// 	'section' => 'noah_blog_options',
	// 	'active_callback' => 'noah_blog_items_secondary_meta_control_show',
	// ) );
}
add_action( 'customize_register', 'noah_customize_register', 11, 1 );

/* ====================================
 * PORTFOLIO GRID CONTROLS CONDITIONALS
 * ==================================== */

/**
 * Decides when to show the project nearby alignment control.
 *
 * @return bool
 */
function noah_portfolio_items_title_alignment_nearby_control_show() {
	$position = get_theme_mod( 'noah_portfolio_items_title_position' );
	// We hide it when displaying as overlay
	if ( 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the project overlay alignment control.
 *
 * @return bool
 */
function noah_portfolio_items_title_alignment_overlay_control_show() {
	$position = get_theme_mod( 'noah_portfolio_items_title_position' );
	// We hide it when not displaying as overlay
	if ( 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the portfolio show excerpt control.
 *
 * @return bool
 */
function noah_portfolio_items_excerpt_visibility_control_show() {
	$layout = get_theme_mod( 'noah_portfolio_grid_layout' );
	$position = get_theme_mod( 'noah_portfolio_items_title_position' );
	// We hide it if the layout is packed or the position is overlay
	if ( 'packed' == $layout || 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the portfolio primary meta control.
 *
 * @return bool
 */
function noah_portfolio_items_primary_meta_control_show() {
	$layout = get_theme_mod( 'noah_portfolio_grid_layout' );
	$position = get_theme_mod( 'noah_portfolio_items_title_position' );

	// We hide the primary meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the portfolio secondary meta control.
 *
 * @return bool
 */
function noah_portfolio_items_secondary_meta_control_show() {
	$layout = get_theme_mod( 'noah_portfolio_grid_layout' );
	$position = get_theme_mod( 'noah_portfolio_items_title_position' );

	// We hide the primary meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

/* ===============================
 * BLOG GRID CONTROLS CONDITIONALS
 * =============================== */

/**
 * Decides when to show the blog nearby alignment control.
 *
 * @return bool
 */
function noah_blog_items_title_alignment_nearby_control_show() {
	$position = get_theme_mod( 'noah_blog_items_title_position' );
	// We hide it when displaying as overlay
	if ( 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog overlay alignment control.
 *
 * @return bool
 */
function noah_blog_items_title_alignment_overlay_control_show() {
	$position = get_theme_mod( 'noah_blog_items_title_position' );
	// We hide it when not displaying as overlay
	if ( 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog show excerpt control.
 *
 * @return bool
 */
function noah_blog_items_excerpt_visibility_control_show() {
	$layout = get_theme_mod( 'noah_blog_grid_layout' );
	$position = get_theme_mod( 'noah_blog_items_title_position' );

	// We hide it if the layout is packed or the position is overlay
	if ( 'packed' == $layout || 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog primary meta control.
 *
 * @return bool
 */
function noah_blog_items_primary_meta_control_show() {
	$layout = get_theme_mod( 'noah_blog_grid_layout' );
	$position = get_theme_mod( 'noah_blog_items_title_position' );

	// We hide the primary meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog secondary meta control.
 *
 * @return bool
 */
function noah_blog_items_secondary_meta_control_show() {
	$layout = get_theme_mod( 'noah_blog_grid_layout' );
	$position = get_theme_mod( 'noah_blog_items_title_position' );

	// We hide the primary meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

/* =========================
 * SANITIZATION FOR SETTINGS
 * ========================= */

/**
 * Sanitize the header position options.
 */
function noah_sanitize_header_position( $input ) {
	$valid = array(
		'static' => esc_html__( 'Static', 'noah' ),
		'sticky' => esc_html__( 'Sticky (fixed)', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the header width options.
 */
function noah_sanitize_header_width( $input ) {
	$valid = array(
		'full'      => esc_html__( 'Full Browser Width', 'components' ),
		'container' => esc_html__( 'Container Width', 'components' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid layout options.
 */
function noah_sanitize_grid_layout( $input ) {
	$valid = array(
		'regular' => esc_html__( 'Regular Grid', 'noah' ),
		'masonry' => esc_html__( 'Masonry', 'noah' ),
		'packed'  => esc_html__( 'Packed', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid items title position options.
 */
function noah_sanitize_items_title_position( $input ) {
	$valid = array(
		'above'   => esc_html__( 'Above', 'noah' ),
		'below'   => esc_html__( 'Below', 'noah' ),
		'overlay' => esc_html__( 'Overlay', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid items title alignment (nearby) options.
 */
function noah_sanitize_items_title_alignment_nearby( $input ) {
	$valid = array(
		'left'   => esc_html__( '← Left', 'noah' ),
		'center' => esc_html__( '↔ Center', 'noah' ),
		'right'  => esc_html__( '→ Right', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid items title alignment (overlay) options.
 */
function noah_sanitize_items_title_alignment_overlay( $input ) {
	$valid = array(
		'top-left'   => esc_html__( '↑ Top     ← Left', 'noah' ),
		'top-center' => esc_html__( '↑ Top     ↔ Center', 'noah' ),
		'top-right'  => esc_html__( '↑ Top     → Right', 'noah' ),

		'middle-left'   => esc_html__( '↕ Middle     ← Left', 'noah' ),
		'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'noah' ),
		'middle-right'  => esc_html__( '↕ Middle     → Right', 'noah' ),

		'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'noah' ),
		'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'noah' ),
		'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid items primary meta options.
 */
function noah_sanitize_items_primary_meta( $input ) {
	$valid = array(
		'none'     => esc_html__( 'None', 'noah' ),
		'category' => esc_html__( 'Category', 'noah' ),
		'author'   => esc_html__( 'Author', 'noah' ),
		'date'     => esc_html__( 'Date', 'noah' ),
		'tags'     => esc_html__( 'Tags', 'noah' ),
		'comments' => esc_html__( 'Comments', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the grid items secondary meta options.
 */
function noah_sanitize_items_secondary_meta( $input ) {
	$valid = array(
		'none'     => esc_html__( 'None', 'noah' ),
		'category' => esc_html__( 'Category', 'noah' ),
		'author'   => esc_html__( 'Author', 'noah' ),
		'date'     => esc_html__( 'Date', 'noah' ),
		'tags'     => esc_html__( 'Tags', 'noah' ),
		'comments' => esc_html__( 'Comments', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the footer layout options.
 */
function noah_sanitize_footer_layout( $input ) {
	$valid = array(
		'stacked' => esc_html__( 'Stacked', 'noah' ),
		'row'     => esc_html__( 'Row', 'noah' ),
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
function noah_sanitize_checkbox( $input ) {
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
 * @see noah_customize_register()
 *
 * @return void
 */
function noah_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @see noah_customize_register()
 *
 * @return void
 */
function noah_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function noah_customize_preview_js() {
	/**
	 * Get theme details inside `$theme` object and later use it for cache busting
	 * with `$theme->get( 'Version' )` method
	 */
	$theme = wp_get_theme();

	wp_enqueue_script( 'noah-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array( 'customize-preview' ), $theme->get( 'Version' ), true );
}
add_action( 'customize_preview_init', 'noah_customize_preview_js' );

/**
 * Assets that will be loaded for the customizer sidebar
 */
function silklite_customizer_assets() {
	wp_enqueue_style( 'noahlite_customizer_style', get_template_directory_uri() . '/assets/css/admin/customizer.css', null, '1.0.4', false );

	wp_enqueue_script( 'noahlite_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'jquery' ), '1.0.4', false );

	// uncomment this to put back your dismiss notice
	// update_user_meta( get_current_user_id(), 'noah_upgrade_dismissed_notice', 0 );
	if ( isset( $_GET['noah-upgrade-dismiss'] ) && check_admin_referer( 'noah-upgrade-dismiss-' . get_current_user_id() ) ) {
		update_user_meta( get_current_user_id(), 'noah_upgrade_dismissed_notice', 'forever' );
		return;
	}

	$dismiss_user = get_user_meta( get_current_user_id(), 'noah_upgrade_dismissed_notice', true );
	if ( $dismiss_user === 'forever' ) {
		return;
	} elseif ( empty( $dismiss_user ) || ( is_numeric( $dismiss_user ) && $dismiss_user < 2  ) ) {

		$value = $dismiss_user + 1;
		update_user_meta( get_current_user_id(), 'noah_upgrade_dismissed_notice', $value );
		return;
	}

	$localized_strings = array(
		'upsell_link'     => noahlite_get_pro_link(),
		'upsell_label'    => esc_html__( 'Upgrade to Noah Pro', 'noah' ),
		'pro_badge_label' => esc_html__( 'Pro', 'noah' ) . '<span class="star"></span>',
		'dismiss_link' => esc_url( wp_nonce_url( add_query_arg( 'noah-upgrade-dismiss', 'forever' ), 'noah-upgrade-dismiss-' . get_current_user_id() ) )
	);

	wp_localize_script( 'noahlite_customizer', 'noahCustomizerObject', $localized_strings );
}

/**
 * Here we organize the fields into one "Theme Options" section
 * @param $wp_customize
 */
function noah_customizer_reorder_fields( $wp_customize ) {
	$wp_customize->get_control( 'blogname' )->section = 'noahlite_theme_options';
	$wp_customize->get_control( 'blogdescription' )->section = 'noahlite_theme_options';

	$wp_customize->get_control( 'site_icon' )->section = 'noahlite_theme_options';
	$wp_customize->get_control( 'site_icon' )->section = 'noahlite_theme_options';


	// $wp_customize->get_control( 'site_logo' )->section = 'noahlite_theme_options';
	// $wp_customize->get_control( 'site_logo' )->priority = '1';

	$site_logo_header_text = $wp_customize->get_control( 'site_logo_header_text' );
	// this field may miss, so we need a check
	if ( ! empty( $site_logo_header_text ) ) {
		$site_logo_header_text->section = 'noahlite_theme_options';
	}
}
add_action( 'customize_register', 'noah_customizer_reorder_fields', 9999 );

/**
 * Generate a link to the noah Lite info page.
 */
function noahlite_get_pro_link() {
	return 'https://pixelgrade.com/themes/noah?utm_source=noah-lite-clients&utm_medium=customizer&utm_campaign=noah-lite#go-pro';
}