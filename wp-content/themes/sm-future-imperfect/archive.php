<?php

$context = Timber::context();
$context['posts'] = new Timber\PostQuery();

Timber::render( 'archive.twig', $context );