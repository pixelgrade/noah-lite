<?php
/**
 * Noah Customizer Options Config
 *
 * @package Noah
 * @since Noah 1.0
 */

/**
 * Hook into the Customify's fields and settings.
 *
 * The config can turn to be complex so is better to visit:
 * https://github.com/pixelgrade/customify
 *
 * @param $options array - Contains the plugin's options array right before they are used, so edit with care
 *
 * @return mixed The return of options is required, if you don't need options return an empty array
 *
 */

/**
 * =====================
 * Some of the Customify controls come straight from components.
 * If you need to customize the settings for those controls you can use the appropriate filter.
 * For details search for the add_customify_options() function in the main component class (usually in class-componentname.php).
 *
 */

// For example, to change the Customify options for the Header component, you can go about it like so:
//=== To change the recommended fonts you can use the following

//        /**
//         * Modify the Customify recommended fonts for the Header font controls.
//         *
//         * @param array $fonts
//         *
//         * @return array
//         */
//        function osteria_change_customify_header_recommended_fonts( $fonts = array() ){
//            // just add some font to the existing list
//            $fonts[] = 'Some Font Family';
//
//            // delete a certain font family
//            if( ( $key = array_search( 'NiceFont', $fonts ) ) !== false) {
//                unset( $fonts[ $key ] );
//            }
//
//            // or just replace the whole array
//            $fonts = array(
//                'Playfair Display',
//                'Oswald',
//                'Lato',
//            );
//
//            // Now return our modified fonts list
//            return $fonts;
//        }
//        add_filter( 'pixelgrade_header_customify_recommended_headings_fonts', 'osteria_change_customify_header_recommended_fonts');


//=== To change the recommended fonts you can use the following

//        /**
//         * Modify the Customify Header section options.
//         *
//         * @param array $options
//         *
//         * @return array
//         */
//        function osteria_change_customify_header_section_options( $options = array() ){
//            // just add some option at the end
//            $options['header_section']['options']['header_transparent_header'] = array(
//                'type'    => 'checkbox',
//                'label'   => esc_html__( 'Transparent Header while on Hero', 'noah' ),
//                'default' => 1,
//            );
//
//            // or you could add some option after another
//            $header_transparent_option = array(
//                'header_transparent' => array(
//                    'type'    => 'checkbox',
//                    'label'   => esc_html__( 'Transparent Header while on Hero', 'noah' ),
//                    'default' => 1,
//                )
//            );
//            $options['header_section']['options'] = pixelgrade_array_insert_after( $options['header_section']['options'], 'header_sides_spacing', $header_transparent_option );
//
//            // delete some option
//            if( array_key_exists( 'header_background', $options['header_section']['options'] ) ) {
//                unset( $options['header_section']['options']['header_background'] );
//            }
//
//            // change some settings for a specific option
//            // First we test to see if we have this option
//            if( array_key_exists( 'header_background', $options['header_section']['options'] ) ) {
//                $options['header_section']['options']['header_background']['default'] = '#555555';
//            }
//
//            // or just replace the whole array
//            $options['header_section'] = array(
//                'title'   => __( 'Header', 'noah' ),
//                'options' => array(
//                        //put your options here
//                )
//            );
//
//            // Now return our modified options
//            return $options;
//        }
//        add_filter( 'pixelgrade_header_customify_section_options', 'osteria_change_customify_header_section_options');

/**
 * =====================
 */

add_filter( 'customify_filter_fields', 'noah_add_customify_options', 11, 1 );
add_filter( 'customify_filter_fields', 'noah_add_customify_general_options', 12, 1 );
// Next come the Header options from the Pixelgrade/Header component - priority 20
add_filter( 'customify_filter_fields', 'noah_add_customify_main_content_options', 30, 1 );
// Next come the Footer options from the Pixelgrade/Footer component - priority 40
add_filter( 'customify_filter_fields', 'noah_add_customify_portfolio_grid_options', 50, 1 );
add_filter( 'customify_filter_fields', 'noah_add_customify_blog_grid_options', 60, 1 );
add_filter( 'customify_filter_fields', 'noah_add_customify_import_demo_options', 70, 1 );

function noah_add_customify_options( $options ) {
	$options['opt-name'] = 'noah_options';

	//start with a clean slate - no Customify default sections
	$options['sections'] = array();

	return $options;
}

function noah_add_customify_general_options( $options ) {
	$general_section = array(
		// General
		'general' => array(
			'title'   => esc_html__( 'General', 'noah' ),
			'options' => array(
				'use_ajax_loading' => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Enable dynamic page content loading using AJAX.', 'noah' ),
					'default' => 1,
				),
			),
		),
    );

	//Allow others to make changes
	$general_section = apply_filters( 'pixelgrade_customify_general_section_options', $general_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the general section
	$options['sections'] = $options['sections'] + $general_section;

	return $options;
}

