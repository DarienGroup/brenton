<?php
defined( 'ABSPATH' ) || exit;

add_action( 'widgets_init', function () {
	register_sidebar( [
		'name'          => __( 'Sidebar', 'brentonpoint' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar', 'brentonpoint' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	] );
} );
