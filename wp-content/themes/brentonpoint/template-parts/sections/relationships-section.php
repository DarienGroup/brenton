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
                                'class'   => 'relationships-section__button',
                            ]); ?>
                        <?php endif; ?>

                        <?php if ($btn_secondary) : ?>
                            <?php brentonpoint_button([
                                'label'   => $btn_secondary['label'],
                                'href'    => $btn_secondary['href'],
                                'target'  => $btn_secondary['target'],
                                'variant' => 'white-outline',
                                'class'   => 'relationships-section__button',
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
                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.2 0H0v6.3h3.6c0 2-1.2 3.6-3.6 3.9v3.5C5.2 13.4 7.2 10.2 7.2 6.7V0Zm10.8 0h-7.2v6.3h3.6c0 2-1.2 3.6-3.6 3.9v3.5C16 13.4 18 10.2 18 6.7V0Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </figcaption>
            </figure>

        </div>
    </div>
</section>
