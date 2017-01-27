<?php

$demo_menus = array(
	'primary-left'  => esc_html__( 'Left', 'noah' ),
	'primary-right' => esc_html__( 'Right', 'noah' )
);


$demo_reading_pages = array(
	'front' => 'Welcome',
	'blog'  => 'Journal'
);

/**
 * Use this plugin to export WordPress options https://wordpress.org/plugins/options-importer/
 * Steps:
 * 1 - Export the demo options
 * 2 - From the exported file get only the options array and encode it http://vps5.cgwizz.com/encoder/
 * 3 - After that paste the encoded string here:
 */
$theme_options = null;

//The export of the widgets using this plugin http://wordpress.org/plugins/widget-settings-importexport/ - base64 encoded: http://www.base64encode.org/
$demo_widgets = 'W3sic2lkZWJhci0xIjpbInNlYXJjaC0yIiwicmVjZW50LXBvc3RzLTIiLCJyZWNlbnQtY29tbWVudHMtMiIsImFyY2hpdmVzLTIiLCJjYXRlZ29yaWVzLTIiLCJtZXRhLTIiXX0seyJzZWFyY2giOnsiMiI6eyJ0aXRsZSI6IiJ9LCJfbXVsdGl3aWRnZXQiOjF9LCJyZWNlbnQtcG9zdHMiOnsiMiI6eyJ0aXRsZSI6IiIsIm51bWJlciI6NX0sIl9tdWx0aXdpZGdldCI6MX0sInJlY2VudC1jb21tZW50cyI6eyIyIjp7InRpdGxlIjoiIiwibnVtYmVyIjo1fSwiX211bHRpd2lkZ2V0IjoxfSwiYXJjaGl2ZXMiOnsiMiI6eyJ0aXRsZSI6IiIsImNvdW50IjowLCJkcm9wZG93biI6MH0sIl9tdWx0aXdpZGdldCI6MX0sImNhdGVnb3JpZXMiOnsiMiI6eyJ0aXRsZSI6IiIsImNvdW50IjowLCJoaWVyYXJjaGljYWwiOjAsImRyb3Bkb3duIjowfSwiX211bHRpd2lkZ2V0IjoxfSwibWV0YSI6eyIyIjp7InRpdGxlIjoiIn0sIl9tdWx0aXdpZGdldCI6MX19XQ==';