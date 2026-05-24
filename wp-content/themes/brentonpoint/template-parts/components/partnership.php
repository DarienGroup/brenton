<?php
defined('ABSPATH') || exit;

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
};

$image    = $field('partnership_image');
$img_url  = is_array($image) ? ($image['url'] ?? '') : (is_numeric($image) ? (wp_get_attachment_image_url((int) $image, 'large') ?: '') : (string) $image);
$img_alt  = is_array($image) ? ($image['alt'] ?? '') : '';

$heading  = $field('partnership_heading') ?: __('Solution-Oriented Partnerships', 'brentonpoint');
$body     = $field('partnership_body');

$btn_label = $field('partnership_button_label') ?: __('Contact Us', 'brentonpoint');
$btn_url   = $field('partnership_button_url');

if (is_array($btn_url)) {
    $btn_href   = $btn_url['url']   ?? '';
    $btn_target = $btn_url['target'] ?? '';
    $btn_title  = $btn_url['title']  ?? $btn_label;
    if ($btn_title) {
        $btn_label = $btn_title;
    }
} else {
    $btn_href   = (string) ($btn_url ?: '#contact');
    $btn_target = '';
}
?>
<div class="partnership">

    <?php if ($img_url) : ?>
        <div class="partnership__media">
            <img class="partnership__image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy">
        </div>
    <?php endif; ?>

    <div class="partnership__content">
        <?php if ($heading) : ?>
            <h2 class="partnership__heading text-h2 text-weight-600 text-color-black">
                <?php echo esc_html($heading); ?>
            </h2>
        <?php endif; ?>

        <?php if ($body) : ?>
            <div class="partnership__body text-body-L">
                <?php echo wp_kses_post(wpautop($body)); ?>
            </div>
        <?php endif; ?>

        <?php if ($btn_href) : ?>
            <div class="partnership__cta">
                <?php brentonpoint_button([
                    'label'   => $btn_label,
                    'href'    => $btn_href,
                    'target'  => $btn_target,
                    'variant' => 'cyan',
                    'class'   => 'partnership__button',
                ]); ?>
            </div>
        <?php endif; ?>
    </div>

</div>
