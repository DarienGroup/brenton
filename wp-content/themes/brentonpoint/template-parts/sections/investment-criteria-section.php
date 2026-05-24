<?php
/**
 * "Investment Criteria" section.
 *
 * Looping background video with dark overlay (like the hero), content anchored
 * to the bottom edge. Up to 3 cards rendered from an ACF repeater.
 *
 * Video source priority:
 *   1. Vimeo URL  → rendered as an <iframe> in background mode.
 *   2. Uploaded mp4 → rendered as <video>. Only requested if Vimeo fails to
 *      start (the fallback element is injected by JS on timeout/error so the
 *      mp4 is not downloaded when Vimeo loads correctly).
 *   3. Poster image → static <img>.
 *
 * ACF fields:
 *   investment_heading      (text)
 *   investment_video_url    (url)   — Vimeo link or numeric ID
 *   investment_video        (file, mp4) — fallback only
 *   investment_poster       (image) — fallback / video poster
 *   investment_cards        (repeater, max 3) →
 *       card_title (text)
 *       card_body  (textarea)
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$heading = $field('investment_heading') ?: __('Investment Criteria', 'brentonpoint');

$video_url_raw = trim((string) $field('investment_video_url'));
$vimeo_id      = '';
if ($video_url_raw !== '') {
    if (preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $video_url_raw, $m)) {
        $vimeo_id = $m[1];
    } elseif (ctype_digit($video_url_raw)) {
        $vimeo_id = $video_url_raw;
    }
}
$vimeo_embed = $vimeo_id
    ? sprintf(
        'https://player.vimeo.com/video/%s?background=1&loop=1&autoplay=1&muted=1&autopause=0',
        rawurlencode($vimeo_id)
    )
    : '';

$video      = $field('investment_video');
$poster     = $field('investment_poster');
$mp4_url    = is_array($video)  ? ($video['url']  ?? '') : (string) $video;
$mp4_mime   = is_array($video)  ? ($video['mime_type'] ?? 'video/mp4') : 'video/mp4';
$poster_url = is_array($poster) ? ($poster['url'] ?? '') : (string) $poster;
$poster_alt = is_array($poster) ? ($poster['alt'] ?? '') : '';

// Theme-asset fallback so the section never renders without a poster, even
// before the field is populated in production.
if (!$poster_url) {
    $poster_url = get_template_directory_uri() . '/images/investment-poster.webp';
    $poster_alt = '';
}

$cards_raw = function_exists('get_field') ? (get_field('investment_cards') ?: []) : [];
$cards = [];
foreach ((array) $cards_raw as $row) {
    if (count($cards) >= 3) { break; }
    $title = trim((string) ($row['card_title'] ?? ''));
    $body  = trim((string) ($row['card_body'] ?? ''));
    if (!$title && !$body) { continue; }
    $cards[] = ['title' => $title, 'body' => $body];
}

$section_classes = ['investment-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'investment-section--home';
}

$has_fallback = $mp4_url !== '';

// Top-right decorative ellipse — inlined so its gradient can animate without
// an extra HTTP request.
$ellipse_svg = file_get_contents(get_template_directory() . '/images/investment-ellipse.svg') ?: '';
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>"
         data-reveal
         data-investment-video
         <?php if ($has_fallback) : ?>
            data-mp4-url="<?php echo esc_url($mp4_url); ?>"
            data-mp4-mime="<?php echo esc_attr($mp4_mime); ?>"
         <?php endif; ?>
         <?php if ($poster_url) : ?>
            data-poster-url="<?php echo esc_url($poster_url); ?>"
            data-poster-alt="<?php echo esc_attr($poster_alt); ?>"
         <?php endif; ?>>
    <div class="investment-section__media" aria-hidden="true">
        <?php if ($poster_url) : ?>
            <div class="investment-section__poster"
                 style="background-image: url('<?php echo esc_url($poster_url); ?>');"
                 role="img"
                 aria-label="<?php echo esc_attr($poster_alt); ?>"></div>
        <?php endif; ?>

        <?php if ($vimeo_embed) : ?>
            <iframe class="investment-section__iframe"
                    src="<?php echo esc_url($vimeo_embed); ?>"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen
                    title="<?php esc_attr_e('Investment criteria background video', 'brentonpoint'); ?>"
                    loading="lazy"></iframe>
        <?php elseif ($mp4_url) : ?>
            <video class="investment-section__video"
                   autoplay muted loop playsinline preload="auto"
                   <?php if ($poster_url) : ?>poster="<?php echo esc_url($poster_url); ?>"<?php endif; ?>>
                <source src="<?php echo esc_url($mp4_url); ?>" type="<?php echo esc_attr($mp4_mime); ?>">
            </video>
        <?php elseif ($poster_url) : ?>
            <img class="investment-section__video" src="<?php echo esc_url($poster_url); ?>" alt="<?php echo esc_attr($poster_alt); ?>">
        <?php endif; ?>

        <div class="investment-section__overlay"></div>

        <?php if ($ellipse_svg) : ?>
            <div class="investment-section__ellipse" aria-hidden="true">
                <?php echo $ellipse_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        <?php endif; ?>
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
