<?php

namespace App\PostTypes\JobList;

class RegisterCPTJob
{
    public function __construct()
    {
        add_action('init', [$this, 'registerPostType']);
        add_filter('manage_job_posts_columns', [$this, 'addCustomColumns']);
        add_action('manage_job_posts_custom_column', [$this, 'customColumnContent'], 10, 2);
        add_action('pre_get_posts', [$this, 'filterByCategory']);
    }

    public function registerPostType()
    {
        register_post_type('job', [
            'labels' => [
                'name' => __('Oferty pracy'),
                'singular_name' => __('Oferta pracy'),
                'add_new' => __('Dodaj nową ofertę pracy'),
                'add_new_item' => __('Dodaj nową ofertę pracy'),
                'edit_item' => __('Edytuj ofertę pracy'),
                'new_item' => __('Nowa oferta pracy'),
                'view_item' => __('Zobacz ofertę pracy'),
                'search_items' => __('Szukaj ofert pracy'),
                'not_found' => __('Nie znaleziono ofert pracy'),
                'not_found_in_trash' => __('Nie znaleziono ofert pracy w koszu'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'jobs'],
            'menu_position' => 5,
            'menu_icon' => 'dashicons-open-folder',
            'show_in_rest' => true,
            'taxonomies' => ['job_category'],
        ]);
    }

    public function addCustomColumns($columns)
    {
        unset($columns['date']);
        $columns['cb'] = '<input type="checkbox" />';
        $columns['title'] = __('Tytuł');
        $columns['job_position'] = __('Stanowisko');
        $columns['job_category'] = __('Kategoria');
        $columns['date'] = __('Data publikacji');
        
        return $columns;
    }


    public function customColumnContent($column, $post_id)
    {
        if ($column === 'job_category') {
            $terms = get_the_terms($post_id, 'job_category');
            if ($terms && !is_wp_error($terms)) {
                $terms_list = array_map(function($term) {
                    return sprintf(
                        '<a href="%s">%s</a>',
                        esc_url(add_query_arg('job_category', $term->slug, admin_url('edit.php?post_type=job'))),
                        esc_html($term->name)
                    );
                }, $terms);
                echo implode(', ', $terms_list);
            } else {
                echo __('Brak kategorii');
            }
        }

        if ($column === 'job_position') {
            $post_content = get_post_field('post_content', $post_id);
            $blocks = parse_blocks($post_content);

            foreach ($blocks as $block) {
                if ($block['blockName'] === 'job-offers/job-position') {
                    $jobTitle = $block['attrs']['jobTitle'] ?? 'Brak stanowiska';
                    echo esc_html($jobTitle);
                    break;
                }
            }
        }
    }

    public function filterByCategory($query)
    {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'job') {
            return;
        }

        if ($category = filter_input(INPUT_GET, 'job_category')) {
            $query->set('tax_query', [
                [
                    'taxonomy' => 'job_category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ],
            ]);
        }
    }
}