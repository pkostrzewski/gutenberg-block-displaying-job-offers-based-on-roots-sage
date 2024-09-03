<?php

namespace App\PostTypes\JobList;

class AjaxSupport
{
    public function __construct()
    {
        add_action('wp_ajax_load_job_offers', [$this, 'load_job_offers_callback']);
        add_action('wp_ajax_nopriv_load_job_offers', [$this, 'load_job_offers_callback']);
    }

    public function load_job_offers_callback()
    {
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : '';
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $posts_per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 5;

        $args = [
            'post_type' => 'job',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
        ];

        if ($category_id) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'job_category',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ]
            ];
        }

        $query = new \WP_Query($args);
        ob_start();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $content = get_the_content();
                $blocks = parse_blocks($content);
                $job_position = '';
                foreach ($blocks as $block) {
                    if ($block['blockName'] === 'job-offers/job-position') {
                        $job_position = isset($block['attrs']['jobTitle']) ? $block['attrs']['jobTitle'] : '';
                        break;
                    }
                }
                $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                $date_formatted = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('temu', 'text-domain');
                
                echo \Roots\view('components.job-card', [
                    'title' => get_the_title(),
                    'position' => $job_position,
                    'featured_image' => $featured_image,
                    'link' => get_permalink(),
                    'date' => $date_formatted,
                ])->render();
            }
        } else {
            echo '<p>' . __('No job offers found.', 'text-domain') . '</p>';
        }

        wp_reset_postdata();

        echo ob_get_clean();

        wp_die();
    }
}