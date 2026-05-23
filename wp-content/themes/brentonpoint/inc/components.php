<?php
defined( 'ABSPATH' ) || exit;

/**
 * Build the markup for a `.btn` component (`_buttons.scss`) and return it.
 *
 * The helper is the single source of truth for button HTML — templates,
 * the `[btn]` shortcode, and the Gravity Forms `gform_submit_button` filter
 * all route through here so the bullet, label, and chevron stay consistent.
 *
 * @param array $args {
 *   @type string $label    Button text. Default ''.
 *   @type string $variant  Color variant matching a `$button-variants` key
 *                          in `_buttons.scss`. Default 'cyan'.
 *   @type string $href     URL. When empty, the element is rendered as
 *                          `<button type="$type">` instead of `<a>`.
 *   @type bool   $full     Apply the `btn--full` modifier. Default false.
 *   @type string $target   `<a>` only. Default ''.
 *   @type string $rel      `<a>` only. Auto-set to `noopener noreferrer`
 *                          when `target="_blank"` and no rel was given.
 *   @type string $type     `<button>` only. Default 'button'.
 *   @type string $class    Extra classes appended after `.btn` classes.
 *   @type string $id       Element id.
 *   @type array  $attrs    Extra HTML attributes (key => value). Escaped.
 * }
 */
function brentonpoint_get_button( array $args = [] ): string {
	$args = wp_parse_args( $args, [
		'label'   => '',
		'variant' => 'cyan',
		'href'    => '',
		'full'    => false,
		'target'  => '',
		'rel'     => '',
		'type'    => 'button',
		'class'   => '',
		'id'      => '',
		'attrs'   => [],
	] );

	$variant = sanitize_html_class( $args['variant'] ) ?: 'cyan';
	$classes = [ 'btn', 'btn--' . $variant ];
	if ( ! empty( $args['full'] ) ) {
		$classes[] = 'btn--full';
	}
	if ( ! empty( $args['class'] ) ) {
		$classes[] = $args['class'];
	}

	$attrs = [ 'class' => implode( ' ', $classes ) ];
	if ( ! empty( $args['id'] ) ) {
		$attrs['id'] = $args['id'];
	}

	$is_link = $args['href'] !== '';
	if ( $is_link ) {
		$attrs['href'] = $args['href'];
		if ( $args['target'] ) {
			$attrs['target'] = $args['target'];
			if ( ! $args['rel'] && $args['target'] === '_blank' ) {
				$attrs['rel'] = 'noopener noreferrer';
			}
		}
		if ( $args['rel'] ) {
			$attrs['rel'] = $args['rel'];
		}
	} else {
		$attrs['type'] = $args['type'];
	}

	// Caller-supplied attrs override the built-ins (useful for data-* and aria-*).
	foreach ( (array) $args['attrs'] as $k => $v ) {
		$attrs[ $k ] = $v;
	}

	$attr_str = '';
	foreach ( $attrs as $k => $v ) {
		$attr_str .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
	}

	$tag = $is_link ? 'a' : 'button';

	// Hard-coded chevron — the icon is part of the component contract, not the API.
	$icon = '<svg viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">'
		. '<path d="M3 1.5 6.5 5 3 8.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'
		. '</svg>';

	return sprintf(
		'<%1$s%2$s><span class="btn__bullet" aria-hidden="true"></span><span class="btn__label">%3$s</span><span class="btn__icon" aria-hidden="true">%4$s</span></%1$s>',
		$tag,
		$attr_str,
		esc_html( $args['label'] ),
		$icon
	);
}

function brentonpoint_button( array $args = [] ): void {
	echo brentonpoint_get_button( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