function noah_add_customify_main_content_options( $options ) {
	// Body
	$recommended_body_fonts = apply_filters( 'customify_theme_recommended_body_fonts',
		array(
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere'
		)
	);

	$main_content_section = array(
		// Main Content
		'main_content' => array(
			'title'   => esc_html__( 'Main Content', 'noah' ),
			'options' => array(
				'main_content_options_customizer_tabs'              => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
							<a href="#section-title-main-layout">' . esc_html__( 'Layout', 'noah' ) . '</a>
							<a href="#section-title-main-colors">' . esc_html__( 'Colors', 'noah' ) . '</a>
							<a href="#section-title-main-fonts">' . esc_html__( 'Fonts', 'noah' ) . '</a>
							</nav>',
				),
				// [Section] Layout
				'main_content_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-main-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'noah' ) . '</span>',
				),
				'main_content_container_width'          => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Container Max Width', 'noah' ),
					'desc'        => esc_html__( 'Adjust the max width of your site content area.', 'noah' ),
					'live'        => true,
					'default'     => 1280,
					'input_attrs' => array(
						'min'          => 600,
						'max'          => 2600,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-container-width',
							'unit'     => 'px',
						),
					),
				),
				'main_content_container_sides_spacing'  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Container Sides Spacing', 'noah' ),
					'desc'        => esc_html__( 'Adjust the space separating the site content and the sides of the browser.', 'noah' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-left',
							'selector'        => '.u-container-sides-spacings',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'padding-right',
							'selector'        => '.u-container-sides-spacings',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
					),
				),
				'main_content_container_padding'        => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Container Padding', 'noah' ),
					'desc'        => esc_html__( 'Adjust the top and bottom distance between the page content and header/footer.', 'noah' ),
					'live'        => true,
					'default'     => 0,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-top',
							'selector'        => '.u-content_container_padding_top',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'margin-top',
							'selector'        => '.u-content_container_margin_top',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
					),
				),
				'main_content_content_width'            => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Content Width', 'noah' ),
					'desc'        => esc_html__( 'Decrease the width of your content to create an inset area for your text. The inset size will be the space between Site Container and Content.', 'noah' ),
					'live'        => true,
					'default'     => 600,
					'input_attrs' => array(
						'min'          => 400,
						'max'          => 2600,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-content-width > *',
							'unit'     => 'px',
						),
					),
				),
				'main_content_border_width'             => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Border Width', 'noah' ),
					'desc'        => '',
					'live'        => true,
					'default'     => 0,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'margin',
							'selector' => 'body',
							'unit'     => 'px',
							'callback_filter' => 'typeline_spacing_cb'
						),
						array(
							'property' => 'border-width',
							'selector' => '.c-border',
							'unit'     => 'px',
							'callback_filter' => 'typeline_spacing_cb'
						),
						array(
							'property'        => 'border-top-width',
							'selector'        => '.c-navbar',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'border-left-width',
							'selector'        => '.c-navbar',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'border-right-width',
							'selector'        => '.c-navbar',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'bottom',
							'selector'        => '.c-slider__bullets, .slick-dots',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'margin-top',
							'selector'        => '.c-overlay__close',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'margin-right',
							'selector'        => '.c-overlay__close',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'margin-left',
							'selector'        => '.c-navbar__label',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),

					),
				),
				'main_content_border_color'             => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Site Border Color', 'noah' ),
					'live'    => true,
					'default' => '#FFF',
					'css'     => array(
						array(
							'property' => 'border-color',
							'selector' => '.c-border',
						),
					),
				),

				// [Section] COLORS
				'main_content_title_colors_section'           => array(
					'type' => 'html',
					'html' => '<span id="section-title-main-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'noah' ) . '</span>',
				),
				'main_content_page_title_color'         => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Page Title Color', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-page-header__title',
						),
					),
				),
				'main_content_body_text_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Text Color', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'body',
						),
					),
				),
				'main_content_body_link_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Link Color', 'noah' ),
					'live'    => true,
					'default' => '#bf493d',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'a',
						),
					),
				),
				'main_content_underlined_body_links'    => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Underlined Body Links', 'noah' ),
					'default' => 0,
				),
				// [Sub Section] Headings Color
				'main_content_title_headings_color_section'              => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Headings Color', 'noah' ) . '</span>',
				),
				'main_content_heading_1_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 1', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h1, .h1',
						),
					),
				),
				'main_content_heading_2_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 2', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h2, .h2',
						),
					),
				),
				'main_content_heading_3_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 3', 'noah' ),
					'live'    => true,
					'default' => '#bf493d',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h3, .h3,
								.u-color-accent.u-color-accent,
								.entry-content cite,
								.h4.c-project__more-title,
								body.search .c-page-header__title span',
						),
					),
				),
				'main_content_heading_4_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 4', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h4, .h4',
						),
					),
				),
				'main_content_heading_5_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 5', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h5, .h5',
						),
					),
				),
				'main_content_heading_6_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 6', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h6, .h6',
						),
					),
				),

				// [Sub Section] Backgrounds
				'main_content_title_backgrounds_section'            => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Backgrounds', 'noah' ) . '</span>',
				),
				'main_content_content_background_color' => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Content Background Color', 'noah' ),
					'live'    => true,
					'default' => '#FFFFFF',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.u-content-background',
						),
					),
				),

				// [Section] FONTS
				'main_content_title_fonts_section'             => array(
					'type' => 'html',
					'html' => '<span id="section-title-main-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'noah' ) . '</span>',
				),

				'main_content_page_title_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Page Title Font', 'noah' ),
					'desc'     => '',
					'selector' => '.c-page-header__title',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 23,
						'line-height'    => 1.174,
						'letter-spacing' => 0.174,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,


					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_body_text_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Body Text Font', 'noah' ),
					'desc'     => '',
					'selector' => 'body, .entry-content p, .comment-content p',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Georgia, serif',
						'font-weight'    => '400',
						'font-size'      => 14,
						'line-height'    => 1.7,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_quote_block_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Quote Block Font', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content blockquote',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => "'Times New Roman', Times, serif",
						'font-weight'    => '400',
						'font-size'      => 23,
						'line-height'    => 1.3,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				// [Sub Section] Headings Fonts
				'main_content_title_headings_fonts_section'     => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Headings Fonts', 'noah' ) . '</span>',
				),

				'main_content_heading_1_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 1', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content h1, h1, .h1',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 23,
						'line-height'    => 1.174,
						'letter-spacing' => 0.174,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,
					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_2_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 2', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content h2, h2, .h2',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 18,
						'line-height'    => 1.167,
						'letter-spacing' => 0.133,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_3_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 3', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content h3, h3, .h3',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 15,
						'line-height'    => 1.2,
						'letter-spacing' => 0.133,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_4_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 4', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content h4, h4, .h4',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 13,
						'line-height'    => 1.154,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_5_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 5', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content h5, h5, .h5',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 12,
						'line-height'    => 1.167,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_6_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 6', 'noah' ),
					'desc'     => '',
					'selector' => '.entry-content h6, h6, .h6',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 11,
						'line-height'    => 1.1818,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			)
		),
	);

	//Allow others to make changes
	$main_content_section = apply_filters( 'pixelgrade_customify_main_content_section_options', $main_content_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the main content section
	$options['sections'] = $options['sections'] + $main_content_section;

	return $options;
}

