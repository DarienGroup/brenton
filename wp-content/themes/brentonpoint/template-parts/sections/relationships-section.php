<?php
/**
 * Homepage — "Creating Lasting Relationships and Shared Prosperity" section.
 *
 * Dark deep-teal block with a two-column layout: left = heading + body +
 * two CTA buttons; right = managing partner portrait + quote.
 *
 * ACF fields (homepage):
 *   relationships_heading        (text)
 *   relationships_body           (wysiwyg)  — supports the inline "Private
 *                                             Equity for a Greater Good" link
 *   relationships_button_primary (link)     — Approach CTA
 *   relationships_button_secondary (link)   — Portfolio CTA
 *   relationships_image          (image)    — square portrait
 *   relationships_quote          (textarea) — quote body
 *   relationships_quote_name     (text)     — quoted person name
 *   relationships_quote_role     (text)     — quoted person role
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name) {
    return function_exists('get_field') ? get_field($name) : null;
};

$image_url = static function ($image) {
    if (is_array($image))   { return $image['url'] ?? ''; }
    if (is_numeric($image)) { return wp_get_attachment_image_url((int) $image, 'large') ?: ''; }
    return (string) $image;
};

$image_alt = static function ($image) {
    if (is_array($image))   { return $image['alt'] ?? ''; }
    if (is_numeric($image)) { return get_post_meta((int) $image, '_wp_attachment_image_alt', true) ?: ''; }
    return '';
};

$button_from_link = static function ($link, string $fallback_label, string $fallback_href = '') {
    if (is_array($link)) {
        $href   = $link['url']    ?? '';
        $target = $link['target'] ?? '';
        $label  = $link['title']  ?: $fallback_label;
    } else {
        $href   = (string) ($link ?: $fallback_href);
        $target = '';
        $label  = $fallback_label;
    }
    if (!$href) { return null; }
    return ['label' => $label, 'href' => $href, 'target' => $target];
};

$heading = $field('relationships_heading') ?: __('Creating Lasting Relationships and Shared Prosperity', 'brentonpoint');
$body    = $field('relationships_body');

$btn_primary   = $button_from_link($field('relationships_button_primary'),   __('Approach',  'brentonpoint'));
$btn_secondary = $button_from_link($field('relationships_button_secondary'), __('Portfolio', 'brentonpoint'));

$image     = $field('relationships_image');
$image_src = $image_url($image);
$image_alt_text = $image_alt($image);

$quote = $field('relationships_quote');
$name  = $field('relationships_quote_name');
$role  = $field('relationships_quote_role');

$section_classes = ['relationships-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'relationships-section--home';
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>" data-reveal>
    <div class="relationships-section__inner container">
        <div class="relationships-section__grid">

            <div class="relationships-section__content">
                <?php if ($heading) : ?>
                    <h2 class="relationships-section__heading text-h2 text-weight-400 text-color-white">
                        <?php echo esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($body) : ?>
                    <div class="relationships-section__body text-body-L">
                        <?php echo wp_kses_post(wpautop($body)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($btn_primary || $btn_secondary) : ?>
                    <div class="relationships-section__cta">
                        <?php if ($btn_primary) : ?>
                            <?php brentonpoint_button([
                                'label'   => $btn_primary['label'],
                                'href'    => $btn_primary['href'],
                                'target'  => $btn_primary['target'],
                                'variant' => 'cyan',
                                'class'   => 'relationships-section__button btn--small',
                            ]); ?>
                        <?php endif; ?>

                        <?php if ($btn_secondary) : ?>
                            <?php brentonpoint_button([
                                'label'   => $btn_secondary['label'],
                                'href'    => $btn_secondary['href'],
                                'target'  => $btn_secondary['target'],
                                'variant' => 'white-outline',
                                'class'   => 'relationships-section__button btn--small',
                            ]); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <figure class="relationships-section__quote-card">
                <?php if ($image_src) : ?>
                    <div class="relationships-section__portrait">
                        <img src="<?php echo esc_url($image_src); ?>"
                             alt="<?php echo esc_attr($image_alt_text); ?>"
                             loading="lazy">
                    </div>
                <?php endif; ?>

                <figcaption class="relationships-section__quote">
                    <?php if ($quote) : ?>
                        <blockquote class="relationships-section__quote-body text-body-L">
                            <?php echo wp_kses_post(wpautop($quote)); ?>
                        </blockquote>
                    <?php endif; ?>

                    <div class="relationships-section__quote-meta">
                        <div class="relationships-section__quote-person">
                            <?php if ($name) : ?>
                                <p class="relationships-section__quote-name text-weight-700">
                                    <?php echo esc_html($name); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($role) : ?>
                                <p class="relationships-section__quote-role">
                                    <?php echo esc_html($role); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <span class="relationships-section__quote-mark" aria-hidden="true">
                            <svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5925 16.3788C15.0559 16.4913 16.1534 16.0973 16.8851 15.1968C17.6168 14.3525 17.9827 13.1706 17.9827 11.6509C17.9827 11.0318 17.9545 10.5534 17.8982 10.2157L13.0015 10.2157L13.0015 0.000113622L22.3728 0.000114441L22.3728 11.0599C22.3728 14.043 21.7255 16.3506 20.431 17.9828C19.1365 19.6714 17.3072 20.5156 14.9433 20.5156C14.5493 20.5156 13.9021 20.4593 13.0015 20.3468L13.5925 16.3788ZM0.590905 16.3788C2.05429 16.4913 3.15183 16.0973 3.88352 15.1968C4.61521 14.3525 4.98105 13.1706 4.98106 11.6509C4.98106 11.0318 4.95291 10.5534 4.89663 10.2157L-7.73008e-05 10.2157L-7.64078e-05 0.000112485L9.37121 0.000113304L9.37121 11.0599C9.3712 14.043 8.72394 16.3506 7.42941 17.9828C6.13488 19.6714 4.30565 20.5156 1.94172 20.5156C1.54773 20.5156 0.900467 20.4593 -7.81865e-05 20.3468L0.590905 16.3788Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </figcaption>
            </figure>

        </div>
    </div>
</section>
