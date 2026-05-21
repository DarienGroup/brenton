<?php
defined( 'ABSPATH' ) || exit;

// CPT registration lives in the Custom Post Type UI plugin.
add_action( 'pre_get_posts', function ( WP_Query $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->get( 'post_type' ) === 'our_portfolio' ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
} );
