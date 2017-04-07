<?php

namespace ELR\Portfolio\Helpers;

class Utility
{
    public function isCptArchive()
    {
        if (is_category() || is_author() || is_tag() || is_date() || is_front_page() || is_home()) {
            return false;
        }

        return true;
    }

    public function isCustomPostType($post = null)
    {
        $all_custom_post_types = get_post_types(array ('_builtin' => false));

        // there are no custom post types
        if (empty($all_custom_post_types)) {
            return false;
        }

        $custom_types = array_keys($all_custom_post_types);
        $current_post_type = get_post_type($post);

        // could not detect current type
        if (! $current_post_type) {
            return false;
        }

        return in_array($current_post_type, $custom_types);
    }

    public function isBlogPage()
    {
        if (is_front_page() && is_home()) {
            return true;
        } elseif (is_front_page()) {
            return false;
        } elseif (is_home()) {
            return true;
        } else {
            return false;
        }
    }

    public function slugify($str)
    {
        return str_replace(' ', '-', strtolower($str));
    }
}
