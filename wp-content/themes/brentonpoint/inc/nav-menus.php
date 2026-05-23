<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function () {
    register_nav_menus([
        'primary' => __('Primary Navigation', 'brentonpoint'),
        'footer' => __('Footer Navigation', 'brentonpoint'),
        'footer_bottom' => __('Footer Bottom Navigation', 'brentonpoint'),
    ]);
});

/**
 * Return footer menu items grouped into columns by their top-level parent.
 *
 * Each top-level item becomes a column heading (rendered as non-clickable text);
 * its direct children become the column's links. Items deeper than depth 1 are ignored.
 *
 * @return array<int, array{ id:int, title:string, children: array<int, WP_Post> }>
 */
function brentonpoint_get_footer_columns(): array
{
    $locations = get_nav_menu_locations();
    if (empty($locations['footer'])) {
        return [];
    }

    $items = wp_get_nav_menu_items($locations['footer']);
    if (!$items) {
        return [];
    }

    $columns = [];
    foreach ($items as $item) {
        if ((int)$item->menu_item_parent === 0) {
            $columns[$item->ID] = [
                'id' => (int)$item->ID,
                'title' => $item->title,
                'children' => [],
            ];
        }
    }
    foreach ($items as $item) {
        $parent = (int)$item->menu_item_parent;
        if ($parent !== 0 && isset($columns[$parent])) {
            $columns[$parent]['children'][] = $item;
        }
    }

    return array_values($columns);
}

add_filter('nav_menu_link_attributes', function ($atts) {
    $atts['class'] = isset($atts['class']) ? $atts['class'] . ' nav-link' : 'text-navigation nav-link';
    return $atts;
});
