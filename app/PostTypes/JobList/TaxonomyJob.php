<?php

namespace App\PostTypes\JobList;

class TaxonomyJob
{
    public function __construct()
    {
        add_action('init', [$this, 'registerTaxonomy']);
    }

    public function registerTaxonomy()
    {
        register_taxonomy('job_category', ['job'], [
            'labels' => [
                'name' => __('Kategorie ofert pracy'),
                'singular_name' => __('Kategoria oferty pracy'),
                'search_items' => __('Szukaj kategorii ofert pracy'),
                'all_items' => __('Wszystkie kategorie ofert pracy'),
                'edit_item' => __('Edytuj kategorię oferty pracy'),
                'update_item' => __('Zaktualizuj kategorię oferty pracy'),
                'add_new_item' => __('Dodaj nową kategorię oferty pracy'),
                'new_item_name' => __('Nazwa nowej kategorii oferty pracy'),
                'menu_name' => __('Kategorie ofert pracy'),
            ],   
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true, 
            'rest_base' => 'job_category',
            'rewrite' => ['slug' => 'job-category'],
        ]);
    }
}