<?php
/**
 * "Why Brenton Point" section.
 *
 * Two-column layout (1440 baseline): left = 666px (heading + body + CTA),
 * right = 646px (ocean/ellipse visual with ACF-driven bullets pinned to
 * the outer ellipse edge).
 *
 * ACF fields:
 *   why_heading        (text)
 *   why_body           (textarea / wysiwyg)
 *   why_button_label   (text)
 *   why_button_url     (link)
 *   why_visual_image   (image) — clean BG of right block (no bullets baked in)
 *   why_bullets        (repeater, max 3) →
 *       bullet_icon  (image, SVG/PNG)
 *       bullet_label (text)
 *       bullet_text  (textarea) — shown under the pill on hover/active
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$image_url = static function ($image) {
    if (is_array($image))   { return $image['url'] ?? ''; }
    if (is_numeric($image)) { return wp_get_attachment_image_url((int) $image, 'large') ?: ''; }
    return (string) $image;
};

$image_alt = static function ($image) {
    if (is_array($image))   { return $image['alt'] ?? ''; }
    if (is_numeric($image)) { return get_post_meta((int) $image, '_wp_attachment_image_alt', true) ?: ''; }
    return '';
};

$heading = $field('why_heading') ?: __('Why Brenton Point', 'brentonpoint');
$body    = $field('why_body');

$btn_label = $field('why_button_label') ?: __('Learn More', 'brentonpoint');
$btn_url   = $field('why_button_url');
if (is_array($btn_url)) {
    $btn_href   = $btn_url['url']    ?? '';
    $btn_target = $btn_url['target'] ?? '';
    if (!empty($btn_url['title'])) {
        $btn_label = $btn_url['title'];
    }
} else {
    $btn_href   = (string) ($btn_url ?: '#');
    $btn_target = '';
}

// Right-block visual. Falls back to the design mockup that ships with the
// theme; production should override via ACF with a clean (no-bullet) export.
$visual_image = $field('why_visual_image');
$visual_url   = $image_url($visual_image);
$visual_alt   = $image_alt($visual_image);
if (!$visual_url) {
    $visual_url = get_template_directory_uri() . '/images/Section/why-brentonpoint-bg.webp';
    $visual_alt = '';
}

// Bullets. Repeater is capped at 3 in ACF — extra rows are silently dropped.
$bullets_raw = function_exists('get_field') ? (get_field('why_bullets') ?: []) : [];
$bullets = [];
foreach ((array) $bullets_raw as $row) {
    if (count($bullets) >= 3) { break; }
    $icon_url = $image_url($row['bullet_icon'] ?? null);
    $label    = trim((string) ($row['bullet_label'] ?? ''));
    $text     = trim((string) ($row['bullet_text'] ?? ''));
    if (!$label && !$icon_url && !$text) { continue; }
    $bullets[] = ['icon' => $icon_url, 'label' => $label, 'text' => $text];
}

// Bullet anchor points in IMAGE pixel coordinates (the background image
// is pinned at its native 664×680 size, anchored to the visual tile's
// top-right — see _why-brentonpoint-section.scss). The horizontal
// positions are measured from the image's left edge; the SCSS then
// converts them to a `left: calc(100% - $img-w + x)` so the bullets
// follow the image as the tile crops on the left.
//
// Layout per design:
//   1 bullet  → centered on the rightmost point of the arc
//   2 bullets → top + bottom (128px inset from image top/bottom)
//   3 bullets → top + middle + bottom
//
// Source percentages mapped to the 664×680 image:
//   45% / 55.5% / 45.5% horizontally, 128px / 50% / 128px-from-bottom
//   vertically.
$position_map = [
    1 => [
        ['x' => '369px', 'y' => '340px'],
    ],
    2 => [
        ['x' => '299px', 'y' => '128px'],
        ['x' => '302px', 'y' => '552px'],
    ],
    3 => [
        ['x' => '299px', 'y' => '128px'],
        ['x' => '369px', 'y' => '340px'],
        ['x' => '302px', 'y' => '552px'],
    ],
];
$positions = $position_map[count($bullets)] ?? [];

$section_classes = ['why-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'why-section--home';
}

// Inlined pin figure (white dot + partial cyan ring + gradient connector line).
// Inlined so the dot/ring can be animated in CSS — that's why we don't render
// it as a CSS pseudo-element. The gradient `id` is rewritten per bullet so
// multiple identical defs don't collide in the DOM.
$pin_svg_template = file_get_contents(get_template_directory() . '/images/Frame 2147236551.svg') ?: '';

// Lighthouse decoration — appears centred over the pin's dot on hover.
$lighthouse_svg = file_get_contents(get_template_directory() . '/images/brenton-lighthouse-decoration.svg') ?: '';
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>" data-reveal>
    <div class="why-section__inner container">
        <div class="why-section__grid">

            <div class="why-section__content">
                <?php if ($heading) : ?>
                    <h2 class="why-section__heading text-h2 text-weight-600 text-color-black">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($body) : ?>
                    <div class="why-section__body text-body-L">
                        <?php echo wp_kses_post(wpautop($body)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($btn_href) : ?>
                    <div class="why-section__cta">
                        <?php brentonpoint_button([
                            'label'   => $btn_label,
                            'href'    => $btn_href,
                            'target'  => $btn_target,
                            'variant' => 'cyan-outline',
                            'class'   => 'why-section__button',
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="why-section__visual"
                 <?php if ($visual_url) : ?>style="background-image: url('<?php echo esc_url($visual_url); ?>');"<?php endif; ?>
                 role="img"
                 aria-label="<?php echo esc_attr($visual_alt); ?>">

                <?php if ($bullets) : ?>
                    <ul class="why-bullets" aria-label="<?php esc_attr_e('Highlights', 'brentonpoint'); ?>">
                        <?php foreach ($bullets as $i => $bullet) :
                            $pos = $positions[$i] ?? ['x' => '369px', 'y' => '340px'];
                            $pin_svg = str_replace(
                                'paint0_linear_2388_2245',
                                'why-pin-grad-' . $i,
                                $pin_svg_template
                            );
                        ?>
                            <li class="why-bullets__item"
                                style="--bullet-x: <?php echo esc_attr($pos['x']); ?>; --bullet-y: <?php echo esc_attr($pos['y']); ?>;">
                                <span class="why-bullets__pin" aria-hidden="true">
                                    <?php echo $pin_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php if ($lighthouse_svg) : ?>
                                        <span class="why-bullets__decoration" aria-hidden="true">
                                            <?php echo $lighthouse_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </span>
                                    <?php endif; ?>
                                </span>
                                <span class="why-bullets__pill">
                                    <?php if (!empty($bullet['icon'])) : ?>
                                        <span class="why-bullets__icon" aria-hidden="true">
                                            <img src="<?php echo esc_url($bullet['icon']); ?>" alt="" loading="lazy">
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($bullet['label']) : ?>
                                        <span class="why-bullets__label text-h4 text-weight-600"><?php echo esc_html($bullet['label']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($bullet['text'])) : ?>
                                        <span class="why-bullets__text text-body-S"><?php echo esc_html($bullet['text']); ?></span>
                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
