<?php

namespace App\PostTypes\JobList;

class JobOffers
{
    public function __construct()
    {
        add_action('init', [$this, 'register_job_offers_block']);
    }

    public function register_job_offers_block()
    {
        register_block_type('job-offers/job-category', [
            'render_callback' => [$this, 'render_job_offers_block'],
        ]);
    }

    public function render_job_offers_block($attributes)
    {
        $categories = get_terms([
            'taxonomy' => 'job_category',
            'hide_empty' => true,
        ]);
    
        ob_start();
        ?>
    
        <div id="job-offers-block" class="p-4 max-w-[1366px] mx-auto flex flex-col gap-[24px]">
            <ul class="tabs flex justify-center border-b-2 my-2 border-gray-300 gap-[8px]">
                <?php foreach ($categories as $category): ?>
                    <li data-category-id="<?php echo esc_attr($category->term_id); ?>" class="tab cursor-pointer py-2 px-4 text-gray-600 hover:text-blue-600 text-lg transition-colors font-medium mb-[-2px]"><?php echo esc_html($category->name); ?></li>
                <?php endforeach; ?>
            </ul>
            <div id="job-offers-content" class="job-offers flex flex-row flex-wrap gap-[24px] justify-center">
                <!-- AJAX -->
            </div>
            <button id="load-more" class="mt-4 py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">Load More</button>
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabs = document.querySelectorAll('#job-offers-block .tab');
                const contentDiv = document.getElementById('job-offers-content');
                const loadMoreButton = document.getElementById('load-more');
                let currentCategoryId = tabs.length > 0 ? tabs[0].getAttribute('data-category-id') : null;
                let currentPage = 1;
                const postsPerPage = 5;

                function loadPosts(categoryId, page = 1) {
                    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        },
                        body: new URLSearchParams({
                            action: 'load_job_offers',
                            category_id: categoryId,
                            page: page,
                            per_page: postsPerPage
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (page === 1) {
                            contentDiv.innerHTML = data;
                        } else {
                            contentDiv.insertAdjacentHTML('beforeend', data);
                        }
                        currentPage = page;
                    });
                }

                function activateTab(tab) {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    currentCategoryId = tab.getAttribute('data-category-id');
                    currentPage = 1;
                    loadPosts(currentCategoryId, currentPage);
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', function () {
                        activateTab(this);
                    });
                });

                loadMoreButton.addEventListener('click', function () {
                    currentPage++;
                    loadPosts(currentCategoryId, currentPage);
                });

                if (tabs.length > 0) {
                    activateTab(tabs[0]);
                }
            });
        </script>
    
        <?php
        return ob_get_clean();
    }
    
}