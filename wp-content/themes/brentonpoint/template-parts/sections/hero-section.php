<?php
defined('ABSPATH') || exit;

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
};

$heading    = $field('hero_heading');
$video      = $field('hero_video');
$poster     = $field('hero_poster');

$video_url  = is_array($video)  ? ($video['url']  ?? '') : (string) $video;
$video_mime = is_array($video)  ? ($video['mime_type'] ?? 'video/mp4') : 'video/mp4';
$poster_url = is_array($poster) ? ($poster['url'] ?? '') : (string) $poster;
$poster_alt = is_array($poster) ? ($poster['alt'] ?? '') : '';
?>
<section class="hero-section" data-reveal>
    <div class="hero-section__media" aria-hidden="true">
        <?php if ($video_url) : ?>
            <video class="hero-section__video"
                   autoplay muted loop playsinline preload="auto"
                   <?php if ($poster_url) : ?>poster="<?php echo esc_url($poster_url); ?>"<?php endif; ?>>
                <source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_mime); ?>">
            </video>
        <?php elseif ($poster_url) : ?>
            <img class="hero-section__video" src="<?php echo esc_url($poster_url); ?>" alt="<?php echo esc_attr($poster_alt); ?>">
        <?php endif; ?>

        <div class="hero-section__overlay"></div>

        <div class="hero-section__video-frame">
            <svg class="hero-section__ellipse" viewBox="0 0 515 393" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false" preserveAspectRatio="xMidYMid meet">
                <path d="M514.643 194.028C504.998 158.034 487.781 124.514 464.141 95.7092C440.501 66.9043 410.985 43.4782 377.564 26.997C344.144 10.5159 307.59 1.35963 270.346 0.140416C233.103 -1.0788 196.029 5.66713 161.602 19.9272C127.175 34.1872 96.1894 55.6328 70.7165 82.8299C45.2436 110.027 25.8705 142.349 13.8927 177.635C1.91477 212.92 -2.39192 250.357 1.26052 287.441C4.91299 324.525 16.4404 360.401 35.0721 392.672L76.8667 368.542C61.6699 342.22 52.2676 312.958 49.2885 282.71C46.3094 252.463 49.8221 221.928 59.5918 193.148C69.3616 164.367 85.1631 138.004 105.94 115.82C126.717 93.6371 151.99 76.1451 180.07 64.5139C208.15 52.8828 238.39 47.3805 268.767 48.3749C299.145 49.3694 328.96 56.8377 356.219 70.2804C383.478 83.7232 407.554 102.831 426.835 126.325C446.117 149.82 460.16 177.16 468.027 206.518L514.643 194.028Z" fill="url(#hero-ellipse-gradient)"/>
                <defs>
                    <linearGradient id="hero-ellipse-gradient" x1="16.6532" y1="-198.553" x2="295.305" y2="249.884" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#65C6EE"/>
                        <stop offset="1" stop-color="#65C6EE" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <?php if ($heading) : ?>
        <div class="hero-section__inner container">
            <h1 class="hero-section__heading text-h1 text-weight-500 text-color-white">
                <?php echo wp_kses_post($heading); ?>
            </h1>
        </div>
    <?php endif; ?>
</section>