function noah_add_customify_portfolio_grid_options( $options ) {
	// Body
	$recommended_body_fonts = apply_filters( 'customify_theme_recommended_body_fonts',
		array(
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere'
		)
	);

	$portfolio_grid_section = array(
		// Portfolio Grid
		'portfolio_grid' => array(
			'title'   => esc_html__( 'Portfolio Grid Items', 'noah' ),
			'options' => array(
				'portfolio_grid_options_customizer_tabs'               => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
							<a href="#section-title-portfolio-layout">' . esc_html__( 'Layout', 'noah' ) . '</a>
							<a href="#section-title-portfolio-colors">' . esc_html__( 'Colors', 'noah' ) . '</a>
							<a href="#section-title-portfolio-fonts">' . esc_html__( 'Fonts', 'noah' ) . '</a>
							</nav>',
				),

				// [Section] Layout
				'portfolio_grid_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-portfolio-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'noah' ) . '</span>',
				),
				'portfolio_grid_width'                     => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Portfolio Grid Max Width', 'noah' ),
					'desc'        => esc_html__( 'Adjust the max width of the portfolio area.', 'noah' ),
					'live'        => true,
					'default'     => 1280,
					'input_attrs' => array(
						'min'          => 600,
						'max'          => 2600,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-portfolio_grid_width',
							'unit'     => 'px',
						),
					),
				),
				'portfolio_container_sides_spacing'        => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Container Sides Spacing', 'noah' ),
					'desc'        => esc_html__( 'Adjust the space separating the site content and the sides of the browser.', 'noah' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-left',
							'selector'        => '.u-portfolio_sides_spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
						array(
							'property'        => 'padding-right',
							'selector'        => '.u-portfolio_sides_spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

                // [Sub Section] Items Grid
				'portfolio_grid_title_items_grid_section'                  => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label large">' . esc_html__( 'Items Grid', 'noah' ) . '</span>',
				),
				'portfolio_grid_layout'                    => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Grid Layout', 'noah' ),
					'desc'    => esc_html__( 'Choose whether the items display in a fixed height regular grid, or in a mosaic style layout.', 'noah' ),
					'default' => 'packed',
					'choices' => array(
						'regular' => esc_html__( 'Regular Grid', 'noah' ),
						'masonry' => esc_html__( 'Masonry', 'noah' ),
						'packed'  => esc_html__( 'Packed', 'noah' ),
					)
				),
				'portfolio_items_aspect_ratio'             => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Aspect Ratio', 'noah' ),
					'desc'        => esc_html__( 'Leave the images to their original ratio or crop them to get a more defined grid layout.', 'noah' ),
					'live'        => true,
					'default'     => 100,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 200,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'dummy',
							'selector'        => '.dummy',
							'callback_filter' => 'noah_aspect_ratio_cb',
							'unit'            => 'px',
						),
					),
				),
				'portfolio_items_per_row'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items per Row', 'noah' ),
					'desc'        => esc_html__( 'Set the desktop-based number of columns you want and we automatically make it right for other screen sizes.', 'noah' ),
					'live'        => false,
					'default'     => 4,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
					'css'         => array(
						array(
							'property' => 'dummy',
							'selector' => '.dummy',
							'unit'     => 'px',
						),
					),
				),
				'portfolio_items_spacing'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Spacing', 'noah' ),
					'desc'        => esc_html__( 'Adjust the spacing between individual items in your grid.', 'noah' ),
					'live'        => true,
					'default'     => 90,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => '',
							'selector'        => '.c-gallery--portfolio',
							'callback_filter' => 'noah_portfolio_grid_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

                // [Sub Section] Items Title
				'portfolio_grid_title_items_title_section'                 => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Title', 'noah' ) . '</span>',
				),
				'portfolio_items_title_position'           => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Items Title Position', 'noah' ),
					'desc'    => esc_html__( 'Choose whether the items titles are placed nearby the thumbnail or show as an overlay cover on  mouse over.', 'noah' ),
					'default' => 'below',
					'choices' => array(
						'above'   => esc_html__( 'Above', 'noah' ),
						'below'   => esc_html__( 'Below', 'noah' ),
						'overlay' => esc_html__( 'Overlay', 'noah' ),
					)
				),
				'portfolio_items_title_alignment_nearby'   => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Above/Below)', 'noah' ),
					'desc'    => esc_html__( 'Adjust the alignment of your title.', 'noah' ),
					'default' => 'left',
					'choices' => array(
						'left'   => esc_html__( '← Left', 'noah' ),
						'center' => esc_html__( '↔ Center', 'noah' ),
						'right'  => esc_html__( '→ Right', 'noah' ),
					),
				),
				'portfolio_items_title_alignment_overlay'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Overlay)', 'noah' ),
					'desc'    => esc_html__( 'Adjust the alignment of your hover title.', 'noah' ),
					'default' => 'middle-center',
					'choices' => array(
						'top-left'   => esc_html__( '↑ Top     ← Left', 'noah' ),
						'top-center' => esc_html__( '↑ Top     ↔ Center', 'noah' ),
						'top-right'  => esc_html__( '↑ Top     → Right', 'noah' ),

						'middle-left'   => esc_html__( '↕ Middle     ← Left', 'noah' ),
						'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'noah' ),
						'middle-right'  => esc_html__( '↕ Middle     → Right', 'noah' ),

						'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'noah' ),
						'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'noah' ),
						'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'noah' ),
					),
				),

				// Title Visiblity
				// Title + Checkbox
				'portfolio_items_title_visibility_title'   => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Title Visibility', 'noah' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'noah' ) . '</span>',
				),
				'portfolio_items_title_visibility'         => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Title', 'noah' ),
					'default' => 1,
				),

				// [Sub Section] Items Excerpt
				'portfolio_grid_title_items_excerpt_section'                 => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Excerpt', 'noah' ) . '</span>',
				),

				// Excerpt Visiblity
				// Title + Checkbox
				'portfolio_items_excerpt_visibility_title' => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Excerpt Visibility', 'noah' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'noah' ) . '</span>',
				),
				'portfolio_items_excerpt_visibility'       => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Excerpt Text', 'noah' ),
					'default' => 1,
				),

				// [Sub Section] Items Meta
				'portfolio_grid_title_items_meta_section'               => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Meta', 'noah' ) . '</span>',
				),

				'portfolio_items_primary_meta' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Primary Meta Section', 'noah' ),
					'desc'    => esc_html__( 'Set the meta info that display around the title. ', 'noah' ),
					'default' => 'none',
					'choices' => array(
						'none'     => esc_html__( 'None', 'noah' ),
						'category' => esc_html__( 'Category', 'noah' ),
						'author'   => esc_html__( 'Author', 'noah' ),
						'date'     => esc_html__( 'Date', 'noah' ),
						'tags'     => esc_html__( 'Tags', 'noah' ),
						'comments' => esc_html__( 'Comments', 'noah' ),
					),
				),

				'portfolio_items_secondary_meta'         => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Secondary Meta Section', 'noah' ),
					'desc'    => '',
					'default' => 'none',
					'choices' => array(
						'none'     => esc_html__( 'None', 'noah' ),
						'category' => esc_html__( 'Category', 'noah' ),
						'author'   => esc_html__( 'Author', 'noah' ),
						'date'     => esc_html__( 'Date', 'noah' ),
						'tags'     => esc_html__( 'Tags', 'noah' ),
						'comments' => esc_html__( 'Comments', 'noah' ),
					),
				),

				// [Section] COLORS
				'portfolio_grid_title_colors_section'             => array(
					'type' => 'html',
					'html' => '<span id="section-title-portfolio-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'noah' ) . '</span>',
				),
				'portfolio_item_title_color'             => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Item Title Color', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__title',
						),
					),
				),
				'portfolio_item_meta_primary_color'      => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Primary', 'noah' ),
					'live'    => true,
					'default' => '#BF493D',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__meta',
						),
					),
				),
				'portfolio_item_meta_secondary_color'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Secondary', 'noah' ),
					'live'    => true,
					'default' => '#757575',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__footer',
						),
					),
				),
				'portfolio_item_thumbnail_background'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Thumbnail Background', 'noah' ),
					'live'    => true,
					'default' => '#FFF',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.c-gallery--portfolio .c-card__frame',
						),
					),
				),

				// [Sub Section] Thumbnail Hover
				'portfolio_grid_title_thumbnail_hover_section'             => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Thumbnail Hover', 'noah' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Customize the mouse over effect for your thumbnails.', 'noah' ) . '</span>',
				),
				'portfolio_item_thumbnail_hover_opacity' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Thumbnail Background Opacity', 'noah' ),
					'desc'        => '',
					'live'        => true,
					'default'     => 0.7,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 1,
						'step'         => 0.1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'opacity',
							'selector' => '.c-gallery--portfolio .c-card__link:hover .c-card__frame.c-card__frame',
							'unit'     => '',
						),
					),
				),

				// [Section] FONTS
				'portfolio_grid_title_fonts_section'               => array(
					'type' => 'html',
					'html' => '<span id="section-title-portfolio-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'noah' ) . '</span>',
				),

				'portfolio_item_title_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Title Font', 'noah' ),
					'desc'     => '',
					'selector' => '.c-gallery--portfolio .c-card__title.c-card__title',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 12,
						'line-height'    => 1.16,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'portfolio_item_meta_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Meta Font', 'noah' ),
					'desc'     => '',
					'selector' => '.c-gallery--portfolio .c-card__meta, .c-gallery--portfolio .c-card__footer',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 10,
						'line-height'    => 1.2,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			),
		),
	);

	//Allow others to make changes
	$portfolio_grid_section = apply_filters( 'pixelgrade_customify_portfolio_grid_section_options', $portfolio_grid_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the portfolio grid section
	$options['sections'] = $options['sections'] + $portfolio_grid_section;

	return $options;
}

