<?php
/**
 * Team page — Executive Advisory Board section.
 *
 * Layout:
 *   1. Heading (stacked, left-aligned)
 *   2. Intro copy (max-width 878)
 *   3. Stats row (4 cards: value + optional suffix + label, with thin top accent)
 *   4. Optional short disclaimer rendered in the bordered .quote box
 *   5. Grid of team cards filtered to the `executive-board` taxonomy term
 *   6. Optional long disclaimer rendered in the bordered .quote box
 *
 * Re-uses the `team-card` component so cards stay identical to the primary
 * Team grid above.
 *
 * ACF (Team Page group, group_9d147bea2e240):
 *   team_advisory_heading            (text)
 *   team_advisory_description        (wysiwyg)
 *   team_advisory_stats              (repeater)
 *     value   (text)
 *     suffix  (text)
 *     label   (text)
 *   team_advisory_disclaimer_top     (textarea — short caption above the grid)
 *   team_advisory_disclaimer_bottom  (textarea — long footnote below the grid)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading           = (string) $field('team_advisory_heading');
$description       = (string) $field('team_advisory_description');
$stats             = $field('team_advisory_stats');
$disclaimer_top    = (string) $field('team_advisory_disclaimer_top');
$disclaimer_bottom = (string) $field('team_advisory_disclaimer_bottom');

$query = new WP_Query([
    'post_type'      => 'teams',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
    'tax_query'      => [
        [
            'taxonomy' => 'team_list',
            'field'    => 'slug',
            'terms'    => 'executive-board',
        ],
    ],
]);

// If there are no advisors and no copy to show, bail.
if (!$heading && !$description && empty($stats) && !$query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="team-advisory-section section" data-reveal style="--section-pt: 0px;">
    <div class="team-advisory-section__inner container">

        <?php if ($heading || $description) : ?>
            <header class="team-advisory-section__header">
                <?php if ($heading) : ?>
                    <h2 class="team-advisory-section__heading text-h2 text-weight-600 text-color-black">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($description) : ?>
                    <div class="team-advisory-section__description text-body-L">
                        <?php echo wp_kses_post(wpautop($description)); ?>
                    </div>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if (is_array($stats) && !empty($stats)) : ?>
            <ul class="team-advisory-section__stats">
                <?php foreach ($stats as $stat) :
                    $value  = isset($stat['value'])  ? (string) $stat['value']  : '';
                    $suffix = isset($stat['suffix']) ? (string) $stat['suffix'] : '';
                    $label  = isset($stat['label'])  ? (string) $stat['label']  : '';
                    if (!$value && !$label) {
                        continue;
                    }
                ?>
                    <li class="team-advisory-stat">
                        <?php if ($value || $suffix) : ?>
                            <p class="team-advisory-stat__value">
                                <span class="team-advisory-stat__number text-h2 text-color-deep-teal"><?php echo esc_html($value); ?></span>
                                <?php if ($suffix) : ?>
                                    <span class="team-advisory-stat__suffix text-h4 text-color-deep-teal"><?php echo esc_html($suffix); ?></span>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($label) : ?>
                            <p class="team-advisory-stat__label text-body-L text-color-primary-gray"><?php echo nl2br(esc_html($label)); ?></p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($disclaimer_top) : ?>
            <div class="team-advisory-section__disclaimer quote text-quote">
                <?php echo wp_kses_post(wpautop($disclaimer_top)); ?>
            </div>
        <?php endif; ?>

        <?php if ($query->have_posts()) : ?>
            <ul class="team-advisory-section__grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <li class="team-advisory-section__item">
                        <?php get_template_part('template-parts/components/team-card', null, [
                            'post_id' => get_the_ID(),
                            'linked'  => true,
                        ]); ?>
                    </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        <?php endif; ?>

        <?php if ($disclaimer_bottom) : ?>
            <div class="team-advisory-section__disclaimer team-advisory-section__disclaimer--footnote quote text-quote">
                <?php echo wp_kses_post(wpautop($disclaimer_bottom)); ?>
            </div>
        <?php endif; ?>

    </div>
</section>
