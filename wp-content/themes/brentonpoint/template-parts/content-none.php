<?php
defined( 'ABSPATH' ) || exit;
?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing found', 'brentonpoint' ); ?></h1>
	</header>
	<div class="page-content">
		<?php get_search_form(); ?>
	</div>
</section>