function noah_add_customify_blog_grid_options( $options ) {
	// Body
	$recommended_body_fonts = apply_filters( 'customify_theme_recommended_body_fonts',
		array(
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere'
		)
	);

	$blog_grid_section = array(
		// Blog Grid
		'blog_grid' => array(
			'title'   => esc_html__( 'Blog Grid Items', 'noah' ),
			'options' => array(
				'blog_grid_options_customizer_tabs'          => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
								<a href="#section-title-blog-layout">' . esc_html__( 'Layout', 'noah' ) . '</a>
								<a href="#section-title-blog-colors">' . esc_html__( 'Colors', 'noah' ) . '</a>
								<a href="#section-title-blog-fonts">' . esc_html__( 'Fonts', 'noah' ) . '</a>
								</nav>',
				),

				// [Section] Layout
				'blog_grid_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-blog-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'noah' ) . '</span>',
				),
				'blog_grid_width'                     => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Blog Grid Max Width', 'noah' ),
					'desc'        => esc_html__( 'Adjust the max width of the blog area.', 'noah' ),
					'live'        => true,
					'default'     => 1280,
					'input_attrs' => array(
						'min'          => 600,
						'max'          => 2600,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-blog_grid_width',
							'unit'     => 'px',
						),
					),
				),
				'blog_container_sides_spacing'        => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Container Sides Spacing', 'noah' ),
					'desc'        => esc_html__( 'Adjust the space separating the site content and the sides of the browser.', 'noah' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-left',
							'selector'        => '.u-blog_sides_spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
						array(
							'property'        => 'padding-right',
							'selector'        => '.u-blog_sides_spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

				// [Sub Section] Items Grid
				'blog_grid_title_items_grid_section'             => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label large">' . esc_html__( 'Items Grid', 'noah' ) . '</span>',
				),
				'blog_grid_layout'                    => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Grid Layout', 'noah' ),
					'desc'    => esc_html__( 'Choose whether the items display in a fixed height regular grid, or in a mosaic style layout.', 'noah' ),
					'default' => 'masonry',
					'choices' => array(
						'regular' => esc_html__( 'Regular Grid', 'noah' ),
						'masonry' => esc_html__( 'Masonry', 'noah' ),
						'packed'  => esc_html__( 'Packed', 'noah' ),
					)
				),
				'blog_items_aspect_ratio'             => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Aspect Ratio', 'noah' ),
					'desc'        => esc_html__( 'Leave the images to their original ratio or crop them to get a more defined grid layout.', 'noah' ),
					'live'        => true,
					'default'     => 100,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 200,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'dummy',
							'selector'        => '.dummy',
							'callback_filter' => 'noah_aspect_ratio_cb',
							'unit'            => 'px',
						),
					),
				),
				'blog_items_per_row'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items per Row', 'noah' ),
					'desc'        => esc_html__( 'Set the desktop-based number of columns you want and we automatically make it right for other screen sizes.', 'noah' ),
					'live'        => false,
					'default'     => 3,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
					'css'         => array(
						array(
							'property' => 'dummy',
							'selector' => '.dummy',
							'unit'     => 'px',
						),
					),
				),
				'blog_items_spacing'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Spacing', 'noah' ),
					'desc'        => esc_html__( 'Adjust the spacing between individual items in your grid.', 'noah' ),
					'live'        => true,
					'default'     => 90,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => '',
							'selector'        => '.dummy',
							'callback_filter' => 'noah_blog_grid_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

                // [Sub Section] Items Title
				'blog_grid_title_items_title_section'            => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Title', 'noah' ) . '</span>',
				),
				'blog_items_title_position'           => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Items Title Position', 'noah' ),
					'desc'    => esc_html__( 'Choose whether the items titles are placed nearby the thumbnail or show as an overlay cover on  mouse over.', 'noah' ),
					'default' => 'below',
					'choices' => array(
						'above'   => esc_html__( 'Above', 'noah' ),
						'below'   => esc_html__( 'Below', 'noah' ),
						'overlay' => esc_html__( 'Overlay', 'noah' ),
					)
				),
				'blog_items_title_alignment_nearby'   => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Above/Below)', 'noah' ),
					'desc'    => esc_html__( 'Adjust the alignment of your title.', 'noah' ),
					'default' => 'left',
					'choices' => array(
						'left'   => esc_html__( '← Left', 'noah' ),
						'center' => esc_html__( '↔ Center', 'noah' ),
						'right'  => esc_html__( '→ Right', 'noah' ),
					),
				),
				'blog_items_title_alignment_overlay'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Overlay)', 'noah' ),
					'desc'    => esc_html__( 'Adjust the alignment of your hover title.', 'noah' ),
					'default' => 'middle-center',
					'choices' => array(
						'top-left'   => esc_html__( '↑ Top     ← Left', 'noah' ),
						'top-center' => esc_html__( '↑ Top     ↔ Center', 'noah' ),
						'top-right'  => esc_html__( '↑ Top     → Right', 'noah' ),

						'middle-left'   => esc_html__( '↕ Middle     ← Left', 'noah' ),
						'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'noah' ),
						'middle-right'  => esc_html__( '↕ Middle     → Right', 'noah' ),

						'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'noah' ),
						'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'noah' ),
						'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'noah' ),
					),
				),

				// Title Visiblity
				// Title + Checkbox
				'blog_items_title_visibility_title'   => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Title Visibility', 'noah' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'noah' ) . '</span>',
				),
				'blog_items_title_visibility'         => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Title', 'noah' ),
					'default' => 1,
				),

				// [Sub Section] Items Excerpt
				'blog_grid_title_items_excerpt_section'            => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Excerpt', 'noah' ) . '</span>',
				),

				// Excerpt Visiblity
				// Title + Checkbox
				'blog_items_excerpt_visibility_title' => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Excerpt Visibility', 'noah' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'noah' ) . '</span>',
				),
				'blog_items_excerpt_visibility'       => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Excerpt Text', 'noah' ),
					'default' => 1,
				),

				// [Sub Section] Items Meta
				'blog_grid_title_items_meta_section'          => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Meta', 'noah' ) . '</span>',
				),

				'blog_items_primary_meta' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Primary Meta Section', 'noah' ),
					'desc'    => esc_html__( 'Set the meta info that display around the title. ', 'noah' ),
					'default' => 'category',
					'choices' => array(
						'none'     => esc_html__( 'None', 'noah' ),
						'category' => esc_html__( 'Category', 'noah' ),
						'author'   => esc_html__( 'Author', 'noah' ),
						'date'     => esc_html__( 'Date', 'noah' ),
						'tags'     => esc_html__( 'Tags', 'noah' ),
						'comments' => esc_html__( 'Comments', 'noah' ),
					),
				),

				'blog_items_secondary_meta'         => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Secondary Meta Section', 'noah' ),
					'desc'    => '',
					'default' => 'date',
					'choices' => array(
						'none'     => esc_html__( 'None', 'noah' ),
						'category' => esc_html__( 'Category', 'noah' ),
						'author'   => esc_html__( 'Author', 'noah' ),
						'date'     => esc_html__( 'Date', 'noah' ),
						'tags'     => esc_html__( 'Tags', 'noah' ),
						'comments' => esc_html__( 'Comments', 'noah' ),
					),
				),

				// [Section] COLORS
				'blog_grid_title_colors_section'        => array(
					'type' => 'html',
					'html' => '<span id="section-title-blog-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'noah' ) . '</span>',
				),
				'blog_item_title_color'             => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Item Title Color', 'noah' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__title',
						),
					),
				),
				'blog_item_meta_primary_color'      => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Primary', 'noah' ),
					'live'    => true,
					'default' => '#BF493D',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__meta',
						),
					),
				),
				'blog_item_meta_secondary_color'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Secondary', 'noah' ),
					'live'    => true,
					'default' => '#757575',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__footer',
						),
					),
				),
				'blog_item_thumbnail_background'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Thumbnail Background', 'noah' ),
					'live'    => true,
					'default' => '#FFF',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.c-card__frame',
						),
					),
				),

				// [Sub Section] Thumbnail Hover
				'blog_grid_title_thumbnail_hover_section'        => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Thumbnail Hover', 'noah' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Customize the mouse over effect for your thumbnails.', 'noah' ) . '</span>',
				),
				'blog_item_thumbnail_hover_opacity' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Thumbnail Background Opacity', 'noah' ),
					'desc'        => '',
					'live'        => true,
					'default'     => 0.7,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 1,
						'step'         => 0.1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'opacity',
							'selector' => '.c-gallery--blog .c-card__link:hover .c-card__frame.c-card__frame img',
							'unit'     => '',
						),
					),
				),

				// [Section] FONTS
				'blog_grid_title_fonts_section'          => array(
					'type' => 'html',
					'html' => '<span id="section-title-blog-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'noah' ) . '</span>',
				),

				'blog_item_title_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Title Font', 'noah' ),
					'desc'     => '',
					'selector' => '.c-gallery--blog .c-card__title',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 18,
						'line-height'    => 1.4,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'blog_item_meta_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Meta Font', 'noah' ),
					'desc'     => '',
					'selector' => '.c-gallery--blog .c-card__meta, .c-gallery--blog .c-card__footer',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Arca Majora',
						'font-weight'    => '400',
						'font-size'      => 10,
						'line-height'    => 1.2,
						'letter-spacing' => 0.154,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			),
		),
	);

	//Allow others to make changes
	$blog_grid_section = apply_filters( 'pixelgrade_customify_blog_grid_section_options', $blog_grid_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the blog grid section
	$options['sections'] = $options['sections'] + $blog_grid_section;

	return $options;
}

