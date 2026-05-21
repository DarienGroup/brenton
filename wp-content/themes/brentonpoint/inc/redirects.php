<?php
defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', function () {
	if ( is_singular( 'our_portfolio' ) ) {
		global $post;
		$anchor = sanitize_title( $post->post_title );
		wp_redirect( home_url( '/portfolio/#' . $anchor ), 301 );
		exit;
	}
} );
