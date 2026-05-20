<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<div class="entry-meta">
					<?php brentonpoint_posted_on(); ?>
					<?php brentonpoint_posted_by(); ?>
				</div>
			</header>
			<?php the_post_thumbnail( 'full', [ 'class' => 'entry-thumbnail' ] ); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php the_post_navigation(); ?>
		<?php comments_template(); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
