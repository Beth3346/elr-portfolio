<?php
namespace Framework\Shortcodes;

class RelatedPosts
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
