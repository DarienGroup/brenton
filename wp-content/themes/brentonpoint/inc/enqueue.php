<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

add_action( 'wp_head', function () {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$light = get_field( 'light_favicon', 'option' );
	$dark  = get_field( 'dark_favicon', 'option' );

	$type_for = static function ( $url ) {
		$ext = strtolower( pathinfo( wp_parse_url( $url, PHP_URL_PATH ) ?: '', PATHINFO_EXTENSION ) );
		return [
			'svg'  => 'image/svg+xml',
			'png'  => 'image/png',
			'ico'  => 'image/x-icon',
			'gif'  => 'image/gif',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'webp' => 'image/webp',
		][ $ext ] ?? '';
	};

	if ( $light ) {
		printf(
			'<link rel="icon" type="%s" href="%s" media="(prefers-color-scheme: light)">' . "\n",
			esc_attr( $type_for( $light ) ),
			esc_url( $light )
		);
	}
	if ( $dark ) {
		printf(
			'<link rel="icon" type="%s" href="%s" media="(prefers-color-scheme: dark)">' . "\n",
			esc_attr( $type_for( $dark ) ),
			esc_url( $dark )
		);
	}
	// Fallback for browsers that ignore media queries on icons.
	if ( $light || $dark ) {
		$fallback = $light ?: $dark;
		printf(
			'<link rel="icon" type="%s" href="%s">' . "\n",
			esc_attr( $type_for( $fallback ) ),
			esc_url( $fallback )
		);
	}
}, 1 );

add_action( 'wp_enqueue_scripts', function () {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();
	$dist       = $theme_uri . '/dist';

	$asset_ver = static function ( $relative ) use ( $theme_path ) {
		$file = $theme_path . '/dist/' . ltrim( $relative, '/' );
		return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
	};

	wp_enqueue_style(
		'brentonpoint-fonts',
		'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Montserrat:wght@400;500;600;700&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'brentonpoint-main',
		$dist . '/css/main.css',
		[ 'brentonpoint-fonts' ],
		$asset_ver( 'css/main.css' )
	);

	wp_enqueue_script(
		'brentonpoint-main',
		$dist . '/js/main.js',
		[ 'jquery' ],
		$asset_ver( 'js/main.js' ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
} );
