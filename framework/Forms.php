<?php

namespace Framework;

class Forms {
    public function defaultOptions(array $fields)
    {
        // push default values to defaults array
        // print_r($fields);

        $defaults = [];

        foreach ($fields as $field) {
            $defaults[] = $field['default_value'];
        }

        // print_r($defaults);

        return apply_filters('default_options', $defaults);
    }

    public function fieldCallback($field, $subpage_id)
    {
        // First, we read the social options collection
        $options = get_option($subpage_id);
        $id = $field['id'];
        $instructions = (isset($field['instructions'])) ? $field['instructions'] : '';
        $type = (isset($field['input_type'])) ? $field['input_type'] : 'text';

        if (!empty($options[$id])) {
            $value = $options[$id];
        } else {
            $value = null;
        }

        if ($type == 'textarea') {
            echo $this->createTextArea($field, $subpage_id, $value);
        } elseif ($type == 'select') {
            echo $this->createSelectField($field, $subpage_id, $value);
        } else {
            echo $this->createTextInput($field, $subpage_id, $value);
        }

        echo '<small>' . $instructions . '</small>';
    }

    private function setFieldLabel(array $field) {
        return (isset($field['label'])) ? $field['label'] : 'text';
    }

    private function setFieldType(array $field) {
        return (isset($field['label'])) ? $field['label'] :  ucwords(str_replace('_', ' ', $field['id']));
    }

    private function createTextInput(array $field, $subpage_id, $value) {
        $id = $field['id'];
        $label = $this->setFieldLabel($field);
        $type = $this->setFieldLabel($field);

        $html = '<input type="' . $type . '" class="widefat" id="' . $id . '"placeholder="' . $label . '"name="' . $subpage_id . '[' . $id . ']' . '"value="' . $value . '"
            />';

        return $html;
    }

    private function createTextArea(array $field, $subpage_id, $value) {
        $id = $field['id'];

        $html = '<textarea class="widefat" name="' . $subpage_id . '[' . $id . ']' . '" id="' . $id . '" cols="30" rows="10">' . $value . '</textarea>';
        return $html;
    }

    private function getSelectOptions($options, $value) {
        $html = '';

        foreach ($options as $option) {
            // if option is the current value
            if ($value == $option) {
                $html .= '<option selected value="' . $option . '">' . $option . '</option>';
            } else {
                $html .= '<option value="' . $option . '">' . $option . '</option>';
            }
        }

        return $html;
    }

    private function createSelectField(array $field, $subpage_id, $value) {
        $id = $field['id'];
        $label = $this->setFieldLabel($field);

        $html = '<select class="widefat" name="' . $subpage_id . '[' . $id . ']' . '" id="' . $id . '">';
        $html .= '<option value="">Select ' . $label . '</option>';
        $html .= $this->getSelectOptions($field['options'], $value);
        $html .= '</select>';

        return $html;
    }
}