<?php
// Template Name: Front

$post = new TimberPost();

$context = Timber::get_context();
$context['post'] = $post;
$context['articles'] = Timber::get_posts('post_type=post&numberposts=3');
$context['educations'] = Timber::get_posts('post_type=education&numberposts=3');
$context['experiences'] = Timber::get_posts('post_type=experience&numberposts=-1');
$context['websites'] = Timber::get_posts('post_type=project&numberposts=3');
$context['readings'] = Timber::get_posts('post_type=reading&numberposts=3');
$context['recommendations'] = Timber::get_posts('post_type=recommendation&numberposts=3');
$context['skills'] = Timber::get_posts('post_type=skill&numberposts=-1');
$context['tutorials'] = Timber::get_posts('post_type=tutorial&numberposts=3');
$context['videos'] = Timber::get_posts('post_type=video&numberposts=3');
$context['summary'] = wpautop(get_option('general_options')['summary']);
$context['blog_url'] = get_permalink(get_option('page_for_posts'));
$context['portfolio_url'] = get_post_type_archive_link('project');
$context['tutorials_url'] = get_post_type_archive_link('tutorial');

// open source projects
// $context['projects'] = Timber::get_posts('post_type=project&numberposts=3');

Timber::render('front-page.twig', $context);
