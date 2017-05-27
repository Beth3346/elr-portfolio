<?php
namespace Framework\Shortcodes;

use \Framework\Shortcodes\Authors;
use \Framework\Shortcodes\Categories;

class Shortcodes
{
    public function addShortcodes()
    {
        // new up all shortcodes
        add_shortcode('elr-author', function($atts, $content = null) {
            $author = new Authors;

            return $author->shortcode($atts, $content);
        });

        add_shortcode('elr-categories', function($atts, $content = null) {
            $categories = new Categories;

            return $categories->shortcode($atts, $content);
        });

        add_shortcode('elr-email', function($atts, $content = null) {
            if ($content) {
                return '<a href="mailto:' . antispambot($content) . '">' . antispambot($content) . '</a>';
            }
        });

        add_shortcode('elr-phone', function($atts, $content = null) {
            return '<a href="tel:' . $content . '">' . $content . '</a>';
        });

        add_shortcode('elr-video', function($atts) {
            extract(shortcode_atts([
                'src' => '',
                'width' => 641,
                'height' => 360
            ], $atts));

            // if there is no video source don't output any html

            if (!$src) {
                return;
            }

            $string = '<div style="position:relative;height:0;padding-bottom:56.21%">';
            $string .= '<iframe src="' . $src . '"';
            $string .= 'style="position:absolute;width:100%;height:100%;left:0" ';
            $string .= 'width="' . $width . '" height="' . $height . '" frameborder="0" allowfullscreen>';
            $string .= '</iframe>';
            $string .= '</div>';

            return $string;
        });
    }
}