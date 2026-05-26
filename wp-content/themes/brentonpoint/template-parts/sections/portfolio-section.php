<?php
/**
 * Portfolio section — heading + filter tabs + grid of partner-company cards.
 *
 * Heading + intro come from the Portfolio Page ACF group (scoped to page 264).
 * Cards come from the `our_portfolio` CPT; the All/Active/Realized tabs filter
 * by the `category_portfolio` taxonomy (terms: active, realized). Filtering is
 * client-side via initPortfolioTabs() in src/js/pages/portfolio.js.
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading     = (string) $field('portfolio_heading');
$description = (string) $field('portfolio_description');

/* Empty-state copy per filter — defaults match the on-design "Realized" empty
 * state. The icon is shared; if no ACF image is set the hourglass SVG below is
 * rendered inline. */
$empty_icon = $field('portfolio_empty_icon');
$empty_states = [
    'Active' => [
        'heading'     => (string) ($field('portfolio_empty_active_heading') ?: __('Active investments will appear here soon.', 'brentonpoint')),
        'description' => (string) ($field('portfolio_empty_active_description') ?: __("We're continuing to grow and evolve our portfolio over time.", 'brentonpoint')),
    ],
    'Realized' => [
        'heading'     => (string) ($field('portfolio_empty_realized_heading') ?: __('Realized investments will appear here in the future.', 'brentonpoint')),
        'description' => (string) ($field('portfolio_empty_realized_description') ?: __("We're continuing to grow and evolve our portfolio over time.", 'brentonpoint')),
    ],
];

$render_empty_icon = static function ($icon): string {
    if (is_array($icon) && !empty($icon['ID'])) {
        return wp_get_attachment_image((int) $icon['ID'], 'thumbnail', false, [
            'class'   => 'portfolio-empty__icon',
            'loading' => 'lazy',
            'alt'     => '',
        ]);
    }
    // Default: theme-bundled hourglass icon matching the on-design empty state.
    return sprintf(
        '<img class="portfolio-empty__icon" src="%s" width="80" height="80" alt="" aria-hidden="true" loading="lazy">',
        esc_url(get_template_directory_uri() . '/images/portfolio-empty-icon.svg')
    );
};

$query = new WP_Query([
    'post_type'      => 'our_portfolio',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);

if (!$query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="portfolio-section section" data-reveal>
    <div class="portfolio-section__inner container">

        <?php if ($heading || $description) : ?>
            <div class="portfolio-section__header">
                <?php if ($heading) : ?>
                    <h2 class="portfolio-section__heading text-h2 text-weight-600 text-color-black">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($description) : ?>
                    <div class="portfolio-section__description text-body-M">
                        <?php echo wp_kses_post(wpautop($description)); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="portfolio-section__tabs portfolio-tabs" role="tablist" aria-label="<?php esc_attr_e('Filter partner companies', 'brentonpoint'); ?>">
            <button type="button" class="active" data-block="All"><?php esc_html_e('All', 'brentonpoint'); ?></button>
            <button type="button" data-block="Active"><?php esc_html_e('Active', 'brentonpoint'); ?></button>
            <button type="button" data-block="Realized"><?php esc_html_e('Realized', 'brentonpoint'); ?></button>
        </div>

        <ul class="portfolio-section__grid portfolio_block">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <li class="portfolio-section__item">
                    <?php get_template_part('template-parts/components/portfolio-card', null, [
                        'post_id' => get_the_ID(),
                    ]); ?>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>

        <?php foreach ($empty_states as $filter => $copy) : ?>
            <div
                class="portfolio-section__empty portfolio-empty"
                data-empty-filter="<?php echo esc_attr($filter); ?>"
                hidden
            >
                <?php echo $render_empty_icon($empty_icon); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <h3 class="portfolio-empty__heading text-h3 text-weight-600 text-color-black">
                    <?php echo esc_html($copy['heading']); ?>
                </h3>
                <?php if ($copy['description']) : ?>
                    <p class="portfolio-empty__description text-body-M text-color-primary-gray">
                        <?php echo esc_html($copy['description']); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>
</section>
