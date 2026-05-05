<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Translate a theme interface string through Polylang when available.
 */
function theme_translate(string $text): string
{
    if (function_exists('pll__')) {
        return pll__($text);
    }

    return __($text, 'sage');
}

/**
 * Resolve a theme page URL for the currently selected language.
 */
function theme_localized_page_url(string $path): string
{
    $path = trim($path, '/');

    if (function_exists('pll_get_post') && $page = get_page_by_path($path)) {
        $translated_page_id = pll_get_post($page->ID);

        if ($translated_page_id) {
            return get_permalink($translated_page_id);
        }
    }

    if (function_exists('pll_home_url')) {
        return trailingslashit(pll_home_url()).$path;
    }

    return home_url('/'.$path);
}

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */

    /**
     * Make theme strings translatable via language files.
     */
    load_theme_textdomain('sage', get_template_directory().'/lang');

    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * Show a notice when Polylang is missing.
 *
 * @return void
 */
add_action('admin_notices', function () {
    if (! current_user_can('activate_plugins')) {
        return;
    }

    if (defined('POLYLANG_VERSION')) {
        return;
    }

    $install_url = admin_url('plugin-install.php?s=polylang&tab=search&type=term');

    echo '<div class="notice notice-error"><p>';
    echo wp_kses_post(sprintf(
        /* translators: %s is the plugin install URL */
        __('This theme requires Polylang. Please <a href="%s">install and activate Polylang</a>.', 'sage'),
        esc_url($install_url)
    ));
    echo '</p></div>';
});


/**
 * Register theme interface strings with Polylang.
 *
 * @return void
 */
add_action('init', function () {
    if (! function_exists('pll_register_string')) {
        return;
    }

    $strings = [
        'Toggle navigation',
        'Primary navigation',
        'Language switcher',
        'Account',
        'Log in',
        'Copyright',
        'Categories',
        'About',
        'Contact',
        'Advertise',
        'Write For Us',
        'Privacy Policy',
        'Terms and Conditions',
        'Follow Us',
        'LinkedIn',
        'Skip to content',
        'Search for:',
        'Search …',
        'Search &hellip;',
        'Search',
        'Featured stories',
        'More news',
        'Search news',
        'Find stories, interviews, insights and more.',
        'See all',
        'Latest posts',
        'Latest',
        'All category news',
        'All news',
        'View all',
        'Previous',
        'Next',
        'Sorry, no results were found for this tag.',
        'Sorry, no results were found.',
        'Page not found',
        'The page you are looking for does not exist or has been moved.',
        'Go to homepage',
        'Posts by',
        'Author',
        'News',
        'No posts were found for this author.',
        'By',
        'Comments',
        'Share your thoughts about this post.',
        'Comments are closed.',
        'Continued',
    ];

    foreach ($strings as $string) {
        pll_register_string($string, $string, 'Theme');
    }
});
