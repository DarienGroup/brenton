<?php
/**
 * News page — listing section.
 *
 * Two-column header (heading on the left, description on the right) above a
 * 3-column grid of news cards. Reuses the .news-card styles from the homepage
 * news section so cards stay visually consistent across the site.
 *
 * ACF fields (scoped to the News page):
 *   news_listing_heading      (text)
 *   news_listing_description  (textarea / wysiwyg)
 *   news_listing_read_more_label (text, optional)
 *   news_listing_posts_per_page  (number, optional — defaults to -1 = all)
 */
defined('ABSPATH') || exit;

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
};

$heading         = $field('news_listing_heading') ?: __('Success That Speaks for Itself', 'brentonpoint');
$description     = $field('news_listing_description') ?: __('Building great companies takes great people. We are honored to play a part in the growth of our partner companies and proud to share their latest news alongside updates from our team.', 'brentonpoint');
$read_more_label = $field('news_listing_read_more_label') ?: __('Read More', 'brentonpoint');

$per_page = (int) ($field('news_listing_posts_per_page') ?: -1);
if ($per_page === 0) {
    $per_page = -1;
}

$paged = max(1, (int) get_query_var('paged'));

$news_query = new WP_Query([
    'post_type'           => 'post',
    'posts_per_page'      => $per_page,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
    'post_status'         => 'publish',
]);

if (!$news_query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="news-listing-section" data-reveal>
    <div class="news-listing-section__inner container">

        <div class="news-listing-section__header">
            <?php if ($heading) : ?>
                <h2 class="news-listing-section__heading text-h2 text-weight-600 text-color-black">
                    <?php echo esc_html($heading); ?>
                </h2>
            <?php endif; ?>

            <?php if ($description) : ?>
                <div class="news-listing-section__description text-body-M">
                    <?php echo wp_kses_post(wpautop($description)); ?>
                </div>
            <?php endif; ?>
        </div>

        <ul class="news-listing-section__cards">
            <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                <li class="news-card">
                    <h3 class="news-card__title text-h4 text-weight-600 text-color-black">
                        <?php the_title(); ?>
                    </h3>

                    <div class="news-card__excerpt text-body-M">
                        <?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
                    </div>

                    <?php brentonpoint_button([
                        'label'   => $read_more_label,
                        'href'    => get_permalink(),
                        'variant' => 'link-teal',
                        'class'   => 'news-card__link',
                        'attrs'   => [
                            'aria-label' => sprintf('%s: %s', $read_more_label, get_the_title()),
                        ],
                    ]); ?>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>

    </div>
</section>
