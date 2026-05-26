<?php
/**
 * Team member gallery — square photo with optional slider + lightbox.
 *
 * Args ($args):
 *   images    array   List of image entries, each with at least:
 *                     - url       Full-size image URL
 *                     - thumb_url URL of the version rendered inline (defaults to url)
 *                     - alt       Alt text
 *                     - width     Intrinsic width (optional)
 *                     - height    Intrinsic height (optional)
 *   name      string  Used only as a fallback alt label.
 *
 * Renders nothing when $images is empty. With one image only the photo is
 * rendered (no controls, no lightbox). With 2+ images the slider controls and
 * lightbox are added and JS wires them up.
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'images' => [],
    'name'   => '',
]);

$images = array_values(array_filter((array) $args['images'], static function ($img) {
    return !empty($img['url']);
}));

if (empty($images)) {
    return;
}

$name        = (string) $args['name'];
$multi       = count($images) > 1;
$count_label = (string) count($images);
?>
<div class="team-member-gallery" data-team-gallery>
    <div class="team-member-gallery__viewport">
        <ul class="team-member-gallery__track">
            <?php foreach ($images as $i => $img) :
                $alt     = $img['alt'] !== '' ? $img['alt'] : $name;
                $focal_x = isset($img['focal_x']) ? (int) $img['focal_x'] : 50;
                $focal_y = isset($img['focal_y']) ? (int) $img['focal_y'] : 50;
                // Only emit a style attribute when the focal point differs
                // from the CSS default (center), to keep markup clean.
                $focal_style = ($focal_x !== 50 || $focal_y !== 50)
                    ? sprintf('object-position: %d%% %d%%;', $focal_x, $focal_y)
                    : '';
            ?>
                <li
                    class="team-member-gallery__slide<?php echo $i === 0 ? ' is-active' : ''; ?>"
                    data-team-gallery-slide
                >
                    <img
                        class="team-member-gallery__image"
                        src="<?php echo esc_url($img['thumb_url'] ?: $img['url']); ?>"
                        data-full-src="<?php echo esc_url($img['url']); ?>"
                        alt="<?php echo esc_attr($alt); ?>"
                        loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"
                        <?php if ($focal_style) : ?>
                            style="<?php echo esc_attr($focal_style); ?>"
                        <?php endif; ?>
                        <?php if (!empty($img['width']) && !empty($img['height'])) : ?>
                            width="<?php echo esc_attr((string) $img['width']); ?>"
                            height="<?php echo esc_attr((string) $img['height']); ?>"
                        <?php endif; ?>
                    >
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($multi) : ?>
        <div class="team-member-gallery__controls">
            <div class="team-member-gallery__nav-group">
                <button
                    type="button"
                    class="team-member-gallery__nav team-member-gallery__nav--prev"
                    data-team-gallery-prev
                    aria-label="<?php esc_attr_e('Previous photo', 'brentonpoint'); ?>"
                >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <span class="team-member-gallery__counter text-body-M" aria-live="polite">
                    <span data-team-gallery-current>1</span><span class="team-member-gallery__counter-slash">/</span><?php echo esc_html($count_label); ?>
                </span>

                <button
                    type="button"
                    class="team-member-gallery__nav team-member-gallery__nav--next"
                    data-team-gallery-next
                    aria-label="<?php esc_attr_e('Next photo', 'brentonpoint'); ?>"
                >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M7.5 5L12.5 10L7.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <button
                type="button"
                class="team-member-gallery__expand"
                data-team-gallery-open
                aria-label="<?php esc_attr_e('View larger', 'brentonpoint'); ?>"
            >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M2 6V2H6M14 6V2H10M2 10V14H6M14 10V14H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($multi) : ?>
    <dialog
        class="team-member-gallery__lightbox"
        data-team-gallery-lightbox
        aria-label="<?php esc_attr_e('Photo viewer', 'brentonpoint'); ?>"
    >
        <div class="team-member-gallery__lightbox-stage">
            <img
                class="team-member-gallery__lightbox-image"
                data-team-gallery-lightbox-image
                src=""
                alt=""
            >

            <button
                type="button"
                class="team-member-gallery__lightbox-close"
                data-modal-close
                aria-label="<?php esc_attr_e('Close', 'brentonpoint'); ?>"
            >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M6 2H2V6M14 6V2H10M2 10V14H6M14 10V14H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="team-member-gallery__lightbox-controls">
                <button
                    type="button"
                    class="team-member-gallery__nav team-member-gallery__nav--prev"
                    data-team-gallery-prev
                    aria-label="<?php esc_attr_e('Previous photo', 'brentonpoint'); ?>"
                >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <span class="team-member-gallery__counter text-body-M" aria-live="polite">
                    <span data-team-gallery-current>1</span><span class="team-member-gallery__counter-slash">/</span><?php echo esc_html($count_label); ?>
                </span>

                <button
                    type="button"
                    class="team-member-gallery__nav team-member-gallery__nav--next"
                    data-team-gallery-next
                    aria-label="<?php esc_attr_e('Next photo', 'brentonpoint'); ?>"
                >
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M7.5 5L12.5 10L7.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </dialog>
    <?php endif; ?>
</div>
