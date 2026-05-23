<?php
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], ['variant' => 'default']);

$field = static function ($name, $source = null) {
    if (!function_exists('get_field')) {
        return null;
    }
    return $source ? get_field($name, $source) : get_field($name);
};

$heading = $field('contact_section_heading') ?: 'Get in Touch with Us';
$description = $field('contact_section_description');
$show_press = (bool)$field('contact_section_show_press');

$form_id = (int)$field('contact_form_id', 'option');
$info_email = $field('contact_email', 'option');
$info_phone = $field('phone_number', 'option');
$press_email = $field('press_inquiries_email', 'option');
$address_body = $field('footer_address', 'option');

$cards = [];

if ($info_email || $info_phone) {
    $items = [];
    if ($info_email) {
        $items[] = ['href' => 'mailto:' . $info_email, 'label' => $info_email];
    }
    if ($info_phone) {
        $items[] = [
                'href' => 'tel:' . preg_replace('/[^0-9+]/', '', $info_phone),
                'label' => $info_phone,
        ];
    }
    $cards[] = [
            'modifier' => 'info',
            'heading' => $field('contact_section_info_heading') ?: 'Contact Info',
            'items' => $items,
    ];
}

if ($show_press && $press_email) {
    $cards[] = [
            'modifier' => 'press',
            'heading' => $field('contact_section_press_heading') ?: 'Press Inquiries',
            'items' => [
                    ['href' => 'mailto:' . $press_email, 'label' => $press_email],
            ],
    ];
}

if ($address_body) {
    $cards[] = [
            'modifier' => 'address',
            'heading' => $field('contact_section_address_heading') ?: 'Address',
            'body' => $address_body,
    ];
}

$section_classes = ['contact-section'];
if ('home' === $args['variant']) {
    $section_classes[] = 'contact-section--home';
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <div class="contact-section__inner container">

        <h2 class="contact-section__heading text-h2 text-weight-600 text-color-black">
            <?php echo esc_html($heading); ?>
        </h2>

        <?php if ($description) : ?>
            <p class="contact-section__description text-body-L">
                <?php echo esc_html($description); ?>
            </p>
        <?php endif; ?>

        <div class="contact-section__cards">
            <?php foreach ($cards as $card) : ?>
                <div class="contact-card contact-card--<?php echo esc_attr($card['modifier']); ?>">
                    <h3 class="contact-card__heading text-h4 text-weight-600">
                        <?php echo esc_html($card['heading']); ?>
                    </h3>

                    <?php if (!empty($card['items'])) : ?>
                        <ul class="contact-card__list">
                            <?php foreach ($card['items'] as $item) : ?>
                                <li>
                                    <a href="<?php echo esc_attr($item['href']); ?>">
                                        <?php echo esc_html($item['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php elseif (!empty($card['body'])) : ?>
                        <address class="contact-card__address">
                            <?php echo wp_kses_post(wpautop($card['body'])); ?>
                        </address>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="contact-section__form">
            <?php if ($form_id && function_exists('gravity_form')) : ?>
                <?php gravity_form($form_id, false, false, false, null, true); ?>
            <?php elseif (current_user_can('edit_theme_options')) : ?>
                <p class="contact-section__form-missing">
                    <?php esc_html_e('Set the Gravity Form ID in Site Settings → Contact form ID.', 'brentonpoint'); ?>
                </p>
            <?php endif; ?>
        </div>

    </div>
</section>
