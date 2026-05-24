<?php
/**
 * Firm page — "Our Approach" section.
 *
 * Two-column layout: kicker + heading on the left, body copy on the right.
 * Sits directly under the about section, so the top padding is suppressed
 * via the .section custom-property override (--section-pt: 0).
 *
 * ACF fields:
 *   firm_approach_kicker  (text)
 *   firm_approach_heading (text)
 *   firm_approach_body    (wysiwyg)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$kicker  = $field('firm_approach_kicker') ?: __('Our Approach', 'brentonpoint');
$heading = $field('firm_approach_heading');
$body    = $field('firm_approach_body');
?>
<section class="firm-approach-section section" data-reveal style="--section-pt: 0px;">
    <div class="firm-approach-section__inner container">

        <div class="firm-approach-section__lead">
            <?php if ($kicker) : ?>
                <p class="firm-approach-section__kicker text-h4 text-color-primary-gray">
                    <?php echo esc_html($kicker); ?>
                </p>
            <?php endif; ?>

            <?php if ($heading) : ?>
                <h2 class="firm-approach-section__heading text-h2 text-weight-600 text-color-black">
                    <?php echo esc_html($heading); ?>
                </h2>
            <?php endif; ?>
        </div>

        <?php if ($body) : ?>
            <div class="firm-approach-section__body text-body-L">
                <?php echo wp_kses_post(wpautop($body)); ?>
            </div>
        <?php endif; ?>

    </div>
</section>
