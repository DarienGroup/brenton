<?php
/**
 * Portfolio card — single partner-company tile shown in the portfolio grid.
 *
 * Args ($args):
 *   post_id   int   our_portfolio post ID (required)
 *
 * Data sources:
 *   ACF portfolio_image            → background photo (large size)
 *   ACF portfolio_logo             → logo shown bottom-left over the photo
 *   ACF portfolio_website_url      → opens externally on detail (next iteration)
 *   ACF portfolio_acquisition_year → meta line (next iteration)
 *   ACF portfolio_mission          → detail body (next iteration)
 *   category_portfolio taxonomy    → Active / Realized status, drives client-side
 *                                    filtering via the WP post_class() output
 *
 * The post title is intentionally not rendered — the logo is the visual label.
 * Rendered as an <article> with post_class() so the existing initPortfolioTabs()
 * filter ( .portfolio_block article.category_portfolio-active / -realized )
 * works without further changes.
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

$image_id = function_exists('get_field') ? $attachment_id(get_field('portfolio_image', $post_id)) : 0;
$image_attr = [
    'class'   => 'portfolio-card__image',
    'loading' => 'lazy',
    'alt'     => $title,
];
if ($image_id && function_exists('brentonpoint_attachment_focal_point')) {
    $focal = brentonpoint_attachment_focal_point((int) $image_id);
    // size = side of the focal square as % of the image's smaller dimension.
    // zoom = how much we scale the rendered image so that square fills the
    // viewport's smaller dimension. size=100 → zoom=1 (no extra zoom).
    $zoom = $focal['size'] > 0 ? 100 / $focal['size'] : 1;
    $image_attr['style'] = sprintf(
        '--focal-x: %d%%; --focal-y: %d%%; --focal-zoom: %s;',
        $focal['x'],
        $focal['y'],
        rtrim(rtrim(number_format($zoom, 4, '.', ''), '0'), '.')
    );
}
$image_html = $image_id ? wp_get_attachment_image($image_id, 'large', false, $image_attr) : '';

$logo_id   = function_exists('get_field') ? $attachment_id(get_field('portfolio_logo', $post_id)) : 0;
$logo_html = $logo_id
    ? wp_get_attachment_image($logo_id, 'medium', false, [
        'class'   => 'portfolio-card__logo',
        'loading' => 'lazy',
        'alt'     => $title,
    ])
    : '';
?>
<article
    id="post-<?php echo esc_attr($post_id); ?>"
    <?php post_class('portfolio-card', $post_id); ?>
    data-portfolio-card
>
    <div class="portfolio-card__media">
        <?php if ($image_html) : ?>
            <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php else : ?>
            <span class="portfolio-card__image portfolio-card__image--placeholder" aria-hidden="true"></span>
        <?php endif; ?>

        <div class="portfolio-card__footer">
            <div class="portfolio-card__logo-wrap">
                <?php if ($logo_html) : ?>
                    <?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php endif; ?>
            </div>

            <button
                type="button"
                class="portfolio-card__open"
                aria-label="<?php echo esc_attr(sprintf(__('Open details: %s', 'brentonpoint'), $title)); ?>"
                aria-haspopup="dialog"
                aria-controls="<?php echo esc_attr('portfolio-popup-' . $post_id); ?>"
                data-portfolio-popup-open
            >
                <span aria-hidden="true">+</span>
            </button>
        </div>
    </div>

    <?php get_template_part('template-parts/components/portfolio-popup', null, [
        'post_id' => $post_id,
    ]); ?>
</article>
