<?php
namespace Framework\Shortcodes;

class Categories
{
    private function createTitle($content, $tag = 'h3')
    {
        if ($content != null) {
            return '<' . $tag . '>' . esc_html($content) . '</' . $tag . '>';
        }
    }

    private function setTermArgs($term)
    {
        return [
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'terms' => $term->slug,
                    'field' => 'slug',
                    'operator' => 'IN',
                ]
            ]
        ];
    }

    private function createTermLink($term)
    {
        $term_link = get_term_link($term);

        return '<a href="' . $term_link . '">' . $term->name . '</a>';
    }

    private function createTerm($term)
    {
        $args = $this->setTermArgs($term);

        $query = new \WP_Query($args);
        $post_count = $query->post_count;

        $string = '<li>';
        $string .= $this->createTermLink($term);

        if (isset($cat_args['count'])) {
            $string .= ' ' . $post_count;
        }

        $string .= '</li>';

        return $string;
    }

    private function createCategoryList(array $cat_args)
    {
        $string = '<ul>';

        $terms = get_terms('category', $cat_args);

        if (!empty($terms) && !is_wp_error($terms)){
            foreach ($terms as $term) {
                $string .= $this->createTerm($term);
            }
        }

        $string .= '</ul>';

        return $string;
    }

    private function createHTML(array $cat_args, $content)
    {
        return '<div class="elr-categories">' . $this->createTitle($content) . $this->createCategoryList($cat_args) . '</div>';
    }

    private function createCatArgs($atts)
    {
        extract(shortcode_atts([
            'style' => '',
            'num' => 'all',
            'by_count' => false,
            'hierarchical' => true,
            'count' => false
        ], $atts));

        $cat_args = [
            'orderby' => 'name',
            'hierarchical' => $hierarchical,
            'hide_empty' => true
        ];

        if ($by_count) {
            $cat_args['orderby'] = 'count';
            $cat_args['order'] = 'DESC';
        }

        if ($num != 'all') {
            $cat_args['number'] = $num;
        }

        return $cat_args;
    }

    public function shortcode($atts, $content)
    {
        return $this->createHTML($this->createCatArgs($atts), $content);
    }
}