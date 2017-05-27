<?php
namespace Framework\Shortcodes;

class RelatedPosts
{
    public function shortcode($atts, $content)
    {
        return $this->createHTML($this->createCatArgs($atts), $content);
    }
}