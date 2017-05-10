<?php
/**
 * Noah Lite Theme About Page logic.
 *
 * @package Noah Lite
 */

function noahlite_admin_setup() {
	/**
	 * Load the About page class
	 */
	require_once 'ti-about-page/class-ti-about-page.php';

	/*
	* About page instance
	*/
	$config = array(
		// Menu name under Appearance.
		'menu_name'               => __( 'About Noah Lite', 'noah-lite' ),
		// Page title.
		'page_name'               => __( 'About Noah Lite', 'noah-lite' ),
		// Main welcome title
		'welcome_title'         => sprintf( __( 'Welcome to %s! - Version ', 'noah-lite' ), 'Noah Lite' ),
		// Main welcome content
		'welcome_content'       => esc_html__( 'Noah Lite is a free one page WordPress theme. It\'s perfect for web agency business,corporate business,personal and parallax business portfolio, photography sites and freelancer.Is built on BootStrap with parallax support, is responsive, clean, modern, flat and minimal. Noah Lite is ecommerce (WooCommerce) Compatible, WPML, RTL, Retina-Ready, SEO Friendly and with parallax, full screen image is one of the best business themes.', 'noah-lite' ),
		/**
		 * Tabs array.
		 *
		 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
		 * the will be the name of the function which will be used to render the tab content.
		 */
		'tabs'                    => array(
			'getting_started'  => __( 'Getting Started', 'noah-lite' ),
			'recommended_actions' => __( 'Recommended Actions', 'noah-lite' ),
			'recommended_plugins' => __( 'Useful Plugins','noah-lite' ),
			'support'       => __( 'Support', 'noah-lite' ),
			'changelog'        => __( 'Changelog', 'noah-lite' ),
			'free_pro'         => __( 'Free VS PRO', 'noah-lite' ),
		),
		// Support content tab.
		'support_content'      => array(
			'first' => array (
				'title' => esc_html__( 'Contact Support','noah-lite' ),
				'icon' => 'dashicons dashicons-sos',
				'text' => esc_html__( 'We want to make sure you have the best experience using Noah Lite and that is why we gathered here all the necessary informations for you. We hope you will enjoy using Noah Lite, as much as we enjoy creating great products.','noah-lite' ),
				'button_label' => esc_html__( 'Contact Support','noah-lite' ),
				'button_link' => esc_url( 'https://themeisle.com/contact/' ),
				'is_button' => true,
				'is_new_tab' => true
			),
			'second' => array(
				'title' => esc_html__( 'Documentation','noah-lite' ),
				'icon' => 'dashicons dashicons-book-alt',
				'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Noah Lite.','noah-lite' ),
				'button_label' => esc_html__( 'Read full documentation','noah-lite' ),
				'button_link' => 'https://docs.themeisle.com/article/5-zerif-lite-documentation',
				'is_button' => false,
				'is_new_tab' => true
			),
			'third' => array(
				'title' => esc_html__( 'Changelog','noah-lite' ),
				'icon' => 'dashicons dashicons-portfolio',
				'text' => esc_html__( 'Want to get the gist on the latest theme changes? Just consult our changelog below to get a taste of the recent fixes and features implemented.','noah-lite' ),
				'button_label' => esc_html__( 'Changelog','noah-lite' ),
				'button_link' => esc_url( admin_url( 'themes.php?page=noahlite-welcome&tab=changelog&show=yes' ) ),
				'is_button' => false,
				'is_new_tab' => false
			),
			'fourth' => array(
				'title' => esc_html__( 'Create a child theme','noah-lite' ),
				'icon' => 'dashicons dashicons-admin-customizer',
				'text' => esc_html__( "If you want to make changes to the theme's files, those changes are likely to be overwritten when you next update the theme. In order to prevent that from happening, you need to create a child theme. For this, please follow the documentation below.",'noah-lite' ),
				'button_label' => esc_html__( 'View how to do this','noah-lite' ),
				'button_link' => 'https://docs.themeisle.com/article/14-how-to-create-a-child-theme',
				'is_button' => false,
				'is_new_tab' => true
			),
			'fifth' => array(
				'title' => esc_html__( 'Speed up your site','noah-lite' ),
				'icon' => 'dashicons dashicons-controls-skipforward',
				'text' => esc_html__( 'If you find yourself in the situation where everything on your site is running very slow, you might consider having a look at the below documentation where you will find the most common issues causing this and possible solutions for each of the issues.','noah-lite' ),
				'button_label' => esc_html__( 'View how to do this','noah-lite' ),
				'button_link' => 'https://docs.themeisle.com/article/63-speed-up-your-wordpress-site',
				'is_button' => false,
				'is_new_tab' => true
			),
			'sixth' => array(
				'title' => esc_html__( 'Build a landing page with a drag-and-drop content builder','noah-lite' ),
				'icon' => 'dashicons dashicons-images-alt2',
				'text' => esc_html__( 'In the below documentation you will find an easy way to build a great looking landing page using a drag-and-drop content builder plugin.','noah-lite' ),
				'button_label' => esc_html__( 'View how to do this','noah-lite' ),
				'button_link' => 'https://docs.themeisle.com/article/219-how-to-build-a-landing-page-with-a-drag-and-drop-content-builder',
				'is_button' => false,
				'is_new_tab' => true
			)
		),
		// Getting started tab
		'getting_started' => array(
			'first' => array (
				'title' => esc_html__( 'Recommended actions','noah-lite' ),
				'text' => esc_html__( 'We have compiled a list of steps for you, to take make sure the experience you will have using one of our products is very easy to follow.','noah-lite' ),
				'button_label' => esc_html__( 'Recommended actions','noah-lite' ),
				'button_link' => esc_url( admin_url( 'themes.php?page=noah-lite-welcome&tab=recommended_actions' ) ),
				'is_button' => false,
				'recommended_actions' => true,
				'is_new_tab' => false
			),
			'second' => array(
				'title' => esc_html__( 'Read full documentation','noah-lite' ),
				'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Noah Lite.','noah-lite' ),
				'button_label' => esc_html__( 'Documentation','noah-lite' ),
				'button_link' => 'http://docs.themeisle.com/article/5-zerif-lite-documentation',
				'is_button' => false,
				'recommended_actions' => false,
				'is_new_tab' => true
			),
			'third' => array(
				'title' => esc_html__( 'Go to Customizer','noah-lite' ),
				'text' => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.','noah-lite' ),
				'button_label' => esc_html__( 'Go to Customizer','noah-lite' ),
				'button_link' => esc_url( admin_url( 'customize.php' ) ),
				'is_button' => true,
				'recommended_actions' => false,
				'is_new_tab' => true
			)
		),
		// Free vs pro array.
		'free_pro'                => array(
			'free_theme_name'     => 'Noah Lite',
			'pro_theme_name'      => 'Noah PRO',
			'pro_theme_link'      => 'https://pixelgrade.com/themes/noah/',
			'get_pro_theme_label' => sprintf( __( 'Get %s now!', 'noah-lite' ), 'Noah Pro' ),
			'features'            => array(
				array(
					'title'       => __( 'Parallax effect', 'noah-lite' ),
					'description' => __( 'Smooth, catchy and easy scrolling experience.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Mobile friendly', 'noah-lite' ),
					'description' => __( 'Responsive layout. Works on every device.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'WooCommerce Compatible', 'noah-lite' ),
					'description' => __( 'Ready for e-commerce. You can build an online store here.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Frontpage sections', 'noah-lite' ),
					'description' => __( 'Big title, Our focus, About us, Our team, Testimonials, Ribbons, Latest news, Contat us', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Background image', 'noah-lite' ),
					'description' => __( 'You can use any background image you want.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Unlimited color option', 'noah-lite' ),
					'description' => __( 'You can change the colors of each section. You have unlimited options.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Google map section', 'noah-lite' ),
					'description' => __( 'Embed your current location to your website by using a Google map.','noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Portfolio', 'noah-lite' ),
					'description' => __( 'Showcase your best projects in the portfolio section.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Sections order', 'noah-lite' ),
					'description' => __( 'Arrange the sections by your priorities.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Background slider/video', 'noah-lite' ),
					'description' => __( 'Apart from static images, you can use videos or sliders on the background.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Support', 'noah-lite' ),
					'description' => __( 'You will benefit of our full support for any issues you have with the theme.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Packages/Subscribe sections', 'noah-lite' ),
					'description' => __( 'Add pricing tables for your products and use newsletter forms to attract the clients.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Change labels for the Contact Us section', 'noah-lite' ),
					'description' => __( 'Write an original text in each Contact us section field.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'No credit footer link', 'noah-lite' ),
					'description' => __( 'Remove "Noah Lite developed by ThemeIsle" copyright from the footer.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				)
			),
		),
		// Plugins array.
		'recommended_plugins'        => array(
			'already_activated_message' => esc_html__( 'Already activated', 'noah-lite' ),
			'version_label' => esc_html__( 'Version: ', 'noah-lite' ),
			'install_label' => esc_html__( 'Install and Activate', 'noah-lite' ),
			'activate_label' => esc_html__( 'Activate', 'noah-lite' ),
			'deactivate_label' => esc_html__( 'Deactivate', 'noah-lite' ),
			'content'                   => array(
				array(
					'slug' => 'siteorigin-panels'
				),
				array(
					'slug' => 'wp-product-review'
				),
				array(
					'slug' => 'intergeo-maps'
				),
				array(
					'slug' => 'visualizer'
				),
				array(
					'slug' => 'adblock-notify-by-bweb'
				),
				array(
					'slug' => 'nivo-slider-lite'
				)
			),
		),
		// Required actions array.
		'recommended_actions'        => array(
			'install_label' => esc_html__( 'Install and Activate', 'noah-lite' ),
			'activate_label' => esc_html__( 'Activate', 'noah-lite' ),
			'deactivate_label' => esc_html__( 'Deactivate', 'noah-lite' ),
			'content'            => array(
				'jetpack' => array(
					'title'       => 'Jetpack',
					'description' => __( 'It is highly recommended that you install Jetpack so you can use the Portfolio post type. Plus, Jetpack provides a whole host of other useful things for you site.', 'noah-lite' ),
					'check'       => defined( 'JETPACK__VERSION' ),
					'plugin_slug' => 'jetpack',
					'id' => 'jetpack'
				),
			),
		),
	);
	TI_About_Page::init( $config );
}
add_action('after_setup_theme', 'noahlite_admin_setup');
