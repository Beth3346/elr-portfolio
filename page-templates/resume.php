<?php
// Template Name: Resume

$post = new TimberPost();

$context = Timber::get_context();
$context['educations'] = Timber::get_posts('post_type=education&numberposts=-1');
$context['experiences'] = Timber::get_posts('post_type=experience&numberposts=-1');
$context['skills'] = Timber::get_posts('post_type=skill&numberposts=-1');
$context['summary'] = wpautop(get_option('general_options')['summary']);

Timber::render('resume.twig', $context);
