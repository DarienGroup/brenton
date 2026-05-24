<?php
/**
 * Generic image + text two-column block.
 *
 * Image on the left, heading + body (+ optional CTA) on the right at lg+.
 * Stacks on mobile with the image moving below the content.
 *
 * Args ($args):
 *   image         mixed   ACF image array | attachment ID | URL string
 *   image_alt     string  Optional alt override (else derived from $image)
 *   heading       string
 *   heading_class string  Type-scale utility class for the heading. Default 'text-h3'.
 *   body          string  Rich text (wpautop applied)
 *   button        array   Optional CTA: ['label' => ..., 'href' => ..., 'target' => '', 'variant' => 'cyan']
 *   reverse       bool    If true, image moves to the right at lg+
 *   class         string  Extra class names on the wrapper
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'image'         => null,
    'image_alt'     => null,
    'heading'       => '',
    'heading_class' => 'text-h3',
    'body'          => '',
    'button'        => null,
    'reverse'       => false,
    'class'         => '',
]);

$image = $args['image'];
if (is_array($image)) {
    $img_url = $image['url'] ?? '';
    $img_alt = $image['alt'] ?? '';
} elseif (is_numeric($image)) {
    $img_url = wp_get_attachment_image_url((int) $image, 'large') ?: '';
    $img_alt = get_post_meta((int) $image, '_wp_attachment_image_alt', true) ?: '';
} else {
    $img_url = (string) $image;
    $img_alt = '';
}
if ($args['image_alt'] !== null) {
    $img_alt = (string) $args['image_alt'];
}

$classes = ['media-text'];
if ($args['reverse']) {
    $classes[] = 'media-text--reverse';
}
if ($args['class']) {
    $classes[] = $args['class'];
}

$button = $args['button'];
if (is_array($button)) {
    $button = wp_parse_args($button, [
        'label'   => '',
        'href'    => '',
        'target'  => '',
        'variant' => 'cyan',
    ]);
}
?>
<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">

    <?php if ($img_url) : ?>
        <div class="media-text__media">
            <img class="media-text__image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy">
        </div>
    <?php endif; ?>

    <div class="media-text__content">
        <?php if ($args['heading']) : ?>
            <h2 class="media-text__heading <?php echo esc_attr($args['heading_class']); ?> text-weight-600 text-color-black">
                <?php echo esc_html($args['heading']); ?>
            </h2>
        <?php endif; ?>

        <?php if ($args['body']) : ?>
            <div class="media-text__body text-body-L">
                <?php echo wp_kses_post(wpautop($args['body'])); ?>
            </div>
        <?php endif; ?>

        <?php if (is_array($button) && $button['href'] && $button['label']) : ?>
            <div class="media-text__cta">
                <?php brentonpoint_button([
                    'label'   => $button['label'],
                    'href'    => $button['href'],
                    'target'  => $button['target'],
                    'variant' => $button['variant'],
                    'class'   => 'media-text__button',
                ]); ?>
            </div>
        <?php endif; ?>
    </div>

</div>
