<?php
/**
 * Single team member profile.
 *
 * Two-column layout (lg+): bio on the left, square photo gallery on the right.
 * Stacks on mobile with the photo dropping between the name/role header and
 * the Experience/Education/Personal sections.
 *
 * Data sources:
 *   post_title                → name
 *   ACF team_position         → role (falls back to first <h4> in body)
 *   post_content              → "Experience" body
 *   ACF team_education        → "Education" body (WYSIWYG)
 *   ACF team_personal         → "Personal" callout body (WYSIWYG)
 *   featured image + ACF      → gallery (featured image first, then
 *     team_gallery               team_gallery entries; controls hidden when
 *                                only one image is present)
 */
defined('ABSPATH') || exit;
get_header();

while (have_posts()) : the_post();

    $post_id   = get_the_ID();
    $name      = get_the_title();

    $position  = function_exists('get_field') ? (string) get_field('team_position', $post_id) : '';
    $content   = get_the_content();
    if ($position === '' && $content && preg_match('/<h4[^>]*>(.*?)<\/h4>/is', $content, $m)) {
        $raw = trim(wp_strip_all_tags($m[1]));
        $position = ($raw && $raw === mb_strtoupper($raw, 'UTF-8'))
            ? mb_convert_case(mb_strtolower($raw, 'UTF-8'), MB_CASE_TITLE, 'UTF-8')
            : $raw;
        // Drop the first <h4> from the body so the role isn't shown twice.
        $content = preg_replace('/<h4[^>]*>.*?<\/h4>/is', '', $content, 1);
    }
    $experience = brentonpoint_clean_post_content((string) $content);

    $education = function_exists('get_field') ? (string) get_field('team_education', $post_id) : '';
    $personal  = function_exists('get_field') ? (string) get_field('team_personal',  $post_id) : '';

    // ── Gallery: featured image first, then ACF team_gallery ──────────────
    $images = [];

    if (has_post_thumbnail($post_id)) {
        $thumb_id  = get_post_thumbnail_id($post_id);
        $full      = wp_get_attachment_image_src($thumb_id, 'full');
        $thumb     = wp_get_attachment_image_src($thumb_id, 'large');
        if ($full) {
            $focal = brentonpoint_attachment_focal_point((int) $thumb_id);
            $images[] = [
                'url'       => $full[0],
                'thumb_url' => $thumb ? $thumb[0] : $full[0],
                'alt'       => (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: $name,
                'width'     => (int) $full[1],
                'height'    => (int) $full[2],
                'focal_x'   => $focal['x'],
                'focal_y'   => $focal['y'],
            ];
        }
    }

    $gallery = function_exists('get_field') ? (array) get_field('team_gallery', $post_id) : [];
    foreach ($gallery as $img) {
        if (is_array($img) && !empty($img['url'])) {
            $sizes  = $img['sizes'] ?? [];
            $att_id = isset($img['ID']) ? (int) $img['ID'] : 0;
            $focal  = $att_id ? brentonpoint_attachment_focal_point($att_id) : ['x' => 50, 'y' => 50];
            $images[] = [
                'url'       => $img['url'],
                'thumb_url' => $sizes['large'] ?? ($sizes['medium_large'] ?? $img['url']),
                'alt'       => $img['alt'] ?? $name,
                'width'     => (int) ($img['width'] ?? 0),
                'height'    => (int) ($img['height'] ?? 0),
                'focal_x'   => $focal['x'],
                'focal_y'   => $focal['y'],
            ];
        } elseif (is_numeric($img)) {
            $att_id = (int) $img;
            $full   = wp_get_attachment_image_src($att_id, 'full');
            $thumb  = wp_get_attachment_image_src($att_id, 'large');
            if ($full) {
                $focal = brentonpoint_attachment_focal_point($att_id);
                $images[] = [
                    'url'       => $full[0],
                    'thumb_url' => $thumb ? $thumb[0] : $full[0],
                    'alt'       => (string) get_post_meta($att_id, '_wp_attachment_image_alt', true) ?: $name,
                    'width'     => (int) $full[1],
                    'height'    => (int) $full[2],
                    'focal_x'   => $focal['x'],
                    'focal_y'   => $focal['y'],
                ];
            }
        }
    }

    $team_page = get_page_by_path('team');
    $team_url  = $team_page ? get_permalink($team_page) : home_url('/team/');
?>

<main id="main" class="site-main site-main--inner">
    <article id="post-<?php the_ID(); ?>" <?php post_class('team-member section'); ?>>
        <div class="team-member__inner container">

            <header class="team-member__header">
                <h1 class="team-member__name text-h2 text-weight-600 text-color-black">
                    <?php echo esc_html($name); ?>
                </h1>

                <?php if ($position) : ?>
                    <p class="team-member__role text-h4 text-weight-500 text-color-deep-teal">
                        <?php echo esc_html($position); ?>
                    </p>
                <?php endif; ?>
            </header>

            <?php if (!empty($images)) : ?>
                <aside class="team-member__media">
                    <?php get_template_part('template-parts/components/team-member-gallery', null, [
                        'images' => $images,
                        'name'   => $name,
                    ]); ?>
                </aside>
            <?php endif; ?>

            <div class="team-member__body">

                <?php if ($experience) : ?>
                    <section class="team-member__section">
                        <h2 class="team-member__section-title text-h4 text-weight-600 text-color-black">
                            <?php esc_html_e('Experience', 'brentonpoint'); ?>
                        </h2>
                        <div class="team-member__section-body text-body-M">
                            <?php echo $experience; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — already kses'd by brentonpoint_clean_post_content ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($education) : ?>
                    <section class="team-member__section">
                        <h2 class="team-member__section-title text-h4 text-weight-600 text-color-black">
                            <?php esc_html_e('Education', 'brentonpoint'); ?>
                        </h2>
                        <div class="team-member__section-body text-body-M">
                            <?php echo wp_kses_post($education); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($personal) :
                    $icon_path = get_template_directory() . '/images/team-personal-icon.svg';
                    $icon      = file_exists($icon_path) ? file_get_contents($icon_path) : '';
                ?>
                    <section class="team-member__section team-member__section--personal">
                        <h2 class="team-member__section-title text-h4 text-weight-600 text-color-black">
                            <?php esc_html_e('Personal', 'brentonpoint'); ?>
                        </h2>
                        <div class="team-member__callout">
                            <?php if ($icon) : ?>
                                <span class="team-member__callout-icon" aria-hidden="true">
                                    <?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            <?php endif; ?>
                            <div class="team-member__callout-body text-body-M text-weight-600 text-color-deep-teal">
                                <?php echo wp_kses_post($personal); ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <?php brentonpoint_button([
                    'label'   => __('Back to Team', 'brentonpoint'),
                    'variant' => 'outline',
                    'href'    => $team_url,
                    'class'   => 'team-member__back btn--short',
                ]); ?>

            </div>
        </div>
    </article>
</main>

<?php endwhile;
get_footer();
