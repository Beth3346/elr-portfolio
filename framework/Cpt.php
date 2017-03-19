<?php

namespace Framework;

class Cpt {
    public function getDefaultSettings($singular_name, $plural_name)
    {
        $settings = array(
            $singular_name . '_root'      => str_replace('_', '-', $plural_name),
            $singular_name . '_base'      => str_replace('_', '-', $plural_name),
            $singular_name . '_item_base' => '%' . str_replace('_', '-', $singular_name) . '%'
        );

        return $settings;
    }

    public function setRoles($cpt) {
        /* Get the administrator role. */
        $role = get_role('administrator');

        if (isset($cpt['singular_name'])) {
            $singular_name = $cpt['singular_name'];
        } else {
            return;
        }

        $plural_name = isset($cpt['plural_name']) ? $cpt['plural_name'] : $singular_name . 's';

        /* If the administrator role exists, add required capabilities for the plugin. */
        if (!empty($role)) {
            $role->add_cap('manage_' . $singular_name);
            $role->add_cap('create_' . $plural_name);
            $role->add_cap('edit_' . $plural_name);
        }
    }

    public function registerPost($cpt) {
        add_action('init', function() use ($cpt) {
                return $this->registerPostType($cpt);
            }, 12
        );
    }

    /**
     * Registers post types needed by the plugin.
     *
     * @since  0.1.0
     * @access public
     * @param  string   $singular_name  singular form of post type name
     * @param  string   $plural_name    plural form of post type name
     * @param  array    $supports       array of features a post type supports
     * @param  array    $taxonomies     built in taxonomies supported by cpt (category or post_tag)
     * @param  boolean  $hierarchical   whether a cpt is hierarchical
     * @return void
     */

    public function registerPostType($cpt) {
        if (isset($cpt['singular_name'])) {
            $singular_name = $cpt['singular_name'];
        } else {
            return;
        }

        $plural_name = isset($cpt['plural_name']) ? $cpt['plural_name'] : $singular_name . 's';
        $supports = isset($cpt['supports']) ? $cpt['supports'] : ['title', 'editor', 'thumbnail', 'comments'];
        $taxonomies = isset($cpt['taxonomies']) ? $cpt['taxonomies'] : ['category', 'post_tags'];
        $hierarchical = isset($cpt['hierarchical']) ? $cpt['hierarchical'] : false;
        $archive = isset($cpt['archive']) ? $cpt['archive'] : true;

        $text_domain = 'elr-' . str_replace('_', '-', $singular_name);

        /* Get the plugin settings. */
        $settings = get_option('plugin_elr_' . $plural_name, $this->getDefaultSettings($singular_name, $plural_name));

        if ($archive == true) {
            $has_archive = $settings[$singular_name . '_root'];
        } else if ($archive == false) {
            $has_archive = false;
        } else {
            $has_archive = $archive;
        }

        /* Set up the arguments for the post type. */
        $args = [
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 11,
            'can_export'          => true,
            'delete_with_user'    => false,
            'hierarchical'        => $hierarchical,
            'taxonomies'          => $taxonomies,
            'has_archive'         => $has_archive,
            'query_var'           => $singular_name,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,

            /* Only 3 caps are needed: 'manage', 'create', and 'edit'. */
            'capabilities' => [

                // meta caps (don't assign these to roles)
                'edit_posts'              => 'edit_' . $plural_name,
                'read_post'              => 'read_' . $singular_name,
                'delete_post'            => 'delete_' . $singular_name,

                // primitive/meta caps
                'create_posts'           => 'create_' . $plural_name,

                // primitive caps used outside of map_meta_cap()
                'edit_posts'             => 'edit_' . $plural_name,
                'read_private_posts'     => 'read',

                // primitive caps used inside of map_meta_cap()
                'read'                   => 'read',
                'edit_private_posts'     => 'edit_' . $plural_name,
                'edit_published_posts'   => 'edit_' . $plural_name
           ],

            /* What features the post type supports. */
            'supports' => $supports,

            /* Labels used when displaying the posts. */
            'labels' => [
                'name'               => __(ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'singular_name'      => __(ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'menu_name'          => __(ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'name_admin_bar'     => __(ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'add_new'            => __('Add New', $text_domain),
                'add_new_item'       => __('Add New ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'edit_item'          => __('Edit ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'new_item'           => __('New ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'view_item'          => __('View ' . ucwords(str_replace('_', ' ', $singular_name)), $text_domain),
                'search_items'       => __('Search ' . ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
                'not_found'          => __('No ' . str_replace('_', ' ', $plural_name) . ' found', $text_domain),
                'not_found_in_trash' => __('No ' . str_replace('_', ' ', $plural_name) . ' found in trash', $text_domain),
                'all_items'          => __(ucwords(str_replace('_', ' ', $plural_name)), $text_domain),

                // Custom labels b/c WordPress doesn't have anything to handle this.
                'archive_title'      => __(ucwords(str_replace('_', ' ', $plural_name)), $text_domain),
           ]
        ];

        /* Register the post type. */
        register_post_type($singular_name, $args);
    }
}