<?php
/**
 * Single post template.
 *
 * Renders the post title, date, optional featured image, and the post body.
 * Legacy Avia/Enfold shortcodes that may still live in imported post content
 * are stripped on output by brentonpoint_clean_post_content() so only
 * meaningful text and media survive.
 */
defined('ABSPATH') || exit;
get_header();
?>

<main id="main" class="site-main site-main--inner">
    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post section'); ?>>
            <div class="single-post__inner container">

                <header class="single-post__header">
                    <div class="single-post__meta text-body-S text-color-primary-gray">
                        <time class="single-post__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </div>

                    <h1 class="single-post__title text-h2 text-weight-600 text-color-black">
                        <?php the_title(); ?>
                    </h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <figure class="single-post__featured">
                        <?php the_post_thumbnail('full', [
                            'class' => 'single-post__featured-image',
                            'alt'   => esc_attr(get_the_title()),
                        ]); ?>
                    </figure>
                <?php endif; ?>

                <div class="single-post__content text-body-M">
                    <?php echo brentonpoint_clean_post_content(get_the_content()); ?>
                </div>

            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php
get_footer();
