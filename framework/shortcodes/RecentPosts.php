<?php
namespace Framework\Shortcodes;

class RecentPosts
{
    public function shortcode($atts, $content)
    {
        return $this->createHTML($this->createCatArgs($atts), $content);
    }
}