function noah_add_customify_import_demo_options( $options ) {
	$import_demo_section = array(
		// Import Demo Data
		'import_demo_data' => array(
			'title'       => __( 'Demo Data', 'noah' ),
			'description' => esc_html__( 'If you would like to have a "ready to go" website as the Noah\'s demo page here is the button', 'noah' ),
			'priority'    => 999999,
			'options'     => array(
				'import_demodata_button' => array(
					'title' => 'Import',
					'type'  => 'html',
					'html'  => '<input type="hidden" name="wpGrade-nonce-import-posts-pages" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_posts_pages' ) . '" />
									<input type="hidden" name="wpGrade-nonce-import-theme-options" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_theme_options' ) . '" />
									<input type="hidden" name="wpGrade-nonce-import-widgets" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_widgets' ) . '" />
									<input type="hidden" name="wpGrade_import_ajax_url" value="' . admin_url( 'admin-ajax.php' ) . '" />' .
					           '<span class="description customize-control-description">(' . esc_html__( 'Note: We cannot serve you the original images due the ', 'noah' ) . '<strong>&copy;</strong>)</span></br>' .
					           '<a href="#" class="button button-primary" id="wpGrade_import_demodata_button" style="width: 70%; text-align: center; padding: 10px; display: inline-block; height: auto;  margin: 0 15% 10% 15%;">
										' . __( 'Import demo data', 'noah' ) . '
									</a>

									<div class="wpGrade-loading-wrap hidden">
										<span class="wpGrade-loading wpGrade-import-loading"></span>
										<div class="wpGrade-import-wait">' .
					           esc_html__( 'Please wait a few minutes (between 1 and 3 minutes usually, but depending on your hosting it can take longer) and ', 'noah' ) .
					           '<strong>' . esc_html__( 'don\'t reload the page', 'noah' ) . '</strong>.' .
					           esc_html__( 'You will be notified as soon as the import has finished!', 'noah' ) . '
										</div>
									</div>

									<div class="wpGrade-import-results hidden"></div>
									<div class="hr"><div class="inner"><span>&nbsp;</span></div></div>'
				)
			)
		)
	);

	//Allow others to make changes
	$import_demo_section = apply_filters( 'pixelgrade_customify_import_demo_section_options', $import_demo_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the general section
	$options['sections'] = $options['sections'] + $import_demo_section;

	return $options;
}

/**
 * Returns the custom CSS rules for the portfolio grid spacing depending on the Customizer settings.
 *
 * @param mixed $value The value of the option.
 * @param string $selector The CSS selector for this option.
 * @param string $property The CSS property of the option.
 * @param string $unit The CSS unit used by this option.
 *
 * @return string
 */
