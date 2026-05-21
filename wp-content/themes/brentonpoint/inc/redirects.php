<?php
defined( 'ABSPATH' ) || exit;

/**
 * Redirect single our_portfolio entries to the main portfolio page.
 *
 * Individual portfolio items have no standalone template; instead visitors
 * land on /portfolio/ with the post title used as the anchor fragment so
 * the page can scroll to the correct block.
 */
add_action( 'template_redirect', function () {
	if ( is_singular( 'our_portfolio' ) ) {
		global $post;
		$anchor = sanitize_title( $post->post_title );
		wp_redirect( home_url( '/portfolio/#' . $anchor ), 301 );
		exit;
	}
} );
