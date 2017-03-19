<?php

namespace Framework;

class CptMeta {

    private function saveMeta($fields)
    {
        global $post;

        foreach ($fields as $field) {
            if (isset($_POST[ $field['id'] ])) {
                update_post_meta($post->ID, $field['id'], $_POST[ $field['id'] ]);
            }
        }
    }

    private function sanitizeMeta($meta_value, $meta_key, $meta_type)
    {
        // if meta key has url then sanitize url
        // if meta key has email then sanitize email
        return strip_tags($meta_value);
    }

    private function createFields($fields)
    {
        // implement security
        wp_nonce_field(__FILE__, 'cpt_nonce');

        $html = '';

        foreach ($fields as $field) {
            $type = $this->getFieldType($field);

            $html .= '<label for="' . $field['id'] . '">' . $field['label'] . ': </label>';

            if ($type == 'textarea') {
                $html .= $this->createTextArea($field);
            } else {
                $html .= $this->createTextField($field);
            }
        }

        echo $html;
    }

    private function getFieldValue($field)
    {
        global $post;

        $current = get_post_meta($post->ID, $field['id'], true);

        if ($current) {
            return $current;
        } else if (isset($field['default_value'])) {
            return $field['default_value'];
        }

        return '';
    }

    private function getFieldType($field)
    {
        if (isset($field['input_type'])) {
            return $field['input_type'];
        }

        return 'text';
    }

    private function createTextField($field)
    {
        $html = '<input type="' . $this->getFieldType($field) . '"';
        $html .= 'id="' . $field['id'] . '"';
        $html .= 'name="' . $field['id'] . '"';
        $html .= 'value="' . esc_attr($this->getFieldValue($field)) . '"';
        $html .= 'class="widefat"';
        $html .= '/>';

        return $html;
    }

    private function createTextArea($field)
    {
        $html = '<textarea cols="10" rows="3" class="widefat" id="' . $field['id'] . '" name="' . $field['id'] . '">';
        $html .= $this->getFieldValue($field);
        $html .= '</textarea>';

        return $html;
    }

    private function registerMeta($fields)
    {
        foreach ($fields as $field) {
            register_meta('post', $field, '[$this, sanitize_meta]', '__return_true');
        }
    }

    public function register($fields)
    {
        add_action('init', function() use ($fields) {
            foreach ($fields as $field) {
                $this->registerMeta($field);
            }
        }, 12);
    }

    public function addMetaBoxes($fields, $name)
    {
        add_action('add_meta_boxes', function() use ($fields, $name) {
            // add meta boxes here
            add_meta_box(
                'elr_' . $name . '_information',
                ucwords($name) . ' Information',
                function() use ($fields) {
                    $this->createFields($fields);
                },
                $name,
                'normal',
                'high'
           );
        });
    }

    public function save($fields)
    {
        add_action('save_post', function() use ($fields) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

            //security check - nonce
            if ($_POST && isset($_POST['cpt_nonce']) && !wp_verify_nonce($_POST['cpt_nonce'], __FILE__)) {
                return;
            }

            return $this->saveMeta($fields);
        }, 12);
    }
}