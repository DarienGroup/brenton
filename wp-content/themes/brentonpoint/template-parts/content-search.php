<?php
/**
 * Single search result card.
 *
 * Renders one row inside the search results listing: linked title plus excerpt.
 * The trailing divider is drawn in CSS so the last card can drop it.
 */
defined('ABSPATH') || exit;

$excerpt = get_the_excerpt();
?>
<li class="search-card">
    <h2 class="search-card__title text-h4 text-weight-600">
        <a class="search-card__link" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>

    <?php if ($excerpt) : ?>
        <div class="search-card__excerpt text-body-M">
            <?php echo esc_html(wp_strip_all_tags($excerpt)); ?>
        </div>
    <?php endif; ?>
</li>
