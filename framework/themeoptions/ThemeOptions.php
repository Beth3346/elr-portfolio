<?php

namespace Framework\ThemeOptions;

use Framework\ThemeOptions\Menu;
use Framework\ThemeOptions\Options;

class ThemeOptions
{
    public function initializeThemeSettings(array $options)
    {
        $menu = new Menu;
        $settings = new Options;

        $menu->addThemeMenu($options);
        $settings->addSubpageOptions($options['subpages']);
    }
}
