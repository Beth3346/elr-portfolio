<?php

namespace ELR\Portfolio\ThemeOptions;

use ELR\Portfolio\ThemeOptions\Menu;
use ELR\Portfolio\ThemeOptions\Options;

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
