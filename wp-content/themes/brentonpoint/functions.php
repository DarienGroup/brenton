<?php
defined( 'ABSPATH' ) || exit;

foreach ( [
	'setup',
	'enqueue',
	'nav-menus',
	'widgets',
	'custom-post-types',
	'helpers',
] as $file ) {
	require_once get_template_directory() . '/inc/' . $file . '.php';
}
