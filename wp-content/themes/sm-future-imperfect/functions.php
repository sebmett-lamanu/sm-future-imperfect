<?php
// -----------------------------------------------------------------
// Composer autoload
// -----------------------------------------------------------------

require_once __DIR__ . '/vendor/autoload.php';

// -----------------------------------------------------------------
// Fonctionnalités du thème
// -----------------------------------------------------------------

add_theme_support('custom-logo');
add_theme_support('post-thumbnails');

// -----------------------------------------------------------------
// Déclarations des CSS et JS
// -----------------------------------------------------------------
function futimp_register_assets()
{
    // On supprime jQuery
    wp_deregister_script('jquery');

    // Déclarer jQuery
    wp_enqueue_script(
        'jquery',
        // 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js',
        'https://code.jquery.com/jquery-3.6.0.min.js',
        false,
        '3.6.0',
        true
    );

    // Browser
    wp_enqueue_script(
        'browser',
        get_template_directory_uri() . '/assets/js/browser.min.js',
        ['jquery'],
        '1.0',
        true
    );
    // Breakpoints
    wp_enqueue_script(
        'breakpoints',
        get_template_directory_uri() . '/assets/js/breakpoints.min.js',
        ['jquery'],
        '1.0',
        true
    );
    // Utils
    wp_enqueue_script(
        'util',
        get_template_directory_uri() . '/assets/js/util.js',
        ['jquery'],
        '1.0',
        true
    );
    // Main JS
    wp_enqueue_script(
        'main',
        get_template_directory_uri() . '/assets/js/main.js',
        ['jquery'],
        '1.0',
        true
    );

    // Main CSS
    wp_enqueue_style(
        'futimp_main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        time()
    );

    // Fontawesome
    wp_enqueue_style(
        'futimp_fontawesome',
        get_template_directory_uri() . '/assets/css/fontawesome-all.min.css',
        [],
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'futimp_register_assets');

// -----------------------------------------------------------------
// Navigation
// -----------------------------------------------------------------

if (!function_exists('smfi_register_nav_menu')) {
    function smfi_register_nav_menu()
    {
        register_nav_menus([
            'primary' => __('Navigation principale', 'smfi'),
        ]);
    }
    add_action('after_setup_theme', 'smfi_register_nav_menu', 0);
}

// -----------------------------------------------------------------
// Carbon fields
// Permet d'ajouter des champs supplémentaires dans le BO de WP
// -----------------------------------------------------------------

include_once 'inc/inc_carbonfields.php';

// -----------------------------------------------------------------
// Timber
// -----------------------------------------------------------------

include_once 'inc/inc_timber.php';

// -----------------------------------------------------------------
// Test like function
// -----------------------------------------------------------------

include_once 'inc/inc_ajaxcall.php';

// -----------------------------------------------------------------
// Gestion des dépendances via TGM Plugin Activation
// https://github.com/TGMPA/TGM-Plugin-Activation
// -----------------------------------------------------------------
require_once get_template_directory() .
    '/dependencies/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/inc/inc_tgmpa.php';
