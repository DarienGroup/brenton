<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	register_nav_menus( [
		'primary' => __( 'Primary Navigation', 'brentonpoint' ),
		'footer'  => __( 'Footer Navigation', 'brentonpoint' ),
	] );
} );

/**
 * Add a default `nav-link` class to every menu item <a>.
 */
add_filter( 'nav_menu_link_attributes', function ( $atts ) {
	$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'text-navigation nav-link';
	return $atts;
} );
