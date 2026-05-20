<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {
	$theme_uri = get_template_directory_uri();
	$dist      = $theme_uri . '/dist';
	$ver       = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'brentonpoint-main',
		$dist . '/css/main.css',
		[],
		$ver
	);

	wp_enqueue_script(
		'brentonpoint-main',
		$dist . '/js/main.js',
		[],
		$ver,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
} );
