<?php
/**
 * ACF local JSON sync.
 *
 * Field groups created/edited in the WP admin are auto-saved as JSON to
 * /acf-json in the theme. Other environments pick those JSON files up as
 * the source of truth (admin shows a "Sync available" prompt), so field
 * structure stays in version control without manual export/import.
 *
 * Workflow:
 *   1. Create / edit a field group in WP admin → Custom Fields.
 *   2. ACF writes <group-key>.json into /acf-json automatically.
 *   3. Commit the JSON file alongside any template/SCSS changes.
 *   4. On other environments, Custom Fields → Tools shows the group as
 *      syncable; clicking Sync imports the JSON into the database.
 */
defined('ABSPATH') || exit;

add_filter('acf/settings/save_json', static function () {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', static function ($paths) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});
