<?php
namespace Framework\Shortcodes;

class Authors
{
    private function createHTML($content)
    {
        $string = '<div class="author-box">';
            $string .= '<h3 class="author-title">Written by: ' . get_the_author() . '</h3>';
            $string .= '<div class="author-info">';
                $string .= '<div class="author-avatar">';
                    $string .= get_avatar(get_the_author_meta('user_email'), '80', '');
                $string .= '</div>';
                $string .= '<div class="author-description">';
                    $string .= wpautop(get_the_author_meta('description'));
                    $string .= '<p><a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">Read more from ' . get_the_author() . '</a></p>';
                $string .= '</div>';
            $string .= '</div>';
        $string .= '</div>';

        return $string;
    }

    public function shortcode($atts, $content)
    {
        extract(shortcode_atts([], $atts));

        return $this->createHTML($content);
    }
}
