<?php
namespace Framework\Shortcodes;

use \Framework\Helpers\Taxonomy;

class Categories
{
    private $tax;

    public function __construct()
    {
        $this->tax = new Taxonomy;
    }

    private function createTitle($content, $tag = 'h3')
    {
        if ($content != null) {
            return '<' . $tag . '>' . esc_html($content) . '</' . $tag . '>';
        }
    }

    private function createHTML(array $cat_args, $content)
    {
        return '<div class="elr-categories">' . $this->createTitle($content) . $this->tax->createCategoryList($cat_args) . '</div>';
    }

    public function shortcode($atts, $content)
    {
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

        return $this->createHTML($this->tax->createCatArgs($attrs), $content);
    }
}
