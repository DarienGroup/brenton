<?php
/**
 * Inner page sections — Team.
 *
 * Loaded by page-templates/inner.php when the current page slug is "team".
 * Renders two grids backed by the `team_list` taxonomy on the `teams` CPT:
 *   1. "Team list"        (slug: team-list)        — the operating team
 *   2. "Executive Board"  (slug: executive-board)  — advisory board
 *
 * Per-grid heading + intro live in ACF on the Team page (group "Team Page").
 */
defined('ABSPATH') || exit;

get_template_part('template-parts/sections/team-grid-section', null, [
    'term_slug'         => 'team-list',
    'heading_field'     => 'team_list_heading',
    'description_field' => 'team_list_description',
    'class'             => 'team-grid-section--primary',
]);

get_template_part('template-parts/sections/team-advisory-section');
