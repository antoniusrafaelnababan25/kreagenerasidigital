@php
    $title = $content['title'] ?? 'Apa Kata Klien Kami';
    $subtitle = $content['subtitle'] ?? '';
    $limit = $content['limit'] ?? 3;
    $backgroundType = $content['background_type'] ?? 'light';
    $isDark = $backgroundType === 'dark';
@endphp

@if(isset($testimonials) && $testimonials->count() > 0)
<section class="py-20 lg:py-28 relative overflow-hidden {{ $isDark ? 'bg-gray-900' : '' }}"
         style="{{ !$isDark ? 'background-color: var(--theme-background);' : '' }}">

    {{-- Decorative Blur --}}
    <div class="absolute top-20 -right-32 w-96 h-96 rounded-full blur-3xl opacity-10 pointer-events-none"
         style="background: var(--theme-primary);"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full blur-3xl opacity-10 pointer-events-none"
         style="background: var(--theme-secondary);"></div>

    <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16 max-w-3xl mx-auto">
            @if($title)
                <h2 class="text-3xl lg:text-4xl font-bold mb-4 {{ $isDark ? 'text-white' : '' }}"
                    style="{{ !$isDark ? 'color: var(--theme-primary);' : '' }}">
                    {{ $title }}
                </h2>
            @endif
            @if($subtitle)
                <p class="text-lg max-w-2xl mx-auto {{ $isDark ? 'text-white/70' : 'opacity-70' }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- Grid — TANPA CONTAINER --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">

            @foreach($testimonials->take($limit) as $testimonial)
                <div class="group relative flex flex-col">

                    {{-- Quote Mark --}}
                    <div class="text-6xl font-serif leading-none mb-2 opacity-20 transition-opacity duration-300 group-hover:opacity-40"
                         style="color: var(--theme-primary);">
                        "
                    </div>

                    {{-- Rating --}}
                    <div class="flex gap-1 mb-5">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5" style="color: {{ $i < $testimonial->rating ? 'var(--theme-accent)' : '#D1D5DB' }};"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    {{-- Content --}}
                    <p class="text-base lg:text-lg leading-relaxed mb-8 italic flex-grow {{ $isDark ? 'text-white/85' : 'text-gray-700' }}">
                        "{{ $testimonial->content }}"
                    </p>

                    {{-- Author --}}
                    <div class="flex items-center gap-4 pt-6 border-t {{ $isDark ? 'border-white/10' : 'border-gray-200' }}">
                        @if($testimonial->avatar)
                            <img src="{{ Storage::url($testimonial->avatar) }}"
                                 alt="{{ $testimonial->name }}"
                                 class="w-14 h-14 rounded-full object-cover ring-2"
                                 style="--tw-ring-color: var(--theme-primary);">
                        @else
                            <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg text-white flex-shrink-0"
                                 style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-base lg:text-lg {{ $isDark ? 'text-white' : 'text-gray-900' }}">
                                {{ $testimonial->name }}
                            </div>
                            @if($testimonial->position || $testimonial->company)
                                <div class="text-sm {{ $isDark ? 'text-white/60' : 'text-gray-500' }}">
                                    {{ $testimonial->position }}{{ $testimonial->position && $testimonial->company ? ', ' : '' }}{{ $testimonial->company }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Bottom Accent Line --}}
                    <div class="mt-6 h-0.5 w-0 group-hover:w-full transition-all duration-700 rounded-full"
                         style="background: linear-gradient(90deg, var(--theme-primary), var(--theme-secondary));"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif