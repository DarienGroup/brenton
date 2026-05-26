<?php
/**
 * Team grid — heading + intro + 4-column grid of team-card components.
 *
 * Reusable: pass which `team_list` taxonomy term to query and where the
 * heading/intro copy live in ACF. Used on the Team page twice (Team list
 * and Executive Board) and can be reused on other pages.
 *
 * Args ($args):
 *   term_slug       string  Slug of a `team_list` taxonomy term. Required.
 *                           Currently: "team-list" or "executive-board".
 *   heading         string  Explicit heading; otherwise read from ACF.
 *   description     string  Explicit description (HTML); otherwise read from ACF.
 *   heading_field   string  ACF key for the heading on the current page.
 *   description_field string ACF key for the description on the current page.
 *   linked_cards    bool    Whether cards link to the single team post. Default true.
 *   class           string  Extra modifier class on the section wrapper.
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'term_slug'         => '',
    'heading'           => null,
    'description'       => null,
    'heading_field'     => '',
    'description_field' => '',
    'linked_cards'      => true,
    'class'             => '',
]);

$term_slug = sanitize_key($args['term_slug']);
if (!$term_slug) {
    return;
}

$field = static function ($name) {
    return ($name && function_exists('get_field')) ? get_field($name) : null;
};

$heading = $args['heading'];
if ($heading === null) {
    $heading = (string) $field($args['heading_field']);
}

$description = $args['description'];
if ($description === null) {
    $description = (string) $field($args['description_field']);
}

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
            'terms'    => $term_slug,
        ],
    ],
]);

if (!$query->have_posts()) {
    wp_reset_postdata();
    return;
}

$classes = ['team-grid-section', 'section'];
if ($args['class']) {
    $classes[] = $args['class'];
}
?>
<section class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-reveal>
    <div class="team-grid-section__inner container">

        <?php if ($heading || $description) : ?>
            <div class="team-grid-section__header">
                <?php if ($heading) : ?>
                    <h2 class="team-grid-section__heading text-h2 text-weight-600 text-color-black">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($description) : ?>
                    <div class="team-grid-section__description text-body-M">
                        <?php echo wp_kses_post(wpautop($description)); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <ul class="team-grid-section__grid">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <li class="team-grid-section__item">
                    <?php get_template_part('template-parts/components/team-card', null, [
                        'post_id' => get_the_ID(),
                        'linked'  => (bool) $args['linked_cards'],
                    ]); ?>
                </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>

    </div>
</section>
