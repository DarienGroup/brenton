<?php
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$section_classes = ['about-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'about-section--home';
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <div class="about-section__inner container">

        <?php get_template_part('template-parts/components/about-tabs', null, brentonpoint_about_tabs_args()); ?>

        <?php
        $partnership_btn_url = $field('partnership_button_url');
        if (is_array($partnership_btn_url)) {
            $partnership_btn_href   = $partnership_btn_url['url']    ?? '';
            $partnership_btn_target = $partnership_btn_url['target'] ?? '';
            $partnership_btn_label  = $partnership_btn_url['title']  ?: ($field('partnership_button_label') ?: __('Contact Us', 'brentonpoint'));
        } else {
            $partnership_btn_href   = (string) ($partnership_btn_url ?: '#contact');
            $partnership_btn_target = '';
            $partnership_btn_label  = $field('partnership_button_label') ?: __('Contact Us', 'brentonpoint');
        }

        get_template_part('template-parts/components/media-text', null, [
            'image'         => $field('partnership_image'),
            'heading'       => $field('partnership_heading') ?: __('Solution-Oriented Partnerships', 'brentonpoint'),
            'heading_class' => 'text-h2',
            'body'          => $field('partnership_body'),
            'button'        => $partnership_btn_href ? [
                'label'   => $partnership_btn_label,
                'href'    => $partnership_btn_href,
                'target'  => $partnership_btn_target,
                'variant' => 'cyan',
            ] : null,
            'class'         => 'about-section__media-text',
        ]);
        ?>

    </div>
</section>
