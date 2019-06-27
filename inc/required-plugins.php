<?php
/**
 * Guides required or recommended plugins
 *
 * @package Noah Lite
 * @since 1.1.0
 */

require_once get_template_directory() . '/inc/required-plugins/class-tgm-plugin-activation.php';

function noah_lite_register_required_plugins() {

	$protocol = 'http:';
	if ( is_ssl() ) {
		$protocol = 'https:';
	}

	$plugins = array(
		array(
			'name'               => 'Jetpack',
			'slug'               => 'jetpack',
			'required'           => false,
		),
	);

	$config = array(
		'domain'           => '__theme_txtd', // Text domain - likely want to be the same as your theme.
		'default_path'     => '', // Default absolute path to pre-packaged plugins
		'menu'             => 'install-required-plugins', // Menu slug
		'has_notices'      => true, // Show admin notices or not
		'is_automatic'     => false, // Automatically activate plugins after installation or not
		'message'          => '', // Message to output right before the plugins table
		'strings'          => array(
			'page_title'                      => esc_html__( 'Install Recommended Plugins', '__theme_txtd' ),
			'menu_title'                      => esc_html__( 'Install Plugins', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'installing'                      => esc_html__( 'Installing Plugin: %s', '__theme_txtd' ),
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', '__theme_txtd' ),
			/* translators: %1$s: plugin name */
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', '__theme_txtd' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', '__theme_txtd' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', '__theme_txtd' ),
			'return'                          => esc_html__( 'Return to Recommened Plugins Installer', '__theme_txtd' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', '__theme_txtd' ),
			/* translators: %1$s: dashboard link */
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', '__theme_txtd' )
		)
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'noah_lite_register_required_plugins', 995 );
