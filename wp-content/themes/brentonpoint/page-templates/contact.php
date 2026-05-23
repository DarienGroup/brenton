<?php
/**
 * Template Name: Contact
 *
 * Renders an editable page intro (the_content) above the shared
 * contact section, with the description shown above the form.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="main" class="site-main site-main--contact">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if ( trim( get_the_content() ) !== '' ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'contact-intro' ); ?>>
				<div class="entry-content container">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>

	<?php
	get_template_part(
		'template-parts/sections/contact-section',
		null,
		[ 'variant' => 'default' ]
	);
	?>
</main>

<?php
get_footer();
