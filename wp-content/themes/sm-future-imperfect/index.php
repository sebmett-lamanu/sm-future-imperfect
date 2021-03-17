<?php

$context = Timber::context();
// $context['posts'] = Timber::get_posts();
// Permet la pagination

$context['posts'] = new Timber\PostQuery();

Timber::render( 'index.twig', $context );