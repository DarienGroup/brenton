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
 * Sanitize post content imported from legacy Enfold/Avia builds.
 *
 * Strips Avia shortcode wrappers like [av_section ...], [av_textblock ...],
 * [av_image ...] and their closing tags while preserving the meaningful text
 * and media between them. Curly/smart quotes in shortcode attributes are
 * normalized first so the regex matches reliably. Images embedded as Avia
 * shortcodes are converted to plain <img> tags using the `src` attribute when
 * present.
 *
 * Output is run through wpautop + wp_kses_post so it's safe to echo without
 * additional escaping.
 */
function brentonpoint_clean_post_content( string $content ): string {
	if ( $content === '' ) {
		return '';
	}

	$content = str_replace(
		[ "\xE2\x80\x98", "\xE2\x80\x99", "\xE2\x80\x9C", "\xE2\x80\x9D" ],
		[ "'", "'", '"', '"' ],
		$content
	);

	$content = preg_replace_callback(
		'/\[av_image\b([^\]]*)\]/i',
		static function ( $m ) {
			if ( preg_match( '/\bsrc=([\'"])(.*?)\1/i', $m[1], $src ) ) {
				return '<img src="' . esc_url( $src[2] ) . '" alt="">';
			}
			if ( preg_match( '/\battachment=([\'"])(\d+)\1/i', $m[1], $att ) ) {
				$url = wp_get_attachment_image_url( (int) $att[2], 'large' );
				if ( $url ) {
					return '<img src="' . esc_url( $url ) . '" alt="">';
				}
			}
			return '';
		},
		$content
	);

	$content = preg_replace( '/\[\/?av_[a-z0-9_]*[^\]]*\]/i', '', $content );

	$content = preg_replace( '/\n{3,}/', "\n\n", trim( (string) $content ) );

	return wp_kses_post( wpautop( $content ) );
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
