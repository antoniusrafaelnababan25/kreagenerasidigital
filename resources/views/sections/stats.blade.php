@php
    $title = $content['title'] ?? null;
    $subtitle = $content['subtitle'] ?? null;
    $items = $content['items'] ?? [];
@endphp

@if(!empty($items))
<section class="py-20" style="background-color: var(--theme-background);">
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

        <div class="grid grid-cols-2 lg:grid-cols-{{ count($items) }} gap-6">
            @foreach($items as $item)
                <div class="text-center p-6">
                    <div class="text-4xl lg:text-5xl font-bold mb-2"
                         style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        {{ $item['value'] ?? '0' }}
                    </div>
                    <div class="text-sm lg:text-base text-gray-600 font-medium">
                        {{ $item['label'] ?? '' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif