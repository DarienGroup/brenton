<?php
/**
 * Team card — single team member tile used in the team grid.
 *
 * Args ($args):
 *   post_id   int     teams CPT post ID (required)
 *   linked    bool    Render as <a> linking to the single team post. Default true.
 *
 * Data sources:
 *   post_title         → name
 *   featured image     → headshot
 *   ACF team_position  → role/title line; falls back to the first <h4> in
 *                        post_content (existing data convention) lowercased
 *                        to title case.
 */
defined('ABSPATH') || exit;

$args = wp_parse_args($args ?? [], [
    'post_id' => 0,
    'linked'  => true,
]);

$post_id = (int) $args['post_id'];
if (!$post_id) {
    return;
}

$name = get_the_title($post_id);

$position = function_exists('get_field') ? (string) get_field('team_position', $post_id) : '';
if ($position === '') {
    $content = get_post_field('post_content', $post_id);
    if ($content && preg_match('/<h4[^>]*>(.*?)<\/h4>/is', $content, $m)) {
        $position = trim(wp_strip_all_tags($m[1]));
    }
}
// Existing posts store the role in uppercase ("VICE PRESIDENT") in either the
// ACF field or the body <h4>. Convert all-caps values to Title Case while
// leaving mixed-case strings alone.
if ($position !== '' && $position === mb_strtoupper($position, 'UTF-8')) {
    $position = mb_convert_case(mb_strtolower($position, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

$img_attrs = [
    'class'   => 'team-card__image',
    'loading' => 'lazy',
    'alt'     => $name,
];
$thumb_id = get_post_thumbnail_id($post_id);
if ($thumb_id && function_exists('brentonpoint_attachment_focal_point')) {
    $focal = brentonpoint_attachment_focal_point((int) $thumb_id);
    if ($focal['x'] !== 50 || $focal['y'] !== 50) {
        $img_attrs['style'] = sprintf('object-position: %d%% %d%%;', $focal['x'], $focal['y']);
    }
}
$image_html = get_the_post_thumbnail($post_id, 'medium_large', $img_attrs);

$linked = (bool) $args['linked'];
$href   = $linked ? get_permalink($post_id) : '';
$Tag    = $linked && $href ? 'a' : 'div';
?>
<<?php echo $Tag; ?>
    class="team-card<?php echo $linked && $href ? ' team-card--linked' : ''; ?>"
    <?php if ($linked && $href) : ?>
        href="<?php echo esc_url($href); ?>"
        aria-label="<?php echo esc_attr(sprintf(__('View profile: %s', 'brentonpoint'), $name)); ?>"
    <?php endif; ?>
>
    <div class="team-card__media">
        <?php if ($image_html) : ?>
            <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php else : ?>
            <span class="team-card__image team-card__image--placeholder" aria-hidden="true"></span>
        <?php endif; ?>
    </div>

    <div class="team-card__body">
        <p class="team-card__name text-h4 text-weight-700 text-color-black"><?php echo esc_html($name); ?></p>

        <div class="team-card__footer">
            <?php if ($position) : ?>
                <p class="team-card__role text-body-S text-color-primary-gray"><?php echo esc_html($position); ?></p>
            <?php endif; ?>

            <?php if ($linked && $href) : ?>
                <span class="team-card__chevron" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M7.5 5L12.5 10L7.5 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            <?php endif; ?>
        </div>
    </div>
</<?php echo $Tag; ?>>
