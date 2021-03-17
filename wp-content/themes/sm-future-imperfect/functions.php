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
        '1.0'
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

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Champs de la page d'options de thème
add_action('carbon_fields_register_fields', 'crb_attach_theme_options', 1);
function crb_attach_theme_options()
{
    Container::make('theme_options', __('Theme Options'))
        //urlpicker : https://github.com/iamntz/carbon-fields-urlpicker
        ->add_tab(__('Réseaux sociaux'), [
            // Facebook
            Field::make(
                'urlpicker',
                'crb_social_url_facebook',
                'Lien vers Facebook'
            )->set_help_text('Saisissez le lien vers votre page Facebook'),
            // Twitter
            Field::make(
                'urlpicker',
                'crb_social_url_twitter',
                'Lien vers Twitter'
            )->set_help_text('Saisissez le lien vers votre compte Twitter'),
            // Instagram
            Field::make(
                'urlpicker',
                'crb_social_url_instagram',
                'Lien vers Instagram'
            )->set_help_text('Saisissez le lien vers votre page Instagram'),
        ])
        //urlpicker : https://github.com/iamntz/carbon-fields-urlpicker
        ->add_tab(__('A propos'), [
            // A  propos
            Field::make('textarea', 'crb_about_text', 'À propos')
                ->set_help_text('Saisissez un texte de présentation')
                ->set_rows(4),
            // Lien vers une page de détail
            Field::make(
                'urlpicker',
                'crb_about_link',
                'Lien vers une page de détail'
            )->set_help_text('Saisissez le lien vers la page d\'information'),
        ])
        ->add_tab(__('Articles de la sidebar #1'), [
            // Mini-posts (sidebar)
            Field::make('association', 'crb_miniposts', __('Mini posts'))
                ->set_types([
                    [
                        'type' => 'post',
                        'post_type' => 'post',
                    ],
                ])
                ->set_min(0)
                ->set_max(5)
                ->set_help_text(
                    'Indiquez les posts à afficher dans la sidebar'
                ),
        ])
        ->add_tab(__('Articles de la sidebar #2'), [
            // ListOfPosts (sidebar)
            Field::make('association', 'crb_listofposts', __('List of posts'))
                ->set_types([
                    [
                        'type' => 'post',
                        'post_type' => 'post',
                    ],
                ])
                ->set_min(0)
                ->set_max(5)
                ->set_help_text(
                    'Indiquez les posts à afficher dans la sidebar'
                ),
        ])
        ->add_fields([]);
}

// Champs personnalisés du single
add_action('carbon_fields_register_fields', 'crb_attach_single_options', 1);
function crb_attach_single_options()
{
    Container::make('post_meta', 'Options de l\'article')
        ->where('post_type', '=', 'post')
        ->add_fields([
            Field::make('text', 'crb_post_subtitle', 'Sous-titre'),
            Field::make('number', 'crb_post_likes', 'Likes')->set_default_value(
                0
            ),
        ]);
}

// Chargement des champs sur le hook after_theme_setup
add_action('after_setup_theme', 'crb_load');
function crb_load()
{
    require_once 'vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
}

// -----------------------------------------------------------------
// Timber
// -----------------------------------------------------------------

$timber = new Timber\Timber(); // déclare une instance Timber

function add_to_context($context)
{
    // ajoute des informations supplémentaires au contexte de Timber
    // Gestion du logo custom
    if (has_custom_logo()) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'medium');
        $context['site_logo'] = $logo;
    }

    // Gestion des champs Carbon Fields
    $context['facebook_url'] = carbon_get_theme_option(
        'crb_social_url_facebook'
    );
    $context['twitter_url'] = carbon_get_theme_option('crb_social_url_twitter');
    $context['instagram_url'] = carbon_get_theme_option(
        'crb_social_url_instagram'
    );
    $context['crb_about_text'] = carbon_get_theme_option('crb_about_text');
    $context['crb_about_link'] = carbon_get_theme_option('crb_about_link');

    $context['crb_post_subtitle'] = carbon_get_post_meta(
        get_the_ID(),
        'crb_post_subtitle'
    );
    $context['crb_post_likes'] = carbon_get_post_meta(
        get_the_ID(),
        'crb_post_likes'
    );

    $context['admin_url'] = admin_url('admin-ajax.php');

    $miniposts = carbon_get_theme_option('crb_miniposts');
    $miniposts_list = [];

    foreach ($miniposts as $key => $value) {
        $miniposts_list[] .= $value['id'];
    }
    $context['crb_miniposts'] = Timber::get_posts($miniposts_list);

    // Now, in similar fashion, you add a Timber Menu and send it along to the context.
    $context['primary_menu'] = new \Timber\Menu('primary');

    return $context;
}

add_filter('timber/context', 'add_to_context');

// -----------------------------------------------------------------
// Test like function
// -----------------------------------------------------------------

function add_ajax_scripts()
{
    // Ajoute le fichier ajax-calls.js dans le DOM
    wp_enqueue_script(
        'ajaxcalls',
        get_template_directory_uri() . '/assets/js/ajax-calls.js?v='.time(),
        ['jquery'],
        '1.0.0',
        true
    );
    // Rend disponible la variable AJAXCALLS avant l'éxécution de ajax-calls.js
    wp_add_inline_script(
        'ajaxcalls',
        'const AJAXCALLS = ' .
            json_encode([
                'ajaxurl' => admin_url('admin-ajax.php'),
                // 'param' => 'value',
            ]),
        'before'
    );
}

add_action('wp_enqueue_scripts', 'add_ajax_scripts');
function update_post_likes()
{
    // on retrouve les infos 'ID' et 'nombre de likes' de l'article concerné grâce aux infos de la requête ajax
    $post_id = $_POST['post_id'];
    $likes_count = $_POST['likes_count'];

    // On incrémente le nombre de likes
    $likes_count = $likes_count + 1;
    // On met à jour la valeur du custom field 'crb_post_likes'
    carbon_set_post_meta($post_id, 'crb_post_likes', $likes_count);
    // On construit la réponse sous forme d'un tableau
    $response = [];
    $response['target'] = '[data-postid="' . $_POST['post_id'] . '"]'; // Le sélecteur correspondant au "Like" à mettre à jour
    $response['likes'] = $likes_count; // la nouvelle valeur du nombre de likes
    echo json_encode($response); // la réponse
    // Permet de s'assurer qu'aucun code ne continuera à s'éxécuter
    die();
}
// https://developer.wordpress.org/reference/hooks/wp_ajax_action/
add_action('wp_ajax_update_post_likes', 'update_post_likes');
add_action('wp_ajax_nopriv_update_post_likes', 'update_post_likes');