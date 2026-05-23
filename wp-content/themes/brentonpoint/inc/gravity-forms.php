<?php
defined('ABSPATH') || exit;

/**
 * Override Gravity Forms' "Indicates a required field" legend text so the
 * design's "* indicates required fields" copy is used everywhere.
 */
add_filter('gform_required_legend', function ($legend, $form) {
    unset($legend, $form);
    return '<span class="gfield_required"><span class="gfield_required_text">*</span></span> indicates required fields';
}, 10, 2);

/**
 * Replace the Gravity Forms submit button with our `.btn` component.
 *
 * `gform-theme__disable` opts the button (and its descendants) out of
 * Gravity Forms' framework theme reset (`all: unset` on every descendant),
 * so the .btn styles win without specificity gymnastics.
 */
add_filter('gform_submit_button', function ($button, $form) {
    unset($button);

    $id   = (int) $form['id'];
    $text = isset($form['button']['text']) && $form['button']['text'] !== ''
        ? $form['button']['text']
        : __('Submit', 'brentonpoint');

    return brentonpoint_get_button([
        'label'   => $text,
        'variant' => 'cyan',
        'full'    => true,
        'type'    => 'submit',
        'id'      => 'gform_submit_button_' . $id,
        'class'   => 'gform_button gform-theme__disable',
    ]);
}, 10, 2);
