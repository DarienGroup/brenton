<?php
defined('ABSPATH') || exit;

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
};

$image         = $field('page_hero_image');
$heading       = $field('page_hero_heading');
$heading       = $heading !== null && $heading !== '' ? $heading : get_the_title();

$image_url     = is_array($image) ? ($image['url'] ?? '') : (string) $image;
$image_alt     = is_array($image) ? ($image['alt'] ?? '') : '';
$image_id      = is_array($image) ? (int) ($image['ID'] ?? 0) : 0;

$focal_style = '';
if ($image_id && function_exists('brentonpoint_attachment_focal_point')) {
    $focal       = brentonpoint_attachment_focal_point($image_id);
    $focal_style = sprintf('object-position: %d%% %d%%;', $focal['x'], $focal['y']);
}
?>
<section class="page-hero" data-reveal>
    <div class="page-hero__media" aria-hidden="true">
        <?php if ($image_url) : ?>
            <img
                class="page-hero__image"
                src="<?php echo esc_url($image_url); ?>"
                alt="<?php echo esc_attr($image_alt); ?>"
                <?php if ($focal_style) : ?>style="<?php echo esc_attr($focal_style); ?>"<?php endif; ?>
            >
        <?php endif; ?>
        <div class="page-hero__overlay"></div>
    </div>

    <?php if ($heading) : ?>
        <div class="page-hero__inner container">
            <h1 class="page-hero__heading text-h2 text-weight-500 text-color-white">
                <?php echo wp_kses_post($heading); ?>
            </h1>
        </div>
    <?php endif; ?>
</section>
