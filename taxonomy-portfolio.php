<?php

/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = ['taxonomy-portfolio.twig'];

$context = Timber::get_context();
$posts = Timber::get_posts();
$term = Timber::get_term(get_queried_object()->term_id, 'portfolio');

$context['page_title'] = $term->name . ' Portfolio';

$context['posts'] = $posts;
$context['pagination'] = Timber::get_pagination();

Timber::render($templates, $context);
