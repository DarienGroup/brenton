<?php
/**
 * Firm page — Testimonials slider.
 *
 * Reads from the `testimonial` CPT. The Firm page picks which testimonials
 * to display (and in which order) via the `firm_testimonials` relationship
 * field. The slider is a small vanilla component in
 * src/js/parts/firm-testimonials.js.
 *
 * Per testimonial post:
 *   post_title             → speaker name
 *   featured image         → avatar (rendered as 56px circle)
 *   ACF testimonial_quote  → quote text
 *   ACF testimonial_role   → role / company line
 *
 * Section ACF fields:
 *   firm_testimonials_heading    (text)
 *   firm_testimonials            (relationship → testimonial)
 *   firm_testimonials_disclaimer (textarea)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading    = $field('firm_testimonials_heading');
$selected   = $field('firm_testimonials');
$disclaimer = $field('firm_testimonials_disclaimer');

// Normalize selected IDs (relationship can return IDs or post objects depending on return_format).
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

if (empty($ids)) {
    return;
}

$testimonials = get_posts([
    'post_type'      => 'testimonial',
    'post__in'       => $ids,
    'posts_per_page' => -1,
    'orderby'        => 'post__in',
    'no_found_rows'  => true,
]);

if (empty($testimonials)) {
    return;
}
?>
<section class="firm-testimonials-section section" data-reveal data-testimonials>
    <div class="firm-testimonials-section__inner container">

        <header class="firm-testimonials-section__header">
            <?php if ($heading) : ?>
                <h2 class="firm-testimonials-section__heading text-h2 text-weight-600 text-color-black">
                    <?php echo esc_html($heading); ?>
                </h2>
            <?php endif; ?>

            <div class="firm-testimonials-section__nav" data-testimonials-nav>
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
        </header>

        <div class="firm-testimonials-section__slider" data-testimonials-slider>
            <ul class="firm-testimonials-section__track" data-testimonials-track>
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
                    <li class="firm-testimonials-section__slide">
                        <article class="testimonial-card">
                            <?php if ($quote) : ?>
                                <div class="testimonial-card__quote text-body-L"
                                     data-truncate-chars="380"
                                     data-truncate-chars-mobile="200">
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

        <?php if ($disclaimer) : ?>
            <div class="firm-testimonials-section__disclaimer quote text-quote">
                <?php echo wp_kses_post(wpautop($disclaimer)); ?>
            </div>
        <?php endif; ?>

    </div>
</section>
