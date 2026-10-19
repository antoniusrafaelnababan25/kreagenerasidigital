@extends('layouts.app')

@section('content')

    @php
        // Section yang di-support
        $supportedSections = [
            'hero',
            'services',
            'portfolio',
            'testimonials',
            'cta',
            'faq',
            'stats',
            'gallery',
        ];
    @endphp

    @foreach($sections as $type => $section)
        @if(in_array($type, $supportedSections))
            @include("sections.{$type}", [
                'content' => $section['content'] ?? [],
                'section' => $section,
                // Data pendukung
                'portfolios' => $portfolios ?? collect(),
                'testimonials' => $testimonials ?? collect(),
                'latestPosts' => $latestPosts ?? collect(),
                'faqs' => $faqs ?? collect(),
            ])
        @endif
    @endforeach

@endsection