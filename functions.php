<?php

use Framework\Helpers\Admin;
use Framework\Helpers\Setup;
use Framework\Helpers\Security;
use Framework\ThemeOptions\ThemeOptions;
use Framework\CustomPosts\CptBuilder;

$elrError = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('ELR &rsaquo; Error', 'elr');
    $footer = '';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

// Ensure dependencies are loaded

if (!file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    $elrError(
        __('You must run <code>composer install</code> from the ELR directory.', 'elr'),
        __('Autoloader not found.', 'elr')
    );
}
require_once $composer;

$timber = new \Timber\Timber();

// Define Constants

define('THEMEROOT', get_stylesheet_directory_uri());
define('IMAGES', THEMEROOT . '/assets/images/compressed');
define('SCRIPTS', THEMEROOT . '/assets/js');
define('STYLES', THEMEROOT . '/assets/css');

// Set Up Content Width Value

if (! isset($content_width)) {
    $content_width = 1300;
}

// Make theme available for translation
$lang_dir = THEMEROOT . '/languages';
load_theme_textdomain('elr', $lang_dir);

update_option('uploads_use_yearmonth_folders', 0);

if (! class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' .
        esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });
    return;
}

// if (! class_exists('Admin') || ! class_exists('Setup') || ! class_exists('Security') || ! class_exists('ThemeOptions') || ! class_exists('CptBuilder')) {
//     $elrError(
//         __('You must run <code>composer install</code> from the ELR directory.', 'elr'),
//         __('Autoloaded classes not found', 'elr')
//     );
// }

$timber::$dirname = ['views'];

