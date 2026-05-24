<?php
/**
 * Firm page — "About Brenton Point" section.
 *
 * Top-level section heading above a two-column image + text block
 * rendered via the shared media-text component.
 *
 * ACF fields (group_6a1359bfb5719):
 *   firm_about_section_heading (text)
 *   firm_about_heading         (text)
 *   firm_about_image           (image, array)
 *   firm_about_body            (wysiwyg)
 */
defined('ABSPATH') || exit;

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$section_heading = $field('firm_about_section_heading') ?: __('About Brenton Point', 'brentonpoint');
$block_heading   = $field('firm_about_heading') ?: __('Brenton Point Capital Partners', 'brentonpoint');
$image           = $field('firm_about_image');
$body            = $field('firm_about_body');
?>
<section class="firm-about-section" data-reveal>
    <div class="firm-about-section__inner container">

        <?php if ($section_heading) : ?>
            <h2 class="firm-about-section__heading text-h2 text-weight-700 text-color-black">
                <?php echo esc_html($section_heading); ?>
            </h2>
        <?php endif; ?>

        <?php get_template_part('template-parts/components/media-text', null, [
            'image'   => $image,
            'heading' => $block_heading,
            'body'    => $body,
            'class'   => 'firm-about-section__media-text',
        ]); ?>

    </div>
</section>
