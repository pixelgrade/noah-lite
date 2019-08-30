<?php
/**
 * Noah Customizer Options Config
 *
 * @package Noah Lite
 * @since Noah 1.2.0
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

add_filter( 'customify_filter_fields', 'noah_lite_add_customify_options', 11, 1 );
add_filter( 'customify_filter_fields', 'noah_lite_add_customify_style_manager_section', 12, 1 );

add_filter( 'customify_filter_fields', 'noah_lite_fill_customify_options', 20 );

define( 'NOAHLITE_SM_COLOR_PRIMARY', '#BF493D' );
define( 'NOAHLITE_SM_DARK_PRIMARY', '#252525' );
define( 'NOAHLITE_SM_DARK_SECONDARY', '#757575' );
define( 'NOAHLITE_SM_LIGHT_PRIMARY', '#FFFFFF' );



function noah_lite_add_customify_options( $options ) {
	$options['opt-name'] = 'noah_options';

	//start with a clean slate - no Customify default sections
	$options['sections'] = array();

	return $options;
}

/**
 * Add the Style Manager cross-theme Customizer section.
 *
 * @param array $options
 *
 * @return array
 */

function noah_lite_add_customify_style_manager_section( $options ) {
	// If the theme hasn't declared support for style manager, bail.
	if ( ! current_theme_supports( 'customizer_style_manager' ) ) {
		return $options;
	}

	if ( ! isset( $options['sections']['style_manager_section'] ) ) {
		$options['sections']['style_manager_section'] = array();
	}

	$new_config = array(
		'options' => array(
			'sm_color_primary'   => array(
				'default'          => NOAHLITE_SM_COLOR_PRIMARY,
				'connected_fields' => array(
					'main_content_body_link_color',
					'main_content_heading_3_color',
					'portfolio_item_meta_primary_color',
					'blog_item_meta_primary_color',
					'header_links_active_color',
				),
			),
			'sm_color_secondary' => array(
				'default'          => NOAHLITE_SM_COLOR_PRIMARY,
				'connected_fields' => array(),
			),
			'sm_color_tertiary'  => array(
				'default'          => NOAHLITE_SM_COLOR_PRIMARY,
				'connected_fields' => array(),
			),
			'sm_dark_primary'    => array(
				'default'          => NOAHLITE_SM_DARK_PRIMARY,
				'connected_fields' => array(
					'main_content_page_title_color',
					'main_content_body_text_color',
					'main_content_heading_1_color',
					'main_content_heading_2_color',
					'main_content_heading_4_color',
					'main_content_heading_5_color',
					'main_content_heading_6_color',
					'portfolio_item_title_color',
					'blog_item_title_color',
					'header_navigation_links_color',
					'footer_links_color',
				),
			),
			'sm_dark_secondary'  => array(
				'default'          => NOAHLITE_SM_DARK_SECONDARY,
				'connected_fields' => array(
					'main_content_micro_elements',
					'portfolio_item_meta_secondary_color',
					'blog_item_meta_secondary_color',
					'footer_body_text_color',
				),
			),
			'sm_dark_tertiary'   => array(
				'default'          => NOAHLITE_SM_DARK_SECONDARY,
				'connected_fields' => array(),
			),
			'sm_light_primary'   => array(
				'default'          => NOAHLITE_SM_LIGHT_PRIMARY,
				'connected_fields' => array(
					'main_content_border_color',
					'main_content_content_background_color',
					'portfolio_item_thumbnail_background',
					'blog_item_thumbnail_background',
					'header_background',
					'footer_background',
				),
			),
			'sm_light_secondary' => array(
				'default'          => NOAHLITE_SM_LIGHT_PRIMARY,
				'connected_fields' => array(),
			),
			'sm_light_tertiary'  => array(
				'default'          => NOAHLITE_SM_LIGHT_PRIMARY,
				'connected_fields' => array(),
			),
		),
	);

	// The section might be already defined, thus we merge, not replace the entire section config.
	if ( class_exists( 'Customify_Array' ) && method_exists( 'Customify_Array', 'array_merge_recursive_distinct' ) ) {
		$options['sections']['style_manager_section'] = Customify_Array::array_merge_recursive_distinct( $options['sections']['style_manager_section'], $new_config );
	} else {
		$options['sections']['style_manager_section'] = array_merge_recursive( $options['sections']['style_manager_section'], $new_config );
	}

	return $options;
}

