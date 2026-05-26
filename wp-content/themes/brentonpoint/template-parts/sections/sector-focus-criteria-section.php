<?php
/**
 * Sector Focus page — investment-criteria-style section with a static image
 * background.
 *
 * Reuses the homepage Investment Criteria visual (full-bleed media with dark
 * overlay, heading anchored to the bottom, up to 3 cards) via the existing
 * .investment-section / .investment-card CSS. The video machinery is dropped
 * — no Vimeo, no mp4, no JS hook (`data-investment-video` is intentionally
 * omitted) — so only the static background image renders.
 *
 * ACF fields:
 *   sector_focus_criteria_heading  (text)
 *   sector_focus_criteria_image    (image)
 *   sector_focus_criteria_cards    (repeater, max 3) →
 *       card_title (text)
 *       card_body  (textarea)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading = $field('sector_focus_criteria_heading');

$image     = $field('sector_focus_criteria_image');
$image_url = is_array($image) ? ($image['url'] ?? '') : (string) $image;
$image_alt = is_array($image) ? ($image['alt'] ?? '') : '';

$cards_raw = $field('sector_focus_criteria_cards') ?: [];
$cards = [];
foreach ((array) $cards_raw as $row) {
    if (count($cards) >= 3) { break; }
    $title = trim((string) ($row['card_title'] ?? ''));
    $body  = trim((string) ($row['card_body'] ?? ''));
    if (!$title && !$body) { continue; }
    $cards[] = ['title' => $title, 'body' => $body];
}
?>
<section class="investment-section investment-section--static" data-reveal>
    <div class="investment-section__media" aria-hidden="true">
        <?php if ($image_url) : ?>
            <div class="investment-section__poster"
                 style="background-image: url('<?php echo esc_url($image_url); ?>');"
                 role="img"
                 aria-label="<?php echo esc_attr($image_alt); ?>"></div>
        <?php endif; ?>

        <div class="investment-section__overlay"></div>
    </div>

    <div class="investment-section__inner container">
        <?php if ($heading) : ?>
            <h2 class="investment-section__heading text-h2 text-weight-500 text-color-white">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <?php if ($cards) : ?>
            <ul class="investment-section__cards">
                <?php foreach ($cards as $card) : ?>
                    <li class="investment-card">
                        <?php if ($card['title']) : ?>
                            <h3 class="investment-card__title text-h3 text-weight-600 text-color-black">
                                <?php echo esc_html($card['title']); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($card['body']) : ?>
                            <div class="investment-card__body text-body-M">
                                <?php echo wp_kses_post(wpautop($card['body'])); ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
