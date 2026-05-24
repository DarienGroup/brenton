<?php
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
};

$heading = $field('news_heading') ?: 'News';
$quote = $field('news_quote');
$read_more_label = $field('news_section_read_more_label') ?: __('Read More', 'brentonpoint');
$count = (int)($field('news_section_posts_count') ?: 3);

$news_query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $count > 0 ? $count : 3,
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
        'post_status' => 'publish',
]);

if (!$news_query->have_posts()) {
    wp_reset_postdata();
    return;
}

$section_classes = ['news-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'news-section--home';
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <div class="news-section__inner container">

        <h2 class="news-section__heading text-h2 text-weight-600 text-color-black">
            <?php echo esc_html($heading); ?>
        </h2>

        <ul class="news-section__cards">
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

        <?php if ($quote) : ?>
            <div class="news-section__quote quote text-quote">
                <?php echo wp_kses_post(wpautop($quote)); ?>
            </div>
        <?php endif; ?>

    </div>
</section>
