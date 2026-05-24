<?php
/**
 * Firm page — featured video + intro section.
 *
 * Two-column layout on lg+: video card on the left, heading + speaker pill
 * + body copy on the right. Stacks under 992px with the video block dropping
 * below the text content per the design.
 *
 * ACF fields:
 *   firm_video_heading      (text)
 *   firm_video_speaker      (text)   — label shown inside the badge pill
 *   firm_video_body         (wysiwyg)
 *   firm_video_card_title   (text)   — caption rendered under the video card
 *   firm_video_type         (radio)  'youtube' | 'upload'
 *   firm_video_cover        (image)
 *   firm_video_youtube_url  (text)   — when type = 'youtube'
 *   firm_video_file         (file)   — when type = 'upload'
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$image_url = static function ($image) {
    if (is_array($image)) {
        return $image['url'] ?? '';
    }
    if (is_numeric($image)) {
        return wp_get_attachment_image_url((int) $image, 'large') ?: '';
    }
    return (string) $image;
};

$image_alt = static function ($image) {
    if (is_array($image)) {
        return $image['alt'] ?? '';
    }
    if (is_numeric($image)) {
        return get_post_meta((int) $image, '_wp_attachment_image_alt', true) ?: '';
    }
    return '';
};

$youtube_id = static function ($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }
    if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/|v/)|youtu\.be/)([A-Za-z0-9_-]{6,})~i', $value, $m)) {
        return $m[1];
    }
    if (preg_match('~^[A-Za-z0-9_-]{6,}$~', $value)) {
        return $value;
    }
    return '';
};

$heading = $field('firm_video_heading');
$speaker = $field('firm_video_speaker');
$body    = $field('firm_video_body');

$type       = $field('firm_video_type') === 'upload' ? 'upload' : 'youtube';
$cover      = $field('firm_video_cover');
$card_title = $field('firm_video_card_title');

$card_args = [
    'type'      => $type,
    'cover_url' => $image_url($cover),
    'cover_alt' => $image_alt($cover),
    'title'     => $card_title,
];

if ($type === 'youtube') {
    $card_args['youtube_id'] = $youtube_id($field('firm_video_youtube_url'));
} else {
    $file = $field('firm_video_file');
    $card_args['file_url']  = is_array($file) ? ($file['url'] ?? '') : (string) $file;
    $card_args['file_mime'] = is_array($file) ? ($file['mime_type'] ?? 'video/mp4') : 'video/mp4';
}

$has_video = ($type === 'youtube' && !empty($card_args['youtube_id']))
          || ($type === 'upload'  && !empty($card_args['file_url']));

if (!$has_video && !$heading && !$body) {
    return;
}

$lighthouse_icon = file_get_contents(get_template_directory() . '/images/lighthouse-icon-white.svg') ?: '';
?>
<section class="firm-video-section section" data-reveal>
    <div class="firm-video-section__inner container">
        <div class="firm-video-section__grid">

            <?php if ($has_video) : ?>
                <div class="firm-video-section__media">
                    <?php get_template_part('template-parts/components/video-card', null, $card_args); ?>
                </div>
            <?php endif; ?>

            <div class="firm-video-section__content">
                <?php if ($heading) : ?>
                    <h2 class="firm-video-section__heading text-h2 text-weight-600 text-color-white">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($speaker) : ?>
                    <span class="firm-video-section__badge">
                        <?php if ($lighthouse_icon) : ?>
                            <span class="firm-video-section__badge-icon" aria-hidden="true">
                                <?php echo $lighthouse_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </span>
                        <?php endif; ?>
                        <span class="firm-video-section__badge-label text-h4"><?php echo esc_html($speaker); ?></span>
                    </span>
                <?php endif; ?>

                <?php if ($body) : ?>
                    <div class="firm-video-section__body text-body-L">
                        <?php echo wp_kses_post(wpautop($body)); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