class Site extends \TimberSite
{
    private $fonts;
    public function __construct()
    {
        $fonts = 'https://fonts.googleapis.com/css?family='.
            'Roboto:700,500,400,300, 200|Raleway:300italic,400,300|Roboto+Slab:300,400,500';
        $admin = new Admin;
        $setup = new Setup;
        $security = new Security;
        $builder = new CptBuilder;

        $setup->registerMenus(['main-nav', 'footer-nav', 'social-nav', 'front-nav']);
        // $setup->registerSidebars(['sidebar']);

        $builder->createPostType([
            'singular_name' => 'project',
            'taxonomies' => [],
            'custom_taxonomies' => [
                [
                    'singular_name' => 'portfolio'
                ],
                [
                    'singular_name' => 'technology',
                    'plural_name' => 'technologies',
                    'hierarchical' => false
                ],
                [
                    'singular_name' => 'tool',
                    'hierarchical' => false
                ],
                [
                    'singular_name' => 'project_type',
                    'hierarchical' => false
                ]
            ],
            'fields' => [
                [
                    'id' => '_project_date',
                    'label' => 'Date',
                ],
                [
                    'id' => '_project_client',
                    'label' => 'Client',
                ],
                [
                    'id' => '_project_url',
                    'label' => 'URL',
                    'input_type' => 'url'
                ],
                [
                    'id' => '_project_location',
                    'label' => 'Client Location',
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'education',
            'plural_name' => 'education',
            'taxonomies' => [],
            'custom_taxonomies' => [
                [
                    'singular_name' => 'education_type'
                ]
            ],
            'fields' => [
                [
                    'id' => '_education_start_date',
                    'label' => 'Start Date',
                ],
                [
                    'id' => '_education_end_date',
                    'label' => 'End Date',
                ],
                [
                    'id' => '_education_institution',
                    'label' => 'Institution',
                ],
                [
                    'id' => '_education_url',
                    'label' => 'Institution URL',
                    'input_type' => 'url'
                ],
                [
                    'id' => '_education_location',
                    'label' => 'Institution Location',
                ],
                [
                    'id' => '_education_degree',
                    'label' => 'Degree',
                ],
                [
                    'id' => '_education_concentration',
                    'label' => 'Concentration',
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'experience',
            'plural_name' => 'experience',
            'taxonomies' => [],
            'custom_taxonomies' => [
                [
                    'singular_name' => 'experience_type'
                ]
            ],
            'fields' => [
                [
                    'id' => '_experience_start_date',
                    'label' => 'Start Date'
                ],
                [
                    'id' => '_experience_end_date',
                    'label' => 'End Date'
                ],
                [
                    'id' => '_experience_business_name',
                    'label' => 'Business Name'
                ],
                [
                    'id' => '_experience_url',
                    'label' => 'Business URL',
                    'input_type' => 'url'
                ],
                [
                    'id' => '_experience_location',
                    'label' => 'Location'
                ],
                [
                    'id' => '_experience_role',
                    'label' => 'Role'
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'reading',
            'plural_name' => 'reading',
            'taxonomies' => [],
            'custom_taxonomies' => [
                [
                    'singular_name' => 'reading_type'
                ]
            ],
            'fields' => [
                [
                    'id' => '_reading_title',
                    'label' => 'Title'
                ],
                [
                    'id' => '_reading_author',
                    'label' => 'Author'
                ],
                [
                    'id' => '_reading_url',
                    'label' => 'URL',
                    'input_type' => 'url'
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'recommendation',
            'taxonomies' => [],
            'fields' => [
                [
                    'id' => '_recommendation_company_name',
                    'label' => 'Company'
                ],
                [
                    'id' => '_recommendation_role',
                    'label' => 'Role'
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'skill',
            'taxonomies' => [],
            'custom_taxonomies' => [
                [
                    'singular_name' => 'skill_type'
                ]
            ],
            'fields' => [
                [
                    'id' => '_skill_length',
                    'label' => 'Length of Experience (in years)'
                ],
                [
                    'id' => '_skill_example_project',
                    'label' => 'Link to example project'
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'tutorial',
            'taxonomies' => [],
            'custom_taxonomies' => [
                [
                    'singular_name' => 'lesson'
                ],
                [
                    'singular_name' => 'difficulty',
                    'singular_name' => 'difficulties'
                ]
            ]
        ]);

        $builder->createPostType([
            'singular_name' => 'video',
            'taxonomies' => [],
            'fields' => [
                [
                    'id' => 'video_url',
                    'label' => 'Video URL'
                ]
            ]
        ]);

        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('menus');
        add_filter('timber_context', [$this, 'addToContext']);
        add_filter('get_twig', [$this, 'addToTwig']);
        add_filter('manage_posts_columns', [$admin, 'thumbnailColumn'], 5);
        add_filter('user_can_richedit', [$this, 'disableVisualEditor'], 50);
        add_filter('the_generator', [$security, 'removeWpVersion']);
        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);
        add_action('wp_print_scripts', [$this, 'themeQueueJs']);
        add_action('after_setup_theme', [$setup, 'themeSlugSetup']);
        add_action('manage_posts_custom_column', [$admin, 'thumbnailCustomColumn'], 5, 2);
        add_action('dashboard_glance_items', [$admin, 'dashboardCpts']);
        add_action('admin_menu', [$this, 'themeMenu']);
        add_action('widgets_init', function () use ($setup) {
            $setup->registerSidebars(['sidebar']);
        });
        parent::__construct();
    }

    public function addToContext($context)
    {
        $context['main_nav'] = new \TimberMenu('main-nav');
        $context['social_nav'] = new \TimberMenu('social-nav');
        $context['front_nav'] = new \TimberMenu('front-nav');
        $context['footer_nav'] = new \TimberMenu('footer-nav');
        $context['site'] = $this;
        $context['sidebar'] = Timber::get_sidebar('sidebar.php');
        return $context;
    }

    public function addToTwig($twig)
    {
        /* this is where you can add your own fuctions to twig */
        $twig->addExtension(new \Twig_Extension_StringLoader());
        $twig->addFilter(new Twig_SimpleFilter('human_diff', function($string) {
            return human_time_diff(strtotime('01/01/' . $string), current_time('timestamp'));
        }));

        return $twig;
    }

    public function loadScripts()
    {
        wp_register_script('main', SCRIPTS . '/main.min.js', ['jquery'], null, true);
        wp_register_script('font-awesome', 'https://use.fontawesome.com/185c4dbad0.js', [], null);
        wp_register_style('style', STYLES . '/custom.css', [], null, 'screen');
        wp_register_style('fonts', $this->fonts, [], null, 'screen');

        wp_enqueue_script('main');
        wp_enqueue_script('font-awesome');
        wp_enqueue_style('fonts');
        wp_enqueue_style('style');
    }

    public function themeQueueJs()
    {
        if ((!is_admin()) && is_single() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    public function disableVisualEditor()
    {
        # add logic here if you want to permit it selectively
        return false;
    }

    public function breadcrumbs()
    {
        if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<p id="breadcrumbs" class="breadcrumbs">', '</p>');
        }

        return;
    }

    public function email($email)
    {
        if ($email) {
            $html = '<a href="mailto:';
            $html .= antispambot($email);
            $html .= '">';
            $html .= antispambot($email);
            $html .= '</a>';

            return $html;
        }

        return;
    }

    public function setNumberOfCpts($query, $num = -1, $post_types = [], $taxonomies = [])
    {
        if ($query->is_main_query()) {
            foreach ($post_types as $post_type) {
                if (is_post_type_archive($post_type, $num)) {
                    $query->set('posts_per_page', $num);
                }
            }

            foreach ($taxonomies as $tax) {
                if (is_tax($tax)) {
                    $query->set('posts_per_page', $num);
                }
            }

            return $query;
        }
    }

    public function themeMenu()
    {
        $settings = new ThemeOptions;
        $options = [
            'title' => 'ELR Portfolio Settings',
            'subpages' => [
                [
                    'id' => 'general_options',
                    'title' => 'General Options',
                    'description' => 'These are some general options for your theme',
                    'fields' => [
                        [
                            'id' => 'name',
                            'instructions' => 'Your first name and last name'
                        ],
                        [
                            'id' => 'email',
                            'input_type' => 'email',
                            'instructions' => 'your preferred email for recruiters and HR managers'
                        ],
                        [
                            'id' => 'phone',
                            'input_type' => 'tel',
                            'instructions' => 'your preferred phone for recruiters and HR managers'
                        ],
                        [
                            'id' => 'location',
                            'input_type' => 'text',
                            'instructions' => 'your current location'
                        ],
                        [
                            'id' => 'role',
                            'input_type' => 'text',
                            'instructions' => 'your current role or industry eg. Web Developer'
                        ],
                        [
                            'id' => 'summary',
                            'input_type' => 'textarea',
                            'instructions' => 'your summary for resume and home page'
                        ],
                        [
                            'id' => 'contact_method',
                            'label' => 'Preferred Contact Method',
                            'input_type' => 'select',
                            'options' => [
                                'phone',
                                'email',
                                'linkedin'
                            ]
                        ]
                    ]
                ],
                [
                    'id' => 'social_options',
                    'title' => 'Social Options',
                    'description' => 'These are some social options for your theme',
                    'fields' => [
                        [
                            'id' => 'facebook',
                            'input_type' => 'url',
                            'placeholder' => 'http://facebook.com'
                        ],
                        [
                            'id' => 'twitter',
                            'input_type' => 'url',
                            'placeholder' => 'http://twitter.com'
                        ],
                        [
                            'id' => 'github',
                            'input_type' => 'url',
                            'placeholder' => 'http://github.com'
                        ],
                        [
                            'id' => 'linkedin',
                            'input_type' => 'url',
                            'placeholder' => 'http://linkedin.com'
                        ],
                        [
                            'id' => 'instagram',
                            'input_type' => 'url',
                            'placeholder' => 'http://instagram.com'
                        ]
                    ]
                ]
            ]
        ];

        $settings->initializeThemeSettings($options);
    }
}

new Site();
