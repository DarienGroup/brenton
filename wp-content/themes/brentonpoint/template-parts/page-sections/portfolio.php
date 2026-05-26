<?php
/**
 * Inner page sections — Portfolio (slug: portfolio, "Partner Companies").
 *
 * Loaded by page-templates/inner.php when the current page slug is "portfolio".
 * Renders the partner-companies grid backed by the `our_portfolio` CPT and the
 * `category_portfolio` taxonomy (terms: active, realized).
 */
defined('ABSPATH') || exit;

get_template_part('template-parts/sections/portfolio-section');
