<?php

namespace Framework\Helpers;

use \Framework\Helpers\Query;

class Content
{
    private $query;

    public function __construct()
    {
        $this->query = new Query;
    }

    public function title($content, $tag = 'h3')
    {
        return '<' . $tag . '>' . esc_html($content) . '</' . $tag . '>';
    }

    public function postLink()
    {
        $string = '<a href="' . get_the_permalink() . '">';
            $string .= get_the_title();
        $string .= '</a>';

        return $string;
    }

    public function relatedPostList($tax, $num)
    {
        $q = $this->query->getRelatedPosts($tax, $num);

        return $this->postList($q, 'There are no related posts');
    }

    public function recentPostList($post_type, $num)
    {
        return $this->postList($this->query->postQuery($post_type, $num));
    }

    public function postList($q, $message = 'There are no recent posts')
    {
        if ($q && $q->have_posts()) {
            $string = '<ul class="post-list">';

            while ($q->have_posts()) :
                $q->the_post();
                $string .= '<li>' . $this->postLink() . '</li>';
            endwhile;

            wp_reset_query();

            $string .= '</ul>';

            return $string;
        }

        return $message;
    }

    public function email(string $email)
    {
        return '<a href="mailto:' . antispambot($email) . '">' . antispambot($email) . '</a>';
    }

    public function phone(string $phone)
    {
        return '<a href="tel:' . $phone . '">' . $phone . '</a>';
    }

    public function video(string $src, int $width, int $height)
    {
        $string = '<div style="position:relative;height:0;padding-bottom:56.21%">';
        $string .= '<iframe src="' . $src . '"';
        $string .= 'style="position:absolute;width:100%;height:100%;left:0" ';
        $string .= 'width="' . $width . '" height="' . $height . '" frameborder="0" allowfullscreen>';
        $string .= '</iframe>';
        $string .= '</div>';

        return $string;
    }

    public function authorBox()
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
}
