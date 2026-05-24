<?php
defined( 'ABSPATH' ) || exit;

// Legacy CPTs (teams, our_portfolio) are registered via the Custom Post Type UI
// plugin. New theme-owned CPTs are registered here so they live in version
// control alongside the templates that consume them.
add_action( 'init', function () {
	register_post_type( 'testimonial', [
		'labels' => [
			'name'               => __( 'Testimonials', 'brentonpoint' ),
			'singular_name'      => __( 'Testimonial', 'brentonpoint' ),
			'add_new_item'       => __( 'Add New Testimonial', 'brentonpoint' ),
			'edit_item'          => __( 'Edit Testimonial', 'brentonpoint' ),
			'new_item'           => __( 'New Testimonial', 'brentonpoint' ),
			'view_item'          => __( 'View Testimonial', 'brentonpoint' ),
			'search_items'       => __( 'Search Testimonials', 'brentonpoint' ),
			'not_found'          => __( 'No testimonials found', 'brentonpoint' ),
			'not_found_in_trash' => __( 'No testimonials found in trash', 'brentonpoint' ),
			'menu_name'          => __( 'Testimonials', 'brentonpoint' ),
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'menu_icon'           => 'dashicons-format-quote',
		'menu_position'       => 25,
		'supports'            => [ 'title', 'thumbnail', 'page-attributes' ],
		'has_archive'         => false,
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'rewrite'             => false,
		'query_var'           => false,
	] );
} );

add_action( 'pre_get_posts', function ( WP_Query $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->get( 'post_type' ) === 'our_portfolio' ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
} );

// Admin: show the featured image (used as testimonial avatar) in the list table.
add_filter( 'manage_testimonial_posts_columns', function ( $columns ) {
	$new = [];
	foreach ( $columns as $key => $label ) {
		if ( $key === 'title' ) {
			$new['testimonial_avatar'] = __( 'Avatar', 'brentonpoint' );
		}
		$new[ $key ] = $label;
	}
	return $new;
} );

add_action( 'manage_testimonial_posts_custom_column', function ( $column, $post_id ) {
	if ( $column !== 'testimonial_avatar' ) {
		return;
	}
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, [ 56, 56 ], [ 'style' => 'border-radius:50%;object-fit:cover;' ] );
	} else {
		echo '<span style="display:inline-block;width:56px;height:56px;border-radius:50%;background:#eee;"></span>';
	}
}, 10, 2 );
