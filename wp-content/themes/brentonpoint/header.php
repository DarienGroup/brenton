<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="site-header__inner container">
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'brentonpoint' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="sr-only"><?php esc_html_e( 'Menu', 'brentonpoint' ); ?></span>
					<span class="hamburger"></span>
				</button>
				<?php wp_nav_menu( [ 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ] ); ?>
			</nav>
		</div>
	</header>

	<div id="content" class="site-content">
