<?php

namespace Framework;
use Framework\Cpt;

class CustomTaxonomyBuilder {
    /**
     * Adds default terms to taxonomy
     *
     * @since  0.1.0
     * @access public
     * @param  string   $parent taxonomy to receive the terms
     * @param  array    $terms  array of terms to add to $parent
     * @return null
     */

    private function taxonomyAddDefaultTerms($parent, $terms)
    {
        $parent_term = term_exists($parent, $parent);
        $parent_term_id = $parent_term['term_id'];

        foreach ($terms as $term) {
            if (!term_exists($term, $parent)) {
                wp_insert_term(
                    $term,
                    $parent,
                    [
                        'slug' => $term,
                        'parent'=> $parent_term_id
                    ]
                );
            }
        }
    }

    private function register($tax, $cpt_singular, $cpt_plural)
    {
        add_action('init', function() use ($tax, $cpt_singular, $cpt_plural) {
            return $this->registerTaxonomy($tax, $cpt_singular, $cpt_plural);
        }, 12);

        return;
    }

    /**
     * Register taxonomies for the plugin.
     *
     * @since  0.1.0
     * @access public
     * @return void.
     */

    private function registerTaxonomy($tax, $cpt_singular, $cpt_plural) {
        if (isset($tax['singular_name'])) {
            $singular_name = $tax['singular_name'];
        } else {
            return;
        }

        $plural_name = isset($tax['plural_name']) ? $tax['plural_name'] : $singular_name . 's';
        $hierarchical = isset($tax['hierarchical']) ? $tax['hierarchical'] : true;
        $default_terms = isset($tax['default_terms']) ? $tax['default_terms'] : [];

        $builder = new Cpt;

        $text_domain = 'elr-' . str_replace('_', '-', $singular_name);

        /* Get the plugin settings. */
        $settings = get_option('plugin_elr_' . $cpt_plural, $builder->getDefaultSettings($cpt_singular, $cpt_plural));

        /* Set up the arguments for the priority taxonomy. */
        $args = [
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'hierarchical'      => $hierarchical,
            'query_var'         => $singular_name,

            /* Only 2 caps are needed: 'manage_announcement' and 'edit_announcement'. */
            'capabilities' => [
                'manage_terms' => 'manage_' . $cpt_singular,
                'edit_terms'   => 'manage_' . $cpt_singular,
                'delete_terms' => 'manage_' . $cpt_singular,
                'assign_terms' => 'edit_' . $cpt_plural,
            ],

            /* Labels used when displaying taxonomy and terms. */
            'labels' => [
                'name'                       => __(ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'singular_name'              => __(ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'menu_name'                  => __(ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'name_admin_bar'             => __(ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'search_items'               => __('Search ' . ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'popular_items'              => __('Popular ' . ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'all_items'                  => __('All ' . ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'edit_item'                  => __('Edit ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'view_item'                  => __('View ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'update_item'                => __('Update ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'add_new_item'               => __('Add New ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'new_item_name'              => __('New ' . ucwords(str_replace('_', ' ', $singular_name)) . ' Name', $text_domain),
                'add_or_remove_items'        => __('Add or remove ' . str_replace('_', ' ', $plural_name), $text_domain),
                'choose_from_most_used'      => __('Choose from the most used ' . str_replace('_', ' ', $plural_name), $text_domain),
                'separate_items_with_commas' => __('Separate ' . str_replace('_', ' ', $plural_name) . 'with commas', $text_domain),
           ]
        ];

        // Register the taxonomy
        register_taxonomy($singular_name, [$cpt_singular], $args);

        // add default terms
        $this->taxonomyAddDefaultTerms($singular_name, $default_terms);
    }

    public function registerTaxonomies($cpt)
    {
        if (isset($cpt['singular_name'])) {
            $singular_name = $cpt['singular_name'];
        } else {
            return;
        }

        $plural_name = isset($cpt['plural_name']) ? $cpt['plural_name'] : $singular_name . 's';
        $taxonomies = $cpt['custom_taxonomies'];

        foreach ($taxonomies as $tax) {
            $this->register($tax, $singular_name, $plural_name);
        }

        return;
    }
}