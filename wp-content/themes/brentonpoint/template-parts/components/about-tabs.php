<?php
/**
 * Mission / Vision tabs component.
 *
 * The tab strip + animated media frame used inside the homepage about
 * section and on the firm page. Data-agnostic — callers pass content
 * via $args.
 *
 * Args ($args):
 *   panels         array  Ordered map of tab key => [tab, heading, body, label, image]
 *   desktop_image  mixed  ACF image array | attachment ID | URL — used in the right-hand media frame at lg+
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'panels'        => [],
    'desktop_image' => null,
]);

$panels = (array) $args['panels'];
if (empty($panels)) {
    return;
}

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

$img_url = $image_url($args['desktop_image']);
$img_alt = $image_alt($args['desktop_image']);

// Inlined assets — kept in /images so designers can edit them, but
// embedded here so the badge + pin render without extra HTTP requests.
$badge_icon     = file_get_contents(get_template_directory() . '/images/lighthouse-icon-white.svg') ?: '';
$pin_decoration = file_get_contents(get_template_directory() . '/images/about-line-decoration.svg') ?: '';
?>
<div class="about-tabs" data-about-tabs data-reveal>

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
                <span class="about-tabs__tab-label"><?php echo esc_html($panel['tab'] ?? ''); ?></span>
            </button>
        <?php $i++; endforeach; ?>
    </div>

    <div class="about-tabs__body">

        <div class="about-tabs__content-stack">
            <?php $i = 0; foreach ($panels as $key => $panel) :
                $is_first       = $i === 0;
                $panel_img_url  = $image_url($panel['image'] ?? null);
                $panel_img_alt  = $image_alt($panel['image'] ?? null);
            ?>
                <div class="about-tabs__content<?php echo $is_first ? ' is-active' : ''; ?>"
                     role="tabpanel"
                     id="about-panel-<?php echo esc_attr($key); ?>"
                     aria-labelledby="about-tab-<?php echo esc_attr($key); ?>"
                     data-about-panel="<?php echo esc_attr($key); ?>"
                     <?php echo $is_first ? '' : 'aria-hidden="true"'; ?>>

                    <?php if (!empty($panel['heading'])) : ?>
                        <h2 class="about-tabs__heading text-h2 text-weight-600 text-color-black">
                            <?php echo esc_html($panel['heading']); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($panel['body'])) : ?>
                        <div class="about-tabs__body-text text-body-M">
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
                                    <?php echo esc_html($panel['label'] ?? ''); ?>
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
