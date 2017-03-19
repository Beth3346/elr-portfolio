<?php

namespace Framework;

use \Framework\Forms;

class ThemeOptions {
    public function addThemeMenu($options_title = 'Theme Options', array $subpages = [])
    {
        add_theme_page(
            $options_title,
            $options_title,
            'administrator',
            'theme_options',
            function () use ($subpages, $options_title) {
                $this->themeDisplay($subpages, $options_title);
            }
        );

        add_menu_page(
            $options_title,
            $options_title,
            'administrator',
            'theme_menu',
            function () use ($subpages, $options_title) {
                $this->themeDisplay($subpages, $options_title);
            }
        );

        $this->addSubPages($subpages);
    }

    private function addSubPages($subpages) {
        foreach ($subpages as $subpage) {
            add_submenu_page(
                'elr_theme_menu',
                __($subpage['title'], 'elr'),
                __($subpage['title'], 'elr'),
                'administrator',
                'theme_' . $subpage['id']
            );
        }
    }

    public function initializeOptions(array $fields, array $subpage)
    {
        $form = new Forms;
        $section = $subpage['id'] . '_section';

        if (false == get_option($subpage['id'])) {
            add_option($subpage['id'], apply_filters([$form, 'defaultOptions'], $form->defaultOptions($fields)));
        }

        add_settings_section(
            $section,
            __($subpage['title'], 'elr'),
            function () use ($subpage) {
                echo '<p>' . __($subpage['description'], 'elr') . '</p>';
            },
            $subpage['id']
        );

        foreach ($fields as $field) {
            $id = $field['id'];
            $label = (isset($field['label'])) ? $field['label'] . ':' :  ucwords(str_replace('_', ' ', $field['id'])) . ':';

            add_settings_field(
                $id,
                $label,
                function () use ($field, $subpage, $form) {
                    $form->fieldCallback($field, $subpage['id']);
                },
                $subpage['id'],
                $section
            );
        }

        register_setting(
            $subpage['id'],
            $subpage['id']
        );
    }

    private function themeDisplay(array $subpages, $title)
    {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : $subpages[0]['id'];
        ?>
            <div class="wrap">
                <h2><?php _e($title, 'elr'); ?></h2>
                <?php settings_errors(); ?>

                <h2 class="nav-tab-wrapper">
                    <?php foreach ($subpages as $subpage) : ?>
                        <a href="?page=theme_options&tab=<?php echo $subpage['id']; ?>" class="nav-tab <?php echo $active_tab == $subpage['id'] ? 'nav-tab-active' : ''; ?>"><?php _e($subpage['title'], 'elr'); ?></a>
                    <?php endforeach; ?>
                </h2>

                <?php foreach ($subpages as $subpage) : ?>
                    <form method="post" action="options.php">
                        <?php if ($active_tab == $subpage['id']) {
                            settings_fields($subpage['id']);
                            do_settings_sections($subpage['id']);
                            submit_button();
    } ?>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php
    }
} ?>