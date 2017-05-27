<?php
namespace Framework\Shortcodes;

class PostTerms
{
    private function createHTML($content)
    {
        return;
    }

    public function shortcode($atts, $content)
    {
        extract(shortcode_atts([], $atts));

        return $this->createHTML($content);
    }
}
