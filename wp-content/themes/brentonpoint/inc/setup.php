<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'brentonpoint', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );

	$GLOBALS['content_width'] = 1200;
} );

/**
 * Hide private/draft posts from front-end search results for every user.
 *
 * WordPress shows posts with `private` status to logged-in users who have
 * the `read_private_posts` capability (admins, editors), which leaks them
 * into the search page. Force the main search query to `publish` only.
 */
add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$query->set( 'post_status', 'publish' );
} );
