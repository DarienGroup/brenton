<?php
/**
 * Template Name: Inner page
 *
 * Shared template for inner pages (Firm, Team, etc.). Renders the standard
 * inner-page chrome — page hero on top — and then loads a page-specific
 * sections file by slug from /template-parts/page-sections/{slug}.php.
 *
 * To add a new inner page:
 *   1. Create the page in WP admin and assign this "Inner page" template.
 *   2. Drop template-parts/page-sections/{slug}.php with that page's sections.
 *   3. Register an ACF field group scoped to that page (or its slug).
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main" class="site-main site-main--inner">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/sections/page-hero-section'); ?>

        <?php
        $slug          = get_post_field('post_name', get_the_ID());
        $sections_file = get_template_directory() . '/template-parts/page-sections/' . $slug . '.php';

        if ($slug && file_exists($sections_file)) {
            get_template_part('template-parts/page-sections/' . $slug);
        }
        ?>
    <?php endwhile; ?>
</main>

<?php
get_footer();
