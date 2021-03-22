<?php
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
