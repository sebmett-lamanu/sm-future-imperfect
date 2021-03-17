<?php
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
        'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js',
        false,
        '3.5.1',
        true
    );

    // Browser
    wp_enqueue_script(
        'browser',
        get_template_directory_uri() . '/assets/js/browser.min.js',
        array('jquery'),
        '1.0',
        true
    );
    // Breakpoints
    wp_enqueue_script(
        'breakpoints',
        get_template_directory_uri() . '/assets/js/breakpoints.min.js',
        array('jquery'),
        '1.0',
        true
    );
    // Utils
    wp_enqueue_script(
        'util',
        get_template_directory_uri() . '/assets/js/util.js',
        array('jquery'),
        '1.0',
        true
    );
    // Main JS
    wp_enqueue_script(
        'main',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        '1.0',
        true
    );

    // Main CSS
    wp_enqueue_style(
        'futimp_main',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        '1.0'
    );

    // Fontawesome
    wp_enqueue_style(
        'futimp_fontawesome',
        get_template_directory_uri() . '/assets/css/fontawesome-all.min.css',
        array(),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'futimp_register_assets');


// -----------------------------------------------------------------
// Navigation
// -----------------------------------------------------------------

if ( ! function_exists( 'smfi_register_nav_menu' ) ) {
 
    function smfi_register_nav_menu(){
        register_nav_menus( array(
            'primary' => __( 'Navigation principale', 'smfi' ),
            'social' => __( 'Réseaux sociaux', 'smfi' ),
        ) );
    }
    add_action( 'after_setup_theme', 'smfi_register_nav_menu', 0 );
}

// -----------------------------------------------------------------
// Timber
// -----------------------------------------------------------------
require_once(__DIR__ . '/vendor/autoload.php');
$timber = new Timber\Timber();


// -----------------------------------------------------------------
// Ajouts au context de Timber
// -----------------------------------------------------------------
function add_to_context($context)
{

    if (has_custom_logo()) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'medium');
        $context['site_logo'] = $logo;
    }

    // So here you are adding data to Timber's context object, i.e...
    $context['foo'] = 'I am some other typical value set in your functions.php file, unrelated to the menu';

    // Now, in similar fashion, you add a Timber Menu and send it along to the context.
    $context['primary_menu'] = new \Timber\Menu( 'primary' );
    $context['social_menu'] = new \Timber\Menu( 'social' );

    return $context;
}

add_filter('timber/context', 'add_to_context');
