<?php
defined( 'ABSPATH' ) || exit;

/**
 * [btn] — render a `.btn` from a shortcode.
 *
 *     [btn variant="cyan" href="/contact"]Get in touch[/btn]
 *     [btn variant="deep-teal" full="1" target="_blank" href="https://…"]Open[/btn]
 *
 * Inner content becomes the label. Tags are stripped for safety.
 */
add_shortcode( 'btn', function ( $atts, $content = null ) {
	$atts = shortcode_atts( [
		'variant' => 'cyan',
		'href'    => '',
		'full'    => '',
		'target'  => '',
		'rel'     => '',
		'class'   => '',
		'id'      => '',
	], $atts, 'btn' );

	return brentonpoint_get_button( [
		'label'   => trim( wp_strip_all_tags( (string) $content ) ),
		'variant' => $atts['variant'],
		'href'    => $atts['href'],
		'full'    => filter_var( $atts['full'], FILTER_VALIDATE_BOOLEAN ),
		'target'  => $atts['target'],
		'rel'     => $atts['rel'],
		'class'   => $atts['class'],
		'id'      => $atts['id'],
	] );
} );
