<?php

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
    require_once get_template_directory() . '/vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
}
