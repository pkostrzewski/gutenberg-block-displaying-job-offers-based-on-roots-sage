@extends('layouts.app')

@section('content')
    @php
        $content = get_the_content();
        $blocks = parse_blocks($content);
        $job_position = '';

        foreach ($blocks as $block) {
            if ($block['blockName'] === 'job-offers/job-position') {
                $job_position = isset($block['attrs']['jobTitle']) ? $block['attrs']['jobTitle'] : '';
                break;
            }
        }

        // Date formatting
        $post_date = get_the_date('Y-m-d H:i:s'); // Pobieramy datę w formacie Y-m-d H:i:s
        $date_formatted = human_time_diff(strtotime($post_date), current_time('timestamp')) . ' temu';
    @endphp

    <div class="wrapper my-24 mx-auto px-[3vw] max-w-[1366px]">
        @include('components.job-card', [
            'title' => get_the_title(),
            'position' => $job_position,
            'featured_image' => get_the_post_thumbnail_url(),
            'link' => get_permalink(),
            'date' => $date_formatted
        ])
    </div>
@endsection
