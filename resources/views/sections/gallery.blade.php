@php
    $title = $content['title'] ?? null;
    $subtitle = $content['subtitle'] ?? null;
    $images = $content['images'] ?? [];
    $columns = $content['columns'] ?? 3;
@endphp

@if(!empty($images))
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">

        @if($title)
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4" style="color: var(--theme-primary);">
                    {{ $title }}
                </h2>
                @if($subtitle)
                    <p class="text-lg opacity-70 max-w-2xl mx-auto">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-{{ $columns }} gap-4">
            @foreach($images as $image)
                @php
                    $imagePath = is_array($image) ? ($image['path'] ?? '') : $image;
                    $imageAlt = is_array($image) ? ($image['alt'] ?? '') : '';
                @endphp
                <div class="aspect-square overflow-hidden rounded-xl group">
                    <img src="{{ Str::startsWith($imagePath, 'http') ? $imagePath : Storage::url($imagePath) }}"
                         alt="{{ $imageAlt }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif