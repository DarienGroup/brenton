<?php
/**
 * Firm page — Mission / Vision tabs section.
 *
 * Replays the about-tabs component from the homepage with the same data,
 * so editing the mission/vision content on the front page updates both
 * places automatically. Top padding is suppressed via --section-pt: 0
 * because this section butts directly against the approach block above.
 */
defined('ABSPATH') || exit;

$home_id = (int) get_option('page_on_front');
if (!$home_id) {
    return;
}

$tabs_args = brentonpoint_about_tabs_args($home_id);
if (empty($tabs_args['panels'])) {
    return;
}
?>
<section class="firm-tabs-section section" style="--section-pt: 0px;">
    <div class="firm-tabs-section__inner container">
        <?php get_template_part('template-parts/components/about-tabs', null, $tabs_args); ?>
    </div>
</section>
