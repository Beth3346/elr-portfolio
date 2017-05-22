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

$templates = ['archive.twig', 'index.twig'];

$context = Timber::get_context();
$skills = Timber::get_posts('post_type=skill&numberposts=-1');

$context['page_title'] = post_type_archive_title('', false);
array_unshift($templates, 'archive-' . get_post_type() . '.twig');

$context['skills'] = $skills;

Timber::render($templates, $context);
