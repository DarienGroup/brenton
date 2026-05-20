<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="main" class="site-main">
	<section class="error-404 not-found">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( '404 — Page not found', 'brentonpoint' ); ?></h1>
		</header>
		<div class="page-content">
			<p><?php esc_html_e( 'The page you are looking for does not exist.', 'brentonpoint' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
				<?php esc_html_e( 'Back to home', 'brentonpoint' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
