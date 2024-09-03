
<div class="jobcard max-w-[300px] p-4 bg-slate-100 rounded shadow flex-shrink basis-[300px]">
    <div class="min-h-[76px]">
        <h2 class="text-xl font-semibold">{{ $title }}</h2>
        <p class="text-sm text-gray-500">{{ $position }}</p>
    </div>
    <div class="thumb max-h-[170px] overflow-hidden	">
        @if($featured_image)
            <img class="w-full h-full object-cover mt-2" src="{{ $featured_image }}" alt="{{ $title }}">
        @else
            <img class="w-full h-full object-cover mt-2" src="{{ asset('images/default-featured-image.jpg') }}" alt="{{ $title }}">
        @endif
    </div>

    @unless(is_singular('job'))
        <a class="bg-indigo-500 hover:bg-indigo-600 my-2 block p-4 rounded-sm font-semibold text-white text-center" href="{{ $link }}">Zobacz więcej</a>
    @endunless

    <p class="text-sm py-2 text-gray-400 text-center">Dodano: {{ $date }}</p>
</div>