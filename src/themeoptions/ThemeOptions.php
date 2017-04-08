<?php

namespace Src\ThemeOptions;

use \Src\ThemeOptions\Menu;
use \Src\ThemeOptions\Options;

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
