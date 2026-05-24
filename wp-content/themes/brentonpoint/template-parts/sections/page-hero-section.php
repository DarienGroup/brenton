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
?>
<section class="page-hero" data-reveal>
    <div class="page-hero__media" aria-hidden="true">
        <?php if ($image_url) : ?>
            <img class="page-hero__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
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