function noah_lite_fill_customify_options( $options ) {
	$new_config = array(
		'main_content_section'  => array(
			'title' => '',
			'type'  => 'hidden',
			'options'   => array(
				'main_content_border_color'                 => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Site Border Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'border-color',
							'selector' => '.c-border',
						),
					),
				),
				'main_content_page_title_color'             => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Page Title Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-page-header__title',
						),
					),
				),
				'main_content_body_text_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Body Text Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'body',
						),
						array(
							'property' => 'background-color',
							'selector' => '
								.c-btn, 
								input[type="submit"], 
								input[type="button"], 
								button[type="submit"], 
								.body .wpforms-container[class] .wpforms-submit, 
								.body .wpforms-container[class] .wpforms-submit:hover, 
								.posts-navigation .nav-previous a, 
								.posts-navigation .nav-next a,
								#content .sd-content ul li',
						),
						array(
							'property' => 'border-color',
							'selector' => '
								.c-btn, 
								input[type="submit"], 
								input[type="button"], 
								button[type="submit"], 
								.body .wpforms-container[class] .wpforms-submit, 
								.body .wpforms-container[class] .wpforms-submit:hover, 
								.posts-navigation .nav-previous a, 
								.posts-navigation .nav-next a,
								#content .sd-content ul li',
						),
					),
				),
				'main_content_body_link_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Body Link Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_COLOR_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'a, a:hover, .c-slider__header .c-meta__share-link:hover',
						),
					),
				),
				'main_content_heading_1_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Heading 1', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h1, .h1',
						),
					),
				),
				'main_content_heading_2_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Heading 2', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h2, .h2',
						),
					),
				),
				'main_content_heading_3_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Heading 3', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_COLOR_PRIMARY,
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
				'main_content_heading_4_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Heading 4', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h4, .h4',
						),
					),
				),
				'main_content_heading_5_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Heading 5', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h5, .h5',
						),
					),
				),
				'main_content_heading_6_color'              => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Heading 6', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h6, .h6',
						),
					),
				),
				'main_content_content_background_color'     => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Content Background Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.u-content-background, .c-mask',
						),
						array(
							'property' => 'color',
							'selector' => '
								.c-btn, 
								input[type="submit"], 
								input[type="button"], 
								button[type="submit"], 
								.body .wpforms-container[class] .wpforms-submit, 
								.body .wpforms-container[class] .wpforms-submit:hover, 
								.posts-navigation .nav-previous a, 
								.posts-navigation .nav-next a,
								#content .sd-content ul li',
						),
					),
				),
			)
		),
		'portfolio_grid_section'    => array(
			'title' => '',
			'type'  => 'hidden',
			'options'   => array(
				'portfolio_item_title_color'                   => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Item Title Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__title',
						),
					),
				),
				'portfolio_item_meta_primary_color'            => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Meta Primary', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_COLOR_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__meta',
						),
					),
				),
				'portfolio_item_meta_secondary_color'          => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Meta Secondary', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__footer',
						),
					),
				),
				'portfolio_item_thumbnail_background'          => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Thumbnail Background', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.c-gallery--portfolio .u-card-thumbnail-background',
						),
					),
				),
			)
		),
		'blog_grid_section' => array(
			'title'    => '',
			'type'  => 'hidden',
			'options'   => array(
				'blog_item_title_color'                   => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Item Title Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__title',
						),
					),
				),
				'blog_item_meta_primary_color'            => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Meta Primary', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_COLOR_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__meta',
						),
					),
				),
				'blog_item_meta_secondary_color'          => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Meta Secondary', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__footer',
						),
					),
				),
				'blog_item_thumbnail_background'          => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Thumbnail Background', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.c-gallery--blog .u-card-thumbnail-background',
						),
					),
				),
			)
		),
		'header_section'    => array(
			'title' => '',
			'type'  => 'hidden',
			'options'   => array(
				'header_navigation_links_color' => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Navigation Links Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-navbar',
						),
					),
				),
				'header_links_active_color'     => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Links Active Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_COLOR_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.c-navbar [class*="current-menu"],
								.c-navbar li:hover',
						),
						array(
							'property' => 'border-top-color',
							'selector' => '.c-navbar [class*="children"]:hover:after',
						),
					),
				),
				'header_background'             => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Header Background', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.site-header',
						),
					),
				),
			)
		),
		'footer_section'    => array(
			'title' => '',
			'type'  => 'hidden',
			'options'   => array(
				'footer_body_text_color'       => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Body Text Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_SECONDARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-footer',
						),
					),
				),
				'footer_links_color'           => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Links Color', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_DARK_PRIMARY,
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-footer a',
						),
					),
				),
				'footer_background'            => array(
					'type'    => 'hidden_control',
					'label'   => esc_html__( 'Footer Background', 'noah-lite' ),
					'live'    => true,
					'default' => NOAHLITE_SM_LIGHT_PRIMARY,
					'css'     => array(
						array(
							'property' => 'background',
							'selector' => '.u-footer__background',
						),
					),
				),
			)
		)
	);

	if ( class_exists( 'Customify_Array' ) && method_exists( 'Customify_Array', 'array_merge_recursive_distinct' ) ) {
		$options['sections'] = Customify_Array::array_merge_recursive_distinct( $options['sections'], $new_config );
	} else {
		$options['sections'] = array_merge_recursive( $options['sections'], $new_config );
	}

	return $options;
}

function noah_lite_add_default_color_palette( $color_palettes ) {

	$color_palettes = array_merge( array(
		'default' => array(
			'label'   => esc_html__( 'Theme Default', 'noah-lite' ),
			'preview' => array(
				'background_image_url' => 'https://cloud.pixelgrade.com/wp-content/uploads/2018/07/noah-palette.jpg',
			),
			'options' => array(
				'sm_color_primary'   => NOAHLITE_SM_COLOR_PRIMARY,
				'sm_color_secondary' => NOAHLITE_SM_COLOR_PRIMARY,
				'sm_color_tertiary'  => NOAHLITE_SM_COLOR_PRIMARY,
				'sm_dark_primary'    => NOAHLITE_SM_DARK_PRIMARY,
				'sm_dark_secondary'  => NOAHLITE_SM_DARK_SECONDARY,
				'sm_dark_tertiary'   => NOAHLITE_SM_DARK_SECONDARY,
				'sm_light_primary'   => NOAHLITE_SM_LIGHT_PRIMARY,
				'sm_light_secondary' => NOAHLITE_SM_LIGHT_PRIMARY,
				'sm_light_tertiary'  => NOAHLITE_SM_LIGHT_PRIMARY,
			),
		),
	), $color_palettes );

	return $color_palettes;
}

add_filter( 'customify_get_color_palettes', 'noah_lite_add_default_color_palette' );
