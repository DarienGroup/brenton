<?php
defined( 'ABSPATH' ) || exit;

foreach ( [
	'setup',
	'enqueue',
	'nav-menus',
	'widgets',
	'custom-post-types',
	'redirects',
	'helpers',
	'components',
	'shortcodes',
	'gravity-forms',
	'acf',
	'attachment-focal-point',
] as $file ) {
	require_once get_template_directory() . '/inc/' . $file . '.php';
}
