<?php

namespace App\PostTypes\JobList;

use WP_REST_Request;
use WP_Query;

class Endpoints {

    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('job-offers/v1', '/jobs', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_jobs'],
            'permission_callback' => '__return_true',
        ));
    }

    public function get_jobs(WP_REST_Request $request) {
        $category = $request->get_param('category');
        $page = $request->get_param('page') ?: 1;
        $posts_per_page = 5;

        $args = array(
            'post_type' => 'job',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
            'tax_query' => array(
                array(
                    'taxonomy' => 'job_category',
                    'field' => 'id',
                    'terms' => $category,
                    'operator' => 'IN',
                ),
            ),
        );

        $query = new WP_Query($args);

        $posts = array();
        foreach ($query->posts as $post) {
            $posts[] = array(
                'id' => $post->ID,
                'title' => get_the_title($post),
                'position' => get_post_meta($post->ID, 'position', true),
                'featured_image' => get_the_post_thumbnail_url($post),
                'link' => get_permalink($post),
                'date' => human_time_diff(get_the_time('U', $post)) . ' ' . __('ago', 'text-domain'),
            );
        }

        return array(
            'posts' => $posts,
            'hasMore' => $query->max_num_pages > $page,
        );
    }
}

$endpoints = new Endpoints();