function noah_portfolio_grid_spacing_cb( $value, $selector, $property, $unit ) {
	$columns        = pixelgrade_option( 'portfolio_items_per_row', 4 );
	$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
	$columns_at_pad = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;

	$ratio = 1.54303;

	$normal   = 'calc(' . ( 100 * $ratio / $columns . '%' ) . ' - ' . $value * $ratio . 'px);';
	$featured = 'calc(' . ( ( 200 * $ratio / $columns . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

	$normal_at_lap   = 'calc(' . ( 100 * $ratio / $columns_at_lap . '%' ) . ' - ' . $value * $ratio . 'px);';
	$featured_at_lap = 'calc(' . ( ( 200 * $ratio / $columns_at_lap . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

	$normal_at_pad   = 'calc(' . ( 100 * $ratio / $columns_at_pad . '%' ) . ' - ' . $value * $ratio . 'px);';
	$featured_at_pad = 'calc(' . ( ( 200 * $ratio / $columns_at_pad . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

	$output = '';

	$output .=
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal . PHP_EOL .
		'}' . PHP_EOL .
		'@media only screen and (min-width: 48em) {' . PHP_EOL .
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal_at_pad . PHP_EOL .
		'}' . PHP_EOL .
		'}' . PHP_EOL .
		'@media only screen and (min-width: 64em) {' . PHP_EOL .
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal_at_lap . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
		'padding-top: ' . $featured_at_lap . PHP_EOL .
		'}' . PHP_EOL .
		'}' . PHP_EOL .
		'@media only screen and (min-width: 80em) {' . PHP_EOL .
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
		'padding-top: ' . $featured . PHP_EOL .
		'}' . PHP_EOL .
		'}'  . PHP_EOL;

	$output .=
        '.c-gallery--portfolio {' . PHP_EOL .
        'margin-top: -' . $value . 'px;' . PHP_EOL .
        'margin-left: -' . $value . 'px;' . PHP_EOL .
        'padding-top: ' . $value . 'px;' . PHP_EOL .
        '}' . PHP_EOL .
        '.c-gallery--portfolio .c-gallery__item {' . PHP_EOL .
        'padding-left: ' . $value . 'px;' . PHP_EOL .
        'margin-top: ' . $value . 'px;' . PHP_EOL .
        '}' . PHP_EOL .
        '.c-gallery--portfolio.c-gallery--packed .c-card__frame { ' . PHP_EOL .
        'left: ' . $value . 'px;' . PHP_EOL .
        '}' . PHP_EOL .
        '.c-gallery--portfolio.c-gallery--packed .c-card__content {' . PHP_EOL .
        'margin-left: ' . $value . 'px;' . PHP_EOL .
        '}' . PHP_EOL;

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();
	// Some sanity check before processing the config
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];


		for ( $i = 0; $i < count( $breakpoints ); $i ++ ) {
			$ratio    = ( typeline_get_y( $value, $points ) - 1 ) * ( $i + 1 ) / count( $breakpoints ) + 1;
			$newValue = round( $value / $ratio );

			$output .=
                '@media only screen and (max-width: ' . $breakpoints[ $i ] . ') {' . PHP_EOL .
                '.c-gallery--portfolio {' . PHP_EOL .
                'margin-top: -' . $newValue . 'px;' . PHP_EOL .
                'margin-left: -' . $newValue . 'px;' . PHP_EOL .
                'padding-top: ' . $newValue . 'px;' . PHP_EOL .
                '}' . PHP_EOL .
                '.c-gallery--portfolio .c-gallery__item {' . PHP_EOL .
                'padding-left: ' . $newValue . 'px;' . PHP_EOL .
                'margin-top: ' . $newValue . 'px;' . PHP_EOL .
                '}' . PHP_EOL .
                '.c-gallery--portfolio.c-gallery--packed .c-card__frame { ' . PHP_EOL .
                'left: ' . $newValue . 'px;' . PHP_EOL .
                '}' . PHP_EOL .
                '.c-gallery--portfolio.c-gallery--packed .c-card__content {' . PHP_EOL .
                'margin-left: ' . $newValue . 'px;' . PHP_EOL .
                '}' . PHP_EOL .
                '}' . PHP_EOL;
		}
	}

	return $output;
}

/**
 * Outputs the inline JS code used in the Customizer for the portfolio grid spacing live preview.
 */
function noah_portfolio_grid_spacing_cb_customizer_preview() {
	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config(); ?>

    <script type="text/javascript">

	    <?php
	    // Some sanity check before processing the config
	    // There is no need for this code since we have nothing to work with
	    if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
            $points      = $typeline_config['spacings']['points'];
            $breakpoints = $typeline_config['spacings']['breakpoints'];
	    ?>

		var points = <?php echo '[[' . $points[0][0] . ', ' . $points[0][1] . '], [' . $points[1][0] . ', ' . $points[1][1] . '], [' . $points[2][0] . ', ' . $points[2][1] . ']]' ?>,
			breakpoints = <?php echo '["' . $breakpoints[0] . '", "' . $breakpoints[1] . '", "' . $breakpoints[2] . '"]'; ?>;

		function getY( x ) {
			if ( x < points[1][0] ) {
				var a = points[0][1],
					b = (points[1][1] - points[0][1]) / Math.pow(points[1][0], 3);
				return a + b * Math.pow(x, 3);
			} else {
				return (points[1][1] + (points[2][1] - points[1][1]) * (x - points[1][0]) / (points[2][0] - points[1][0]));
			}
		}

		<?php } ?>

		function noah_portfolio_grid_spacing_cb( value, selector, property, unit ) {

			var css = '',
				style = document.getElementById('portfolio_grid_spacing_style_tag'),
				head = document.head || document.getElementsByTagName('head')[0];

			var ratio = 1.54303,
				columns = <?php echo pixelgrade_option( 'portfolio_items_per_row', 4 ); ?>,
				columns_at_lap = columns === 1 ? 1 : columns > 4 ? columns - 1 : columns;
			columns_at_pad = columns_at_lap > 1 ? columns_at_lap - 1 : columns_at_lap;
			normal = 'calc(' + ( (100 * ratio / columns + '%') + ' - ' + ( value * ratio ) ) + 'px);',
				featured = 'calc(' + ( (200 * ratio / columns + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);',
				normal_at_lap = 'calc(' + ( (100 * ratio / columns_at_lap + '%') + ' - ' + ( value * ratio ) ) + 'px);',
				featured_at_lap = 'calc(' + ( (200 * ratio / columns_at_lap + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);',
				normal_at_pad = 'calc(' + ( (100 * ratio / columns_at_pad + '%') + ' - ' + ( value * ratio ) ) + 'px);',
				featured_at_pad = 'calc(' + ( (200 * ratio / columns_at_pad + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);';

			css +=
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal +
				'}' +
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
				'padding-top: ' + featured +
				'}' +
				'@media only screen and (max-width: 64em) {' +
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal_at_lap +
				'}' +
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
				'padding-top: ' + featured_at_lap +
				'}' +
				'}' +
				'@media only screen and (max-width: 48em) {' +
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal_at_pad +
				'}' +
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
				'padding-top: ' + featured_at_pad +
				'}' +
				'}';

			css += '.c-gallery--portfolio {' +
				'margin-top: -' + value + 'px;' +
				'margin-left: -' + value + 'px;' +
				'padding-top: ' + value + 'px;' +
				'}' +
				'.c-gallery--portfolio .c-gallery__item {' +
				'padding-left: ' + value + 'px;' +
				'margin-top: ' + value + 'px;' +
				'}' +
				'.c-gallery--portfolio.c-gallery--packed .c-card__frame {' +
				'left: ' + value + 'px;' +
				'}' +
				'.c-gallery--portfolio.c-gallery--packed .c-card__content {' +
				'margin-left: ' + value + 'px;' +
				'}';

			<?php if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) { ?>

			for ( var i = 0; i <= breakpoints.length - 1; i++ ) {
				var newRatio = (getY(value) - 1) * (i + 1) / breakpoints.length + 1,
					newValue = Math.round(value / newRatio);

				css += '@media only screen and (max-width: ' + breakpoints[i] + 'px) {' +
					'.c-gallery--portfolio {' +
					'margin-top: -' + value + 'px;' +
					'margin-left: -' + value + 'px;' +
					'padding-top: ' + value + 'px;' +
					'}' +
					'.c-gallery--portfolio .c-gallery__item {' +
					'padding-left: ' + newValue + 'px;' +
					'margin-top: ' + newValue + 'px;' +
					'}' +
					'.c-gallery--portfolio.c-gallery--packed .c-card__frame {' +
					'left: ' + newValue + 'px;' +
					'}' +
					'.c-gallery--portfolio.c-gallery--packed .c-card__content {' +
					'margin-left: ' + newValue + 'px;' +
					'}' +
					'}';
			}

			<?php } ?>

			if ( style !== null ) {
				style.innerHTML = css;
			} else {
				style = document.createElement('style');
				style.setAttribute('id', 'portfolio_grid_spacing_style_tag');

				style.type = 'text/css';
				if ( style.styleSheet ) {
					style.styleSheet.cssText = css;
				} else {
					style.appendChild(document.createTextNode(css));
				}

				head.appendChild(style);
			}
		}

	</script>

<?php }
add_action( 'customize_preview_init', 'noah_portfolio_grid_spacing_cb_customizer_preview' );

/**
 * Returns the custom CSS rules for the blog grid spacing depending on the Customizer settings.
 *
 * @param mixed $value The value of the option.
 * @param string $selector The CSS selector for this option.
 * @param string $property The CSS property of the option.
 * @param string $unit The CSS unit used by this option.
 *
 * @return string
 */
function noah_blog_grid_spacing_cb( $value, $selector, $property, $unit ) {
	// Get the number of columns
	$columns        = pixelgrade_option( 'portfolio_items_per_row', 4 );
	$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
	$columns_at_pad = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;

	$ratio = 1.54303;

	$normal   = 'calc(' . ( 100 * $ratio / $columns . '%' ) . ' - ' . $value * $ratio . 'px);';
	$featured = 'calc(' . ( ( 200 * $ratio / $columns . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

	$normal_at_lap   = 'calc(' . ( 100 * $ratio / $columns_at_lap . '%' ) . ' - ' . $value * $ratio . 'px);';
	$featured_at_lap = 'calc(' . ( ( 200 * $ratio / $columns_at_lap . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

	$normal_at_pad   = 'calc(' . ( 100 * $ratio / $columns_at_pad . '%' ) . ' - ' . $value * $ratio . 'px);';
	$featured_at_pad = 'calc(' . ( ( 200 * $ratio / $columns_at_pad . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

	$output = '';

	$output .=
		'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal . PHP_EOL .
		'}' .
		'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
		'padding-top: ' . $featured . PHP_EOL .
		'}' . PHP_EOL .
		'@media only screen and (max-width: 64em) {' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal_at_lap . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
		'padding-top: ' . $featured_at_lap . PHP_EOL .
		'}' . PHP_EOL .
		'}' . PHP_EOL .
		'@media only screen and (max-width: 64em) {' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
		'padding-top: ' . $normal_at_pad . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
		'padding-top: ' . $featured_at_pad . PHP_EOL .
		'}' . PHP_EOL .
		'}'  . PHP_EOL;

	$output .=
		'.c-gallery--blog {' . PHP_EOL .
		'margin-top: -' . $value . 'px;' . PHP_EOL .
		'margin-left: -' . $value . 'px;' . PHP_EOL .
		'padding-top: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog .c-gallery__item {' . PHP_EOL .
		'padding-left: ' . $value . 'px;' . PHP_EOL .
		'margin-top: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-card__frame { ' . PHP_EOL .
		'left: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-card__content {' . PHP_EOL .
		'margin-left: ' . $value . 'px;' . PHP_EOL .
		'}'  . PHP_EOL;

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();
	// Some sanity check before processing the config
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		for ( $i = 0; $i < count( $breakpoints ); $i ++ ) {
			$ratio    = ( typeline_get_y( $value, $points ) - 1 ) * ( $i + 1 ) / count( $breakpoints ) + 1;
			$newValue = round( $value / $ratio );

			$output .=
				'@media only screen and (max-width: ' . $breakpoints[ $i ] . ') {' . PHP_EOL .
				'.c-gallery--blog {' . PHP_EOL .
				'margin-top: -' . $newValue . 'px;' . PHP_EOL .
				'margin-left: -' . $newValue . 'px;' . PHP_EOL .
				'padding-top: ' . $newValue . 'px;' . PHP_EOL .
				'}' .
				'.c-gallery--blog .c-gallery__item {' . PHP_EOL .
				'padding-left: ' . $newValue . 'px;' . PHP_EOL .
				'margin-top: ' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--blog.c-gallery--packed .c-card__frame { ' . PHP_EOL .
				'left: ' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--blog.c-gallery--packed .c-card__content {' . PHP_EOL .
				'margin-left: ' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'}' . PHP_EOL;
		}
	}

	return $output;
}

/**
 * Outputs the inline JS code used in the Customizer for the blog grid spacing live preview.
 */
function noah_blog_grid_spacing_cb_customizer_preview() {
	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config(); ?>

    <script type="text/javascript">

		<?php
		// Some sanity check before processing the config
		// There is no need for this code since we have nothing to work with
		if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
            $points      = $typeline_config['spacings']['points'];
            $breakpoints = $typeline_config['spacings']['breakpoints'];
		?>

        var points = <?php echo '[[' . $points[0][0] . ', ' . $points[0][1] . '], [' . $points[1][0] . ', ' . $points[1][1] . '], [' . $points[2][0] . ', ' . $points[2][1] . ']]' ?>,
            breakpoints = <?php echo '["' . $breakpoints[0] . '", "' . $breakpoints[1] . '", "' . $breakpoints[2] . '"]'; ?>;

        function getY( x ) {
            if ( x < points[1][0] ) {
                var a = points[0][1],
                    b = (points[1][1] - points[0][1]) / Math.pow(points[1][0], 3);
                return a + b * Math.pow(x, 3);
            } else {
                return (points[1][1] + (points[2][1] - points[1][1]) * (x - points[1][0]) / (points[2][0] - points[1][0]));
            }
        }

		<?php } ?>

        function noah_blog_grid_spacing_cb( value, selector, property, unit ) {

            var css = '',
                style = document.getElementById('blog_grid_spacing_style_tag'),
                head = document.head || document.getElementsByTagName('head')[0];

            var ratio = 1.54303,
                columns = <?php echo pixelgrade_option( 'portfolio_items_per_row', 4 ); ?>,
                columns_at_lap = columns === 1 ? 1 : columns > 4 ? columns - 1 : columns;
            columns_at_pad = columns_at_lap > 1 ? columns_at_lap - 1 : columns_at_lap;
            normal = 'calc(' + ( (100 * ratio / columns + '%') + ' - ' + ( value * ratio ) ) + 'px);',
                featured = 'calc(' + ( (200 * ratio / columns + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);',
                normal_at_lap = 'calc(' + ( (100 * ratio / columns_at_lap + '%') + ' - ' + ( value * ratio ) ) + 'px);',
                featured_at_lap = 'calc(' + ( (200 * ratio / columns_at_lap + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);',
                normal_at_pad = 'calc(' + ( (100 * ratio / columns_at_pad + '%') + ' - ' + ( value * ratio ) ) + 'px);',
                featured_at_pad = 'calc(' + ( (200 * ratio / columns_at_pad + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);';

            css +=
                '.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
                'padding-top: ' + normal +
                '}' +
                '.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
                'padding-top: ' + featured +
                '}' +
                '@media only screen and (max-width: 64em) {' +
                '.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
                'padding-top: ' + normal_at_lap +
                '}' +
                '.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
                'padding-top: ' + featured_at_lap +
                '}' +
                '}' +
                '@media only screen and (max-width: 48em) {' +
                '.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
                'padding-top: ' + normal_at_pad +
                '}' +
                '.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
                'padding-top: ' + featured_at_pad +
                '}' +
                '}';

            css += '.c-gallery--blog {' +
                'margin-top: -' + value + 'px;' +
                'margin-left: -' + value + 'px;' +
                'padding-top: ' + value + 'px;' +
                '}' +
                '.c-gallery--blog .c-gallery__item {' +
                'padding-left: ' + value + 'px;' +
                'margin-top: ' + value + 'px;' +
                '}' +
                '.c-gallery--blog.c-gallery--packed .c-card__frame {' +
                'left: ' + value + 'px;' +
                '}' +
                '.c-gallery--blog.c-gallery--packed .c-card__content {' +
                'margin-left: ' + value + 'px;' +
                '}';

			<?php if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) { ?>

            for ( var i = 0; i <= breakpoints.length - 1; i++ ) {
                var newRatio = (getY(value) - 1) * (i + 1) / breakpoints.length + 1,
                    newValue = Math.round(value / newRatio);

                css += '@media only screen and (max-width: ' + breakpoints[i] + 'px) {' +
                    '.c-gallery--blog {' +
                    'margin-top: -' + value + 'px;' +
                    'margin-left: -' + value + 'px;' +
                    'padding-top: ' + value + 'px;' +
                    '}' +
                    '.c-gallery--blog .c-gallery__item {' +
                    'padding-left: ' + newValue + 'px;' +
                    'margin-top: ' + newValue + 'px;' +
                    '}' +
                    '.c-gallery--blog.c-gallery--packed .c-card__frame {' +
                    'left: ' + newValue + 'px;' +
                    '}' +
                    '.c-gallery--blog.c-gallery--packed .c-card__content {' +
                    'margin-left: ' + newValue + 'px;' +
                    '}' +
                    '}';
            }

			<?php } ?>

            if ( style !== null ) {
                style.innerHTML = css;
            } else {
                style = document.createElement('style');
                style.setAttribute('id', 'blog_grid_spacing_style_tag');

                style.type = 'text/css';
                if ( style.styleSheet ) {
                    style.styleSheet.cssText = css;
                } else {
                    style.appendChild(document.createTextNode(css));
                }

                head.appendChild(style);
            }
        }

    </script>

<?php }
add_action( 'customize_preview_init', 'noah_blog_grid_spacing_cb_customizer_preview' );

/**
 * Returns the custom CSS rules for the aspect ratio depending on the Customizer settings.
 *
 * @param mixed $value The value of the option.
 * @param string $selector The CSS selector for this option.
 * @param string $property The CSS property of the option.
 * @param string $unit The CSS unit used by this option.
 *
 * @return string
 */
function noah_aspect_ratio_cb( $value, $selector, $property, $unit ) {
	$min = 0;
	$max = 200;

	$value  = intval( $value );
	$center = ( $max - $min ) / 2;
	$offset = $value / $center - 1;

	if ( $offset >= 0 ) {
		$padding = 100 + $offset * 100 . '%';
	} else {
		$padding = 100 + $offset * 50 . '%';
	}

	$output = '';

	$output .= '.c-gallery--regular .c-card__frame {' . PHP_EOL .
	           'padding-top: ' . $padding . ';' . PHP_EOL .
	           '}';

	return $output;
}

/**
 * Outputs the inline JS code used in the Customizer for the aspect ratio live preview.
 */
function noah_aspect_ratio_cb_customizer_preview() { ?>

    <script type="text/javascript">
		function noah_aspect_ratio_cb( value, selector, property, unit ) {

			var css = '',
				style = document.getElementById('noah_aspect_ratio_cb_style_tag'),
				head = document.head || document.getElementsByTagName('head')[0];

			var min = 0,
				max = 200,
				center = (max - min) / 2,
				offset = value / center - 1,
				padding;

			if ( offset >= 0 ) {
				padding = 100 + offset * 100 + '%';
			} else {
				padding = 100 + offset * 50 + '%';
			}

			css += '.c-gallery--regular .c-card__frame {' +
				'padding-top: ' + padding +
				'}';

			if ( style !== null ) {
				style.innerHTML = css;
			} else {
				style = document.createElement('style');
				style.setAttribute('id', 'noah_aspect_ratio_cb_style_tag');

				style.type = 'text/css';
				if ( style.styleSheet ) {
					style.styleSheet.cssText = css;
				} else {
					style.appendChild(document.createTextNode(css));
				}

				head.appendChild(style);
			}
		}

	</script>

<?php }
add_action( 'customize_preview_init', 'noah_aspect_ratio_cb_customizer_preview' );

function noah_add_customify_theme_fonts( $fonts ) {
	$fonts['Arca Majora'] = array(
		'family'   => 'Arca Majora',
		'src'      => get_template_directory_uri() . '/assets/fonts/arcamajora3/stylesheet.css',
		'variants' => array( '400', '700' )
	);

	return $fonts;
}

add_filter( 'customify_theme_fonts', 'noah_add_customify_theme_fonts' );
