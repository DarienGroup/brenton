<?php
/**
 * Sector Focus page — Testimonials section.
 *
 * Two-column layout:
 *   - Left:  section heading, slider nav arrows, body copy, and a single
 *            "Over N repeat executive partnerships" stat block with a
 *            sky-blue accent rule (same visual language as the Team Deals
 *            block on this page).
 *   - Right: testimonials slider showing one card per view at every
 *            breakpoint (data-testimonials-per-view="1"). Reuses the shared
 *            .testimonial-card markup and CSS from the firm template, as
 *            well as the slider JS in parts/firm-testimonials.js.
 *
 * Below the columns: optional disclaimer in the same .quote container used
 * on the firm page.
 *
 * ACF fields:
 *   sector_focus_testimonials_heading      (text)
 *   sector_focus_testimonials_body         (wysiwyg)
 *   sector_focus_testimonials_stat_icon    (image)
 *   sector_focus_testimonials_stat_prefix  (text)   e.g. "Over"
 *   sector_focus_testimonials_stat_number  (text)   e.g. "20"
 *   sector_focus_testimonials_stat_label   (text)   e.g. "repeat executive partnerships"
 *   sector_focus_testimonials              (relationship → testimonial)
 *   sector_focus_testimonials_disclaimer   (textarea)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading      = $field('sector_focus_testimonials_heading');
$body         = $field('sector_focus_testimonials_body');
$stat_icon    = $field('sector_focus_testimonials_stat_icon');
$stat_prefix  = $field('sector_focus_testimonials_stat_prefix');
$stat_number  = $field('sector_focus_testimonials_stat_number');
$stat_label   = $field('sector_focus_testimonials_stat_label');
$selected     = $field('sector_focus_testimonials');
$disclaimer   = $field('sector_focus_testimonials_disclaimer');

$ids = [];
if (is_array($selected)) {
    foreach ($selected as $item) {
        if (is_object($item) && isset($item->ID)) {
            $ids[] = (int) $item->ID;
        } elseif (is_numeric($item)) {
            $ids[] = (int) $item;
        }
    }
}

$testimonials = [];
if ($ids) {
    $testimonials = get_posts([
        'post_type'      => 'testimonial',
        'post__in'       => $ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
        'no_found_rows'  => true,
    ]);
}

$stat_icon_url = is_array($stat_icon) ? ($stat_icon['url'] ?? '') : (string) $stat_icon;
$stat_icon_alt = is_array($stat_icon) ? ($stat_icon['alt'] ?? '') : '';
$has_stat      = $stat_icon_url || $stat_prefix || $stat_number || $stat_label;
?>
<section class="sector-testimonials-section section" data-reveal data-testimonials data-testimonials-per-view="1">
    <div class="sector-testimonials-section__inner container">

        <div class="sector-testimonials-section__cols">

            <aside class="sector-testimonials-section__left">

                <div class="sector-testimonials-section__top">
                    <?php if ($heading) : ?>
                        <h2 class="sector-testimonials-section__heading text-h2 text-weight-600 text-color-black">
                            <?php echo esc_html($heading); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($testimonials)) : ?>
                        <div class="sector-testimonials-section__nav sector-testimonials-section__nav--desktop" data-testimonials-nav>
                            <button type="button"
                                    class="firm-testimonials-section__nav-btn"
                                    data-testimonials-prev
                                    aria-label="<?php esc_attr_e('Previous testimonial', 'brentonpoint'); ?>">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button type="button"
                                    class="firm-testimonials-section__nav-btn"
                                    data-testimonials-next
                                    aria-label="<?php esc_attr_e('Next testimonial', 'brentonpoint'); ?>">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M7.5 5L12.5 10L7.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($body || $has_stat) : ?>
                    <div class="sector-testimonials-section__bottom">
                        <?php if ($body) : ?>
                            <div class="sector-testimonials-section__body text-body-L text-color-primary-gray">
                                <?php echo wp_kses_post(wpautop($body)); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($has_stat) : ?>
                            <div class="sector-testimonials-stat">
                                <?php if ($stat_icon_url) : ?>
                                    <span class="sector-testimonials-stat__icon" aria-hidden="true">
                                        <img src="<?php echo esc_url($stat_icon_url); ?>" alt="<?php echo esc_attr($stat_icon_alt); ?>">
                                    </span>
                                <?php endif; ?>

                                <p class="sector-testimonials-stat__line">
                                    <?php if ($stat_prefix) : ?>
                                        <span class="sector-testimonials-stat__prefix text-body-L text-color-black">
                                            <?php echo esc_html($stat_prefix); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($stat_number) : ?>
                                        <span class="sector-testimonials-stat__number text-h2 text-color-primary">
                                            <?php echo esc_html($stat_number); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($stat_label) : ?>
                                        <span class="sector-testimonials-stat__label text-body-L text-color-black">
                                            <?php echo esc_html($stat_label); ?>
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </aside>

            <?php if (!empty($testimonials)) : ?>
                <div class="sector-testimonials-section__right">
                    <div class="sector-testimonials-section__nav sector-testimonials-section__nav--mobile" data-testimonials-nav>
                        <button type="button"
                                class="firm-testimonials-section__nav-btn"
                                data-testimonials-prev
                                aria-label="<?php esc_attr_e('Previous testimonial', 'brentonpoint'); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button"
                                class="firm-testimonials-section__nav-btn"
                                data-testimonials-next
                                aria-label="<?php esc_attr_e('Next testimonial', 'brentonpoint'); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M7.5 5L12.5 10L7.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <div class="sector-testimonials-section__slider" data-testimonials-slider>

                        <ul class="sector-testimonials-section__track" data-testimonials-track>
                            <?php foreach ($testimonials as $testimonial) :
                                $id    = $testimonial->ID;
                                $name  = get_the_title($testimonial);
                                $quote = get_post_meta($id, 'testimonial_quote', true);
                                $role  = get_post_meta($id, 'testimonial_role',  true);
                                $avatar = get_the_post_thumbnail($id, [112, 112], [
                                    'class'   => 'testimonial-card__avatar-img',
                                    'loading' => 'lazy',
                                    'alt'     => $name,
                                ]);

                                if (!$quote && !$name) {
                                    continue;
                                }
                            ?>
                                <li class="sector-testimonials-section__slide">
                                    <article class="testimonial-card">
                                        <?php if ($quote) : ?>
                                            <div class="testimonial-card__quote text-body-L" data-truncate-chars="380" data-truncate-chars-mobile="200">
                                                <?php echo esc_html($quote); ?>
                                            </div>
                                        <?php endif; ?>

                                        <footer class="testimonial-card__footer">
                                            <div class="testimonial-card__person">
                                                <?php if ($avatar) : ?>
                                                    <span class="testimonial-card__avatar">
                                                        <?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                    </span>
                                                <?php endif; ?>
                                                <div class="testimonial-card__meta">
                                                    <?php if ($name) : ?>
                                                        <p class="testimonial-card__name text-weight-700"><?php echo esc_html($name); ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($role) : ?>
                                                        <p class="testimonial-card__role"><?php echo esc_html($role); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <span class="testimonial-card__mark" aria-hidden="true">
                                                <svg width="18" height="14" viewBox="0 0 18 14" fill="none">
                                                    <path d="M7.2 0H0v6.3h3.6c0 2-1.2 3.6-3.6 3.9v3.5C5.2 13.4 7.2 10.2 7.2 6.7V0Zm10.8 0h-7.2v6.3h3.6c0 2-1.2 3.6-3.6 3.9v3.5C16 13.4 18 10.2 18 6.7V0Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                        </footer>
                                    </article>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <?php if ($disclaimer) : ?>
            <div class="sector-testimonials-section__disclaimer quote text-quote">
                <?php echo wp_kses_post(wpautop($disclaimer)); ?>
            </div>
        <?php endif; ?>

    </div>
</section>
