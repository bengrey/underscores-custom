<?php
/**
 * test functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package test
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

// Translations domain
load_theme_textdomain('test', get_template_directory() . '/languages');

// Add automatic rss support
add_theme_support('automatic-feed-links');

// Let WordPress manage the document title
add_theme_support('title-tag');

// Enable support for Post Thumbnails on posts and pages
add_theme_support('post-thumbnails');

// This theme uses wp_nav_menu() in one location.
register_nav_menus(
    array(
        'menu-1' => esc_html__('Primary', 'test'),
    )
);

// Switch default core markup to output valid HTML5.
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    )
);

// Add theme support for selective refresh for widgets.
add_theme_support('customize-selective-refresh-widgets');

// Register widget area
function test_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'test'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'test'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'test_widgets_init');

// Enqueue scripts and styles
function test_scripts()
{
    wp_enqueue_style('test-style', get_stylesheet_uri(), array());
}

add_action('wp_enqueue_scripts', 'test_scripts');

// Load WooCommerce compatibility file
if (class_exists('WooCommerce')) {
    require get_template_directory() . '/functions/woocommerce.php';
}

// disable gutenberg frontend styles
function disable_gutenberg_wp_enqueue_scripts() {

    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // disable woocommerce frontend block styles

}
add_filter('wp_enqueue_scripts', 'disable_gutenberg_wp_enqueue_scripts', 100);

// Disable the emoji's
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    // Remove from TinyMCE
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

// Filter out the tinymce emoji plugin
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}