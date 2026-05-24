<?php
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
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

$panels = [
    'mission' => [
        'tab'     => $field('about_mission_tab_label') ?: __('Mission Statement', 'brentonpoint'),
        'heading' => $field('about_mission_heading') ?: __('Mission Statement', 'brentonpoint'),
        'body'    => $field('about_mission_body'),
        'label'   => $field('about_mission_label') ?: __('Mission', 'brentonpoint'),
        'image'   => $field('about_mission_image'),
    ],
    'vision' => [
        'tab'     => $field('about_vision_tab_label') ?: __('Vision Statement', 'brentonpoint'),
        'heading' => $field('about_vision_heading') ?: __('Vision Statement', 'brentonpoint'),
        'body'    => $field('about_vision_body'),
        'label'   => $field('about_vision_label') ?: __('Vision', 'brentonpoint'),
        'image'   => $field('about_vision_image'),
    ],
];

// Desktop image used in the animated media panel at ≥568px. Falls back to
// the per-panel mission/vision images for backwards compatibility if the
// new dedicated field isn't populated yet.
$desktop_image = $field('about_desktop_image') ?: $field('about_mission_image') ?: $field('about_vision_image');
$img_url       = $image_url($desktop_image);
$img_alt       = $image_alt($desktop_image);

$section_classes = ['about-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'about-section--home';
}

// Inlined assets — kept in /images so designers can edit them, but
// embedded here so the badge + pin render without extra HTTP requests
// and so each instance gets unique <defs> ids if needed later.
$badge_icon     = file_get_contents(get_template_directory() . '/images/lighthouse-icon-white.svg') ?: '';
$pin_decoration = file_get_contents(get_template_directory() . '/images/about-line-decoration.svg') ?: '';
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>" data-reveal>
    <div class="about-section__inner container">
        <div class="about-tabs" data-about-tabs>

            <div class="about-tabs__nav" role="tablist" aria-label="<?php esc_attr_e('Mission and Vision', 'brentonpoint'); ?>">
                <?php $i = 0; foreach ($panels as $key => $panel) : $is_first = $i === 0; ?>
                    <button type="button"
                            class="about-tabs__tab<?php echo $is_first ? ' is-active' : ''; ?>"
                            role="tab"
                            id="about-tab-<?php echo esc_attr($key); ?>"
                            aria-controls="about-panel-<?php echo esc_attr($key); ?>"
                            aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
                            tabindex="<?php echo $is_first ? '0' : '-1'; ?>"
                            data-about-tab="<?php echo esc_attr($key); ?>">
                        <span class="about-tabs__tab-label"><?php echo esc_html($panel['tab']); ?></span>
                    </button>
                <?php $i++; endforeach; ?>
            </div>

            <div class="about-tabs__body">

                <div class="about-tabs__content-stack">
                    <?php $i = 0; foreach ($panels as $key => $panel) :
                        $is_first       = $i === 0;
                        $panel_img_url  = $image_url($panel['image']);
                        $panel_img_alt  = $image_alt($panel['image']);
                    ?>
                        <div class="about-tabs__content<?php echo $is_first ? ' is-active' : ''; ?>"
                             role="tabpanel"
                             id="about-panel-<?php echo esc_attr($key); ?>"
                             aria-labelledby="about-tab-<?php echo esc_attr($key); ?>"
                             data-about-panel="<?php echo esc_attr($key); ?>"
                             <?php echo $is_first ? '' : 'aria-hidden="true"'; ?>>

                            <?php if ($panel['heading']) : ?>
                                <h2 class="about-tabs__heading text-h2 text-weight-600 text-color-black">
                                    <?php echo esc_html($panel['heading']); ?>
                                </h2>
                            <?php endif; ?>

                            <?php if ($panel['body']) : ?>
                                <div class="about-tabs__body-text text-body-L">
                                    <?php echo wp_kses_post(wpautop($panel['body'])); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($panel_img_url) : ?>
                                <div class="about-tabs__panel-image">
                                    <img src="<?php echo esc_url($panel_img_url); ?>" alt="<?php echo esc_attr($panel_img_alt); ?>" loading="lazy">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>

                <?php if ($img_url) : ?>
                    <div class="about-tabs__media">
                        <div class="about-tabs__media-frame">
                            <img class="about-tabs__image" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy">
                            <span class="about-tabs__badge">
                                <span class="about-tabs__badge-icon" aria-hidden="true"><?php echo $badge_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

                                <span class="about-tabs__badge-labels">
                                    <?php $i = 0; foreach ($panels as $key => $panel) : ?>
                                        <span class="about-tabs__badge-label<?php echo $i === 0 ? ' is-active' : ''; ?>"
                                              data-about-label="<?php echo esc_attr($key); ?>"
                                              aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
                                            <?php echo esc_html($panel['label']); ?>
                                        </span>
                                    <?php $i++; endforeach; ?>
                                </span>

                                <span class="about-tabs__pin" aria-hidden="true">
                                    <?php echo $pin_decoration; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <?php get_template_part('template-parts/components/partnership'); ?>

    </div>
</section>
