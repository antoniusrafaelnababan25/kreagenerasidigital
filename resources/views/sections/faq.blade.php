@php
    $title = $content['title'] ?? 'Pertanyaan Umum';
    $subtitle = $content['subtitle'] ?? '';
    $limit = $content['limit'] ?? 10;
@endphp

@if(isset($faqs) && $faqs->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16">
            @if($title)
                <h2 class="text-3xl lg:text-4xl font-bold mb-4" style="color: var(--theme-primary);">
                    {{ $title }}
                </h2>
            @endif
            @if($subtitle)
                <p class="text-lg opacity-70">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- FAQ Accordion --}}
        <div class="space-y-4">
            @foreach($faqs->take($limit) as $index => $faq)
                <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                     class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">

                    <button @click="open = !open"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900 pr-4">
                            {{ $faq->question }}
                        </span>
                        <svg class="w-5 h-5 flex-shrink-0 transition-transform"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--theme-primary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="px-6 pb-6 text-gray-600 leading-relaxed border-t border-gray-100"
                         style="display: none;">
                        <div class="pt-4">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif