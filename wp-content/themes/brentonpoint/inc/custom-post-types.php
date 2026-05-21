<?php
defined( 'ABSPATH' ) || exit;

// CPT registration lives in the Custom Post Type UI plugin.
// This file handles theme-level behaviour for those post types.

/**
 * Order our_portfolio posts by menu_order ASC on all front-end queries.
 *
 * Mirrors the Enfold child avia_blog_post_query filter which set the same
 * ordering inside Enfold's grid element. Applied via pre_get_posts so it
 * works on archives, taxonomy pages, and any main query for this CPT.
 */
add_action( 'pre_get_posts', function ( WP_Query $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->get( 'post_type' ) === 'our_portfolio' ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
} );
