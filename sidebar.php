<?php
// page sidebar

$context = [];
$context['general'] = get_option('general_options');
$context['logo'] = IMAGES . '/cat11-thumb.jpg';

Timber::render('sidebar.twig', $context);