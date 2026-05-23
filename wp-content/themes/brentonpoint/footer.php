</div><!-- #content -->

<?php
$footer_description = function_exists('get_field') ? get_field('footer_description', 'option') : '';
$footer_address = function_exists('get_field') ? get_field('footer_address', 'option') : '';
$footer_logo = function_exists('get_field') ? get_field('site_logo', 'option') : '';
$footer_copyright = function_exists('get_field') ? get_field('footer_copyright', 'option') : '';
$columns = brentonpoint_get_footer_columns();
?>
<footer id="colophon" class="site-footer">
    <div class="site-footer__inner container">

        <div class="site-footer__brand">
            <?php if ($footer_logo) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-footer__logo">
                    <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                </a>
            <?php endif; ?>
            <div>
                <?php if ($footer_description) : ?>
                    <p class="site-footer__description"><?php echo esc_html($footer_description); ?></p>
                <?php endif; ?>

                <?php if ($footer_address) : ?>
                    <address
                            class="site-footer__address"><?php echo wp_kses_post(wpautop($footer_address)); ?></address>
                <?php endif; ?>
            </div>
        </div>

        <div class="site-footer__menu">
            <?php if ($columns) : ?>
                <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer', 'brentonpoint'); ?>">
                    <?php foreach ($columns as $col) : ?>
                        <div class="footer-col" data-footer-col>
                            <button
                                    type="button"
                                    class="footer-col__heading"
                                    aria-expanded="false"
                                    data-footer-col-toggle
                            >
                                <span><?php echo esc_html($col['title']); ?></span>
                                <svg class="footer-col__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <div class="footer-col__panel" data-footer-col-panel>
                                <ul class="footer-col__list">
                                    <?php foreach ($col['children'] as $child) : ?>
                                        <li class="footer-col__item">
                                            <a class="footer-col__link"
                                               href="<?php echo esc_url($child->url); ?>"<?php echo !empty($child->target) ? ' target="' . esc_attr($child->target) . '"' : ''; ?>>
                                                <?php echo esc_html($child->title); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <button
                    type="button"
                    class="site-footer__top"
                    aria-label="<?php esc_attr_e('Back to top', 'brentonpoint'); ?>"
                    data-scroll-top
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
            </button>
        </div>

    </div><!-- .site-footer__inner -->

    <div class="site-footer__bottom container">
        <nav class="site-footer__legal" aria-label="<?php esc_attr_e('Footer legal', 'brentonpoint'); ?>">
            <?php
            wp_nav_menu([
                    'theme_location' => 'footer_bottom',
                    'menu_id' => 'footer-bottom-menu',
                    'container' => false,
                    'depth' => 1,
                    'fallback_cb' => false,
            ]);
            ?>
        </nav>
        <p class="site-footer__copyright text-body-s">
            <?php echo esc_html($footer_copyright) . ' ' . gmdate('Y'); ?> All Rights Reserved.
        </p>
    </div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
