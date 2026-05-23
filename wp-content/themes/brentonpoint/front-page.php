<?php
defined('ABSPATH') || exit;
get_header();
?>

    <main id="main" class="site-main site-main--home">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php if (trim(get_the_content()) !== '') : ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('home-content'); ?>>
                        <div class="entry-content container">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php
        get_template_part(
                'template-parts/sections/contact-section',
                null,
                ['variant' => 'home']
        );
        ?>
    </main>

<?php
get_footer();
