<?php
namespace Framework\Shortcodes;

use \Framework\Shortcodes\Categories;
use \Framework\Helpers\Content;

class Shortcodes
{
    private $helper;

    private function __construct()
    {
        $this->helper = new Content;
    }

    public function addShortcodes()
    {
        // new up all shortcodes
        add_shortcode('elr-author', function () {
            return $this->helper->authorBox();
        });

        add_shortcode('elr-categories', function ($atts, $content = null) {
            $categories = new Categories;

            return $categories->shortcode($atts, $content);
        });

        add_shortcode('elr-email', function ($atts, $content = null) {
            if ($content) {
                return $this->helper->email($content);
            }
        });

        add_shortcode('elr-phone', function ($atts, $content = null) {
            return $this->helper->phone($content);
        });

        add_shortcode('elr-video', function ($atts) {
            extract(shortcode_atts([
                'src' => '',
                'width' => 641,
                'height' => 360
            ], $atts));

            // if there is no video source don't output any html

            if (!$src) {
                return;
            }

            return $this->helper->video($src, $width, $height);
        });
    }
}
