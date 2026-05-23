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
