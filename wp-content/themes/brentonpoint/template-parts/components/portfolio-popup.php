<?php
/**
 * Portfolio popup — detail modal for a single partner-company card.
 *
 * Rendered as a native <dialog> sibling of its trigger button so the open/close
 * JS can reach it via `opener.closest('[data-portfolio-card]').querySelector(...)`.
 *
 * Args ($args):
 *   post_id   int   our_portfolio post ID (required)
 *
 * Data sources (all ACF on the our_portfolio CPT):
 *   portfolio_image / portfolio_logo  → media column
 *   portfolio_mission                 → mission paragraph
 *   portfolio_acquisition_year        → stats cell
 *   portfolio_addon_acquisitions      → stats cell (hidden when 0/empty)
 *   portfolio_website_url / _label    → CTA button
 *   category_portfolio taxonomy       → status pill (Active / Realized)
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'post_id' => 0,
]);

$post_id = (int) $args['post_id'];
if (!$post_id) {
    return;
}

$title = get_the_title($post_id);

$attachment_id = static function ($value): int {
    if (is_array($value)) {
        return (int) ($value['ID'] ?? 0);
    }
    return (int) $value;
};

$image_id   = function_exists('get_field') ? $attachment_id(get_field('portfolio_image', $post_id)) : 0;
$logo_id    = function_exists('get_field') ? $attachment_id(get_field('portfolio_logo', $post_id)) : 0;
$mission    = function_exists('get_field') ? (string) get_field('portfolio_mission', $post_id) : '';
$year       = function_exists('get_field') ? (int) get_field('portfolio_acquisition_year', $post_id) : 0;
$addons     = function_exists('get_field') ? (int) get_field('portfolio_addon_acquisitions', $post_id) : 0;
$site_url   = function_exists('get_field') ? (string) get_field('portfolio_website_url', $post_id) : '';
$site_label = function_exists('get_field') ? (string) get_field('portfolio_website_label', $post_id) : '';

// Status — first matching `category_portfolio` term that's either Active or Realized.
$status_label = '';
$status_slug  = '';
$terms = get_the_terms($post_id, 'category_portfolio');
if (is_array($terms)) {
    foreach ($terms as $term) {
        if (in_array($term->slug, ['active', 'realized'], true)) {
            $status_slug  = $term->slug;
            $status_label = $term->name;
            break;
        }
    }
}

$image_html = $image_id
    ? wp_get_attachment_image($image_id, 'large', false, [
        'class'   => 'portfolio-popup__image',
        'loading' => 'lazy',
        'alt'     => $title,
    ])
    : '';

$logo_html = $logo_id
    ? wp_get_attachment_image($logo_id, 'medium', false, [
        'class'   => 'portfolio-popup__logo',
        'loading' => 'lazy',
        'alt'     => $title,
    ])
    : '';

$dialog_id = sprintf('portfolio-popup-%d', $post_id);
?>
<dialog class="portfolio-popup" id="<?php echo esc_attr($dialog_id); ?>" aria-labelledby="<?php echo esc_attr($dialog_id . '-title'); ?>">
    <div class="portfolio-popup__panel">

        <button type="button" class="portfolio-popup__close" aria-label="<?php esc_attr_e('Close', 'brentonpoint'); ?>" data-modal-close>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
            </svg>
        </button>

        <div class="portfolio-popup__layout">

            <div class="portfolio-popup__media">
                <?php if ($image_html) : ?>
                    <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php else : ?>
                    <span class="portfolio-popup__image portfolio-popup__image--placeholder" aria-hidden="true"></span>
                <?php endif; ?>

                <?php if ($logo_html) : ?>
                    <div class="portfolio-popup__logo-wrap">
                        <?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="portfolio-popup__body">
                <h2 id="<?php echo esc_attr($dialog_id . '-title'); ?>" class="portfolio-popup__title text-h2 text-weight-600 text-color-black">
                    <?php echo esc_html($title); ?>
                </h2>

                <?php
                $has_year   = $year > 0;
                $has_status = $status_label !== '';
                $has_addons = $addons > 0;
                $has_cta    = $site_url !== '';
                $has_stats  = $has_year || $has_status || $has_addons || $has_cta;
                if ($mission || $has_stats) :
                ?>
                <div class="portfolio-popup__details">

                    <?php if ($mission) : ?>
                        <div class="portfolio-popup__mission">
                            <p class="portfolio-popup__mission-label text-h4 text-weight-700 text-color-black">
                                <?php esc_html_e('Mission:', 'brentonpoint'); ?>
                            </p>
                            <div class="portfolio-popup__mission-body text-body-M text-color-primary-gray">
                                <?php echo wp_kses_post(wpautop($mission)); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($has_stats) : ?>
                        <div class="portfolio-popup__stats">

                            <?php if ($has_year || $has_status || $has_addons) : ?>
                                <div class="portfolio-popup__stat-group">
                                    <?php if ($has_year) : ?>
                                        <div class="portfolio-popup__stat">
                                            <p class="portfolio-popup__stat-label text-body-S text-color-primary-gray">
                                                <?php esc_html_e('Acquisition year:', 'brentonpoint'); ?>
                                            </p>
                                            <p class="portfolio-popup__stat-value text-body-M text-weight-700 text-color-black">
                                                <?php echo esc_html((string) $year); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($has_status) : ?>
                                        <div class="portfolio-popup__stat">
                                            <p class="portfolio-popup__stat-label text-body-S text-color-primary-gray">
                                                <?php esc_html_e('Status:', 'brentonpoint'); ?>
                                            </p>
                                            <p class="portfolio-popup__stat-value portfolio-popup__stat-value--status portfolio-popup__stat-value--<?php echo esc_attr($status_slug); ?> text-body-M text-weight-700">
                                                <span class="portfolio-popup__status-dot" aria-hidden="true"></span>
                                                <?php echo esc_html($status_label); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($has_addons) : ?>
                                        <div class="portfolio-popup__stat">
                                            <p class="portfolio-popup__stat-label text-body-S text-color-primary-gray">
                                                <?php esc_html_e('Add-on acquisitions:', 'brentonpoint'); ?>
                                            </p>
                                            <p class="portfolio-popup__stat-value text-body-M text-weight-700 text-color-black">
                                                <?php echo esc_html((string) $addons); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($has_cta) : ?>
                                <div class="portfolio-popup__cta">
                                    <?php
                                    // Visible CTA text is the generic "Visit Website"; the
                                    // company-specific label lives in aria-label so screen
                                    // readers still know which site is being opened.
                                    $cta_aria = $site_label
                                        ? sprintf(__('Visit %s website (opens in new tab)', 'brentonpoint'), $site_label)
                                        : sprintf(__('Visit %s website (opens in new tab)', 'brentonpoint'), $title);
                                    brentonpoint_button([
                                        'label'   => __('Visit Website', 'brentonpoint'),
                                        'href'    => $site_url,
                                        'target'  => '_blank',
                                        'variant' => 'deep-teal',
                                        'class'   => 'btn--short',
                                        'attrs'   => [
                                            'aria-label' => $cta_aria,
                                        ],
                                    ]);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</dialog>
