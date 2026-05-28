<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="site-header__inner container">
            <div class="site-branding">
                <?php $site_logo = function_exists('get_field') ? get_field('site_logo', 'option') : ''; ?>
                <?php if ($site_logo) : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-logo">
                        <img src="<?php echo esc_url($site_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    </a>
                <?php elseif (has_custom_logo()) : the_custom_logo(); else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-title">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="header-right">

                <nav id="site-navigation" class="main-navigation"
                     aria-label="<?php esc_attr_e('Primary', 'brentonpoint'); ?>">
                    <?php wp_nav_menu(['theme_location' => 'primary', 'menu_id' => 'desktop-menu', 'container' => false, 'fallback_cb' => false]); ?>
                </nav>

                <div class="header-actions">
                <button
                        class="search-toggle<?php echo is_search() ? ' is-active' : ''; ?>"
                        aria-label="<?php esc_attr_e('Toggle search', 'brentonpoint'); ?>"
                        aria-expanded="<?php echo is_search() ? 'true' : 'false'; ?>"
                        aria-controls="search-bar"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.1527 15.0943H16.0686L15.6844 14.7238C17.0292 13.1595 17.8388 11.1286 17.8388 8.91938C17.8388 3.99314 13.8456 0 8.91938 0C3.99314 0 0 3.99314 0 8.91938C0 13.8456 3.99314 17.8388 8.91938 17.8388C11.1286 17.8388 13.1595 17.0292 14.7238 15.6844L15.0943 16.0686V17.1527L21.9554 24L24 21.9554L17.1527 15.0943ZM8.91938 15.0943C5.50257 15.0943 2.74443 12.3362 2.74443 8.91938C2.74443 5.50257 5.50257 2.74443 8.91938 2.74443C12.3362 2.74443 15.0943 5.50257 15.0943 8.91938C15.0943 12.3362 12.3362 15.0943 8.91938 15.0943Z"
                              fill="#D3CAC0"/>
                    </svg>
                </button>

                <span class="header-separator" aria-hidden="true"></span>

                <button
                        class="menu-toggle"
                        aria-label="<?php esc_attr_e('Toggle menu', 'brentonpoint'); ?>"
                        aria-expanded="false"
                        aria-controls="mobile-menu"
                >
					<span class="menu-toggle__icon menu-toggle__icon--open" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="3" y1="6" x2="21" y2="6"></line>
							<line x1="3" y1="12" x2="21" y2="12"></line>
							<line x1="3" y1="18" x2="21" y2="18"></line>
						</svg>
					</span>
                    <span class="menu-toggle__icon menu-toggle__icon--close" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</span>
                </button>
                </div><!-- .header-actions -->

            </div><!-- .header-right -->

        </div><!-- .site-header__inner -->

        <!-- Dark overlay behind the open mobile menu -->
        <div class="mobile-menu-overlay" aria-hidden="true"></div>

        <!-- Mobile navigation panel (slides down inside sticky header) -->
        <div id="mobile-menu" class="mobile-menu" aria-hidden="true">
            <nav class="mobile-menu__nav container" aria-label="<?php esc_attr_e('Mobile Primary', 'brentonpoint'); ?>">
                <?php wp_nav_menu(['theme_location' => 'primary', 'menu_id' => 'mobile-menu-nav', 'container' => false, 'fallback_cb' => false]); ?>
            </nav>
        </div>

        <!-- Search bar panel (slides down inside sticky header; pinned open on the search results page) -->
        <div id="search-bar" class="search-bar<?php echo is_search() ? ' is-open' : ''; ?>" aria-hidden="<?php echo is_search() ? 'false' : 'true'; ?>">
            <form class="search-bar__form container" role="search" method="get"
                  action="<?php echo esc_url(home_url('/')); ?>">
				<span class="search-bar__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</span>
                <input
                        type="search"
                        class="search-bar__input"
                        name="s"
                        placeholder="<?php esc_attr_e('Search...', 'brentonpoint'); ?>"
                        value="<?php echo esc_attr(get_search_query()); ?>"
                        aria-label="<?php esc_attr_e('Search', 'brentonpoint'); ?>"
                        autocomplete="off"
                >
                <button type="button" class="search-bar__clear"
                        aria-label="<?php esc_attr_e('Clear search', 'brentonpoint'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true" focusable="false">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <button type="submit" class="search-bar__submit">
                    <?php esc_html_e('Search', 'brentonpoint'); ?>
                </button>
            </form>
        </div>

    </header><!-- #masthead -->

    <div id="content" class="site-content">
