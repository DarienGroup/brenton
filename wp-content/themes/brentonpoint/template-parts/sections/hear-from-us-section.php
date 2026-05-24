<?php
/**
 * "Hear From Us" section.
 *
 * A grid of video tiles, each driven by an ACF row. Two video sources are
 * supported:
 *   - youtube : a YouTube URL or ID. Plays inline by swapping the cover for
 *               a YouTube iframe on click. Shows a "Watch on YouTube" badge.
 *   - upload  : a self-hosted mp4 (or other browser-playable file). Plays
 *               inline by swapping the cover for a native <video> element.
 *
 * Both types share the same custom cover image + custom play button overlay.
 *
 * ACF fields:
 *   hear_from_us_heading  (text)
 *   hear_from_us_items    (repeater) →
 *       video_type    (radio: 'youtube' | 'upload')
 *       cover_image   (image)
 *       title         (text)
 *       youtube_url   (text)   — when video_type = 'youtube'
 *       video_file    (file)   — when video_type = 'upload'
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

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

$heading = $field('hear_from_us_heading') ?: __('Hear From Us', 'brentonpoint');

$items_raw = (array) ($field('hear_from_us_items') ?: []);
$items = [];
foreach ($items_raw as $row) {
    $type = ($row['video_type'] ?? 'youtube') === 'upload' ? 'upload' : 'youtube';

    $cover = $row['cover_image'] ?? null;
    $cover_url = $image_url($cover);
    $cover_alt = $image_alt($cover);

    $title = trim((string) ($row['title'] ?? ''));

    if ($type === 'youtube') {
        $yt_id = $youtube_id($row['youtube_url'] ?? '');
        if (!$yt_id) {
            continue;
        }
        $items[] = [
            'type'       => 'youtube',
            'cover_url'  => $cover_url,
            'cover_alt'  => $cover_alt,
            'title'      => $title,
            'youtube_id' => $yt_id,
        ];
    } else {
        $file = $row['video_file'] ?? null;
        $file_url = is_array($file) ? ($file['url'] ?? '') : (string) $file;
        $file_mime = is_array($file) ? ($file['mime_type'] ?? 'video/mp4') : 'video/mp4';
        if (!$file_url) {
            continue;
        }
        $items[] = [
            'type'      => 'upload',
            'cover_url' => $cover_url,
            'cover_alt' => $cover_alt,
            'title'     => $title,
            'file_url'  => $file_url,
            'file_mime' => $file_mime,
        ];
    }
}

if (!$items) {
    return;
}

$section_classes = ['hear-from-us-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'hear-from-us-section--home';
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>" data-reveal>
    <div class="hear-from-us-section__inner container">

        <?php if ($heading) : ?>
            <h2 class="hear-from-us-section__heading text-h2 text-weight-600 text-color-black">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <ul class="hear-from-us-section__list">
            <?php foreach ($items as $item) : ?>
                <?php get_template_part('template-parts/components/video-card', null, array_merge($item, ['wrapper' => 'li'])); ?>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
