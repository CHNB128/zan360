<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Read the active tag slug on category archive pages.
 */
function category_topic_slug(): string
{
    $slug = sanitize_title((string) get_query_var('topic'));

    if ($slug === '' && isset($_GET['topic'])) {
        $slug = sanitize_title(wp_unslash((string) $_GET['topic']));
    }

    return $slug;
}

/**
 * Allow tag filtering on category archives via ?topic=slug.
 *
 * @param  array<int, string>  $vars
 * @return array<int, string>
 */
add_filter('query_vars', function (array $vars): array {
    $vars[] = 'topic';

    return $vars;
});

/**
 * Keep the topic query arg on category pagination URLs.
 *
 * @param  string|false  $redirect_url
 * @return string|false
 */
add_filter('redirect_canonical', function ($redirect_url) {
    if (is_category() && category_topic_slug() !== '') {
        return false;
    }

    return $redirect_url;
});

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});
