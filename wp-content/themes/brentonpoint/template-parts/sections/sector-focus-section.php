<?php
/**
 * Sector Focus page — "Both Focused and Flexible" section.
 *
 * Two-column layout:
 *   - Left:  fixed-width "Team Deals" block with a vertical accent rule,
 *            small icon + label, and a large deal-range readout. Does not
 *            shrink until the layout stacks.
 *   - Right: intro copy, body copy, an "Industries" label, a repeater of
 *            industry rows rendered as bordered chips, and a closing
 *            paragraph.
 *
 * Column gap: 24px. Gap between the right column's top text block and the
 * industries block: 40px.
 *
 * ACF fields (group: Sector Focus Page):
 *   sector_focus_heading           (text)
 *   sector_focus_deals_icon        (image)
 *   sector_focus_deals_label       (text)
 *   sector_focus_deals_min_amount  (text)
 *   sector_focus_deals_min_unit    (text)
 *   sector_focus_deals_max_amount  (text)
 *   sector_focus_deals_max_unit    (text)
 *   sector_focus_intro             (wysiwyg)
 *   sector_focus_industries_label  (text)
 *   sector_focus_industries        (repeater)
 *     - industry_name              (text)
 *   sector_focus_outro             (wysiwyg)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading          = $field('sector_focus_heading');
$deals_icon       = $field('sector_focus_deals_icon');
$deals_label      = $field('sector_focus_deals_label');
$deals_min_amount = $field('sector_focus_deals_min_amount');
$deals_min_unit   = $field('sector_focus_deals_min_unit');
$deals_max_amount = $field('sector_focus_deals_max_amount');
$deals_max_unit   = $field('sector_focus_deals_max_unit');
$intro            = $field('sector_focus_intro');
$industries_label = $field('sector_focus_industries_label');
$industries       = $field('sector_focus_industries');
$outro            = $field('sector_focus_outro');

$icon_url = is_array($deals_icon) ? ($deals_icon['url'] ?? '') : (string) $deals_icon;
$icon_alt = is_array($deals_icon) ? ($deals_icon['alt'] ?? '') : '';
?>
<section class="sector-focus-section section" data-reveal>
    <div class="sector-focus-section__inner container">

        <?php if ($heading) : ?>
            <h2 class="sector-focus-section__heading text-h2 text-weight-600 text-color-black">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <div class="sector-focus-section__cols">

            <aside class="sector-focus-section__left">
                <div class="sector-focus-deals">
                    <div class="sector-focus-deals__head">
                        <?php if ($icon_url) : ?>
                            <span class="sector-focus-deals__icon" aria-hidden="true">
                                <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>">
                            </span>
                        <?php endif; ?>
                        <?php if ($deals_label) : ?>
                            <span class="sector-focus-deals__label text-body-L text-color-black">
                                <?php echo esc_html($deals_label); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($deals_min_amount || $deals_max_amount) : ?>
                        <p class="sector-focus-deals__range text-color-primary">
                            <?php if ($deals_min_amount) : ?>
                                <span class="sector-focus-deals__amount text-h2"><?php echo esc_html($deals_min_amount); ?></span>
                            <?php endif; ?>
                            <?php if ($deals_min_unit) : ?>
                                <span class="sector-focus-deals__unit text-h4"><?php echo esc_html($deals_min_unit); ?></span>
                            <?php endif; ?>
                            <span class="sector-focus-deals__dash text-h2" aria-hidden="true">—</span>
                            <?php if ($deals_max_amount) : ?>
                                <span class="sector-focus-deals__amount text-h2"><?php echo esc_html($deals_max_amount); ?></span>
                            <?php endif; ?>
                            <?php if ($deals_max_unit) : ?>
                                <span class="sector-focus-deals__unit text-h4"><?php echo esc_html($deals_max_unit); ?></span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </aside>

            <div class="sector-focus-section__right">

                <?php if ($intro) : ?>
                    <div class="sector-focus-section__intro text-body-L text-color-primary-gray">
                        <?php echo wp_kses_post(wpautop($intro)); ?>
                    </div>
                <?php endif; ?>

                <div class="sector-focus-section__industries">
                    <?php if ($industries_label) : ?>
                        <p class="sector-focus-section__industries-label text-h4 text-color-black">
                            <?php echo esc_html($industries_label); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (is_array($industries) && $industries) : ?>
                        <ul class="sector-focus-section__industries-list">
                            <?php foreach ($industries as $row) :
                                $name = is_array($row) ? ($row['industry_name'] ?? '') : '';
                                if (!$name) { continue; }
                                ?>
                                <li class="sector-focus-industry">
                                    <span class="sector-focus-industry__name text-h4 text-weight-500 text-color-black">
                                        <?php echo esc_html($name); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($outro) : ?>
                        <div class="sector-focus-section__outro text-body-L text-color-primary-gray">
                            <?php echo wp_kses_post(wpautop($outro)); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</section>
