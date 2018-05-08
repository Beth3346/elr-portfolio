<?php

/**
 * The template for displaying Skill Archive.
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

$templates = ['archive-project.twig'];

$context = Timber::get_context();
$context['types'] = Timber::get_terms('project_type', ['hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']);
// $context['projects'] = Timber::get_posts('post_type=project');
// $context['pagination'] = Timber::get_pagination();
$context['page_title'] = post_type_archive_title('Projects', false);

Timber::render($templates, $context);
