<?php
defined( 'ABSPATH' ) || exit;

function brentonpoint_posted_on(): void {
	echo '<time class="entry-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">'
		. esc_html( get_the_date() ) . '</time>';
}

function brentonpoint_posted_by(): void {
	echo '<span class="author">' . esc_html( get_the_author() ) . '</span>';
}
