<?php
/**
 * This file is responsible for registering sidebar areas and call widgets files
 */

/**
 * First register the widget areas, aka sidebars, following this pattern
 * https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function noah_widgets_areas() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'noah' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Widgets in this area will be shown on all blog posts.', 'noah' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	) );

	// register widgets
//	register_widget( 'Sidebar_Map_Widget' );
}

add_action( 'widgets_init', 'noah_widgets_areas' );

/**
 * Any widget registration should follow this pattern
 * https://codex.wordpress.org/Function_Reference/register_widget
 *
 * We split every widget in its own file. A child theme should be able to overwrite individual widgets
 */
//get_template_part( 'widgets/widget-sidebar_map_widget' );
