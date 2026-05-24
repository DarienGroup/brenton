<?php
/**
 * Click-to-play video card (cover image + custom play overlay).
 *
 * Renders the .hear-from-us-card markup used by the homepage "Hear From
 * Us" grid and the firm page video block. The data-hear-from-us hook
 * binds the existing JS handler that swaps the cover for a YouTube
 * iframe or native <video> on click.
 *
 * Args ($args):
 *   type        string  'youtube' | 'upload'
 *   cover_url   string
 *   cover_alt   string
 *   title       string  Optional caption rendered below the player
 *   youtube_id  string  When type = 'youtube'
 *   file_url    string  When type = 'upload'
 *   file_mime   string  When type = 'upload', default 'video/mp4'
 *   wrapper     string  Outer element tag ('div' default, 'li' inside lists)
 *   class       string  Extra class names on the wrapper
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'type'       => 'youtube',
    'cover_url'  => '',
    'cover_alt'  => '',
    'title'      => '',
    'youtube_id' => '',
    'file_url'   => '',
    'file_mime'  => 'video/mp4',
    'wrapper'    => 'div',
    'class'      => '',
]);

$wrapper = in_array($args['wrapper'], ['div', 'li'], true) ? $args['wrapper'] : 'div';

if ($args['type'] === 'youtube' && !$args['youtube_id']) {
    return;
}
if ($args['type'] === 'upload' && !$args['file_url']) {
    return;
}

$classes = ['hear-from-us-card'];
if ($args['class']) {
    $classes[] = $args['class'];
}

$play_icon = file_get_contents(get_template_directory() . '/images/play-button.svg') ?: '';
$yt_badge  = file_get_contents(get_template_directory() . '/images/youtube-badge.svg') ?: '';
?>
<<?php echo $wrapper; ?> class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <button type="button"
            class="hear-from-us-card__player"
            data-hear-from-us
            data-video-type="<?php echo esc_attr($args['type']); ?>"
            <?php if ($args['type'] === 'youtube') : ?>
                data-youtube-id="<?php echo esc_attr($args['youtube_id']); ?>"
            <?php else : ?>
                data-video-url="<?php echo esc_url($args['file_url']); ?>"
                data-video-mime="<?php echo esc_attr($args['file_mime']); ?>"
            <?php endif; ?>
            aria-label="<?php echo esc_attr(sprintf(
                /* translators: %s: video title */
                __('Play video: %s', 'brentonpoint'),
                $args['title'] ?: __('Video', 'brentonpoint')
            )); ?>">
        <?php if ($args['cover_url']) : ?>
            <img class="hear-from-us-card__cover"
                 src="<?php echo esc_url($args['cover_url']); ?>"
                 alt="<?php echo esc_attr($args['cover_alt']); ?>"
                 loading="lazy">
        <?php endif; ?>

        <span class="hear-from-us-card__play" aria-hidden="true">
            <?php echo $play_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </span>

        <?php if ($args['type'] === 'youtube' && $yt_badge) : ?>
            <span class="hear-from-us-card__badge" aria-hidden="true">
                <?php echo $yt_badge; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </span>
        <?php endif; ?>
    </button>

    <?php if ($args['title']) : ?>
        <p class="hear-from-us-card__title text-body-M text-color-black">
            <?php echo esc_html($args['title']); ?>
        </p>
    <?php endif; ?>
</<?php echo $wrapper; ?>>
