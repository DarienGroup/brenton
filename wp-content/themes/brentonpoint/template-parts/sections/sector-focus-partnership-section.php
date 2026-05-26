<?php
/**
 * Sector Focus page — "A Partnership, Not a Transaction" section.
 *
 * Two-column header (heading left, intro right) above a 2×2 grid of cards.
 * Each card is white, with a taupe rule above the card and an auto-numbered
 * index (01, 02, …) generated from the loop position.
 *
 * ACF fields:
 *   sector_focus_partnership_heading  (text)
 *   sector_focus_partnership_intro    (textarea)
 *   sector_focus_partnership_cards    (repeater) →
 *       card_title (text)
 *       card_body  (textarea)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading = $field('sector_focus_partnership_heading');
$intro   = $field('sector_focus_partnership_intro');

$cards_raw = $field('sector_focus_partnership_cards') ?: [];
$cards = [];
foreach ((array) $cards_raw as $row) {
    $title = trim((string) ($row['card_title'] ?? ''));
    $body  = trim((string) ($row['card_body'] ?? ''));
    if (!$title && !$body) { continue; }
    $cards[] = ['title' => $title, 'body' => $body];
}
?>
<section class="sector-partnership-section section" data-reveal>
    <div class="sector-partnership-section__inner container">

        <div class="sector-partnership-section__head">
            <?php if ($heading) : ?>
                <h2 class="sector-partnership-section__heading text-h2 text-weight-600 text-color-black">
                    <?php echo esc_html($heading); ?>
                </h2>
            <?php endif; ?>

            <?php if ($intro) : ?>
                <div class="sector-partnership-section__intro text-body-L text-color-black">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($cards) : ?>
            <ul class="sector-partnership-section__cards">
                <?php foreach ($cards as $i => $card) : ?>
                    <li class="sector-partnership-card">
                        <span class="sector-partnership-card__index text-body-L text-color-taupe">
                            <?php echo esc_html(sprintf('%02d', $i + 1)); ?>
                        </span>

                        <?php if ($card['title']) : ?>
                            <h3 class="sector-partnership-card__title text-h4 text-weight-600 text-color-black">
                                <?php echo esc_html($card['title']); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($card['body']) : ?>
                            <div class="sector-partnership-card__body text-body-L text-color-primary-gray">
                                <?php echo wp_kses_post(wpautop($card['body'])); ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>
