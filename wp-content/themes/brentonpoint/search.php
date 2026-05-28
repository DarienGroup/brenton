<?php
/**
 * Search results template.
 *
 * The search input itself lives in the global header (header.php) — on this
 * page it is forced open via the `is-open` class, prefilled with the current
 * query. Here we render the range/total counter and the list of result cards
 * (template-parts/content-search.php).
 */
defined('ABSPATH') || exit;
get_header();

$total       = (int) $GLOBALS['wp_query']->found_posts;
$per_page    = (int) $GLOBALS['wp_query']->get('posts_per_page');
$paged       = max(1, (int) get_query_var('paged'));
$range_start = $total ? (($paged - 1) * $per_page) + 1 : 0;
$range_end   = $total ? min($paged * $per_page, $total) : 0;
?>

<main id="main" class="site-main site-main--inner">

    <section class="search-results">
        <div class="search-results__inner container">

            <?php if (have_posts()) : ?>

                <p class="search-results__count text-body-M">
                    <?php
                    printf(
                        /* translators: 1: first result, 2: last result, 3: total results */
                        esc_html__('%1$s-%2$s of %3$s Results', 'brentonpoint'),
                        esc_html(number_format_i18n($range_start)),
                        esc_html(number_format_i18n($range_end)),
                        esc_html(number_format_i18n($total))
                    );
                    ?>
                </p>

                <ul class="search-results__list">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content', 'search'); ?>
                    <?php endwhile; ?>
                </ul>

                <?php the_posts_navigation(); ?>

            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php
get_footer();
