<?php
namespace Framework\Shortcodes;

class PostTerms
{
    public function shortcode($atts, $content)
    {
        return $this->createHTML($this->createCatArgs($atts), $content);
    }
}