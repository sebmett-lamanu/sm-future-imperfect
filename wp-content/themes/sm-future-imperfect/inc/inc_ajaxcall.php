<?php


function add_ajax_scripts()
{
    // Ajoute le fichier ajax-calls.js dans le DOM
    wp_enqueue_script(
        'ajaxcalls',
        get_template_directory_uri() . '/assets/js/ajax-calls.js?v=' . time(),
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