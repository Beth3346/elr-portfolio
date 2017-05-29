<?php
namespace Framework\Shortcodes;

use \Framework\Shortcodes\Categories;
use \Framework\Shortcodes\RecentPosts;
use \Framework\Shortcodes\RelatedPosts;
use \Framework\Helpers\Content;
use \Framework\Helpers\Taxonomy;

class Shortcodes
{
    private $content;
    private $tax;

    public function __construct()
    {
        $this->content = new Content;
        $this->tax = new Taxonomy;
    }

    public function addShortcodes()
    {
        // new up all shortcodes
        add_shortcode('elr-author', function () {
            return $this->content->authorBox();
        });

        add_shortcode('elr-categories', function ($atts, $content = null) {
            extract(shortcode_atts([
                'num' => 'all',
                'by_count' => false,
                'hierarchical' => true,
                'count' => false
            ], $atts));

            $attrs = [
                'num' => $num,
                'by_count' => $by_count,
                'hierarchical' => $hierarchical
            ];

            $cat_args = $this->tax->createCatArgs($attrs);

            return '<div class="elr-categories">' .
                $this->content->title($content) .
                $this->tax->createCategoryList($cat_args) .
                '</div>';
        });

        add_shortcode('elr-recent-posts', function ($atts, $content = null) {
            extract(shortcode_atts([
                'num' => 5,
                'post_type' => 'post'
            ], $atts));

            $string = $this->content->title($content);
            $string .= $this->content
                ->recentPostList($post_type, $num);

            return $string;
        });

        add_shortcode('elr-related-posts', function ($atts, $content = null) {
            extract(shortcode_atts([
                'tax' => 'category',
                'num' => 5
            ], $atts));

            $string = $this->content->title($content);
            $string .= $this->content
                ->relatedPostList($tax, $num);

            return $string;
        });

        add_shortcode('elr-email', function ($atts, $content = null) {
            if ($content) {
                return $this->content->email($content);
            }
        });

        add_shortcode('elr-phone', function ($atts, $content = null) {
            return $this->content->phone($content);
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

            return $this->content->video($src, $width, $height);
        });
    }
}
