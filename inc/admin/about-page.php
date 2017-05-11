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
		'welcome_content'       => esc_html__( ' Noah Lite is a free photography WordPress theme that creates momentum and makes a lovely intro to your journey.', 'noah-lite' ),
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
				'text' => __( 'We want to make sure you have the best experience using Noah Lite. If you <strong>do not have a paid upgrade</strong>, please post your question in our community forums.','noah-lite' ),
				'button_label' => esc_html__( 'Contact Support','noah-lite' ),
				'button_link' => esc_url( 'https://wordpress.org/support/theme/noah-lite' ),
				'is_button' => true,
				'is_new_tab' => true
			),
			'second' => array(
				'title' => esc_html__( 'Documentation','noah-lite' ),
				'icon' => 'dashicons dashicons-book-alt',
				'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Noah Lite.','noah-lite' ),
				'button_label' => esc_html__( 'Read The Documentation','noah-lite' ),
				'button_link' => 'https://pixelgrade.com/noah-lite-documentation/',
				'is_button' => false,
				'is_new_tab' => true
			)
		),
		// Getting started tab
		'getting_started' => array(
			'first' => array(
				'title' => esc_html__( 'Go to Customizer','noah-lite' ),
				'text' => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.','noah-lite' ),
				'button_label' => esc_html__( 'Go to Customizer','noah-lite' ),
				'button_link' => esc_url( admin_url( 'customize.php' ) ),
				'is_button' => true,
				'recommended_actions' => false,
				'is_new_tab' => true
			),
			'second' => array (
				'title' => esc_html__( 'Recommended actions','noah-lite' ),
				'text' => esc_html__( 'We have compiled a list of steps for you, to take make sure the experience you will have using one of our products is very easy to follow.','noah-lite' ),
				'button_label' => esc_html__( 'Recommended actions','noah-lite' ),
				'button_link' => esc_url( admin_url( 'themes.php?page=noah-lite-welcome&tab=recommended_actions' ) ),
				'button_ok_label' => esc_html__( 'You are good to go!','noah-lite' ),
				'is_button' => false,
				'recommended_actions' => true,
				'is_new_tab' => false
			),
			'third' => array(
				'title' => esc_html__( 'Read the documentation','noah-lite' ),
				'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Noah Lite.','noah-lite' ),
				'button_label' => esc_html__( 'Documentation','noah-lite' ),
				'button_link' => 'https://pixelgrade.com/noah-lite-documentation/',
				'is_button' => false,
				'recommended_actions' => false,
				'is_new_tab' => true
			)
		),
		// Free vs pro array.
		'free_pro'                => array(
			'free_theme_name'     => 'Noah Lite',
			'pro_theme_name'      => 'Noah PRO',
			'pro_theme_link'      => 'https://pixelgrade.com/themes/noah-lite/?utm_source=noah-lite-clients&utm_medium=about-page&utm_campaign=noah-lite#pro',
			'get_pro_theme_label' => sprintf( __( 'View %s', 'noah-lite' ), 'Noah Pro' ),
			'features'            => array(
				array(
					'title'       => __( 'Charming Design', 'noah-lite' ),
					'description' => __( 'The visual approach highlights your unique personality and sustains a way of building your true-to-yourself brand.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Mobile-Ready and Responsive for All Devices', 'noah-lite' ),
					'description' => __( 'We knows how much you love your content and how critical it is to showcase it beautifully to all of your users, no matter the device they happen to use.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Steady SEO', 'noah-lite' ),
					'description' => __( 'We\'ve made everything it requires in terms of SEO practices so that you can have a proper start. In the end, everyone has a thing for how they show up in search engines.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Developer Friendly', 'noah-lite' ),
					'description' => __( 'Writing clean, structured, consistent, smart code means that it can be easily extended by you or any developer you hire. With Noah, nothing but the latest WordPress features and standards are used.', 'noah-lite' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Galleries Slideshows', 'noah-lite' ),
					'description' => __( 'Beautifull zoom-in transitions from grid based gallery to slideshow.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Thrilling Page Transitions', 'noah-lite' ),
					'description' => __( 'The whole experience is packed into a fascinating journey that engages in an experiment that goes beyond what’s ordinary and familiar.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Home Page Slideshow', 'noah-lite' ),
					'description' => __( 'Bring your best photos or videos in the front of the world by adding them into a front page slideshow.','noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Flexible Color Scheme', 'noah-lite' ),
					'description' => __( 'Match your unique style in an easy and smart way by using an intuitive interface that you can fine-tune it until it fully represents you.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Modular Layout', 'noah-lite' ),
					'description' => __( 'You have the freedom to play with various design elements in order to adapt the whole digital puzzle in a way you resonate.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Advance Typography Settings', 'noah-lite' ),
					'description' => __( 'Adjust your fonts by taking advantage of a playground with 600+ fonts varieties you can wisely chose from at any moment.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Social Sharing Features', 'noah-lite' ),
					'description' => __( 'Start gathering the right people who find your work purposeful and ready to be shared with the world.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Premium Support', 'noah-lite' ),
					'description' => __( 'You will benefit by priority support from a caring and devoted team, eager to help and to spread happiness. We work hard to provide a flawless experience for those who vote us with trust and choose to be our special clients.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Comprehensive Help Guide', 'noah-lite' ),
					'description' => __( 'Extensive documentation that will help you get your site up quickly and seamlessly.', 'noah-lite' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'No credit footer link', 'noah-lite' ),
					'description' => __( 'Remove "Theme: Noah Lite by Pixelgrade" copyright from the footer area.', 'noah-lite' ),
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
					'slug' => 'jetpack'
				),
				array(
					'slug' => 'wordpress-seo'
				),
				array(
					'slug' => 'gridable'
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
					'description' => __( 'It is highly recommended that you install Jetpack so you can enable the <b>Portfolio</b> content type for adding and managing your projects. Plus, Jetpack provides a whole host of other useful things for you site.', 'noah-lite' ),
					'check'       => defined( 'JETPACK__VERSION' ),
					'plugin_slug' => 'jetpack',
					'id' => 'jetpack',
					'redirect_url' => home_url( 'wp-admin/plugins.php' ),
				),
			),
		),
	);
	TI_About_Page::init( $config );
}
add_action('after_setup_theme', 'noahlite_admin_setup');
