<?php
defined( 'ABSPATH' ) || exit;

function brentonpoint_posted_on(): void {
	echo '<time class="entry-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">'
		. esc_html( get_the_date() ) . '</time>';
}

function brentonpoint_posted_by(): void {
	echo '<span class="author">' . esc_html( get_the_author() ) . '</span>';
}

/**
 * Build the args for the about-tabs component from ACF fields on a given
 * post. Used by the homepage about section and by the firm page tabs
 * section, which pulls the same data from the front page.
 *
 * @param int|null $post_id Read fields from this post. Null = current post.
 * @return array{panels: array, desktop_image: mixed}
 */
function brentonpoint_about_tabs_args( ?int $post_id = null ): array {
	$get = static function ( string $name ) use ( $post_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}
		return $post_id ? get_field( $name, $post_id ) : get_field( $name );
	};

	$panels = [
		'mission' => [
			'tab'     => $get( 'about_mission_tab_label' ) ?: __( 'Mission Statement', 'brentonpoint' ),
			'heading' => $get( 'about_mission_heading' )   ?: __( 'Mission Statement', 'brentonpoint' ),
			'body'    => $get( 'about_mission_body' ),
			'label'   => $get( 'about_mission_label' )     ?: __( 'Mission', 'brentonpoint' ),
			'image'   => $get( 'about_mission_image' ),
		],
		'vision' => [
			'tab'     => $get( 'about_vision_tab_label' ) ?: __( 'Vision Statement', 'brentonpoint' ),
			'heading' => $get( 'about_vision_heading' )   ?: __( 'Vision Statement', 'brentonpoint' ),
			'body'    => $get( 'about_vision_body' ),
			'label'   => $get( 'about_vision_label' )     ?: __( 'Vision', 'brentonpoint' ),
			'image'   => $get( 'about_vision_image' ),
		],
	];

	$desktop_image = $get( 'about_desktop_image' )
		?: $get( 'about_mission_image' )
		?: $get( 'about_vision_image' );

	return [
		'panels'        => $panels,
		'desktop_image' => $desktop_image,
	];
}
